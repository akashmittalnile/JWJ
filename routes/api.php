<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\JournalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('send-otp', [AuthController::class, 'sendOtp']);
Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('otp-verfication', [AuthController::class, 'otpVerification']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);

Route::get('mood', [JournalController::class, 'mood']);
Route::get('search-criteria', [JournalController::class, 'searchCriteria']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('logout', [AuthController::class, "logout"]);

    Route::get('profile', [AuthController::class, 'profile']);
    Route::post('update-profile', [AuthController::class, 'updateProfile']);
    Route::post('change-password', [AuthController::class, 'changePassword']);

    Route::get('journals', [JournalController::class, 'journal']);
    Route::delete('delete-journal', [JournalController::class, 'deleteJournal']);
    Route::post('create-journal', [JournalController::class, 'createJournal']);
});

Route::get('token-expire', [AuthController::class, 'tokenExpire'])->name('login');