<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        return User::where('id', '!=', $user->id)->with([
            'confirmation',
            'memberships.organization.loans',
            'memberships.organization.members',
        ])->paginate(10);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();
        return User::where('id', '!=', $user->id)
            ->with([
                'confirmation',
                'memberships.organization.loans',
                'memberships.organization.members',
            ])->findOrFail($id);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'username' => ['required', Rule::unique('users', 'username')],
            'password' => ['required', 'string', 'max:255'],
            'position' => ['required'],
            'first_name' => ['required'],
            'last_name' => ['required'],
            'address' => ['required'],
            'number' => ['required', Rule::unique('users', 'number')],
            'role' => ['required', Rule::in(['Super Admin', 'Normal'])],
        ]);

        return User::create($data);
    }

    public function update(Request $request, $id)
    {
        $currentUser = $request->user();
        $user = User::where('id', '!=', $currentUser->id)
            ->with([
                'confirmation',
                'memberships.organization.loans',
                'memberships.organization.members',
            ])->findOrFail($id);

        $data = $request->validate([
            'username' => ['nullable', Rule::unique('users', 'username')],
            'password' => ['nullable', 'string', 'max:255'],
            'position' => ['nullable'],
            'first_name' => ['nullable'],
            'last_name' => ['nullable'],
            'address' => ['nullable'],
            'number' => ['nullable', Rule::unique('users', 'number')],
            'role' => ['nullable', Rule::in(['Super Admin', 'Normal'])],
        ]);

        $user->update($data);

        return $user;
    }

    public function destroy(Request $request, $id)
    {
        $currentUser = $request->user();
        $user = User::where('id', '!=', $currentUser->id)
            ->findOrFail($id);
        $user->delete();

        return response('', 204);
    }
}
