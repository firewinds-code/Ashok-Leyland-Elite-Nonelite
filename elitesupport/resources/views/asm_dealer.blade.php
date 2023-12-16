@extends("layouts.masterlayout")
@section('title','ASM Dealer')
@section('bodycontent')
	<div class="content-wrapper mobcss">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Manage ASM Dealer</h4>
                <div class="row">  
                    <div class="col-md-12">
                    	<div id="insertDealer" >
						<form name="myForm" method="post" enctype="multipart/form-data" action="{{url('store-dealer')}}" onsubmit="return formSubmit()">
                        	<input type="hidden" name="_token" value="{{csrf_token()}}">
                        	<input type="hidden" name="DataID" id="DataID">
                            <div class="row">
								<div class="form-group col-md-3">
									<label for="sac_code">SAC Code</label> <span style="color: red;">*</span>
									<input type="text" name="sac_code" id="sac_code" class="form-control" placeholder="SAC Code" readonly />
								</div>
								<div class="form-group col-md-3">
									<label for="DealerName">Dealer Name</label> <span style="color: red;">*</span>
									<input type="text" name="DealerName" id="DealerName" class="form-control" placeholder="Dealer Name" readonly />
									<span id="DealerName_error" style="color:red"></span>
								</div>
								<div class="form-group col-md-3">
									<label for="phone">Phone</label> <span style="color: red;">*</span>
									<input type="text" maxlength=10" name="phone" id="phone" class="form-control" placeholder="Dealer Name" disabled />
									<span id="phone_error" style="color:red"></span>
								</div>
								<div class="form-group col-md-3">
									<label for="mail">Mail</label> <span style="color: red;">*</span>
									<input type="email" name="mail" id="mail" class="form-control" placeholder="mail" disabled />
								</div>
								<div class="form-group col-md-3">
									<label for="DealerCode">Address</label> <span style="color: red;">*</span>
									<textarea name="address" rows="2" cols="39" id="address" class="form-control" placeholder="Address" disabled></textarea>
									<span id="address_error" style="color:red"></span>
								</div>
								
								<div class="form-group col-md-3">
									<label for="working_mon_fri">Working Mon-Sat</label> <span style="color: red;">*</span>
									<select name="working_mon_fri" id="working_mon_fri" class="form-control">
										<option value="">--Select--</option>
										@isset($time_slots)
											@foreach ($time_slots as $item)
												<option value="{{$item}}">{{$item}}</option>
											@endforeach
										@endisset
									</select>     
								</div>
								
								<div class="form-group col-md-3">
									<label for="sunday_working">Sunday Working</label> <span style="color: red;">*</span>
									<select name="sunday_working" id="sunday_working" class="form-control">
										<option value="">--Select--</option>
										<option value="1">Yes</option>
										<option value="0">No</option>
										
									</select>
								</div>
								<input type="hidden" name="working_hours" id="working_hours" />
								<div class="form-group col-md-3">
									<label for="Region">Zone</label> <span style="color: red;">*</span> 
									{{-- <select name="zone" id="zone" class="form-control" onchange="On_Dealer_Zone(this.value,'')" required> --}}
									<select name="zone" id="zone" class="form-control" onchange="fn_zone_change(this.value,'')" disabled>
										<option value="NA">--Select--</option>
										@isset($regionData)
											@foreach($regionData as $regionRow)
												<option value="{{$regionRow->id}}">{{$regionRow->region}}</option>
											@endforeach
										@endisset
									</select>
									<span id="Zone_error" style="color:red"></span>
								</div>
                             	<div class="form-group col-md-3">
                                    <label for="State" >Region</label> <span style="color: red;">*</span>
									<select name="state" id="state" class="form-control" onchange="Dealer_State_change(zone.value,this.value,'')" disabled></select>
                                </div>
                                 <div class="form-group col-md-3">
                                   <label for="City">Area</label> <span style="color: red;">*</span>
									<select name="city" id="city" class="form-control" disabled><option value="NA" disabled>--Select--</option></select>
                                </div>
								<div class="form-group col-md-3">
									<label for="DealerName">Pin Code</label>
									<span style="color: red;">*</span>
									<input type="text" maxlength="50" name="pincode" id="pincode" class="form-control" placeholder="Pin Code" disabled>
									<span id="pincode_error" style="color:red"></span>
								</div>
								<div class="form-group col-md-3">
									<label for="latitude">Latitude</label>
									<span style="color: red;">*</span>
									<input type="text" name="latitude" id="latitude" class="form-control" placeholder="Latitude" disabled>
									<span id="latitude_error" style="color:red"></span>
								</div>
								<div class="form-group col-md-3">
									<label for="latitude">Longitude</label>
									<span style="color: red;">*</span>
									<input type="text" name="longitude" id="longitude" class="form-control" placeholder="Longitude" disabled>
									<span id="longitude_error" style="color:red"></span>
								</div>
								<div class="form-group col-md-3">
									<label for="dealer_type">Type</label>
									<div class="radio">
										<label><input type="radio" name="dealer_type" id="dealer_type" value="ALASC">ALASC</label>
										<label><input type="radio" name="dealer_type" id="dealer_type1" value="DASC">DASC</label>
										<label><input type="radio" name="dealer_type" id="dealer_type2" value="SASSY">SASSY</label>
										<label><input type="radio" name="dealer_type" id="dealer_type3" value="VD">VD</label>
										<label><input type="radio" name="dealer_type" id="dealer_type4" value="WOW">WOW</label>
									</div>
								</div>
								<div class="form-group col-md-3">
									<label for="SC_State_Name">State Name</label> <span style="color: red;">*</span>
									<input type="text" name="SC_State_Name" id="SC_State_Name" class="form-control" placeholder="State Name" disabled />
								</div>
								<div class="form-group col-md-3">
									<label for="SC_City_Name">City Name</label> <span style="color: red;">*</span>
									<input type="text" name="SC_City_Name" id="SC_City_Name" class="form-control" placeholder="City Name" disabled />
								</div>
								<div class="form-group col-md-3">
									<label for="bsvi">BSVI</label> <span style="color: red;">*</span>
									<input type="text" name="bsvi" id="bsvi" class="form-control" placeholder="BSVI" disabled />
								</div>
								<div class="form-group col-md-3">
									<label for="area_champion">Area Champion</label> <span style="color: red;">*</span>
									<input type="text" name="area_champion" id="area_champion" class="form-control" placeholder="Area Champion" disabled />
								</div>
								<div class="form-group col-md-3">
									<label for="region_champion">Region Champion</label> <span style="color: red;">*</span>
									<input type="text" name="region_champion" id="region_champion" class="form-control" placeholder="Region Champion" disabled />
								</div>
								<div class="form-group col-md-3">
									<label for="night_spoc_1_name">Night Spoc Person 1</label> {{-- <span style="color: red;">*</span> --}}
									<input type="text" name="night_spoc_1_name" id="night_spoc_1_name" class="form-control" placeholder="Night Spoc Person 1"/>
								</div>
								<div class="form-group col-md-3">
									<label for="night_spoc_1_number">Night Spoc Person 1 Number</label> {{-- <span style="color: red;">*</span> --}}
									<input type="number" name="night_spoc_1_number" id="night_spoc_1_number" class="form-control checkMobile" placeholder="Night Spoc Person 1 Number"/>
								</div>
								<div class="form-group col-md-3">
									<label for="night_spoc_2_name">Night Spoc Person 2</label> {{-- <span style="color: red;">*</span> --}}
									<input type="text" name="night_spoc_2_name" id="night_spoc_2_name" class="form-control" placeholder="Night Spoc Person 2"/>
								</div>
								<div class="form-group col-md-3">
									<label for="night_spoc_2_number">Night Spoc Person 2 Number</label> {{-- <span style="color: red;">*</span> --}}
									<input type="number" name="night_spoc_2_number" id="night_spoc_2_number" class="form-control checkMobile" placeholder="Night Spoc Person 2 Number"/>
								</div>
								<div class="form-group col-md-3" id="td_Status">
									<label for="shift_time">24/7</label> <span style="color: red;">*</span>
									<select name="shift_time" id="shift_time" tabindex="1" class="form-control" disabled>
										<optgroup>
											<option Value="">--select--</option>
											<option Value="Yes">Yes</option>
											<option Value="No">No</option>
											
										</optgroup>
									</select>
									<span id="shift_time_error" style="color:red"></span>
								</div>
								<div class="form-group col-md-3" id="td_Status">
									<label for="Name">Status</label> <span style="color: red;">*</span>
									<select name="flag" id="flag" tabindex="1" class="form-control" disabled>
										<optgroup>
											<option Value="NA">--select--</option>
											<option Value="1">Active</option>
											<option Value="0">Inactive</option>
											
										</optgroup>
									</select>
									<span id="flag_error" style="color:red"></span>
								</div>
                            </div>
							@if(Auth::user()->role  == '78')
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
						 <br />                       
						<div class="table-responsive"><br />
							<table id="order-dealer" class="table">
                                <thead>
                                    <tr>
										<th>Action</th>
										<th>Dealer Name</th>
										<th>SAC Code</th>
										<th>Phone</th>
										<th>Address</th>
										<th>Pin Code</th>
										<th>Zone</th>
										<th>Region</th>
										<th>Area</th>
										<th>Status</th>
										<th style="display: none;">Region</th> 
										<th style="display: none;">State</th>
										<th style="display: none;">city</th>
										<th style="display: none;">plant_code</th>
										
										<th style="display: none;">mail</th>
										<th style="display: none;">latitude</th>
										<th style="display: none;">longitude</th>
										<th style="display: none;">working_mon_fri</th>
										<th style="display: none;">working_sat</th>
										<th style="display: none;">working_sun</th>
										<th style="display: none;">working_hours</th>
										<th style="display: none;">sunday_working</th>
										<th style="display: none;">dealer_type</th>

										<th style="display: none;">SC_State_Name</th>
										<th style="display: none;">SC_City_Name</th>
										<th style="display: none;">bsvi</th>
										<th style="display: none;">area_champion</th>
										<th style="display: none;">region_champion</th>

										<th style="display: none;">night_spoc_1_name</th>
										<th style="display: none;">night_spoc_1_number</th>
										<th style="display: none;">night_spoc_2_name</th>
										<th style="display: none;">night_spoc_2_number</th>
										<th style="display: none;">shift_time</th>
										
                                    </tr>
                                </thead>
                                <tbody>
                                @php $count=1; @endphp
                                @isset($rowData)
								
									@foreach($rowData as $row)
                                    <tr>
										<td>
											<i class="fa fa-pencil-square-o" aria-hidden="true" id="{{$row->id}}" data-position="left" data-tooltip="Edit" onclick="javascript:return EditDealer(this);" style="cursor: pointer;"></i>
											{{-- <a href="{{route('Dealer_delete.dealerDelete', ['id' => $row->id])}}" onclick="return confirm('Do you want to delete?')">
											<i class="fa fa-trash-o" aria-hidden="true" style="cursor: pointer;"></i></a> --}}
										</td>
                                    	<td class="cls_dealername">{{$row->dealer_name}}</td>
										<td class="cls_sac_code">{{$row->sac_code}}</td>
                                        <td class="cls_phone">{{$row->phone}}</td>
                                        <td class="cls_address">{{$row->address}}</td>
                                        <td class="cls_pincode">{{$row->pincode}}</td>
                                        <td>{{$row->region}}</td>
                                        <td>{{$row->stateName}}</td>
                                        <td>{{$row->cityName}}</td>
										<td class="cls_flag" >@if($row->flag=='1')
                                        	<label class='badge badge-success'>Active</label>
                                        	@else
                                        	<label class="badge badge-danger">Inactive</label>
                                        	@endif
                                        </td> 
										<td class="cls_zone"  style="display: none;">{{$row->zone}}</td> 
                                        <td class="cls_state" style="display: none;">{{$row->state}}</td>
                                        <td class="cls_city" style="display: none;">{{$row->city}}</td>

										<td class="cls_latitude" style="display: none;">{{$row->latitude}}</td>
										<td class="cls_longitude" style="display: none;">{{$row->longitude}}</td>

										<td class="cls_plant_code" style="display: none;">{{$row->plant_code}}</td>
										
										<td class="cls_mail" style="display: none;">{{$row->mail}}</td>
										<td class="cls_working_mon_fri" style="display: none;">{{$row->working_mon_fri}}</td>
										<td class="cls_working_sat" style="display: none;">{{$row->working_sat}}</td>
										<td class="cls_working_sun" style="display: none;">{{$row->working_sun}}</td>
										<td class="cls_working_hours" style="display: none;">{{$row->working_hours}}</td>
										<td class="cls_sunday_working" style="display: none;">{{$row->sunday_working}}</td>
										<td class="cls_dealer_type" style="display: none;">{{$row->dealer_type}}</td>

										<td class="cls_SC_State_Name" style="display: none;">{{$row->SC_State_Name}}</td>
										<td class="cls_SC_City_Name" style="display: none;">{{$row->SC_City_Name}}</td>
										<td class="cls_bsvi" style="display: none;">{{$row->bsvi}}</td>
										<td class="cls_area_champion" style="display: none;">{{$row->area_champion}}</td>
										<td class="cls_region_champion" style="display: none;">{{$row->region_champion}}</td>


										<td class="cls_night_spoc_1_name" style="display: none;">{{$row->night_spoc_1_name}}</td>
										<td class="cls_night_spoc_1_number" style="display: none;">{{$row->night_spoc_1_number}}</td>
										<td class="cls_night_spoc_2_name" style="display: none;">{{$row->night_spoc_2_name}}</td>
										<td class="cls_night_spoc_2_number" style="display: none;">{{$row->night_spoc_2_number}}</td>
										<td class="cls_shift_time" style="display: none;">{{$row->shift_time}}</td>
										
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
	function formSubmit(){
		var night_spoc_1_number = $('#night_spoc_1_number').val();
		var night_spoc_2_number = $('#night_spoc_2_number').val();
		if(night_spoc_1_number.length > 10 && night_spoc_1_number.length < 10){
			alert("Night SPOC 1 number should be 10");
			return false;
		}
		if(night_spoc_2_number.length > 10 && night_spoc_2_number.length < 10){
			alert("Night SPOC 2 number should be 10");
			return false;
		}
		return true;
	}
	$(".checkMobile").keyup(function(){
		const lnth = this.value;
		const getlenth = lnth.length;
		if(getlenth >10){
			alert("Night SPOC number should be Less than 10");
			$('#submit').prop('disabled',true);
			
		}else{
			$('#submit').prop('disabled',false);
		}

	});
	function bulkUpload(){
		$("#sh").slideToggle();
	}
	function addWorkingHour(param1,param2,param3){
		var workingHour = 'Mon - Fri : '+param1+', Sat : '+param2+', Sun : '+param3;
		$('#working_hours').val(workingHour); 
	}
	function getSubDealer(e){
		if (e=='Site Support') {
			$('#sub_dealer_div').show();
			$('#type_support_div').show();
		}else{
			$('#sub_dealer_div').hide();
			$('#type_support_div').hide();
		}
	}

function EditDealer(el){
	$("#insertDealer").show();
	var dealername =$(el).parents('td').parents('tr').find('.cls_dealername').text();
	var phone =$(el).parents('td').parents('tr').find('.cls_phone').text();
	var address =$(el).parents('td').parents('tr').find('.cls_address').text();
	var pincode =$(el).parents('td').parents('tr').find('.cls_pincode').text();
	var zone =$(el).parents('td').parents('tr').find('.cls_zone').text();
	var state =$(el).parents('td').parents('tr').find('.cls_state').text();
	var city =$(el).parents('td').parents('tr').find('.cls_city').text();
	var latitude =$(el).parents('td').parents('tr').find('.cls_latitude').text();
	var longitude =$(el).parents('td').parents('tr').find('.cls_longitude').text();

	var plant_code =$(el).parents('td').parents('tr').find('.cls_plant_code').text();
	var sac_code =$(el).parents('td').parents('tr').find('.cls_sac_code').text();
	var mail =$(el).parents('td').parents('tr').find('.cls_mail').text();
	var working_mon_fri =$(el).parents('td').parents('tr').find('.cls_working_mon_fri').text();
	var working_sat =$(el).parents('td').parents('tr').find('.cls_working_sat').text();
	var working_sun =$(el).parents('td').parents('tr').find('.cls_working_sun').text();
	var working_hours =$(el).parents('td').parents('tr').find('.cls_working_hours').text();
	var sunday_working =$(el).parents('td').parents('tr').find('.cls_sunday_working').text();
	var dealer_type =$(el).parents('td').parents('tr').find('.cls_dealer_type').text();

	var SC_State_Name =$(el).parents('td').parents('tr').find('.cls_SC_State_Name').text();
	var SC_City_Name =$(el).parents('td').parents('tr').find('.cls_SC_City_Name').text();
	var bsvi =$(el).parents('td').parents('tr').find('.cls_bsvi').text();
	var area_champion =$(el).parents('td').parents('tr').find('.cls_area_champion').text();
	var region_champion =$(el).parents('td').parents('tr').find('.cls_region_champion').text();
	var flg=$(el).parents('td').parents('tr').find('.cls_flag').text();

	var night_spoc_1_name=$(el).parents('td').parents('tr').find('.cls_night_spoc_1_name').text();
	var night_spoc_1_number=$(el).parents('td').parents('tr').find('.cls_night_spoc_1_number').text();
	var night_spoc_2_name=$(el).parents('td').parents('tr').find('.cls_night_spoc_2_name').text();
	var night_spoc_2_number=$(el).parents('td').parents('tr').find('.cls_night_spoc_2_number').text();
	var shift_time=$(el).parents('td').parents('tr').find('.cls_shift_time').text();
	if(flg.trim()=='Active'){
		flg='1';
	}else{
		flg='0';
	}
	
	$('#flag').val(flg);
	addWorkingHour(working_mon_fri,working_sat,working_sun);
	$('#night_spoc_1_name').val(night_spoc_1_name);
	$('#night_spoc_1_number').val(night_spoc_1_number);
	$('#night_spoc_2_name').val(night_spoc_2_name);
	$('#night_spoc_2_number').val(night_spoc_2_number);
	$('#shift_time').val(shift_time);
	$('#DealerName').val(dealername);
	$('#phone').val(phone);	
	$('#address').val(address);
	$('#pincode').val(pincode);
	$('#latitude').val(latitude);
	$('#longitude').val(longitude);
	$('#zone').val(zone);

	$('#plant_code').val(plant_code);
	$('#sac_code').val(sac_code);
	$('#working_mon_fri').val(working_mon_fri);
	$('#working_sat').val(working_sat);
	$('#working_sun').val(working_sun);
	$('#working_hours').val(working_hours);
	$('#mail').val(mail);
	$('#sunday_working').val(sunday_working);

	$('#SC_State_Name').val(SC_State_Name);
	$('#SC_City_Name').val(SC_City_Name);
	$('#bsvi').val(bsvi);
	$('#area_champion').val(area_champion);
	$('#region_champion').val(region_champion);
	//alert(dealer_type);
		$('#dealer_type').prop('checked',false);
		$('#dealer_type1').prop('checked',false);
		$('#dealer_type2').prop('checked',false);
		$('#dealer_type3').prop('checked',false);
		$('#dealer_type4').prop('checked',false);
	if(dealer_type == 'ALASC'){
		$('#dealer_type').prop('checked',true);
//		$('#dealer_type1').prop('checked',false);
//		$('#dealer_type2').prop('checked',false);
//		$('#dealer_type3').prop('checked',false);
//		$('#dealer_type4').prop('checked',false);
	}else if(dealer_type == 'DASC'){
		$('#dealer_type1').prop('checked',true);
	}	else if(dealer_type == 'SASSY'){
		$('#dealer_type2').prop('checked',true);
	}else if(dealer_type == 'VD'){
		$('#dealer_type3').prop('checked',true);
	}else if(dealer_type == 'WOW'){
		$('#dealer_type4').prop('checked',true);
	}

	$('#DataID').val(el.id);
	//Dealer_get_zone(zone);
	//On_Dealer_Zone(zone,state);
	fn_zone_change(zone,state);
	//Dealer_Reg_Office(zone,state,city);
	Dealer_State_change(zone,state,city);
}
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
</script>
	<link rel="stylesheet" href="datatable/cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
	<link rel="stylesheet" href="datatable/cdn.datatables.net/1.10.12/css/buttons.dataTables.min.css" />
	<script src="plugin/jquery/dist/jquery.min.js"></script>
	<script src="plugin/datatables.net/js/jquery.dataTables.min.js"></script>
	<script src="datatable/cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
	<script src="datatable/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
	<script src="datatable/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
	<script src="datatable/cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
			$("#insertDealer").hide();
            $('#order-dealer').DataTable({
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
						exportOptions: { columns: [ 1,2,3,4,5,6,7 ] }
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
		function On_Dealer_Zone(el,ell){
			var myarray= [];
			var favorite = [];
			if(ell!='')
			{
            $('#zone :selected').each(function(i, sel)
            { 
    			//favorite.push(ell);
			});
			
			//var zz=favorite.join(",");
			var zz=el;
			}
			else
			{
				 $('#zone :selected').each(function(i, sel){
				favorite.push($(this).val());
				});
				var zz=favorite.join(",");
			}
			
            $.ajax({ url: '{{url("get-multi-id-reg-office")}}',
            data: { 'zone':zz},
				success: function(data){
					var Result = data.split(",");var str = '';
					Result.pop();
					str += "<option value='NA' selected>--Select--</option>";
					for (item in Result)
					{
						Result2 = Result[item].split("~");
						var mith = ell.split(",");
						
						if(ell!='')
						{
							if (jQuery.inArray(Result2[0], mith)!='-1') //if(ell==Result[item])
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
					document.getElementById('reg_office').innerHTML =str;
				}
			});
		}
function Dealer_Reg_Office(el,ell,elll){
	
	$.ajax({ url: '{{url("get-area-office")}}',
		data: { 'r_id':el,'s_id':ell },
		success: function(data){
		var Result = data.split(",");var str = '';
		Result.pop();
		str += "<option value='NA' selected>--Select--</option>";
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
		document.getElementById('city').innerHTML = str;
	}});
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
    </script>

@endsection
