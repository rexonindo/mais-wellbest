<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\DepartmentController;
use App\Http\Controllers\Api\V1\ShiftController;
use App\Http\Controllers\Api\V1\MachineController;
use App\Http\Controllers\Api\V1\EmployeeController;
use App\Http\Controllers\Api\V1\ItemController;
use App\Http\Controllers\Api\V1\ProcessController;
use App\Http\Controllers\Api\V1\ProductRouteController;
use App\Http\Controllers\Api\V1\WorkOrderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function () {
    // Authentication routes
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        // Master Data
        Route::apiResource('departments', DepartmentController::class);
        Route::apiResource('shifts', ShiftController::class);
        Route::apiResource('machines', MachineController::class);
        Route::apiResource('employees', EmployeeController::class);
        Route::apiResource('items', ItemController::class);
        Route::apiResource('processes', ProcessController::class);
        Route::apiResource('product-routes', ProductRouteController::class);

        // Transactions
        Route::apiResource('work-orders', WorkOrderController::class);

        // Add other API resource routes here in the next steps
    });
});
