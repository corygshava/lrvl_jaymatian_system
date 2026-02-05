// routes/api.php
<?php
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:api')->group(function () {
    Route::get('/test', fn () => ['message' => 'API works']);
});
