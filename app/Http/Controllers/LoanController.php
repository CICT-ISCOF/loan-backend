<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Organization;
use App\Rules\Confirmed;
use App\Rules\IsMember;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Organization $organization)
    {
        return $organization->loans()
            ->with('user.confirmation')
            ->with('comaker.confirmation')
            ->with('confirmation')
            ->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Organization $organization)
    {
        $data = $request->validate([
            'user_id' => [
                'required',
                Rule::exists('users', 'id'),
                new Confirmed(),
                new IsMember($organization),
            ],
            'status' => ['required', 'string', Rule::in(['New', 'Renewal'])],
            'type' => ['required', 'string', Rule::in([
                'Regular',
                'Emergency',
                'Special Emergency',
                'Provident',
                'Petty Cash',
            ])],
            'amount' => ['required'],
            'previous_amount' => ['nullable', 'string', 'max:255'],
            'net_amount' => ['nullable', 'string', 'max:255'],
            'interval' => ['nullable', 'string', 'max:255'],
            'charges' => ['required', 'string', 'max:255'],
            'terms' => ['required', 'string'],
            'comaker_id' => [
                'required',
                Rule::exists('users', 'id'),
                new Confirmed(),
            ],
        ]);

        $loan = $organization->loans()->create($data);

        return response($loan, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function show(Organization $organization, $id)
    {
        return $organization->loans()
            ->with('user.confirmation')
            ->with('comaker.confirmation')
            ->with('confirmation')
            ->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Organization $organization, $id)
    {
        $loan = $organization->loans()->findOrFail($id);

        $data = $request->validate([
            'status' => ['nullable', 'string', Rule::in(['New', 'Renewal'])],
            'type' => ['nullable', 'string', Rule::in([
                'Regular',
                'Emergency',
                'Special Emergency',
                'Provident',
                'Petty Cash',
            ])],
            'amount' => ['nullable'],
            'previous_amount' => ['nullable', 'string', 'max:255'],
            'net_amount' => ['nullable', 'string', 'max:255'],
            'interval' => ['nullable', 'string', 'max:255'],
            'charges' => ['nullable', 'string', 'max:255'],
            'terms' => ['nullable', 'string'],
            'comaker_id' => [
                'nullable',
                Rule::exists('users', 'id'),
                new Confirmed(),
            ],
        ]);

        $loan->update($data);

        return $loan;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Organization $organization, $id)
    {
        $loan = $organization->loans()->findOrFail($id);

        $loan->delete();

        return response('', 204);
    }
}
