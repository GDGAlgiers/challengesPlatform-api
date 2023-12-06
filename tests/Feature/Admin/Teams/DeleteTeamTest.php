<?php

namespace Tests\Feature;

use App\Models\Team;
use Illuminate\Http\Response;
use Tests\AdminTestCase;

class DeleteTeamTest extends AdminTestCase
{
    private $endpoint = '/api/admin/team/';
    /**
     * A feature test for deleting a team.
     *
     * @return void
     */
    public function test_delete_team()
    {
        $team = Team::factory()->create();

        $response = $this->deleteJson($this->endpoint.$team->id.'/delete');

        $response->assertStatus(Response::HTTP_OK)->assertExactJson([
            'success' => true,
            'data' => [],
            'message' => 'Successfully deleted the team!',
        ]);

        $this->assertDatabaseCount('teams',0);

        $this->assertDatabaseMissing('teams', [
            'name' => $team->name,
        ]);


    
    }

    /**
     * A feature test for unsuccessfull deletion because of unexisting team.
     *
     * @return void
     */

     public function test_delete_unexisting_team()
     {
        $response = $this->deleteJson($this->endpoint.'2333'.'/delete');

        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson([
            'success' => false,
            'message' => 'Team can not be found!'
        ]);

        $this->assertDatabaseCount('teams', 0);
     }

}
