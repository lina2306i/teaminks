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
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                            </ul>
                        </div>
                    @endif

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
                                    @error('subtasks')
                                        <div class="text-danger small mb-2">{{ $message }}</div>
                                    @enderror

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
                                                            <label class="form-label small text-gray-300 mb-1">Priority 1..5 </label>
                                                            <input type="number"
                                                                name="subtasks[{{ $loop->index }}][priority]"
                                                                min="1" max="5"
                                                                value="{{ is_array($subtask) ? ($subtask['priority'] ?? 3) : $subtask->priority }}"
                                                                class="form-control bg-gray-600 text-white border-gray-500 text-center"
                                                                placeholder="1-5">
                                                        </div>
                                                        <!-- Points -->
                                                        <div class="col-lg-3 col-md-3">
                                                            <label class="form-label small text-gray-300 mb-1">Points 1..5 </label>
                                                            <input type="number"
                                                                name="subtasks[{{ $loop->index }}][points]"
                                                                min="1" max="5"
                                                                value="{{ is_array($subtask) ? ($subtask['points'] ?? 3) : $subtask->points }}"
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

                                <!-- Notes -->
                                <div class="mb-4">
                                    <label for="notes" class="form-label fw-semibold">Notes</label>
                                    <textarea name="notes"
                                            rows="3"
                                            class="form-control bg-gray-700 border-gray-600 text-white"
                                            placeholder="Internal notes...">{{ old('notes', $task->notes ?? '') }}</textarea>
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
                                <!-- Pinned -->
                                <div class="form-check form-switch mt-3">
                                    <input class="form-check-input"
                                        type="checkbox"
                                        name="pinned"
                                        value="1"
                                        {{ old('pinned', $task->pinned ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold">Pin this task</label>
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

                                <!-- Reminder -->
                                <div class="mt-3">
                                    <label for="reminder_at" class="form-label fw-semibold">Reminder</label>
                                    <input type="datetime-local"
                                        name="reminder_at"
                                        id="reminder_at"
                                        class="form-control bg-gray-700 border-gray-600 text-white"
                                        value="{{ old('reminder_at', $task->reminder_at ?? '') }}">
                                </div>

                                <!-- Priority -->
                                <div class="mt-3">
                                    <label for="priority" class="form-label fw-semibold">Priority 1..5 </label>
                                    <input type="number"
                                        name="priority"
                                        min="1" max="5"
                                        class="form-control bg-gray-700 border-gray-600 text-white"
                                        value="{{ old('priority', $task->priority ?? 3) }}">
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
                                    <label for="points" class="form-label fw-semibold">Points 1..6</label>
                                    @error('points')
                                        <div class="text-danger small mb-2">{{ $message }}</div>
                                    @enderror
                                    <input   class="form-control bg-gray-700 border-gray-600 text-white" type="number" name="points" id="points" value="{{ old('points', $task->points) }}" min="1" required>
                                </div>

                            </div>

                            {{--  AJOUT DU COMPOSANT FICHIERS
                            @include('leader.tasks.editFile', ['task' => $task])
                             --}}
                            <!-- Attachement  @ if($task->attachments_count > 0) -->
                            <h3 class="h5 mb-4 text-primary">Attachments — (Max 5 files)</h3>
                            {{-- ✅ SECTION FICHIERS AVEC BOUTON EDIT --}}
                            <div class="card  shadow mb-4">
                                <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        <i class="fas fa-paperclip me-2"></i> Attachment(s)
                                        <span class="badge bg-light text-dark">{{ $task->attachments->count() }} / 5</span>
                                    </h5>
                                    {{-- ✅ BOUTON EDIT FILES --}}
                                    <button type="button"   class="btn btn-light btn-sm"   data-bs-toggle="modal"  data-bs-target="#editFilesModal">
                                        Files Manager <i class="fas fa-edit me-1"></i>
                                    </button>
                                </div>
                                <div class="card-body">
                                    @if($task->attachments->count() > 0)
                                        <div class="list-group">
                                            @foreach($task->attachments as $attachment)
                                            <div class="list-group-item  d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <i class="{{ getFileIconHelper($attachment->mime_type) }} fa-2x text-primary me-3"></i>
                                                    <div>
                                                        <a href="{{ asset('storage/' . $attachment->path) }}"
                                                        target="_blank"
                                                        class="fw-bold text-decoration-none">
                                                            {{ $attachment->filename }}
                                                        </a>
                                                    <span class="text-xs text-gray-400">({{ $attachment->uploader->name ?? 'Unknown' }}) </span>
                                                        <br>
                                                        <small class="text-muted">
                                                            Added on {{ $attachment->created_at->format('d/m/Y à H:i') }}
                                                            • Uploaded {{ $attachment->created_at->diffForHumans() }}
                                                        </small>
                                                        <small class="text-gray-400 ">
                                                            _ {{ formatBytesHelper($attachment->size) }}
                                                            {{-- number_format($attachment->size / 1024, 2) KB--}}
                                                        </small>
                                                    </div>
                                                </div>
                                                <a href="{{ asset('storage/' . $attachment->path) }}"
                                                    download="{{ $attachment->filename }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-download"></i>
                                                </a>
                                            </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-4 text-muted">
                                            <i class="fas fa-folder-open fa-3x mb-3"></i>
                                            <p>No attachments yet.</p>
                                            <button type="button"
                                                    class="btn btn-primary btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editFilesModal">
                                                <i class="fas fa-plus me-1"></i>Add File(s)
                                            </button>
                                        </div>
                                    @endif
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

    {{-- ✅ MODAL POUR ÉDITER LES FICHIERS --}}
    <div class="modal fade" id="editFilesModal" tabindex="-1" aria-labelledby="editFilesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('leader.tasks.update', $task) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Champs cachés pour garder les valeurs actuelles --}}
                    <input type="hidden" name="project_id" value="{{ $task->project_id }}">
                    <input type="hidden" name="title" value="{{ $task->title }}">
                    <input type="hidden" name="description" value="{{ $task->description }}">
                    <input type="hidden" name="status" value="{{ $task->status }}">
                    <input type="hidden" name="difficulty" value="{{ $task->difficulty }}">
                    <input type="hidden" name="points" value="{{ $task->points }}">
                    <input type="hidden" name="priority" value="{{ $task->priority }}">
                    <input type="hidden" name="assigned_to" value="{{ $task->assigned_to }}">

                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="editFilesModalLabel">
                            <i class="fas fa-paperclip me-2"></i> Files Manager
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        {{-- ✅ INCLUSION DU COMPOSANT --}}
                        @include('leader.tasks.editFile', ['task' => $task])
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Save updates
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script><script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <script>

        let subtaskIndex = {{ count(old('subtasks', $task->subtasks ?? [])) }};

        // Initialiser Sortable :: use de drag&drop
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
            }
        });

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
                    <!-- Title -->
                    <div class="ccol-md-6">
                        <label class="form-label small text-gray-300 mb-1">Title</label>
                        <input type="text"
                            name="subtasks[${subtaskIndex}][title]"
                            class="form-control bg-gray-600 text-white border-gray-500"
                            placeholder="Sub-task title"
                            required>
                    </div>
                    <!-- Status -->
                    <div class=" col-md-3>
                        <label class="form-label small text-gray-300 mb-1">Status</label>
                        <select name="subtasks[${subtaskIndex}][status]" class="form-select bg-gray-600 text-white border-gray-500">
                            <option value="pending" selected>Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <!-- Assigned to -->
                    <div class=" col-md-3">
                        <label class="form-label small text-gray-300 mb-1">Assign to</label>
                        <select name="subtasks[${subtaskIndex}][assigned_to]" class="form-select bg-gray-600 text-white border-gray-500">
                            <option value="">Not assigned</option>
                            @foreach($teamMembers as $member)
                                <option value="{{ $member->id }}"
                                    {{ $member->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Priority -->
                    <div class=" col-md-2">
                        <label class="form-label small text-gray-300 mb-1">Priority 5..1 </label>
                        <input type="number"
                            name="subtasks[${subtaskIndex}][priority]"
                            min="1" max="5" value ="3"
                            class="form-control bg-gray-600 text-white border-gray-500 text-center"
                            placeholder="1-5">
                    </div>
                    <!-- Points -->
                    <div class="col-md-2">
                        <label class="form-label small text-gray-300 mb-1">Points 1..5 </label>
                        <input type="number"
                            name="subtasks[${subtaskIndex}][points]"
                            min="1" max="5" value ="3"
                            class="form-control bg-gray-600 text-white border-gray-500 text-center"
                            placeholder="1-5">
                    </div>
                    <!-- Due Date -->
                    <div class="col-md-4">
                        <label class="form-label small text-gray-300 mb-1">Due Date</label>
                        <input type="datetime-local"
                            name="subtasks[${subtaskIndex}][due_date]"
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
