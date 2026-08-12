<?php

use App\Http\Controllers\BizdataController;
use App\Http\Controllers\CashReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SamplesController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ChartsController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\WorksheetsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserController::class,'index']);

Route::get('/dashboard', [ViewController::class,'showdash'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/my_permissions', [UserController::class,'mypermissions'])->middleware(['auth', 'verified']);
Route::get('/am_i_admin', [UserController::class,'checkadmin'])->middleware(['auth', 'verified']);
Route::get('/verify_file', [ViewController::class,'show_verify_files_page']);

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
	Route::get('/console', [ViewController::class,'showconsole'])->middleware(['check.permissions.ui:view_console'])->name('user_console');
	Route::get('/admin_console', [ViewController::class,'showadminconsole'])->middleware(['check.permissions.ui:view_dashboard'])->name('user_console');

	// Route::get('/get_menu', [UserController::class,'getOptions'])->middleware(['auth', 'verified']);
	Route::get('/user_roles', [ViewController::class,'showroles'])->middleware(['check.permissions.ui:manage_user_roles'])->name('user_roles');
	Route::get('/user_mgt', [ViewController::class,'showusers'])->middleware(['check.permissions.ui:manage_users'])->name('user_mgt');
	Route::get('/view_user', [ViewController::class,'showuser']);

	Route::get('/client_mgt', [ViewController::class,'showclients'])->middleware(['check.permissions.ui:manage_clients'])->name('client_mgt');
	Route::get('/view_client', [ViewController::class,'showclient'])->middleware(['check.permissions.ui:view_client']);
	Route::get('/debtors_mgt', [ViewController::class,'showdebtors'])->middleware(['check.permissions.ui:manage_debtors']);
	Route::get('/client_info', [ViewController::class,'showclientinfo']);

	Route::get('/task_mgt', [ViewController::class,'showtasks']);
	Route::get('/add_task', [ViewController::class,'addtask']);
	Route::get('/view_task', [ViewController::class,'showtask']);
	Route::get('/task_info', [ViewController::class,'showtaskinfo']);

	Route::get('/samples_mgt', [ViewController::class,'showsamples']);

	Route::get('/payments_mgt', [ViewController::class,'showpayments']);

	Route::get('/gen_report', [ViewController::class,'showgenreport']);
	Route::get('/reports_mgt', [ViewController::class,'showreports']);

	Route::get('/gen_invoice', [ViewController::class,'showgeninvoice']);
	Route::get('/invoices_mgt', [ViewController::class,'showinvoices']);

	Route::get('/biz_data', [ViewController::class,'showbizdata']);
	Route::get('/verify_file', [ViewController::class,'showverifiersubscreen']);

	Route::get('/expenses_mgt', [ViewController::class,'showexpenses']);
	Route::get('/cash_reports_mgt', [ViewController::class,'showcashreports']);
	Route::get('/gen_cash_report', [ViewController::class,'showprintcashreports']);
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

	Route::post('/all_debtors', [ClientController::class,'get_all_debtors'])->middleware(['auth','verified']);
	Route::get('/get_debtors', [ClientController::class,'get_debtors'])->middleware(['auth','verified']);
	Route::get('/all_debtors_stats', [ClientController::class,'get_debtors_stats'])->middleware(['auth','verified']);

	Route::get('/tasks', [TaskController::class,'get_all'])->middleware(['auth','verified']);
	Route::post('/task', [TaskController::class,'get_one'])->middleware(['auth','verified']);
	Route::post('/get_task', [TaskController::class,'get_one'])->middleware(['auth','verified']);
	Route::post('/tasks/incomplete', [TaskController::class,'get_incomplete'])->middleware(['auth','verified']);
	Route::post('/get_worksheets', [WorksheetsController::class,'get_some'])->middleware(['auth','verified']);

	Route::get('/samples', [SamplesController::class,'get_all'])->middleware(['auth','verified']);
	Route::post('/get_sample', [SamplesController::class,'get_sample'])->middleware(['auth','verified']);
	Route::post('/get_samples', [SamplesController::class,'get_some'])->middleware(['auth','verified']);
	Route::post('/samples/incomplete', [SamplesController::class,'get_incomplete'])->middleware(['auth','verified']);

	Route::get('/payments', [PaymentsController::class,'get_all'])->middleware(['auth','verified']);
	Route::post('/get_payments', [PaymentsController::class,'get_some'])->middleware(['auth','verified']);

	Route::get('/reports', [ReportsController::class,'get_all'])->middleware(['auth','verified']);
	Route::post('/get_reports', [ReportsController::class,'get_some'])->middleware(['auth','verified']);
	Route::post('/get_reports_count', [ReportsController::class,'get_count'])->middleware(['auth','verified']);

	Route::get('/invoices', [InvoiceController::class,'get_all'])->middleware(['auth','verified']);
	Route::post('/get_invoice_info', [InvoiceController::class,'get_my_info'])->middleware(['auth','verified']);
	Route::post('/get_spec_invoice_info', [InvoiceController::class,'get_my_spec_info'])->middleware(['auth','verified']);
	Route::post('/get_invoices', [InvoiceController::class,'get_some'])->middleware(['auth','verified']);

	Route::get('/expenses', [ExpenseController::class,'get_all'])->middleware(['auth','verified']);

	Route::post('/get_daily_report', [CashReportController::class,'get_for_today'])->middleware(['auth','verified']);
	Route::get('/cash_reports', [CashReportController::class,'route_stuff'])->middleware(['auth','verified']);
	Route::post('/cash_report', [CashReportController::class,'get_one'])->middleware(['auth','verified']);

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
	Route::post('/update_client_payment',[ClientController::class, 'edit_payment'])->middleware(['auth','verified']);
	Route::post('/standardize_charges',[ClientController::class, 'standardize_all'])->middleware(['auth','verified']);

	Route::post('/add_task',[TaskController::class, 'add_new_task'])->middleware(['auth','verified']);
	Route::post('/add_payment',[TaskController::class, 'add_payment'])->middleware(['auth','verified']);
	Route::post('/add_task_note',[TaskController::class, 'add_note'])->middleware(['auth','verified']);
	Route::post('/save_worksheet',[WorksheetsController::class, 'add_edit'])->middleware(['auth','verified']);
	Route::post('/save_worksheet_readings',[WorksheetsController::class, 'save_readings'])->middleware(['auth','verified']);
	Route::post('/publish_worksheet',[WorksheetsController::class, 'publish_one'])->middleware(['auth','verified']);
	Route::post('/change_sample_tag',[SamplesController::class, 'change_sample_tag'])->middleware(['auth','verified']);
	Route::post('/send_sample_payment',[TaskController::class, 'add_payment'])->middleware(['auth','verified']);

	// Route::post('/add_result',[SamplesController::class, 'add_results'])->middleware(['auth','verified']);

	Route::post('/update_bizdata',[BizdataController::class, 'update_sth'])->middleware(['auth','verified']);
	Route::post('/update_bizdata_images',[BizdataController::class, 'update_sth_2'])->middleware(['auth','verified']);

	Route::post('/save_invoice', [InvoiceController::class,'add_new'])->middleware(['auth','verified']);
	Route::post('/add_payment_invoice',[InvoiceController::class, 'add_payment'])->middleware(['auth','verified']);
	Route::post('/change_invoice_image',[InvoiceController::class, 'change_image'])->middleware(['auth','verified']);
	Route::post('/verify_invoice', [InvoiceController::class,'verify_me'])
		->middleware('throttle:5,1')
		// ->middleware(['auth','verified'])
	;

	Route::post('/save_report', [ReportsController::class,'add_new'])->middleware(['auth','verified']);
	Route::post('/verify_report', [ReportsController::class,'verify_me'])
		->middleware('throttle:5,1')
		// ->middleware(['auth','verified'])
	;

	Route::post('/add_expense',[ExpenseController::class, 'add_new'])->middleware(['auth','verified']);
	// Route::post('/add_result',[SamplesController::class, 'add_results'])->middleware(['auth','verified']);

	Route::post('/save_daily_report',[CashReportController::class, 'save_me'])->middleware(['auth','verified']);
	Route::post('/regen_daily_reports_from_date',[CashReportController::class, 'recreate_from_date'])->middleware(['auth','verified']);
})
// ->middleware('')
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

require __DIR__.'/auth.php';
