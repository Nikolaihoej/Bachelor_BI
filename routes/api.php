<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;


Route::get('/api', [ApiController::class, 'index']);
Route::get('/api/customers', [ApiController::class, 'customers']);

