<?php

namespace App\Scopes;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class AuditLogRoleScope
{
    public function apply(Builder $query, User $user): Builder
    {
        return match ($user->role) {
            'university_admin' => $query->whereHas(
                'user',
                fn($q) =>
                $q->where('university_id', $user->university_id)
            ),
            default => $query
        };
    }
}
