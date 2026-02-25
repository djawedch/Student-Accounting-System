<?php

namespace App\Http\Requests\Admin\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:unpaid,partially_paid,paid,overdue',
            'issued_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issued_date',
        ];
    }
}
