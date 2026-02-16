<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{User, Student};

class StudentSeeder extends Seeder
{
    public function run()
    {
        User::where('role', 'student')->each(function ($user) {
            Student::factory()->create([
                'user_id' => $user->id,
            ]);
        });
    }
}
