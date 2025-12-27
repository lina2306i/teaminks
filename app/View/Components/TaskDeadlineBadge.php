<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Carbon\Carbon;

class TaskDeadlineBadge extends Component
{
    public $task;
    public $label;
    public $color;
    public $icon;

    public $overdue;


    public function __construct($task)
    {
        $this->task = $task;
        $this->overdue = false;

        if (!$task->due_date) {
            $this->label = 'No deadline';
            $this->color = 'secondary';
            $this->icon = 'fas fa-calendar-alt';
            return;
        }

        $now = Carbon::now();
        $due = Carbon::parse($task->due_date);

        $diffInSeconds = $due->diffInSeconds($now, false); // négatif si passé

        if ($diffInSeconds < 0) {
            // Overdue
            $this->overdue = true;
            $seconds = abs($diffInSeconds);
            $days = floor($seconds / 86400);
            $hours = floor(($seconds % 86400) / 3600);
            $minutes = floor(($seconds % 3600) / 60);

            $parts = array_filter([$days ? "{$days}d" : null, $hours ? "{$hours}h" : null, $minutes ? "{$minutes}m" : null]);
            $timeText = $parts ? implode(' ', $parts) : '0m';

            $this->label = "⛔ Overdue by {$days}d {$hours}h {$minutes}m";
            $this->color = 'danger';
            $this->icon = 'fas fa-exclamation-triangle';


        } else {
            // Encore dans le futur
            $days = floor($diffInSeconds / 86400);
            $hours = floor(($diffInSeconds % 86400) / 3600);
            $minutes = floor(($diffInSeconds % 3600) / 60);

            if ($days >= 2) {
                $this->label = "📅 Due in {$days}d {$hours}h";
                $this->color = 'success';
                $this->icon = 'fas fa-calendar-check';

            } elseif ($days == 1) {
                $this->label = "⏳ Due tomorrow";
                $this->color = 'info';
                $this->icon = 'fas fa-calendar-day';
            } elseif ($hours > 0) {
                $this->label = "⏳ Due in {$hours}h {$minutes}m";
                $this->color = 'warning';
                $this->icon = 'fas fa-hourglass-half';

            } else {
                $this->label = "⏰ Due in {$minutes}m";
                $this->color = 'danger'; // urgent !
                $this->icon = 'fas fa-clock';
            }
        }
    }

    public function render()
    {
        return view('components.task-deadline-badge');
    }
}
