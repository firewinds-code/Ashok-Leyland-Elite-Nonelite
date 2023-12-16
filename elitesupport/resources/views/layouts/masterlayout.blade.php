<!doctype html>
<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	{{-- <meta http-equiv="Content-Security-Policy" content="default-src 'self' data: gap: https://ssl.gstatic.com 'unsafe-eval'; style-src 'self' 'unsafe-inline'; script-src 'self' https://helpline.ashokleyland.com/elitesupport/ 'unsafe-inline' 'unsafe-eval';"> --}}

	{{-- <meta http-equiv="Content-Security-Policy" content="default-src *; style-src 'self' 'unsafe-inline'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://helpline.ashokleyland.com/ "> --}}
	
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<title>Elite_Support! @yield("title")</title> 
	<script src="{{asset('js/jquery-min.js')}}"></script>
	{{-- <link rel="stylesheet" href="{{asset('vendors/css/vendor.bundle.base.css')}}"> --}}
	<link rel="stylesheet" href="{{asset('css/style.css')}}">
	<link rel="stylesheet" href="{{asset('font-awesome/css/font-awesome.css')}}">
	<link rel="stylesheet" href="{{asset('font-awesome/css/font-awesome.min.css')}}">
	<link rel="stylesheet" href="{{asset('vendors/datatables.net-bs4/dataTables.bootstrap4.css')}}">
	<script src="{{asset('js/form_validation.js')}}"></script>
	<!-- endinject -->
	<link rel="shortcut icon" href="{{asset('images/favicon.ico')}}" />
	<link href="{{asset('css/toastr.min.css')}}" rel="stylesheet">
	<!-- Toastr Css  -->

	{{-- Ajax Datatable --}}
	{{-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
	<script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.3.5/js/dataTables.buttons.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.3.5/js/buttons.html5.min.js"></script> --}}
	{{-- Ajax Datatable --}}
	

	{{-- <!-- <script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyADPkns3qwooRo_1WuhIcr0665fQbHNILU&callback=initMap">--> --}}
	
	{{-- <script async="" src="{{asset('js/google-map-api.js')}}"></script> --}}

	{{-- <script async="" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC24ZE24NHk0-PwEEE0o5rXEXNujhQ5RCY&amp;callback=initMap&amp;libraries=geometry"></script> --}}

	<script async="" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAV6BgWh1ehLjFne6sl9pvkkYrXyrI792U&amp;callback=initMap&amp;libraries=geometry"></script>
	{{-- <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC24ZE24NHk0-PwEEE0o5rXEXNujhQ5RCY&callback=initMap&libraries=geometry"></script> --}}
	{{-- <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCw3k4zHKvIlohxAF5cDVkFQuDVnCwjKiQ&callback=initMap&libraries=geometry"></script> --}}
	{{-- <script async="" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAV6BgWh1ehLjFne6sl9pvkkYrXyrI792U&amp;callback=initMap&amp;libraries=geometry"></script> --}}
{{-- <!-- <script src="{{asset('js/google-map.js')}}"></script> --> --}}
<style>

	.loader {
		position: fixed;
		left: 0px;
		top: 0px;
		width: 100%;
		height: 100%;
		z-index: 9999;
		background: url('public/images/loading.gif') center no-repeat #fff;
	}

</style>
	
</head>
<body>

<!-- Paste this code after body tag -->
	<div class="loader"></div>
	<div class="loader" style="display: none;" id="ajaxLoader"></div>
<!-- Ends -->

<div class="container-scroller">
	<!-- partial:partials/_horizontal-navbar.html -->
	<div class="horizontal-menu">
		
		<nav class="navbar top-navbar col-lg-12 col-12 p-0" style="padding:0">
					<div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
						@if(Auth::user()->role == '29' || Auth::user()->role == '30')
						@php $accessView ='Yes'; @endphp
						@else
						@php $accessView='No'; @endphp
						@endif
						{{-- @if(Auth::user()->role') == '29' || Auth::user()->role') == '30') --}}
						@if(Auth::user()->user_type_id == '5')
						<a class="navbar-brand brand-logo" href="{{url('ticket-creation')}}"><img src="{{asset('images/al-logo.svg')}}" alt="logo" width="178.05" height="37.88" /></a>
						<a class="navbar-brand brand-logo-mini" href="{{url('ticket-creation')}}"><img src="{{asset('images/al-logo.svg')}}" alt="logo" width="178.05" height="37.88" /></a>
						@elseif(Auth::user()->user_type_id == '3')
						<a class="navbar-brand brand-logo" href="{{url('case-list')}}"><img src="{{asset('images/al-logo.svg')}}" width="178.05"   height="37.88" alt="logo"/></a>
						<a class="navbar-brand brand-logo-mini" href="{{url('case-list')}}"><img src="{{asset('images/al-logo.svg')}}" width="178.05"  height="37.88" alt="logo"/></a>
						@else
						<a class="navbar-brand brand-logo" href="{{url('dashboard2')}}"><img src="{{asset('images/al-logo.svg')}}"  width="178.05"   height="37.88" alt="logo"/></a>
						<a class="navbar-brand brand-logo-mini" href="{{url('dashboard2')}}"><img src="{{asset('images/al-logo.svg')}}" width="178.05"  height="37.88" alt="logo"/></a>
						@endif
						
					</div>
					<div style="text-align:center;width:100%;position: absolute;">
						<h4 class="card-title">
							<b>Elite Support</b> <br>
							{{-- <p style="text-align: left;">Cogent CMS</p> --}} </h4>
					</div>
					<div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
						<ul class="navbar-nav navbar-nav-right">
							<li class="nav-item nav-profile dropdown" style="float: right;">
								<a class="nav-link" href="#" data-toggle="dropdown" id="profileDropdown">
									<i class="fa fa-user fa-1x" aria-hidden="true"></i> 
									@if(Auth::user()->user_type_id == 3)
									{{"Dealer"}}
										{{-- {{Auth::user()->dealerName}} --}}
									@else
										{{Auth::user()->name}}
									@endif
								</a>
								<div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
									<a  class="dropdown-item" href="http://ashokleyland.localhost/non_elitesupport/autologin?id={{base64_encode(Auth::user()->id)}}" ><i class="fa fa-exchange" aria-hidden="true"></i>
										Standard Support
									</a>
									<a class="dropdown-item" href="{{ url('reset-password') }}">
										<i class="fa fa-cogs"></i>
										Reset Password 
									</a>
									<form method="POST" action="{{ route('logout') }}" x-data>
										@csrf
										<a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
										this.closest('form').submit(); ">
											<i class="fa fa-sign-out" aria-hidden="true"></i> Logout 
										</a>
									</form>
									
								</div>
							</li>
							
						</ul>
						<button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="horizontal-menu-toggle">
							<span class="fa fa-bars"></span>
						</button>
					</div>
			
		</nav>
<nav class="navbar navbar-expand-lg navbar-light" style="background: #d8d7d5;">
@if(Route::current()->getName() == 'cms')
    	<i class="fa fa-bars" aria-hidden="true" id="slide-toggle" style="cursor: pointer;"></i><a class="nav-link" style="width: 30%;"><span class="menu-title">Complaint Management System</span></a>
@endif
<div class="container">
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
	<span class="navbar-toggler-icon"></span>
	</button>
  	<div class="collapse navbar-collapse" id="navbarSupportedContent">
    <!--<ul class="navbar-nav mr-auto">-->
    <ul class="navbar-nav mr-auto"> <!--mr-auto,mx-auto-->    	
      	<li class="nav-item ">     		
				
				@if(Auth::user()->user_type_id == '5')
				 	<a class="nav-link" href="{{url('ticket-creation')}}">
						<span class="menu-title">HOME</span>
					</a>
					@elseif(Auth::user()->user_type_id == '3')					
					<a class="nav-link" href="{{url('case-list')}}">
						<span class="menu-title">HOME</span>
					</a>
					@else
					<a class="nav-link" href="{{url('dashboard2')}}">
						<span class="menu-title">HOME</span>
					</a>
				@endif
				
		</li>
		<li class="nav-item dropdown"> 
			<a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">TICKET
					<i class="fa fa-angle-down" style="margin-left: 5px;"></i>
			</a>
			<div class="dropdown-menu" aria-labelledby="navbarDropdown">
				@if(Auth::user()->role == '29' || Auth::user()->role == '30' || Auth::user()->role == '87')
					<a class="dropdown-item" href="{{url('ticket-creation')}}">Create Ticket</a>
					<a class="dropdown-item" href="{{url('followups')}}">Caller List</a>
				@endif
				<a class="dropdown-item" href="{{url('case-list')}}">Ticket List</a>
				
			</div>
		</li>
		@if(Auth::user()->role == '29' || Auth::user()->role == '30')
		<li class="nav-item dropdown">
				<a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">MASTER PAGE
					<i class="fa fa-angle-down" style="margin-left: 5px;"></i>
			</a>
			<div class="dropdown-menu" aria-labelledby="navbarDropdown">
				{{-- 
				<a class="dropdown-item" href="{{url('customer-master')}}">Customer Master</a>
				<a class="dropdown-item" href="{{url('area')}}">Location</a>
				<a class="dropdown-item" href="{{url('brand')}}">Brand</a>
				<a class="dropdown-item" href="{{url('complaint-type')}}">Complaint Category</a>
				<a class="dropdown-item" href="{{url('vehicle-models')}}">Manage Vehicle Models</a>
				--}}
				<a class="dropdown-item" href="{{url('users')}}">Manage Users</a>
				<a class="dropdown-item" href="{{url('vehicle')}}">Manage Vehicle </a>
				<a class="dropdown-item" href="{{url('role')}}">Manage Roles</a>
				<a class="dropdown-item" href="{{url('dealer')}}">Manage Dealer</a>
				<a class="dropdown-item" href="{{url('owner-view')}}">Manage Owner</a>
				<a class="dropdown-item" href="{{url('owner-contact-view')}}">Manage Customer Contact</a>
				<a class="dropdown-item" href="{{url('escalation')}}">Manage Escalation</a>
				{{-- <a class="dropdown-item" href="{{url('import-excel')}}">Manage M3 Master</a>	--}}			
				<a class="dropdown-item" href="{{url('change-password')}}">Change User Password</a>
				<a class="dropdown-item" href="{{url('vahan-api-report')}}">Download Vahan API Report</a>
				
			</div>
		</li>
		<li class="nav-item dropdown"> 
			<a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">LOCATION
					<i class="fa fa-angle-down" style="margin-left: 5px;"></i>
			</a>
			<div class="dropdown-menu" aria-labelledby="navbarDropdown">
				@if(Auth::user()->role == '29' || Auth::user()->role == '30')
				<a class="dropdown-item" href="{{url('region-view')}}">Manage Zone</a>
				<a class="dropdown-item" href="{{url('state-view')}}">Manage Region</a>
				<a class="dropdown-item" href="{{url('city-view')}}">Manage Area</a>
				@endif
				{{-- <a class="dropdown-item" href="{{url('case-list')}}">Case List</a> --}}
			</div>
		</li>
		{{-- <li class="nav-item dropdown">
				<a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">ESCALATION
					<i class="fa fa-angle-down" style="margin-left: 5px;"></i>
			</a>
			<div class="dropdown-menu" aria-labelledby="navbarDropdown">
				<a class="dropdown-item" href="{{url('escalation')}}">Escalation</a>
				<a class="dropdown-item" href="{{url('dealer-escalation')}}">Dealer Escalation</a>
			</div>
		</li> --}}
		@endif
		<li class="nav-item dropdown">
			<a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">REPORT
				<i class="fa fa-angle-down" style="margin-left: 5px;"></i>
			</a>
			<div class="dropdown-menu" aria-labelledby="navbarDropdown">
				<a class="dropdown-item" href="{{url('consolidated-report')}}">Consolidated Report</a>
				<a class="dropdown-item" href="{{url('consolidated-closed-report')}}">Consolidated Closed Report</a>
				<a class="dropdown-item" href="{{url('ticket-report')}}">Single Ticket Report</a>
				<a class="dropdown-item" href="{{url('dealer-activity-report')}}">Dealer Activity</a>
				{{-- <a class="dropdown-item" href="{{url('top-focus')}}">Top Focus Report</a>
				<a class="dropdown-item" href="{{url('open-complaint-report')}}">Open Complaint Report</a>
				<a class="dropdown-item" href="{{url('dealer-summary-report')}}">Dealer Summary Report</a>
				<a class="dropdown-item" href="{{url('kpi-trend')}}">KPI Trend</a>
				<a class="dropdown-item" href="{{url('pcs-process')}}">PCS Process</a>
				<a class="dropdown-item" href="{{url('preventive-action')}}">Preventive Action</a> --}}
			</div>
		</li>
		@if(Auth::user()->role == '29' || Auth::user()->role == '30' || Auth::user()->role == '87')
 	
 		<li class="nav-item dropdown">
 			<a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">GENERAL TICKET
 				<i class="fa fa-angle-down" style="margin-left: 5px;"></i>
 			</a>
 			<div class="dropdown-menu" aria-labelledby="navbarDropdown">
 				<a class="dropdown-item" href="{{url('general-ticket')}}">General Ticket</a>
 				<a class="dropdown-item" href="{{url('general-ticket-list')}}">General Ticket List</a>
 				
 			</div>
 		</li>
		{{-- Cogent Followup --}}
		<li class="nav-item dropdown">
			<a class="nav-link" href="#" id="navbarDropdownFollowup" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">FOLLOWUP
				<i class="fa fa-angle-down" style="margin-left: 5px;"></i>
			</a>
			<div class="dropdown-menu" aria-labelledby="navbarDropdownFollowup">					
				<a class="dropdown-item" href="{{url('cogent-assign')}}">Assign Followup</a>
				<a class="dropdown-item" href="{{url('cogent-dealer')}}">Dealer Followup</a>
				<a class="dropdown-item" href="{{url('cogent-complete')}}">Complete Followup</a>
			</div>
		</li>
		{{-- Cogent Followup --}}
 		@endif
		@if(Auth::user()->role == '78')
		 <li class="nav-item ">
			 <a class="nav-link" href="{{url('asm-user')}}">
				 <span class="menu-title">ASM User</span>
			 </a>
		 </li>
		 <li class="nav-item ">
			 <a class="nav-link" href="{{url('asm-dealer')}}">
				 <span class="menu-title">ASM Dealer</span>
			 </a>
		 </li>
		 
	 @endif
	 @if(Auth::user()->role == '79')
		 <li class="nav-item ">
			 <a class="nav-link" href="{{url('rsm-user')}}">
				 <span class="menu-title">RSM USER</span>
			 </a>
		 </li>
		 <li class="nav-item ">
			 <a class="nav-link" href="{{url('rsm-dealer')}}">
				 <span class="menu-title">RSM DEALER</span>
			 </a>
		 </li>
		 
	 @endif
	
	 @if(Auth::user()->role == '29' || Auth::user()->role == '30' || Auth::user()->role == '87')
	 <li class="nav-item dropdown">
		<a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">PSF SURVEY
			<i class="fa fa-angle-down" style="margin-left: 5px;"></i>
		</a>
		<div class="dropdown-menu" aria-labelledby="navbarDropdown">
			<a class="dropdown-item" href="{{url('psf-import')}}">Upload Data</a>
			<a class="dropdown-item" href="{{url('psf-list')}}">List</a>
			<a class="dropdown-item" href="{{url('psf-report')}}">Datewise Report</a>
			<a class="dropdown-item" href="{{url('psf-list-report')}}">Download Not Attempt Report</a>
			<a class="dropdown-item" href="{{url('psf-complaint-report')}}">Download Complaint Log Report</a>
			{{-- <a class="dropdown-item" href="{{url('psf-survey')}}">Survey</a> --}}
		</div>
	</li>
	@endif
	
	@if(Auth::user()->user_type_id== '3' || Auth::user()->user_type_id== '2')
	<li class="nav-item dropdown">
		<a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">PSF SURVEY
			<i class="fa fa-angle-down" style="margin-left: 5px;"></i>
		</a>
		<div class="dropdown-menu" aria-labelledby="navbarDropdown">
			<a class="dropdown-item" href="{{url('dealer-complaint-list')}}">Complaint</a>
			{{-- <a class="dropdown-item" href="{{url('dealer-survey-list')}}">Survey</a> --}}
			<a class="dropdown-item" href="{{url('psf-report')}}">Datewise Report</a>
		</div>
	</li>
	@endif
    </ul>
   
  </div>
</div>
</nav>

	</div>


	<!-- partial -->
	<div class="container-fluid page-body-wrapper">
		<div class="main-panel">
			@section('bodycontent')
			@show
		</div>
	</div>



	<!-- content-wrapper ends -->
	<!-- partial:../../partials/_footer.html -->
	<footer class="footer">
		<div class="w-100 clearfix">
			@php $curYear = date("Y"); @endphp
			<span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © {{$curYear}}
				<a href="https://cogenteservices.com/" target="blank">Cogent E-Services Limited</a>. All rights reserved.</span>				
		</div>
	</footer>
	<!-- partial -->
</div>
<!-- main-panel ends -->

<!-- page-body-wrapper ends -->

<!-- container-scroller -->

<!-- base:js -->

{{-- <script src="{{asset('js/off-canvas.js')}}"></script>
<script src="{{asset('js/hoverable-collapse.js')}}"></script>
<script src="{{asset('js/template.js')}}"></script> --}}

	<script src="{{asset('vendors/js/vendor.bundle.base.js')}}"></script>

		{{-- <script src="{{asset('js/data-table.js')}}"></script> --}}
  <!-- Toastr Js -->   
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
	<link rel="stylesheet" href="{{asset('datatable/css/jquery.dataTables.min.css')}}" />
	<link rel="stylesheet" href="{{asset('datatable/css/buttons.dataTables.min.css')}}" />
	<script type="text/javascript" src="{{asset('datatable/js/jquery-1.12.3.js')}}"></script>
	<script type="text/javascript" src="{{asset('datatable/js/jquery.dataTables.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('datatable/js/dataTables.buttons.min.js')}}"></script>
	
	<script type="text/javascript" src="{{asset('datatable/js/jszip.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('datatable/js/pdfmake.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('datatable/js/vfs_fonts.js')}}"></script>
	<script type="text/javascript" src="{{asset('datatable/js/buttons.html5.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('datapicker/js/jquery.datetimepicker.js')}}"></script>
	<link rel="stylesheet" href="{{asset('datapicker/css/jquery.datetimepicker.min.css')}}">
	

	<script type="text/javascript">
		$(document).ready(function () {	
			$.ajaxSetup({
 				headers: {
 					'X-Accel-Buffering': 'no',
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
 				}
 			});
	        $("#slide-toggle").click(function(){
	            $(".box").animate({
	                width: "toggle"
	            });
	        });
			$('#order-listing').DataTable({
				dom: 'Bfrtip',				
				"language": {
					"paginate": {
						"previous": "<",
						"next": ">"
					}
				},
				buttons: [{
						extend: 'excel',
						text: 'Excel',
						className: 'exportExcel',
						filename: '@yield("title")',
						exportOptions: { modifier: { page: 'all'} }
					}/*,
							{
						extend: 'csv',
						text: 'CSV',
						className: 'exportExcel',
						filename: 'Test_Csv',
						exportOptions: { modifier: { page: 'all'} }
					},
							{
						extend: 'pdf',
						text: 'PDF',
						className: 'exportExcel',segment
						filename: 'Test_Pdf',
						exportOptions: { modifier: { page: 'all'} }
					}*/]
			});

		});
	</script>

	
<script type="text/javascript">
$('#complaintcategory').change(function()
	    {
	    	var comp=this.value;
	$.ajax({ url: '{{url("get-complaint-type")}}',
	data: { 'comp':comp },
	success: function(data){
	var Result = data.split(",");var str = '';
	Result.pop();
	for (item in Result)
	{
			str += "<option value='" + Result[item] + "'>" + Result[item] + "</option>";
	}
	document.getElementById('subcategory').innerHTML =str;
	}});
});
/* fn_get_zone('');
function fn_get_zone(el)
{	
		$.ajax({ url: '{{url("get-zone2")}}',
			 success: function(data) {	
			
				 var Result = data.split(",");var str = ''; // Central,East,North,South,West
				 Result.pop();
				 var custIds = new Array(el.trim());
				 var selectedIds = custIds.join(',').split(',');
				 for (item1 in Result) {
					 var Result2 = Result[item1].split("~");
					 if (el!='') {
						 if (jQuery.inArray( Result2[0], selectedIds ) !== -1 ) {
							str += "<option value='" + Result2[0] + "' selected>" + Result2[1] + "</option>";
							} 
							else
							{
							str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
							}
					}
					 else
					  {
						 str += "<option value='" + Result2[0]+ "'>" + Result2[1] + "</option>";
					 }
				 }
	document.getElementById('zone').innerHTML = str;
			 }
		 });
	}
 */
function fn_user_type_change(el,ell)
{
		var id='';
		if(ell==''){id=el.value;}else{id=el;}
		if(id == '3'){
			$('#td_dealer_id').show();
			$('#td_dealer_id1').hide();
			$('#td_State').hide();$('#td_City').hide();$('#td_zone').hide();
		}else{
			$('#td_dealer_id').hide();
			$('#td_dealer_id1').show();
			$('#td_State').show();$('#td_City').show();$('#td_zone').show();$('#td_dealer_id').hide();
		}
		if (ell!=''){
			id=el;
		}
		$.ajax({ url: '{{url("get-role")}}',data :{'id':id},
			 success: function(data) {
				var Result = data.split(",");var str = '';
				Result.pop();
				for (item in Result) {
				 	var Result2 = Result[item].split("~");
					if (ell!='') {
						if (ell==Result2['0']) {
							str += "<option value='" + Result2['0'] + "' selected>" + Result2['1'] + "</option>";
						} else {
							str += "<option value='" + Result2['0'] + "'>" + Result2['1'] + "</option>";
						}
					}else {
						str += "<option value='" + Result2['0'] + "'>" + Result2['1'] + "</option>";
					}
				}
				document.getElementById('role').innerHTML =str;
			 }
		 });
	}
function getCcRole(el){

	$.ajax({ url: '{{url("get-cc-role")}}',	
	success: function(data){		
	var Result = data.split(",");var str = '';
	Result.pop();
		var custIds = new Array(el.trim());
		var selectedIds = custIds.join(',').split(',');
		for (item1 in Result) {
			var Result2 = Result[item1].split("~");
			if (el!='') {
				if (jQuery.inArray( Result2[0], selectedIds ) !== -1 ) {
				str += "<option value='" + Result2[0] + "' selected>" + Result2[1] + "</option>";	
				}
				else
				{
				str += "<option value='" + Result2[0] + "'>" +Result2[1] + "</option>";		
				}	
			}
			else
			{
			str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
			}
	}
	document.getElementById('cc_to').innerHTML =str;
	}});
}
function fn_Zone_change(el,ell){			
			var myarray= [];
			var favorite = [];
			if(ell!='')
			{
	            $('#zone :selected').each(function(i, sel)
	            {
	    			//favorite.push($(this).val());
	    			favorite.push(el);
				});
				var zz=favorite.join(",");
			}
			else
			{
				var zz=el;
			}
			
            $.ajax({ url: '{{url("get-state")}}',data: { 'zone':zz},success: function(data){
			var Result = data.split(",");var str = '';
			Result.pop();
				var custIds = new Array(ell.trim());
				var selectedIds = custIds.join(',').split(',');
			for (item1 in Result) {
					var Result2 = Result[item1].split("~");
				if (ell!='') {
					if (jQuery.inArray( Result2[0], selectedIds ) !== -1 ) {
						str += "<option value='" +Result2[0] + "' selected>" + Result2[1] + "</option>";
					}
					else
					{
						str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
					}
				}
				else
				{
					str += "<option value='" +Result2[0] + "'>" + Result2[1] + "</option>";
				}
			}
			document.getElementById('state').innerHTML = str;
		}
	});
}
function fn_State_change(el,ell,elll)// Zone ID,State ID,Edit
{
	var state = ell;
	var favorite = [];
			if(elll!='')
			{
            $('#state :selected').each(function(i, sel)
            { 
    			//favorite.push($(this).val());
    			favorite.push(elll);
			});
			var state=favorite.join(",");
			}
			else
			{
				var state=ell;
			}
	
	$.ajax({ url: '{{url("get-city")}}',
	data: { 'r_id':el,'s_id':ell },
	success: function(data){
		
	var Result = data.split(",");var str = '';
	Result.pop();
	for (item in Result)
	{	Result2 = Result[item].split("~");
		var mith = elll.split(",");
		if(elll!='')
			{
				if (jQuery.inArray(Result2[0], mith)!=='-1') //if(ell==Result[item])
				{
				str += "<option value='" + Result2[0] + "' selected>" + Result2[1] + "</option>";	
				}
				else
				{
				str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";		
				}	
			}
			else
			{
			str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";			
			}
	}
	document.getElementById('City').innerHTML = str;
	}});
}

function Dealer_Zone_change(el,ell){
	var myarray= [];
	var favorite = [];
	if(ell!=''){
		$('#zone :selected').each(function(i, sel){ 
		//favorite.push(ell);
		});
		var zz=el;
	}else{
		$('#zone :selected').each(function(i, sel){
		favorite.push($(this).val());
		});
		var zz=favorite.join(",");
	}
	$.ajax({ url: '{{url("get-multi-id-state")}}',data: { 'zone':zz},
		success: function(data){
			console.log(data);
			var Result = data.split(",");var str = '';
			Result.pop();
			for (item in Result){
				Result2 = Result[item].split("~");
				var mith = ell.split(",");
				if(ell!=''){
					if (jQuery.inArray(Result2[0], mith)!='-1'){
						str += "<option value='" + Result2[0] + "' selected>" + Result2[1] + "</option>";
					}else{
						str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
					}
				}else{
					str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
				}
			}
			document.getElementById('state').innerHTML =str;
		}
	});
}
/* function Dealer_State_change(el,ell,elll)
{
	
	var favorite = [];
	var AllZone_ = [];
	var AllState_ = [];
			if(elll!='')
			{
			//var state=favorite.join(",");
			
			AllZone = el;
			AllState=ell;
			}
			else
			{
				
				$('#zone :selected').each(function(i, sel)
	            { 
	    			AllZone_.push($(this).val());
				});
				var AllZone = AllZone_.join(',');
				
				$('#state :selected').each(function(i, sel)
	            { 
	    			AllState_.push($(this).val());
				});
				
				var AllState = AllState_.join(',');
			}
			
	//$('#City').val('NA');
	
	
	
	$.ajax({ url: '{{url("get-multi-id-city")}}',
	data: { 'r_id':AllZone,'s_id':AllState },
	success: function(data){		
	var Result = data.split(",");var str = '';
	Result.pop();
	for (item in Result)
	{	Result2 = Result[item].split("~");
		var mith = elll.split(",");
		if(elll!='')
			{
				if (jQuery.inArray(Result2[0], mith)!='-1') //if(ell==Result[item])
				{
				str += "<option value='" + Result2[0] + "' selected>" + Result2[1] + "</option>";	
				}
				else
				{
				str += "<option value='" + Result2[0] + "'>" +Result2[1] + "</option>";		
				}	
			}
			else
			{
			str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";			
			}
	}
	document.getElementById('City').innerHTML = str;
	}});
}
 */
/* function Dealer_get_zone(ell)
{
	
		$.ajax({ url: '{{url("get-multi-zone")}}',
			 success: function(data) {
				 var Result = data.split(",");var str = '';
				 Result.pop();				 
				 for (item in Result){
				  	Result2 = Result[item].split("~");
				  	
					var mith = ell.split(",");					
					if (ell!=''){
			  			if (jQuery.inArray(Result2[0], mith)!='-1')
						{
							$('#zone').val(mith);
						} 							
					}					 
				 }			
			 }
		 });
	} */
function fn_City_change(el,ell,elll)
{
	$('#Dealer').val('');$('#DealerCode').val('');$('#tdDealerCode').hide();
	var state = el.value;
	var city = ell.value;
	//alert(state+city);
	if(elll!=''){var state = el;var city = ell;}
	$.ajax({ url: '{{url("get-zone")}}',
		data: { 'state':state,'city':city},
		success: function(data){
			var Result = data.split(",");var str = '';
			Result.pop();
			for (item in Result){
				if(elll!=''){
					if(elll==Result[item]){
						str += "<option value='" + Result[item] + "' selected>" + Result[item] + "</option>";	
					}
					else{
						str += "<option value='" + Result[item] + "'>" + Result[item] + "</option>";		
					}	
				}
				else{
					str += "<option value='" + Result[item] + "'>" + Result[item] + "</option>";			
				}
			}
			document.getElementById('Zone').innerHTML =str;
		}
	});
}


function get_dealer(zone,state,city,product)
{
	var Zone_ = zone.value;
	var State_ = state.value;
	var City_ = city.value;
	var Product_ = product.value;
	$.ajax({ url: '{{url("get-dealer")}}',
	data: {'zone':Zone_,'state':State_,'city':City_,'product':Product_},
	success: function(data){
	var Result = data.split(",");var str = '';
	Result.pop();
	for (item in Result){
		str += "<option value='" + Result[item] + "'>" + Result[item] + "</option>";	
	}
	document.getElementById('Dealer').innerHTML =str;
	}
	});
	// alert(Zone+State+City+Product);
}
function fn_dealer_change(el,ell,elll,ellll,elllll)
{
	$('#tdDealerCode').hide();
	$('#DealerCode').val('');
	var state = el.value;
	var city = ell.value;
	var zone = elll.value;
	var dealer = ellll.value;
	if(elllll!=''){var state = el;var city = ell;var zone = elll;var dealer = ellll;}
	$.ajax({ url: '{{url("get-dealercode")}}',
	data: { 'state':state,'city':city,'zone':zone,'DN':dealer},
	success: function(data){
		if(elllll!=''){
			$('#tdDealerCode').show();
			$('#DealerCode').val(elllll);
		}
		else{
			$('#tdDealerCode').show();
			$('#DealerCode').val(data);
		}
	}
	});
}
function fun_loc(el)
{
	var  Zone_=el.value;
	$.ajax({ url: '{{url("get-location")}}',
	data: {'zone':Zone_},
	success: function(data){
		var Result = data.split(",");var str = '';
			//Result.pop();
			for (item in Result)
			{
				str += "<option value='" + Result[item] + "'>" + Result[item] + "</option>";	
			}
	document.getElementById('location').innerHTML = str;
	}
	});
}

	function fn_get_brand(el){	
		$.ajax({ url: '{{url("get-brand")}}',success: function(data) {				  
			var Result = data.split(",");var str = '';			
			Result.pop();			
			for (item in Result){
				if (el!=''){
					Result2 = Result[item].split("~");					
					var mith = el.split(",");					
		  			if (jQuery.inArray(Result2[0], mith)!='-1'){
						str += "<option value='" + Result2[0] + "' selected>" + Result2[1] + "</option>";
					} 
					else{
						str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
					}
				}
				else{
					str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
				}
			}
			document.getElementById('brand').innerHTML = str;
		}
		});
	}
	function fn_get_city_zone_id(el,ell){	
		$.ajax({ url: '{{url("get-city-zone-id")}}',data: {'zone_id':el},success: function(data) {				  
			var Result = data.split(",");var str = '';			
			Result.pop();
			
			for (item in Result){
			Result2 = Result[item].split("~");
				if (ell!=''){										
					//var mith = el.split(",");					
		  			//if (jQuery.inArray(Result2[0], ell)!='-1'){
		  			if (Result2[0]==ell){
						str += "<option value='" + Result2[0] + "' selected>" + Result2[1] + "</option>";
					} 
					else{
						str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
					}
				}
				else{
					str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
				}
			}
			document.getElementById('city').innerHTML =str;
		}
		});
	}	
	function fn_get_product(el){	
		$.ajax({ url: '{{url("get-product")}}',success: function(data) {				  
			var Result = data.split(",");var str = ''; 
			Result.pop();
			
			for (item in Result){
				if (el!=''){
					Result2 = Result[item].split("~");					
					var mith = el.split(",");
		  			if (jQuery.inArray(Result2[0], mith)!='-1'){
						str += "<option value='" + Result2[0] + "' selected>" + Result2[1] + "</option>";
					} 
					else{
						str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
					}
				}
				else{
					str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
				}
			}
			
			document.getElementById('product').innerHTML = str;			
		}
		});
	}
	function fn_get_complaint_cat(el){	
		$.ajax({ url: '{{url("get-complaint-cat")}}',success: function(data) {				  
			var Result = data.split(",");var str = ''; 
			Result.pop();
			
			for (item in Result){
				if (el!=''){
					Result2 = Result[item].split("~");					
					var mith = el.split(",");
		  			if (jQuery.inArray(Result2[0], mith)!='-1'){
						str += "<option value='" + Result2[0] + "' selected>" + Result2[1] + "</option>";
					} 
					else{
						str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
					}
				}
				else{
					str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
				}
			}
			
			document.getElementById('complaint_cat').innerHTML = str;			
		}
		});
	}
	function fn_get_dealer(el){	
		$.ajax({ url: '{{url("get-multi-dealer")}}',success: function(data) {
			var Result = data.split(",");var str = ''; 
			Result.pop();
			
			for (item in Result){
				if (el!=''){
					Result2 = Result[item].split("~");					
					var mith = el.split(",");
		  			if (jQuery.inArray(Result2[0], mith)!='-1'){
						str += "<option value='" + Result2[0] + "' selected>" + Result2[1] + "</option>";
					} 
					else{
						str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
					}
				}
				else{
					str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
				}
			}
			
			document.getElementById('dealer_code_asoc').innerHTML =str;			
			
		}
		});
	}
	function fn_get_scope_Service(el){
	
		$.ajax({ url: '{{url("get-scope-service")}}',success: function(data) {
		
			var Result = data.split(",");var str = ''; 
			Result.pop();
			
			for (item in Result){
				if (el!=''){
					Result2 = Result[item].split("~");
									
					var mith = el.split(",");
		  			if (jQuery.inArray(Result2[0], mith)!='-1'){
						str += "<option value='" + Result2[0] + "' selected>" + Result2[1] + "</option>";
					} 
					else{
						str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
					}
				}
				else{
					str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
				}
			}
			
			document.getElementById('scope_of_services').innerHTML = str;			
			
		}
		});
	} 
	function fn_get_support_type(el){	
		$.ajax({ url: '{{url("get-support-type")}}',success: function(data) {		
			var Result = data.split(",");var str = ''; 
			Result.pop();			
			for (item in Result){
				if (el!=''){
					Result2 = Result[item].split("~");
								
					var mith = el.split(",");
		  			if (jQuery.inArray(Result2[0], mith)!='-1'){
						str += "<option value='" + Result2[0] + "' selected>" + Result2[1] + "</option>";
					} 
					else{
						str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
					}
				}
				else{
					str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
				}
			}			
			document.getElementById('scope_of_services').innerHTML = str;						
		}
		});
	} 
	function fn_get_dealer_user(el){
	
		$.ajax({ url: '{{url("get-multi-dealer")}}',success: function(data) {
					  ;
			var Result = data.split(",");var str = ''; 
			Result.pop();
			
			for (item in Result){
				if (el!=''){
					Result2 = Result[item].split("~");
					var mith = el.split(",");
					
		  			if (jQuery.inArray(Result2[0], mith)!='-1'){
						str += "<option value='" + Result2[0] + "' selected>" + Result2[1] + "</option>";
					} 
					else{
						str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
					}
				}
				else{
					str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
				}
			}
			
			
			document.getElementById('dealer_id').innerHTML = str;			
		}
		});
	} 
function fn_product_change(el,ell,segment1,segment2)
{
			$('#segment').val('NA');
			var myarray= [];
			var favorite = [];
			if(ell!='' || segment1!='' || segment2!='')
			{
            $('#vehicle :selected').each(function(i, sel)
            { 
    			favorite.push($(this).val());
			});
			var zz=favorite.join(",");
			}
			else
			{
				var zz=el;
				$('#model').val('');
			}
			
            $.ajax({ url: '{{url("get-product-segment")}}',
            data: { 'product_id':zz},
			success: function(data){
				// alert(data);// 1,Mining~2,C & I~3,On-Road~4,Special App~
				var Result = data.split("~");var str = '';var str1 = '';var str2= '';
				Result.pop();
				var custIds = new Array(ell.trim());
				var selectedIds = custIds.join(',').split(',');
				for (item1 in Result) {
					var Result2 = Result[item1].split(",");
					if (ell!='') {
						if (jQuery.inArray( Result2[0], selectedIds ) !== -1 ) {
							str += "<option value='" + Result2[0] + "' selected>" +Result2[1] + "</option>";
						}
						else
						{
							str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
						}
					}
					else
					{
						str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
					}
					if(segment1!='')
					{
						var mith = segment1.split(",");
						if (jQuery.inArray( Result2[0], mith)!=='-1') //if(ell==Result[item])
						{
							str1 += "<option value='" + Result2[0] + "' selected>" +Result2[1] + "</option>";
						}
						else
						{
							str1 += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
						}
					}
					else
					{
						str1 += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
					}
					if(segment2!='')
					{
						var mith = segment2.split(",");
						if (jQuery.inArray( Result2[0], mith)!=='-1') //if(ell==Result[item])
						{
							str2 += "<option value='" + Result2[0] + "' selected>" +Result2[1] + "</option>";
						}
						else
						{
							str2 += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
						}
					}
					else
					{
						str2 += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
					}
				}
			document.getElementById('segment').innerHTML = "<optgroup>" + str + "</optgroup>";
			document.getElementById('segment1').innerHTML = "<optgroup>" + str1 + "</optgroup>";
			document.getElementById('segment2').innerHTML = "<optgroup>" + str2 + "</optgroup>";
			}
	});
}

function User_get_product(el){
		
		$.ajax({ url: '{{url("get-multi-product")}}',success: function(data) {				  
			
				 var Result = data.split(",");var str = ''; 				 
				 Result.pop();				 
				for (item in Result){
				  	Result2 = Result[item].split("~");
				  	
					var mith = el.split(",");					
					if (el!=''){
			  			if (jQuery.inArray(Result2[0], mith)!=='-1')
						{
							$('#product').val(mith);
							$('#vehicle').val(mith);
						} 							
					}					 
				}			
			}
		});
	} 
function User_product_change(el,ell)
{
			//alert(ell);
			var myarray= [];
			var favorite = [];
			if(ell!='')
			{           
			var zz='';
			}
			else
			{
				 $('#product :selected').each(function(i, sel)
	            { 
	    			favorite.push($(this).val());
				});
				var zz=favorite.join(",");
				
			}
			zz = zz !=''?zz:el;
			
            $.ajax({ url: '{{url("get-multi-product-segment")}}',
            data: { 'product_id':zz},
			success: function(data){				
				// alert(data);// 1,Mining~2,C & I~3,On-Road~4,Special App~
				var Result = data.split("~");var str = '';
				Result.pop();
				var custIds = new Array(ell.trim());
				var selectedIds = custIds.join(',').split(',');
				for (item1 in Result) {
					var Result2 = Result[item1].split(",");
					if (ell!='') {
						if (jQuery.inArray( Result2[0], selectedIds ) !== -1 ) {
							str += "<option value='" + Result2[0] + "' selected>" +Result2[1] + "</option>";
						}
						else
						{
							str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
						}
					}
					else
					{
						str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
					}
				}
			document.getElementById('segment').innerHTML = str;
			}
	});
}
function getSubComplaint(el,ell)
{	
		$.ajax({ url: '{{url("get-sub-complaint")}}',data: {'complaint_type_id':el},
			 success: function(data) {	
						
				 var Result = data.split(",");var str = ''; 				 
				 Result.pop();
				 var custIds = new Array(ell.trim());
				 var selectedIds = custIds.join(',').split(',');
				 for (item1 in Result) {
					 var Result2 = Result[item1].split("~");
					 if (ell!='') {
						 if (jQuery.inArray( Result2[0], selectedIds ) !== -1 ) {
							str += "<option value='" + Result2[0] + "' selected>" +Result2[1] + "</option>";
						}
						else
						{
							str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
						}
					}
					else
					{
						str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
					}
					 
				 }				 
				document.getElementById('sub_complaint_type').innerHTML = str;
			 }
		 });
	}
	function reloadPage(){
		location.reload(true);
	}
</script>

 <script>
		$(window).load(function(){
		     $('.loader').fadeOut();
		});
</script> 
</body>

</html>
<style>
.box-header{
    color: #eee;
    display: block;
    padding: 0px;
    position: relative;
    background-color: #19aec4;
}

.line_box{
    border: 1px solid #0089DA;
    padding: 20px;
}
.checkbox label, .radio label{
    min-height: 20px;
    padding-left: 8px;
    margin-bottom: 0;
    font-weight: 400;
    cursor: pointer;
}
.wpsp-gender-field .radio{
    display: inline-block;
    margin: 5px 0 8px;
}

.radio{
    padding-left: 20px;
}
</style>

 <style>
	.form-control {
    display: block;
    width: 100%;
    height: 24px;
    padding: 1px 7px;
    font-size: 14px;
    line-height: 1.42857143;
    color: #555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    font-weight: 400;
}

label {
    display: inline-block;
    max-width: 100%;
    margin-bottom: 0px;
    font-weight: 500;
        margin-top: 2px;
}

.form-group {
    margin-bottom: 2px;
}

.shivv{
	padding: 4px;
}
.fatch{
	font-weight: 500!important;
}
</style>
   