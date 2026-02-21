<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Scholarship;
use App\Models\Student;
use App\Models\StudentScholarship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentScholarshipController extends Controller
{
    public function index()
    {
        $awards = StudentScholarship::with('student.user', 'scholarship')->latest()->paginate(10);

        return view('admin.student-scholarships.index', compact('awards'));
    }

    public function create()
    {
        $students = Student::with('user')->orderBy('id')->get();
        $scholarships = Scholarship::orderBy('name')->get();

        return view('admin.student-scholarships.create', compact('students', 'scholarships'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'exists:students,id',
            'scholarship_ids' => 'required|array|min:1',
            'scholarship_ids.*' => 'exists:scholarships,id',
            'grant_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:grant_date',
            'status' => 'required|in:awarded,paid,cancelled',
            'paid_at' => 'nullable|date',
            'reference' => 'nullable|string|max:255',
        ]);

        $studentIds = $request->student_ids;
        $scholarshipIds = $request->scholarship_ids;
        $createdCount = 0;

        DB::beginTransaction();

        try {
            foreach ($studentIds as $studentId) {
                foreach ($scholarshipIds as $scholarshipId) {

                    StudentScholarship::create([
                        'student_id' => $studentId,
                        'scholarship_id' => $scholarshipId,
                        'grant_date' => $request->grant_date,
                        'end_date' => $request->end_date,
                        'status' => $request->status,
                        'paid_at' => $request->paid_at,
                        'reference' => $request->reference,
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

    public function update(Request $request, StudentScholarship $studentScholarship)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'scholarship_id' => 'required|exists:scholarships,id',
            'grant_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:grant_date',
            'status' => 'required|in:awarded,paid,cancelled',
            'paid_at' => 'nullable|date',
            'reference' => 'nullable|string|max:255',
        ]);

        $studentScholarship->toArray();
        $studentScholarship->update($request->all());

        return redirect()->route('admin.student-scholarships.index')
            ->with('success', 'Scholarship award updated successfully.');
    }

    public function destroy(StudentScholarship $studentScholarship)
    {
        $studentScholarship->delete();

        return redirect()->route('admin.student-scholarships.index')
            ->with('success', 'Scholarship award deleted successfully.');
    }
}
