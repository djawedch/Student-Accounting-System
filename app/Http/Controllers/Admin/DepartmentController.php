<?php

namespace App\Http\Controllers\Admin;

use App\Filters\DepartmentFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Department\{StoreDepartmentRequest, UpdateDepartmentRequest};
use App\Models\{University, Department, AuditLog};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Department::class);

        $filter = new DepartmentFilter($request);

        $departments = $filter->apply(Department::with('university')->latest())
            ->paginate(10)
            ->withQueryString();

        return view('admin.departments.index', compact('departments'));
    }

    public function create()
    {
        $this->authorize('create', Department::class);

        $universities = Auth::user()->role === 'super_admin'
            ? University::orderBy('name')->get()
            : University::where('id', Auth::user()->university_id)->get();

        return view('admin.departments.create', compact('universities'));
    }

    public function store(StoreDepartmentRequest $request)
    {
        $this->authorize('create', Department::class);

        $user = Auth::user();

        if ($user->role === 'university_admin' && $request->university_id != $user->university_id) {
            abort(403, 'You can only create departments in your own university.');
        }

        $department = Department::create($request->validated());

        AuditLog::create([
            'user_id' => Auth::id(),
            'event_type' => 'create',
            'model_type' => 'Department',
            'model_id' => $department->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department created successfully.');
    }

    public function show(Department $department)
    {
        $this->authorize('view', $department);

        $department->load('university', 'users', 'fees');

        return view('admin.departments.show', compact('department'));
    }

    public function edit(Department $department)
    {
        $this->authorize('update', $department);

        $universities = Auth::user()->role === 'super_admin'
            ? University::orderBy('name')->get()
            : University::where('id', Auth::user()->university_id)->get();

        return view('admin.departments.edit', compact('department', 'universities'));
    }

    public function update(UpdateDepartmentRequest $request, Department $department)
    {
        $this->authorize('update', $department);

        $user = Auth::user();

        if ($user->role === 'university_admin' && $request->university_id != $user->university_id) {
            abort(403, 'You can only update departments in your own university.');
        }

        $department->update($request->validated());

        AuditLog::create([
            'user_id' => Auth::id(),
            'event_type' => 'update',
            'model_type' => 'Department',
            'model_id' => $department->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        $this->authorize('delete', $department);

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
            'user_id' => Auth::id(),
            'event_type' => 'delete',
            'model_type' => 'Department',
            'model_id' => $department->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department deleted successfully.');
    }
}
