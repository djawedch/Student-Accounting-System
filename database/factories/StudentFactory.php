<?php

namespace Database\Factories;

use App\Models\{User, Student};
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition()
    {
        return [
            'user_id' => User::factory()->student(),
            'academic_year' => $this->faker->randomElement(['2023-2024', '2024-2025', '2025-2026']),
            'baccalaureate_year' => $this->faker->numberBetween(2020, 2024),
            'study_system' => $this->faker->randomElement(['LMD', '3 years classic', '4 years classic', '5 years classic', '6 years classic', '7 years classic']),
            'level' => $this->faker->randomElement(['L1', 'L2', 'L3', 'M1', 'M2']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
