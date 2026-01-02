<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostComment extends Model
{
    protected $fillable = ['post_id', 'user_id', 'content'];

    protected $with = ['user']; // Charge automatiquement l'utilisateur

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(PostComment::class, 'parent_id')->where('is_approved', true);
    }

    public function parent()
    {
        return $this->belongsTo(PostComment::class, 'parent_id');
}
}
