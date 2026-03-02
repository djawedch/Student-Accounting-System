<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;
        if (!$student) {
            abort(404, 'Student profile not found.');
        }
        $payments = $student->invoices()
            ->with('payments')
            ->get()
            ->pluck('payments')
            ->flatten()
            ->sortByDesc('payment_date');
        return view('student.payments.index', compact('payments'));
    }
}
