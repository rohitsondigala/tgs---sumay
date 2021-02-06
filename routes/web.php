<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\CustomController;
use App\Http\Controllers\ModeratorDashboardController;
use App\Http\Controllers\ModeratorProfileController;
use App\Http\Controllers\UserAuthController;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Mail;
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

Route::get('/', function () {
    return view('welcome');
});



Route::post('login',[UserAuthController::class,'postLogin'])->name('login');
Route::get('login',[UserAuthController::class,'postLogin'])->name('login');
Route::get('forgot-password',[UserAuthController::class,'forgotPassword'])->name('forgot-password');
Route::post('forgot-password',[UserAuthController::class,'postForgotPassword'])->name('forgot-password');
Route::get('verify-otp',[UserAuthController::class,'verifyOTP'])->name('verify-otp');
Route::post('verify-otp',[UserAuthController::class,'postVerifyOTP'])->name('verify-otp');
Route::get('change-password',[UserAuthController::class,'changePassword'])->name('change-password');
Route::post('change-password',[UserAuthController::class,'postChangePassword'])->name('change-password');
Route::get('resend-otp',[UserAuthController::class,'resendOtp'])->name('resend-otp');
Route::get('index',[UserAuthController::class,'index'])->name('index');
Route::get('logout',[UserAuthController::class,'logout'])->name('logout');
Route::get('index',[UserAuthController::class,'index'])->name('index');
Route::get('mail', function () {
    $user = user()->find(14);
//    return view('mail.forgot-password-otp',compact('user'));
    return view('mail.moderator-registered',compact('user'));
//    Mail::to($user->email)->send(new \App\Mail\ModeratorRegistered($user));
});
Route::get('/get-state-list',[CustomController::class,'getStateList'])->name('state-list');
Route::get('/get-city-list',[CustomController::class,'getCityList'])->name('city-list');

Route::name('admin.')
    ->middleware(['auth', 'admin','revalidate'])
    ->prefix('admin')
    ->group(function () {
        Route::get('/dashboard',[AdminDashboardController::class,'dashboard'])->name('dashboard');
        Route::get('/profile',[AdminProfileController::class,'profile'])->name('profile');
        Route::post('/profile',[AdminProfileController::class,'postProfile'])->name('profile');
        Route::get('/change-password',[AdminProfileController::class,'changePassword'])->name('change-password');
        Route::post('/change-password',[AdminProfileController::class,'postChangePassword'])->name('change-password');
        Route::post('/profile',[AdminProfileController::class,'postProfile'])->name('profile');
        Route::resource('country','App\Http\Controllers\AdminCountriesController');
        Route::resource('state','App\Http\Controllers\AdminStatesController');
        Route::resource('city','App\Http\Controllers\AdminCitiesController');
        Route::resource('roles','App\Http\Controllers\AdminUserRolesControllers');
        Route::resource('streams','App\Http\Controllers\AdminStreamsController');
        Route::resource('subjects','App\Http\Controllers\AdminSubjectsController');
        Route::resource('moderator','App\Http\Controllers\AdminModeratorController');
        Route::get('/search/{table}',[CustomController::class,'search'])->name('search');
        Route::get('/get-subject-list',[CustomController::class,'getSubjectList'])->name('subject-list');

    });

Route::name('moderator.')
    ->middleware(['auth', 'moderator','revalidate'])
    ->prefix('moderator')
    ->group(function () {
        Route::get('/dashboard',[ModeratorDashboardController::class,'dashboard'])->name('dashboard');
        Route::get('/change-password',[ModeratorDashboardController::class,'changePassword'])->name('change-password');
        Route::post('/change-password',[ModeratorDashboardController::class,'postChangePassword'])->name('change-password');
        Route::get('/user-change-password',[ModeratorProfileController::class,'userChangePassword'])->name('user-change-password');
        Route::post('/user-change-password',[ModeratorProfileController::class,'userPostChangePassword'])->name('user-change-password');
        Route::get('/profile',[ModeratorProfileController::class,'profile'])->name('profile');
        Route::post('/profile',[ModeratorProfileController::class,'postProfile'])->name('profile');
        Route::resource('daily-posts','App\Http\Controllers\ModeratorDailyPostController');

    });
