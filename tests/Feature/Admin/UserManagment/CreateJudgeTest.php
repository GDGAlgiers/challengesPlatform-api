<?php

namespace Tests\Feature;

use App\Models\Track;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreateJudgeTest extends TestCase
{
    protected $endpoint = '/api/admin/user/create-judge';

    /**
     * A feature test for success creation of a judge.
     *
     * @return void
     */
    public function test_success_creation_of_judge()
    {
        Sanctum::actingAs(
            User::factory()->create(['role' => 'admin']),
            ['*']
        );

        $track = Track::factory()->create();
        $payload = [
            'full_name' => $this->faker->name(),
            'password' => $this->faker->password(),
            'track' => $track->type
        ];

        $response = $this->postJson($this->endpoint, $payload);

        $response->assertStatus(200)->assertExactJson([
            'success' => true,
            'data' => [
                'full_name' => $payload["full_name"],
                'role' => 'judge',
                'track' => $track->type,
            ],
            'message' => 'Succefully registred the judge!'
        ]);

        $this->assertDatabaseHas('users', [
            'full_name' => $payload['full_name'],
            'role' => 'judge',
        ]);
    }

    /**
     * A feature test for unsuccess creation of a judge because of payload data missing.
     *
     * @return void
     */
    public function test_unsuccess_creation_because_of_missing_data()
    {
        $payload = [];

        Sanctum::actingAs(
            User::factory()->create(['role' => 'admin']),
            ['*']
        );

        $response = $this->postJson($this->endpoint, $payload);

        $response->assertStatus(400)->assertExactJson([
            'success' => false,
            'message' => 'Validation failed!',
            'data' => [
                'full_name' => ['The full name field is required.'],
                'password' => ['The password field is required.'],
                'track' => ['The track field is required.']
            ]
        ]);

        $this->assertDatabaseCount('users', 1);
    }

    /**
     * A feature test for unsuccess creation of a judge because of unexisting track.
     *
     * @return void
     */
    public function test_unsuccess_creation_because_of_unexisting_track()
    {
        $payload = [
            'full_name' => $this->faker->name(),
            'password' => $this->faker->password(),
            'track' => $this->faker->text(6)
        ];

        Sanctum::actingAs(
            User::factory()->create(['role' => 'admin']),
            ['*']
        );

        $response = $this->postJson($this->endpoint, $payload);

        $response->assertStatus(400)->assertExactJson([
            'success' => false,
            'message' => 'Validation failed!',
            'data' => [
                'track' => ['The selected track is invalid.']
            ]
        ]);

        $this->assertDatabaseCount('users', 1);
    }

    /**
     * A feature test for unsuccess creation of a judge because of existing user.
     *
     * @return void
     */
    public function test_unsuccess_creation_because_of_existing_user()
    {
        $user = User::factory()->create();
        $track = Track::factory()->create();
        $payload = [
            'full_name' => $user->full_name,
            'password' => $this->faker->password(),
            'track' => $track->type
        ];

        Sanctum::actingAs(
            User::factory()->create(['role' => 'admin']),
            ['*']
        );

        $response = $this->postJson($this->endpoint, $payload);

        $response->assertStatus(400)->assertExactJson([
            'success' => false,
            'message' => 'Validation failed!',
            'data' => [
                'full_name' => ['The full name has already been taken.']
            ]
        ]);

        $this->assertDatabaseCount('users', 2);
    }
}
