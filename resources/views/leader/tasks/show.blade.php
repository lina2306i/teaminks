@extends('layouts.appW')

@section('contentW')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card bg-gray-800 text-white shadow-2xl">
                <div class="card-body p-5">
                    <h1 class="display-6 fw-bold mb-4">{{ $task->title }}</h1>
                    <p class="text-gray-300 mb-4">{{ $task->description ?? 'No description' }}</p>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <strong>Project:</strong> {{ $task->project->name }}
                        </div>
                        <div class="col-md-6">
                            <strong>Assigned to:</strong> {{ $task->assignedTo?->name ?? 'Not assigned' }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Status:</strong>
                            <span class="badge {{ $task->status == 'completed' ? 'bg-success' : ($task->status == 'in_progress' ? 'bg-warning' : 'bg-secondary') }}">
                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <strong>Due date:</strong> {{ $task->due_date ? $task->due_date->format('d M Y') : 'Not set' }}
                        </div>
                    </div>
                    <div class="d-flex gap-3 mb-4">
                        <a href="{{ route('leader.tasks.edit', $task) }}" class="btn btn-warning">Edit Task</a>
                        <form action="{{ route('leader.tasks.destroy', $task) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this task?')">Delete Task</button>
                        </form>
                        <a href="{{ route('leader.tasks.index') }}" class="btn btn-outline-light ms-auto">← Back to Tasks</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
