<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRoleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserController::class,'index']);

Route::get('/dashboard', [UserController::class,'showdash'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/my_permissions', [UserController::class,'mypermissions'])->middleware(['auth', 'verified']);
Route::get('/am_i_admin', [UserController::class,'checkadmin'])->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
	Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
	Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
	Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('test')->group(function () {
	Route::get('/is_logged_in', [ProfileController::class, 'check_session']);
});

Route::prefix('/ui')->group(function (){
    // Route::get('/get_menu', [UserController::class,'getOptions'])->middleware(['auth', 'verified']);
    Route::get('/get_menu', [UserController::class,'showpermissions'])->middleware(['auth', 'verified']);
	Route::get('/console', [UserController::class,'showconsole'])->name('user_console');
	Route::get('/user_roles', [UserController::class,'showroles'])->name('user_roles');
});

Route::prefix('/data')->group(function(){
	Route::get('/user_roles', [UserRoleController::class,'get_all']);
	Route::get('/allpermissions', [UserController::class,'showpermissions']);
});

Route::prefix('/op')->group(function(){
	Route::post('/user_roles', [UserRoleController::class,'add_new']);
	Route::delete('/user_roles', [UserRoleController::class,'delete_role']);
});

require __DIR__.'/auth.php';
