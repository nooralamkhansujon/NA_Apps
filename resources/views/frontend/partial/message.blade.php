
@if(session()->has('message'))
<!-- message alert  -->
<div class="col-md-12 mt-2">
        <div class="alert alert-{{session()->get('type')}}" role="alert">
              {{session()->get('message')}}
        </div>
</div>
<!-- end of message alert  -->
@endif 


<div class="col-md-12 mt-2" >
        <div class="alert"  id="message"></div>
</div>



@if($errors->any())
 
       <!-- message alert  -->
       <div class="col-md-12 mt-2">
          <ul class="alert alert-danger" role="alert">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach  
          </ul>
        </div>
       <!-- end of message alert  -->
    
@endif 