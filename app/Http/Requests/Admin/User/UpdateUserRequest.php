<?php
namespace App\Http\Requests\Admin\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')->id;
        $authUser = User::find(Auth::id());

        $allRoles = [
            'super_admin'      => 4,
            'university_admin' => 3,
            'department_admin' => 2,
            'staff_admin'      => 1,
            'student'          => 0,
        ];

        $allowedRoles = array_keys(array_filter(
            $allRoles,
            fn($rank) => $rank < $authUser->roleRank()
        ));

        return [
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'email'         => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($userId),
            ],
            'password'      => 'nullable|string|min:8|confirmed',
            'date_of_birth' => 'required|date|before:today',
            'university_id' => [
                'nullable',
                Rule::requiredIf(in_array($this->role, ['university_admin', 'department_admin', 'staff_admin'])),
                'exists:universities,id'
            ],
            'department_id' => [
                'nullable',
                Rule::requiredIf(in_array($this->role, ['department_admin', 'staff_admin'])),
                'exists:departments,id'
            ],
            'role'      => ['required', 'string', Rule::in($allowedRoles)],
            'is_active' => 'sometimes|boolean',
        ];
    }
}
