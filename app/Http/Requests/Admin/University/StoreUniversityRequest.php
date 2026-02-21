<?php

namespace App\Http\Requests\Admin\University;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUniversityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('universities')->where(
                    fn($query) => $query->where('city', $this->city)
                ),
            ],
            'city' => 'required|string|max:255',
        ];
    }
}
