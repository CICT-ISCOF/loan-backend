<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserLoanController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = User::find($request->input('user_id'));
        return $user->loans()->paginate(10);
    }
}
