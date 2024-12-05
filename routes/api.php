<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;


// Route::get('/api', [ApiController::class, 'index']);
Route::get('/api/customers', [ApiController::class, 'customers']);
Route::get('/api/customersactivitystatus', [ApiController::class, 'customersactivitystatus']);
Route::get('/api/membershiptype', [ApiController::class, 'membershiptype']);

