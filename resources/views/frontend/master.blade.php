
@include('frontend.partial.header')
@include('frontend.partial.nav')




<main role="main">
    	 <div class="col-md-6 offset-md-1 mt-3 ">
    	 	  @include('frontend.partial.message')
    	 </div>
    	 <div class="col-md-12">
    	 	 @yield('maincontent')
    	 </div>
</main>

@include('frontend.partial.footer')
