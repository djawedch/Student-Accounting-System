<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with('university')->latest()->get();
        
        return view('student.departments.index', compact('departments'));
    }
}
