<?php
namespace App\Http\Requests\Admin\Payment;

use App\Models\Invoice;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!in_array($user->role, ['super_admin', 'university_admin', 'department_admin', 'staff_admin'])) {
            return false;
        }

        if ($this->invoice_id) {
            $invoice = Invoice::with('student.user')->find($this->invoice_id);
            if ($invoice && $invoice->student) {
                $student = $invoice->student;
                if ($user->role === 'university_admin' && $student->user->university_id !== $user->university_id) {
                    return false;
                }
                if (in_array($user->role, ['department_admin', 'staff_admin']) && $student->user->department_id !== $user->department_id) {
                    return false;
                }
            }
        }

        return true;
    }

    public function rules(): array
    {
        return [
            'invoice_id'     => 'required|exists:invoices,id',
            'amount'         => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,bank_transfer,ccp',
            'reference'      => 'nullable|string|max:255',
            'payment_date'   => 'required|date',
        ];
    }
}