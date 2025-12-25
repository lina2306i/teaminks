<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PostLike;
use App\Models\PostComment;

class Post extends Model
{
protected $fillable = [
        'title',          // ← ajouté
        'content',
        'excerpt',        // ← ajouté
        'image',
        'user_id',
        'team_id',
        'visibility',     // ← ajouté
        'pinned',         // ← ajouté
    ];
    // Casts pour les types
    protected $casts = [
        'pinned' => 'boolean',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function likes()
    {
        return $this->hasMany(PostLike::class);
    }

    public function comments()
    {
        return $this->hasMany(PostComment::class)->latest();
    }
    protected $withCount = ['likes', 'comments'];

    /*public function getExcerptAttribute()
    {
        return $this->excerpt ?? Str::limit(strip_tags($this->content), 150);
    }*/



}
