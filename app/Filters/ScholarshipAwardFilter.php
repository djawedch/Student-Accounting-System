<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ScholarshipAwardFilter
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder
    {
        return $query
            ->when(
                $this->request->filled('student'),
                fn($q) =>
                $q->whereHas(
                    'student.user',
                    fn($u) =>
                    $u->where('first_name', 'like', '%' . $this->request->student . '%')
                        ->orWhere('last_name', 'like', '%' . $this->request->student . '%')
                )
            )
            ->when(
                $this->request->filled('scholarship'),
                fn($q) =>
                $q->whereHas(
                    'scholarship',
                    fn($s) =>
                    $s->where('name', 'like', '%' . $this->request->scholarship . '%')
                )
            )
            ->when(
                $this->request->filled('status'),
                fn($q) =>
                $q->where('status', $this->request->status)
            )
            ->when(
                $this->request->filled('grant_from'),
                fn($q) =>
                $q->whereDate('grant_date', '>=', $this->request->grant_from)
            )
            ->when(
                $this->request->filled('grant_to'),
                fn($q) =>
                $q->whereDate('grant_date', '<=', $this->request->grant_to)
            )
            ->when(
                $this->request->filled('paid_from'),
                fn($q) =>
                $q->whereDate('paid_at', '>=', $this->request->paid_from)
            )
            ->when(
                $this->request->filled('paid_to'),
                fn($q) =>
                $q->whereDate('paid_at', '<=', $this->request->paid_to)
            )
            ->when(
                $this->request->filled('reference'),
                fn($q) =>
                $q->where('reference', 'like', '%' . $this->request->reference . '%')
            );
    }
}
