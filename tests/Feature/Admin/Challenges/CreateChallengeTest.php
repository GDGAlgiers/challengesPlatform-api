<?php

namespace Tests\Feature;

use App\Models\Track;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\AdminTestCase;

class CreateChallengeTest extends AdminTestCase
{
    private $endpoint = '/api/admin/challenge/create';

    /**
     * A feature test for creating a challenge without an attachment successfully.
     *
     * @return void
     */
    public function test_create_challenge_without_attachment()
    {
        $track = Track::factory()->create();
        $payload = [
            'track' => $track->type,
            'name' => $this->faker->name(),
            'author' => $this->faker->name(),
            'difficulty' => 'easy',
            'description' => $this->faker->text(50),
            'max_tries' => rand(1, 10),
            'requires_judge' => false,
            'points' => rand(10, 500),
        ];

        $response = $this->postJson($this->endpoint, $payload);

        $response->assertStatus(Response::HTTP_CREATED)->assertExactJson([
            'success' => true,
            'data' => [
                'track' => $payload['track'],
                'name' => $payload['name'],
                'author' => $payload['author'],
                'difficulty' => $payload['difficulty'],
                'description' => $payload['description'],
                'points' => $payload['points'],
                'requires_judge' => $payload['requires_judge'],
                'max_tries' => $payload['max_tries'],
                'has_attachment' => false,
                'is_locked' => false,
                'external_resource' => null
            ],
            'message' => 'The challenge was succefully added!'
        ]);

        $this->assertDatabaseCount('challenges', 1);
        $this->assertDatabaseHas('challenges', [
            'track_id' => $track->id,
            'name' => $payload['name'],
            'author' => $payload['author'],
            'difficulty' => $payload['difficulty'],
            'description' => $payload['description'],
            'points' => $payload['points'],
            'requires_judge' => 0,
            'max_tries' => $payload['max_tries'],
            'attachment' => null,
            'is_locked' => 0,
            'external_resource' => null
        ]);
    }

    /**
     * A feature test for creating a challenge without providing data.
     *
     * @return void
     */
    public function test_attempt_creating_challenge_without_data()
    {
        $payload = [];

        $response = $this->postJson($this->endpoint, $payload);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson([
            'success' => false,
            'message' => 'Validation failed',
            'data' => [
                'track' => ['The track field is required.'],
                'name' => ['The name field is required.'],
                'author' => ['The author field is required.'],
                'difficulty' => ['The difficulty field is required.'],
                'description' => ['The description field is required.'],
                'max_tries' => ['The max tries field is required.'],
                'requires_judge' => ['The requires judge field is required.'],
                'points' => ['The points field is required.'],
            ]
        ]);

        $this->assertDatabaseCount('challenges', 0);
    }

    /**
     * A feature test for creating a challenge with an attachment.
     *
     * @return void
     */
    public function test_create_challenge_with_unexisting_track()
    {
        $payload = [
            'track' => $this->faker->name(),
        ];

        $response = $this->postJson($this->endpoint, $payload);
        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertJson([
            'success' => false,
            'message' => 'Validation failed',
            'data' => [
                'track' => ['The selected track is invalid.']
            ]
        ]);
    }

    /**
     * A feature test for creating a challenge with an attachment.
     *
     * @return void
     */
    public function test_create_challenge_with_attachment()
    {
        Storage::fake('local');
        $mockAttachment = UploadedFile::fake()->create('mockFile.zip', 1024);
        $track = Track::factory()->create();
        $payload = [
            'track' => $track->type,
            'name' => $this->faker->name(),
            'author' => $this->faker->name(),
            'difficulty' => 'easy',
            'description' => $this->faker->text(50),
            'max_tries' => rand(1, 10),
            'requires_judge' => false,
            'points' => rand(10, 500),
            'attachment' => $mockAttachment
        ];

        $response = $this->postJson($this->endpoint, $payload);

        $response->assertStatus(Response::HTTP_CREATED)->assertExactJson([
            'success' => true,
            'data' => [
                'track' => $payload['track'],
                'name' => $payload['name'],
                'author' => $payload['author'],
                'difficulty' => $payload['difficulty'],
                'description' => $payload['description'],
                'points' => $payload['points'],
                'requires_judge' => $payload['requires_judge'],
                'max_tries' => $payload['max_tries'],
                'has_attachment' => true,
                'is_locked' => false,
                'external_resource' => null
            ],
            'message' => 'The challenge was succefully added!'
        ]);
        Storage::assertExists('challenges_attachments/'.$mockAttachment->hashName());
        $this->assertDatabaseCount('challenges', 1);
        $this->assertDatabaseHas('challenges', [
            'track_id' => $track->id,
            'name' => $payload['name'],
            'author' => $payload['author'],
            'difficulty' => $payload['difficulty'],
            'description' => $payload['description'],
            'points' => $payload['points'],
            'requires_judge' => 0,
            'max_tries' => $payload['max_tries'],
            'is_locked' => 0,
            'external_resource' => null
        ]);
    }

    /**
     * A feature test for creating a challenge with an attachment.
     *
     * @return void
     */
    public function test_creating_challenge_with_too_big_attachment()
    {
        Storage::fake('local');
        $mockAttachment = UploadedFile::fake()->create('mockFile.zip', 3072); // 3KB
        $track = Track::factory()->create();
        $payload = [
            'track' => $track->type,
            'name' => $this->faker->name(),
            'author' => $this->faker->name(),
            'difficulty' => 'easy',
            'description' => $this->faker->text(50),
            'max_tries' => rand(1, 10),
            'requires_judge' => false,
            'points' => rand(10, 500),
            'attachment' => $mockAttachment
        ];

        $response = $this->postJson($this->endpoint, $payload);
        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson([
            'success' => false,
            'message' => 'Validation failed',
            'data' => [
                'attachment' => ['The attachment must not be greater than 2024 kilobytes.']
            ]
        ]);

        Storage::assertMissing('challenges_attachments/'.$mockAttachment->hashName());
        $this->assertDatabaseCount('challenges', 0);
    }

    /**
     * A feature test for creating a challenge with invalid attachment.
     *
     * @return void
     */
    public function test_create_challenge_with_invalid_attachment()
    {
        Storage::fake('local');
        $mockAttachment = UploadedFile::fake()->create('mockFile.xls', 1024);
        $payload = [
            'attachment' => $mockAttachment
        ];

        $response = $this->postJson($this->endpoint, $payload);
        $response
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonPath('data.attachment', ['The attachment must be a file of type: zip, pdf, txt.'])
        ;

        Storage::assertMissing('challenges_attachments/'.$mockAttachment->hashName());
        $this->assertDatabaseCount('challenges', 0);
    }
}
