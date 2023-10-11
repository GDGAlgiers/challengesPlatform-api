<?php

namespace Tests\Feature;

use App\Models\Submission;
use Illuminate\Http\Response;
use Tests\JudgeTestCase;

class AssignJudgeToSubmissionTest extends JudgeTestCase
{
    private $endpoint = '/api/judge/submission/';

    /**
     * A feature test for self judge assign to submission.
     *
     * @return void
     */
    public function test_self_judge_assign_to_submission()
    {
        $submission = Submission::factory()->create(['track_id' => $this->judge->track_id]);

        $response = $this->postJson($this->endpoint.$submission->id.'/assign-judge');

        $response->assertStatus(Response::HTTP_OK)->assertExactJson([
            'success' => true,
            'message' => 'Succefully assigned the submission judgment to you',
            'data' => []
        ]);

        $this->assertDatabaseHas('submissions', [
            'id' => $submission->id,
            'judge_id' => $this->judge->id,
            'status' => 'judging'
        ]);
        $this->assertDatabaseMissing('submissions', [
            'id' => $submission->id,
            'judge_id' => null,
            'status' => 'pending'
        ]);
    }

    /**
     * A feature test for self judge assign to unexisting submission.
     *
     * @return void
     */
    public function test_self_judge_assign_to_unexisting_submission()
    {
        $response = $this->postJson($this->endpoint.'100/assign-judge');

        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertExactJson([
            'success' => false,
            'message' => 'Submission can not be found!',
        ]);
    }

    /**
     * A feature test for self judge assign to submission that does not have `pending` status.
     *
     * @return void
     */
    public function test_self_judge_assign_to_submission_that_does_not_have_pending_status()
    {
        $submission = Submission::factory()->create(['track_id' => $this->judge->track_id, 'status' => 'judging']);

        $response = $this->postJson($this->endpoint.$submission->id.'/assign-judge');

        $response->assertStatus(Response::HTTP_FORBIDDEN)->assertExactJson([
            'success' => false,
            'message' => 'This action can be made for submissions that have status pending',
        ]);

        $this->assertDatabaseHas('submissions', [
            'id' => $submission->id,
            'judge_id' => $submission->judge_id,
            'status' => $submission->status
        ]);
        $this->assertDatabaseMissing('submissions', [
            'id' => $submission->id,
            'judge_id' => $this->judge->id,
            'status' => 'pending'
        ]);
    }

    /**
     * A feature test for self judge assign to submission that does not belong to judge's track.
     *
     * @return void
     */
    public function test_self_judge_assign_to_submission_that_does_not_belong_to_judge_track()
    {
        $submission = Submission::factory()->create();

        $response = $this->postJson($this->endpoint.$submission->id.'/assign-judge');
        $response->assertStatus(Response::HTTP_FORBIDDEN)->assertExactJson([
            'success' => false,
            'message' => 'You can not judge this submission',
        ]);

        $this->assertDatabaseHas('submissions', [
            'id' => $submission->id,
            'judge_id' => $submission->judge_id,
            'status' => $submission->status
        ]);
        $this->assertDatabaseMissing('submissions', [
            'id' => $submission->id,
            'judge_id' => $this->judge->id,
            'status' => 'pending'
        ]);
    }
}
