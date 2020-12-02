<?php

namespace App\Http\Controllers;

use App\Models\Withdrawal;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    public function index()
    {
        return Withdrawal::with('organization')->get();
    }

    public function store(Request $request)
    {
        return Withdrawal::create($request->all());
    }

    
    public function show($id)
    {
        return Withdrawal::where('user_id', $id)->get();
    }

   
    public function update(Request $request, $id)
    {
        return Withdrawal::find($id)->update($request->all());  
    }

  
    public function destroy($id)
    {
       return Withdrawal::find($id)->delete();
    }
}
