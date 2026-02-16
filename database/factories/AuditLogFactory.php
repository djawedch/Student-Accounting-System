<?php

namespace Database\Factories;

use App\Models\{User, AuditLog};
use Illuminate\Database\Eloquent\Factories\Factory;

class AuditLogFactory extends Factory
{
    protected $model = AuditLog::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'event_type' => $this->faker->randomElement(['create', 'update', 'delete']),
            'model_type' => fake()->randomElement([
                'App\Models\University',
                'App\Models\Department',
                'App\Models\User',
                'App\Models\Student',
                'App\Models\Fee',
                'App\Models\Invoice',
                'App\Models\Payment',
                'App\Models\Scholarship',
                'App\Models\StudentScholarship'
            ]),
            'model_id' => $this->faker->numberBetween(1, 1000),
            'ip_address' => $this->faker->ipv4(),
            'user_agent' => $this->faker->userAgent(),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
