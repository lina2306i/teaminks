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
            <!-- Projets   style="background: linear-gradient(to right, #4d4aed, #903ce3);" -->
            <div class="col-md-6 col-lg-3">
                <div class="card feature-card text-white shadow-lg border-0"  style="background: linear-gradient(to right,);">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-project-diagram fa-3x mb-3 text-primary"></i>
                        <h5 class="mb-0 text-gray-200">Active projects</h5>
                        <h3 class="fw-bold mb-0">{{ $projectsCount ?? 0 }}</h3>
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

            <!-- Membres  style="background: linear-gradient(to right, #10b981, #34d399);"-->
            <div class="col-md-6 col-lg-3">
                <div class="card feature-card text-white shadow-lg border-0">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-users fa-3x mb-3 text-success text-emerald-200"></i>
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

            <hr class="border-white-600 my-5">

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
            <hr class="border-red-600 my-5">

        </div>

        <!-- Actions rapides -->
        <div class="row g-4 mb-5 justify-content-center">
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

        <hr class="border-gray-600 my-5">
        <!-- Graphiques --  Graphiques avec barres partiellement colorées (chart.js)-->
        <hr class="border-gray-600 my-5">
        <!-- Graphiques -->
        <h5>Graphiques avec  Chart.js </h5>
        <div class="row g-4 mb-5 justify-content-center">
            <!-- Tâches complétées par jour (Line Chart) -->
            <div class="col-lg-6">
                <div class="card bg-gray-800 text-white shadow-lg border-0 rounded-3 overflow-hidden">
                    <div class="card-header fw-bold bg-gray-900 py-3">
                        Tasks completed per day (last 7 days)
                    </div>
                    <div class="card-body p-4">
                        @if(empty($completedTasks_labels) || empty($completedTasks_values) || count($completedTasks_labels) === 0)
                            <div class="text-center py-8 text-gray-400">
                                <i class="fas fa-chart-line fa-3x mb-3 opacity-50"></i>
                                <p class="text-center text-gray-400 py-5">No tasks have been completed in the last 7 days so far</p>
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
                        Most Active Members (tasks completed)
                    </div>
                    <div class="card-body p-4">
                        @if(empty($members_labels) || empty($members_values) || count($members_labels) === 0)
                            <div class="text-center py-8 text-gray-400">
                                <i class="fas fa-users fa-3x mb-3 opacity-50"></i>
                                <p class="text-center text-gray-400 py-5">No activities are currently available.</p>
                            </div>
                        @else
                            <canvas id="membersActivityChart" height="350"></canvas>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <hr class="border-gray-600 my-5">


        <hr class="border-gray-600 my-5">
        <h5>Barre avec Bootstrap uniquement</h5>
         <!-- Graphiques avec Bootstrap 5 uniquement (progress bar) -->
        <div class="row g-4 mb-5">

            <!-- Tâches complétées par jour -->
            <div class="col-lg-6">
                <div class="card leader-card">
                    <div class="card-header leader-header">
                        Tasks completed per day (last 7 days)
                    </div>

                    <div class="card-body leader-body">

                        @if(empty($completedTasks_labels))
                            <p class="text-center text-muted py-5">Aucune donnée disponible</p>
                        @else
                            @php $maxTasks = max($completedTasks_values); @endphp

                            @foreach($completedTasks_labels as $i => $day)
                                @php
                                    $value = $completedTasks_values[$i];
                                    $percent = ($value / $maxTasks) * 100;
                                @endphp

                                <div class="mb-4">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted fw-semibold">{{ $day }}</span>
                                        <span class="fw-bold">{{ $value }}</span>
                                    </div>

                                    <div class="progress leader-progress">
                                        <div class="progress-bar leader-bar"
                                            style="width: {{ $percent }}%">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                    </div>
                </div>
            </div>

            <!-- Membres les plus actifs -->
            <div class="col-lg-6">
                <div class="card leader-card">
                    <div class="card-header leader-header">
                        Most active members (tasks completed)
                    </div>

                    <div class="card-body leader-body">

                        @if(empty($members_labels))
                            <p class="text-center text-muted py-5">No activity saved</p>
                        @else
                            @php $maxMember = max($members_values); @endphp

                            @foreach($members_labels as $i => $name)
                                @php
                                    $value = $members_values[$i];
                                    $percent = ($value / $maxMember) * 100;
                                @endphp

                                <div class="mb-5">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="fw-bold">{{ $name }}</span>
                                        <span class="text-muted">{{ $value }} tasks</span>
                                    </div>

                                    <div class="progress leader-progress-lg">
                                        <div class="progress-bar leader-bar"
                                            style="width: {{ $percent }}%">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                    </div>
                </div>
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


        <!-- Dernier post -->
        @if($hasPost && $post)
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card bg-gray-800 text-white shadow-lg border-0">
                        <div class="card-header fw-bold">
                            Latest post in Teams groups
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-start gap-3">
                                <div class="bg-gray-600 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:50px;height:50px;">
                                    <i class="fas fa-user text-white fa-lg"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <strong>◉ {{ $post->user->name }}</strong>
                                    <small class="text-gray-400 d-block">{{ $post->created_at->diffForHumans() }}</small>
                                    <div class="mt-4 mb-2">
                                        <h5 class="mt-2 mb-0 "> ⫷◆⫸ {{ $post->title }}</h5>
                                        <p class="mt-2 mb-0">{{ $post->content }}</p>
                                        @if($post->image)
                                            <img src="{{ asset('storage/' . $post->image) }}" class="img-fluid rounded mt-3" alt="Post image">
                                        @endif
                                    </div>

                                    <div class="col-lg-8 col-xl-7">
                                        <div class="card bg-gray-800 text-white shadow-lg border-0 rounded-xl hover:shadow-xl hover:border-blue-500 transition-all">
                                            <div class="card-body p-5">
                                                <!-- Title -->
                                                <h3 class="h4 fw-bold mb-3">⫷◆⫸
                                                    <a href="{{ route('leader.posts.show', $post) }}" class="text-white hover:text-blue-400 transition">
                                                        {{ $post->title ?? 'Untitled Post' }}
                                                    </a>
                                                </h3>

                                                <!-- Content -->
                                                <p class="text-gray-200 text-lg mb-4">{{ $post->content }}</p>

                                                <!-- Image -->
                                                @if($post->image)
                                                    <div class="mb-4">
                                                        <img src="{{ asset('storage/' . $post->image) }}"
                                                            class="img-fluid rounded-lg shadow"
                                                            alt="Post image">
                                                    </div>
                                                @endif

                                                <!-- Quick Actions -->
                                                <div class="d-flex align-items-center gap-5 text-gray-400">
                                                    <a href="{{ route('leader.posts.show', $post) }}" class="d-flex align-items-center gap-2 hover:text-blue-400 transition">
                                                        <i class="fas fa-thumbs-up"></i>
                                                        {{ $post->likes_count }} Like{{ $post->likes_count != 1 ? 's' : '' }}
                                                    </a>
                                                    <a href="{{ route('leader.posts.show', $post) }}" class="d-flex align-items-center gap-2 hover:text-emerald-400 transition">
                                                        <i class="fas fa-comment"></i>
                                                        {{ $post->comments_count }} Comment{{ $post->comments_count != 1 ? 's' : '' }}
                                                    </a>
                                                    <a href="{{ route('leader.posts.show', $post) }}" class="hover:text-blue-400 transition ms-auto">
                                                        View post →
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center mt-5">
                                        {{-- $posts->links() --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <p class="text-center text-gray-400 fs-5">No recent posts from the team groups</p>
        @endif

        <hr class="border-gray-600 my-5">


    </div>
</section>
@endsection


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
                        label: 'Tasks completed',
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
                        label: 'Tasks completed',
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


@push('styles')
<style>

/* partie qui fonctionne pour les barres de progression personnalisées */
/* Carte */
.leader-card {
    background: #0f172a;
    border: none;
    border-radius: 16px;
    box-shadow: 0 25px 50px rgba(0,0,0,.5);
    color: #e5e7eb;
}

/* Header */
.leader-header {
    background: linear-gradient(180deg, #111827, #0f172a);
    font-weight: 700;
    font-size: 1.1rem;
    padding: 1.5rem 2rem;
    border-bottom: 1px solid #1f2933;
}

/* Body */
.leader-body {
    padding: 2rem;
}

/* Progress container */
.leader-progress,
.leader-progress-lg {
    background: #1f2937;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: inset 0 2px 6px rgba(0,0,0,.6);
}

.leader-progress {
    height: 18px;
}

.leader-progress-lg {
    height: 26px;
}

/* Barre ACTIVE (bien visible) */
.leader-bar {
    background: linear-gradient(
        90deg,
        #2dd4bf,
        #22d3ee,
        #38bdf8
    );
    box-shadow:
        0 0 15px rgba(45,212,191,.8),
        inset 0 0 4px rgba(255,255,255,.4);
    transition: width 1.6s ease;
}

/* Texte */
.text-muted {
    color: #9ca3af !important;
}

</style>
@endpush
