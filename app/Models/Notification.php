<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'notifiable_id',
        'notifiable_type',
        'read'
    ];

    protected $casts = [
        'read' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notifiable()
    {
        return $this->morphTo();
    }

    public function markAsRead()
    {
        $this->update(['read' => true]);
    }
}
