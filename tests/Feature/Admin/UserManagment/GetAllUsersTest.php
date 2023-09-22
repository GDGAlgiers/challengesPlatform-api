<?php

namespace Tests\Feature\Admin\UserManagment;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetAllUsersTest extends TestCase
{
    private $endpoint = '/api/admin/user';
    /**
     * A feature test for getting all users.
     *
     * @return void
     */
    public function test_get_all_users()
    {
        User::factory()->count(2)->create();

        Sanctum::actingAs(
            User::factory()->create(['role' => 'admin']),
            ['*']
        );
        
        $response = $this->getJson($this->endpoint);

        $response->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => [
                '*' => [
                    'id',
                    'full_name',
                    'role',
                    'track',
                    'points'
                ]
            ],
            'message'
        ]);
    }
}
