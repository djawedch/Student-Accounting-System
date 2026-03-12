<?php
namespace Database\Factories;

use App\Models\{Student, Fee, Invoice};
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition()
    {
        $student = Student::with('user')->inRandomOrder()->first();
        $fee = Fee::where('department_id', $student->user->department_id)->inRandomOrder()->first();

        $issued = fake()->dateTimeBetween('-1 year', 'now');
        $due    = (clone $issued)->modify('+30 days');

        return [
            'student_id'  => $student->id,
            'fee_id'      => $fee?->id ?? Fee::factory(),
            'status'      => fake()->randomElement(['unpaid', 'partially_paid', 'paid', 'overdue']),
            'issued_date' => $issued,
            'due_date'    => $due,
            'created_at'  => $issued,
            'updated_at'  => $issued,
        ];
    }
}