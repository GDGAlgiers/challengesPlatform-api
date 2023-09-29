<?php

namespace Tests\Feature;

use App\Models\Challenge;
use App\Models\Submission;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\ParticipantTestCase;

class SubmitChallengeTest extends ParticipantTestCase
{
    private $endpoint = '/api/participant/challenge/';

    /**
     * A feature test for submitting a submission.
     *
     * @return void
     */
    public function test_submit_challenge_that_does_not_require_judgment()
    {
        $this->participant->track->is_locked = false;
        $this->participant->track->save();

        $challenge = Challenge::factory()->create(['max_tries' => 2, 'solution' => Hash::make('easyOne')]);
        $challenge->track()->associate($this->participant->track);
        $payload = [
            'answer' => 'easyOne'
        ];

        $response = $this->postJson($this->endpoint.$challenge->id.'/submit', $payload);
        $response->assertStatus(Response::HTTP_OK)->assertExactJson([
            'success' => true,
            'message' => "That's right! you've succefully solved this challenge",
            'data' => []
        ]);

        $this->assertDatabaseCount('submissions', 1);
        $submission = Submission::find(1);
        $this->assertEquals($this->participant->id, $submission->participant_id);
        $this->assertEquals($challenge->id, $submission->challenge_id);
        $this->assertEquals($this->participant->track->id, $submission->track_id);
        $this->assertEquals(null, $submission->attachment);
        $this->assertEquals('approved', $submission->status);
        $this->assertEquals($challenge->points, $submission->assigned_points);

        $this->assertEquals($challenge->points, $this->participant->points);

        $this->assertEquals(1, count($this->participant->solves));
        $solve = $this->participant->solves;
        $this->assertEquals($challenge->id, $solve[0]->pivot->challenge_id);
        $this->assertEquals($this->participant->id, $solve[0]->pivot->user_id);

        $locks = DB::select('select * from locks');
        $this->assertEquals(1, count($locks));
        $this->assertEquals($challenge->id, $locks[0]->challenge_id);
        $this->assertEquals($this->participant->id, $locks[0]->user_id);
    }

    /**
     * A feature test for submitting a submission that does not require judgment without providing the answer.
     *
     * @return void
     */
    public function test_submit_challenge_that_does_not_require_judgment_without_sending_answer()
    {
        $this->participant->track->is_locked = false;
        $this->participant->track->save();

        $challenge = Challenge::factory()->create(['max_tries' => 2, 'solution' => Hash::make('easyOne')]);
        $challenge->track()->associate($this->participant->track);
        $payload = [];

        $response = $this->postJson($this->endpoint.$challenge->id.'/submit', $payload);
        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson([
            'success' => false,
            'message' => "Validation failed",
            'data' => [
                'answer' => ['The answer field is required.']
            ]
        ]);
    }

    /**
     * A feature test for submitting a submission of a challenge that its track is locked.
     *
     * @return void
     */
    public function test_submit_challenge_that_its_track_is_locked()
    {
        $this->participant->track->is_locked = false;
        $this->participant->track->save();

        $challenge = Challenge::factory()->create(['max_tries' => 2, 'solution' => Hash::make('easyOne'), 'is_locked' => 1]);
        $challenge->track()->associate($this->participant->track);
        $payload = [
            'answer' => 'easyOne'
        ];

        $response = $this->postJson($this->endpoint.$challenge->id.'/submit', $payload);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson([
            'success' => false,
            'message' => 'This challenge is locked for now'
        ]);

        $this->assertDatabaseEmpty('submissions');
    }

    /**
     * A feature test for submitting a submission of a challenge that is locked.
     *
     * @return void
     */
    public function test_submit_challenge_that_is_locked()
    {

        $challenge = Challenge::factory()->create(['max_tries' => 2, 'solution' => Hash::make('easyOne')]);
        $challenge->track()->associate($this->participant->track);
        $payload = [
            'answer' => 'easyOne'
        ];

        $response = $this->postJson($this->endpoint.$challenge->id.'/submit', $payload);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson([
            'success' => false,
            'message' => 'Submissions can not be accepted now'
        ]);

        $this->assertDatabaseEmpty('submissions');
    }

    /**
     * A feature test for submitting a submission of a challenge that after reached limit.
     *
     * @return void
     */
    public function test_submit_challenge_after_reached_limit()
    {
        $this->participant->track->is_locked = false;
        $this->participant->track->save();

        $challenge = Challenge::factory()->create(['max_tries' => 1, 'solution' => Hash::make('easyOne')]);
        $challenge->track()->associate($this->participant->track);
        $payload = [
            'answer' => 'easyOne'
        ];

        Submission::create([
            'challenge_id' => $challenge->id,
            'participant_id' => $this->participant->id,
            'track_id' => $this->participant->track->id,
            'status' => 'rejected',
            'assigned_points' => 0
        ]);

        $response = $this->postJson($this->endpoint.$challenge->id.'/submit', $payload);

        $response->assertStatus(Response::HTTP_FORBIDDEN)->assertExactJson([
            'success' => false,
            'message' => 'You reached your submissions limit for this challenge'
        ]);

        $this->assertDatabaseCount('submissions', 1);
    }

    /**
     * A feature test for submitting a submission of a locked-submissions challenge.
     *
     * @return void
     */
    public function test_submit_challenge_after_submissions_are_locked()
    {
        $this->participant->track->is_locked = false;
        $this->participant->track->save();

        $challenge = Challenge::factory()->create(['max_tries' => 1, 'solution' => Hash::make('easyOne')]);
        $challenge->track()->associate($this->participant->track);
        $payload = [
            'answer' => 'easyOne'
        ];

        $this->participant->locks()->attach($challenge->id);

        $response = $this->postJson($this->endpoint.$challenge->id.'/submit', $payload);

        $response->assertStatus(Response::HTTP_FORBIDDEN)->assertExactJson([
            'success' => false,
            'message' => "This challenge is locked for you, either it's under judgment or you already solved it"
        ]);
    }

    /**
     * A feature test for submitting a submission with an attachment.
     *
     * @return void
     */
    public function test_submit_challenge_that_requires_judgment()
    {
        $this->participant->track->is_locked = false;
        $this->participant->track->save();

        $challenge = Challenge::factory()->create(['max_tries' => 2, 'solution' => null, 'requires_judge' => 1]);
        $challenge->track()->associate($this->participant->track);
        $payload = [
            'attachment' => $this->faker->text(20)
        ];

        $response = $this->postJson($this->endpoint.$challenge->id.'/submit', $payload);
        $response->assertStatus(Response::HTTP_OK)->assertExactJson([
            'success' => true,
            'message' => "The submission was succefully done, it is under judgment...",
            'data' => []
        ]);

        $this->assertDatabaseCount('submissions', 1);
        $submission = Submission::find(1);
        $this->assertEquals($this->participant->id, $submission->participant_id);
        $this->assertEquals($challenge->id, $submission->challenge_id);
        $this->assertEquals($this->participant->track->id, $submission->track_id);
        $this->assertEquals($payload['attachment'], $submission->attachment);
        $this->assertEquals('pending', $submission->status);
        $this->assertEquals(null, $submission->assigned_points);

        $this->assertEquals(0, $this->participant->points);

        $this->assertEquals(0, count($this->participant->solves));

        $locks = DB::select('select * from locks');
        $this->assertEquals(1, count($locks));
        $this->assertEquals($challenge->id, $locks[0]->challenge_id);
        $this->assertEquals($this->participant->id, $locks[0]->user_id);
    }

    /**
     * A feature test for submitting a submission with an attachment without sending the payload.
     *
     * @return void
     */
    public function test_submit_challenge_that_requires_judgment_without_sending_attachment()
    {
        $this->participant->track->is_locked = false;
        $this->participant->track->save();

        $challenge = Challenge::factory()->create(['max_tries' => 2, 'solution' => null, 'requires_judge' => 1]);
        $challenge->track()->associate($this->participant->track);
        $payload = [];

        $response = $this->postJson($this->endpoint.$challenge->id.'/submit', $payload);
        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson([
            'success' => false,
            'message' => "Validation failed",
            'data' => [
                'attachment' => ['The attachment field is required.']
            ]
        ]);

        $this->assertDatabaseCount('submissions', 0);
    }
}
