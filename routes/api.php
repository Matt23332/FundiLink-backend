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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::resource('users', UserController::class)->only(['index']);

// Other routes
Route::resource('roles', RoleController::class);
Route::resource('services', ServiceController::class);
Route::resource('service-requests', ServiceRequestController::class);
Route::resource('reviews', ReviewsController::class);
Route::resource('categories', CategoriesController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout']);
});
