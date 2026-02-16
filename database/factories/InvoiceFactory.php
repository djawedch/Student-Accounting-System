<?php

namespace Database\Factories;

use App\Models\{Student, Fee, Invoice};
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition()
    {
        $issued = $this->faker->dateTimeBetween('-1 year', 'now');
        $due = (clone $issued)->modify('+30 days');

        return [
            'student_id' => Student::factory(),
            'fee_id' => Fee::factory(),
            'status' => $this->faker->randomElement(['unpaid', 'partially_paid', 'paid', 'overdue']),
            'issued_date' => $issued,
            'due_date' => $due,
            'created_at' => $issued,
            'updated_at' => $issued,
        ];
    }
}
