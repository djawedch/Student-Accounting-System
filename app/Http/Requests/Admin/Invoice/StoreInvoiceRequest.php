<?php
namespace App\Http\Requests\Admin\Invoice;

use App\Models\{Student, Fee};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!in_array($user->role, ['super_admin', 'university_admin', 'department_admin', 'staff_admin'])) {
            return false;
        }

        if ($this->student_ids) {
            foreach ($this->student_ids as $studentId) {
                $student = Student::with('user')->find($studentId);
                if (!$student) continue;

                if ($user->role === 'university_admin' && $student->user->university_id !== $user->university_id) {
                    return false;
                }
                if (in_array($user->role, ['department_admin', 'staff_admin']) && $student->user->department_id !== $user->department_id) {
                    return false;
                }
            }
        }

        if ($this->fee_ids) {
            foreach ($this->fee_ids as $feeId) {
                $fee = Fee::with('department')->find($feeId);
                if (!$fee) continue;

                if ($user->role === 'university_admin' && $fee->department->university_id !== $user->university_id) {
                    return false;
                }
                if (in_array($user->role, ['department_admin', 'staff_admin']) && $fee->department_id !== $user->department_id) {
                    return false;
                }
            }
        }

        return true;
    }

    public function rules(): array
    {
        return [
            'student_ids'   => 'required|array|min:1',
            'student_ids.*' => 'required|exists:students,id',
            'fee_ids'       => 'required|array|min:1',
            'fee_ids.*'     => 'required|exists:fees,id',
            'issued_date'   => 'required|date',
            'due_date'      => 'required|date|after_or_equal:issued_date',
        ];
    }
}