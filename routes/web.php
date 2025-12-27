<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\UsersController;
use App\Http\Controllers\Leader\LeaderTaskController;
use App\Http\Controllers\Leader\LeaderProjectController;
use App\Http\Controllers\Leader\LeaderPostController;
use App\Http\Controllers\Leader\LeaderTeamController;
use App\Http\Controllers\Leader\LeaderController;

// Pages publiques

Route::get('/', function () {
    return view('welcome');
});

/*Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');*/

//window home route
Route::get('/win', function () {   return view('components.features');})
    ->name('home');

//spint 1 : auth routes
//frontend ::
/*
Route::get('/login', fn () => view('auth.login'))
    ->name('login.form');
Route::get('/register', fn () => view('auth.register'))
    ->name('register.form');
*/

Route::get('/login', [UsersController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [UsersController::class, 'login']);

Route::get('/register', [UsersController::class, 'showRegisterForm'])->name('register.form');

Route::post('/register', [UsersController::class, 'register']);
Route::post('/logout', [UsersController::class, 'logout'])->name('logout');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/'); // ← ou route('home')
})->name('logout');

/*Routes protégées
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn() => view('dashboard.leader'))->name('leader.dashboard');
    Route::get('/m/dashboard', fn() => view('dashboard.member'))->name('member.dashboard');

    Route::post('/profile/photo', [UsersController::class, 'updateProfilePhoto'])->name('profile.photo');
    Route::patch('/profile', [UsersController::class, 'updateProfile'])->name('profile.update');
    Route::get('/profile', [UsersController::class, 'profile'])->name('profile.edit');
});*/


// Pages sécurisées
Route::middleware('auth:sanctum')->group(function () {
    // Dashboard page ::
   // Route::get('/homeapp', fn () => view('homeapp'))
    //->middleware('auth')
   // ->name('homeapp');
    //Profile page ::
   /* Route::get('/profile', fn () => view('profile'))
        ->middleware('auth');

    //Auth actions :: backend
    Route::post('/register', [AuthController::class, 'register'])
        ->name('register');
    Route::post('/login', [AuthController::class, 'login'])
        ->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])
        ->middleware('auth:sanctum')->name('logout');*/

    // Redirection automatique après login selon le rôle
    // (tu peux aussi le mettre dans UsersController@login si tu préfères)
    Route::get('/redirect', function () {
        $user = auth()->user();

        if ($user->role === 'leader') {
            return redirect()->route('leader.dashboard');
        }

        if ($user->role === 'member') {
            return redirect()->route('member.dashboard'); // à créer plus tard
        }

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('home');
    })->name('redirect.after.login');

// ────────── Role Based Pages :: sprint 2 not yet fixed──────────
/*->middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':leader'])
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
    });*/


});

// Web for role Middleware testing
//Route::middleware(['auth', 'role:admin'])->group(function () {Route::get('/admin/users', [AdminController::class, 'index']);});



//leader  page view

Route::middleware(['auth', 'role:leader'])
    ->prefix('leader')
    ->name('leader.')
    ->group(function () {

        Route::get('/dashboard', [LeaderController::class, 'dashboard'])->name('dashboard');  //done

        // ==================== PEOJECTS ====================
        // Projets
        Route::resource('projects', LeaderProjectController::class)->except(['show']);
        Route::get('projects/{project}', [LeaderProjectController::class, 'show'])->name('projects.show');
        Route::resource('projects', LeaderProjectController::class);

        // ==================== TASKS ====================
        // Tâches
        Route::resource('tasks', LeaderTaskController::class)->except(['show']);
        Route::get('tasks/{task}', [LeaderTaskController::class, 'show'])->name('tasks.show');

        Route::resource('tasks', LeaderTaskController::class)->names([
            'index' => 'tasks.index',
            'create' => 'tasks.create',
            'store' => 'tasks.store',
            'show' => 'tasks.show',
            'edit' => 'tasks.edit',
            'update' => 'tasks.update',
            'destroy' => 'tasks.destroy',
        ]);
        Route::post('tasks/{task}/pin', [LeaderTaskController::class, 'pin'])->name('tasks.pin');

        Route::resource('tasks', LeaderTaskController::class)
            ->only(['index', 'create', 'store', 'show']);

        // ==================== POSTS ====================
        // Liste des posts + pagination
        Route::get('posts', [LeaderPostController::class, 'index'])->name('posts.index');
        // Création
        Route::get('posts/create', [LeaderPostController::class, 'create'])->name('posts.create');
        Route::post('posts', [LeaderPostController::class, 'store'])->name('posts.store');
        // Affichage détail d'un post
        Route::get('posts/{post}', [LeaderPostController::class, 'show'])->name('posts.show');
        // Édition
        Route::get('posts/{post}/edit', [LeaderPostController::class, 'edit'])->name('posts.edit');
        Route::put('posts/{post}', [LeaderPostController::class, 'update'])->name('posts.update');
        // Suppression
         // Safer path to avoid conflict
        //Route::delete('posts/{post}/remove-image', [LeaderPostController::class, 'destroyImage'])->name('posts.destroy-image');
        // Delete full post
        Route::delete('posts/{post}', [LeaderPostController::class, 'destroy'])->name('posts.destroy');
       // In routes/web.php
        //Route::get('posts/{post}/image/delete', [LeaderPostController::class, 'destroyImage'])->name('posts.destroy-image');
        // ==================== ACTIONS SUR POST ====================
        // Like / Unlike (toggle)
        Route::post('posts/{post}/like', [LeaderPostController::class, 'toggleLike'])->name('posts.like.toggle');
        // Commentaires
        Route::post('posts/{post}/comment', [LeaderPostController::class, 'storeComment'])->name('posts.comment.store');
        Route::delete('posts/comment/{comment}', [LeaderPostController::class, 'destroyComment'])->name('posts.comment.destroy');



        // ==================== TEAM ====================
        // Équipe
        Route::resource('team', LeaderTeamController::class)->only(['index', 'create', 'store', 'show']);
        //Route::get('team', [LeaderTeamController::class, 'index'])->name('team.index');
       // Route::post('team/create/{user}', [LeaderTeamController::class, 'create'])->name('team.create');
        //Route::post('team/accept/{user}', [LeaderTeamController::class, 'accept'])->name('team.accept');
        //Route::delete('team/reject/{user}', [LeaderTeamController::class, 'reject'])->name('team.reject');
        //Route::delete('team/remove/{pivot}', [LeaderTeamController::class, 'remove'])->name('team.remove');

        Route::get('team/{team}/edit', [LeaderTeamController::class, 'edit'])->name('team.edit');
        Route::put('team/{team}', [LeaderTeamController::class, 'update'])->name('team.update');

        Route::post('team/{team}/accept/{user}', [LeaderTeamController::class, 'accept'])->name('team.accept');
        Route::delete('team/{team}/reject/{user}', [LeaderTeamController::class, 'reject'])->name('team.reject');
        Route::delete('team/{team}/remove/{user}', [LeaderTeamController::class, 'remove'])->name('team.remove');


        // ==================== PROFILE & OTHERS ====================
        // Autres :: Notes, Notifications, Profile, Folders
        Route::get('notifications', [LeaderController::class, 'notifications'])->name('notifications');
        Route::get('notes', [LeaderController::class, 'notes'])->name('notes');
        Route::get('profile', [LeaderController::class, 'profile'])->name('profile');
        Route::get('folders', [LeaderController::class, 'folders'])->name('folders');


    }
);


// ────────── FIN Role Based Pages ──────────
