<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class IsMember implements Rule
{
    /**
     * @var \App\Models\Organization
     */
    protected $organization;

    /**
     * Create a new rule instance.
     *
     * @param \App\Models\Organization $organization
     * @return void
     */
    public function __construct($organization)
    {
        $this->organization = $organization;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->organization->members()
            ->where('user_id', $value)
            ->first() !== null;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'User is not a member.';
    }
}
