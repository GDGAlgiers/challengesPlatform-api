<?php

namespace Tests\Feature;

use App\Models\Track;
use Illuminate\Http\Response;
use Tests\AdminTestCase;

class UpdateTrackTest extends AdminTestCase
{
    private $endpoint = '/api/admin/track/';

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

        $response = $this->postJson($this->endpoint.$track->id.'/update', $payload);

        $response->assertStatus(Response::HTTP_OK)->assertExactJson([
            'success' => true,
            'data' => [
                'id' => $track->id,
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

        $response = $this->postJson($this->endpoint.$track->id.'/update', $payload);

        $response->assertStatus(Response::HTTP_OK)->assertExactJson([
            'success' => true,
            'data' => [
                'id' => $track->id,
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
