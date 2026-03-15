<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class InvoiceFilter
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
                $this->request->filled('fee'),
                fn($q) =>
                $q->whereHas(
                    'fee',
                    fn($f) =>
                    $f->where('name', 'like', '%' . $this->request->fee . '%')
                )
            )
            ->when(
                $this->request->filled('status'),
                fn($q) =>
                $q->where('status', $this->request->status)
            )
            ->when(
                $this->request->filled('issued_from'),
                fn($q) =>
                $q->whereDate('issued_date', '>=', $this->request->issued_from)
            )
            ->when(
                $this->request->filled('issued_to'),
                fn($q) =>
                $q->whereDate('issued_date', '<=', $this->request->issued_to)
            )
            ->when(
                $this->request->filled('due_from'),
                fn($q) =>
                $q->whereDate('due_date', '>=', $this->request->due_from)
            )
            ->when(
                $this->request->filled('due_to'),
                fn($q) =>
                $q->whereDate('due_date', '<=', $this->request->due_to)
            )
            ->when(
                $this->request->filled('amount_min'),
                fn($q) =>
                $q->whereHas(
                    'fee',
                    fn($f) =>
                    $f->where('amount', '>=', $this->request->amount_min)
                )
            )
            ->when(
                $this->request->filled('amount_max'),
                fn($q) =>
                $q->whereHas(
                    'fee',
                    fn($f) =>
                    $f->where('amount', '<=', $this->request->amount_max)
                )
            );
    }
}
