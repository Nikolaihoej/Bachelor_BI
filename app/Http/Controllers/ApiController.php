<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Customer;

class ApiController extends Controller
{
    public function index()
    {
        $Customers = Customer::all();
        return response()->json($Customers);
    }
    public function customers()
    {
        $Customers = Customer::all();
        return response()->json($Customers);
    }
}