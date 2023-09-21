<?php

namespace Tests\Feature;

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
}
