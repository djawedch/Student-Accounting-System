<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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
            'university_id' => [
                'nullable',
                Rule::requiredIf(in_array($this->role, ['university_admin', 'department_admin', 'staff_admin', 'student'])),
                'exists:universities,id'
            ],
            'department_id' => [
                'nullable',
                Rule::requiredIf(in_array($this->role, ['department_admin', 'staff_admin', 'student'])),
                'exists:departments,id'
            ],
            'role' => 'required|in:super_admin,university_admin,department_admin,staff_admin',
            'is_active' => 'sometimes|boolean',
        ];
    }
}
