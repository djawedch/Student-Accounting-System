<?php
namespace Database\Factories;

use App\Models\{Student, Scholarship, ScholarshipAward};
use Illuminate\Database\Eloquent\Factories\Factory;

class ScholarshipAwardFactory extends Factory
{
    protected $model = ScholarshipAward::class;

    public function definition()
    {
        $grantDate = fake()->dateTimeBetween('-2 years', 'now');
        $endDate   = (clone $grantDate)->modify('+1 year');
        $status    = fake()->randomElement(['awarded', 'paid', 'cancelled']);

        return [
            'student_id'     => Student::inRandomOrder()->first()->id,
            'scholarship_id' => Scholarship::inRandomOrder()->first()->id,
            'grant_date'     => $grantDate,
            'end_date'       => $endDate,
            'status'         => $status,
            'paid_at'        => $status === 'paid' ? fake()->dateTimeBetween($grantDate, 'now') : null,
            'reference'      => fake()->optional()->uuid(),
            'created_at'     => $grantDate,
            'updated_at'     => $grantDate,
        ];
    }
}