<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class UniversityFilter
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
                $this->request->filled('city'),
                fn($q) =>
                $q->where('city', 'like', '%' . $this->request->city . '%')
            );
    }
}
