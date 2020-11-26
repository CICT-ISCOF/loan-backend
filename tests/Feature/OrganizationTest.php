<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class OrganizationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $headers = ['Accept' => 'application/json'];

    protected function boot(): User
    {
        return Sanctum::actingAs(User::factory()->create([
            'role' => 'Super Admin'
        ]), ['*']);
    }

    public function testFetchOrganizations()
    {
        $this->boot();
        $response = $this->get('/api/organizations', $this->headers);

        $response->assertOk();
    }

    public function testCreateOrganization()
    {
        $this->boot();
        $response = $this->post('/api/organizations', [
            'name' => $this->faker->name,
        ], $this->headers);

        $response->assertCreated();
    }

    public function testUpdateOrganization()
    {
        $this->boot();
        $organization = Organization::factory()->create();
        $response = $this->put("/api/organizations/{$organization->id}", [
            'name' => $this->faker->name,
        ], $this->headers);

        $response->assertOk();
    }

    public function testDeleteOrganization()
    {
        $this->boot();
        $organization = Organization::factory()->create();
        $response = $this->delete("/api/organizations/{$organization->id}", [], $this->headers);

        $response->assertNoContent();
    }
}
