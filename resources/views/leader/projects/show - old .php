<!-- ... header ... -->

 <!-- ... header ... -->
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div class="flex-grow-1">
                 <!-- Titre du projet -->
                <h3 class="display-5 fw-bold text-gradient">{{ $project->name }}</h3>
                <!-- Infos dates + équipe (seulement si au moins une info existe) -->
                @if($project->start_date || $project->end_date || $project->due_date)
                    <p class="text-gray-400 mb-0">
                       From : {{ $project->start_date?->format('d/m/Y') ?? '?' }}
                        To : {{ $project->end_date?->format('d/m/Y') ?? '?' }}
                        or To : {{ $project->due_date?->format('d/m/Y') ?? '?' }}
                    </p>
                @endif
            </div>
            <div>
                <a href="{{ route('leader.projects.edit', $project) }}" class="btn btn-outline-primary me-2">Modifier</a>
                <form action="{{ route('leader.projects.destroy', $project) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Supprimer définitivement ce projet  ?')">Supprimer</button>
                </form>
            </div>
        </div>






{{-- Vue show.blade.php – Version finale propre
(Tu peux garder ta version, mais voici une version plus propre et sans doublons)
--}}
<div class="row g-4">
    <div class="col-lg-8">
        <!-- Description -->
        <div class="card bg-gray-800 text-white mb-4">
            <div class="card-body">
                <h4>Description</h4>
                <p>{{ $project->description ?? 'Aucune description.' }}</p>
            </div>
        </div>

        <!-- Tâches -->
        <div class="card bg-gray-800 text-white">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Tâches ({{ $project->tasks->count() }})</h4>
                <a href="{{ route('leader.tasks.create', $project) }}" class="btn btn-sm btn-contact">
                    + Nouvelle tâche
                </a>
            </div>
            <div class="card-body">
                @forelse($project->tasks as $task)
                    <div class="border-bottom border-gray-700 py-3">
                        <a href="{{ route('leader.tasks.show', $task) }}" class="text-white fw-bold">
                            {{ $task->title }}
                        </a>
                        <div class="small text-gray-400 mt-1">
                            @if($task->assignedTo) Assignée à {{ $task->assignedTo->name }} • @endif
                            {{ ucfirst($task->status) }} • {{ $task->subtasks->count() }} subtâche{{ $task->subtasks->count() > 1 ? 's' : '' }}
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-400 py-4">Aucune tâche.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Infos -->
        <div class="card bg-gray-800 text-white mb-4">
            <div class="card-header">Informations</div>
            <div class="card-body small">
                <p><strong>Leader :</strong> {{ $project->leader->name }}</p>
                <p><strong>Équipe :</strong> {{ $project->team?->name ?? 'Aucune' }}</p>
                <p><strong>Début :</strong> {{ $project->start_date?->format('d/m/Y') ?? '-' }}</p>
                <p><strong>Fin prévue :</strong>
                    <span class="{{ $project->is_overdue ? 'text-danger' : '' }}">
                        {{ $project->end_date?->format('d/m/Y') ?? 'Non définie' }}
                    </span>
                </p>
                <p><strong>Progression :</strong> {{ $project->progress }}%</p>
            </div>
        </div>

        <!-- Membres -->
        <div class="card bg-gray-800 text-white">
            <div class="card-header">Membres ({{ $project->users->count() }})</div>
            <div class="card-body">
                @foreach($project->users as $member)
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar avatar-sm bg-primary rounded-circle me-3 text-white">
                            {{ Str::upper(Str::substr($member->name, 0, 1)) }}
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold small">{{ $member->name }}</div>
                            <small class="text-gray-400">{{ $member->email }}</small>
                        </div>
                        @if($member->id === $project->leader_id)
                            <span class="badge bg-warning small">Leader</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
