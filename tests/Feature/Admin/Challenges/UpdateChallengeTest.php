<?php

namespace Tests\Feature;

use App\Models\Challenge;
use App\Models\Track;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\AdminTestCase;

class UpdateChallengeTest extends AdminTestCase
{
    private $endpoint = '/api/admin/challenge/';

    /**
     * A feature test for updating challenge that doesn't have an attachment.
     *
     * @return void
     */
    public function test_update_challenge_without_attachment()
    {
        $track = Track::factory()->create();
        $challenge = Challenge::factory()->create();
        $payload = [
            'track' => $track->type,
            'name' => $this->faker->name(),
            'author' => $this->faker->name(),
            'difficulty' => 'medium',
            'description' => $this->faker->text(50),
            'max_tries' => rand(1, 10),
            'requires_judge' => false,
            'solution' => 'solution',
            'points' => rand(10, 500),
        ];

        $response = $this->postJson($this->endpoint.$challenge->id.'/update', $payload);
        $response->assertStatus(200)->assertExactJson([
            'success' => true,
            'data' => [
                'track' => $payload['track'],
                'name' => $payload['name'],
                'author' => $payload['author'],
                'difficulty' => $payload['difficulty'],
                'description' => $payload['description'],
                'max_tries' => $payload['max_tries'],
                'requires_judge' => $payload['requires_judge'],
                'points' => $payload['points'],
                'is_locked' => false,
                'has_attachment' => false,
                'external_resource' => null
            ],
            'message' => 'The challenge was succefully updated!'
        ]);
        $challenge = Challenge::find(1);

        $this->assertDatabaseHas('challenges', [
            'track_id' => $track->id,
            'name' => $payload['name'],
            'author' => $payload['author'],
            'difficulty' => $payload['difficulty'],
            'description' => $payload['description'],
            'max_tries' => $payload['max_tries'],
            'points' => $payload['points'],
        ]);
        $this->assertTrue(Hash::check($payload['solution'], $challenge['solution']));
    }

    /**
     * A feature test for updating challenge infos that has attachment without updating attachment.
     *
     * @return void
     */
    public function test_update_challenge_that_has_attachment_without_updating_attachment()
    {
        Storage::fake('local');
        $mockAttachment = UploadedFile::fake()->create('attachment.zip', 1024)->store('challenges_attachments');
        $track = Track::factory()->create();
        $oldChallenge = Challenge::factory()->create(['attachment' => $mockAttachment]);
        $payload = [
            'track' => $track->type,
            'name' => $this->faker->name(),
            'author' => $this->faker->name(),
            'difficulty' => 'medium',
            'description' => $this->faker->text(50),
            'max_tries' => rand(1, 10),
            'requires_judge' => false,
            'solution' => 'solution',
            'points' => rand(10, 500),
        ];

        $response = $this->postJson($this->endpoint.$oldChallenge->id.'/update', $payload);
        $response->assertStatus(200)->assertExactJson([
            'success' => true,
            'data' => [
                'track' => $payload['track'],
                'name' => $payload['name'],
                'author' => $payload['author'],
                'difficulty' => $payload['difficulty'],
                'description' => $payload['description'],
                'max_tries' => $payload['max_tries'],
                'requires_judge' => $payload['requires_judge'],
                'points' => $payload['points'],
                'is_locked' => false,
                'has_attachment' => true,
                'external_resource' => null
            ],
            'message' => 'The challenge was succefully updated!'
        ]);
        $updatedChallenge = Challenge::find(1);

        $this->assertDatabaseHas('challenges', [
            'track_id' => $track->id,
            'name' => $payload['name'],
            'author' => $payload['author'],
            'difficulty' => $payload['difficulty'],
            'description' => $payload['description'],
            'max_tries' => $payload['max_tries'],
            'points' => $payload['points'],
            'attachment' => $mockAttachment
        ]);
        $this->assertTrue(Hash::check($payload['solution'], $updatedChallenge['solution']));
        $this->assertEquals($oldChallenge->attachment, $updatedChallenge->attachment);
        Storage::assertExists($oldChallenge->attachment);
    }

    /**
     * A feature test for updating challenge infos that has attachment with updating attachment.
     *
     * @return void
     */
    public function test_update_challenge_that_has_attachment_with_updating_attachment()
    {
        Storage::fake('local');
        $mockAttachment = UploadedFile::fake()->create('attachment.zip', 1024)->store('challenges_attachments');
        $track = Track::factory()->create();
        $oldChallenge = Challenge::factory()->create(['attachment' => $mockAttachment]);

        $mockAttachment = UploadedFile::fake()->create('mockAttachment.zip', 1024);
        $payload = [
            'track' => $track->type,
            'name' => $this->faker->name(),
            'author' => $this->faker->name(),
            'difficulty' => 'medium',
            'description' => $this->faker->text(50),
            'max_tries' => rand(1, 10),
            'requires_judge' => false,
            'points' => rand(10, 500),
            'attachment' => $mockAttachment
        ];

        $response = $this->postJson($this->endpoint.$oldChallenge->id.'/update', $payload);
        $response->assertStatus(200)->assertExactJson([
            'success' => true,
            'data' => [
                'track' => $payload['track'],
                'name' => $payload['name'],
                'author' => $payload['author'],
                'difficulty' => $payload['difficulty'],
                'description' => $payload['description'],
                'max_tries' => $payload['max_tries'],
                'requires_judge' => $payload['requires_judge'],
                'points' => $payload['points'],
                'is_locked' => false,
                'has_attachment' => true,
                'external_resource' => null
            ],
            'message' => 'The challenge was succefully updated!'
        ]);
        $updatedChallenge = Challenge::find(1);

        $this->assertDatabaseHas('challenges', [
            'track_id' => $track->id,
            'name' => $payload['name'],
            'author' => $payload['author'],
            'difficulty' => $payload['difficulty'],
            'description' => $payload['description'],
            'max_tries' => $payload['max_tries'],
            'points' => $payload['points'],
            'attachment' => 'challenges_attachments/'.$mockAttachment->hashName()
        ]);

        Storage::assertExists($updatedChallenge->attachment);
        Storage::assertMissing($oldChallenge->attachment);
    }
}
