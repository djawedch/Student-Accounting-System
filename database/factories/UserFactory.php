<?php
namespace Database\Factories;

use App\Models\{University, Department, User};
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;
    protected static ?string $password = null;

    public function definition()
    {
        $department = Department::with('university')->inRandomOrder()->first();

        return [
            'university_id' => $department?->university_id,
            'department_id' => $department?->id,
            'first_name'    => fake()->firstName(),
            'last_name'     => fake()->lastName(),
            'email'         => fake()->unique()->safeEmail(),
            'password'      => static::$password ??= Hash::make('password'),
            'date_of_birth' => fake()->date('Y-m-d', '2000-01-01'),
            'role'          => 'staff_admin',
            'is_active'     => true,
            'created_at'    => now(),
            'updated_at'    => now(),
        ];
    }

    public function superAdmin()
    {
        return $this->state([
            'role'          => 'super_admin',
            'university_id' => null,
            'department_id' => null,
        ]);
    }

    public function universityAdmin(int $universityId)
    {
        return $this->state([
            'role'          => 'university_admin',
            'university_id' => $universityId,
            'department_id' => null,
        ]);
    }

    public function departmentAdmin(int $departmentId, int $universityId)
    {
        return $this->state([
            'role'          => 'department_admin',
            'university_id' => $universityId,
            'department_id' => $departmentId,
        ]);
    }

    public function staffAdmin(int $departmentId, int $universityId)
    {
        return $this->state([
            'role'          => 'staff_admin',
            'university_id' => $universityId,
            'department_id' => $departmentId,
        ]);
    }

    public function student(int $departmentId, int $universityId)
    {
        return $this->state([
            'role'          => 'student',
            'university_id' => $universityId,
            'department_id' => $departmentId,
        ]);
    }
}