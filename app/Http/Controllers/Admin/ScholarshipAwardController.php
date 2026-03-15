<?php

namespace App\Http\Controllers\Admin;

use App\Filters\ScholarshipAwardFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ScholarshipAward\{StoreScholarshipAwardRequest, UpdateScholarshipAwardRequest};
use App\Models\{AuditLog, Student, Scholarship, ScholarshipAward, University};
use App\Scopes\ScholarshipAwardRoleScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};

class ScholarshipAwardController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', ScholarshipAward::class);

        $user = Auth::user();
        $baseQuery = ScholarshipAward::with('student.user', 'scholarship');

        $awards = (new ScholarshipAwardFilter($request))
            ->apply((new ScholarshipAwardRoleScope)->apply($baseQuery, $user))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $statuses = ['awarded', 'paid', 'cancelled'];

        return view('admin.scholarship-awards.index', compact('awards', 'statuses'));
    }

    public function create()
    {
        $this->authorize('create', ScholarshipAward::class);
        $user = Auth::user();

        $universities = match ($user->role) {
            'super_admin' => University::with('departments')->orderBy('name')->get(),
            default => University::with('departments')->where('id', $user->university_id)->get(),
        };

        $scholarships = Scholarship::orderBy('name')->get();

        return view('admin.scholarship-awards.create', compact('universities', 'scholarships'));
    }

    public function store(StoreScholarshipAwardRequest $request)
    {
        $this->authorize('create', ScholarshipAward::class);

        $request->validated();

        $studentIds = $request->student_ids;
        $scholarshipIds = $request->scholarship_ids;
        $createdCount = 0;

        DB::beginTransaction();

        try {
            foreach ($studentIds as $studentId) {
                foreach ($scholarshipIds as $scholarshipId) {

                    $award = ScholarshipAward::create([
                        'student_id' => $studentId,
                        'scholarship_id' => $scholarshipId,
                        'grant_date' => $request->grant_date,
                        'end_date' => $request->end_date,
                        'status' => $request->status,
                        'paid_at' => $request->paid_at,
                        'reference' => $request->reference,
                    ]);

                    AuditLog::create([
                        'user_id' => Auth::id(),
                        'event_type' => 'create',
                        'model_type' => 'ScholarshipAward',
                        'model_id' => $award->id,
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]);

                    $createdCount++;
                }
            }

            DB::commit();

            return redirect()->route('admin.scholarship-awards.index')
                ->with('success', "{$createdCount} scholarship award(s) created successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create awards: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(ScholarshipAward $award)
    {
        $award->load('student.user', 'scholarship');

        $this->authorize('view', $award);

        return view('admin.scholarship-awards.show', compact('award'));
    }

    public function edit(ScholarshipAward $award)
    {
        $award->load('student.user');

        $this->authorize('update', $award);

        $user = Auth::user();

        $students = match ($user->role) {
            'super_admin' => Student::with('user')->get(),
            'university_admin' => Student::whereHas('user', fn($q) => $q->where('university_id', $user->university_id))->with('user')->get(),
            'department_admin', 'staff_admin' => Student::whereHas('user', fn($q) => $q->where('department_id', $user->department_id))->with('user')->get(),
            default => collect()
        };

        $scholarships = Scholarship::orderBy('name')->get();

        return view('admin.scholarship-awards.edit', compact('award', 'students', 'scholarships'));
    }

    public function update(UpdateScholarshipAwardRequest $request, ScholarshipAward $award)
    {
        $award->load('student.user');

        $this->authorize('update', $award);

        $award->update($request->validated());

        AuditLog::create([
            'user_id' => Auth::id(),
            'event_type' => 'update',
            'model_type' => 'ScholarshipAward',
            'model_id' => $award->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.scholarship-awards.index')
            ->with('success', 'Scholarship award updated successfully.');
    }
}
