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
                    'type',
                    'description',
                    'number_of_challenges',
                    'is_locked'
                ]
            ],
            'message'
        ])->assertJsonPath('success', true);

    }
}
