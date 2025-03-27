<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'parent_id',
        'name',
        'iconpath',
        'chat',
        'timestamp',
        'hasUnread',
        'isMe',
    ];

    // Message belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Message belongs to a Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Message may have replies (Self-referencing relationship)
    public function replies()
    {
        return $this->hasMany(Message::class, 'parent_id');
    }

    // Message may belong to a parent message
    public function parent()
    {
        return $this->belongsTo(Message::class, 'parent_id');
    }
}
