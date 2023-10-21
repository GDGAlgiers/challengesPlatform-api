<?php

namespace Tests\Feature;

use App\Models\Track;
use App\Models\User;
use Tests\TestCase;

class RegisterUserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_registers_successfully()
    {
        $track = Track::factory()->create();
        $payload = [
            'full_name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'password' => $this->faker->password(),
            'track' => $track->type,
        ];

        $response = $this->postJson('/api/register', $payload);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'user' => [
                        'full_name',
                        'email',
                        'points',
                        'role',
                        'email_verified',
                        'track',
                        'submissions'
                    ],
                    'token'
                ],
                'message'
            ])
        ;

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users', [
            'full_name' =>  $payload["full_name"],
            'email' => $payload["email"],
            'points' => 0,
            'role' => 'participant',
            'track_id' => $track->id,
        ]);
    }

    public function test_unsuccessfull_register_because_of_missing_data() {
        $payload = [];

        $response = $this->postJson('/api/register', $payload);

        $response
            ->assertStatus(400)
            ->assertExactJson([
                'success' => false,
                'message' => 'Validation of data failed',
                'data' => [
                    'full_name' => ['The full name field is required.'],
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.'],
                    'track' => ['The track field is required.'],
                ]
            ]);
        $this->assertDatabaseEmpty('users');
    }

    public function test_unsuccessfull_register_because_of_existing_full_name() {
        $user = User::factory()->create();
        $track = Track::factory()->create();
        $payload = [
            'full_name' => $user->full_name,
            'email' => $this->faker->email(),
            'password' => $this->faker->password(),
            'track' => $track->type,
        ];

        $response = $this->postJson('/api/register', $payload);

        $response
            ->assertStatus(400)
            ->assertExactJson([
                'success' => false,
                'message' => 'Validation of data failed',
                'data' => [
                    'full_name' => ['The full name has already been taken.']
                ]
            ]
        );
        $this->assertDatabaseCount('users', 1);
    }

    public function test_unsuccessfull_register_because_of_existing_email() {
        $user = User::factory()->create();
        $track = Track::factory()->create();
        $payload = [
            'full_name' => $this->faker->name(),
            'email' => $user->email,
            'password' => $this->faker->password(),
            'track' => $track->type,
        ];

        $response = $this->postJson('/api/register', $payload);

        $response
            ->assertStatus(400)
            ->assertExactJson([
                'success' => false,
                'message' => 'Validation of data failed',
                'data' => [
                    'email' => ['The email has already been taken.']
                ]
            ]
        );
        $this->assertDatabaseCount('users', 1);
    }

    public function test_unsuccessfull_register_because_of_unexisting_track()
    {
        $payload = [
            'full_name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'password' => $this->faker->password(),
            'track' => $this->faker->text(8),
        ];

        $response = $this->postJson('/api/register', $payload);
        $response
            ->assertStatus(400)
            ->assertExactJson([
                'success' => false,
                'message' => 'Validation of data failed',
                'data' => [
                    'track' => ['The selected track is invalid.']
                ]
            ]
        );
        $this->assertDatabaseCount('users', 0);
    }
}
