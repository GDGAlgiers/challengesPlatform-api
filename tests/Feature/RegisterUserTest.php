<?php

namespace Tests\Feature;

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
        $payload = [
            'full_name' => $this->faker->name(),
            'password' => $this->faker->password()
        ];

        $response = $this->postJson('/api/register', $payload);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'user' => [
                        'id',
                        'full_name',
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
            'points' => 0,
            'role' => 'participant',
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
                    'password' => ['The password field is required.']
                ]
                ]);
        $this->assertDatabaseEmpty('users');
    }

    public function test_unsuccessfull_register_because_of_existing_full_name() {
        $user = User::factory()->create();
        $payload = [
            'full_name' => $user->full_name,
            'password' => $this->faker->password()
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
}
