<?php


namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Project;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        //'profile_photo_path',
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
        return $this->belongsToMany(Team::class, 'team_members')
                    ->withPivot('role', 'status')
                  //->withPivot('status')
                    ->withTimestamps();
    }

    // Relations avec d'autres modèles for leader interface ::
    public function teamsAsLeader()
    {
       return $this->hasMany(Team::class, 'leader_id');
                 // ->belongsToMany(Team::class, 'team_user')
                  //->wherePivot('role', 'leader') // ou selon ta logique
                  //->withTimestamps();
         //->hasMany(Team::class, 'leader_id');
    }

    public function teamsAsMember()
    {
        return $this->belongsToMany(Team::class, 'team_members')
                    ->withPivot('role', 'status')   // pending ou accepted
                    ->wherePivot('role', 'member')
                    ->withTimestamps();



    }


    public function tasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    // for project
    /**
     * Les projets dont cet utilisateur est le leader/créateur
     */
    public function ownedProjects()
    {
        return $this->hasMany(Project::class, 'leader_id');
        //return $this->hasMany(Project::class, 'user_id');
    }

    /**
     * Les projets dont cet utilisateur est membre (via table pivot)
     */
    public function projects(): HasMany
    {
        return $this-> hasMany(Project::class, 'leader_id');
                  //belongsToMany(Project::class, 'leader_id');
    }

    /**
     * Toutes les tâches assignées à cet utilisateur
     */
    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    // Optionnel : si tu veux une méthode qui retourne tous les projets (owned + member)
    public function allProjects()
    {
        $owned = $this->ownedProjects()->pluck('id');
        $member = $this->projects()->pluck('id');

        $allIds = $owned->merge($member)->unique();

        return Project::whereIn('id', $allIds)->get();
    }

    // fait appel on project model .calender function
    public function ledProjects()
    {
        return $this->hasMany(Project::class, 'leader_id');
    }
    // ou derictly created hire
    public function calendar()
    {
        $projects = Project::where('leader_id', auth()->id())
            ->with('team')
            ->get()
            ->map(function ($project) {
                return [
                    'title' => $project->title . ' (' . $project->progress . '%)',
                    'start' => optional($project->start_date)->format('Y-m-d'),
                    'end'   => optional($project->end_date)?->addDay()->format('Y-m-d'),
                    'url'   => route('leader.projects.show', $project),
                    'color' => $project->is_overdue
                        ? '#dc3545'
                        : ($project->progress == 100 ? '#28a745' : '#007bff'),
                    'team'  => $project->team?->name ?? 'No team',
                    'progress' => $project->progress,
                ];
            });

        return view('leader.projects.calendar', compact('projects'));
    }


    //end proj ;

}
