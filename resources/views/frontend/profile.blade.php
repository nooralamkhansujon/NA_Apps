
@extends('frontend.master')

@section('maincontent')

 <div class="row py-1">
  <div class="col-md-8 pl-2" >
  
    <div class="container mt-4" style="border:0.2px solid rgba(0,0,0,0.4) !important;width:700px !important; ">
        <div class="col-md-12 pt-3">
                <h2 class="text-muted" style="border-bottom:1px dashed gray !important;">Profile Of <strong>{{ucwords(auth()->user()->name)}} </strong></h2>
        </div>
        <div class="form-container p-3">
             <form action="{{route('frontend.profile.store')}}" method="POST"  enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="row mb-2">
                    <!-- profile image  -->
                    <div class="col-md-4">
                        <a href="#">
                          <img src="{{isset(auth()->user()->profile->image)? asset(auth()->user()->profile->image->url):asset('images/user.jpg') }}" class="rounded-circle" width="100px" height="100px" alt="">
                        </a>
                    </div>
                    

                </div>
 

                <!-- input group  -->
                <div class="form-group">
                    <!-- start row  -->
                    <div class="row">
                            <!-- first column  -->
                            <div class="col-md-6">
                                <label for="first_name"  class="text-muted">First Name</label>
                                <input type="text" class="form-control text-muted" name="first_name" id="first_name" value="{{ auth()->user()->profile->first_name }}" />
                            </div>

                            <!-- second column  -->
                            <div class="col-md-6">
                                <label for="last_name" class="text-muted">Last Name</label>
                                <input type="text" class="form-control" name="last_name" 
                                id="last_name" value="{{ auth()->user()->profile->last_name }}" />
                            </div>

                   </div>
                   <!-- end of row  -->
                </div>
                <!-- end of input group  -->

                <!-- input group  -->
                <div class="form-group">
                    <!-- start row  -->
                    <div class="row">
                        <div class="col-md-12">
                             <label for="bio" class="text-muted">Bio Data <span class="text-muted">*Optional</span></label>
                            <textarea  type="text" name="bio"
                                 id="bio"  class="form-control" >
                                 {{ auth()->user()->profile->bio }} 
                            </textarea>
                        </div>
                    </div>
                    <!-- end of row  -->
                </div>
                <!-- end of input group  -->


                <!-- input group  -->
                <div class="form-group">
                    <!-- start row  -->
                    <div class="row">
                            <div class="col-md-12">
                            <label class="text-muted">Marital Status</label><br/>
                               <span class="text-muted"> Married </span>  : 
                               <input type="radio" name="marital_status" 
                               value="married" {{ (auth()->user()->profile->marital_status == 'married') ?'checked':''}} />

                               <span class="text-muted"> Unmarried </span> : 
                               <input type="radio" name="marital_status" value="unmarried" 
                               {{ (auth()->user()->profile->marital_status == 'unmarried')?'checked': ''}} />

                            </div>
                    </div>
                    <!-- end of row  -->
                </div>
                <!-- end of input group  -->

                <!-- input group  -->
                <div class="form-group">
                    <!-- start row  -->
                    <div class="row">
                        <div class="col-md-12">
                            <label for="address" class="text-muted">Address <span class="text-muted">*Optional</span></label>
                            <textarea name="address" class="form-control" id="address">
                              {{ auth()->user()->profile->address }}
                            </textarea>
                        </div>
                    </div>
                    <!-- end of row  -->
                </div>
                <!-- end of input group -->

                <!-- input group  -->
                <div class="form-group">
                    <!-- start row  -->
                    <div class="row">
                        <div class="col-md-12">
                            <label for="address">Upload Image</label>
                            <input type="file" name="image" id="image" class="form-control">
                        </div>
                    </div>
                    <!-- end of row  -->
                </div>
                <!-- end of input group -->

                <div class="form-group d-flex justify-content-center">
                     <input type="submit" name="update" class="btn btn-primary" value="update profile">    
                </div>
            </form>
            </div>

          
    </div>

    <div class="col-md-12 mt-2" align="center">
          <div class="container " id="posts">
            @foreach($posts as $post)
            <!-- start of single post item  -->
            <div class="card mb-2" style="width:700px !important;">
               <div class="card-header">
                 <div class="col-md-12 pb-2 mb-2" style="border-bottom:0.6px solid rgba(0,0,0,0.4) !important;" >
                   <h3 class="text-info" style="text-transform:capitalize;">{{ $post->title }}</h3>
                 </div>
                 <div class="d-flex">
                  <a href="#">  
                      <img src="{{isset($post->user->profile->image)? asset($post->user->profile->image->url):asset('images/user.jpg') }}" class="rounded-circle" 
                      width="100px" height="100px" alt="" />
                      </a>
                  
                      <div class="ml-2 align-items-center mt-3 d-flex flex-column">
                          <a href="#">{{$post->user->name}}</a>
                          <span>{{ $post->created_at->toFormattedDateString() }}</span>
                      </div>
                 </div>
                 
                   
               </div>
              <div class="card-body">
                <p class="card-text text-muted">
                  {{ $post->post_content }}
                </p>
                <div class="d-flex justify-content-between align-items-center">
                  <div class="btn-group ">
                      <a href="{{route('frontend.post.show',$post->id)}}" class="btn btn-lg btn-outline-info">View</a>
                  </div>
                   @if(auth()->user()->disable_likes_button($post->id) > 0 )
                           <button class="btn btn-secondary likes_button" disabled data-post_id="{{$post->id}}">
                           <span id="total_like{{$post->id}}">{{ count($post->likes) }}</span> Like</button>
                    @else
                          <button class="btn btn-secondary likes_button" data-post_id="{{$post->id}}">
                           <span  id="total_like{{$post->id}}">{{ count($post->likes) }}
                          </span> Like</button>
                     @endif
                </div>
              </div>
            </div>
            <!-- end of single post item  -->
            @endforeach
           </div> 
        </div>
         
    </div>
     <!-- end of col-md-8 row  -->

    <div class="col-md-4 pl-3 my-3 " id="users">
         
        @foreach($users as $user)
         <!-- single item  -->
          <div class="mb-2">
             <div class="d-flex flex-column pl-3 py-2 mr-3" style="background-color:rgba(200,236,200,0.5) !important;">

                 <div class="d-flex">
                      <a href="#" class="mr-3 mb-2">
                     <img src="{{isset($user->profile->image)? asset($user->profile->image->url):asset('images/user.jpg') }}" width="40px" height="40px" class="rounded-circle" alt=""></a>
                      <a class="text-info mb-3 ml-3" id="profile_user">{{$user->name}}</a>
                 </div>
                
                  @if( count(auth()->user()->following($user->id)) > 0)
                   <button data-touserid="{{ $user->id }}" class="btn btn-warning bg-sm align-self-start follow_button">
                          following
                    </button>
                   @else 
                        <button data-touserid="{{ $user->id }}" class="btn btn-primary bg-sm align-self-start follow_button">
                           follow
                      </button>
                  @endif 
             </div>
          </div>
          <!-- end of single item -->
          @endforeach
    </div>

 </div>
  
@endsection 

















           