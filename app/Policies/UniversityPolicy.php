<?php

namespace App\Policies;

use App\Models\{University, User};

class UniversityPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'university_admin', 'department_admin', 'staff_admin']);
    }

    public function view(User $user, University $university): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->role === 'super_admin';
    }

    public function update(User $user, University $university): bool
    {
        if ($user->role === 'super_admin') {
            return true;
        }

        return $user->role === 'university_admin' && $user->university_id === $university->id;
    }

    public function delete(User $user, University $university): bool
    {
        return $user->role === 'super_admin';
    }
}
