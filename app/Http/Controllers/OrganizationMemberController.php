<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Rules\Confirmed;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrganizationMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Organization $organization)
    {
        $organization->load('members.user');
        return $organization->members;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Organization $organization)
    {
        $data = $request->validate([
            'role' => ['required', Rule::in(['Admin', 'Bookeeper', 'Normal'])],
            'user_id' => [
                'required',
                Rule::exists('users', 'id'),
                new Confirmed(),
                function ($attribute, $value, $fail) use ($organization) {
                    $membership = $organization->members()
                        ->where('user_id', $value)
                        ->first();
                    if ($membership) {
                        $fail("{$attribute} is already a member.");
                    }
                },
            ]
        ]);

        return $organization->members()->create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OrganizationMember  $organizationMember
     * @return \Illuminate\Http\Response
     */
    public function show(Organization $organization, $id)
    {
        return $organization->members()->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OrganizationMember  $organizationMember
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Organization $organization, $id)
    {
        $membership = $organization->members()->findOrFail($id);

        $data = $request->validate([
            'role' => ['nullable', Rule::in(['Admin', 'Bookeeper', 'Normal'])],
        ]);

        $membership->update($data);

        return $membership;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OrganizationMember  $organizationMember
     * @return \Illuminate\Http\Response
     */
    public function destroy(Organization $organization, $id)
    {
        $membership = $organization->members()->findOrFail($id);
        $membership->delete();

        return response('', 204);
    }
}
