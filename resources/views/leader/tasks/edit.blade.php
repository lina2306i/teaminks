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
                            <!-- Subtasks  -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold fs-5 d-flex justify-content-between align-items-center">
                                    Sub-Tasks:
                                    <button type="button" class="btn btn-primary btn-sm rounded-circle" onclick="addSubTask()">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </label>
                                @error('subtasks')
                                    <div class="text-danger small mb-2">{{ $message }}</div>
                                @enderror
                                <div id="subtasks-container">
                                    @if($task->subtasks && count($task->subtasks) > 0)
                                        @foreach($task->subtasks ?? [] as $subtask)
                                            <div class="input-group mb-2 subtask-item">
                                                <input type="text"
                                                    name="subtasks[]"
                                                    value="{{-- $subtask --}} {{ is_string($subtask) ? $subtask : $subtask->title }}"
                                                    class="form-control bg-gray-700 border-gray-600 text-white"
                                                    placeholder="Sub-task name">
                                                <button type="button" class="btn btn-outline-danger" onclick="removeSubTask(this)">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    @else
                                        <!-- Exemple vide pour le premier ajout : @ if(empty($task->subtasks) || count($task->subtasks) == 0) ou bien @ if(!count($task->subtasks))  ou bien fait : @ if($task->subtasks && count($task->subtasks) > 0) puis @ else -->
                                        <div class="input-group mb-2 subtask-item">
                                            <input type="text"
                                                name="subtasks[]"
                                                class="form-control bg-gray-700 border-gray-600 text-white"
                                                placeholder="Sub-task name">
                                            <button type="button" class="btn btn-outline-danger" onclick="removeSubTask(this)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    @endif
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
                                        <option value="{{ $member->id }}" {{ $task->assigned_to == $member->id ? 'selected' : '' }}>
                                            {{ $member->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <hr>
                                <label for="user_id" class="form-label fw-semibold">
                                    Assign to
                                    @if($membersLoading ?? false)
                                        <span class="spinner-border spinner-border-sm ms-2"></span>
                                    @endif
                                </label>
                                @error('user_id')
                                    <div class="text-danger small mb-2">{{ $message }}</div>
                                @enderror
                                <select name="user_id" id="user_id" class="form-select bg-gray-700 border-gray-600 text-white" required>
                                    <option value="">Not assigned</option>
                                    @foreach($teamMembers as $member)
                                        <option value="{{ $member->id }}" {{ old('user_id', $task->user_id) == $member->id ? 'selected' : '' }}>
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
