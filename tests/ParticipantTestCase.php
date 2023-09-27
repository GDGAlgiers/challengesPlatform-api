<?php

namespace Tests;

use App\Models\Track;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Sanctum\Sanctum;

abstract class ParticipantTestCase extends BaseTestCase
{
    use CreatesApplication, WithFaker, DatabaseMigrations;

    protected $participant;

    public function setUp(): void
    {
        parent::setUp();

        $track = Track::factory()->create();
        $this->participant = User::factory()->create();
        $this->participant->track()->associate($track);

        Sanctum::actingAs(
            $this->participant,
            ['*']
        );
    }
}
