<?php

namespace App\Http\Controllers;

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
}
