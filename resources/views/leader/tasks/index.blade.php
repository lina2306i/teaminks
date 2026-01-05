@extends('layouts.appW')


@section('contentW')
<div class="container py-5">
    <!-- Titre + Bouton Add Task -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4 mb-5">
        <div>
            <h1 class="display-5 fw-bold text-gradient text-uppercase mb-2">Tasks</h1>
            <p class="text-gray-400 mb-0">Manage and track all your team's tasks in one place</p>
        </div>
        <a href="{{ route('leader.tasks.create') }}" class="btn btn-primary btn-lg shadow">
            <i class="fas fa-plus me-2"></i> Add Task
        </a>
        <a href="{{ route('leader.interface.calendar') }}" class="btn btn-info">
            <i class="fas fa-calendar-alt me-2"></i> Vue Calendrier
        </a>
    </div>

    {{-- Barre de filtres élégante --}}
    {{-- Barre de filtres premium & scalable --}}
    <div class="shadow card rounded-xl shadow-lg p-4 mb-4 border border-gray-700"   >
        <div class="row g-4 align-items-center">
            {{-- Colonne gauche : Filtres principaux text-center mb-5 --}}
            <div class="col-lg-8 mb-5">
                <div class="d-flex flex-wrap gap-3 align-items-center">
                    {{-- Bouton All Tasks ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg ring-4 ring-purple-500/30'
                                : 'bg-gray-700 text-gray-300 hover:bg-gray-600 border border-gray-600'--}}
                    <a href="{{ route('leader.tasks.index') }}"
                    class="px-5 py-2.5 rounded-full border font-semibold transition-all duration-300
                            {{ !request()->hasAny(['projectId', 'status', 'search'])
                            ? 'border-gray-500 text-gray-400 hover:bg-gray-700 hover:text-white'
                            : 'bg-gradient-to-r from-blue-600 to-purple-600 text-white border-transparent shadow-lg'   }}">
                        <i class="fas fa-tasks me-2"></i>All Tasks
                    </a>

                    {{-- Dropdown pour les Projets (scalable !) --}}
                    <div class="dropdown d-inline-block">
                        <button class="btn px-5 py-2.5  rounded-full font-semibold dropdown-toggle
                                    {{ request()->has('projectId')
                                        ? 'bg-indigo-600 text-white shadow-md'
                                        : 'bg-gray-700 text-gray-300 hover:bg-gray-600 border border-gray-600' }}"
                                type="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">
                            <i class="fas fa-folder me-2"></i>
                            Project:
                            <span class="fw-bold">
                                {{ request()->has('projectId')
                                    ? ($projects->find(request('projectId'))?->name ?? 'Unknown')
                                    : 'All Projects' }}
                            </span>
                        </button>
                        <ul class="dropdown-menu bg-gray-800 border-gray-700 shadow-xl mt-2" style="min-width: 280px;">
                            <li>
                                <a class="dropdown-item text-gray-300 hover:bg-gray-700 py-2 px-4 {{ !request()->has('projectId') ? 'active bg-gray-700' : '' }}"
                                href="{{ route('leader.tasks.index', request()->except('projectId')) }}">
                                <i class="fas fa-globe me-2"></i> All Projects
                                </a>
                            </li>
                            <li><hr class="dropdown-divider border-gray-600"></li>
                            @foreach($projects as $project)
                                <li>
                                    <a class="dropdown-item text-gray-300 hover:bg-gray-700 py-2 px-4 d-flex justify-content-between align-items-center
                                            {{ request()->query('projectId') == $project->id ? 'active bg-indigo-600 text-white' : '' }}"
                                    href="{{ route('leader.tasks.index', ['projectId' => $project->id] + request()->except('projectId')) }}">
                                        <span>{{ $project->name }}</span>
                                        <span class="badge bg-gray-600 text-gray-300 small">
                                            {{ $project->tasks_count ?? $project->tasks->count() }}
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Badges pour les Statuts (toujours visibles, compact) --}}
                    @php
                        $statuses = [
                            'todo' => ['label' => 'To Do', 'icon' => 'fa-clock', 'color' => 'secondary'],
                            'in_progress' => ['label' => 'In Progress', 'icon' => 'fa-spinner', 'color' => 'warning'],
                            'completed' => ['label' => 'Completed', 'icon' => 'fa-check', 'color' => 'success'],
                        ];
                    @endphp

                    @foreach($statuses as $key => $status)
                        @php
                            $isActive = request()->query('status') == $key;
                        @endphp
                        <a href="{{ route('leader.tasks.index', request()->except('status') + ['status' => $key]) }}"
                        class="px-4 py-2.5 border rounded-full font-medium transition-all duration-300
                                {{ $isActive
                                    ? "bg-{$status['color']} text-white shadow-md ring-4 ring-{$status['color']}-500/30"
                                    : "bg-gray-700 text-gray-300 hover:bg-{$status['color']} hover:text-white border border-gray-600" }}">
                            <i class="fas {{ $status['icon'] }} me-2"></i>
                            {{ $status['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Colonne droite : Recherche rapide --}}
            <div class="col-lg-4">
                <form action="{{ route('leader.tasks.index') }}" method="GET" class="d-flex border">
                    <input type="text"  name="search"  value="{{ request('search') }}" placeholder="Search tasks by title..."
                        class="form-control bg-gray-700 border-gray-600 text-white rounded-l-full px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <button type="submit"
                            class="btn btn-contact rounded-r-full px-5 shadow-lg hover:shadow-xl transition">
                        <i class="fas fa-search"></i>
                    </button>
                    @if(request()->has('search'))
                        <a href="{{ route('leader.tasks.index', request()->except('search')) }}"
                        class="btn btn-outline-danger ms-2 px-3">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </form>
            </div>
        </div>

        {{-- Indicateur du filtre actif (sous la barre) --}}
        @if(request()->hasAny(['projectId', 'status', 'search']))
            <div class="mt-4 pt-3 border-top border-gray-700 text-center">
                <small class="text-gray-400">
                    <i class="fas fa-filter text-primary me-1"></i>
                    Active filters:
                    @if(request('projectId'))
                        <span class="badge bg-indigo-600 text-white mx-1">
                            Project: {{ $projects->find(request('projectId'))?->name }}
                        </span>
                    @endif
                    @if(request('status'))
                        <span class="badge bg-{{ $statuses[request('status')]['color'] ?? 'secondary' }} text-white mx-1">
                            {{ $statuses[request('status')]['label'] ?? ucfirst(request('status')) }}
                        </span>
                    @endif
                    @if(request('search'))
                        <span class="badge bg-primary text-white mx-1">
                            Search: "{{ request('search') }}"
                        </span>
                    @endif
                    <a href="{{ route('leader.tasks.index') }}" class="text-danger ms-3 small hover:underline">
                        Clear all filters
                    </a>
                </small>
            </div>
        @endif
    </div>
    <hr> <br>

    <!-- Pas de projets -->
    @if(!$hasProjects)
        <div class="text-center py-10">
            <i class="fas fa-folder-open fa-5x text-gray-600 mb-4"></i>
            <h3 class="text-gray-400 mb-3">No projects yet</h3>
            <p class="text-gray-500 mb-5">Create a project to start managing tasks</p>
            <a href="{{ route('leader.projects.create') }}" class="btn btn-primary btn-lg shadow">
                <i class="fas fa-plus me-2"></i> Create First Project
            </a>
        </div>
    @elseif(!$hasTasks)
        <!-- Pas de tâches -->
        <div class="text-center py-10">
            <i class="fas fa-clipboard-list fa-5x text-gray-600 mb-4"></i>
            <h3 class="text-gray-400 mb-3">No tasks found</h3>
            <p class="text-gray-500 mb-5">Create your first task in this project</p>
            <a href="{{ route('leader.tasks.create') }}" class="btn btn-primary btn-lg shadow">
                <i class="fas fa-plus me-2"></i> Add First Task
            </a>
        </div>
    @else
        <!-- Liste des tâches en cartes -->
        <div class="row g-4">
            @foreach($tasks as $task)
                <div class="col-lg-6 col-xl-4">
                    <div class="card bg-gray-800 text-white shadow-lg border-0 rounded-xl h-100  overflow-hidden hover:shadow-2xl hover:border-blue-500 transition-all">
                        <!-- Icône Pin si épinglée ::  En haut à droite de la carte -->
                        @if($task->pinned)
                            <div class="position-absolute top-0 end-0 p-3">
                                <i class="fas fa-thumbtack text-primary fs-4" title="Pinned task"></i>
                            </div>
                        @endif
                        <div class="card-body   p-5 d-flex flex-column ">
                           <!-- project Badge -->
                            @if($task->project)
                                <span class="badge bg-info text-dark small">
                                    {{ Str::limit($task->project->name, 30) }}
                                </span>
                            @endif
                            <br>
                            <!-- Titre + Status -->
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="fw-bold mb-0">
                                    <a href="{{ route('leader.tasks.show', $task) }}" class="text-white hover:text-blue-400 transition">
                                        {{ $task->title }}
                                    </a>
                                </h5>
                                <span class="badge {{ $task->status == 'completed' ? 'bg-success' : ($task->status == 'in_progress' ? 'bg-warning' : 'bg-secondary') }} text-uppercase fs-6">
                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                </span>

                            </div>

                            <!-- Barre de progression hybride intelligente v3-->
                            <div class="mb-4">
                                @php
                                    // 1. Cas avec subtasks → priorité à la complétion des subtasks
                                    if ($task->subtasks->count() > 0) {
                                        $completedSubtasks = $task->subtasks->where('status', 'completed')->count();
                                        $totalSubtasks = $task->subtasks->count();
                                        $progress = round(($completedSubtasks / $totalSubtasks) * 100);
                                        $progressText = "$completedSubtasks / $totalSubtasks subtasks";
                                        $progressType = 'subtasks';
                                    }
                                    // 2. Cas sans subtasks → progression basée sur status + temps écoulé
                                    else {
                                        $progressType = 'status_time';

                                        // Progression selon status
                                        $statusProgress = match($task->status) {
                                            'completed' => 100,
                                            'in_progress' => 50,
                                            default => 0, // todo / pending
                                        };

                                        // Progression temporelle (si start_at et due_date définis)
                                        $timeProgress = 0;
                                        if ($task->start_at && $task->due_date) {
                                            $now = now();
                                            $start = $task->start_at;
                                            $end = $task->due_date;

                                            if ($now->lt($start)) {
                                                $timeProgress = 0;
                                            } elseif ($now->gt($end)) {
                                                $timeProgress = 100;
                                            } else {
                                                $totalDuration = $start->diffInSeconds($end);
                                                $elapsed = $start->diffInSeconds($now);
                                                $timeProgress = round(($elapsed / $totalDuration) * 100);
                                            }
                                        }

                                        // Moyenne pondérée : 70% status + 30% temps (ou 100% status si pas de dates)
                                        $progress = $task->start_at && $task->due_date
                                            ? round(0.7 * $statusProgress + 0.3 * $timeProgress)
                                            : $statusProgress;

                                        $progressText = $task->start_at && $task->due_date
                                            ? "Status + Time ({$timeProgress}% elapsed-deppased)"
                                            : ucfirst(str_replace('_', ' ', $task->status));
                                    }

                                    // Couleur de la barre
                                    $barColor = match(true) {
                                        $progress == 100 => 'bg-success',
                                        $progress >= 70 => 'bg-info',
                                        $progress >= 40 => 'bg-warning',
                                        default => 'bg-danger',
                                    };
                                @endphp

                                <!-- Texte descriptif -->
                                <div class="d-flex justify-content-between small text-gray-400 mb-1">
                                    <span>Progress</span>
                                    <span>{{ $progress }}% • {{ $progressText }}</span>
                                </div>

                                <!-- Barre -->
                                <div class="progress bg-gray-700 rounded" style="height: 12px;">
                                    <div class="progress-bar {{ $barColor }} rounded"
                                        role="progressbar"
                                        style="width: {{ $progress }}%"
                                        aria-valuenow="{{ $progress }}"
                                        aria-valuemin="0"
                                        aria-valuemax="100">
                                    </div>
                                </div>

                                <!-- Indicateur visuel supplémentaire -->
                                <div class="mt-2 text-end">
                                    @if($progress == 100)
                                        <span class="text-success fw-bold"><i class="fas fa-check-circle me-1"></i> Completed</span>
                                    @elseif($task->status == 'completed')
                                        <span class="text-success fw-bold"><i class="fas fa-check-circle me-1"></i> Done</span>
                                    @elseif($progress >= 80)
                                        <span class="text-info"><i class="fas fa-fire me-1"></i> Almost there!</span>
                                    @elseif($progress < 30 && $task->due_date && now()->gt($task->due_date->subDays(2)))
                                        <span class="text-danger fw-bold"><i class="fas fa-exclamation-triangle me-1"></i> At risk</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Description -->
                            <p class="text-gray-300 flex-grow-1 mb-4">
                                {{ $task->description ? Str::limit($task->description, 100) : 'No description' }}
                            </p>


                            <!-- Infos -->
                            <div class="small text-gray-400 mb-4">
                                <div class="d-flex justify-content-between"><strong>Project :</strong> {{ $task->project->name }}</div>
                                <div class="d-flex justify-content-between mt-1" ><strong><i class="fas fa-user text-primary me-2"></i>Assigned to :</strong> {{ $task->assignedTo?->name ?? 'Not assigned' }}</div>
                                <div class="d-flex justify-content-between mt-1" >
                                    <strong>  <i class="fas fa-paperclip  text-{{ $task->attachments->count() > 0 ? 'success' : 'primary' }} me-1"></i> Attachement(s) : </strong>
                                        {{ $task->attachments->count() }} file{{ $task->attachments->count() != 1 ? 's' : '' }}
                                </div>
                                <div class="d-flex justify-content-between mt-1"><strong>Deadline :</strong> {{ $task->start_at ? $task->start_at ->format('d M Y') : 'No deadline' }} → {{ $task->due_date ? $task->due_date->format('d M Y') : 'No deadline' }}</div>
                                <div class="d-flex justify-content-between mt-1"><strong>Points :</strong> {{ $task->points }}</div>
                                <div class="d-flex justify-content-between mt-1"><strong>Difficulty :</strong> {{ ucfirst($task->difficulty) }} </div>

                            </div>



                            <!-- Spacer <div class="flex-grow-1"></div>-->

                             <!--Temps restant / Overdue Dans la carte ou détail -->
                            <!-- Sous-section: Badge Overdue pour Task (maintenant affiché !) -->
                            <!-- Badge wrapper FIX -->
                            <div class="mt-auto pt-3 text-end">
                                <x-task-deadline-badge :task="$task" />
                            </div>

                            <!-- Subtasks aperçu -->
                            @if($task->subtasks->count() > 0)
                                <div class="border-top border-gray-700 pt-3 mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <small class="text-warning fw-semibold">Subtasks ({{ $task->subtasks->count() }})</small>
                                        <small class="text-gray-500">
                                            {{ $task->subtasks->where('status', 'completed')->count() }}/{{ $task->subtasks->count() }} completed
                                        </small>
                                    </div>
                                    <div class="small">
                                        @foreach($task->subtasks->take(3) as $subtask)
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <i class="fas fa-circle {{ $subtask->status == 'completed' ? 'text-success' : 'text-warning' }} fs-6"></i>
                                                <span class="flex-grow-1">{{ Str::limit($subtask->title, 30) }}</span>
                                                <span class=" badge bg-gray-800 ms-auto">Priority : {{ $subtask->priority }}</span>
                                            </div>
                                        @endforeach
                                        @if($task->subtasks->count() > 3)
                                            <small class="text-gray-500 d-block text-end">
                                                <a href="{{ route('leader.tasks.show', $task) }}" class="text-info">
                                                    ... +{{ $task->subtasks->count() - 3 }} more
                                                </a>
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Actions + Bouton Pin/Unpin-->
                            <div class="d-flex gap-2 mt-auto">
                                <a href="{{ route('leader.tasks.show', $task) }}" class="btn btn-sm btn-outline-info flex-fill">Details</a>
                                <a href="{{ route('leader.tasks.edit', $task) }}" class="btn btn-sm btn-outline-warning flex-fill">Edit</a>

                                <!-- Bouton Pin/Unpin :: Bouton Pin dans les actions -->
                                <form action="{{ route('leader.tasks.pin', $task) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $task->pinned ? 'btn-primary' : 'btn-outline-primary' }} flex-fill">
                                        <i class="fas fa-thumbtack me-1"></i>
                                        {{ $task->pinned ? 'Unpin' : 'Pin' }}
                                    </button>
                                </form>

                                <form action="{{ route('leader.tasks.destroy', $task) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger flex-fill"
                                            onclick="return confirm('Delete this task and all subtasks?')">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach


        </div>

        <!-- Pagination  <div class="d-flex justify-content-center mt-5">
            { { $tasks->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}
        </div>-->
        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-5">
            {{ $tasks->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection
