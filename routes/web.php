<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;



Route::get('/', function () {
    return view('welcome');
});

/*Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');*/
//spint 1 : auth routes
//frontend ::
Route::get('/login', fn () => view('auth.login'))
    ->name('login.form');
Route::get('/register', fn () => view('auth.register'))
    ->name('register.form');
Route::get('/home', fn () => view('home'))
    ->middleware('auth')
    ->name('home');

//Auth actions :: backend
Route::post('/register', [AuthController::class, 'register'])
    ->name('register');
Route::post('/login', [AuthController::class, 'login'])
    ->name('login');
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum')->name('logout');
//Profile page ::
Route::get('/profile', fn () => view('profile'))
    ->middleware('auth');
