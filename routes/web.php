<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
require __DIR__.'/auth.php';




// راوت اداره المستخدمين
use App\Http\Controllers\Admin\UserManagementController;
Route::middleware(['auth'])->prefix('admin')->group(function () {

    // صفحة إضافة طالب
    Route::get('/users/create', [UserManagementController::class, 'create'])
        ->name('admin.users.create');

    // حفظ الطالب
    Route::post('/users', [UserManagementController::class, 'store'])
        ->name('admin.users.store');

    // تعديل مستخدم
    Route::put('/users/{user}', [UserManagementController::class, 'update'])
        ->name('admin.users.update');

    // حذف مستخدم
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])
        ->name('admin.users.destroy');
});


use App\Http\Controllers\Admin\RouteController;

Route::middleware(['auth'])->prefix('admin')->group(function () {

    Route::get('/routes', [RouteController::class, 'index'])
        ->name('admin.routes.index');

    Route::post('/routes', [RouteController::class, 'store'])
        ->name('admin.routes.store');

    Route::put('/routes/{route}', [RouteController::class, 'update'])
        ->name('admin.routes.update');

    Route::delete('/routes/{route}', [RouteController::class, 'destroy'])
        ->name('admin.routes.destroy');
});

use App\Http\Controllers\Admin\StationController;

Route::middleware(['auth'])->prefix('admin')->group(function () {

    Route::get('/stations', [StationController::class, 'index'])
        ->name('admin.stations.index');

    Route::post('/stations', [StationController::class, 'store'])
        ->name('admin.stations.store');

    Route::put('/stations/{station}', [StationController::class, 'update'])
        ->name('admin.stations.update');

    Route::delete('/stations/{station}', [StationController::class, 'destroy'])
        ->name('admin.stations.destroy');

});


use App\Http\Controllers\Admin\RouteStationController;

Route::middleware(['auth'])->prefix('admin')->group(function () {

    Route::post('/route-stations', [RouteStationController::class, 'store'])
        ->name('admin.route-stations.store');

   Route::delete(
    'admin/route-stations/delete',
    [RouteStationController::class, 'destroy']
)->name('admin.route-stations.destroy');


Route::put('/admin/route-stations/order', 
    [RouteStationController::class, 'updateOrder']
)->name('admin.route-stations.order');
Route::put(
    '/admin/route-stations/bulk-order',
    [\App\Http\Controllers\Admin\RouteStationController::class, 'bulkUpdateOrder']
)->name('admin.route-stations.bulk-order');



  
});





