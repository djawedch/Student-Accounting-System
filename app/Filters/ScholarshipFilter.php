<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ScholarshipFilter
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder
    {
        return $query
            ->when($this->request->filled('name'), fn($q) =>
                $q->where('name', 'like', '%' . $this->request->name . '%')
            )
            ->when($this->request->filled('description'), fn($q) =>
                $q->where('description', 'like', '%' . $this->request->description . '%')
            )
            ->when($this->request->filled('amount_min'), fn($q) =>
                $q->where('amount', '>=', $this->request->amount_min)
            )
            ->when($this->request->filled('amount_max'), fn($q) =>
                $q->where('amount', '<=', $this->request->amount_max)
            );
    }
}