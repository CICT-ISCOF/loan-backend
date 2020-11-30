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

        $user = User::where('username', $data['username'])
            ->with([
                'memberships.organization.loans',
                'memberships.organization.members',
            ])->first();
        if (!$user->confirmation->approved) {
            return response([
                'errors' => [
                    'confirmation' => ['Phone number not verified.'],
                ],
            ], 403);
        }
        if (!$user->approved) {
            return response([
                'errors' => [
                    'approval' => ['Account not yet approved. Please try again later.'],
                ],
            ], 403);
        }
        if (!Hash::check($data['password'], $user->password)) {
            return response(['errors' => ['password' => ['Password incorrect.']], $data], 422);
        }
        $token = $user->createToken($user->username);

        $roles = [
            'admin' => [],
            'bookeeper' => [],
            'staff' => [],
            'member' => [],
        ];

        if ($user->role === 'Normal') {
            foreach ($user->memberships as $membership) {
                $roles[ucfirst($membership->role)][] = $membership;
            }
        }

        return response([
            'user' => $user,
            'token' => $token->plainTextToken,
            'roles' => $roles,
        ]);
    }
}
