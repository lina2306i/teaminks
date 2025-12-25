<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['name', 'description', 'due_date', 'leader_id', 'team_id'];

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
