<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ExampleController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Unprotected routes
Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now()->toIso8601String()
    ]);
});

Route::get('/examples/cached', [ExampleController::class, 'custom_cached']);
Route::apiResource('examples', ExampleController::class);