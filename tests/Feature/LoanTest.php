<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LoanTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $headers = ['Accept' => 'application/json'];

    protected function boot($url = '')
    {
        Sanctum::actingAs(User::factory()->create([
            'role' => 'Super Admin',
        ]), ['*']);

        $organization = Organization::factory()->create();
        return "/api/organizations/{$organization->id}/loans{$url}";
    }

    public function testFetchLoans()
    {
        $response = $this->get($this->boot('/'), $this->headers);

        $response->assertOk();
    }

    public function testCreateLoan()
    {
        $this->boot();
        $organization = Organization::factory()->create();
        $url = "/api/organizations/{$organization->id}/loans";
        $user = User::factory()->create([
            'role' => 'Super Admin',
        ]);
        $organization->members()->create([
            'user_id' => $user->id,
            'role' => 'Member',
        ]);
        $response = $this->post($url, [
            'user_id' => $user->id,
            'status' => 'New',
            'type' => 'Regular',
            'amount' => $this->faker->randomDigit,
            'charges' => $this->faker->sentence,
            'terms' => $this->faker->text,
            'comaker_id' => User::factory()->create()->id,
        ], $this->headers);

        $response->assertCreated();
    }

    public function testUpdateLoan()
    {
        $this->boot();
        $organization = Organization::factory()->create();
        $user = User::factory()->create();
        $organization->members()->create([
            'user_id' => $user->id,
            'role' => 'Member',
        ]);
        $loan = $organization->loans()->create([
            'user_id' => $user->id,
            'role' => 'Admin',
            'status' => 'New',
            'type' => 'Regular',
            'amount' => $this->faker->randomDigit,
            'charges' => $this->faker->sentence,
            'terms' => $this->faker->text,
            'comaker_id' => User::factory()->create(),
        ]);
        $url = "/api/organizations/{$organization->id}/loans/{$loan->id}";

        $response = $this->put($url, [
            'status' => 'New',
            'type' => 'Regular',
            'amount' => $this->faker->randomDigit,
            'charges' => $this->faker->sentence,
            'terms' => $this->faker->text,
        ], $this->headers);

        $response->assertOk();
    }

    public function testDeleteLoan()
    {
        $this->boot();
        $organization = Organization::factory()->create();
        $loan = $organization->loans()->create([
            'user_id' => User::factory()->create()->id,
            'role' => 'Admin',
            'status' => 'New',
            'type' => 'Regular',
            'amount' => $this->faker->randomDigit,
            'charges' => $this->faker->sentence,
            'terms' => $this->faker->text,
            'comaker_id' => User::factory()->create(),
        ]);
        $url = "/api/organizations/{$organization->id}/loans/{$loan->id}";

        $response = $this->delete($url, [], $this->headers);

        $response->assertNoContent();
    }
}
