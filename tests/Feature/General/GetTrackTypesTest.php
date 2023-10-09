<?php

namespace Tests\Feature;

use App\Models\Track;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class GetTrackTypesTest extends TestCase
{
    private $endpoint = "/api/tracks";
    /**
     * A feature test for testing getting all tracks types
     *
     * @return void
     */
    public function test_get_tracks_types()
    {
        $tracks = Track::factory()->count(2)->create();

        $response = $this->getJson($this->endpoint);

        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure([
            'success',
            'message',
            'data'
        ]);

        $this->assertTrue($response['success']);
        $this->assertEquals('Successfully retrieved all tracks types', $response['message']);

        foreach($tracks as $track) {
            $this->assertTrue(in_array($track->type, $response['data']));
        }
    }
}
