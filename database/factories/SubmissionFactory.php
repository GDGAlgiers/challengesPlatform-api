<?php

namespace Database\Factories;

use App\Models\Challenge;
use App\Models\Track;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Submission>
 */
class SubmissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $track = Track::factory()->create();
        $participant = User::factory()->create(['track_id' => $track->id]);
        $challenge = Challenge::factory()->create(['track_id' => $track->id, 'requires_judge' => true]);

        return [
            'challenge_id' => $challenge->id,
            'participant_id' => $participant->id,
            'track_id' => $track->id,
            'status' => 'pending'
        ];
    }
}
