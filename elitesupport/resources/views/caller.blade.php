@extends("layouts.masterlayout")
@section('title','Caller')
@section('bodycontent')
	<div class="content-wrapper mobcss">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Manage Caller</h4>
                <div class="row">
                    <div class="col-md-12">
                    	<div id="insertvehicle" >
							<form name="myForm" method="post" enctype="multipart/form-data" action="{{url('store-caller')}}" >
	                        	<input type="hidden" name="_token" value="{{csrf_token()}}">
	                        	<input  type="hidden" name="dataid" id="dataid"/>
	                            <div class="row">
									{{-- <div class="form-group col-md-3">
										<label for="vehicle_id">Registration Number</label> <span style="color: red;">*</span>
										<select name="vehicle_id" id="vehicle_id" class="form-control" onchange="vehicleChange(this.value,'')" required>
												<option value="">--Select--</option>
												@isset($vehicleData)
													@foreach ($vehicleData as $item)
														<option value="{{$item->id}}">{{$item->reg_number}}</option>
													@endforeach
												@endisset
										</select>
									</div> --}}
                                    <div class="form-group col-md-3">
										<label for="owner_id">Owner Name</label> <span style="color: red;">*</span>
										<select name="owner_id" id="owner_id" class="form-control" required="">
											<option value="" selected="">--Select--</option>
											@foreach ($ownerData as $row )
												<option value="{{$row->id}}">{{$row->owner_name}}</option>
											@endforeach
										
										</select>
									</div>
									<div class="form-group col-md-3">
										<label for="caller_type">Caller Type</label> <span style="color: red;">*</span>
										<select name="caller_type" id="caller_type" class="form-control" required >
                                            <option value="">--Select--</option>
                                            <option value="Driver">Driver</option>
                                            <option value="Owner">Owner</option>
                                            <option value="Owner cum driver">Owner cum driver</option>
                                            <option value="Support">Support</option>
                                            <option value="AL representative">AL representative</option>
                                            <option value="Passer By">Passer By</option>
                                            <option value="Existing cutomer">Existing cutomer</option>
                                            <option value="Potential customer">Potential customer</option>
                                            <option value="Spare parts retailer">Spare parts retailer</option>
                                            <option value="Support executive">Support executive</option>
                                            <option value="AL executive">AL executive</option>
                                            <option value="AL select">AL select</option>
                                        </select>
									</div>
									<div class="form-group col-md-3">
										<label for="contact_name">Caller Name</label>
										<span style="color: red;">*</span>
										<input type="text" name="caller_name" id="caller_name"  placeholder="Caller Name" class="form-control" required  />
									</div>
									<div class="form-group col-md-3">
										<label for="caller_contact">Caller Contact Number</label>
										<span style="color: red;">*</span>
										<input type="text" name="caller_contact" id="caller_contact"  placeholder="Caller Contact Number" class="form-control" maxlength="10" requeired />
									</div>
									{{-- <div class="form-group col-md-3">
										<label for="caller_location">Caller Location</label>
										<span style="color: red;">*</span>
										<input type="text" name="caller_location" id="caller_location"  placeholder="Caller Location" class="form-control" requeired />
									</div>
									<div class="form-group col-md-3">
										<label for="caller_landmark">Caller Landmark</label>
										<span style="color: red;">*</span>
										<input type="text" name="caller_landmark" id="caller_landmark"  placeholder="Caller Landmark" class="form-control" maxlength="10" requeired />
									</div> --}}
                                    {{-- <div class="form-group col-md-3">
										<label for="vehicle_type">Vehicle Type</label>
										<span style="color: red;">*</span>
										<select name="vehicle_type" id="vehicle_type" class="form-control">
                                            <option value="">--Select--</option>
                                            <option value="Warranty">Warranty</option>
                                            <option value="AMC">AMC</option>
                                            <option value="Extended Warranty">Extended Warranty</option>
                                            <option value="Paid">Paid</option>
                                            <option value="Self">Self</option>
                                        </select>
									</div>
                                    <div class="form-group col-md-3">
                                        <label for="vehicle_movable">Is Vehicle Movable</label>
                                        <select name="vehicle_movable" id="vehicle_movable" class="form-control" required>
                                            <option value="">--Select--</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>  
                                    </div> --}}
                                    {{-- <div class="form-group col-md-3">
                                        <label for="zone" >zone</label>
                                        <span style="color: red;">*</span>
                                        <select name="zone" id="zone" class="form-control" onchange="fn_zone_change(this.value,'')" >
                                            <option value="NA">--Select--</option>
                                            @isset($region)
                                                @foreach ($region as $row)
                                                    <option value="{{$row->id}}">{{$row->region}}</option>	
                                                @endforeach
                                            @endisset
                                        </select> 
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="state" >State</label>
                                        <span style="color: red;">*</span>
                                        <select name="state" id="state" class="form-control" onchange="Dealer_State_change(zone.value,this.value,'')" required>
                                            <option value="NA">--Select--</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="city" >City</label>
                                        <span style="color: red;">*</span>
                                        <select id="city" name="city" class="form-control">
                                            <option value="NA">--Select--</option>
                                        </select>
                                    </div> --}}
	                            </div>
								@if(Auth::user()->role == '29' || Auth::user()->role == '30')
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
										<th>Actions</th>
										{{-- <th>Registration Number</th> --}}
										<th>Owner Name</th>
										<th>Caller Name</th>
										<th>Caller Type</th>
										<th>Caller Contact</th>
										<th style="display: none;">vehicle_id </th>
										<th style="display: none;">owner_id</th>
										{{-- <th style="display: none;">vehicle_type</th>
										<th style="display: none;">vehicle_movable</th> --}}
										{{-- 
										<th style="display: none;">caller_location</th>
										<th style="display: none;">caller_landmark</th> --}}
										
										{{-- <th style="display: none;">zone</th>
										<th style="display: none;">state</th>
										<th style="display: none;">city</th> --}}
										
                                    </tr>
                                </thead>
                                <tbody>
                                @isset($rowData)
								
                                @php $count=1; @endphp
									@foreach($rowData as $row)
                                    <tr>
										<td>
											<i class="fa fa-pencil-square-o" aria-hidden="true" id="{{$row->id}}" data-position="left" data-tooltip="Edit" onclick="javascript:return editCallerContact(this);" style="cursor: pointer;"></i>
											<a href="{{route('caller_delete.callerDelete', ['id' => $row->id])}}" onclick="return confirm('Do you want to delete?')">
												<i class="fa fa-trash-o" aria-hidden="true" style="cursor: pointer;"></i>
											</a>
										</td>
										{{-- <td class="cls_reg_number">{{$row->reg_number}}</td> --}}
										<td class="cls_owner_name">{{$row->owner_name}}</td>
										<td class="cls_caller_name">{{$row->caller_name}}</td>
										<td class="cls_caller_type">{{$row->caller_type}}</td>
										<td class="cls_caller_contact">{{$row->caller_contact}}</td>
                                        <td class="cls_vehicle_id" style="display: none;">{{$row->vehicle_id}}</td>
                                        <td class="cls_owner_id" style="display: none;">{{$row->owner_id}}</td>
										{{-- <td class="cls_vehicle_type" style="display: none;">{{$row->vehicle_type}}</td>
                                        <td class="cls_vehicle_movable" style="display: none;">{{$row->vehicle_movable}}</td> --}}
                                       {{--  <td class="cls_caller_location" style="display: none;">{{$row->caller_location}}</td>
                                        <td class="cls_caller_landmark" style="display: none;">{{$row->caller_landmark}}</td> --}}
                                        
                                       {{--  <td class="cls_zone" style="display: none;">{{$row->zone}}</td>
                                        <td class="cls_state" style="display: none;">{{$row->state}}</td>
                                        <td class="cls_city" style="display: none;">{{$row->city}}</td> --}}
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
	 function fn_zone_change(zoneId,ell){
		$.ajax({ url: '{{url("get-zone-change")}}',
				data: { 'zoneId':zoneId},
				success: function(response){
                    
					var Result = response.split(",");var str = '';
					Result.pop();
					str += "<option value='NA'>--Select--</option>";
					for (item1 in Result) {
					var Result2 = Result[item1].split("~~");
					if (ell!='') {
						if ( Result2[0] == ell ) {
								str += "<option value='" + Result2[0] + "' selected>" + Result2[1] + "</option>";
							} 
							else
							{
								str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
							}
					}else{
						str += "<option value='" + Result2[0]+ "'>" + Result2[1] + "</option>";
					}
				}
				document.getElementById('state').innerHTML = str;
				}
		});
	}
	function Dealer_State_change(el,ell,elll)
	{
		
		$.ajax({ url: '{{url("get-state-id-city")}}',
		data: { 'r_id':el,'s_id':ell },
		success: function(data){		
		var Result = data.split(",");var str = '';
		Result.pop();
		for (item in Result){
				Result2 = Result[item].split("~");
				var value = elll.split(",");
				if(elll!=''){
					if (jQuery.inArray(Result2[0], value)!='-1'){
						str += "<option value='" + Result2[0] + "' selected>" + Result2[1] + "</option>";	
					}else{
						str += "<option value='" + Result2[0] + "'>" +Result2[1] + "</option>";		
					}	
				}else{
					str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";			
				}
			}
			document.getElementById('city').innerHTML = str;
		}});
	}
	function editCallerContact(el,ell){
		$('#dataid').val(el.id);
		var vehicle_type=$(el).parents('td').parents('tr').find('.cls_vehicle_type').text();
		//alert(vehicle_type);
		$('#vehicle_type').val(vehicle_type);
		var vehicle_movable =$(el).parents('td').parents('tr').find('.cls_vehicle_movable').text();
		$('#vehicle_movable').val(vehicle_movable);
		var reg_number=$(el).parents('td').parents('tr').find('.cls_reg_number').text();
		var owner_name=$(el).parents('td').parents('tr').find('.cls_owner_name').text();
		
		var caller_type=$(el).parents('td').parents('tr').find('.cls_caller_type').text();
		var caller_name=$(el).parents('td').parents('tr').find('.cls_caller_name').text();
		var vehicle_id=$(el).parents('td').parents('tr').find('.cls_vehicle_id').text();
		var caller_contact=$(el).parents('td').parents('tr').find('.cls_caller_contact').text();
	
		/* var caller_location=$(el).parents('td').parents('tr').find('.cls_caller_location').text();
		var caller_landmark=$(el).parents('td').parents('tr').find('.cls_caller_landmark').text(); */
		
		/* var zone=$(el).parents('td').parents('tr').find('.cls_zone').text();
		var state=$(el).parents('td').parents('tr').find('.cls_state').text();
		var city=$(el).parents('td').parents('tr').find('.cls_city').text(); */
		var owner_id=$(el).parents('td').parents('tr').find('.cls_owner_id').text();
		
		
		//$('#vehicle_id').val(vehicle_id);
		//vehicleChange(vehicle_id,owner_id);
		$('#owner_id').val(owner_id);
		$('#caller_type').val(caller_type);
		$('#caller_name').val(caller_name);
		$('#caller_contact').val(caller_contact);
		$('#caller_location').val(caller_location);
		$('#caller_landmark').val(caller_landmark);
		
		//alert(vehicle_movable);
		
		//$('#zone').val(zone);
		/* fn_state_change(zone,state);
        fn_district_change(zone,state,city); */
		/* fn_zone_change(zone,state);
		Dealer_State_change(zone,state,city); */
		
	}
    function vehicleChange(el,ell){
        var id = el;
		$.ajax({ url: '{{url("get-owner-name")}}',data: {'id':id},success: function(data) {
				var Result = data.split(",");var str = '';
				Result.pop();
				str += "<option value='' selected>--Select--</option>";
				for (item in Result){
				Result2 = Result[item].split("~~");
					if (ell!=''){
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
				document.getElementById('owner_id').innerHTML =str;
			}
		});
    }
    function fn_state_change(stateId,ell){
		$.ajax({ url: '{{url("get-stateChange")}}',
				data: { 'stateId':stateId},
				success: function(response){
					var Result = response.split(",");var str = '';
					Result.pop();
					str += "<option value='NA'>--Select--</option>";
					for (item1 in Result) {
					var Result2 = Result[item1].split("~~");
					if (ell!='') {
						if ( Result2[0] == ell ) {
								str += "<option value='" + Result2[0] + "' selected>" + Result2[1] + "</option>";
							} 
							else
							{
								str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
							}
					}else{
						str += "<option value='" + Result2[0]+ "'>" + Result2[1] + "</option>";
					}
				}
				document.getElementById('district').innerHTML = str;
				}
		});
	}
	function fn_district_change(el,ell,elll){
		$.ajax({ url: '{{url("get-city")}}',
			data: { 's_id':el,'d_id':ell },
			success: function(data){
				
				var Result = data.split(",");var str = '';
				Result.pop();
				
				str += "<option value='NA' selected>--Select--</option>";
				for (item in Result){
					Result2 = Result[item].split("~");
					
					if(elll!=''){
						if (Result2[0] == elll) //if(ell==Result[item])
						{
						str += "<option value='" + Result2[0] + "' selected>" + Result2[1] + "</option>";	
						}
						else
						{
						str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";		
						}	
					}
					else{
						str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";			
					}
				}
				document.getElementById('city').innerHTML = str;
			}
		});
	}
 </script>  

@endsection
