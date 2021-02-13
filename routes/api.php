<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/login',[ApiController::class,'login'])->name('login');
Route::post('/check-email',[ApiController::class,'checkEmail'])->name('check-email');
Route::post('/check-mobile',[ApiController::class,'checkMobile'])->name('check-mobile');
Route::post('/streams',[ApiController::class,'streams'])->name('streams');
Route::post('/subjects',[ApiController::class,'subjects'])->name('subjects');
Route::post('/student-register',[ApiController::class,'postStudentRegister'])->name('student-register');
Route::post('/professor-register',[ApiController::class,'postProfessorRegister'])->name('professor-register');
Route::post('/resend-otp',[ApiController::class,'resendOtp'])->name('resend-otp');
Route::post('/verify-email',[ApiController::class,'verifyEmail'])->name('verify-email');
Route::post('/forgot-password',[ApiController::class,'forgotPassword'])->name('forgot-password');
Route::post('/verify-forgot-password',[ApiController::class,'verifyForgotPassword'])->name('verify-forgot-password');
Route::post('/verify-change-password',[ApiController::class,'verifyChangePassword'])->name('verify-change-password');

Route::group([
    'middleware' => 'api_access'
], function () {
    Route::post('/moderator-posts',[ApiController::class,'moderatorPosts'])->name('moderator-posts');
    Route::post('/student-package-list',[ApiController::class,'studentPackageList'])->name('student-package-list');
    Route::post('/all-package-list',[ApiController::class,'getAllPackageList'])->name('all-package-list');
    Route::post('/all-package-list',[ApiController::class,'getAllPackageList'])->name('all-package-list');
    Route::post('/upload-notes',[ApiController::class,'userUploadNotes'])->name('user-upload-notes');
});
