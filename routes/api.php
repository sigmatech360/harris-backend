<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\StatController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PodcastController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SocialAuthController;


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

Route::post('/social-login', [SocialAuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('login', function(){
    return response()->json('Please Login!');
})->name('login');

Route::POST('user-login', [AuthController::class, 'login']);
Route::get('check-login', [AuthController::class, 'checkLogin']);
Route::POST('user-register', [AuthController::class, 'register']);
Route::post('forgot-password', [AuthController::class, 'forgot_password']); //email
Route::post('otp-verification', [AuthController::class, 'otp_verification']); //email,otp
Route::post('reset-password', [AuthController::class, 'reset_password']); //email,otp,newpass


// get all programs
Route::get('program', [ProgramController::class, 'index']);
// contact form
Route::POST('submit-query', [GeneralController::class, 'contactForm']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    // _____________Auth Restricted routes_________________
    Route::post('change-password', [AuthController::class, 'changePass']);
    Route::post('logout', [AuthController::class, 'logout']);
    // profile update
    Route::get('/edit-profile', [UserController::class, 'edit']);
    Route::POST('update-profile', [UserController::class, 'update']);
    // Route::POST('delete-profile/{id}', [UserController::class, 'destroy']);

    Route::get('order-history/{id?}', [UserController::class, 'myOrders']);
    Route::post('/generate-report', [ReportController::class, 'generateAndSendReport']);   
    Route::post('/generate-report2', [ReportController::class, 'generateAndSendReport2']); 
    
    Route::get('pricing', [UserController::class, 'getPricing']);
    
    // search
    Route::post('search-records', [ReportController::class, 'search']);
    Route::post('general-search-records', [ReportController::class, 'generalSearch']);
    
    
    Route::get('/notifications',[UserController::class, 'allNotifications']);
    Route::get('notifications/unread',[UserController::class, 'unreadNotifications']);
    Route::post('notifications/mark-as-read/{id}',[UserController::class, 'markAsRead']);
    Route::get('notifications/mark-all-as-read/',[UserController::class, 'markAllAsRead']);
    
    
    
    Route::middleware(['admin'])->group(function () {
        // _______________Auth & Admin restricted routes____________
        
        //dashboard
         Route::get('admin/dashboard-analytics', [UserController::class, 'dashboardAnalytics']);
        
        // profile edit/update
        Route::get('admin/edit-profile', [UserController::class, 'showAdmin']);
        Route::POST('admin/update-profile', [UserController::class, 'updateAdmin']);
        
        // user
        Route::resource('admin/user', UserController::class);
        Route::POST('admin/user-update/{id}', [UserController::class, 'update']);
       
         // program
        Route::resource('admin/program', ProgramController::class);
        Route::post('admin/program/update/{id}', [ProgramController::class,'update']);
        
        //order
        Route::get('orders', [UserController::class, 'allOrders']);
        Route::get('orders/{id}', [UserController::class, 'orderView']);
       // Route::post('orders/{id}', [UserController::class, 'orderUpdate']);
       Route::post('send-social-media-report/{id}', [UserController::class, 'sendSocialMediaReport']);
       
       //pricing
       Route::get('admin/pricing', [UserController::class, 'getPricing']);
       Route::post('admin/pricing', [UserController::class, 'updatePricing']);
    });

    // Route::middleware(['user'])->group(function () {
    //     Route::controller(UserController::class)->group(function () {});
    // });

});

