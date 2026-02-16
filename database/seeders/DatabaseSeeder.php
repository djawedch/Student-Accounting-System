<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UniversitySeeder::class,
            DepartmentSeeder::class,
            UserSeeder::class,
            StudentSeeder::class,
            FeeSeeder::class,
            InvoiceSeeder::class,
            PaymentSeeder::class,
            ScholarshipSeeder::class,
            StudentScholarshipSeeder::class,
            AuditLogSeeder::class,
        ]);
    }
}
