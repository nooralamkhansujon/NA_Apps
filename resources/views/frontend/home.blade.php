@extends('frontend.master')

@section('maincontent')

  <div class="album py-1 container">
   

      <div class="row">
         
        <div class="col-md-8 pl-2">

          <div class="row">
            <div class="col-md-12 my-5">
                     
                <form action="{{route('frontend.post.store')}}" method="POST" enctype="multipart/form-data">
                    
                   {{ csrf_field() }}

                   <div class="form-group">
                         <label class="lead" for="title">Post Title</label>
                         <input type="text" name="title" class="form-control" placeholder="Enter Your Post Title">
                    </div>

                    <div class="form-group">
                         <label class="lead" for="post_content">Share  your post</label>
                        <textarea name="post_content"  class="form-control" placeholder="Enter Your Post" cols="25" rows="5"></textarea>

                    </div>

                    <div class="form-group">
                         <input type="submit" name="post" value="Share Post" 
                         class="btn btn-primary" />
                    </div>
                  
                </form>
            </div>
          </div>
        <div id="posts">
        @foreach($posts as $post )
                   <!-- single post start  -->
                  <div class="card mb-2 shadow-sm">
                       
                       <div class="p-2">
                            <h2><a href="{{route('frontend.post.show',$post->id)}}" class="text-info">{{$post->title}}</a><h2>
                        </div>
                     <div class="pl-2 d-flex">
                         
                        <a href="{{route('frontend.post.show',$post->id)}}">  
                            <img src="{{isset($post->user->profile->image)?  asset($post->user->profile->image->url):asset('images/user.jpg') }}" class="rounded-circle" 
                           width="100px" height="100px"  />
                        </a>
                       
                         <div class="ml-2 align-items-center mt-3 d-flex flex-column">
                             <a href="#">{{ $post->user->name }}</a>
                              <span>{{ $post->created_at->toFormattedDateString() }}</span>
                         </div>
                         
                     </div>
                    <div class="card-body">
                      <p class="card-text text-muted">
                       {{substr($post->post_content,0,220).'...' }}
                      </p>
                      <div class="d-flex justify-content-between align-items-center">

                        <div class="btn-group">
                          <a href="{{route('frontend.post.show',$post->id)}}" class="btn btn-lg btn-outline-info">View</a>
                        </div>
                         
                         @if(auth()->user()->disable_likes_button($post->id) > 0 )
                               <button class="btn btn-secondary likes_button" disabled data-post_id="{{$post->id}}">
                               <span id="total_like{{$post->id}}">{{ count($post->likes) }}</span> Like</button>
                         @else
                              <button class="btn btn-secondary likes_button" data-post_id="{{$post->id}}">
                              <span id="total_like{{$post->id}}">{{ count($post->likes) }}
                              </span> Like</button>
                         @endif
                      
                        
                      </div>

                    </div>
                  </div>
                  <!-- end of single post  -->
            @endforeach
        </div>
          
       </div>
     <!-- end of col-md-8  -->

    <div class="col-md-4 pl-5 my-3" id="users">
         
         @foreach($users as $user)
           <!-- single item  -->
            <div class="mb-2">
               <div class="d-flex flex-column pl-3 py-2 mr-3" style="background-color:rgba(200,236,200,0.5) !important;">

                   <div class="d-flex">
                        <a href="#" class="mr-3 mb-2">
                       <img src="{{isset($user->profile->image)? asset($user->profile->image->url):asset('images/user.jpg') }}" width="40px" height="40px" class="rounded-circle" alt=""></a>
                        <a class="text-info mb-3 ml-3">{{$user->name}}</a>
                   </div>

                  @if( count(auth()->user()->following($user->id)) > 0 )
                   <button data-touserid="{{ $user->id }}" class="btn btn-warning bg-sm align-self-start follow_button" id="follow_user{{$user->id}}">
                          following
                    </button>
                   @else 
                        <button data-touserid="{{ $user->id }}" class="btn btn-primary bg-sm align-self-start follow_button"  id="follow_user{{$user->id}}">
                           follow
                      </button>
                  @endif 
               </div>
            </div>
            <!-- end of single item -->
          @endforeach
        
      </div>

      </div>
   
</div>

@endsection 






