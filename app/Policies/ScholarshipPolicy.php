<?php

namespace App\Policies;

use App\Models\{User, Scholarship};

class ScholarshipPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'university_admin', 'department_admin', 'staff_admin']);
    }

    public function view(User $user, Scholarship $scholarship): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->role === 'super_admin';
    }

    public function update(User $user, Scholarship $scholarship): bool
    {
        return $this->create($user);
    }

    public function delete(User $user, Scholarship $scholarship): bool
    {
        return $this->create($user);
    }
}
