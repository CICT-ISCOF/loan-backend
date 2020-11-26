<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test registering a user.
     * 
     * @return void
     */
    public function testRegisterUser()
    {
        $response = $this->post('/api/register', [
            'username' => $this->faker->userName,
            'password' => $this->faker->password,
            'number' => '+639502794623',
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'address' => $this->faker->address,
            'position' => $this->faker->text,
        ], ['Accept' => 'application/json']);

        $response->assertCreated();
    }

    /**
     * Test login.
     * 
     * @return void
     */
    public function testLoginUser()
    {
        $user = User::factory()->create();

        $response = $this->post('/api/login', [
            'username' => $user->username,
            'password' => 'password',
        ]);

        $response->assertOk();
    }
}
