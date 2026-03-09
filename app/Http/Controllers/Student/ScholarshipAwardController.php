<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\{User,StudentScholarship};
use Illuminate\Support\Facades\Auth;

class ScholarshipAwardController extends Controller
{
    public function index()
    {
        $user = User::find(Auth::id())->load('student.studentScholarships.scholarship');

        if (!$user->student) {
            abort(404, 'Student profile not found.');
        }

        $awards = $user->student->studentScholarships->sortByDesc('created_at');

        return view('student.scholarship-awards.index', compact('awards'));
    }

    public function show(StudentScholarship $award)
    {
        $user = User::find(Auth::id())->load('student');

        if (!$user->student || $award->student_id !== $user->student->id) {
            abort(403);
        }

        $award->load('scholarship', 'student.user');

        return view('student.scholarship-awards.show', compact('award'));
    }
}
