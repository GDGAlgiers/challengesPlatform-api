<?php

namespace Tests\Feature;

use App\Models\Team;
use Illuminate\Http\Response;
use Tests\AdminTestCase;

class GetTeamByIdTest extends AdminTestCase
{
    private $endpoint = '/api/admin/team/';

    /**
     * A feature test for getting all teams.
     *
     * @return void
     */
    public function test_get_team_by_id()
    {
        $teams = Team::factory()->create();

        $response = $this->getJson($this->endpoint.$teams->id);
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'success' => true,
                'data' => [
                    "id" => $teams->id,
                    "name" => $teams->name,
                    "token" => $teams->token,
                    "participants" => $teams->participants,
                ],
                'message' => "Successfully retrieved the team!",
            ]);

        $this->assertDatabaseCount('teams', 1);

        
    }


    public function test_get_unexisting_team()
    {
        $response = $this->getJson($this->endpoint.'2333');

        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson([
            'success' => false,
            'message' => 'Team can not be found!'
        ]);

        $this->assertDatabaseCount('teams', 0);
    }

    
}
