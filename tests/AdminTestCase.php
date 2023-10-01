<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Sanctum\Sanctum;

abstract class AdminTestCase extends BaseTestCase
{
    use CreatesApplication, WithFaker, DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        Sanctum::actingAs(
            User::factory()->create(['role' => 'admin']),
            ['*']
        );
    }
}
