<?php

namespace Database\Factories;

use App\Models\Track;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Challenge>
 */
class ChallengeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $trackID = Track::InRandomOrder()->limit(1)->pluck('id');
        return [
            'track_id' => $trackID[0],
            'name' => fake()->name(),
            'author' => fake()->name(),
            'description' => fake()->text(30),
            'difficulty' => 'easy',
            'points' => rand(10, 500),
            'max_tries' => fake()->randomDigit(),
            'requires_judge' => false,
            'solution' => Hash::make(fake()->word()),
        ];
    }
}
