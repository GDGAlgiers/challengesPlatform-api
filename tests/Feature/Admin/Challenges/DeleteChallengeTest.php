<?php

namespace Tests\Feature;

use App\Models\Challenge;
use App\Models\Track;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\AdminTestCase;

class DeleteChallengeTest extends AdminTestCase
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
        $mockAttachment = UploadedFile::fake()->create('attachment.zip', 1024)->store('challenges_attachments');
        Track::factory()->create();
        $challenge = Challenge::factory()->create(['attachment' => $mockAttachment]);

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
