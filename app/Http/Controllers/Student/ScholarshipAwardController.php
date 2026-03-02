<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ScholarshipAwardController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;
        if (!$student) {
            abort(404, 'Student profile not found.');
        }
        $awards = $student->studentScholarships()->with('scholarship')->latest()->get();
        return view('student.scholarship-awards.index', compact('awards'));
    }
}
