<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Department, User};

class UserSeeder extends Seeder
{
    public function run()
    {
        User::factory()->superAdmin()->create([
            'email' => 'superadmin@example.com',
            'department_id' => Department::first()->id ?? Department::factory(),
        ]);

        User::factory(3)->universityAdmin()->create();

        Department::all()->each(function ($department) 
        {
            User::factory()->departmentAdmin()->create([
                'department_id' => $department->id,
                'email' => 'admin.' . $department->id . '@example.com',
            ]);

            User::factory(2)->staffAdmin()->create();

            User::factory(10)->student()->create();
        });
    }
}
