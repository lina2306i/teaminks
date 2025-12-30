<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'from_id',
        'title',
        'message',
        'type',              // nouveau : info, warning, error...
        'notifiable_id',
        'notifiable_type',
        'read',
        'data',

    ];

    protected $casts = [
        'read' => 'boolean',
        'data' => 'array',

    ];

    /* =======================
        RELATIONS
    ======================== */    public function user()  //destinataire
    {
        return $this->belongsTo(User::class , 'user_id');
    }
    public function from() // expéditeur (si manuel)
    {
        return $this->belongsTo(User::class, 'from_id');
    }

    // 🔗 Lien polymorphique (Task, Post, Project…)
    public function notifiable()
    {
        return $this->morphTo();
    }

    /* =======================
        HELPERS
    ======================== */

    public function markAsRead()
    {
       // $this->update(['read' => true]);
        // or
        if (!$this->read) {
            $this->update(['read' => true]);
        }
        //ou  if (!$this->unread) {$this->update([''=> false]);}
    }
     public function scopeUnread($query)
    {
        return $query->where('read', false);
    }




}
