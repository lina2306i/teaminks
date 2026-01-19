<?php

namespace App\Http\Controllers\Leader;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Project;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Notification;
use App\Events\LeaderNotification;
use App\Http\Resources\TaskResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\TaskAttachment;
use RahulHaque\Filepond\Facades\Filepond;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;


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

        // Filtre par projet si demandé v0
        if ($projectId) {
            $tasksQuery->where('project_id', $projectId);
        }
        if ($status && in_array($status, ['todo', 'in_progress', 'completed'])) {
            $tasksQuery->where('status', $status);
        }
        // Filtre par projet si demandé v1
      /*  if ($request->filled('projectId')) {
            $tasksQuery->where('project_id', $request->projectId);
        }

        if ($request->filled('status')) {
            $tasksQuery->where('status', $request->status);
        }*/

        if ($request->filled('search')) {
            $tasksQuery->where('title', 'like', '%' . $request->search . '%');
        }
        // Tri + pagination directement sur la query
        $tasks = $tasksQuery
            ->with('project', 'assignedTo', 'subtasks')
            ->pinnedFirst() // ← épinglées en haut
            ->latest()
            ->paginate(6)
            ->appends(['projectId' => $projectId]); // garde le filtre dans les liens de pagination

        $projects = Auth::user()->projects()->withCount('tasks')->get();
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
        // logs dans storage/logs/laravel.log.
        Log::info('hasFile attachments ?', ['has' => $request->hasFile('attachments')]);
        Log::info('files', ['files' => $request->file('attachments')]);
        // DEBUG VISIBILE
        if ($request->hasFile('attachments')) {
            \Log::info('Files reçus !', ['count' => count($request->file('attachments'))]);
        } else {
            \Log::warning('AUCUN fichier reçu dans la requête', [
                'all_files' => $request->allFiles(),
                'hasFile' => $request->hasFile('attachments'),
                'input_name' => $request->keys(), // pour voir si "attachments" existe
            ]);
        }
        Log::info('Requête reçue dans store', ['inputs' => $request->all(), 'files' => $request->file()]);

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
            'priority' => 'required|integer|max:5|min:1',
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

           'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,txt,zip,mp4|max:10240', // 10MB max

            //'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,txt,zip,mp4,application/octet-stream|max:10240',
            /*'attachments.*' => 'nullable|file|mimetypes:  image/jpeg,  image/png,  image/gif,  application/pdf,    application/msword,
                application/vnd.openxmlformats-officedocument.wordprocessingml.document,   application/vnd.ms-excel,
                application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,   text/plain,  application/zip,   video/mp4  |max:10240',
            */
        ]);
        $project = Project::where('leader_id', Auth::id())->findOrFail($validated['project_id']);
        $task = $project->tasks()->create($validated);
        // Subtasks ::
        if (!empty($validated['subtasks'])) {
            foreach ($validated['subtasks'] as $index => $sub) {
                $task->subtasks()->create(array_merge($sub, ['order_pos' => $index + 1]));
            }
            /*forreach ($validated['subtasks'] as $sub) {
                $task->subtasks()->create($sub);
            }*/
        }

        /*
                    // notify the user
                    $notification =  Notification::create([
                        'from' => Auth::id(),
                        'to' => $task->user_id,
                        'title' => 'New Task!',
                        'message' => 'New Task : ' . $task->title . ' assigned to your!',
                        'type' => 'info'
                    ]);
                    broadcast(new LeaderNotification($notification));
                    return response()->json(['message' => 'Task Created Successfully!', 'task' => TaskResource::make($task->load('subtasks', 'user'))], 200);
        */

        // === GESTION DES ATTACHMENTS v4 ===
        if ($request->hasFile('attachments')) {
           Log::info('files upload & save ', ['count' => count($request->file('attachments'))]);
            $attachmentsCount = 0;
            foreach ($request->file('attachments') as $file) {
                // Vérifie que le fichier est valide
                if ($attachmentsCount >= 5) {
                    break; // Maximum 5 fichiers
                }
                $path = $file->store('tasks/' . $task->id, 'public');
                Log::info('Fichier stocké', ['path' => $path]);

                $task->attachments()->create([
                    'filename'    => $file->getClientOriginalName(),
                    'path'        => $path,
                    'mime_type'   => $file->getMimeType(),
                    'size'        => $file->getSize(),
                    'uploaded_by' => Auth::id(),
                ]);
                $attachmentsCount++ ;
            }
            // met à jour le compteur (même si Eloquent le fait automatiquement via relation)
            $task->update(['attachments_count' => $attachmentsCount ]);
        } else {
            Log::warning('Any file upload with the store');
        }

        return redirect()->route('leader.tasks.index')
            ->with('success', "Task created successfully with " . $task->attachments()->count() . "attachment(s)!");
            // ->with('success', 'Task created successfully!');
    }


    /**
     * Display the specified task.
     */
    public function show(Task $task)
    {
        $this->authorizeTask($task);
        // Vérifier que la tâche appartient au projet du leader
      //Facul::  $project = Project::where('leader_id', Auth::id()) ->findOrFail($task->project_id);
       $task->load(['subtasks','project','assignedTo','attachments']);// pour avoir user name, profile, position
        return view('leader.tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified task Totally.
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
            'status'       => 'required|in:todo,in_progress,completed',
            'start_at' => 'nullable|date',
            'due_date' => 'nullable|date|after:start_at',
            'user_id' => 'nullable|exists:users,id',
            'difficulty' => 'required|in:easy,medium,hard,challenging',
            'points' => 'required|integer|min:1|max:6',
            'priority' => 'required|integer|max:5|min:1',
            'pinned'       => 'nullable|boolean',
            'reminder_at'  => 'nullable|date',
            'notes'        => 'nullable|string',

            'subtasks' => 'nullable|array',  //'subtasks.*' => 'string|max:255',
            'subtasks.*.title' => 'required_with:subtasks|string|max:255',
            'subtasks.*.status' => 'required_with:subtasks|in:pending,in_progress,completed',
            'subtasks.*.assigned_to' => 'nullable|exists:users,id', //Rendre assigned_to facultatif ← déjà nullable
            'subtasks.*.priority' => 'required_with:subtasks|integer|min:1|max:5',
            'subtasks.*.due_date' => 'nullable|date',

            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,txt,zip,mp4|max:10240', // 10MB max
            //'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,txt,zip,mp4,application/octet-stream|max:10240',
            /*'attachments.*' => 'nullable|file|mimetypes:   image/jpeg, image/png,  image/gif,   application/pdf,   application/msword,
                application/vnd.openxmlformats-officedocument.wordprocessingml.document,   application/vnd.ms-excel,
                application/vnd.openxmlform   video/mp4  |max:10240',
            */

            'delete_attachments' => 'nullable|array',
            'delete_attachments.*' => 'exists:task_attachments,id',
        ]);
        // Vérifie que le nouveau projet (si changé) appartient au leader
        if ($validated['project_id'] != $task->project_id) {
            Project::where('leader_id', Auth::id())->findOrFail($validated['project_id']);
        }

        // ✅ 1. SUPPRIMER LES FICHIERS MARQUÉS
        if ($request->has('delete_attachments') && is_array($request->delete_attachments)) {
            foreach ($request->delete_attachments as $attachmentId) {
                $attachment = TaskAttachment::where('id', $attachmentId)
                                           ->where('task_id', $task->id)
                                           ->first();

                if ($attachment) {
                    // Supprimer le fichier physique
                    if (Storage::disk('public')->exists($attachment->path)) {
                        Storage::disk('public')->delete($attachment->path);
                        Log::info('Fichier physique supprimé', ['path' => $attachment->path]);
                    }

                    // Supprimer l'enregistrement
                    $attachment->delete();
                    Log::info('Attachment supprimé', ['id' => $attachmentId]);
                }
            }
        }

        // ✅ 2. METTRE À JOUR LES DONNÉES DE LA TÂCHE
        /*  $taskData = $validated;
        unset($taskData['subtasks']);
        unset($taskData['attachments']);
        unset($taskData['delete_attachments']);

        $task->update($taskData);
        */
        //ou  bien
        $task->update($validated);
        // ✅ 3. GÉRER LES SUBTASKS
        // Gestion des subtasks
        $task->subtasks()->delete(); // Supprime les anciennes
        // Créer les nouvelles
        if (!empty($validated['subtasks'])) {
            foreach ($validated['subtasks'] as $index => $sub) {
                $task->subtasks()->create(array_merge($sub, ['order_pos' => $index + 1]));
            }

        }
        // ✅ 4. AJOUTER DE NOUVEAUX FICHIERS v1 .2
        if ($request->hasFile('attachments')) {
            $currentCount = $task->attachments()->count();
            $maxFiles = 5;
            $availableSlots = $maxFiles - $currentCount;

            Log::info('Upload de nouveaux fichiers', [
                'current_count' => $currentCount,
                'available_slots' => $availableSlots,
                'new_files' => count($request->file('attachments'))
            ]);

            if ($availableSlots > 0) {
                $uploadedCount = 0;

                foreach ($request->file('attachments') as $file) {
                    if ($uploadedCount >= $availableSlots) {
                        Log::warning('Limite atteinte', ['max' => $availableSlots]);
                        break;
                    }

                    if ($file->isValid()) {
                        $path = $file->store('tasks/' . $task->id, 'public');

                        $task->attachments()->create([
                            'filename' => $file->getClientOriginalName(),
                            'path' => $path,
                            'mime_type' => $file->getMimeType(),
                            'size' => $file->getSize(),
                            'uploaded_by' => Auth::id(),
                        ]);

                        $uploadedCount++;
                        Log::info('Nouveau fichier uploadé', [
                            'filename' => $file->getClientOriginalName(),
                            'path' => $path
                        ]);
                    }
                }

                Log::info('Upload terminé', ['uploaded' => $uploadedCount]);
            }
        }

        // ✅ 4.  Gestion des attachments v0
       /* if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('tasks/' . $task->id, 'public');

                $task->attachments()->create([
                    'filename'     => $file->getClientOriginalName(),
                    'path'         => $path,
                    'mime_type'    => $file->getMimeType(),
                    'size'         => $file->getSize(),
                    'uploaded_by'  => Auth::id(),
                ]);
            }
            /* $task->attachments = $request->input('attachments');
               $task->save();
             * /
            $task->update(['attachments_count' => $task->attachments()->count()]);
        } */
        // ✅ 5. METTRE À JOUR LE COMPTEUR
        $newCount = $task->attachments()->count();
        if (Schema::hasColumn('tasks', 'attachments_count')) {
            $task->update(['attachments_count' => $newCount]);
        }

        return redirect()->route('leader.tasks.show', $task)
                ->with('success', "Task updated successfully!Attachments: {$newCount}/5");
    }

    /**
     * Delete a specific attachment (Optionnel - pour Ajax ou redirection)
     */
    public function deleteAttachment(TaskAttachment $attachment)
    {
        // Vérifier que l'attachment appartient à une tâche du leader
        $task = Task::findOrFail($attachment->task_id);
        $project = Project::where('leader_id', Auth::id())
                         ->findOrFail($task->project_id);

        // Supprimer le fichier physique
        if (Storage::disk('public')->exists($attachment->path)) {
            Storage::disk('public')->delete($attachment->path);
        }

        // Supprimer l'enregistrement
        $attachment->delete();

        // Mettre à jour le compteur
        if (Schema::hasColumn('tasks', 'attachments_count')) {
            $task->update(['attachments_count' => $task->attachments()->count()]);
        }

        // Réponse pour Ajax
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'file deleted successesfuly',
                'remaining_count' => $task->attachments()->count()
            ]);
        }

        return redirect()->back()->with('success', 'File deleted withc success');
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(Task $task)
    {
        $this->authorizeTask($task);
        // Supprimer tous les fichiers attachés
        foreach ($task->attachments as $attachment) {
            if (Storage::disk('public')->exists($attachment->path)) {
                Storage::disk('public')->delete($attachment->path);
            }
            $attachment->delete();
        }

        // Supprimer les subtasks
        $task->subtasks()->delete();

        // Supprimer la tâche
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

    // fct for Ajax status update :: in Kanban Board pour les tâches (effet "waouh" garanti)
    public function kanbanv0(Project $project = null)
    {
        $tasks = Task::with(['assignedTo', 'project'])
            ->when($project, fn($q) => $q->where('project_id', $project->id))
            ->get()
           // ->groupBy('status');
            ->groupBy(function ($task) {
                return $task->assigned_to ? $task->status : 'unassigned';
            });

        return view('leader.tasks.kanban', compact('tasks', 'project'));
    }
    public function kanban(Request $request, Project $project = null)
    {
        $user = Auth::user();

        $query = Task::with(['assignedTo', 'project']) ;
        /*
            ->where(function ($q) use ($user, $project) {
                // Si on est dans un projet spécifique
                if ($project) {
                    $q->where('project_id', $project->id);
                }
                // Sinon : toutes les tâches des projets où l'utilisateur est impliqué
                else {
                    $q->whereIn('project_id', $user->projects()->pluck('id'))
                    ->orWhereIn('project_id', $user->ownedProjects()->pluck('id'));
                }
            });*/

        // Cas 1 : Projet spécifique passé en paramètre d'URL
        if ($project) {
            $query->where('project_id', $project->id);
        }
        // Cas 2 : Équipe passée en query string (?team=14)
        elseif (request()->filled('team')) {
            $team = Team::findOrFail(request('team'));

            // Vérifie que l'utilisateur est leader ou membre de l'équipe
            if ($team->leader_id !== $user->id && !$team->users->contains($user->id)) {
                abort(403, 'Vous n\'êtes pas membre de cette équipe.');
            }

            // Toutes les tâches des projets de cette équipe
            $query->whereIn('project_id', $team->projects->pluck('id'));
        }
        // Cas 3 : Global (toutes les tâches de l'utilisateur)
        else {
            $query->whereIn('project_id', $user->projects->pluck('id'))
                ->orWhereIn('project_id', $user->ownedProjects->pluck('id'));
        }
        /* Si team est passé en query string (optionnel)
        if (request()->has('team')) {
            $team = Team::findOrFail(request('team'));
            $query->whereIn('project_id', $team->projects->pluck('id'));
        }*/

        //$tasks = $query->get()->groupBy('status');
        $allTasks = $query->get();

        // On groupe par status réel
        $grouped = $allTasks->groupBy('status');

        // Colonne spéciale Overdue : tâches non terminées ET date passée
        /*$tasks['overdue'] = $query->get()
            ->where('status', '!=', 'completed')
            ->whereNotNull('end_date')
            ->where('end_date', '<', now());*/
        // On crée les colonnes virtuelles /calculées
         $tasks = [

            'unassigned'  => $grouped->get('todo', collect())->whereNull('assigned_to'),
            'todo'        => $grouped->get('todo', collect())->whereNotNull('assigned_to'),
            'in_progress' => $grouped->get('in_progress', collect()),
            'overdue'     => $allTasks->whereIn('status', ['todo', 'in_progress'])
                                    ->whereNotNull('due_date')
                                    ->where('due_date', '<', now()),
            'completed'   => $grouped->get('completed', collect()),
        ];


        return view('leader.tasks.kanban', compact('tasks', 'project'));
    }


    public function updateStatus(Request $request, Task $task)
    {
        $request->validate(['status' => 'required|in:todo,in_progress,completed']);

        // Sécurité : vérifier que l'utilisateur peut modifier cette tâche
        if ($task->project->leader_id !== auth()->id() && !$task->project->users->contains(auth()->id())) {
            //abort(403);
            return response()->json(['success' => false, 'message' => 'Non autorisé'], 403);
        }

        $task->update(['status' => $request->status]);

        return response()->json(['success' => true, 'new_status' => $task->status]);
    }





    //Ajouter une route Ajax pour upload || route used tempUpload == uploadAttachment not needed
    public function tempUpload(Request $request, Task $task)
    {
        $this->authorizeTask($task);

        $request->validate([
            'file' => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,txt,zip,mp4|max:10240', // 10MB max
        ]);

        $file = $request->file('file');
        // Stockage temporaire par utilisateur
       //  $path = $file->store('temp-uploads/' . $task->id, 'public');
        $path = $file->store('temp-uploads/' . auth()->id(), 'public');

        // On garde le chemin en session pour le récupérer après création
        session()->push('temp_attachments.' . auth()->id(), $path);
        // $task->addAttachment($path);

        return response()->json([
            'success' => true,
            'name'    => $file->getClientOriginalName(),
            'path' => $path,
            'url' => Storage::url($path),
        ]);
    }
    /**
     * Afficher le formulaire de test
     */
    public function createTest()
    {
        // Récupérer des données pour les selects (si nécessaire)
        $projects = Auth::user()->projects;
        $teamMembers = Auth::user()
            ->teamsAsLeader
            ->pluck('members')
            ->flatten()
            ->unique('id');

        return view('leader.tasks.createTest', compact('projects', 'teamMembers'));
    }

    /**
     * Traiter le formulaire de test
     */
    public function storeTest(Request $request){
        Log::info('Requête reçue dans storeTest', ['inputs' => $request->all(), 'files' => $request->file()]);

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
            'priority' => 'required|integer|max:5|min:1',
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
            'attachments_count' => 0,
            'comments_count' => 0,
           'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,txt,zip,mp4|max:10240', // 10MB max
        ]);
        $project = Project::where('leader_id', Auth::id())->findOrFail($validated['project_id']);
        // Créer la tâche
        $task = $project->tasks()->create($validated);
        // Subtasks ::
        if (!empty($validated['subtasks'])) {
            foreach ($validated['subtasks'] as $index => $sub) {
                $task->subtasks()->create(array_merge($sub, ['order_pos' => $index + 1]));
            }
        }
        // Traiter les fichiers uploadés
        if ($request->hasFile('attachments')) {
            Log::info('Fichiers reçus Test', ['count' => count($request->file('attachments'))]);

            $attachmentsCount = 0;

            foreach ($request->file('attachments') as $file) {
                if ($attachmentsCount >= 5) {
                    break; // Maximum 5 fichiers
                }
                // Générer un nom unique pour le fichier
                $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                // Stocker le fichier
                $path = $file ->store('task_attachmentsTest/' . $task->id, 'public');
                // ->storeAs('task_attachmentsTest/' . $task->id, $filename, 'public');
                // Créer l'enregistrement dans la base de données
                TaskAttachment::create([
                    'task_id' => $task->id,
                    'filename' => $file->getClientOriginalName(),
                    'path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'uploaded_by' => auth()->id(),
                ]);
                $attachmentsCount++;
            }

            // Mettre à jour le compteur d'attachements
            $task->update(['attachments_count' => $attachmentsCount]);
        }

        return redirect()->route('leader.tasks.index', $task->id)
                ->with('success', 'Tâche créée avec succès!');
    }

}
