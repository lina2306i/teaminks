@extends('layouts.appW')

@section('contentW')
<div class="container py-5">
    <h1 class="display-5 fw-bold text-white">Profil de {{ $user->name }}</h1>
    <div class="card bg-gray-800 text-white shadow-lg mt-4">
        <div class="card-body text-center">
            <img src="{{ $user->profile ?? asset('images/user-default.jpg') }}" class="rounded-circle mb-3" width="120">
            <h3>{{ $user->name }}</h3>
            <p class="text-gray-400">{{ $user->email }}</p>
            <p class="text-gray-400">Rôle : {{ $user->role ?? 'Membre' }}</p>
            <!-- Ajoute ici stats, tâches assignées, etc. -->
        </div>
    </div>
</div>
@endsection
