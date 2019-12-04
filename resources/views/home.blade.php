
@include('frontend.partial.header')
@include('frontend.partial.nav')


<style>
  #content{
      position:absolute !important;
      top:50%;
      left:50%;
      transform:translate(-50%,-50%);
  }
</style>
<main role="main" id="content">
         <div class="col-md-6 offset-md-1 mt-3 ">
              @include('frontend.partial.message')
         </div>
         <div class="col-md-12" >
             <h1 class="display-4 text-info">
                Welcome to our website 
            </h1>
         </div>

         <script  src="{{asset('js/bootstrap.min.js')}}"></script>

</main>

