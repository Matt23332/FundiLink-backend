<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceRequestController;
use App\Http\Controllers\ReviewsController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\VerifyEmailController;
use App\Http\Controllers\ResendEmailVerificationController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
// Route::resource('users', UserController::class);
Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, 'verify'])
    ->name('verification.verify')
    ->middleware(['signed', 'throttle:6,1']);
Route::post('/email/resend', [ResendEmailVerificationController::class, 'resend'])
    ->name('verification.resend')
    ->middleware('throttle:6,1');

// Other routes
Route::resource('roles', RoleController::class);
Route::resource('services', ServiceController::class);
// Route::resource('service-requests', ServiceRequestController::class);
// Route::patch('service-requests/{id}/cancel', [ServiceRequestController::class, 'cancel']);
Route::resource('reviews', ReviewsController::class);
Route::resource('categories', CategoriesController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('users', UserController::class);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::resource('service-requests', ServiceRequestController::class);
    Route::patch('service-requests/{id}/cancel', [ServiceRequestController::class, 'cancel']);
});
