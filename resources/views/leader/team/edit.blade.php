{{-- resources/views/leader/team/edit.blade.php — page dédiée à la modification du nom et de la description d’une équipe. --}}
@extends('layouts.appW')

@section('contentW')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">
            <div class="card bg-gray-800 text-white shadow-2xl border-0 rounded-xl">
                <div class="card-header bg-gradient-warning text-center py-4">
                    <h3 class="fw-bold mb-0">
                        <i class="fas fa-edit me-2"></i> Edit Team
                    </h3>
                </div>

                <div class="card-body p-5">
                    <form action="{{ route('leader.team.update', $team) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Team Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">Team Name</label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   class="form-control bg-gray-700 border-gray-600 text-white"
                                   value="{{ old('name', $team->name) }}"
                                   required>
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-5">
                            <label for="description" class="form-label fw-semibold">Description (optional)</label>
                            <textarea name="description"
                                      id="description"
                                      rows="6"
                                      class="form-control bg-gray-700 border-gray-600 text-white"
                                      placeholder="Describe the purpose or goals of this team...">{{ old('description', $team->description) }}</textarea>
                            @error('description')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-3 justify-content-end">
                            <a href="{{ route('leader.team.show', $team) }}" class="btn btn-outline-light">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-warning btn-lg px-5 fw-bold">
                                <i class="fas fa-save me-2"></i> Update Team
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
