<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{University, Department};

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        $departmentNames = [
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

        University::all()->each(function ($university) use ($departmentNames) {
            $selected = collect($departmentNames)->shuffle()->take(5);
            foreach ($selected as $name) {
                Department::create([
                    'university_id' => $university->id,
                    'name'          => $name,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        });
    }
}