<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\University;
use Illuminate\Support\Facades\Auth;

class UniversityController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $university = University::with('departments')
            ->findOrFail($user->university_id);

        return view('student.universities.show', compact('university'));
    }
}
