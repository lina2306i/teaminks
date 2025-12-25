<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

//Quand tout marche, tu pourras recréer les Form Requests propres avec :
//php artisan make:request LoginRequest
//php artisan make:request RegisterRequest

class UsersController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
            'remember' => 'sometimes|boolean',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role === 'leader') {
                return redirect()->intended(route('leader.dashboard'));
            }

            if ($user->role === 'member') {
                return redirect()->intended(route('member.dashboard')); // à créer plus tard
            }

            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.users'));
            }

            return redirect()->intended('/m/dashboard');
        }
        //if (Auth::attempt($credentials)) {
        //    return redirect()->route('redirect.after.login');
        //}

        throw ValidationException::withMessages([
            'email' => ['Les identifiants fournis sont incorrects.'],
        ]);
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|string|email|max:255|unique:users',
            'password'   => 'required|string|min:8|confirmed',
            'position'   => 'nullable|string|max:255',
            'birthdate'  => 'nullable|date',
            'role'       => 'required|in:leader,member',
        ]);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'position'  => $request->position,
            'birthdate' => $request->birthdate,
            'role'      => $request->role,
        ]);

        Auth::login($user);

        return $user->role === 'leader'
            ? redirect(route('leader.dashboard'))
            : redirect('/m/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/win');
    }

    // Redirection après authentification selon le rôle
    protected function authenticated(Request $request, $user)
    {
        if ($user->role === 'leader') {
            return redirect('/leader/dashboard');
        }

        if ($user->role === 'member') {
            return redirect('/member/dashboard');
        }

        return redirect('/home');
    }

    public function updateProfilePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        if ($user->profile) {
            Storage::disk('profiles')->delete($user->profile);
        }

        $path = $request->file('photo')->store('/', 'profiles');
        $user->profile = $path;
        $user->save();

        return back()->with('success', 'Photo mise à jour avec succès.');
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . Auth::id(),
            'position'      => 'nullable|string|max:255',
            'old_password'  => 'nullable|required_with:new_password',
            'new_password'  => 'nullable|min:8|confirmed',
        ]);

        $user = Auth::user();

        if ($request->filled('old_password') && !Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'Ancien mot de passe incorrect.']);
        }

        $user->update([
            'name'     => $request->name,
            'email'    => $request->email,
            'position' => $request->position,
            'password' => $request->filled('new_password')
                ? Hash::make($request->new_password)
                : $user->password,
        ]);

        return back()->with('success', 'Profil mis à jour avec succès.');
    }
}

