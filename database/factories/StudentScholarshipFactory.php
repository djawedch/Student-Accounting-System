<?php

namespace Database\Factories;

use App\Models\{Student, Scholarship, StudentScholarship};
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentScholarshipFactory extends Factory
{
    protected $model = StudentScholarship::class;

    public function definition()
    {
        $grantDate = $this->faker->dateTimeBetween('-2 years', 'now');
        $endDate = (clone $grantDate)->modify('+1 year');
        $status = $this->faker->randomElement(['awarded', 'paid', 'cancelled']);

        return [
            'student_id' => Student::factory(),
            'scholarship_id' => Scholarship::factory(),
            'grant_date' => $grantDate,
            'end_date' => $status === 'paid' ? $endDate : null,
            'status' => $status,
            'paid_at' => $status === 'paid' ? $this->faker->dateTimeBetween($grantDate, 'now') : null,
            'reference' => $this->faker->optional()->uuid(),
            'created_at' => $grantDate,
            'updated_at' => $grantDate,
        ];
    }
}
