<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeleteUserTest extends TestCase
{
    protected $endpoint = '/api/admin/user/delete/';

    /**
     * A feature test for successfull deletion of a user.
     *
     * @return void
     */
    public function test_delete_user_successfully()
    {
        $user = User::factory()->create();

        Sanctum::actingAs(
            User::factory()->create(['role' => 'admin']),
            ['*']
        );

        $response = $this->deleteJson($this->endpoint.$user->id);

        $response->assertStatus(200)->assertExactJson([
            'success' => true,
            'data' => [],
            'message' => 'The user was succefully deleted!'
        ]);

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseMissing('users', [
            'full_name' => $user->full_name
        ]);
    }

    /**
     * A feature test for unsuccessfull deletion because of unexisting user.
     *
     * @return void
     */

     public function test_delete_unexisting_user()
     {
        Sanctum::actingAs(
            User::factory()->create(['role' => 'admin']),
            ['*']
        );

        $response = $this->deleteJson($this->endpoint.'2333');

        $response->assertStatus(400)->assertExactJson([
            'success' => false,
            'message' => 'User can not be found!'
        ]);

        $this->assertDatabaseCount('users', 1);
     }
}
