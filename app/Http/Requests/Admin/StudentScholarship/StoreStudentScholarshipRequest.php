<?php

namespace App\Http\Requests\Admin\StudentScholarship;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentScholarshipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'exists:students,id',
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
