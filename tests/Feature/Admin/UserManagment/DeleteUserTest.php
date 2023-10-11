<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\AdminTestCase;

class DeleteUserTest extends AdminTestCase
{
    protected $endpoint = '/api/admin/user/';

    /**
     * A feature test for successfull deletion of a user.
     *
     * @return void
     */
    public function test_delete_user_successfully()
    {
        $user = User::factory()->create();

        $response = $this->deleteJson($this->endpoint.$user->id.'/delete');

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
        $response = $this->deleteJson($this->endpoint.'2333'.'/delete');

        $response->assertStatus(400)->assertExactJson([
            'success' => false,
            'message' => 'User can not be found!'
        ]);

        $this->assertDatabaseCount('users', 1);
     }
}
