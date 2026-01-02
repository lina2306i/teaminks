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

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('leader.tasks.store') }}" method="POST" id="create-task-form">
                        @csrf

                        <div class="row g-5">
                            <!-- G.Left Column: Main Content -->
                            <div class="col-lg-8">

                                <!-- Task Details Section -->
                                <h5 class="fw-bold mb-4 text-primary"><i class="fas fa-info-circle me-2"></i>Task Details</h5>

                                <!-- Task Title -->
                                <div class="mb-4">
                                    <label for="title" class="form-label fw-semibold fs-5"><i class="fas fa-pen me-2"></i>Task Title</label>
                                    <input type="text" name="title" id="title"
                                           class="form-control form-control-lg bg-gray-700 border-gray-600 text-white"
                                           placeholder="Enter task title"
                                           value="{{ old('title') }}"
                                           required>
                                    @error('title')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Description with Preview -->
                                <div class="mb-4">
                                    <label for="description" class="form-label fw-semibold fs-5"><i class="fas fa-file-alt me-2"></i>Description Task</label>
                                    <textarea name="description"  id="description"  rows="6"
                                              class="form-control bg-gray-700 border-gray-600 text-white"
                                              placeholder="Describe the task in detail...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                    <div class="mt-2 d-flex justify-content-end">
                                        <button type="button" class="btn btn-sm btn-outline-info" onclick="toggleDescriptionPreview()">Toggle Preview</button>
                                    </div>
                                    <div id="description-preview" class="mt-3 p-3 bg-gray-700 border border-gray-600 rounded d-none"></div>
                                </div>

                                <!-- Subtasks Section -->
                                <!-- Sub-Tasks --> <!--  <div class="mb-4"> Exemple pour create et edit (même structure) -->
                                <div class="mb-5">
                                    <h5 class="fw-bold mb-4 text-primary"><i class="fas fa-list-ul me-2"></i>Subtasks <small class="text-gray-400">(Max 10)</small></h5>
                                    <hr class="border-gray-600 mb-4">
                                    <label class="form-label fw-semibold fs-5 d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="fw-semibold">Add Subtask</h6>
                                        <button type="button" class="btn btn-primary btn-sm rounded-circle shadow" onclick="addSubTask()">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </label>

                                    @error('subtasks')
                                        <div class="text-danger small mb-2">{{ $message }}</div>
                                    @enderror

                                    <div id="subtasks-container" class="mt-3 space-y-3">
                                        @php
                                            // Récupère les subtasks existantes (edit) ou old input (create)
                                            // Pour create : old() ou tableau vide
                                            // Pour edit : old() ou $task->subtasks->toArray()  :: $existingSubtasks = old('subtasks', $task->subtasks->toArray());
                                            // $existingSubtasks = old('subtasks')  ? old('subtasks') : ($task->subtasks ?? collect());
                                            $existingSubtasks = old('subtasks', []);
                                            // $existingSubtasks = old('subtasks') ? old('subtasks')  : (isset($task) ? $task->subtasks->toArray() : []);
                                        //@if(empty($existingSubtasks))
                                        @endphp
                                        @if(count($existingSubtasks) === 0)
                                            <div class="text-center py-5">
                                                <p class="text-gray-400 mb-0">No sub-tasks yet. Click  the + button to add one.</p>
                                            </div>
                                        @endif

                                        {{-- -@forelse($existingSubtasks as $index => $subtask) --}}
                                        @foreach($existingSubtasks as $index => $subtask)
                                            <div class="card bg-gray-700 mb-3 subtask-item shadow-sm border border-gray-600">
                                                <div class="card-body p-4">
                                                    <div class="row g-3 align-items-end">
                                                        @error('subtasks')
                                                            <div class="alert alert-danger small">{{ $message }}</div>
                                                        @enderror
                                                        <!-- Title $loop->index  == $index -->
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
                                                            <select name="subtasks[{{ $index }}][status]" class="form-select bg-gray-600 text-white border-gray-500">
                                                                <option value="pending" {{ ($subtask['status'] ?? 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                                                                <option value="in_progress" {{ ($subtask['status'] ?? '') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                                                <option value="completed" {{ ($subtask['status'] ?? '') == 'completed' ? 'selected' : '' }}>Completed</option>
                                                            </select>
                                                        </div>

                                                        <!-- Assigned to  3 -->
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

                                                        <!-- Priority  2 -->
                                                        <div class=" col-md-3">
                                                            <label class="form-label small text-gray-300 mb-1" title="1=Urgent, 5=Very Low" >Priority 5..1 </label>
                                                            <input type="number" min="1" max="5" placeholder="1-5"
                                                                name="subtasks[{{ $index }}][priority]"
                                                                value="{{ $subtask['priority'] ?? 3 }}"
                                                                class="form-control bg-gray-600 text-white border-gray-500 text-center ">
                                                            @error('subtasks.$index.priorit')
                                                                <div class="text-danger small mb-2">
                                                                    {{ $message }} (Sub-task priority must be between 1 and 5)
                                                                </div>
                                                            @enderror
                                                        </div>
                                                        <!-- Points -->
                                                        <div class="col-md-2">
                                                            <label class="form-label small text-gray-300 mb-1" title="Estimation in story points" >Points 1..5 </label>
                                                            <input type="number"  min="1" max="5"  placeholder="1-5"
                                                                name="subtasks[{{ $loop->index }}][points]"
                                                                value="{{ is_array($subtask) ? ($subtask['points'] ?? 3) : $subtask->points }}"
                                                                class="form-control bg-gray-600 text-white border-gray-500 text-center">
                                                            @error('subtasks.$index.points')
                                                                <div class="text-danger small mb-2">
                                                                    {{ $message }} (Sub-task points must be between 1 and 5)
                                                                </div>
                                                            @enderror
                                                        </div>

                                                        <!-- Due Date -->
                                                        <div class="col-md-4">
                                                            <label class="form-label small text-gray-300 mb-1">Due Date</label>
                                                            <input type="datetime-local"
                                                                name="subtasks[{{ $loop->index }}][due_date]"
                                                                value="{{ is_array($subtask) ? ($subtask['due_date'] ?? '') : ($subtask->due_date?->format('Y-m-d\TH:i') ?? '') }}"
                                                                class="form-control bg-gray-600 text-white border-gray-500">
                                                        </div>

                                                        <!-- Delete Button -->
                                                        <div class=" col-md-1 text-end">
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
                                    </div>
                                    <!-- Subtasks Progress (visual) -->
                                    <div class="mt-3">
                                        <div class="progress bg-gray-700 rounded" style="height: 8px;">
                                            <div id="subtasks-progress" class="progress-bar bg-primary" style="width: 0%;"></div>
                                        </div>
                                        <small class="text-gray-400 d-block mt-1">Subtasks added: <span id="subtasks-count">0</span>/10</small>
                                    </div>
                                </div>

                                <!-- Note  & Pin  Section -->
                                <h5 class="fw-bold mb-4 text-primary"><i class="fas fa-project-diagram me-2"></i>Note & Pin</h5>
                                <!-- Notes -->
                                <div class="mb-4">
                                    <label for="notes" class="form-label fw-semibold"><i class="fas fa-sticky-note me-2"></i> Notes</label>
                                    <textarea name="notes"
                                            rows="3"
                                            class="form-control bg-gray-700 border-gray-600 text-white"
                                            placeholder="Internal notes...">{{ old( 'notes' ) }}</textarea>
                                </div>
                                 <!-- Reminder -->
                                <div class="mb-4">
                                    <label for="reminder_at" class="form-label fw-semibold"><i class="fas fa-bell me-2"></i>Reminder</label>
                                    <input type="datetime-local"   name="reminder_at"  id="reminder_at"
                                        class="form-control bg-gray-700 border-gray-600 text-white"
                                        value="{{ old('reminder_at', $task->reminder_at ?? '') }}">
                                </div>

                                <!-- Pinned -->
                                <div class="form-check form-switch mt-3">
                                    <label class="form-check-label fw-semibold"><i class="fas fa-thumbtack me-2"></i> Pin this task</label>
                                    <input class="form-check-input"  type="checkbox"  name="pinned"  value="1"
                                        {{ old('pinned', $task->pinned ?? false) ? 'checked' : '' }}>
                                </div>

                            </div>


                            <!-- Colonne droite -->
                            <div class="col-lg-4">
                                <!-- Project & Status Section -->
                                <h5 class="fw-bold mb-4 text-primary"><i class="fas fa-project-diagram me-2"></i>Project & Status </h5>
                                <!-- Project -->
                                <div class="mb-4">
                                    <label for="project_id" class="form-label fw-semibold"><i class="fas fa-folder  me-2"></i> Project:</label>
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
                                <!-- Status -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold"><i class="fas fa-check-circle me-2"></i> Status</label>
                                    <select name="status" class="form-select bg-gray-700 border-gray-600 text-white">
                                        <option value="todo" {{ old('status', 'todo') == 'todo' ? 'selected' : '' }}>To Do</option>
                                        <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                     {{-- <select name="status" class="form-select bg-gray-700 border-gray-600 text-white">
                                        <option value="todo">To Do</option>
                                        <option value="in_progress">In Progress</option>
                                        <option value="completed">Completed</option>
                                    </select> - --}}
                                </div>

                                <!-- Dates & Reminders Section -->
                                <h5 class="fw-bold mb-4 text-primary"><i class="fas fa-calendar-alt me-2"></i>Dates & Reminders</h5>

                                <!-- Start at row g-3 mb-4 -->
                                <div class="mb-4">
                                    <!-- Start at <div class="col-md-6"> -->
                                    <label for="start_at" class="form-label fw-semibold"><i class="fas fa-play me-2"></i>Start at</label>
                                    <input type="datetime-local"   name="start_at" id="start_at"
                                        class="form-control bg-gray-700 border-gray-600 text-white"
                                        value="{{ old('start_at') }}">
                                    @error('start_at')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Deadline -->
                                <div class="mb-4">
                                    <!-- Deadline  <div class="col-md-6"> -->
                                    <label for="due_date" class="form-label fw-semibold"><i class="fas fa-stopwatch me-2"></i>Deadline</label>
                                    <input type="datetime-local"  name="due_date"   id="due_date"
                                        class="form-control bg-gray-700 border-gray-600 text-white"
                                        value="{{ old('due_date') }}">
                                    @error('due_date')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Assignment & Priority Section -->
                                <h5 class="fw-bold mb-4 text-primary"><i class="fas fa-user-check me-2"></i>Assignment & Priority</h5>

                                <!--<div class="row g-3 mt-3"><div class="col-md-6"-->
                                <!-- Assign to -->
                                <div class="mb-4">
                                    <label for="assigned_to" class="form-label fw-semibold"><i class="fas fa-user me-2"></i>Assign to</label>
                                    <select name="assigned_to" id="assigned_to" class="form-select bg-gray-700 border-gray-600 text-white">
                                        <option value="">Not assigned</option>
                                        @foreach($teamMembers as $member)
                                            <!--  Rendre le select "Assign to" facultatif (avec option vide par défaut)
                                                option value="{ { $member->id }}" { { old('assigned_to') == $member->id ? 'selected' : '' }}>
                                                { { $member->name }}
                                            </!--option-->
                                            {{-- <option value="{{ $member->id }}" {{ old('assigned_to', $task->assigned_to ?? '') == $member->id ? 'selected' : '' }}> --}}
                                            <option value="{{ $member->id }}" {{ old('assigned_to') == $member->id ? 'selected' : '' }}>
                                                {{ $member->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Priority -->
                                <div class="mb-4">
                                    <label for="priority" class="form-label fw-semibold"><i class="fas fa-exclamation-triangle me-2"></i>Priority</label>
                                    <select name="priority" id="priority" class="form-select bg-gray-700 border-gray-600 text-white" title="1=Urgent, 5=Very Low">
                                        <option value="1" {{ old('priority', 3) == 1 ? 'selected' : '' }}>1 - Urgent</option>
                                        <option value="2" {{ old('priority', 3) == 2 ? 'selected' : '' }}>2 - High</option>
                                        <option value="3" {{ old('priority', 3) == 3 ? 'selected' : '' }}>3 - Normal</option>
                                        <option value="4" {{ old('priority', 3) == 4 ? 'selected' : '' }}>4 - Low</option>
                                        <option value="5" {{ old('priority', 3) == 5 ? 'selected' : '' }}>5 - Very Low</option>
                                    </select>
                                </div>

                                <!-- Difficulty <option value="challenging" { { old('difficulty', 'medium') == 'challenging' ? 'selected' : '' }}>Challenging</option>-->
                                <div class="mb-4">
                                    <label for="difficulty" class="form-label fw-semibold"><i class="fas fa-tools me-2"></i>Difficulty</label>
                                    <select name="difficulty" id="difficulty" class="form-select bg-gray-700 border-gray-600 text-white">
                                        <option value="easy" {{ old('difficulty') == 'easy' ? 'selected' : '' }}>Easy</option>
                                        <option value="medium" {{ old('difficulty') == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="hard" {{ old('difficulty') == 'hard' ? 'selected' : '' }}>Hard</option>
                                        <option value="challenging" {{ old('difficulty') == 'challenging' ? 'selected' : '' }}>Challenging</option>
                                    </select>
                                </div>


                                <!-- Points -->
                                <div class="mb-4">
                                    <label for="points" class="form-label fw-semibold"><i class="fas fa-star me-2"></i>Points (1-6)</label>
                                    <input type="number"  name="points"   id="points" min="1"
                                           class="form-control bg-gray-700 border-gray-600 text-white"
                                           value="{{ old('points', 5) }}"
                                           required>
                                    @error('points')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Attachments -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold"><i class="fas fa-paperclip me-2"></i>Attachments</label>
                            <div class="bg-gray-700 border border-gray-600 rounded p-3">
                                <input type="file" name="attachments[]" multiple
                                    class="form-control bg-gray-700 text-white border-gray-500"
                                    accept=".jpg,.png,.pdf,.doc,.docx,.xls,.xlsx,.txt,.zip">
                                <small class="text-gray-400 d-block mt-2">
                                    Max 10MB per file • Multiple files allowed
                                </small>
                            </div>
                        </div>

                        <!-- Bouton Create -->
                        <div class="text-end mt-5">
                            <button type="submit" class="btn btn-primary btn-lg px-5 shadow">
                                <i class="fas fa-save me-2"></i>Create Task
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

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/marked@4.0.0/marked.min.js"></script> <!-- For Markdown preview -->
<script>
// En create, $task n’existe PAS. so pas ;; let subtaskIndex = {{ count(old('subtasks', $task->subtasks ?? [])) }};
let subtaskIndex = {{ count(old('subtasks', [])) }};
const MAX_SUBTASKS = 20; //10
// Initialiser Sortable
    new Sortable(document.getElementById('subtasks-container'), {
        animation: 150,
        ghostClass: 'bg-gray-900',
        handle: '.subtask-item',
        onEnd: function () {
            // Mettre à jour les index des inputs après drag & drop
            const items = document.querySelectorAll('.subtask-item');
            items.forEach((item, index) => {
                item.querySelectorAll('input, select').forEach(input => {
                    const name = input.getAttribute('name');
                    if (name) {
                        input.setAttribute('name', name.replace(/\[\d+\]/, `[${index}]`));
                    }
                });
            });
            updateSubtasksProgress();
        }
    });
// add SubTask
function addSubTask() {

    if (document.querySelectorAll('.subtask-item').length >= MAX_SUBTASKS) {
        alert('Maximum of 20 subtasks reached.');
        return;
    }

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
                <div class="ccol-md-6">
                    <label class="form-label small text-gray-300 mb-1">Title</label>
                    <input type="text"  name="subtasks[${subtaskIndex}][title]" class="form-control bg-gray-600 text-white border-gray-500"
                        placeholder="Sub-task title"  required>
                </div>
                <div class=" col-md-3">
                    <label class="form-label small text-gray-300 mb-1">Status</label>
                    <select name="subtasks[${subtaskIndex}][status]" class="form-select bg-gray-600 text-white border-gray-500">
                        <option value="pending" {{ ($subtask['status'] ?? 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ ($subtask['status'] ?? '') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ ($subtask['status'] ?? '') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <!-- Assigned to  3 -->
                <div class=" col-md-6">
                    <label class="form-label small text-gray-300 mb-1">Assign to</label>
                    <select name="subtasks[${subtaskIndex}][assigned_to]" class="form-select bg-gray-600 text-white border-gray-500">
                        <option value="">Not assigned</option>
                        @foreach($teamMembers as $member)
                            <option value="{{ $member->id }}"  {{ $member->name }}  </option>
                        @endforeach
                    </select>
                </div>
                <!-- Priority  2 -->
                <div class=" col-md-3">
                    <label class="form-label small text-gray-300 mb-1" title="1=Urgent, 5=Very Low" >Priority 5..1 </label>
                    <input type="number" min="1" max="5" placeholder="1-5"
                        name="subtasks[${subtaskIndex}][priority]"  value=" 3" class="form-control bg-gray-600 text-white border-gray-500 text-center ">
                </div>
                <!-- Points -->
                <div class="col-md-2">
                    <label class="form-label small text-gray-300 mb-1" title="Estimation in story points" >Points 1..5 </label>
                    <input type="number"  min="1" max="5"  placeholder="1-5"
                        name="subtasks[${subtaskIndex}][points]" class="form-control bg-gray-600 text-white border-gray-500 text-center">
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-gray-300 mb-1">Due Date</label>
                    <input type="datetime-local"  name="subtasks[${subtaskIndex}][due_date]"  class="form-control bg-gray-600 text-white border-gray-500">
                </div>
                <div class=" col-md-1 text-end">
                    <button type="button"class="btn btn-outline-danger btn-sm rounded-circle shadow-sm"
                            onclick="removeSubTask(this)" title="Remove sub-task">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    container.appendChild(div);
    subtaskIndex++;
    updateSubtasksProgress();
}
// remove Subtasks
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
    updateSubtasksProgress();
}
// Update Subtasks Progress
function updateSubtasksProgress() {
    const count = document.querySelectorAll('.subtask-item').length;
    document.getElementById('subtasks-count').textContent = count;
    document.getElementById('subtasks-progress').style.width = `${(count / MAX_SUBTASKS) * 100}%`;
}

// Markdown Preview
function toggleDescriptionPreview() {
    const textarea = document.getElementById('description');
    const preview = document.getElementById('description-preview');
    if (preview.classList.contains('d-none')) {
        preview.innerHTML = marked.parse(textarea.value);
        preview.classList.remove('d-none');
    } else {
        preview.classList.add('d-none');
    }
}

// Submit Confirmation
document.getElementById('create-task-form').addEventListener('submit', (e) => {
    if (document.querySelectorAll('.subtask-item').length > 5) {
        if (!confirm('You have more than 5 subtasks. Are you sure you want to create?')) {
            e.preventDefault();
        }
    }
});

// Init Progress
updateSubtasksProgress();
</script>
@endpush
