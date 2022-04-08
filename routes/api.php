<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\CustomerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::post('/auth/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum'],'prefix'=>'auth'], function () {
    Route::get('/info', [AuthController::class, 'info']);
    Route::get('/logout', [AuthController::class, 'logout']);
});

Route::group(['middleware' => ['auth:api']], function () {
    Route::apiResource('customer', CustomerController::class);
    Route::apiResource('bills', BillController::class);
});


Route::get('/my-bills', [BillController::class, 'myBills'])->middleware('auth:customer');
