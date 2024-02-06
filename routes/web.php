<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RevenueController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// clear cache
Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('config:cache');
    $exitCode = Artisan::call('view:clear');
    $exitCode = Artisan::call('optimize:clear');
    $exitCode = Artisan::call('route:clear');
    return '<center>Cache clear</center>';
});

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    // admin login panel
    Route::get('/', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'checkUser'])->name('check.user');

    Route::middleware(["isAdmin"])->group(function () {
        // dashboard
        Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

        // users
        Route::get('/users', [UserController::class, 'users'])->name('users.list');
        Route::get('/user-details/{id}', [UserController::class, 'userDetails'])->name('users.details');
        Route::post('/user-change-status', [UserController::class, 'userChangeStatus'])->name('users.change.status');

        // revenue management
        Route::get('/revenue-management', [RevenueController::class, 'revenueManagement'])->name('revenue-management.list');
        Route::get('/subscription-plan', [RevenueController::class, 'subscriptionPlan'])->name('revenue-management.plans');

        // community management
        Route::get('/community-management', [CommunityController::class, 'communityManagement'])->name('community-management.list');
        Route::post('/add-new-community', [CommunityController::class, 'communityStoreData'])->name('community-management.store.data');
        Route::post('/change-community-status', [CommunityController::class, 'changeCommunityStatus'])->name('community-management.change.status');
        Route::get('/community-management-details/{id}', [CommunityController::class, 'communityManagementDetails'])->name('community-management.details');
        Route::get('/community-approval', [CommunityController::class, 'communityApproval'])->name('community-management.approval');
        Route::get('/community-approval-details/{id}', [CommunityController::class, 'communityApprovalDetails'])->name('community-management.approval-details');
        Route::get('/community-post-details/{id}', [CommunityController::class, 'communityPostDetails'])->name('community-management.post-details');

        // support & communication
        Route::get('/support-communication', [SupportController::class, 'supportCommunication'])->name('support');
        Route::get('/notifications', [SupportController::class, 'notification'])->name('notification');

        // profile
        Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
        Route::post('/profile', [AuthController::class, 'updateData'])->name('update.profile');
        Route::get('/check-pwsd', [AuthController::class, 'checkPassword'])->name('check.password');
        Route::post('/update-password', [AuthController::class, 'updatePassword'])->name('update.password');

        // rating & reviews
        Route::get('/rating-reviews', [DashboardController::class, 'ratingReviews'])->name('rating-review.list');

        // logout
        Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});
