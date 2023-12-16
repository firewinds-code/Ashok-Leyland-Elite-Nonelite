@extends("layouts.masterlayout")
@section('title','Create Ticket')
@section('bodycontent')

<div class="content-wrapper mobcss">
	<div class="card">
		<div class="card-body">
                <h4 class="card-title">Create Ticket</h4>
			<div class="row">
				<div class="col-md-8" style="border: 1px solid #ccc">
					<div class="ribbon">Vehicle Search</div>
					<form name="myForm" id="myForm1" method="post" enctype="multipart/form-data">
						<input type="hidden" name="_token" value="{{csrf_token()}}">
						<div class="row" style="margin-bottom: 10px;">
							<div class="form-group col-md-3">
								<label for="datefrom">Registration Number</label>
								<input type="text" name="reg_number" id="reg_number" class="form-control" placeholder="Registration Number" value="{{ $vehicle_Data !='No' && !empty($vehicle_Data)?$vehicle_Data[0]->reg_number:'' }}" />
								<span id="reg_number_error" style="color:red"></span>
							</div>
							<div class="form-group col-md-3">
								<label for="datefrom">Chassis Number</label>
								<input type="text" name="chassis_number" id="chassis_number" class="form-control" placeholder="Chassis Number" value="{{ $vehicle_Data !='No' && !empty($vehicle_Data)?$vehicle_Data[0] ->chassis_number:''}}"/>
								<span id="chassis_number_error" style="color:red"></span>
							</div>
							<div class="form-group col-md-3">
								<label for="datefrom">Engine Number</label>
								<input type="text" name="engine_number" id="engine_number" class="form-control" placeholder="Engine Number"  value="{{ $vehicle_Data !='No' && !empty($vehicle_Data)?$vehicle_Data[0] ->engine_number:'' }}" />
								<span id="engine_number_error" style="color:red"></span>
							</div>

							<div class="form-group col-md-3">
								<a class="btn-secondary" onclick="getData(reg_number.value,chassis_number.value,engine_number.value);" style="color: #fff;padding: 5px;border-radius: 10px;position: relative;top: 30px;">Search</a>
								<a class="btn-secondary" onclick="reloadPage();" style="color: #fff;padding: 5px;border-radius: 10px;position: relative;top: 30px;">Reset</a>
							</div>
						</div>
					</form>
					<hr>
					<div class="ribbon">Vehicle Details</div>
					<form name="myForm" id="myForm" method="post" enctype="multipart/form-data" action="{{url('ticket-creation-data')}}">
						<input type="hidden" name="_token" value="{{csrf_token()}}">
						<input type="hidden" name="vehicleId" id="vehicleId">
						<input type="hidden" name="ownerId" id="ownerId">
						<input type="hidden" name="owenerContactId" id="owenerContactId">
						<input type="hidden" name="callerId" id="callerId">
							<input type="hidden" name="latValue" id="latValue">
							<input type="hidden" name="longValue" id="longValue">
						<div class="row">
							<div class="form-group col-md-3">
								<label for="datefrom">Registration Number</label>
								<span style="color: red;">*</span>
									<input type="text" name="reg_number1" id="reg_number1" class="form-control"  placeholder="Registration Number" required/>
								<span id="reg_number1_error" style="color:red"></span>
							</div>
							<div class="form-group col-md-3">
								<label for="datefrom">Chassis Number</label>
								<span style="color: red;">*</span>
									<input type="text" name="chassis_number1" id="chassis_number1" class="form-control"  placeholder="Chassis Number" required/>
								<span id="chassis_number1_error" style="color:red"></span>
							</div>
							<div class="form-group col-md-3">
								<label for="datefrom">Engine Number</label>
								<span style="color: red;">*</span>
									<input type="text" name="engine_number1" id="engine_number1" class="form-control"  placeholder="Engine Number" required/>
								<span id="engine_number1_error" style="color:red"></span>
							</div>
							<div class="form-group col-md-3">
								<label for="datefrom">Vehicle Model</label>
								<span style="color: red;">*</span>
									<input type="text" name="vehicle_model" id="vehicle_model" class="form-control" placeholder="Vehicle Model" required/>
									{{-- <select name="vehicle_model" id="vehicle_model" class="form-control" required onchange="funcVehicleModel(this.value)">
										<option value="">--Select--</option>
										@isset($vehicleModels)
											@foreach ($vehicleModels as $row)
												<option value="{{$row->id}}">{{$row->vehicle_model}}</option>
											@endforeach
										@endisset

									</select>  --}}
							</div>
							<div class="form-group col-md-3">
								<label for="datefrom">Vehicle Segment</label>
								<span style="color: red;">*</span>
									<input type="text" name="vehicle_segment" id="vehicle_segment" class="form-control"  placeholder="Vehicle Segment" required/>
							</div>
							<div class="form-group col-md-3">
								<label for="datefrom">Purchase Date</label>
								<input type="text" name="purchase_date" id="purchase_date" autocomplete="off" class="form-control" value="@isset($purchase_date){{$purchase_date}} @endisset" placeholder="Purchase Date" />
							</div>
							<div class="form-group col-md-3">
								<label for="datefrom">Add Blue Use</label>
								<span style="color: red;">*</span>
									<select name="add_blue_use" id="add_blue_use" class="form-control" onchange="addBlueUse(this.value)" required>
										<option value="">--Select--</option>
									<option value="Yes">Yes</option>
									<option value="No">No</option>
								</select>
							</div>
							<div class="form-group col-md-3">
								<label for="datefrom">Engine Emission Type</label>
								<span style="color: red;">*</span>
									<input type="text"  name="engine_emmission_type" id="engine_emmission_type"  class="form-control" required>
									{{-- <select name="engine_emmission_type" id="engine_emmission_type"  class="form-control" required>
										<option value="">--Select--</option>
										<option value="BS6">BS-6</option>
										<option value="Non BS6">Non BS-6</option>
									</select> --}}
									{{-- <input type="text" name="engine_emmission_type" id="engine_emmission_type" class="form-control"  placeholder="Engine Emission Type" required/> --}}
							</div>
						</div>
							
						<hr>
							<div class="ribbon" style="background: #ADD8E6 !important;">Owner Details</div>
						<div class="row">
							<div class="form-group col-md-3">
			                        <label for="owner_name" >Owner Company Name</label>
								<span style="color: red;">*</span>
									{{--<!-- <input type="text" name="owner_name" id="owner_name" class="form-control"  placeholder="Name" required/> -->--}}
									<select name="owner_name" id="owner_name" class="form-control"  placeholder="Name" onchange="ownerContactNameData(this.value)" required>
										<option value="">--Select--</option>
										@foreach($ownerData as $row)
											<option value="{{$row->id}}">{{$row->owner_name}}</option>
										@endforeach
									</select>
							</div>
							<div class="form-group col-md-3">
								<label for="owner_mob">Mobile Number</label>
									<input type="text" name="owner_mob" id="owner_mob" class="form-control"  placeholder="Mobile Number" maxlength="10" />
							</div>
							<div class="form-group col-md-3">
								<label for="owner_landline">Landline Number</label>
								<input type="text" name="owner_landline" id="owner_landline" class="form-control" placeholder="Landline Number" />
								<span id="engine_number_error" style="color:red"></span>
							</div>
							<div class="form-group col-md-3">
								<label for="owner_cat">Owner Category</label>
									<input type="text" name="owner_cat" id="owner_cat" class="form-control"  value="Select Customer" disabled/> 
									<input type="hidden" name="owner_cat" id="owner_cat" class="form-control"  value="Select Customer"/> 
							</div>
								{{-- <div class="form-group col-md-3">
			                        <label for="datefrom" >Owner Campany Name</label>
								<span style="color: red;">*</span>
									<input type="text" name="owner_company" id="owner_company" class="form-control"  placeholder="Campany Name" required/>
			                    </div> --}}
								<div class="form-group col-md-3">
			                        <label for="alse_mail" >ALSE / ASM Email</label>
									<span style="color: red;">*</span>
									<input type="email" name="alse_mail" id="alse_mail" class="form-control"  placeholder="ALSE Email" required/>
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="asm_mail" >RSM Email</label>
									<span style="color: red;">*</span>
									<input type="email" name="asm_mail" id="asm_mail" class="form-control"  placeholder="ASM Email" required/>
			                    </div>
						</div>
						<div class="row">
							<div class="container-fluid">
								<div class="col-sm-12 text-center">
										<a class="btn-secondary" id="vehicleEdit" onclick="vehicleUpdate(vehicleId.value,reg_number1.value,chassis_number1.value,engine_number1.value,vehicle_model.value,vehicle_segment.value,purchase_date.value,add_blue_use.value,engine_emmission_type.value);" style="display: none; color: #fff;padding: 5px;border-radius: 10px;position: relative;top: 10px;">Save</a>
									</div>
								 </div>
							</div>
							{{-- <div class="row" >
								<div class="container-fluid">
									<div class="col-sm-12 text-center">
										<a class="btn-secondary" id="ownerEdit" onclick="ownerUpdate(vehicleId.value,ownerId.value,owner_name.value,owner_mob.value,owner_landline.value,owner_cat.value,owner_company.value,alse_mail.value,asm_mail.value);" style="display: none; color: #fff;padding: 5px;border-radius: 10px;position: relative;top: 10px;">Save</a>
								</div>
							</div>
							</div> --}}
						<hr>
						<div class="ribbon">Contact Person Details</div>
						<div class="row">
							<div class="form-group col-md-3">
								<label for="contact_name">Contact Person</label>
								<span style="color: red;">*</span>
									{{-- <input type="text" name="contact_name" id="contact_name" class="form-control"  placeholder="Contact Person" required/> --}}
									<select name="contact_name" id="contact_name_select" class="form-control" onchange="contactNameData(this.value)" required>
										<option value="">--Select--</option>
										@foreach($ownerContactData as $row)
											<option value="{{$row->id}}">{{$row->contact_name}}</option>
										@endforeach
									</select> 
							</div>
							<div class="form-group col-md-3">
								<label for="datefrom">Phone Number</label>
								<span style="color: red;">*</span>
									<input type="tel" name="owner_contact_mob" id="owner_contact_mob" class="form-control"  placeholder="Phone Number" maxlength="10" pattern="[0-9]{10}" required/>
								<span id="engine_number_error" style="color:red"></span>
							</div>
								<div class="form-group col-md-3">
			                        <label for="datefrom" >Email</label>
									<span style="color: red;">*</span>
									<input type="email" name="owner_contact_email" id="owner_contact_email" class="form-control"  placeholder="Email"  required/>
			                    </div>
						</div>
						<div class="row">
							<div class="container-fluid">
								<div class="col-sm-12 text-center">
										<a class="btn-secondary" id="ownerContactEdit" onclick="ownerContactUpdate(vehicleId.value,ownerId.value,owenerContactId.value,contact_name.value,owner_contact_mob.value,owner_contact_email.value);" style="display: none; color: #fff;padding: 5px;border-radius: 10px;position: relative;top: 10px;">Save</a>
								</div>
							</div>
						</div>
						<hr>
						<div class="ribbon">Caller Info</div>
						<div class="row">
							<div class="form-group col-md-3">
								<label for="datefrom">Caller Type</label>
									<select name="caller_type" id="caller_type" class="form-control" required>
										<option value="">--Select--</option>
									<option value="Driver">Driver</option>
									<option value="Owner">Owner</option>
									<option value="Owner cum driver">Owner cum driver</option>
										<option value="Fleet Manager">Fleet Manager</option>
										{{-- <option value="AL representative">AL representative</option>
										<option value="Passer By">Passer By</option>
										<option value="Existing cutomer">Existing cutomer</option>
										<option value="Potential customer">Potential customer</option>
										<option value="Spare parts retailer">Spare parts retailer</option>
										<option value="Support executive">Support executive</option>
										<option value="AL executive">AL executive</option>
										<option value="AL select">AL select</option> --}}
								</select>
							</div>
							<div class="form-group col-md-3">
								<label for="datefrom">Caller Name</label>
								<span style="color: red;">*</span>
									<input type="text" name="caller_name" id="caller_name" class="form-control"  placeholder="Caller Name" required/>
							</div>
							<div class="form-group col-md-3">
								<label for="datefrom">Caller Contact Number</label>
								<span style="color: red;">*</span>
									<input type="tel" name="caller_contact" id="caller_contact" class="form-control"  placeholder="Caller Contact Number" maxlength="10" pattern="[0-9]{10}" required/>
							</div>
								{{-- <div class="form-group col-md-3">
								<label for="">Location</label>
								<span style="color: red;">*</span>
									<input type="text" name="caller_location" id="caller_location" class="form-control"  placeholder="Location"  required/> 
							</div>
							<div class="form-group col-md-3">
								<label for="caller_landmark">Landmark</label>
								<span style="color: red;">*</span>
									<input type="text" name="caller_landmark" id="caller_landmark" class="form-control"  placeholder="Landmark"  required/>
			                    </div> --}}
								
								{{-- <div class="form-group col-md-3">
								<label for="zone">Zone</label>
								<span style="color: red;">*</span>
									<select name="zone" id="zone" class="form-control" onchange="fn_zone_change(this.value,'')" required>
									<option value="">--Select--</option>
									@isset($region)
									@foreach ($region as $row)
									<option value="{{$row->id}}">{{$row->region}}</option>
									@endforeach
									@endisset
								</select>
							</div>
							<div class="form-group col-md-3">
								<label for="state">Region</label>
								<span style="color: red;">*</span>
									<select name="state" id="state" class="form-control" onchange="Dealer_State_change(zone.value,this.value,'')" onclick="getAssignDetailsManually(zone.value,this.value);" required>
									<option value="">--Select--</option>
								</select>
							</div>
							<div class="form-group col-md-3">
								<label for="city">Area</label>
								<span style="color: red;">*</span>
									<select class="form-control" required>
									<option value="">--Select--</option>
								</select>
								</div> --}}
						</div>
						<div class="row">
							<div class="container-fluid">
								<div class="col-sm-12 text-center">
										<a class="btn-secondary" id="callerEdit" onclick="callerUpdate(vehicleId.value,ownerId.value,callerId.value,caller_type.value,caller_name.value,caller_contact.value);" style="display: none; color: #fff;padding: 5px;border-radius: 10px;position: relative;top: 10px;">Save</a>
								</div>
							</div>
						</div>
						<div style="clear: both;margin: 10px"></div>
						<hr>
						<div class="ribbon">Vehicle Breakdown Details</div>
						<div class="row">
							<div class="form-group col-md-3">
								<label for="">From Where</label>
								<span style="color: red;">*</span>
									<input type="text" name="from_where" id="from_where" class="form-control"  placeholder="From Where" required/>
							</div>
							<div class="form-group col-md-3">
								<label for="">To Where</label>
								<span style="color: red;">*</span>
									<input type="text" name="to_where" id="to_where" class="form-control"  placeholder="To Where" required/>
							</div>
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
								<label for="city" >City</label>
								<span style="color: red;">*</span>
								<select id="cityCaller" name="city" class="form-control" required>
									<option value="">--Select--</option>
								</select>
							</div>
							<div class="form-group col-md-3">
								<label for="" >District</label>
								<span style="color: red;">*</span>
								<input type="text" name="district" id="district" class="form-control"  placeholder="District"  required/> 
							</div>
							<div class="form-group col-md-3">
							<label for="">Highway</label>
								<span style="color: red;">*</span>
								<input type="text" name="highway" id="highway" class="form-control"  placeholder="Highway"  required/> 
								{{-- <select name="highway" id="highway" class="form-control" required>
								<option value="NA">--Select--</option>
								<option value="Highway">Highway</option>
								<option value="Not a highway">Not a highway</option>
								<option value="Name of the Highway (NH2,NH6,NH10 etc).">Name of the Highway (NH2,NH6,NH10 etc).</option>
								</select>  --}} 
						</div>
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
									<option value="Accident Ticket">Accident Ticket</option>
									<option value="Breakdown Ticket">Breakdown Ticket</option>
									<option value="Vehicle in workshop">Vehicle in workshop</option>
							</select>
						</div>
						<div class="form-group col-md-3">
							<label for="Aggregate">Aggregate</label>
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
							<div class="form-group col-md-9">
							<label for="datefrom">Vehicle Problem (max 150 chars)<sup style="color: red;">*</sup></label>
								<textarea name="vehicle_problem" id="" cols="30" rows="5" class="form-control" required></textarea>
							<span id="engine_number_error" style="color:red"></span>
						</div>
					</div>

						<hr>
						<div class="ribbon">Vehicle Breakdown Ticket Details</div>
						<div class="row">
								
							<div class="form-group col-md-3">
								<label for="datefrom">Ticket Assign To</label>
										<select name="assign_to" id="assign_to" class="form-control" onchange="getAssignMob(this.value)" required>
									<option value="">--Select--</option>
								</select>
							</div>
							<div class="form-group col-md-3">
								<label for="datefrom">Mobile Number</label>
								<span style="color: red;">*</span>
										<input type="tel" name="dealer_mob_number" id="dealer_mob_number" class="form-control"  placeholder="Mobile Number" maxlength="10" pattern="[0-9]{10}" required/>
							</div>
							<div class="form-group col-md-3">
								<label for="datefrom">Alt. Contact Number</label>
								<input type="text" name="dealer_alt_mob_number" id="dealer_alt_mob_number" class="form-control" placeholder="Alt. Contact Number" maxlength="10" />
							</div>
								
							<div class="form-group col-md-3">
										<label for="remark_type" >Ticket Status</label><span style="color: red;">*</span>
										<select name="remark_type" id="remark_type" class="form-control" required>
											<option value="">--Select--</option>
										@isset($remark_type)
											@foreach ($remark_type as $row)
													@if(Session::get('role') == '29' || Session::get('role') == '30' || Session::get('role') == '87')
														<option value="{{$row->type}}">{{$row->type}}</option>
													@else
													@php $tictStatus = array(32,33,34,35,36); @endphp
														@if(!in_array($row->id,$tictStatus))
												<option value="{{$row->type}}">{{$row->type}}</option>
														@endif
													@endif
											@endforeach
											
										@endisset
										{{-- <option value="Arranging Parts Locally">Arranging Parts Locally</option>
									<option value="Awaiting parts from AL">Awaiting parts from AL</option>
									<option value="Awaiting AL Approval">Awaiting AL Approval</option>
									<option value="Awaiting completion from Ancillary suppliers">Awaiting completion from Ancillary suppliers</option>
									<option value="Awaiting completion of contracted Job">Awaiting completion of contracted Job</option>
									<option value="Awaiting customer approval">Awaiting customer approval</option>
									<option value="Awaiting customer Payment">Awaiting customer Payment</option>
									<option value="Awaiting Good will Approval">Awaiting Good will Approval</option>
									<option value="Awaiting parts from another dealer branch">Awaiting parts from another dealer branch</option>
									<option value="Awaiting parts from customer">Awaiting parts from customer</option>
									<option value="Dealer Feedback">Dealer Feedback</option>
									<option value="Investigation in progress">Investigation in progress</option>
									<option value="Load transfer in progress">Load transfer in progress</option>
									<option value="Man power not available">Man power not available</option>
									<option value="Mechanic left to BD spot">Mechanic left to BD spot</option>
									<option value="Mechanic reached BD spot">Mechanic reached BD spot</option>
									<option value="Moved to another vehicle on urgency">Moved to another vehicle on urgency</option>
									<option value="Public Holiday">Public Holiday</option>
									<option value="Reassigned support">Reassigned support</option>
									<option value="Response Delay">Response Delay</option>
									<option value="Response not Initiated">Response not Initiated</option>
									<option value="Restored by Self">Restored by Self</option>
									<option value="Restored by Unknown support">Restored by Unknown support</option>
									<option value="Restored by Support">Restored by Support</option>
									<option value="Vehicle being Towed">Vehicle being Towed</option>
									<option value="Vehicle reached support point">Vehicle reached support point</option>
									<option value="Work held up due to bandh">Work held up due to bandh</option>
									<option value="Work held up due to injury/accident">Work held up due to injury/accident</option>
									<option value="Work in progress">Work in progress</option>
									<option value="Workshop closed - Sunday">Workshop closed - Sunday</option>

										<option value="Work Completed">Work Completed</option>
										<option value="Customer Confirmation Due">Customer Confirmation Due</option>
										<option value="Customer Confirmation Completed">Customer Confirmation Completed</option>
										<option value="Customer Feedback">Customer Feedback</option>
										<option value="Ticket Closed">Ticket Closed</option> --}}
								</select>
							</div>
								
							<div class="form-group col-md-3">
								<label for="datefrom">Disposition</label>
								<select name="disposition" id="disposition" class="form-control">
									<option value="NA">--Select--</option>
									<option value="RNR">RNR</option>
									<option value="Callback">Callback</option>
									<option value="Line Busy">Line Busy</option>
									<option value="Switched Off">Switched Off</option>
									<option value="Not Reachable">Not Reachable</option>
									<option value="Status Collected">Status Collected</option>
								</select>
							</div>
							<div class="form-group col-md-3">
										<label for="datefrom" >Agent Remarks</label><span style="color: red;">*</span>
										<select name="agent_remark" id="agent_remark" class="form-control" required>
											<option value="">--Select--</option>
									<option value="Incorrect response and restoration">Incorrect response and restoration</option>
									<option value="Incorrect ticket closure/ticket reopen">Incorrect ticket closure/ticket reopen</option>
									<option value="NO error">NO error</option>

								</select>
							</div>
									
									<div class="form-group col-md-3">
										<label for="datefrom" >Est. Response Time</label>
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
								<label for="datefrom">Acceptance</label>
								<div class="radio">
									<label><input type="radio" name="acceptance" value="1">Yes</label>
									<label><input type="radio" name="acceptance" value="0">No</label>
								</div>

							</div>
								<div class="form-group col-md-9">
			                        <label for="datefrom" >Standard Remarks</label>
									<textarea name="standard_remark" id="standard_remark" cols="20" rows="3" class="form-control"></textarea>
			                    </div>
						</div>
						<div class="row">
								<div class="form-group col-md-12">
								<label for="datefrom">Remarks (Max 500 Chars)<sup style="color: red;">*</sup></label>
								<textarea name="assign_remarks" id="assign_remarks" cols="30" rows="5" class="form-control"></textarea>
								<span id="engine_number_error" style="color:red"></span>
							</div>
						</div>
						<br />
						<div class="row" style="margin-bottom: 10px;">
							<div class="container-fluid">
								<div class="col-sm-12 text-center">
									<input type="submit" class="btn btn-primary rounded" name="submit" value="Submit" />

								</div>
							</div>
						</div>
					</form>
				</div>
					<div class="col-md-4" style="border: 1px solid #ccc">
					<div class="row">
						<div class="form-group col-md-6">
							<label for="">Send Link</label>
							<input type="text" name="phoneNumber" id="phoneNumber" class="form-control" maxlength="10" placeholder="Phone Number" />
							<input type="hidden" name="sessionId" id="sessionId" />
						</div>
						<div class="form-group col-md-6">
							<label for=""></label>
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
	function fn_zone_change(zoneId, ell) {
		$.ajax({ url: '{{url("get-zone-change")}}',
				data: { 'zoneId':zoneId},
			success: function(response) {

					var Result = response.split(",");var str = '';
				Result.pop();
				str += "<option value='NA'>--Select--</option>";
				for (item1 in Result) {
					var Result2 = Result[item1].split("~~");
					if (ell != '') {
						if (Result2[0] == ell) {
							str += "<option value='" + Result2[0] + "' selected>" + Result2[1] + "</option>";
							} 
							else
							{
							str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
						}
					} else {
						str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
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
			success: function(data) {
		var Result = data.split(",");var str = '';
				Result.pop();
				for (item in Result) {
					Result2 = Result[item].split("~");
					var value = elll.split(",");
					if (elll != '') {
						if (jQuery.inArray(Result2[0], value) != '-1') {
							str += "<option value='" + Result2[0] + "' selected>" + Result2[1] + "</option>";
						} else {
							str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
						}
					} else {
						str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
					}
				}
				document.getElementById('city').innerHTML = str;
		}});
	}
	$(document).ready(function() {
		var today = new Date();
 		var options = {
 			maxDate: new Date(),
 			maxTime: new Date(),
 			format: 'Y-m-d H:i:s',
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
		$('#purchase_date').datetimepicker({ maxDate: 0,format:'Y-m-d',timepicker:false});
		$('#estimated_response_time').datetimepicker({format:'Y-m-d H:i:s'});
		/* $('#actual_response_time').datetimepicker({maxDate: 0,maxTime: 0,format:'Y-m-d H:i:s'});
		$('#tat_scheduled').datetimepicker({maxDate: 0,maxTime: 0,format:'Y-m-d H:i:s'}); */
		$('#actual_response_time').datetimepicker(options);
		$('#tat_scheduled').datetimepicker(options);
	});
	function fn_state_change(stateId, ell) {
		$.ajax({ url: '{{url("get-stateChange")}}',
				data: { 'stateId':stateId},
			success: function(response) {
					var Result = response.split(",");var str = '';
				Result.pop();
				str += "<option value='NA'>--Select--</option>";
				for (item1 in Result) {
					var Result2 = Result[item1].split("~~");
					if (ell != '') {
						if (Result2[0] == ell) {
							str += "<option value='" + Result2[0] + "' selected>" + Result2[1] + "</option>";
							} 
							else
							{
							str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
						}
					} else {
						str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
					}
				}
				document.getElementById('district').innerHTML = str;
			}
		});
	}
	function fn_district_change(el, ell, elll) {
		$.ajax({ url: '{{url("get-city")}}',
			data: { 's_id':el,'d_id':ell },
			success: function(data) {
				var Result = data.split(",");var str = '';
				Result.pop();
				str += "<option value='NA' selected>--Select--</option>";
				for (item in Result) {
					Result2 = Result[item].split("~");
					if (elll != '') {
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
		console.log(reg_number);
		$.ajax({ url: '{{url("get-vehicle-details")}}',
			data: { 'reg_number':reg_number,'chassis_number':chassis_number,'engine_number':engine_number},
			success: function(data){
				console.log(data);
				if(data =='no'){
					alert("There is no Vehicle");
					$('#callerEdit').show();
					$('#vehicleEdit').show();
					
				}else{
					$('#callerEdit').show();
					$('#vehicleEdit').show();
					var res = data.split('~~');
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
					$('#owner_name').val(res[10]);
					var ownerName = res[11];
					var ownerId = res[10];
					var contact_name = res[29];
					$('#owner_mob').val(res[12]);
					$('#owner_landline').val(res[13]);
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
 function getData123(reg_number,chassis_number,engine_number){
		//
		console.log(reg_number);
		$.ajax({ url: '{{url("get-vehicle-details")}}',
			data: { 'reg_number':reg_number,'chassis_number':chassis_number,'engine_number':engine_number},
			success: function(data) {
				console.log(data);
				if (data == 'no') {
					alert("There is no Vehicle");
					$('#callerEdit').show();
					$('#vehicleEdit').show();
					//$('#ownerEdit').show();
					//$('#ownerContactEdit').show();
					/* document.getElementById('callerEdit').innerHTML = 'Insert';
					document.getElementById('vehicleEdit').innerHTML = 'Insert';
					document.getElementById('ownerEdit').innerHTML = 'Insert'; */
				} else {
					$('#callerEdit').show();
					$('#vehicleEdit').show();
					//$('#ownerEdit').show();
					//$('#ownerContactEdit').show();

					/* document.getElementById('callerEdit').innerHTML = 'Edit';
					document.getElementById('vehicleEdit').innerHTML = 'Edit';
					document.getElementById('ownerEdit').innerHTML = 'Edit'; */
					var res = data.split('~~');
					$('#vehicleId').val(res[0]);


					//fn_zone_change(res[26],res[27]);
					//Dealer_State_change(res[26],res[27],res[28]);

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
					$('#owner_name').val(res[10]);
					//funcVehicleModel(res[1]);



					var ownerName = res[11];
					var ownerId = res[10];
					var contact_name = res[29];
					//$('#owner_name').val(res[11]);
						$('#owner_mob').val(res[12]);
						$('#owner_landline').val(res[13]);
						//$('#owner_cat').val(res[14]);
						$('#owner_company').val(res[15]);
						$('#ownerId').val(res[10]);
						$('#alse_mail').val(res[31]);
						$('#asm_mail').val(res[32]);
						//$('#callerId').val(res[18]);
						//$('#caller_type').val(res[19]);
						//$('#caller_name').val(res[20]);
						//$('#caller_contact').val(res[21]);
						/* $('#vehicle_type').val(res[24]);
						$('#vehicle_movable').val(res[25]); */
						var ownerId = res[10];
						
					/* if (contact_name.indexOf('!!') == -1) {
						$('#contact_name').show();
						$("#contact_name").attr("disabled", false);
						$("#contact_name_select").attr("disabled", true);
						$('#contact_name_select').hide();
						$('#owenerContactId').val(res[16]);
						$('#owner_contact_mob').val(res[17]);  
						$('#contact_name').val(res[29]);
						$('#owner_contact_email').val(res[30]);
					}else{
						$("#contact_name").attr("disabled", true);
						$('#contact_name').hide();
						$('#contact_name_select').show();
						$("#contact_name_select").attr("disabled", false);
						var owenerContactId =res[16];
						var contact_nameArr = contact_name.split('!!');
						var owenerContactIdArr = owenerContactId.split('!!');
						var length  = contact_nameArr.length;
						var str = "<option value=''>--Select--</option>";
						for(var i=0;i<length;i++){
							str +="<option value='" + owenerContactIdArr[i] + "'>" + contact_nameArr[i] + "</option>";
						}
						
						document.getElementById('contact_name_select').innerHTML =str;
					if (ownerName.indexOf('!!') == -1) {
						$('#owner_name').show();
						$("#owner_name").attr("disabled", false);
						$("#owner_name_select").attr("disabled", true);
						$('#owner_name_select').hide();
						$('#owner_name').val(res[11]);
						$('#owner_mob').val(res[12]);
						$('#owner_landline').val(res[13]);
						//$('#owner_cat').val(res[14]);
						$('#owner_company').val(res[15]);
						$('#ownerId').val(res[10]);

						$('#owenerContactId').val(res[16]);
						$('#owner_contact_mob').val(res[17]);
						$('#contact_name').val(res[29]);
						$('#owner_contact_email').val(res[30]);
						$('#alse_mail').val(res[31]);
						$('#asm_mail').val(res[32]);

						$('#callerId').val(res[18]);
						$('#caller_type').val(res[19]);
						$('#caller_name').val(res[20]);
						$('#caller_contact').val(res[21]);
						//$('#caller_location').val(res[22]);
						//$('#caller_landmark').val(res[23]);
						$('#vehicle_type').val(res[24]);
						$('#vehicle_movable').val(res[25]);
						//$('#zone').val(res[26]);
						//$('#state').val(res[27]);
						//$('#city').val(res[28]);
						//fn_zone_change(res[26],res[27]);
						//Dealer_State_change(res[26],res[27],res[28]);
						//getAssignDetailsManually(res[26],res[27],res[28]);
						//getAssignDetailsManually(res[26],res[27]);
					} else {
						$("#owner_name").attr("disabled", true);
						$('#owner_name').hide();
						$('#owner_name_select').show();
						$("#owner_name_select").attr("disabled", false);
						var ownerNameArr = ownerName.split('!!');
						var ownerIdArr = ownerId.split('!!');
						var length = ownerNameArr.length;
						var str = "<option value=''>--Select--</option>";
						for (var i = 0; i < length; i++) {
							str += "<option value='" + ownerIdArr[i] + "'>" + ownerNameArr[i] + "</option>";
						}
						document.getElementById('owner_name_select').innerHTML =str;
					}  */




				}

			}
		});

	}

 function ownerContactNameData(id){
	$('#ownerId').val(id);
	$.ajax({ url: '{{url("get-owner-change")}}',
		data: {'id':id},
			success: function(response) {
			var Result = response.split(",");var str = '';
				Result.pop();
				console.log(Result);
				var res = Result[0].split('~~');
			$('#owner_name').val(res[0]);
				$('#owner_mob').val(res[3]);
				$('#owner_landline').val(res[4]);
				$('#owner_cat').val(res[5]);
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
			success: function(response) {
			
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
	/* $.ajax({ url: '{{url("get-owner-change-caller")}}',
		data: {'ownerId':id,'vehicleId':vehicleId},
			success: function(response) {
				if (response == 'no') {
				//$('#callerId').val('');
				//$('#caller_type').val('');
				//$('#caller_name').val('');
				//$('#caller_contact').val('');
					$('#caller_location').val('');
					$('#caller_landmark').val('');
					$('#vehicle_type').val('');
					$('#vehicle_movable').val('');
					$('#zone').val('');
					$('#state').val('');
					$('#city').val('');

				} else {
				var Result = response.split(",");var str = '';
					Result.pop();
					console.log(Result);
					var res = Result[0].split('~~');
				//$('#callerId').val(res[0]);
				//$('#caller_type').val(res[1]);
				//$('#caller_name').val(res[2]);
				//$('#caller_contact').val(res[3]);
					$('#caller_location').val(res[4]);
					$('#caller_landmark').val(res[5]);
					$('#vehicle_type').val(res[6]);
					$('#vehicle_movable').val(res[7]);
					$('#zone').val(res[8]);
					$('#state').val(res[9]);
					$('#city').val(res[10]);
					fn_zone_change(res[8], res[9]);
					Dealer_State_change(res[8], res[9], res[10]);
				}

			}
	}); */
	}

	function getLocation(ph) {
		if (ph != '') {
		$.ajax({ url: '{{url("send-latlong-link")}}',
			data: { 'phone':ph},
				success: function(data) {
					var value = data.split("@~~@");
					$('#sessionId').val(value[1]);
				console.log(value[0]);
					alert(value[0]);
				}
			});
		} else {
			alert("Please enter mobile number");
			document.getElementById("phoneNumber").focus();
		}

	}

 
</script>
<script type="text/javascript">
	var lat = '';
	function my_function() {
		var phone = phoneNumber.value;

		if(phone!=''){
			var sessionId = ($('#sessionId').val()) != '' ? $('#sessionId').val() : 0;
			$.ajax({ url: '{{url("get-latlong-map")}}',
				data: { 'phone':phone,'sessionId':sessionId},
				success: function(response) {
					var res = response.split('~~');
					lat = res[0];
					$('#latValue').val(res[0]);
					$('#longValue').val(res[1]);
					var long = res[1];
					var myLatlng = new google.maps.LatLng(lat, long);
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
						title: "User Location"
					});
					addMarker(lat,long,'');
					getAssignDetails(lat, long, '');
				}
			});
		}

	}

	function addMarker(lat, long, el) {
		var infowindow = new google.maps.InfoWindow({});
		var global_markers = [];
		$.ajax({ url: '{{url("get-nearest-latlong")}}',dataType: 'JSON',
				data: { 'lat':lat,'long':long},
			success: function(response) {
				markers = response;
				for (var i = 0; i < markers.length; i++) {
					// obtain the attribues of each marker
					var latitude = parseFloat(markers[i].latitude);
					var lng = parseFloat(markers[i].longitude);
					var trailhead_name = markers[i].dealer_name;
					var myLatlng = new google.maps.LatLng(latitude, lng);
					var contentString = "<html><body><div><p><h2>" + trailhead_name + "</h2></p></div></body></html>";

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
	function getAssignDetails(lat, long, el) {
		$.ajax({ url: '{{url("get-assign-details")}}',
				data: { 'lat':lat,'long':long},
			success: function(response) {
					var Result = response.split(",");var str = '';
				Result.pop();
				var custIds = new Array(el.trim());
				var selectedIds = custIds.join(',').split(',');
				str += "<option value='NA'>--Select--</option>";
				for (item1 in Result) {
					var Result2 = Result[item1].split("~~");
					if (el != '') {
						if (jQuery.inArray(Result2[0], selectedIds) !== -1) {
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
			success: function(response) {
				var Result = response.split(",");var str = '';
				Result.pop();
				str += "<option value='NA'>--Select--</option>";
				for (item1 in Result) {
					var Result2 = Result[item1].split("~~");
					str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
				}
				document.getElementById('assign_to').innerHTML = str;
			}
		});
	}
	function getAssignMob(id) {
		$.ajax({
			url: '{{url("get-assign-mob")}}',
			data: {'id':id},
			success: function(response) {
				if(response == 'No' || response == 0){
					alert("No Work Manager available");
					$('#dealer_mob_number').val('');
				}else{
				$('#dealer_mob_number').val(response);
			}

			}

		});
	}


	function callerUpdate(vehicleId,ownerId,callerId,caller_type,caller_name,caller_contact)
	{		
		$.ajax({ url: '{{url("caller-update")}}',
			data:{'vehicleId':vehicleId,'ownerId':ownerId,'callerId':callerId,'caller_type':caller_type, 'caller_name':caller_name,'caller_contact':caller_contact},
			success: function(response) {
				var res = response.split('~~');
				$('#callerId').val(res[0]);
				alert(res[1]);
			}

		});
	}

	function vehicleUpdate(vehicleId,reg_number1,chassis_number1,engine_number1,vehicle_model,vehicle_segment,purchase_date,add_blue_use,engine_emmission_type)
	{		
		var owner_name = $('#owner_name').val();
		if(owner_name ==''){
			alert("Please select owner");
			$('#owner_name').focus();
		}else{
		$.ajax({ url: '{{url("vehicle-update")}}',
			data:{'vehicleId':vehicleId,'reg_number1':reg_number1,'chassis_number1':chassis_number1, 'engine_number1':engine_number1,'vehicle_model':vehicle_model, 'vehicle_segment':vehicle_segment, 'purchase_date':purchase_date, 'add_blue_use':add_blue_use, 'engine_emmission_type':engine_emmission_type,'owner_id':owner_name},
			success: function(response) {
				var res = response.split('~~');
				$('#vehicleId').val(res[0]);
				alert(res[1]);
			}

		});
	}

	}
	function ownerUpdate(vehicleId,ownerId,owner_name,owner_mob,owner_landline,owner_cat,owner_company,alse_mail,asm_mail)
	{		
		$.ajax({ url: '{{url("owner-update")}}',
			data:{'vehicleId':vehicleId,'ownerId':ownerId,'owner_name':owner_name,'owner_mob':owner_mob, 'owner_landline':owner_landline,'owner_cat':owner_cat, 'owner_company':owner_company, 'alse_mail':alse_mail, 'asm_mail':asm_mail},
			success: function(response) {
				var res = response.split('~~');
				$('#ownerId').val(res[0]);
				alert(res[1]);
			}

		});
	}
	function ownerContactUpdate(vehicleId,ownerId,owenerContactId,contact_name,owner_contact_mob,owner_contact_email)
	{		
		$.ajax({ url: '{{url("owner-contact-update")}}',
			data:{'vehicleId':vehicleId,'ownerId':ownerId,'owenerContactId':owenerContactId,'contact_name':contact_name,'owner_contact_mob':owner_contact_mob,'owner_contact_email':owner_contact_email},
			success: function(response) {
				var res = response.split('~~');
				$('#owenerContactId').val(res[0]);
				alert(res[1]);
			}
		});
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
				$('#vehicle_segment').attr('disabled','disabled');
				$('#add_blue_use').attr('disabled','disabled');
				$('#engine_emmission_type').attr('disabled','disabled');

			}
		})
	}
	function resetForm() {
		$('#myForm').trigger("reset");
		$('#myForm1').trigger("reset");
		$('#assign_to').val('');
	}

	</script>
@endsection
