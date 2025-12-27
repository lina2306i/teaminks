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
        'start_at',
        'due_date',
        'assigned_to',
        'status',
        'difficulty',
        'points',
        'pinned',
    ];
    //'subtasks', nouveau == 'has_subtasks', //partie json a delete



    protected $casts = [
        'due_date'  => 'date:d/m/Y H:i',         // affiche seulement la date
        'start_at'  => 'date:d/m/Y H:i',         // même format pour start_at
        // 'subtasks'  => 'array',  important : Laravel convertit automatiquement JSON ↔ array
        'pinned' => 'boolean',
        'pinned_at' => 'datetime',
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

    // Optionnel : scope pour récupérer les tâches épinglées en premier
    public function scopePinnedFirst($query)
    {
        return $query->orderByDesc('pinned')->orderByDesc('created_at');
    }

}
