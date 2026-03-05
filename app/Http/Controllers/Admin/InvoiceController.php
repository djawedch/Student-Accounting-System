<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Invoice\{StoreInvoiceRequest, UpdateInvoiceRequest};
use App\Models\{AuditLog, Student, Fee, Invoice};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Invoice::class);

        $user = Auth::user();
        $query = Invoice::with('student.user', 'fee.department');

        if ($user->role === 'university_admin') {
            $query->whereHas('student.user', fn($q) => $q->where('university_id', $user->university_id));
        } elseif (in_array($user->role, ['department_admin', 'staff_admin'])) {
            $query->whereHas('student.user', fn($q) => $q->where('department_id', $user->department_id));
        }

        if ($request->filled('student')) {
            $student = $request->student;
            $query->whereHas('student.user', function ($q) use ($student) {
                $q->where('first_name', 'like', "%{$student}%")
                    ->orWhere('last_name', 'like', "%{$student}%");
            });
        }

        if ($request->filled('fee')) {
            $query->whereHas('fee', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->fee . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('issued_from')) {
            $query->whereDate('issued_date', '>=', $request->issued_from);
        }
        if ($request->filled('issued_to')) {
            $query->whereDate('issued_date', '<=', $request->issued_to);
        }

        if ($request->filled('due_from')) {
            $query->whereDate('due_date', '>=', $request->due_from);
        }
        if ($request->filled('due_to')) {
            $query->whereDate('due_date', '<=', $request->due_to);
        }

        if ($request->filled('amount_min')) {
            $query->whereHas('fee', function ($q) use ($request) {
                $q->where('amount', '>=', $request->amount_min);
            });
        }
        if ($request->filled('amount_max')) {
            $query->whereHas('fee', function ($q) use ($request) {
                $q->where('amount', '<=', $request->amount_max);
            });
        }

        $invoices = $query->latest()->paginate(10)->withQueryString();

        $statuses = ['unpaid', 'paid', 'overdue', 'cancelled'];

        return view('admin.invoices.index', compact('invoices', 'statuses'));
    }

    public function create()
    {
        $this->authorize('create', Invoice::class);

        $user = Auth::user();

        $students = match ($user->role) {
            'super_admin' => Student::with('user')->get(),
            'university_admin' => Student::whereHas('user', fn($q) => $q->where('university_id', $user->university_id))->with('user')->get(),
            'department_admin', 'staff_admin' => Student::whereHas('user', fn($q) => $q->where('department_id', $user->department_id))->with('user')->get(),
            default => collect()
        };

        $fees = match ($user->role) {
            'super_admin' => Fee::with('department')->orderBy('name')->get(),
            'university_admin' => Fee::whereHas('department', fn($q) => $q->where('university_id', $user->university_id))->with('department')->orderBy('name')->get(),
            'department_admin', 'staff_admin' => Fee::where('department_id', $user->department_id)->with('department')->orderBy('name')->get(),
            default => collect()
        };

        return view('admin.invoices.create', compact('students', 'fees'));
    }

    public function store(StoreInvoiceRequest $request)
    {
        $this->authorize('create', Invoice::class);

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
                        'user_id' => Auth::id(),
                        'event_type' => 'create',
                        'model_type' => 'Invoice',
                        'model_id' => $invoice->id,
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
        $this->authorize('view', $invoice);

        $invoice->load('student.user', 'fee.department');

        return view('admin.invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $this->authorize('update', $invoice);

        $invoice->load('student.user', 'fee');

        return view('admin.invoices.edit', compact('invoice'));
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        $this->authorize('update', $invoice);

        $request->validated();

        $invoice->update($request->only(['status', 'issued_date', 'due_date']));

        AuditLog::create([
            'user_id' => Auth::id(),
            'event_type' => 'update',
            'model_type' => 'Invoice',
            'model_id' => $invoice->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.invoices.show', $invoice)
            ->with('success', 'Invoice updated successfully.');
    }
}
