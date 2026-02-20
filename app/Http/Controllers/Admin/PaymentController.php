<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('invoice.student.user', 'invoice.fee')->latest()->paginate(10);

        return view('admin.payments.index', compact('payments'));
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

    public function store(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,bank_transfer,ccp',
            'reference' => 'nullable|string|max:255',
            'payment_date' => 'required|date',
        ]);

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

    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,bank_transfer,ccp',
            'reference' => 'nullable|string|max:255',
            'payment_date' => 'required|date',
        ]);

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

            DB::commit();

            return redirect()->route('admin.payments.show', $payment)
                ->with('success', 'Payment updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update payment: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Payment $payment)
    {
        //
    }
}
