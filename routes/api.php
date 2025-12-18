<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\AdminController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');




// routes spint 1

// ────────── PUBLIC ──────────
// Auth routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
// ────────── PROTECTED ──────────
Route::middleware('auth:sanctum')->group(function () {
    //───── Infos du user connecté ─────
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/auth/update', [AuthController::class, 'update']);


    // routes sprint 2

    // ────────── TEAMS ──────────
    // ───── Routes Leader ─────
    Route::middleware('role:leader')->group(function () {
        Route::post('/teams', [TeamController::class, 'store']);  // créer équip
        Route::get('/team', [TeamController::class, 'team']); // liste équipes
        Route::post('/teams/accept', [TeamController::class, 'accept']) ;// accepter membre
        Route::delete('/teams/remove', [TeamController::class, 'remove']); //  supprime un membre ou la team
        Route::post('/projects', [ProjectController::class, 'store']); // créer projet
    });

    // Leader + Admin
    Route::middleware('role:leader,admin')->group(function () {
        Route::delete('/projects/{id}', [ProjectController::class, 'destroy']); // supprimer projet
    });

    // Admin only
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/users', [AdminController::class, 'index']); // liste users
        Route::delete('/admin/users/{id}', [AdminController::class, 'destroy']); // supprimer user
    });

    // Member only
    Route::middleware('role:member')->group(function () {
        Route::get('/my-teams', function () {
            return auth()->user()->teams;
        });
        Route::post('/teams/join', [TeamController::class, 'join']); // membre demander à rejoindre équipe

    });
    // Voir ses équipes (member ou leader)
    Route::get('/my-team', [TeamController::class, 'myTeam']);

});

// ───── Routes accessibles à TOUS les users connectés ───── ::: sprint 2
Route::middleware('auth:sanctum')->get('/me', [AuthController::class, 'me']);


/*Route::middleware('')->group(function () {
    Route::get('
    ', [AuthController::class,'
    ']);
});*/
