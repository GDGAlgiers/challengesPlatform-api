<?php

namespace Tests\Feature;

use App\Models\Challenge;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\ParticipantTestCase;

class GetChallengeSubmissionsTest extends ParticipantTestCase
{
    private $endpoint = '/api/participant/challenge/';

    /**
     * A feature test for getting participant's submissions for a challenge.
     *
     * @return void
     */
    public function test_get_participant_challenge_submissions()
    {
        $challenge = Challenge::factory()->create();
        $challenge->track()->associate($this->participant->track);

        $submission = Submission::create([
            'challenge_id' => $challenge->id,
            'participant_id' => $this->participant->id,
            'track_id' => $this->participant->track->id,
            'status' => 'approved',
            'assigned_points' => rand(10, 200)
        ]);

        // submission of another challenge
        $challenge2 = Challenge::factory()->create();
        $challenge2->track()->associate($this->participant->track);
        Submission::create([
            'challenge_id' => $challenge2->id,
            'participant_id' => $this->participant->id,
            'track_id' => $this->participant->track->id,
            'status' => 'rejected',
            'assigned_points' => rand(10, 200)
        ]);

        // submission of another participant for the same challenge
        $participant2 = User::factory()->create();
        $participant2->track()->associate($this->participant->track);
        Submission::create([
            'challenge_id' => $challenge->id,
            'participant_id' => $participant2->id,
            'track_id' => $participant2->track->id,
            'status' => 'approved',
            'assigned_points' => rand(10, 200)
        ]);

        $response = $this->getJson($this->endpoint.$challenge->id.'/submissions');

        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure([
            'success',
            'data' => [
                '*' => [
                    'id',
                    'track',
                    'challenge',
                    'included_attachment',
                    'status',
                    'assigned_points',
                    'submitted_at'
                ]
            ],
            'message'
        ]);

        $this->assertTrue($response['success']);
        $this->assertEquals('Succefully retrieved all submissions', $response['message']);

        $returnedData = $response['data'][0];
        $this->assertEquals(1, count($response['data']));
        $this->assertEquals($this->participant->track->type, $returnedData['track']);
        $this->assertEquals([
            'id' => $challenge->id,
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
        ], $returnedData['challenge']);
        $this->assertFalse($returnedData['included_attachment']);
        $this->assertEquals($submission->status, $returnedData['status']);
        $this->assertEquals($submission->assigned_points, $returnedData['assigned_points']);
    }
}
