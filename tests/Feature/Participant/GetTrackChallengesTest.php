<?php

namespace Tests\Feature;

use App\Models\Challenge;
use App\Models\Track;
use Illuminate\Http\Response;
use Tests\ParticipantTestCase;

class GetTrackChallengesTest extends ParticipantTestCase
{
    private $endpoint = '/api/participant/track/';

    /**
     * A feature test for getting a track challenges
     *
     * @return void
     */
    public function test_get_track_challenges()
    {
        $this->participant->track->is_locked = false;
        $this->participant->track->save();

        $challenges = Challenge::factory()->count(2)->create(['track_id' => $this->participant->track->id]);

        $response = $this->getJson($this->endpoint.$this->participant->track->id.'/challenges');
        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure([
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
                ],
            'message'
        ]);

        $this->assertEquals(2, count($response['data']));
        foreach($challenges as $challenge) {
            $this->assertTrue(in_array([
                'id' => $challenge->id,
                'track' => $this->participant->track->type,
                'name' => $challenge->name,
                'author' => $challenge->author,
                'difficulty' => $challenge->difficulty,
                'description' => $challenge->description,
                'points' => $challenge->points,
                'has_attachment' => $challenge->attachment ? true:false,
                'external_resource' => $challenge->external_resource,
                'max_tries' => $challenge->max_tries,
                'requires_judge' => $challenge->require_judge ? true: false,
                'is_locked' => $challenge->is_locked ? true: false,
            ], $response['data']));
        }
    }

    /**
     * A feature test for getting challenges of track that is locked
     *
     * @return void
     */
    public function test_get_challenges_of_track_that_is_locked()
    {
        $challenges = Challenge::factory()->count(2)->create(['track_id' => $this->participant->track->id]);

        $response = $this->getJson($this->endpoint.$this->participant->track->id.'/challenges');
        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson([
            'success' => false,
            'message' => 'The track is locked for now'
        ]);
    }

    /**
     * A feature test for getting challenges of track that does not belong to a participant
     *
     * @return void
     */
    public function test_get_challenges_of_track_that_does_not_belong_to_participant()
    {
        $track = Track::factory()->create(['is_locked' => 0]);

        Challenge::factory()->count(2)->create(['track_id' => $track->id]);

        $response = $this->getJson($this->endpoint.$track->id.'/challenges');
        $response->assertStatus(Response::HTTP_UNAUTHORIZED)->assertExactJson([
            'success' => false,
            'message' => 'You can not get access to this challenge!'
        ]);
    }
}
