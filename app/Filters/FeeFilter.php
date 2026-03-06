<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class FeeFilter
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder
    {
        return $query
            ->when($this->request->filled('name'), fn($q) =>
                $q->where('name', 'like', '%' . $this->request->name . '%')
            )
            ->when($this->request->filled('department'), fn($q) =>
                $q->whereHas('department', fn($d) =>
                    $d->where('name', 'like', '%' . $this->request->department . '%')
                )
            )
            ->when($this->request->filled('university'), fn($q) =>
                $q->whereHas('department.university', fn($u) =>
                    $u->where('name', 'like', '%' . $this->request->university . '%')
                )
            )
            ->when($this->request->filled('amount_min'), fn($q) =>
                $q->where('amount', '>=', $this->request->amount_min)
            )
            ->when($this->request->filled('amount_max'), fn($q) =>
                $q->where('amount', '<=', $this->request->amount_max)
            )
            ->when($this->request->filled('academic_year'), fn($q) =>
                $q->where('academic_year', 'like', '%' . $this->request->academic_year . '%')
            );
    }
}