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
                    <!--  enctype="multipart/form-data" -->
                        <form action="{{ route('leader.tasks.store') }}" method="POST" id="create-task-form"
                                enctype="multipart/form-data" >
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
                                                            <!-- 6666663 mine: 6363241 || 6332241  <6332242||656562>-->

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

                                                            <!-- Status 3-->
                                                            <div class=" col-md-6">
                                                                <label class="form-label small text-gray-300 mb-1">Status</label>
                                                                <select name="subtasks[{{ $index }}][status]" class="form-select bg-gray-600 text-white border-gray-500">
                                                                    <option value="pending" {{ ($subtask['status'] ?? 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                                                                    <option value="in_progress" {{ ($subtask['status'] ?? '') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                                                    <option value="completed" {{ ($subtask['status'] ?? '') == 'completed' ? 'selected' : '' }}>Completed</option>
                                                                </select>
                                                            </div>

                                                            <!-- Assigned to  3 $loop->index -->
                                                            <div class=" col-md-6">
                                                                <label class="form-label small text-gray-300 mb-1">Assign to</label>
                                                                <select name="subtasks[{{ $index }}][assigned_to]" class="form-select bg-gray-600  text-white border-gray-500">
                                                                    <option value="">Not assigned</option>
                                                                    @foreach($teamMembers as $member)
                                                                    {{-- " { { (is_array($subtask) ? $subtask['assigned_to'] ?? '' : $subtask->assigned_to) == $member->id ? 'selected' : '' }}>
                                                                            { { $member->name }}
                                                                        </option--> --}}
                                                                        <option value="{{ $member->id }}"
                                                                            {{ (is_array($subtask) ? ($subtask['assigned_to'] ?? '') : ($subtask->assigned_to ?? '')) == $member->id ? 'selected' : '' }}>
                                                                            {{ $member->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <!-- Priority  2-3 -->
                                                            <div class=" col-md-6">
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
                                                            <!-- Points 2 -->
                                                            <div class="col-md-6">
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

                                                            <!-- Due Date 4 -->
                                                            <div class="col-md-6">
                                                                <label class="form-label small text-gray-300 mb-1">Due Date</label>
                                                                <input type="datetime-local"
                                                                    name="subtasks[{{ $loop->index }}][due_date]"
                                                                    value="{{ is_array($subtask) ? ($subtask['due_date'] ?? '') : ($subtask->due_date?->format('Y-m-d\TH:i') ?? '') }}"
                                                                    class="form-control bg-gray-600 text-white border-gray-500">
                                                            </div>

                                                            <!-- Delete Button 1 -->
                                                            <div class=" col-md-3 text-end">
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
                                            <small class="text-gray-400 d-block mt-1">Subtasks added: <span id="subtasks-count">0</span>/20</small>
                                        </div>
                                    </div>

                                    <!-- Note  & Pin  Section -->
                                    <h5 class="fw-bold mb-4 text-primary"><i class="fas fa-project-diagram me-2"></i>Note & Pin</h5>
                                    <!-- Notes -->
                                    <div class="mb-4">
                                        <label for="notes" class="form-label fw-semibold"><i class="fas fa-sticky-note me-2"></i> Notes</label>
                                        <textarea name="notes" id="notes"
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
                                        <label for="pinned" class="form-check-label fw-semibold"><i class="fas fa-thumbtack me-2"></i> Pin this task</label>
                                        <input class="form-check-input" id="pinned" type="checkbox"  name="pinned"  value="1"
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
                                        <label for="status" class="form-label fw-semibold"><i class="fas fa-check-circle me-2"></i> Status</label>
                                        <select name="status" id="status" class="form-select bg-gray-700 border-gray-600 text-white">
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

                            <!-- Attachments Section -->
                            <div class="mb-5">
                                <h5 class="fw-bold mb-4 text-primary"><i class="fas fa-paperclip me-2"></i>Attachments — (Max 5 files)</h5>
                                <label class="text-lg fw-bold mb-4 d-block"></label>
                                <div  id="attachments" class="form-group mb-4 rounded-xl">
                                    {{-- Input --}}

                                    <div id="dropZone" class="bg-gray-700 border border-2 border-dashed border-gray-600 rounded-xl p-5 text-center position-relative"
                                        style="min-height: 200px; cursor: pointer; transition: all 0.3s;">
                                        <label for="upload" class="form-label fw-semibold"><i class="fas fa-paperclip me-2"></i>Upload files</label>

                                        <input type="file" id="fileInput"  name="attachments[]"  multiple
                                            accept="*/*"   class="d-none"   max="5">

                                        <div id="dropZoneContent" class="py-4">
                                            <i class="fas fa-cloud-upload-alt fa-4x text-gray-400 mb-3"></i>
                                            <p class="text-lg font-bold mb-2">Drag drop files here</p>
                                            <p class="text-sm text-gray-400">or
                                                <span class="text-primary " style="text-decoration: underline;">click to browse</span>
                                            </p>
                                            <p class="text-xs mt-3 text-gray-500">Max 5 files • 10MB per file</p>
                                        </div>
                                    </div>
                                    {{-- Liste des fichiers sélectionnés --}}
                                    <div id="filesList" class="mt-3"></div>
                                </div>

                                <small class="text-gray-400 d-block mt-2">
                                    <i class="fas fa-cloud-upload-alt text-8xl text-gray-400 mb-6"></i>
                                    Max 5 files 🔹 10MB per file 🔹
                                    <i class="fas fa-info-circle me-1"></i>
                                    Multiple files allowed •• Formats: Images, PDF, Office, ZIP, Video...
                                </small>


                                @error('attachments.*')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Attachments section
                            @php $task = new App\Models\Task(['attachments' => collect()]) @endphp
                            @include('leader.tasks.partials._attachments', ['task' => $task])
                            --}}
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropZone = document.getElementById('dropZone');
            const fileInput = document.getElementById('fileInput');
        // const browseBtn = document.getElementById('browseBtn'); // 1
            const filesList = document.getElementById('filesList');
            const maxFiles = 5;
            let selectedFiles = [];

            // Click sur le bouton parcourir
        /* browseBtn.addEventListener('click', function(e) {
                e.preventDefault();

                fileInput.click();
            });*/
            // 2
            // Click sur la zone pour ouvrir le sélecteur
            dropZone.addEventListener('click', function(e) {
                fileInput.click();
            });

            // Click sur la zone de drop
            /*   dropZone.addEventListener('click', function(e) {
                if (e.target.id !== 'browseBtn' && !e.target.closest('#browseBtn')) {
                    fileInput.click();  // 2 ss if
                }
            });*/

            // Sélection de fichiers via l'input
            fileInput.addEventListener('change', function(e) {
                handleFiles(e.target.files);
            });

            // Drag & Drop events
            dropZone.addEventListener('dragover', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropZone.classList.add('drag-over');
            });

            dropZone.addEventListener('dragleave', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropZone.classList.remove('drag-over');
            });

            dropZone.addEventListener('drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropZone.classList.remove('drag-over');

                const files = e.dataTransfer.files;
                handleFiles(files);
                // 2 :handleFiles(e.dataTransfer.files);

            });

            function handleFiles(files) {
                const filesArray = Array.from(files);

                // Vérifier le nombre total de fichiers
                if (selectedFiles.length + filesArray.length > maxFiles) {
                    alert(`You can only add ${maxFiles} more file(s).`);
                    return;
                }

                // Ajouter les nouveaux fichiers
                filesArray.forEach(file => {
                    // Vérifier la taille du fichier (10MB max)
                    if (file.size > 10 * 1024 * 1024) {
                        alert(`The file ${file.name} is too large (max 10MB).`);
                        return;
                    }

                    selectedFiles.push(file);
                });

                updateFilesList();
                updateFileInput();
            }

            function updateFilesList() {
                filesList.innerHTML = '';

                if (selectedFiles.length === 0) {
                    return;
                }

                selectedFiles.forEach((file, index) => {
                    const fileItem = document.createElement('div');
                    fileItem.className = 'file-item';

                    const icon = getFileIcon(file.type);  //2

                    fileItem.innerHTML = `
                        <div class="file-icon">
                            <!-- i class="bi bi-file-earmark"></ -->
                            <i class="${icon}"></i>
                        </div>
                        <div class="file-info">
                            <div class="file-name">${escapeHtml(file.name)}</div>
                            <div class="file-size">${formatFileSize(file.size)}</div>
                        </div>
                        <div class="file-remove" data-index="${index}">
                            <i class="bi bi-x-circle-fill"></i>
                            <i class="fas fa-times-circle"></i>
                        </div>
                    `;

                    filesList.appendChild(fileItem);
                });

                // Ajouter les événements de suppression
                document.querySelectorAll('.file-remove').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const index = parseInt(this.getAttribute('data-index'));
                        removeFile(index);
                    });
                });
            }

            function removeFile(index) {
                selectedFiles.splice(index, 1);
                updateFilesList();
                updateFileInput();
            }

            function updateFileInput() {
                const dataTransfer = new DataTransfer();
                selectedFiles.forEach(file => {
                    dataTransfer.items.add(file);
                });
                fileInput.files = dataTransfer.files;
            }

            //2
            function getFileIcon(mimeType) {
                if (mimeType.startsWith('image/')) return 'fas fa-image';
                if (mimeType.includes('pdf')) return 'fas fa-file-pdf';
                if (mimeType.includes('word')) return 'fas fa-file-word';
                if (mimeType.includes('excel') || mimeType.includes('spreadsheet')) return 'fas fa-file-excel';
                if (mimeType.includes('zip')) return 'fas fa-file-archive';
                if (mimeType.includes('video')) return 'fas fa-file-video';
                return 'fas fa-file';
            }
            // 2+1
            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
            }

            function escapeHtml(text) {
                const map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                };
                return text.replace(/[&<>"']/g, m => map[m]);
            }
        });
    </script>
@endpush

@push('styles')
    <style>
        #dropZone.drag-over {
            background-color: rgba(59, 130, 246, 0.1);
            border-color: #3b82f6 !important;
            transform: scale(1.02);
        }

        .file-item {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            margin-bottom: 10px;
            transition: all 0.3s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .file-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .file-icon {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(238, 210, 210, 0.656);
            color: rgba(123, 131, 240, 0.792);
            border-radius: 8px;
            margin-right: 15px;
            font-size: 1.2rem;
        }

        .file-info {
            flex-grow: 1;
            color: rgb(247, 246, 245);
        }

        .file-name {
            font-weight: 600;
            margin-bottom: 3px;
            font-size: 0.95rem;
        }

        .file-size {
            font-size: 0.8rem;
            opacity: 0.8;
        }

        .file-remove {
            cursor: pointer;
            color: rgb(251, 37, 37);
            font-size: 1.3rem;
            padding: 5px 10px;
            transition: all 0.3s;
            opacity: 0.7;
        }

        .file-remove:hover {
            opacity: 1;
            transform: scale(1.2);
        }
        .border-gray-600 {
            border-color: #4b5563;
        }
    </style>
@endpush



@push('scripts')

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/marked@4.0.0/marked.min.js"></script> <!-- For Markdown preview -->

    <script>
        // === 1. Enregistrement correct du composant Alpine ===

       // FilePond.create(document.querySelector('#attachments'));

        // === 2. Tes autres scripts (subtasks, markdown, etc.) ===

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
                <!-- mine: 6363241 || 6332241  <6332242||656562>-->
                <div class="card-body p-4">
                    <div class="row g-3 align-items-end">
                        <div class="ccol-md-6">
                            <label class="form-label small text-gray-300 mb-1">Title</label>
                            <input type="text"  name="subtasks[${subtaskIndex}][title]" class="form-control bg-gray-600 text-white border-gray-500"
                                placeholder="Sub-task title"  required>
                        </div>
                        <div class=" col-md-6">
                            <label class="form-label small text-gray-300 mb-1">Status</label>
                            <select name="subtasks[${subtaskIndex}][status]" class="form-select bg-gray-600 text-white border-gray-500">
                                <option value="pending" {{ ($subtask['status'] ?? 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ ($subtask['status'] ?? '') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ ($subtask['status'] ?? '') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                        <!-- Assigned to  3-6 -->
                        <div class=" col-md-6">
                            <label class="form-label small text-gray-300 mb-1">Assign to</label>
                            <select name="subtasks[${subtaskIndex}][assigned_to]" class="form-select bg-gray-600 text-white border-gray-500">
                                <option value="">Not assigned</option>
                                @foreach($teamMembers as $member)
                                    <option value="{{ $member->id }}">{{ $member->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Priority  2-6-4 -->
                        <div class=" col-md-6">
                            <label class="form-label small text-gray-300 mb-1" title="1=Urgent, 5=Very Low" >Priority 5..1 </label>
                            <input type="number" min="1" max="5" placeholder="1-5"
                                name="subtasks[${subtaskIndex}][priority]"  value=" 3" class="form-control bg-gray-600 text-white border-gray-500 text-center ">
                        </div>
                        <!-- Points 2-4 -->
                        <div class="col-md-6">
                            <label class="form-label small text-gray-300 mb-1" title="Estimation in story points" >Points 1..5 </label>
                            <input type="number"  min="1" max="5"  placeholder="1-5"
                                name="subtasks[${subtaskIndex}][points]" class="form-control bg-gray-600 text-white border-gray-500 text-center">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-gray-300 mb-1">Due Date</label>
                            <input type="datetime-local"  name="subtasks[${subtaskIndex}][due_date]"  class="form-control bg-gray-600 text-white border-gray-500">
                        </div>
                        <div class=" col-md-3 text-end">
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
