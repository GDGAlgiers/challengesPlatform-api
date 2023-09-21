<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, WithFaker, DatabaseMigrations;
    
    public function setUp(): void
    {
        parent::setUp();
    }
}
