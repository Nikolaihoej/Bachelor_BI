<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerActivityStatus;
use App\Models\MembershipType;

class ApiController extends Controller
{

    /*
        Customers table
    */
    public function customers()
    {
        $Customers = Customer::all();
        return response()->json($Customers);
    }

    /*
        Customers column
    */
    public function customerID()
    {
        $CustomerIDs = Customer::pluck('CustomerID');
        return response()->json($CustomerIDs);
    }

    public function customerName()
    {
        $CustomerNames = Customer::pluck('Name');
        return response()->json($CustomerNames);
    }
    
    public function customerAddress()
    {
        $CustomerAddresses = Customer::pluck('Address');
        return response()->json($CustomerAddresses);
    }

    public function customerAge()
    {
        $CustomerAges = Customer::pluck('Age');
        return response()->json($CustomerAges);
    }
    

    /*
      customer_activity_status table
    */
    public function customersactivitystatus()
    {
        $customersactivitystatus = CustomerActivityStatus::all();
        return response()->json($customersactivitystatus);
    }
    
    /*
        customer_activity_status column
    */    
    public function activityStatusID()
    {
        $activityStatusID = CustomerActivityStatus::pluck('ActivityStatusID');
        return response()->json($activityStatusID);
    }

    public function memberSinceMonths()
    {
        $memberSinceMonths = CustomerActivityStatus::pluck('MemberSinceMonths');
        return response()->json($memberSinceMonths);
    }

    public function hasTrainedLastMonth()
    {
        $hasTrainedLastMonth = CustomerActivityStatus::pluck('HasTrainedLastMonth');
        return response()->json($hasTrainedLastMonth);
    }

    public function daysSinceLastVisit()
    {
        $daysSinceLastVisit = CustomerActivityStatus::pluck('DaysSinceLastVisit');
        return response()->json($daysSinceLastVisit);
    }

    public function trainingSessionsThisMonth()
    {
        $trainingSessionsThisMonth = CustomerActivityStatus::pluck('TrainingSessionsThisMonth');
        return response()->json($trainingSessionsThisMonth);
    }


    /*
      membershiptype table
    */
    public function membershipType()
    {
        $membershiptype = MembershipType::all();
        return response()->json($membershiptype);
    }
    
    /*
      membershiptype columns
    */    
    public function membershipTypeID()
    {
        $membershipTypeID = MembershipType::pluck('MembershipTypeID');
        return response()->json($membershipTypeID);
    }

    public function typeName()
    {
        $typeName = MembershipType::pluck('TypeName');
        return response()->json($typeName);
    }
}