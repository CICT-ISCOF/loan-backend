<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Organization $organization, $loanID)
    {
        $loan = $organization->loans()->findOrFail($loanID);

        return $loan->payments()->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Organization $organization, $loanID)
    {
        $loan = $organization->loans()->findOrFail($loanID);
        return response($loan->payments()->create($request->all()), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Organization $organization, $loanID, $paymentID)
    {
        $loan = $organization->loans()->findOrFail($loanID);

        return $loan->payments()->findOrFail($paymentID);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Organization $organization, $loanID, $paymentID)
    {
        $loan = $organization->loans()->findOrFail($loanID);

        $payment = $loan->payments()->findOrFail($paymentID);

        $payment->update($request->all());

        return $payment;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Organization $organization, $loanID, $paymentID)
    {
        $loan = $organization->loans()->findOrFail($loanID);

        $payment = $loan->payments()->findOrFail($paymentID);

        $payment->delete();

        return response('', 204);
    }
}
