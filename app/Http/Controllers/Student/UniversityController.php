<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\University;

class UniversityController extends Controller
{
    public function index()
    {
        $universities = University::withCount('departments')->latest()->get();
        return view('student.universities.index', compact('universities'));
    }
}
