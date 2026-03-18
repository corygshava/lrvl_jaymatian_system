<?php

use App\Http\Controllers\BizdataController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SamplesController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ChartsController;
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
	Route::get('/datatables', [ViewController::class,'showdatatable']);
	Route::get('/get_menu', [ViewController::class,'showpermissions'])->middleware(['auth', 'verified']);
	Route::get('/console', [ViewController::class,'showconsole'])->name('user_console');
	Route::get('/admin_console', [ViewController::class,'showadminconsole'])->name('user_console');

	// Route::get('/get_menu', [UserController::class,'getOptions'])->middleware(['auth', 'verified']);
	Route::get('/user_roles', [ViewController::class,'showroles'])->name('user_roles');
	Route::get('/user_mgt', [ViewController::class,'showusers'])->name('user_mgt');
	Route::get('/view_user', [ViewController::class,'showuser']);

	Route::get('/client_mgt', [ViewController::class,'showclients'])->name('client_mgt');
	Route::get('/view_client', [ViewController::class,'showclient']);
	Route::get('/client_info', [ViewController::class,'showclientinfo']);

	Route::get('/task_mgt', [ViewController::class,'showtasks']);
	Route::get('/add_task', [ViewController::class,'addtask']);
	Route::get('/view_task', [ViewController::class,'showtask']);
	Route::get('/task_info', [ViewController::class,'showtaskinfo']);

	Route::get('/samples_mgt', [ViewController::class,'showsamples']);

	Route::get('/payments_mgt', [ViewController::class,'showpayments']);

	Route::get('/gen_report', [ViewController::class,'showgenreport']);
	Route::get('/reports_mgt', [ViewController::class,'showreports']);

	Route::get('/biz_data', [ViewController::class,'showbizdata']);
});

Route::prefix('/data')->group(function(){
	Route::get('/user_roles', [UserRoleController::class,'get_all'])->middleware(['auth','verified']);
	Route::get('/cur_user', [UserController::class,'get_current'])->middleware(['auth','verified']);
	Route::get('/users', [UserController::class,'get_all'])->middleware(['auth','verified']);
	Route::get('/allpermissions', [UserController::class,'showpermissions'])->middleware(['auth','verified']);
	Route::get('/my_permissions', [UserController::class,'mypermissions'])->middleware(['auth','verified']);
	Route::post('/staffstats', [UserController::class,'staff_stats'])->middleware(['auth','verified']);
	Route::post('/adminstats', [UserController::class,'admin_stats'])->middleware(['auth','verified']);

	Route::get('/clients', [ClientController::class,'get_all'])->middleware(['auth','verified']);
	Route::get('/search_clients', [ClientController::class,'get_all'])->middleware(['auth','verified']);
	Route::post('/get_client', [ClientController::class,'get_one'])->middleware(['auth','verified']);

	Route::get('/tasks', [TaskController::class,'get_all'])->middleware(['auth','verified']);
	Route::post('/task', [TaskController::class,'get_one'])->middleware(['auth','verified']);
	Route::post('/get_task', [TaskController::class,'get_one'])->middleware(['auth','verified']);
	Route::post('/tasks/incomplete', [TaskController::class,'get_incomplete'])->middleware(['auth','verified']);

	Route::get('/samples', [SamplesController::class,'get_all'])->middleware(['auth','verified']);
	Route::post('/get_sample', [SamplesController::class,'get_sample'])->middleware(['auth','verified']);
	Route::post('/get_samples', [SamplesController::class,'get_some'])->middleware(['auth','verified']);
	Route::post('/samples/incomplete', [SamplesController::class,'get_incomplete'])->middleware(['auth','verified']);

	Route::get('/payments', [PaymentsController::class,'get_all'])->middleware(['auth','verified']);
	Route::post('/get_payments', [PaymentsController::class,'get_some'])->middleware(['auth','verified']);

	Route::get('/reports', [ReportsController::class,'get_all'])->middleware(['auth','verified']);
	Route::post('/get_reports', [ReportsController::class,'get_some'])->middleware(['auth','verified']);

	Route::post('/bizdata', [BizdataController::class,'get_all'])->middleware(['auth','verified']);

	// chart datapoints
	Route::post('/sales_chart_data', [ChartsController::class,'sales'])->middleware(['auth','verified']);
	Route::post('/samples_chart_data', [ChartsController::class,'samples'])->middleware(['auth','verified']);
	Route::post('/visits_chart_data', [ChartsController::class,'visits'])->middleware(['auth','verified']);
	Route::post('/income_chart_data', [ChartsController::class,'income'])->middleware(['auth','verified']);
});

Route::prefix('/op')->group(function(){
	Route::post('/user_roles', [UserRoleController::class,'add_new'])->middleware(['auth','verified']);
	Route::delete('/user_roles', [UserRoleController::class,'delete_role'])->middleware(['auth','verified']);

	Route::post('/users',[UserController::class, 'add_edit'])->middleware(['auth','verified']);
	Route::post('/change_password',[UserController::class, 'change_password'])->middleware(['auth','verified']);
	Route::post('/toggle_active',[UserController::class, 'toggle_active'])->middleware(['auth','verified']);

	Route::post('/clients',[ClientController::class, 'add_edit'])->middleware(['auth','verified']);
	Route::post('/change_credit',[ClientController::class, 'change_credit'])->middleware(['auth','verified']);

	Route::post('/add_task',[TaskController::class, 'add_new_task'])->middleware(['auth','verified']);
	Route::post('/add_payment',[TaskController::class, 'add_payment'])->middleware(['auth','verified']);
	Route::post('/add_task_note',[TaskController::class, 'add_note'])->middleware(['auth','verified']);

	Route::post('/add_result',[SamplesController::class, 'add_results'])->middleware(['auth','verified']);

	Route::post('/update_bizdata',[BizdataController::class, 'update_sth'])->middleware(['auth','verified']);
	Route::post('/update_bizdata_images',[BizdataController::class, 'update_sth_2'])->middleware(['auth','verified']);

	Route::post('/save_report', [ReportsController::class,'add_new'])->middleware(['auth','verified']);
});

Route::prefix('/media')->group(function(){
	Route::get('/bizimg/{fyl}',[ViewController::class, 'showimg']);
	Route::get('/getreport/imgs/{fyl}',[ViewController::class, 'showreport_img']);
	Route::get('/getreport/pdfs/{fyl}',[ViewController::class, 'showreport_pdf']);
});

require __DIR__.'/auth.php';
