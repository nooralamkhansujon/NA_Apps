<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    //

    protected $guard = [];

    public function image()
    {
        return $this->morphOne('App\Image', 'imageable');
    }
}
