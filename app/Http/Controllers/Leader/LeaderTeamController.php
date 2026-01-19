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
        if ($team->leader_id !== Auth::id() && !$team->members()->where('user_id', auth()->id())->exists()) {
            abort(403);
        }

        $team->load(['members', 'pendingMembers', 'projects.tasks']);

        // Eager loading optimisé pour éviter N+1
        /*$team->load([
            'members.tasks' => fn($q) => $q->whereIn('project_id', $team->projects->pluck('id')),
            'pendingMembers',
            'projects.tasks.assignedTo',
        ]);*/

        // Récupère les IDs des membres déjà dans l'équipe (acceptés)
        $memberIds = $team->members->pluck('id')->all(); // safe : collection déjà chargée ---after add getmbrAttributs can use that :: $memberIds = $team->member_ids; // propre et lisible
        // IMPORTANT : récupère les utilisateurs NON encore dans l'équipe (pour l'invitation)
        $availableUsers = User::whereNotIn('id', $memberIds)
                            // $team->users->pluck('id') ?? collect()->pluck('id'))
                            ->where('id', '!=', auth()->id()) // exclure soi-même
                            ->orderBy('name')
                            ->get(['id', 'name', 'email']); // on prend seulement ce qu'il faut
                            //->get();

        return view('leader.team.show', compact('team', 'availableUsers'));
        //return view('leader.team.show', compact('team'));
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

    public function promote(Team $team, User $user)
    {
        if ($team->leader_id !== auth()->id()) {
            abort(403);
        }

        $team->users()->updateExistingPivot($user->id, ['role' => 'admin']);

        return back()->with('success', "{$user->name} est maintenant Admin de l'équipe.");
    }

    public function demote(Team $team, User $user)
    {
        if ($team->leader_id !== auth()->id()) {
            abort(403);
        }

        $team->users()->updateExistingPivot($user->id, ['role' => 'member']);

        return back()->with('success', "{$user->name} est revenu au rôle Membre.");
    }

    public function inviteBySearch(Request $request, Team $team)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);

        $user = User::findOrFail($request->user_id);

        // Ici tu peux parser le search pour trouver l'ID (ex: extraire entre parenthèses)
        // Pour simplifier, on suppose que tu envoies directement user_id via JS ou hidden input
        // Version basique : cherche par name/email
        $users = User::where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->first();

        if (!$users) {
            return back()->with('error', 'Utilisateur non trouvé.');
        }

        if ($team->users()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'Ce membre est déjà dans l\'équipe.');
        }
        if ($team->users()->where('user_id', $users->id)->exists()) {
            return back()->with('error', 'Ce membre est déjà dans l\'équipe.');
        }

        // Ajoute en pending avec rôle member
        $team->users()->attach($user->id, [
            'status' => 'pending',
            'accepted' => false,
            'role' => 'member'
        ]);
         $team->users()->attach($users->id, [
            'status' => 'pending',
            'accepted' => false,
            'role' => 'member'
        ]);

        // TODO: envoyer notification au user (on peut le faire plus tard)

        return back()->with('success', "Invitation envoyée à {$user->name}.");
        //return back()->with('success', "Invitation envoyée à {$users->name}.");
    }

    public function regenerateCode(Team $team)
    {
        if ($team->leader_id !== auth()->id()) {
            abort(403);
        }

        $team->regenerateInviteCode();
        return back()->with('success', 'Nouveau code d\'invitation généré.');
    }


}
