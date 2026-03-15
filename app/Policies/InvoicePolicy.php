<?php

namespace App\Policies;

use App\Models\{User, Invoice};

class InvoicePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'university_admin', 'department_admin', 'staff_admin']);
    }

    public function view(User $user, Invoice $invoice): bool
    {
        return $this->canAccessInvoice($user, $invoice);
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function update(User $user, Invoice $invoice): bool
    {
        return $this->canAccessInvoice($user, $invoice);
    }

    public function delete(User $user, Invoice $invoice): bool
    {
        return false;
    }

    protected function canAccessInvoice(User $user, Invoice $invoice): bool
    {
        $student = $invoice->student;

        if (!$student) {
            return false;
        }

        if ($user->role === 'super_admin') {
            return true;
        }

        if ($user->role === 'university_admin') {
            return $student->user->university_id === $user->university_id;
        }

        if (in_array($user->role, ['department_admin', 'staff_admin'])) {
            return $student->user->department_id === $user->department_id;
        }

        return false;
    }
}
