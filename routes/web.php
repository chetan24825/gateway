<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/payment', [PaymentController::class, 'index'])->name('payment');
Route::post('/payment', [PaymentController::class, 'processPayment']);

Route::post('/payment/process', [PaymentController::class, 'processPayment']);
Route::get('/payment/success', [PaymentController::class, 'handleSuccess']);
Route::get('/payment/callback', [PaymentController::class, 'handleCallback']);




// Route::get('/payment/status/{transactionId}', [PaymentController::class, 'checkStatus']);
// Route::post('/payment/refund/{transactionId}', [PaymentController::class, 'refund']);
