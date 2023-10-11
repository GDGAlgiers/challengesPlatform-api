<?php

namespace Tests\Feature;

use App\Models\Track;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RateLimitTest extends TestCase
{

    /**
     * A feature test for testing Rate Limit.
     *
     * @return void
     */
    public function test_rate_limit()
    {
        $track = Track::factory()->create();
        Sanctum::actingAs(User::factory()->create(), ['*']);

        $response = $this->getJson('/api/track/'.$track->type.'/leaderboard');
        $response
            ->assertStatus(200)
            ->assertHeader('X-Ratelimit-Limit', 40)
            ->assertHeader('X-Ratelimit-Remaining', 39)
        ;
    }

    /**
     * A feature test for testing that Rate Limit is decreasing.
     *
     * @return void
     */
    public function test_rate_limit_is_decreasing()
    {
        $track = Track::factory()->create();
        Sanctum::actingAs(User::factory()->create(), ['*']);

        for ($i = 40; $i >=1 ; $i--) {
            $response = $this->getJson('/api/track/'.$track->type.'/leaderboard');
            $response
                ->assertStatus(200)
                ->assertHeader('X-Ratelimit-Limit', 40)
                ->assertHeader('X-Ratelimit-Remaining', $i -1)
            ;
        }
    }
}
