    <!-- Smile, breathe, and go slowly. - Thich Nhat Hanh -->
{{-- -
@if($task->due_date)
    <div class="deadline-badge badge text-end mb-4">
        <span class="badge fs-6 px-3 py-2 fw-semibold
            {{ $isOverdue ? 'bg-danger' :
            ($isToday ? 'bg-primary' :
            ($isTomorrow ? 'bg-info' :
            ($isSoon ? 'bg-warning text-dark' : 'bg-success'))) }}">

            @if($isOverdue)
                <i class="fas fa-exclamation-triangle me-1"></i>
                  ⛔ Overdue by {{ abs( $daysLeft ) }} days
            @elseif($isToday)
                <i class="fas fa-clock me-1"></i>
                  ⏰ Due today
            @elseif($isTomorrow)
                <i class="fas fa-hourglass-half me-1"></i>
                  ⏳ Tomorrow
            @else
                <i class="fas fa-calendar-check me-1"></i>
                  📅 {{ $daysLeft }} days left
            @endif

        </span>
    </div>
@else
    <div class="text-end mb-4">
        <span class="badge bg-secondary">No deadline</span>
    </div>
@endif
 --}}

{{-- -
@if($label)
    <div class="deadline-badge text-end mb-4">
        <span class="badge px-3 py-2 fw-semibold fs-6
            @class([
                'bg-danger' => $state === 'overdue',
                'bg-primary' => $state === 'today',
                'bg-info' => $state === 'tomorrow',
                'bg-warning text-dark' => $state === 'soon',
                'bg-success' => $state === 'normal',
            ])
        ">
            @if($state === 'overdue')
                <i class="fas fa-exclamation-triangle me-1"></i>
                  ⛔
            @elseif($state === 'today')
                <i class="fas fa-clock me-1"></i>
                  ⏰
            @elseif($state === 'tomorrow')
                <i class="fas fa-hourglass-half me-1"></i>
                  ⏳
            @else
                <i class="fas fa-calendar-check me-1"></i>
                  📅
            @endif

            {{  $label }}
        </span>
    </div>
@else
    <div class="text-end mb-4">
        <span class="badge bg-secondary">No deadline</span>
    </div>
@endif
 --}}
@props([
    'task',
    'label' => $label ?? '',
    'color' => $color ?? 'secondary',
    'overdue' => $overdue ?? false,
    'icon' => $icon ?? 'fas fa-calendar-alt'
])

<div class="deadline-badge text-end mb-4">
    <span class="badge fs-6 px-3 py-2 fw-semibold  bg-{{ $color }}
                {{ $overdue ? 'animate-pulse' : '' }}"
            title="{{ $label }}">
        <i class="{{ $icon }} me-2"></i>
        <span class="text-truncate" style="max-width: 150px;">
            {{ $label }}
        </span>
    </span>

</div>

@push('style')
    <style>
        .deadline-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;

            max-width: 100%;
            padding: 0.45rem 0.75rem;
            border-radius: 0.5rem;

            font-size: 0.85rem;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .deadline-badge .text {
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 180px;
        }

        @media (max-width: 768px) {
            .deadline-badge .text {
                max-width: 120px;
            }
        }
        .animate-pulse {
            /*animation: pulse 1.5s infinite;*/
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

    </style>
@endpush
