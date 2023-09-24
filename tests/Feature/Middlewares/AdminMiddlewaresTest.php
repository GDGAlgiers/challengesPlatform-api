<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminMiddlewaresTest extends TestCase
{
    private $endpoint = "/api/admin/user";
    /**
     * A feature test for unauthenticated user.
     *
     * @return void
     */
    public function test_not_authenticated_request()
    {
        $response = $this->getJson($this->endpoint);

        $response->assertStatus(401)->assertExactJson([
            'success' => false,
            'message' => 'Unauthenticated.'
        ]);
    }


    /**
     * A feature test for a request coming from non admin.
     *
     * @return void
     */
    public function test_not_admin()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $response = $this->getJson($this->endpoint);

        $response->assertStatus(401)->assertExactJson([
            'success' => false,
            'message' => 'You are not authorized'
        ]);
    }
}
