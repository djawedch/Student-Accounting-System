<?php

namespace Database\Factories;

use App\Models\{Department, Fee};
use Illuminate\Database\Eloquent\Factories\Factory;

class FeeFactory extends Factory
{
    protected $model = Fee::class;

    public function definition()
    {
        return [
            'department_id' => Department::inRandomOrder()->first()?->id ?? Department::factory(),
            'name' => $this->faker->randomElement(['Tuition Fee', 'Library Fee', 'Sports Fee', 'Lab Fee', 'Registration Fee']),
            'amount' => $this->faker->randomFloat(2, 5000, 50000),
            'academic_year' => $this->faker->randomElement(['2023-2024', '2024-2025', '2025-2026']),
            'description' => $this->faker->optional()->sentence(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
