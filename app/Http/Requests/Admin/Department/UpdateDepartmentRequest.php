<?php
namespace App\Http\Requests\Admin\Department;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = Auth::user();
        $department = $this->route('department');

        if ($user->role === 'super_admin') {
            return true;
        }

        if ($user->role === 'university_admin') {
            return $department->university_id === $user->university_id;
        }

        if ($user->role === 'department_admin') {
            return $department->id === $user->department_id;
        }

        return false;
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