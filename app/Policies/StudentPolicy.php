<?php

namespace App\Policies;

use App\Models\User;

class StudentPolicy
{
    public function viewAny(User $user): bool
    {
        // Staff admin CAN manage students
        return in_array($user->role, ['super_admin', 'university_admin', 'department_admin', 'staff_admin']);
    }

    public function view(User $user, User $student): bool
    {
        if ($student->role !== 'student') return false;

        if ($user->role === 'super_admin') return true;

        if ($user->role === 'university_admin') {
            return $student->university_id === $user->university_id;
        }

        if (in_array($user->role, ['department_admin', 'staff_admin'])) {
            return $student->department_id === $user->department_id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function update(User $user, User $student): bool
    {
        if ($student->role !== 'student') return false;
        
        if ($user->role === 'super_admin') return true;
        
        if ($user->role === 'university_admin') {
            return $student->university_id === $user->university_id;
        }
        
        if (in_array($user->role, ['department_admin', 'staff_admin'])) {
            return $student->department_id === $user->department_id;
        }
        
        return false;
    }

    public function delete(User $user, User $student): bool
    {
        return false;
    }

    public function toggleStatus(User $user, User $student): bool
    {
        return $this->update($user, $student);
    }
}