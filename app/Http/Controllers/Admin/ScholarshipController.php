<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Scholarship\{StoreScholarshipRequest, UpdateScholarshipRequest};
use App\Models\Scholarship;

class ScholarshipController extends Controller
{
    public function index()
    {
        $scholarships = Scholarship::latest()->paginate(10);

        return view('admin.scholarships.index', compact('scholarships'));
    }

    public function create()
    {
        return view('admin.scholarships.create');
    }

    public function store(StoreScholarshipRequest $request)
    {
        $request->validated();

        Scholarship::create($request->only(['name', 'amount', 'description']));

        return redirect()->route('admin.scholarships.index')
            ->with('success', 'Scholarship created successfully.');
    }

    public function show(Scholarship $scholarship)
    {
        $scholarship->load('studentScholarships.student.user');

        return view('admin.scholarships.show', compact('scholarship'));
    }

    public function edit(Scholarship $scholarship)
    {
        return view('admin.scholarships.edit', compact('scholarship'));
    }

    public function update(UpdateScholarshipRequest $request, Scholarship $scholarship)
    {
        $request->validated();

        $scholarship->update($request->only(['name', 'amount', 'description']));

        return redirect()->route('admin.scholarships.index')
            ->with('success', 'Scholarship updated successfully.');
    }

    public function destroy(Scholarship $scholarship)
    {
        if ($scholarship->studentScholarships()->exists()) {
            return redirect()->route('admin.scholarships.index')
                ->with('error', 'Cannot delete scholarship because it has been awarded to students.');
        }

        $scholarship->delete();

        return redirect()->route('admin.scholarships.index')
            ->with('success', 'Scholarship deleted successfully.');
    }
}
