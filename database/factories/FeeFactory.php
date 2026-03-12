<?php
namespace Database\Factories;

use App\Models\{Department, Fee};
use Illuminate\Database\Eloquent\Factories\Factory;

class FeeFactory extends Factory
{
    protected $model = Fee::class;

    public function definition()
    {
        $feeTypes = [
            'Tuition Fee'      => fake()->randomFloat(2, 20000, 80000),
            'Library Fee'      => fake()->randomFloat(2, 1000, 5000),
            'Sports Fee'       => fake()->randomFloat(2, 1000, 3000),
            'Lab Fee'          => fake()->randomFloat(2, 5000, 15000),
            'Registration Fee' => fake()->randomFloat(2, 2000, 8000),
            'Exam Fee'         => fake()->randomFloat(2, 3000, 10000),
            'Insurance Fee'    => fake()->randomFloat(2, 1000, 4000),
        ];

        $name   = fake()->randomElement(array_keys($feeTypes));
        $amount = $feeTypes[$name];

        return [
            'department_id' => Department::inRandomOrder()->first()?->id ?? Department::factory(),
            'name'          => $name,
            'amount'        => $amount,
            'academic_year' => fake()->randomElement(['2023-2024', '2024-2025', '2025-2026']),
            'description'   => fake()->optional()->sentence(),
            'created_at'    => now(),
            'updated_at'    => now(),
        ];
    }
}