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
            'user_id'    => User::where('role', '!=', 'student')->inRandomOrder()->first()->id,
            'event_type' => fake()->randomElement(['create', 'update', 'delete']),
            'model_type' => fake()->randomElement([
                'University',
                'Department',
                'User',
                'Student',
                'Fee',
                'Invoice',
                'Payment',
                'Scholarship',
                'StudentScholarship',
            ]),
            'model_id'   => fake()->numberBetween(1, 100),
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}