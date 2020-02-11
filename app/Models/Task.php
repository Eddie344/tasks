<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title', 'description', 'status_id'
    ];

    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }
}
