<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\OrganizationMember;
use App\Models\User;
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

        return $organization->members()
            ->where('role', $request->role ?: 'Member')
            ->with('user')
            ->paginate(10);
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
                return response([
                    'errors' => [
                        'role' => ['Insufficient priviledges.'],
                    ]
                ], 403);
            }
        }

        $data = $request->validate([
            'role' => ['required', Rule::in(['Admin', 'Bookeeper', 'Member', 'Staff'])],
            'username' => ['required'],
            // --
            'password' => [
                Rule::requiredIf(function () use ($request) {
                    return User::where('username', $request->username)
                        ->first() !== null;
                }),
                'string',
                'max:255'
            ],
            'position' => [
                Rule::requiredIf(function () use ($request) {
                    return User::where('username', $request->username)
                        ->first() !== null;
                })
            ],
            'first_name' => [
                Rule::requiredIf(function () use ($request) {
                    return User::where('username', $request->username)
                        ->first() !== null;
                })
            ],
            'last_name' => [
                Rule::requiredIf(function () use ($request) {
                    return User::where('username', $request->username)
                        ->first() !== null;
                })
            ],
            'address' => [
                Rule::requiredIf(function () use ($request) {
                    return User::where('username', $request->username)
                        ->first() !== null;
                })
            ],
            'number' => [
                Rule::requiredIf(function () use ($request) {
                    return User::where('username', $request->username)
                        ->first() !== null;
                })
            ],
            'monthly_salary' => [
                Rule::requiredIf(function () use ($request) {
                    return User::where('username', $request->username)
                        ->first() !== null;
                })
            ],
            'user_id' => [
                Rule::requiredIf(function () use ($request) {
                    return !$request->has('username');
                }),
                Rule::exists('users', 'id'),
                new Confirmed(),
                new IsNotMember($organization),
            ]
        ]);

        $user = User::where('username', $data['username'])
            ->first();

        if (!$user) {
            $user = User::factory()
                ->make($data + ['approved' => true]);
            $user->role = 'Normal';
            $user->save();
            $user->confirmation->approved = true;
            $user->confirmation->save();
        }

        if (!(new Confirmed())->passes('user_id', $user->id)) {
            return response([
                'errors' => [
                    'user_id' => ['User is not verified.']
                ]
            ], 403);
        }

        if (!(new IsNotMember($organization))->passes('user_id', $user->id)) {
            return response([
                'errors' => [
                    'membership' => ['User is already a member.'],
                ]
            ], 403);
        }

        $member = $organization->members()->create($data + [
            'user_id' => $user->id
        ]);
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
        return $organization->members()->with('user')->findOrFail($id);
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
            'role' => ['required', Rule::in(['Admin', 'Bookeeper', 'Member', 'Staff'])],
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
