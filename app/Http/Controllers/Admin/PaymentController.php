<?php

namespace App\Http\Controllers\Admin;

use App\Filters\PaymentFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Payment\{StorePaymentRequest, UpdatePaymentRequest};
use App\Models\{Invoice, Payment, AuditLog};
use App\Scopes\PaymentRoleScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Payment::class);

        $user = Auth::user();
        $baseQuery = Payment::with('invoice.student.user', 'invoice.fee');

        $payments = (new PaymentFilter($request))
            ->apply((new PaymentRoleScope)->apply($baseQuery, $user))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $paymentMethods = Payment::distinct()->pluck('payment_method');

        return view('admin.payments.index', compact('payments', 'paymentMethods'));
    }

    public function create()
    {
        $this->authorize('create', Payment::class);

        $user = Auth::user();
        $invoices = Invoice::with('student.user', 'fee')
            ->whereHas('student.user', function ($q) use ($user) {
                if ($user->role === 'university_admin') {
                    $q->where('university_id', $user->university_id);
                } elseif (in_array($user->role, ['department_admin', 'staff_admin'])) {
                    $q->where('department_id', $user->department_id);
                }
            })
            ->get()
            ->filter(fn($invoice) => $invoice->remaining_amount > 0);

        return view('admin.payments.create', compact('invoices'));
    }

    public function store(StorePaymentRequest $request)
    {
        $this->authorize('create', Payment::class);

        $invoice = Invoice::with('student.user', 'fee')->findOrFail($request->invoice_id);

        $this->authorize('view', $invoice);

        DB::beginTransaction();

        try {
            $payment = Payment::create($request->validated());
            $invoice = $payment->invoice;
            $totalPaid = $invoice->total_paid;
            $invoiceAmount = $invoice->fee->amount;

            if ($totalPaid >= $invoiceAmount) {
                $invoice->status = 'paid';
            } elseif ($totalPaid > 0) {
                $invoice->status = 'partially_paid';
            }

            $invoice->save();

            AuditLog::create([
                'user_id' => Auth::id(),
                'event_type' => 'create',
                'model_type' => 'Payment',
                'model_id' => $payment->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();

            return redirect()->route('admin.payments.show', $payment)
                ->with('success', 'Payment recorded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to record payment: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Payment $payment)
    {
        $payment->load('invoice.student.user', 'invoice.fee');

        $this->authorize('view', $payment);

        return view('admin.payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        $payment->load('invoice.student.user', 'invoice.fee');

        $this->authorize('update', $payment);

        return view('admin.payments.edit', compact('payment'));
    }

    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        $payment->load('invoice.student.user');
        
        $this->authorize('update', $payment);

        DB::beginTransaction();

        try {
            $payment->update($request->validated());
            $invoice = Invoice::with('fee')->findOrFail($payment->invoice_id);
            $totalPaid = $invoice->total_paid;
            $invoiceAmount = $invoice->fee->amount;

            if ($totalPaid >= $invoiceAmount) {
                $invoice->status = 'paid';
            } elseif ($totalPaid > 0) {
                $invoice->status = 'partially_paid';
            } else {
                $invoice->status = 'unpaid';
            }

            $invoice->save();

            AuditLog::create([
                'user_id' => Auth::id(),
                'event_type' => 'update',
                'model_type' => 'Payment',
                'model_id' => $payment->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();

            return redirect()->route('admin.payments.show', $payment)
                ->with('success', 'Payment updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update payment: ' . $e->getMessage())
                ->withInput();
        }
    }
}
