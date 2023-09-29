<?php

namespace Tests\Feature;

use App\Models\Challenge;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\ParticipantTestCase;

use function PHPUnit\Framework\assertEquals;

class GetSubmissionsForParticipantTest extends ParticipantTestCase
{
    private $endpoint = '/api/participant/submission';

    /**
     * A feature test for getting submissions for authenticated participant.
     *
     * @return void
     */
    public function test_get_submissions_for_authenticated_participant()
    {
        $this->participant->track->is_locked = false;
        $this->participant->track->save();

        $challenge = Challenge::factory()->create();
        $challenge->track()->associate($this->participant->track);

        $submission1 = Submission::create([
            'challenge_id' => $challenge->id,
            'participant_id' => $this->participant->id,
            'track_id' => $this->participant->track->id,
            'status' => 'approved',
            'assigned_points' => rand(10, 300)
        ]);

        // another submission for another participant
        $participant2 = User::factory()->create();
        $participant2->track()->associate($this->participant->track);
        $submission2 = Submission::create([
            'challenge_id' => $challenge->id,
            'participant_id' => $participant2->id,
            'track_id' => $participant2->track->id,
            'status' => 'approved',
            'assigned_points' => rand(10, 300)
        ]);
        $response = $this->getJson($this->endpoint);

        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure([
            'success',
            'data' => [
                '*' => [
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
        $this->assertEquals(1, count($response['data']));
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('message', 'Succefully retrieved all previous submissions!');

        $retrievedSubmission = $response["data"][0];
        $this->assertEquals($this->participant->track->type, $retrievedSubmission["track"]);
        $this->assertEquals($challenge->name, $retrievedSubmission["challenge"]["name"]);
        $this->assertEquals($submission1->status, $retrievedSubmission["status"]);
        $this->assertEquals($submission1->assigned_points, $retrievedSubmission["assigned_points"]);
    }
}
