<?php

namespace Database\Factories;

use App\Models\{Department, User};
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'department_id' => Department::factory(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'date_of_birth' => $this->faker->date('Y-m-d', '2008-01-01'),
            'role' => $this->faker->randomElement(['super_admin', 'university_admin', 'department_admin', 'staff_admin', 'student']),
            'is_active' => true,
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    // State for specific roles
    public function superAdmin()
    {
        return $this->state(['role' => 'super_admin']);
    }

    public function universityAdmin()
    {
        return $this->state(['role' => 'university_admin']);
    }

    public function departmentAdmin()
    {
        return $this->state(['role' => 'department_admin']);
    }

    public function staffAdmin()
    {
        return $this->state(['role' => 'staff_admin']);
    }

    public function student()
    {
        return $this->state(['role' => 'student']);
    }
}
