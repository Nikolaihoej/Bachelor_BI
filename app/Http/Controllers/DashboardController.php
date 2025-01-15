<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dashboard;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function store(Request $request)
    {
        // Log the incoming request data
        Log::info('Storing new dashboard', ['title' => $request->title, 'category' => $request->category]);

        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
        ]);

        $dashboard = new Dashboard();
        $dashboard->title = $request->title;
        $dashboard->category = $request->category;
        $dashboard->save();

        // Log the successful storage
        Log::info('Dashboard stored successfully', ['dashboard_id' => $dashboard->id]);

        return response()->json(['message' => 'Dashboard saved successfully'], 201);
    }

    public function index()
    {
        $dashboards = Dashboard::all();
        return response()->json($dashboards);
    }
}