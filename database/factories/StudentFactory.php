<?php
namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition()
    {
        return [
            'user_id'            => null, // always passed explicitly from seeder
            'academic_year'      => fake()->randomElement(['2023-2024', '2024-2025', '2025-2026']),
            'baccalaureate_year' => fake()->numberBetween(2018, 2024),
            'study_system'       => fake()->randomElement(['LMD', 'Classic']),
            'level'              => fake()->randomElement(['L1', 'L2', 'L3', 'M1', 'M2']),
            'created_at'         => now(),
            'updated_at'         => now(),
        ];
    }
}