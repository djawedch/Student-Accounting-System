<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StudentScholarship\{StoreStudentScholarshipRequest, UpdateStudentScholarshipRequest};
use App\Models\{AuditLog, Student, Scholarship, StudentScholarship};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};

class StudentScholarshipController extends Controller
{
    public function index(Request $request)
    {
        $query = StudentScholarship::with('student.user', 'scholarship');

        if ($request->filled('student')) {
            $student = $request->student;
            $query->whereHas('student.user', function ($q) use ($student) {
                $q->where('first_name', 'like', "%{$student}%")
                    ->orWhere('last_name', 'like', "%{$student}%");
            });
        }

        if ($request->filled('scholarship')) {
            $query->whereHas('scholarship', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->scholarship . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('grant_from')) {
            $query->whereDate('grant_date', '>=', $request->grant_from);
        }
        if ($request->filled('grant_to')) {
            $query->whereDate('grant_date', '<=', $request->grant_to);
        }

        if ($request->filled('paid_from')) {
            $query->whereDate('paid_at', '>=', $request->paid_from);
        }
        if ($request->filled('paid_to')) {
            $query->whereDate('paid_at', '<=', $request->paid_to);
        }

        if ($request->filled('reference')) {
            $query->where('reference', 'like', '%' . $request->reference . '%');
        }

        $awards = $query->latest()->paginate(10)->withQueryString();

        $statuses = ['awarded', 'paid', 'cancelled'];

        return view('admin.student-scholarships.index', compact('awards', 'statuses'));
    }

    public function create()
    {
        $students = Student::with('user')->orderBy('id')->get();
        $scholarships = Scholarship::orderBy('name')->get();

        return view('admin.student-scholarships.create', compact('students', 'scholarships'));
    }

    public function store(StoreStudentScholarshipRequest $request)
    {
        $request->validated();

        $studentIds = $request->student_ids;
        $scholarshipIds = $request->scholarship_ids;
        $createdCount = 0;

        DB::beginTransaction();

        try {
            foreach ($studentIds as $studentId) {
                foreach ($scholarshipIds as $scholarshipId) {

                    $award = StudentScholarship::create([
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
                        'model_type' => 'StudentScholarship',
                        'model_id' => $award->id,
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]);

                    $createdCount++;
                }
            }

            DB::commit();

            return redirect()->route('admin.student-scholarships.index')
                ->with('success', "{$createdCount} scholarship award(s) created successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create awards: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(StudentScholarship $studentScholarship)
    {
        $studentScholarship->load('student.user', 'scholarship');

        return view('admin.student-scholarships.show', compact('studentScholarship'));
    }

    public function edit(StudentScholarship $studentScholarship)
    {
        $students = Student::with('user')->orderBy('id')->get();
        $scholarships = Scholarship::orderBy('name')->get();

        return view('admin.student-scholarships.edit', compact('studentScholarship', 'students', 'scholarships'));
    }

    public function update(UpdateStudentScholarshipRequest $request, StudentScholarship $studentScholarship)
    {
        $request->validated();
        $studentScholarship->toArray();
        $studentScholarship->update($request->all());

        AuditLog::create([
            'user_id' => Auth::id(),
            'event_type' => 'update',
            'model_type' => 'StudentScholarship',
            'model_id' => $studentScholarship->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.student-scholarships.index')
            ->with('success', 'Scholarship award updated successfully.');
    }
}
