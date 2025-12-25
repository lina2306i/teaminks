@extends('layouts.appW')
{{-- resources/views/leader/team/create.blade.php – Créer une équipe --}}
@section('contentW')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card bg-gray-800 text-white shadow-2xl">
                <div class="card-header bg-gradient-info text-center py-4">
                    <h3 class="fw-bold">Create New Team</h3>
                </div>
                <div class="card-body p-5">
                    <form action="{{ route('leader.team.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Team Name</label>
                            <input type="text" name="name" class="form-control bg-gray-700 border-gray-600 text-white" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Description (optional)</label>
                            <textarea name="description" rows="4" class="form-control bg-gray-700 border-gray-600 text-white"></textarea>
                        </div>
                        <div class="d-flex gap-3 justify-content-end">
                            <a href="{{ route('leader.team.index') }}" class="btn btn-outline-light">Cancel</a>
                            <button type="submit" class="btn btn-primary btn-lg">Create Team</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
