@extends('layouts.appW')

@section('contentW')
<div class="container py-5">
    <div class="row justify-content-center col-xl-10 col-lg-11">
        <div class="card bg-gray-800 text-white shadow-2xl ">
            <!-- Header -->
            <div class="d-flex align-items-center card-header bg-gradient-warning text-center py-3" >
                <a href="{{ route('leader.tasks.show', $task) }}" class="btn btn-outline-light me-4">
                    ← Back to Task
                </a>
                <h3 class="display-5   mb-2 fw-bold" style="color: slateblue ">Edit Task</h3>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('leader.tasks.update', $task) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-5">
                        <!-- Colonne gauche : Title, Description, Sub-tasks -->
                        <div class="col-lg-8">
                            <!-- Task Project -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Project</label>
                                <select name="project_id" class="form-select bg-gray-700 border-gray-600 text-white" required>
                                    @foreach(auth()->user()->projects as $project)
                                        <option value="{{ $project->id }}" {{ $task->project_id == $project->id ? 'selected' : '' }}>
                                            {{ $project->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Task Title -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Title</label>
                                @error('title')
                                    <div class="text-danger small mb-2">{{ $message }}</div>
                                @enderror
                                <input type="text" name="title" value="{{ old('title', $task->title) }}"
                                    class="form-control bg-gray-700 border-gray-600 text-white" required>
                            </div>


                            <!-- Sub-Tasks -->
                            <!--  <div class="mb-4"> Exemple pour create et edit (même structure) -->
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
                                            // $existingSubtasks = old('subtasks')  ? old('subtasks') : ($task->subtasks ?? collect());
                                            $existingSubtasks = old('subtasks', $task->subtasks->toArray());
                                        @endphp

                                    @forelse($existingSubtasks as $index => $subtask)
                                        <div class="card bg-gray-700 mb-3 subtask-item shadow-sm border border-gray-600">
                                            <div class="card-body p-4">
                                                <div class="row g-3 align-items-end">
                                                    <!-- Title -->
                                                    <div class="col-lg-4 col-md-6">
                                                        <label class="form-label small text-gray-300 mb-1">Title</label>
                                                        <input type="text"
                                                            name="subtasks[{{ $loop->index }}][title]"
                                                            value="{{ is_array($subtask) ? ($subtask['title'] ?? '') : $subtask->title }}"
                                                            class="form-control bg-gray-600 text-white border-gray-500"
                                                            placeholder="Sub-task title"
                                                            required>
                                                    </div>

                                                    <!-- Status -->
                                                    <div class="col-lg-2 col-md-3">
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
                                                    <div class="col-lg-3 col-md-6">
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
                                                    <div class="col-lg-1 col-md-3">
                                                        <label class="form-label small text-gray-300 mb-1">Priority</label>
                                                        <input type="number"
                                                            name="subtasks[{{ $loop->index }}][priority]"
                                                            min="1" max="5"
                                                            value="{{ is_array($subtask) ? ($subtask['priority'] ?? 3) : $subtask->priority }}"
                                                            class="form-control bg-gray-600 text-white border-gray-500 text-center"
                                                            placeholder="1-5">
                                                    </div>

                                                    <!-- Due Date -->
                                                    <div class="col-lg-2 col-md-6">
                                                        <label class="form-label small text-gray-300 mb-1">Due Date</label>
                                                        <input type="datetime-local"
                                                            name="subtasks[{{ $loop->index }}][due_date]"
                                                            value="{{ is_array($subtask) ? ($subtask['due_date'] ?? '') : ($subtask->due_date?->format('Y-m-d\TH:i') ?? '') }}"
                                                            class="form-control bg-gray-600 text-white border-gray-500">
                                                    </div>

                                                    <!-- Delete Button -->
                                                    <div class="col-lg-1 col-md-2 text-end">
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
                                    @empty
                                        <!-- Aucun subtask → on affiche un champ vide pour commencer -->
                                        <div class="text-center text-gray-500 py-4">
                                            <p>No sub-tasks yet. Click the + button to add one.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Task Description (optional)</label>
                                @error('description')
                                    <div class="text-danger small mb-2">{{ $message }}</div>
                                @enderror
                                <textarea name="description" rows="5"
                                        class="form-control bg-gray-700 border-gray-600 text-white">{{ old('description', $task->description) }}</textarea>
                            </div>

                            <!-- Assign to -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Assign to</label>
                                <select name="assigned_to" class="form-select bg-gray-700 border-gray-600 text-white">
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


                            <!-- Status -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Status</label>
                                <select name="status" class="form-select bg-gray-700 border-gray-600 text-white">
                                    <option value="todo" {{ $task->status == 'todo' ? 'selected' : '' }}>To Do</option>
                                    <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>


                        </div>
                        <!-- Colonne droite -->
                        <div class="col-lg-4">
                            {{-- <div class="mb-4">
                                <label class="form-label fw-semibold">Due Date (optional)</label>
                                <input type="date" name="due_date" value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}"
                                    class="form-control bg-gray-700 border-gray-600 text-white">
                            </div> --}}
                            <hr>
                            <!-- Start at & Due date dans edit  added-->
                            <!-- Start at -->
                            <div class="mb-4">
                                <label for="start_at" class="form-label fw-semibold fs-5">Start at</label>
                                @error('start_at')
                                    <div class="text-danger small mb-2">{{ $message }}</div>
                                @enderror
                                <input type="datetime-local" name="start_at" id="start_at"  value="{{ old('start_at',$task->start_at?->format('Y-m-d\TH:i')) }}"
                                    class="form-control bg-gray-700 border-gray-600 text-white">
                            </div>
                            <!-- Deadline -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Due Date </label>
                                @error('deadline')
                                    <div class="text-danger small mb-2">{{ $message }}</div>
                                @enderror
                                <input type="datetime-local" name="due_date" value="{{ old('dua_date',$task->due_date?->format('Y-m-d\TH:i')) }}"
                                    class="form-control bg-gray-700 border-gray-600 text-white">

                            </div>

                            <!-- Difficulty -->
                            <div class="mb-4">
                                <label for="difficulty" class="form-label fw-semibold">Difficulty</label>
                                @error('difficulty')
                                    <div class="text-danger small mb-2">{{ $message }}</div>
                                @enderror
                                <select   class="form-control bg-gray-700 border-gray-600 text-white" name="difficulty"  id="difficulty" required>
                                    <option value="easy" {{ $task->difficulty == 'easy' ? 'selected' : '' }}>Easy</option>
                                    <option value="medium" {{ $task->difficulty == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="hard" {{ $task->difficulty == 'hard' ? 'selected' : '' }}>Hard</option>
                                    <option value="challenging" {{ old('difficulty', $task->difficulty) == 'challenging' ? 'selected' : '' }}>Challenging</option>
                                </select>
                            </div>

                            <!-- Points -->
                            <div class="mb-4">
                                <label for="points" class="form-label fw-semibold">Points</label>
                                @error('points')
                                    <div class="text-danger small mb-2">{{ $message }}</div>
                                @enderror
                                <input   class="form-control bg-gray-700 border-gray-600 text-white" type="number" name="points" id="points" value="{{ old('points', $task->points) }}" min="1" required>
                            </div>

                        </div>
                        <!-- Bouton Create -->
                        <div class="text-end mt-4 d-flex gap-3 justify-content-end">
                            <a href="{{ route('leader.tasks.show', $task) }}" class="btn btn-outline-light">Cancel</a>
                            <button type="submit" class="btn  btn-primary btn-lg px-5 shadow  btn-warning btn-lg">
                                Update Task
                                @if($formLoading ?? false)
                                    <span class="spinner-border spinner-border-sm ms-2"></span>
                                @endif
                            </button>
                        </div>
                    </div>
                </form>
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
    div.className ='card bg-gray-700 mb-3 subtask-item shadow-sm border border-gray-600';
    //'d-flex align-items-center gap-3 mb-3 subtask-item p-3 bg-gray-700 rounded-lg border border-gray-600';
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
