<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Carbon\Carbon;

class SubtaskDeadlineBadge extends Component
{
    public $subtask;
    public $label;
    public $color;
    public $icon;
    public $overdue = false;

    public function __construct($subtask)
    {
        $this->subtask = $subtask;

        // Cas où la subtask n'a pas de due_date (ex: string simple ou array sans date)
        // $dueDate = $subtask['due_date'] ?? $subtask->due_date ?? null;
        // Récupérer la due_date (gère array ou objet Eloquent)
        $dueDate = is_array($subtask)
            ? ($subtask['due_date'] ?? null)
            : ($subtask->due_date ?? null);

        if (! $dueDate) {
            $this->label = 'No deadline';
            $this->color = 'secondary';
            $this->icon = 'fas fa-calendar-alt';
            return;
        }

        $now = Carbon::now();
        $due = Carbon::parse($dueDate);

        $diffInSeconds = $due->diffInSeconds($now, false);



        if ($diffInSeconds < 0) {
            //overdue
            $this->overdue = true;
            //$time = $this->formatTime(abs($diffInSeconds));
            $seconds = abs($diffInSeconds);
            $days = floor($seconds / 86400);
            $hours = floor(($seconds % 86400) / 3600);
            $minutes = floor(($seconds % 3600) / 60);

            $parts = array_filter([
                $days ? "{$days}d" : null,
                $hours ? "{$hours}h" : null,
                $minutes ? "{$minutes}m" : null
            ]);
            $timeText = $parts ? implode(' ', $parts) : '0m';

            $this->label = "⚠️Overdue by {$timeText}";
            $this->color = 'danger';
            $this->icon = 'fas fa-exclamation-triangle';
        } else {
            // À venir
            $days = floor($diffInSeconds / 86400);
            $hours = floor(($diffInSeconds % 86400) / 3600);
            $minutes = floor(($diffInSeconds % 3600) / 60);

            if ($days >= 2 ) {

                $this->label = "✅ Due – {$days}d ($hours)h {$minutes}m left";
                $this->color = 'success';
                $this->icon = 'fas fa-calendar-check';

            } elseif ($days == 1) {
                $this->label = "📅 Due Tomorrow";
                $this->color = 'info';
                $this->icon = 'fas fa-calendar-day';

            } elseif ($hours > 0) {
                $this->label = "📅 In  {$hours}h {$minutes}m ";
                $this->color = 'success';
                $this->icon = 'fas fa-calendar-check';
            } else {
                $this->label = "⏰ Due Today – {$minutes}m left";
                $this->color = 'danger';
                $this->icon = 'fas fa-clock';
            }

        }
            /*
                if ($days > 0) {
                    $this->label = "{$days}d {$hours}h {$minutes}m left";
                    $this->color = 'success';
                    $this->icon = 'fas fa-calendar-check';
                } elseif ($hours > 0) {
                    $this->label = "⏳ Due today in {$hours}h {$minutes}m";
                    $this->color = 'info';
                    $this->icon = 'fas fa-hourglass-half';
                } else {
                    $this->label = "⏰ Due today   in {$minutes}m";
                    $this->color = 'warning';
                    $this->icon = 'fas fa-clock';
                }
        }*/
    }

    /*
     Formate les secondes en "Xd Yh Zm" de manière intelligente
      private function formatTime(int $seconds): string
    {
        $days = floor($seconds / 86400);
        $hours = floor(($seconds % 86400) / 3600);
        $minutes = floor(($seconds % 3600) / 60);

        $parts = array_filter([
            $days > 0 ? "{$days}d" : null,
            $hours > 0 ? "{$hours}h" : null,
            $minutes > 0 ? "{$minutes}m" : null,
        ]);

        return $parts ? implode(' ', $parts) : '0m';
    }*/


    public function render()
    {
        return view('components.subtask-deadline-badge');
    }

}
