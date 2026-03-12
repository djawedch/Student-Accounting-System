<?php
namespace Database\Factories;

use App\Models\{Invoice, Payment};
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition()
    {
        $invoice = Invoice::with('fee')->inRandomOrder()->first();

        return [
            'invoice_id'     => $invoice->id,
            'payment_method' => fake()->randomElement(['cash', 'bank_transfer', 'ccp']),
            'amount'         => fake()->randomFloat(2, 1000, $invoice->fee->amount),
            'reference'      => fake()->optional()->uuid(),
            'payment_date'   => fake()->dateTimeBetween('-6 months', 'now'),
            'created_at'     => now(),
            'updated_at'     => now(),
        ];
    }
}