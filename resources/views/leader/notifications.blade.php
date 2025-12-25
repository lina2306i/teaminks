@extends('layouts.appW')

@section('contentW')
<section class="py-5">
    <div class="container">
        <h1 class="display-5 fw-bold text-gradient mb-5">Notifications</h1>

        @if($notifications->count() > 0)
            <ul class="list-group">
                @foreach($notifications as $notification)
                    <li class="list-group-item bg-gray-800 text-white border-0 mb-2 rounded shadow">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong>{{ $notification->data['title'] ?? 'Notification' }}</strong>
                                <p class="mb-0">{{ $notification->data['message'] ?? '' }}</p>
                            </div>
                            <small class="text-gray-400">{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-center text-gray-400 py-5">Aucune notification pour le moment.</p>
        @endif
    </div>
</section>
@endsection
