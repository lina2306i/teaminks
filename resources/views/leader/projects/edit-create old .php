@extends('layouts.appW')

@section('contentW')
<section class="py-5 min-vh-100 d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="card feature-card text-white shadow-lg border-0">
                    <div class="card-header bg-gradient-primary text-center py-4 fw-bold fs-4">
                        {{ $project->exists ? 'Modifier le projet' : 'Nouveau projet' }}
                    </div>

                    <div class="card-body p-5">
                        <form method="POST" action="{{ $project->exists ? route('leader.projects.update', $project) : route('leader.projects.store') }}">
                            @csrf
                            @if($project->exists) @method('PUT') @endif

                            <div class="mb-4">
                                <label class="form-label fw-medium">Nom du projet</label>
                                <input type="text" name="name" value="{{ old('name', $project->name ?? '') }}"
                                       class="form-control form-control-lg @error('name') is-invalid @enderror" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-medium">Description</label>
                                <textarea name="description" rows="5" class="form-control form-control-lg @error('description') is-invalid @enderror">{{ old('description', $project->description ?? '') }}</textarea>
                                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Date de début</label>
                                    <input type="date" name="start_date" value="{{ old('start_date', $project->start_date ?? '') }}"
                                           class="form-control form-control-lg @error('start_date') is-invalid @enderror">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Date de fin</label>
                                    <input type="date" name="end_date" value="{{ old('end_date', $project->end_date ?? '') }}"
                                           class="form-control form-control-lg @error('end_date') is-invalid @enderror">
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-medium">Équipe associée (optionnel)</label>
                                <select name="team_id" class="form-control form-control-lg @error('team_id') is-invalid @enderror">
                                    <option value="">-- Aucune équipe --</option>
                                    @foreach($teams as $team)
                                        <option value="{{ $team->id }}" {{ old('team_id', $project->team_id ?? '') == $team->id ? 'selected' : '' }}>
                                            {{ $team->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('team_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-lg btn-contact text-white fw-bold">
                                    {{ $project->exists ? 'Enregistrer les modifications' : 'Créer le projet' }}
                                </button>
                            </div>


                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

// ou bien

@php $project = $project ?? new App\Models\Project @endphp
@include('leader.projects.form')
