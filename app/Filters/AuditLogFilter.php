<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AuditLogFilter
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder
    {
        return $query
            ->when(
                $this->request->filled('user_id'),
                fn($q) =>
                $q->where('user_id', $this->request->user_id)
            )
            ->when(
                $this->request->filled('event_type'),
                fn($q) =>
                $q->where('event_type', $this->request->event_type)
            )
            ->when(
                $this->request->filled('model_type'),
                fn($q) =>
                $q->where('model_type', $this->request->model_type)
            )
            ->when(
                $this->request->filled('role'),
                fn($q) =>
                $q->whereHas(
                    'user',
                    fn($u) =>
                    $u->where('role', $this->request->role)
                )
            )
            ->when(
                $this->request->filled('date_from'),
                fn($q) =>
                $q->whereDate('created_at', '>=', $this->request->date_from)
            )
            ->when(
                $this->request->filled('date_to'),
                fn($q) =>
                $q->whereDate('created_at', '<=', $this->request->date_to)
            );
    }
}
