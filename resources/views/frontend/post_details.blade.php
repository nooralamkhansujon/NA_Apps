@extends('frontend.master')

@section('maincontent')

  <div class="album py-1">
    <div class="container">

      <div class="row">
         
        <div class="col-md-8 pl-2">

          <div class="row">
            <div class="col-md-12 my-5" style="display:none;" id="post_form">
                     
                <form action="{{route('frontend.post.update',$post->id)}}" method="POST">
                    
                   {{ csrf_field() }}
                   {{ method_field('PUT') }}
                   <div class="form-group">
                         <label class="lead" for="title">Update Post Title</label>
                         <input type="text" name="title" id="title" class="form-control" placeholder="Enter Your Post Title">
                    </div>

                    <div class="form-group">
                         <label class="lead" for="post_content">Update Post</label>
                        <textarea name="post_content" id="post_content" class="form-control" placeholder="Enter Your Post" cols="25" rows="5"></textarea>

                    </div>

                    <div class="form-group">
                         <input type="submit" name="post" value="Update Post" 
                         class="btn btn-primary" />
                    </div>
                  
                </form>
            </div>
          </div>
           <div class="col-md-12" id="post">
             <!-- single post start  -->
              <div class="card mb-2 shadow-sm">
                   <div class="p-2">
                    <h2 class="text-info" id="post_title">{{$post->title}}</h2>
                  </div>
                  <div class="pl-2 d-flex">
                      <a href="#">  
                         <img src="{{isset($post->user->profile->image)? asset($post->user->profile->image->url):asset('images/user.jpg') }}" class="rounded-circle" 
                         width="100px" id="post_image" height="100px" alt="" />
                      </a>
                     
                       <div class="ml-2 align-items-center mt-3 d-flex flex-column">
                           <a href="#" id="post_name">{{ $post->user->name }}</a>
                            <span id="post_date">{{ $post->created_at->toFormattedDateString() }}</span>
                            <div class="d-flex" id="post_button">
                                  @if(auth()->user()->id == $post->user->id)
                                      <form action="{{route('frontend.post.delete',$post->id)}}" id="post_delete">
                                          {{ csrf_field() }}
                                          {{ method_field('DELETE') }}
                                          <input type="submit" value="Delete" class="btn btn-danger btn-sm mr-1">
                                      </form>
                                       
                                      <button href="#" id="update_post" class="btn btn-info btn-sm">Update</button>
                                  @endif  
                            </div>
                       </div>
                       
                  </div>
                  <div class="card-body">
                    <p class="card-text text-muted" id="post_content">
                     {{ $post->post_content }}
                    </p>
                    <div class="d-flex justify-content-between align-items-center">
                          @if(auth()->user()->disable_likes_button($post->id) > 0 )
                               <button class="btn btn-secondary likes_button" disabled data-post_id="{{$post->id}}">
                                <span id="total_like{{$post->id}}">{{ count($post->likes) }}</span> Like</button>
                          @else
                              <button class="btn btn-secondary likes_button" data-post_id="{{$post->id}}">
                                <span id="total_like{{$post->id}}">
                                  {{ count($post->likes) }}
                                </span> Like
                              </button>
                          @endif
                    </div>

                  </div>
              </div>
              <!-- end of single post  -->
          </div>
           

      </div>
        

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
                            <button data-touserid="{{ $user->id }}" class="btn btn-primary bg-sm align-self-start follow_button" id="follow_user{{$user->id}}">
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
</div>



<script type="text/javascript">
   
   $(document).ready(function(){

        $("#update_post").on('click',function(event){

               $.ajax({
                  url    : "{{route('frontend.post.edit',$post->id)}}",
                  method : "get",
                  dataType:"json",
                  success:  function(data){
                       let post_form = document.getElementById('post_form');
                       post_form.style.display="block";
                       $("#post_content").val(data.post_content);
                       $("#title").val(data.title);

                  } 

               });
         });
       $('#post_delete').on('click',function(event){
            event.preventDefault();
           if(confirm('Are You sure do You want to delete this post?'))
           {
             this.submit();
           }
       });
      

      //  $(".like_button").on('click',function(){
      //      let content_id = this.dataset.post_id;
      //      console.log(content_id);

      //       $.ajax({
      //           url    : "{{route('frontend.likes')}}",
      //           method : "GET",
      //           data   : {content_id:content_id},
      //           success: function(data)
      //           {
      //              loadlike(content_id);
      //           }
      //       });
      // });



   });

</script>
@endsection 






