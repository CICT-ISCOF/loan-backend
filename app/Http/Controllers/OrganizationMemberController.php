<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Rules\Confirmed;
use App\Rules\IsNotMember;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrganizationMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Organization $organization)
    {
        if (!$request->user()->isAdmin()) {
            $membership = $request->user()
                ->memberships()
                ->where('organization_id', $organization->id)
                ->first();
            if (!$membership) {
                return response('', 403);
            }
        }
        $organization->load(['members.user']);
        return $organization->members()->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Organization $organization)
    {
        if (!$request->user()->isAdmin()) {
            $membership = $request->user()
                ->memberships()
                ->where('organization_id', $organization->id)
                ->first();
            if (!$membership || $membership->role === 'Member') {
                return response('', 403);
            }
        }

        $data = $request->validate([
            'role' => ['required', Rule::in(['Admin', 'Bookeeper', 'Member'])],
            'user_id' => [
                'required',
                Rule::exists('users', 'id'),
                new Confirmed(),
                new IsNotMember($organization),
            ]
        ]);

        $member = $organization->members()->create($data);
        return response($member, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OrganizationMember  $organizationMember
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Organization $organization, $id)
    {
        if (!$request->user()->isAdmin()) {
            $membership = $request->user()
                ->memberships()
                ->where('organization_id', $organization->id)
                ->first();
            if (!$membership || $membership->role === 'Member') {
                return response('', 403);
            }
        }
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
        if (!$request->user()->isAdmin()) {
            $membership = $request->user()
                ->memberships()
                ->where('organization_id', $organization->id)
                ->first();
            if (!$membership || $membership->role === 'Member') {
                return response('', 403);
            }
        }
        $membership = $organization->members()->findOrFail($id);

        $data = $request->validate([
            'role' => ['nullable', Rule::in(['Admin', 'Bookeeper', 'Member'])],
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
    public function destroy(Request $request, Organization $organization, $id)
    {
        if (!$request->user()->isAdmin()) {
            $membership = $request->user()
                ->memberships()
                ->where('organization_id', $organization->id)
                ->first();
            if (!$membership || $membership->role === 'Member') {
                return response('', 403);
            }
        }

        $membership = $organization->members()->findOrFail($id);
        $membership->delete();

        return response('', 204);
    }
}
