<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerActivityStatus;
use App\Models\MembershipType;

class ApiController extends Controller
{
    public function customers()
    {
        $Customers = Customer::all();
        return response()->json($Customers);
    }
    public function customersactivitystatus()
    {
        $customersactivitystatus = CustomerActivityStatus::all();
        return response()->json($customersactivitystatus);
    }
    public function membershiptype()
    {
        $membershiptype = MembershipType::all();
        return response()->json($membershiptype);
    }
}