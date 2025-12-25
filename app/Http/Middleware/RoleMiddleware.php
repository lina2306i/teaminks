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
     * @param  \Closure(\Illuminate\Http\Request): \Symfony\Component\HttpFoundation\Response  $next
     * @param  string  $roles  // Peut être un seul rôle ou plusieurs séparés par des virgules : "leader" ou "leader,admin"
     * @return \Symfony\Component\HttpFoundation\Response
     */
    /*
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
    }*/
    //ou bien
    public function handle(Request $request, Closure $next, $role)
    {
        //if (! $request->user() || $request->user()->role !== $role) {
          //  return response()->json(['message' => 'Unauthorized.'], 403);
        //}

        if (! $request->user() || $request->user()->role !== $role) {
            abort(403, 'Accès refusé : vous n\'avez pas le rôle requis.');
        }

        return $next($request);
    }

/*
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        // 1. Vérifier si l'utilisateur est authentifié
        if (! auth()->check()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        $user = auth()->user();

        // 2. Transformer la chaîne de rôles en tableau (ex: "leader,admin" → ['leader', 'admin'])
        $requiredRoles = array_map('trim', explode(',', $roles));

        // 3. Vérifier si l'utilisateur possède l'un des rôles requis
        $hasRequiredRole = false;

        // Cas 1 : Utilisation du package Spatie Laravel-Permission
        if (method_exists($user, 'hasRole')) {
            foreach ($requiredRoles as $role) {
                if ($user->hasRole($role)) {
                    $hasRequiredRole = true;
                    break;
                }
            }
        }
        // Cas 2 : Champ simple "role" dans la table users (string)
        else {
            $hasRequiredRole = in_array($user->role, $requiredRoles, true); // strict comparison
        }

        // 4. Si pas le bon rôle → accès refusé
        if (! $hasRequiredRole) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => 'Forbidden. Vous n\'avez pas les permissions nécessaires.'], 403);
            }

            return redirect()->route('home')->with('error', 'Accès refusé : vous n\'avez pas les permissions nécessaires.');
        }

        // 5. Tout est OK → continuer
        return $next($request);
    }*/
}
