<?php

namespace Tests\Feature;

use App\Models\Challenge;
use App\Models\Track;
use Illuminate\Http\Response;
use Tests\ParticipantTestCase;

class GetChallengeTest extends ParticipantTestCase
{
    private $endpoint = '/api/participant/challenge/';

    /**
     * A feature test for getting a challenge.
     *
     * @return void
     */
    public function test_get_challenge()
    {
        $this->participant->track->is_locked = false;
        $this->participant->track->save();

        $challenge = Challenge::factory()->create();
        $challenge->track()->associate($this->participant->track);

        $response = $this->getJson($this->endpoint.$challenge->id);

        $response->assertStatus(Response::HTTP_OK)->assertExactJson([
            'success' => true,
            'data' => [
                'track' => $this->participant->track->type,
                'name' => $challenge->name,
                'author' => $challenge->author,
                'difficulty' => $challenge->difficulty,
                'description' => $challenge->description,
                'points' => $challenge->points,
                'has_attachment' => $challenge->attachment ? true: false,
                'external_resource' => $challenge->external_resource,
                'max_tries' => $challenge->max_tries,
                'requires_judge' => $challenge->requires_judge ? true: false,
                'is_locked' => $challenge->is_locked ? true: false
            ],
            'message' => 'Succefully retrieved the challenge'
        ]);
    }

    /**
     * A feature test for getting a challenge that is locked.
     *
     * @return void
     */
    public function test_get_challenge_that_is_locked()
    {
        $this->participant->track->is_locked = false;
        $this->participant->track->save();

        $challenge = Challenge::factory()->create(['is_locked' => 1]);
        $challenge->track()->associate($this->participant->track);

        $response = $this->getJson($this->endpoint.$challenge->id);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson([
            'success' => false,
            'message' => 'This challenge is locked for now'
        ]);
    }

    /**
     * A feature test for getting a challenge that its track is locked.
     *
     * @return void
     */
    public function test_get_challenge_that_its_track_is_locked()
    {
        $challenge = Challenge::factory()->create();
        $challenge->track()->associate($this->participant->track);

        $response = $this->getJson($this->endpoint.$challenge->id);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson([
            'success' => false,
            'message' => 'Submissions can not be accepted now'
        ]);
    }

    /**
     * A feature test for getting a challenge that doesn't belong to participant's track.
     *
     * @return void
     */
    public function test_get_challenge_that_can_not_be_viewed_by_participant()
    {
        $track = Track::factory()->create(['is_locked' => 0]);
        $challenge = Challenge::factory()->create();
        $challenge->track()->associate($track);

        $response = $this->getJson($this->endpoint.$challenge->id);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson([
            'success' => false,
            'message' => 'You can not view this challenge!'
        ]);
    }

    /**
     * A feature test for getting a challenge that doesn't exit.
     *
     * @return void
     */
    public function test_get_challenge_that_does_not_exist()
    {
        $response = $this->getJson($this->endpoint."5000");

        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson([
            'success' => false,
            'message' => 'Challenge can not be found'
        ]);
    }
}
