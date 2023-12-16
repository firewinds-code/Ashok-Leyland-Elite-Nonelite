@extends("layouts.masterlayout")
@section('title','Customer Master')
@section('bodycontent')
	<div class="content-wrapper mobcss">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Manage Customer Contact @isset($masterCustomerName)<b style="color:#72BC41;"> {{': '.$masterCustomerName}} </b>@endisset</h4>
                <div class="row">
                    <div class="col-md-12">
                    	<div id="insertBrand" >
							<form name="myForm" method="post" enctype="multipart/form-data" action="{{url('store-customer-contact')}}" onsubmit="return customerContactValidate()">
	                        		<input type="hidden" name="_token" value="{{csrf_token()}}">
	                        		<input type="hidden" name="dataid" id="dataid"/>
									<input type="hidden" name="customerId" id="customerId" value="{{$customerId}}"/>
	                            <div class="row">
									<div class="form-group col-md-3">
										<label for="custname">Name</label>
										<span style="color: red;">*</span>
										<input type="text" name="custname" id="custname" class="form-control" placeholder="Name">
										<span id="custname_error" style="color:red"></span>
									</div>
									<div class="form-group col-md-3">
										<label for="custrole">Role</label>
										<span style="color: red;">*</span>
										<input type="text" name="custrole" id="custrole" class="form-control" placeholder="Role">
										<span id="custrole_error" style="color:red"></span>
									</div>
									<div class="form-group col-md-3">
										<label for="locate">Located at</label>
										<span style="color: red;">*</span>
										<input type="text" name="locate" id="locate" class="form-control" placeholder="Located at">
										<span id="locate_error" style="color:red"></span>
									</div>                                
									<div class="form-group col-md-3">
										<label for="support_type">Support Type</label>
										<span style="color: red;">*</span>										
										<select name="support_type" id="support_type" class="form-control">
											<option value="NA">--Select--</option>
											@isset($support_type_details)
												@foreach($support_type_details as $row)
													<option value="{{$row->id}}">{{$row->type}}</option>
												@endforeach
											@endisset
										</select>
										<span id="support_type_error" style="color:red"></span>
									</div>	
									<div class="form-group col-md-3">																				
										<label for="scope_of_services">Scope of Services</label>
										<span style="color: red;">*</span>										
										<select name="scope_of_services[]" multiple id="scope_of_services" class="form-control">
											<option value="NA">--Select--</option>
											@isset($region_details)
												@foreach($scope_of_services_details as $row)
													<option value="{{$row->id}}">{{$row->scope_of_services}}</option>
												@endforeach
											@endisset
										</select>
										<span id="scope_of_services_error" style="color:red"></span>
									</div>
									<div class="form-group col-md-3">
										<label for="Region">Region</label>
										<span style="color: red;">*</span>
										<select name="region" id="region" class="form-control" onchange="fn_get_city_zone_id(this.value,'')">
											<optgroup>
												<option value="NA">--Select--</option>
												@isset($region_details)
													@foreach($region_details as $regionRow)
														<option value="{{$regionRow->id}}">{{$regionRow->region}}</option>
													@endforeach
												@endisset
												</optgroup>
											</select>
										<span id="region_error" style="color:red"></span>
									</div>									
									<div class="form-group col-md-3">
										<label for="city">Location</label>
										<span style="color: red;">*</span>
										<select name="city" id="city" class="form-control">
											<optgroup>
												<option value="NA">--Select--</option>
												<!--@isset($city_details)
													@foreach($city_details as $cityRow)
														<option value="{{$cityRow->id}}">{{$cityRow->city}}</option>
													@endforeach
												@endisset-->
											</optgroup>
										</select>
										<span id="city_error" style="color:red"></span>
									</div>
									<div class="form-group col-md-3">
										<label for="Mobile 1" >Mobile 1</label>
										<span style="color: red;">*</span>
										<input type="text" name="mobile1" id="mobile1" class="form-control" maxlength="10" onblur="fn_mob_change(this.value)" placeholder="Mobile 1">
										<span id="mobile1_error" style="color:red"></span>
									</div>
									<div class="form-group col-md-3">
										<label for="Mobile 2" >Mobile 2</label>										
										<input type="text" name="mobile2" id="mobile2" class="form-control" maxlength="10" onblur="fn_mob_change1(this.value)" placeholder="Mobile 2">
										<span id="mobile2_error" style="color:red"></span>
									</div>
									<div class="form-group col-md-3">
									<label for="email">Email</label>
										<span style="color: red;">*</span>
										<input type="text" name="email" id="email" class="form-control" placeholder="Email">
										<span id="email_error" style="color:red"></span>
									</div>
									<div class="form-group col-md-3">
										<label for="Primary / Secondary">Primary / Secondary</label>
										<span style="color: red;">*</span>										
										<select name="pri_sec" id="pri_sec" class="form-control">
											<option value="NA">--Select--</option>
											<option value="Primary">Primary</option>
											<option value="Secondary">Secondary</option>
											
										</select>
										<span id="pri_sec_error" style="color:red"></span>
									</div>
									<div class="form-group col-md-3">
										<label for="Dealer Codes Associated">Dealer Codes Associated</label>
										<span style="color: red;">*</span>
										
										<select name="dealer_code_asoc[]" multiple id="dealer_code_asoc" class="form-control">
											<optgroup>
												<option value="NA">--Select--</option>
												@isset($dealer_details)
													@foreach($dealer_details as $dealerRow)
														<option value="{{$dealerRow->id}}">{{$dealerRow->dealer_code.'_'.$dealerRow->dealer_name.'_'.$dealerRow->region}}</option>
													@endforeach
												@endisset
											</optgroup>
										</select>
										<span id="dealer_code_asoc_error" style="color:red"></span>
									</div>
									<div class="form-group col-md-3">
										<label for="brand">Brand</label>
										<span style="color: red;">*</span>
										<select name="brand[]" multiple id="brand" class="form-control">
										<optgroup>
											<option value="NA">--Select--</option>
											@isset($brandData)
												@foreach($brandData as $brandRow)
													<option value="{{$brandRow->id}}">{{$brandRow->brand}}</option>
												@endforeach
											@endisset
										</optgroup>
									</select>
									<span id="brand_error" style="color:red"></span>
									</div>
									<div class="form-group col-md-3">
										<label for="Name">Product</label>
										<span style="color: red;">*</span>
										<select name="vehicle" id="vehicle" class="form-control" onchange="fn_product_change(this.value,'','','')">
											<optgroup>
											<option value="NA">--Select--</option>
											@isset($product_details)
												@foreach($product_details as $productRow)
													<option value="{{$productRow->id}}">{{$productRow->vehicle}}</option>
												@endforeach
											@endisset
											</optgroup>
										</select>
										<span id="product_error" style="color:red"></span>
									</div>
									
									<div class="form-group col-md-3">
										<label for="Segment">Segment</label>
										<span style="color: red;">*</span>
										<select name="segment[]" multiple id="segment" class="form-control">
												<optgroup><option value="NA">--Select--</option></optgroup>
										</select>
										<span id="segment_error" style="color:red"></span>
									</div>
									<div class="form-group col-md-3">
										<label for="complaint_cat">Complaint Category</label>
										<span style="color: red;">*</span>
										<select name="complaint_cat[]" multiple id="complaint_cat" class="form-control">
											<optgroup>
												<option value="NA">--Select--</option>
												@isset($complaint_details)												
													@foreach($complaint_details as $complaintRow)
														<option value="{{$complaintRow->id}}">{{$complaintRow->complaint_type}}</option>
													@endforeach
												@endisset
												</optgroup>
											</select>
										<span id="complaint_cat_error" style="color:red"></span>
									</div>
									
									
	                               
	                            </div>
	                            <div class="box-footer">
	                                <span class="pull-right">
									<button type="button" onclick="reloadPage();" class="btn-secondary">Cancel</button>	
	                                <input type="submit"name="submit" id="submit" value="Submit" class="btn-secondary">
	                                </span>
	                            </div>
	                        </form>  
						</div> 
						               
                        <div class="clear"></div>
                        <hr>                       
                        <div class="table-responsive">
                            <table id="order-listing" class="table">
                                <thead>
                                    <tr>
									
										<th style="display: none;">city</th>										
										<th style="display: none;">vehicle</th>										
										<th style="display: none;">brand</th>										
										<th style="display: none;">dealer_code_asoc</th>										
										<th style="display: none;">segment</th>										
										<th style="display: none;">complaint_cat</th>										
										<th style="display: none;">region</th>
										<th style="display: none;">Scope of Services</th>
										<th style="display: none;">Support Type</th>
																	
										<th>Actions</th> 										                                       
										<th >Name</th> 										                                       
										<th >Role</th> 										                                       
										<th>Locate At</th>					                                       
										<th>Mobile 1</th>
										<th>Mobile 2</th> 										                                       
										<th>Email</th>
										<th>Primary / Secondary</th>			                                       
										<th>Region</th>
										<th>Location</th>
                                    </tr>
                                </thead>
                                <tbody>
	                                @isset($rowData)
	                                
	                                	@php $count=1; @endphp							
										@foreach($rowData as $row)
	                                    <tr>											
											<td class="cls_city" style="display: none;">{{$row->city}}</td>	                                        
											<td class="cls_vehicle" style="display: none;">{{$row->vehicle}}</td>	                                        
											<td class="cls_brand" style="display: none;">{{$row->brand}}</td>
											<td class="cls_dealer_code_asoc" style="display: none;">{{$row->dealer_code_asoc}}</td>
											<td class="cls_segment" style="display: none;">{{$row->segment}}</td>	                                        
											<td class="cls_complaint_cat" style="display: none;">{{$row->complaint_cat}}</td>	                                        
											<td class="cls_region" style="display: none;">{{$row->region}}</td>
											<td class="cls_scope_of_services" style="display: none;">{{$row->scope_of_services}}</td>
											<td class="cls_support_type" style="display: none;">{{$row->support_type}}</td>
											<td>
												<i class="fa fa-pencil-square-o" aria-hidden="true" id="{{$row->id}}" data-position="left" data-tooltip="Edit" onclick="javascript:return EditcustomerContact(this);" style="cursor: pointer;"></i>
												<a href="{{route('customer_contact_delete.customerContactDelete', ['id' => $row->id])}}" onclick="return confirm('Do you want to delete?')">
											<i class="fa fa-trash-o" aria-hidden="true" style="cursor: pointer;"></i>
												</a>												
											</td>	                                        
											<td class="cls_custname">{{$row->custname}}</td>
											<td class="cls_custrole">{{$row->custrole}}</td>
											<td class="cls_locate">{{$row->locate}}</td>
											<td class="cls_mobile1">{{$row->mobile1}}</td>
											<td class="cls_mobile2">{{$row->mobile2}}</td>
											<td class="cls_email">{{$row->email}}</td>
											<td class="cls_pri_sec">{{$row->pri_sec}}</td>
											<td>{{$row->regionName}}</td>
											<td>{{$row->cityname}}</td>
	                                    </tr>
	                                     @php $count++; @endphp	
	                                    @endforeach
	                                @endisset                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  <script>
  function customerContactValidate(){
	var custname=$('#custname').val();
	var custrole=$('#custrole').val();
	var locate=$('#locate').val();
	var support_type=$('#support_type').val();
	var scope_of_services=$('#scope_of_services').val();
	var region=$('#region').val();
	var city=$('#city').val();
	var mobile1=$('#mobile1').val();
	
	var mobile2=$('#mobile2').val();
	var email=$('#email').val();
	var pri_sec=$('#pri_sec').val();
	var dealer_code_asoc=$('#dealer_code_asoc').val();
	var brand=$('#brand').val();	
	var vehicle=$('#vehicle').val();	
	var segment	=$('#segment').val();	
	var complaint_cat=$('#complaint_cat').val();	
	var dataid=document.forms["myForm"]["dataid"].value;
	var status=true;
	if (custname=="") {
		document.getElementById("custname_error").innerHTML= "Please enter customer name";
		status=false;
	} else {
		document.getElementById("custname_error").innerHTML= "";
	}
	if (custrole=="") {
		document.getElementById("custrole_error").innerHTML= "Please enter customer role";
		status=false;
	} else {
		document.getElementById("custrole_error").innerHTML= "";
	}
	if (locate=="") {
		document.getElementById("locate_error").innerHTML= "Please enter Located at";
		status=false;
	} else {
		document.getElementById("locate_error").innerHTML= "";
	}
	if (support_type=='NA') {
		document.getElementById("support_type_error").innerHTML= "Please enter support type";
		status=false;
	} else {
		document.getElementById("segment_error").innerHTML= "";
	}
	if (scope_of_services===null) {
		document.getElementById("scope_of_services_error").innerHTML= "Please enter scope of services";
		status=false;
	} else {
		document.getElementById("scope_of_services_error").innerHTML= "";
	}
	if (region=="NA") {
		document.getElementById("region_error").innerHTML= "Please enter region";
		status=false;
	} else {
		document.getElementById("region_error").innerHTML= "";
	}
	if (city=="NA") {
		document.getElementById("city_error").innerHTML= "Please enter city";
		status=false;
	} else {
		document.getElementById("city_error").innerHTML= "";
	}if (mobile1=="") {
		document.getElementById("mobile1_error").innerHTML= "Please enter mobile1";
		status=false;
	} else {
		//fn_mob_change(mobile1);
		var dataid = $('#dataid').val();var sat='';	
 	if(dataid ==''){
 		var mob = mobile1;
	 	$.ajax({
			url:'{{url("check-mobile-duplicate")}}',
			data:{"mob":mob},
			success:function(result) {																		
				if(result =='not'){
					document.getElementById("mobile1_error").innerHTML= '';sat=true;
				}else{
					document.getElementById("mobile1_error").innerHTML= result;sat=false;
					
				}
			}
		});
		
 	}
 	else{
 		var mob = mobile1;
 		
	 	$.ajax({
			url:'{{url("check-mobile-duplicate")}}',
			data:{"mob":mob,"custContactId":dataid},
			success:function(result) {																		
				if(result =='not'){
					document.getElementById("mobile1_error").innerHTML= '';
					sat=true;
				}else{
					document.getElementById("mobile1_error").innerHTML= result;
					sat=false;
				}
			}
		});
		
 	}	
 	
 		status = sat;
		document.getElementById("mobile1_error").innerHTML= "";
	}
	if (mobile2!="") {
		if (mobile1 == mobile2) {
			document.getElementById("mobile2_error").innerHTML= "Mobile 1 and Mobile 2 cannot be duplicates";
			status=false;
		} else {
			//fn_mob_change1(mobile2);
			var dataid = $('#dataid').val();var sat='';	
 	if(dataid ==''){
 		var mob = mobile2;
	 	$.ajax({
			url:'{{url("check-mobile-duplicate")}}',
			data:{"mob":mob},
			success:function(result) {																		
				if(result =='not'){
					document.getElementById("mobile2_error").innerHTML= '';sat=true;
				}else{
					document.getElementById("mobile2_error").innerHTML= result;sat=false;
					status=false;
				}
			}
		});
		
 	}else{
 		var mob = mobile2;
	 	$.ajax({
			url:'{{url("check-mobile-duplicate")}}',
			data:{"mob":mob,"custContactId":dataid},
			success:function(result) {																		
				if(result =='not'){
					document.getElementById("mobile2_error").innerHTML= '';sat=true;
				}else{
					document.getElementById("mobile2_error").innerHTML= result;sat=false;
				}
			}
		});
		
 	}
 	status = sat;
			document.getElementById("mobile2_error").innerHTML= "";
		}
	}
	/*if (mobile2=="") {
		document.getElementById("mobile2_error").innerHTML= "Please enter mobile2";
		status=false;
	} else {
		document.getElementById("mobile2_error").innerHTML= "";
	}*/
	if (email=="") {
		document.getElementById("email_error").innerHTML= "Please enter email";
		status=false;
	} else {
		document.getElementById("email_error").innerHTML= "";
	}
	if (pri_sec=="NA") {
		document.getElementById("pri_sec_error").innerHTML= "Please enter primary/secondary";
		status=false;
	} else {
		document.getElementById("pri_sec_error").innerHTML= "";
	}
	if (dealer_code_asoc===null) {
		document.getElementById("dealer_code_asoc_error").innerHTML= "Please enter dealer code";
		status=false;
	} else {
		document.getElementById("dealer_code_asoc_error").innerHTML= "";
	}
	if (brand===null) {
		document.getElementById("brand_error").innerHTML= "Please enter brand";
		status=false;
	} else {
		document.getElementById("brand_error").innerHTML= "";
	}
	if (segment===null) {
		document.getElementById("segment_error").innerHTML= "Please enter segment";
		status=false;
	} else {
		document.getElementById("segment_error").innerHTML= "";
	}
	if (complaint_cat===null) {
		document.getElementById("complaint_cat_error").innerHTML= "Please enter complaint category";
		status=false;
	} else {
		document.getElementById("complaint_cat_error").innerHTML= "";
	}
	if (vehicle=='NA') {
		document.getElementById("product_error").innerHTML= "Please enter product";
		status=false;
	} else {
		document.getElementById("product_error").innerHTML= "";
	}
	
	return status;
}
  	function fn_mob_change(el){
  	
	var dataid = $('#dataid').val();	
 	if(dataid ==''){
 		var mob = el;
	 	$.ajax({
			url:'{{url("check-mobile-duplicate")}}',
			data:{"mob":mob},
			success:function(result) {																		
				if(result =='not'){
					document.getElementById("mobile1_error").innerHTML= '';
				}else{
					document.getElementById("mobile1_error").innerHTML= result;
				}
			}
		});
 	}else{
 		var mob = el;
	 	$.ajax({
			url:'{{url("check-mobile-duplicate")}}',
			data:{"mob":mob,"custContactId":dataid},
			success:function(result) {																		
				if(result =='not'){
					document.getElementById("mobile1_error").innerHTML= '';
				}else{
					document.getElementById("mobile1_error").innerHTML= result;
				}
			}
		});
 	}
 }
 function fn_mob_change1(el){
 	
	var dataid = $('#dataid').val();	
 	if(dataid ==''){
 		var mob = el;
	 	$.ajax({
			url:'{{url("check-mobile-duplicate")}}',
			data:{"mob":mob},
			success:function(result) {																		
				if(result =='not'){
					document.getElementById("mobile2_error").innerHTML= '';
				}else{
					document.getElementById("mobile2_error").innerHTML= result;
				}
			}
		});
 	}else{
 		var mob = el;
	 	$.ajax({
			url:'{{url("check-mobile-duplicate")}}',
			data:{"mob":mob,"custContactId":dataid},
			success:function(result) {																		
				if(result =='not'){
					document.getElementById("mobile2_error").innerHTML= '';
				}else{
					document.getElementById("mobile2_error").innerHTML= result;
				}
			}
		});
 	}
 }
  </script> 

@endsection
