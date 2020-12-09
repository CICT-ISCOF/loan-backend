<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\SavingTerms;
use Illuminate\Http\Request;

class SavingTermsController extends Controller
{

	public function index()
	{
		return SavingTerms::all();
	}


	public function store(Request $request)
	{
		if (SavingTerms::count() === 0) {
			$terms = SavingTerms::create($request->all());
			return $terms;
		}
		return response('Cooperative has already setup its saving terms', 400);
	}

	public function show($id)
	{
		return SavingTerms::first();
	}

	public function update(Request $request, SavingTerms $terms)
	{
		$terms->update($request->all());
		return $terms;
	}


	public function destroy(SavingTerms $terms)
	{
		$terms->delete();

		return response('', 204);
	}
}
