<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\{User, Invoice};
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function index()
    {
        $user = User::find(Auth::id())->load('student.invoices.fee');

        if (!$user->student) {
            abort(404, 'Student profile not found.');
        }

        $invoices = $user->student->invoices->sortByDesc('created_at');

        return view('student.invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        $user = User::find(Auth::id())->load('student');

        if (!$user->student || $invoice->student_id !== $user->student->id) {
            abort(403);
        }

        $invoice->load('fee.department', 'student.user');

        return view('student.invoices.show', compact('invoice'));
    }
}
