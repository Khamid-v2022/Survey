<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
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
Route::resource('/super/companies', AuthController::class);

Route::get('/', [HomeController::class, 'index'])->name('/')->middleware('auth');

// Auth
Route::get('/registratie', [AuthController::class, 'signup_page']);
Route::post('/registratie', [AuthController::class, 'store']);
Route::get('/login', [AuthController::class, 'signin_page'])->name('login');
Route::post('/login', [AuthController::class, 'sign_in'])->name('signin');
Route::get('/logout', [AuthController::class, 'sign_out'])->name('signout');


