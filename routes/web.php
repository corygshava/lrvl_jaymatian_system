<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;

Route::get('/', [UserController::class,'index']);

Route::get('/dashboard', [UserController::class,'showdash'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/get_menu', [UserController::class,'getOptions'])->middleware(['auth', 'verified']);
Route::get('/my_permissions', [UserController::class,'mypermissions'])->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('test')->group(function () {
    Route::get('/is_logged_in', [ProfileController::class, 'check_session']);
});

Route::prefix('ui_view')->group(function (){
    // echo `<base href="../">`;

    Route::get('/console', [UserController::class,'showconsole'])->name('user_console');
    Route::get('/user_roles', [UserController::class,'showroles'])->name('user_roles');
});

require __DIR__.'/auth.php';
