<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;

class DepartmentController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $department = Department::with('university')
            ->findOrFail($user->department_id);

        return view('student.departments.show', compact('department'));
    }
}
