<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ProcessingRequestController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', function (Request $request) {
        return response()->json($request->user());
    });

    Route::get('/projects', [ProjectController::class, 'index']);

    Route::get('/processing-requests', [ProcessingRequestController::class, 'index']);
    Route::post('/processing-requests', [ProcessingRequestController::class, 'store']);
    Route::get('/processing-requests/{processingRequest}', [ProcessingRequestController::class, 'show']);
    Route::post('/processing-requests/{processingRequest}/retry', [ProcessingRequestController::class, 'retry']);

    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
});
