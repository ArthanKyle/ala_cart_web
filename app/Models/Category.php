<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name'];

    // Category can have many Messages
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
