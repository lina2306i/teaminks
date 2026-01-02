@extends('layouts.appW')

@php
    /**
     * ===============================
     * GLOBAL DATE HELPERS (CLEAN)
     * ===============================
     */
    function dateBadge($dueDate) {
        if (!$dueDate) return null;

        $daysLeft = now()->diffInDays($dueDate, false);

        return [
            'daysLeft'   => $daysLeft,
            'isOverdue'  => $daysLeft < 0,
            'isToday'    => $daysLeft === 0,
            'isTomorrow' => $daysLeft === 1,
            'isSoon'     => $daysLeft > 1 && $daysLeft <= 2,
        ];
    }
@endphp


@section('contentW')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12">

                <!-- Header ::Back button, title, and action buttons -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4 mb-5">
                    <a href="{{ route('leader.tasks.index') }}" class="btn btn-outline-light">
                        ← Back to Tasks
                    </a>
                    <h1 class="display-6 fw-bold text-white mb-0">
                        Task : {{ $task->title }}
                    </h1>
                    <div class="d-flex gap-3">
                        <a href="{{ route('leader.tasks.edit', $task) }}" class="btn btn-warning shadow">Edit Task</a>
                        <form action="{{ route('leader.tasks.pin', $task) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn {{ $task->pinned ? 'btn-primary shadow' : 'btn-outline-primary shadow' }}">
                                <i class="fas fa-thumbtack me-2"></i>
                                {{ $task->pinned ? 'Unpin' : 'Pin to top' }}
                            </button>
                        </form>
                        <form action="{{ route('leader.tasks.destroy', $task) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger shadow" onclick="return confirm('Delete this task and all subtasks?')">
                                Delete Task
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Carte principale:: Contient toutes les infos de la tâche -->
                <div class="card bg-gray-800 text-white shadow-2xl border-0 rounded-xl ">
                    <div class="card-body  p-5">

                        <!-- Sous-section: Titre + Difficulty + Points -->
                        <!-- Main Task Info :: Titre + Difficulty + Points -->
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                            <h2 class="display-8 h4 fw-semibold mb-0 fw-bold mb-3">
                                {{ $task->title }}
                                <span class="ms-2 text-sm fw-normal
                                    {{ $task->difficulty == 'easy' ? 'text-success' :
                                    ($task->difficulty == 'medium' ? 'text-warning' : 'text-danger') }}">
                                    {{ ucfirst($task->difficulty) }}
                                </span>
                            </h2>
                            <span class="text-gray-400"> <i class="fas fa-flag text-gray-500"></i>
                                {{ $task->points }} points</span>
                        </div>

                        <!-- Sous-section: Barre de progression -->
                        <!-- Barre de progression hybride intelligente v3-->
                        <div class="mb-4">
                            @php
                                // 1. Cas avec subtasks → priorité à la complétion des subtasks
                                if ($task->subtasks->count() > 0) {
                                    $completedSubtasks = $task->subtasks->where('status', 'completed')->count();
                                    $totalSubtasks = $task->subtasks->count();
                                    $progress = round(($completedSubtasks / $totalSubtasks) * 100);
                                    $progressText = "$completedSubtasks / $totalSubtasks subtasks completed";
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
                                    /*
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
                                        ? "Status + Time ({$timeProgress}% elapsed)"
                                        : ucfirst(str_replace('_', ' ', $task->status));*/

                                    if ($task->start_at && $task->due_date) {
                                        $totalTime = $task->start_at->diffInSeconds($task->due_date);
                                        $elapsed = $task->start_at->diffInSeconds(now());
                                        $timeProgress = min(100, round(($elapsed / $totalTime) * 100));
                                        $progress = round(0.7 * $statusProgress + 0.3 * $timeProgress);
                                    } else {
                                        $progress = $statusProgress;
                                    }

                                    $progressText = ucfirst(str_replace('_',' ',$task->status));
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

                        <!-- Sous-section: Description -->
                        <p class="text-gray-400 px-3 fs-5 mb-5">{{ $task->description ?? 'No description' }}</p>

                        <!-- SECTION: Subtasks -->
                        <!-- Subtasks |mb-5 | mb-4| row g-4 mb-5 | col-md-6 col-lg-6 | card-body d-flex flex-column-->
                        <div class="mb-5">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="h4 fw-bold mb-4 text-warning fw-semibold">
                                    Subtasks ({{ $task->subtasks->count() }})
                                </h4>
                                <small class="text-gray-400 fw-medium">
                                    <i class="fas fa-check text-success me-1"></i>
                                    {{ $task->subtasks->where('status', 'completed')->count() }}/{{ $task->subtasks->count() }} completed
                                </small>
                            </div>
                            @if($task->subtasks->count() > 0)
                                <div class="row g-4 ">
                                    @foreach($task->subtasks as $subtask)

                                        <div class="col-12 col-lg-6">
                                        <!--div class="col-md-6 col-lg-6"-->
                                            <div class="card bg-gray-700 border-0 hover-shadow transition-all flex-column overflow-hidden shadow-sm h-100">
                                                <div class="card-body d-flex flex-column ">
                                                    <!-- Status Icon + Title + Status Badge -->
                                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                                        @if($subtask->status === 'completed' ?? false)
                                                            <i class="fas fa-check-circle text-success fs-5"></i>
                                                        @else
                                                            <i class="far fa-circle text-gray-500 fs-5"></i>
                                                        @endif
                                                        <!--i class="fas { { $subtask->status === 'completed' ? 'fa-check-circle text-success' : 'fa-circle text-gray-500' }}"></!--i-->
                                                        {{-- Title & Status --}}
                                                        <div class="flex-grow-1 ms-3">
                                                            <!--span class="text-gray-300"-->
                                                            <h6 class=" fw-bold mb-1">{{ $subtask->title  ?? 'Untitled subtask' }}</h6>
                                                            <span class="badge fs-6 rounded-pill px-3 py-2
                                                                {{ $subtask->status == 'completed' ? 'bg-success' :
                                                                    ($subtask->status == 'in_progress' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                                                <i class="fas fa-circle-small me-1"></i>
                                                                {{ ucfirst(str_replace('_', ' ', $subtask->status)) }}
                                                            </span>
                                                            <!-- /span-->
                                                        </div>
                                                    </div>
                                                    <hr class="border-gray-600 my-3">
                                                    <!-- Subtask Details  :: Infos assigné, priorité, dates -->
                                                    <small class="text-gray-400 space-y-2 transition-all">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <i class="fas fa-user text-gray-500"></i>
                                                            <strong>Assigned:</strong> {{ $subtask->assignedTo?->name ?? 'Not assigned' }}
                                                        </div>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <i class="fas fa-flag text-gray-500"></i>
                                                            <strong>Priority:</strong> {{ $subtask->priority }}
                                                        </div>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <i class="fas fa-play text-gray-500"></i>
                                                            <strong>Started at:</strong> {{ $subtask->started_at ? $subtask->started_at->format(' H:i - d M Y') : 'Not set' }}
                                                        </div>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <i class="fas fa-stopwatch text-gray-500"></i>
                                                            <strong>Deadline:</strong> {{ $subtask->due_date ? $subtask->due_date->format('H:i - d M Y') : 'No deadline' }}
                                                        </div>
                                                        @if($subtask->completed_at)
                                                            <div class="d-flex align-items-center gap-2 text-success">
                                                                <i class="fas fa-trophy"></i>
                                                                <strong>Completed:</strong>
                                                                    <span>{{ $subtask->completed_at->format('d M Y - H:i') }}</span>
                                                            </div>
                                                        @endif
                                                    </small>


                                                    <!--Badge Overdue / Due Soon / Days left For subtasks || Temps restant / Overdue Dans la carte ou détail -->
                                                    {{-- ============================= --}}
                                                    {{-- SUBTASK CARD (CLEAN & FIXED) --}}
                                                    {{-- ============================= --}}
                                                    {{-- Deadline badge --}}
                                                        <x-subtask-deadline-badge :subtask="$subtask" />


                                                </div>

                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-5 bg-gray-500 rounded">
                                    <i class="fas fa-tasks fa-3x text-gray-600 mb-3"></i>
                                    <h5 class="text-gray-400 fw-medium">No subtasks added yet</h5>
                                    <p class="text-gray-500 small mt-2">Add subtasks to break down this task into smaller steps.</p>
                                </div>
                            @endif
                        </div>

                        <!-- SECTION: Task Details -->
                        <!-- Task Details Sidebar :: Liste des détails de la tâche principale-->
                        <div class="mt-5 ">
                            <h5 class="fw-bold mb-4 text-info">Task Details</h5>
                            <div class="card bg-gray-700 border-0 shadow">
                                <div class="card-body">
                                    <dl class="row small mb-0 ">
                                        <dt class="col-sm-4 text-gray-400">Project</dt>
                                        <dd class="col-sm-8">{{ $task->project->name }}</dd>

                                        <dt class="col-sm-4 text-gray-400">Assigned to</dt>
                                        <dd class="col-sm-8">{{ $task->assignedTo?->name ?? 'Not assigned' }}</dd>

                                        <dt class="col-sm-4 text-gray-400">Status</dt>
                                        <dd class="col-sm-8">
                                            <span class="badge {{ $task->status == 'completed' ? 'bg-success' : ($task->status == 'in_progress' ? 'bg-warning' : 'bg-secondary') }}">
                                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                            </span>
                                        </dd>

                                        <dt class="col-sm-4 text-gray-400">Difficulty</dt>
                                        <dd class="col-sm-8">{{ ucfirst($task->difficulty) }}</dd>

                                        <dt class="col-sm-4 text-gray-400">Points</dt>
                                        <dd class="col-sm-8">{{ $task->points }}</dd>

                                        <dt class="col-sm-4 text-gray-400">Start Date</dt>
                                        <dd class="col-sm-8">{{ $task->start_at ? $task->start_at->format('H:i - d M Y') : 'Not set' }}</dd>

                                        <dt class="col-sm-4 text-gray-400">Deadline</dt>
                                        <dd class="col-sm-8">{{ $task->due_date ? $task->due_date->format('H:i - d M Y') : 'Not deadlina' }}</dd>

                                        <dt class="col-sm-4 text-gray-400">Pinned</dt>
                                        <dd class="col-sm-8">
                                            @if($task->pinned)
                                                <span class="badge bg-primary">
                                                    <i class="fas fa-thumbtack me-1"></i> Pinned
                                                </span>
                                            @else
                                                <span class="text-gray-500">Not pinned</span>
                                            @endif
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>

                        <!-- Sous-section: Badge Overdue pour Task (maintenant affiché !) -->
                        {{-- ============================= --}}
                        {{-- TASK DEADLINE BADGE (FIXED) <div class="text-end mb-4"></div>--}}
                        {{-- ============================= --}}
                        <div class="flex-grow-1"><br></div>

                        <x-task-deadline-badge :task="$task" />

                        <!-- SECTION: Assigned To -->
                        <!-- Assigned To: Section assignation avec photo et notify -->
                        <div class="mb-4">
                            <h3 class="h5 fw-semibold mb-4 ">
                                Assigned To :
                                <span class="ms-2
                                    {{ $task->status == 'completed' ? 'text-success' :
                                    ($task->status == 'in_progress' ? 'text-info' : 'text-warning') }}">
                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                </span>
                            </h3>

                            <div class="d-flex justify-content-between align-items-center p-3 bg-gray-700 rounded-lg border border-gray-600">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $task->assignedTo?->profile ?? asset('images/user-default.jpg') }}"
                                        alt="Profile"
                                        class="rounded-circle"
                                        width="40"
                                        height="40">
                                    <div>
                                        <h4 class="mb-0 text-white">
                                            {{ $task->assignedTo?->name ?? 'Not assigned' }}
                                        </h4>
                                        <p class="text-gray-400 text-sm mb-0">
                                            {{ $task->assignedTo?->position ?? '' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Bouton Notify: Peut être transformé en modal -->
                                @if($task->assigned_to)
                                    <button class="btn btn-outline-info btn-sm" onclick="alert('Notify feature coming soon!')">
                                        notify
                                    </button>
                                @endif
                            </div>
                        </div>

                        @if($task->attachments_count > 0)
                            <div class="card bg-gray-800 text-white border-0 shadow mt-4">
                                <div class="card-header bg-dark fw-bold">
                                    <i class="fas fa-paperclip me-2"></i> Attachments ({{ $task->attachments_count }})
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        @foreach($task->attachments as $attachment)
                                            <div class="col-md-6 col-lg-4">
                                                <div class="d-flex align-items-center bg-gray-700 p-3 rounded">
                                                    <i class="fas fa-file me-3 text-primary fa-2x"></i>
                                                    <div class="flex-grow-1">
                                                        <div class="fw-bold small">{{ $attachment->filename }}</div>
                                                        <small class="text-gray-400">
                                                            {{ round($attachment->size / 1024, 1) }} KB
                                                            • {{ $attachment->created_at->format('d/m/Y H:i') }}
                                                        </small>
                                                    </div>
                                                    <a href="{{ $attachment->url }}" target="_blank" class="btn btn-sm btn-outline-info ms-2">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                        <br>
                        <!-- Boutons en bas (répétés pour mobile) -->
                        <div class="d-flex flex-column d-md-none gap-3 mt-5">
                            <a href="{{ route('leader.tasks.edit', $task) }}" class="btn btn-warning">Edit Task</a>
                            <form action="{{ route('leader.tasks.pin', $task) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn {{ $task->pinned ? 'btn-primary' : 'btn-outline-primary' }}">
                                    <i class="fas fa-thumbtack me-2"></i>
                                    {{ $task->pinned ? 'Unpin' : 'Pin to top' }}
                                </button>
                            </form>
                            <form action="{{ route('leader.tasks.destroy', $task) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this task?')">Delete Task</button>
                            </form>
                            <a href="{{ route('leader.tasks.index') }}" class="btn btn-outline-light ms-auto">← Back to Tasks</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



@push('style')
    <style>
        .hover-shadow {
            transition: all 0.3s ease;
        }
        .hover-shadow:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 15px rgba(0,0,0,0.2);
            /*transform: translateY(-4px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);*/
         }

        .transition-all {
            transition: all 0.3s ease;
        }
        .space-y-2 > * + * {
            margin-top: 0.5rem;
        }

        .badge {
            font-size: 0.85rem;
        }
        @media (max-width: 767px) {
            .card-body {
                padding: 1.5rem !important;
            }
            h4, h5 {
                font-size: 1.25rem !important;
            }
        }
    </style>
@endpush
