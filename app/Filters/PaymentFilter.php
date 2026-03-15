<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PaymentFilter
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder
    {
        return $query
            ->when(
                $this->request->filled('student'),
                fn($q) =>
                $q->whereHas(
                    'invoice.student.user',
                    fn($u) =>
                    $u->where('first_name', 'like', '%' . $this->request->student . '%')
                        ->orWhere('last_name', 'like', '%' . $this->request->student . '%')
                )
            )
            ->when(
                $this->request->filled('invoice_id'),
                fn($q) =>
                $q->where('invoice_id', $this->request->invoice_id)
            )
            ->when(
                $this->request->filled('payment_method'),
                fn($q) =>
                $q->where('payment_method', $this->request->payment_method)
            )
            ->when(
                $this->request->filled('amount_min'),
                fn($q) =>
                $q->where('amount', '>=', $this->request->amount_min)
            )
            ->when(
                $this->request->filled('amount_max'),
                fn($q) =>
                $q->where('amount', '<=', $this->request->amount_max)
            )
            ->when(
                $this->request->filled('date_from'),
                fn($q) =>
                $q->whereDate('payment_date', '>=', $this->request->date_from)
            )
            ->when(
                $this->request->filled('date_to'),
                fn($q) =>
                $q->whereDate('payment_date', '<=', $this->request->date_to)
            )
            ->when(
                $this->request->filled('reference'),
                fn($q) =>
                $q->where('reference', 'like', '%' . $this->request->reference . '%')
            );
    }
}
