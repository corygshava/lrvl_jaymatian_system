<?php
use App\Http\Controllers\PdfVerificationController;

Route::post('/pdf-reports/save', [PdfVerificationController::class, 'savePdfReport']);
Route::post('/pdf-reports/verify', [PdfVerificationController::class, 'verifyPdfReport']);
Route::post('/pdf-reports/info', [PdfVerificationController::class, 'getPdfInfo']); // Debug endpoint

?>