@extends('layouts.appW')

@section('contentW')
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h1 class="display-5 fw-bold text-gradient">{{ $project->name }}</h1>
            <div>
                <a href="{{ route('leader.projects.edit', $project) }}" class="btn btn-outline-primary me-2">Modifier</a>
                <form action="{{ route('leader.projects.destroy', $project) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Confirmer ?')">Supprimer</button>
                </form>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card bg-gray-800 text-white border-0 shadow mb-4">
                    <div class="card-body p-4">
                        <h4 class="fw-bold">Description</h4>
                        <p class="text-gray-300">{{ $project->description ?? 'Aucune description' }}</p>
                    </div>
                </div>

                <div class="card bg-gray-800 text-white border-0 shadow">
                    <div class="card-header bg-dark fw-bold">Tâches associées</div>
                    <div class="card-body p-0">
                        @if($project->tasks->count() > 0)
                            <ul class="list-group list-group-flush">
                                @foreach($project->tasks as $task)
                                    <li class="list-group-item bg-transparent text-white d-flex justify-content-between align-items-center py-3">
                                        <div>
                                            <strong>{{ $task->title }}</strong>
                                            <small class="d-block text-gray-400">{{ $task->description ?? '' }}</small>
                                        </div>
                                        <span class="badge bg-{{ $task->status === 'done' ? 'success' : 'warning' }}">
                                            {{ $task->status }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-center py-4 text-gray-400">Aucune tâche pour ce projet</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card bg-gray-800 text-white border-0 shadow">
                    <div class="card-header bg-dark fw-bold">Informations</div>
                    <div class="card-body">
                        <p><strong>Créé le :</strong> {{ $project->created_at->format('d/m/Y') }}</p>
                        <p><strong>Début :</strong> {{ $project->start_date ? $project->start_date->format('d/m/Y') : '-' }}</p>
                        <p><strong>Fin :</strong> {{ $project->end_date ? $project->end_date->format('d/m/Y') : '-' }}</p>
                        <p><strong>Membres assignés :</strong> {{ $project->members->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
