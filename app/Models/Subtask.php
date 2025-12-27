<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subtask extends Model
{
    //

    protected $fillable = [
        'task_id',
        'title',
        'status',
        'order_pos',
        'assigned_to',
        'due_date',
        'started_at',
        'completed_at',
        'priority',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'priority' => 'integer',
        'order_pos' => 'integer',
    ];

    // un observer pour auto-remplir les dates
    protected static function booted()
    {
        //Quand une subtask passe à in_progress → started_at = now()
        //Quand elle passe à completed → completed_at = now()
        //Si on repasse à pending → reset des dates
        static::saving(function ($subtask) {
            if ($subtask->isDirty('status')) {
                if ($subtask->status === 'in_progress' && is_null($subtask->started_at)) {
                    $subtask->started_at = now();
                }

                if ($subtask->status === 'completed' && is_null($subtask->completed_at)) {
                    $subtask->completed_at = now();
                }

                // Si on repasse à pending, on peut reset started_at
                if ($subtask->status === 'pending') {
                    $subtask->started_at = null;
                    $subtask->completed_at = null;
                }
            }
        });
    }

    // Relation vers la task parente
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    // Relation vers l’utilisateur assigné
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
