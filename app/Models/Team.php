<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Team extends Model
{
    //

        use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'leader_id',
        'invite_code'
    ];

    /*public static function boot()
    {
        parent::boot();

        static::creating(function ($team) {
            $team->invite_code = strtoupper(Str::random(8)); // ex: A1B2C3D4
        });
    }*/
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($team) {
            // Génère un code unique de 8 caractères alphanumériques en majuscules
            do {
                $code = strtoupper(Str::random(8));
            } while (Team::where('invite_code', $code)->exists());

            $team->invite_code = $code;
        });
    }

    // Relations


    /**
     * Membres de l’équipe
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'team_members')
                    ->wherePivot('status', 'accepted')
                    ->withPivot('role', 'status')
                    ->withTimestamps();
    }

    /**
     * Leader de l’équipe
     */
    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }


    /** leader interface ...
     * Membres en attente de validation
     */
    public function pendingMembers()
    {
        return $this->belongsToMany(User::class, 'team_members')
                    ->wherePivot('status', 'pending')
                    ->withTimestamps();
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
