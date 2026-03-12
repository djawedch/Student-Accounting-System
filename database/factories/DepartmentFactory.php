<?php
namespace Database\Factories;

use App\Models\{University, Department};
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    public function definition(): array
    {
        $departments = [
            'Computer Science',
            'Mathematics',
            'Physics',
            'Chemistry',
            'Biology',
            'Civil Engineering',
            'Electrical Engineering',
            'Mechanical Engineering',
            'Architecture',
            'Economics',
            'Management',
            'Law',
            'Arabic Literature',
            'French Literature',
            'History',
            'Geography',
            'Sociology',
            'Psychology',
            'Medicine',
            'Pharmacy',
        ];

        return [
            'university_id' => University::inRandomOrder()->first()?->id ?? University::factory(),
            'name'          => fake()->randomElement($departments),
            'created_at'    => now(),
            'updated_at'    => now(),
        ];
    }
}