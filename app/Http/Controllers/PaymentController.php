<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($loanID)
    {
        $loan = Loan::findOrFail($loanID);

        return $loan->payments()->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $loanID)
    {
        $loan = Loan::findOrFail($loanID);
        return response($loan->payments()->create($request->all()), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show($loanID, $paymentID)
    {
        $loan = Loan::findOrFail($loanID);

        return $loan->payments()->findOrFail($paymentID);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $loanID, $paymentID)
    {
        $loan = Loan::findOrFail($loanID);

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
    public function destroy($loanID, $paymentID)
    {
        $loan = Loan::findOrFail($loanID);

        $payment = $loan->payments()->findOrFail($paymentID);

        $payment->delete();

        return response('', 204);
    }
}
