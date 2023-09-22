<?php

namespace Tests\Feature;


use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use App\Models\User;
class LogoutTest extends TestCase
{
    /**
     * A feature test for successful logout.
     *
     * @return void
     */
    public function test_successful_logout()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $response = $this->postJson('/api/logout');

        $response
            ->assertStatus(200)
            ->assertExactJson([
                'success' => true,
                'data' => [],
                'message' => 'Logged out succesfully'
            ])
        ;
    }

    /**
     * A feature test for unsuccessful logout because of unauthenticated request.
     *
     * @return void
     */
    public function test_unsuccessful_logout_because_of_unauthenticated_request() {
        $response = $this->postJson('/api/logout');
        $response
            ->assertStatus(401)
            ->assertExactJson([
                'success' => false,
                'message' => 'Unauthenticated.'
            ])
        ;
    }
}
