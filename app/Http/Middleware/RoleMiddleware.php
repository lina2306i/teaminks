<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $roles  // multiple roles separated by comma
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        // 1️ Vérifier si l'utilisateur est connecté
        if (! auth()->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated'], 401);
            }
            return redirect()->route('login')->with('error', 'Vous devez être connecté');
        }

        $user = auth()->user();

        // 2️ Gérer plusieurs rôles
        $rolesArray = array_map('trim', explode(',', $roles));

        // Vérification rôle Spatie ou champ role simple
        $hasRole = false;

        if (method_exists($user, 'hasRole')) {
            foreach ($rolesArray as $role) {
                if ($user->hasRole($role)) {
                    $hasRole = true;
                    break;
                }
            }
        } else {
            $hasRole = in_array($user->role, $rolesArray);
        }

        if (! $hasRole) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Access denied'], 403);
            }
            return redirect()->route('home')->with('error', 'Accès refusé');
        }

        return $next($request);
    }
    //ou bien
    /* public function handle(Request $request, Closure $next, $role)
    {
        if (! $request->user() || $request->user()->role !== $role) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        return $next($request);
    }*/
}
