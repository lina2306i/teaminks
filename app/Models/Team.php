<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Team extends Model
{
    //

        use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'leader_id',
    ];

    /**
     * Membres de l’équipe
     */
    public function members()
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('status')
                    ->withTimestamps();
    }

    /**
     * Leader de l’équipe
     */
    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }
}
