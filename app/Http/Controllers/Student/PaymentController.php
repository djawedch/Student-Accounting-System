<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\{User, Payment};
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index()
    {
        $user = User::find(Auth::id())->load('student.invoices.payments');

        if (!$user->student) {
            abort(404, 'Student profile not found.');
        }

        $payments = $user->student->invoices
            ->pluck('payments')
            ->flatten()
            ->sortByDesc('payment_date');

        return view('student.payments.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        $user = User::find(Auth::id())->load('student.invoices');

        if (!$user->student) {
            abort(404, 'Student profile not found.');
        }

        $studentInvoiceIds = $user->student->invoices->pluck('id');

        if (!$studentInvoiceIds->contains($payment->invoice_id)) {
            abort(403);
        }

        $payment->load('invoice.fee', 'invoice.student.user');

        return view('student.payments.show', compact('payment'));
    }
}
