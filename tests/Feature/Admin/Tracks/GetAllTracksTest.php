<?php

namespace Tests\Feature;

use App\Models\Challenge;
use App\Models\Track;
use App\Models\User;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetAllTracksTest extends TestCase
{
    private $endpoint = '/api/admin/track';

    /**
     * A feature test for getting all tracks.
     *
     * @return void
     */
    public function test_get_all_tracks()
    {
        $tracks = Track::factory()->count(3)->create();
        $challenge = Challenge::factory()->create();
        $challenge->track()->associate($tracks[0]);

        Sanctum::actingAs(
            User::factory()->create(['role' => 'admin']),
            ['*']
        );

        $response = $this->getJson($this->endpoint);
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'type',
                        'description',
                        'number_of_challenges',
                        'is_locked'
                    ]
                ],
                'message'
            ])
        ;
        $this->assertTrue($response["success"], true);
        $this->assertEquals($response["message"], "Tracks were succefully retreived");
        $this->assertDatabaseCount('tracks', 3);
    }
}
