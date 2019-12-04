<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Post;
use App\Image;
use App\Profile;
use DB;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(){

        $this->middleware('auth');
    }

    public function index()
    {
         $users   = User::where('id','!=',auth()->user()->id)->orderBy('id','DESC')->get();
         $posts   = Post::where('user_id',auth()->user()->id)->get();
         $posts   = auth()->user()->posts;
        return view('frontend.profile',compact('users','posts'));
    }

    


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate the profile data 
        $this->validate($request,[
              'first_name'      => 'required',
              'last_name'       => 'required', 
              'marital_status'  => "required",
        ]);

        $address        = null;
        $bio            = null;
        $profile        = auth()->user()->profile;

        
        if($request->has('address'))
        { 
            $address  = trim($request->input('address'));
        }
        if($request->has('bio'))
        {
            $bio      = trim($request->input('bio')) ;
        } 

        //checking image is not null and  valid  
        if(!is_null($request->file('image')) && $request->file('image')->isValid())
        {

            // get image extension 
            $image_extension    = $request->file('image')->getClientOriginalExtension();
            $image_new_name     = "profile_".time().".".$image_extension; //make random name for 
            $image_new_location = 'images/'.$image_new_name;
            
            // save image in storage 
            move_uploaded_file($request->file('image'), public_path($image_new_location));


            //check image object already exists or not 
            if(isset($profile->image) && !is_null($profile->image))
            {
                // if image already exist then remove old image 
                if(file_exists(public_path($profile->image->url))){
                      unlink(public_path($profile->image->url));
                }
                $image      = $profile->image;
            }
            else{
                $image    = new \App\Image() ;
            }
           
            //save image 
            $image->imageable_type    =  "App\Profile";
            $image->imageable_id      =  $profile->id;
            $image->url               =  $image_new_location;
            $image->save();
        }

        ///save profile 
        $profile->first_name     = $request->input('first_name');
        $profile->last_name      = $request->input('last_name');
        $profile->marital_status = $request->input('marital_status');
        $profile->bio            = $bio;
        $profile->address        = $address;
        $profile->save();
        
        $this->setSuccess('Your Profile Updated successfully !');
        return redirect()->back();

    }



}
