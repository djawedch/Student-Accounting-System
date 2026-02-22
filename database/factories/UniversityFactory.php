<?php

namespace Database\Factories;

use App\Models\University;
use Illuminate\Database\Eloquent\Factories\Factory;

class UniversityFactory extends Factory
{
    protected $model = University::class;

    public function definition()
    {
        return [
            'name' => fake()->unique()->company() . ' University',
            'city' => fake()->city(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
