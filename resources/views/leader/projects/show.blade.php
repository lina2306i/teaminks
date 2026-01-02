@extends('layouts.appW')
{{--  Et dans ta vue show.blade.php, remplace partout $project->members par $project->team?->members :: 3 remplacement faite --}}
@section('contentW')
<section class="py-5">
    <div class="container">
        <!-- ...Header : ... :: Titre + Back + Actions -->
        <!-- ... ... <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4 mb-5"> -->
        <div class="d-flex   justify-content-between align-items-start  mb-5">
            <div class="flex-grow-1">
                <a href="{{ route('leader.projects.index') }}" class="btn btn-outline-light ">
                        ← Back to Project
                </a>
                <!-- Titre du projet display-5 -->
                <h1 class=" display-6  fw-bold text-gradient mb-3">{{ $project->name }}</h1>
                <!-- Infos dates + équipe -->
                @php
                    $hasDates = $project->start_date || $project->end_date || $project->due_date ;
                    // $hasDates = $project->start_date || $project->end_date;
                    $hasTeam = $project->team;
                @endphp
                @if($hasDates || $hasTeam)
                    <div class="d-flex flex-wrap gap-4 text-gray-400 small">
                        <!-- Dates -->
                        @if($hasDates)
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-calendar-alt text-primary"></i>
                                <span>
                                    @if($project->start_date && $project->end_date || $project->due_date)
                                        <p class="text-gray-400 mb-0">
                                            From : {{ $project->start_date?->format('H:i - d/m/Y') ?? '-' }}
                                            To :{{ $project->end_date?->format('H:i - d/m/Y') ?? '-' }}
                                            or To : {{ $project->due_date?->format('H:i - d/m/Y') ?? '-' }}
                                        </p>
                                    @elseif($project->start_date)
                                       <i class="fas fa-solid fa-hourglass-start text-info"   ></i>  Starting from the {{ $project->start_date?->format('H:i - d/m/Y') ?? '-' }}
                                    @elseif($project->end_date)
                                        <i class="fas fa-solid fa-hourglass-end text-info"  ></i> Due date :{{ $project->end_date?->format('H:i - d/m/Y') ?? '-' }}
                                    @else
                                        <i class="fas fa-solid fa-hourglass-end text-info"  ></i> Due date : {{ $project->due_date?->format('H:i - d/m/Y') ?? '-' }}
                                    @endif
                                </span>
                            </div>
                        @endif
                        <br>
                        <!-- Équipe associée -->
                        @if($hasTeam)
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-users text-info"></i>
                                <span>Team : <strong>{{ $project->team->name }}</strong></span>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Boutons d'action -->
            <div class="text-end">
                <a href="{{ route('leader.projects.edit', $project) }}"
                class="btn btn-outline-primary btn-lg me-2">
                    <i class="fas fa-edit me-2"></i> Edit
                </a>
                <!--button type="button"
                        class="btn btn-outline-danger btn-lg"
                        data-bs-toggle="modal"
                        data-bs-target="#deleteProjectModal">
                    <i class="fas fa-trash-alt me-2"></i> Delete
                </!--button-->
                <form action="{{ route('leader.projects.destroy', $project) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="btn btn-outline-danger btn-lg"
                            onclick="return confirm('Delete this project permanently  ?')">
                        <i class="fas fa-trash-alt me-2"></i> Delete
                    </button>
                </form>
            </div>
        </div>

        <!-- no used --- Modal de confirmation de suppression (plus propre qu’un confirm() JS basique) -->
        <div class="modal fade" id="deleteProjectModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content bg-gray-800 text-white border-0">
                    <div class="modal-header border-0">
                        <h5 class="modal-title">Confirmer la suppression</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Êtes-vous sûr de vouloir <strong>supprimer définitivement</strong> le projet :</p>
                        <p class="fw-bold text-warning mb-0">"{{ $project->name }}"</p>
                        <small class="text-gray-400 d-block mt-3">
                            Cette action est irréversible. Toutes les tâches associées seront également supprimées.
                        </small>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <form action="{{ route('leader.projects.destroy', $project) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger">Oui, supprimer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Description + Tâches || :: border-0 shadow -->
            <div class="col-lg-8">
                <!-- Description -->
                <div class="card bg-gray-800 text-white border-0 shadow mb-4">
                    <div class="card-body p-4">
                        <h4 class="fw-bold">Description</h4>
                        <!--p class="text-gray-300">
                            {{-- $project->description ?? 'Any description existe' --}}
                        </!--p-->
                        <!-- Description courte si elle existe -->
                        @if($project->description)
                            <p class="mt-3 text-gray-300 lead">
                                {{ Str::limit($project->description, 200) }}
                            </p>
                        @else
                            <p class="text-gray-400 italic">No description provided.</p>
                        @endif
                    </div>
                </div>

                <!-- Tâches -->
                <div class="card bg-gray-800 text-white border-0 shadow">
                    <div id="tasks-section" class="card-header bg-dark fw-bold  d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fa-solid fa-list-check"></i> Related Tasks :
                            <span class="text-primary"> ({{ $project->tasks->count() }})</span>
                        </h4>
                        <a href="{{ route('leader.tasks.create', ['project' => $project->id]) }}"
                           class="btn btn-sm btn-contact px-4">
                           <i class="fas fa-plus me-2"></i> New Taske
                        </a>
                    </div>

                    <div class="card-body p-4">
                        @if($project->tasks->count() > 0)
                            <ul class="list-group-item list-group-flush">
                                @foreach($project->tasks as $task)
                                    {{-- <li class="list-group-item bg-transparent text-white d-flex justify-content-between align-items-center py-3"> --}}
                                    <li class="list-group-item bg-transparent  border-0 px-4 py-4 hover:bg-gray-700 transition">
                                        <div class="d-flex justify-content-between align-items-start gap-4">
                                            <!-- Contenu principal de la tâche -->
                                            <div class="flex-grow-1">
                                                <a href="{{ route('leader.tasks.show', $task) }}"
                                                   class="text-white fw-boldfs-5 hover:text-blue-400 transition">
                                                    <strong>{{ $task->title }}</strong>
                                                </a>


                                                <div class="d-flex flex-wrap gap-3  text-gray-400">
                                                    <!-- Assignation -->
                                                    @if($task->assignedTo)
                                                        <span>
                                                            <i class="fas fa-user me-1 text-primary"></i>
                                                           Assigned to : <strong class="text-gray-500">{{ $task->assignedTo->name }}</strong>
                                                        </span>
                                                    @else
                                                        <span class="text-gray-500">
                                                            <i class="fas fa-user-slash me-1"></i> Not Assigned
                                                        </span>
                                                    @endif

                                                    <!-- Priorité (si tu as une colonne priority ==difficulty dans Task) -->
                                                    @if($task->difficulty ?? null)
                                                        <span>
                                                            <i class="fas fa-flag me-1 text-{{ $task->difficulty == 'hard' ? 'danger' : ($task->difficulty == 'medium' ? 'warning' : 'info') }}"></i>
                                                            {{ ucfirst($task->difficulty) }} priority
                                                        </span>
                                                    @endif

                                                    <!-- Progression subtasks -->
                                                    @if($task->subtasks->count() > 0)
                                                        @php
                                                            $completedSubtasks = $task->subtasks->where('status', 'completed')->count();
                                                        @endphp
                                                        <span>
                                                            <i class="fas fa-check-square me-1 text-success"></i>
                                                            {{ $completedSubtasks }} / {{ $task->subtasks->count() }} subtasks complered
                                                        </span>
                                                    @endif
                                                </div>
                                                <!-- description fa-reguler fa-audio-description-->
                                                <div class="small text-gray-400 mt-1">
                                                   @if($task->description)
                                                        <span>
                                                            <i class="fas fa-brands  fa-adversal me-1 text-primary"></i>
                                                                Description :
                                                        </span>
                                                        <p class="text-gray-400 small mt-2 mb-3">
                                                            {{ Str::limit($task->description, 150) }}
                                                        </p>
                                                    @endif
                                                </div>
                                                <div class="small text-gray-400 mt-1">
                                                </div>
                                            </div>

                                            <!-- Badges à droite -->
                                            <!-- Statut + subtasks count +details show -->
                                            <div class="text-end ms-3">
                                                <!-- Statut -->
                                                <div class="mb-3">
                                                    <span class="badge bg-{{
                                                        $task->status === 'completed' ? 'success' :
                                                        ($task->status === 'in_progress' ? 'warning' :
                                                        ($task->status === 'todo' ? 'secondary' : 'info'))
                                                    }} text-uppercase px-3 py-2">
                                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                                    </span>
                                                </div>

                                                <!-- Nombre de subtasks -->
                                                <div class=" mb-3 text-gray-500 small">
                                                    <i class="fas fa-list-check me-1"></i>
                                                    {{ $task->subtasks->count() }}  subtask{{ $task->subtasks->count() > 1 ? 's' : '' }}
                                                </div>
                                                <div class=" d-flex gap-3 mb-2">
                                                     <a  href="{{ route('leader.tasks.show', $task) }}"
                                                        class="btn btn-sm btn-outline-info">
                                                        <i class="fas fa-eye me-1"></i> Details
                                                    </a>
                                                </div>

                                            </div>
                                        </div>
                                    </li>
                               {{-- @empty
                                    <p class="text-center text-gray-400 py-4">No Taske.</p> --}}
                                @endforeach
                            </ul>
                        @else
                            <div class="text-center py-8">
                                <i  class="fas fa-tasks fa-3x text-gray-600 mb-4"></i>
                                <p class="text-center  mb-4 py-5 text-gray-400">• No tasks for this project.</p>
                                <a href="{{ route('leader.tasks.create', ['project' => $project->id]) }}"
                                    class="btn btn-sm btn-contact px-5">
                                    <i class="fas fa-plus me-2"></i>Create the first task
                                </a>
                            </div>

                         @endif
                    </div>
                </div>
            </div>
            <!-- Sidebar : Infos+ Progression + Membres -->
            <div class="col-lg-4">

                <!-- Infos -->
                <div class="card bg-gray-800 text-white mb-4 border-0 shadow">
                    <div class="card-header bg-dark fw-bold">
                        <i class=" fas fa-light fa-circle-info"></i> Project Information
                    </div>
                    <div class="card-body small">
                        <p><i class=" fa-slab fa-regular fa-circle-user text-info"></i><strong> Leader :</strong> {{ $project->leader->name }}</p>
                        <p><i class="fas fa-users text-info"></i>
                            <strong> Team :</strong> {{ $project->team?->name ?? 'No Team' }}
                            <strong>   ||   </strong> {{ $project->team ? $project->team->name : 'No Team attached' }}
                        </p>
                        <p><i class="fas fa-regular fa-calendar-plus text-info"   > </i><strong> Created at :</strong> {{ $project->created_at ? $project->created_at->format('h:i - d/m/Y') : '-' }}</p>
                        <p><i class="fas fa-solid fa-hourglass-start text-info"   > </i><strong> Start at  :</strong>
                           {{ $project->start_date?->format('H:i - d/m/Y') ?? '-' }}
                        </p>
                        <p> <i class="fas fa-solid fa-hourglass-end text-info"   > </i><strong> End at : :</strong> {{ $project->end_date?->format('H:i - d/m/Y') ?? '-' }}
                                <strong> || or :</strong> {{ $project->due_date?->format('h:i - d/m/Y') ??  ' - ' }}
                        </p>
                        <p><i class="fas fa-solid fa-hourglass-end text-info" >  </i><strong> Expected End :</strong>
                            <span class="{{ $project->is_overdue ? 'text-danger' : '' }}">
                                {{ $project->end_date?->format('h:i  - d/m/Y') ?? 'Not defined' }}
                                @if($project->is_overdue) (late, overdue, delayed!) @endif
                            </span>
                        </p>
                        <p><i class="fas fa-slab fa-regular fa-user text-info"></i><strong > Members assigned :</strong>
                            {{ $project->team?->members->count() ?? 0 }}
                            <a href="#members-section" class="text-primary hover:underline fw-bold">
                                {{ $project->team?->members->count() ?? 0 }}
                            </a>
                        </p>
                        <p><i class="fas fa-solid fa-list-check text-info"></i><strong> Tasks :</strong>
                            <a href="#tasks-section" class="text-primary hover:underline fw-bold">
                                 {{ $project->tasks->count() }}
                            </a>
                        </p>
                    </div>
                </div>
                <!-- Barre de progression -->
                <div class="card bg-gray-800 text-white border-0 shadow mb-4">

                    <div class="card-header fw-bold d-flex  bg-dark justify-content-between mb-2">
                        <strong>Project progress</strong>
                        <span class="text-primary fw-bold">{{ $project->progress }}%</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="progress" style="height: 12px;">
                            <div class="progress-bar bg-gradient-primary"
                                 role="progressbar"
                                 style="width: {{ $project->progress }}%"
                                 aria-valuenow="{{ $project->progress }}"
                                 aria-valuemin="0"
                                 aria-valuemax="100">
                                 {{ $project->progress }}%
                            </div>
                        </div>
                        <span class="text-primary fw-bold">{{ $project->progress }}% complered</span>
                    </div>
                </div>
                <hr class="bg-light opacity-25 my-3">
                <!-- Membres -->
                <div class="card bg-gray-800 text-white border-0 shadow">
                    <div class="card-header bg-dark fw-bold">
                        <h5 class="fw-bold mb-3" id="members-section">
                            <i class="fa-sharp-duotone fa-solid fa-users"></i> Members of the projet
                            ({{ $project->team?->members->count() ?? 0 }})
                        </h5>
                    </div>
                    <div class="card-body">
                        {{-- @if( $project->team?->members->count() > 0) || @foreach($project->members as $member) ou b1 @foreach($project->users as $member)→ à supprimer, car $project->users n’existe pas !::foreach users not members sans ul & li --}}
                        @if($project->team && $project->team?->members->count() > 0)
                            <ul class="list-unstyled">
                                <div class="d-flex flex-column gap-3">
                                   @foreach($project->team->members as $member)
                                        <li class="d-flex align-items-center mb-2">
                                            <div class="d-flex align-items-center">
                                                <!-- Avatar avec photo ou initiales -->
                                                <div class="me-3">
                                                    @if($member->profile_photo_path ?? false)
                                                        <img src="{{ $member->profile_photo_url }}"
                                                            alt="{{ $member->name }}"
                                                            class="rounded-circle"
                                                            width="48"
                                                            height="48"
                                                            style="object-fit: cover;">
                                                    @else
                                                        <div class="avatar avatar-md bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center"
                                                            style="width: 48px; height: 48px;">
                                                            <span class="fw-bold fs-5">
                                                                {{--  Str::substr($member->name, 0, 1) --}}
                                                                {{ Str::upper(Str::substr($member->name, 0, 2)) }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="fw-bold small">
                                                        <i class="fas fa-user me-2"></i>{{ $member->name }}
                                                    </div>
                                                    <small class="text-gray-400">{{ $member->email }}</small>
                                                </div>
                                                <!-- Leader badge -->
                                                @if($member->id === $project->leader_id)
                                                    <span class="badge bg-warning ms-auto small">Leader</span>
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </div>
                            </ul>
                        @else
                            <p class="text-gray-400 mb-0">No members assigned (project without a team).</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
