<?php

use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\TaskController;
use App\Http\Controllers\API\TaskFileController;
use App\Http\Controllers\API\TaskTimeLogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    // Task routes
    Route::apiResource('tasks', TaskController::class);

    // Comment routes
    Route::get('tasks/{task}/comments', [CommentController::class, 'index']);
    Route::post('tasks/{task}/comments', [CommentController::class, 'store']);

    // Time log routes
    Route::get('tasks/{task}/time-log', [TaskTimeLogController::class, 'index']);
    Route::post('tasks/{task}/time-log', [TaskTimeLogController::class, 'store']);

    // File routes
    Route::get('tasks/{task}/files', [TaskFileController::class, 'index']);
    Route::post('tasks/{task}/upload', [TaskFileController::class, 'store']);

    // Auth routes
    Route::middleware('auth:sanctum')->post('/logout', [App\Http\Controllers\API\AuthController::class, 'logout']);
});

Route::post('/register', [App\Http\Controllers\API\AuthController::class, 'register']);
Route::post('/login', [App\Http\Controllers\API\AuthController::class, 'login']);