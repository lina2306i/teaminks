<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;


// Pages publiques

Route::get('/', function () {
    return view('welcome');
});

/*Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');*/

//window home route
Route::get('/win', function () {
    return view('windoHome');
})->name('home');

//spint 1 : auth routes
//frontend ::
Route::get('/login', fn () => view('auth.login'))
    ->name('login.form');
Route::get('/register', fn () => view('auth.register'))
    ->name('register.form');

// Pages sécurisées
Route::middleware('auth:sanctum')->group(function () {
    // Dashboard page ::
    Route::get('/home', fn () => view('home'))
    ->middleware('auth')
    ->name('home');
    //Profile page ::
    Route::get('/profile', fn () => view('profile'))
        ->middleware('auth');

    //Auth actions :: backend
    Route::post('/register', [AuthController::class, 'register'])
        ->name('register');
    Route::post('/login', [AuthController::class, 'login'])
        ->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])
        ->middleware('auth:sanctum')->name('logout');

// ────────── Role Based Pages :: sprint 2 not yet fixed──────────

    // Leader pages
    Route::middleware('role:leader')->group(function () {
        Route::get('/teams/create', fn() => view('teams.create'))->name('teams.create');
        Route::get('/projects/create', fn() => view('projects.create'))->name('projects.create');
    });

    // Admin pages
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/users', fn() => view('admin.users'))->name('admin.users');
    });

    // Member pages
    Route::middleware('role:member')->group(function () {
        Route::get('/my-teams', fn() => view('teams.my'))->name('my.teams');
    });


});

// Web for role Middleware testing
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/users', [AdminController::class, 'index']);
});
