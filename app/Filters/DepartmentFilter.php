<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DepartmentFilter
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder
    {
        return $query
            ->when(
                $this->request->filled('name'),
                fn($q) =>
                $q->where('name', 'like', '%' . $this->request->name . '%')
            )
            ->when(
                $this->request->filled('university'),
                fn($q) =>
                $q->whereHas(
                    'university',
                    fn($u) =>
                    $u->where('name', 'like', '%' . $this->request->university . '%')
                )
            );
    }
}
