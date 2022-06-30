<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;
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
// SuperAdmin
// Route::resource('/admin_dashboard', CompanyController::class)->middleware('auth', 'admin');

// Route::get('/', [DashboardController::class, 'index'])->middleware('auth', 'user')->name('dashboard');


// user protected routes
Route::group(['middleware' => ['auth']], function () {
    Route::get('/profile', [AuthController::class, 'profile_page'])->name('profile');
    Route::post('/update_profile', [AuthController::class, 'update_profile']);
    Route::post('/change_password', [AuthController::class, 'change_password']);

    Route::group(['middleware' => ['auth', 'user']], function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        
    });

    // admin protected routes
    Route::group(['middleware' => ['auth', 'admin']], function () {
        Route::resource('/admin_dashboard', AdminDashboardController::class);
        
    });
});

// Auth
Route::get('/registratie', [AuthController::class, 'signup_page']);
Route::post('/registratie', [AuthController::class, 'store']);
Route::get('/login', [AuthController::class, 'signin_page'])->name('login');
Route::post('/login', [AuthController::class, 'sign_in'])->name('signin');
Route::get('/logout', [AuthController::class, 'sign_out'])->name('signout');


