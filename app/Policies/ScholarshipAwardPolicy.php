<?php

namespace App\Policies;

use App\Models\{User, ScholarshipAward};

class ScholarshipAwardPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'university_admin', 'department_admin', 'staff_admin']);
    }

    public function view(User $user, ScholarshipAward $award): bool
    {
        return $this->canAccessAward($user, $award);
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function update(User $user, ScholarshipAward $award): bool
    {
        if ($user->role === 'staff_admin') {
            return false;
        }

        return $this->canAccessAward($user, $award);
    }

    public function delete(User $user, ScholarshipAward $award): bool
    {
        return false;
    }

    protected function canAccessAward(User $user, ScholarshipAward $award): bool
    {
        $student = $award->student;

        if (!$student) {
            return false;
        }

        if ($user->role === 'super_admin') {
            return true;
        }

        if ($user->role === 'university_admin') {
            return $student->user->university_id === $user->university_id;
        }

        if (in_array($user->role, ['department_admin', 'staff_admin'])) {
            return $student->user->department_id === $user->department_id;
        }

        return false;
    }
}
