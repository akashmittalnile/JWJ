<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\RevenueController;
use App\Http\Controllers\RoutineController;
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

Route::get('/phpinfo', function () {
    phpinfo();
});

Route::stripeWebhooks('stripe-auto-payment');

Route::get('/', function () {
    return view('welcome');
});
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    // admin login panel
    Route::get('/', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'checkUser'])->name('check.user');
    Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot.password');
    Route::post('/send-otp', [AuthController::class, 'sendOtp'])->name('send.otp');
    Route::get('/otp-verification/{email}', [AuthController::class, 'otpVerification'])->name('otp.verification');
    Route::post('/send-verify', [AuthController::class, 'sendVerify'])->name('send.verify');
    Route::get('/reset-password/{email}', [AuthController::class, 'resetPassword'])->name('reset.password');
    Route::post('/reset-password', [AuthController::class, 'changePassword'])->name('change.password');

    Route::middleware(["isAdmin"])->group(function () {
        // notification
        Route::get('/notify-seen', [UserController::class, 'notifySeen'])->name('notify.seen');
        Route::get('/clear-notification', [UserController::class, 'clearNotification'])->name('clear.notification');

        // dashboard
        Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

        // users
        Route::get('/users', [UserController::class, 'users'])->name('users.list');
        Route::get('/users-reports', [UserController::class, 'usersDownloadReport'])->name('users.download.report');
        Route::get('/user-details/{id}', [UserController::class, 'userDetails'])->name('users.details');
        Route::get('/user-change-mood-data/{id}', [UserController::class, 'userChangeMoodData'])->name('users.change.mood.data');
        Route::post('/user-change-status', [UserController::class, 'userChangeStatus'])->name('users.change.status');
        Route::get('/user/routines/{id}', [UserController::class, 'userRoutines'])->name('users.routines');
        Route::get('/user/routine-details/{id}', [UserController::class, 'userRoutineDetails'])->name('users.routine.details');

        // revenue management
        Route::get('/revenue-management', [RevenueController::class, 'revenueManagement'])->name('revenue-management.list');
        Route::get('/revenue-management-reports', [RevenueController::class, 'revenueDownloadReport'])->name('revenue-management.report');
        Route::get('/subscription-plan', [RevenueController::class, 'subscriptionPlan'])->name('subscription.plans');
        Route::get('/plan-detail', [RevenueController::class, 'planDetails'])->name('subscription.plan.details');
        Route::post('/update-plan', [RevenueController::class, 'updatePlan'])->name('subscription.update.plan');

        // community management
        Route::get('/community-management', [CommunityController::class, 'communityManagement'])->name('community-management.list');
        Route::post('/add-new-community', [CommunityController::class, 'communityStoreData'])->name('community-management.store.data');
        Route::post('/change-community-status', [CommunityController::class, 'changeCommunityStatus'])->name('community-management.change.status');
        Route::post('/edit-community', [CommunityController::class, 'updateCommunity'])->name('community-management.update.data');
        Route::post('/community/delete', [CommunityController::class, 'deleteCommunity'])->name('community-management.delete');
        Route::get('/community-management-details/{id}', [CommunityController::class, 'communityManagementDetails'])->name('community-management.details');
        Route::get('/community-approval', [CommunityController::class, 'communityApproval'])->name('community-management.approval');
        Route::get('/rejected-community', [CommunityController::class, 'communityRejected'])->name('community-management.rejected');
        Route::get('/community-details/{id}', [CommunityController::class, 'communityDetails'])->name('community-management.approval-details');
        Route::post('/community-management/create-post', [CommunityController::class, 'createPost'])->name('community-management.create-post');
        Route::post('/community/post/delete', [CommunityController::class, 'deletePost'])->name('community-management.post.delete');
        Route::get('/community/post/details/{id}', [CommunityController::class, 'postDetails'])->name('community-management.post.details');
        Route::post('/community/post/comment/delete', [CommunityController::class, 'deleteComment'])->name('community-management.post.comment.delete');
        Route::post('/community/post/create-comment', [CommunityController::class, 'createComment'])->name('community-management.post.create.comment');
        Route::post('/community/post/edit-comment', [CommunityController::class, 'editComment'])->name('community-management.post.edit.comment');
        Route::post('/like-unlike-post', [CommunityController::class, 'postLikeUnlike'])->name('community-management.post.like.unlike');

        Route::get('/remove-community-dropzone-image/{id}', [CommunityController::class, 'removeImage'])->name('community-management.remove.dropzone.image');

        // journals
        Route::get('/journals', [JournalController::class, 'journalList'])->name('journal.list');
        Route::get('/journal-details/{id}', [JournalController::class, 'journalDetails'])->name('journal.details');
        Route::post('/journal-change-status', [JournalController::class, 'journalChangeStatus'])->name('journal.change.status');

        // support & communication
        Route::get('/support-communication', [SupportController::class, 'supportCommunication'])->name('support');
        Route::post('/send-reply', [SupportController::class, 'sendReply'])->name('support.send.reply');
        Route::get('/support-communication-download-report', [SupportController::class, 'supportDownloadReport'])->name('support.download.report');
        Route::get('/notifications', [SupportController::class, 'notification'])->name('notification');
        Route::post('/create-notifications', [SupportController::class, 'createNotification'])->name('notification.store');

        // chats
        Route::get('/chat/{id?}', [ChatController::class, 'chat'])->name('chats');
        Route::post('/chat-image', [ChatController::class, 'chatImage'])->name('chats.image');
        Route::post('/chat-record', [ChatController::class, 'chatRecord'])->name('chats.record');
        Route::post('/chat-record-seen', [ChatController::class, 'chatRecordSeen'])->name('chats.record.seen');

        // routine category
        Route::get('/routine-category', [RoutineController::class, 'routineCategory'])->name('routine.category');
        Route::post('/routine-category-store', [RoutineController::class, 'routineCategoryStore'])->name('routine.category.store');
        Route::post('/routine-category-update', [RoutineController::class, 'routineCategoryUpdate'])->name('routine.category.update');
        Route::post('/routine-category-delete', [RoutineController::class, 'routineCategoryDelete'])->name('routine.category.delete');

        // profile
        Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
        Route::post('/profile', [AuthController::class, 'updateData'])->name('update.profile');
        Route::get('/check-pwsd', [AuthController::class, 'checkPassword'])->name('check.password');
        Route::post('/update-password', [AuthController::class, 'updatePassword'])->name('update.password');

        // rating & reviews
        Route::get('/rating-reviews', [DashboardController::class, 'ratingReviews'])->name('rating-review.list');
        Route::post('/delete-review', [DashboardController::class, 'deleteRating'])->name('rating-review.delete');
        Route::get('/rating-reports', [DashboardController::class, 'ratingDownloadReport'])->name('rating-review.download.report');

        // contact us
        Route::get('/contact-us', [SupportController::class, 'contactList'])->name('contact.list');

        // logout
        Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});
