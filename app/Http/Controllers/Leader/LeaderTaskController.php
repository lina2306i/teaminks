<?php

namespace App\Http\Controllers\Leader;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $projects = Auth::user()->projects()->get();

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
            ->paginate(10)
            ->appends(['projectId' => $projectId]); // garde le filtre dans les liens de pagination

        $hasProjects = $projects->count() > 0;
        $hasTasks = $tasks->count() > 0;

        return view('leader.tasks.index', [
            'tasks' => $tasks,
            'projects' => $projects,
            'hasProjects' => $projects->count() > 0,
            'hasTasks' => $tasks->count() > 0
        ]);

       // return view('leader.tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create()
    {
        //$projects = Auth::user()->projects()->with('team.members')->get();
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
            'start_at' => 'nullable|date',
            'due_date' => 'nullable|date|after:start_at',
            'assigned_to' => 'nullable|exists:users,id',   //Rendre assigned_to facultatif ← déjà nullable
            'difficulty' => 'required|in:easy,medium,hard,challenging',
            'points' => 'required|integer|min:1',

            'subtasks' => 'nullable|array',
            'subtasks.*.title' => 'required_with:subtasks|string|max:255',
            'subtasks.*.status' => 'required_with:subtasks|in:pending,in_progress,completed',
            'subtasks.*.assigned_to' => 'nullable|exists:users,id',   //Rendre assigned_to facultatif ← déjà nullable
            'subtasks.*.priority' => 'required_with:subtasks|integer|min:1|max:5',
            'subtasks.*.due_date' => 'nullable|date',
        ]);

        /*
        $project = Project::where('leader_id', Auth::id())->findOrFail($validated['project_id']);

        // Tu peux sauvegarder les subtasks séparément ou en JSON selon ton modèle
        $task=$project->tasks()->create($validated);

        if (!empty($validated['subtasks'])) {
            /*foreach ($validated['subtasks'] as $subtaskData) {
                $task->subtasks()->create([
                    'title'       => $subtaskData['title'] ?? null,
                    'status'      => $subtaskData['status'] ?? 'pending',
                    'assigned_to' => $subtaskData['assigned_to'] ?? null,
                    'priority'    => $subtaskData['priority'] ?? 3,
                    'due_date'    => $subtaskData['due_date'] ?? null,
                ]);
            }* /
            foreach ($validated['subtasks'] as $i => $title) {
                $task->subtasks()->create([
                    'title' => $title,
                    'order_pos' => $i+1,
                ]);
            }
        }

        */
         /*$task = Task::create($validated);

        if (!empty($validated['subtasks'])) {
            foreach ($validated['subtasks'] as $sub) {
                $task->subtasks()->create($sub);
            }
        }*/
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
        $task->load(['subtasks','project','assignedTo']);
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

       // $task->load(['subtasks', 'project', 'assignedTo']);
       // return view('leader.tasks.edit', compact('task', 'projects', 'teamMembers'));

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
            'status'      => 'required|in:todo,in_progress,completed',
            'start_at' => 'nullable|date',
            'due_date' => 'nullable|date|after:start_at',
            'user_id' => 'nullable|exists:users,id',
            'difficulty' => 'required|in:easy,medium,hard,challenging',
            'points' => 'required|integer|min:1',

            'subtasks' => 'nullable|array',
            //'subtasks.*' => 'string|max:255',
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
            /*foreach ($validated['subtasks'] as $subtaskData) {
                $task->subtasks()->create([
                    'title'       => $subtaskData['title'] ?? null,
                    'status'      => $subtaskData['status'] ?? 'pending',
                    'assigned_to' => $subtaskData['assigned_to'] ?? null,
                    'priority'    => $subtaskData['priority'] ?? 3,
                    'due_date'    => $subtaskData['due_date'] ?? null,
                ]);
            }*/
            /*foreach ($validated['subtasks'] as $i => $title) {
                $task->subtasks()->create([
                    'title' => $title,
                    'order_pos' => $i+1,
                ]);
            }*/
           /* foreach ($validated['subtasks'] as $sub) {
                $task->subtasks()->create($sub);
            }*/
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
