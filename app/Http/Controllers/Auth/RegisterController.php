<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
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
            'username' => ['required', Rule::unique('users', 'username')],
            'password' => ['required', 'string', 'max:255'],
            'position' => ['required'],
            'first_name' => ['required'],
            'last_name' => ['required'],
            'address' => ['required'],
            'number' => ['required', Rule::unique('users', 'number')],
            'monthly_salary' => ['required'],
            'account_number' => ['nullable'],
            'net_pay' => ['nullable'],
        ]);

        return User::create($data);
    }
}
