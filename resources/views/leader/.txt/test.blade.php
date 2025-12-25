@extends('layouts.appW')

@section('contentW')
<section class="py-5 min-vh-100">
    <div class="container">

        <!-- Titre -->
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h1 class="display-5 fw-bold text-gradient mb-4 text-center">Dashboard Leader</h1>
            <div class="text-gray-300">
                Wellcome, <strong>{{ Auth::user()->name }}</strong>
            </div>
        </div>

        <hr> <br>
        <!-- Indicateurs principaux -->

        <!-- Cartes statistiques -->
        <div class="row g-4 mb-5 justify-content-center">

            <div class="col-md-6 col-lg-3">
                <div class="card feature-card text-white shadow-lg border-0">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-project-diagram fa-3x mb-3 text-primary"></i>
                        <h5>Active projects</h5>
                        <h3 class="fw-bold">{{ $projectsCount ?? 0 }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card feature-card text-white shadow-lg border-0">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-tasks fa-3x mb-3 text-info"></i>
                        <h5>Tasks in progress</h5>
                        <h3 class="fw-bold">{{ $tasksInProgress ?? 0 }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card feature-card text-white shadow-lg border-0">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-users fa-3x mb-3 text-success"></i>
                        <h5>Members</h5>
                        <h3 class="fw-bold">{{ $membersCount ?? 0 }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card feature-card text-white shadow-lg border-0">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-bell fa-3x mb-3 text-warning"></i>
                        <h5>Notifications</h5>
                        <h3 class="fw-bold">{{ $notificationsCount ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <a href="{{ route('leader.projects.create') }}" class="btn btn-lg btn-contact text-white w-100 py-4 shadow">
                    <i class="fas fa-plus me-2"></i> Create a project
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('leader.tasks.create') }}" class="btn btn-lg btn-primary text-white w-100 py-4 shadow">
                    <i class="fas fa-plus me-2"></i> Add a task
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('leader.posts.create') }}" class="btn btn-lg btn-info text-white w-100 py-4 shadow">
                    <i class="fas fa-pen me-2"></i> Publish a post
                </a>
            </div>
        </div>

        <!-- Derniers projets -->
        <h3 class="fw-bold mb-4 text-gradient">Latest Projects</h3>
        <div class="row g-4">
            @forelse($recentProjects as $project)
                <div class="col-md-6 col-lg-4">
                    <div class="card bg-gray-800 text-white border-0 shadow hover:shadow-xl transition">
                        <div class="card-body">
                            <h5 class="fw-bold">{{ $project->name }}</h5>
                            <p class="text-gray-400 small"> ◆ {{ Str::limit($project->description, 80) }}</p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <small>{{ $project->tasks->count() }} Tasks</small>
                                <a href="{{ route('leader.projects.show', $project) }}" class="btn btn-sm btn-outline-light">
                                    See-More <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-gray-400">No projects yet.</p>
                    <a href="{{ route('leader.projects.create') }}" class="btn btn-contact">Create your first project</a>
                </div>
            @endforelse
        </div>



        <hr class="border-gray-600 my-5">
        <!-- Graphiques  v0 a corriger apres -->
        <div class="row g-4 mb-5">

            <!-- Tâches complétées par jour (Line Chart) -->
            <div class="col-lg-6">
                <div class="card bg-gray-800 text-white shadow-lg border-0">
                    <div class="card-header fw-bold">
                        Number of tasks completed per day
                    </div>
                    <div class="card-body">
                        <canvas id="completedTasksChart" height="300"></canvas>
                    </div>
                </div>
            </div>

            <!-- Membres les plus actifs (Bar Chart) -->
            <div class="col-lg-6">
                <div class="card bg-gray-800 text-white shadow-lg border-0">
                    <div class="card-header fw-bold">
                        Most active members
                    </div>
                    <div class="card-body">
                        <canvas id="membersActivityChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <hr class="border-gray-600 my-5">



    </div>
</section>
@endsection



@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Line Chart - Tâches complétées
    const ctxLine = document.getElementById('completedTasksChart').getContext('2d');
    new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: @json($completedTasks_labels),
            datasets: [{
                label: 'Tâches complétées',
                data: @json($completedTasks_values),
                borderColor: '#60a5fa',
                backgroundColor: 'rgba(96, 165, 250, 0.2)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { labels: { color: '#e2e8f0' } } },
            scales: {
                x: { ticks: { color: '#9ca3af' }, grid: { color: '#374151' } },
                y: { ticks: { color: '#9ca3af' }, grid: { color: '#374151' } }
            }
        }
    });

    // Bar Chart - Membres actifs
    const ctxBar = document.getElementById('membersActivityChart').getContext('2d');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: @json($members_labels),
            datasets: [{
                label: 'Tâches complétées',
                data: @json($members_values),
                backgroundColor: '#a78bfa',
                borderColor: '#c4b5fd',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { labels: { color: '#e2e8f0' } } },
            scales: {
                x: { ticks: { color: '#9ca3af' }, grid: { color: '#374151' } },
                y: { beginAtZero: true, ticks: { color: '#9ca3af' }, grid: { color: '#374151' } }
            }
        }
    });
});
</script>
@endpush
------------------( Chart.js  )----------------++++++++++++++++++++

       // Données pour les graphiques (exemple statique pour l'instant)
        //$completedTasks_labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        //$completedTasks_values = [5, 12, 8, 15, 10, 6, 9];
        $startDate = Carbon::now()->subDays(6)->startOfDay(); // 7 jours incluant aujourd'hui
        $endDate   = Carbon::now()->endOfDay();

        $completedTasksData = Task::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date');

        // Génère les 7 derniers jours (même si pas de tâches certains jours → 0)
        $period = collect([]);
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dayName = Carbon::now()->subDays($i)->format('D'); // Lun, Mar, etc. (en anglais, mais tu peux traduire)
            $period->put($date, $dayName);
        }

        $completedTasks_labels = $period->values()->toArray(); // ['Mon', 'Tue', ..., 'Sun']
        $completedTasks_values = $period->keys()->map(function ($date) use ($completedTasksData) {
            return $completedTasksData->get($date, 0);
        })->toArray();

        /*Traduction en français (optionnel) :
        PHP$dayTranslations = [
            'Mon' => 'Lun', 'Tue' => 'Mar', 'Wed' => 'Mer', 'Thu' => 'Jeu',
            'Fri' => 'Ven', 'Sat' => 'Sam', 'Sun' => 'Dim'
        ];
        $completedTasks_labels = array_map(fn($day) => $dayTranslations[$day], $completedTasks_labels);
        */

        //Membres les plus actifs (Bar Chart)
        //$members_labels = ['Alice', 'Bob', 'Charlie', 'Diana'];
        //$members_values = [25, 18, 30, 12];
        $topActiveMembers = Task::select('assigned_to', DB::raw('COUNT(*) as tasks_count'))
            ->where('status', 'completed')
            ->whereIn('project_id', Auth::user()->projects()->pluck('id')) // Seulement les projets du leader
            ->groupBy('assigned_to')
            ->orderByDesc('tasks_count')
            ->limit(5)
            ->with('assignedTo') // Charge l'utilisateur
            ->get();

        $members_labels = $topActiveMembers->pluck('assignedTo.name')->toArray();
        $members_values = $topActiveMembers->pluck('tasks_count')->toArray();

        // Si pas de données
        if ($topActiveMembers->isEmpty()) {
            $members_labels = ['Aucun membre actif'];
            $members_values = [0];
        }

------------------------------------------------


@push('scripts')
<!-- Charger Chart.js une seule fois -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>

<script>
    // Attendre que tout le DOM soit chargé
    document.addEventListener('DOMContentLoaded', function () {
        console.log('DOM chargé, initialisation des graphiques...');

        // Données injectées depuis Laravel (vérifie qu'elles sont bien des arrays)
        const completedTasksLabels = @json($completedTasks_labels ?? []);
        const completedTasksValues = @json($completedTasks_values ?? []);
        const membersLabels = @json($members_labels ?? []);
        const membersValues = @json($members_values ?? []);

        console.log('Labels tâches:', completedTasksLabels);
        console.log('Valeurs tâches:', completedTasksValues);
        console.log('Labels membres:', membersLabels);
        console.log('Valeurs membres:', membersValues);

        // Graphique 1 : Tâches complétées par jour
        const lineCtx = document.getElementById('completedTasksChart');
        if (lineCtx && completedTasksLabels.length > 0) {
            new Chart(lineCtx, {
                type: 'line',
                data: {
                    labels: completedTasksLabels,
                    datasets: [{
                        label: 'Tâches complétées',
                        data: completedTasksValues,
                        borderColor: '#60a5fa',
                        backgroundColor: 'rgba(96, 165, 250, 0.15)',
                        borderWidth: 3,
                        pointBackgroundColor: '#60a5fa',
                        pointRadius: 6,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: true, labels: { color: '#e2e8f0' } }
                    },
                    scales: {
                        x: { ticks: { color: '#9ca3af' }, grid: { color: '#374151' } },
                        y: { beginAtZero: true, ticks: { color: '#9ca3af', stepSize: 1 }, grid: { color: '#374151' } }
                    }
                }
            });
        }

        // Graphique 2 : Membres actifs
        const barCtx = document.getElementById('membersActivityChart');
        if (barCtx && membersLabels.length > 0) {
            new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: membersLabels,
                    datasets: [{
                        label: 'Tâches complétées',
                        data: membersValues,
                        backgroundColor: 'rgba(167, 139, 250, 0.8)',
                        borderColor: '#a78bfa',
                        borderWidth: 1,
                        borderRadius: 8,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { labels: { color: '#e2e8f0' } }
                    },
                    scales: {
                        x: { ticks: { color: '#9ca3af' }, grid: { color: '#374151' } },
                        y: { beginAtZero: true, ticks: { color: '#9ca3af', stepSize: 1 }, grid: { color: '#374151' } }
                    }
                }
            });
        }
    });
</script>
@endpush

----------------------------------
        <!-- Graphiques -->
        <div class="row g-4 mb-5 justify-content-center">
            <!-- Tâches complétées par jour (Line Chart) -->
            <div class="col-lg-6">
                <div class="card bg-gray-800 text-white shadow-lg border-0 rounded-3 overflow-hidden">
                    <div class="card-header fw-bold bg-gray-900 py-3">
                        Tâches complétées par jour (7 derniers jours)
                    </div>
                    <div class="card-body p-4">
                        @if(empty($completedTasks_labels) || empty($completedTasks_values) || count($completedTasks_labels) === 0)
                            <div class="text-center py-8 text-gray-400">
                                <i class="fas fa-chart-line fa-3x mb-3 opacity-50"></i>
                                <p class="text-center text-gray-400 py-5">Aucune tâche complétée ces 7 derniers jours pour le moment.</p>
                            </div>
                        @else
                            <canvas id="completedTasksChart" height="350"></canvas>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Membres les plus actifs (Bar Chart) -->
            <div class="col-lg-6">
                <div class="card bg-gray-800 text-white shadow-lg border-0 rounded-3 overflow-hidden">
                    <div class="card-header fw-bold bg-gray-900 py-3">
                        Membres les plus actifs (tâches complétées)
                    </div>
                    <div class="card-body p-4">
                        @if(empty($members_labels) || empty($members_values) || count($members_labels) === 0)
                            <div class="text-center py-8 text-gray-400">
                                <i class="fas fa-users fa-3x mb-3 opacity-50"></i>
                                <p class="text-center text-gray-400 py-5">Aucune activité disponible pour le moment.</p>
                            </div>
                        @else
                            <canvas id="membersActivityChart" height="350"></canvas>
                        @endif
                    </div>
                </div>
            </div>
        </div>

----------------------------------
