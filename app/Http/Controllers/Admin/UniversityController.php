<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\University;
use Illuminate\Http\Request;

class UniversityController extends Controller
{
    public function index()
    {
        $universities = University::withCount('departments')->latest()->paginate(10);
        
        return view('admin.universities.index', compact('universities'));
    }

    public function create()
    {
        return view('admin.universities.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:universities,name',
            'city' => 'required|string|max:255',
        ]);

        University::create($validated);

        return redirect()->route('universities.index')->with('success', 'University created successfully.');
    }

    public function show(University $university)
    {
        $university->load('departments');

        return view('admin.universities.show', compact('university'));
    }

    public function edit(University $university)
    {
        return view('admin.universities.edit', compact('university'));
    }

    public function update(Request $request, University $university)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:universities,name,' . $university->id,
            'city' => 'required|string|max:255',
        ]);

        $university->update($validated);

        return redirect()->route('universities.index')->with('success', 'University updated successfully.');
    }

    public function destroy(University $university)
    {
        if ($university->departments()->count() > 0) {
            return redirect()->route('universities.index')
                ->with('error', 'Cannot delete university because it has associated departments.');
        }

        $university->delete();
        return redirect()->route('universities.index')
            ->with('success', 'University deleted successfully.');
    }
}
