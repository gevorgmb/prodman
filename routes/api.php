<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ContactVerificationController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/send-email-verification', [ContactVerificationController::class, 'sendVerificationEmail']);
    Route::post('/verify-email-code', [ContactVerificationController::class, 'verifyEmailCode']);
    Route::get('/current-user', [UserController::class, 'index']);
    Route::patch('/current-user', [UserController::class, 'update']);
});
