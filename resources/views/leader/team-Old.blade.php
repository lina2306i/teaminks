@extends('layouts.appW')
{{-- resources/views/leader/team.blade.php – Gestion de l’équipe --}}
@section('contentW')
<div class="container py-5">
    <h1 class="display-5 fw-bold text-white mb-5">Team Management v0</h1>

    <div class="row g-5">
        <!-- Pending Requests -->
        <div class="col-lg-6">
            <div class="card bg-gray-800 border-0 shadow-lg rounded-xl">
                <div class="card-header bg-warning text-dark fw-bold py-3">
                    <i class="fas fa-clock me-2"></i> Pending Join Requests ({{ $pendingRequests->count() }})
                </div>
                <div class="card-body">
                    @if($pendingRequests->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($pendingRequests as $request)
                                <li class="list-group-item bg-transparent text-white d-flex justify-content-between align-items-center py-3 border-bottom border-gray-700">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $request->profile ?? asset('images/default-avatar.png') }}"
                                             class="rounded-circle"
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                        <div>
                                            <strong>{{ $request->name }}</strong><br>
                                            <small class="text-gray-400">{{ $request->position ?? 'No position' }}</small>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <form action="{{ route('leader.team.accept', $request) }}" method="POST">
                                            @csrf
                                            <button class="btn btn-success btn-sm">Accept</button>
                                        </form>
                                        <form action="{{ route('leader.team.reject', $request) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-sm">Reject</button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-400 text-center py-4">No pending requests.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Current Members -->
        <div class="col-lg-6">
            <div class="card bg-gray-800 border-0 shadow-lg rounded-xl">
                <div class="card-header bg-success text-white fw-bold py-3">
                    <i class="fas fa-check me-2"></i> Current Members ({{ $team->members->count() }})
                </div>
                <div class="card-body">
                    @if($team->members->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($team->members as $member)
                                <li class="list-group-item bg-transparent text-white d-flex justify-content-between align-items-center py-3 border-bottom border-gray-700">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $member->profile ?? asset('images/default-avatar.png') }}"
                                             class="rounded-circle"
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                        <div>
                                            <strong>{{ $member->name }}</strong><br>
                                            <small class="text-gray-400">{{ $member->position ?? 'No position' }}</small>
                                        </div>
                                    </div>
                                    @if(auth()->id() !== $member->id)
                                        <form action="{{ route('leader.team.remove', $member->pivot) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-outline-danger btn-sm">Remove</button>
                                        </form>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-400 text-center py-4">No members yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
