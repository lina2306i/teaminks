<?php


namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable ,HasApiTokens;


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'position',
        'birthdate',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    //add helpers métiers :
    public function isLeader(): bool
    {
        return $this->role === 'leader';
    }

    public function isMember(): bool
    {
        return $this->role === 'member';
    }
    //moi added
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    //spint 2 : relation with teams
     // équipes du user
    public function teams()
    {
        return $this->belongsToMany(Team::class)
                    ->withPivot('status')
                    ->withTimestamps();
    }

    // Relations avec d'autres modèles for leader interface ::
    public function teamsAsLeader()
    {
        return $this->hasMany(Team::class, 'leader_id');
    }

    public function teamsAsMember()
    {
        return $this->belongsToMany(Team::class, 'team_members')
                    ->withPivot('status') // pending ou accepted
                    ->withTimestamps();
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'leader_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }


}
