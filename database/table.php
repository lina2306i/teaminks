        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['todo', 'in_progress', 'completed'])->default('todo');
            // start_at en datetime
            $table->dateTime('start_at')->nullable();
            $table->dateTime('due_date')->nullable();
            // Difficulty :: À quel point la tâche est techniquement complexe ou demande d’effort
            $table->enum('difficulty', ['easy', 'medium', 'hard','challenging'])->default('medium');
            // Points 1..6 .: Valeur en points (souvent pour gamification ou estimation agile — Story Points)
            $table->integer('points')->default(5);
            //Priority 1..5 :: À quel point la tâche est importante / urgente pour l’équipe ou le leader
            $table->tinyInteger('priority')
                  ->default(3)
                   ->comment('1=Urgent, 2=High, 3=Normal, 4=Low, 5=Very Low');
            $table->boolean('pinned')->default(false);
            $table->timestamp('pinned_at')->nullable();
           $table->dateTime('reminder_at')->nullable();
            $table->text('notes')->nullable(); // Notes ou commentaires libres                      $table->integer('attachments_count')->default(0);
            $table->integer('comments_count')->default(0);
            $table->timestamps();
        });
------------
        Schema::create('subtasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->string('title');
            $table->enum('status',[
                'pending',
                'in_progress',
                'completed'
            ])->default('pending');
            // Ordre d’affichage
            $table->integer('order_pos')->default(0);
            // Assignation
            $table->foreignId('assigned_to')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            // Dates
            $table->timestamp('started_at')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->timestamp('completed_at')->nullable();
            // Points 1..5  :: Valeur en points (souvent pour gamification ou estimation agile — Story Points)
            $table->integer(column: 'points')->default(5);
            // Priorité (1 = haute, 5 = basse)
            $table->tinyInteger('priority')
                  ->default(3)
                  ->comment('1=Urgent, 2=High, 3=Normal, 4=Low, 5=Very Low');
            $table->text('notes')->nullable(); // Notes ou commentaires libres
            $table->unsignedInteger('estimated_hours')->nullable()->default(0); // Estimation temps
            $table->unsignedInteger('actual_hours')->nullable()->default(0); // Temps réel passé
            $table->timestamps();
        });
----------------------
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = [
        'project_id',
        'title',
        'description',
        'notes',
        'start_at',
        'due_date',
        'assigned_to',
        'status',
        'difficulty',
        'points',
        'priority',
        'pinned',
        'reminder_at',
        'notes',
        'attachments_count',
        'comments_count',
    ];
    protected $casts = [
        'due_date'  => 'date:d/m/Y H:i',         // affiche seulement la date
        'start_at'  => 'date:d/m/Y H:i',         // même format pour start_at
        'priority' => 'integer',
        'pinned' => 'boolean',
        'pinned_at' => 'datetime',
        'reminder_at' => 'datetime',
    ];
    // Relations
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    // RELATION with subtask
    public function subtasks(): HasMany
    {
        return $this->hasMany(Subtask::class)->orderBy('order_pos');
        // ou bien
        //return $this->hasMany(Subtask::class);

    }
    // Priority label
    public function getPriorityLabelAttribute()
    {
        return match($this->priority) {
            1 => 'Urgent',
            2 => 'High',
            3 => 'Normal',
            4 => 'Low',
            5 => 'Very Low',
            default => 'Normal'
        };
    }
    // Optionnel : scope pour récupérer les tâches épinglées en premier
    public function scopePinnedFirst($query)
    {
        return $query->orderByDesc('pinned')->orderByDesc('created_at');
    }
    //option; pas utiliser ::! Puis dans la vue : {{ $task->progress }}% -- barre de progression intelligente,
    public function getProgressAttribute()
    {
        if ($this->subtasks->count() > 0) {
            $completed = $this->subtasks->where('status', 'completed')->count();
            return round(($completed / $this->subtasks->count()) * 100);
        }
        return match($this->status) {
            'completed' => 100,
            'in_progress' => 50,
            default => 0,
        };
    }
}
-----------------------------------------
<?php
namespace App\Http\Controllers\Leader;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Events\LeaderNotification;
use App\Http\Resources\TaskResource;
use Carbon\Carbon;
class LeaderTaskController extends Controller
{
    /**
     * Display a listing of all tasks from the leader's projects.
     */
    public function index(Request $request)
    {
        $projectId = $request->query('projectId');
        $status = $request->query('status');
        // Récupère tous les projets du leader pour le filtre
        $projects = Auth::user()->projects()->get()
        // Requête de base sur les tâches
        $tasksQuery = Task::query()
            ->with('project', 'assignedTo' , 'subtasks')
            ->whereHas('project', function ($query) {
                $query->where('leader_id', Auth::id());
            });
        // Filtre par projet si demandé
        if ($projectId) {
            $tasksQuery->where('project_id', $projectId);
        }
        if ($status && in_array($status, ['todo', 'in_progress', 'completed'])) {
            $tasksQuery->where('status', $status);
        }
        // Tri + pagination directement sur la query
        $tasks = $tasksQuery
            ->with('project', 'assignedTo', 'subtasks')
            ->pinnedFirst() // ← épinglées en haut
            ->latest()
            ->paginate(6)
            ->appends(['projectId' => $projectId]); // garde le filtre dans les liens de pagination
        $hasProjects = $projects->count() > 0;
        $hasTasks = $tasks->count() > 0;
        return view('leader.tasks.index', [
            'tasks' => $tasks,
            'projects' => $projects,
            'hasProjects' => $projects->count() > 0,
            'hasTasks' => $tasks->count() > 0
        ]);
    }
    /**
     * Show the form for creating a new task.
     */
    public function create()
    {
        $projects = Auth::user()->projects;
        $teamMembers = Auth::user()
            ->teamsAsLeader
            ->pluck('members')
            ->flatten()
            ->unique('id');
        return view('leader.tasks.create', compact('projects', 'teamMembers'));
    }
    /**
     * Store a newly created task in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'       => 'required|in:todo,in_progress,completed',
            'start_at' => 'nullable|date',
            'due_date' => 'nullable|date|after:start_at',
            'assigned_to' => 'nullable|exists:users,id',   //Rendre assigned_to facultatif ← déjà nullable
            'difficulty' => 'required|in:easy,medium,hard,challenging',
            'points' => 'required|integer|min:1|max:6',
            'priority' => 'required|integer|max:5|min:3',
            'pinned'       => 'nullable|boolean',
            'reminder_at'  => 'nullable|date',
            'notes'        => 'nullable|string',
            'subtasks' => 'nullable|array',
            'subtasks.*.title' => 'required_with:subtasks|string|max:255',
            'subtasks.*.status' => 'required_with:subtasks|in:pending,in_progress,completed',
            'subtasks.*.assigned_to' => 'nullable|exists:users,id',   //Rendre assigned_to facultatif ← déjà nullable
            'subtasks.*.priority' => 'required_with:subtasks|integer|min:1|max:5',
            'subtasks.*.points' => 'required_with:subtasks|integer|min:1|max:5',
            'subtasks.*.due_date' => 'nullable|date',
        ]);
        $project = Project::where('leader_id', Auth::id())->findOrFail($validated['project_id']);

        $task = $project->tasks()->create($validated);

        if (!empty($validated['subtasks'])) {
            foreach ($validated['subtasks'] as $index => $sub) {
                $task->subtasks()->create(array_merge($sub, ['order_pos' => $index + 1]));
            }
        }
        return redirect()->route('leader.tasks.index')->with('success', 'Task created successfully!');
    }
    /**
     * Display the specified task.
     */
    public function show(Task $task)
    {
        $this->authorizeTask($task);
        $task->load(['subtasks','project','assignedTo']); // pour avoir user name, profile, position
        return view('leader.tasks.show', compact('task'));
    }
    /**
     * Show the form for editing the specified task.
     */
    public function edit(Task $task)
    {
        $this->authorizeTask($task);
        $projects = Auth::user()->projects;
        $teamMembers = Auth::user()->teamsAsLeader->pluck('members')->flatten()->unique('id');
        $task->load('subtasks.assignedTo');
        return view('leader.tasks.edit', compact('task', 'projects', 'teamMembers'));

    }
    /**
     * Update the specified task in storage.
     */
    public function update(Request $request, Task $task)
    {
        $this->authorizeTask($task);
        $validated = $request->validate([
            'project_id'  => 'required|exists:projects,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id', //Rendre assigned_to facultatif ← déjà nullable
            'status'       => 'required|in:todo,in_progress,completed',
            'start_at' => 'nullable|date',
            'due_date' => 'nullable|date|after:start_at',
            'user_id' => 'nullable|exists:users,id',
            'difficulty' => 'required|in:easy,medium,hard,challenging',
            'points' => 'required|integer|min:1|max:6',
            'priority' => 'required|integer|max:5|min:3',
            'pinned'       => 'nullable|boolean',
            'reminder_at'  => 'nullable|date',
            'notes'        => 'nullable|string',
            'subtasks' => 'nullable|array',
             'subtasks.*.title' => 'required_with:subtasks|string|max:255',
            'subtasks.*.status' => 'required_with:subtasks|in:pending,in_progress,completed',
            'subtasks.*.assigned_to' => 'nullable|exists:users,id', //Rendre assigned_to facultatif ← déjà nullable
            'subtasks.*.priority' => 'required_with:subtasks|integer|min:1|max:5',
            'subtasks.*.due_date' => 'nullable|date',
    ]);
        // Vérifie que le nouveau projet (si changé) appartient au leader
        if ($validated['project_id'] != $task->project_id) {
            Project::where('leader_id', Auth::id())->findOrFail($validated['project_id']);
        }
        $task->update($validated);
        // Gestion des subtasks
        $task->subtasks()->delete(); // Supprime les anciennes
        // Créer les nouvelles
        if (!empty($validated['subtasks'])) {
                  foreach ($validated['subtasks'] as $index => $sub) {
                $task->subtasks()->create(array_merge($sub, ['order_pos' => $index + 1]));
            }
        }
        return redirect()->route('leader.tasks.index', $task)
            ->with('success', 'Task updated successfully!');
    }
    /**
     * Remove the specified task from storage.
     */
    public function destroy(Task $task)
    {
        $this->authorizeTask($task);

        $task->delete();

        return redirect()->route('leader.tasks.index')
            ->with('success', 'Task deleted successfully.');
    }
    /**
     * Toggle pin status of a task
     */
    public function pin(Task $task)
    {
        $this->authorizeTask($task);

        $task->pinned = !$task->pinned;
        $task->pinned_at = $task->pinned ? now() : null;
        $task->save();

        return back()->with('success', $task->pinned ? 'Task pinned to top!' : 'Task unpinned.');
    }

    /**
     * Check if the task belongs to one of the authenticated leader's projects.
     */
    private function authorizeTask(Task $task): void
    {
        if ($task->project->leader_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }
    }
}
----------------------------------
