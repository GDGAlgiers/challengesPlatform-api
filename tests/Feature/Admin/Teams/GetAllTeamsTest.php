<?php

namespace Tests\Feature;

use App\Models\Team;
use Illuminate\Http\Response;
use Tests\AdminTestCase;

class GetAllTeamsTest extends AdminTestCase
{
    private $endpoint = '/api/admin/team';

    /**
     * A feature test for getting all teams.
     *
     * @return void
     */
    public function test_get_all_teams()
    {
        $teams = Team::factory()->count(3)->create();
        $response = $this->getJson($this->endpoint);
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'token',
                    ]
                ],
                'message'
            ])
        ;
        $this->assertTrue($response["success"], true);
        $this->assertEquals($response["message"], "Successfully retrieved all the teams!");
        $this->assertDatabaseCount('teams', 3);
    }
}
