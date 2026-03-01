<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Payment\{StorePaymentRequest, UpdatePaymentRequest};
use App\Models\{Invoice, Payment, AuditLog};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with('invoice.student.user', 'invoice.fee');

        if ($request->filled('student')) {
            $student = $request->student;
            $query->whereHas('invoice.student.user', function ($q) use ($student) {
                $q->where('first_name', 'like', "%{$student}%")
                    ->orWhere('last_name', 'like', "%{$student}%");
            });
        }

        if ($request->filled('invoice_id')) {
            $query->where('invoice_id', $request->invoice_id);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('amount_min')) {
            $query->where('amount', '>=', $request->amount_min);
        }
        if ($request->filled('amount_max')) {
            $query->where('amount', '<=', $request->amount_max);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }

        if ($request->filled('reference')) {
            $query->where('reference', 'like', '%' . $request->reference . '%');
        }

        $payments = $query->latest()->paginate(10)->withQueryString();

        $paymentMethods = Payment::distinct()->pluck('payment_method');

        return view('admin.payments.index', compact('payments', 'paymentMethods'));
    }

    public function create()
    {
        $invoices = Invoice::with(['student.user', 'fee'])
            ->get()
            ->filter(function ($invoice) {
                return $invoice->remaining_amount > 0;
            });

        return view('admin.payments.create', compact('invoices'));
    }

    public function store(StorePaymentRequest $request)
    {
        $request->validated();

        DB::beginTransaction();

        try {
            $payment = Payment::create([
                'invoice_id' => $request->invoice_id,
                'payment_method' => $request->payment_method,
                'amount' => $request->amount,
                'reference' => $request->reference,
                'payment_date' => $request->payment_date,
            ]);

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

        return view('admin.payments.show', compact('payment'));
    }


    public function edit(Payment $payment)
    {
        $payment->load('invoice.student.user', 'invoice.fee');

        return view('admin.payments.edit', compact('payment'));
    }

    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        $request->validated();

        DB::beginTransaction();

        try {
            $payment->update([
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'reference' => $request->reference,
                'payment_date' => $request->payment_date,
            ]);

            $invoice = $payment->invoice;
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
