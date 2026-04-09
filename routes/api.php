<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

Route::get('/test',[ApiController::class,'test']);
Route::post('/log_visit',[ApiController::class,'log_visit']);
Route::post('/log_intent',[ApiController::class,'log_intent'])->middleware('throttle:72,1');
Route::post('/log_error',[ApiController::class,'log_error'])->middleware('throttle:72,1');

// Route::post('/verify_file')
