<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Department\{StoreDepartmentRequest, UpdateDepartmentRequest};
use App\Models\{University, Department, AuditLog};
use Illuminate\Support\Facades\Auth;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with('university')->latest()->paginate(10);

        return view('admin.departments.index', compact('departments'));
    }

    public function create()
    {
        $universities = University::orderBy('name')->get();

        return view('admin.departments.create', compact('universities'));
    }

    public function store(StoreDepartmentRequest $request)
    {
        $department = Department::create($request->validated());

        AuditLog::create([
            'user_id'    => Auth::id(),
            'event_type' => 'create',
            'model_type' => 'Department',
            'model_id'   => $department->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department created successfully.');
    }

    public function show(Department $department)
    {
        $department->load('university', 'users', 'fees');

        return view('admin.departments.show', compact('department'));
    }

    public function edit(Department $department)
    {
        $universities = University::orderBy('name')->get();

        return view('admin.departments.edit', compact('department', 'universities'));
    }

    public function update(UpdateDepartmentRequest $request, Department $department)
    {
        $department->update($request->validated());

        AuditLog::create([
            'user_id'    => Auth::id(),
            'event_type' => 'update',
            'model_type' => 'Department',
            'model_id'   => $department->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        if ($department->users()->count() > 0) {
            return redirect()->route('admin.departments.index')
                ->with('error', 'Cannot delete department because it has associated users.');
        }

        if ($department->fees()->count() > 0) {
            return redirect()->route('admin.departments.index')
                ->with('error', 'Cannot delete department because it has associated fees.');
        }

        $department->delete();

        AuditLog::create([
            'user_id'    => Auth::id(),
            'event_type' => 'delete',
            'model_type' => 'Department',
            'model_id'   => $department->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department deleted successfully.');
    }
}
