<?php

namespace App\Http\Requests\Admin\Fee;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'amount' => 'required|numeric|min:0',
            'academic_year' => 'required|string|max:20',
            'description' => 'nullable|string',
        ];
    }
}
