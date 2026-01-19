<?php

namespace App\Http\Controllers\Leader;

//use App\Http\Controllers\Controller;
use Illuminate\Routing\Controller;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;           // ← AJOUTE CETTE LIGNE
use Spatie\Activitylog\Models\Activity;


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
        // Derniers projets
        //$recentProjects = $user->projects()->latest()->take(3)->get();

        //Indicateur de projets en retard (overdue) – 5 min
        $overdueProjects = $user->projects()
            ->whereNotNull('end_date')
            ->where('end_date', '<', now())
            ->whereHas('tasks', fn($q) => $q->where('status', '!=', 'completed'))
            ->count();

            // Charge globale de toutes les équipes de l'utilisateur connecté
        $allTeams = auth()->user()->teamsAsLeader // équipes où leader
                ->merge(auth()->user()->teams ?? collect()) // + équipes où membre
                ->unique('id'); // évite doublons
                //->teams ; // toutes les équipes où il est membre ou leader
        $globalTeamLoad = 0;
        $teamCount = $allTeams->count();
        if ($teamCount > 0) {
            $totalAssignedTasks = 0;
            $totalPendingTasks  = 0;
            $totalOverdueTasks  = 0;

            foreach ($allTeams as $team) {
                $teamTasks = Task::whereIn('project_id', $team->projects->pluck('id'))
                    ->where('assigned_to', '!=', null) // tâches assignées
                    ->get();

                $totalAssignedTasks += $teamTasks->count();
                $totalPendingTasks  += $teamTasks->where('status', '!=', 'completed')->count();
                $totalOverdueTasks  += $teamTasks->where('due_date', '<', now())
                                                ->where('status', '!=', 'completed')
                                                ->count();
            }

            // Formule de charge globale (ajustable)
            $globalTeamLoad = $totalAssignedTasks > 0
                ? round(50 + ($totalPendingTasks * 25) + ($totalOverdueTasks * 35))
                : 0;

            $globalTeamLoad = min(max($globalTeamLoad, 0), 150); // cap à 150%
        }
        $teamWorkload = $globalTeamLoad . '%';
        $teamWorkloadColor = $globalTeamLoad < 70 ? 'success' : ($globalTeamLoad < 100 ? 'warning' : 'danger');
        $teamWorkloadDetail = $teamCount > 0
            ? "$teamCount équipe" . ($teamCount > 1 ? 's' : '')
            : 'Aucune équipe';
        //Tâches urgentes / en retard assignées à moi – 10 min
        $urgentTasks = Task::where('assigned_to', $user->id)
            ->where('status', '!=', 'completed')
            ->whereNotNull('due_date')
            ->where('due_date', '<', now())
            ->count();
        // Graphique tâches terminées (7 derniers jours)
        $completedTasks = Task::where('status', 'completed')
            //->where('completed_at', '>=', now()->subDays(7))
           ->where('updated_at', '>=', now()->subDays(60))
            // ->selectRaw('DATE(completed_at) as date, COUNT(*) as count')
           ->selectRaw('DATE(updated_at) as date, COUNT(*) as count')
           ->groupBy('date')
            ->orderBy('date')
            ->get();
        // Progression globale de tous mes projets – 10 min
        $totalTasks = Task::whereIn('project_id', $user->projects->pluck('id'))->count();
        $totalCompletedTasks = Task::whereIn('project_id', $user->projects->pluck('id'))
            ->where('status', 'completed')->count();
        $globalProgress = $totalTasks ? round(($totalCompletedTasks / $totalTasks) * 100) : 0;
        //Dernières activités / feed rapide – 15 min
       //$recentActivities = Activity::where('user_id', $user->id)
        $recentActivities = Activity::with('causer')
        //$recentActivities = Activity::whereIn('project_id', $user->projects->pluck('id'))
            ->latest()
            ->take(8)
            ->get();
        // Burndown : pour le projet principal ou global (ex: total points restants sur 7 jours)
        // $burndownDays = 7;
        $burndownDays = (int) request('period', 7); //7 , 14, 30,...
        $burndownLabels = collect(range($burndownDays, 0))->map(function ($day) {
            return now()->subDays($day)->format('d/m');
        });
            // collect(range(0, $burndownDays))->map(fn($day) => now()->subDays($day)->format('d/m'));
        //$burndownIdeal = [100, 85, 70, 55, 40, 25, 10, 0]; // ligne idéale (ex: 100 points à brûler en 7j)
        // Ligne idéale : linéaire de total points à 0
        $totalIdealPoints = 100; // ou calculé dynamiquement si tu veux
        $burndownIdeal = collect(range(0, $burndownDays))->map(function ($day) use ($totalIdealPoints, $burndownDays) {
            return round($totalIdealPoints * (1 - ($day / $burndownDays)), 1);
        })->toArray();
        // Points réels restants (calculé par jour passé)
        $burndownRemaining = []; // points restants par jour
        //for ($day = 0; $day <= $burndownDays; $day++) {
        for ($day = $burndownDays; $day >= 0; $day--) { // du plus ancien au plus récent
            $date = now()->subDays($day);
            $remaining = Task::whereIn('project_id', $user->projects->pluck('id'))
                ->where('status', '!=', 'completed')
                ->where('due_date', '>', $date) // tâches qui ne sont pas encore dues
                ->sum('points') ?? 0; // suppose que tu as une colonne 'points' dans tasks;
            $burndownRemaining[] = (float) $remaining;
        }
        // Transformer en collection pour utiliser last() dans la vue
        // $burndownRemaining = array_reverse($burndownRemaining); // pour ordre chronologique
        $burndownRemaining = collect($burndownRemaining);
        //->reverse();
        //$burndownRemaining = collect(array_reverse($burndownRemaining)); // ← devient une Collection
        // Progression finale
        $lastRemaining = $burndownRemaining->last() ?? 0;
        $progress = $totalIdealPoints > 0 ? round(100 - ($lastRemaining / $totalIdealPoints) * 100) : 0;



        $completedTasks_labels = $completedTasks->pluck('date')->map(fn($d) => Carbon::parse($d)->format('d/m'));
        $completedTasks_values = $completedTasks->pluck('count');

        // Membres les plus actifs
        $activeMembers = Task::whereIn('project_id', $user->projects->pluck('id'))
            ->where('status', 'completed')
            ->select('assigned_to', DB::raw('COUNT(*) as count'))
            ->groupBy('assigned_to')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        $members_labels = $activeMembers->map(fn($m) => User::find($m->assigned_to)?->name ?? 'Inconnu');
        $members_values = $activeMembers->pluck('count');


        // Dernier post
        $post = Post::whereIn('team_id', $user->teams->pluck('id'))
            ->latest()
            ->first();
        // Dernier post de l'équipe
        //$hasPost = true;
        $post = Post::with('user')
            ->whereHas('team', function ($query) use ($user) {
                $query->where('leader_id', $user->id);
            })
            ->latest()
            ->first(); // ou le dernier post de l'équipe
        $hasPost = $post !== null;


        //Exemple de note du jour (à remplacer par ta logique réelle plus tard)
        $hasNote = true; // ou logique réelle
        $note = (object)[ 'title' => 'Important meeting', 'content' => 'Don\'t forget the meeting at 2 PM with the design team. a supp later' ];

        // Workload simulation (remplace par vrai calcul)
        // $allProjectMembers 2. Tous les membres assignés à au moins une tâche dans les projets du leader
        // $teamMembers = $user->currentTeam ? $user->currentTeam->users->wherePivot('status', 'accepted')->get() : collect();
        $teamMembers = User::whereIn('id',
            Task::whereIn('project_id', $user->projects->pluck('id'))
            ->whereNotNull('assigned_to')
            ->pluck('assigned_to')
            ->unique())  ->get();
        // Tous les membres assignés à au moins une tâche dans TES projets (leader ou membre)
        $projectMembers = User::whereIn('id',
            Task::whereIn('project_id', $user->projects->pluck('id'))
                ->whereNotNull('assigned_to')
                ->pluck('assigned_to')
                ->unique()
        )->get();

        // 1. Membres de l'équipe actuelle (si le leader en a une)
        //$currentTeam = $user->currentTeam; // ou $user->teams()->first()
        $currentTeamMemberss = $user->currentTeam ? $user->currentTeam
            ->users()->wherePivot('status', 'accepted')->get()
            : collect();

        $currentTeam = $user->teamsAsLeader()->first()
            ?? $user->teams()->first();

        $currentTeamMembers = $currentTeam
            ? $currentTeam->members()->get() // utilise la méthode members()
            //->users()->wherePivot('status', 'accepted')->get()
            : collect();

        /* v0
        $completedTasks_labels = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
        $completedTasks_values = [5, 12, 8, 15, 10, 6, 9];

        $members_labels = ['Alice', 'Bob', 'Charlie', 'Diana'];
        $members_values = [25, 18, 30, 12];
        //end charts data exple */

        return view('leader.dashboard', compact(
            'projectsCount', 'user',
            'tasksInProgress', 'totalCompletedTasks', 'totalTasks',
            'membersCount',
            'notificationsCount',
            'teamWorkload', 'teamWorkloadColor','allTeams','teamWorkloadDetail',
            'recentProjects',  'overdueProjects',  'urgentTasks',  'globalProgress', 'recentActivities','projectMembers','currentTeamMembers','currentTeam',
            'burndownLabels', 'burndownIdeal', 'burndownRemaining','progress', 'burndownDays','lastRemaining',
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

        $note = Note::where('user_id', auth()->id())
                    ->whereDate('created_at', today())
                    ->first();

        return view('leader.note', compact('note'));

        //return view('leader.notes');
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


    public function calendarIndex()
    {
        $user = Auth::user();

        // === ÉVÉNEMENTS PROJETS ===
        $projectEvents = $user->ledProjects() // relation à créer dans User model si pas déjà
            ->with('team')
            ->get()
            ->map(function ($project) {
                $isOverdue = $project->end_date && Carbon::parse($project->end_date)->isPast();

                return [
                    'id'              => 'project-' . $project->id,
                    'title'           => $project->name . ' (' . $project->progress . '%)',
                    'start'           => $project->start_date?->format('Y-m-d'),
                    'end'             => $project->end_date?->addDay()->format('Y-m-d'), // FullCalendar exclut l'end
                    'url'             => route('leader.projects.show', $project),
                    'backgroundColor' => $isOverdue ? '#dc3545' : ($project->progress == 100 ? '#28a745' : '#0d6efd'),
                    'borderColor'     => $isOverdue ? '#dc3545' : ($project->progress == 100 ? '#28a745' : '#0d6efd'),
                    'textColor'       => '#ffffff',
                    'extendedProps'   => [
                        'type'     => 'project',
                        'team'     => $project->team?->name ?? 'Aucune équipe',
                        'progress' => $project->progress . '%',
                        'status'   => $isOverdue ? 'En retard' : ($project->progress == 100 ? 'Terminé' : 'En cours'),
                    ]
                ];
            });

        // === ÉVÉNEMENTS TÂCHES (avec due_date ou end_date) ===
        $taskEvents = Task::whereHas('project', fn($q) => $q->where('leader_id', $user->id))
            ->whereNotNull('due_date')
            ->orWhereNotNull('end_date')
            ->with(['project', 'assignedTo'])
            ->get()
            ->map(function ($task) {
                $deadline = $task->due_date ?? $task->end_date;
                $isOverdue = $deadline && Carbon::parse($deadline)->isPast() && $task->status !== 'completed';

                return [
                    'id'              => 'task-' . $task->id,
                    'title'           => '📌 ' . $task->title . ' (' . ucfirst($task->status) . ')',
                    'start'           => $task->start_at?->format('Y-m-d') ?? $deadline?->format('Y-m-d'),
                    'end'             => $deadline?->addDay()->format('Y-m-d'),
                    'url'             => route('leader.tasks.show', $task),
                    'backgroundColor' => $isOverdue ? '#e74c3c' : ($task->status === 'completed' ? '#27ae60' : '#f39c12'),
                    'borderColor'     => $isOverdue ? '#c0392b' : ($task->status === 'completed' ? '#27ae60' : '#e67e22'),
                    'textColor'       => '#ffffff',
                    'extendedProps'   => [
                        'type'      => 'task',
                        'project'   => $task->project->name,
                        'assignee'  => $task->assignedTo?->name ?? 'Non assigné',
                        'priority'  => ucfirst($task->difficulty ?? 'normal'),
                        'status'    => $isOverdue ? 'En retard' : ucfirst($task->status),
                    ]
                ];
            });

        // Fusion des événements
        $events = $projectEvents->merge($taskEvents)->values()->toJson();

        return view('leader.calendar.calendar-inter', compact('events'));
    }

}
