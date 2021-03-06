<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserManagementController;

use App\Http\Controllers\EnquetesController;
use App\Http\Controllers\DistributieController;
use App\Http\Controllers\SurveyController;


// Email
use App\Mail\SignupMail;
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
        
        // User Management
        Route::get('/user_management', [UserManagementController::class, 'user_manage_page'])->name('user_management');
        Route::post('/user_management/getUserTreeByRole', [UserManagementController::class, 'getUserTreeByRole'])->name('getUserTreeByRole');
        Route::post('/user_management/addUpdateUser', [UserManagementController::class, 'addUpdateUser'])->name('addUpdateUser');
        Route::get('/user_management/userInfo/{id}', [UserManagementController::class, 'userInfo'])->where('id', '[0-9]+');
        Route::post('/user_management/changeActive', [UserManagementController::class, 'changeActive']);
        Route::delete('/user_management/deleteUser/{id}', [UserManagementController::class, 'deleteUser'])->where('id', '[0-9]+');

        Route::get('/trainee_management', [UserManagementController::class, 'trainee_manage_page'])->name('trainee_management');

        Route::get('/enquetes', [EnquetesController::class, 'index'])->name('enquetes');
        Route::post('/enquetes/addUpdateForm', [EnquetesController::class, 'addUpdateForm']);
        Route::post('/enquetes/changeActive', [EnquetesController::class, 'changeActive']);
        Route::delete('/enquetes/deleteForm/{id}', [EnquetesController::class, 'deleteForm'])->where('id', '[0-9]+');


        Route::get('/distributie', [DistributieController::class, 'index'])->name('distributie');
        Route::post('/distributie/sendFormToTranees', [DistributieController::class, 'sendFormToTranees']);
        Route::delete('/distributie/deleteSurveyItem', [DistributieController::class, 'deleteSurveyItem']);
        
    });

    // admin protected routes
    Route::group(['middleware' => ['auth', 'admin']], function () {
        Route::resource('/admin_dashboard', UserManagementController::class);
    });
});

// Auth
Route::get('/registratie', [AuthController::class, 'signup_page']);
Route::post('/registratie', [AuthController::class, 'store']);
Route::get('/login', [AuthController::class, 'signin_page'])->name('login');
Route::post('/login', [AuthController::class, 'sign_in'])->name('signin');
Route::get('/logout', [AuthController::class, 'sign_out'])->name('signout');
Route::get('/password_reset', [AuthController::class, 'password_reset_page'])->name('password_reset');
Route::post('/password_reset', [AuthController::class, 'password_reset']);


// Public Survey link
Route::get('/survey/{unique_str}', [SurveyController::class, 'index'])->name('survey');



// Email
Route::get('/signupemail', function(){
    return new SignupMail();
});

