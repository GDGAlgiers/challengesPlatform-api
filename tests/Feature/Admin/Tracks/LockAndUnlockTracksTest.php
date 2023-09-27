<?php

namespace Tests\Feature;

use App\Models\Track;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LockAndUnlockTracksTest extends TestCase
{
    /**
     * A feature test for locking all tracks.
     *
     * @return void
     */
    public function test_lock_all_tracks()
    {
        $tracks = Track::factory()->count(3)->create(['is_locked' => 0]);

        Sanctum::actingAs(User::factory()->create(['role' => 'admin']), ['*']);

        $response = $this->postJson('/api/admin/track/lock-all');

        $response->assertStatus(200)->assertExactJson([
            'success' => true,
            'data' => [],
            'message' => 'Tracks were succefully locked'
        ]);

        foreach($tracks as $track) {
            $this->assertDatabaseHas('tracks', [
                'type' => $track->type,
                'is_locked' => 1
            ]);
        }
    }

    /**
     * A feature test for unlocking all tracks.
     *
     * @return void
     */
    public function test_unlock_all_tracks()
    {
        $tracks = Track::factory()->count(3)->create(['is_locked' => 1]);

        Sanctum::actingAs(User::factory()->create(['role' => 'admin']), ['*']);

        $response = $this->postJson('/api/admin/track/unlock-all');

        $response->assertStatus(200)->assertExactJson([
            'success' => true,
            'data' => [],
            'message' => 'Tracks were succefully unlocked'
        ]);

        foreach($tracks as $track) {
            $this->assertDatabaseHas('tracks', [
                'type' => $track->type,
                'is_locked' => 0
            ]);
        }
    }

    /**
     * A feature test for locking a specific track.
     *
     * @return void
     */
    public function test_lock_track()
    {
        $track = Track::factory()->create(['is_locked' => 0]);

        Sanctum::actingAs(User::factory()->create(['role' => 'admin']), ['*']);

        $response = $this->postJson('/api/admin/track/lock/'.$track->id);

        $response->assertStatus(200)->assertExactJson([
            'success' => true,
            'data' => [],
            'message' => 'Track was succefully locked'
        ]);

        $this->assertDatabaseHas('tracks', [
            'type' => $track->type,
            'is_locked' => 1
        ]);
    }

    /**
     * A feature test for unlocking a specific track.
     *
     * @return void
     */
    public function test_unlock_track()
    {
        $track = Track::factory()->create(['is_locked' => 1]);

        Sanctum::actingAs(User::factory()->create(['role' => 'admin']), ['*']);

        $response = $this->postJson('/api/admin/track/unlock/'.$track->id);

        $response->assertStatus(200)->assertExactJson([
            'success' => true,
            'data' => [],
            'message' => 'Track was succefully unlocked'
        ]);

        $this->assertDatabaseHas('tracks', [
            'type' => $track->type,
            'is_locked' => 0
        ]);
    }
}
