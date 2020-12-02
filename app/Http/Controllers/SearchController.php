<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\OrganizationMember;
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
            if ($organization) {
                $ids = $users->map(function ($user) {
                    return $user->id;
                })
                    ->all();
                $members = OrganizationMember::where('organization_id', $organization->id)
                    ->whereIn('user_id', $ids)
                    ->get();

                $ids = $members->map(function ($member) {
                    return $member->user_id;
                })->all();

                $users = User::whereIn('id', $ids)->get();
            }
        }

        return $users->all();
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
