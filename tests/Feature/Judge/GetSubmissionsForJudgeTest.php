<?php

namespace Tests\Feature;

use App\Http\Resources\SubmissionResource;
use App\Models\Submission;
use App\Models\Track;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\JudgeTestCase;

class GetSubmissionsForJudgeTest extends JudgeTestCase
{
    private $endpoint = '/api/judge/submissions';

    /**
     * A feature test for getting pending submissions.
     *
     * @return void
     */
    public function test_get_pending_submissions_for_authenticated_judge()
    {
        Submission::factory()->count(2)->create(['track_id' => $this->judge->track_id]);

        // another submission for different track other than the authenticated judge's track
        // this should not be returned in the response
        Submission::factory()->create();

        $response = $this->getJson($this->endpoint);

        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure([
            'success',
            'data' => [
                '*' => [
                  'track',
                  'challenge' => [
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
                  ],
                  'attachment',
                  'status',
                  'assigned_points',
                  'submitted_at'
                ]
            ],
            'message'
        ]);

        $this->assertEquals(true, $response['success']);
        $this->assertEquals('Succefully retrieved all the pending submissions', $response['message']);
        $this->assertEquals(2, count($response['data']));

    }

    /**
     * A feature test for getting submissions either having pending status or being judged by the authenticated judge.
     *
     * @return void
     */
    public function test_get_submissions_for_authenticated_judge_either_with_judging_status_or_pending_status()
    {
        Submission::factory()->create(['track_id' => $this->judge->track_id]);
        Submission::factory()->create(['track_id' => $this->judge->track_id, 'judge_id' => $this->judge->id]);

        // another submission being judge by another judge
        // this should not be included in the response
        $track = Track::factory()->create();
        $judge2 = User::factory()->create(['track_id' => $track->id]);
        Submission::factory()->create(['status' => 'judging', 'judge_id' => $judge2->id, 'track_id' => $this->judge->track_id]);

        $response = $this->getJson($this->endpoint);

        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure([
            'success',
            'data' => [
                '*' => [
                  'track',
                  'challenge' => [
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
                  ],
                  'attachment',
                  'status',
                  'assigned_points',
                  'submitted_at'
                ]
            ],
            'message'
        ]);

        $this->assertEquals(true, $response['success']);
        $this->assertEquals('Succefully retrieved all the pending submissions', $response['message']);
        $this->assertEquals(2, count($response['data']));

    }

}
