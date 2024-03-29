<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommunityController;
use App\Http\Controllers\Api\JournalController;
use App\Http\Controllers\Api\RoutineController;
use App\Http\Controllers\Api\SupportController;
use App\Http\Controllers\Api\UserController;
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
Route::get('test-push-notification', [AuthController::class, 'testPushNotification']);
Route::get('mood', [JournalController::class, 'mood']);
Route::get('plans', [CommunityController::class, 'plans']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('logout', [AuthController::class, "logout"]);
    Route::get('search-criteria', [JournalController::class, 'searchCriteria']);

    Route::get('notifications', [SupportController::class, 'notifications']);
    Route::post('notification-seen', [SupportController::class, 'notificationSeen']);

    Route::get('home', [UserController::class, 'home']);
    Route::get('search', [UserController::class, 'search']);
    Route::post('mood-capture', [UserController::class, 'moodCapture']);
    Route::get('mood-calender', [UserController::class, 'moodCalender']);
    Route::post('submit-rating', [UserController::class, 'submitRating']);
    Route::get('users-list', [UserController::class, 'userList']);

    Route::get('profile', [AuthController::class, 'profile']);
    Route::post('update-profile', [AuthController::class, 'updateProfile']);
    Route::post('change-password', [AuthController::class, 'changePassword']);

    Route::get('journals', [JournalController::class, 'journal']);
    Route::get('journal/{id}', [JournalController::class, 'journalDetails']);
    Route::delete('delete-journal', [JournalController::class, 'deleteJournal']);
    Route::post('create-journal', [JournalController::class, 'createJournal']);
    Route::post('edit-journal', [JournalController::class, 'editJournal']);

    Route::post('create-criteria', [JournalController::class, 'createCriteria']);
    Route::post('edit-criteria', [JournalController::class, 'editCriteria']);
    Route::delete('delete-criteria', [JournalController::class, 'deleteCriteria']);

    Route::get('community-list', [CommunityController::class, 'communityList']);
    Route::get('my-community-list', [CommunityController::class, 'myCommunityList']);
    Route::get('community/{id}', [CommunityController::class, 'communityDetails']);
    Route::post('follow-unfollow', [CommunityController::class, 'followUnfollow']);
    Route::post('create-community', [CommunityController::class, 'createCommunity']);
    Route::post('edit-community', [CommunityController::class, 'editCommunity']);
    Route::delete('delete-community', [CommunityController::class, 'deleteCommunity']);

    Route::post('create-post', [CommunityController::class, 'createPost']);
    Route::post('edit-post', [CommunityController::class, 'editPost']);
    Route::delete('delete-post', [CommunityController::class, 'deletePost']);
    Route::get('post/{id}', [CommunityController::class, 'postDetails']);
    Route::post('like-unlike-post', [CommunityController::class, 'postLikeUnlike']);
    Route::post('post-comment', [CommunityController::class, 'postComment']);
    Route::get('report-reasons', [CommunityController::class, 'reportReason']);
    Route::post('post-report', [CommunityController::class, 'postReport']);

    Route::get('routine-category', [RoutineController::class, 'routineCategory']);
    Route::get('routine', [RoutineController::class, 'routine']);
    Route::get('routine-detail/{id}', [RoutineController::class, 'routineDetail']);
    Route::post('create-routine', [RoutineController::class, 'createRoutine']);
    Route::post('edit-routine', [RoutineController::class, 'editRoutine']);
    Route::post('share-routine', [RoutineController::class, 'shareRoutine']);
    

    Route::post('create-task', [RoutineController::class, 'createTask']);
    Route::get('task-detail/{id}', [RoutineController::class, 'taskDetail']);
    Route::post('edit-task', [RoutineController::class, 'editTask']);
    Route::delete('delete-task', [RoutineController::class, 'deleteTask']);

    Route::post('create-query', [SupportController::class, 'createQuery']);
    Route::get('query-list', [SupportController::class, 'queryList']);
    Route::get('inquiry-type-list', [SupportController::class, 'inquiryList']);
});

Route::get('token-expire', [AuthController::class, 'tokenExpire'])->name('login');