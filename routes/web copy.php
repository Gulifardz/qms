<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\QuarryController;
use App\Http\Controllers\QuarryCompanyController;
use App\Http\Controllers\QuarryProductController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\CheckerController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\TruckController;
use App\Http\Controllers\LogController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Login Route
Route::get('/', [ LoginController::class, 'index' ])->middleware(['is_logged_in']);
Route::post('/', [ LoginController::class, 'login' ]);
Route::get('/logout', [ LoginController::class, 'logout' ]);


// Registration Routes
Route::prefix('registration')->group(function () {
    Route::get('/', [ RegistrationController::class, 'index' ])->middleware(['is_logged_in']);
    Route::post('/validate-step', [ RegistrationController::class, 'validate_step' ]);
    Route::post('quarry/store', [ QuarryController::class, 'store' ]);
    Route::post('company/store', [ CompanyController::class, 'store' ]);
});


// Quarry Routes
Route::prefix('quarries')->middleware(['is_admin'])->group(function () {
    Route::get('/', [ QuarryController::class, 'index' ]);
    Route::post('/list', [ QuarryController::class, 'list' ]);
    Route::get('/{quarry_id}/companies', [ QuarryCompanyController::class, 'index' ]);
    Route::post('/{quarry_id}/companies/list', [ QuarryCompanyController::class, 'list' ]);
    Route::post('/{quarry_id}/companies/grant', [ QuarryCompanyController::class, 'grant' ]);
    Route::post('/{quarry_id}/companies/revoke', [ QuarryCompanyController::class, 'revoke' ]);
    Route::get('/{quarry_id}/products', [ QuarryProductController::class, 'index' ]);
    Route::post('/{quarry_id}/products/list', [ QuarryProductController::class, 'list' ]);
});


// Companies Routes
Route::prefix('companies')->controller(CompanyController::class)->middleware(['is_admin'])->group(function () {
    Route::get('/', [ CompanyController::class, 'index' ]);
    Route::post('/list', [ CompanyController::class, 'list' ]);
});


// Products Routes
Route::prefix('products')->middleware(['is_admin_or_quarry'])->group(function () {
    Route::get('/', [ ProductController::class, 'index' ]);
    Route::post('/list', [ ProductController::class, 'list' ]);
    Route::post('/store', [ ProductController::class, 'store' ]);
    Route::post('/update', [ ProductController::class, 'update' ]);
    Route::get('/destroy/{id}', [ ProductController::class, 'destroy' ]);
});


// Checkers Routes
Route::prefix('checkers')->group(function () {
    Route::get('/', [ CheckerController::class, 'index' ])->middleware(['is_admin_or_quarry']);
    Route::post('/list', [ CheckerController::class, 'list' ])->middleware(['is_admin_or_quarry']);
    Route::get('/add', [ CheckerController::class, 'add' ])->middleware(['is_admin_or_quarry']);
    Route::post('/store', [ CheckerController::class, 'store' ])->middleware(['is_admin_or_quarry']);
    Route::get('/edit/{id}', [ CheckerController::class, 'edit' ])->middleware(['is_admin_or_quarry']);
    Route::post('/update/{id}', [ CheckerController::class, 'update' ])->middleware(['is_admin_or_quarry']);
    Route::get('/destroy/{id}', [ CheckerController::class, 'destroy' ])->middleware(['is_admin_or_quarry']);
    Route::get('/records/{id}', [ LogController::class, 'checker_records' ])->middleware(['is_quarry']);
    Route::post('/records/{id}/list', [ LogController::class, 'checker_records_list' ])->middleware(['is_quarry']);;
});


// Drivers Routes
Route::prefix('drivers')->middleware(['is_company'])->group(function () {
    Route::get('/', [ DriverController::class, 'index' ]);
    Route::post('/list', [ DriverController::class, 'list' ]);
    Route::get('/add', [ DriverController::class, 'add' ]);
    Route::post('/store', [ DriverController::class, 'store' ]);
    Route::get('/edit/{id}', [ DriverController::class, 'edit' ]);
    Route::post('/update/{id}', [ DriverController::class, 'update' ]);
    Route::get('/destroy/{id}', [ DriverController::class, 'destroy' ]);
    Route::get('/qr/{id}', [ DriverController::class, 'qr' ]);
});


// Trucks Routes
Route::prefix('trucks')->middleware(['is_company'])->group(function () {
    Route::get('/', [ TruckController::class, 'index' ]);
    Route::post('/list', [ TruckController::class, 'list' ]);
    Route::get('/add', [ TruckController::class, 'add' ]);
    Route::post('/store', [ TruckController::class, 'store' ]);
    Route::get('/edit/{id}', [ TruckController::class, 'edit' ]);
    Route::post('/update/{id}', [ TruckController::class, 'update' ]);
    Route::get('/destroy/{id}', [ TruckController::class, 'destroy' ]);
});


// Logs Routes
Route::prefix('logs')->group(function () {
    Route::get('/', [ LogController::class, 'index' ])->middleware(['is_quarry_or_company_or_checker']);
    Route::post('/list', [ LogController::class, 'list' ])->middleware(['is_quarry_or_company_or_checker']);
    Route::get('/scan', [ LogController::class, 'scan' ])->middleware(['is_quarry_or_checker']);
    Route::post('/store', [ LogController::class, 'store' ])->middleware(['is_quarry_or_checker']);
});


// Options Routes
Route::prefix('options')->group(function () {
    Route::post('/products', [ OptionController::class, 'products' ]);
    Route::post('/quarries', [ OptionController::class, 'quarries' ]);
    Route::post('/drivers', [ OptionController::class, 'drivers' ]);
});
