<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
  <a class="navbar-brand" href="{{route('frontend.home')}}">NA</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent"     aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarContent">

        
        <ul class="navbar-nav mr-auto">
           @if(auth()->check())
              <li class="nav-item active">
                <a class="nav-link" href="{{route('frontend.home')}}" id="home">Home</a>
              </li>
              <li class="nav-item active">
                <a class="nav-link" href="{{route('frontend.profile')}}" >Welcome, 
                  <strong class="text-warning display-5" style="text-transform:uppercase;">
                    {{auth()->user()->name}} {{auth()->user()->id}}
                  </strong>  </a>
              </li>
           @endif  

        </ul>
    
    <ul class="navbar-nav">

      @if(auth()->guest())
            <li class="nav-item active">
              <a class="nav-link" href="{{route('login')}}">
                Login 
                <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item active">
              <a class="nav-link" href="{{route('register')}}">Register</a>
          </li>
      @else 
           <li class="nav-item active">
              <div class="dropdown">
                    <a class="nav-link dropdown-toggle text-light" href="#" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Notification <span id="total_notification" class="badge badge-danger">
                        {{ auth()->user()->total_notifications() }}</span>
                    </a>
                    <div class="dropdown-menu p-2" aria-labelledby="dropdownMenuButton" id="notifications"  style="overflow-y:auto !important; height:200px !important;">
                        @forelse (auth()->user()->notifications()->where('status',0)->orderBy('id','DESC')->get() as $notification)
                        <div class="d-flex justify-content-between">
                            <a class="dropdown-item list-group-item text-warning notification" style="font-size:14px !important;" 
                                href="#" disabled >
                                {!! $notification->text_content !!}
                                </a> 
                                <input type="button" name="notifications" class="ml-2 btn btn-success btn-sm notifications"  data-notification_id="{{$notification->id}}" value="mark"  id="notification{{$notification->id}}"  >
                        </div>
                           
                        @empty 
                             <a class="dropdown-item list-group-item text-muted" style="font-size:14px !important;" href="#" disabled >
                            No  Notifications 
                            </a> 
                        @endforelse
                      
                    </div>
                </div>
           </li>
    
          <li class="nav-item active">
              <a class="nav-link active" href="{{route('frontend.profile')}}">Profile <span class="sr-only">(current)</span></a>
           </li>
           <li class="nav-item active">
            <a class="nav-link" href="{{ route('userlogout') }}"
                        onclick="event.preventDefault();
                          document.getElementById('logout-form').submit();">
                            Logout
            </a>

              <form id="logout-form" action="{{ route('userlogout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
              </form>
           </li>

            
      @endif 
    
    </ul>
   
  </div>

</div>
</nav>


<script type="text/javascript">

markAsRead();
  function markAsRead()
  { 

    let notifications = document.querySelectorAll('.notifications');
    notifications.forEach(element=>{
          element.addEventListener('click',function(event){
              let notification_id = this.dataset.notification_id;
              updateMarkNotification(notification_id);
          });
          
    });

  }
  function updateMarkNotification(notification_id)
  {
        $.ajax({
            url     : "{{route('frontend.notification_update')}}",
            method  : "get",
            data    : {notification_id:notification_id},
            dataType: "json",
            success : function(data){
            }
        });
  }
  

</script>