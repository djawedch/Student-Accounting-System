<?php

namespace App\Http\Requests\Admin\Department;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $departmentId = $this->route('department')->id;

        return [
            'university_id' => [
                'required',
                'exists:universities,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('departments')
                    ->where(fn($query) => $query->where('university_id', $this->university_id))
                    ->ignore($departmentId),
            ],
        ];
    }
}
