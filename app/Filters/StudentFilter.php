<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class StudentFilter
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder
    {
        return $query
            ->when($this->request->filled('name'), fn($q) =>
                $q->where(fn($u) =>
                    $u->where('first_name', 'like', '%' . $this->request->name . '%')
                      ->orWhere('last_name', 'like', '%' . $this->request->name . '%')
                )
            )
            ->when($this->request->filled('email'), fn($q) =>
                $q->where('email', 'like', '%' . $this->request->email . '%')
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
            ->when($this->request->filled('level'), fn($q) =>
                $q->whereHas('student', fn($s) =>
                    $s->where('level', 'like', '%' . $this->request->level . '%')
                )
            )
            ->when($this->request->filled('study_system'), fn($q) =>
                $q->whereHas('student', fn($s) =>
                    $s->where('study_system', $this->request->study_system)
                )
            )
            ->when($this->request->filled('academic_year'), fn($q) =>
                $q->whereHas('student', fn($s) =>
                    $s->where('academic_year', 'like', '%' . $this->request->academic_year . '%')
                )
            )
            ->when($this->request->filled('is_active'), fn($q) =>
                $q->where('is_active', $this->request->is_active)
            );
    }
}