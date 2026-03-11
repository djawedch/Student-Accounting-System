<?php

namespace App\Http\Requests\Admin\ScholarshipAward;

use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreScholarshipAwardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = Auth::user();

        return [
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => [
                'required',
                'exists:students,id',
                function ($attribute, $value, $fail) use ($user) {
                    $student = Student::with('user')->find($value);
                    if (!$student) {
                        $fail('Invalid student.');
                        return;
                    }
                    if ($user->role === 'university_admin' && $student->user->university_id !== $user->university_id) {
                        $fail('You cannot award scholarships to students outside your university.');
                    }
                    if (in_array($user->role, ['department_admin', 'staff_admin']) && $student->user->department_id !== $user->department_id) {
                        $fail('You cannot award scholarships to students outside your department.');
                    }
                }
            ],
            'scholarship_ids' => 'required|array|min:1',
            'scholarship_ids.*' => 'exists:scholarships,id',
            'grant_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:grant_date',
            'status' => 'required|in:awarded,paid,cancelled',
            'paid_at' => 'nullable|date',
            'reference' => 'nullable|string|max:255',
        ];
    }
}
