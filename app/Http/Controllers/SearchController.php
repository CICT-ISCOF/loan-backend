<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function user(Request $request)
    {
        $users = new Collection(User::search($request->input('query'))
            ->get()
            ->all());

        $users->load([
            'confirmation',
            'memberships.organization.loans',
            'memberships.organization.members',
        ]);

        if ($request->has('role')) {
            $role = $request->input('role');

            $users = $users->filter(function ($user) use ($role) {
                foreach ($user->memberships as $membership) {
                    if ($membership->role === $role) {
                        return true;
                    }
                }
                return false;
            });
        }

        if ($request->has('organization_id')) {
            $organization = Organization::find($request->input('organization_id'));
            $members = $organization->members()
                ->whereIn('user_id', $users->map(function ($user) {
                    return $user->id;
                })
                    ->all())
                ->with('user')
                ->get();

            $users = $members->map(function ($member) {
                return $member->user;
            });
        }

        return $users;
    }

    public function members(Request $request, Organization $organization)
    {
        $builder = $organization->members()->with('user');
        if ($request->has('role')) {
            $builder = $builder->{$request->role}();
        }
        return $builder->get();
    }
}
