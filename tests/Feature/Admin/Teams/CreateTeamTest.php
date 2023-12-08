<?php

namespace Tests\Feature;

use App\Models\Team;
use Illuminate\Http\Response;
use Tests\AdminTestCase;

class CreateTeamTest extends AdminTestCase
{
    private $endpoint = '/api/admin/team/create';
    /**
     * A feature test for creating a team.
     *
     * @return void
     */
    public function test_create_team()
    {
        $payload = [
            'name' => $this->faker->text(8),
            'token' => $this->faker->text(30)
        ];
        $response = $this->postJson($this->endpoint, $payload);
        $response->assertStatus(Response::HTTP_OK)->assertExactJson([
            'success' => true,
            'data' => [
                'name' => $payload['name'],
                'token' => $payload['token'],
                'participants' => [],
                'id' => 1,                
            ],
            'message' => 'Successfully created the team!'
        ]);
        $this->assertDatabaseCount('teams', 1);
        $this->assertDatabaseHas('teams', [
            'name' => $payload['name'],
            'token' => $payload['token'],
        ]);
    }
    /**
     * A feature test for creating a team without data.
     *
     * @return void
     */
    public function test_creating_team_without_data()
    {
        $payload = [];
        $response = $this->postJson($this->endpoint, $payload);
        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson([
            'success' => false,
            'message' => 'Validation failed!',
            'data' => [
                'name' => ['The name field is required.'],
                'token' => ['The token field is required.'],
            ],
        ]);
        $this->assertDatabaseCount('teams', 0);
    }
    /**
     * A feature test for creating a team that exists.
     *
     * @return void
     */
    public function test_create_team_that_exists()
    {
        $team = Team::factory()->create();
        $payload = [
            'name' => $team->name,
            'token' => $this->faker->text(30)
        ];
        $response = $this->postJson($this->endpoint, $payload);
        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson([
            'success' => false,
            'message' => 'Validation failed!',
            'data' => [
                'name' => ['The name has already been taken.'],
            ],
        ]);
        $this->assertDatabaseCount('teams', 1);
    }
}
