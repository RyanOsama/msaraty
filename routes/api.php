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


use App\Http\Controllers\Api\RouteController;



    Route::get('/routes', [RouteController::class, 'index']);

    Route::post('/routes', [RouteController::class, 'store']);

    Route::put('/routes/{route}', [RouteController::class, 'update']);

    Route::delete('/routes/{route}', [RouteController::class, 'destroy']);







Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



use App\Http\Controllers\Api\AuthController;
// انشاء حساب مستخدم من التطبيق APi
Route::post('/register', [AuthController::class, 'register']);


Route::post('/login', [AuthController::class, 'login']);


Route::get('/check-status', [AuthController::class, 'checkStatus']);




use App\Http\Controllers\Api\UserController;

Route::get('/users', [UserController::class, 'index']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']); 
Route::post('/users', [UserController::class, 'store']);





use App\Http\Controllers\Api\StationController;

Route::get('/stations', [StationController::class, 'index']);
Route::post('/stations', [StationController::class, 'store']);
Route::put('/stations/{id}', [StationController::class, 'update']);
Route::delete('/stations/{id}', [StationController::class, 'destroy']);







use App\Http\Controllers\Api\RouteStationController;

Route::get('/route-stations', [RouteStationController::class, 'index']);

Route::post('/route-stations', [RouteStationController::class, 'store']);
Route::put('/route-stations/order', [RouteStationController::class, 'updateOrder']);
Route::put('/route-stations/bulk-order', [RouteStationController::class, 'bulkUpdateOrder']);
Route::delete('/route-stations', [RouteStationController::class, 'destroy']);
