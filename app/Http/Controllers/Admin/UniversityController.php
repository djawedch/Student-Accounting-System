<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\University\{StoreUniversityRequest, UpdateUniversityRequest};
use App\Models\{University, AuditLog};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UniversityController extends Controller
{
    public function index(Request $request)
    {
        $query = University::withCount('departments');

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        $universities = $query->latest()->paginate(10)->withQueryString();

        return view('admin.universities.index', compact('universities'));
    }

    public function create()
    {
        return view('admin.universities.create');
    }

    public function store(StoreUniversityRequest $request)
    {
        $university = University::create($request->validated());

        AuditLog::create([
            'user_id' => Auth::id(),
            'event_type' => 'create',
            'model_type' => 'University',
            'model_id' => $university->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.universities.index')->with('success', 'University created successfully.');
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

    public function update(UpdateUniversityRequest $request, University $university)
    {
        $university->update($request->validated());

        AuditLog::create([
            'user_id' => Auth::id(),
            'event_type' => 'update',
            'model_type' => 'University',
            'model_id' => $university->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.universities.index')->with('success', 'University updated successfully.');
    }

    public function destroy(University $university)
    {
        if ($university->departments()->count() > 0) {
            return redirect()->route('admin.universities.index')
                ->with('error', 'Cannot delete university because it has associated departments.');
        }

        $university->delete();

        AuditLog::create([
            'user_id' => Auth::id(),
            'event_type' => 'delete',
            'model_type' => 'University',
            'model_id' => $university->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('admin.universities.index')
            ->with('success', 'University deleted successfully.');
    }
}
