<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Notifications\TaskDeadlineReminder;
use Carbon\Carbon;

class SendTaskDeadlineReminders extends Command
{
    protected $signature = 'tasks:send-deadline-reminders';
    protected $description = 'Envoie des rappels pour les tâches dues demain ou aujourd\'hui';

    public function handle()
    {
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        // Tâches dues aujourd'hui (J-0)
        $tasksToday = Task::whereDate('due_date', $today)
                          ->whereNotNull('assigned_to')
                          ->with('assignedTo')
                          ->get();

        // Tâches dues demain (J-1)
        $tasksTomorrow = Task::whereDate('due_date', $tomorrow)
                             ->whereNotNull('assigned_to')
                             ->with('assignedTo')
                             ->get();

        foreach ($tasksToday as $task) {
            $task->assignedTo->notify(new TaskDeadlineReminder($task, 'today'));
            $this->info("Rappel J-0 envoyé pour tâche {$task->id}");
        }

        foreach ($tasksTomorrow as $task) {
            $task->assignedTo->notify(new TaskDeadlineReminder($task, 'tomorrow'));
            $this->info("Rappel J-1 envoyé pour tâche {$task->id}");
        }

        $this->info('Tous les rappels envoyés !');
    }
}
