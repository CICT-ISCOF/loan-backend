<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class MemberTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $headers = ['Accept' => 'application/json'];

    protected function boot($url = '')
    {
        Sanctum::actingAs(User::factory()->create([
            'role' => 'Super Admin',
        ]), ['*']);

        $organization = Organization::factory()->create();
        return "/api/organizations/{$organization->id}/members{$url}";
    }

    public function testFetchMembers()
    {
        $response = $this->get($this->boot('/'), $this->headers);

        $response->assertOk();
    }

    public function testCreateMember()
    {
        $user = User::factory()->create();
        $response = $this->post($this->boot('/'), [
            'role' => 'Admin',
            'user_id' => $user->id,
        ], $this->headers);

        $response->assertCreated();
    }

    public function testUpdateMember()
    {
        $this->boot();
        $organization = Organization::factory()->create();
        $member = $organization->members()->create([
            'user_id' => User::factory()->create()->id,
            'role' => 'Admin',
        ]);
        $url = "/api/organizations/{$organization->id}/members/{$member->id}";

        $response = $this->put($url, [
            'role' => 'Bookeeper',
        ], $this->headers);

        $response->assertOk();
    }

    public function testDeleteMember()
    {
        $this->boot();
        $organization = Organization::factory()->create();
        $member = $organization->members()->create([
            'user_id' => User::factory()->create()->id,
            'role' => 'Admin',
        ]);
        $url = "/api/organizations/{$organization->id}/members/{$member->id}";

        $response = $this->delete($url, [], $this->headers);

        $response->assertNoContent();
    }
}
