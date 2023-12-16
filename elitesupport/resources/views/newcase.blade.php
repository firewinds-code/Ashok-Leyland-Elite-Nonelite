@extends("layouts.masterlayout")
@section('title','Create Case')
@section('bodycontent')
@php  $case_status=null; @endphp
<div class="content-wrapper mobcss">
	<h4 class="card-title">Case Registration</h4>
    <div class="card">
        <div class="card-body">       
        	<form name="myForm" method="post" enctype="multipart/form-data" action="{{url('store-new-case')}}" onsubmit="return newCaseValidate()">
        		<input type="hidden" name="_token" value="{{csrf_token()}}">
				<input type="hidden" name="customer_contact_id" id="customer_contact_id">
	            <input type="hidden" name="DataID" id="DataID">
	            <h6 class="card-title"><u style="font-size: 14px;"><b>Customer Details</b></u></h6>
	            <div class="clear"></div><hr> 
	            <div class="row">
	                <div class="col-md-12">	                                	
	                    <div class="row">	                    	
	                    	<div class="form-group col-md-3">
								<label for="phonenumbers" >Phone numbers</label>
								<span style="color: red;">*</span>
                                <input type="text" name="phonenumbers" id="phonenumbers" maxlength="10" class="form-control" />
                                <span id="phonenumbers_error" style="color:red"></span> 
                            </div>
                            <div class="form-group col-md-3" style="position: relative;top: 14px;">                                
                                <a class="btn-primary" id="getdata" name="getdata" onclick="showModal(phonenumbers)" data-toggle="modal" data-target="#myModal" style="color: #fff;padding: 5px 5px;position: relative;top: 14px;">Get Data</a><span id="not_found" style="color:red;position: relative;top: 14px;left: 8px;"></span>
                            </div>
                             <div class="form-group col-md-3"></div>   
                             <div class="form-group col-md-3"></div>   
                           <hr>
							
                            <div class="form-group col-md-3">
                                <label for="contactperson" >Contact Person</label>                            	
                            	<input type="text" name="contactperson" id="contactperson" class="form-control" />                                	
                                <span id="contactperson_error" style="color:red"></span> 
                            </div>
            				<div class="form-group col-md-3">
								<label for="email" >E-mail</label>
								<span style="color: red;">*</span>
                                <input type="text" name="email" id="email" class="form-control" />
                                <span id="email_error" style="color:red"></span> 
                            </div>
            				<div class="form-group col-md-3">
                                <label for="customerorg" >Customer Org</label>
                                <input type="text" name="customerorg" id="customerorg" class="form-control" />
                                <span id="customerorg_error" style="color:red"></span> 
                            </div>
                            <div class="form-group col-md-3">
                                <label for="mobile2" >Secondary Mobile</label>
                                <input type="text" name="mobile2" id="mobile2" class="form-control" />
                                <span id="customerorg_error" style="color:red"></span> 
                            </div>
            				
						</div>               		
	            	</div>
	            </div>
<!-- -----------------------------------------Model-------------------------------------------------------------------- -->

			
				<div class="modal fade" id="myModal" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">        
								<h4 class="modal-title" id="exampleModalLabel">Customer Lists</h4>
								<a href="" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></a>
							</div>
							<div class="modal-body" id="popUpModal">
								
							</div>
							<div class="modal-footer">
								{{--<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>--}}
								
							</div>
						</div>
				  	</div>
			  	</div>
			
			     
<!-- -----------------------------------------Model-------------------------------------------------------------------- -->
				<div class="clear"></div> <hr>         
	            <h6 class="card-title" style="margin-top: 10px;"><u style="font-size: 14px;"><b>Complaint Details</b></u></h6>
	            <div class="clear"></div><hr>
	            <div class="row">
	                <div class="col-md-12">                	
		                <div class="row">
							<div class="form-group col-md-3">
								<label for="case_type" >Case Type</label>
								<span style="color: red;">*</span>
                            	<select name="case_type" id="case_type" class="form-control">
                                	<optgroup>
                                		<option value="NA">--Select--</option>
                                		<option value="Complaint">Complaint</option>
                                		<option value="Feedback">Feedback</option>
                                	</optgroup>
                                </select>
                                <span id="case_type_error" style="color:red"></span> 
                            </div>
                            <div class="form-group col-md-3">
								<label for="brands" >Brands</label>
								<span style="color: red;">*</span>
                                <select name="brands" id="brands" class="form-control">
                                	<option value="NA">--Select--</option>
                                	<!--{{-- @isset($brandData)
                                		@foreach($brandData as $row)
                                			<option value="{{$row->id}}">{{$row->brand}}</option>
                                		@endforeach
                                	@endisset--}}-->
                                </select>
                                <span id="brands_error" style="color:red"></span> 
                            </div> 
							<div class="form-group col-md-3">
                                <label for="complaintcategory">Complaint Category</label>
                                <select name="complaintcategory" id="complaintcategory" class="form-control" onchange="getSubComplaint(this.value,'')">
                                	<optgroup>
                                		<option value="NA">--Select--</option>
	                                	@isset($complaintTypeData)
	                                		@foreach($complaintTypeData as $row)
	                                			<option value="{{$row->id}}">{{$row->complaint_type}}</option>
	                                		@endforeach
	                                	@endisset
                                	</optgroup>
                                </select>
                                <span id="complaintcategory_error" style="color:red"></span> 
                            </div>
                            <div class="form-group col-md-3">
                                <label for="sub_complaint_type">Sub Complaint Type</label>
                                <select name="sub_complaint_type" id="sub_complaint_type" class="form-control">
                                	<optgroup><option value="NA">--Select--</option></optgroup>
                                </select>
                                <span id="complaintcategory_error" style="color:red"></span> 
                            </div>
							<div class="form-group col-md-3">
								<label for="center_module">Mode Of Complaint</label>
								<span style="color: red;">*</span>
                                <select name="center_module" id="center_module" class="form-control" onchange="modechange(this);">
                                <option value="NA">--Select--</option>
                                	@isset($centerData)
                                		@foreach($centerData as $row)
                                			<option value="{{$row->id}}">{{$row->mode_name}}</option>
                                		@endforeach
                                	@endisset
                                </select>
                                <span id="center_module_error" style="color:red"></span> 
                            </div>
							<div class="form-group col-md-3">
								<label for="product" >Product</label>
								<span style="color: red;">*</span>
                				<!--{{-- <select name="product" id="product" class="form-control" onchange="get_dealer(Zone,State,City,this)">
                				<select name="product" id="product" class="form-control" onchange="User_product_change(this.value,'')">--}}-->
                				<select name="product" id="product" class="form-control">
                                	<option value="NA">--Select--</option>
                                	<!--{{--@isset($vehicleData)
                                		@foreach($vehicleData as $row)                                			
											<option value="{{$row->id}}">{{$row->vehicle}}</option>											
                                		@endforeach
                                	@endisset--}}-->
                                </select>
                                <span id="product_error" style="color:red"></span> 
                            </div>
							<div class="form-group col-md-3" id="td_segment">
	                            <label for="Name">Segment</label>	                                    
	                            <select name="segment" id="segment" class="form-control">
									<optgroup><option value="NA">--Select--</option></optgroup>
								</select>
	                            <span id="segment_error" style="color:red"></span> 
	                        </div>
							<div class="form-group col-md-3">
								<label for="Zone" >Region</label>
								<span style="color: red;">*</span>
								<!--{{--<select name="Zone" id="Zone" class="form-control" onchange="getDealerByZoneId(this.value,'')" >--}}-->
								<select name="Zone" id="Zone" class="form-control">
									<option value="NA">--Select--</option>
									<!--@isset($region)
										@foreach($region as $row)
											<option value="{{$row->id}}">{{$row->region}}</option>
										@endforeach
									@endisset-->
								</select>
								<span id="Zone_error" style="color:red"></span>
							</div> 
							<div class="form-group col-md-3">
								<label for="Dealer" >Dealer</label>
								<span style="color: red;">*</span>
							   <select   class="form-control" name="Dealer" id="Dealer" onchange="getCityByDealerId(this.value,'')">
							    	<optgroup><option value="NA">--Select--</option></optgroup>
							    </select>
							    <!--{{--<input type="text" name="Dealer" id="Dealer"  placeholder="Search" onkeyup="dealerSearch(this.value)" class="form-control">		   
                                <select id="result" name="dealerId" style="display: none;" class="form-control"></select>--}} -->
								<span id="Dealer_error" style="color:red"></span> 
                            </div>
                            <div class="form-group col-md-3">
								<label for="City" >Location</label>
								<span style="color: red;">*</span>
								<select   class="form-control" name="City" id="City" onchange="getAssignUser(this.value)">
							    	<optgroup><option value="NA">--Select--</option></optgroup>
							    </select>							    
								<span id="City_error" style="color:red"></span> 
                            </div>
							<div class="form-group col-md-3" id="assigned_Div" style="display: none;">
								<label for="City" >Assignee</label>								
								<input type="text" name="assigned_user" id="assigned_user" disabled="" class="form-control">
								<span id="assigned_user_error" style="color:red"></span> 
                            </div> 
							<div class="form-group col-md-3">
                                <label for="vehicle_registration" >Vehicle Registration</label>
                    			<input type="text" name="vehicle_registration" id="vehicle_registration" class="form-control" />
                                <span id="vehicle_registration_error" style="color:red"></span> 
                            </div>
							<div class="form-group col-md-3">
                                <label for="vehicle_model" >Vehicle Model</label>
                                <span style="color: red;">*</span>
                                @isset($productData)
                                	<select name="vehicle_model" id="vehicle_model" class="form-control">
                                		<option value="NA">--Select--</option>
                                		@foreach($productData as $row)
											<option value="{{$row->id}}">{{$row->model}}</option>
										@endforeach
                                	</select>
                                @endisset                                
                                <span id="vehicle_model_error" style="color:red"></span> 
                            </div>
							<div class="form-group col-md-3">
                                <label for="chassis_number" >Chassis Number</label>
                                <input type="text" name="chassis_number" id="chassis_number" maxlength="10" class="form-control"  autocomplete="off"/>
                                <span id="chassis_number_error" style="color:red"></span> 
                            </div>
                            <div class="form-group col-md-3" id="attachment_div" style="display: none;">
                                <label for="attachment" >Attachment</label>
                                <input type="file" name="attachment" id="attachment" class="form-control" autocomplete="off"/>
                                <span id="attachment_error" style="color:red"></span> 
                            </div>
							<!--{{--<div class="form-group col-md-3">
                                <label for="location" >Location</label>
                                <select name="location" id="location" class="form-control">
                                	<optgroup>
                                		<option value="NA">--Select--</option>
                                	</optgroup>
                                </select>
                                <span id="location_error" style="color:red"></span> 
                            </div>
							<div class="form-group col-md-3">
                                <label for="dop" >Date of purchase</label>
                                <input type="text" name="dop" id="dop" class="form-control" readonly />
                                <span id="dop_error" style="color:red"></span> 
                            </div>
							<div class="form-group col-md-3">
                                <label for="phonenumbers" >Date of service</label>
                                <input type="text" name="dos" id="dos" class="form-control" readonly />
                                <span id="dos_error" style="color:red"></span> 
                            </div>--}}-->
							<div class="form-group col-md-12">
                                <label for="description" >Description </label><span style="color: red;"> *</span> 
                                <textarea name="description" id="description" class="form-control" style="height: 80px;"></textarea>
                                <span id="description_error" style="color:red"></span> 
                            </div>
	                    </div>
	                    <div class="clear"></div><hr> 
	                    <div class="box-footer">
	                        <span class="pull-right" style="text-align: center;">	
	                        	<input type="submit"name="submit" id="submit" value="Submit" class="btn-secondary">
	                        </span>
	                    </div>
	                </div>
            	</div>
            </form>
    	</div>
	</div>
</div>
<script>
function modechange(el){
	var id = el.value;
	if(id == '2' || id == '4'){
		$('#attachment_div').show();
	}else{
		$('#attachment_div').hide();
	}
}
 $(function () {
        $("#chassis_number").keypress(function (e) {
            var keyCode = e.keyCode || e.which;
 
            $("#chassis_number_error").html("");
 
            //Regex for Valid Characters i.e. Alphabets and Numbers.
            var regex = /^[ A-Za-z0-9_@./!@#$%:{}[]*$/;
 
            //Validate TextBox value against the Regex.
            var isValid = regex.test(String.fromCharCode(keyCode));
            if (!isValid) {
                $("#chassis_number_error").html("Only Alpha numeric and special character.");
            }
 		
            return isValid;
        });
    });
	function getDealerByZoneId(zoneId){
		$.ajax({ url: '{{url("get-dealer-by-zone-id")}}',data :{'zone_id':zoneId},
			success: function(data) {
				var Result = data.split(",");
				var str='';
				Result.pop();
				for (item1 in Result) {
					var Result2 = Result[item1].split("~");
					str += "<option value='" + Result2[0]+ "'>" + Result2[1] + "</option>";
				}
				document.getElementById('Dealer').innerHTML = "<optgroup><option value='NA'>--Select--</option>" + str + "</optgroup>";
			}
		});	
	}
	function getCityByDealerId(dealerId){
		$.ajax({ url: '{{url("get-city-by-dealer-id")}}',data :{'dealer_id':dealerId},
			success: function(data) {
				
				var Result = data.split(",");
				var str='';
				Result.pop();
				for (item1 in Result) {
					var Result2 = Result[item1].split("~");
					str += "<option value='" + Result2[0]+ "'>" + Result2[1] + "</option>";
				}
				document.getElementById('City').innerHTML = "<optgroup><option value='NA'>--Select--</option>" + str + "</optgroup>";
			}
		});	
	}
	function dealerSearch(str) {
		
		
		//$("#result").html("<img alt="ajax search" src='{{asset('images/loading.gif')}}'/>");
		var title=str;
	 	// $('#ajax').html(title);
		if(title!=""){
			//$("#result").html("<img alt="ajax search" src='{{asset('images/loading.gif')}}'/>");
			$.ajax({
				type:"get",
				url:'{{url("search-dealer")}}',
				data:"title="+title,
				success:function(data){
					$("#result").show();	
					$("#result").html(data);
					$("#Dealer").val("");
				}
			});
		}
	}

function showModal(el){	
 	var phonenumbers = el.value;
 	$('#not_found').hide();
		$.ajax({ url: '{{url("get-customer-details")}}',data :{'ph':phonenumbers},
			success: function(data) {		 	
				$('#popUpModal').html(data);			 	
				if(data =="No Customer Found"){			 	
					$('#not_found').show();
					$('#not_found').html(data);
					
				}			
			}
		});
		
	}
function CustomerData(id){
	$('#customer_contact_id').val(id);
	$.ajax({ url: '{{url("get-customer-details-id")}}',data :{'id':id},
		success: function(data) {
			var result=data.split('~');
			//alert(result)		
			//alert(result[0])		;
			var result1=result[0].split(',');
			
			var custname = result1[0];
			var email = result1[1];
			var customerOrg = result1[2];
			/*alert(result[1]);
			alert(result[2]);
			alert(result[3]);*/
			var segmentId = result[1];
			var complaint_cat = result[2];
			var regionId = result[3];
			var vehicle = result[4];
			var brand = result[5];
			var dealer_code_asoc = result[6];			
			var mobile2 = result[7];
			var mob2 = mobile2!=''?mobile2:'NA';
			$('#contactperson').val(custname);
			$('#email').val(email);			
			$('#customerorg').val(customerOrg);				
			$('#mobile2').val(mob2);				
			$.ajax({ url: '{{url("get-segment-id")}}',data :{'segmentId':segmentId},
				success: function(data) {
					var Result = data.split(",");
					var str='';
					Result.pop();
					for (item1 in Result) {
						var Result2 = Result[item1].split("~");
						str += "<option value='" + Result2[0]+ "'>" + Result2[1] + "</option>";
					}
					document.getElementById('segment').innerHTML = "<optgroup><option value='NA'>--Select--</option>" + str + "</optgroup>";
				}
			});
			$.ajax({ url: '{{url("get-region-id")}}',data :{'regionId':regionId},
				success: function(data) {
					var Result = data.split(",");
					var str='';
					Result.pop();
					for (item1 in Result) {
						var Result2 = Result[item1].split("~");
						str += "<option value='" + Result2[0]+ "'>" + Result2[1] + "</option>";
					}
					document.getElementById('Zone').innerHTML = "<optgroup><option value='NA'>--Select--</option>" + str + "</optgroup>";
				}
			});
			
			$.ajax({ url: '{{url("get-complaint-cat-id")}}',data :{'complaint_cat':complaint_cat},
				success: function(data) {				
					var Result = data.split(",");					
					var str='';
					Result.pop();
					for (item1 in Result) {
						var Result2 = Result[item1].split("~");
						str += "<option value='" + Result2[0]+ "'>" + Result2[1] + "</option>";
					}
					document.getElementById('complaintcategory').innerHTML = "<optgroup><option value='NA'>--Select--</option>" + str + "</optgroup>";
				}
			});
			$.ajax({ url: '{{url("get-product-id")}}',data :{'vehicle':vehicle},
				success: function(data) {
					var Result = data.split(",");					
					var str='';
					Result.pop();
					for (item1 in Result) {
						var Result2 = Result[item1].split("~");
						str += "<option value='" + Result2[0]+ "'>" + Result2[1] + "</option>";
					}
					document.getElementById('product').innerHTML = "<optgroup><option value='NA'>--Select--</option>" + str + "</optgroup>";
				}
			});			
			$.ajax({ url: '{{url("get-brand-id")}}',data :{'brand':brand},
				success: function(data) {
					var Result = data.split(",");					
					var str='';
					Result.pop();
					for (item1 in Result) {
						var Result2 = Result[item1].split("~");
						str += "<option value='" + Result2[0]+ "'>" + Result2[1] + "</option>";
					}
					document.getElementById('brands').innerHTML = "<optgroup><option value='NA'>--Select--</option>" + str + "</optgroup>";
				}
			});
			$.ajax({ url: '{{url("get-dealercodeasoc-id")}}',data :{'dealer_code_asoc':dealer_code_asoc},
				success: function(data) {
					var Result = data.split(",");					
					var str='';
					Result.pop();
					for (item1 in Result) {
						var Result2 = Result[item1].split("~");
						str += "<option value='" + Result2[0]+ "'>" + Result2[1] + "</option>";
					}
					document.getElementById('Dealer').innerHTML = "<optgroup><option value='NA'>--Select--</option>" + str + "</optgroup>";
				}
			});
			
		}
	});
	
}
	
function zone_change(el,ell){
	
	var zoneId = el;
	$.ajax({ url: '{{url("get-city-zone-id")}}',data :{'zone_id':zoneId},
			   	success: function(data) {			   		
				   var Result = data.split(",");
				   var str='';
				   Result.pop();
				   for (item1 in Result){
				   			var Result2 = Result[item1].split("~");
				   			/*if (jQuery.inArray(Result2[0], zoneId)!='-1')
							{
							str += "<option value='" + Result[item] + "' selected>" + Result[item] + "</option>";
							} 
							else
							{
							str += "<option value='" + Result[item] + "'>" + Result[item] + "</option>";
							}
				   			*/
				   			//str += "<option value='" + Result2[0]+ "'>" + Result2[1] + "</option>";
				   			
				   		 if (ell!=''){
				   		 	
				  			if (Result2[0]==ell)
							{
							str += "<option value='" + Result2[0] + "' selected>" + Result2[1] + "</option>";
							} 
							else
							{
							str += "<option value='" +Result2[0] + "'>" + Result2[1] + "</option>";
							}
					}
					 else
					  {
						 str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
					 }	
				   			
							
					}					   
				   document.getElementById('City').innerHTML = "<optgroup><option value='NA'>--Select--</option>" + str + "</optgroup>";
			   	}
		   	});	
	
}
function getAssignUser(param){

	var complaintcategory = $('#complaintcategory').val();
	var sub_complaint_type = $('#sub_complaint_type').val();
	var product = $('#product').val();
	var segment = $('#segment').val();
	var Zone = $('#Zone').val();
	var City = $('#City').val();
	var Dealer = $('#Dealer').val();
	$.ajax({ url: '{{url("get-assign-user")}}',data :{'complaintcategory':complaintcategory,'sub_complaint_type':sub_complaint_type,'product':product,'segment':segment,'Zone':Zone,'City':City,'DealerIds':Dealer},
		success: function(data) {			
			$('#assigned_Div').show();
			$('#assigned_user').val(data);
		}
	});
	
	
}
</script>
@endsection