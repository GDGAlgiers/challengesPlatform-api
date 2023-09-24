<?php

namespace Tests\Feature;

use App\Models\Challenge;
use App\Models\Track;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LockChallengeTest extends TestCase
{
    private $endpoint = '/api/admin/challenge/lock/';

    /**
     * A feature test for locking a challenge.
     *
     * @return void
     */
    public function test_lock_challenge()
    {
        Track::factory()->create();
        $challenge = Challenge::factory()->create();
        Sanctum::actingAs(
            User::factory()->create(['role' => 'admin']),
            ['*']
        );

        $response = $this->postJson($this->endpoint.$challenge->id);

        $response->assertStatus(200)->assertExactJson([
            'success' => true,
            'data' => [],
            'message' => 'Challenge Succefully locked!'
        ]);

        $this->assertDatabaseHas('challenges', [
            'id' => $challenge->id,
            'is_locked' => 1
        ]);
    }

    /**
     * A feature test for locking a locked challenge.
     *
     * @return void
     */
    function test_locking_already_locked_challenge()
    {
        Track::factory()->create();
        $challenge = Challenge::factory()->create(['is_locked' => true]);
        Sanctum::actingAs(
            User::factory()->create(['role' => 'admin']),
            ['*']
        );

        $response = $this->postJson($this->endpoint.$challenge->id);

        $response->assertStatus(400)->assertExactJson([
            'success' => false,
            'message' => 'This challenge is locked for now'
        ]);

        $this->assertDatabaseHas('challenges', [
            'id' => $challenge->id,
            'is_locked' => 1
        ]);
    }
}
