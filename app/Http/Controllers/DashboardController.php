<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dashboard;

class DashboardController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'data' => 'required|json',
        ]);

        $dashboard = new Dashboard();
        $dashboard->data = $request->data;
        $dashboard->save();

        return response()->json(['message' => 'Dashboard saved successfully'], 201);
    }
}