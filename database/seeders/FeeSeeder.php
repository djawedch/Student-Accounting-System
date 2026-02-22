<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Department, Fee};

class FeeSeeder extends Seeder
{
    public function run()
    {
        Department::all()->each(function ($department) {
            Fee::factory(3)->create([
                'department_id' => $department->id,
            ]);
        });
    }
}
