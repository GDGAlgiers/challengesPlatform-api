<?php

namespace Tests\Feature;

use App\Models\Challenge;
use App\Models\Track;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeleteChallengeTest extends TestCase
{
    private $endpoint = '/api/admin/challenge/delete/';

    /**
     * A feature test for deleting a challenge that doesn't contain attachment.
     *
     * @return void
     */
    public function test_delete_challenge_without_attachment()
    {
        Track::factory()->create();
        $challenge = Challenge::factory()->create();

        Sanctum::actingAs(
            User::factory()->create(['role' => 'admin']),
            ['*']
        );

        $response = $this->deleteJson($this->endpoint.$challenge->id);
        $response->assertStatus(Response::HTTP_OK)->assertExactJson([
            'success' => true,
            'data' => [],
            'message' => 'The challenge was succefully deleted!'
        ]);

        $this->assertDatabaseCount('challenges', 0);
    }

    /**
     * A feature test for deleting a challenge that contains an attachment.
     *
     * @return void
     */
    public function test_delete_challenge_with_attachment()
    {
        Storage::fake('local');
        UploadedFile::fake()->create('attachment.zip', 1024)->store('challenges_attachments');
        Track::factory()->create();
        $challenge = Challenge::factory()->create(['attachment' => 'challenges_attachments/attachment.zip']);

        Sanctum::actingAs(
            User::factory()->create(['role' => 'admin']),
            ['*']
        );
        
        $response = $this->deleteJson($this->endpoint.$challenge->id);

        $response->assertStatus(200)->assertExactJson([
            'success' => true,
            'data' => [],
            'message' => 'The challenge was succefully deleted!'
        ]);

        $this->assertDatabaseCount('challenges', 0);
        Storage::assertMissing($challenge->attachment);
    }
}
