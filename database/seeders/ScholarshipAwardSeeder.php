<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Student, Scholarship, ScholarshipAward};

class ScholarshipAwardSeeder extends Seeder
{
    public function run()
    {
        Student::inRandomOrder()->take(50)->get()->each(function ($student) {
            $scholarships = Scholarship::inRandomOrder()->take(rand(1, 3))->get();

            foreach ($scholarships as $scholarship) {
                ScholarshipAward::factory()->create([
                    'student_id'     => $student->id,
                    'scholarship_id' => $scholarship->id,
                ]);
            }
        });
    }
}
