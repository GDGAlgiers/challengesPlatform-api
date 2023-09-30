<?php

namespace Tests;

use App\Models\Track;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Sanctum\Sanctum;

abstract class JudgeTestCase extends BaseTestCase
{
    use CreatesApplication, WithFaker, DatabaseMigrations;

    protected $judge;

    public function setUp(): void
    {
        parent::setUp();

        $track = Track::factory()->create();
        $this->judge = User::factory()->create(['role' => 'judge']);
        $this->judge->track()->associate($track);

        Sanctum::actingAs(
            $this->judge,
            ['*']
        );
    }
}
