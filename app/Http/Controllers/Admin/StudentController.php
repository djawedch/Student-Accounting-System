<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Student\{StoreStudentRequest, UpdateStudentRequest};
use App\Models\{AuditLog, Department, Student, User};
use Illuminate\Support\Facades\{Auth, DB, Hash};

class StudentController extends Controller
{
    public function index()
    {
        $students = User::where('role', 'student')
            ->with('student', 'department.university')
            ->latest()
            ->paginate(10);

        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();

        return view('admin.students.create', compact('departments'));
    }

    public function store(StoreStudentRequest $request)
    {
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'date_of_birth' => $validated['date_of_birth'],
                'department_id' => $validated['department_id'],
                'role' => 'student',
                'is_active' => $request->has('is_active'),
            ]);

            Student::create([
                'user_id' => $user->id,
                'level' => $validated['level'],
                'academic_year' => $validated['academic_year'],
                'study_system' => $validated['study_system'],
                'baccalaureate_year' => $validated['baccalaureate_year'],
            ]);

            AuditLog::create([
                'user_id'    => Auth::id(),
                'event_type' => 'create',
                'model_type' => 'User',
                'model_id'   => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();

            return redirect()->route('admin.students.index')
                ->with('success', 'Student created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create student. Please try again.')->withInput();
        }
    }

    public function show(User $student)
    {
        $student->load('student', 'department.university');

        return view('admin.students.show', compact('student'));
    }

    public function edit(User $student)
    {
        $student->load('student');

        $departments = Department::orderBy('name')->get();

        return view('admin.students.edit', compact('student', 'departments'));
    }

    public function update(UpdateStudentRequest $request, User $student)
    {
        if ($student->role !== 'student') {
            abort(404, 'Student not found.');
        }

        $validated = $request->validated();

        try {
            DB::beginTransaction();

            $userData = [
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'date_of_birth' => $validated['date_of_birth'],
                'department_id' => $validated['department_id'],
                'is_active' => $request->has('is_active'),
            ];

            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $student->update($userData);

            if ($student->student) {
                $student->student->update([
                    'level' => $validated['level'],
                    'academic_year' => $validated['academic_year'],
                    'study_system' => $validated['study_system'],
                    'baccalaureate_year' => $validated['baccalaureate_year'],
                ]);
            } else {
                Student::create([
                    'user_id' => $student->id,
                    'level' => $validated['level'],
                    'academic_year' => $validated['academic_year'],
                    'study_system' => $validated['study_system'],
                    'baccalaureate_year' => $validated['baccalaureate_year'],
                ]);
            }

            AuditLog::create([
                'user_id'    => Auth::id(),
                'event_type' => 'update',
                'model_type' => 'User',
                'model_id'   => $student->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();

            return redirect()->route('admin.students.index')
                ->with('success', 'Student updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update student. Please try again.')->withInput();
        }
    }

    public function toggleStatus(User $student)
    {
        $student->is_active = !$student->is_active;
        $student->save();

        AuditLog::create([
            'user_id'    => Auth::id(),
            'event_type' => 'update',
            'model_type' => 'User',
            'model_id'   => $student->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $status = $student->is_active ? 'activated' : 'deactivated';
        return redirect()->route('admin.students.index')
            ->with('success', "Student {$student->first_name} {$student->last_name} {$status} successfully.");
    }
}
