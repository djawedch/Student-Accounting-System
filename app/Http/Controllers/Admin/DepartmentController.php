<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{University, Department};
use Illuminate\Http\Request;

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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'university_id' => 'required|exists:universities,id',
            'name' => 'required|string|max:255|unique:departments,name,NULL,id,university_id,' . $request->university_id,
        ]);

        Department::create($validated);

        return redirect()->route('departments.index')
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

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'university_id' => 'required|exists:universities,id',
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
        ]);

        $department->update($validated);

        return redirect()->route('departments.index')
            ->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        if ($department->users()->count() > 0) {
            return redirect()->route('departments.index')
                ->with('error', 'Cannot delete department because it has associated users.');
        }

        if ($department->fees()->count() > 0) {
            return redirect()->route('departments.index')
                ->with('error', 'Cannot delete department because it has associated fees.');
        }

        $department->delete();

        return redirect()->route('departments.index')
            ->with('success', 'Department deleted successfully.');
    }
}
