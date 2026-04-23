<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ApartmentController;
use App\Http\Controllers\Api\ApartmentUserController;
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
    Route::apiResource('/apartments', ApartmentController::class);
    Route::get('/apartment-users/find-by-email', [ApartmentUserController::class, 'findByEmail']);
    Route::get('/apartment-users/find-by-phone', [ApartmentUserController::class, 'findByPhone']);
    Route::post('/apartment-users', [ApartmentUserController::class, 'addUserToApartment']);
    Route::delete('/apartment-users/{userId}', [ApartmentUserController::class, 'removeUserFromApartment']);
    Route::delete('/apartment-users/self/disconnect/{apartmentId}', [ApartmentUserController::class, 'disconnectFromApartment']);
    Route::get('/apartment-users', [ApartmentUserController::class, 'getUsers']);
});
