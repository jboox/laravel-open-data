<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DatasetApiController;

Route::get('/datasets', [DatasetApiController::class, 'index']);
Route::post('/datasets', [DatasetApiController::class, 'store']);
Route::get('/datasets/{dataset}', [DatasetApiController::class, 'show']);
Route::get('/datasets/{dataset}/download/{format?}', [DatasetApiController::class, 'download'])
    ->where('format', 'csv|xlsx|json');

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/datasets', [DatasetApiController::class, 'store']);
    Route::put('/datasets/{dataset}', [DatasetApiController::class, 'update']);
    Route::delete('/datasets/{dataset}', [DatasetApiController::class, 'destroy']);
});