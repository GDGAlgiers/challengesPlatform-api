<?php

namespace Tests\Feature;

use App\Models\Challenge;
use App\Models\Submission;
use App\Models\Track;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\JudgeTestCase;

class JudgeSubmbissionTest extends JudgeTestCase
{
    private $endpoint = '/api/judge/submission/';

    /**
     * A feature test for rejecting a submission.
     *
     * @return void
     */
    public function test_judge_submission_by_rejecting_it()
    {
        $submission = Submission::factory()->create([
            'track_id' => $this->judge->track_id,
            'status' => 'judging',
            'judge_id' => $this->judge->id
        ]);

        $payload = [
            'judgment' => 'rejected'
        ];

        $response = $this->postJson($this->endpoint.$submission->id.'/judge', $payload);
        $response->assertStatus(Response::HTTP_OK)->assertExactJson([
            'success' => true,
            'data' => [],
            'message' => 'Succefully Rejected the submission'
        ]);

        $this->assertDatabaseHas('submissions', [
            'id' => $submission->id,
            'judge_id' => $this->judge->id,
            'status' => 'rejected',
            'assigned_points' => 0
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $submission->participant->id,
            'points' => 0
        ]);

        $solves = $submission->participant->solves;
        $this->assertEquals(0, count($solves));

        $locks = $submission->participant->locks;
        $this->assertEquals(0, count($locks));
    }

    /**
     * A feature test for rejecting a submission without providing the judgment.
     *
     * @return void
     */
    public function test_judge_submission_by_rejecting_it_without_providing_judgment()
    {
        $submission = Submission::factory()->create([
            'track_id' => $this->judge->track_id,
            'status' => 'judging',
            'judge_id' => $this->judge->id
        ]);

        $payload = [];

        $response = $this->postJson($this->endpoint.$submission->id.'/judge', $payload);
        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson([
            'success' => false,
            'message' => 'Validation error',
            'data' => [
                'judgment' => ['The judgment field is required.']
            ]
        ]);

        $this->assertDatabaseHas('submissions', [
            'id' => $submission->id,
            'judge_id' => $this->judge->id,
            'status' => 'judging',
            'assigned_points' => null
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $submission->participant->id,
            'points' => 0
        ]);

        $solves = $submission->participant->solves;
        $this->assertEquals(0, count($solves));

        $locks = $submission->participant->locks;
        $this->assertEquals(1, count($locks));
    }

    /**
     * A feature test for juding a submission that does not exist.
     *
     * @return void
     */
    public function test_judge_submission_that_does_not_exist()
    {
        $payload = [
            'judgment' => 'rejected'
        ];

        $response = $this->postJson($this->endpoint.'100/judge', $payload);
        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertExactJson([
            'success' => false,
            'message' => 'Submission can not be found!',
        ]);
    }

    /**
     * A feature test for juding a submission that belongs to different challenge's track other than the judge.
     *
     * @return void
     */
    public function test_judge_submission_that_does_not_belong_to_judge_track()
    {
        $track = Track::factory()->create();

        $submission = Submission::factory()->create([
            'track_id' => $track->id,
            'status' => 'judging',
        ]);

        $payload = [
            'judgment' => 'rejected'
        ];

        $response = $this->postJson($this->endpoint.$submission->id.'/judge', $payload);
        $response->assertStatus(Response::HTTP_FORBIDDEN)->assertExactJson([
            'success' => false,
            'message' => 'You can not judge this submission',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $submission->participant->id,
            'points' => 0
        ]);

        $solves = $submission->participant->solves;
        $this->assertEquals(0, count($solves));

        $locks = $submission->participant->locks;
        $this->assertEquals(1, count($locks));
    }

    /**
     * A feature test for juding a submission that belongs to a challenge that does not require judgment.
     *
     * @return void
     */
    public function test_judge_submission_that_belongs_to_challenge_that_does_not_require_judgment()
    {
        $track = Track::factory()->create();
        $challenge = Challenge::factory()->create(['track_id' => $track->id]);
        $submission = Submission::factory()->create([
            'track_id' => $track->id,
            'challenge_id' => $challenge->id,
            'status' => 'judging',
        ]);

        $payload = [
            'judgment' => 'rejected'
        ];

        $response = $this->postJson($this->endpoint.$submission->id.'/judge', $payload);
        $response->assertStatus(Response::HTTP_FORBIDDEN)->assertExactJson([
            'success' => false,
            'message' => 'You can not judge this submission',
        ]);
    }

    /**
     * A feature test for juding a submission that does not have an attachment.
     *
     * @return void
     */
    public function test_judge_submission_that_does_not_have_attachment()
    {
        $submission = Submission::factory()->create([
            'track_id' => $this->judge->track_id,
            'status' => 'judging',
            'attachment' => null
        ]);

        $payload = [
            'judgment' => 'rejected'
        ];

        $response = $this->postJson($this->endpoint.$submission->id.'/judge', $payload);
        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR)->assertExactJson([
            'success' => false,
            'message' => 'This submission misses attachment and it can not be judged, contact the admin',
        ]);
    }

    /**
     * A feature test for juding a submission that is being judge by other judge.
     *
     * @return void
     */
    public function test_judge_submission_that_is_being_judge_by_other_judge()
    {
        $judge2 = User::factory()->create([
            'role' => 'judge',
            'track_id' => $this->judge->track_id
        ]);

        $submission = Submission::factory()->create([
            'track_id' => $this->judge->track_id,
            'status' => 'judging',
            'judge_id' => $judge2->id
        ]);

        $payload = [
            'judgment' => 'rejected'
        ];

        $response = $this->postJson($this->endpoint.$submission->id.'/judge', $payload);
        $response->assertStatus(Response::HTTP_FORBIDDEN)->assertExactJson([
            'success' => false,
            'message' => 'This submission is being reviewd by another judge already',
        ]);

        $this->assertDatabaseHas('submissions', [
            'id' => $submission->id,
            'judge_id' => $judge2->id,
            'status' => 'judging'
        ]);

        $this->assertDatabaseMissing('submissions', [
            'id' => $submission->id,
            'judge_id' => $this->judge->id
        ]);
    }

    /**
     * A feature test for juding a submission that has not status judging.
     *
     * @return void
     */
    public function test_judge_submission_that_is_not_being_judge()
    {
        $submission = Submission::factory()->create([
            'track_id' => $this->judge->track_id,
        ]);

        $payload = [
            'judgment' => 'rejected'
        ];

        $response = $this->postJson($this->endpoint.$submission->id.'/judge', $payload);
        $response->assertStatus(Response::HTTP_FORBIDDEN)->assertExactJson([
            'success' => false,
            'message' => 'This action can be made for submissions that have status judging',
        ]);
    }

    /**
     * A feature test for juding a submission by approving it.
     *
     * @return void
     */
    public function test_judge_submbission_by_approving_it()
    {
        $submission = Submission::factory()->create([
            'track_id' => $this->judge->track_id,
            'status' => 'judging',
            'judge_id' => $this->judge->id
        ]);

        $payload = [
            'judgment' => 'approved',
            'points' => rand(1, 90)
        ];

        $response = $this->postJson($this->endpoint.$submission->id.'/judge', $payload);
        $response->assertStatus(Response::HTTP_OK)->assertExactJson([
            'success' => true,
            'message' => 'Succefully Approved the submission',
            'data' => []
        ]);

        $this->assertDatabaseHas('submissions', [
            'id' => $submission->id,
            'status' => 'approved',
            'participant_id' => $submission->participant->id,
            'challenge_id' => $submission->challenge->id,
            'assigned_points' => $payload['points']
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $submission->participant->id,
            'points' => $payload['points']
        ]);

        $this->assertEquals($payload["points"], $submission->participant->points);

        $locks = $submission->participant->locks;
        $this->assertEquals(0, count($locks));

        $solves = $submission->participant->solves;
        $this->assertEquals(1, count($solves));
    }

    /**
     * A feature test for juding a submission by approving it without providing points.
     *
     * @return void
     */
    public function test_judge_submbission_by_approving_it_without_providing_points()
    {
        $submission = Submission::factory()->create([
            'track_id' => $this->judge->track_id,
            'status' => 'judging',
            'judge_id' => $this->judge->id
        ]);

        $payload = [
            'judgment' => 'approved',
        ];

        $response = $this->postJson($this->endpoint.$submission->id.'/judge', $payload);
        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson([
            'success' => false,
            'message' => 'Validation error',
            'data' => [
                'points' => ['The points field is required.']
            ]
        ]);
    }

    /**
     * A feature test for juding a submission by approving it and assigning higher points than the challange points.
     *
     * @return void
     */
    public function test_judge_submbission_by_approving_it_with_assigning_higher_points_than_challenge_points()
    {
        $submission = Submission::factory()->create([
            'track_id' => $this->judge->track_id,
            'status' => 'judging',
            'judge_id' => $this->judge->id
        ]);

        $payload = [
            'judgment' => 'approved',
            'points' => $submission->challenge->points + 1
        ];

        $response = $this->postJson($this->endpoint.$submission->id.'/judge', $payload);
        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson([
            'success' => false,
            'message' => 'The given points are greater than the maximaum points of this challenge!',
        ]);

        $this->assertDatabaseHas('submissions', [
            'id' => $submission->id,
            'status' => 'judging',
            'assigned_points' => null
        ]);
        $this->assertDatabaseMissing('submissions', [
            'id' => $submission->id,
            'status' => 'approved',
            'assigned_points' => $payload['points']
        ]);
        $this->assertDatabaseHas('users', [
            'id' => $submission->participant->id,
            'points' => 0
        ]);
    }

    /**
     * A feature test for juding a submission by approving it and assigning points lower than previous assigned ones.
     *
     * @return void
     */
    public function test_judge_submbission_by_approving_it_with_assigning_lower_points_than_pre_assigned()
    {
        $prevSubmission = Submission::factory()->create([
            'track_id' => $this->judge->track_id,
            'status' => 'approved',
            'judge_id' => $this->judge->id,
            'assigned_points' => 50
        ]);
        $prevSubmission->participant->points = 50;
        $prevSubmission->participant->save();

        $newSubmission = Submission::factory()->create([
            'track_id' => $this->judge->track_id,
            'challenge_id' => $prevSubmission->challenge_id,
            'participant_id' => $prevSubmission->participant_id,
            'status' => 'judging',
            'judge_id' => $this->judge->id,
        ]);


        $payload = [
            'judgment' => 'approved',
            'points' => 40
        ];

        $response = $this->postJson($this->endpoint.$newSubmission->id.'/judge', $payload);
        $response->assertStatus(Response::HTTP_OK)->assertExactJson([
            'success' => true,
            'message' => 'Succefully Approved the submission',
            'data' => []
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $newSubmission->participant_id,
            'points' => $prevSubmission->assigned_points
        ]);
    }


    /**
     * A feature test for juding a submission by approving it and assigning higher points than previous judgments.
     *
     * @return void
     */
    public function test_judge_submbission_by_approving_it_with_assigning_higher_points_than_pre_assigned()
    {
        $prevSubmission = Submission::factory()->create([
            'track_id' => $this->judge->track_id,
            'status' => 'approved',
            'judge_id' => $this->judge->id,
            'assigned_points' => 50
        ]);
        $prevSubmission->participant->points = 50;
        $prevSubmission->participant->solves()->attach($prevSubmission->challenge_id);
        $prevSubmission->participant->save();

        $newSubmission = Submission::factory()->create([
            'track_id' => $this->judge->track_id,
            'challenge_id' => $prevSubmission->challenge_id,
            'participant_id' => $prevSubmission->participant_id,
            'status' => 'judging',
            'judge_id' => $this->judge->id,
        ]);


        $payload = [
            'judgment' => 'approved',
            'points' => 60
        ];

        $response = $this->postJson($this->endpoint.$newSubmission->id.'/judge', $payload);
        $response->assertStatus(Response::HTTP_OK)->assertExactJson([
            'success' => true,
            'message' => 'Succefully Approved the submission',
            'data' => []
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $newSubmission->participant_id,
            'points' => $payload['points']
        ]);

        $solves = $newSubmission->participant->solves;
        $this->assertEquals(1, count($solves));

        $locks = $newSubmission->participant->locks;
        $this->assertEquals(0, count($locks));
    }

    /**
     * A feature test for juding a submission by approving it and assigning full points.
     *
     * @return void
     */
    public function test_judge_submbission_by_approving_it_with_assigning_full_points()
    {
        $submission = Submission::factory()->create([
            'track_id' => $this->judge->track_id,
            'status' => 'judging',
            'judge_id' => $this->judge->id,
        ]);

        $payload = [
            'judgment' => 'approved',
            'points' => 100 // max points (see Submission::factory class)
        ];

        $response = $this->postJson($this->endpoint.$submission->id.'/judge', $payload);
        $response->assertStatus(Response::HTTP_OK)->assertExactJson([
            'success' => true,
            'message' => 'Succefully Approved the submission',
            'data' => []
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $submission->participant_id,
            'points' => $payload['points']
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $submission->participant_id,
            'points' => $payload['points']
        ]);

        $solves = $submission->participant->solves;
        $this->assertEquals(1, count($solves));

        $locks = $submission->participant->locks;
        $this->assertEquals(1, count($locks));
    }

    /**
     * A feature test for juding a submission of last try.
     *
     * @return void
     */
    public function test_judge_submbission_of_last_try()
    {
        // max_tries is 3, see Submission::factory class
        $submission1 = Submission::factory()->create([
            'track_id' => $this->judge->track_id,
            'status' => 'rejected',
            'judge_id' => $this->judge->id,
            'assigned_points' => 0
        ]);

        $submission2 = Submission::factory()->create([
            'track_id' => $this->judge->track_id,
            'challenge_id' => $submission1->challenge_id,
            'participant_id' => $submission1->participant_id,
            'status' => 'approved',
            'judge_id' => $this->judge->id,
            'assigned_points' => 20
        ]);
        $submission2->participant->points = 20;

        $submission3 = Submission::factory()->create([
            'track_id' => $this->judge->track_id,
            'challenge_id' => $submission1->challenge_id,
            'participant_id' => $submission1->participant_id,
            'status' => 'judging',
            'judge_id' => $this->judge->id,
        ]);
        $payload = [
            'judgment' => 'approved',
            'points' => 80 // max points (see Submission::factory class)
        ];

        $response = $this->postJson($this->endpoint.$submission3->id.'/judge', $payload);
        $response->assertStatus(Response::HTTP_OK)->assertExactJson([
            'success' => true,
            'message' => 'Succefully Approved the submission',
            'data' => []
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $submission3->participant_id,
            'points' => 60
        ]);

        $solves = $submission3->participant->solves;
        $this->assertEquals(1, count($solves));

        $locks = $submission3->participant->locks;
        $this->assertEquals(1, count($locks));
    }
}
