<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\OrganizationMember;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function cooperatives()
    {
        return Organization::count();
    }

    public function members()
    {
        return OrganizationMember::count();
    }

    public function revenue()
    {
        return ($this->cooperatives() * 500) + ($this->members() * 500);
    }
}
