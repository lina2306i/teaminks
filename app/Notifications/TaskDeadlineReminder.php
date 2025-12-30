<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Task;

class TaskDeadlineReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Task $task, public string $type) // 'tomorrow' ou 'today'
    {}

    public function via($notifiable)
    {
        return ['mail', 'database']; // + 'broadcast' si tu veux push realtime
    }

    public function toMail($notifiable)
    {
        $message = $this->type === 'tomorrow'
            ? "⚠️ La tâche « {$this->task->title} » est due demain !"
            : "🔴 La tâche « {$this->task->title} » est due aujourd'hui !";

        return (new MailMessage)
                    ->subject('Rappel deadline tâche')
                    ->line($message)
                    ->action('Voir la tâche', url('/tasks/' . $this->task->id))
                    ->line('Merci de vérifier et compléter à temps.');
    }

    public function toArray($notifiable)
    {
        return [
            'task_id' => $this->task->id,
            'title'   => $this->task->title,
            //'type'    => $this->type,
            'type'    => $this->type === 'today' ? 'warning' : 'info',
            //'message' => $this->type === 'tomorrow' ? 'Due demain' : 'Due aujourd\'hui',
            'message' => $this->type === 'tomorrow'
                ? "Due demain ({$this->task->due_date->format('d M Y')})"
                : "Due aujourd'hui !",
            // Polymorphisme : important !
            'notifiable_id'   => $this->task->id,
            'notifiable_type' => Task::class,
        ];
    }
}
