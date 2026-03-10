<?php

namespace App\Http\Requests\Admin\Invoice;

use App\Models\{Student, Fee};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = Auth::user();

        return [
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => [
                'required',
                'exists:students,id',
                function ($attribute, $value, $fail) use ($user) {
                    $student = Student::with('user')->find($value);
                    if (!$student) {
                        $fail('Invalid student.');
                        return;
                    }
                    if ($user->role === 'university_admin' && $student->user->university_id !== $user->university_id) {
                        $fail('You cannot create invoices for students outside your university.');
                    }
                    if (in_array($user->role, ['department_admin', 'staff_admin']) && $student->user->department_id !== $user->department_id) {
                        $fail('You cannot create invoices for students outside your department.');
                    }
                }
            ],
            'fee_ids' => 'required|array|min:1',
            'fee_ids.*' => [
                'required',
                'exists:fees,id',
                function ($attribute, $value, $fail) use ($user) {
                    $fee = Fee::with('department')->find($value);
                    if (!$fee) {
                        $fail('Invalid fee.');
                        return;
                    }
                    if ($user->role === 'university_admin' && $fee->department->university_id !== $user->university_id) {
                        $fail('You cannot use fees outside your university.');
                    }
                    if (in_array($user->role, ['department_admin', 'staff_admin']) && $fee->department_id !== $user->department_id) {
                        $fail('You cannot use fees outside your department.');
                    }
                }
            ],
            'issued_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issued_date',
        ];
    }
}
