
@extends('layouts.appW')

@section('contentW')
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h1 class="display-5 fw-bold text-gradient">Mes Projets</h1>
            <a href="{{ route('leader.projects.create') }}" class="btn btn-lg btn-contact">
                <i class="fas fa-plus me-2"></i> Nouveau projet
            </a>
        </div>

        @if($projects->count() > 0)
            <div class="row g-4">
                @foreach($projects as $project)
                    <div class="col-md-6 col-lg-4">
                        <div class="card bg-gray-800 text-white border-0 shadow hover:shadow-xl transition">
                            <div class="card-body">
                                <h5 class="fw-bold">{{ $project->name }}</h5>
                                <p class="text-gray-400 small">{{ Str::limit($project->description ?? '', 80) }}</p>

                                <div class="d-flex justify-content-between mt-3 small">
                                    <span><i class="fas fa-tasks me-1"></i> {{ $project->tasks_count ?? 0 }} tâches</span>
                                    <span><i class="fas fa-calendar-alt me-1"></i> {{ $project->created_at->format('d/m/Y') }}</span>
                                </div>

                                <div class="mt-4 d-flex gap-2">
                                    <a href="{{ route('leader.projects.show', $project) }}" class="btn btn-sm btn-outline-light">Détails</a>
                                    <a href="{{ route('leader.projects.edit', $project) }}" class="btn btn-sm btn-outline-primary">Modifier</a>
                                    <form action="{{ route('leader.projects.destroy', $project) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Confirmer la suppression ?')">Supprimer</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <p class="text-gray-400">Vous n'avez aucun projet pour le moment.</p>
                <a href="{{ route('leader.projects.create') }}" class="btn btn-lg btn-contact">Créer votre premier projet</a>
            </div>
        @endif
    </div>
</section>
@endsection
