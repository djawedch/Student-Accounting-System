<?php

namespace App\Policies;

use App\Models\{Department, User};

class DepartmentPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'university_admin', 'department_admin', 'staff_admin']);
    }

    public function view(User $user, Department $department): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'university_admin']);
    }

    public function update(User $user, Department $department): bool
    {
        if ($user->role === 'super_admin') {
            return true;
        }

        if ($user->role === 'university_admin' && $user->university_id === $department->university_id) {
            return true;
        }

        if ($user->role === 'department_admin' && $user->department_id === $department->id) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Department $department): bool
    {
        return $user->role === 'super_admin' ||
            ($user->role === 'university_admin' && $user->university_id === $department->university_id);
    }
}
