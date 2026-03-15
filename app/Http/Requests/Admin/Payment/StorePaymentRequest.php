<?php

namespace App\Http\Requests\Admin\Payment;

use App\Models\Invoice;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = Auth::user();

        return [
            'invoice_id' => [
                'required',
                'exists:invoices,id',
                function ($attribute, $value, $fail) use ($user) {
                    $invoice = Invoice::with('student.user')->find($value);

                    if (!$invoice) {
                        $fail('Invalid invoice.');
                        return;
                    }

                    $student = $invoice->student;

                    if (!$student) {
                        $fail('Invoice has no student.');
                        return;
                    }

                    if ($user->role === 'university_admin' && $student->user->university_id !== $user->university_id) {
                        $fail('You cannot record payments for invoices outside your university.');
                    }

                    if (in_array($user->role, ['department_admin', 'staff_admin']) && $student->user->department_id !== $user->department_id) {
                        $fail('You cannot record payments for invoices outside your department.');
                    }
                }
            ],
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,bank_transfer,ccp',
            'reference' => 'nullable|string|max:255',
            'payment_date' => 'required|date',
        ];
    }
}
