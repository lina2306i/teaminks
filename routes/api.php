<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;




Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');




// Auth routes spint 1
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::put('/auth/update', [AuthController::class, 'update']);
});




// Project routes sprint 2
//🔹 Routes accessibles à TOUS les users connectés
Route::middleware('auth:sanctum')->get('/me', [AuthController::class, 'me']);
//🔹 Routes réservées aux Leader
Route::middleware(['auth:sanctum', 'role:leader'])->group(function () {
    Route::post('/projects', [ProjectController::class, 'store']);
});
Route::middleware(['auth:sanctum', 'role:leader'])->group(function () {
    Route::get('/team', [TeamController::class, 'team']);
});

//🔹 Routes Leader + Admin
Route::middleware(['auth:sanctum', 'role:leader,admin'])->group(function () {
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);
});
//🔹 Routes Admin only
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/admin/users', [AdminController::class, 'index']);
});


/*Route::middleware('')->group(function () {
    Route::get('
    ', [AuthController::class,'
    ']);
});*/
