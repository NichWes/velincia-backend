<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ProjectItemController;
use App\Http\Controllers\Api\MaterialController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::get('/projects/{project}', [ProjectController::class, 'show']);
    Route::get('/projects/{project}/estimate', [ProjectController::class, 'estimate']);

    Route::get('/projects/{project}/items', [ProjectItemController::class, 'index']);
    Route::post('/projects/{project}/items', [ProjectItemController::class, 'store']);

    Route::patch('/project-items/{item}', [ProjectItemController::class, 'update']);
    Route::delete('/project-items/{item}', [ProjectItemController::class, 'destroy']);

    Route::get('/materials', [MaterialController::class, 'index']);
    Route::get('/materials/{material}', [MaterialController::class, 'show']);

    Route::post('/materials', [MaterialController::class, 'store']);
    Route::patch('/materials/{material}', [MaterialController::class, 'update']);
    Route::delete('/materials/{material}', [MaterialController::class, 'destroy']);

});