<?php

namespace App\Http\Controllers\Leader;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
class LeaderTeamController extends Controller
{
    //gestion membres équipe pour leader

    public function index()
    {
        $team = auth()->user()->teamsAsLeader->first(); // ou sélection multiple plus tard
        $pendingRequests = $team?->pendingMembers ?? collect();
        $currentMembers = $team?->members ?? collect();

        return view('leader.team.index', compact('team', 'pendingRequests', 'currentMembers'));
    }

    public function create()
    {
        return view('leader.team.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Auth::user()->teamsAsLeader()->create($validated);

        return redirect()->route('leader.team.index')->with('success', 'Team created successfully!');
    }

    public function show(Team $team)
    {
        if ($team->leader_id !== Auth::id()) {
            abort(403);
        }

        $team->load(['members', 'pendingMembers', 'projects.tasks']);

        return view('leader.team.show', compact('team'));
    }
    public function edit(Team $team)
    {
        if ($team->leader_id !== Auth::id()) {
            abort(403);
        }

        return view('leader.team.edit', compact('team'));
    }

    public function update(Request $request, Team $team)
    {
        if ($team->leader_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $team->update($validated);

        return redirect()->route('leader.team.show', $team)->with('success', 'Team updated successfully!');
    }

    public function accept(User $user)
    {

        $team = auth()->user()->teamsAsLeader->first();
        //$team->members()->updateExistingPivot($user->id, ['status' => 'accepted']);

        if ($team->leader_id !== Auth::id()) abort(403);

        if ($team && $team->pendingMembers->contains($user)) {
            $team->members()->updateExistingPivot($user->id, ['status' => 'accepted']);
            return back()->with('success', 'Member accepted successfully.');
        }

        //return back()->with('success', 'Membre accepté !');
        return back()->with('error', 'Invalid request.');
    }

    public function reject(User $user)
    {
        $team = auth()->user()->teamsAsLeader->first();
        //$team->members()->detach($user->id);
        //return back()->with('success', 'Demande refusée');
        if ($team->leader_id !== Auth::id()) abort(403);
        if ($team && $team->pendingMembers->contains($user)) {
            $team->members()->detach($user->id);
            return back()->with('success', 'Request rejected.');
        }

        return back()->with('error', 'Invalid request.');
    }

    public function remove($pivotId)
    {
        $team = Auth::user()->teamsAsLeader->first();

        $pivot = $team->members()->wherePivot('id', $pivotId)->first();

        if ($pivot) {
            $team->members()->detach($pivot->id);
            return back()->with('success', 'Member removed from team.');
        }

        return back()->with('error', 'Invalid action.');
    }
}
