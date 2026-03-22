<?php
namespace App\Http\Requests\Admin\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

abstract class BaseUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!in_array($user->role, ['super_admin', 'university_admin', 'department_admin', 'staff_admin'])) {
            return false;
        }

        if ($this->role) {
            $allRoles = [
                'super_admin'      => 4,
                'university_admin' => 3,
                'department_admin' => 2,
                'staff_admin'      => 1,
                'student'          => 0,
            ];

            $requestedRank = $allRoles[$this->role] ?? -1;

            if ($requestedRank >= $user->roleRank()) {
                return false;
            }
        }

        return true;
    }

    protected function allowedRoles(): array
    {
        $authUser = User::find(Auth::id());

        $allRoles = [
            'super_admin'      => 4,
            'university_admin' => 3,
            'department_admin' => 2,
            'staff_admin'      => 1,
            'student'          => 0,
        ];

        return array_keys(array_filter(
            $allRoles,
            fn($rank) => $rank < $authUser->roleRank()
        ));
    }

    protected function commonRules(): array
    {
        return [
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'university_id' => [
                'nullable',
                Rule::requiredIf(in_array($this->role, ['university_admin', 'department_admin', 'staff_admin'])),
                'exists:universities,id',
            ],
            'department_id' => [
                'nullable',
                Rule::requiredIf(in_array($this->role, ['department_admin', 'staff_admin'])),
                'exists:departments,id',
            ],
            'role'      => ['required', 'string', Rule::in(array_keys([
                'super_admin'      => 4,
                'university_admin' => 3,
                'department_admin' => 2,
                'staff_admin'      => 1,
                'student'          => 0,
            ]))],
            'is_active' => 'sometimes|boolean',
        ];
    }
}