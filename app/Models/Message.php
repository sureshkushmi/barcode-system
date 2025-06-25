<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Message extends Model
{
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'parent_id',
        'subject',
        'message',
        'is_read',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function replies()
    {
        return $this->hasMany(Message::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Message::class, 'parent_id');
    }
}
