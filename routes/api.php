// routes/api.php
<?php
use Illuminate\Support\Facades\Route;

Route::get('/test', fn () => ['message' => 'API works']);
