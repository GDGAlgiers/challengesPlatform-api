<?php

namespace Tests\Feature\Admin\Challenges;

use App\Models\Challenge;
use App\Models\Track;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetAllChallengesTest extends TestCase
{
    private $endpoint = '/api/admin/challenge';
    /**
     * A feature test for getting all challenges.
     *
     * @return void
     */
    public function test_get_all_challengse()
    {
        Track::factory()->count(4)->create();
        $challenges = Challenge::factory()->count(2)->create();

        Sanctum::actingAs(
            User::factory()->create(['role' => 'admin']),
            ['*']
        );

        $response = $this->getJson($this->endpoint);
        $response->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => [
                '*' => [
                    'track',
                    'name',
                    'author',
                    'difficulty',
                    'description',
                    'points',
                    'has_attachment',
                    'external_resource',
                    'max_tries',
                    'requires_judge',
                    'is_locked'
                ]
            ]
        ]);

        $this->assertDatabaseCount('challenges', 2);
        $this->assertDatabaseHas('challenges', [
            'track_id' => $challenges[0]->track_id,
            'name' => $challenges[0]->name,
            'author' => $challenges[0]->author,
            'points' => $challenges[0]->points,
            'max_tries' => $challenges[0]->max_tries,
            'difficulty' => $challenges[0]->difficulty,
        ]);
    }
}
