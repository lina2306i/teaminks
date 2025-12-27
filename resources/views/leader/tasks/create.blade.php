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

                                <!-- Sub-Tasks --> <!--  <div class="mb-4"> Exemple pour create et edit (même structure) -->
                                <div class="mb-5">
                                    <hr class="border-gray-600 my-5">

                                    <label class="form-label fw-semibold fs-5 d-flex justify-content-between align-items-center mb-3">
                                        Sub-Tasks
                                        <button type="button" class="btn btn-primary btn-sm rounded-circle shadow" onclick="addSubTask()">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </label>

                                    <div id="subtasks-container" class="mt-3">
                                        @php
                                            // Récupère les subtasks existantes (edit) ou old input (create)
                                            // Pour create : old() ou tableau vide
                                            // Pour edit : old() ou $task->subtasks->toArray()  :: $existingSubtasks = old('subtasks', $task->subtasks->toArray());
                                            // $existingSubtasks = old('subtasks')  ? old('subtasks') : ($task->subtasks ?? collect());
                                            $existingSubtasks = old('subtasks') ? old('subtasks')  : (isset($task) ? $task->subtasks->toArray() : []);
                                        @endphp
                                        @if(count($existingSubtasks) === 0)
                                            <div class="text-center py-5">
                                                <p class="text-gray-400 mb-0">No sub-tasks yet. Click the + button to add one.</p>
                                            </div>
                                        @endif

                                        {{-- -@forelse($existingSubtasks as $index => $subtask) --}}
                                        @foreach($existingSubtasks as $index => $subtask)
                                            <div class="card bg-gray-700 mb-3 subtask-item shadow-sm border border-gray-600">
                                                <div class="card-body p-4">
                                                    <div class="row g-3 align-items-end">
                                                        <!-- Title -->
                                                        <div class="ccol-md-6">
                                                            <label class="form-label small text-gray-300 mb-1">Title</label>
                                                            <input type="text"
                                                                name="subtasks[{{ $loop->index }}][title]"
                                                                value="{{ is_array($subtask) ? ($subtask['title'] ?? '') : $subtask->title }}"
                                                                class="form-control bg-gray-600 text-white border-gray-500"
                                                                placeholder="Sub-task title"
                                                                required>
                                                        </div>

                                                        <!-- Status -->
                                                        <div class=" col-md-3">
                                                            <label class="form-label small text-gray-300 mb-1">Status</label>
                                                            <select name="subtasks[{{ $loop->index }}][status]" class="form-select bg-gray-600 text-white border-gray-500">
                                                                <option value="pending" {{ (is_array($subtask) ? ($subtask['status'] ?? 'pending') : $subtask->status) == 'pending' ? 'selected' : '' }}>
                                                                    Pending
                                                                </option>
                                                                <option value="in_progress" {{ (is_array($subtask) ? ($subtask['status'] ?? '') : $subtask->status) == 'in_progress' ? 'selected' : '' }}>
                                                                    In Progress
                                                                </option>
                                                                <option value="completed" {{ (is_array($subtask) ? ($subtask['status'] ?? '') : $subtask->status) == 'completed' ? 'selected' : '' }}>
                                                                    Completed
                                                                </option>
                                                            </select>
                                                        </div>

                                                        <!-- Assigned to -->
                                                        <div class=" col-md-6">
                                                            <label class="form-label small text-gray-300 mb-1">Assign to</label>
                                                            <select name="subtasks[{{ $loop->index }}][assigned_to]" class="form-select bg-gray-600 text-white border-gray-500">
                                                                <option value="">Not assigned</option>
                                                                @foreach($teamMembers as $member)
                                                                    <!--option value="{ { $member->id }}" { { (is_array($subtask) ? $subtask['assigned_to'] ?? '' : $subtask->assigned_to) == $member->id ? 'selected' : '' }}>
                                                                        { { $member->name }}
                                                                    </!--option-->
                                                                    <option value="{{ $member->id }}"
                                                                        {{ (is_array($subtask) ? ($subtask['assigned_to'] ?? '') : ($subtask->assigned_to ?? '')) == $member->id ? 'selected' : '' }}>
                                                                        {{ $member->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <!-- Priority -->
                                                        <div class=" col-md-3">
                                                            <label class="form-label small text-gray-300 mb-1">Priority</label>
                                                            <input type="number"
                                                                name="subtasks[{{ $loop->index }}][priority]"
                                                                min="1" max="5"
                                                                value="{{ is_array($subtask) ? ($subtask['priority'] ?? 3) : $subtask->priority }}"
                                                                class="form-control bg-gray-600 text-white border-gray-500 text-center"
                                                                placeholder="1-5">
                                                        </div>

                                                        <!-- Due Date -->
                                                        <div class="  col-md-6">
                                                            <label class="form-label small text-gray-300 mb-1">Due Date</label>
                                                            <input type="datetime-local"
                                                                name="subtasks[{{ $loop->index }}][due_date]"
                                                                value="{{ is_array($subtask) ? ($subtask['due_date'] ?? '') : ($subtask->due_date?->format('Y-m-d\TH:i') ?? '') }}"
                                                                class="form-control bg-gray-600 text-white border-gray-500">
                                                        </div>

                                                        <!-- Delete Button -->
                                                        <div class=" col-md-2 text-end">
                                                            <button type="button"
                                                                    class="btn btn-outline-danger btn-sm rounded-circle shadow-sm"
                                                                    onclick="removeSubTask(this)"
                                                                    title="Remove sub-task">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        {{-- - @empty
                                            <!-- Aucun subtask → on affiche un champ vide pour commencer -->
                                            <div class="text-center text-gray-500 py-4">
                                                <p>No sub-tasks yet. Click the + button to add one.</p>
                                            </div>
                                        @endforelse --}}
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

                                <!-- Start at-->
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

                                <!-- Deadline -->
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
                                                <!--  Rendre le select "Assign to" facultatif (avec option vide par défaut)
                                                    option value="{ { $member->id }}" { { old('assigned_to') == $member->id ? 'selected' : '' }}>
                                                    { { $member->name }}
                                                </!--option-->
                                                <option value="{{ $member->id }}" {{ old('assigned_to', $task->assigned_to ?? '') == $member->id ? 'selected' : '' }}>
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


let subtaskIndex = {{ count(old('subtasks', $task->subtasks ?? [])) }};

function addSubTask() {
    const container = document.getElementById('subtasks-container');

    // Supprime le message "No sub-tasks" si présent
    const noSubtasksMsg = container.querySelector('.text-center');
    if (noSubtasksMsg) noSubtasksMsg.remove();

    const div = document.createElement('div');
    div.className = 'd-flex align-items-center gap-3 mb-3 subtask-item p-3 bg-gray-700 rounded-lg border border-gray-600';
    //'card bg-gray-700 mb-3 subtask-item shadow-sm border border-gray-600';
    div.innerHTML = `
        <div class="card-body p-4">
            <div class="row g-3 align-items-end">
                <div class="  col-md-6">
                    <label class="form-label small text-gray-300 mb-1">Title</label>
                    <input type="text" name="subtasks[${subtaskIndex}][title]" class="form-control bg-gray-600 text-white border-gray-500" placeholder="Sub-task title" required>
                </div>
                <div class="col-md-5">
                    <label class="form-label small text-gray-300 mb-1">Status</label>
                    <select name="subtasks[${subtaskIndex}][status]" class="form-select bg-gray-600 text-white border-gray-500">
                        <option value="pending" selected>Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div class=" col-md-6">
                    <label class="form-label small text-gray-300 mb-1">Assign to</label>
                    <select name="subtasks[${subtaskIndex}][assigned_to]" class="form-select bg-gray-600 text-white border-gray-500">
                        <option value="">Not assigned</option>
                        @foreach($teamMembers as $member)
                            <option value="{{ $member->id }}">{{ $member->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class=" col-md-5">
                    <label class="form-label small text-gray-300 mb-1">Priority</label>
                    <input type="number" name="subtasks[${subtaskIndex}][priority]" min="1" max="5" value="3" class="form-control bg-gray-600 text-white border-gray-500 text-center">
                </div>
                <div class=" col-md-6">
                    <label class="form-label small text-gray-300 mb-1">Due Date</label>
                    <input type="datetime-local" name="subtasks[${subtaskIndex}][due_date]" class="form-control bg-gray-600 text-white border-gray-500">
                </div>
                <div class="col-md-2 text-end">
                    <button type="button" class="btn btn-outline-danger btn-sm rounded-circle shadow-sm" onclick="removeSubTask(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    container.appendChild(div);
    subtaskIndex++;
}

function removeSubTask(button) {
    button.closest('.subtask-item').remove();
    // Si plus aucune subtask, affiche le message
    if (document.querySelectorAll('.subtask-item').length === 0) {
        const container = document.getElementById('subtasks-container');
        const msg = document.createElement('div');
        msg.className = 'text-center py-5';
        msg.innerHTML = '<p class="text-gray-400 mb-0">No sub-tasks yet. Click the + button to add one.</p>';
        container.appendChild(msg);
    }
}

</script>
@endpush
