@extends("layouts.masterlayout")
@section('title','ASM Users')
@section('bodycontent')

<style>
	// Pager pagination
	.my-active span{
		background: linear-gradient(to bottom,#fff 0,#dcdcdc 100%) !important;
		color: white !important;
		border-color: #5cb85c !important;
				
	}

	.pager {
	padding-left: 0;
	margin: 20px 0;
	text-align: right;
	list-style: none;
	}
	.pager li {
	display: inline;
	}
	.pager li > a,
	.pager li > span {
	display: inline-block;
	padding: 5px 14px;
	background-color: #fff;
	border: 1px solid #ddd;
	/* border-radius: 15px; */
	}
	.pager li > a:hover,
	.pager li > a:focus {
	text-decoration: none;
	background-color: #eee;
	}
	.pager .next > a,
	.pager .next > span {
	float: right;
	}
	.pager .previous > a,
	.pager .previous > span {
	float: left;
	}
	.pager .disabled > a,
	.pager .disabled > a:hover,
	.pager .disabled > a:focus,
	.pager .disabled > span {
	color: #777;
	cursor: not-allowed;
	background-color: #fff;
	}
	.pager {
	padding-left: 0;
	margin: @line-height-computed 0;
	list-style: none;
	text-align: :right;
	&:extend(.clearfix all);
	li {
		display: inline;
		> a,
		> span {
		display: inline-block;
		padding: 5px 14px;
		background-color: @pager-bg;
		border: 1px solid @pager-border;
		border-radius: @pager-border-radius;
		}

		> a:hover,
		> a:focus {
		text-decoration: none;
		background-color: @pager-hover-bg;
		}
	}

	.next {
		> a,
		> span {
		float: right;
		}
	}

	.previous {
		> a,
		> span {
		float: left;
		}
	}

	.disabled {
		> a,
		> a:hover,
		> a:focus,
		> span {
		color: @pager-disabled-color;
		background-color: @pager-bg;
		cursor: @cursor-disabled;
		}
	}
	}
	table.dataTable tbody td {
		word-break: break-word;
		vertical-align: top;
	}
</style>
	<div class="content-wrapper mobcss">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Manage ASM Users</h4>
                <div class="row">
                    <div class="col-md-12">
                    	<div id="insertusers" >
							{{-- " onsubmit="return usersFormValidation()" --}}
							<form name="myForm" method="post" enctype="multipart/form-data" action="{{url('store-users')}}" autocomplete="off">
	                        	<input type="hidden" name="_token" value="{{csrf_token()}}">
	                        	<input type="hidden" name="dataid" id="dataid">
	                        	<input type="hidden" name="userTypeId" id="userTypeId">
								<div class="row">
									<div class="form-group col-md-3">
										<label for="usertype_id">User Type</label>
										<span style="color: red;">*</span>
										<select name="usertype_id" id="usertype_id" tabindex="1" class="form-control" onchange="fn_user_type_change(this,'');" required>
											<option Value="">--select--</option>
											@isset($roleUserTypeData)
												@foreach($roleUserTypeData as $row)
													<option Value="{{$row->id}}">{{$row->usertype}}</option>
												@endforeach
											@endisset
										</select>
										<span id="usertype_id_error" style="color:red"></span>
									</div>
									<div class="form-group col-md-3">
										<label for="Name">First Name</label>
										<span style="color: red;">*</span>
										<input type="text" name="name" id="name" class="form-control" placeholder="Name" required>
										<span id="name_error" style="color:red"></span>
									</div>
									<div class="form-group col-md-3">
										<label for="Name">Lastname</label>
										<input type="text" name="last_name" id="last_name" class="form-control" placeholder="Lastname">
										<span id="last_name_error" style="color:red"></span>
									</div>
									<div class="form-group col-md-3" id="td_employee_id">
										<label for="Name">Login Id</label>
										<span style="color: red;">*</span>
										<input type="text" name="employee_id" id="employee_id" class="form-control" placeholder="Login Id" required>
										<span id="employee_id_error" style="color:red"></span>
									</div>
									<div class="form-group col-md-3">
										<label for="Name">Mobile</label> <span style="color: red;">*</span>
										<input type="text" name="phonenumbers" id="phonenumbers"  class="form-control" placeholder="Mobile" onkeyup="remSpace(this.value)" required >
										<span id="mobile_error" style="color:red"></span> 
										{{-- onblur="fn_mob_change(this)" --}}
									</div>
									<div class="form-group col-md-3">
										<label for="Name">Role</label>
										<span style="color: red;">*</span>
										<select name="role" id="role" class="form-control" required>
												<option value="NA">--Select--</option>
										</select>
										<span id="role_error" style="color:red"></span>
									</div>
									<div class="form-group col-md-3">
										<label for="Name">Email</label> <span style="color: red;">*</span> <i title="Multiple Email separated by comma" class="fa fa-info-circle" style="font-size:15px;color:red"></i>
										<input type="text" name="email" id="email" class="form-control" placeholder="Email" required onblur="checkEmail(this.value)">
										<span id="email_error" style="color:red"></span> 
									</div> 
									{{-- <div class="form-group col-md-3">
										<label for="Name">Password</label> <span style="color: red;">*</span>
										<input type="password" name="password" id="password" class="form-control" placeholder="Password" >
										<span id="password_error" style="color:red"></span> 
									</div> --}}
									<div class="form-group col-md-3" id="td_dealer_id" style="display: none;">
										<label for="Name">Dealer Name</label> <span style="color: red;">*</span>
										<select name="dealer_id[]" multiple id="dealer_id" tabindex="1" class="form-control" >
												@isset($dealerData)
													@foreach($dealerData as $row)
														<option Value="{{$row->id}}">{{$row->dealer_name}}</option>
													@endforeach
												@endisset
										</select>
										<span id="dealer_code_error" style="color:red"></span> 
									</div> 
									<div class="form-group col-md-3" id="td_zone" style="display: none;">
										<label for="Zone">Zone</label>
										<span style="color: red;">*</span>
										<select name="zone[]" id="zone" multiple class="form-control" onchange="Dealer_Zone_changeAsm(this.value,'')">
											@isset($regionData)
												@foreach($regionData as $regionRow)
													<option value="{{$regionRow->id}}">{{$regionRow->region}}</option>
												@endforeach
											@endisset
										</select>
										<span id="Zone_error" style="color:red"></span>
									</div>
									<div class="form-group col-md-3" id="td_State" style="display: none;">
										<label for="State" >Region</label> <span style="color: red;">*</span>
										<select name="state[]" multiple id="state" class="form-control" onchange="Dealer_State_change(zone,this.value,'');"></select>
										<span id="State_error" style="color:red"></span> 
									</div>
									<div class="form-group col-md-3" id="td_City" style="display: none;">
										<label for="City">Area</label> <span style="color: red;">*</span>
										<select name="City[]" multiple id="City" class="form-control" onchange="cityChangeGetDealer(zone,state,this.value,'');">
											<option value="NA">--Select--</option>
										</select>
										<span id="City_error" style="color:red"></span> 
									</div>
									<div class="form-group col-md-3" id="td_dealer_id1" style="display: none;">
										<label for="Name">Dealer Name</label> <span style="color: red;">*</span>
										<select name="dealer_id_arr[]" multiple id="dealer_id1" tabindex="1" class="form-control">
												<option Value="NA">--select--</option>
										</select>
									</div> 
									<div class="form-group col-md-3" id="td_Status" style="display:none;">
										<label for="Name">Status</label> <span style="color: red;">*</span>
										<select name="flag" id="flag" tabindex="1" class="form-control">
											<optgroup>
												<option Value="NA">--select--</option>
												<option Value="1">Active</option>
												<option Value="0">Inactive</option>
												
											</optgroup>
										</select>
										<span id="flag_error" style="color:red"></span>
									</div>
								</div>
								@if(Auth::user()->role == '78')
	                            <div class="box-footer">
	                                <span class="pull-right">
									<button type="button" onclick="reloadPage();" class="btn-secondary">Cancel</button>	
	                                <input type="submit"name="submit" id="submit" value="Submit" class="btn-secondary">
	                                </span>
	                            </div>
								@endif
	                        </form>
						</div>
                        <div class="clear"></div>
                        <hr>
						
						
                        <div class="table-responsive">
                            <table id="order-listing" class="table">
                                <thead>
                                    <tr>
                                    	<th class="d-none">id</th>	
                                    	<th class="d-none">last_name</th>	
                                    	<th class="d-none">User Id</th>	
                                    	<th class="d-none">UserType ID</th>	
                                    	
										<th class="d-none">State</th>
										<th class="d-none">Region</th>
										<th class="d-none">City</th>
										<th class="d-none">Role_id</th>
										<th class="d-none">Dealer Id</th>
										<th>Actions</th>
										<th>Login Id</th>
										<th>Name</th>
										<th class="d-none">User Type</th>
										<th>Role</th>
										<th>Mobile</th>
										<th>Email</th>
										<th>Status</th>
										<th class="d-none">SAC Code</th>
                                    </tr>
                                </thead>
                                <tbody>
									@isset($rowData)
										@php $count=0;//dd($sac_code);
										//dd($rowData);
										 @endphp
										@foreach($rowData as $row)
										<tr>
											<td class="d-none">{{$row->id}}</td>	
											<td class="cls_last_name d-none">{{$row->last_name}}</td>	
											<td class="cls_username d-none">{{$row->name}}</td>	
											<td class="cls_usertype_id d-none">{{$row->user_Type_id}}</td>	
											
											<td class="cls_state d-none">{{$row->state}}</td>
											<td class="cls_zone d-none">{{$row->zone}}</td>
											<td class="cls_city d-none">{{$row->city}}</td>	
											<td  class="cls_roleName d-none">{{$row->role}}</td>
											<td class="cls_dealer_id d-none">{{$row->dealer_id}}</td>
											<td>
												<i class="fa fa-pencil-square-o" aria-hidden="true" id="{{$row->id}}" data-position="left" data-tooltip="Edit" onclick="javascript:return editUser(this);" style="cursor: pointer;"></i>
												{{-- <a href="{{route('users_delete.usersDelete', ['id' => $row->id])}}" onclick="return confirm('Do you want to delete?')">
													<i class="fa fa-trash-o" aria-hidden="true" style="cursor: pointer;"></i></a> --}}
											</td>
											<td class="cls_employee_id">{{$row->employee_id}}</td>
											<td class="cls_name">{{$row->name}}</td>
											<td class="cls_usertype d-none">{{$row->usertype}}</td>
											<td>{{$row->role_name}}</td>
											<td class="cls_mobile">{{$row->mobile}}</td>
											<td class="cls_email">{{$row->email}}</td>
											<td class="cls_flag" >@if($row->flag=='1')
												<label class='badge badge-success'>Active</label>
												@else
												<label class="badge badge-danger">Inactive</label>
												@endif
											</td>
											@php
 												//$sacCode1 = explode("~~",$sac_code);
												$dealerId = $row->dealer_id!=''?rtrim($row->dealer_id,','):'0';
 												$sac_code =DB::select("select sac_code from mstr_dealer  where id in ($dealerId)");
												$sacCode='';
												if(sizeof($sac_code)>0){
													foreach ($sac_code as  $row) {
														$sacCode .= $row->sac_code.',';
													}
													$sacCode = rtrim($sacCode,',');
												}else{
													$sacCode='NA';
												}
												
 											@endphp	       
 											<td class="sacCode1 d-none">
 												
												 {{ $sacCode}}
 											</td> 
										</tr>
										@php $count++; @endphp	
										@endforeach
									@endisset
                                </tbody>
								
                            </table>
							
                        </div>
						
						{{-- Ajax Table --}}

						{{-- <table class="table table-bordered user_datatable">
							<thead>
								<tr>
									<th class="d-none">id</th>	
									<th class="d-none">last_name</th>	
									<th class="d-none">User Id</th>	
									<th class="d-none">UserType ID</th>	
									
									<th class="d-none">State</th>
									<th class="d-none">Region</th>
									<th class="d-none">City</th>
									<th class="d-none">Role_id</th>
									<th class="d-none">Dealer Id</th>
									<th>Actions</th>
									<th>Login Id</th>
									<th>Name</th>
									<th class="d-none">User Type</th>
									<th>Role</th>
									<th>Mobile</th>
									<th>Email</th>
									<th>Status</th>
									<th>SAC Code</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table> --}}
						{{-- Ajax Table --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
 <script>
	/* Reomve space in phone number */
	function remSpace(str){
		str = str.trim();
		var val =  str.replace(/\s/g, '');			
		$("#phonenumbers").val(val);
	};
	/* Reomve space in phone number */
	function checkEmail(param){
 		var emailValue = param;
 		var emailRegex = /^([a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6})(,[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6})*$/;
 		isValid = emailRegex.test(emailValue);
 		if(isValid == false){
 			// alert("Email format not valid");
 			const errorMessage = 'Invalid email format.';
             $('<div class="error-message text-danger" id="errEmail">' + errorMessage + '</div>')
                .insertAfter($('#email'));
 			$("#submit").hide();
 		}else{
 			$('#errEmail').hide();
 			$("#submit").show();
 		}
 	}
	  $(document).ready(function () {
		$('#insertusers').hide();
		/* Ajax Datatable */
		/* var table = $('.user_datatable').DataTable({
			dom: 'Bfrtip',
			processing: true,
			serverSide: false,
			ajax: "{{ route('users') }}",
				columns: [
					{data: 'id', name: 'id',class: 'd-none',visible: false},
					{data: 'last_name', name: 'last_name', class: 'cls_last_name d-none'},
					{data: 'name', name: 'name', class: 'cls_username d-none'},
					{data: 'user_Type_id', name: 'user_Type_id', class: 'cls_usertype_id d-none'},
					{data: 'state', name: 'state', class: 'cls_state d-none'},
					{data: 'zone', name: 'zone', class: 'cls_zone d-none'},
					{data: 'city', name: 'city', class: 'cls_city d-none'},
					{data: 'role', name: 'role', class: 'cls_roleName d-none'},
					{data: 'dealer_id', name: 'dealer_id', class: 'cls_dealer_id d-none'},
					{data: 'action', name: 'action', orderable: false, searchable: false},
					{data: 'employee_id', name: 'employee_id', class: 'cls_employee_id'},
					{data: 'name', name: 'name', class: 'cls_name'},
					{data: 'usertype', name: 'usertype', class: 'cls_usertype d-none'},
					{data: 'role_name', name: 'role_name', class: 'cls_role_name'},
					{data: 'mobile', name: 'mobile', class: 'cls_mobile'},
					{data: 'email', name: 'email', class: 'cls_email'},
					{data: 'flbtn', name: 'flbtn', class: 'cls_flag'},
					{data: 'sacbtn', name: 'sacbtn'}					
				],
				buttons: [
					{ 
						extend: 'excelHtml5', 
						exportOptions: { 
							columns: [ 2,10,11,12,13,14,15,17 ] ,
							modifier: { search: 'none', page: 'all' }						
						}
					}
					
				]
			});
		 *//* Ajax Datatable */




		$("#SearchVicle").keyup(function(){
 			var fieldtype = $('#fieldtype').val();
 			if(fieldtype ==''){
 				alert("Please insert select box");
 				$('#fieldtype').css("border",'1px solid red');
 				//$('#fieldtype').css("color",'red');
 				$('#fieldtype').focus();
 			}else{
 				var inptData = $(this).val();
				 $('#fieldtype').css("border",'1px solid green');
 				$.ajax({
 					url: '{{url("ajax-user-report-data")}}',
 					data: {'keyword':inptData,'fieldtype':fieldtype},
 					success: function(data){
 						
 						$("#tableDisabled").hide();
 						$("#tableEnabled").show();
 						$("#tableEnabled").html(data);
 						$("#SearchVicle").css("background","#FFF");
 					}
 				});
 			}
 			
 		});
		$('#purchase_date').datetimepicker({ format:'Y-m-d',timepicker:false});
		$('#order-listing1').DataTable({
				dom: 'Bfrtip',
				"pageLength":20,
				"paging":   false,
				"searching": false,
				"language": {
					"paginate": {
						"previous": "<",
						"next": ">"
					}
				}/* ,
				buttons: [
					{
						extend: 'excel',
						text: 'Excel',
						className: 'exportExcel',
						filename: '@yield("title")',
						exportOptions: { modifier: { page: 'all'} }
					}
				] */
			});
	});
 function fn_mob_change(el){
	var dataid = $('#dataid').val();
 	if(dataid ==''){
 		var mob = el.value;
	 	$.ajax({
			url:'{{url("check-mobile-duplicate")}}',
			data:{"mob":mob},
			success:function(result) {
				if(result =='not'){
					document.getElementById("mobile_error").innerHTML= '';
				}else{
					document.getElementById("mobile_error").innerHTML= result;
				}
			}
		});
 	}else{
 		var mob = el.value;
	 	$.ajax({
			url:'{{url("check-mobile-duplicate")}}',
			data:{"mob":mob,"userId":dataid},
			success:function(result) {
				if(result =='not'){
					document.getElementById("mobile_error").innerHTML= '';
				}else{
					document.getElementById("mobile_error").innerHTML= result;
				}
			}
		});
 	} 
 }
function editUser(el){
	$('#insertusers').show();
	$('#usertype_id').focus();
	$('#dataid').val(el.id);
	var state =$(el).parents('td').parents('tr').find('.cls_state').text();
	var city =$(el).parents('td').parents('tr').find('.cls_city').text();
	var zone =$(el).parents('td').parents('tr').find('.cls_zone').text();
	var employee_id =$(el).parents('td').parents('tr').find('.cls_employee_id').text();
	
	var name =$(el).parents('td').parents('tr').find('.cls_name').text();
	var last_name =$(el).parents('td').parents('tr').find('.cls_last_name').text();
	var email =$(el).parents('td').parents('tr').find('.cls_email').text();
	var usertype_id =$(el).parents('td').parents('tr').find('.cls_usertype_id').text();
	var roleName =$(el).parents('td').parents('tr').find('.cls_roleName').text();
	var dealer_id =$(el).parents('td').parents('tr').find('.cls_dealer_id').text();
	var mobile =$(el).parents('td').parents('tr').find('.cls_mobile').text();
	var flg=$(el).parents('td').parents('tr').find('.cls_flag').text();
	
	if(flg.trim()=='Active'){
		flg='1';
	}else{
		flg='0';
	}
	$('#flag').val(flg);
	var username ='';
	if (usertype_id !=3) {
		$('#dealer_id').val('');
		
		cityChangeGetDealer(zone,state,city,dealer_id);
	}else{
		$('#zone').val('');
		$('#state').val('');
		$('#City').val('');
		$('#dealer_id1').val('');
		fn_get_dealer_user(dealer_id);
	}
	
	fn_user_type_change(usertype_id,roleName);
	if(usertype_id !=3){
		Dealer_get_zone(zone);
		
		Dealer_Zone_changeAsm(zone,state);
		Dealer_State_change(zone,state,city);
	 }
	 $('#employee_id').val(employee_id);
	 $('#role').val(roleName);	  
	 $('#name').val(name);
	 $('#last_name').val(last_name);
	 $('#email').val(email);
	 $('#password').val('*******');
	 $('#password').attr('disabled','disabled');
	  $('#usertype_id').attr('disabled','disabled');
	 $('#userTypeId').val(usertype_id);
	 $('#usertype_id').val(usertype_id);
	 $('#phonenumbers').val(mobile);
}

 </script>
<script>
	function Dealer_State_change(el,ell,elll){
		var favorite = [];
		var AllZone_ = [];
		var AllState_ = [];
		if(elll!=''){
			AllZone = el;
			AllState=ell;
		}else{
			$('#zone :selected').each(function(i, sel){ 
				AllZone_.push($(this).val());
			});
			var AllZone = AllZone_.join(',');
			$('#state :selected').each(function(i, sel){ 
				AllState_.push($(this).val());
			});
			var AllState = AllState_.join(',');
		}
		$.ajax({ url: '{{url("get-multi-id-city")}}',
			data: { 'r_id':AllZone,'s_id':AllState },
			success: function(data){		
				var Result = data.split(",");var str = '';
				Result.pop();
				for (item in Result){	
					Result2 = Result[item].split("~");
					var mith = elll.split(",");
					if(elll!=''){
						if (jQuery.inArray(Result2[0], mith)!='-1'){
							str += "<option value='" + Result2[0] + "' selected>" + Result2[1] + "</option>";	
						}else{
							str += "<option value='" + Result2[0] + "'>" +Result2[1] + "</option>";		
						}	
					}else{
						str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";			
					}
				}
				document.getElementById('City').innerHTML = str;
			}
		});
	}
	function cityChangeGetDealer(el,ell,elll,ellll){
			
		var favorite = [];
		var AllZone_ = [];
		var AllState_ = [];
		var AllCity_ = [];
		if(ellll!=''){
			AllZone = el;
			AllState=ell;
			AllCity=elll;
		}else{
			$('#zone :selected').each(function(i, sel){ 
				AllZone_.push($(this).val());
			});
			var AllZone = AllZone_.join(',');
			$('#state :selected').each(function(i, sel){ 
				AllState_.push($(this).val());
			});
			var AllState = AllState_.join(',');
			$('#City :selected').each(function(i, sel){ 
				AllCity_.push($(this).val());
			});
			var AllCity = AllCity_.join(',');
		}
		$.ajax({ url: '{{url("city-change-get-dealer")}}',
			data: { 'r_id':AllZone,'s_id':AllState,'c_id':AllCity },
			success: function(data){
				console.log(data);
			var Result = data.split(",");var str = '';
			Result.pop();
			for (item in Result){	
				Result2 = Result[item].split("~");
				var checkVar = ellll.split(",");
				if(ellll!=''){
					if (jQuery.inArray(Result2[0], checkVar)!='-1'){
						str += "<option value='" + Result2[0] + "' selected>" + Result2[1] + "</option>";	
					}else{
						str += "<option value='" + Result2[0] + "'>" +Result2[1] + "</option>";		
					}	
				}else{
					str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
				}
			}
			document.getElementById('dealer_id1').innerHTML = str;
		}});
	}
	function Dealer_get_zone(ell){
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
	}
	function Dealer_Zone_changeAsm(el,ell){
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
							// str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
						}
					}else{
						// str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
					}
				}
				document.getElementById('state').innerHTML =str;
			}
		});
	}
</script> 
@endsection
