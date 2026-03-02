<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FeeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user->student) {
            abort(404, 'Student profile not found.');
        }

        if (!$user->department) {
            abort(404, 'Your department is not assigned.');
        }

        $fees = $user->department->fees()->latest()->get();

        return view('student.fees.index', compact('fees'));
    }
}
