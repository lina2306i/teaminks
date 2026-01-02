<?php

namespace App\Http\Controllers\Leader;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use Carbon\Carbon;
class LeaderProjectController extends Controller
{
    /*
    $user = Auth::user();

    // Projets dont je suis le leader
    $user->ownedProjects;

    // Projets dont je suis membre (pas forcément leader)
    $user->projects;

    // Toutes les tâches qui me sont assignées
    $user->assignedTasks;

    */


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $user = Auth::user();
        // Tous les projets où l'utilisateur est impliqué
        $query = Auth::user()->projects()
                    ->with(['team.members'])
                    ->select('projects.*') // important !:: obligatoire pour ajouter des subqueries
                    ->addSelect([ // Ajoute les deux counts manuellement
                        'tasks_count' => Task::selectRaw('COUNT(*)')
                            ->whereColumn('project_id', 'projects.id'),

                        'completed_tasks_count' => Task::selectRaw('COUNT(*)')
                            ->whereColumn('project_id', 'projects.id')
                            ->where('status', 'completed'), // adapte si ton statut est 'done'
                    ])
                    ;

        // Recherche par nom
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        // Filtre par statut (dynamique)
        if ($request->filled('status')) {
            $now = now();
            match ($request->status) {
                'active' => $query
                    ->where(function ($q) use ($now) {
                    $q->whereNull('end_date')
                    ->orWhere('end_date', '>', $now);
                })
                ->having('tasks_count', '>', 0)
                ->havingRaw('completed_tasks_count < tasks_count'), // au moins une tâche non terminée
                // //})->whereRaw('(SELECT COUNT(*) FROM tasks WHERE tasks.project_id = projects.id AND tasks.status != "completed") > 0'), // au moins une tâche non terminée

                'completed' => $query->havingRaw('completed_tasks_count = tasks_count')
                                ->having('tasks_count', '>', 0), // évite les projets sans tâches
                                //->whereRaw('(SELECT COUNT(*) FROM tasks WHERE tasks.project_id = projects.id AND tasks.status = "completed") = (SELECT COUNT(*) FROM tasks WHERE tasks.project_id = projects.id)'), // toutes les tâches completed

                'overdue' => $query->where('end_date', '<', $now)
                                   ->havingRaw('completed_tasks_count < tasks_count'),
                                   //->whereRaw('(SELECT COUNT(*) FROM tasks WHERE tasks.project_id = projects.id AND tasks.status != "completed") > 0'),
                default => null,
            };
        }
        // Tri
        $sort = $request->get('sort', 'created_at'); // default
        $direction = $request->get('direction', 'desc');

        //match ($sort) {'name' =>
        switch ($sort) {
            case 'name':
                $query->orderBy('name', $direction);
            break;

            case 'progress':
            // Tri par progression calculée : Progression = completed / total (gère division par zéro)
                $query->orderByRaw("(completed_tasks_count / NULLIF(tasks_count, 0)) " . ($direction === 'desc' ? 'DESC' : 'ASC'));
              //$query->orderByRaw("(completed_tasks_count / NULLIF(tasks_count, 0)) " . ($direction === 'desc' ? 'DESC' : 'ASC') . " NULLS LAST");
            break;
                //$query->orderBy('progress', $direction === 'desc' ? 'desc' : 'asc'), // progress est un accessor

            case 'deadline':
                $query->orderBy('end_date', $direction === 'desc' ? 'desc' : 'asc');
            break;
            //    'deadline' => $query->orderBy('end_date', $direction === 'asc' ? 'asc' : 'desc'), // nulls last ?

            case 'created_at':
            default:
                $query->orderBy('created_at', $direction);
            break;
            //'created_at' => $query->orderBy('created_at', $direction),
            // default => $query->latest(),
        };

         // 1. Projets dont l'utilisateur est leader (via leader_id)
       // $ownedProjects = Project::where('leader_id', $user->id) ;
       /* $ownedProjects = Project::where(function ($query) use ($user) {
            // Projets où l'utilisateur est leader
            $query->where('leader_id', $user->id)
                // OU projets où il est membre via équipe
                ->orWhereHas('team', function ($q) use ($user) {
                    $q->whereHas('members', function ($q2) use ($user) {
                        $q2->where('user_id', $user->id)
                           ->where('status', 'accepted');
                    });
                });
        }) ;
        $projectsQuery = $ownedProjects
            // ->union($teamProjects)
            ->with(['team.members', 'leader' , 'tasks.subtasks'])           // relations utiles
            ->withCount('tasks')                 // nombre de tâches
            //->orderBy('created_at', 'desc')      // tri correct au niveau DB
           ;//->latest()->distinct() ; //->get() ;
        // Si tu veux éviter les doublons (au cas où un leader est aussi membre de sa propre team)
        // $projects = $projectsQuery->paginate(6); // Optional: pagination instead of get();
        */
        //$projects = $projectsQuery->paginate(6)->appends($request->query());
        $projects = $query->paginate(6)->withQueryString(); // garde les params dans les liens pagination

        return view('leader.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teams = auth()->user()->teamsAsLeader; // tes équipes où tu es leader
        return view('leader.projects.create', compact('teams'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'team_id' => 'nullable|exists:teams,id',
        ]);
        $validated['leader_id'] = Auth::id();
        $project = Project::create($validated);

        //oub1
        //Auth::user()->projects()->create($validated);
        //$project = Auth::user()->projects()->create($validated);

        return redirect()->route('leader.projects.index')->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified resource.
    */
    //public function show(string $id)
    public function show(Project $project)
    {
        // Autorisation : leader OU membre du projet (via team)
        // $this->authorizeProject($project);
        if ($project->leader_id !== Auth::id() && (! $project->team || ! $project->team->members->contains(Auth::id()))){
           // !$project->users->contains(Auth::id())) {
            abort(403);
        }

        // Charge tout ce qu’on affiche dans la vue show
        //$project->load('tasks.assignedTo');
        // Charge uniquement les relations valides //→ enlève 'members' et 'team.users' du load()
        $project->load([
            'tasks.assignedTo', // tâche + personne assignée
            'tasks.subtasks', // si tu veux voir les subtasks aussi
            //'members' ,// <-- nouveau // ← PROBLÈME ICI so we do //Supprime 'members' du load() et utilise directement la team
            'team.members',          // membres de l'équipe liée // ← c'est suffisant !
            //'team.users',
        ]);

        return view('leader.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
    */
    //public function edit(string $id)
    public function edit(Project $project)
    {
        $this->authorizeProject($project);
        /*if ($project->leader_id !== Auth::id()) {
            abort(403);
        }*/
        $teams = auth()->user()->teamsAsLeader;

        return view('leader.projects.edit', compact('project', 'teams'));
    }

    /**
     * Update the specified resource in storage.
     */
    //public function update(Request $request, string $id)
    public function update(Request $request, Project $project)
    {
        $this->authorizeProject($project);
        /*if ($project->leader_id !== Auth::id()) {
            abort(403);
        }*/
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'team_id' => 'nullable|exists:teams,id',
        ]);


        $project->update($validated);
        // redirction vers aussi index as i like
        return redirect()->route('leader.projects.show', $project)->with('success', 'Project updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    //public function destroy(string $id)
    public function destroy(Project $project)
    {
        $this->authorizeProject($project);
        /*if ($project->leader_id !== Auth::id()) {
            abort(403);
        }*/
        $project->delete();
        return back()->with('success', 'Projet deleted');
        // return redirect()->route('leader.projects.index')->with('success', 'Projet supprimé.');
    }

    // Calendrier visuel des deadlines (FullCalendar) ::  FullCalendar (via CDN, pas de build lourd)
    public function calendar()
    {
        // === PROJETS ===
        // On charge les projets du leader connecté avec les relations nécessaires
        $projects = auth()->user()
            ->ledProjects() // ou Project::where('leader_id', auth()->id())
            ->with('team')
            ->get()
            ->map(function ($project) {
                return [
                    'id'         => 'project-' . $project->id,
                    'title' => $project->name,
                    //'title' => $project->title . ' (' . $project->progress . '%)',
                    'start' => optional($project->start_date)->format('Y-m-d'),
                    'end'   => optional($project->end_date)?->addDay()->format('Y-m-d'),
                    'url'   => route('leader.projects.show', $project),
                    'color' => $project->is_overdue
                        ? '#f43649ff'
                        : ($project->progress == 100 ? '#87e09cff' : '#8fbdedff'),
                    //'team'  => $project->team?->name ?? 'No team',
                    //'progress' => $project->progress,
                    'backgroundColor' => $project->is_overdue ? '#e15362ff' : ($project->progress == 100 ? '#28a745' : '#0d6efd'),
                    'borderColor'     => $project->is_overdue ? '#dc3545' : ($project->progress == 100 ? '#28a745' : '#0d6efd'),
                    'textColor'       => '#ffffff',
                    'extendedProps' => [
                        'type'      => 'project',
                        'team'      => $project->team?->name ?? 'No team',
                        'progress'  => $project->progress,
                        'status'    => $project->is_overdue ? 'overdue' : ($project->progress == 100 ? 'completed' : 'in_progress'),
                    ]

                ];
            });
             // ->projects()->with('team')->get();

        //return vi ew('leader.projects.calendar', comp act('projects'));
        //maj
        $user = auth()->user();
        // === PROJETS ===
        // === TÂCHES avec deadline (seulement celles qui ont une due_date) ===
        $tasksEvents = Task::whereHas('project', function ($q) use ($user) {
                $q->where('leader_id', $user->id);
            })
            ->whereNotNull('due_date')
            ->with(['project', 'assignedTo'])
            ->get()
            ->map(function ($task) {
                $isOverdue = $task->due_date && Carbon::parse($task->due_date)->isPast() && $task->status !== 'completed';

                return [
                    'id'         => 'task-' . $task->id,
                    'title'      => '📌 ' . $task->title,
                    'start'      => $task->due_date,
                    'end'        => $task->due_date,
                    'url'        => route('leader.tasks.show', $task),
                    'backgroundColor' => $isOverdue ? '#e94b5bff' : ($task->status === 'completed' ? '#28a745' : '#ffc107'),
                    'borderColor'     => $isOverdue ? '#dc3545' : ($task->status === 'completed' ? '#28a745' : '#ffc107'),
                    'textColor'       => '#000',
                    'extendedProps' => [
                        'type'        => 'task',
                        'project'     => $task->project->name,
                        'assignee'    => $task->assignedTo?->name ?? 'Unassigned',
                        'status'      => $isOverdue ? 'overdue' : ($task->status === 'completed' ? 'completed' : 'in_progress'),
                        'priority'    => $task->difficulty ?? 'normal',
                    ]
                ];
            }
        );

        // Fusion des événements
        $events = $projects->merge(items: $tasksEvents)->toArray();
        //$events = $projectsEvents->merge($tasksEvents)->values()->toJson();

        return view('leader.projects.calendar', compact('events'));


    }

    // Helper to authorize project actions
    private function authorizeProject(Project $project)
    {
        if ($project->leader_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
    }



}
