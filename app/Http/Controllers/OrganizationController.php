<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->user()->isAdmin()) {
            return $request->user()->memberships()
                ->orderBy('created_at')
                ->with([
                    'organization.members.user',
                    'organization.loans'
                ])->paginate(15);
        }
        return Organization::orderBy('created_at', 'DESC')
            ->with([
                'members.user'
            ])->paginate(15);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'website' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'number' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'max:255'],
        ]);

        return Organization::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Organization $organization)
    {
        if (!$request->user()->isAdmin()) {
            $membership = $request->user()->memberships()
                ->where('organization_id', $organization->id)
                ->with([
                    'organization.members.user',
                    'organization.loans.user',
                    'organization.loans.comaker',
                    'organization.loans.confirmation',
                ])->first();
            return $membership ? $membership->organization : response('', 404);
        }
        return $organization;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Organization $organization)
    {
        if (!$request->user()->isAdmin()) {
            $membership = $request->user()->memberships()
                ->where('organization_id', $organization->id)
                ->with([
                    'organization.members.user',
                    'organization.loans.user',
                    'organization.loans.comaker',
                    'organization.loans.confirmation',
                ])->first();
            if (!$membership || $membership->role !== 'Admin') {
                return response('', 404);
            }
        }
        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'number' => ['nullable', 'string', 'max:255'],
        ]);

        $organization->update($data);

        return $organization;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Organization $organization)
    {
        if (!$request->user()->isAdmin()) {
            $membership = $request->user()->memberships()
                ->where('organization_id', $organization->id)
                ->with([
                    'organization.members.user',
                    'organization.loans.user',
                    'organization.loans.comaker',
                    'organization.loans.confirmation',
                ])->first();
            if (!$membership || $membership->role !== 'Admin') {
                return response('', 404);
            }
        }
        $organization->delete();

        return response('', 204);
    }
}
