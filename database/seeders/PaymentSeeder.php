<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Invoice, Payment};

class PaymentSeeder extends Seeder
{
    public function run()
    {
        // For some invoices, create 0-3 payments (partial payments)
        Invoice::all()->each(function ($invoice) {
            $numPayments = rand(0, 3);
            for ($i = 0; $i < $numPayments; $i++) {
                Payment::factory()->create([
                    'invoice_id' => $invoice->id,
                ]);
            }
        });
    }
}
