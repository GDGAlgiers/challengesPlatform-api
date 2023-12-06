<?php

namespace Tests\Feature;

use App\Models\Team;
use App\Models\User;
use App\Models\Track;

use Illuminate\Http\Response;

use Tests\AdminTestCase;

class AddTeamMemberTest extends AdminTestCase
{
    private $endpoint = '/api/admin/team/';
    /**
     * A feature test for add member for team.
     *
     * @return void
     */
    public function test_add_team_member()
    {


        $team = Team::factory()->create();
        $track = Track::factory()->create();

        $participant = User::factory()->create([
            'track_id' => $track->id,
        ]);
 

        $payload = [
            'participant_id' => $participant->id,
        ];



        $response = $this->postJson($this->endpoint.$team->id.'/add-member', $payload);

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
                'team' => $team->name,
                ],
            'message' => 'Successfully added the member!'
        ]);

        $this->assertDatabaseCount('teams', 1);

        $this->assertDatabaseHas('teams', [
            'id' => $team->id,
            'name' => $team->name,
        ]);

        $this->assertDatabaseCount('users', 2);

        $this->assertDatabaseHas('users', [
            'team_id' => $team->id,
        ]);



    }


    public function test_add_team_member_without_data()
    {
        $team = Team::factory()->create();
        $payload = [];

        $response = $this->postJson($this->endpoint.$team->id.'/add-member', $payload);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson([
            'success' => false,
            'message' => 'Validation failed!',
            'data' => [
                'participant_id' => ['The participant id field is required.'],
            ],
        ]);
    }


    public function test_add_team_member_does_not_exist()
    {
        $team = Team::factory()->create();
        $payload = [
            'participant_id' => 100,
        ];

        $response = $this->postJson($this->endpoint.$team->id.'/add-member', $payload);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson([
            'success' => false,
            'message' => 'Participant not found!',
        ]);

        $this->assertDatabaseCount('teams', 1);
        

    }


}
