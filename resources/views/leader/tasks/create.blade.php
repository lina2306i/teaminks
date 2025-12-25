@extends('layouts.appW')


@section('contentW')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-11">
            <!-- Header -->
            <div class="d-flex align-items-center mb-5">
                <a href="{{ route('leader.tasks.index') }}" class="btn btn-outline-light me-4">
                    ← Back
                </a>
                <h1 class="display-6 fw-bold text-white mb-0">Create Task</h1>
            </div>

            <div class="card bg-gray-800 text-white shadow-2xl border-0 rounded-xl">
                <div class="card-body p-5 p-md-6">
                    <form action="{{ route('leader.tasks.store') }}" method="POST">
                        @csrf

                        <div class="row g-5">
                            <!-- Colonne gauche -->
                            <div class="col-lg-8">
                                <!-- Task Title -->
                                <div class="mb-4">
                                    <label for="title" class="form-label fw-semibold fs-5">Task Title</label>
                                    <input type="text"
                                           name="title"
                                           id="title"
                                           class="form-control form-control-lg bg-gray-700 border-gray-600 text-white"
                                           placeholder="Enter task title"
                                           value="{{ old('title') }}"
                                           required>
                                    @error('title')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Sub-Tasks -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold fs-5 d-flex justify-content-between align-items-center">
                                        Sub-Tasks:
                                        <button type="button" class="btn btn-primary btn-sm rounded-circle" onclick="addSubTask()">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </label>
                                    <div id="subtasks-container">
                                        <!-- Sub-task dynamique ajoutée via JS -->
                                        <div class="input-group mb-2 subtask-item">
                                            <input type="text"
                                                   name="subtasks[]"
                                                   class="form-control bg-gray-700 border-gray-600 text-white"
                                                   placeholder="Sub-task name">
                                            <button type="button" class="btn btn-outline-danger" onclick="removeSubTask(this)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="mb-4">
                                    <label for="description" class="form-label fw-semibold fs-5">Task description</label>
                                    <textarea name="description"
                                              id="description"
                                              rows="6"
                                              class="form-control bg-gray-700 border-gray-600 text-white"
                                              placeholder="Describe the task in detail...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Status -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Status</label>
                                    <select name="status" class="form-select bg-gray-700 border-gray-600 text-white">
                                        <option value="todo">To Do</option>
                                        <option value="in_progress">In Progress</option>
                                        <option value="completed">Completed</option>
                                    </select>
                                </div>
                            </div>


                            <!-- Colonne droite -->
                            <div class="col-lg-4">
                                <!-- Project -->
                                <div class="mb-4">
                                    <label for="project_id" class="form-label fw-semibold">Project:</label>
                                    <select name="project_id" id="project_id" class="form-select bg-gray-700 border-gray-600 text-white" required>
                                        <option value="">Select a project</option>
                                        @foreach(Auth::user()->projects as $project)
                                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                                {{ $project->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('project_id')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row g-3">
                                    <!-- Start at <div class="col-md-6"> -->
                                    <label for="start_at" class="form-label fw-semibold fs-5">Start at</label>
                                    <input type="datetime-local"
                                        name="start_at"
                                        id="start_at"
                                        class="form-control bg-gray-700 border-gray-600 text-white"
                                        value="{{ old('start_at') }}">
                                    @error('start_at')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row g-3">
                                    <!-- Deadline  <div class="col-md-6"> -->
                                    <label for="due_date" class="form-label fw-semibold fs-5">Deadline</label>
                                    <input type="datetime-local"
                                        name="due_date"
                                        id="due_date"
                                        class="form-control bg-gray-700 border-gray-600 text-white"
                                        value="{{ old('due_date') }}">
                                    @error('due_date')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row g-3 mt-3">
                                    <!-- Assign to -->
                                    <div class="col-md-6">
                                        <label for="assigned_to" class="form-label fw-semibold">Assign to</label>
                                        <select name="assigned_to" id="assigned_to" class="form-select bg-gray-700 border-gray-600 text-white">
                                            <option value="">Not assigned</option>
                                            @foreach($teamMembers as $member)
                                                <option value="{{ $member->id }}" {{ old('assigned_to') == $member->id ? 'selected' : '' }}>
                                                    {{ $member->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Difficulty -->
                                    <div class="col-md-6">
                                        <label for="difficulty" class="form-label fw-semibold">Difficulty</label>
                                        <select name="difficulty" id="difficulty" class="form-select bg-gray-700 border-gray-600 text-white">
                                            <option value="easy" {{ old('difficulty') == 'easy' ? 'selected' : '' }}>Easy</option>
                                            <option value="medium" {{ old('difficulty') == 'medium' ? 'selected' : '' }}>Medium</option>
                                            <option value="hard" {{ old('difficulty') == 'hard' ? 'selected' : '' }}>Hard</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Points -->
                                <div class="mt-4">
                                    <label for="points" class="form-label fw-semibold">Points</label>
                                    <input type="number"
                                           name="points"
                                           id="points"
                                           min="1"
                                           class="form-control bg-gray-700 border-gray-600 text-white"
                                           value="{{ old('points', 1) }}"
                                           required>
                                </div>
                            </div>
                        </div>

                        <!-- Bouton Create -->
                        <div class="text-end mt-5">
                            <button type="submit" class="btn btn-primary btn-lg px-5 shadow">
                                Create
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function addSubTask() {
    const container = document.getElementById('subtasks-container');
    const div = document.createElement('div');
    div.className = 'input-group mb-2 subtask-item';
    div.innerHTML = `
        <input type="text" name="subtasks[]" class="form-control bg-gray-700 border-gray-600 text-white" placeholder="Sub-task name">
        <button type="button" class="btn btn-outline-danger" onclick="removeSubTask(this)">
            <i class="fas fa-times"></i>
        </button>
    `;
    container.appendChild(div);
}

function removeSubTask(button) {
    button.closest('.subtask-item').remove();
}
</script>
@endpush
