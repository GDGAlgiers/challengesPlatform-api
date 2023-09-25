<?php

namespace Tests\Feature;

use App\Models\Track;
use App\Models\User;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateTrackTest extends TestCase
{
    private $endpoint = '/api/admin/track/update/';

    /**
     * A feature test for updating a track.
     *
     * @return void
     */
    public function test_update_track()
    {
        $track = Track::factory()->create();
        $payload = [
            'description' => $this->faker->text(30)
        ];

        Sanctum::actingAs(User::factory()->create(['role' => 'admin']), ['*']);


        $response = $this->postJson($this->endpoint.$track->id, $payload);

        $response->assertStatus(Response::HTTP_OK)->assertExactJson([
            'success' => true,
            'data' => [
                'type' => $track->type,
                'description' => $payload['description'],
                'number_of_challenges' => count($track->challenges),
                'is_locked' => $track->is_locked ? true:false
            ],
            'message' => 'Successfully updated the track!'
        ]);

        $this->assertDatabaseMissing('tracks', [
            'type' => $track->type,
            'description' => $track->description
        ]);
        $this->assertDatabaseHas('tracks', [
            'type' => $track->type,
            'description' => $payload['description']
        ]);
    }

    /**
     * A feature test for updating a track's type.
     *
     * @return void
     */
    public function test_update_track_type()
    {
        $track = Track::factory()->create();
        $payload = [
            'type' => $this->faker->word(),
            'description' => $this->faker->text(30)
        ];

        Sanctum::actingAs(User::factory()->create(['role' => 'admin']), ['*']);


        $response = $this->postJson($this->endpoint.$track->id, $payload);

        $response->assertStatus(Response::HTTP_OK)->assertExactJson([
            'success' => true,
            'data' => [
                'type' => $track->type,
                'description' => $payload['description'],
                'number_of_challenges' => count($track->challenges),
                'is_locked' => $track->is_locked ? true:false
            ],
            'message' => 'Successfully updated the track!'
        ]);

        $this->assertDatabaseMissing('tracks', [
            'type' => $payload['type'],
            'description' => $track->description
        ]);
        $this->assertDatabaseHas('tracks', [
            'type' => $track->type,
            'description' => $payload['description']
        ]);
    }
}
