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

        $data = [];

        foreach ($users->all() as $user) {
            $data[] = $user;
        }
        return $data;
    }
}
