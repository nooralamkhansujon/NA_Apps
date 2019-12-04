<?php

namespace App\Http\Controllers\frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Post;
use App\Like;
use App\Notification;
use App\Follower;
use DB;
class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
     
    public function index()
    {
        $users         = User::where('id','!=',auth()->user()->id)->orderBy('id','DESC')->get();
        $posts         = $this->posts();

        $profile       = auth()->user()->profile;
        $notifications = Notification::all();
        return view('frontend.home',compact('users','posts','profile','notifications'));
    }

    private function posts()
    {
        $posts         = Post::join('users','posts.user_id','=','users.id')
        ->leftJoin('followers','posts.user_id','=','followers.sender_id')
        ->where('posts.user_id',auth()->user()->id)
        ->orWhere('followers.receiver_id','=',auth()->user()->id)
        ->select('posts.*')
        ->orderBy('posts.id','DESC')
        ->get();

        return $posts;
    }

    public function load_users(){

            $users   = User::where('id','!=',auth()->user()->id)->orderBy('id','DESC')->get();
            $output  = '';

            foreach($users as $user)
            {
                $output .='  
                <!-- single item  -->
                <div class="mb-2">
                  <div class="d-flex flex-column pl-3 py-2 mr-3" style="background-color:rgba(200,236,200,0.5) !important;">  
                   <div class="d-flex">
                  <a href="#" class="mr-3 mb-2">';
                
                if(isset($user->profile->image) && !is_null($user->profile->image)){
                    $output .= '<img src="'.asset($user->profile->image->url).'" width="40px" height="40px" class="rounded-circle" ></a>';
                }

                else{
                    $output .='<img src="'.asset("images/user.jpg").'" width="40px" height="40px" class="rounded-circle" alt=""></a>';
                }

                $output .='<a class="text-info mb-3 ml-3" id="profile_user">'.$user->name.'</a></div>';

                if(count(auth()->user()->following($user->id) ) > 0)
                {
                    $output .= '<button data-touserid="'.$user->id.'" 
                    class="btn btn-warning bg-sm align-self-start follow_button">following</button>';
                }
                else{
                    $output .='<button data-touserid="'.$user->id.'" class="btn btn-primary bg-sm align-self-start follow_button">follow</button>';
                }
                $output .='</div></div><!-- end of single item -->';
            }

            echo $output;
    }
    
    public function load_posts(){


        $posts         = $this->posts();
        $output        = '';
      
        foreach($posts as $post)
        {

            $output .= '<!-- single post start  -->
                  <div class="card mb-2">
                       <div class="p-2">';

            $output .= '<h2><a href="'.route('frontend.post.show',$post->slug).'"  class="text-info">'.$post->title.'</a><h2>
                 </div>
                     <div class="pl-2 d-flex">';

            if(isset($post->user->profile->image) && !is_null($post->user->profile->image))
            {
                $path    = $post->user->profile->image->url;
                $output .='<a href="'.route('frontend.post.show',$post->slug).'"> 
                        <img src="'.asset($path).'" class="rounded-circle" 
                               width="100px" height="100px" alt="" /></a>';
            }
            else{

                $output .='<a href="'.route('frontend.post.show',$post->slug).'"> 
                        <img src="'.asset('images/user.jpg').'" class="rounded-circle"  width="100px" height="100px" alt="" /></a>';
            }
          
            $output .= '<div class="ml-2 align-items-center mt-3 d-flex flex-column">
                         <a href="#">'. $post->user->name.'</a>
                        <span>'.$post->created_at->toFormattedDateString().'</span></div></div>';
            $output  .= '<div class="card-body">
                          <p class="card-text text-muted">
                           '.$post->post_content.'
                          </p>';
            $output .= '<div class="d-flex justify-content-between align-items-center">
                        <div class="btn-group">';
            $output .= '<a href="'.route('frontend.post.show',$post->slug).'" class="btn btn-lg btn-outline-info">View</a></div>';

            if(auth()->user()->disable_likes_button($post->id) > 0 )
            {
                $output .= '<button class="btn btn-secondary likes_button" 
                   disabled data-post_id="'.$post->id.'">
                   <span id="total_like'.$post->id.'">'.count($post->likes) .'</span>Like</button>';
            }
            else{
                $output .= '<button class="btn btn-secondary likes_button" data-post_id="'.$post->id.'">
                <span id="total_like'.$post->id.'">'.count($post->likes) .'</span>Like</button>';
            } 
            $output .='</div></div></div>
                    <!-- end of single post  -->';
        }
        echo $output;
    }


    public function likes()
    {
        $content_id          = request()->input('content_id'); // fetch the post_id 
        $alreadyLikeOrNot    = auth()->user()->disable_likes_button($content_id);//auth user already like or not 
        $content_user_id     = Post::find($content_id)->user_id; //post user id 

        if($alreadyLikeOrNot < 1 )
        {
           $like             = new Like();
           $like->content_id = $content_id;
           $like->user_id    = auth()->user()->id;
           $like->save();
           
           //show notification message 
           if($content_user_id == auth()->user()->id)
           {
           
              $message = "<strong class='text-dark'>You </strong> Like on Your Post";
           }
           else{
              $message = "<strong class='text-dark'>".auth()->user()->name."</strong> Like on Your Post";
           
           }
          
           $notification               = new  Notification();
           $notification->sender_id    = $content_user_id;
           $notification->content_id   = $content_id;
           $notification->from_id      = auth()->user()->id;
           $notification->text_content = $message;
           $notification->save();

        }
     }

    public function following()
    {
        //getting data using ajax request
        $touserid         = request()->input('touserid');
        $fromuserid       = auth()->user()->id;
        $touser           =  User::find($touserid);
        $following_or_not = auth()->user()->following($touserid);
        
        
        //checking user already following or not 
        if(count($following_or_not) < 1)
        {
            //added data to the followers table 
            $followers                 = new Follower();
            $followers->sender_id      = $touserid;
            $followers->receiver_id    = $fromuserid;
            $followers->save();

            //update touserid follower_number attribute
            $touser->followers_number  = $touser->followers_number + 1;
            $touser->save();

            //store notification message 
            $message = "<strong class='text-danger'>".auth()->user()->name."</strong> is following  You";
            $notification               = new  Notification();
            $notification->sender_id    = $touserid;
            $notification->from_id      = auth()->user()->id;
            $notification->text_content = $message;
            $notification->save();

            //return ajax request data 
            echo json_encode(['message'=>"You are following ".$touser->name,'type'=>"success"]);
        }
        // if  user unfollow then 
        else{
            $followers                = Follower::where('sender_id','=',$touserid)->first();
            $followers->delete();
           
            //update touser follower_number attribute
            $touser->followers_number = $touser->followers_number - 1;
            $touser->save();

            
            //store notification message 
            $message = "<strong class='text-danger'>".auth()->user()->name."</strong> unfollowing  You";
            $notification               = new Notification();
            $notification->sender_id    = $touserid;
            $notification->from_id      = auth()->user()->id;
            $notification->text_content = $message;
            $notification->save();

            //return ajax request data 
            echo json_encode(['message'=>"You unfollow ".$touser->name,'type'=>"danger"]);
        }
    }

    public function notifications()
    {
        ///find auth notification record from the notifications table 
        $notifications = auth()->user()->notifications;
        $output       = '';

        if(count($notifications) > 0  )
        {
            // if user has notification message 
            foreach($notifications as $notification)
            {
                $output .='<a class="dropdown-item list-group-item text-warning" style="font-size:14px  !important;" href="#" disabled >
                           '.$notification->text_content.'
                </a> ';  
            }
        }

        // if user has not any notification message then 
        else{
           $output .='<a class="dropdown-item list-group-item text-muted" style="font-size:14px  !important;" href="#" disabled >
                           No Notifications 
                </a> ';
        }  

        $data = array(
                 "output"              => $output,
                 'total_notifications' => auth()->user()->total_notifications()
            );

        echo json_encode($data);
   }

   function loadlike()
   {
         $content_id = request()->input('content_id');
         $post       = Post::find($content_id);
         $output     = array(
              'total_like' => $post->total_likes(),
         );
        echo json_encode($output);
   }

   function loadfollowing()
   {
       $user_id = request()->input('touserid');
       $output  = array();
       if( count(auth()->user()->following($user_id)) > 0 )
       {
            $output['follow_text'] = 'following';
            $output['css']         = 'btn-warning';
       }
       else{
             $output['follow_text'] = 'follow';
             $output['css']         = 'btn-primary';
       }
       echo json_encode($output);
   }

   function notification_update(){

       $notification_id       = request()->input('notification_id'); 
       $notification          = Notification::find($notification_id);
       $notification->status  = 1;
       $notification->save();
    

   }
}
