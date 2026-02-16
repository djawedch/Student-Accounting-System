<?php

namespace Database\Factories;

use App\Models\{University, Department};
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    public function definition()
    {
        return [
            'university_id' => University::factory(),
            'name' => $this->faker->randomElement([
                'Computer Science',
                'Mathematics',
                'Physics',
                'Chemistry',
                'Biology',
                'Economics'
            ]) . ' Department',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
