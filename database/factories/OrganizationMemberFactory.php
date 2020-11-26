<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\OrganizationMember;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrganizationMemberFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrganizationMember::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $organization = Organization::factory()->create();
        $user = User::factory()->create();
        return [
            'organization_id' => $organization->id,
            'user_id' => $user->id,
            'role' => 'Admin',
        ];
    }
}
