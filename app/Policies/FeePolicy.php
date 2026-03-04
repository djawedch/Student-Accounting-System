<?php

namespace App\Policies;

use App\Models\{Department, User, Fee};

class FeePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'university_admin', 'department_admin', 'staff_admin']);
    }

    public function view(User $user, Fee $fee): bool
    {
        return $this->viewAny($user);
    }

    public function createAny(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'university_admin', 'department_admin', 'staff_admin']);
    }

    public function create(User $user, Department $department): bool
    {
        if ($user->role === 'super_admin') {
            return true;
        }
        if ($user->role === 'university_admin') {
            return $department->university_id === $user->university_id;
        }
        if (in_array($user->role, ['department_admin', 'staff_admin'])) {
            return $department->id === $user->department_id;
        }
        return false;
    }

    public function update(User $user, Fee $fee): bool
    {
        return $this->canManageFee($user, $fee);
    }

    public function delete(User $user, Fee $fee): bool
    {
        return $this->canManageFee($user, $fee);
    }

    protected function canManageFee(User $user, Fee $fee): bool
    {
        if ($user->role === 'super_admin') {
            return true;
        }

        if ($user->role === 'university_admin') {
            return $fee->department->university_id === $user->university_id;
        }

        if (in_array($user->role, ['department_admin', 'staff_admin'])) {
            return $fee->department_id === $user->department_id;
        }
        return false;
    }
}
