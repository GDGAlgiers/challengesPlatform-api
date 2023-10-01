<?php

namespace Tests\Feature;

use App\Models\Challenge;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\ParticipantTestCase;

class DownloadAttachmentTest extends ParticipantTestCase
{
    private $endpoint = '/api/participant/challenge/';

    /**
     * A feature test for downloading a challenge attachment.
     *
     * @return void
     */
    public function test_download_challenge_attachment()
    {
        Storage::fake('local');
        $mockAttachment = UploadedFile::fake()->create('attachment.zip', 1024)->store('challenges_attachments');
        $challenge = Challenge::factory()->create(['attachment' => $mockAttachment]);
        $this->participant->track->is_locked = false;
        $this->participant->track->save();

        $response = $this->getJson($this->endpoint.$challenge->id.'/download');
        $response->assertStatus(Response::HTTP_OK)->assertDownload($challenge->name);
    }

    /**
     * A feature test for downloading a challenge that does not have attachment.
     *
     * @return void
     */
    public function test_download_challenge_that_does_not_have_attachment()
    {
        $challenge = Challenge::factory()->create();
        $this->participant->track->is_locked = false;
        $this->participant->track->save();

        $response = $this->getJson($this->endpoint.$challenge->id.'/download');
        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertExactJson([
            'success' => false,
            'message' => 'This challenge does not have an attachment'
        ]);
    }

    /**
     * A feature test for downloading a challenge that has attachment but it is deleted.
     *
     * @return void
     */
    public function test_download_challenge_that_its_challenge_is_deleted_from_storage()
    {
        Storage::fake('local');
        $mockAttachment = UploadedFile::fake()->create('attachment.zip', 1024)->store('challenges_attachments');
        $challenge = Challenge::factory()->create(['attachment' => $mockAttachment]);

        $this->participant->track->is_locked = false;
        $this->participant->track->save();

        Storage::delete($challenge->attachment);
        $response = $this->getJson($this->endpoint.$challenge->id.'/download');
        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertExactJson([
            'success' => false,
            'message' => 'Can not find the challenge file, contact the admins'
        ]);
    }

    /**
     * A feature test for downloading an attachment of a challenge that is locked.
     *
     * @return void
     */
    public function test_download_attachment_of_challenge_that_is_locked()
    {
        Storage::fake('local');
        $mockAttachment = UploadedFile::fake()->create('attachment.zip', 1024)->store('challenges_attachments');
        $challenge = Challenge::factory()->create(['attachment' => $mockAttachment, 'is_locked' => 1]);

        $this->participant->track->is_locked = false;
        $this->participant->track->save();

        $response = $this->getJson($this->endpoint.$challenge->id.'/download');
        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson([
            'success' => false,
            'message' => 'This challenge is locked for now'
        ]);
    }

    /**
     * A feature test for downloading an attachment of a challenge that its track is locked.
     *
     * @return void
     */
    public function test_download_attachment_of_challenge_that_its_track_is_locked()
    {
        Storage::fake('local');
        $mockAttachment = UploadedFile::fake()->create('attachment.zip', 1024)->store('challenges_attachments');
        $challenge = Challenge::factory()->create(['attachment' => $mockAttachment]);

        $response = $this->getJson($this->endpoint.$challenge->id.'/download');
        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson([
            'success' => false,
            'message' => 'Submissions can not be accepted now'
        ]);
    }
}
