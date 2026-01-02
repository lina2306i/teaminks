<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Project extends Model
{
    protected $fillable = [
        'name',
        'description',
        'leader_id',  //'user_id',
        'team_id',
        'start_date',
        'end_date',  // date de fin prévue (remplace due_date)
        'due_date', // //'deadline', || garde due_date si tu l'utilises ailleurs

    ];

  /*protected $casts = [
        'due_date' =>'date:d/m/Y H:i', // format d'affichage joli
        'start_date' => 'date:d/m/Y H:i',// format d'affichage joli
        'end_date' => 'date:d/m/Y H:i',// format d'affichage joli
    ]; */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
        'due_date'   => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    //protected $dates = ['start_date', 'end_date']; // Laravel < 9
    // OU (recommandé Laravel 9+) :
    /*protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date'   => 'date',
        ];
    }*/

    protected $appends = ['progress', 'is_overdue'];
    /**
     * Le leader / créateur du projet
     */

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
    /**
     * Tous les membres du projet (y compris le leader)
     * // Optionnel : si plus tard tu veux assigner des membres directement au projet (sans team)
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_user');
    }

    /**
     * Les tâches du projet
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Accesseur : nombre de tâches complétées
     */
    public function getCompletedTasksCountAttribute(): int
    {
        return $this->tasks()->where('status', 'completed')->count();
    }

    /**
     * Accesseur : nombre total de tâches
     */
    public function getTotalTasksCountAttribute(): int
    {
        return $this->tasks()->count();
    }

    /**
     * Accesseur : pourcentage de progression
     */
    public function getProgressAttribute(): int
    {
        // Progression automatique basée sur les tâches
       // $total = $this->total_tasks_count;
       // if ($total === 0) return 0;
       // $completed = $this->tasks()->where('status', 'completed')->count();
       // $completed = $this->attributes['completed_tasks_count'] ?? 0;
       //return round(($completed / $total) * 100);
       // return (int) round(($completed / $total) * 100);


       //rendre tes pages projets ultra-pro:: Calcul automatique de la progression du projet
       //stocker progress en base : il est calculé à la volée, toujours à jour.

       $tasks = $this->tasks;
        if ($tasks->count() === 0) {
            return 0;
        }
        $totalWeight = 0;
        $completedWeight = 0;
        foreach ($tasks as $task) {
            // Poids de la tâche = nombre de subtasks + 1 (la tâche elle-même compte)
            $subtasksCount = $task->subtasks->count();
            $taskWeight = $subtasksCount + 1;

            $totalWeight += $taskWeight;

            // Si la tâche est completed → tout son poids est gagné
            if ($task->status === 'completed') {
                $completedWeight += $taskWeight;
            } else {
                // Sinon, on compte seulement les subtasks complétés
                $completedSubtasks = $task->subtasks->where('status', 'completed')->count();
                $completedWeight += $completedSubtasks;
            }
        }
        return $totalWeight > 0 ? (int) round(($completedWeight / $totalWeight) * 100) : 0;

    }

    // calcules le progress à la volée via ton accessor getProgressAttribute(), tu peux utiliser un scope pour filtrer :
   /* public function scopeStatus($query, $status)
    {
       return match($status) {
        'active' => $query->where('start_date', '<=', now())
                           ->where('end_date', '>=', now()),
        'completed' => $query->where('end_date', '<', now())
                             ->where('progress', 100),
        'overdue' => $query->where('end_date', '<', now())
                            ->where('progress', '<', 100),
        default => $query
        };
    } */


    /**
     * Vérifie si le projet est en retard :: Overdue
     */
    public function getIsOverdueAttribute(): bool
    {
        return $this->end_date && Carbon::parse($this->end_date)->isPast() && $this->progress < 100;
        //return $this->end_date && Carbon::parse($this->end_date)->isPast();
        // return $this->due_datee && Carbon::parse($this->due_date)->isPast();
    }

    /**
     * Les membres du projet = membres de l'équipe associée
     */
    public function members()
    {
        //return $this->belongsToMany(User::class, 'team_user', 'team_id', 'leader_id')
                   // ->wherePivot('team_id', $this->team_id);
        return $this->team ? $this->team->members() : collect([]);
    }
    // Dans Project.php → tu peux garder ça pour plus tard (si projet sans team)
    // Ou mieux, crée un accessor pour plus de clarté :: Pour le calendrier : membres via team
    public function getMembersAttribute()
    {
        return $this->team ? $this->team->members : collect();
    }
    /**
     * Compteur de membres (accessor pour la vue)
     */
    public function getMembersCountAttribute(): int
    {
        return $this->team ? $this->team->members()->count() : 0;
    }




    // ===================================================================
    // Méthodes utilitaires (optionnelles mais pratiques)
    // ===================================================================

    /**
     * Formatage joli des dates pour les vues
     */
    public function getFormattedStartDateAttribute(): string
    {
        return $this->start_date?->format('H:i - d/m/Y') ?? '-';
    }

    public function getFormattedEndDateAttribute(): string
    {
        return $this->end_date?->format('H:i - d/m/Y') ?? '-';
    }
}
