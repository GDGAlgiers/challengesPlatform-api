<?php

namespace Tests\Feature;

use App\Models\Track;
use App\Models\User;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreateTrackTest extends TestCase
{
    private $endpoint = '/api/admin/track/create';

    /**
     * A feature test for creating a track.
     *
     * @return void
     */
    public function test_create_track()
    {
        $payload = [
            'type' => $this->faker->text(8),
            'description' => $this->faker->text(30)
        ];

        Sanctum::actingAs(User::factory()->create(['role' => 'admin']), ['*']);


        $response = $this->postJson($this->endpoint, $payload);

        $response->assertStatus(Response::HTTP_OK)->assertExactJson([
            'success' => true,
            'data' => [
                'type' => $payload['type'],
                'description' => $payload['description'],
                'number_of_challenges' => 0,
                'is_locked' => true
            ],
            'message' => 'Track was succefully created!'
        ]);

        $this->assertDatabaseCount('tracks', 1);
        $this->assertDatabaseHas('tracks', [
            'type' => $payload['type'],
            'description' => $payload['description'],
            'is_locked' => 1
        ]);
    }

    /**
     * A feature test for creating a track without data.
     *
     * @return void
     */
    public function test_creating_track_without_data()
    {
        $payload = [];

        Sanctum::actingAs(User::factory()->create(['role' => 'admin']), ['*']);


        $response = $this->postJson($this->endpoint, $payload);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson([
            'success' => false,
            'message' => 'Validation failed',
            'data' => [
                'type' => ['The type field is required.'],
                'description' => ['The description field is required.']
            ],
        ]);

        $this->assertDatabaseCount('tracks', 0);
    }

    /**
     * A feature test for creating a track that exists.
     *
     * @return void
     */
    public function test_create_track_that_exists()
    {
        $track = Track::factory()->create();

        $payload = [
            'type' => $track->type,
            'description' => $this->faker->text(30)
        ];

        Sanctum::actingAs(User::factory()->create(['role' => 'admin']), ['*']);


        $response = $this->postJson($this->endpoint, $payload);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson([
            'success' => false,
            'message' => 'Validation failed',
            'data' => [
                'type' => ['The type has already been taken.'],
            ],
        ]);

        $this->assertDatabaseCount('tracks', 1);
    }
}
