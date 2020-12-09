<?php

namespace App\Http\Controllers;

use App\Models\Savings;
use Illuminate\Http\Request;

class SavingsController extends Controller
{
    public function index()
    {
        return Savings::with('user')->get();
    }

    public function store(Request $request)
    {
        return Savings::create($request->all());
    }


    public function show(Savings $savings)
    {
        $savings->load('user');
        return $savings;
    }


    public function update(Request $request, Savings $savings)
    {
        $savings->update($request->all());
        $savings->load('user');
        return $savings;
    }


    public function destroy(Savings $savings)
    {
        $savings->delete();
        return response('', 204);
    }
}
