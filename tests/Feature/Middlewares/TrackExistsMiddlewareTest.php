<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TrackExistsMiddlewareTest extends TestCase
{
    /**
     * A feature test for EnsureTrackExists Middleware.
     *
     * @return void
     */
    public function test_EnsureTrackExists_middleware()
    {
        Sanctum::actingAs(User::factory()->create(['role' => 'admin']), ['*']);

        $response = $this->postJson('/api/admin/track/update/111');

        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson([
            'success' => false,
            'message' => 'Track can not be found!'
        ]);
    }
}
