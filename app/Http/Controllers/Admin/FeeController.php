<?php

namespace App\Http\Controllers\Admin;

use App\Filters\FeeFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Fee\{StoreFeeRequest, UpdateFeeRequest};
use App\Models\{Department, Fee, AuditLog};
use App\Scopes\FeeRoleScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class FeeController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Fee::class);

        $user = Auth::user();
        $baseQuery = Fee::with('department.university')->latest();

        $fees = (new FeeFilter($request))
            ->apply((new FeeRoleScope)->apply($baseQuery, $user))
            ->paginate(10)
            ->withQueryString();

        return view('admin.fees.index', compact('fees'));
    }

    public function create()
    {
        $this->authorize('createAny', Fee::class);

        $user = Auth::user();

        $departments = match ($user->role) {
            'super_admin' => Department::orderBy('name')->get(),
            'university_admin' => Department::where('university_id', $user->university_id)->orderBy('name')->get(),
            'department_admin', 'staff_admin' => Department::where('id', $user->department_id)->orderBy('name')->get(),
            default => collect()
        };

        return view('admin.fees.create', compact('departments'));
    }

    public function store(StoreFeeRequest $request)
    {
        $department = Department::findOrFail($request->department_id);

        $this->authorize('create', [Fee::class, $department]);

        $fee = Fee::create($request->validated());

        AuditLog::create([
            'user_id' => Auth::id(),
            'event_type' => 'create',
            'model_type' => 'Fee',
            'model_id' => $fee->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.fees.index')
            ->with('success', 'Fee created successfully.');
    }

    public function show(Fee $fee)
    {
        $this->authorize('view', $fee);

        $fee->load('department.university');

        return view('admin.fees.show', compact('fee'));
    }

    public function edit(Fee $fee)
    {
        $this->authorize('update', $fee);

        $departments = Department::with('university')->orderBy('name')->get();

        return view('admin.fees.edit', compact('fee', 'departments'));
    }

    public function update(UpdateFeeRequest $request, Fee $fee)
    {
        $fee->load('department');

        $this->authorize('update', $fee);

        $validated = $request->validated();

        $fee->update($validated);

        AuditLog::create([
            'user_id' => Auth::id(),
            'event_type' => 'update',
            'model_type' => 'Fee',
            'model_id' => $fee->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.fees.index')
            ->with('success', 'Fee updated successfully.');
    }

    public function destroy(Fee $fee)
    {
        $fee->load('department');

        $this->authorize('delete', $fee);

        if ($fee->invoices()->exists()) {
            return redirect()->route('admin.fees.index')
                ->with('error', 'Cannot delete fee because it has associated invoices.');
        }

        $feeName = $fee->name;

        $fee->delete();

        AuditLog::create([
            'user_id' => Auth::id(),
            'event_type' => 'delete',
            'model_type' => 'Fee',
            'model_id' => $fee->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('admin.fees.index')
            ->with('success', "Fee '{$feeName}' deleted successfully.");
    }
}
