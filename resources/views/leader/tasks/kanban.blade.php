@extends('layouts.appW')
{{--<div class="container py-5">
  --}}
@section('contentW')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-5 fw-bold text-white mb-5">
            Kanban Board :
            @if(isset($project))
                – {{ $project->name }}
            @else
                – Toutes mes tâches
            @endif
        </h1>
        <!-- Bouton Nouvelle tâche : seulement si projet existe -->
        @if(isset($project) && $project)
            <a href="{{ route('leader.tasks.create', $project) }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Nouvelle tâche
            </a>
        @endif
    </div>

    <div class="row g-4" id="kanban-board">

        <!-- 2. To Do (todo + assigné) -->
        <div class="col-md-4">
            <div class="card bg-gray-800 text-white shadow">
                <div class="card-header bg-secondary fw-bold text-center">
                    To Do ({{ ($tasks['todo']?? collect())->count() }} )
                </div>
                <div class="card-body kanban-column min-vh-50 p-3" data-status="todo">
                    @forelse($tasks['todo'] as $task)
                        <div class="kanban-card bg-gray-700 p-3 mb-3 rounded shadow" data-task-id="{{ $task->id }}">
                            <h5 class="mb-2">{{ $task->title }}</h5>
                            <p class="small text-gray-400 mb-2">{{ Str::limit($task->description ?? 'no description', 60) }}</p>
                            @if($task->assignedTo)
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <img src="{{ $task->assignedTo->profile ?? asset('images/user-default.jpg') }}" class="rounded-circle" width="24" height="24">
                                    <small>{{ $task->assignedTo->name }}</small>
                                </div>
                            @endif
                            <div class="mt-2">
                                <span class="badge bg-{{ $task->difficulty == 'easy' ? 'success' : ($task->difficulty == 'medium' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($task->difficulty) }}
                                </span>
                                <span class="badge bg-info ms-2">{{ $task->points }} pts</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-5">Aucune tâche planifiée</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- 3. In Progress
              In Progress ({ { $project->tasks->where('status', 'in_progress')->count() }})
        -->
        <div class="col-md-4">
            <div class="card bg-gray-800 text-white shadow">
                <div class="card-header bg-warning fw-bold text-center text-dark">
                    In Progress ({{ ($tasks['in_progress']?? collect())->count() }})
                </div>
                <div class="card-body kanban-column min-vh-50 p-3" data-status="in_progress">
                    @forelse($tasks['in_progress'] as $task)
                        <div class="kanban-card bg-gray-700 p-3 mb-3 rounded shadow" data-task-id="{{ $task->id }}">
                            <h5 class="mb-2">{{ $task->title }}</h5>
                            <p class="small text-gray-400 mb-2">{{ Str::limit($task->description ?? 'no description', 60) }}</p>
                            @if($task->assignedTo)
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <img src="{{ $task->assignedTo->profile ?? asset('images/user-default.jpg') }}" class="rounded-circle" width="24" height="24">
                                    <small>{{ $task->assignedTo->name }}</small>
                                </div>
                            @endif
                            <div class="mt-2">
                                <span class="badge bg-{{ $task->difficulty == 'easy' ? 'success' : ($task->difficulty == 'medium' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($task->difficulty) }}
                                </span>
                                <span class="badge bg-info ms-2">{{ $task->points }} pts</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-5">Aucune tâche en cours</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- 4. Overdue (tâches en retard non terminées) -->
        <div class="col-md-4">
            <div class="card bg-gray-800 text-white">
                <div class="card-header bg-primary fw-bold"
                    >Overdue({{ ($tasks['overdue'] ?? collect())->count() }})
                </div>
                <div class="card-body kanban-column" data-status="overdue">
                    <!-- Même structure -->
                    @forelse($tasks['overdue'] ?? [] as $task)
                        <div class="kanban-card bg-gray-700 p-3 mb-3 rounded shadow border border-danger" data-task-id="{{ $task->id }}">
                            <h5 class="mb-2 text-danger">{{ $task->title }}</h5>
                            <p class="small text-gray-400 mb-2">{{ Str::limit($task->description ?? 'no description', 60) }}</p>
                            @if($task->assignedTo)
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $task->assignedTo->profile ?? asset('images/user-default.jpg') }}" class="rounded-circle" width="24" height="24">
                                    <small>{{ $task->assignedTo->name }}</small>
                                </div>
                            @endif
                            <div class="mt-2">
                                <span class="badge bg-{{ $task->difficulty == 'easy' ? 'success' : ($task->difficulty == 'medium' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($task->difficulty) }}
                                </span>
                                <span class="badge bg-info ms-2">{{ $task->points }} pts</span>
                            </div>
                            <small class="d-block text-danger mt-2">
                                Deadline dépassée : {{ $task->due_date->format('h:i d/m/Y') }}
                            </small>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-4">No overdue tasks</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- 5. Completed
             Completed ( { { $project->tasks->where('status', 'completed')->count() }})
        -->
        <div class="col-md-4">
            <div class="card bg-gray-800 text-white shadow">
                <div class="card-header bg-success fw-bold text-center">
                    Completed ({{ ($tasks['completed']?? collect())->count() }})
                </div>
                <div class="card-body kanban-column min-vh-50" data-status="completed">
                    @forelse($tasks['completed'] ?? [] as $task)
                        <div class="kanban-card bg-gray-700 p-3 mb-3 rounded shadow opacity-75" data-task-id="{{ $task->id }}">
                            <h5 class="mb-2 text-decoration-line-through">{{ $task->title }}</h5>
                            <p class="small text-gray-400 mb-2">{{ Str::limit($task->description ?? 'no description', 60) }}</p>
                            <!-- Contenu réduit pour les tâches terminées -->
                            <small class="text-gray-400">Terminée le {{ $task->updated_at->format('h:i  d/m/Y') }}</small>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-5">Aucune tâche terminée</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!--1.  Unassigned (nouvelles) -->
        <div class="col-md-3">
            <div class="card bg-gray-900 text-white border border-warning">
                <div class="card-header bg-warning fw-bold text-dark">
                    À assigner (Unassigned) ({{ ($tasks['unassigned'] ?? collect())->count() }})
                </div>
                <div class="card-body kanban-column" data-status="todo ,in_progress, completed">
                    @forelse($tasks['unassigned'] ?? [] as $task)
                        <div class="kanban-card bg-gray-700 p-3 mb-3 rounded shadow border border-warning" data-id="{{ $task->id }}">
                            <span class="badge bg-warning">À assigner</span>
                            <h6 class="text-warning mb-1">{{ $task->title }}</h6>
                            <p class="small text-gray-400 mb-2">{{ Str::limit($task->description ?? 'no description', 60) }}</p>
                            <small class="text-gray-400">Projet: {{ $task->project->name }}</small>
                            <br>
                            <small class="text-gray-500">Créée le {{ $task->created_at->format('d/m/Y') }}</small>
                            <div class=" mt-2">
                                <span class="badge bg-{{ $task->difficulty == 'easy' ? 'success' : ($task->difficulty == 'medium' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($task->difficulty) }}
                                </span>
                                <span class="badge bg-info ms-2">{{ $task->points }} pts</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-4">Aucune tâche non assignée</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
<!-- Sortable.js -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const columns = document.querySelectorAll('.kanban-column');

    columns.forEach(column => {
        new Sortable(column, {
            group: 'shared-tasks',          // même groupe = drag entre colonnes
            animation: 180,
            ghostClass: 'bg-primary-subtle',   // ← une seule classe
            chosenClass: 'bg-info-subtle',     // ← une seule classe
            dragClass: 'dragging',             // ← classe simple
            fallbackTolerance: 3,
            invertSwap: false,
            onEnd: function (evt) {
                const taskCard = evt.item;
                const taskId = taskCard.dataset.taskId;
                const newStatus = evt.to.dataset.status;

                if (!taskId || !newStatus) {
                    console.error('Manque taskId ou newStatus');
                    evt.from.appendChild(taskCard);
                    return;
                }

                // Bloquer les colonnes virtuelles
                if (['unassigned', 'overdue'].includes(newStatus)) {
                    alert('Cette colonne est virtuelle (non modifiable directement)');
                    evt.from.appendChild(taskCard);
                    return;
                }

                console.log(`Déplacement tâche ${taskId} vers ${newStatus}`);

                // fetch(`/tasks/${taskId}/update-status`, {
                fetch('{{ route("leader.tasks.update-status", ":id") }}'.replace(':id', taskId), {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ status: newStatus })
                })
                .then(async response => {
                    const data = await response.json();
                    if (!response.ok) {
                        console.error('Erreur serveur:', response.status, data);
                        throw new Error(data.message || 'Erreur ' + response.status);
                    }
                    return data;
                })
                .then(data => {
                    if (data.success) {
                        console.log('Succès ! Statut mis à jour');
                        // Feedback visuel
                        taskCard.classList.add('border-success', 'animate__animated', 'animate__pulse');
                        setTimeout(() => {
                            taskCard.classList.remove('border-success', 'animate__animated', 'animate__pulse');
                        }, 2000);
                    } else {
                        console.warn('Réponse non success:', data);
                        evt.from.appendChild(taskCard);
                    }
                })
                .catch(error => {
                    console.error('Erreur AJAX:', error);
                    evt.from.appendChild(taskCard);
                    alert('Échec du déplacement : ' + (error.message || 'Vérifiez votre connexion'));
                });
            }
        });
    });
});
</script>
@endpush


@endsection
