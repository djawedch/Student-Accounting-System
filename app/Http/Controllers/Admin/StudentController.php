<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Student\{StoreStudentRequest, UpdateStudentRequest};
use App\Models\{AuditLog, Department, Student, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Hash};

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'student')->with('student', 'department.university');

        if ($request->filled('name')) {
            $name = $request->name;
            $query->where(function ($q) use ($name) {
                $q->where('first_name', 'like', "%{$name}%")
                    ->orWhere('last_name', 'like', "%{$name}%");
            });
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        if ($request->filled('department')) {
            $query->whereHas('department', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->department . '%');
            });
        }

        if ($request->filled('university')) {
            $query->whereHas('department.university', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->university . '%');
            });
        }

        if ($request->filled('level')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('level', 'like', '%' . $request->level . '%');
            });
        }

        if ($request->filled('study_system')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('study_system', $request->study_system);
            });
        }

        if ($request->filled('academic_year')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('academic_year', 'like', '%' . $request->academic_year . '%');
            });
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $students = $query->latest()->paginate(10)->withQueryString();

        $studySystems = ['LMD', 'Classic'];

        return view('admin.students.index', compact('students', 'studySystems'));
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
                'user_id' => Auth::id(),
                'event_type' => 'create',
                'model_type' => 'User',
                'model_id' => $user->id,
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
                'user_id' => Auth::id(),
                'event_type' => 'update',
                'model_type' => 'User',
                'model_id' => $student->id,
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
            'user_id' => Auth::id(),
            'event_type' => 'update',
            'model_type' => 'User',
            'model_id' => $student->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $status = $student->is_active ? 'activated' : 'deactivated';
        return redirect()->route('admin.students.index')
            ->with('success', "Student {$student->first_name} {$student->last_name} {$status} successfully.");
    }
}
