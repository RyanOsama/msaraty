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

Route::post('/register', [AuthController::class, 'register']);


Route::post('/login', [AuthController::class, 'login']);


Route::get('/check-status', [AuthController::class, 'checkStatus']);




use App\Http\Controllers\Api\UserController;

Route::get('/users', [UserController::class, 'index']);
Route::patch('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']); 
Route::post('/users', [UserController::class, 'store']);

Route::get('/roles', [UserController::class, 'getRoles']);
Route::patch('/user/password', [UserController::class, 'updatePassword']);




use App\Http\Controllers\Api\StationController;

Route::get('/stations', [StationController::class, 'index']);
Route::post('/stations', [StationController::class, 'store']);
Route::put('/stations/{id}', [StationController::class, 'update']);
Route::delete('/stations/{id}', [StationController::class, 'destroy']);







use App\Http\Controllers\Api\RouteStationController;

Route::get('/assign', [RouteStationController::class, 'index']);

Route::post('/assign', [RouteStationController::class, 'store']);
Route::patch('/assign/{id}', [RouteStationController::class, 'updateOrder']);
Route::put('/assign/bulk-order', [RouteStationController::class, 'bulkUpdateOrder']);
Route::delete('/assign/{route_id}', [RouteStationController::class, 'destroy']);


use App\Http\Controllers\Api\UniversityController;

Route::get('universities', [UniversityController::class, 'index']);
Route::get('universities/{id}', [UniversityController::class, 'show']);
Route::post('universities', [UniversityController::class, 'store']);
Route::put('universities/{id}', [UniversityController::class, 'update']);
Route::delete('universities/{id}', [UniversityController::class, 'destroy']);



use App\Http\Controllers\Api\CollegeController;

Route::get('colleges', [CollegeController::class, 'index']);
Route::get('colleges/{id}', [CollegeController::class, 'show']);
Route::post('colleges', [CollegeController::class, 'store']);
Route::put('colleges/{id}', [CollegeController::class, 'update']);
Route::delete('colleges/{id}', [CollegeController::class, 'destroy']);




use App\Http\Controllers\Api\DepartmentController;

Route::get('departments', [DepartmentController::class, 'index']);
Route::get('departments/{id}', [DepartmentController::class, 'show']);
Route::post('departments', [DepartmentController::class, 'store']);
Route::put('departments/{id}', [DepartmentController::class, 'update']);
Route::delete('departments/{id}', [DepartmentController::class, 'destroy']);


use App\Http\Controllers\Api\LevelController;

Route::get('levels', [LevelController::class, 'index']);
Route::get('levels/{id}', [LevelController::class, 'show']);
Route::post('levels', [LevelController::class, 'store']);
Route::put('levels/{id}', [LevelController::class, 'update']);
Route::delete('levels/{id}', [LevelController::class, 'destroy']);


// Route::get('departments/{id}/levels', [LevelController::class, 'levelsByDepartment']);



use App\Http\Controllers\Api\DayController;

Route::get('days', [DayController::class, 'index']);
Route::get('days/{id}', [DayController::class, 'show']);
Route::post('days', [DayController::class, 'store']);
Route::put('days/{id}', [DayController::class, 'update']);
Route::delete('days/{id}', [DayController::class, 'destroy']);




use App\Http\Controllers\Api\StudentController;

Route::get('students', [StudentController::class, 'index']);
Route::get('/students/{id}', [StudentController::class, 'show']);
Route::post('students', [StudentController::class, 'store']);
Route::patch('students/{id}', [StudentController::class, 'update']);
Route::delete('students/{id}', [StudentController::class, 'destroy']);






use App\Http\Controllers\Api\DriverController;

Route::get('/drivers', [DriverController::class, 'index']);
Route::post('/drivers', [DriverController::class, 'store']);
Route::patch('/drivers/{driver}', [DriverController::class, 'update']);
Route::delete('/drivers/{driver}', [DriverController::class, 'destroy']);


use App\Http\Controllers\Api\BusController;

Route::get('/buses', [BusController::class, 'index']);
Route::post('/buses', [BusController::class, 'store']);
Route::patch('/buses/{bus}', [BusController::class, 'update']);
Route::delete('/buses/{bus}', [BusController::class, 'destroy']);

use App\Http\Controllers\Api\NotificationController;

Route::get('notifications', [NotificationController::class, 'index']);
Route::post('notifications', [NotificationController::class, 'store']);
Route::patch('notifications/{notification}', [NotificationController::class, 'update']);
Route::delete('notifications/{notification}', [NotificationController::class, 'destroy']);

Route::get('/send-notification/{id}', [NotificationController::class, 'sendFirebaseNotification']);



   


use App\Http\Controllers\API\DeviceTokenController;

Route::post('/save-token', [DeviceTokenController::class, 'saveToken']);




use App\Http\Controllers\Api\AbsenceRequestController;

// إرسال طلب غياب (من الجوال)
Route::post('/absence-requests', [AbsenceRequestController::class, 'store']);

// عرض كل الغيابات
Route::get('/absence-requests', [AbsenceRequestController::class, 'index']);

use App\Http\Controllers\Api\TripController;

Route::get('trips', [TripController::class, 'index']);
Route::post('trips', [TripController::class, 'store']);
Route::patch('trips/{id}', [TripController::class, 'update']); // 🔥 PATCH
Route::delete('trips/{id}', [TripController::class, 'destroy']);
Route::post('/student-trip-by-date', [TripController::class, 'checkStudentTripByDate']);


use App\Http\Controllers\Api\TripCancellationController;

Route::get('/trip-cancellations', [TripCancellationController::class, 'index']);
Route::post('/trip-cancellations', [TripCancellationController::class, 'store']);
Route::post('/trip-cancellations-check', [TripCancellationController::class, 'check']);
Route::delete('/trip-cancellations/{id}', [TripCancellationController::class, 'destroy']);
Route::post('/trip-cancellations-update-status', [TripCancellationController::class, 'updateStatus']);