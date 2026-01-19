@extends('layouts.appW')
{{-- -resources/views/leader/team/show.blade.php – Gestion complète d'une équipe --}}
@section('contentW')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="display-5 fw-bold text-white">{{ $team->name }}</h1>
            <p class="text-gray-400">{{ $team->description ?? 'No description' }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('leader.team.edit', $team) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i> Edit Team
            </a>
            <a href="{{ route('leader.team.index') }}" class="btn btn-outline-light">
                ← Back to Teams
            </a>
           {{-- <!-- Kanban de l'équipe -->
            <a href="{{ route('leader.tasks.kanban', ['team' => $team->id]) }}" class="btn btn-outline-info">
                <i class="fas fa-columns me-2"></i> Kanban de l'équipe
            </a>

            <!-- Kanban Global -->
            <a href="{{ route('leader.tasks.kanban') }}" class="btn btn-primary">
                <i class="fas fa-globe me-2"></i> Kanban Global
            </a>
             Page 404 quand tu cliques sur "Kanban de l'équipe" ou "Kanban Global" dans team/show.blade.php
             --}}

        </div>

    </div>

    <!-- Invitation Code -->
    <div class="card bg-gray-800 text-white shadow mb-5">
        <div class="card-body d-flex  flex-column justify-content-between align-items-center gap-3">
            <div>
                <strong class="fw-semibold">Invitation Code:</strong>
                <code class="bg-gray-700 px-4 py-2 rounded ms-3 fs-4 fw-bold">{{ $team->invite_code }}</code>
                <p class="text-gray-400 small mt-2 mb-0">
                    Share this code with members so they can join your team.
                </p>
                <p>Or share this link:
                    <a href="{{ url('/join-team/' . $team->invite_code) }}" class="text-info">
                        {{ url('/join-team/' . $team->invite_code) }}
                    </a>
                </p>
            </div>
            <button class="btn btn-info btn-lg" onclick="navigator.clipboard.writeText('{{ $team->invite_code }}'); showToast('Code copied!')">
                <i class="fas fa-copy me-2"></i> Copy Code
            </button>
            <button class="btn btn-info btn-lg" onclick="copyToClipboard('{{ $team->invite_code }}')">
                <i class="fas fa-copy me-2"></i> Copy Code toast
            </button>
             <!-- Regenerate code -->
            @if($team->leader_id === auth()->id())
                <div class="text-center mt-4">
                    <form action="{{ route('leader.team.regenerate-code', $team) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-sync-alt me-2"></i> code invitation regenerated
                        </button>
                    </form>
                </div>
            @endif
        </div>
        <!-- Invitation par recherche (seulement pour leader/admin) -->
        @if($team->leader_id === auth()->id() || $team->admins->contains(auth()->user()))
            <div class="card-header p-3 bg-primary fw-bold">
                Invite a new  member Team
            </div>
            <div class="card-body">
                <form action="{{ route('leader.team.invite.search', $team) }}" method="POST">
                    @csrf
                    <div class="input-group">
                        <input type="text" name="search" class="form-control bg-gray-700 border-0 text-white"
                            placeholder="Rechercher un utilisateur..." list="users-list">
                        <datalist id="users-list">
                            @foreach($availableUsers as $user) <!-- passe $availableUsers depuis le controller -->
                                <option value="{{ $user->name }}  --{{ $user->status }} ({{ $user->email }})" data-id="{{ $user->id }}"></option>
                            @endforeach
                            @if(isset($availableUsers) && $availableUsers->count() > 0)
                                @foreach($availableUsers as $user)
                                    <option value="{{ $user->name }} -- {{ $user->status }}-- ({{ $user->email }})" data-id="{{ $user->id }}"></option>
                                @endforeach
                            @else
                                <option value="No user available for the moment"></option>
                            @endif
                        </datalist>
                        <button class="btn btn-primary" type="submit">Invited</button>
                    </div>
                </form>
            </div>
        @endif
    </div>

    <!-- Pending Requests & Current Members -->
    <div class="row g-5">
        <!-- Pending Requests -->
        <div class="col-lg-6">
            <div class="card bg-gray-800 shadow-lg">
                <div class="card-header bg-warning text-dark fw-bold">
                    Pending Requests ({{ $team->pendingMembers->count() }})
                </div>
                <div class="card-body">
                    @forelse($team->pendingMembers as $user)
                        <div class="d-flex justify-content-between align-items-center py-3 border-bottom border-gray-700">
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $user->profile ?? asset('images/user-default.jpg') }}"
                                        class="rounded-circle" style="width: 40px; height: 40px;">
                                <div>
                                    <strong>{{ $user->name }}</strong><br>
                                    <small class="text-gray-400">{{ $user->position ?? '' }}</small>
                                </div>
                            </div>
                            <div>
                                <form action="{{ route('leader.team.accept', ['team' => $team, 'user' => $user]) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-success btn-sm">Accept</button>
                                </form>
                                <form action="{{ route('leader.team.reject', ['team' => $team, 'user' => $user]) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Reject</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-400 text-center py-4">No pending requests</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Current Members -->
        <div class="col-lg-6">
            <div class="card bg-gray-800 shadow-lg">
                <div class="card-header bg-success text-white fw-bold">
                    Team Members ({{ $team->members->count() }}) ({{-- $team->users()->wherePivot('status/accepted', 'accepted'/true)->count() --}})
                </div>
                <div class="card-body">
                    @forelse($team->members as $member)
                        <div class="d-flex justify-content-between align-items-center py-3 border-bottom border-gray-700">
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $member->profile ?? asset('images/user-default.jpg') }}" class="rounded-circle" style="width: 45px; height: 45px;">
                                <div>
                                    <strong>{{ $member->name }}</strong><br><small class="text-gray-400">{{ $member->email }}</small><br>
                                    <small class="text-gray-400">{{ $member->position ?? '' }}</small>
                                </div>
                            </div>
                            @if(auth()->id() !== $member->id)
                                <form action="{{ route('leader.team.remove', ['team' => $team, 'user' => $member]) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm">Remove</button>
                                </form>
                            @endif
                        </div>

                        <div class="d-flex gap-2 align-items-center">
                            <!-- Badge rôle -->
                            @if($member->pivot->role === 'leader')
                                <span class="badge bg-danger">Leader</span>
                            @elseif($member->pivot->role === 'admin')
                                <span class="badge bg-primary">Admin</span>
                            @else
                                <span class="badge bg-secondary">Member</span>
                            @endif

                            <!-- Actions (seulement leader) -->
                            @if($team->leader_id === auth()->id() && $member->id !== auth()->id())
                                @if($member->pivot->role === 'admin')
                                    <form action="{{ route('leader.team.demote', [$team, $member]) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-warning">Demote</button>
                                    </form>
                                @else
                                    <form action="{{ route('leader.team.promote', [$team, $member]) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-warning">Promote Admin</button>
                                    </form>
                                @endif

                                <form action="{{ route('leader.team.remove', [$team, $member]) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm">Remove</button>
                                </form>
                            @endif
                        </div>

                    @empty
                        <p class="text-gray-400 text-center py-4">No members yet</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>



    <!-- Bonus : Simple Stats -->
    <div class="card bg-gray-800 shadow-lg mt-5">
        <div class="card-header bg-info text-white fw-bold">
            Team Statistics
        </div>
        <div class="card-body">
            <div class="row text-center">
                <div class="col-md-4">
                    <h4>{{ $team->projects->count() }}</h4>
                    <p class="text-gray-400">Projects</p>
                </div>
                <div class="col-md-4">
                    <h4>{{ $team->projects->sum(fn($p) => $p->tasks->count()) }}</h4>
                    <p class="text-gray-400">Total Tasks</p>
                </div>
                <div class="col-md-4">
                    <h4>{{ $team->projects->sum(fn($p) => $p->tasks->where('status', 'completed')->count()) }}</h4>
                    <p class="text-gray-400">Completed Tasks</p>
                </div>
            </div>
        </div>
    </div>

    <hr>
<!-- Bonus : Team Statistics – Version améliorée -->
<div class="card bg-gray-800 shadow-xl mt-5 border-0 rounded-xl overflow-hidden">
    <div class="card-header bg-gradient-to-r from-info to-cyan-600 text-white fw-bold d-flex justify-content-between align-items-center py-3">
        <div>
            <i class="fas fa-chart-line me-2"></i>
            Statistiques de l'équipe
        </div>
        <small class="opacity-75">
            Mise à jour : {{ now()->format('d/m/Y H:i') }}
        </small>
    </div>

    <div class="card-body p-4">
        <!-- Progression globale -->
        @php
            $totalProjects = $team->projects ;
            //->count();
            $totalTasks = $team->projects->sum(fn($p) => $p->tasks->count());
            $completedTasks = $team->projects->sum(fn($p) => $p->tasks->where('status', 'completed')->count());

            // Subtasks (suppose que chaque Task a une relation subtasks() avec champ 'completed')
            $totalSubtasks = $totalProjects->flatMap->tasks->sum(fn($t) => $t->subtasks->count());
            $completedSubtasks = $totalProjects->flatMap->tasks->sum(fn($t) => $t->subtasks->where('completed', true)->count());

            $progressGlobal = $totalTasks ? round(($completedTasks / $totalTasks) * 100) : 0;
            $progressSubtasks = $totalSubtasks ? round(($completedSubtasks / $totalSubtasks) * 100) : 0;
            $globalProgress = ($progressGlobal + $progressSubtasks) / 2; // moyenne pondérée simple
            // Tâches en retard (exemple)
            $overdueTasks = $totalProjects->flatMap->tasks
                ->filter(fn($t) => $t->end_date && $t->end_date->isPast() && $t->status !== 'completed')
                ->count();

            $overdueSubtasks = $totalProjects->flatMap->tasks->flatMap->subtasks
                ->filter(fn($s) => $s->due_date && $s->due_date->isPast() && !$s->completed)
                ->count();

            $overdueProjects = $team->projects->filter(fn($p) => $p->end_date && $p->end_date->isPast() && $p->progress < 100)->count();
        @endphp

        <div class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0 text-info">Progression globale de l'équipe</h6>
                <span class="badge bg-info fs-6">{{ $globalProgress }}%</span>
            </div>
            <div class="progress bg-gray-700 rounded-pill" style="height: 14px;">
                <div class="progress-bar bg-gradient-to-r from-green-500 to-cyan-500"
                     role="progressbar"
                     style="width: {{ $globalProgress }}%"
                     aria-valuenow="{{ $globalProgress }}"
                     aria-valuemin="0"
                     aria-valuemax="100">
                </div>
            </div>
        </div>

        <!-- Cartes statistiques -->
        <div class="row g-4 text-center">
            <!-- Projets -->
            <div class="col-md-3 col-6">
                <div class="card-stat bg-gray-700 rounded-3 p-4 hover-lift transition-all">
                    <i class="fas fa-folder-open fa-2x text-primary mb-3 d-block"></i>
                    <h4 class="fw-bold mb-1">{{ $totalProjects->count() }}</h4>
                    <p class="text-gray-400 small mb-0">Projets</p>
                </div>
            </div>
            {{--
            <!-- Tâches totales -->
            <div class="col-md-3 col-6">
                <div class="card-stat bg-gray-700 rounded-3 p-4 hover-lift transition-all">
                    <i class="fas fa-tasks fa-2x text-info mb-3 d-block"></i>
                    <h4 class="fw-bold mb-1">{{ $totalTasks }}</h4>
                    <p class="text-gray-400 small mb-0">Tâches totales</p>
                    <small class="badge bg-success mt-1">{{ $progressGlobal }}% fait</small>
                </div>
            </div>

            <!-- Subtasks -->
            <div class="col-md-3 col-6">
                <div class="stat-card bg-gray-700 rounded-3 p-4 transition hover-lift border border-gray-600">
                    <i class="fas fa-tasks fa-2x text-indigo-400 mb-3"></i>
                    <h4 class="fw-bold text-white mb-1">{{ $totalSubtasks }}</h4>
                    <p class="text-gray-400 small">Subtasks</p>
                    <small class="badge bg-purple-600 mt-1">{{ $progressSubtasks }}% fait</small>
                </div>
            </div>
            --}}

            <!-- Tâches totales -->
            <div class="col-md-3 col-6">
                <div class="card-stat bg-gray-700 rounded-3 p-4 hover-lift transition-all">
                    <i class="fas fa-tasks fa-2x text-info mb-3 d-block"></i>
                    <div>
                        <h4 class="fw-bold ">{{ $totalTasks }}</h4>
                        <p class="text-gray-400 small mb-0">
                            Tâches totales
                            <small class="badge bg-success mt-1">{{ $progressGlobal }}% fait</small>
                        </p>
                    </div>
                   <!-- Subtasks -->
                   <hr>
                   <div>
                        <h4 class="text-white mb-1">{{ $totalSubtasks }}</h4>
                        <p class="text-gray-400 small">Subtasks
                            <small class="badge bg-purple-600 mt-1">{{ $progressSubtasks }}% fait</small>
                        </p>
                   </div>

                </div>
            </div>

            <!-- Projets en retard -->
            <div class="col-md-3 col-6">
                <div class="card-stat bg-gray-700 rounded-3 p-4 hover-lift transition-all {{ $overdueProjects > 0 ? 'border border-danger animate-pulse' : '' }}">
                    <i class="fas fa-clock fa-2x text-danger mb-3 d-block"></i>
                    <h4 class="fw-bold mb-1">{{ $overdueProjects }}</h4>
                    <p class="text-gray-400 small mb-0">Projets en retard</p>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="card-stat bg-gray-700 rounded-3 p-4 hover-lift transition-all">
                    <i class="fas fa-user-check fa-2x text-success mb-3 d-block"></i>
                    <h4 class="fw-bold mb-1">{{ $team->members->count() }}</h4>
                    <p class="text-gray-400 small mb-0">Membres actifs</p>
                    <span class="badge bg-warning ms-2">{{ $team->pendingMembers->count() }} pending</span>
                    {{-- filtrer sur la Collection déjà chargée (moins performant)         <h4 class="fw-bold mb-1">{{ $team->members->where('pivot.status', 'accepted')->count() }}</h4>
                    --}}
                </div>
            </div>

            <!-- Tâches terminées -->
            <div class="col-md-3 col-6">
                <div class="card-stat bg-gray-700 rounded-3 p-4 hover-lift transition-all">
                    <i class="fas fa-check-circle fa-2x text-success mb-3 d-block"></i>
                    <h4 class="fw-bold mb-1">{{ $completedTasks }}</h4>
                    <p class="text-gray-400 small mb-0">Tâches terminées</p>
                </div>
            </div>
            <!-- sousTâches terminées -->
            <div class="col-md-3 col-6">
                <div class="card-stat bg-gray-700 rounded-3 p-4 hover-lift transition-all">
                    <i class="fas fa-check-circle fa-2x text-success mb-3 d-block"></i>
                    <h4 class="fw-bold mb-1">{{ $completedSubtasks }}</h4>
                    <p class="text-gray-400 small mb-0">Sous-tâches terminées</p>
                </div>
            </div>

            <!-- Retards -->
            <div class="col-md-3 col-6">
                <div class="stat-card bg-gray-700 rounded-3 p-4 transition hover-lift border {{ ($overdueTasks + $overdueSubtasks) > 0 ? 'border-danger animate-pulse' : 'border-gray-600' }}">
                    <i class="fas fa-clock-rotate-left fa-2x text-danger mb-3"></i>
                    <h4 class="fw-bold text-white mb-1">{{ $overdueTasks + $overdueSubtasks }}</h4>
                    <p class="text-gray-400 small">Éléments en retard</p>
                    @if($overdueTasks + $overdueSubtasks > 0)
                        <small class="text-danger fw-semibold">Action requise !</small>
                    @endif
                </div>
            </div>



        </div>
    </div>

    <!-- Petit footer avec dernière mise à jour -->
    <div class="card-footer bg-gray-900 text-center py-2 small text-gray-500 border-top-0">
        <i class="fas fa-sync-alt me-1"></i> Données actualisées en temps réel
    </div>
</div>

<!-- Styles supplémentaires pour hover et animation -->
@push('styles')
<style>
    .card-stat {
        transition: all 0.3s ease;
        border: 1px solid rgba(255,255,255,0.08);
    }
    .card-stat:hover {
        transform: translateY(-6px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.4);
        border-color: rgba(59,130,246,0.3);
    }
    .hover-lift:hover {
        transform: translateY(-4px);
    }
    .animate-pulse {
        animation: pulse 2.5s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.75; }
    }
</style>
@endpush

    <hr>
    <!-- Workload des membres + Tâches non assignées -->

    <!-- Workload par membre
         @ forelse($team->users()->wherePivot('accepted', true)->get() as $member)
            @ php
                // Tâches assignées à ce membre dans TOUS les projets de l'équipe
                $assignedTasks = Task::where('assigned_to', $member->id)
                    ->whereIn('project_id', $team->projects->pluck('id'))
                    ->get();

                $total = $assignedTasks->count();
                $completed = $assignedTasks->where('status', 'completed')->count();
                $pending = $total - $completed;
                $overdue = $assignedTasks->where('end_date', '<', now())
                    ->where('status', '!=', 'completed')
                    ->count();

                $progress = $total ? round(($completed / $total) * 100) : 0;
            @ endphp
    -->
    <div class="card bg-gray-800 text-white shadow-lg mt-5">
        <div class="card-header bg-info fw-bold">Workload des membres</div>
        <div class="card-body">
            <div class="row g-4">
                @forelse($team->members as $member) <!-- utilise la relation members() -->
                    @php
                        // Tâches assignées à ce membre dans TOUS les projets de l'équipe
                        // $assignedTasks = \App\Models\Task::where('assigned_to', $member->id)
                        $assignedTasks = $member->tasks()
                            ->whereIn('project_id', $team->projects->pluck('id'))
                            ->get();

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
                        <div class="card bg-gray-700 h-100 shadow-sm">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex align-items-center mb-3">
                                    <img src="{{ $member->profile ?? asset('images/user-default.jpg') }}"
                                        class="rounded-circle me-3" width="50" height="50" alt="{{ $member->name }}" >

                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ $member->name }}</h6>
                                        <small class="text-gray-400 d-block">{{ $member->email }}</small>
                                        @if($member->pivot->role === 'leader')
                                            <span class="badge bg-danger mt-1">Leader</span>
                                        @elseif($member->pivot->role === 'admin')
                                            <span class="badge bg-primary mt-1">Admin</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="progress bg-gray-600 mb-3" style="height: 10px;">
                                    <div class="progress-bar bg-success" style="width: {{ $progress }}%"></div>
                                </div>

                                <div class="small text-center mt-auto  ">
                                    <span class="badge bg-primary me-1">{{ $total }} tâches</span>
                                    <span class="badge bg-success me-1 ms-1">{{ $completed }} terminées</span>
                                    <span class="badge bg-warning me-1 ms-1">{{ $pending }} en cours</span>
                                    @if($overdue > 0)
                                        <span class="badge bg-danger ms-1">{{ $overdue }} en retard</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-400 py-4">Aucun membre pour calculer le workload.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Tâches non assignées dans l'équipe -->
    <div class="card bg-gray-900 text-white shadow mt-5 border border-warning">
        <div class="card-header bg-warning text-dark fw-bold">
            Tâches non assignées dans l'équipe
            ({{ $team->projects->flatMap->tasks->whereNull('assigned_to')->count() }})
    </div>
    <div class="card-body">
        @if($team->projects->flatMap->tasks->whereNull('assigned_to')->count() > 0)
            <ul class="list-unstyled">
                    @foreach($team->projects->flatMap->tasks->whereNull('assigned_to') as $task)
                        <li class="py-2 border-bottom border-warning">
                            <div>
                                <strong class="text-warning">{{ $task->title }}</strong>
                                <small class="d-block text-gray-400">
                                    Projet : {{ $task->project->name }}
                                </small>
                            </div>
                            <a href="{{ route('leader.tasks.edit', $task) }}" class="btn btn-sm btn-warning">
                                Assigner
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-center text-gray-400 py-3">Aucune tâche non assignée dans les projets de cette équipe.</p>
            @endif
        </div>
    </div>

    <hr>

    <!-- Workload des membres + Tâches non assignées -->
    <div class="card bg-gray-800 text-white shadow-lg mt-5 border-0 rounded-xl">
        <div class="card-header bg-info fw-bold d-flex justify-content-between align-items-center">
            <span>Workload & Tâches de l'équipe</span>
            <small>Total tâches : {{ $team->projects->flatMap->tasks->count() }}</small>
        </div>

        <div class="card-body">

            <!-- 1. Workload des membres (utilise $team->members déjà chargé) -->
            @php
                // Filtre les membres qui ont des tâches dans les projets de l'équipe
                $activeMembers = $team->members->filter(function ($member) use ($team) {
                    return $member->tasks()
                        ->whereIn('project_id', $team->projects->pluck('id'))
                        ->exists();
                });
            @endphp

            @if($activeMembers->count() > 0)
                <h5 class="fw-semibold mb-4 text-info">Charge de travail des membres</h5>
                <div class="row g-4 mb-5">
                    @foreach($activeMembers as $member)
                        @php
                            $assignedTasks = $member->tasks()
                                ->whereIn('project_id', $team->projects->pluck('id'))
                                ->get();
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
                            <div class="card bg-gray-700 h-100 border {{ $overdue > 0 ? 'border-danger animate-pulse' : 'border-gray-600' }}">
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex align-items-center mb-3">
                                        <img src="{{ $member->profile ?? asset('images/user-default.jpg') }}"
                                            class="rounded-circle me-3 shadow-sm" width="50" height="50" alt="{{ $member->name }}">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $member->name }}</h6>
                                            <small class="text-gray-400 d-block">{{ $member->email }}</small>
                                            @if($member->pivot->role === 'leader')
                                                <span class="badge bg-danger mt-1">Leader</span>
                                            @elseif($member->pivot->role === 'admin')
                                                <span class="badge bg-primary mt-1">Admin</span>
                                            @else
                                                <span class="badge bg-secondary mt-1">Membre</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Barre de progression -->
                                    <div class="progress bg-gray-600 mb-3" style="height: 12px; border-radius: 6px;">
                                        <div class="progress-bar bg-success" role="progressbar"
                                            style="width: {{ $progress }}%" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>

                                    <!-- Badges -->
                                    <div class="d-flex flex-wrap gap-2 justify-content-center small mt-auto">
                                        <span class="badge bg-primary px-3 py-2">{{ $total }} tâches</span>
                                        <span class="badge bg-success px-3 py-2">{{ $completed }} terminées</span>
                                        <span class="badge bg-warning px-3 py-2">{{ $pending }} en cours</span>
                                        @if($overdue > 0)
                                            <span class="badge bg-danger px-3 py-2 animate-pulse">{{ $overdue }} en retard</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info text-center py-4 mb-5">
                    <i class="fas fa-users-slash fa-2x mb-3 d-block text-info"></i>
                        Aucun membre n'a de tâches assignées dans les projets de cette équipe.
                </div>
            @endif

            <!-- 2. Tâches non assignées dans les projets de l'équipe -->
            @php
                $unassignedTasks = $team->projects
                    ->flatMap(fn($project) => $project->tasks->whereNull('assigned_to'))
                    ->sortByDesc('created_at');
            @endphp

            @if($unassignedTasks->count() > 0)
                <h5 class="fw-semibold mb-3 text-warning">Tâches non assignées dans l'équipe</h5>
                <div class="card bg-gray-900 border border-warning mb-5">
                    <div class="card-header bg-warning text-dark fw-bold">
                        {{ $unassignedTasks->count() }} tâche{{ $unassignedTasks->count() > 1 ? 's' : '' }} non assignée{{ $unassignedTasks->count() > 1 ? 's' : '' }}
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @foreach($unassignedTasks->take(5) as $task) {{-- Limite à 5 pour éviter surcharge --}}
                                <li class="list-group-item bg-transparent text-white border-bottom border-warning py-3">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                        <div class="flex-grow-1">
                                            <a href="{{ route('leader.tasks.show', $task) }}"
                                            class="text-warning fw-bold d-block mb-1">
                                                {{ $task->title }}
                                            </a>
                                            <small class="text-gray-400 d-block">
                                                Projet : {{ $task->project->name }}
                                            </small>
                                            <small class="text-gray-500 d-block">
                                                Créée le {{ $task->created_at->format('d/m/Y') }}
                                            </small>
                                        </div>
                                        <a href="{{ route('leader.tasks.edit', $task) }}"
                                        class="btn btn-sm btn-warning">
                                            <i class="fas fa-user-plus me-1"></i> Assigner
                                        </a>
                                    </div>
                                </li>
                            @endforeach
                            @if($unassignedTasks->count() > 5)
                                <li class="list-group-item text-center bg-gray-900 py-3">
                                    <small class="text-muted">
                                        +{{ $unassignedTasks->count() - 5 }} de plus...
                                    </small>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            @else
                <div class="alert alert-success text-center py-4">
                    <i class="fas fa-check-circle fa-2x mb-3 d-block text-success"></i>
                    Toutes les tâches des projets de cette équipe sont assignées.
                </div>
            @endif
        </div>
    </div>

    <hr>


    <!-- Workload des membres + Tâches non assignées + Subtasks -->
    <div class="card bg-gray-800 text-white shadow-lg mt-5 border-0 rounded-xl">
        <div class="card-header bg-info fw-bold d-flex justify-content-between align-items-center">
            <span>Workload & Tâches de l'équipe</span>
            <small>Total tâches : {{ $team->projects->flatMap->tasks->count() }} | Subtasks : {{ $team->projects->flatMap(fn($p) => $p->tasks->flatMap->subtasks)->count() }}</small>
        </div>

        <div class="card-body p-4">

            <!-- 1. Workload des membres (tâches + subtasks) -->
            @php
                use App\Models\Task;
                $activeMembers = $team->members->filter(function ($member) use ($team) {
                    return $member->tasks()
                        ->whereIn('project_id', $team->projects->pluck('id'))
                        ->orWhereHas('subtasks', function($q) use ($team) {
                            $q->whereIn('task_id', Task::whereIn('project_id', $team->projects->pluck('id'))->pluck('id'));
                        })
                        ->exists();
                });
            @endphp

            @if($activeMembers->count() > 0)
                <h5 class="fw-semibold mb-4 text-info">Charge de travail des membres</h5>
                <div class="row g-4 mb-5">
                    @foreach($activeMembers as $member)
                        @php
                            // Tâches principales assignées dans les projets de l'équipe
                            $mainTasks = $member->tasks()
                                ->whereIn('project_id', $team->projects->pluck('id'))
                                ->get();

                            // Subtasks assignées (si tu as un champ assigned_to sur subtasks, sinon on considère les subtasks de ses tâches)
                            $subtasks = $member->subtasks() // suppose relation hasMany subtasks sur User
                                ->whereIn('task_id', $mainTasks->pluck('id'))
                                ->get();

                            $totalMain = $mainTasks->count();
                            $completedMain = $mainTasks->where('status', 'completed')->count();
                            $totalSub = $subtasks->count();
                            $completedSub = $subtasks->where('completed', true)->count(); // suppose champ 'completed' sur subtasks

                            $total = $totalMain + $totalSub;
                            $completed = $completedMain + $completedSub;
                            $pending = $total - $completed;
                            $overdueMain = $mainTasks->where('end_date', '<', now())->where('status', '!=', 'completed')->count();
                            $progress = $total ? round(($completed / $total) * 100) : 0;
                        @endphp

                        <div class="col-md-6 col-lg-4">
                            <div class="card bg-gray-700 h-100 border {{ $overdueMain > 0 ? 'border-danger animate-pulse' : 'border-gray-600' }}">
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex align-items-center mb-3">
                                        <img src="{{ $member->profile ?? asset('images/user-default.jpg') }}"
                                            class="rounded-circle me-3 shadow-sm" width="50" height="50" alt="{{ $member->name }}">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $member->name }}</h6>
                                            <small class="text-gray-400 d-block">{{ $member->email }}</small>
                                            @if($member->pivot->role === 'leader')
                                                <span class="badge bg-danger mt-1">Leader</span>
                                            @elseif($member->pivot->role === 'admin')
                                                <span class="badge bg-primary mt-1">Admin</span>
                                            @else
                                                <span class="badge bg-secondary mt-1">Membre</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Barre de progression globale -->
                                    <div class="progress bg-gray-600 mb-3" style="height: 12px; border-radius: 6px;">
                                        <div class="progress-bar bg-success" role="progressbar"
                                            style="width: {{ $progress }}%" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>

                                    <!-- Badges détaillés -->
                                    <div class="d-flex flex-wrap gap-2 justify-content-center small mt-auto">
                                        <span class="badge bg-primary px-3 py-2">{{ $totalMain }} tâches</span>
                                        <span class="badge bg-info px-3 py-2">{{ $totalSub }} subtasks</span>
                                        <span class="badge bg-success px-3 py-2">{{ $completed }} terminées</span>
                                        <span class="badge bg-warning px-3 py-2">{{ $pending }} en cours</span>
                                        @if($overdueMain > 0)
                                            <span class="badge bg-danger px-3 py-2 animate-pulse">{{ $overdueMain }} en retard</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info text-center py-4 mb-5">
                    <i class="fas fa-users-slash fa-2x mb-3 d-block text-info"></i>
                    Aucun membre n'a de tâches ou subtasks assignées dans cette équipe.
                </div>
            @endif

            <!-- 2. Tâches principales non assignées -->
            @php
                $unassignedMainTasks = $team->projects
                    ->flatMap(fn($p) => $p->tasks->whereNull('assigned_to'))
                    ->sortByDesc('created_at');
            @endphp

            @if($unassignedMainTasks->count() > 0)
                <h5 class="fw-semibold mb-3 text-warning">Tâches principales non assignées</h5>
                <div class="card bg-gray-900 border border-warning mb-5">
                    <div class="card-header bg-warning text-dark fw-bold">
                        {{ $unassignedMainTasks->count() }} tâche{{ $unassignedMainTasks->count() > 1 ? 's' : '' }} non assignée{{ $unassignedMainTasks->count() > 1 ? 's' : '' }}
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @foreach($unassignedMainTasks->take(8) as $task)
                                <li class="list-group-item bg-transparent text-white border-bottom border-warning py-3">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                        <div class="flex-grow-1">
                                            <a href="{{ route('leader.tasks.show', $task) }}"
                                            class="text-warning fw-bold d-block mb-1">
                                                {{ $task->title }}
                                            </a>
                                            <small class="text-gray-400 d-block">
                                                Projet : {{ $task->project->name }}
                                            </small>
                                        </div>
                                        <a href="{{ route('leader.tasks.edit', $task) }}"
                                        class="btn btn-sm btn-warning">
                                            <i class="fas fa-user-plus me-1"></i> Assigner
                                        </a>
                                    </div>
                                </li>
                            @endforeach

                            @if($unassignedMainTasks->count() > 8)
                                <li class="list-group-item text-center bg-gray-900 py-3">
                                    <small class="text-muted">+{{ $unassignedMainTasks->count() - 8 }} autres...</small>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            @else
                <div class="alert alert-success text-center py-4">
                    <i class="fas fa-check-circle fa-2x mb-3 d-block text-success"></i>
                    Toutes les tâches des projets de cette équipe sont assignées.
                </div>
            @endif

            <!-- 3. Subtasks non assignées ou orphelines -->
            @php
                $unassignedSubtasks = $team->projects
                    ->flatMap(fn($p) => $p->tasks->flatMap(function($task) {
                        return $task->subtasks->whereNull('assigned_to'); // si subtasks a un champ assigned_to
                    }))
                    ->sortByDesc('created_at');
            @endphp

            @if($unassignedSubtasks->count() > 0)
                <h5 class="fw-semibold mb-3 text-orange">Subtasks non assignées / orphelines</h5>
                <div class="card bg-gray-900 border border-orange mb-5">
                    <div class="card-header bg-orange text-dark fw-bold">
                        {{ $unassignedSubtasks->count() }} subtask{{ $unassignedSubtasks->count() > 1 ? 's' : '' }} non assignée{{ $unassignedSubtasks->count() > 1 ? 's' : '' }}
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @foreach($unassignedSubtasks->take(8) as $subtask)
                                <li class="list-group-item bg-transparent text-white border-bottom border-orange py-3">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                        <div class="flex-grow-1">
                                            <strong class="text-orange">{{ $subtask->title }}</strong>
                                            <small class="text-gray-400 d-block">
                                                Tâche parent : {{ $subtask->task->title }} ({{ $subtask->task->project->name }})
                                            </small>
                                        </div>
                                        <a href="{{ route('leader.tasks.edit', $subtask->task) }}"
                                        class="btn btn-sm btn-orange">
                                            <i class="fas fa-user-plus me-1"></i> Assigner
                                        </a>
                                    </div>
                                </li>
                            @endforeach
                            @if($unassignedSubtasks->count() > 8)
                                <li class="list-group-item text-center bg-gray-400 py-3">
                                    <small class="text-muted">+{{ $unassignedSubtasks->count() - 8 }} autres...</small>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            @else
                <div class="alert alert-success text-center py-4">
                    <i class="fas fa-check-circle fa-2x mb-3 d-block text-success"></i>
                    Toutes les soustâches des projets de cette équipe sont assignées.
                </div>
            @endif

        </div>
    </div>





</div>
<!-- Animation pulse -->
@push('styles')
<style>
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    .bg-orange { background-color: #f97316 !important; }
    .border-orange { border-color: #f97316 !important; }
    .text-orange { color: #f97316 !important; }
    .btn-orange { background-color: #f97316; border-color: #f97316; }
    .btn-orange:hover { background-color: #ea580c; border-color: #ea580c; }
</style>
@endpush
<!-- Animation pulse pour les tâches en retard @ push('styles')
<style>
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    @ keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
</style>
@ endpush -->


@endsection

@push('scripts')
    <script>
        function showToast(message) {
            alert(message); // ou utilise un toast plus joli si tu en as un
        }
    </script>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                // Toast simple (tu peux utiliser vue-toastification si tu veux)
                const toast = document.createElement('div');
                toast.innerText = 'Code copié !';
                toast.style.position = 'fixed';
                toast.style.bottom = '20px';
                toast.style.right = '20px';
                toast.style.padding = '12px 20px';
                toast.style.background = '#28a745';
                toast.style.color = 'white';
                toast.style.borderRadius = '6px';
                toast.style.zIndex = '9999';
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 2500);
            });
        }
    </script>
@endpush
