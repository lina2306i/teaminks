<!-- Barre de progression (subtasks complétées) v1-->
                            @if($task->subtasks->count() > 0)
                                @php
                                    $completedSubtasks = $task->subtasks->where('status', 'completed')->count();
                                    $totalSubtasks = $task->subtasks->count();
                                    $progress = $totalSubtasks > 0 ? round(($completedSubtasks / $totalSubtasks) * 100) : 0;
                                @endphp

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between small text-gray-400 mb-1">
                                        <span>Progress</span>
                                        <span>{{ $completedSubtasks }} / {{ $totalSubtasks }} subtasks</span>
                                    </div>
                                    <div class="progress bg-gray-700" style="height: 8px;">
                                        <div class="progress-bar {{ $progress == 100 ? 'bg-success' : 'bg-primary' }}"
                                            role="progressbar"
                                            style="width: {{ $progress }}%">
                                        </div>
                                    </div>
                                    <small class="text-gray-400">{{ $progress }}% complete</small>
                                </div>
                            @endif

                            <!-- Description -->
                            <p class="text-gray-300 flex-grow-1 mb-4">
                                {{ $task->description ? Str::limit($task->description, 100) : 'No description' }}
                            </p>

                            <!-- Barre de progression intelligente v2-->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between small text-gray-400 mb-1">
                                    <span>Progress</span>
                                    <span>{{ $progress }}% complete</span>
                                </div>

                                <div class="progress bg-gray-700 rounded" style="height: 10px;">
                                    <div class="progress-bar {{ $progress == 100 ? 'bg-success' : ($progress > 0 ? 'bg-primary' : 'bg-secondary') }}"
                                        role="progressbar"
                                        style="width: {{ $progress }}%">
                                    </div>
                                </div>

                                <!-- Détail selon le cas -->
                                <small class="text-gray-400 d-block mt-1">
                                    @if($task->subtasks->count() > 0)
                                        {{ $task->subtasks->where('status', 'completed')->count() }} / {{ $task->subtasks->count() }} subtasks completed
                                    @else
                                        Task status: {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                    @endif
                                </small>
                            </div>

                            @php
                                // Calcul du progrès (à placer avant le bloc HTML)
                                if ($task->subtasks->count() > 0) {
                                    $completedSubtasks = $task->subtasks->where('status', 'completed')->count();
                                    $totalSubtasks = $task->subtasks->count();
                                    $progress = $totalSubtasks > 0 ? round(($completedSubtasks / $totalSubtasks) * 100) : 0;
                                } else {
                                    $progress = match($task->status) {
                                        'completed' => 100,
                                        'in_progress' => 50,
                                        'todo' => 0,
                                        default => 0,
                                    };
                                }
                            @endphp
                            <hr>
                            <!-- Barre de progression hybride intelligente v3-->
                            <div class="mb-4">
                                @php
                                    // 1. Cas avec subtasks → priorité à la complétion des subtasks
                                    if ($task->subtasks->count() > 0) {
                                        $completedSubtasks = $task->subtasks->where('status', 'completed')->count();
                                        $totalSubtasks = $task->subtasks->count();
                                        $progress = round(($completedSubtasks / $totalSubtasks) * 100);
                                        $progressText = "$completedSubtasks / $totalSubtasks subtasks";
                                        $progressType = 'subtasks';
                                    }
                                    // 2. Cas sans subtasks → progression basée sur status + temps écoulé
                                    else {
                                        $progressType = 'status_time';

                                        // Progression selon status
                                        $statusProgress = match($task->status) {
                                            'completed' => 100,
                                            'in_progress' => 50,
                                            default => 0, // todo / pending
                                        };

                                        // Progression temporelle (si start_at et due_date définis)
                                        $timeProgress = 0;
                                        if ($task->start_at && $task->due_date) {
                                            $now = now();
                                            $start = $task->start_at;
                                            $end = $task->due_date;

                                            if ($now->lt($start)) {
                                                $timeProgress = 0;
                                            } elseif ($now->gt($end)) {
                                                $timeProgress = 100;
                                            } else {
                                                $totalDuration = $start->diffInSeconds($end);
                                                $elapsed = $start->diffInSeconds($now);
                                                $timeProgress = round(($elapsed / $totalDuration) * 100);
                                            }
                                        }

                                        // Moyenne pondérée : 70% status + 30% temps (ou 100% status si pas de dates)
                                        $progress = $task->start_at && $task->due_date
                                            ? round(0.7 * $statusProgress + 0.3 * $timeProgress)
                                            : $statusProgress;

                                        $progressText = $task->start_at && $task->due_date
                                            ? "Status + Time ({$timeProgress}% elapsed)"
                                            : ucfirst(str_replace('_', ' ', $task->status));
                                    }

                                    // Couleur de la barre
                                    $barColor = match(true) {
                                        $progress == 100 => 'bg-success',
                                        $progress >= 70 => 'bg-info',
                                        $progress >= 40 => 'bg-warning',
                                        default => 'bg-danger',
                                    };
                                @endphp

                                <!-- Texte descriptif -->
                                <div class="d-flex justify-content-between small text-gray-400 mb-1">
                                    <span>Progress</span>
                                    <span>{{ $progress }}% • {{ $progressText }}</span>
                                </div>

                                <!-- Barre -->
                                <div class="progress bg-gray-700 rounded" style="height: 12px;">
                                    <div class="progress-bar {{ $barColor }} rounded"
                                        role="progressbar"
                                        style="width: {{ $progress }}%"
                                        aria-valuenow="{{ $progress }}"
                                        aria-valuemin="0"
                                        aria-valuemax="100">
                                    </div>
                                </div>

                                <!-- Indicateur visuel supplémentaire -->
                                <div class="mt-2 text-end">
                                    @if($progress == 100)
                                        <span class="text-success fw-bold"><i class="fas fa-check-circle me-1"></i> Completed</span>
                                    @elseif($task->status == 'completed')
                                        <span class="text-success fw-bold"><i class="fas fa-check-circle me-1"></i> Done</span>
                                    @elseif($progress >= 80)
                                        <span class="text-info"><i class="fas fa-fire me-1"></i> Almost there!</span>
                                    @elseif($progress < 30 && $task->due_date && now()->gt($task->due_date->subDays(2)))
                                        <span class="text-danger fw-bold"><i class="fas fa-exclamation-triangle me-1"></i> At risk</span>
                                    @endif
                                </div>
                            </div>
