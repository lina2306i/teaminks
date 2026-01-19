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
    // Méthode pour régénérer le code d'invitation
    public function regenerateInviteCode()
    {
        $this->invite_code = Str::random(8);
        $this->save();
    }
    // Relations


    /**
     * Membres de l’équipe
     */
    /* // Relation avec les membres (table pivot team_members)
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_members', 'team_id', 'user_id')
                    ->withPivot('status', 'role') // ajoute les colonnes pivot que tu utilises
                    ->withTimestamps();
    }

    // Scope ou accesseur pour les membres acceptés
    public function members()
    {
        return $this->users()->wherePivot('status', 'accepted');
    }
        , 'team_id', 'user_id'
        */
    public function members()
    {
        return $this->belongsToMany(User::class, 'team_members')
                    ->wherePivot('status', 'accepted')
                    ->withPivot('role', 'status')
                    ->withPivot('created_at', 'updated_at')
                    ->withTimestamps();
        //return $this->users()->wherePivot('role', 'member');
    }

    /**
     * Leader de l’équipe
     */
    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
        //return $this->users()->wherePivot('role', 'leader')->first();
    }

    public function admins()
    {
        return $this->users()->wherePivot('role', 'admin');
    }

    /** leader interface ...
     * Membres en attente de validation
     */
    public function pendingMembers()
    {
        return $this->belongsToMany(User::class, 'team_members')
                    ->wherePivot('status', 'pending')
                    ->withTimestamps();
        //return $this->users()->wherePivot('accepted', false);
    }

    public function getMemberIdsAttribute()
    {
        return $this->members->pluck('id')->all();
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'team_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
