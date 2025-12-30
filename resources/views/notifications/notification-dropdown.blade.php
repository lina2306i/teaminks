<!-- resources/views/components/notification-dropdown.blade.php -->
<div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle position-relative" data-bs-toggle="dropdown">
        Notifications
        @if(auth()->user()->notifications()->unread()->count() > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ auth()->user()->notifications()->unread()->count() }}
            </span>
        @endif
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
        @forelse(auth()->user()->notifications()->latest()->take(10)->get() as $notif)
            <li>
                <a class="dropdown-item {{ !$notif->read ? 'fw-bold' : '' }}" href="#">
                    <small class="text-muted">{{ $notif->title }}</small><br>
                    {{ Str::limit($notif->message, 50) }}
                </a>
            </li>
        @empty
            <li><span class="dropdown-item text-muted">Aucune notification</span></li>
        @endforelse
    </ul>
</div>
