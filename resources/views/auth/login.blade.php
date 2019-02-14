<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Login System</title>
    <!-- Base Css Files -->
       <link href="{{ URL::asset('public/assets/libs/jqueryui/ui-lightness/jquery-ui-1.10.4.custom.min.css')}}" rel="stylesheet" />
       <link href="{{ URL::asset('public/assets/libs/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" />
       <link href="{{ URL::asset('public/assets/libs/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" />
       <link href="{{ URL::asset('public/assets/libs/fontello/css/fontello.css')}}" rel="stylesheet" />
       <link href="{{ URL::asset('assets/libs/nifty-modal/css/component.css')}}" rel="stylesheet" />
       <link href="{{ URL::asset('public/public/assets/libs/animate-css/animate.min.css')}}" rel="stylesheet" />
       <link href="{{ URL::asset('public/assets/libs/magnific-popup/magnific-popup.css')}}" rel="stylesheet" />
       <link href="{{ URL::asset('public/assets/libs/ios7-switch/ios7-switch.css')}}" rel="stylesheet" />
       <link href="{{ URL::asset('public/assets/libs/pace/pace.css')}}" rel="stylesheet" />
       <link href="{{ URL::asset('public/assets/libs/sortable/sortable-theme-bootstrap.css')}}" rel="stylesheet" />
       <link href="{{ URL::asset('public/assets/libs/jquery-icheck/skins/all.css')}}" rel="stylesheet" />
       <link href="{{ URL::asset('public/assets/libs/bootstrap-datepicker/css/datepicker.css')}}" rel="stylesheet" />
       <link href="{{ URL::asset('public/assets/libs/jquery-notifyjs/styles/metro/notify-metro.css')}}" rel="stylesheet" type="text/css" />

       <!-- Code Highlighter for Demo -->
       <link href="{{ URL::asset('public/assets/libs/prettify/github.css')}}" rel="stylesheet" />

               <!-- Extra CSS Libraries Start -->
               <link href="{{ URL::asset('public/assets/css/style.css')}}" rel="stylesheet" type="text/css" />

                <link href="{{ URL::asset('public/assets/css/style4.css')}}" rel="stylesheet" />

               <!-- Extra CSS Libraries End -->
       <link href="{{ URL::asset('public/assets/css/style-responsive.css')}}" rel="stylesheet" />

       <link rel="shortcut icon" href="{{ URL::asset('public/assets/img/favicon.ico')}}">
        <link rel="apple-touch-icon" href="{{ URL::asset('public/assets/img/apple-touch-icon.png')}}" />
        <link rel="apple-touch-icon" sizes="57x57" href="{{ URL::asset('public/assets/img/apple-touch-icon-57x57.png')}}" />
        <link rel="apple-touch-icon" sizes="72x72" href="{{ URL::asset('public/assets/img/apple-touch-icon-72x72.png')}}" />
        <link rel="apple-touch-icon" sizes="76x76" href="{{ URL::asset('public/assets/img/apple-touch-icon-76x76.png')}}" />
        <link rel="apple-touch-icon" sizes="114x114" href="{{ URL::asset('public/assets/img/apple-touch-icon-114x114.png')}}" />
        <link rel="apple-touch-icon" sizes="120x120" href="{{ URL::asset('public/assets/img/apple-touch-icon-120x120.png')}}" />
        <link rel="apple-touch-icon" sizes="144x144" href="{{ URL::asset('public/assets/img/apple-touch-icon-144x144.png')}}" />
        <link rel="apple-touch-icon" sizes="152x152" href="{{ URL::asset('public/assets/img/apple-touch-icon-152x152.png')}}" />
  </head>
  <body>
    <ul class="cb-slideshow">
                <li><span>Image 01</span><div><h3></h3></div></li>
                <li><span>Image 02</span><div><h3></h3></div></li>
                <li><span>Image 03</span><div><h3></h3></div></li>
                <li><span>Image 04</span><div><h3></h3></div></li>
                <li><span>Image 05</span><div><h3></h3></div></li>
                <li><span>Image 06</span><div><h3></h3></div></li>
            </ul>



    <div class="container">

  		<div class="full-content-center">

  			<div class="login-wrap animated flipInX">
  				<div class="login-block">
  					<div align="center"><img src="{{ URL::asset('public/assets/images/users/yuasa-logo.png')}}" width="100" height="100" class=""></div>
            <p></p>
  					<form role="form" id='loginform' method="POST" action="{{ url('/login') }}">
              {{ csrf_field() }}
              <div class="form-group login-input{{ $errors->has('username') ? ' has-error' : '' }}">
  						<i class="fa  fa-envelope overlay"></i>
  						<input id="username" type="text" name='username' class="form-control text-input" placeholder="" value="{{ old('username') }}">
              @if ($errors->has('username'))
                 <span class="help-block">
                     <strong>{{ $errors->first('username') }}</strong>
                 </span>
              @endif

              </div>
  						<div class="form-group login-input{{ $errors->has('password') ? ' has-error' : '' }}">
  						<i class="fa fa-key overlay"></i>
  						<input type="password"  id="password" name='password' class="form-control text-input" placeholder="">
              @if ($errors->has('password'))
                 <span class="help-block">
                     <strong>{{ $errors->first('password') }}</strong>
                 </span>
              @endif
              </div>

  						<div class="row">
  							<div class="col-sm-6">
  							<button type="submit" class="btn btn-success btn-block">LOGIN</button>
  							</div>
  							<div class="col-sm-6">
                  <a class="btn btn-link" href="{{ url('/password/reset') }}">
                                      Forgot Your Password?
                                  </a>
  							</div>
                php
  						</div>
  					</form>
  				</div>
  			</div>

  		</div>
  	</div>

    <script>
		var resizefunc = [];
	</script>
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="{{ URL::asset('public/assets/libs/jquery/jquery-1.11.1.min.js')}}"></script>
	<script src="{{ URL::asset('public/assets/libs/bootstrap/js/bootstrap.min.js')}}"></script>
	<script src="{{ URL::asset('public/assets/libs/jqueryui/jquery-ui-1.10.4.custom.min.js')}}"></script>
	<script src="{{ URL::asset('public/assets/libs/jquery-ui-touch/jquery.ui.touch-punch.min.js')}}"></script>
	<script src="{{ URL::asset('public/assets/libs/jquery-detectmobile/detect.js')}}"></script>
	<script src="{{ URL::asset('public/assets/libs/jquery-animate-numbers/jquery.animateNumbers.js')}}"></script>
	<script src="{{ URL::asset('public/assets/libs/ios7-switch/ios7.switch.js')}}"></script>
	<script src="{{ URL::asset('public/assets/libs/fastclick/fastclick.js')}}"></script>
	<script src="{{ URL::asset('public/assets/libs/jquery-blockui/jquery.blockUI.js')}}"></script>
	<script src="{{ URL::asset('public/assets/libs/bootstrap-bootbox/bootbox.min.js')}}"></script>
	<script src="{{ URL::asset('public/assets/libs/jquery-slimscroll/jquery.slimscroll.js')}}"></script>
	<script src="{{ URL::asset('public/assets/libs/jquery-sparkline/jquery-sparkline.js')}}"></script>
	<script src="{{ URL::asset('public/assets/libs/nifty-modal/js/classie.js')}}"></script>
	<script src="{{ URL::asset('public/assets/libs/nifty-modal/js/modalEffects.js')}}"></script>
	<script src="{{ URL::asset('public/assets/libs/sortable/sortable.min.js')}}"></script>
	<script src="{{ URL::asset('public/assets/libs/bootstrap-fileinput/bootstrap.file-input.js')}}"></script>
	<script src="{{ URL::asset('public/assets/libs/bootstrap-select/bootstrap-select.min.js')}}"></script>
	<script src="{{ URL::asset('public/assets/libs/bootstrap-select2/select2.min.js')}}"></script>
	<script src="{{ URL::asset('public/assets/libs/magnific-popup/jquery.magnific-popup.min.js')}}"></script>
	<script src="{{ URL::asset('public/assets/libs/pace/pace.min.js')}}"></script>
	<script src="{{ URL::asset('public/assets/libs/jquery-notifyjs/notify.min.js')}}"></script>
  <script src="{{ URL::asset('public/assets/libs/jquery-notifyjs/styles/metro/notify-metro.js')}}"></script>
	<script src="{{ URL::asset('public/assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.js')}}"></script>
	<script src="{{ URL::asset('public/assets/libs/jquery-icheck/icheck.min.js')}}"></script>


  </body>
</html>
