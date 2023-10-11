<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ChallengeExistsMiddlewareTest extends TestCase
{
    /**
     * A feature test for not existing challenge.
     *
     * @return void
     */
    public function test_challenge_does_not_exist()
    {
        Sanctum::actingAs(
            User::factory()->create(['role' => 'admin']),
            ['*']
        );

        $response = $this->postJson('/api/admin/challenge/1000/lock');

        $response->assertStatus(400)->assertExactJson([
            'success' => false,
            'message' => 'Challenge can not be found'
        ]);
    }
}
