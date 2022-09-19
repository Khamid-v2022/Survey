<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\AdminUserManagementController;

use App\Http\Controllers\EnquetesController;
use App\Http\Controllers\AdminEnquetesController;

use App\Http\Controllers\DistributieController;
use App\Http\Controllers\AdminDistributieController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\SurveyFormController;


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
        
        // Route::get('/testemail', [DashboardController::class, 'send_email_test'])->name('testemail');
        
        // User Management
        Route::get('/user_management', [UserManagementController::class, 'user_manage_page'])->name('user_management');
        Route::post('/user_management/getUserTreeByRole', [UserManagementController::class, 'getUserTreeByRole'])->name('getUserTreeByRole');
        Route::post('/user_management/addUpdateUser', [UserManagementController::class, 'addUpdateUser'])->name('addUpdateUser');
        Route::get('/user_management/userInfo/{id}', [UserManagementController::class, 'userInfo'])->where('id', '[0-9]+');
        Route::post('/user_management/changeActive', [UserManagementController::class, 'changeActive']);
        Route::delete('/user_management/deleteUser/{id}', [UserManagementController::class, 'deleteUser'])->where('id', '[0-9]+');

        Route::get('/trainee_management', [UserManagementController::class, 'trainee_manage_page'])->name('trainee_management');

        // Survey
        Route::get('/enquetes', [EnquetesController::class, 'index'])->name('enquetes');
        Route::post('/enquetes/addUpdateForm', [EnquetesController::class, 'addUpdateForm']);
        Route::post('/enquetes/changeActive', [EnquetesController::class, 'changeActive']);
        Route::get('/enquetes/exportCSV/{id}', [EnquetesController::class, 'exportCSV'])->where('id', '[0-9]+');
        Route::delete('/enquetes/deleteForm/{id}', [EnquetesController::class, 'deleteForm'])->where('id', '[0-9]+');

        // Survey Form
        Route::get('/survey_form/{form_id}', [SurveyFormController::class, 'index'])->where('form_id', '[0-9]+');
        Route::post('/survey_form/addUpdateQuestion', [SurveyFormController::class, 'addUpdateQuestion']);
        Route::get('/survey_form/getQuestion/{question_id}', [SurveyFormController::class, 'getQuestion'])->where('question_id', '[0-9]+');
        Route::delete('/survey_form/deleteQuestion/{question_id}', [SurveyFormController::class, 'deleteQuestion'])->where('question_id', '[0-9]+');


        Route::get('/distributie', [DistributieController::class, 'index'])->name('distributie');
        Route::post('/distributie/sendFormToTranees', [DistributieController::class, 'sendFormToTranees']);
        Route::delete('/distributie/deleteSurveyItem', [DistributieController::class, 'deleteSurveyItem']);
        Route::get('/distributie/viewSurveyInfo/{survey_id}', [DistributieController::class, 'viewSurveyInfo'])->where('survey_id', '[0-9]+');
        
    });

    // admin protected routes
    Route::group(['middleware' => ['auth', 'admin']], function () {
        Route::resource('/admin_dashboard', AdminUserManagementController::class);
        Route::put('/admin_dashboard', [AdminUserManagementController::class, 'update']);

        // User Management
        Route::get('/adminuser_management', [AdminUserManagementController::class, 'user_manage_page'])->name('admin_user_management');
        Route::get('/adminuser_management/userInfo/{id}', [AdminUserManagementController::class, 'userInfo'])->where('id', '[0-9]+');
        Route::post('/adminuser_management/getUserTreeByRole', [AdminUserManagementController::class, 'getUserTreeByRole']);
        Route::post('/adminuser_management/addUpdateUser', [AdminUserManagementController::class, 'addUpdateUser']);
        Route::post('/adminuser_management/changeActive', [AdminUserManagementController::class, 'changeActive']);
        Route::delete('/adminuser_management/deleteUser/{id}', [AdminUserManagementController::class, 'deleteUser'])->where('id', '[0-9]+');

        Route::get('/admin_trainee_management', [AdminUserManagementController::class, 'trainee_manage_page'])->name('admin_trainee_management');
       
        // Survey Form
        Route::get('/admin_enquetes', [AdminEnquetesController::class, 'index'])->name('admin_enquetes');
        Route::post('/admin_enquetes/addUpdateForm', [AdminEnquetesController::class, 'addUpdateForm']);
        Route::post('/admin_enquetes/changeActive', [AdminEnquetesController::class, 'changeActive']);
        Route::delete('/admin_enquetes/deleteForm/{id}', [AdminEnquetesController::class, 'deleteForm'])->where('id', '[0-9]+');

        Route::get('/admin_distributie', [AdminDistributieController::class, 'index'])->name('admin_distributie');
        Route::post('/admin_distributie/sendFormToTranees', [AdminDistributieController::class, 'sendFormToTranees']);
        Route::delete('/admin_distributie/deleteSurveyItem', [AdminDistributieController::class, 'deleteSurveyItem']);
        Route::get('/admin_distributie/viewSurveyInfo/{survey_id}', [AdminDistributieController::class, 'viewSurveyInfo'])->where('survey_id', '[0-9]+');
        
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
Route::post('/survey', [SurveyController::class, 'postAnswer']);
Route::get('/thanks', [SurveyController::class, 'thanks']);


// Email
Route::get('/signupemail', function(){
    return new SignupMail();
});

