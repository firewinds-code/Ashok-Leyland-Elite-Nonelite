<!doctype html>
<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Elite_Support! @yield("title")</title>
	<script src="{{asset('js/jquery-min.js')}}"></script>
	<link rel="stylesheet" href="{{asset('css/style.css')}}">
	<link rel="stylesheet" href="{{asset('font-awesome/css/font-awesome.css')}}">
	<link rel="stylesheet" href="{{asset('font-awesome/css/font-awesome.min.css')}}">
	<link rel="stylesheet" href="{{asset('vendors/datatables.net-bs4/dataTables.bootstrap4.css')}}">
	<script src="{{asset('js/form_validation.js')}}"></script>
	<!-- endinject -->
	<link rel="shortcut icon" href="{{asset('images/favicon.ico')}}" />
	<link href="{{asset('css/toastr.min.css')}}" rel="stylesheet">
</head>
<body>
	<div class="horizontal-menu">

		<nav class="navbar top-navbar col-lg-12 col-12 p-0" style="padding:0">
					<div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                       <a class="navbar-brand brand-logo" href="{{url('ticket-creation')}}"><img src="{{asset('images/al-logo.svg')}}" alt="logo" width="178.05" height="37.88" /></a>
						<a class="navbar-brand brand-logo-mini" href="{{url('ticket-creation')}}"><img src="{{asset('images/al-logo.svg')}}" alt="logo" width="178.05" height="37.88" /></a>
                    </div>
					<div style="text-align:center;width:100%;position: absolute;">
						<h4 class="card-title">
							<b>Elite Support</b> <br>
                    </div>
					<div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">

					</div>

		</nav>
	</div>


<div class="container-scroller">

	<div class="container-fluid page-body-wrapper">
        <div class="main-panel">
			@section('bodycontent')
			@show
		</div>
	</div>
</div>

    <footer class="footer">
		<div class="w-100 clearfix">
			@php $curYear = date("Y"); @endphp
			<span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © {{$curYear}}
				<a href="https://cogenteservices.com/" target="blank">Cogent E-Services Limited</a>. All rights reserved.</span>
		</div>
	</footer>
</body>
</html>

	<script src="{{asset('vendors/js/vendor.bundle.base.js')}}"></script>
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












