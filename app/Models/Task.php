<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'project_id',
        'title',
        'description',
        'subtasks',        // nouveau
        'start_at',        // nouveau
        'due_date',
        'assigned_to',
        'status',
        'difficulty',      // nouveau
        'points',          // nouveau
        'has_subtasks', // si tu l'utilises encore
    ];


    protected $casts = [
        'due_date'  => 'date:d/m/Y H:i',         // affiche seulement la date
        'start_at'  => 'date:d/m/Y H:i',         // même format pour start_at
        'subtasks'  => 'array',              // important : Laravel convertit automatiquement JSON ↔ array
    ];

    //Bonus : Pour afficher aussi l’heure si besoin
    protected $casts1 = [
        'due_date' => 'datetime:d/m/Y H:i',
    ];


    // ou 'datetime' si tu veux aussi l'heure
    protected $casts2 = [ 'due_date' => 'date',  ];



    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // ← AJOUTE CETTE RELATION
    public function subtasks(): HasMany
    {
        return $this->hasMany(Subtask::class);
    }
}
