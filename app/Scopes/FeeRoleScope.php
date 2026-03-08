<?php

namespace App\Scopes;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class FeeRoleScope
{
    public function apply(Builder $query, User $user): Builder
    {
        return match($user->role) {
            'university_admin' => $query->whereHas('department', fn($q) =>
                $q->where('university_id', $user->university_id)
            ),
            'department_admin', 'staff_admin' => $query->where('department_id', $user->department_id),
            default => $query
        };
    }
}