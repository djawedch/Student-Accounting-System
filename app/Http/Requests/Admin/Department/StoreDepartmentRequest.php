<?php
namespace App\Http\Requests\Admin\Department;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = Auth::user();

        if ($user->role === 'super_admin') {
            return true;
        }

        if ($user->role === 'university_admin') {
            return (int) $this->university_id === $user->university_id;
        }

        return false;
    }

    public function rules(): array
    {
        return [
            'university_id' => [
                'required',
                'exists:universities,id',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('departments')->where(
                    fn($query) => $query->where('university_id', $this->university_id)
                ),
            ],
        ];
    }
}