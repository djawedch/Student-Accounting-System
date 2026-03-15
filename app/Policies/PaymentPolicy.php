<?php

namespace App\Policies;

use App\Models\{User, Payment};

class PaymentPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'university_admin', 'department_admin', 'staff_admin']);
    }

    public function view(User $user, Payment $payment): bool
    {
        return $this->canAccessPayment($user, $payment);
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function update(User $user, Payment $payment): bool
    {
        return $this->canAccessPayment($user, $payment);
    }

    public function delete(User $user, Payment $payment): bool
    {
        return false;
    }

    protected function canAccessPayment(User $user, Payment $payment): bool
    {
        $invoice = $payment->invoice;

        if (!$invoice) {
            return false;
        }

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
