<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\JwtMiddleware;
// use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use App\Http\Middleware\VerifyCsrfToken;

Route::get('/csrf-token', function () {
    return response()->json(['csrfToken' => csrf_token()]);
});

// CSRF-protected routes
    Route::post('/login', [AuthController::class, 'loginUser']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/api/logout', [AuthController::class, 'logout']);
    Route::get('/api/me', [AuthController::class, 'me']);

Route::middleware(JwtMiddleware::class, 'api')->group(function () {
    Route::get('/api/all', [ApiController::class, 'all']);
    Route::get('/api/customers', [ApiController::class, 'customers']);
    Route::get('/api/customerid', [ApiController::class, 'customerID']);
    Route::get('/api/customername', [ApiController::class, 'customerName']);
    Route::get('/api/customeraddress', [ApiController::class, 'customerAddress']);
    Route::get('/api/customerage', [ApiController::class, 'customerAge']);
    Route::get('/api/signupdate', [ApiController::class, 'signupDate']);
    Route::get('/api/customersactivitystatus', [ApiController::class, 'customersActivityStatus']);
    Route::get('/api/activitystatusid', [ApiController::class, 'activityStatusID']);
    Route::get('/api/membersincemonths', [ApiController::class, 'memberSinceMonths']);
    Route::get('/api/hastrainedlastmonth', [ApiController::class, 'hasTrainedLastMonth']);
    Route::get('/api/dayssincelastvisit', [ApiController::class, 'daysSinceLastVisit']);
    Route::get('/api/trainingsessionsthismonth', [ApiController::class, 'trainingSessionsThisMonth']);
    Route::get('/api/membershiptype', [ApiController::class, 'membershipType']);
    Route::get('/api/membershiptypeid', [ApiController::class, 'membershipTypeID']);
    Route::get('/api/typename', [ApiController::class, 'typeName']);
});

Route::fallback(function () {
    return view('dist/index');
});