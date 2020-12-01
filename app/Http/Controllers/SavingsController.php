<?php

namespace App\Http\Controllers;

use App\Models\Savings;
use Illuminate\Http\Request;

class SavingsController extends Controller
{
   
    public function index()
    {
        return Savings::with('organization')->get();
    }

    public function store(Request $request)
    {
        return Savings::create($request->all());
    }

    
    public function show($id)
    {
        return Savings::where('user_id', $id)->get();
    }

   
    public function update(Request $request, $id)
    {
        return Savings::find($id)->update($request->all());  
    }

  
    public function destroy($id)
    {
       return Savings::find($id)->delete();
    }
}
