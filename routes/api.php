<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\JwtMiddleware;
use App\Http\Controllers\DashboardController;

    Route::post('/login', [AuthController::class, 'loginUser']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/api/me', [AuthController::class, 'me']);

    Route::post('/api/csv', [ApiController::class, 'csv']);

Route::middleware(JwtMiddleware::class, 'api')->group(function () {
    Route::get('/api/all', [ApiController::class, 'all']);

    Route::post('/api/dashboard', [DashboardController::class, 'store']); //Create a new dashboard
    Route::get('/api/getdashboards', [DashboardController::class, 'index']); //Read all dashboards
    Route::get('/api/getdashboard/{id}', [DashboardController::class, 'getOne']); //Read one dashboards
    Route::put('/api/dashboard/{id}/title', [DashboardController::class, 'updateTitle']); //Update the title of a dashboard
    Route::delete('/api/dashboard/{id}', [DashboardController::class, 'destroy']); //Delete a dashboard
});

Route::fallback(function () {
    return view('dist/index');
});