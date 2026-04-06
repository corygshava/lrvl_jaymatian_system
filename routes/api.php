<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

Route::get('/test',[ApiController::class,'test']);
Route::post('/log_visit',[ApiController::class,'log_visit']);

// Route::post('/verify_file')
