<?php

namespace Database\Factories;

use App\Models\{Invoice, Payment};
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition()
    {
        return [
            'invoice_id' => Invoice::factory(),
            'payment_method' => $this->faker->randomElement(['cash', 'bank_transfer', 'ccp']),
            'amount' => $this->faker->randomFloat(2, 1000, 50000),
            'reference' => $this->faker->optional()->uuid(),
            'payment_date' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
