<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
   
    public function posts()
    {
      return $this->hasMany(\App\Post::class);
    }
    
    public function profile()
    {
        return $this->hasOne(\App\Profile::class);
    }

    public function total_posts()
    {
        return DB::table('posts')->select(DB::raw('count(*) as total_posts'))->where('user_id',$this->id)->get();
    }
    
    public function notifications()
    {
        return $this->hasMany(\App\Notification::class,'sender_id');
    }

    public function total_notifications()
    {
        $total_row =  DB::table('notifications')
                     ->select(DB::raw('count(*) as notifications'))
                      ->where('sender_id','=',$this->id)->first();
        return $total_row->notifications;        
    }

    public function following($sender_id)
    {  
       return DB::table('followers')->where([
           ['receiver_id','=',$this->id],
           ['sender_id','=',$sender_id]
       ])->get();
       
    }

    public function disable_likes_button($content_id)
    {
        $already_like_or_not = Like::where([
                                     ['content_id','=',$content_id],
                                     ['user_id' ,'=',$this->id]
                                     ])->get();

        return count($already_like_or_not);
    }
    

    
}
