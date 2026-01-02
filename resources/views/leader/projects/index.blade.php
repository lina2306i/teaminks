
@extends('layouts.appW')

@section('contentW')
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h1 class="display-5 fw-bold text-gradient">My Projets</h1>
            <a href="{{ route('leader.projects.create') }}" class="btn btn-lg btn-contact">
                <i class="fas fa-plus me-2"></i> New Project
            </a>
            <a href="{{ route('leader.projects.calendar') }}" class="btn btn-outline-info">
                <i class="fas fa-calendar-alt me-2"></i> Calendar View
            </a>
        </div>
        <form method="GET" action="{{ route('leader.projects.index') }}" class="mb-5">
            <div class="row g-3 align-items-end">
                <!-- Recherche -->
                <div class="col-md-4">
                    <label class="form-label fw-medium">Search by name</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="form-control form-control-lg" placeholder="Search Project name...">
                </div>
                <!-- Filtre statut -->
                <div class="col-md-3">
                    <label class="form-label fw-medium">Status</label>
                    <select name="status" class="form-select form-select-lg">
                        <option value="">All statuses</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                    </select>
                </div>
                <!-- Tri -->
                <div class="col-md-3">
                    <label class="form-label fw-medium">Sort by</label>
                    <select name="sort" class="form-select form-select-lg" onchange="this.form.submit()">
                        <option value="created_at" {{ request('sort', 'created_at') == 'created_at' ? 'selected' : '' }}>Creation date</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                        <option value="progress" {{ request('sort') == 'progress' ? 'selected' : '' }}>Progress</option>
                        <option value="deadline" {{ request('sort') == 'deadline' ? 'selected' : '' }}>Deadline</option>
                    </select>
                </div>
                <!-- Direction (asc/desc) <button type="submit" class="btn btn-contact btn-lg w-100">Apply</button> -->
                <div class="col-md-2">
                    <button type="submit" name="direction" value="{{ request('direction', 'desc') === 'desc' ? 'asc' : 'desc' }}" class="btn btn-contact btn-lg w-100 btn-outline-secondary">
                        <i class="fas fa-sort{{ request('direction', 'desc') === 'desc' ? '-down' : '-up' }}"></i> Apply
                    </button>
                </div>

            </div>
        </form>

        <!-- Résultats -->
        @if($projects->count() > 0)
            <div class="row g-4">
                @foreach($projects as $project)
                    <div class="col-md-6 col-lg-4">
                        <div class="card bg-gray-800 text-white border-0 shadow hover:shadow-xl transition">
                            <div class="card-body justify-content-between  d-flex flex-column">
                                <!-- Title + Team -->
                                <div>
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="fw-bold mb-0">{{ $project->name }}</h5>
                                        @if($project->is_overdue)
                                            <span class="badge bg-danger small">Overdue</span>
                                        @endif
                                    </div>

                                    @if($project->team)
                                        <small class="text-info d-block mb-2">
                                            <i class="fas fa-users me-1"></i>{{ $project->team->name }}
                                        </small>
                                    @endif

                                    <!-- Description -->
                                    <p class="text-gray-400 small mb-3">
                                        {{ Str::limit($project->description ?? 'No description provided.', 100) }}
                                    </p>
                                </div>
                                {{--  --}}
                                <!-- Progress -->
                                @php
                                     $total = $project->tasks_count ?? 0; // de meme pas no needed
                                     $completed = $project->completed_tasks_count ?? 0;
                                    $progress = $total > 0 ? round(($completed / $total) * 100) : 0;
                                    // remplacer $project->progress == $progress
                                @endphp
                                <div class="mt-3">
                                    <div class="d-flex justify-content-between small text-gray-400 mb-1">
                                        <span>Progress</span>
                                        <span class="fw-bold text-primary">{{ $progress }}% Completed</span>
                                    </div>
                                    <div class="progress bg-gray-700 rounded " style="height: 10px;">
                                        <div class="progress-bar bg-primary transition-all duration-50"
                                            style="width: {{ $progress }}%"
                                            role="progressbar">
                                        </div>
                                    </div>
                                    <small class="text-gray-400">{{ $progress }}% Completed </small>
                                </div>
                                <!-- Stats <i class="fas fa-calendar-alt me-1"></i> -->
                                <div class="d-flex flex-wrap gap-3 mb-3 small   text-gray-400">
                                    <span><i class="fas fa-tasks me-1"></i> {{ $project->tasks_count ?? 0 }} Task{{ $project->tasks_count != 1 ? 's' : '' }}</span>
                                    <span><i class="fas fa-users me-1"></i> {{ $project->members->count() }} Member{{ $project->members->count() > 1 ? 's' : '' }}</span>
                                    <span><i class="fas fa-clock me-1"></i> Created {{ $project->created_at->format('h:i - d/m/Y') }}</span>
                                </div>
                                <!-- Dates
                                    {{--@if($project->end_date)
                                    <small class="mt-2 text-{{ $project->is_overdue ? 'danger' : 'warning' }}">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        Ends on: {{ $project->end_date->format('d/m/Y') }}
                                        @if($project->is_overdue) (	late-overdue !) @endif
                                    </small>
                                @endif --}} -->
                                <!-- Dates -->
                                @if($project->start_date || $project->end_date)
                                    <small class="d-block mb-3 text-{{ $project->is_overdue ? 'danger' : 'warning' }}">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        @if($project->start_date && $project->end_date)
                                            {{ $project->start_date->format('d/m/Y') }} → {{ $project->end_date->format('d/m/Y') }}
                                        @elseif($project->end_date)
                                            Ends on : {{ $project->end_date->format('d/m/Y') }}
                                            @if($project->is_overdue)
                                                <span class="text-danger fw-bold"> (Overdue!)</span>
                                            @endif
                                        @else
                                            Starts at: {{ $project->start_date->format('d/m/Y') }}
                                        @endif
                                    </small>
                                @endif

                                <!-- Actions -->
                                <div class="mt-4 d-flex gap-2">
                                    <a href="{{ route('leader.projects.show', $project) }}"
                                        class="btn btn-sm btn-outline-light"><i class="fas fa-eye me-1"></i>Details
                                    </a>
                                    @if($project->leader_id === auth()->id())
                                        <a href="{{ route('leader.projects.edit', $project) }}"
                                            class="btn btn-sm btn-outline-primary"><i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                        <form action="{{ route('leader.projects.destroy', $project) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this project? This action cannot be undone.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Confirmer la suppression ?')">
                                                <i class="fas fa-trash-alt me-1"></i> Delete</button>
                                        </form>
                                     @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- Pagination (if you have many projects) :: Pagination Bootstrap 5 - Compact & Clean -->
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-5">
                {{--Et si plus tard tu ajoutes des filtres (recherche, statut, etc.), tu pourras faire : %
                     $projects->appends(request()->query())->links() // pour le filtre
                    {{ $projects->links() }}
                     <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm mb-0">  <!-- pagination-sm pour la rendre small -->
                            {!! $projects->onEachSide(1)->links('vendor.pagination.bootstrap-4 || 5') !!}
                        </ul>
                    </nav>
                --}}
                @if($projects->hasPages())
                     {{ $projects->onEachSide(2)->links() }}
                @endif
            </div>

            <!-- Debug (à supprimer plus tard) -->
            <div class="text-center text-gray-400 small mt-3">
                Debug: {{ $projects->total() }} projets au total |
                Page actuelle: {{ $projects->currentPage() }} / {{ $projects->lastPage() }}
            </div>


        @else
            <div class="text-center py-5">
                <i class="fas fa-folder-open fa-4x text-gray-600 mb-4"></i>
                <h3 class="text-gray-400 mb-3">No projects yet</h3>
                <p class="text-gray-500 mb-4">Start organizing your work by creating your first project.</p>
                <a href="{{ route('leader.projects.create') }}" class="btn btn-lg btn-contact px-5">
                    <i class="fas fa-plus me-2"></i> Create your first projet
                </a>
            </div>
        @endif
    </div>
</section>
@endsection
