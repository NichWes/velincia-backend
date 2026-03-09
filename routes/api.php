<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ProjectItemController;
use App\Http\Controllers\Api\MaterialController;
use App\Http\Controllers\Api\OrderController;

// public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// authenticated routes
Route::middleware('auth:sanctum')->group(function () {
    // auth
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // materials
    Route::get('/materials', [MaterialController::class, 'index']);
    Route::get('/materials/{material}', [MaterialController::class, 'show']);

    // projects
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::get('/projects/{project}', [ProjectController::class, 'show']);
    Route::get('/projects/{project}/estimate', [ProjectController::class, 'estimate']);

    // project items
    Route::get('/projects/{project}/items', [ProjectItemController::class, 'index']);
    Route::post('/projects/{project}/items', [ProjectItemController::class, 'store']);
    Route::patch('/project-items/{item}', [ProjectItemController::class, 'update']);
    Route::delete('/project-items/{item}', [ProjectItemController::class, 'destroy']);

    // orders - user/contractor side
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::post('/orders/{order}/submit', [OrderController::class, 'submit']);

    // sementara masih di auth biasa 
    // nanti dapat dipindah jika ada webhook/payment internal 
    Route::post('/orders/{order}/mark-paid', [OrderController::class, 'markPaid']);
    Route::post('/orders/{order}/apply-to-project-items', [OrderController::class, 'applyToProjectItems']);
});

// admin only routes
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    // materials
    Route::post('/materials', [MaterialController::class, 'store']);
    Route::patch('/materials/{material}', [MaterialController::class, 'update']);
    Route::delete('/materials/{material}', [MaterialController::class, 'destroy']);

    // admin order actions
    Route::post('/orders/{order}/admin-adjust', [OrderController::class, 'adminAdjust']);
    Route::post('/orders/{order}/set-waiting-payment', [OrderController::class, 'setWaitingPayment']);

    // Route::post('/orders/{order}/mark-paid', [OrderController::class, 'markPaid']);
    // Route::post('/orders/{order}/apply-to-project-items', [OrderController::class, 'applyToProjectItems']);
});