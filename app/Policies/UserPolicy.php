<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'university_admin', 'department_admin', 'staff_admin']);
    }

    public function view(User $user, User $targetUser): bool
    {
        if ($user->id === $targetUser->id) return true;

        if ($user->role === 'super_admin') return true;

        if ($user->role === 'university_admin') {
            return $targetUser->university_id === $user->university_id;
        }

        if (in_array($user->role, ['department_admin', 'staff_admin'])) {
            return $targetUser->department_id === $user->department_id;
        }

        return false;
    }
    
    public function create(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function update(User $user, User $targetUser): bool
    {
        return $this->canManageUser($user, $targetUser);
    }

    public function delete(User $user, User $targetUser): bool
    {
        return false;
    }

    public function toggleStatus(User $user, User $targetUser): bool
    {
        return $this->canManageUser($user, $targetUser);
    }

    protected function canManageUser(User $currentUser, User $targetUser): bool
    {
        if ($currentUser->id === $targetUser->id) return false;

        if ($currentUser->roleRank() <= $targetUser->roleRank()) return false;

        if ($currentUser->role === 'university_admin') {
            return $targetUser->university_id === $currentUser->university_id;
        }

        if (in_array($currentUser->role, ['department_admin', 'staff_admin'])) {
            return $targetUser->department_id === $currentUser->department_id;
        }

        return true;
    }
}
