<?php
namespace Database\Factories;

use App\Models\Scholarship;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScholarshipFactory extends Factory
{
    protected $model = Scholarship::class;

    public function definition()
    {
        $scholarships = [
            'Merit Scholarship'          => fake()->randomFloat(2, 30000, 100000),
            'Social Aid'                 => fake()->randomFloat(2, 10000, 40000),
            'Research Grant'             => fake()->randomFloat(2, 50000, 150000),
            'Excellence Award'           => fake()->randomFloat(2, 40000, 120000),
            'Need-Based Aid'             => fake()->randomFloat(2, 15000, 50000),
            'Ministry Scholarship'       => fake()->randomFloat(2, 20000, 80000),
            'International Exchange'     => fake()->randomFloat(2, 60000, 200000),
            'Disability Support Grant'   => fake()->randomFloat(2, 10000, 40000),
            'Cultural Achievement Award' => fake()->randomFloat(2, 20000, 60000),
            'Sports Excellence Grant'    => fake()->randomFloat(2, 15000, 50000),
            'Academic Distinction Award' => fake()->randomFloat(2, 25000, 90000),
            'Innovation Grant'           => fake()->randomFloat(2, 40000, 130000),
            'Leadership Scholarship'     => fake()->randomFloat(2, 30000, 100000),
            'Community Service Award'    => fake()->randomFloat(2, 15000, 55000),
            'STEM Excellence Grant'      => fake()->randomFloat(2, 35000, 110000),
        ];

        $name   = fake()->unique()->randomElement(array_keys($scholarships));
        $amount = $scholarships[$name];

        return [
            'name'        => $name,
            'description' => fake()->optional()->paragraph(),
            'amount'      => $amount,
            'created_at'  => now(),
            'updated_at'  => now(),
        ];
    }
}