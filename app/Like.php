<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    
    public function user()
    {
    	$this->belongsTo(\App\User::class);
    }
    public function post()
    {
    	$this->belongsTo(\App\Post::class);
    }
}
