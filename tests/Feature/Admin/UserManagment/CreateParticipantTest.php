<?php

namespace Tests\Feature\Admin\UserManagement;

use App\Models\Track;
use App\Models\User;
use Tests\AdminTestCase;

class CreateParticipantTest extends AdminTestCase
{
    protected $endpoint = '/api/admin/user/create-participant';

    /**
     * A feature test for success creation of a participant.
     *
     * @return void
     */
    public function test_success_creation_of_participant()
    {
        $track = Track::factory()->create();
        $payload = [
            'full_name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'password' => $this->faker->password(),
            'track' => $track->type
        ];

        $response = $this->postJson($this->endpoint, $payload);

        $response->assertStatus(200)->assertJson([
            'success' => true,
            'data' => [
                'full_name' => $payload["full_name"],
                'email' => $payload["email"],
                'points' => 0,
                'role' => 'participant',
                'email_verified' => false,
                'track' => $track->type,
                'submissions' => []
            ],
            'message' => 'Participant was succefully created!'
        ]);

        $this->assertDatabaseHas('users', [
            'full_name' => $payload['full_name'],
            'role' => 'participant',
            'points' => 0
        ]);
    }

    /**
     * A feature test for unsuccess creation of a participant because of payload data missing.
     *
     * @return void
     */
    public function test_unsuccess_creation_because_of_missing_data()
    {
        $payload = [];

        $response = $this->postJson($this->endpoint, $payload);

        $response->assertStatus(400)->assertExactJson([
            'success' => false,
            'message' => 'Validation failed!',
            'data' => [
                'full_name' => ['The full name field is required.'],
                'password' => ['The password field is required.'],
                'track' => ['The track field is required.'],
                'email' => ['The email field is required.']
            ]
        ]);

        $this->assertDatabaseCount('users', 1);
    }

    /**
     * A feature test for unsuccess creation of a participant because of unexisting track.
     *
     * @return void
     */
    public function test_unsuccess_creation_because_of_unexisting_track()
    {
        $payload = [
            'full_name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'password' => $this->faker->password(),
            'track' => $this->faker->text(6)
        ];

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
     * A feature test for unsuccess creation of a participant because of existing participant name.
     *
     * @return void
     */
    public function test_unsuccess_creation_because_of_existing_participant_name()
    {
        $user = User::factory()->create();
        $track = Track::factory()->create();
        $payload = [
            'full_name' => $user->full_name,
            'email' => $this->faker->email(),
            'password' => $this->faker->password(),
            'track' => $track->type
        ];

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

    /**
     * A feature test for unsuccess creation of a participant because of existing participant email.
     *
     * @return void
     */
    public function test_unsuccess_creation_because_of_existing_participant_email()
    {
        $user = User::factory()->create();
        $track = Track::factory()->create();
        $payload = [
            'full_name' => $this->faker->name(),
            'email' => $user->email,
            'password' => $this->faker->password(),
            'track' => $track->type
        ];

        $response = $this->postJson($this->endpoint, $payload);

        $response->assertStatus(400)->assertExactJson([
            'success' => false,
            'message' => 'Validation failed!',
            'data' => [
                'email' => ['The email has already been taken.']
            ]
        ]);

        $this->assertDatabaseCount('users', 2);
    }
}
