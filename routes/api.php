<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;


Route::get('/api', [ApiController::class, 'index']);
