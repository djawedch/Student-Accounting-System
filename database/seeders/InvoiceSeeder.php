<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Student, Fee, Invoice};

class InvoiceSeeder extends Seeder
{
    public function run()
    {
        Student::all()->each(function ($student) {
            $fees = Fee::where('department_id', $student->user->department_id)->get();
            if ($fees->count()) {
                foreach ($fees->random(min(3, $fees->count())) as $fee) {
                    Invoice::factory()->create([
                        'student_id' => $student->id,
                        'fee_id' => $fee->id,
                    ]);
                }
            }
        });
    }
}
