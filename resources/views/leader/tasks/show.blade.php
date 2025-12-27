@extends('layouts.appW')

@section('contentW')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12">
                <!-- Header -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4 mb-5">
                    <a href="{{ route('leader.tasks.index') }}" class="btn btn-outline-light">
                        ← Back to Tasks
                    </a>
                    <div class="d-flex gap-3">
                        <a href="{{ route('leader.tasks.edit', $task) }}" class="btn btn-warning shadow">Edit Task</a>
                        <form action="{{ route('leader.tasks.destroy', $task) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger shadow" onclick="return confirm('Delete this task and all subtasks?')">
                                Delete Task
                            </button>
                        </form>
                    </div>
                </div>
                <!-- Carte principale -->
                <div class="card bg-gray-800 text-white shadow-2xl border-0 rounded-xl ">
                    <div class="card-body  p-5">
                        <!-- Main Task Info -->
                        <h1 class="display-6 fw-bold mb-3">{{ $task->title }}</h1>
                        <p class="text-gray-300 fs-5 mb-5">{{ $task->description ?? 'No description' }}</p>

                        <!-- Subtasks -->
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h4 class="fw-bold mb-4 text-warning fw-semibold">Subtasks ({{ $task->subtasks->count() }})</h4>
                            <small class="text-gray-500">
                                {{ $task->subtasks->where('status', 'completed')->count() }}/{{ $task->subtasks->count() }} completed
                            </small>
                        </div>
                        @if($task->subtasks->count() > 0)
                            <div class="row g-4 mb-5">
                                @foreach($task->subtasks as $subtask)
                                    <div class="col-md-6 col-lg-6">
                                        <div class="card bg-gray-700 border-0 shadow-sm h-100">
                                            <div class="card-body d-flex flex-column">
                                                <!-- Title + Status -->
                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    <h6 class="fw-bold">{{ $subtask->title }}</h6>
                                                    <span class="badge fs-6 px-3 {{ $subtask->status == 'completed' ? 'bg-success' : ($subtask->status == 'in_progress' ? 'bg-warning' : 'bg-secondary') }}">
                                                        {{ ucfirst(str_replace('_', ' ', $subtask->status)) }}
                                                    </span>
                                                </div>
                                                <!-- Subtask Details -->
                                                <small class="text-gray-400 mt-auto">
                                                    <div><strong>Assigned:</strong> {{ $subtask->assignedTo?->name ?? 'Not assigned' }}</div>
                                                    <div><strong>Priority:</strong> {{ $subtask->priority }}</div>
                                                    <div><strong>Started at:</strong> {{ $subtask->started_at ? $subtask->started_at->format('d M Y, H:i') : '-' }}</div>
                                                    <div><strong>Deadline:</strong> {{ $subtask->due_date ? $subtask->due_date->format('d M Y, H:i') : 'No deadline' }}</div>
                                                    @if($subtask->completed_at)
                                                        <div><strong>Completed:</strong> {{ $subtask->completed_at->format('d M Y, H:i') }}</div>
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5 bg-gray-500 rounded">
                                <i class="fas fa-tasks fa-3x text-gray-600 mb-3"></i>
                                <p class="text-gray-400 mb-0">No subtasks added yet.</p>
                            </div>
                        @endif

                        <!-- Task Details Sidebar -->
                        <div class="mt-5 row">
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
                                        <dd class="col-sm-8">{{ $task->start_at ? $task->start_at->format('d M Y , h:m ') : 'Not set' }}</dd>

                                        <dt class="col-sm-4 text-gray-400">Deadline</dt>
                                        <dd class="col-sm-8">{{ $task->due_date ? $task->due_date->format('d M Y , h:m ') : 'Not set' }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <br>
                        <!-- Boutons en bas (répétés pour mobile) -->
                        <div class="d-flex flex-column d-md-none gap-3 mt-5">
                            <a href="{{ route('leader.tasks.edit', $task) }}" class="btn btn-warning">Edit Task</a>
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
