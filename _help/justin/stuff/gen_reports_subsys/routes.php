<?php
use App\Http\Controllers\ReportVerificationController;

Route::post('/reports/save', [ReportVerificationController::class, 'saveReportFile']);
Route::post('/reports/verify', [ReportVerificationController::class, 'verifyReportFile']);
Route::post('/reports/verify-similar', [ReportVerificationController::class, 'verifyReportFileSimilar']); // Optional

?>