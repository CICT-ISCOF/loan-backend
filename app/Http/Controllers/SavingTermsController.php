<?php
 
namespace App\Http\Controllers;
use App\Models\Organization;
use App\Models\SavingTerms;
use Illuminate\Http\Request;

class SavingTermsController extends Controller
{
     
    public function index()
    {
       return  SavingTerms::all();
    }

   
    public function store(Request $request, Organization $organization)
    {
         $savingTerms = SavingTerms::where('organization_id',$request->input('organization_id'))->get();
         if(count($savingTerms) == 0 ){
           $newSavingTerms = SavingTerms::create($request->all());
           return $newSavingTerms;
         }
         return response('Cooperative has already set-iup its saving terms',400 );
    }

    public function show($id)
    {
        return SavingTerms::where('organization_id', $id)->first();
    }

  

    public function update(Request $request, $id)
    {
       return SavingTerms::find($id)->update($request->all());            
    }

 
    public function destroy(SavingTerms $savingTerms)
    {
         return SavingTerms::find($savingTerms)->delete();
    }
}
