<?php

namespace App\Policies;

use App\Models\{User, AuditLog};

class AuditLogPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'super_admin';
    }

    public function view(User $user, AuditLog $auditLog): bool
    {
        return $user->role === 'super_admin';
    }
}