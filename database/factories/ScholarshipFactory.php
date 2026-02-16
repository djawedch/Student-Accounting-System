<?php

namespace Database\Factories;

use App\Models\Scholarship;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScholarshipFactory extends Factory
{
    protected $model = Scholarship::class;

    public function definition()
    {
        return [
            'name' => $this->faker->randomElement(['Merit Scholarship', 'Social Aid', 'Study Abroad', 'Research Grant', 'Athletic Scholarship']),
            'description' => $this->faker->optional()->paragraph(),
            'amount' => $this->faker->randomFloat(2, 10000, 100000),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
