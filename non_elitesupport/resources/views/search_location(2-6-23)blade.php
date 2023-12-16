@extends("layouts.masterlayout")
@section('title','Create Ticket')
@section('bodycontent')
<style>
	/* The Modal (background) */
	.modal {
	  display: none; /* Hidden by default */
	  position: fixed; /* Stay in place */
	  z-index: 1; /* Sit on top */
	  padding-top: 100px; /* Location of the box */
	  left: 0;
	  top: 0;
	  width: 100%; /* Full width */
	  height: 100%; /* Full height */
	  overflow: auto; /* Enable scroll if needed */
	  background-color: rgb(0,0,0); /* Fallback color */
	  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
	}
	
	/* Modal Content */
	.modal-content {
	  background-color: #fefefe;
	  margin: auto;
	  padding: 20px;
	  border: 1px solid #888;
	  width: 80%;
	}
	
	/* The Close Button */
	.close {
	  color: #aaaaaa;
	  float: right;
	  font-size: 28px;
	  font-weight: bold;
	}
	
	.close:hover,
	.close:focus {
	  color: #000;
	  text-decoration: none;
	  cursor: pointer;
	}
	</style>
	<div class="content-wrapper mobcss">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Create Ticket</h4>
                <div class="row" >
                    <div class="col-md-8" style="border: 1px solid #ccc">
						<div class="ribbon">Vehicle Search</div>
                        <form name="myForm" id="myForm1" method="post" enctype="multipart/form-data">
				            <input type="hidden" name="_token" value="{{csrf_token()}}">
				            <div class="row" style="margin-bottom: 10px;">
			                 	<div class="form-group col-md-3">
			                        <label for="datefrom" >Registration Number</label>
									<input type="text" name="reg_number" id="reg_number" class="form-control" placeholder="Registration Number" />
			                        <span id="reg_number_error" style="color:red"></span> 
			                    </div>
			                 	<div class="form-group col-md-3">
			                        <label for="datefrom" >Chassis Number</label>
									<input type="text" name="chassis_number" id="chassis_number" class="form-control" placeholder="Chassis Number" />
			                        <span id="chassis_number_error" style="color:red"></span> 
			                    </div>
			                 	<div class="form-group col-md-3">
			                        <label for="datefrom" >Engine Number</label>
									<input type="text" name="engine_number" id="engine_number" class="form-control" placeholder="Engine Number" />
			                        <span id="engine_number_error" style="color:red"></span> 
			                    </div>
			                 	
			                	 <div class="form-group col-md-3">
			                        <a class="btn-secondary" onclick="getData(reg_number.value,chassis_number.value,engine_number.value);" style="color: #fff;padding: 5px;border-radius: 10px;position: relative;top: 30px;">Search</a>
									<a class="btn-secondary" onclick="reloadPage();" style="color: #fff;padding: 5px;border-radius: 10px;position: relative;top: 30px;">Reset</a>
									{{-- <a class="btn-secondary" onclick="resetForm();" style="color: #fff;padding: 5px;border-radius: 10px;position: relative;top: 30px;">Reset</a> --}}
			                    </div>
			                </div> 
			            </form>
						<hr>
						<div class="ribbon">Vehicle Details</div>
						<form name="myForm" id="myForm" method="post" enctype="multipart/form-data" action="{{url('ticket-creation-data')}}" onsubmit="return formSubmit()">
							<input type="hidden" name="_token" value="{{csrf_token()}}">
							<input type="hidden" name="vehicleId" id="vehicleId">
							<input type="hidden" name="ownerId" id="ownerId">
							{{-- <input type="hidden" name="owenerContactId" id="owenerContactId"> --}}
							<input type="hidden" name="callerId" id="callerId">
							<input type="hidden" name="latValue" id="latValue">
							<input type="hidden" name="longValue" id="longValue">
							<div class="row">
								<div class="form-group col-md-3">
			                        <label for="datefrom" >Registration Number</label>
									<span style="color: red;">*</span>
									<input type="text" name="reg_number1" id="reg_number1" class="form-control"  placeholder="Registration Number"  required />
			                        <span id="reg_number1_error" style="color:red"></span> 
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="datefrom" >Chassis Number</label>
									<span style="color: red;">*</span>
									<input type="text" name="chassis_number1" id="chassis_number1" class="form-control"  placeholder="Chassis Number"/>
			                        <span id="chassis_number1_error" style="color:red"></span> 
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="datefrom" >Engine Number</label>
									<span style="color: red;">*</span>
									<input type="text" name="engine_number1" id="engine_number1" class="form-control"  placeholder="Engine Number"/>
			                        <span id="engine_number1_error" style="color:red"></span> 
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="datefrom" >Vehicle Model</label>
									<span style="color: red;">*</span>
									<input type="text" name="vehicle_model" id="vehicle_model" class="form-control" placeholder="Vehicle Model" required/>
									
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="datefrom" >Vehicle Segment</label>
									
									<span style="color: red;">*</span>
									<input type="text" name="vehicle_segment" id="vehicle_segment" class="form-control"  placeholder="Vehicle Segment"/>
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="datefrom" >Purchase Date</label>
									
									<span style="color: red;">*</span>
									<input type="text" name="purchase_date" id="purchase_date" autocomplete="off" class="form-control" value="@isset($purchase_date){{$purchase_date}} @endisset"  placeholder="Purchase Date" />
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="datefrom" >Add Blue Use</label>
									<span style="color: red;">*</span>
									<select name="add_blue_use" id="add_blue_use" class="form-control" onchange="addBlueUse(this.value)">
										<option value="">--Select--</option>
										<option value="Yes">Yes</option>
										<option value="No">No</option>
									</select> 
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="datefrom" >Engine Emission Type</label>
									<span style="color: red;">*</span>
									<input type="text"  name="engine_emmission_type" id="engine_emmission_type"  class="form-control">
									<select name="engine_emmission_type" id="engine_emmission_type1"  class="form-control" disabled style="display:none;">
										<option value="">--Select--</option>
										<option value="BS6">BS-6</option>
										<option value="Non BS6">Non BS-6</option>
									</select>
									{{-- <input type="text" name="engine_emmission_type" id="engine_emmission_type" class="form-control"  placeholder="Engine Emission Type" required/> --}}
			                    </div>
								<div class="col-sm-12 text-center">
									<a class="btn-secondary" id="vehicleEdit" onclick="vehicleUpdate(ownerId.value,reg_number1.value,chassis_number1.value,engine_number1.value,vehicle_model.value,vehicle_segment.value,purchase_date.value,add_blue_use.value,engine_emmission_type.value);" style="display: none; color: #fff;padding: 5px;border-radius: 10px;position: relative;top: 10px;">Save Vehicle</a>
									<img src="{{asset('images/left.gif')}}" width="5%" id="vehicleIndication" style="display:none" /> 
								</div>
							</div>
							
							<hr>
							<div class="ribbon">Owner Details</div>
							<div class="row" >
								<div class="form-group col-md-3">
			                        <label for="owner_name" >Owner/Company</label>
									<span style="color: red;">*</span>
									{{-- <input type="text" name="owner_name" id="owner_name" class="form-control"  placeholder="Name" required/> --}}
									<input type="text" name="owner_name" id="owner_name_text" class="form-control"  placeholder="Name" style="display:none" disabled/>
									<select name="owner_name" id="owner_name" class="form-control"  placeholder="Name" onchange="ownerContactNameData(this.value)" required>
										<option value="">--Select--</option>
										{{-- @foreach($ownerData as $row)
											<option value="{{$row->id}}">{{$row->owner_name}}</option>
										@endforeach --}}
									</select>
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="owner_mob" >Mobile Number</label>
									<input type="text" name="owner_mob" id="owner_mob" class="form-control"  placeholder="Mobile Number" maxlength="10" readonly/>
									{{-- <input type="hidden" name="owner_mob" id="owner_mob" class="form-control"  placeholder="Mobile Number" maxlength="10" /> --}}
			                    </div> 
								{{-- <div class="form-group col-md-3">
			                        <label for="owner_landline" >Landline Number</label>
									<input type="text" name="owner_landline" id="owner_landline" class="form-control"  placeholder="Landline Number" readonly/>
									
			                        <span id="engine_number_error" style="color:red"></span> 
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="owner_cat" >Owner Category</label>
									<input type="text" name="owner_cat" id="owner_cat" class="form-control" readonly/> 
									
			                    </div> --}}
								<input type="hidden" name="owner_company" id="owner_company" class="form-control"  placeholder="Company Name"/>
								{{-- <div class="form-group col-md-3">
			                        <label for="datefrom" >Owner Campany Name</label>
									<span style="color: red;">*</span>
									<input type="text" name="owner_company" id="owner_company" class="form-control"  placeholder="Campany Name" required/>
			                    </div> --}}
								<div class="form-group col-md-3" style="display:none">
			                        <label for="alse_mail" >ALSE / ASM Email</label>
									<input type="text" name="alse_mail" id="alse_mail" class="form-control"  placeholder="ALSE Email" value="test@dispostable.com"/>
									{{-- <input type="hidden" name="alse_mail" id="alse_mail" class="form-control"  placeholder="ALSE Email" /> --}}
			                    </div>
								<div class="form-group col-md-3" style="display:none">
			                        <label for="asm_mail" >RSM Email</label>
									<input type="text" name="asm_mail" id="asm_mail" class="form-control"  placeholder="RSM Email" value="test@dispostable.com"/>
									{{-- <input type="hidden" name="asm_mail" id="asm_mail" class="form-control"  placeholder="RSM Email" /> --}}
			                    </div>
							</div>
							{{-- <div class="row" >
								<div class="container-fluid">
									<div class="col-sm-12 text-center">
										<a class="btn-secondary" id="vehicleEdit" onclick="vehicleUpdate(vehicleId.value,reg_number1.value,chassis_number1.value,engine_number1.value,vehicle_model.value,vehicle_segment.value,purchase_date.value,add_blue_use.value,engine_emmission_type.value);" style="display: none; color: #fff;padding: 5px;border-radius: 10px;position: relative;top: 10px;">Save</a>
									</div>
								 </div>
							</div> --}}
							<div class="row" >
								<div class="container-fluid">
									<div class="col-sm-12 text-center">
										<a class="btn-secondary" id="ownerEdit" onclick="ownerUpdate(owner_name_text.value,owner_mob.value);" style="display: none; color: #fff;padding: 5px;border-radius: 10px;position: relative;top: 10px;">Save Owner</a>
										<img src="{{asset('images/left.gif')}}" width="5%" id="ownerIndication" style="display:none" />
									</div>
								 </div>
							</div>
							<hr>
							{{-- <div class="ribbon">Contact Person Details</div>
							<div class="row" >
								<div class="form-group col-md-3">
			                        <label for="contact_name" >Contact Person</label>
									<span style="color: red;">*</span>
									<input type="text" name="contact_name" id="contact_name_text" class="form-control"  placeholder="Contact Person" style="display:none" disabled/>
									<select name="contact_name" id="contact_name_select" class="form-control" onchange="contactNameData(this.value)">
										<option value="">--Select--</option>
										@foreach($ownerContactData as $row)
											<option value="{{$row->id}}">{{$row->contact_name}}</option>
										@endforeach
									</select> 
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="datefrom" >Phone Number</label>
									<input type="tel" name="owner_contact_mob" id="owner_contact_mob" class="form-control"  placeholder="Phone Number" maxlength="10" pattern="[0-9]{10}" readonly/>
									
			                        <span id="engine_number_error" style="color:red"></span> 
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="datefrom" >Email</label>
									<input type="email" name="owner_contact_email" id="owner_contact_email" class="form-control"  placeholder="Email"  readonly/>
									
			                    </div>
							</div>
							<div class="row" >
								<div class="container-fluid">
									<div class="col-sm-12 text-center">
										<a class="btn-secondary" id="ownerContactEdit" onclick="ownerContactUpdate(vehicleId.value,ownerId.value,owenerContactId.value,owner_contact_mob.value,owner_contact_email.value);" style="display: none; color: #fff;padding: 5px;border-radius: 10px;position: relative;top: 10px;">Save Contact Details</a>
										<img src="{{asset('images/left.gif')}}" width="5%" id="contactIndication" style="display:none" />
									</div>
								 </div>
							</div> --}}
							<hr>
							<div class="ribbon">Caller Info</div>
							<div class="row">
								<div class="form-group col-md-3">
			                        <label for="datefrom" >Caller Type</label>
									<select name="caller_type" id="caller_type" class="form-control" required>
										<option value="">--Select--</option>
										<option value="Driver">Driver</option>
										<option value="Owner">Owner</option>
										<option value="Owner cum driver">Owner cum driver</option>
										<option value="Fleet Manager">Fleet Manager</option>
										
									</select> 
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="datefrom" >Caller Name</label>
									<span style="color: red;">*</span>
									<input type="text" name="caller_name" id="caller_name" class="form-control"  placeholder="Caller Name" required/>
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="datefrom" >Caller Contact Number</label>
									<span style="color: red;">*</span>
									<input type="tel" name="caller_contact" id="caller_contact" class="form-control"  placeholder="Caller Contact Number" maxlength="10" pattern="[0-9]{10}" required/>
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="datefrom" >Alternative Number</label>									
									<input type="tel" name="caller_contact_alt" id="caller_contact_alt" class="form-control"  placeholder="Alternative Number" maxlength="10" pattern="[0-9]{10}"/>
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="datefrom" >Caller Language</label>
									<select name="caller_language" id="caller_language" class="form-control" required>
										<option value="">--Select--</option>
										<option value="Hindi">Hindi</option>
										<option value="English">English</option>
										<option value="Malayalam">Malayalam</option>
										<option value="Kannad">Kannada</option>
										<option value="Tamil">Tamil</option>
										<option value="Telugu">Telugu</option>
									</select> 
			                    </div>
								<div class="form-group col-md-3" id="latDiv">
			                        <label for="datefrom" >Latitude</label>
									<span style="color: red;">*</span>
									<input type="text" name="lat" id="lat" class="form-control"  placeholder="Latitude" required/>
			                    </div>
								<div class="form-group col-md-3" id="longDiv">
			                        <label for="datefrom" >Longitude</label>
									<span style="color: red;">*</span>
									<input type="text" name="long" id="long" class="form-control"  placeholder="Longitude" required onkeyup="manualGoogleMap(lat.value,long.value)"/>
			                    </div>
							</div>
							<div class="row" >
								<div class="container-fluid">
									<div class="col-sm-12 text-center">
										<a class="btn-secondary" id="callerEdit" onclick="callerUpdate(vehicleId.value,ownerId.value,callerId.value,caller_type.value,caller_name.value,caller_contact.value,caller_contact_alt.value,caller_language.value);" style="display: none; color: #fff;padding: 5px;border-radius: 10px;position: relative;top: 10px;">Save Caller</a>
										<img src="{{asset('images/left.gif')}}" width="5%" id="callerIndication" style="display:none" />
									</div>
								 </div>
							</div>
							<div style="clear: both;margin: 10px"></div>
							<hr>
							<div class="ribbon">Vehicle Breakdown Details</div>
							<div class="row" >
								
								<div class="form-group col-md-3"> 
			                        <label for="state" >State</label>
									<span style="color: red;">*</span>
									<select name="state" id="state" class="form-control" onchange="functionStateChange(this.value,'');functionAssignDealer(this.value,'')" required>
										<option value="">--Select--</option>
										@isset($caller_state)
											@foreach ($caller_state as $row)
												<option value="{{$row->id}}">{{$row->state}}</option>
											@endforeach
										@endisset
									</select>
			                    </div>
									
								<div class="form-group col-md-3">
			                        <label for="city" >District</label>
									<span style="color: red;">*</span>
									<select id="cityCaller" name="city" class="form-control" required>
										<option value="">--Select--</option>
									</select>
								</div>
								{{-- <div class="form-group col-md-3">
			                        <label for="" >City</label>
									<span style="color: red;">*</span>
									<input type="text" name="district" id="district" class="form-control"  placeholder="City"  required/> 
			                    </div> --}}
								{{-- <div class="form-group col-md-3">
			                        <label for="" >Highway</label>
									<span style="color: red;">*</span>
									<input type="text" name="highway" id="highway" class="form-control"  placeholder="Highway"  required/> 
									
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="" >From Where</label>
									<span style="color: red;">*</span>
									<input type="text" name="from_where" id="from_where" class="form-control"  placeholder="From Where" required/>
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="" >To Where</label>
									<span style="color: red;">*</span>
									<input type="text" name="to_where" id="to_where" class="form-control"  placeholder="To Where" required/>
			                    </div> --}}
								<div class="form-group col-md-3">
			                        <label for="" >Location</label>
									<span style="color: red;">*</span>
									<input type="text" name="location" id="location" class="form-control"  placeholder="Location"  required/> 
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="landmark" >Landmark</label>
									<span style="color: red;">*</span>
									<input type="text" name="landmark" id="landmark" class="form-control"  placeholder="Landmark"  required/>
			                    </div>
								
								<div class="form-group col-md-3">
			                        <label for="vehicle_movable" >Is Vehicle Movable</label>
									<select name="vehicle_movable" id="vehicle_movable" class="form-control">
										<option value="NA">--Select--</option>
										<option value="Yes">Yes</option>
										<option value="No">No</option>
									</select>  
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="" >Ticket Type</label><span style="color: red;">*</span>
									<select name="ticket_type" id="ticket_type" class="form-control" required>
										<option value="">--Select--</option>
										{{-- <option value="Accident Ticket">Accident Ticket</option> --}}
										<option value="Breakdown Ticket">Breakdown Ticket</option>
										<option value="Vehicle in workshop">Vehicle in workshop</option>
									</select>  
			                    </div>
								
								{{--<div class="form-group col-md-9">
			                        <label for="datefrom" >Vehicle Problem (max 150 chars)<sup style="color: red;">*</sup></label>
									<textarea name="vehicle_problem" id="" cols="30" rows="5" class="form-control" required></textarea>
			                        <span id="engine_number_error" style="color:red"></span> 
			                    </div>--}}
							</div>
							
							<hr>
							<div class="ribbon">Vehicle Breakdown Ticket Details @if(Auth::user()->role == '29' || Auth::user()->role == '30' || Auth::user()->role == '87') <span style="float: right;"><a href="#" id="myBtn" style="color:#fff;text-decoration: underline;">Dealer Info</a></span> @endif</div>
 							{{-- <!-------------------------- The Modal --> --}}
 							<div id="myModal" class="modal">
 							{{-- <!-- Modal content --> --}}
 							<div class="modal-content">
 								<div class="row">
 									<span class="close">&times;</span>
 									<table class="table" border="1">
 										<thead>
 											<tr>
 												<th>Dealer Name</th>
 												<th>SAC Code</th>
 												<th>Address</th>
 												<th>Role</th>
 												<th>State</th> 
 												<th>User Name</th> 
 												<th>Mobile</th>
 											</tr>
 										</thead>
 										<tbody id="dealSearchTable"></tbody>
 									</table>
 								</div>
 							</div>
 							</div>
   							{{-- <!-------------------------- The Modal --> --}}
							<div class="row">
								
									<div class="form-group col-md-6">
										<label for="datefrom" >Ticket Assign To</label>
										<select name="assign_to" id="assign_to" class="form-control" onchange="getAssignMob(this.value),getAssignWorkManager(this.value,'')" required>
											<option value="">--Select--</option>
										</select>
									</div>
									<input type="hidden" name="dealer_mob_number" id="dealer_mob_number1"/>
									<input type="hidden" name="dealer_alt_mob_number" id="dealer_alt_mob_number" />
									{{-- <div class="form-group col-md-3">
										<label for="datefrom" >Dealer Mobile Number</label>
										<span style="color: red;">*</span>
										<input type="text" name="dealer_mob_number" id="dealer_mob_number" class="form-control"  placeholder="Mobile Number" disabled/>
										<input type="hidden" name="dealer_mob_number" id="dealer_mob_number1"/>
									</div>
									
									<div class="form-group col-md-3">
										<label for="datefrom" >Alt. Contact Number</label>
										<input type="text" name="dealer_alt_mob_number" id="dealer_alt_mob_number" class="form-control"  placeholder="Alt. Contact Number" maxlength="10" />
									</div> --}}
									<div class="form-group col-md-3">
										<label for="datefrom" >Work Manager</label>
										<select name="assign_work_manager" id="assign_work_manager" class="form-control" onchange="getAssignWorkManagerMobile(this.value)" required>
										</select>
									</div>
									<div class="form-group col-md-3">
										<label for="datefrom" >Work Manager Mobile</label>
										<input type="text" name="assign_work_manager_mobile" id="assign_work_manager_mobile" class="form-control"  placeholder="Mobile Number" readonly onkeyup="checkWorkManagerMobile()" />
										<span id="folio-invalid" style="color:#ff0000;display:none">Invalid mobile No</span>
										{{-- <input type="hidden" name="assign_work_manager_mobile" id="assign_work_manager_mobile1"/> --}}
									</div>
									<div class="form-group col-md-3">
										<label for="remark_type" >Ticket Status</label><span style="color: red;">*</span>
										<select name="remark_type" id="remark_type" class="form-control" required>
											<option value="">--Select--</option>
											@isset($remark_type)
												@foreach ($remark_type as $row)
													@if(Auth::user()->role == '29' || Auth::user()->role == '30' || Auth::user()->role == '87')
														<option value="{{$row->type}}">{{$row->type}}</option>
													@else
													@php $tictStatus = array(32,33,34,35,36,13); @endphp
														@if(!in_array($row->id,$tictStatus))
															<option value="{{$row->type}}">{{$row->type}}</option>
														@endif
													@endif
												@endforeach
												
											@endisset
											
										</select> 
									</div>
								
									{{-- <div class="form-group col-md-3">
										<label for="datefrom" >Disposition</label>
										<select name="disposition" id="disposition" class="form-control" >
											<option value="NA">--Select--</option>
											<option value="RNR">RNR</option>
											<option value="Callback">Callback</option>
											<option value="Line Busy">Line Busy</option>
											<option value="Switched Off">Switched Off</option>
											<option value="Not Reachable">Not Reachable</option>
											<option value="Status Collected">Status Collected</option>
										</select>
									</div> --}}
									<div class="form-group col-md-3">
										<label for="vehicle_type" >Vehicle Type</label>
										<select name="vehicle_type" id="vehicle_type" class="form-control">
											<option value="NA">--Select--</option>
											<option value="Warranty">Warranty</option>
											<option value="AMC">AMC</option>
											<option value="Extended Warranty">Extended Warranty</option>
											<option value="Paid">Paid</option>
											<option value="Self">Self</option>
										</select>   
									</div>
									<div class="form-group col-md-3">
										<label for="Aggregate" >Aggregate</label>
										<select name="aggregate" id="aggregate" class="form-control">
											<option value="NA">--Select--</option>
											<option value="Engine">Engine</option>
											<option value="Clutch">Clutch</option>
											<option value="Gear Box">Gear Box</option>
											<option value="Tipping Units">Tipping Units</option>
											<option value="PP Shaft">PP Shaft</option>
											<option value="Rear Axie">Rear Axie</option>
											<option value="Steering">Steering</option>
											<option value="Brakes">Brakes</option>
											<option value="Chassis">Chassis</option>
											<option value="Accessories">Accessories</option>
											<option value="Electricals">Electricals</option>
										</select> 
									</div>
									{{-- <div class="form-group col-md-3">
										<label for="datefrom" >Agent Remarks</label><span style="color: red;">*</span>
										<select name="agent_remark" id="agent_remark" class="form-control" required>
											<option value="">--Select--</option>
											<option value="Incorrect response and restoration">Incorrect response and restoration</option>
											<option value="Incorrect ticket closure/ticket reopen">Incorrect ticket closure/ticket reopen</option>
											<option value="NO error">NO error</option>
										</select> 
									</div> --}}
									
									<div class="form-group col-md-3">
										<label for="datefrom" >Est. Response Time</label>
										<span style="color: red;">*</span>
										<input type="text" name="estimated_response_time" id="estimated_response_time" autocomplete="off" class="form-control" >
										{{-- <input type="text" name="estimated_response_time" id="estimated_response_time" class="form-control" placeholder="Estimated Response Time" /> --}}
									</div>
								
								<div class="form-group col-md-3">
			                        <label for="datefrom" >Actual Response Time</label>
									<input type="text" name="actual_response_time" id="actual_response_time" autocomplete="off" class="form-control" >
									{{-- <input type="text" name="actual_response_time" id="actual_response_time" class="form-control" placeholder="Actual Response Time" /> --}}
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="datefrom" >Restoration Time</label>
									<input type="text" name="tat_scheduled" id="tat_scheduled" autocomplete="off" class="form-control" >
									{{-- <input type="text" name="tat_scheduled" id="tat_scheduled" class="form-control" placeholder="Restoration Time" /> --}}
			                    </div>
								<div class="form-group col-md-3">
									<label for="datefrom" >Restoration Type</label>
									<select name="restoration_type" id="restoration_type" class="form-control" >
										<option value="">--Select--</option>
										<option value="Restored by self">Restored by self</option>
										<option value="Restored By Support">Restored By Support</option>
										<option value="Restored By Unknown Support">Restored By Unknown Support</option>
									</select>
								</div>
								<div class="form-group col-md-3" style="display:none;">
									<label for="datefrom" >Response Delay Reason</label>
									<select name="response_delay_reason" id="response_delay_reason" class="form-control" >
										<option value="">--Select--</option>
										@foreach($responseDelayReason as $row)
											<option value="{{ $row->reason }}">{{ $row->reason }}</option>
										@endforeach
										
									</select>
								</div>
								<div class="form-group col-md-3">
			                        <label for="datefrom" >Acceptance</label>
									<div class="radio">
										<label><input type="radio" name="acceptance" value="1" checked >Yes</label>
										<label><input type="radio" name="acceptance" value="0">No</label>
									</div>
			                    </div>
								{{-- <div class="form-group col-md-3">
									<label for="datefrom" >Source</label>
									<span style="color: red;">*</span>
									<select name="source" id="source" class="form-control" required>
										<option value="">--Select--</option>
										<option value="Inbound ticket">Inbound ticket</option>
										<option value="Email ticket">Email ticket</option>
									</select>
								</div> --}}
								<div class="form-group col-md-3">
			                        <label for="datefrom" >Followup Time</label>
									<span style="color: red;">*</span>
									<input type="text" name="followup_time" id="followup_time" autocomplete="off" placeholder="Select Date" class="form-control" required >
									
			                    </div>
								<div class="form-group col-md-9">
			                        <label for="datefrom" >Complaint Reported</label>
									<textarea name="standard_remark" id="standard_remark" cols="20" rows="3" class="form-control"></textarea>
			                    </div>
							</div> 
							<div class="row">
								<div class="form-group col-md-12">
			                        <label for="datefrom" >Remarks ({{-- Max 500 Chars --}} Don't put it special char and press enter for new line )<sup style="color: red;">*</sup></label>
									<textarea name="assign_remarks" id="assign_remarks" cols="30" rows="5" class="form-control"></textarea>
			                        <span id="engine_number_error" style="color:red"></span> 
			                    </div>
							</div>
							<br/>
							<div class="row" style="margin-bottom: 10px;">
								<div class="container-fluid">
									<div class="col-sm-12 text-center">
										<input type="submit"class="btn btn-primary rounded" name="submit" id="submit" value="Submit" />
										
									</div>
								 </div>
							</div>
						</form>
                    </div>
					<div class="col-md-4" style="border: 1px solid #ccc">
							<div class="row">
								<div class="form-group col-md-6">
									<label for="" >Send Link</label>
									<input type="text" name="phoneNumber" id="phoneNumber" class="form-control" maxlength="10" placeholder="Phone Number" />
									<input type="hidden" name="sessionId" id="sessionId" />
								</div>
								<div class="form-group col-md-6">
									<label for="" ></label>
									<a class="btn-secondary" onclick="getLocation(phoneNumber.value);" style="color: #fff;padding: 5px 12px 5px 12px;border-radius: 10px;position: relative;top: 30px;">Send</a>
									<a class="btn-secondary" onclick="my_function();" style="color: #fff;padding: 5px;border-radius: 10px;position: relative;top: 30px;">Get Location</a>
								</div>
							</div>
							<hr>
							<div class="row">
								<div id="map_canvas" style="width:100%; height:1300px;"></div>
							</div>
						
					</div>
                </div>
            </div>
        </div>
    </div>
	<script src="{{asset('datapicker/js/jquery.datetimepicker.js')}}"></script>
	<link rel="stylesheet" href="{{asset('datapicker/css/jquery.datetimepicker.min.css')}}">
{{-- <script async
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyADPkns3qwooRo_1WuhIcr0665fQbHNILU&callback=initMap">
</script>	 --}}
 <script>
function formSubmit(){
 		var vehicleId = $('#vehicleId').val();
 		var ownerId = $('#ownerId').val();
 		var followup_time = $('#followup_time').val();
 		
 		/* var owenerContactId = $('#owenerContactId').val(); */
 		var callerId = $('#callerId').val();
 		var estimated_response_time = $('#estimated_response_time').val();
 		if(vehicleId =='' || vehicleId == 0){
 			toastr.info("Please save vehicle info");
			 $('#vehicleIndication').show();
			 $('#reg_number1').focus();
 			return false;
 		}else if(ownerId == '' || ownerId == 0){
			toastr.info("Please save owner info");
			 $('#ownerIndication').show();
			 $('#owner_name').focus();
 			return false;
 		}else if(callerId == '' || callerId == 0){
			toastr.info("Please save caller info");
			 $('#callerIndication').show();
			 $('#caller_type').focus();
 			return false;
 		}else if(followup_time == '' || followup_time == 0){
			toastr.info("Please save followup time");
			 $('#followup_time').focus();
 			return false;
 		}else if(followup_time == '' || followup_time == 0){
			toastr.info("Please enter Followup Time");
			 $('#followup_time').focus();
 			return false;
 		}else if(estimated_response_time == '' || estimated_response_time == 0){
			toastr.info("Please enter Estimated Response Time");
			 $('#estimated_response_time').focus();
 			return false;
 		}else{
 			return true;
 		}
 	}
/* *********************************Model***************************************** */
 	 // Get the modal
	  var modal = document.getElementById("myModal");
 
 // Get the button that opens the modal
 var btn = document.getElementById("myBtn");
 
 // Get the <span> element that closes the modal
 var span = document.getElementsByClassName("close")[0];
 
 // When the user clicks the button, open the modal 
 btn.onclick = function() {
 	var assign_to = $('#assign_to').val();
 	if(assign_to == ''){
		toastr.info("Please Select Ticket Asign To");
 	}else{
 		modal.style.display = "block";
 		$.ajax({ url: '{{url("dealer-search-function")}}',
 		data: { 'dealerId':assign_to},
 		success: function(response){
 			console.log(response);
 			var Result = response.split("##");var str = '';
 			Result.pop();
 			for (item1 in Result) {
 			var Result2 = Result[item1].split("~~");
 				str += "<tr><td>" + Result2[0] + "</td>";
 				str += "<td>" + Result2[1] + "</td>";
 				str += "<td>" + Result2[2] + "</td>";
 				str += "<td>" + Result2[6] + "</td>";
 				str += "<td>" + Result2[3] + "</td>";
 				str += "<td>" + Result2[4] + "</td>";
 				str += "<td>" + Result2[5] + "</td></tr>";
 		}
 		document.getElementById('dealSearchTable').innerHTML = str;
 		}
 	});
 	}
   
 }
 
 // When the user clicks on <span> (x), close the modal
 span.onclick = function() {
   modal.style.display = "none";
 }
 
 // When the user clicks anywhere outside of the modal, close it
 window.onclick = function(event) {
   if (event.target == modal) {
     modal.style.display = "none";
   }
 }
 /* *********************************Model***************************************** */
 
	 function addBlueUse(param){
		 if(param == 'Yes'){
			$('#engine_emmission_type').val('BS6');
		 }else{
			$('#engine_emmission_type').val('Non BS6');
		 }
	 }
	 function functionStateChange(stateId,ell){
		$.ajax({ url: '{{url("get-caller-state-change")}}',
				data: { 'stateId':stateId},
				success: function(response){
					var Result = response.split(",");var str = '';
					Result.pop();
					str += "<option value=''>--Select--</option>";
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
				document.getElementById('cityCaller').innerHTML = str;
				}
		});
	} 
	 function functionAssignDealer(stateId,ell){
		 
		$.ajax({ url: '{{url("get-assign-dealer-state-change")}}',
				data: { 'stateId':stateId},
				success: function(response){
					//alert(response);
					var Result = response.split(",");var str = '';
					Result.pop();
					str += "<option value=''>--Select--</option>";
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
				document.getElementById('assign_to').innerHTML = str;
				}
		});
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
	$(document).ready(function () {
		var today = new Date();
 		var options = {
 			maxDate: new Date(),
 			maxTime: new Date(),
 			format: 'Y-m-d H:i:s',
			step:1,
 			timepicker: true,
 			onChangeDateTime: function(date) {
 				// Here you need to compare date! this is up to you :-)
 				if (date.getDate() === today.getDate()) {
 				this.setOptions({maxTime: new Date()});
 				} else {
 				this.setOptions({maxTime: false});
 				}
 			}
 		};
 		var options1 = {
 			//minDate: 0,  // disable past date
     		//minTime: 0, // disable past time
 			//maxTime: new Date(),
 			format:'Y-m-d H:i:s',
			step:1,
 			/* onChangeDateTime: function(date) {
  				// Here you need to compare date! this is up to you :-)
 				if (today.getDate() >= date.getDate()) {
 						this.setOptions({maxTime: new Date()});
  				} else {
 					this.setOptions({maxTime: false});
 				 	
  				}
  			} */
  		};
		$('#purchase_date').datetimepicker({ maxDate: 0,format:'Y-m-d',timepicker:false});
		$('#estimated_response_time').datetimepicker(options1).attr('readonly','readonly');
		$('#followup_time').datetimepicker(options1).attr('readonly','readonly');
		/* $('#actual_response_time').datetimepicker({maxDate: 0,maxTime: 0,format:'Y-m-d H:i:s'});
		$('#tat_scheduled').datetimepicker({maxDate: 0,maxTime: 0,format:'Y-m-d H:i:s'}); */
		$('#actual_response_time').datetimepicker(options).attr('readonly','readonly');
		$('#tat_scheduled').datetimepicker(options).attr('readonly','readonly');
	});
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
	
	function getData(reg_number,chassis_number,engine_number){
		
		//console.log(reg_number);
		$.ajax({ url: '{{url("check-registration-ticket")}}',
			data: { 'reg_number':reg_number,'chassis_number':chassis_number,'engine_number':engine_number},
			success: function(ReultAjax){
				
				if(ReultAjax =='Yes'){
					toastr.info("Given vehicle ticket is open");
					$('#submit').hide();
					
				}else{
					
					$('#submit').show();
					$.ajax({ url: '{{url("get-vehicle-details")}}',
						data: { 'reg_number':reg_number,'chassis_number':chassis_number,'engine_number':engine_number},
						success: function(result){
							console.log(result);
							if(result =='no'){
								// alert("There is no Vehicle");
								// $('#callerEdit').show();
								// $('#vehicleEdit').show();
								$.ajax({ url: '{{url("check-elite-reg")}}',
									data: { 'reg_number':reg_number,'chassis_number':chassis_number,'engine_number':engine_number},
									success: function(res){
										if(res == "Yes"){
											alert("Redirect to Elite Support");
											window.location.href = "https://helpline.ashokleyland.com/elitesupport/autologin?id={{base64_encode(Auth::user()->id)}}";
										}else{

											$('#callerEdit').show();
											$('#vehicleEdit').show();
											$('#ownerEdit').show();
											$('#ownerContactEdit').show();
											$('#engine_emmission_type1').show();
											$('#contact_name_text').show();
											
											$('#owner_name').hide();
											$('#engine_emmission_type').hide();
											$('#contact_name_select').hide();
											$('#owner_name_text').show();
											$("#owner_name").prop('disabled', true);
											$("#engine_emmission_type").prop('disabled', true);
											$("#engine_emmission_type1").prop('disabled', false);
											$("#contact_name_text").prop('disabled', false);
											$("#owner_name_text").prop('disabled', false);
											
											$("#contact_name_select").prop('disabled', true);
											$("#owner_mob").prop('readonly', false);
											$("#owner_landline").prop('readonly', false);
											$("#owner_cat").prop('readonly', false);
											$("#owner_contact_mob").prop('readonly', false);
											$("#owner_contact_email").prop('readonly', false);

										}

									}
								});
								
							}else{
								$('#callerEdit').show();
								//$('#vehicleEdit').show();
								var res = result.split('~~');
								$('#vehicleId').val(res[0]);
								$('#chassis_number').val(res[3]);
								$('#engine_number').val(res[4]);
								$('#reg_number1').val(res[2]);
								$('#chassis_number1').val(res[3]);
								$('#engine_number1').val(res[4]);
								$('#vehicle_model').val(res[1]);
								$('#vehicle_segment').val(res[5]);
								$('#purchase_date').val(res[6]);
								$('#add_blue_use').val(res[7]);
								$('#engine_emmission_type').val(res[9]);
								// $('#owner_name').val(res[10]);
								$('#owner_name').append(`<option value="${res[10]}" selected>${res[11]}</option>`);
								var ownerName = res[11];
								var ownerId = res[10];
								var contact_name = res[29];
								$('#owner_mob').val(res[12]);
								$('#owner_landline').val(res[13]);
								$('#owner_cat').val(res[14]);
								$('#owner_company').val(res[15]);
								$('#ownerId').val(res[10]);
								$('#alse_mail').val(res[31]);
								$('#asm_mail').val(res[32]);
								var ownerId = res[10];
								$('#owenerContactId').val(res[16]);
								$('#owner_contact_mob').val(res[17]);  
								$('#contact_name_select').val(res[16]);
								$('#owner_contact_email').val(res[30]);
							}
								
						}
					});					
				}					
			}
		});
		
	
 }


 function ownerContactNameData(id){
	$('#ownerId').val(id);
	$.ajax({ url: '{{url("get-owner-change")}}',
		data: {'id':id},
		success: function(response){
			var Result = response.split("##");var str = '';
			Result.pop();
			console.log(Result);
			var res = Result[0].split('~~');
			$('#owner_name').val(res[0]);
			$('#owner_mob').val(res[3]);
			$('#owner_landline').val(res[4]);
			$('#owner_cat').val(res[5]);
			//$('#owner_cat').val('Select Customer');
			$('#owner_company').val(res[6]);
			$('#alse_mail').val(res[7]);
			$('#asm_mail').val(res[8]);
		}
	});
 }
 function contactNameData(id){
	// alert(id);
	$('#owenerContactId').val(id);
	//var vehicleId = $('#vehicleId').val();
	/*  */
	$.ajax({ url: '{{url("get-owner-contact-change")}}',
		data: {'owenerContactId':id},
		success: function(response){
			
			var Result = response.split(",");var str = '';
			Result.pop();
			console.log(Result);
			var res = Result[0].split('~~');
			$('#owenerContactId').val(res[0]);
			$('#owner_contact_mob').val(res[2]);
			$('#contact_name').val(res[1]);
			$('#owner_contact_email').val(res[3]);
		}
	});
	
 }

 function getLocation (ph){
	 if(ph!=''){
		$.ajax({ url: '{{url("send-latlong-link")}}',
			data: { 'phone':ph},
			success: function(data){
				//console.log(data);
				var value = data.split("@~~@");
				$('#sessionId').val(value[1]);				
				toastr.success(value[0]);
			}
		});
	 }else{
		toastr.info("Please enter mobile number");
		 document.getElementById("phoneNumber").focus();
	 }
	
 }

 
 </script>
<script type="text/javascript">
	var lat='';
	function my_function_old(){
		var phone = phoneNumber.value;
		
		if(phone!=''){
			var sessionId = ($('#sessionId').val())!=''?$('#sessionId').val():0;
			$.ajax({ url: '{{url("get-latlong-map")}}',
				data: { 'phone':phone,'sessionId':sessionId},
				success: function(response){
					var res = response.split('~~');
					lat = res[0];
					$('#latValue').val(res[0]);
					$('#longValue').val(res[1]);
					$('#lat').prop('required',false);
 					$('#long').prop('required',false);
 					$('#latDiv').hide();
 					$('#longDiv').hide();
					var long = res[1];
					var myLatlng = new google.maps.LatLng(lat,long);
					const svgMarker = {
						path:
						"M10.453 14.016l6.563-6.609-1.406-1.406-5.156 5.203-2.063-2.109-1.406 1.406zM12 2.016q2.906 0 4.945 2.039t2.039 4.945q0 1.453-0.727 3.328t-1.758 3.516-2.039 3.070-1.711 2.273l-0.75 0.797q-0.281-0.328-0.75-0.867t-1.688-2.156-2.133-3.141-1.664-3.445-0.75-3.375q0-2.906 2.039-4.945t4.945-2.039z",
						fillColor: "blue",
						fillOpacity: 0.6,
						strokeWeight: 0,
						rotation: 0,
						scale: 2,
						anchor: new google.maps.Point(15, 30),
					};
					var myOptions = {
						zoom: 12,
						center: myLatlng,
						
						mapTypeId: google.maps.MapTypeId.ROADMAP
						}
					map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
					var marker = new google.maps.Marker({
						position: myLatlng, 
						map: map,
						"icon": svgMarker,
						title:"User Location"
					});
					addMarker(lat,long,'');
					getAssignDetails(lat,long,'');
				}
			});
		}
		
	}

	function manualGoogleMap_old(lat,long){
		if(lat !='' && long !=''){
			/* $('#latValue').val(lat);
			$('#longValue').val(long);
			$('#lat').prop('required',false);
			$('#long').prop('required',false);
			$('#latDiv').hide();
			$('#longDiv').hide(); */
			
			var myLatlng = new google.maps.LatLng(lat,long);
			const svgMarker = {
				path:
				"M10.453 14.016l6.563-6.609-1.406-1.406-5.156 5.203-2.063-2.109-1.406 1.406zM12 2.016q2.906 0 4.945 2.039t2.039 4.945q0 1.453-0.727 3.328t-1.758 3.516-2.039 3.070-1.711 2.273l-0.75 0.797q-0.281-0.328-0.75-0.867t-1.688-2.156-2.133-3.141-1.664-3.445-0.75-3.375q0-2.906 2.039-4.945t4.945-2.039z",
				fillColor: "blue",
				fillOpacity: 0.6,
				strokeWeight: 0,
				rotation: 0,
				scale: 2,
				anchor: new google.maps.Point(15, 30),
			};
			var myOptions = {
				zoom: 12,
				center: myLatlng,
				
				mapTypeId: google.maps.MapTypeId.ROADMAP
				}
			map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
			var marker = new google.maps.Marker({
				position: myLatlng, 
				map: map,
				"icon": svgMarker,
				title:"User Location"
			});
			addMarker(lat,long,'');
			getAssignDetails(lat,long,'');
		}else{
			toastr.info("Please fill lat and long fields")
		}
					
	}
	
	function addMarker(lat,long,el) {
 		var infowindow = new google.maps.InfoWindow({});
 		var global_markers = []; 
 		$.ajax({ url: '{{url("get-nearest-latlong")}}',dataType: 'JSON',
 				data: { 'lat':lat,'long':long},
 				success: function(response){
 					markers = response;
 					for (var i = 0; i < markers.length; i++) {
 						// obtain the attribues of each marker
 						var latitude = parseFloat(markers[i].latitude);
 						var lng = parseFloat(markers[i].longitude);
 						var trailhead_name = markers[i].dealer_name;
 						var myLatlng = new google.maps.LatLng(latitude, lng);
 						/*********************************Distance Code**********************************************/
 						var distanceInMeters='';
						 lat = parseFloat(lat);						
						 long = parseFloat(long);						
 						if(lat != '' && latitude !='' ){
 							var distanceInMeters = google.maps.geometry.spherical.computeDistanceBetween(
 								new google.maps.LatLng({
 									lat: lat, 
 									lng: long
 								}),
 								new google.maps.LatLng({
 									lat: latitude, 
 									lng: lng
 								})
 							);
 						}
 						/*********************************Distance Code**********************************************/
 						var contentString = "<html><body><div><p><h2>" + trailhead_name + "</h2><br><span><b>Distance:</b> "+ Math.round(distanceInMeters * 0.001) +" Kms</span></p></div></body></html>";
 				
 						var marker = new google.maps.Marker({
 							position: myLatlng,
 							map: map,
 							title: "Coordinates: " + latitude + " , " + lng + " | Dealer name: " + trailhead_name
 						});
 				
 						marker['infowindow'] = contentString;
 						
 						global_markers[i] = marker;
 				
 						google.maps.event.addListener(global_markers[i], 'click', function() {
 							infowindow.setContent(this['infowindow']);
 							infowindow.open(map, this);
 						});
 					}
 
 				}
 			});
 		
 	}
	function getAssignDetails(lat,long,el){
		$.ajax({ url: '{{url("get-assign-details")}}',
				data: { 'lat':lat,'long':long},
				success: function(response){
					var Result = response.split(",");var str = '';
					Result.pop();
					var custIds = new Array(el.trim());
				 	var selectedIds = custIds.join(',').split(',');
					 str += "<option value='NA'>--Select--</option>";
					for (item1 in Result) {
					 var Result2 = Result[item1].split("~~");
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
				document.getElementById('assign_to').innerHTML = str;
				}
			});
			
	}
	function getAssignDetailsManually(zone,state){
		
		/* var zone = $('#zone').val();
		var state = $('#state').val();
		var city = $('#city').val(); */
		
		$.ajax({ url: '{{url("get-assign-details-manually")}}',
			data: { 'zone':zone,'state':state},
			success: function(response){
				var Result = response.split(",");var str = '';
				Result.pop();
				str += "<option value='NA'>--Select--</option>";
				for (item1 in Result) {
					var Result2 = Result[item1].split("~~");
					str += "<option value='" + Result2[0]+ "'>" + Result2[1] + "</option>";
				}
				document.getElementById('assign_to').innerHTML = str;
			}
		});
	}
	function getAssignMob(id){
		$.ajax({
			url: '{{url("get-assign-mob")}}',
			data: {'id':id},
			success: function(response){
				if(response == 'No' || response == 0){
					toastr.error("No Work Manager available");
					$('#dealer_mob_number').val('');
					$('#dealer_mob_number1').val('');
				}else{
					$('#dealer_mob_number').val(response);
					$('#dealer_mob_number1').val(response);
				}
			}
		});
		
	}
	function getAssignWorkManager(id,ell){
		$('#assign_work_manager_mobile').val('');
		$.ajax({
			url: '{{url("get-assign-workManager")}}',
			data: {'id':id},
			success: function(response){
				if(response == 'No' || response == 0){
					toastr.error("No Work Manager available");
					
				}else{
					var Result = response.split(",");var str = '';
					str += "<option value=''>--Select--</option>";
					Result.pop();
					var cnt=1;
					for (item1 in Result) {
						var Result2 = Result[item1].split("~~");
						if (ell!='') {
							if ( Result2[1] == ell ) {
									str += "<option value='" + Result2[1] + "' selected>"+cnt+' '+ Result2[1] + "</option>";
								} 
								else
								{
									str += "<option value='" + Result2[1] + "'>"+cnt+' ' + Result2[1] + "</option>";
								}
						}else{
							str += "<option value='" + Result2[1]+ "'>"+cnt+' ' + Result2[1] + "</option>";
						}
						cnt++;
					}
					document.getElementById('assign_work_manager').innerHTML = str;
				}
			}
		});
	}
	function getAssignWorkManagerMobile(username){
		if(username !=''){
			$.ajax({
				url: '{{url("get-assign-workManager-mobile")}}',
				type:'POST',
				data: {'username':username},
				success: function(response){
					if(response == 'No' || response == 0){
						toastr.error("No Work Manager Mobile available");
					}else{
						var mobileNum = response;
						var filter = /^\d*(?:\.\d{1,2})?$/;
						if (filter.test(mobileNum ) && mobileNum.length == 10) {
							$("#assign_work_manager_mobile").attr("readonly");
							$('#assign_work_manager_mobile').val(response);
							$('#assign_work_manager_mobile1').val(response);
							$('#folio-invalid').hide();
							$('#submit').show();
						}else{
							$("#assign_work_manager_mobile").removeAttr("readonly");
							$('#assign_work_manager_mobile').val(response);
							$('#assign_work_manager_mobile1').val(response);
							$('#folio-invalid').show();
							$('#submit').hide();
						}
					}
				}
			});
		}else{
			$('#assign_work_manager_mobile').val('');
		}
		
	}
	function checkWorkManagerMobile(){
		
        var mobileNum = $('#assign_work_manager_mobile').val();
		
        var filter = /^\d*(?:\.\d{1,2})?$/;
		if (filter.test(mobileNum ) && mobileNum.length == 10) {
			
			$("#assign_work_manager_mobile").attr("readonly");
			$('#assign_work_manager_mobile').val(mobileNum);
			$('#assign_work_manager_mobile1').val(mobileNum);
			$('#submit').show();
			$('#folio-invalid').hide();
		}else{
			$("#assign_work_manager_mobile").removeAttr("readonly");
			$('#assign_work_manager_mobile').val(mobileNum);
			$('#assign_work_manager_mobile1').val(mobileNum);
			$('#folio-invalid').show();
			$('#submit').hide();
		}
  	};
	
	function callerUpdate(vehicleId,ownerId,callerId,caller_type,caller_name,caller_contact,caller_contact_alt,caller_language)
	{
		if(ownerId == ''){
			toastr.info("Please save owner info");
			 $('#ownerIndication').show();
			 $('#owner_name').focus();
		}else{
			$('#ownerIndication').hide();
			if(caller_type !=''){
				if(caller_language != ''){
					$.ajax({ url: '{{url("caller-update")}}',
						data:{'vehicleId':vehicleId,'ownerId':ownerId,'callerId':callerId,'caller_type':caller_type, 'caller_name':caller_name,'caller_contact':caller_contact,'caller_contact_alt':caller_contact_alt,'caller_language':caller_language},
						success: function(response){
							var res =  response.split('~~');
							$('#callerId').val(res[0]);
							toastr.success(res[1]);
							$('#callerIndication').hide();
							$('#callerEdit').hide();
						}

					});
				}else{
					toastr.success("Please select Caller Language");
					$('#caller_language').focus();
				}
			}else{
				toastr.info("Please save caller info");
				$('#callerIndication').show();
				$('#caller_type').focus();
			}
		}
			
		
	}
	
	function vehicleUpdate(ownerId,reg_number1,chassis_number1,engine_number1,vehicle_model,vehicle_segment,purchase_date,add_blue_use,engine_emmission_type)
	{		
		if(reg_number1 !=''){
			if(ownerId != ''){
				var reg_number1 = $('#reg_number1').val();
				var chassis_number1 = $('#chassis_number1').val();
				var engine_number1 = $('#engine_number1').val();
				var vehicle_model = $('#vehicle_model').val();
				var vehicle_segment = $('#vehicle_segment').val();
				var purchase_date = $('#purchase_date').val();
				var add_blue_use = $('#add_blue_use').val();
				var engine_emmission_type = $('#engine_emmission_type').val();
				
				if(reg_number1 == ''){
					toastr.info("Please fill registration number");		
				}else if(vehicle_model == ''){
					toastr.info("Please fill vehicle model");
				}else if(vehicle_segment == ''){
					toastr.info("Please fill vehicle segment");
				}else if(purchase_date == ''){
					toastr.info("Please fill purchase date");
				}else if(add_blue_use == ''){
					toastr.info("Please fill add blue use");
				}else if(engine_emmission_type == ''){
					toastr.info("Please fill engine emmission type");
				}else{
					$.ajax({ url: '{{url("vehicle-update")}}',
						data:{'reg_number1':reg_number1,'chassis_number1':chassis_number1, 'engine_number1':engine_number1,'vehicle_model':vehicle_model, 'vehicle_segment':vehicle_segment, 'purchase_date':purchase_date, 'add_blue_use':add_blue_use, 'engine_emmission_type':engine_emmission_type,'owner_id':ownerId},
						success: function(response){
							var res =  response.split('~~');
							$('#vehicleId').val(res[0]);
							toastr.success(res[1]);
							$('#vehicleIndication').hide();
							$('#vehicleEdit').hide();
						}
					});
				}
			}else{
				toastr.info("Please Fill owner details");
				$('#ownerIndication').show();
				$('#ownerIndication').focus();
			}
		}else{
			toastr.info("Please save vehicle info");
			 $('#vehicleIndication').show();
			 $('#reg_number1').focus();
		}
	}
	function ownerUpdate(owner_name_text,owner_mob,owner_company)
	{		
		if(owner_name_text !=''){
			$.ajax({ url: '{{url("owner-update")}}',
				data:{'owner_name':owner_name_text,'owner_mob':owner_mob, 'owner_landline':"NA",'owner_cat':"NA", 'owner_company':owner_name_text},
				success: function(response){
					var res =  response.split('~~');
					$('#ownerId').val(res[0]);
					toastr.success(res[1]);
					$('#ownerIndication').hide();
					$('#ownerEdit').hide();
				}

			});
		}else{
			toastr.info("Please save owner info");
			 $('#ownerIndication').show();
			 $('#owner_name').focus();
		}
	}
	function ownerContactUpdate(vehicleId,ownerId,owenerContactId,owner_contact_mob,owner_contact_email)
	{	
		var contactPersonName = $('#contact_name_text').val();
		if(contactPersonName !=''){
			
			$.ajax({ url: '{{url("owner-contact-update")}}',
				data:{'vehicleId':vehicleId,'ownerId':ownerId,'owenerContactId':owenerContactId,'contact_name':contactPersonName,'owner_contact_mob':owner_contact_mob,'owner_contact_email':owner_contact_email},
				success: function(response){
					var res =  response.split('~~');
					$('#owenerContactId').val(res[0]);
					toastr.success(res[1]);
					$('#contactIndication').hide();
					$('#ownerContactEdit').hide();
				}
			});
		}else{
			toastr.info("Please save owner contact info");
			 $('#contactIndication').show();
			 $('#contact_name_text').focus();
		}
		
	}
	function funcVehicleModel(param){
		$.ajax({
			url: '{{url("get-vehicle-models")}}',
			data: {'id': param},
			success: function(result){
				var res = result.split('~~');
				var vehicle_segment = res[0];
				var add_blue_use = res[1];
				var engine_emmission_type = res[2];
				$('#vehicle_segment').val(vehicle_segment);
				$('#add_blue_use').val(add_blue_use);
				$('#engine_emmission_type').val(engine_emmission_type);
				$('#vehicle_segment').attr('readonly','readonly');
				$('#add_blue_use').attr('readonly','readonly');
				$('#engine_emmission_type').attr('readonly','readonly');

			}
		})
	}
	function resetForm(){
		$('#myForm').trigger("reset");
		$('#myForm1').trigger("reset");
		$('#assign_to').val('');
	}
	
	</script>
<script>
    // Initialize and add the map
    var map;

	function my_function(){
		var phone = phoneNumber.value;
		
		if(phone!=''){
			var sessionId = ($('#sessionId').val())!=''?$('#sessionId').val():0;
			$.ajax({ url: '{{url("get-latlong-map")}}',
				data: { 'phone':phone,'sessionId':sessionId},
				success: function(response){

					console.log("Mobile",response);
					var res = response.split('~~');
					lat = res[0];
					$('#latValue').val(res[0]);
					$('#longValue').val(res[1]);

					
					$('#lat').prop('required',false);
 					$('#long').prop('required',false);
 					$('#latDiv').hide();
 					$('#longDiv').hide();
					manualGoogleMap(res[0],res[1]);

				}
			});
		}
	}

    function manualGoogleMap(lat, long) {
        var lat = parseFloat(lat);
        var long = parseFloat(long);
        //var lat = parseFloat("28.523244");
        //var long = parseFloat("77.44458");
        //alert(lat + '&' + long);
        if (isNaN(lat) && isNaN(long)) {
            //$('#showMap').hide();
        } else {
           // $('#showMap').show();

            //
            var myLatlng = new google.maps.LatLng(lat, long);

            var myOptions = {
                zoom: 8,
                center: myLatlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
            };
            map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
            var marker = new google.maps.Marker({
                position: myLatlng,
                map: map,
                icon: {
                    // url: 'https://webcrm.cogentlab.com/dicv/public/images/bb_user.png'
                    url: '{{asset('images/al_user.png')}}'
                },
                title: "User Location",
            });
            addMarker(lat, long, "");
            //getAssignDetails(lat,long,'');
        }
    }

	async function calCulateDistance(fromloc, toloc) {

        let directionsService = new google.maps.DirectionsService();
       
        const route = {
            origin: fromloc,
            destination: toloc,
            travelMode: 'DRIVING'
        }

        var distanceInMetersN = "100 KM";

        var getDis =  await directionsService.route(route,
            function(response, status) { // anonymous function to capture directions
                if (status !== 'OK') {
                    // window.alert('Directions request failed due to ' + status);
                    return;
                } else {
                    // directionsRenderer.setDirections(response); // Add route to the map
                    var directionsData = response.routes[0].legs[0]; // Get data about the mapped route
                    if (!directionsData) {
                        // window.alert('Directions request failed');
                        return;
                    } else {
                        // document.getElementById('msg').innerHTML += " Driving distance is " + directionsData.distance.text + " (" + directionsData.duration.text + ").";

                        var distanceInMetersN = directionsData.distance.text;
                        console.log("Actual Distance=>", distanceInMetersN);

                        // $('#distance').html(distanceInMetersN);
                    }
                }
            });
        var directionsDataN = getDis.routes[0].legs[0];

        var distanceInMetersN = directionsDataN.distance.text + ' (' + directionsDataN.duration.text + ')';
        console.log("GET DISTANCE", directionsDataN.distance.text);
        return distanceInMetersN;


    }

    function calcDistance(p1, p2) {
        return (google.maps.geometry.spherical.computeDistanceBetween(p1, p2) / 1000).toFixed(2);
    }
	function callBackAjax(res){

		return res;

	}
	
	
	function getMarkerValue(lat,long){
		
		 let ajaxOption=$.ajax({ url: '{{url("get-nearest-latlong")}}',dataType: 'JSON',
 				data: { 'lat':lat,'long':long},async:false,
 				success: function(response){
					// var result = Object.keys(response).map((key) => [key, response[key]]);
					//arr1.concat(arr2);
					//console.log(response);
					//mval.push(result);				
				}
				
		});
		//console.log("mval",JSON.stringify(ajaxOption.responseJSON));
		// return mval;
		return JSON.stringify(ajaxOption.responseJSON);
	}
     async function addMarker(lat, long, el) {

        const fromloc = {
            lat: lat,
            lng: long
        };

        // console.log("FROM=>", fromloc);
        var infowindow = new google.maps.InfoWindow({});

	
 		var global_markers = []; 
		 //getMarkerValue(lat,long);
		

				markers1 = getMarkerValue(lat,long);	
				markers = JSON.parse(markers1);	
				console.log("markers",markers);
				var distanceInMetersN = "";
				console.log("markers",typeof markers);
				for (var i = 0; i < markers.length; i++) {

					
					var latitude = parseFloat(markers[i].latitude);
					var lng = parseFloat(markers[i].longitude);

					

					if (lat != "NaN" && latitude != "NaN" && lng != "NaN") {
						// obtain the attribues of each marker
						var trailhead_name = markers[i].dealer_name;
						
						var myLatlng = new google.maps.LatLng(latitude, lng);
						/*********************************Distance Code**********************************************/
						var distanceInMeters = "";
						var dis = "";
						const toloc = {
							lat: latitude,
							lng: lng
						};
						console.log("From=>", fromloc);
						console.log("TO=>", toloc);

						var dc = calcDistance(fromloc, toloc);

						var dcp = parseInt(dc);

						 console.log("SHOW ACTUAL DISTANCE=>", dcp);

						if (dcp < 250000) {
							// callMapDetails();
							// var contentString = callMapDetails();
							// async function callMapDetails(){	
								
									var  dis = await calCulateDistance(fromloc, toloc);
									// alert(result);
									// return result;
								
											
								// dis =  await calCulateDistance(fromloc, toloc);							
								console.log("Distance", dis);
								/*********************************Distance Code**********************************************/
									var contentString = "<html><body><div><p><h2>" + trailhead_name + "</h2><br><span><b>Distance:</b> "+ dis +"</span></p></div></body></html>";									
									// return contentString1;
							// }


								// console.log("detaisl:",contentString);
								var marker = new google.maps.Marker({
									position: myLatlng,
									map: map,
									icon: {
										// url: 'https://webcrm.cogentlab.com/dicv/public/images/bb_icon.jpg'
										url: '{{asset('images/favicon.png')}}'
									},
									title: "Coordinates: " +
										latitude +
										" , " +
										lng +
										" | Dealer name: " +
										trailhead_name,
								});

								marker["infowindow"] = contentString;

								global_markers[i] = marker;

								google.maps.event.addListener(
									global_markers[i],
									"click",
									function() {
										// callFun();
										infowindow.setContent(this["infowindow"]);
										infowindow.open(map, this);
									}
								);
								
							
							
						}
					}
				
				}
		
		
			
	
	
    }
</script>
@endsection
