<?php

namespace Database\Factories;

use App\Models\{University, Department};
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    public function definition(): array
    {
        return [
            'university_id' => University::inRandomOrder()->first()?->id ?? University::factory(),
            'name' => $this->faker->unique()->word() . ' Department',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}