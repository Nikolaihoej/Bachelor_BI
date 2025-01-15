<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dashboard;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function store(Request $request) //Create a new dashboard
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

        return response()->json($dashboard, 201);
    }

    public function index() //Read all dashboards
    {
        $dashboards = Dashboard::all();
        return response()->json($dashboards);
    }

    public function updateTitle(Request $request, $id) //Update the title of a dashboard
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $dashboard = Dashboard::findOrFail($id);
        $dashboard->title = $request->title;
        $dashboard->save();

        // Log the successful update
        Log::info('Dashboard title updated successfully', ['dashboard_id' => $id, 'new_title' => $request->title]);

        return response()->json(['message' => 'Dashboard title updated successfully'], 200);
    }

    public function destroy($id) //Delete a dashboard
    {
        $dashboard = Dashboard::findOrFail($id);
        $dashboard->delete();

        // Log the successful deletion
        Log::info('Dashboard deleted successfully', ['dashboard_id' => $id]);

        return response()->json(['message' => 'Dashboard deleted successfully'], 200);
    }
}