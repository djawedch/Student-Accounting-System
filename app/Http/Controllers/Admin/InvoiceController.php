<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Invoice\{StoreInvoiceRequest, UpdateInvoiceRequest};
use App\Models\{AuditLog, Student, Fee, Invoice};
use Illuminate\Support\Facades\Auth;
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

    public function store(StoreInvoiceRequest $request)
    {
        $request->validated();

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

                    AuditLog::create([
                        'user_id'    => Auth::id(),
                        'event_type' => 'create',
                        'model_type' => 'Invoice',
                        'model_id'   => $invoice->id,
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
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

    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        $request->validated();

        $invoice->update($request->only(['status', 'issued_date', 'due_date']));

        AuditLog::create([
            'user_id'    => Auth::id(),
            'event_type' => 'update',
            'model_type' => 'Invoice',
            'model_id'   => $invoice->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.invoices.show', $invoice)
            ->with('success', 'Invoice updated successfully.');
    }
}
