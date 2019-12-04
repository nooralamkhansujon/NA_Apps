<?php

namespace App\Http\Controllers\frontend;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Post;
use App\User;
use DB;
class PostController extends Controller
{
   
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request )
    {
        
        //validate the data
        $this->validationRules($request);
        
        // insert data to data base using model 
        $post = Post::create([
                 'title'   => $request->input('title') ,
                 'slug'    => str_slug($request->input('title')),
                 'user_id' => auth()->user()->id ,
                 'post_content'=>$request->input('post_content')
               ]);

        $this->setSuccess('Your post is created successfully!');
        return redirect()->route('frontend.home');
    }

   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post           = Post::Where('id','=',$id)->first();
        $users          = User::where('id','!=',auth()->user()->id)->orderBy('id','DESC')->get();

        if(is_null($post) or is_null($users)){
            return redirect()->route('frontend.home');
        }

        return view('frontend.post_details',compact('post','users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update (Request $request, $id)
    {

        //validate the data
         $this->validationRules($request,$id);
         
         $post = Post::find($id);
         $post->title = $request->input('title');
         $post->post_content = $request->input('post_content'); 
         $post->save();

         $this->setSuccess('Your post is updated successfully!');
         return redirect()->route('frontend.post.show',$post->slug);
       
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $post = Post::where('id',$id)->first();
        $data = array();
        $data['title'] = $post->title;
        $data['post_content'] = $post->post_content;

        echo json_encode($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        $post->delete();

        $this->setSuccess('Your post is deleted successfully!');
        return redirect()->route('frontend.home');
      
    }

    private function validationRules($request,$id=null)
    {
        if($id != null)
        {
             $this->validate($request,[
                 'title'        => ['required','min:10',Rule::unique('posts')->ignore($id)],
                 'post_content' => 'required'
              ]);

        }
       else {
            $this->validate($request,[
               'title'        =>['required','min:10','unique:posts,title'],
               'post_content' => 'required'
              ]);
       }

       
  }



 

}
