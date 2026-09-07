<?php

// dadssaka
// sleep(77);

use App\Http\Controllers\ApiController;
use App\Http\Controllers\BizdataController;
use App\Http\Controllers\CashReportController;
use App\Http\Controllers\ChartsController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SamplesController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\WorksheetsController;
use Illuminate\Support\Facades\Route;

Route::get('/test',[ApiController::class,'test']);
Route::get('/sitedata',[ApiController::class,'sitedata']);

Route::post('/log_visit',[ApiController::class,'log_visit']);
Route::post('/log_intent',[ApiController::class,'log_intent'])
	// ->middleware('throttle:72,1')
;
Route::post('/log_error',[ApiController::class,'log_error'])
	// ->middleware('throttle:72,1')
;

// API routes for tools
Route::post('/checkuser',[ApiController::class,'check_user'])
	// ->middleware('throttle:60,1')
;
Route::post('/get_clients_motherlode',[ApiController::class,'get_all_clients'])
	// ->middleware('throttle:60,1')
;
Route::post('/add_client',[ApiController::class,'add_client'])
	// ->middleware('throttle:60,1')
;

Route::post('/add_task',[ApiController::class,'add_task'])
	// ->middleware('throttle:60,1')
;
Route::post('/save_worksheet',[ApiController::class,'save_worksheet'])
	// ->middleware('throttle:60,1')
;
Route::post('/save_worksheet_readings',[ApiController::class,'save_worksheet_readings'])
	// ->middleware('throttle:60,1')
;
Route::post('/publish_worksheet',[ApiController::class,'publish_worksheet'])
	// ->middleware('throttle:60,1')
;

Route::post('/get_samples_motherlode',[ApiController::class,'get_all_samples'])
	// ->middleware('throttle:60,1')
;
Route::post('/change_sample_tag',[ApiController::class,'change_sample_tag'])
	// ->middleware('throttle:60,1')
;

Route::post('/get_reports_count',[ApiController::class,'get_reports_count'])
	// ->middleware('throttle:60,1')
;
Route::post('/save_report',[ApiController::class,'mek_new_report'])
	// ->middleware('throttle:60,1')
;

Route::post('/get_invoices_count',[ApiController::class,'get_invoices_count'])
	// ->middleware('throttle:60,1')
;
Route::post('/save_invoice',[ApiController::class,'mek_new_invoice'])
	// ->middleware('throttle:60,1')
;
Route::post('/get_invoices_motherlode',[ApiController::class,'get_all_invoices'])
	// ->middleware('throttle:60,1')
;
Route::post('/save_invoice_payment',[ApiController::class,'add_invoice_payment'])
	// ->middleware('throttle:60,1')
;

Route::post('/save_payment',[ApiController::class,'add_payment'])
	// ->middleware('throttle:60,1')
;

Route::post('/get_bizdata',[ApiController::class,'get_bizdata'])
	// ->middleware('throttle:60,1')
;

Route::post('/get_invoice_info',[ApiController::class,'get_invoice_info'])
	// ->middleware('throttle:60,1')
;

Route::prefix('/media')->group(function(){
	Route::get('/bizimg/{fyl}',[ViewController::class, 'showimg']);
	Route::get('/getreport/imgs/{fyl}',[ViewController::class, 'showreport_img']);
	// Route::get('/getreport/pdfs/{fyl}',[ViewController::class, 'showreport_pdf']);

	Route::get('/getinvoice/imgs/{fyl}',[ViewController::class, 'showinvoice_img']);
	// Route::get('/getinvoice/pdfs/{fyl}',[ViewController::class, 'showinvoice_pdf']);

	// Route::get('/reports/pdf/{fyl}',[ViewController::class, 'findreport_pdf']);
	Route::get('/reports/image/{fyl}',[ViewController::class, 'findreport_img']);
	Route::get('/reports/img/{fyl}',[ViewController::class, 'findreport_img']);

	// Route::get('/invoices/pdf/{fyl}',[ViewController::class, 'findinvoice_pdf']);
	Route::get('/invoices/image/{fyl}',[ViewController::class, 'findinvoice_img']);
	Route::get('/invoices/img/{fyl}',[ViewController::class, 'findinvoice_img']);
});

// auth handlers
Route::prefix('/auth')->group(function (){
	Route::post('/login',[UserController::class,'api_login']);
});
Route::prefix('/auth')->group(function (){
	//
})
->middleware('auth:sanctum');

// remade
Route::prefix('/data')->group(function(){
	Route::get('/user_roles', [UserRoleController::class,'get_all']);
	Route::get('/cur_user', [UserController::class,'get_current']);
	Route::get('/users', [UserController::class,'get_all']);
	Route::get('/allpermissions', [UserController::class,'showpermissions']);
	Route::get('/my_permissions', [UserController::class,'mypermissions']);
	Route::post('/staffstats', [UserController::class,'staff_stats']);
	Route::post('/adminstats', [UserController::class,'admin_stats']);

	Route::get('/clients', [ClientController::class,'get_all']);
	Route::get('/search_clients', [ClientController::class,'get_all']);
	Route::post('/get_client', [ClientController::class,'get_one']);
	Route::post('/get_clients_by_ids', [ClientController::class,'get_by_ids']);

	Route::post('/all_debtors', [ClientController::class,'get_all_debtors']);
	Route::get('/get_debtors', [ClientController::class,'get_debtors']);
	Route::get('/all_debtors_stats', [ClientController::class,'get_debtors_stats']);

	Route::get('/tasks', [TaskController::class,'get_all']);
	Route::post('/task', [TaskController::class,'get_one']);
	Route::post('/get_task', [TaskController::class,'get_one']);
	Route::post('/tasks/incomplete', [TaskController::class,'get_incomplete']);
	Route::post('/get_worksheets', [WorksheetsController::class,'get_some']);

	Route::get('/samples', [SamplesController::class,'get_all']);
	Route::post('/get_sample', [SamplesController::class,'get_sample']);
	Route::post('/get_samples', [SamplesController::class,'get_some']);
	Route::post('/samples/incomplete', [SamplesController::class,'get_incomplete']);

	Route::get('/payments', [PaymentsController::class,'get_all']);
	Route::post('/get_payments', [PaymentsController::class,'get_some']);

	Route::get('/reports', [ReportsController::class,'get_all']);
	Route::post('/get_reports', [ReportsController::class,'get_some']);
	Route::post('/get_reports_count', [ReportsController::class,'get_count']);

	Route::get('/invoices', [InvoiceController::class,'get_all']);
	Route::post('/get_invoice_info', [InvoiceController::class,'get_my_info']);
	Route::post('/get_spec_invoice_info', [InvoiceController::class,'get_my_spec_info']);
	Route::post('/get_invoices', [InvoiceController::class,'get_some']);

	Route::get('/expenses', [ExpenseController::class,'get_all']);
	Route::get('/expenses_stats', [ExpenseController::class,'get_expenses_stats']);

	Route::post('/get_daily_report', [CashReportController::class,'get_for_today']);
	Route::get('/cash_reports', [CashReportController::class,'route_stuff']);
	Route::post('/cash_report', [CashReportController::class,'get_one']);

	Route::post('/bizdata', [BizdataController::class,'get_all']);

	// chart datapoints
	Route::post('/sales_chart_data', [ChartsController::class,'sales']);
	Route::post('/samples_chart_data', [ChartsController::class,'samples']);
	Route::post('/visits_chart_data', [ChartsController::class,'visits']);
	Route::post('/income_chart_data', [ChartsController::class,'income']);
})
->middleware('auth:sanctum');

Route::prefix('/op')->group(function(){
	Route::post('/user_roles', [UserRoleController::class,'add_new']);
	Route::delete('/user_roles', [UserRoleController::class,'delete_role']);

	Route::post('/users', [UserController::class, 'add_edit']);
	Route::post('/change_password',[UserController::class, 'change_password']);
	Route::post('/toggle_active',[UserController::class, 'toggle_active']);

	Route::post('/clients',[ClientController::class, 'add_edit']);
	Route::post('/change_credit',[ClientController::class, 'change_credit']);
	Route::post('/update_client_payment',[ClientController::class, 'edit_payment']);
	Route::post('/standardize_charges',[ClientController::class, 'standardize_all']);

	Route::post('/add_task',[TaskController::class, 'add_new_task']);
	Route::post('/add_payment',[TaskController::class, 'add_payment']);
	Route::post('/add_task_note',[TaskController::class, 'add_note']);
	Route::post('/save_worksheet',[WorksheetsController::class, 'add_edit']);
	Route::post('/save_worksheet_readings',[WorksheetsController::class, 'save_readings']);
	Route::post('/publish_worksheet',[WorksheetsController::class, 'publish_one']);
	Route::post('/change_sample_tag',[SamplesController::class, 'change_sample_tag']);
	Route::post('/send_sample_payment',[TaskController::class, 'add_payment']);

	// Route::post('/add_result',[SamplesController::class, 'add_results']);

	Route::post('/update_bizdata',[BizdataController::class, 'update_sth']);
	Route::post('/update_bizdata_images',[BizdataController::class, 'update_sth_2']);

	Route::post('/save_invoice', [InvoiceController::class,'add_new']);
	Route::post('/add_payment_invoice',[InvoiceController::class, 'add_payment']);
	Route::post('/change_invoice_image',[InvoiceController::class, 'change_image']);
	Route::post('/verify_invoice', [InvoiceController::class,'verify_me'])
		->middleware('throttle:5,1')
	;

	Route::post('/save_report', [ReportsController::class,'add_new']);
	Route::post('/verify_report', [ReportsController::class,'verify_me'])
		->middleware('throttle:5,1')
	;

	Route::post('/add_expense',[ExpenseController::class, 'add_new']);
	// Route::post('/add_result',[SamplesController::class, 'add_results']);

	Route::post('/save_daily_report',[CashReportController::class, 'save_me']);
	Route::post('/regen_daily_reports_from_date',[CashReportController::class, 'recreate_from_date']);
})
->middleware('auth:sanctum');

// Route::post('/verify_file')
