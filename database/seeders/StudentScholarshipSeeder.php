<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Student, Scholarship, StudentScholarship};

class StudentScholarshipSeeder extends Seeder
{
    public function run()
    {
        Student::all()->random(8)->each(function ($student) {
            $scholarships = Scholarship::inRandomOrder()->take(rand(1, 3))->get();
            foreach ($scholarships as $scholarship) {
                StudentScholarship::factory()->create([
                    'student_id' => $student->id,
                    'scholarship_id' => $scholarship->id,
                ]);
            }
        });
    }
}
