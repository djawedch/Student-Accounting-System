<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Scholarship\{StoreScholarshipRequest, UpdateScholarshipRequest};
use App\Models\{Scholarship, AuditLog};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScholarshipController extends Controller
{
    public function index(Request $request)
    {
        $query = Scholarship::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('description')) {
            $query->where('description', 'like', '%' . $request->description . '%');
        }

        if ($request->filled('amount_min')) {
            $query->where('amount', '>=', $request->amount_min);
        }
        if ($request->filled('amount_max')) {
            $query->where('amount', '<=', $request->amount_max);
        }

        $scholarships = $query->latest()->paginate(10)->withQueryString();

        return view('admin.scholarships.index', compact('scholarships'));
    }

    public function create()
    {
        return view('admin.scholarships.create');
    }

    public function store(StoreScholarshipRequest $request)
    {
        $request->validated();

        $scholarship = Scholarship::create($request->only(['name', 'amount', 'description']));

        AuditLog::create([
            'user_id' => Auth::id(),
            'event_type' => 'create',
            'model_type' => 'Scholarship',
            'model_id' => $scholarship->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

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

        AuditLog::create([
            'user_id' => Auth::id(),
            'event_type' => 'update',
            'model_type' => 'Scholarship',
            'model_id' => $scholarship->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

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

        AuditLog::create([
            'user_id' => Auth::id(),
            'event_type' => 'delete',
            'model_type' => 'Scholarship',
            'model_id' => $scholarship->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('admin.scholarships.index')
            ->with('success', 'Scholarship deleted successfully.');
    }
}
