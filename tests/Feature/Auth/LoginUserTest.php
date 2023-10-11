<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
class LoginUserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_successfull_login()
    {
        $user = User::factory()->create();
        $payload = [
            'full_name' => $user->full_name,
            'password' => 'password'
        ];

        $response = $this->postJson('/api/login', $payload);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'user',
                    'token'
                ],
                'message'
            ])
            ->assertJsonPath('data.user.full_name', $user->full_name)
            ->assertJsonPath('data.user.role', $user->role)
        ;
    }

    public function test_unsuccessfull_login_because_of_empty_data() {
        $payload = [];

        $response = $this->postJson('/api/login', $payload);

        $response
            ->assertStatus(400)
            ->assertExactJson([
                'success' => false,
                'message' => 'Validation of data failed',
                'data' => [
                    'full_name' => ['The full name field is required.'],
                    'password' => ['The password field is required.']
                ]
            ]
        );
    }

    public function test_unsuccessfull_login_because_of_invalid_username() {
        $user = User::factory()->create();

        $payload = [
            'full_name' => $this->faker->name(),
            'password' => 'password'
        ];

        $response = $this->postJson('/api/login', $payload);

        $response
            ->assertStatus(400)
            ->assertExactJson([
                'success' => false,
                'message' => 'Validation of data failed',
                'data' => [
                    'full_name' => ['The selected full name is invalid.'],
                ]
            ]
        );
    }

    public function test_unsuccessfull_login_because_of_invalid_pasword() {
        $user = User::factory()->create();

        $payload = [
            'full_name' => $user->full_name,
            'password' => $this->faker->password()
        ];

        $response = $this->postJson('/api/login', $payload);

        $response
            ->assertStatus(400)
            ->assertExactJson([
                'success' => false,
                'message' => 'Incorrect data',
                'data' => [
                    'password' => ['No user found with the specified data'],
                ]
            ]
        );
    }
}
