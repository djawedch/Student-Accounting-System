<?php

namespace App\Http\Requests\Admin\ScholarshipAward;

use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreScholarshipAwardRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = Auth::user();
        
        $studentIds = $this->input('student_ids', []);
        
        foreach ($studentIds as $studentId) {
            $student = Student::with('user')->find($studentId);
            
            if (!$student) {
                continue;
            }
            
            if ($user->role === 'university_admin' && $student->user->university_id !== $user->university_id) {
                return false;
            }
            
            if (in_array($user->role, ['department_admin', 'staff_admin']) && $student->user->department_id !== $user->department_id) {
                return false;
            }
        }
        
        return true;
    }

    public function rules(): array
    {
        return [
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'required|exists:students,id',
            'scholarship_ids' => 'required|array|min:1',
            'scholarship_ids.*' => 'exists:scholarships,id',
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
            'student_ids.required' => 'Please select at least one student.',
            'student_ids.*.exists' => 'One or more selected students do not exist.',
            'scholarship_ids.required' => 'Please select at least one scholarship.',
            'scholarship_ids.*.exists' => 'One or more selected scholarships do not exist.',
        ];
    }
}