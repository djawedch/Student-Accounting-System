<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\Invoice;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('student.user', 'fee.department')->latest()->paginate(10);

        return view('admin.invoices.index', compact('invoices'));
    }

    public function create()
    {
        $students = Student::with('user')->orderBy('id')->get();
        $fees = Fee::with('department')->orderBy('name')->get();

        return view('admin.invoices.create', compact('students', 'fees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'exists:students,id',
            'fee_ids' => 'required|array|min:1',
            'fee_ids.*' => 'exists:fees,id',
            'issued_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issued_date',
        ]);

        $studentIds = $request->student_ids;
        $feeIds = $request->fee_ids;
        $issuedDate = $request->issued_date;
        $dueDate = $request->due_date;
        $status = 'unpaid';

        $createdCount = 0;
        $skippedCount = 0;

        DB::beginTransaction();

        try {
            foreach ($studentIds as $studentId) {
                foreach ($feeIds as $feeId) {
                    $exists = Invoice::where('student_id', $studentId)->where('fee_id', $feeId)->exists();
                    if ($exists) {
                        $skippedCount++;
                        continue;
                    }

                    $invoice = Invoice::create([
                        'student_id' => $studentId,
                        'fee_id' => $feeId,
                        'status' => $status,
                        'issued_date' => $issuedDate,
                        'due_date' => $dueDate,
                    ]);

                    $createdCount++;
                }
            }

            DB::commit();

            $message = "{$createdCount} invoice(s) generated successfully.";
            if ($skippedCount > 0) {
                $message .= " {$skippedCount} duplicate(s) skipped.";
            }

            return redirect()->route('admin.invoices.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to generate invoices: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('student.user', 'fee.department');

        return view('admin.invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $invoice->load('student.user', 'fee');

        return view('admin.invoices.edit', compact('invoice'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'status' => 'required|in:unpaid,partially_paid,paid,overdue',
            'issued_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issued_date',
        ]);

        $oldData = $invoice->only(['status', 'issued_date', 'due_date']);

        $invoice->update($request->only(['status', 'issued_date', 'due_date']));

        return redirect()->route('admin.invoices.show', $invoice)
            ->with('success', 'Invoice updated successfully.');
    }
}
