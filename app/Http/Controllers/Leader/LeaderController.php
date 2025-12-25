<?php

namespace App\Http\Controllers\Leader;

//use App\Http\Controllers\Controller;
use Illuminate\Routing\Controller;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;           // ← AJOUTE CETTE LIGNE

class LeaderController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $projectsCount = $user->projects()->count();
        $tasksInProgress = $user->projects()->with('tasks')->get()->pluck('tasks')->flatten()->where('status', 'in_progress')->count();
        $membersCount = $user->teamsAsLeader->pluck('members')->flatten()->unique('id')->count();
        $notificationsCount = $user->notifications()->where('read', false)->count();

        $recentProjects = $user->projects()->with('tasks')->latest()->take(6)->get();

        // Exemple de note du jour (à remplacer par ta logique réelle plus tard)
        $hasNote = true; // ou logique réelle
        $note = (object)[ 'title' => 'Important meeting', 'content' => 'Don\'t forget the meeting at 2 PM with the design team. a supp later' ];



        $completedTasks_labels = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
        $completedTasks_values = [5, 12, 8, 15, 10, 6, 9];

        $members_labels = ['Alice', 'Bob', 'Charlie', 'Diana'];
        $members_values = [25, 18, 30, 12];




        // Dernier post de l'équipe
        //$hasPost = true;
        $post = Post::with('user')
            ->whereHas('team', function ($query) use ($user) {
                $query->where('leader_id', $user->id);
            })
            ->latest()
            ->first(); // ou le dernier post de l'équipe

        $hasPost = $post !== null;

        return view('leader.dashboard', compact(
            'projectsCount',
            'tasksInProgress',
            'membersCount',
            'notificationsCount',
            'recentProjects',
            'hasNote', 'note',
            'completedTasks_labels', 'completedTasks_values',
            'members_labels', 'members_values',
            'hasPost', 'post'
        ));
    }

    public function notifications()
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(15);
        return view('leader.notifications', compact('notifications'));
    }

    public function notes()
    {
        return view('leader.notes');
    }

    public function profile()
    {
        return view('leader.profile', ['user' => Auth::user()]);
        //$user = Auth::user();
        //return view('leader.profile', compact('user'));
    }

    public function folders()
    {
        // Bonus : gestion de dossiers/fichiers
        return view('leader.folders');
    }


}
