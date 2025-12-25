@extends('layouts.appW')


@section('contentW')
<div class="container py-5">
    <!-- Titre + Bouton Add Task -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4 mb-5">
        <div>
            <h1 class="display-5 fw-bold text-white mb-1">Tasks</h1>
            <p class="text-gray-400 mb-0">Manage and track all your team tasks</p>
        </div>
        <a href="{{ route('leader.tasks.create') }}" class="btn btn-primary btn-lg shadow">
            <i class="fas fa-plus me-2"></i> Add Task
        </a>
    </div>

    <!-- Filtre par projet -->
    <div class="d-flex flex-wrap gap-3 justify-content-center mb-5">

        <!-- All Tasks -->
        <a href="{{ route('leader.tasks.index') }}"
           class="px-5 py-2 rounded-full border transition-all {{ request()->filled(['projectId', 'status']) ? 'border-gray-500 text-gray-400 hover:bg-gray-700 hover:text-white' : 'bg-gradient-to-r from-blue-600 to-purple-600 text-white border-transparent shadow-lg' }}">
            All Tasks
        </a>

        <!-- Filtre par Projet -->
        @foreach($projects as $project)
            <a href="{{ route('leader.tasks.index', array_merge(request()->query(), ['projectId' => $project->id, 'status' => null])) }}"
               class="px-5 py-2 rounded-full border transition-all {{ request()->query('projectId') == $project->id && !request()->has('status') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white border-transparent shadow-lg' : 'border-blue-500 text-blue-400 hover:bg-blue-600 hover:text-white hover:shadow' }}">
                {{ $project->name }}
            </a>
        @endforeach
        <!-- Séparateur -->
        <span class="text-gray-500 align-self-center px-3">|</span>

        <!-- Filtre par Statut -->
        <a href="{{ route('leader.tasks.index', array_merge(request()->query(), ['status' => 'todo', 'projectId' => null])) }}"
           class="px-5 py-2 rounded-full border transition-all {{ request()->query('status') == 'todo' ? 'bg-secondary text-white border-transparent shadow-lg' : 'border-gray-500 text-gray-400 hover:bg-secondary hover:text-white' }}">
            To Do
        </a>

        <a href="{{ route('leader.tasks.index', array_merge(request()->query(), ['status' => 'in_progress', 'projectId' => null])) }}"
           class="px-5 py-2 rounded-full border transition-all {{ request()->query('status') == 'in_progress' ? 'bg-warning text-white border-transparent shadow-lg' : 'border-warning text-yellow-400 hover:bg-warning hover:text-white' }}">
            In Progress
        </a>

        <a href="{{ route('leader.tasks.index', array_merge(request()->query(), ['status' => 'completed', 'projectId' => null])) }}"
           class="px-5 py-2 rounded-full border transition-all {{ request()->query('status') == 'completed' ? 'bg-success text-white border-transparent shadow-lg' : 'border-success text-green-400 hover:bg-success hover:text-white' }}">
            Completed
        </a>
    </div>

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
                    <div class="card bg-gray-800 text-white shadow-lg border-0 rounded-xl h-100 hover:shadow-2xl hover:border-blue-500 transition-all">
                        <div class="card-body d-flex flex-column">
                            <!-- Titre + Status -->
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="fw-bold mb-0">
                                    <a href="{{ route('leader.tasks.show', $task) }}" class="text-white hover:text-blue-400 transition">
                                        {{ $task->title }}
                                    </a>
                                </h5>
                                <span class="badge {{ $task->status == 'completed' ? 'bg-success' : ($task->status == 'in_progress' ? 'bg-warning' : 'bg-secondary') }} fs-6">
                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                </span>
                            </div>

                            <!-- Description -->
                            <p class="text-gray-300 flex-grow-1 mb-3">
                                {{ $task->description ? Str::limit($task->description, 100) : 'No description' }}
                            </p>

                            <!-- Infos -->
                            <div class="small text-gray-400 mb-4">
                                <div><strong>Project:</strong> {{ $task->project->name }}</div>
                                <div><strong>Assigned to:</strong> {{ $task->assignedTo?->name ?? 'Not assigned' }}</div>
                                <div><strong>Due:</strong> {{ $task->due_date ? $task->due_date->format('d M Y') : 'No deadline' }}</div>
                            </div>

                            <!-- Actions -->
                            <div class="d-flex gap-2 mt-auto">
                                <a href="{{ route('leader.tasks.show', $task) }}" class="btn btn-sm btn-outline-info flex-fill">View</a>
                                <a href="{{ route('leader.tasks.edit', $task) }}" class="btn btn-sm btn-outline-warning flex-fill">Edit</a>
                                <form action="{{ route('leader.tasks.destroy', $task) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger flex-fill"
                                            onclick="return confirm('Delete this task?')">
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
