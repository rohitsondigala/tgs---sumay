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
Route::post('/logout',[ApiController::class,'logout'])->name('logout');
Route::post('/check-email',[ApiController::class,'checkEmail'])->name('check-email');
Route::post('/check-mobile',[ApiController::class,'checkMobile'])->name('check-mobile');
Route::post('/streams',[ApiController::class,'streams'])->name('streams');
Route::post('/subjects',[ApiController::class,'subjects'])->name('subjects');
Route::post('/countries',[ApiController::class,'countries'])->name('countries');
Route::post('/states',[ApiController::class,'states'])->name('states');
Route::post('/cities',[ApiController::class,'cities'])->name('cities');
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
    Route::post('/upload-notes',[ApiController::class,'userUploadNotes'])->name('user-upload-notes');
    Route::post('/get-notes',[ApiController::class,'userGetNotes'])->name('user-get-notes');
    Route::post('/get-student-list',[ApiController::class,'getStudentList'])->name('get-student-list');
    Route::post('/get-professor-list',[ApiController::class,'getProfessorList'])->name('get-professor-list');
    Route::post('/edit-user-profile',[ApiController::class,'editUserProfile'])->name('edit-user-profile');
    Route::post('/update-user-profile',[ApiController::class,'updateUserProfile'])->name('update-user-profile');
    Route::post('/user-change-password',[ApiController::class,'userChangePassword'])->name('user-change-password');
    Route::post('/get-user-notes',[ApiController::class,'getUserNotes'])->name('get-user-notes');
    Route::post('/delete-user-notes',[ApiController::class,'deleteUserNotes'])->name('delete-user-notes');
    Route::post('/edit-user-notes',[ApiController::class,'userEditNotes'])->name('user-edit-notes');
    Route::post('/student-purchase-subjects',[ApiController::class,'studentPurchasedSubjects'])->name('student-purchased-subjects');
    Route::post('/add-review',[ApiController::class,'addReview'])->name('add-review');
    Route::post('/get-submitted-reviews',[ApiController::class,'getSubmittedReviews'])->name('get-submitted-reviews');
    Route::post('/post-query',[ApiController::class,'postQuery'])->name('post-query');
    Route::post('/user-get-query',[ApiController::class,'userGetQuery'])->name('user-get-query');
    Route::post('/professor-reply-query',[ApiController::class,'professorReplyQuery'])->name('professor-reply-query');
    Route::post('/delete-note-file',[ApiController::class,'deleteNoteFile'])->name('delete-note-file');
    Route::post('/purchase-package',[ApiController::class,'purchasePackage'])->name('purchase-package');
    Route::post('/get-note-detail',[ApiController::class,'getNoteDetail'])->name('get-note-detail');

});
