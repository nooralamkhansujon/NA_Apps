<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Post extends Model
{
    protected $guarded = [];

    public function user(){

    	return $this->belongsTo(\App\User::class);
    }

    public function likes()
    {
    	return  $this->hasMany(\App\Like::class,'content_id','id');
    }
    
    public function total_likes()
    {
        return DB::table('likes')->select(DB::raw("count(*) as total_likes"))->where('content_id',$this->id)->get()[0]->total_likes;
    }
}
 