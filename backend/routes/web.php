<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoViewController;
use App\Http\Controllers\MovimientoViewController;
use App\Http\Controllers\TestMailController;

require __DIR__.'/auth.php';
    

Route::get('/test-mail', [TestMailController::class, 'send']);
Route::get('/test-mail-hola', [TestMailController::class, 'sendHelloWorld']);
