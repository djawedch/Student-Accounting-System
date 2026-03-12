<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\{User, Fee};
use Illuminate\Support\Facades\Auth;

class FeeController extends Controller
{
    public function index()
    {
        $user = User::find(Auth::id())->load('student', 'department.fees');

        if (!$user->student) {
            abort(404, 'Student profile not found.');
        }

        if (!$user->department) {
            abort(404, 'Your department is not assigned.');
        }

        $fees = $user->department->fees->sortByDesc('created_at');

        return view('student.fees.index', compact('fees'));
    }

    public function show(Fee $fee)
    {
        $user = User::find(Auth::id())->load('department');

        if ($fee->department_id !== $user->department_id) {
            abort(403);
        }

        $fee->load('department.university');

        return view('student.fees.show', compact('fee'));
    }
}
