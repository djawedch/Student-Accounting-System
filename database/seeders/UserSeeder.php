<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{University, Department, User};
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $password = Hash::make('password'); // hash once

        // Super Admin — no university or department
        User::factory()->superAdmin()->create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'superadmin@example.com',
            'password' => $password,
        ]);

        // One university_admin per university
        University::all()->each(function ($university) use ($password) {
            User::factory()->universityAdmin($university->id)->create([
                'first_name' => 'Admin',
                'last_name' => $university->name,
                'email' => 'univadmin.' . $university->id . '@example.com',
                'password' => $password,
            ]);

            // 5 departments per university — already seeded
            Department::where('university_id', $university->id)->each(function ($department) use ($password, $university) {

                // One department_admin per department
                User::factory()->departmentAdmin($department->id, $university->id)->create([
                    'first_name' => 'Dept',
                    'last_name' => $department->name,
                    'email' => 'deptadmin.' . $department->id . '@example.com',
                    'password' => $password,
                ]);

                // One staff_admin per department
                User::factory()->staffAdmin($department->id, $university->id)->create([
                    'first_name' => 'Staff',
                    'last_name' => $department->name,
                    'email' => 'staff.' . $department->id . '@example.com',
                    'password' => $password,
                ]);

                // 10 students per department
                for ($i = 1; $i <= 10; $i++) {
                    User::factory()->student($department->id, $university->id)->create([
                        'email' => 'student.' . $department->id . '.' . $i . '@example.com',
                        'password' => $password,
                    ]);
                }
            });
        });
    }
}