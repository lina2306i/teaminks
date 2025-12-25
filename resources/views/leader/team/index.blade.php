@extends('layouts.appW')
{{-- resources/views/leader/team/index.blade.php – Liste des équipes --}}
@section('contentW')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h1 class="display-5 fw-bold text-white">My Teams</h1>
        <a href="{{ route('leader.team.create') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-plus me-2"></i> Create Team
        </a>
    </div>

    @if(auth()->user()->teamsAsLeader->count() > 0)
        <div class="row g-4">
            @foreach(auth()->user()->teamsAsLeader as $team)
                <div class="col-md-6 col-lg-4">
                    <div class="card bg-gray-800 text-white shadow-lg hover:shadow-xl transition-all">
                        <div class="card-body">
                            <h5 class="fw-bold">{{ $team->name }}</h5>
                            <p class="text-gray-400 small">{{ $team->description ?? 'No description' }}</p>
                            <div class="mt-3">
                                <span class="badge bg-info">{{ $team->members->count() }} members</span>
                                <span class="badge bg-warning ms-2">{{ $team->pendingMembers->count() }} pending</span>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('leader.team.show', $team) }}" class="btn btn-outline-light btn-sm w-100">
                                    Manage Team
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-10">
            <i class="fas fa-users fa-5x text-gray-600 mb-4"></i>
            <h3 class="text-gray-400">No team created yet</h3>
            <p class="text-gray-500 mb-4">Start by creating your first team!</p>
            <a href="{{ route('leader.team.create') }}" class="btn btn-primary btn-lg">
                Create Your First Team
            </a>
        </div>
    @endif
</div>
@endsection
