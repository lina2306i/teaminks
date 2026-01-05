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

                                <hr>
                                <div class="mb-4">
                                    <div id="subtasks-container">
                                        <h4>Subtasks</h4>
                                        <div class="subtask-item">
                                            <input type="text" name="subtasks[0][title]" placeholder="Subtask title" required>
                                            <select name="subtasks[0][status]">
                                                <option value="pending">Pending</option>
                                                <option value="in_progress">In Progress</option>
                                                <option value="completed">Completed</option>
                                            </select>
                                            <select name="subtasks[0][assigned_to]">
                                                <option value="">Unassigned</option>
                                                @foreach($teamMembers as $member)
                                                    <option value="{{ $member->id }}">{{ $member->name }}</option>
                                                @endforeach
                                            </select>
                                            <select name="subtasks[0][priority]">
                                                @for($i=1;$i<=5;$i++)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                            <input type="datetime-local" name="subtasks[0][due_date]">
                                            <button type="button" onclick="removeSubTask(this)">Remove</button>
                                        </div>
                                    </div>

                                    <button type="button" onclick="addSubTask()">Add Subtask</button>
                                </div>
                                <hr>
                                <!-- Exemple pour create et edit (même structure) -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold fs-5 d-flex justify-content-between align-items-center">
                                        Sub-Tasks
                                        <button type="button" class="btn btn-primary btn-sm rounded-circle" onclick="addSubTask()">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </label>

                                    <div id="subtasks-container" class="mt-3">
                                        @foreach(old('subtasks', $task->subtasks ?? []) as $index => $subtask)
                                            <div class="card bg-gray-700 mb-3 subtask-item">
                                                <div class="card-body">
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <input type="text" name="subtasks[{{ $index }}][title]"
                                                                value="{{ is_array($subtask) ? $subtask['title'] : $subtask->title }}"
                                                                class="form-control bg-gray-600 text-white" placeholder="Title" required>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <select name="subtasks[{{ $index }}][status]" class="form-select bg-gray-600 text-white">
                                                                <option value="pending" {{ (is_array($subtask) ? $subtask['status'] ?? 'pending' : $subtask->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                                                <option value="in_progress" {{ (is_array($subtask) ? $subtask['status'] ?? '' : $subtask->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                                                <option value="completed" {{ (is_array($subtask) ? $subtask['status'] ?? '' : $subtask->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <button type="button" class="btn btn-outline-danger w-100" onclick="removeSubTask(this)">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <select name="subtasks[{{ $index }}][assigned_to]" class="form-select bg-gray-600 text-white">
                                                                <option value="">Not assigned</option>
                                                                @foreach($teamMembers as $member)
                                                                    <option value="{{ $member->id }}" {{ (is_array($subtask) ? $subtask['assigned_to'] ?? '' : $subtask->assigned_to) == $member->id ? 'selected' : '' }}>
                                                                        {{ $member->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <input type="number" name="subtasks[{{ $index }}][priority]" min="1" max="5"
                                                                value="{{ is_array($subtask) ? ($subtask['priority'] ?? 3) : $subtask->priority }}"
                                                                class="form-control bg-gray-600 text-white" placeholder="Priority (1-5)">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <input type="datetime-local" name="subtasks[{{ $index }}][due_date]"
                                                                value="{{ is_array($subtask) ? ($subtask['due_date'] ?? '') : ($subtask->due_date?->format('Y-m-d\TH:i')) }}"
                                                                class="form-control bg-gray-600 text-white">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>





@push('scripts')
<script>
//1er cas
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
// subtask script -- 2eme cas
let subtaskIndex = 1;

function addSubTask() {
    const container = document.getElementById('subtasks-container');
    const div = document.createElement('div');
    div.className = 'subtask-item';
    div.innerHTML = `
        <input type="text" name="subtasks[${subtaskIndex}][title]" placeholder="Subtask title" required>
        <select name="subtasks[${subtaskIndex}][status]">
            <option value="pending">Pending</option>
            <option value="in_progress">In Progress</option>
            <option value="completed">Completed</option>
        </select>
        <select name="subtasks[${subtaskIndex}][assigned_to]">
            <option value="">Unassigned</option>
            @foreach($teamMembers as $member)
                <option value="{{ $member->id }}">{{ $member->name }}</option>
            @endforeach
        </select>
        <select name="subtasks[${subtaskIndex}][priority]">
            @for($i=1;$i<=5;$i++)
                <option value="{{ $i }}">{{ $i }}</option>
            @endfor
        </select>
        <input type="datetime-local" name="subtasks[${subtaskIndex}][due_date]">
        <button type="button" onclick="removeSubTask(this)">Remove</button>
    `;
    container.appendChild(div);
    subtaskIndex++;
}

function removeSubTask(button) {
    button.closest('.subtask-item').remove();
}

// 3eme cas
let subtaskIndex = {{ old('subtasks', $task->subtasks ?? collect())->count() }};

function addSubTask() {
    const container = document.getElementById('subtasks-container');
    const div = document.createElement('div');
    div.className = 'card bg-gray-700 mb-3 subtask-item';
    div.innerHTML = `
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="subtasks[${subtaskIndex}][title]" class="form-control bg-gray-600 text-white" placeholder="Title" required>
                </div>
                <div class="col-md-3">
                    <select name="subtasks[${subtaskIndex}][status]" class="form-select bg-gray-600 text-white">
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-outline-danger w-100" onclick="removeSubTask(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="col-md-4">
                    <select name="subtasks[${subtaskIndex}][assigned_to]" class="form-select bg-gray-600 text-white">
                        <option value="">Not assigned</option>
                        @foreach($teamMembers as $member)
                            <option value="{{ $member->id }}">{{ $member->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="number" name="subtasks[${subtaskIndex}][priority]" min="1" max="5" value="3" class="form-control bg-gray-600 text-white">
                </div>
                <div class="col-md-4">
                    <input type="datetime-local" name="subtasks[${subtaskIndex}][due_date]" class="form-control bg-gray-600 text-white">
                </div>
            </div>
        </div>
    `;
    container.appendChild(div);
    subtaskIndex++;
}

function removeSubTask(button) {
    button.closest('.subtask-item').remove();
}
</script>
@endpush









---------------------------------edit------------------------------



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
                            <hr>
                            <!-- Exemple pour create et edit (même structure) -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold fs-5 d-flex justify-content-between align-items-center">
                                    Sub-Tasks
                                    <button type="button" class="btn btn-primary btn-sm rounded-circle" onclick="addSubTask()">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </label>

                                <div id="subtasks-container" class="mt-3">
                                    @foreach(old('subtasks', $task->subtasks ?? []) as $index => $subtask)
                                        <div class="card bg-gray-700 mb-3 subtask-item">
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <input type="text" name="subtasks[{{ $index }}][title]"
                                                            value="{{ is_array($subtask) ? $subtask['title'] : $subtask->title }}"
                                                            class="form-control bg-gray-600 text-white" placeholder="Title" required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <select name="subtasks[{{ $index }}][status]" class="form-select bg-gray-600 text-white">
                                                            <option value="pending" {{ (is_array($subtask) ? $subtask['status'] ?? 'pending' : $subtask->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                                            <option value="in_progress" {{ (is_array($subtask) ? $subtask['status'] ?? '' : $subtask->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                                            <option value="completed" {{ (is_array($subtask) ? $subtask['status'] ?? '' : $subtask->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <button type="button" class="btn btn-outline-danger w-100" onclick="removeSubTask(this)">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label small">Assign to (optional)</label>
                                                        <select name="subtasks[{{ $index }}][assigned_to]" class="form-select bg-gray-600 text-white">
                                                            <option value="">Not assigned</option>
                                                            @foreach($teamMembers as $member)
                                                                <!--option value="{ { $member->id }}" { { (is_array($subtask) ? $subtask['assigned_to'] ?? '' : $subtask->assigned_to) == $member->id ? 'selected' : '' }}>
                                                                    { { $member->name }}
                                                                </!--option-->
                                                                <option value="{{ $member->id }}" {{ (is_array($subtask) ? ($subtask['assigned_to'] ?? '') : ($subtask->assigned_to ?? '')) == $member->id ? 'selected' : '' }}>
                                                                    {{ $member->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input type="number" name="subtasks[{{ $index }}][priority]" min="1" max="5"
                                                            value="{{ is_array($subtask) ? ($subtask['priority'] ?? 3) : $subtask->priority }}"
                                                            class="form-control bg-gray-600 text-white" placeholder="Priority (1-5)">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input type="datetime-local" name="subtasks[{{ $index }}][due_date]"
                                                            value="{{ is_array($subtask) ? ($subtask['due_date'] ?? '') : ($subtask->due_date?->format('Y-m-d\TH:i')) }}"
                                                            class="form-control bg-gray-600 text-white">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>




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
//3eme cas
let subtaskIndex = {{ old('subtasks', $task->subtasks ?? collect())->count() }};

function addSubTask() {
    const container = document.getElementById('subtasks-container');
    const div = document.createElement('div');
    div.className = 'card bg-gray-700 mb-3 subtask-item';
    div.innerHTML = `
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="subtasks[${subtaskIndex}][title]" class="form-control bg-gray-600 text-white" placeholder="Title" required>
                </div>
                <div class="col-md-3">
                    <select name="subtasks[${subtaskIndex}][status]" class="form-select bg-gray-600 text-white">
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-outline-danger w-100" onclick="removeSubTask(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="col-md-4">
                    <label class="form-label small">Assign to (optional)</label>
                    <select name="subtasks[${subtaskIndex}][assigned_to]" class="form-select bg-gray-600 text-white">
                        <option value="">Not assigned</option>
                        @foreach($teamMembers as $member)
                            <option value="{{ $member->id }}">{{ $member->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="number" name="subtasks[${subtaskIndex}][priority]" min="1" max="5" value="3" class="form-control bg-gray-600 text-white">
                </div>
                <div class="col-md-4">
                    <input type="datetime-local" name="subtasks[${subtaskIndex}][due_date]" class="form-control bg-gray-600 text-white">
                </div>
            </div>
        </div>
    `;
    container.appendChild(div);
    subtaskIndex++;
}

function removeSubTask(button) {
    button.closest('.subtask-item').remove();
}
</script>
@endpush

-----------------------------Autre cas -------------------------------
<!-- Sub-Tasks Section -->
                            <div class="mb-5">
                                <hr class="border-gray-600 my-5">

                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h4 class="fw-bold text-white mb-0">Sub-Tasks</h4>
                                    <button type="button" class="btn btn-primary btn-sm rounded-circle shadow" onclick="addSubTask()">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>

                                <div id="subtasks-container" class="space-y-4">
                                    @php
                                        $subtasks = old('subtasks', $task->subtasks ?? collect());
                                        $subtaskIndex = $subtasks->count();
                                    @endphp

                                    @forelse($subtasks as $index => $subtask)
                                        <div class="card bg-gray-700 border border-gray-600 rounded-xl shadow-sm subtask-item p-4">
                                            <div class="row g-3 align-items-end">
                                                <!-- Title -->
                                                <div class="col-lg-4">
                                                    <label class="form-label small text-gray-300 mb-1">Title</label>
                                                    <input type="text"
                                                        name="subtasks[{{ $index }}][title]"
                                                        value="{{ is_object($subtask) ? $subtask->title : ($subtask['title'] ?? '') }}"
                                                        class="form-control bg-gray-600 border-gray-500 text-white"
                                                        placeholder="Sub-task title"
                                                        required>
                                                </div>

                                                <!-- Status -->
                                                <div class="col-lg-2">
                                                    <label class="form-label small text-gray-300 mb-1">Status</label>
                                                    <select name="subtasks[{{ $index }}][status]" class="form-select bg-gray-600 border-gray-500 text-white">
                                                        <option value="pending" {{ (is_object($subtask) ? $subtask->status : ($subtask['status'] ?? 'pending')) == 'pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="in_progress" {{ (is_object($subtask) ? $subtask->status : ($subtask['status'] ?? '')) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                                        <option value="completed" {{ (is_object($subtask) ? $subtask->status : ($subtask['status'] ?? '')) == 'completed' ? 'selected' : '' }}>Completed</option>
                                                    </select>
                                                </div>

                                                <!-- Assigned to -->
                                                <div class="col-lg-2">
                                                    <label class="form-label small text-gray-300 mb-1">Assign to</label>
                                                    <select name="subtasks[{{ $index }}][assigned_to]" class="form-select bg-gray-600 border-gray-500 text-white">
                                                        <option value="">Not assigned</option>
                                                        @foreach($teamMembers as $member)
                                                            <option value="{{ $member->id }}"
                                                                {{ (is_object($subtask) ? ($subtask->assigned_to ?? '') : ($subtask['assigned_to'] ?? '')) == $member->id ? 'selected' : '' }}>
                                                                {{ $member->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <!-- Priority -->
                                                <div class="col-lg-1">
                                                    <label class="form-label small text-gray-300 mb-1">Priority</label>
                                                    <input type="number"
                                                        name="subtasks[{{ $index }}][priority]"
                                                        min="1" max="5"
                                                        value="{{ is_object($subtask) ? $subtask->priority : ($subtask['priority'] ?? 3) }}"
                                                        class="form-control bg-gray-600 border-gray-500 text-white text-center">
                                                </div>

                                                <!-- Due Date -->
                                                <div class="col-lg-2">
                                                    <label class="form-label small text-gray-300 mb-1">Due Date</label>
                                                    <input type="datetime-local"
                                                        name="subtasks[{{ $index }}][due_date]"
                                                        value="{{ is_object($subtask) ? ($subtask->due_date?->format('Y-m-d\TH:i') ?? '') : ($subtask['due_date'] ?? '') }}"
                                                        class="form-control bg-gray-600 border-gray-500 text-white">
                                                </div>

                                                <!-- Remove -->
                                                <div class="col-lg-1 text-end">
                                                    <button type="button"
                                                            class="btn btn-outline-danger btn-sm rounded-circle mt-4"
                                                            onclick="removeSubTask(this)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <!-- Ligne vide par défaut -->
                                        <div class="card bg-gray-700 border border-gray-600 rounded-xl shadow-sm subtask-item p-4">
                                            <div class="row g-3 align-items-end">
                                                <div class="col-lg-4">
                                                    <input type="text" name="subtasks[0][title]" class="form-control bg-gray-600 border-gray-500 text-white" placeholder="Sub-task title">
                                                </div>
                                                <div class="col-lg-2">
                                                    <select name="subtasks[0][status]" class="form-select bg-gray-600 border-gray-500 text-white">
                                                        <option value="pending">Pending</option>
                                                        <option value="in_progress">In Progress</option>
                                                        <option value="completed">Completed</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-2">
                                                    <select name="subtasks[0][assigned_to]" class="form-select bg-gray-600 border-gray-500 text-white">
                                                        <option value="">Not assigned</option>
                                                        @foreach($teamMembers as $member)
                                                            <option value="{{ $member->id }}">{{ $member->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-1">
                                                    <input type="number" name="subtasks[0][priority]" min="1" max="5" value="3" class="form-control bg-gray-600 border-gray-500 text-white text-center">
                                                </div>
                                                <div class="col-lg-2">
                                                    <input type="datetime-local" name="subtasks[0][due_date]" class="form-control bg-gray-600 border-gray-500 text-white">
                                                </div>
                                                <div class="col-lg-1 text-end">
                                                    <button type="button" class="btn btn-outline-danger btn-sm rounded-circle mt-4" onclick="removeSubTask(this)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                            </div>




    let subtaskIndex = {{ $subtasks->count() }};

    function addSubTask() {
        const container = document.getElementById('subtasks-container');
        const div = document.createElement('div');
        div.className = 'card bg-gray-700 border border-gray-600 rounded-xl shadow-sm subtask-item p-4 mb-4';
        //div.innerHTML = `... même code que ci-dessus avec ${subtaskIndex} ...`;
        div.innerHTML = `
            <div class="card-body p-4">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-5">
                        <input type="text" name="subtasks[${subtaskIndex}][title]" class="form-control bg-gray-600 border-gray-500 text-white" placeholder="Sub-task title" required>
                    </div>
                    <div class="col-lg-2">
                        <select name="subtasks[${subtaskIndex}][status]" class="form-select bg-gray-600 border-gray-500 text-white">
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <select name="subtasks[${subtaskIndex}][assigned_to]" class="form-select bg-gray-600 border-gray-500 text-white">
                            <option value="">Not assigned</option>
                            @foreach($teamMembers as $member)
                                <option value="{{ $member->id }}">{{ $member->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-1">
                        <input type="number" name="subtasks[${subtaskIndex}][priority]" min="1" max="5" value="3" class="form-control bg-gray-600 border-gray-500 text-white text-center">
                    </div>
                    <div class="col-lg-1">
                        <input type="datetime-local" name="subtasks[${subtaskIndex}][due_date]" class="form-control bg-gray-600 border-gray-500 text-white">
                    </div>
                    <div class="col-lg-1 text-end">
                        <button type="button" class="btn btn-outline-danger btn-sm rounded-circle mt-4" onclick="removeSubTask(this)">
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
    }




    <!-- Sub-Tasks -->
                                <!-- Sub-Tasks Section -->
                                <!-- Exemple pour create et edit (même structure) -->
                                <div class="mb-4">
                                    <hr class="border-gray-600 my-5">
                                    <label class="form-label fw-semibold fs-5 d-flex justify-content-between align-items-center">
                                        Sub-Tasks
                                        <button type="button" class="btn btn-primary btn-sm rounded-circle" onclick="addSubTask()">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </label>

                                    <div id="subtasks-container" class="mt-3">
                                        @foreach(old('subtasks', $task->subtasks ?? []) as $index => $subtask)
                                            <div class="card bg-gray-700 mb-3 subtask-item">
                                                <div class="card-body">
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label small text-gray-300 mb-1">Title</label>
                                                            <input type="text" name="subtasks[{{ $index }}][title]"
                                                                value="{{ is_array($subtask) ? $subtask['title'] : $subtask->title }}"
                                                                class="form-control bg-gray-600 text-white" placeholder="Title" required>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small text-gray-300 mb-1">Status</label>
                                                            <select name="subtasks[{{ $index }}][status]" class="form-select bg-gray-600 text-white">
                                                                <option value="pending" {{ (is_array($subtask) ? $subtask['status'] ?? 'pending' : $subtask->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                                                <option value="in_progress" {{ (is_array($subtask) ? $subtask['status'] ?? '' : $subtask->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                                                <option value="completed" {{ (is_array($subtask) ? $subtask['status'] ?? '' : $subtask->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label small text-gray-300 mb-1">Assign to</label>
                                                            <select name="subtasks[{{ $index }}][assigned_to]" class="form-select bg-gray-600 text-white">
                                                                <option value="">Not assigned</option>
                                                                @foreach($teamMembers as $member)
                                                                    <!--option value="{ { $member->id }}" { { (is_array($subtask) ? $subtask['assigned_to'] ?? '' : $subtask->assigned_to) == $member->id ? 'selected' : '' }}>
                                                                        { { $member->name }}
                                                                    </!--option-->
                                                                    <option value="{{ $member->id }}" {{ (is_array($subtask) ? ($subtask['assigned_to'] ?? '') : ($subtask->assigned_to ?? '')) == $member->id ? 'selected' : '' }}>
                                                                        {{ $member->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label small text-gray-300 mb-1">Priority</label>
                                                            <input type="number" name="subtasks[{{ $index }}][priority]" min="1" max="5"
                                                                value="{{ is_array($subtask) ? ($subtask['priority'] ?? 3) : $subtask->priority }}"
                                                                class="form-control bg-gray-600 text-white" placeholder="Priority (1-5)">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label small text-gray-300 mb-1">Due Date</label>
                                                            <input type="datetime-local" name="subtasks[{{ $index }}][due_date]"
                                                                value="{{ is_array($subtask) ? ($subtask['due_date'] ?? '') : ($subtask->due_date?->format('Y-m-d\TH:i')) }}"
                                                                class="form-control bg-gray-600 text-white">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <button type="button" class="btn btn-outline-danger btn-sm rounded-circle mt-4 w-100" onclick="removeSubTask(this)">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

---------------------------------edit------------------------------<!-- Assign to -->
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
                                        <!--option value="{ { $member->id }}" { { old('user_id', $task->user_id) == $member->id ? 'selected' : '' }}>
                                            { { $member->name }}
                                        </!--option-->
                                        <option value="{{ $member->id }}" {{ old('assigned_to', $task->assigned_to ?? '') == $member->id ? 'selected' : '' }}>
                                            {{ $member->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>



//let subtaskIndex = { { old('subtasks', $task->subtasks ?? collect())-> cou n t() }};

//let subtaskIndex = {{ $existingSubtasks->count() }};
