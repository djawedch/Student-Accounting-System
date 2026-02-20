<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Department, Fee};
use Illuminate\Http\Request;

class FeeController extends Controller
{
    public function index()
    {
        $fees = Fee::with('department')->latest()->paginate(10);

        return view('admin.fees.index', compact('fees'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();

        return view('admin.fees.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'amount' => 'required|numeric|min:0',
            'academic_year' => 'required|string|max:20',
            'description' => 'nullable|string',
        ]);

        $fee = Fee::create($validated);

        return redirect()->route('admin.fees.index')
            ->with('success', 'Fee created successfully.');
    }

    public function show(Fee $fee)
    {
        $fee->load('department.university');

        return view('admin.fees.show', compact('fee'));
    }

    public function edit(Fee $fee)
    {
        $departments = Department::orderBy('name')->get();

        return view('admin.fees.edit', compact('fee', 'departments'));
    }

    public function update(Request $request, Fee $fee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'amount' => 'required|numeric|min:0',
            'academic_year' => 'required|string|max:20',
            'description' => 'nullable|string',
        ]);

        $fee->update($validated);

        return redirect()->route('admin.fees.index')
            ->with('success', 'Fee updated successfully.');
    }

    public function destroy(Fee $fee)
    {
        if ($fee->invoices()->exists()) {
            return redirect()->route('admin.fees.index')
                ->with('error', 'Cannot delete fee because it has associated invoices.');
        }

        $feeName = $fee->name;

        $fee->delete();

        return redirect()->route('admin.fees.index')
            ->with('success', "Fee '{$feeName}' deleted successfully.");
    }
}
