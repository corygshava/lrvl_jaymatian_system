<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserRoleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserController::class,'index']);

Route::get('/dashboard', [ViewController::class,'showdash'])->middleware(['auth', 'verified'])->name('dashboard');
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
	Route::get('/get_menu', [ViewController::class,'showpermissions'])->middleware(['auth', 'verified']);
	Route::get('/console', [ViewController::class,'showconsole'])->name('user_console');
	Route::get('/user_roles', [ViewController::class,'showroles'])->name('user_roles');
	Route::get('/user_mgt', [ViewController::class,'showusers'])->name('user_mgt');
	Route::get('/datatables', [ViewController::class,'showdatatable']);
	Route::get('/view_user', [ViewController::class,'showuser']);
	Route::get('/client_mgt', [ViewController::class,'showclients'])->name('client_mgt');
	Route::get('/view_client', [ViewController::class,'showclient']);
	Route::get('/task_mgt', [ViewController::class,'showtasks']);
	Route::get('/add_task', [ViewController::class,'addtask']);
});

Route::prefix('/data')->group(function(){
	Route::get('/user_roles', [UserRoleController::class,'get_all'])->middleware(['auth','verified']);
	Route::get('/cur_user', [UserController::class,'get_current'])->middleware(['auth','verified']);
	Route::get('/users', [UserController::class,'get_all'])->middleware(['auth','verified']);
	Route::get('/allpermissions', [UserController::class,'showpermissions'])->middleware(['auth','verified']);
	Route::get('/my_permissions', [UserController::class,'mypermissions'])->middleware(['auth','verified']);
	Route::get('/clients', [ClientController::class,'get_all'])->middleware(['auth','verified']);
	Route::get('/search_clients', [ClientController::class,'get_all'])->middleware(['auth','verified']);
});

Route::prefix('/op')->group(function(){
	Route::post('/user_roles', [UserRoleController::class,'add_new'])->middleware(['auth','verified']);
	Route::delete('/user_roles', [UserRoleController::class,'delete_role'])->middleware(['auth','verified']);

	Route::post('/users',[UserController::class, 'add_edit'])->middleware(['auth','verified']);
	Route::post('/change_password',[UserController::class, 'change_password'])->middleware(['auth','verified']);
	Route::post('/toggle_active',[UserController::class, 'toggle_active'])->middleware(['auth','verified']);

	Route::post('/clients',[ClientController::class, 'add_edit'])->middleware(['auth','verified']);
	Route::post('/change_credit',[ClientController::class, 'change_credit'])->middleware(['auth','verified']);

	Route::post('/add_task',[TaskController::class, 'log_my_payload'])->middleware(['auth','verified']);
});

require __DIR__.'/auth.php';
