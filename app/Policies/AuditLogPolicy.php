<?php
namespace App\Policies;

use App\Models\{User, AuditLog};

class AuditLogPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'university_admin']);
    }

    public function view(User $user, AuditLog $auditLog): bool
    {
        if ($user->role === 'super_admin') {
            return true;
        }

        if ($user->role === 'university_admin') {
            return $auditLog->user?->university_id === $user->university_id;
        }

        return false;
    }
}