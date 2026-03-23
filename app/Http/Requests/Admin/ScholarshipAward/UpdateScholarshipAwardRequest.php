<?php

namespace App\Http\Requests\Admin\ScholarshipAward;

use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateScholarshipAwardRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = Auth::user();
        $award = $this->route('award');
        
        if (!$award || !$award->student) {
            return false;
        }
        
        $student = $award->student->load('user');
        
        if ($user->role === 'super_admin') {
            return true;
        }
        
        if ($user->role === 'university_admin') {
            return $student->user->university_id === $user->university_id;
        }
        
        if ($user->role === 'department_admin') {
            return $student->user->department_id === $user->department_id;
        }
        
        if ($user->role === 'staff_admin') {
            return false;
        }
        
        return false;
    }

    public function rules(): array
    {
        return [
            'student_id' => 'required|exists:students,id',
            'scholarship_id' => 'required|exists:scholarships,id',
            'grant_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:grant_date',
            'status' => 'required|in:awarded,paid,cancelled',
            'paid_at' => 'nullable|date',
            'reference' => 'nullable|string|max:255',
        ];
    }
    
    public function messages(): array
    {
        return [
            'student_id.required' => 'The student field is required.',
            'student_id.exists' => 'The selected student does not exist.',
            'scholarship_id.required' => 'The scholarship field is required.',
            'scholarship_id.exists' => 'The selected scholarship does not exist.',
        ];
    }
}