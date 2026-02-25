<?php

namespace App\Http\Requests\Admin\Student;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'date_of_birth' => 'required|date|before:today',
            'department_id' => 'required|exists:departments,id',
            'level' => 'required|string|max:50',
            'academic_year' => 'required|string|max:20',
            'study_system' => 'required|in:LMD,Classic',
            'baccalaureate_year' => 'required|integer|min:1900|max:' . date('Y'),
            'is_active' => 'sometimes|boolean',
        ];
    }
}
