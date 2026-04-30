<?php

// dadssaka
// sleep(77);

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

Route::get('/test',[ApiController::class,'test']);
Route::post('/log_visit',[ApiController::class,'log_visit']);
Route::post('/log_intent',[ApiController::class,'log_intent'])->middleware('throttle:72,1');
Route::post('/log_error',[ApiController::class,'log_error'])->middleware('throttle:72,1');

// API routes for tools
Route::post('/checkuser',[ApiController::class,'check_user'])->middleware('throttle:25,1');
Route::post('/get_clients_motherlode',[ApiController::class,'get_all_clients'])->middleware('throttle:25,1');
Route::post('/add_client',[ApiController::class,'add_client'])->middleware('throttle:25,1');

Route::post('/add_task',[ApiController::class,'add_task'])->middleware('throttle:25,1');
Route::post('/save_worksheet',[ApiController::class,'save_worksheet'])->middleware('throttle:25,1');

// Route::post('/verify_file')
