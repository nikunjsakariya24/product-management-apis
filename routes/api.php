<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/* authentication route */
Route::post('login',[App\Http\Controllers\APIs\AuthController::class, 'login']);
Route::post('register', [App\Http\Controllers\APIs\AuthController::class, 'register']);


/* authenticated routs */
Route::middleware('auth:api')->group(function () {

    /* user routes */
    Route::apiResource('user', App\Http\Controllers\APIs\UserController::class);
    Route::post('user/search', [App\Http\Controllers\APIs\UserController::class, 'search']);
    Route::patch('user/password/{user}', [App\Http\Controllers\APIs\UserController::class, 'updatePassword']);

    /* product routes */
    Route::apiResource('product', App\Http\Controllers\APIs\ProductController::class);
    Route::post('product/search', [App\Http\Controllers\APIs\ProductController::class, 'search']);

    /* payment routes */
    Route::post('payment/initiate', [App\Http\Controllers\APIs\PaymentController::class, 'initiate']);
    Route::get('payment/success', [App\Http\Controllers\APIs\PaymentController::class, 'success']);
    Route::get('payment/failed', [App\Http\Controllers\APIs\PaymentController::class, 'failed']);

});
