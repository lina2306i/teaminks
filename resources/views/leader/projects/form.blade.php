@extends('layouts.appW')

@section('contentW')
<section class="py-5 min-vh-100 d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="card feature-card text-white shadow-lg border-0">
                    {{-- card-header bg-gradient-primary text-center py-4   fw-bold fs-4  --}}
                    <div class="card-header bg-gradient-primary text-center py-4 ">
                        <h3 class="fw-bold mb-0">
                            {{ $project->exists ? 'Edit the project': 'Create a new project' }}
                        </h3>
                    </div>

                    <div class="card-body p-5">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ $project->exists ? route('leader.projects.update', $project) : route('leader.projects.store') }}">
                            @csrf
                            @if($project->exists) @method('PUT') @endif

                            <div class="mb-4">
                                <label class="form-label fw-medium">Name of the projet</label>
                                <input type="text" name="name" id="name"
                                        value="{{ old('name', $project->name ?? '') }}"
                                        class="form-control form-control-lg bg-gray-700 border-0 text-white
                                        @error('name') is-invalid @enderror" required autofocus>
                                @error('name')
                                    <div class=" text-danger small mt-1 invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-medium">Description</label>
                                <textarea name="description" rows="5" id="description"
                                    class="form-control bg-gray-700 border-0 text-white form-control-lg @error('description') is-invalid @enderror">
                                    {{ old('description', $project->description ?? '') }}</textarea>
                                @error('description')
                                    <div class="text-danger small mt-1  invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Dates -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label  for="start_date" class="form-label fw-semibold ">Start Date & Time</label>
                                    <input type="datetime-local" name="start_date" id="start_date"
                                            value="{{ old('start_date', $project->start_date?->format('Y-m-d\TH:i')) }}"
                                            class="form-control form-control-lg bg-gray-700 border-0 text-white @error('start_date') is-invalid @enderror">
                                    @error('start_date')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium">End Date & Time</label>
                                    <input type="datetime-local" name="end_date"
                                            value="{{ old('end_date', $project->end_date?->format('Y-m-d\TH:i')) }}"
                                            id="end_date"   class="form-control form-control-lg bg-gray-700 border-0 text-white
                                            @error('end_date') is-invalid @enderror">
                                    @error('end_date')
                                        <div class="text-danger small mb-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium">End Date</label>
                                    <input type="datetime-local" name="due_date" value="{{ old('due_date', $project->due_date?->format('Y-m-d\TH:i')) }}"
                                           class="form-control form-control-lg @error('due_date') is-invalid @enderror">
                                </div>
                            </div>
                            <!-- Associated Team (optional) -->
                            @if(auth()->user()->teamsAsLeader->count() > 0)
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Associated team (optional)</label>
                                    <select name="team_id" class="form-select form-select-lg bg-gray-700 border-0 text-white">
                                        <option value="">No Team Associated</option>
                                        @foreach(auth()->user()->teamsAsLeader as $team)
                                            <option value="{{ $team->id }}" {{ old('team_id', $project->team_id) == $team->id ? 'selected' : '' }}>
                                                {{ $team->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('team_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @else
                                <div class="alert alert-info small">
                                    <i class="fas fa-info-circle me-2"></i>
                                    You are not leading any team yet. Create a team first to associate it with projects.
                                </div>
                            @endif
                            <!-- Submit Button -->
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-lg btn-contact text-white fw-bold">
                                    {{ $project->exists ? 'Save changes': 'Create project' }}
                                </button>
                            </div>

                            <!-- Cancel / Back Link -->
                            <div class="text-center mt-3">
                                <a href="{{ route('leader.projects.index') }}" class="text-gray-400 hover:text-white small">
                                    ← Back to projects list
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
