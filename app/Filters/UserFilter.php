<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class UserFilter
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder
    {
        return $query
            ->when(
                $this->request->filled('name'),
                fn($q) =>
                $q->where(
                    fn($u) =>
                    $u->where('first_name', 'like', '%' . $this->request->name . '%')
                        ->orWhere('last_name', 'like', '%' . $this->request->name . '%')
                )
            )
            ->when(
                $this->request->filled('email'),
                fn($q) =>
                $q->where('email', 'like', '%' . $this->request->email . '%')
            )
            ->when(
                $this->request->filled('role'),
                fn($q) =>
                $q->where('role', $this->request->role)
            )
            ->when(
                $this->request->filled('department'),
                fn($q) =>
                $q->whereHas(
                    'department',
                    fn($d) =>
                    $d->where('name', 'like', '%' . $this->request->department . '%')
                )
            );
    }
}
