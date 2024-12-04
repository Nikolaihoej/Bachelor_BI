<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Customer;

class ApiController extends Controller
{
    public function index()
    {
        // return response("hej");
        $Customers = Customer::all();
        return response()->json($Customers);
    }
}