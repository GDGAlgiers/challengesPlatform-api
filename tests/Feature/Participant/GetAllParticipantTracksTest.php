<?php

namespace Tests\Feature;

use App\Models\Track;
use Tests\ParticipantTestCase;

class GetAllParticipantTracksTest extends ParticipantTestCase
{
    private $endpoint = '/api/participant/track';
    /**
     * A feature test for getting all tracks.
     *
     * @return void
     */
    public function test_get_all_tracks()
    {
        $tracks = Track::factory()->count(2)->create();

        $response = $this->getJson($this->endpoint);

        $response->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => [
                '*' => [
                    'id',
                    'type',
                    'description',
                    'number_of_challenges',
                    'is_locked'
                ]
            ],
            'message'
        ])->assertJsonPath('success', true);

        foreach ($tracks as $track) {
            $this->assertTrue(in_array([
                'id' => $track->id,
                'type' => $track->type,
                'description' => $track->description,
                'number_of_challenges' => count($track->challenges),
                'is_locked' => $track->is_locked ? true: false
            ], $response['data']));
        }
    }
}
