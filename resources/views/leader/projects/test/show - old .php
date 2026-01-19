<!-- ... header ... -->

 <!-- ... header ... -->
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div class="flex-grow-1">
                 <!-- Titre du projet -->
                <h3 class="display-5 fw-bold text-gradient">{{ $project->name }}</h3>
                <!-- Infos dates + équipe (seulement si au moins une info existe) -->
                @if($project->start_date || $project->end_date || $project->due_date)
                    <p class="text-gray-400 mb-0">
                       From : {{ $project->start_date?->format('d/m/Y') ?? '?' }}
                        To : {{ $project->end_date?->format('d/m/Y') ?? '?' }}
                        or To : {{ $project->due_date?->format('d/m/Y') ?? '?' }}
                    </p>
                @endif
            </div>
            <div>
                <a href="{{ route('leader.projects.edit', $project) }}" class="btn btn-outline-primary me-2">Modifier</a>
                <form action="{{ route('leader.projects.destroy', $project) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Supprimer définitivement ce projet  ?')">Supprimer</button>
                </form>
            </div>
        </div>






{{-- Vue show.blade.php – Version finale propre
(Tu peux garder ta version, mais voici une version plus propre et sans doublons)
--}}
<div class="row g-4">
    <div class="col-lg-8">
        <!-- Description -->
        <div class="card bg-gray-800 text-white mb-4">
            <div class="card-body">
                <h4>Description</h4>
                <p>{{ $project->description ?? 'Aucune description.' }}</p>
            </div>
        </div>

        <!-- Tâches -->
        <div class="card bg-gray-800 text-white">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Tâches ({{ $project->tasks->count() }})</h4>
                <a href="{{ route('leader.tasks.create', $project) }}" class="btn btn-sm btn-contact">
                    + Nouvelle tâche
                </a>
            </div>
            <div class="card-body">
                @forelse($project->tasks as $task)
                    <div class="border-bottom border-gray-700 py-3">
                        <a href="{{ route('leader.tasks.show', $task) }}" class="text-white fw-bold">
                            {{ $task->title }}
                        </a>
                        <div class="small text-gray-400 mt-1">
                            @if($task->assignedTo) Assignée à {{ $task->assignedTo->name }} • @endif
                            {{ ucfirst($task->status) }} • {{ $task->subtasks->count() }} subtâche{{ $task->subtasks->count() > 1 ? 's' : '' }}
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-400 py-4">Aucune tâche.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Infos -->
        <div class="card bg-gray-800 text-white mb-4">
            <div class="card-header">Informations</div>
            <div class="card-body small">
                <p><strong>Leader :</strong> {{ $project->leader->name }}</p>
                <p><strong>Équipe :</strong> {{ $project->team?->name ?? 'Aucune' }}</p>
                <p><strong>Début :</strong> {{ $project->start_date?->format('d/m/Y') ?? '-' }}</p>
                <p><strong>Fin prévue :</strong>
                    <span class="{{ $project->is_overdue ? 'text-danger' : '' }}">
                        {{ $project->end_date?->format('d/m/Y') ?? 'Non définie' }}
                    </span>
                </p>
                <p><strong>Progression :</strong> {{ $project->progress }}%</p>
            </div>
        </div>

        <!-- Membres -->
        <div class="card bg-gray-800 text-white">
            <div class="card-header">Membres ({{ $project->users->count() }})</div>
            <div class="card-body">
                @foreach($project->users as $member)
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar avatar-sm bg-primary rounded-circle me-3 text-white">
                            {{ Str::upper(Str::substr($member->name, 0, 1)) }}
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold small">{{ $member->name }}</div>
                            <small class="text-gray-400">{{ $member->email }}</small>
                        </div>
                        @if($member->id === $project->leader_id)
                            <span class="badge bg-warning small">Leader</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>




 <!-- Workload in the project -->
            <div class="card bg-gray-800 text-white mt-4">
                <div class="card-header bg-info fw-bold">
                    Project workload
                </div>
                <div class="card-body">
                    {{-- @forelse($project->member_workload as $wl)
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-gray-700">
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $wl['user']->profile ?? asset('images/default-avatar.png') }}" class="rounded-circle" width="36" height="36">
                                <strong>{{ $wl['user']->name }}</strong>
                            </div>
                            <div>
                                <span class="badge bg-{{ $wl['overdue'] > 0 ? 'danger' : 'secondary' }}">
                                    {{ $wl['total'] }} tâches
                                </span>
                                @if($wl['overdue'] > 0)
                                    <span class="badge bg-danger ms-2">{{ $wl['overdue'] }} retard</span>
                                @endif
                            </div>
                        </div> --}}
                    <div class="row g-4">
                        @forelse($project->users as $member)
                            @php
                                $assignedTasks = $project->tasks()->where('assigned_to', $member->id)->get();
                                $total = $assignedTasks->count();
                                $completed = $assignedTasks->where('status', 'completed')->count();
                                $overdue = $assignedTasks->where('end_date', '<', now())
                                    ->where('status', '!=', 'completed')->count();
                                $progress = $total ? round(($completed / $total) * 100) : 0;
                            @endphp

                            <div class="col-md-6">
                                <div class="card bg-gray-700 h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <img src="{{ $member->profile ?? asset('images/default-avatar.png') }}"
                                                class="rounded-circle me-3" width="50" height="50">
                                            <div>
                                                <h6 class="mb-0">{{ $member->name }}</h6>
                                                <small class="text-gray-400">{{ $member->email }}</small>
                                            </div>
                                        </div>

                                        <div class="progress bg-gray-600 mb-3" style="height: 10px;">
                                            <div class="progress-bar bg-success" style="width: {{ $progress }}%"></div>
                                        </div>

                                        <div class="small text-center">
                                            <span class="badge bg-primary">{{ $total }} tâches</span>
                                            <span class="badge bg-success ms-1">{{ $completed }} terminées</span>
                                            @if($overdue > 0)
                                                <span class="badge bg-danger ms-1">{{ $overdue }} en retard</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-gray-400 py-3">No tasks assigned to this project!.</p>
                        @endforelse
                    </div>
                </div>
                <!-- Tâches non assignées -->
                @if($project->tasks->whereNull('assigned_to')->count() > 0)
                    <div class="card bg-gray-900 text-white mt-4 border border-warning">
                        <div class="card-header bg-warning text-dark fw-bold">
                            Tâches non assignées ({{ $project->tasks->whereNull('assigned_to')->count() }})
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                @foreach($project->tasks->whereNull('assigned_to') as $task)
                                    <li class="list-group-item bg-transparent text-white border-bottom border-warning">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <a href="{{ route('leader.tasks.show', $task) }}" class="text-warning fw-bold">
                                                    {{ $task->title }}
                                                </a>
                                                <small class="d-block text-gray-400">
                                                    {{ Str::limit($task->description ?? '', 80) }}
                                                </small>
                                            </div>
                                            <a href="{{ route('leader.tasks.edit', $task) }}" class="btn btn-sm btn-warning">
                                                Assigner maintenant
                                            </a>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
            <hr>
            <!-- Workload par membre + Tâches non assignées -->
            <div class="card bg-gray-800 text-white mt-4 shadow-lg">
                <div class="card-header bg-info fw-bold d-flex justify-content-between align-items-center">
                    <span>Workload du projet</span>
                    <small class="text-white-50">Total tâches : {{ $project->tasks->count() }}</small>
                </div>

                <div class="card-body">
                    <!-- 1. Workload des membres (si membres existent) -->
                    @if($project->users->count() > 0)
                        <div class="row g-4 mb-5">
                            @foreach($project->users as $member)
                                @php
                                    $assignedTasks = $project->tasks()->where('assigned_to', $member->id)->get();
                                    $total = $assignedTasks->count();
                                    $completed = $assignedTasks->where('status', 'completed')->count();
                                    $pending = $total - $completed;
                                    $overdue = $assignedTasks
                                        ->where('end_date', '<', now())
                                        ->where('status', '!=', 'completed')
                                        ->count();
                                    $progress = $total ? round(($completed / $total) * 100) : 0;
                                @endphp

                                <div class="col-md-6 col-lg-4">
                                    <div class="card bg-gray-700 h-100 border {{ $overdue > 0 ? 'border-danger' : '' }}">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-3">
                                                <img src="{{ $member->profile ?? asset('images/default-avatar.png') }}"
                                                    class="rounded-circle me-3 shadow-sm" width="50" height="50">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">{{ $member->name }}</h6>
                                                    <small class="text-gray-400 d-block">{{ $member->email }}</small>
                                                </div>
                                            </div>

                                            <!-- Barre de progression -->
                                            <div class="progress bg-gray-600 mb-3" style="height: 12px; border-radius: 6px;">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    style="width: {{ $progress }}%" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>

                                            <!-- Badges -->
                                            <div class="d-flex flex-wrap gap-2 justify-content-center small">
                                                <span class="badge bg-primary px-3 py-2">{{ $total }} tâches</span>
                                                <span class="badge bg-success px-3 py-2">{{ $completed }} terminées</span>
                                                <span class="badge bg-warning px-3 py-2">{{ $pending }} en cours</span>
                                                @if($overdue > 0)
                                                    <span class="badge bg-danger px-3 py-2">{{ $overdue }} en retard</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info text-center py-4 mb-4">
                            Aucun membre assigné à ce projet pour le moment.
                        </div>
                    @endif

                    <!-- 2. Tâches non assignées (toujours visibles) -->
                    @if($project->tasks->whereNull('assigned_to')->count() > 0)
                        <div class="card bg-gray-900 border border-warning mt-4">
                            <div class="card-header bg-warning text-dark fw-bold">
                                Tâches non assignées ({{ $project->tasks->whereNull('assigned_to')->count() }})
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    @foreach($project->tasks->whereNull('assigned_to') as $task)
                                        <li class="list-group-item bg-transparent text-white border-bottom border-warning py-3">
                                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                                <div class="flex-grow-1">
                                                    <a href="{{ route('leader.tasks.show', $task) }}"
                                                    class="text-warning fw-bold d-block mb-1">
                                                        {{ $task->title }}
                                                    </a>
                                                    <small class="text-gray-400 d-block">
                                                        {{ Str::limit($task->description ?? 'Pas de description', 90) }}
                                                    </small>
                                                    <small class="text-gray-500">
                                                        Créée le {{ $task->created_at->format('d/m/Y') }}
                                                    </small>
                                                </div>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('leader.tasks.edit', $task) }}"
                                                    class="btn btn-sm btn-warning">
                                                        <i class="fas fa-user-plus me-1"></i> Assigner
                                                    </a>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @elseif($project->tasks->count() > 0)
                        <div class="alert alert-success text-center py-4 mt-4">
                            Toutes les tâches de ce projet sont assignées.
                        </div>
                        <div class="card-header bg-primary  text-dark fw-bold">
                                Tâches assignées ({{ $project->tasks->where('assigned_to')->count() }})
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                @foreach($project->tasks->where('assigned_to') as $task)
                                    <li class="list-group-item bg-transparent text-white border-bottom border-primary py-3">
                                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                            <div class="flex-grow-1">
                                                <a href="{{ route('leader.tasks.show', $task) }}"
                                                class="text-warning fw-bold d-block mb-1">
                                                    {{ $task->title }}
                                                </a>
                                                <small class="text-gray-400 d-block">
                                                    {{ Str::limit($task->description ?? 'Pas de description', 90) }}
                                                </small>
                                                <small class="text-gray-500">
                                                    Créée le {{ $task->created_at->format('d/m/Y') }}
                                                </small>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('leader.tasks.edit', $task) }}"
                                                class="btn btn-sm btn-warning">
                                                    <i class="fas fa-user-plus me-1"></i> Assigner Edit
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                    @else
                        <div class="alert alert-secondary text-center py-5 mt-4">
                            <i class="fas fa-tasks fa-3x text-gray-500 mb-3 d-block"></i>
                            Aucune tâche dans ce projet pour le moment.
                            <br>
                            <a href="{{ route('leader.tasks.create', ['project' => $project->id]) }}"
                            class="btn btn-sm btn-primary mt-3">
                                <i class="fas fa-plus me-1"></i> Créer la première tâche
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            <hr>
