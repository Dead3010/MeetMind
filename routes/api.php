<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\AnalyzeController;
use App\Http\Controllers\GenerateFrontendController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/analyze', [AnalyzeController::class, 'analyze']);
    Route::post('/generate-frontend', [GenerateFrontendController::class, 'generate']);
    Route::get('/meetings', [MeetingController::class, 'index']);
    Route::post('/meetings', [MeetingController::class, 'store']);
    Route::delete('/meetings', [MeetingController::class, 'clearAll']);
    Route::delete('/meetings/{id}', [MeetingController::class, 'destroy']);
    Route::post('/meetings/{id}/preview', [MeetingController::class, 'savePreview']);
});
