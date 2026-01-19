@extends('layouts.appW')

@section('contentW')
<section class="py-5 min-vh-100 bg-gradient-dark">
    <div class="container">

        <!-- Header avec bienvenue et date -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5">
            <div>
                <h1 class="display-5 fw-bold text-gradient mb-1">Dashboard Leader</h1>
                <p class="text-gray-400">
                     Wellcome,  <strong>{{ Auth::user()->name }}</strong> •
                    {{ now()->format('l d F Y • H:i') }}
                </p>
            </div>
            <div class="mt-3 mt-md-0">
                <a href="{{ route('leader.projects.create') }}" class="btn btn-contact btn-lg shadow">
                    <i class="fas fa-plus me-2"></i> New projet
                </a>
            </div>
        </div>
        <!-- Header personnalisé -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5 pb-4 border-bottom border-gray-700">
            <div class="d-flex align-items-center gap-3">
                <img src="{{ Auth::user()->profile ?? asset('images/user-default.jpg') }}"
                    class="rounded-circle shadow" width="64" height="64" alt="Avatar">
                <div>
                    <h1 class="display-5 fw-bold text-gradient mb-1">
                        Dashboard of " {{ Auth::user()->name }} "
                    </h1>
                    <p class="text-gray-400 mb-0">
                        {{ Auth::user()->role ?? 'Leader' }} •
                        Équipe actuelle : <strong>{{ Auth::user()->currentTeam?->name ?? 'Aucune' }}</strong> •
                        {{ now()->format('l d F Y • H:i') }}
                    </p>
                </div>
            </div>

            <!-- Quick actions contextuelles -->

            <!-- Actions rapides -->
            <div class="row g-4 mb-5 justify-content-center">
                <div class="col-md-4 col-lg-3">
                    <a href="{{ route('leader.projects.create') }}" class="btn btn-lg btn-contact text-white w-100  shadow">
                        <i class="fas fa-plus me-2"></i> Create a project
                    </a>
                </div>
                <div class="col-md-4 col-lg-3">
                    <a href="{{ route('leader.tasks.create') }}" class="btn btn-lg btn-primary text-white w-100 shadow">
                        <i class="fas fa-plus me-2"></i> Add a task
                    </a>
                </div>
                <div class="col-md-4 col-lg-3">
                    <a href="{{ route('leader.posts.create') }}" class="btn btn-lg btn-info text-white w-100shadow">
                        <i class="fas fa-pen me-2"></i> Publish a post
                    </a>
                </div>

                <div class="dropdown">
                    <button class="btn btn-outline-light btn-sm dropdown-toggle shadow" data-bs-toggle="dropdown">
                        <i class="fas fa-bolt me-1"></i> Plus d'actions
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end bg-gray-800 border-gray-700">
                        <li><a class="dropdown-item text-white" href="#"><i class="fas fa-bug me-2"></i> Signaler un bug</a></li>
                        <li><a class="dropdown-item text-white" href="#"><i class="fas fa-lightbulb me-2"></i> Idée produit</a></li>
                        <li><hr class="dropdown-divider bg-gray-700"></li>
                        <li><a class="dropdown-item text-info" href="#"><i class="fas fa-robot me-2"></i> AI : Résume mon sprint</a></li>
                        <li><a class="dropdown-item text-info" href="#"><i class="fas fa-robot me-2"></i> AI : Crée tâche depuis mail</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Cartes statistiques principales -->
        <div class="row g-4 mb-5">
            <!-- Projets actifs -->
            <!--   style="background: linear-gradient(to right, #4d4aed, #903ce3);" -->
            <div class="col-md-6 col-lg-3">
                <div class="card feature-card text-white shadow-lg border-0 h-100">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-project-diagram fa-3x mb-3 text-primary"></i>
                        <h5 class="mb-0 text-gray-200">Active projects</h5>
                        <h3 class="fw-bold mb-0">{{ $projectsCount ?? 0 }}</h3>
                    </div>
                </div>
            </div>

            <!-- Carte Projets en retard ;; Indicateur de projets en retard (overdue) – 5 min -->
            <div class="col-md-6 col-lg-3">
                <div class="card feature-card text-white shadow-lg border-0 h-100 {{ $overdueProjects > 0 ? 'bg-danger' : 'bg-gray-800' }}">
                    <div class="card-body text-center">
                        <i class="fas fa-exclamation-triangle fa-3x mb-3 {{ $overdueProjects > 0 ? 'text-info' : 'text-warning' }}"></i>
                        <h6 class="text-gray-200 mb-1">Projets en retard</h6>
                        <h3 class="fw-bold">{{ $overdueProjects ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <!-- Tâches en cours -->
            <div class="col-md-6 col-lg-3">
                <div class="card feature-card text-white shadow-lg border-0 h-100">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-tasks fa-3x mb-3 text-info"></i>
                        <h5>Tasks in progress</h5>
                        <h3 class="fw-bold">{{ $tasksInProgress ?? 0 }}</h3>
                    </div>
                </div>
            </div>

            <!-- Tâches urgentes / en retard assignées à moi – 10 min-->
            <div class="col-md-6 col-lg-3">
                <div class="card feature-card text-white shadow-lg border-0 h-100 {{ $urgentTasks > 0 ? 'bg-warning' : 'bg-gray-800' }}">
                    <div class="card-body text-center">
                        <i class="fas fa-exclamation-triangle fa-3x mb-3 text-danger"></i>
                        <h6 class="text-gray-400 mb-1">Tâches urgentes</h6>
                        <h3 class="fw-bold text-white">{{ $urgentTasks ?? 0 }}</h3>
                        @if($urgentTasks > 0)
                            <a href="{{ route('leader.tasks.index', ['filter' => 'urgent']) }}" class="btn btn-sm btn-dark mt-2">Voir</a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Membres -->
            <!--  style="background: linear-gradient(to right, #10b981, #34d399);"-->
            <div class="col-md-6 col-lg-3">
                <div class="card feature-card text-white shadow-lg border-0 h-100">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-users fa-3x mb-3 text-success text-emerald-200"></i>
                        <h5>Members</h5>
                        <h3 class="fw-bold">{{ $membersCount ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <!-- Charge équipe (workload global) -->
            <div class="col-md-6 col-lg-3">
                <div class="card feature-card text-white shadow-lg border-0 h-100 hover-scale transition">
                    <div class="card-body text-center">
                        <i class="fas fa-weight-hanging fa-3x mb-3 text-warning"></i>
                        <h6 class="text-gray-400 mb-1">Charge équipe</h6>
                        <h3 class="fw-bold text-white">{{ $teamWorkload ?? '0%' }}</h3>
                    </div>
                </div>
            </div>

            <!-- Points burnup simplifié -->
            <div class="col-md-6 col-lg-3">
                <div class="card bg-gray-800 border-0 shadow-lg hover-scale transition">
                    <div class="card-body text-center">
                        <i class="fas fa-fire fa-3x mb-3 text-danger"></i>
                        <h6 class="text-gray-400 mb-1">Points burnup</h6>
                        <h3 class="fw-bold text-white">{{ $burnupPoints ?? '0 / 0' }}</h3>
                    </div>
                </div>
            </div>

            <!-- Notifications non lues -->
            <div class="col-md-6 col-lg-3">
                <div class="card feature-card text-white shadow-lg border-0 h-100">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-bell fa-3x mb-3 text-warning"></i>
                        <h5>Notifications</h5>
                        <h3 class="fw-bold">{{ $notificationsCount ?? 0 }}</h3>
                    </div>
                </div>
            </div>

            <!-- Dernières activités / feed rapide – 15 min
                Ou utilise un package comme spatie/laravel-activitylog pour plus de puissance.
            -->
            <div class="col-lg-6">
                <div class="card bg-gray-800 text-white shadow-lg border-0">
                    <div class="card-header bg-gray-900 fw-bold">Activité récente</div>
                    <div class="list-group list-group-flush">
                        @forelse($recentActivities as $activity)
                            <div class="list-group-item bg-transparent text-white border-gray-700 py-3">
                                <small class="text-gray-500">{{ $activity->created_at->diffForHumans() }}</small><br>
                                <strong>{{ $activity->description }}</strong>
                            </div>
                            <hr class="my-0 border-gray-700">
                            <div class="list-group-item bg-transparent border-gray-700 py-3 px-4">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $activity->causer?->profile ?? asset('images/default-avatar.png') }}"
                                        class="rounded-circle" width="40" height="40">
                                    <div class="flex-grow-1">
                                        <strong>{{ $activity->causer?->name ?? 'Système' }}</strong>
                                        <span class="text-gray-400 ms-2">• {{ $activity->created_at->diffForHumans() }}</span>
                                        <p class="mb-0 mt-1 small">
                                            {{ $activity->description }}
                                            @if($activity->subject)
                                                <a href="#" class="text-info">
                                                    {{ $activity->subject_type }} #{{ $activity->subject_id }}
                                                </a>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                        @empty
                            <div class="text-center py-5 text-gray-400">Aucune activité récente</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Progression globale de tous mes projets – 10 min-->
            <div class="col-12 mb-5">
                <div class="card bg-gray-800 text-white shadow-lg border-0">
                    <div class="card-header bg-dark fw-bold">Progression globale</div>
                    <div class="card-body">
                        <div class="progress bg-gray-700" style="height: 25px;">
                            <div class="progress-bar bg-gradient-primary fw-bold d-flex align-items-center justify-content-center"
                                style="width: {{ $globalProgress }}%">
                                {{ $globalProgress }}% – Tous projets
                            </div>
                        </div>
                        <small class="text-gray-400 mt-2 d-block text-center">
                            {{ $totalCompletedTasks }} / {{ $totalTasks }} tâches terminées
                        </small>
                    </div>
                </div>
            </div>

            <!-- Note du jour amodifier then  -->
            <div class="col-md-4 ">
                <div class="card text-white shadow-lg border-0 h-100" style="background: linear-gradient(to right, #2563eb, #60a5fa);">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="fw-semibold mb-0">
                                {{ $hasNote ? $note->title : "Note du jour" }}
                            </h5>
                            <i class="fas fa-check-circle text-success fs-4"></i>
                        </div>
                        <p class="text-gray-200 small mb-0">
                            {{ $hasNote ? $note->content : 'Aucune note pour aujourd\'hui.' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Citation inspirante --Petit widget sympa pour la touche humaine.
                Widget météo / citation motivante – 5 min (fun & waouh)
            -->
            <div class="col-md-4">
                <div class="card bg-gradient-primary text-white shadow-lg border-0 h-100">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-quote-left fa-2x mb-3 opacity-75"></i>
                        <p class="fst-italic mb-3">"Success is falling seven times and getting up eight." </p>
                         <small>Japanese Proverb </small>
                    </div>
                </div>
            </div>

        </div>

        <!-- Notes & Motivation – Colonne double -->
        <div class="row g-4 mb-5">
            <!-- Note du jour (à développer plus tard) -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-lg border-0 overflow-hidden animate__animated animate__fadeIn"
                    style="background: linear-gradient(135deg, #1e40af, #3b82f6);">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="fw-bold text-white mb-0">
                                <i class="fas fa-sticky-note me-2"></i>
                                {{ $hasNote ? $note->title : "Note du jour" }}
                            </h5>
                            <i class="fas fa-check-circle text-success fs-4 opacity-75"></i>
                        </div>

                        <div class="flex-grow-1 d-flex align-items-center">
                            @if($hasNote)
                                <p class="text-white small mb-0 lh-base">{{ $note->content }}</p>
                            @else
                                <p class="text-white-75 fst-italic small mb-0">
                                    Aucune note personnalisée pour aujourd'hui.<br>
                                    <a href="#" class="text-white text-decoration-underline small">
                                        Ajouter une note rapide →
                                    </a>
                                </p>
                            @endif
                        </div>

                        <!-- Bouton futur pour éditer -->
                        <div class="mt-3 text-end">
                            <button class="btn btn-sm btn-outline-light opacity-75">
                                <i class="fas fa-edit me-1"></i> Éditer
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Citation du jour – animée et différente chaque jour -->
            <div class="col-md-6 col-lg-8">
                <div class="card h-100 shadow-lg border-0 overflow-hidden position-relative animate__animated animate__fadeIn animate__delay-1s"
                    style="background: linear-gradient(135deg, #7c3aed, #c084fc);">
                    <div class="card-body p-5 d-flex flex-column justify-content-center text-center">
                        <i class="fas fa-quote-left fa-4x text-white opacity-25 position-absolute top-10 start-5"></i>

                        <blockquote class="blockquote text-white mb-0 fs-4 fw-light lh-lg typing-effect">
                            <span id="quote-text"></span>
                        </blockquote>

                        <footer class="blockquote-footer text-white-75 mt-4 fs-6">
                            <cite id="quote-author"></cite>
                        </footer>
                    </div>
                </div>
            </div>
        </div>



        <!-- Graphiques + filtre -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-white">Activité récente</h4>
            <div class="btn-group btn-group-sm">
                <button class="btn btn-outline-light active" data-days="7">7 jours</button>
                <button class="btn btn-outline-light" data-days="30">30 jours</button>
                <button class="btn btn-outline-light" data-days="90">90 jours</button>
            </div>
        </div>
        <div class="row g-4 mb-5">
            <div class="col-lg-6">
                <div class="card bg-gray-800 shadow-lg border-0 rounded-3">
                    <div class="card-body p-4">
                        <canvas id="completedTasksChart" height="280"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card bg-gray-800 shadow-lg border-0 rounded-3">
                    <div class="card-body p-4">
                        <canvas id="membersActivityChart" height="280"></canvas>
                    </div>
                </div>
            </div>
        </div>


        <!-- Graphiques Chart.js -->
        <div class="row g-4 mb-5">
            <!-- Tâches complétées par jour -->
            <div class="col-lg-6">
                <div class="card bg-gray-800 text-white shadow-lg border-0 rounded-3">
                    <div class="card-header bg-gray-900 fw-bold py-3">
                        Tâches terminées par jour (60 derniers jours)
                    </div>
                    <div class="card-body p-4">
                        @if(!empty($completedTasks_labels) && !empty($completedTasks_values))
                            <canvas id="completedTasksChart" height="300"></canvas>
                        @else
                            <p class="text-center text-gray-400 py-5">
                                <i class="fas fa-chart-line fa-3x mb-3 opacity-50"></i><br>
                                Aucune tâche terminée récemment
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Membres les plus actifs -->
            <div class="col-lg-6">
                <div class="card bg-gray-800 text-white shadow-lg border-0 rounded-3">
                    <div class="card-header bg-gray-900 fw-bold py-3">
                        Membres les plus actifs
                    </div>
                    <div class="card-body p-4">
                        @if(!empty($members_labels) && !empty($members_values))
                            <canvas id="membersActivityChart" height="300"></canvas>
                        @else
                            <p class="text-center text-gray-400 py-5">
                                <i class="fas fa-users fa-3x mb-3 opacity-50"></i><br>
                                Pas encore d'activité enregistrée
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Workload heatmap simple -->
            <h4 class="fw-bold text-white mb-4">Charge de l'équipe</h4>
            <div class="row g-3 mb-5">
                @forelse($teamMembers ?? [] as $member)
                    @php
                        $load = rand(40, 120); // simulation – remplace par vrai calcul
                        $color = $load < 70 ? 'success' : ($load < 100 ? 'warning' : 'danger');
                    @endphp
                    <div class="col-md-4 col-lg-3">
                        <div class="card bg-gray-800 border-0 shadow hover-scale">
                            <div class="card-body text-center">
                                <img src="{{ $member->profile ?? asset('images/user-default.jpg') }}" class="rounded-circle mb-3" width="60">
                                <h6 class="mb-1">{{ $member->name }}</h6>
                                <div class="progress bg-gray-700 mt-2" style="height: 8px;">
                                    <div class="progress-bar bg-{{ $color }}" style="width: {{ min($load, 100) }}%"></div>
                                </div>
                                <small class="text-{{ $color }} mt-1 d-block">{{ $load }}% chargé</small>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-400 col-12">Aucun membre pour afficher la charge.</p>
                @endforelse
            </div>


            <!-- Workload heatmap – Charge de l'équipe -->
            <h4 class="fw-bold text-white mb-4 d-flex align-items-center gap-2">
                <i class="fas fa-weight-hanging text-warning"></i>
                Workload Team Members
            </h4>
           <!-- 1. Charge de l'équipe actuelle -->
            <h4 class="fw-bold text-white mb-4 d-flex align-items-center gap-3">
                <i class="fas fa-users-crown text-warning"></i>
                Charge de l'équipe actuelle
                @if($user->currentTeam )
                    <small class="text-gray-400">({{ $user->currentTeam ->name }})</small>
                @endif
            </h4>
            @if($currentTeamMembers->isEmpty())
                <div class="alert alert-info bg-gray-800 border-0 text-center py-5 rounded-3 shadow mb-5">
                    <i class="fas fa-users-slash fa-3x mb-3 opacity-50"></i>
                    <p class="mb-2">Aucun membre dans l'équipe actuelle.</p>
                    <small class="text-gray-400">
                        @if(!$user->currentTeam )
                            Tu n'as pas encore d'équipe sélectionnée.
                        @else
                            Ajoute des membres à ton équipe actuelle.
                        @endif
                    </small>
                </div>
            @else
                <div class="row g-3 mb-5">
                    @foreach($currentTeamMembers as $member)
                        @php
                            $assignedTasks = \App\Models\Task::where('assigned_to', $member->id)
                                ->whereIn('project_id', $user->projects->pluck('id'))
                                ->get();

                            $totalTasks     = $assignedTasks->count();
                            $pendingTasks   = $assignedTasks->where('status', '!=', 'completed')->count();
                            $overdueTasks   = $assignedTasks->where('end_date', '<', now())
                                                        ->where('status', '!=', 'completed')
                                                        ->count();

                            // Charge : 50% base + 30% par tâche en cours + 20% par tâche en retard
                            $load = 50 + ($pendingTasks * 30) + ($overdueTasks * 20);
                            $load = min(max($load, 0), 150);

                            $color = $load < 70 ? 'success' : ($load < 100 ? 'warning' : 'danger');
                            $icon  = $load < 70 ? 'smile' : ($load < 100 ? 'meh' : 'frown');
                        @endphp
                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <div class="card bg-gray-800 border-0 shadow-lg hover-scale transition h-100">
                                <div class="card-body text-center p-4">
                                    <!-- Avatar + humeur -->
                                    <div class="mb-3 position-relative">
                                        <img src="{{ $member->profile ?? asset('images/user-default.jpg') }}"
                                            class="rounded-circle shadow border border-{{ $color }} border-3"
                                            width="80" height="80" alt="{{ $member->name }}">
                                        <i class="fas fa-{{ $icon }} fa-2x position-absolute bottom-0 end-0 text-{{ $color }} bg-dark rounded-circle p-1"
                                        style="transform: translate(25%, 25%); font-size: 1.5rem;"></i>
                                    </div>

                                    <!-- Nom cliquable -->
                                    <h6 class="mb-2">
                                        <a href="{{ route('leader.users.profile', $member) }}"
                                        class="text-white text-decoration-none hover-text-primary">
                                            {{ $member->name }}
                                        </a>
                                    </h6>

                                    <!-- Barre -->
                                    <div class="progress bg-gray-700 rounded-pill mb-3" style="height: 10px;">
                                        <div class="progress-bar bg-{{ $color }} rounded-pill"
                                            role="progressbar"
                                            style="width: {{ min($load, 100) }}%"
                                            aria-valuenow="{{ $load }}"
                                            aria-valuemin="0"
                                            aria-valuemax="150">
                                        </div>
                                    </div>

                                    <!-- Infos -->
                                    <div class="small text-gray-400 mb-2">
                                        Charge : <strong class="text-{{ $color }}">{{ $load }}%</strong>
                                    </div>

                                    <div class="d-flex justify-content-center gap-2 flex-wrap small">
                                        <span class="badge bg-primary">{{ $totalTasks }} tâches</span>
                                        <span class="badge bg-warning">{{ $pendingTasks }} en cours</span>
                                        @if($overdueTasks > 0)
                                            <span class="badge bg-danger">{{ $overdueTasks }} en retard</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- 2. Charge globale de tous les membres assignés aux projets -->
            <h4 class="fw-bold text-white mb-4 d-flex align-items-center gap-3 mt-5">
                <i class="fas fa-globe text-info"></i>
                Charge globale des membres (tous projets)
            </h4>
            @if(($teamMembers ?? [])->isEmpty())
                <div class="alert alert-info bg-gray-800 border-0 text-center py-5 rounded-3 shadow">
                    <i class="fas fa-users-slash fa-3x mb-3 opacity-50"></i>
                    <p class="mb-2">Aucun membre dans l'équipe actuelle.</p>
                    <small class="text-gray-400">Ajoute des membres pour voir leur charge de travail.</small>
                </div>
            @else
                <div class="row g-4 mb-5">
                    @foreach($teamMembers as $member)
                        @php

                            // Vrai calcul de charge (exemple réaliste)
                            $assignedTasks = \App\Models\Task::where('assigned_to', $member->id)
                                ->whereIn('project_id', Auth::user()->projects->pluck('id')) // projets de l'utilisateur connecté
                                ->get();

                            $totalTasks     = $assignedTasks->count();
                            $pendingTasks   = $assignedTasks->where('status', '!=', 'completed')->count();
                            $overdueTasks   = $assignedTasks->where('end_date', '<', now())
                                                        ->where('status', '!=', 'completed')
                                                        ->count();

                            // Charge en % : 50% base + 30% par tâche en cours + 20% par tâche en retard
                            $load = 50 + ($pendingTasks * 30) + ($overdueTasks * 20);
                            $load = min(max($load, 0), 150); // cap entre 0 et 150%

                            $color = $load < 70 ? 'success' : ($load < 100 ? 'warning' : 'danger');
                            $icon  = $load < 70 ? 'smile' : ($load < 100 ? 'meh' : 'frown');
                        @endphp

                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <div class="card bg-gray-800 border-0 shadow-lg hover-scale transition h-100">
                                <div class="card-body text-center p-4">
                                    <!-- Avatar + nom -->
                                    <div class="mb-3 position-relative">
                                        <img src="{{ $member->profile ?? asset('images/user-default.jpg') }}"
                                            class="rounded-circle shadow border border-{{ $color }} border-3"
                                            width="80" height="80" alt="{{ $member->name }}">
                                        <i class="fas fa-{{ $icon }} fa-2x position-absolute bottom-0 end-0 text-{{ $color }} bg-dark rounded-circle p-1"
                                        style="transform: translate(25%, 25%);font-size: 1.5rem;"></i>
                                    </div>

                                    <!-- Nom + lien vers profil  url('/users/' . $member->id)-->
                                    <h6 class="mb-2">
                                        <a href="{{ route('leader.users.profile', $member) }}"
                                            class="text-white text-decoration-none hover-text-primary">
                                            {{ $member->name }}
                                        </a>
                                    </h6>

                                    <!-- Progress bar -->
                                    <div class="progress bg-gray-700 rounded-pill mb-3" style="height: 10px;">
                                        <div class="progress-bar bg-{{ $color }} rounded-pill"
                                            role="progressbar"
                                            style="width: {{ min($load, 100) }}%"
                                            aria-valuenow="{{ $load }}"
                                            aria-valuemin="0"
                                            aria-valuemax="150">
                                        </div>
                                    </div>

                                    <!-- Détails charge -->
                                    <div class="small text-gray-400 mb-2">
                                        Charge : <strong class="text-{{ $color }}">{{ $load }}%</strong>
                                    </div>

                                    <div class="d-flex justify-content-center gap-3 small">
                                        <span class="badge bg-primary">{{ $totalTasks }} tâches</span>
                                        <span class="badge bg-warning">{{ $pendingTasks }} en cours</span>
                                        @if($overdueTasks > 0)
                                            <span class="badge bg-danger">{{ $overdueTasks }} en retard</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>

        <!-- Workload heatmap simple -->
             <!-- Workload heatmap – Charge de l'équipe -->
             <!-- Debug temporaire -->

            <h4 class="fw-bold text-white mb-4 d-flex align-items-center gap-2">
                <i class="fas fa-weight-hanging text-warning"></i>
                Workload Team Members

            </h4>
            <!-- Workload heatmap – Charge de l'équipe -->
            <h4 class="fw-bold text-white mb-4 d-flex align-items-center gap-2">
                <i class="fas fa-weight-hanging text-warning"></i>
                Charge de l'équipe
                @if($currentTeam)
                    <small class="text-gray-400 ms-2">({{ $currentTeam->name }})</small>
                @endif
            </h4>
            @if(!$currentTeam)
                <div class="alert alert-warning bg-gray-800 border-warning text-center py-5 rounded-3 shadow mb-5">
                    <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                    <h5>Aucune équipe sélectionnée</h5>
                    <p class="mb-3">Sélectionne ou crée une équipe pour voir la charge de ses membres.</p>
                    <a href="{{ route('leader.team.index') }}" class="btn btn-outline-warning">
                        <i class="fas fa-users me-2"></i> Gérer mes équipes
                    </a>
                </div>
            @elseif($currentTeamMembers->isEmpty())
                <div class="alert alert-info bg-gray-800 border-0 text-center py-5 rounded-3 shadow">
                    <i class="fas fa-users-slash fa-3x mb-3 opacity-75"></i>
                    <h5>Aucun membre accepté dans cette équipe</h5>
                    <p class="mb-3 text-gray-400">
                        Invite des membres ou accepte les demandes en attente.
                    </p>
                    <a href="{{ route('leader.team.show', $currentTeam) }}" class="btn btn-outline-info">
                        <i class="fas fa-user-plus me-2"></i> Gérer les membres
                    </a>
                </div>
            @else
                <div class="row g-4 mb-5">
                    @foreach($currentTeamMembers as $member)
                        @php
                            // Calcul réel basé sur les projets de l'équipe
                            $assignedTasks = Task::where('assigned_to', $member->id)
                                ->whereIn('project_id', $currentTeam->projects->pluck('id'))
                                ->get();

                            $totalTasks     = $assignedTasks->count();
                            $pendingTasks   = $assignedTasks->where('status', '!=', 'completed')->count();
                            $overdueTasks   = $assignedTasks->where('end_date', '<', now())
                                                        ->where('status', '!=', 'completed')
                                                        ->count();

                            // Charge pondérée
                            $load = 50 + ($pendingTasks * 25) + ($overdueTasks * 35);
                            $load = min(max($load, 0), 150);

                            $color = $load < 70 ? 'success' : ($load < 100 ? 'warning' : 'danger');
                            $icon  = $load < 70 ? 'smile' : ($load < 100 ? 'meh' : 'frown');
                        @endphp

                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <div class="card bg-gray-800 border-0 shadow-lg hover-scale transition h-100">
                                <div class="card-body text-center p-4">
                                    <div class="mb-3 position-relative d-inline-block">
                                        <img src="{{ $member->profile ?? asset('images/user-default.jpg') }}"
                                            class="rounded-circle shadow border border-{{ $color }} border-3"
                                            width="80" height="80" alt="{{ $member->name }}">
                                        <i class="fas fa-{{ $icon }} fa-xl position-absolute bottom-0 end-0 text-{{ $color }} bg-dark rounded-circle p-2 shadow-sm"
                                        style="transform: translate(30%, 30%);"></i>
                                    </div>

                                    <h6 class="mb-2">
                                        <a href="{{ route('leader.users.profile', $member) ?? '#' }}"
                                        class="text-white text-decoration-none hover-text-{{ $color }}">
                                            {{ Str::limit($member->name, 18) }}
                                        </a>
                                    </h6>

                                    <div class="progress bg-gray-700 rounded-pill mb-3 mx-auto" style="width: 90%; height: 10px;">
                                        <div class="progress-bar bg-{{ $color }} rounded-pill shadow-sm"
                                            style="width: {{ min($load, 100) }}%">
                                            <span class="visually-hidden">{{ $load }}%</span>
                                        </div>
                                    </div>

                                    <div class="small text-center">
                                        <div>Charge : <strong class="text-{{ $color }}">{{ $load }}%</strong></div>
                                        <div class="mt-1">
                                            {{ $totalTasks }} tâches •
                                            <span class="text-warning">{{ $pendingTasks }} en cours</span> •
                                            @if($overdueTasks > 0)
                                                <span class="text-danger animate-pulse">{{ $overdueTasks }} retard</span>
                                            @else
                                                <span class="text-success">OK</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- 1️⃣ Workload Heatmap – Charge des membres d'ÉQUIPE -->
            <div class="alert alert-secondary mb-4">
                <strong>Debug équipe actuelle :</strong><br>
                Current Team ID : {{ $currentTeam->id ?? 'NULL' }}<br>
                Current Team Name : {{ $currentTeam->name ?? 'Aucune' }}<br>
                Nombre membres trouvés : {{ $currentTeamMembers->count() ?? 'variable non définie' }}
            </div>

            <h4 class="fw-bold text-white mb-4 d-flex align-items-center gap-2 mt-5">
                    <i class="fas fa-users text-info"></i>
                    Charge équipe : {{ $currentTeam->name ?? 'Équipe actuelle' }}
                    @if($currentTeam)
                        <small class="text-gray-400 ms-2">({{ $currentTeam->name }})</small>
                    @endif
            </h4>
            @if($currentTeamMembers->isNotEmpty())
                <div class="row g-4 mb-5">
                    @foreach($currentTeamMembers as $member)
                        @php
                            // Calcul pour membres d'ÉQUIPE (tâches dans projets de l'équipe)
                            $assignedTasks = Task::where('assigned_to', $member->id)
                                ->whereIn('project_id', $currentTeam->projects->pluck('id'))
                                ->get();

                            $totalTasks     = $assignedTasks->count();
                            $pendingTasks   = $assignedTasks->where('status', '!=', 'completed')->count();
                            $overdueTasks   = $assignedTasks->where('end_date', '<', now())
                                                        ->where('status', '!=', 'completed')
                                                        ->count();
                            $load = 50 + ($pendingTasks * 25) + ($overdueTasks * 30);
                            $load = min(max($load, 0), 150);
                            $color = $load < 70 ? 'success' : ($load < 100 ? 'warning' : 'danger');
                            $icon  = $load < 70 ? 'smile' : ($load < 100 ? 'meh' : 'frown');
                        @endphp

                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <div class="card bg-gray-800 border-left-4 border-{{ $color }} shadow-lg hover-scale transition h-100">
                                <div class="card-body text-center p-4">
                                    <!-- Avatar -->
                                    <div class="mb-3 position-relative">
                                        <img src="{{ $member->profile ?? asset('images/user-default.jpg') }}"
                                            class="rounded-circle shadow border border-{{ $color }} border-2 mx-auto"
                                            width="70" height="70" alt="{{ $member->name }}">
                                        <i class="fas fa-{{ $icon }} fa-xl position-absolute -bottom-2 -right-2 text-{{ $color }} bg-dark rounded-circle p-2 shadow"
                                        style="transform: translate(20%, 20%); font-size: 1.4rem;"></i>
                                    </div>

                                    <!-- Nom -->
                                    <h6 class="mb-2">
                                        <a href="{{ route('leader.users.profile', $member) }}" class="text-white text-decoration-none hover-text-{{ $color }}">
                                            {{ Str::limit($member->name, 20) }}
                                        </a>
                                    </h6>

                                    <!-- Progress bar -->
                                    <div class="progress bg-gray-700 rounded-pill mb-3 mx-auto" style="width: 90%; height: 12px;">
                                        <div class="progress-bar bg-gradient-{{ $color }} rounded-pill shadow-sm"
                                            role="progressbar"
                                            style="width: {{ min($load, 100) }}%"
                                            aria-valuenow="{{ $load }}" aria-valuemin="0" aria-valuemax="150">
                                            <small class="d-inline-block ms-2 fw-bold">{{ min($load, 100) }}%</small>
                                        </div>
                                    </div>

                                    <!-- Stats rapides -->
                                    <div class="text-center small mb-2">
                                        <div class="text-muted">Charge : <strong class="text-{{ $color }}">{{ $load }}%</strong></div>
                                    </div>

                                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                                        <span class="badge bg-primary fs-2xs">{{ $totalTasks }} tâches</span>
                                        <span class="badge bg-warning fs-2xs">{{ $pendingTasks }} en cours</span>
                                        @if($overdueTasks > 0)
                                            <span class="badge bg-danger fs-2xs animate-pulse">{{ $overdueTasks }} retard</span>
                                        @else
                                            <span class="text-success">Aucun retard</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info bg-gray-800 border-0 text-center py-6 rounded-3 shadow-lg mb-5">
                    <i class="fas fa-users-slash fa-3x mb-3 opacity-75"></i>
                    <h5 class="mb-2">Aucune équipe active</h5>
                    <p class="mb-3 text-gray-400">
                        @if(!$currentTeam)
                            Aucune équipe sélectionnée comme actuelle.
                        @else
                            Cette équipe n'a pas encore de membres acceptés.
                        @endif
                    </p>
                    <a href="{{ route('leader.team.index') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-users me-2"></i> Gérer mes équipes
                    </a>
                    <p class="mb-3 text-gray-400">Crée une équipe ou assigne des membres pour voir leur charge.</p>
                    <a href="{{ route('leader.team.create') }}" class="btn btn-outline-info">
                        <i class="fas fa-plus me-2"></i> Créer équipe
                    </a>
                </div>
            @endif

            <hr class="border-gray-600 my-5">

            <!-- 2️⃣ Workload Heatmap – Charge membres PROJETS (tous, même sans équipe) -->
            <h4 class="fw-bold text-white mb-4 d-flex align-items-center gap-2">
                <i class="fas fa-briefcase text-primary"></i>
                Charge membres projets ({{ $teamMembers->count() }})
            </h4>

            @if($teamMembers->isEmpty())
                <div class="alert alert-secondary bg-gray-800 border-0 text-center py-6 rounded-3 shadow mb-5">
                    <i class="fas fa-tasks fa-3x mb-3 opacity-50"></i>
                    <h5 class="mb-2">Aucun membre assigné aux projets</h5>
                    <p class="text-gray-400">Assigne des tâches à des membres pour voir leur charge.</p>
                    <a href="{{ route('leader.tasks.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-tasks me-2"></i> Gérer tâches
                    </a>
                </div>
            @else
                <div class="row g-4 mb-5">
                    @foreach($teamMembers as $member)
                        @php
                            // Calcul pour membres PROJETS (toutes tâches assignées aux projets de l'utilisateur)
                            $assignedTasks = Task::where('assigned_to', $member->id)
                                ->whereIn('project_id', Auth::user()->projects->pluck('id'))
                                ->get();

                            $totalTasks     = $assignedTasks->count();
                            $pendingTasks   = $assignedTasks->where('status', '!=', 'completed')->count();
                            $overdueTasks   = $assignedTasks->where('end_date', '<', now())
                                                        ->where('status', '!=', 'completed')
                                                        ->count();
                            $load = 40 + ($pendingTasks * 28) + ($overdueTasks * 35);
                            $load = min(max($load, 0), 160);
                            $color = $load < 75 ? 'success' : ($load < 110 ? 'warning' : 'danger');
                            $icon  = $load < 75 ? 'smile' : ($load < 110 ? 'meh' : 'dizzy');

                        @endphp

                        <div class="col-md-6 col-lg-4 col-xl-3 ">
                            <div class="card bg-gray-800 border-0 shadow-lg hover-scale transition h-100 position-relative overflow-hidden">
                                <!-- Badge surcharge -->
                                @if($load > 120)
                                    <div class="position-absolute top-0 start-0 bg-danger text-white px-2 py-1 fs-2xs fw-bold rounded-end m-1">
                                        <i class="fas fa-exclamation-triangle me-1"></i>URGENT
                                    </div>
                                @endif

                                <div class="card-body text-center p-4">
                                    <!-- Avatar -->
                                    <div class="mb-4 position-relative">
                                        <img src="{{ $member->profile ?? asset('images/user-default.jpg') }}"
                                            class="rounded-circle shadow-lg border border-{{ $color }} border-3 mx-auto"
                                            width="75" height="75" alt="{{ $member->name }}">
                                        <i class="fas fa-{{ $icon }} fa-lg position-absolute -bottom-1 -right-1 text-{{ $color }} bg-dark rounded-circle p-2 shadow-lg"
                                        style="transform: translate(20%, 20%); font-size: 1.5rem; z-index: 10;"></i>
                                    </div>

                                    <!-- Nom + rôle -->
                                    <div class="mb-3">
                                        <h6 class="mb-1">
                                            <a href="{{ route('leader.users.profile', $member) }}" class="text-white text-decoration-none hover-text-{{ $color }} fw-semibold">
                                                {{ Str::limit($member->name, 22) }}
                                            </a>
                                        </h6>
                                        <small class="text-gray-500">{{ $member->position ?? 'Collaborateur' }}</small>
                                    </div>

                                    <!-- Progress bar améliorée -->
                                    <div class="progress-container mb-4 mx-auto" style="width: 95%;">
                                        <div class="progress bg-gray-700 rounded-pill shadow-sm mb-1" style="height: 14px;">
                                            <div class="progress-bar bg-gradient-{{ $color }} rounded-pill shadow-sm pulse-{{ $color }}"
                                                role="progressbar"
                                                style="width: {{ min($load, 100) }}%; transition: width 0.8s ease;"
                                                aria-valuenow="{{ $load }}" aria-valuemin="0" aria-valuemax="160">
                                                <small class="progress-text text-white fw-bold fs-2xs">{{ min($load, 100) }}%</small>
                                            </div>
                                        </div>
                                        <small class="text-{{ $color }} fw-semibold d-block text-center">{{ $load }}% total charge</small>
                                    </div>

                                    <!-- Badges stats -->
                                    <div class="d-flex justify-content-center gap-2 flex-wrap small mb-3">
                                        <span class="badge bg-primary fs-2xs px-2 py-1">{{ $totalTasks }} tâches</span>
                                        <span class="badge bg-warning fs-2xs px-2 py-1">{{ $pendingTasks }} en cours</span>
                                        @if($overdueTasks > 0)
                                            <span class="badge bg-danger fs-2xs px-2 py-1 animate-pulse">
                                                <i class="fas fa-clock me-1"></i>{{ $overdueTasks }} retard
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Quick action -->
                                    <div class="mt-3">
                                        <a href="{{ route('leader.tasks.index', ['assigned_to' => $member->id]) }}"
                                        class="btn btn-sm btn-outline-{{ $color }} w-100">
                                            <i class="fas fa-tasks me-1"></i>
                                            Voir ses tâches
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif


            <hr class="border-gray-600 my-5">


         <!-- Activity stream récent -->
        <h4 class="fw-bold text-white mb-4">Activité récente</h4>
        <div class="card bg-gray-800 border-0 shadow-lg">
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($recentActivities ?? [] as $activity)
                        <li class="list-group-item bg-transparent border-gray-700 py-3">
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $activity->causer?->profile ?? asset('images/user-default.jpg') }}" class="rounded-circle" width="40">
                                <div class="flex-grow-1">
                                    <strong>{{ $activity->user?->name ?? 'Système' }}</strong>
                                    <span class="text-gray-400 ms-2">{{ $activity->message }}</span>
                                    <small class="d-block text-gray-500 mt-1">{{ $activity->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item bg-transparent text-center text-gray-400 py-5">
                            Aucune activité récente.
                        </li>
                    @endforelse
                    @forelse($recentActivities as $activity)
                        <li class="list-group-item bg-transparent border-gray-700 py-3">
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $activity->causer?->profile ?? asset('images/user-default.jpg') }}" class="rounded-circle" width="40">
                                <div class="flex-grow-1">
                                    <strong>{{ $activity->causer?->name ?? 'Système' }}</strong>
                                    <span class="text-gray-400 ms-2">{{ $activity->description }}</span>
                                    <small class="d-block text-gray-500 mt-1">{{ $activity->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item bg-transparent text-center text-gray-400 py-5">
                            Aucune activité récente.
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- Derniers projets -->
        <h3 class="fw-bold mb-4 text-gradient">Last projets (6 projects)</h3>
        <div class="row g-4 mb-5">
            @forelse($recentProjects as $project)
                <div class="col-md-6 col-lg-4">
                    <div class="card bg-gray-800 text-white border-0 shadow hover:shadow-xl transition h-100">
                        <div class="card-body">
                            <h5 class="fw-bold">{{ $project->name }}</h5>
                            <p class="text-gray-400 small mb-3">
                                {{ Str::limit($project->description ?? 'No description', 80) }}

                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge bg-info">{{ $project->tasks_count }} tâches</span>
                                    <span class="badge bg-success ms-1">{{ $project->progress }}%</span>
                                </div>
                                <a href="{{ route('leader.projects.show', $project) }}" class="btn btn-sm btn-outline-light">
                                    Voir <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-gray-400">Aucun projet récent.</p>
                    <a href="{{ route('leader.projects.create') }}" class="btn btn-contact mt-3">
                        Créer un projet
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Dernier post (amélioré) -->
        @if($hasPost && $post)
            <h3 class="fw-bold mb-4 text-gradient">Dernier post d'équipe</h3>
            <div class="card bg-gray-800 text-white shadow-lg border-0 rounded-3">
                <div class="card-body p-5">
                    <div class="d-flex align-items-start gap-4">
                        <img src="{{ $post->user->profile ?? asset('images/user-default.jpg') }}"
                             class="rounded-circle" width="60" height="60" alt="{{ $post->user->name }}">
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>{{ $post->user->name }}</strong>
                                    <small class="text-gray-400 ms-2">• {{ $post->created_at->diffForHumans() }}</small>
                                </div>
                                <a href="{{ route('leader.posts.show', $post) }}" class="btn btn-sm btn-outline-light">
                                    Voir le post complet →
                                </a>
                            </div>

                            <h5 class="mt-3 mb-2">{{ $post->title ?? 'Sans titre' }}</h5>
                            <p class="text-gray-300 mb-4">{{ Str::limit($post->content, 200) }}</p>

                            @if($post->image)
                                <img src="{{ asset('storage/' . $post->image) }}"
                                     class="img-fluid rounded-lg mb-4 shadow" alt="Post image">
                            @endif

                            <div class="d-flex gap-4 text-gray-400 small">
                                <span><i class="fas fa-thumbs-up me-1"></i> {{ $post->likes_count ?? 0 }}</span>
                                <span><i class="fas fa-comment me-1"></i> {{ $post->comments_count ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <p class="text-center text-gray-400 py-5">Aucun post récent dans les groupes d'équipe.</p>
        @endif

    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Graphique tâches terminées
    const lineCtx = document.getElementById('completedTasksChart');
    if (lineCtx) {
        new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: @json($completedTasks_labels ?? []),
                datasets: [{
                    label: 'Tâches terminées',
                    data: @json($completedTasks_values ?? []),
                    borderColor: '#60a5fa',
                    backgroundColor: 'rgba(96, 165, 250, 0.2)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#60a5fa',
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { labels: { color: '#e2e8f0' } } },
                scales: {
                    x: { ticks: { color: '#9ca3af' }, grid: { color: '#374151' } },
                    y: { beginAtZero: true, ticks: { color: '#9ca3af' }, grid: { color: '#374151' } }
                }
            }
        });
    }

    // Graphique membres actifs
    const barCtx = document.getElementById('membersActivityChart');
    if (barCtx) {
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: @json($members_labels ?? []),
                datasets: [{
                    label: 'Tâches terminées',
                    data: @json($members_values ?? []),
                    backgroundColor: '#a78bfa',
                    borderColor: '#a78bfa',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { labels: { color: '#e2e8f0' } } },
                scales: {
                    x: { ticks: { color: '#9ca3af' }, grid: { color: '#374151' } },
                    y: { beginAtZero: true, ticks: { color: '#9ca3af' }, grid: { color: '#374151' } }
                }
            }
        });
    }
});
</script>
@endpush

@push('styles')
<style>
.feature-card {
    background: linear-gradient(135deg, #1e293b, #0f172a);
    border-radius: 16px;
    transition: transform 0.3s, box-shadow 0.3s;
}
.feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 25px 50px -12px rgba(0,0,0,0.6);
}
.btn-contact {
    background: linear-gradient(to right, #3b82f6, #8b5cf6);
    border: none;
    transition: all 0.3s;
}
.btn-contact:hover {
    background: linear-gradient(to right, #2563eb, #7c3aed);
    transform: translateY(-2px);
}
.text-gradient {
    background: linear-gradient(to right, #60a5fa, #a78bfa);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
</style>
@endpush

@push('scripts')
<script>
// Filtre rapide pour graphiques (simulation – à connecter au back si besoin)
document.querySelectorAll('[data-days]').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('[data-days]').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        // Ici tu peux recharger les données via fetch si tu veux
        alert('Filtre changé : ' + btn.textContent);
    });
});

// Micro-interactions sur cartes
document.querySelectorAll('.card.hover-scale').forEach(card => {
    card.addEventListener('mouseenter', () => card.style.transform = 'scale(1.03)');
    card.addEventListener('mouseleave', () => card.style.transform = 'scale(1)');
});
</script>
@endpush

@push('styles')
<style>
.hover-scale {
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}
.hover-scale:hover {
    transform: scale(1.03);
    box-shadow: 0 20px 40px rgba(0,0,0,0.6) !important;
}
.text-gradient {
    background: linear-gradient(90deg, #60a5fa, #c084fc);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.bg-gradient-dark {
    background: linear-gradient(to bottom, #0f172a, #020617);
}
</style>
@endpush


@push('scripts')
<script>
// Liste de citations motivantes en anglais (tu peux en ajouter autant que tu veux)
const quotes = [
    { text: "Success is not final, failure is not fatal: it is the courage to continue that counts.", author: "Winston Churchill" },
    { text: "The only way to do great work is to love what you do.", author: "Steve Jobs" },
    { text: "Believe you can and you're halfway there.", author: "Theodore Roosevelt" },
    { text: "Your time is limited, so don't waste it living someone else's life.", author: "Steve Jobs" },
    { text: "The future belongs to those who believe in the beauty of their dreams.", author: "Eleanor Roosevelt" },
    { text: "It does not matter how slowly you go as long as you do not stop.", author: "Confucius" },
    { text: "Everything you’ve ever wanted is on the other side of fear.", author: "George Addair" },
    { text: "The harder you work for something, the greater you’ll feel when you achieve it.", author: "Anonymous" },
    { text: "Dream big and dare to fail.", author: "Norman Vaughan" },
    { text: "Act as if what you do makes a difference. It does.", author: "William James" },
    { text: "Success usually comes to those who are too busy to be looking for it.", author: "Henry David Thoreau" },
    { text: "Don’t watch the clock; do what it does. Keep going.", author: "Sam Levenson" },
    { text: "The only limit to our realization of tomorrow will be our doubts of today.", author: "Franklin D. Roosevelt" },
    { text: "You are never too old to set another goal or to dream a new dream.", author: "C.S. Lewis" },
    { text: "What you get by achieving your goals is not as important as what you become by achieving your goals.", author: "Zig Ziglar" },
    { text: "The best way to predict the future is to create it.", author: "Peter Drucker" },
    { text: "Challenges are what make life interesting and overcoming them is what makes life meaningful.", author: "Joshua J. Marine" },
    { text: "A year from now you may wish you had started today.", author: "Karen Lamb" },
    { text: "The only person you are destined to become is the person you decide to be.", author: "Ralph Waldo Emerson" },
    { text: "In the middle of every difficulty lies opportunity.", author: "Albert Einstein" },
    { text: "The only way to do great work is to love what you do.", author: "Steve Jobs" },
    { text: "Success is not the key to happiness. Happiness is the key to success.", author: "Albert Schweitzer" },
    { text: "Don’t be afraid to give up the good to go for the great.", author: "John D. Rockefeller" },
    { text: "I find that the harder I work, the more luck I seem to have.", author: "Thomas Jefferson" },
    { text: "Opportunities don’t happen. You create them.", author: "Chris Grosser" },
    { text: "The secret of getting ahead is getting started.", author: "Mark Twain" },
    { text: "I never dreamed about success. I worked for it.", author: "Estée Lauder" },
    { text: "The only place where success comes before work is in the dictionary.", author: "Vidal Sassoon" },
    { text: "There are no shortcuts to any place worth going.", author: "Beverly Sills" },
    { text: "Success is walking from failure to failure with no loss of enthusiasm.", author: "Winston Churchill" },
    // ... ajoute encore 50 si tu veux
    // Tu peux continuer à ajouter ici (50+ si tu veux plus de variété)
];

document.addEventListener('DOMContentLoaded', () => {
    // Choisir une citation en fonction du jour de l’année (même citation tous les jours du même jour)
    const now = new Date();
    const start = new Date(now.getFullYear(), 0, 0);
    const diff = now - start;
    const oneDay = 1000 * 60 * 60 * 24;
    const dayOfYear = Math.floor(diff / oneDay); // 1 à 365/366

    const index = (dayOfYear - 1) % quotes.length; // -1 pour commencer à 0
    const selected = quotes[index];

    // Effet machine à écrire
    const quoteElement = document.getElementById('quote-text');
    const authorElement = document.getElementById('quote-author');

    let i = 0;
    quoteElement.textContent = ''; // reset au cas où

    function typeWriter() {
        if (i < selected.text.length) {
            quoteElement.textContent += selected.text.charAt(i);
            i++;
            setTimeout(typeWriter, 35); // vitesse un peu plus rapide
        } else {
            authorElement.textContent = selected.author;
        }
    }

    typeWriter();
});
</script>
@endpush
