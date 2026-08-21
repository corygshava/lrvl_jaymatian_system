<?php

// dadssaka
// sleep(77);

use App\Http\Controllers\ApiController;
use App\Http\Controllers\ViewController;
use Illuminate\Support\Facades\Route;

Route::get('/test',[ApiController::class,'test']);
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


// Route::post('/verify_file')
