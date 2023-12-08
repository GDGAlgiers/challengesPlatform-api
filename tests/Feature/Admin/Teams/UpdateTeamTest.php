<?php

namespace Tests\Feature;

use App\Models\Team;
use Illuminate\Http\Response;
use Tests\AdminTestCase;

class UpdateTeamTest extends AdminTestCase
{
    private $endpoint = '/api/admin/team/';

    /**
     * A feature test for updating a team name.
     *
     * @return void
     */
    public function test_update_team_name()
    {
        $team = Team::factory()->create();
        $payload = [
            'name' => $this->faker->text(30)
        ];
        $response = $this->postJson($this->endpoint.$team->id.'/update', $payload);

        $response->assertStatus(Response::HTTP_OK)->assertExactJson([
            'success' => true,
            'data' => [
                'id' => $team->id,
                'name' => $payload['name'],
                'token' => $team->token,
                'participants' => $team->participants,
            ],
            'message' => 'Successfully updated the team!'
        ]);

        $this->assertDatabaseMissing('teams', [
            'token' => $team->token,
            'name' => $team->name
        ]);
        $this->assertDatabaseHas('teams', [
            'token' => $team->token,
            'name' => $payload['name']
        ]);
    }
    public function test_update_team_name_without_data()
    {
        $team = Team::factory()->create();
        $payload = [];

        $response = $this->postJson($this->endpoint.$team->id.'/update', $payload);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson([
            'success' => false,
            'message' => 'Validation failed!',
            'data' => [
                'name' => ['The name field is required.'],
            ],
        ]);
    }
    public function test_update_team_name_dont_exist()
    {
        $payload = [
            "name"  =>  $this->faker->name()
        ];
        $response = $this->postJson($this->endpoint."233".'/update', $payload);
        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson([
            'success' => false,
            'message' => 'Team can not be found!',
        ]);
        $this->assertDatabaseCount('teams', 0);
    }
}
