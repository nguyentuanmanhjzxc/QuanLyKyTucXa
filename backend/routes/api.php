<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\BuildingController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\RoomRegistrationController;
use App\Http\Controllers\Api\RoomAssignmentController;
use App\Http\Controllers\Api\ServiceController;




Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource( 'buildings', BuildingController::class);
Route::apiResource('students', StudentController::class);
Route::apiResource('rooms', RoomController::class);
Route::apiResource('room-registrations',RoomRegistrationController::class);
Route::apiResource('room-assignments', RoomAssignmentController::class);
Route::apiResource('services', ServiceController::class);