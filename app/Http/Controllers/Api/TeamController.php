<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\User;

class TeamController extends Controller
{
    /**
     * Leader crée une équipe
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $team = Team::create([
            'name' => $request->name,
            'description' => $request->description,
            'leader_id' => auth()->id(),
        ]);

        // Leader devient membre automatiquement
        $team->members()->attach(auth()->id(), ['status' => 'accepted']);

        return response()->json([
            'message' => 'Team created successfully',
            'team' => $team
        ], 201);
    }

    /**
     * Member demande à rejoindre une équipe
     */
    public function join(Request $request)
    {
        $request->validate([
            'team_id' => 'required|exists:teams,id',
        ]);

        $team = Team::findOrFail($request->team_id);

        // Vérifie si le user est déjà dans la team
        if ($team->members()->where('user_id', auth()->id())->exists()) {
            return response()->json(['message' => 'Already requested or member'], 400);
        }

        $team->members()->attach(auth()->id(), ['status' => 'pending']);

        return response()->json(['message' => 'Join request sent']);
    }

    /**
     * Leader accepte un membre
     */
    public function accept(Request $request)
    {
        $request->validate([
            'team_id' => 'required|exists:teams,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $team = Team::findOrFail($request->team_id);

        // Vérifie que l'auth user est leader
        if ($team->leader_id !== auth()->id()) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $team->members()->updateExistingPivot($request->user_id, ['status' => 'accepted']);

        return response()->json(['message' => 'Member accepted']);
    }

    /**
     * Leader supprime un membre ou la team
     */
    public function remove(Request $request)
    {
        $request->validate([
            'team_id' => 'required|exists:teams,id',
            'user_id' => 'sometimes|exists:users,id',
        ]);

        $team = Team::findOrFail($request->team_id);

        if ($team->leader_id !== auth()->id()) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        if ($request->filled('user_id')) {
            // Supprimer un membre
            $team->members()->detach($request->user_id);
            return response()->json(['message' => 'Member removed']);
        }

        // Supprimer la team entière
        $team->delete();
        return response()->json(['message' => 'Team deleted']);
    }

    /**
     * Voir ses équipes
     */
    public function myTeam()
    {
        $teams = auth()->user()->teams()->with('leader')->get();
        return response()->json($teams);
    }
}
