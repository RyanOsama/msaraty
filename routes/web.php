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


use App\Http\Controllers\StudentController;

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/

// عرض جميع الطلاب
Route::get('/students', [StudentController::class, 'index'])
    ->name('students.index');

// صفحة إضافة طالب
Route::get('/students/create', [StudentController::class, 'create'])
    ->name('students.create');

// حفظ طالب جديد
Route::post('/students', [StudentController::class, 'store'])
    ->name('students.store');

// عرض طالب معين
Route::get('/students/{student}', [StudentController::class, 'show'])
    ->name('students.show');

// صفحة تعديل طالب
Route::get('/students/{student}/edit', [StudentController::class, 'edit'])
    ->name('students.edit');

// تحديث طالب
Route::put('/students/{student}', [StudentController::class, 'update'])
    ->name('students.update');

// حذف طالب
Route::delete('/students/{student}', [StudentController::class, 'destroy'])
    ->name('students.destroy');






    use App\Http\Controllers\UniversityController;

/*
|--------------------------------------------------------------------------
| Universities Routes
|--------------------------------------------------------------------------
*/

// عرض جميع الجامعات
Route::get('/universities', [UniversityController::class, 'index'])
    ->name('universities.index');

// إضافة جامعة
Route::post('/universities', [UniversityController::class, 'store'])
    ->name('universities.store');

// تحديث جامعة
Route::put('/universities/{university}', [UniversityController::class, 'update'])
    ->name('universities.update');

// حذف جامعة
Route::delete('/universities/{university}', [UniversityController::class, 'destroy'])
    ->name('universities.destroy');




    use App\Http\Controllers\CollegeController;

Route::get('/colleges', [CollegeController::class, 'index'])
    ->name('colleges.index');

Route::post('/colleges', [CollegeController::class, 'store'])
    ->name('colleges.store');

Route::put('/colleges/{college}', [CollegeController::class, 'update'])
    ->name('colleges.update');

Route::delete('/colleges/{college}', [CollegeController::class, 'destroy'])
    ->name('colleges.destroy');










    use App\Http\Controllers\DepartmentController;

Route::get('/departments', [DepartmentController::class, 'index'])
    ->name('departments.index');

Route::post('/departments', [DepartmentController::class, 'store'])
    ->name('departments.store');

Route::put('/departments/{department}', [DepartmentController::class, 'update'])
    ->name('departments.update');

Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])
    ->name('departments.destroy');


  Route::get('/levels-by-department/{id}', 
    [App\Http\Controllers\LevelController::class, 'levelsByDepartment']);








    use App\Http\Controllers\LevelController;

Route::get('/levels', [LevelController::class, 'index'])
    ->name('levels.index');

Route::post('/levels', [LevelController::class, 'store'])
    ->name('levels.store');

Route::put('/levels/{level}', [LevelController::class, 'update'])
    ->name('levels.update');

Route::delete('/levels/{level}', [LevelController::class, 'destroy'])
    ->name('levels.destroy');


 use App\Http\Controllers\DayController;

    Route::get('/days', [DayController::class, 'index'])->name('days.index');

Route::post('/days', [DayController::class, 'store'])->name('days.store');

Route::put('/days/{day}', [DayController::class, 'update'])->name('days.update');

Route::delete('/days/{day}', [DayController::class, 'destroy'])->name('days.destroy');
