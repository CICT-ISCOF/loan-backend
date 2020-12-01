<?php

namespace App\Http\Controllers;
use App\Models\Organization;
use App\Models\LoanTerms;
use Illuminate\Http\Request;



class LoanTermsController extends Controller
{
   
    public function index()
    {
        return LoanTerms::all();
    }

    public function store(Request $request)
    {
       return LoanTerms::create($request->all());
    }

   
    public function show($id)
    {
        return LoanTerms::where('organization_id', $id)->get();
    }

    
    public function update(Request $request,$id)
    {
        return LoanTerms::find($id)->update($request->all());  
    }

    
    public function destroy(LoanTerms $loanTerms)
    {
        return LoanTerms::find($loanTerms)->delete();
    }
}
