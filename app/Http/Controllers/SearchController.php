<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function user(Request $request)
    {
        return User::search($request->query)
            ->where('role', $request->role)
            ->get();
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
