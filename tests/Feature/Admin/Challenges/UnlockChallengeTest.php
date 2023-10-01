<?php

namespace Tests\Feature;

use App\Models\Challenge;
use App\Models\Track;
use Tests\AdminTestCase;

class UnlockChallengeTest extends AdminTestCase
{
    private $endpoint = '/api/admin/challenge/unlock/';

    /**
     * A feature test for unlocking a challenge.
     *
     * @return void
     */
    public function test_unlock_challenge()
    {
        Track::factory()->create();
        $challenge = Challenge::factory()->create(['is_locked' => true]);

        $response = $this->postJson($this->endpoint.$challenge->id);

        $response->assertStatus(200)->assertExactJson([
            'success' => true,
            'data' => [],
            'message' => 'Challenge Succefully unlocked!'
        ]);

        $this->assertDatabaseHas('challenges', [
            'id' => $challenge->id,
            'is_locked' => 0
        ]);
    }

    /**
     * A feature test for unlocking an unlocked challenge.
     *
     * @return void
     */
    function test_unlocking_already_unlocked_challenge()
    {
        Track::factory()->create();
        $challenge = Challenge::factory()->create();

        $response = $this->postJson($this->endpoint.$challenge->id);

        $response->assertStatus(400)->assertExactJson([
            'success' => false,
            'message' => 'Challenge is already unlocked!'
        ]);

        $this->assertDatabaseHas('challenges', [
            'id' => $challenge->id,
            'is_locked' => 0
        ]);
    }
}
