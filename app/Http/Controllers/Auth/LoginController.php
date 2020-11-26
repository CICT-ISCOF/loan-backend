<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'username' => ['required', 'string', Rule::exists('users', 'username')],
            'password' => ['required', 'string'],
        ]);

        $password = $data['password'];

        $user = new User();
        foreach ($data as $key => $value) {
            $user = $user->orWhere($key, $value);
        }
        $user = $user->first();
        if (!Hash::check($password, $user->password)) {
            return response(['errors' => ['password' => ['Password incorrect.']], $data], 422);
        }
        $token = $user->createToken($user->username);

        return response([
            'user' => $user,
            'token' => $token->plainTextToken,
        ]);
    }
}
