@props(['index', 'subtask' => null])

<div class="card bg-gray-700 border border-gray-600 shadow-sm rounded-lg subtask-item">
    <div class="card-body p-4">
        <div class="row g-3 align-items-end">
            <!-- Title -->
            <div class="col-lg-4 col-md-6">
                <label class="form-label text-gray-300 small mb-1">Title</label>
                <input type="text"
                       name="subtasks[{{ $index }}][title]"
                       value="{{ is_array($subtask) ? ($subtask['title'] ?? '') : ($subtask?->title ?? '') }}"
                       class="form-control bg-gray-600 border-gray-500 text-white"
                       placeholder="Sub-task title"
                       required>
            </div>

            <!-- Status -->
            <div class="col-lg-2 col-md-3">
                <label class="form-label text-gray-300 small mb-1">Status</label>
                <select name="subtasks[{{ $index }}][status]"
                        class="form-select bg-gray-600 border-gray-500 text-white">
                    <option value="pending" {{ (is_array($subtask) ? ($subtask['status'] ?? 'pending') : ($subtask?->status ?? 'pending')) == 'pending' ? 'selected' : '' }}>
                        Pending
                    </option>
                    <option value="in_progress" {{ (is_array($subtask) ? ($subtask['status'] ?? '') : ($subtask?->status ?? '')) == 'in_progress' ? 'selected' : '' }}>
                        In Progress
                    </option>
                    <option value="completed" {{ (is_array($subtask) ? ($subtask['status'] ?? '') : ($subtask?->status ?? '')) == 'completed' ? 'selected' : '' }}>
                        Completed
                    </option>
                </select>
            </div>

            <!-- Assigned to -->
            <div class="col-lg-3 col-md-4">
                <label class="form-label text-gray-300 small mb-1">Assign to (optional)</label>
                <select name="subtasks[{{ $index }}][assigned_to]"
                        class="form-select bg-gray-600 border-gray-500 text-white">
                    <option value="">Not assigned</option>
                    @foreach($teamMembers as $member)
                        <option value="{{ $member->id }}"
                            {{ (is_array($subtask) ? ($subtask['assigned_to'] ?? '') : ($subtask?->assigned_to ?? '')) == $member->id ? 'selected' : '' }}>
                            {{ $member->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Priority -->
            <div class="col-lg-1 col-md-2">
                <label class="form-label text-gray-300 small mb-1">Priority</label>
                <input type="number"
                       name="subtasks[{{ $index }}][priority]"
                       min="1"
                       max="5"
                       value="{{ is_array($subtask) ? ($subtask['priority'] ?? 3) : ($subtask?->priority ?? 3) }}"
                       class="form-control bg-gray-600 border-gray-500 text-white text-center">
            </div>

            <!-- Due Date -->
            <div class="col-lg-2 col-md-4">
                <label class="form-label text-gray-300 small mb-1">Due Date</label>
                <input type="datetime-local"
                       name="subtasks[{{ $index }}][due_date]"
                       value="{{ is_array($subtask) ? ($subtask['due_date'] ?? '') : ($subtask?->due_date?->format('Y-m-d\TH:i') ?? '') }}"
                       class="form-control bg-gray-600 border-gray-500 text-white">
            </div>

            <!-- Remove Button -->
            <div class="col-lg-auto col-md-1 text-end">
                <button type="button"
                        class="btn btn-outline-danger btn-sm rounded-circle"
                        onclick="removeSubTask(this)"
                        title="Remove sub-task">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
</div>


@push('scripts')
<script>
    let subtaskIndex = {{ old('subtasks', $task->subtasks ?? collect())->count() }};

    function addSubTask() {
        const container = document.getElementById('subtasks-container');
        const div = document.createElement('div');
        div.className = 'card bg-gray-700 border border-gray-600 shadow-sm rounded-lg subtask-item mb-4';
        div.innerHTML = `
            <div class="card-body p-4">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label text-gray-300 small mb-1">Title</label>
                        <input type="text" name="subtasks[${subtaskIndex}][title]" class="form-control bg-gray-600 border-gray-500 text-white" placeholder="Sub-task title" required>
                    </div>
                    <div class="col-lg-2 col-md-3">
                        <label class="form-label text-gray-300 small mb-1">Status</label>
                        <select name="subtasks[${subtaskIndex}][status]" class="form-select bg-gray-600 border-gray-500 text-white">
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-4">
                        <label class="form-label text-gray-300 small mb-1">Assign to (optional)</label>
                        <select name="subtasks[${subtaskIndex}][assigned_to]" class="form-select bg-gray-600 border-gray-500 text-white">
                            <option value="">Not assigned</option>
                            @foreach($teamMembers as $member)
                                <option value="{{ $member->id }}">{{ $member->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-1 col-md-2">
                        <label class="form-label text-gray-300 small mb-1">Priority</label>
                        <input type="number" name="subtasks[${subtaskIndex}][priority]" min="1" max="5" value="3" class="form-control bg-gray-600 border-gray-500 text-white text-center">
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <label class="form-label text-gray-300 small mb-1">Due Date</label>
                        <input type="datetime-local" name="subtasks[${subtaskIndex}][due_date]" class="form-control bg-gray-600 border-gray-500 text-white">
                    </div>
                    <div class="col-lg-auto col-md-1 text-end">
                        <button type="button" class="btn btn-outline-danger btn-sm rounded-circle" onclick="removeSubTask(this)">
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
        // Ne supprime pas le dernier si c'est le seul (pour garder un champ vide)
        if (document.querySelectorAll('.subtask-item').length > 1) {
            button.closest('.subtask-item').remove();
        } else {
            // Réinitialise le dernier champ
            const inputs = button.closest('.subtask-item').querySelectorAll('input, select');
            inputs.forEach(input => {
                if (input.type === 'text' || input.type === 'datetime-local' || input.type === 'number') {
                    input.value = input.type === 'number' ? 3 : '';
                } else if (input.tagName === 'SELECT') {
                    input.selectedIndex = 0;
                }
            });
        }
    }
</script>
@endpush
