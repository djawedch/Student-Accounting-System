<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;
        if (!$student) {
            abort(404, 'Student profile not found.');
        }
        $invoices = $student->invoices()->with('fee')->latest()->get();
        return view('student.invoices.index', compact('invoices'));
    }
}
