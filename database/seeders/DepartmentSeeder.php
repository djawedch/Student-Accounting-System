<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        \App\Models\University::all()->each(function ($university) {
            Department::factory(rand(2, 4))->create([
                'university_id' => $university->id,
            ]);
        });
    }
}
