<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = [
        'project_id',
        'title',
        'description',
        'notes',
        'start_at',
        'due_date',
        'assigned_to',
        'status',
        'difficulty',
        'points',
        'priority',
        'pinned',
        'reminder_at',
        'notes',
        'attachments_count',
        'comments_count',
    ];
    //'subtasks', nouveau == 'has_subtasks', //partie json a delete



    protected $casts = [
        'due_date'  => 'date:d/m/Y H:i',         // affiche seulement la date
        'start_at'  => 'date:d/m/Y H:i',         // même format pour start_at
        // 'subtasks'  => 'array',  important : Laravel convertit automatiquement JSON ↔ array
        'priority' => 'integer',
        'pinned' => 'boolean',
        'pinned_at' => 'datetime',
        'reminder_at' => 'datetime',
        'attachments_count' => 'integer',
        'comments_count' => 'integer',

    ];

    //Bonus : Pour afficher aussi l’heure si besoin
    //protected $casts1 = ['due_date' => 'datetime:d/m/Y H:i',];
    // ou 'datetime' si tu veux aussi l'heure
    //protected $casts2 = [ 'due_date' => 'date',  ];

    // Relations
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // RELATION with subtask
    public function subtasks(): HasMany
    {
        return $this->hasMany(Subtask::class)->orderBy('order_pos');
        // ou bien
        //return $this->hasMany(Subtask::class);

    }

    // Priority label
    public function getPriorityLabelAttribute()
    {
        return match($this->priority) {
            1 => 'Urgent',
            2 => 'High',
            3 => 'Normal',
            4 => 'Low',
            5 => 'Very Low',
            default => 'Normal'
        };
    }
    // Optionnel : scope pour récupérer les tâches épinglées en premier
    public function scopePinnedFirst($query)
    {
        return $query->orderByDesc('pinned')->orderByDesc('created_at');
    }

    //option; pas utiliser ::! Puis dans la vue : {{ $task->progress }}% -- barre de progression intelligente,
    public function getProgressAttribute()
    {
        if ($this->subtasks->count() > 0) {
            $completed = $this->subtasks->where('status', 'completed')->count();
            return round(($completed / $this->subtasks->count()) * 100);
        }

        return match($this->status) {
            'completed' => 100,
            'in_progress' => 50,
            default => 0,
        };
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(TaskAttachment::class);
    }

    // Accessor pour le nombre de fichiers (utile dans les vues)
    public function getAttachmentsCountAttribute(): int
    {
        return $this->attachments()->count();
    }
}
