<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Invoice, Payment};

class PaymentSeeder extends Seeder
{
    public function run()
    {
        Invoice::with('fee')->each(function ($invoice) {
            $numPayments  = rand(0, 3);
            $remaining    = $invoice->fee->amount;

            for ($i = 0; $i < $numPayments; $i++) {
                if ($remaining <= 0) break;

                $amount = round(fake()->randomFloat(2, 100, min($remaining, $invoice->fee->amount / 2)), 2);

                Payment::create([
                    'invoice_id'     => $invoice->id,
                    'payment_method' => fake()->randomElement(['cash', 'bank_transfer', 'ccp']),
                    'amount'         => $amount,
                    'reference'      => fake()->optional()->uuid(),
                    'payment_date'   => fake()->dateTimeBetween('-6 months', 'now'),
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);

                $remaining -= $amount;
            }
        });
    }
}