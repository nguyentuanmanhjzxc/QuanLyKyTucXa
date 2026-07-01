<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\BuildingController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\RoomRegistrationController;
use App\Http\Controllers\Api\RoomAssignmentController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\StudentServiceController;
use App\Http\Controllers\Api\RoomTransferRequestController;
use App\Http\Controllers\Api\UtilityReadingController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FeedbackController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ViolationController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('buildings', BuildingController::class);

Route::apiResource('students', StudentController::class);

Route::apiResource('rooms', RoomController::class);

Route::apiResource('room-registrations',RoomRegistrationController::class);

Route::apiResource('room-assignments', RoomAssignmentController::class);

Route::apiResource('services', ServiceController::class);

Route::apiResource('student-services', StudentServiceController::class);

Route::apiResource('room-transfer-requests',RoomTransferRequestController::class);

Route::apiResource('utility-readings',UtilityReadingController::class);

Route::apiResource('feedbacks',FeedbackController::class);

Route::apiResource('notifications',NotificationController::class);

Route::apiResource('violations',ViolationController::class);

Route::apiResource('users', UserController::class);

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout']);


