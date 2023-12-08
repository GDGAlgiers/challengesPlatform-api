<?php

namespace Tests\Feature;

use App\Models\Team;
use App\Models\User;
use App\Models\Track;

use Illuminate\Http\Response;

use Tests\AdminTestCase;

class RemoveTeamMemberTest extends AdminTestCase
{
    private $endpoint = '/api/admin/team/remove-member';
    /**
     * A feature test for remove member from team.
     *
     * @return void
     */
    public function test_remove_team_member()
    {
        $team = Team::factory()->create();
        $track = Track::factory()->create();

        $participant = User::factory()->create([
            'track_id' => $track->id,
            'team_id' => $team->id,
        ]);
        $payload = [
            'participant_id' => $participant->id,
        ];
        $response = $this->postJson($this->endpoint, $payload);
        $response->assertStatus(Response::HTTP_OK)->assertExactJson([
            'success' => true,
            'data' => [
                'id' => $participant->id,
                'full_name' => $participant->full_name,
                'email' => $participant->email,
                'points' => 0,
                'role' => 'participant',
                'email_verified' => $participant->email_verified_at ? true: false,
                'track' => $track->type,
                'submissions' => [],
                'team' => null,
                ],
            'message' => 'Successfully removed the member!'
        ]);
        $this->assertDatabaseCount('teams', 1);
        $this->assertDatabaseHas('teams', [
            'id' => $team->id,
            'name' => $team->name,
        ]);
        $this->assertDatabaseCount('users', 2);
        $this->assertDatabaseHas('users', [
            'id' => $participant->id,
            'team_id' => null,
        ]);
    }
    public function test_remove_team_member_without_data()
    {
        $payload = [];
        $response = $this->postJson($this->endpoint, $payload);
        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson([
            'success' => false,
            'message' => 'Validation failed!',
            'data' => [
                'participant_id' => ['The participant id field is required.'],
            ],
        ]);

    }
    public function test_remove_team_member_does_not_exist()
    {
        $payload = [
            'participant_id' => 878787,
        ];
        $response = $this->postJson($this->endpoint, $payload);
        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson([
            'success' => false,
            'message' => 'Participant not found!',
        ]);
    }
}




