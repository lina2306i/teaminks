@extends('layouts.appW')
{{-- -resources/views/leader/team/show.blade.php – Gestion complète d'une équipe --}}
@section('contentW')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="display-5 fw-bold text-white">{{ $team->name }}</h1>
            <p class="text-gray-400">{{ $team->description ?? 'No description' }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('leader.team.edit', $team) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i> Edit Team
                    </a>
                    <a href="{{ route('leader.team.index') }}" class="btn btn-outline-light">
                        ← Back to teams
                    </a>
        </div>

    </div>

    <!-- Invitation Code -->
    <div class="card bg-gray-800 text-white shadow mb-5">
        <div class="card-body d-flex  flex-column justify-content-between align-items-center gap-3">
            <div>
                <strong class="fw-semibold">Invitation Code:</strong>
                <code class="bg-gray-700 px-4 py-2 rounded ms-3 fs-4 fw-bold">{{ $team->invite_code }}</code>
                <p class="text-gray-400 small mt-2 mb-0">
                    Share this code with members so they can join your team.
                </p>
                <p>Or share this link:
                    <a href="{{ url('/join-team/' . $team->invite_code) }}" class="text-info">
                        {{ url('/join-team/' . $team->invite_code) }}
                    </a>
                </p>
                </div>
            <button class="btn btn-info btn-lg" onclick="navigator.clipboard.writeText('{{ $team->invite_code }}'); showToast('Code copied!')">
                <i class="fas fa-copy me-2"></i> Copy Code
            </button>
        </div>
    </div>

    <div class="row g-5">
        <!-- Pending Requests -->
        <div class="col-lg-6">
            <div class="card bg-gray-800 shadow-lg">
                <div class="card-header bg-warning text-dark fw-bold">
                    Pending Requests ({{ $team->pendingMembers->count() }})
                </div>
                <div class="card-body">
                    @forelse($team->pendingMembers as $user)
                        <div class="d-flex justify-content-between align-items-center py-3 border-bottom border-gray-700">
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $user->profile ?? asset('images/default-avatar.png') }}" class="rounded-circle" style="width: 40px; height: 40px;">
                                <div>
                                    <strong>{{ $user->name }}</strong><br>
                                    <small class="text-gray-400">{{ $user->position ?? '' }}</small>
                                </div>
                            </div>
                            <div>
                                <form action="{{ route('leader.team.accept', ['team' => $team, 'user' => $user]) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-success btn-sm">Accept</button>
                                </form>
                                <form action="{{ route('leader.team.reject', ['team' => $team, 'user' => $user]) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Reject</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-400 text-center py-4">No pending requests</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Current Members -->
        <div class="col-lg-6">
            <div class="card bg-gray-800 shadow-lg">
                <div class="card-header bg-success text-white fw-bold">
                    Team Members ({{ $team->members->count() }})
                </div>
                <div class="card-body">
                    @forelse($team->members as $member)
                        <div class="d-flex justify-content-between align-items-center py-3 border-bottom border-gray-700">
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $member->profile ?? asset('images/default-avatar.png') }}" class="rounded-circle" style="width: 40px; height: 40px;">
                                <div>
                                    <strong>{{ $member->name }}</strong><br>
                                    <small class="text-gray-400">{{ $member->position ?? '' }}</small>
                                </div>
                            </div>
                            @if(auth()->id() !== $member->id)
                                <form action="{{ route('leader.team.remove', ['team' => $team, 'user' => $member]) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm">Remove</button>
                                </form>
                            @endif
                        </div>
                    @empty
                        <p class="text-gray-400 text-center py-4">No members yet</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Bonus : Simple Stats -->
    <div class="card bg-gray-800 shadow-lg mt-5">
        <div class="card-header bg-info text-white fw-bold">
            Team Statistics
        </div>
        <div class="card-body">
            <div class="row text-center">
                <div class="col-md-4">
                    <h4>{{ $team->projects->count() }}</h4>
                    <p class="text-gray-400">Projects</p>
                </div>
                <div class="col-md-4">
                    <h4>{{ $team->projects->sum(fn($p) => $p->tasks->count()) }}</h4>
                    <p class="text-gray-400">Total Tasks</p>
                </div>
                <div class="col-md-4">
                    <h4>{{ $team->projects->sum(fn($p) => $p->tasks->where('status', 'completed')->count()) }}</h4>
                    <p class="text-gray-400">Completed Tasks</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        function showToast(message) {
            alert(message); // ou utilise un toast plus joli si tu en as un
        }
    </script>
@endpush
