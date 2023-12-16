<!DOCTYPE html>
<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Ashokleyland! @yield('title')</title>
	<!-- Form Validation  -->
	{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> --}}
	<script src="{{asset('js/jquery-min.js')}}"></script>
	<script src="{{asset('js/form_validation.js')}}"></script>
	<!-- base:css -->
	{{--<link rel="stylesheet" href="{{asset('vendors/mdi/css/materialdesignicons.min.css')}}">--}}
	<link rel="stylesheet" href="{{asset('vendors/css/vendor.bundle.base.css')}}">
	<!-- endinject -->
	<!-- plugin css for this page -->
	<!-- End plugin css for this page -->
	<!-- inject:css -->
	<link rel="stylesheet" href="{{asset('css/style.css')}}">
	<!-- endinject -->
	<link rel="shortcut icon" href="{{asset('images/favicon.png')}}" />
	<!-- Toastr Css  -->
	{{-- <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet"> --}}
	<link href="{{asset('css/toastr.min.css')}}" rel="stylesheet">
		
</head>

<body>
 	<div class="container-scroller">
			<div class="horizontal-menu">

				<nav class="navbar top-navbar col-lg-12 col-12 p-0" style="padding:0">
					<div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
						<a class="navbar-brand brand-logo" href=""><img src="{{asset('images/al-logo.svg')}}" alt="logo" width="178.05" height="37.88" ></a>
						<a class="navbar-brand brand-logo-mini" href=""><img src="{{asset('images/al-logo.svg')}}" width="178.05" height="37.88" alt="logo"></a>
							{{--<h4 class="card-title" style="margin-left: 22px;margin-top: 12px">
							<b>Complaint Management System</b> <br>
							<p style="text-align: left;">Cogent CMS</p> </h4>--}}
					</div>
				</nav>
				

			</div>
	</div>
    <div class="container-fluid page-body-wrapper full-page-wrapper">
			<div class="main-panel" style="background:url('{{asset('images/Overview-page-banner.png')}}');background-size: cover;">
        <div class="content-wrapper mobcss">
			<div class="row">
				<div class="col-lg-5"></div>
				<div class="col-lg-4"></div>
				<div class="col-lg-3">
					{{--<div class="auth-form-light text-left py-5 px-4 px-sm-5 border" style="background: transparent;">--}}
					<div style="background: transparent;">
						{{--<div class="brand-logo">
						<!--<img src="images/logo.png" alt="logo">-->

						</div>--}}
						@section('form')
						@show
					</div>
					<span style="font-size:14px;color: #004a87;position: relative;top: 10px;">
					<span style="color: #004a87;">
					<b>*</b></span> Application best compatible with <b>Chrome & Firefox.</b></span>
					</div>
				</div>
			</div>
        
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  
  <!-- container-scroller -->
  <!-- base:js -->
	</body>
 {{--  <script src="{{asset('vendors/js/vendor.bundle.base.js')}}"></script> --}}
  <!-- endinject -->
  <!-- inject:js -->
  <script src="{{asset('js/off-canvas.js')}}"></script>
  <script src="{{asset('js/hoverable-collapse.js')}}"></script>
  <script src="{{asset('js/template.js')}}"></script>
  <script src="{{asset('js/settings.js')}}"></script>
  {{--<script src="js/todolist.js"></script>--}}
  <!-- endinject -->
  {{-- <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script> --}}
  <script type="text/javascript" src="{{asset('js/toastr.min.js')}}"></script>
   <script>
	@if(Session::has('message'))
		var type="{{Session::get('alert-type','info')}}";	
		switch(type){
			case 'info':
		         toastr.info("{{ Session::get('message') }}");
		         break;
	        case 'success':
	            toastr.success("{{ Session::get('message') }}");
	            break;
         	case 'warning':
	            toastr.warning("{{ Session::get('message') }}");
	            break;
	        case 'error':
		        toastr.error("{{ Session::get('message') }}");
		        break;
		}
	@endif
	</script>

</html>
