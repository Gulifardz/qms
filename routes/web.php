<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\QuarryController;
use App\Http\Controllers\QuarryCompanyController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\TruckCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\QuarryProductController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\TruckController;
use App\Http\Controllers\CheckerController;
use App\Http\Controllers\SupermityController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\OptionController;

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


// Admin Routes
Route::prefix('admin')->middleware('is_admin')->group(function () {
    // Dashboard
    Route::prefix('dashboard')->group(function () {
        Route::get('/', [ DashboardController::class, 'index' ]);
        Route::post('/reports', [ DashboardController::class, 'reports' ]);
    });

    // Quarries
    Route::prefix('quarries')->group(function () {
        Route::get('/', [ QuarryController::class, 'index' ]);
        Route::post('/list', [ QuarryController::class, 'list' ]);
        Route::get('/{quarry_id}/edit', [ QuarryController::class, 'edit' ]);
        Route::post('/{quarry_id}/update', [ QuarryController::class, 'update' ]);
        Route::get('/{quarry_id}/destroy', [ QuarryController::class, 'destroy' ]);

        // Quarry Companies
        Route::get('/{quarry_id}/companies', [ QuarryCompanyController::class, 'index' ]);
        Route::post('/{quarry_id}/companies/list', [ QuarryCompanyController::class, 'list' ]);
        Route::post('/{quarry_id}/companies/grant', [ QuarryCompanyController::class, 'grant' ]);
        Route::post('/{quarry_id}/companies/revoke', [ QuarryCompanyController::class, 'revoke' ]); 
    });

    // Companies Routes
    Route::prefix('companies')->group(function () {
        Route::get('/',  [ CompanyController::class, 'index' ]);
        Route::post('/list',  [ CompanyController::class, 'list' ]);
        Route::get('/{company_id}/edit', [ CompanyController::class, 'edit' ]);
        Route::post('/{company_id}/update', [ CompanyController::class, 'update' ]);
        Route::get('/{company_id}/destroy', [ CompanyController::class, 'destroy' ]);
    });

    // Truck Categories Routes
    Route::prefix('truck-categories')->group(function () {
        Route::get('/', [ TruckCategoryController::class, 'index' ]);
        Route::post('/list', [ TruckCategoryController::class, 'list' ]);
        Route::post('/store', [ TruckCategoryController::class, 'store' ]);
        Route::post('/update', [ TruckCategoryController::class, 'update' ]);
        Route::get('/destroy/{id}', [ TruckCategoryController::class, 'destroy' ]);
    });

    // Products Routes
    Route::prefix('products')->group(function () {
        Route::get('/', [ ProductController::class, 'index' ]);
        Route::post('/list', [ ProductController::class, 'list' ]);
        Route::post('/store', [ ProductController::class, 'store' ]);
        Route::post('/update', [ ProductController::class, 'update' ]);
        Route::get('/destroy/{id}', [ ProductController::class, 'destroy' ]);
    });

    // Checkers Routes
    Route::prefix('checkers')->group(function () {
        Route::get('/', [ CheckerController::class, 'index' ]);
        Route::post('/list', [ CheckerController::class, 'list' ]);
        Route::get('/add', [ CheckerController::class, 'add' ]);
        Route::post('/store', [ CheckerController::class, 'store' ]);
        Route::get('/edit/{id}', [ CheckerController::class, 'edit' ]);
        Route::post('/update/{id}', [ CheckerController::class, 'update' ]);
        Route::get('/destroy/{id}', [ CheckerController::class, 'destroy' ]);
    });

    // Supermties Routes
    Route::prefix('supermities')->group(function () {
        Route::get('/', [ SupermityController::class, 'index' ]);
        Route::post('/list', [ SupermityController::class, 'list' ]);
        Route::get('/add', [ SupermityController::class, 'add' ]);
        Route::post('/store', [ SupermityController::class, 'store' ]);
        Route::get('/edit/{id}', [ SupermityController::class, 'edit' ]);
        Route::post('/update/{id}', [ SupermityController::class, 'update' ]);
        Route::get('/destroy/{id}', [ SupermityController::class, 'destroy' ]);
    });

    // Logs
    Route::prefix('logs')->group(function () {
        Route::get('/monthly-revenue', [ LogController::class, 'monthly_revenue' ]);
    });
});





// Company Routes
Route::prefix('company')->middleware('is_company')->group(function () {
    // Drivers Routes
    Route::prefix('drivers')->group(function () {
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
    Route::prefix('trucks')->group(function () {
        Route::get('/', [ TruckController::class, 'index' ]);
        Route::post('/list', [ TruckController::class, 'list' ]);
        Route::get('/add', [ TruckController::class, 'add' ]);
        Route::post('/store', [ TruckController::class, 'store' ]);
        Route::get('/edit/{id}', [ TruckController::class, 'edit' ]);
        Route::post('/update/{id}', [ TruckController::class, 'update' ]);
        Route::get('/destroy/{id}', [ TruckController::class, 'destroy' ]);
    });

    // Logs
    Route::prefix('logs')->group(function () {
        Route::get('/', [ LogController::class, 'index' ]);
        Route::post('/list', [ LogController::class, 'list' ]);
    });
});





// Quarry Routes
Route::prefix('quarry')->middleware('is_quarry')->group(function () {
    // Products
    Route::prefix('products')->group(function () {
        Route::get('/', [ QuarryProductController::class, 'index' ]);
        Route::post('/list', [ QuarryProductController::class, 'list' ]);
        Route::get('/add', [ QuarryProductController::class, 'add' ]);
        Route::post('/store', [ QuarryProductController::class, 'store' ]);
        Route::get('/edit/{id}', [ QuarryProductController::class, 'edit' ]);
        Route::post('/update/{id}', [ QuarryProductController::class, 'update' ]);
        Route::get('/destroy/{id}', [ QuarryProductController::class, 'destroy' ]);
    });

    // Checkers
    Route::prefix('checkers')->group(function () {
        Route::get('/', [ CheckerController::class, 'index' ]);
        Route::post('/list', [ CheckerController::class, 'list' ]);
    });

    // Supermties
    Route::prefix('supermities')->group(function () {
        Route::get('/', [ SupermityController::class, 'index' ]);
        Route::post('/list', [ SupermityController::class, 'list' ]);
    });

    // Logs
    Route::prefix('logs')->group(function () {
        Route::get('/', [ LogController::class, 'index' ]);
        Route::post('/list', [ LogController::class, 'list' ]);
    });
});





// Checker Routes
Route::prefix('checker')->middleware('is_checker')->group(function () {
    // Logs
    Route::prefix('logs')->group(function () {
        Route::get('/', [ LogController::class, 'index' ]);
        Route::post('/list', [ LogController::class, 'list' ]);
        Route::post('/update/{log_id}', [ LogController::class, 'update' ]);
    });

    // scanner
    Route::prefix('scanner')->group(function () {
        Route::get('/', [ LogController::class, 'scanner' ]);
        Route::post('/truck-details', [ LogController::class, 'truck_details' ]);
    });
});





// Supermity Routes
Route::prefix('supermity')->middleware('is_supermity')->group(function () {
    // Logs
    Route::prefix('logs')->group(function () {
        Route::get('/', [ LogController::class, 'index' ]);
        Route::post('/list', [ LogController::class, 'list' ]);
        Route::post('/store', [ LogController::class, 'store' ]);
        Route::post('/update/{log_id}', [ LogController::class, 'update' ]);
    });

    // scanner
    Route::prefix('scanner')->group(function () {
        Route::get('/', [ LogController::class, 'scanner' ]);
        Route::post('/truck-details', [ LogController::class, 'truck_details' ]);
        Route::post('/product-details', [ LogController::class, 'product_details' ]);
    });
});





// Options Routes
Route::prefix('options')->group(function () {
    Route::post('/products', [ OptionController::class, 'products' ]);
    Route::post('/quarries', [ OptionController::class, 'quarries' ]);
    Route::post('/drivers', [ OptionController::class, 'drivers' ]);
    Route::post('/truck-categories', [ OptionController::class, 'truck_categories' ]);
});
