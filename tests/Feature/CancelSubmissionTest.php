<?php

namespace Tests\Feature;

use App\Models\Challenge;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\ParticipantTestCase;

class CancelSubmissionTest extends ParticipantTestCase
{
    private $endpoint = '/api/participant/submission/';

    /**
     * A feature test for cancelling a submission.
     *
     * @return void
     */
    public function test_cancel_submission()
    {
        $challenge = Challenge::factory()->create();
        $challenge->track()->associate($this->participant->track);
        $submission = Submission::create([
            'challenge_id' => $challenge->id,
            'participant_id' => $this->participant->id,
            'track_id' => $this->participant->track->id,
            'status' => 'pending',
        ]);


        $response = $this->postJson($this->endpoint.$submission->id.'/cancel');

        $response->assertStatus(Response::HTTP_OK)->assertExactJson([
            'success' => true,
            'data' => [],
            'message' => 'Submission was successfully canceled!'
        ]);

        $this->assertDatabaseHas('submissions', [
            'challenge_id' => $submission->challenge_id,
            'participant_id' => $submission->participant_id,
            'status' => 'canceled'
        ]);

        $this->assertDatabaseMissing('submissions', [
            'challenge_id' => $submission->challenge_id,
            'participant_id' => $submission->participant_id,
            'status' => 'pending'
        ]);
    }

    /**
     * A feature test for cancelling a submission that does not exist.
     *
     * @return void
     */
    public function test_cancel_submission_that_does_not_exist()
    {
        $response = $this->postJson($this->endpoint.'1000/cancel');

        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertExactJson([
            'success' => false,
            'message' => 'Submission can not be found!'
        ]);
    }

    /**
     * A feature test for cancelling a submission that does not belong to authenticated participant.
     *
     * @return void
     */
    public function test_cancel_submission_that_does_not_belong_to_participant()
    {
        $challenge = Challenge::factory()->create();
        $challenge->track()->associate($this->participant->track);
        $participant2 = User::factory()->create();
        $participant2->track()->associate($this->participant->track);
        $submission = Submission::create([
            'challenge_id' => $challenge->id,
            'participant_id' => $participant2->id,
            'track_id' => $this->participant->track->id,
            'status' => 'pending',
        ]);


        $response = $this->postJson($this->endpoint.$submission->id.'/cancel');

        $response->assertStatus(Response::HTTP_FORBIDDEN)->assertExactJson([
            'success' => false,
            'message' => 'Unauthorized'
        ]);
    }

    /**
     * A feature test for cancelling a submission that does not have `pending` status.
     *
     * @return void
     */
    public function test_cancel_submission_that_has_not_pending_status()
    {
        $challenge = Challenge::factory()->create();
        $challenge->track()->associate($this->participant->track);
        $submission = Submission::create([
            'challenge_id' => $challenge->id,
            'participant_id' => $this->participant->id,
            'track_id' => $this->participant->track->id,
            'status' => 'approved',
        ]);


        $response = $this->postJson($this->endpoint.$submission->id.'/cancel');

        $response->assertStatus(Response::HTTP_FORBIDDEN)->assertExactJson([
            'success' => false,
            'message' => 'This action can be made for submissions that have status pending'
        ]);
    }
}
