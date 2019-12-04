<footer class="text-muted mt-5">
  <div class="container" align="center">
    <p>&copy; By Nooralam khan</p>
   
  </div>
</footer>


<!-- Custom styles for this template -->


<!-- <script type="text/javascript" src="{{asset('js/bootstrap.bundle.min.js')}}"></script> -->
<script  src="{{asset('js/bootstrap.min.js')}}"></script>

<script defer  type="text/javascript">
  
  $(document).ready(function(){

        function logout(event)
        {
           event.preventDefault();
           form = document.getElementById('logout-form');
           form.submit();
        }
        
         setInterval(function(){
              loadNotifications();
         },5000);


        function loadUsers(){

            $.ajax({
              url      : "{{route('frontend.loadUsers')}}",
              method   : "GET",
              success:function(data){
                $("#users").html(data);
              }
            });
        }
        function loadPosts(){
              $.ajax({
                  url      : "{{route('frontend.loadPosts')}}",
                  method   : "GET",
                  success:function(data){
                     $("#posts").html(data);
                  }
              });
        }
       
        


        function loadNotifications()
        {
          $.ajax({
              url:"{{route('frontend.notification')}}",
              method:"get",
              success:function(data)
              {
                $("#notifications").html(data.output);
                $("#total_notification").html(data.total_notifications);

              }
          })
        }

        saveFollowingUser()
        function saveFollowingUser()
        {
            $(".follow_button").on('click',function(event){
                   touserid = this.dataset.touserid;
                   $.ajax({
                         url      : "{{route('frontend.post.follow') }}",
                         data     : {touserid:touserid},
                         method   : "GET",
                         dataType : "json",
                         success  : function(data){
                             message            = document.getElementById('message');
                             if(!message.classList.contains('alert-'+data.type))
                             {
                                      classl = message.classList;
                                      if(data.type == "danger")
                                      {
                                         (classl.contains('alert-success'))?classl.remove('alert-success'):'' ;
                                      }
                                      else if(data.type == 'success')
                                      {
                                          (classl.contains('alert-danger'))?classl.remove('alert-danger'):'';
                                      }
                             }
                             message.classList.add('alert-'+data.type);
                             message.innerHTML  = data.message;
                             loadfollowingAndShow_Message(touserid);
                             loadPosts()
                         }
                   });
            });
        }

        function loadfollowingAndShow_Message(touserid)
         {
          
            $.ajax({
              url      : "{{route('frontend.loadfollowing')}}",
              method   : "GET",
              data     : {touserid:touserid},
              dataType :"json",
              success  : function(data){
                   let follow_user = document.getElementById('follow_user'+touserid);
                 
                   if(data.follow_text == 'following')
                   {
                     if(follow_user.classList.contains('btn-primary'))
                     {
                         follow_user.classList.remove('btn-primary');
                     }
                      
                   }
                   else if(data.follow_text == 'follow'){
                      if(follow_user.classList.contains('btn-warning'))
                      {  
                        follow_user.classList.remove('btn-warning');
                      }
                   }   
                   follow_user.classList.add(data.css);
                   follow_user.innerHTML = data.follow_text; 
                    console.log(follow_user);
                  
                   
              }
            });
        }
        

        $(".likes_button").on('click',function(){

            let content_id = this.dataset.post_id;
            console.log(content_id);

            $.ajax({
              url    :  "{{route('frontend.likes')}}",
              method :  "GET",
              data   :  {content_id:content_id},
              success:  function(data)
              {
                   loadlike(content_id)
              }
            })

        });

    function loadlike(content_id){
          
            $.ajax({
                url     : "{{route('frontend.loadlike')}}",
                method  : "get",
                data    : {content_id:content_id},
                dataType:'json',
                success :function(data){
                   likes_button = document.getElementById('total_like'+content_id);
                   likes_button.innerHTML = data.total_like;
                   likes_button.setAttribute('disabled',true);
                  console.log(content_id);
                  
                }
            });
    }


       
  });
 

</script>

</body>
</html>