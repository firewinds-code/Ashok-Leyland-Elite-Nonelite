@extends("layouts.masterlayout")
@section('title','Update Ticket')
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
                <h4 class="card-title">Ticket Update <span style="color: green">{{$complaint_number}}</span> <span style="float: right"><a href="{{route('getTicketReport.get-ticket-report',['get_complaint_number'=>$complaint_number])}}" style="font-size: 16px;font-weight: 600;">Get Report</a></span></h4>
                <div class="row" >
                    <div class="col-md-9" style="border: 1px solid #ccc">
						
						<div class="ribbon">Vehicle Details <span style="float: right;">Assigned : <b>{{$assignedName}}</b></span> </div>
						<form name="myForm" method="post" enctype="multipart/form-data" action="{{url('store-update-cases')}}">
							<input type="hidden" name="_token" value="{{csrf_token()}}">
							<input type="hidden" name="caseId" id="caseId" value="{{$caseId}}">
							<input type="hidden" name="vehicleId" id="vehicleId" value="{{$vehicleId}}">
							<input type="hidden" name="complaint_number" id="complaint_number" value="{{$complaint_number}}">	
							<div class="row">
								<div class="form-group col-md-3">
			                        <label for="datefrom" >Registration Number</label>
									<span style="color: red;">*</span>
									<input type="text" name="reg_number1" id="reg_number1" value="{{$reg_number}}" class="form-control" disabled/>
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="chassis_number1" >Chassis Number</label>
									<span style="color: red;">*</span>
									<input type="text" name="chassis_number1" id="chassis_number1" value="{{$chassis_number}}" class="form-control" disabled />
			                        <span id="chassis_number1_error" style="color:red"></span> 
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="engine_number1" >Engine Number</label>
									<span style="color: red;">*</span>
									<input type="text" name="engine_number1" id="engine_number1" class="form-control" value="{{$engine_number}}" disabled/>
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="vehicle_model" >Vehicle Model</label>
									<span style="color: red;">*</span>
									<input type="text" name="vehicle_model" id="vehicle_model" class="form-control" value="{{$vehicle_model}}" disabled/>
									{{-- <select name="vehicle_model" id="vehicle_model" class="form-control" disabled >
											<option value="">--Select--</option>
											@isset($vehicleModels)
												@foreach ($vehicleModels as $row)
													<option value="{{$row->id}}" {{$vehicle_model==$row->id?"Selected":""}}>{{$row->vehicle_model}}</option>
												@endforeach
											@endisset

										</select>  --}}
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="vehicle_segment" >Vehicle Segment</label>
									<span style="color: red;">*</span>
									<input type="text" name="vehicle_segment" id="vehicle_segment" class="form-control" value="{{$vehicle_segment}}" disabled/>
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="purchase_date" >Purchase Date</label>
									<span style="color: red;">*</span>
									<input type="text" name="purchase_date" class="form-control" value="@isset($purchase_date){{$purchase_date}} @endisset" disabled/>
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="datefrom" >Add Blue Use</label>
									<span style="color: red;">*</span>
									<select name="add_blue_use" id="add_blue_use" class="form-control" disabled>
										<option value="NA">--Select--</option>
										<option value="Yes" {{$add_blue_use=="Yes"?"Selected":""}}>Yes</option>
										<option value="No" {{$add_blue_use=="No"?"Selected":""}}>No</option>
									</select> 
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="engine_emmission_type" >Engine Emission Type</label>
									<span style="color: red;">*</span>
									<select name="engine_emmission_type" id="engine_emmission_type"  class="form-control" disabled>
										<option value="">--Select--</option>
										<option value="BS6" {{$engine_emmission_type=="BS6"?"Selected":""}}>BS6</option>
										<option value="Non BS6" {{$engine_emmission_type=="Non BS6"?"Selected":""}}>Non BS6</option>
									</select>
									{{-- <input type="text" name="engine_emmission_type" id="engine_emmission_type" class="form-control"  value="{{$engine_emmission_type}}" disabled /> --}}
			                    </div>
							</div>
							<hr>
							<div class="ribbon" style="background: #ADD8E6 !important;">Owner Details</div>
							<div class="row" >
								<div class="form-group col-md-3">
			                        <label for="owner_name" >Owner/Company</label>
									<span style="color: red;">*</span>
									<input type="text" name="owner_name" id="owner_name" class="form-control"  value="{{$owner_name}}" disabled />
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="owner_mob" >Mobile Number</label>
									<span style="color: red;">*</span>
									<input type="text" name="owner_mob" id="owner_mob" class="form-control"  value="{{$owner_mob}}" disabled />
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="owner_landline" >Landline Number</label>
									<span style="color: red;">*</span>
									<input type="text" name="owner_landline" id="owner_landline" class="form-control"  value="{{$owner_landline}}" disabled />
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="owner_cat" >Owner Category</label>
									<span style="color: red;">*</span>
									<input type="text" name="owner_cat" id="owner_cat" class="form-control"  value="{{$owner_cat}}" disabled /> 
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="datefrom" >Campany Name</label>
									<span style="color: red;">*</span>
									<input type="text" name="owner_company" id="owner_company" class="form-control" value="{{$owner_company}}" disabled />
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="alse_mail" >ALSE Email</label>
									<span style="color: red;">*</span>
									<input type="email" name="alse_mail" id="alse_mail" class="form-control"  value="{{$alse_mail}}" disabled/>
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="asm_mail" >ASM Email</label>
									<span style="color: red;">*</span>
									<input type="email" name="asm_mail" id="asm_mail" class="form-control"  value="{{$asm_mail}}" disabled/>
			                    </div>
							</div>
							<hr>
							<div class="ribbon">Contact Person Details</div>
							<div class="row" >
								<div class="form-group col-md-3">
			                        <label for="contact_name" >Contact Person</label>
									<span style="color: red;">*</span>
									<input type="text" name="contact_name" id="contact_name" class="form-control"  value="{{$contact_name}}" disabled/> 
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="datefrom" >Phone Number</label>
									<span style="color: red;">*</span>
									<input type="text" name="owner_contact_mob" id="owner_contact_mob" class="form-control" value="{{$mob}}" disabled/>
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="datefrom" >Email</label>
									<span style="color: red;">*</span>
									<input type="email" name="owner_contact_email" id="owner_contact_email" class="form-control" value="{{$owner_contact_email}}"  disabled/>
			                    </div>
							</div>
							
							<hr>
							<div class="ribbon">Caller Info</div>
							<div class="row">
								<div class="form-group col-md-3">
			                        <label for="datefrom" >Caller Type</label>
									<select name="caller_type" id="caller_type" class="form-control" disabled>
										<option value="{{$caller_type}}">{{$caller_type}}</option>
									</select> 
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="datefrom" >Caller Name</label>
									<span style="color: red;">*</span>
									<input type="text" name="caller_name" id="caller_name" class="form-control" value="{{$caller_name}}" disabled />
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="datefrom" >Caller Contact Number</label>
									<span style="color: red;">*</span>
									<input type="text" name="caller_contact" id="caller_contact" class="form-control"  value="{{$caller_contact}}" disabled/>
			                    </div>
								{{-- <div class="form-group col-md-3">
			                        <label for="" >Location</label>
									<span style="color: red;">*</span>
									<input type="text" name="caller_location" id="caller_location" class="form-control" value="{{$caller_location}}" disabled /> 
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="caller_landmark" >Landmark</label>
									<span style="color: red;">*</span>
									<input type="text" name="caller_landmark" id="caller_landmark" class="form-control" value="{{$caller_landmark}}" disabled  />
			                    </div> --}}
								{{-- <div class="form-group col-md-3">
			                        <label for="zone" >Zone</label>
									<span style="color: red;">*</span>
									<select name="zone" id="zone" class="form-control" disabled >
										<option value="{{$region}}">{{$region}}</option>
									</select> 
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="state" >State</label>
									<span style="color: red;">*</span>
									<select name="state" id="state" class="form-control" disabled>
										<option value="{{$state}}">{{$state}}</option>
									</select>
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="city" >City</label>
									<span style="color: red;">*</span>
									<select id="city" name="city" class="form-control" disabled>
										<option value="{{$city}}">{{$city}}</option>
									</select>
								</div> --}}
							</div>
							
							<div style="clear: both;margin: 10px"></div>
							<hr>
							<div class="ribbon">Vehicle Breakdown Details</div>
							<div class="row" >
								<div class="form-group col-md-3">
			                        <label for="" >From Where</label>
									<span style="color: red;">*</span>
									<input type="text" name="from_where" id="from_where" class="form-control" value="{{$from_where}}" disabled/>
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="" >To Where</label>
									<span style="color: red;">*</span>
									<input type="text" name="to_where" id="to_where" class="form-control" value="{{$to_where}}" disabled />
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="" >Location</label>
									<span style="color: red;">*</span>
									<input type="text" name="caller_location" id="caller_location" class="form-control" value="{{$location}}" disabled /> 
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="caller_landmark" >Landmark</label>
									<span style="color: red;">*</span>
									<input type="text" name="caller_landmark" id="caller_landmark" class="form-control" value="{{$landmark}}" disabled  />
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="state" >State</label>
									<span style="color: red;">*</span>
									<select name="state" id="state" class="form-control" disabled>
										<option value="{{$state}}">{{$state}}</option>
									</select>
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="city" >City</label>
									<span style="color: red;">*</span>
									<select id="city" name="city" class="form-control" disabled>
										<option value="{{$city}}">{{$city}}</option>
									</select>
								</div>
								<div class="form-group col-md-3">
			                        <label for="" >District</label>
									<span style="color: red;">*</span>
									<input type="text" name="district" id="district" class="form-control" value="{{$district}}" disabled />
			                    </div>
								
								<div class="form-group col-md-3">
			                        <label for="" >Highway</label>
									<input type="text" name="highway" id="highway" class="form-control"  value="{{$highway}}"  disabled/> 
									{{-- <select name="highway" id="highway" class="form-control" disabled>
										<option value="{{$highway}}">{{$highway}}</option>
									</select>   --}}
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="vehicle_type" >Vehicle Type</label>
									<select name="vehicle_type" id="vehicle_type" class="form-control" disabled>
										<option value="{{$vehicle_type}}">{{$vehicle_type}}</option>
									</select>   
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="vehicle_movable" >Is Vehicle Movable</label>
									<select name="vehicle_movable" id="vehicle_movable" class="form-control" disabled>
										<option value="{{$vehicle_movable}}">{{$vehicle_movable}}</option>
									</select>  
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="" >Ticket Type</label>
									<select name="ticket_type" id="ticket_type" class="form-control" disabled>
										<option value="{{$ticket_type}}">{{$ticket_type}}</option>
									</select>  
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="Aggregate" >Aggregate</label>
									<select name="aggregate" id="aggregate" class="form-control" disabled>
										<option value="{{$aggregate}}">{{$aggregate}}</option>
									</select> 
			                    </div>
								<div class="form-group col-md-6">
			                        <label for="datefrom" >Vehicle Problem (max 150 chars)<sup style="color: red;">*</sup></label>
									<textarea name="vehicle_problem" id="" cols="30" rows="5" class="form-control" disabled>{{$vehicle_problem}}</textarea>
			                    </div>
							</div>
							
							<hr>
							<div class="ribbon">Vehicle Breakdown Ticket Details @if(Session::get('role') == '29' || Session::get('role') == '30' || Session::get('role') == '87') <span style="float: right;"><a href="#" id="myBtn" style="color:#fff;text-decoration: underline;">Dealer Info</a></span> @endif</div>
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
							
								<div class="form-group col-md-3">
			                        <label for="datefrom" >Ticket Assign To</label>
									<select name="assign_to" id="assign_to" class="form-control" onchange="getAssignMob(this.value)"  {{(Session::get('role') == 29 || Session::get('role') == 30 || Session::get('role') == 87)?"":"disabled"}}>
										<option value="NA">--Select--</option>
										@isset($sqlLatLong)
										@foreach ($sqlLatLong as $row)
											<option value="{{$row->id}}" {{$assign_to == $row->id?"Selected":""}}>{{$row->dealer_name}}</option>
										@endforeach
											
										@endisset
									</select>
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="datefrom" >Mobile Number</label>
									<span style="color: red;">*</span>
									<input type="text" name="dealer_mob_number" id="dealer_mob_number" class="form-control"  value="{{$dealer_mob_number}}" maxlength="10" {{(Session::get('role') == 29 || Session::get('role') == 30 || Session::get('role') == 87)?"":"disabled"}}/>
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="datefrom" >Alt. Contact Number</label>
									<input type="text" name="dealer_alt_mob_number" id="dealer_alt_mob_number" class="form-control"  value="{{$dealer_alt_mob_number}}" maxlength="10" {{(Session::get('role') == 29 || Session::get('role') == 30 || Session::get('role') == 87)?"":"disabled"}}/>
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="datefrom" >Ticket Status</label>
									<select name="remark_type" id="remark_type" class="form-control">
										<option value="NA">--Select--</option>
										@isset($remark_type_table)
											@foreach ($remark_type_table as $row)
												@if(Session::get('role') == '29' || Session::get('role') == '30' || Session::get('role') == '87')
													<option value="{{$row->type}}" @isset($remark_type) {{$row->type == $remark_type?'Selected':""}} @endisset>{{$row->type}}</option>
												@else
													@php $tictStatus = array(32,33,34,35,36); @endphp
													@if(!in_array($row->id,$tictStatus))
														<option value="{{$row->type}}" @isset($remark_type) {{$row->type == $remark_type?'Selected':""}} @endisset>{{$row->type}}</option>
													@else
														@if($row->type == $remark_type)
															<option value="{{$row->type}}" Selected>{{$row->type}}</option>
														@endif
													@endif
												@endif
											@endforeach
											
										@endisset
										
									</select> 
			                    </div> 
							
								<div class="form-group col-md-3">
			                        <label for="datefrom" >Disposition</label>
									<select name="disposition" id="disposition" class="form-control" {{(Session::get('role') == 29 || Session::get('role') == 30 || Session::get('role') == 87)?"":"disabled"}}>
										<option value="NA">--Select--</option>
										<option value="RNR" @isset($disposition) {{'RNR' == $disposition?'Selected':""}} @endisset>RNR</option>
										<option value="Callback" @isset($disposition) {{'Callback' == $disposition?'Selected':""}} @endisset>Callback</option>
										<option value="Line Busy" @isset($disposition) {{'Line Busy' == $disposition?'Selected':""}} @endisset>Line Busy</option>
										<option value="Switched Off" @isset($disposition) {{'Switched Off' == $disposition?'Selected':""}} @endisset>Switched Off</option>
										<option value="Not Reachable" @isset($disposition) {{'Not Reachable' == $disposition?'Selected':""}} @endisset>Not Reachable</option>
										<option value="Status Collected" @isset($disposition) {{'Status Collected' == $disposition?'Selected':""}} @endisset>Status Collected</option>
									</select>
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="datefrom" >Agent Remarks</label>
									<select name="agent_remark" id="agent_remark" class="form-control" {{(Session::get('role') == 29 || Session::get('role') == 30 || Session::get('role') == 87)?"":"disabled"}}>
										<option value="NA">--Select--</option>
										<option value="Incorrect response and restoration" @isset($agent_remark) {{'Incorrect response and restoration' == $agent_remark?'Selected':""}} @endisset>Incorrect response and restoration</option>
										<option value="Incorrect ticket closure/ticket reopen" @isset($agent_remark) {{'Incorrect ticket closure/ticket reopen' == $agent_remark?'Selected':""}} @endisset>Incorrect ticket closure/ticket reopen</option>
										<option value="NO error" @isset($agent_remark) {{'NO error' == $agent_remark?'Selected':""}} @endisset>NO error</option>

									</select> 
			                    </div>
								
								<div class="form-group col-md-3">
			                        <label for="datefrom" >Est. Response Time</label>
									<input type="text" name="estimated_response_time" id="estimated_response_time" autocomplete="off" class="form-control" value="{{$estimated_response_time}}" />
									{{-- <input type="text" name="estimated_response_time" id="estimated_response_time" class="form-control" value="{{$estimated_response_time}}" /> --}}
			                    </div>
							
								<div class="form-group col-md-3">
									<label for="datefrom" >Actual Response Time</label>
									{{-- <input type="text" name="actual_response_time" id="actual_response_time" autocomplete="off" class="form-control" value="{{$actual_response_time}}" {{$remark_type == 'Closed'?"required":""}} onblur="estimatedChange(this.value)"> --}}
									<input type="text" name="actual_response_time" id="actual_response_time" autocomplete="off" class="form-control" value="{{$actual_response_time}}" {{( $remark_type !='Assigned' && $remark_type !='Reassigned support')?"required":""}} {{($actual_response_time != '')?"disabled":""}} onblur="estimatedChange(this.value)">
 									@if($actual_response_time != '')
 										<input type="hidden" name="actual_response_time" value="{{$actual_response_time}}" />
 									@endif
									{{-- <input type="text" name="actual_response_time" id="actual_response_time" class="form-control" value="{{$actual_response_time}}" {{(Session::get('role') == 29 || Session::get('role') == 30)?"":"disabled"}}/> --}}
								</div>
							
								<div class="form-group col-md-3">
			                        <label for="datefrom" >Restoration Time</label>
									<input type="text" name="tat_scheduled" id="tat_scheduled" autocomplete="off" class="form-control" value="{{$tat_scheduled}}" {{$remark_type == 'Closed'?"required":""}}>
									{{-- <input type="text" name="tat_scheduled" id="tat_scheduled" class="form-control" value="{{$tat_scheduled}}" /> --}}
			                    </div>
								<div class="form-group col-md-3">
									<label for="datefrom" >Restoration Type</label>
									<select name="restoration_type" id="restoration_type" class="form-control" {{$remark_type == 'Closed'?"required":""}} {{(Session::get('user_type_id') == 3)?"disabled":""}}>
										<option value="">--Select--</option>
										<option value="Restored by self" {{ $restoration_type_db == 'Restored by self'?"Selected":"" }}>Restored by self</option>
										<option value="Restored By Support" {{ $restoration_type_db == 'Restored By Support'?"Selected":"" }}>Restored By Support</option>
										<option value="Restored By Unknown Support" {{ $restoration_type_db == 'Restored By Unknown Support'?"Selected":"" }}>Restored By Unknown Support</option>
									</select>
								</div>
								<div class="form-group col-md-3">
									<label for="datefrom" >Response Delay Reason</label>
									<select name="response_delay_reason" id="response_delay_reason" class="form-control" {{$remark_type == 'Closed'?"required":""}} {{(Session::get('user_type_id') == 3)?"disabled":""}}>
										<option value="">--Select--</option>
										@foreach($responseDelayReason as $row)
											<option value="{{ $row->reason }}" {{ ($response_delay_reason_db == $row->reason)?"Selected":"" }}>{{ $row->reason }}</option>
										@endforeach
										
									</select>
								</div>
								@if(Session::get('user_type_id') == 3)
									<input type="hidden" name="restoration_type" value="{{$restoration_type_db}}" />
									<input type="hidden" name="response_delay_reason" value="{{$response_delay_reason_db}}" />
								@endif
								<div class="form-group col-md-3">
			                        <label for="datefrom" >Acceptance</label>
									<div class="radio" >
										@isset($acceptance)
										<label><input type="radio" name="acceptance" value="1" {{$acceptance==1?"Checked":""}} {{(Session::get('role') == 29 || Session::get('role') == 30 || Session::get('role') == 87)?"":"disabled"}}>Yes</label>
										<label><input type="radio" name="acceptance" value="0" {{$acceptance==0?"Checked":""}} {{(Session::get('role') == 29 || Session::get('role') == 30 || Session::get('role') == 87)?"":"disabled"}}>No</label>
										@endisset
									</div>
			                    </div>								
								<div class="form-group col-md-3">
			                        <label for="feedback_rating" >Feedback Rating</label>
									<select name="feedback_rating" id="feedback_rating" class="form-control" {{(Session::get('role') == 29 || Session::get('role') == 30 || Session::get('role') == 87)?"":"disabled"}}>
										<option value="">--Select--</option>
										@for ($i=1;$i<=10;$i++ )
											<option value="{{$i}}" {{$feedback_rating==$i?"selected":""}}>{{$i}}</option>
										@endfor
									</select>
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="feedback_desc" >Feedback Description</label>
									<textarea name="feedback_desc" id="feedback_desc" cols="20" rows="3" class="form-control" {{(Session::get('role') == 29 || Session::get('role') == 30 || Session::get('role') == 87)?"":"disabled"}}>{{$feedback_desc}}</textarea>
			                    </div>						
							</div>
							@php $roleArray = array(29,30,87); @endphp
							{{-- @if(Session::get('role') != 29 || Session::get('role') != 30 || Session::get('role') != 87) --}}
							@if(!in_array(Session::get('role'),$roleArray))
								<input type="hidden" name="assign_to" value="{{$assign_to}}" />
								<input type="hidden" name="dealer_mob_number" value="{{$dealer_mob_number}}" />
								<input type="hidden" name="dealer_alt_mob_number" value="{{$dealer_alt_mob_number}}" />
								<input type="hidden" name="disposition" value="{{$disposition}}" />
								<input type="hidden" name="agent_remark" value="{{$agent_remark}}" />
								<input type="hidden" name="actual_response_time" value="{{$actual_response_time}}" />
								<input type="hidden" name="acceptance" value="{{$acceptance}}" />
								<input type="hidden" name="feedback_rating" value="{{$feedback_rating}}" />
								<input type="hidden" name="feedback_desc" value="{{$feedback_desc}}" />
							@endif
							<div class="row">
								<div class="form-group col-md-9">
									<label for="standard_remark" >Standard Remarks</label>
									<textarea name="standard_remark" id="standard_remark" cols="20" rows="3" class="form-control" disabled>{{$standard_remark}}</textarea>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-md-12">
			                        <label for="assign_remarks" >Remarks (Max 500 Chars)<sup style="color: red;">*</sup></label>
									<textarea name="assign_remarks" id="assign_remarks" cols="30" rows="5" class="form-control" maxlength="500">{{$assign_remarks}}</textarea>
			                    </div>
							</div>
							<div class="form-group col-md-12">
								<p>
									<span class="btn-primary"  style="padding-left: 5px;padding-right: 5px;padding-top: 2px;padding-bottom: 2px;cursor: pointer;position: relative;top: 13px;" title="{{$complaint_number}} - History" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="false" aria-controls="multiCollapseExample1 multiCollapseExample2">{{$complaint_number}} - History</span> 
								</p>
									<div class="collapse multi-collapse" id="multiCollapseExample2" style="position: relative;top: 10px;">
										<div class="card card-body" style="width: 100%;overflow: auto;">
											<table style="font-size: 11px;" class="table table-bordered">
												<tr>
													<th style="text-align: left;">Status</th>
													<th style="text-align: left;">Assigned</th>
													<th style="text-align: left;">Assign Remarks</th>
													<th style="text-align: left;">Action by</th>
													<th style="text-align: left;">Agent Remarks</th>
													<th style="text-align: left;">Estimated Response Time (Min)</th>
													<th style="text-align: left;">Restoration Time (Min)</th>
													<th style="text-align: left;">Acceptance</th>
													<th style="text-align: left;">Feedback Rating</th>
													<th style="text-align: left;">Feedback Description</th>
													<th style="text-align: left;">Date</th>
												</tr>
												@isset($history)
												@foreach($history as $row)
												<tr>
													<td style="text-align: left;">{{$row->remark_type}}</td>
													<td style="text-align: left;">{{$row->dealer_name}}</td>
													<td style="text-align: left;">{{$row->assign_remarks}}</td>
													<td style="text-align: left;">{{$row->employee_name}}</td>
													<td style="text-align: left;">{{$row->agent_remark}}</td>
													<td style="text-align: left;">{{$row->estimated_response_time!=''?date('d-m-Y H:i:s',strtotime($row->estimated_response_time)):'NA'}}</td>
													<td style="text-align: left;">{{$row->tat_scheduled}}</td>
													<td style="text-align: left;">{{$row->acceptance==1?"Yes":"No"}}</td>
													<td style="text-align: left;">{{$row->feedback_rating}}</td>
													<td style="text-align: left;">{{$row->feedback_desc}}</td>
													<td style="text-align: left;">{{$row->created_at!=''?date('d-m-Y H:i:s',strtotime($row->created_at)):'NA'}}</td>
												</tr>
												@endforeach
												@endisset

											</table>
										</div>
									</div>
							</div>
							<br/>
							@if($remark_type != 'Closed')
							<div class="row" style="margin-bottom: 10px;">
								<div class="container-fluid">
									<div class="col-sm-12 text-center">
										<input type="submit"class="btn btn-primary rounded" name="submit" value="Update" onclick="return remarkTypeFunc(remark_type.value)"/>
										
									</div>
								 </div>
							</div>
							@endif
						</form>
                    </div>
					<div class="col-md-3" style="border: 1px solid #ccc">
							{{-- <div class="row">
								<div class="form-group col-md-6">
									<label for="" >Send Link</label>
									<input type="text" name="phoneNumber" id="phoneNumber" class="form-control" maxlength="10" placeholder="Phone Number" />
									<input type="hidden" name="sessionId" id="sessionId" />
								</div>
								<div class="form-group col-md-6">
									<label for="" ></label>
									<a class="btn-secondary" onclick="getLocation(phoneNumber.value);" style="color: #fff;padding: 5px 12px 5px 12px;border-radius: 10px;position: relative;top: 30px;">Send</a>
								</div>
							</div> --}}
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

 <script>
	 function estimatedChange(params) {
		/* var estimated_response_time = $('#estimated_response_time').val();
		var actual_response_time = params;
		if(estimated_response_time < actual_response_time){			
		}else{
			alert("Actual Response Time should be greater than Est. reponse time");
		 } */
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
 		alert("Please Select Ticket Asign To");
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
	$(document).ready(function(){
			
		var vehicle = '{{$vehicle_model}}';
		//funcVehicleModel(vehicle);
	});
	function remarkTypeFunc(param){
 		var actual_response_time = $('#actual_response_time').val();
 		var estimated_response_time = $('#estimated_response_time').val();
		var tat_scheduled = $('#tat_scheduled').val();
		if(param != 'Assigned' && param != 'Reassigned support'){
			var actual_response_time = $('#actual_response_time').val();
			if(actual_response_time ==''){ 				
 				alert("Please fill actual response time");
 				$('#actual_response_time').focus();
 				return false;
 			}
		}
		if(tat_scheduled !=''){
			if((actual_response_time > tat_scheduled) && (actual_response_time !='')){
				alert("Actual Response Time should be less than Restoration time");
				return false;
			}
		}
		if(param == 'Closed'){
 			var actual_response_time = $('#actual_response_time').val();
 			var tat_scheduled = $('#tat_scheduled').val();
 			var restoration_type = $('#restoration_type').val();
 			var response_delay_reason = $('#response_delay_reason').val();
			
 			if(actual_response_time ==''){ 				
 				alert("Please fill actual response time");
 				$('#actual_response_time').focus();
 				return false;
 			}
			if(tat_scheduled ==''){
 				alert("Please fill restoration time");
 				$('#tat_scheduled').focus();
 				return false; 			
 			}
			if((actual_response_time > tat_scheduled) && (actual_response_time !='')){
				alert("Actual Response Time should be less than Restoration time");
				return false;
			}
			if(restoration_type ==''){
 				alert("Please fill restoration type");
 				$('#restoration_type').focus();
 				return false; 			
 			}
			if(response_delay_reason ==''){
 				alert("Please fill response delay reason");
 				$('#response_delay_reason').focus();
 				return false;
 			}
			
 		}
		if(param == 'Closed'){
			return confirm("You are about to close this ticket. Once closed, the ticket can not be edited or upload. Are you sure to close this ticket.")
		}
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
	function Dealer_State_change(el,ell,elll){
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
 			/* onChangeDateTime: function(date) {
  				// Here you need to compare date! this is up to you :-)
 				if (today.getDate() > date.getDate()) {
 						this.setOptions({maxTime: new Date()});
  				} else {
 					this.setOptions({maxTime: false});
 				 	
  				}
  			} */
  		};
		$('#purchase_date').datetimepicker({ format:'Y-m-d',timepicker:false});
		//$('#estimated_response_time').datetimepicker(options1);
		$('#estimated_response_time').datetimepicker(options1);
		/* $('#actual_response_time').datetimepicker({maxDate: 0,maxTime: 0,format:'Y-m-d H:i:s'});
		$('#tat_scheduled').datetimepicker({maxDate: 0,maxTime: 0,format:'Y-m-d H:i:s'}); */
		$('#actual_response_time').datetimepicker(options);
		$('#tat_scheduled').datetimepicker(options);
	});
	/*  function fn_state_change(stateId,ell){
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
	} */
 function getData(reg_number,chassis_number,engine_number){
		$.ajax({ url: '{{url("get-vehicle-details")}}',
			data: { 'reg_number':reg_number,'chassis_number':chassis_number,'engine_number':engine_number},
			success: function(data){
				console.log(data);
				if(data =='no'){
					alert("There is no Vehicle");
					$('#callerEdit').show();
					$('#vehicleEdit').show();
					$('#ownerEdit').show();
					$('#ownerContactEdit').show();
					/* document.getElementById('callerEdit').innerHTML = 'Insert';
					document.getElementById('vehicleEdit').innerHTML = 'Insert';
					document.getElementById('ownerEdit').innerHTML = 'Insert'; */
				}else{
					$('#callerEdit').show();
					$('#vehicleEdit').show();
					$('#ownerEdit').show();
					$('#ownerContactEdit').show();
					
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
					//$('#vehicle_segment').val(res[5]);
					$('#purchase_date').val(res[6]);
					//$('#add_blue_use').val(res[7]);
					//$('#engine_emmission_type').val(res[9]);
					
					
					
					var ownerName = res[11];
					var ownerId = res[10];
					if (ownerName.indexOf('!!') == -1) {
						$('#owner_name').show();
						$("#owner_name").attr("disabled", false);
						$("#owner_name_select").attr("disabled", true);
						$('#owner_name_select').hide();
						$('#owner_name').val(res[11]);
						$('#owner_mob').val(res[12]);
						$('#owner_landline').val(res[13]);
						$('#owner_cat').val(res[14]);
						$('#owner_company').val(res[15]);
						$('#ownerId').val(res[10]);

						$('#owenerContactId').val(res[16]);
						$('#owner_contact_mob').val(res[17]);
						$('#contact_name').val(res[29]);

						$('#callerId').val(res[18]);
						$('#caller_type').val(res[19]);
						$('#caller_name').val(res[20]);
						$('#caller_contact').val(res[21]);
						$('#caller_location').val(res[22]);
						$('#caller_landmark').val(res[23]);
						$('#vehicle_type').val(res[24]);
						$('#vehicle_movable').val(res[25]);
						$('#zone').val(res[26]);
						$('#state').val(res[27]);
						$('#city').val(res[28]);
						//fn_zone_change(res[26],res[27]);
						//Dealer_State_change(res[26],res[27],res[28]);
					}else{
						$("#owner_name").attr("disabled", true);
						$('#owner_name').hide();
						$('#owner_name_select').show();
						$("#owner_name_select").attr("disabled", false);
						var ownerNameArr = ownerName.split('!!');
						var ownerIdArr = ownerId.split('!!');
						var length  = ownerNameArr.length;
						var str = "<option value=''>--Select--</option>";
						for(var i=0;i<length;i++){
							str +="<option value='" + ownerIdArr[i] + "'>" + ownerNameArr[i] + "</option>";
						}
						document.getElementById('owner_name_select').innerHTML =str;
					} 
					
					
					
					
				}
					
			}
		});	
	
 }

 function ownerData(id){
	$('#ownerId').val(id);
	var vehicleId = $('#vehicleId').val();
	$.ajax({ url: '{{url("get-owner-change")}}',
		data: {'id':id},
		success: function(response){
			var Result = response.split(",");var str = '';
			Result.pop();
			console.log(Result);
			var res = Result[0].split('~~');
			$('#owner_name').val(res[2]);
			$('#owner_mob').val(res[3]);
			$('#owner_landline').val(res[4]);
			$('#owner_cat').val(res[5]);
			$('#owner_company').val(res[6]);
			
		}
	});
	$.ajax({ url: '{{url("get-owner-contact-change")}}',
		data: {'ownerId':id,'vehicleId':vehicleId},
		success: function(response){
			var Result = response.split(",");var str = '';
			Result.pop();
			console.log(Result);
			var res = Result[0].split('~~');
			
			$('#owenerContactId').val(res[0]);
			$('#owner_contact_mob').val(res[2]);
			$('#contact_name').val(res[1]);
			
		}
	});
	$.ajax({ url: '{{url("get-owner-change-caller")}}',
		data: {'ownerId':id,'vehicleId':vehicleId},
		success: function(response){
			if(response =='no'){
				$('#callerId').val('');
				$('#caller_type').val('');
				$('#caller_name').val('');
				$('#caller_contact').val('');
				$('#caller_location').val('');
				$('#caller_landmark').val('');
				$('#vehicle_type').val('');
				$('#vehicle_movable').val('');
				$('#zone').val('');
				$('#state').val('');
				$('#city').val('');
				
			}else{
				var Result = response.split(",");var str = '';
				Result.pop();
				console.log(Result);
				var res = Result[0].split('~~');
				$('#callerId').val(res[0]);
				$('#caller_type').val(res[1]);
				$('#caller_name').val(res[2]);
				$('#caller_contact').val(res[3]);
				$('#caller_location').val(res[4]);
				$('#caller_landmark').val(res[5]);
				$('#vehicle_type').val(res[6]);
				$('#vehicle_movable').val(res[7]);
				$('#zone').val(res[8]);
				//$('#state').val(res[9]);
				//$('#city').val(res[10]);
			//	fn_zone_change(res[8],res[9]);
			//	Dealer_State_change(res[8],res[9],res[10]);
			}
			
		}
	});
 }

 function getLocation (ph){
	 if(ph!=''){
		$.ajax({ url: '{{url("send-latlong-link")}}',
			data: { 'phone':ph},
			success: function(data){
				var value = data.split("@~~@");
				$('#sessionId').val(value[1]);
				alert(value[0]);
			}
		});
	 }else{
		 alert("Please enter mobile number");
		 document.getElementById("phoneNumber").focus();
	 }
	
 }

 
 </script>
<script type="text/javascript">
$(document).ready(function () {
		var latitude = '{{$latitude}}';
		var longitude = '{{$longitude}}';
		
		if(latitude!=''){
			var assign_to = '{{$assign_to}}';
			console.log(assign_to);
			var lat =latitude;
			var long = longitude;
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
			//getAssignDetails(lat,long,assign_to);
		}else{
			//alert("vdrrdrd");
			getAssignDetailsManually();
		}
		
});
	
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
	function getAssignDetails(lat,long,el){
		
		$.ajax({ url: '{{url("get-assign-details")}}', 
				data: { 'lat':lat,'long':long},
				success: function(response){
					var Result = response.split(",");var str = '';
					Result.pop();


					
					 str += "<option value='NA'>--Select--</option>";
					for (item1 in Result) {
					 var Result2 = Result[item1].split("~~");
					 if (el!='') {
						 //if (jQuery.inArray( Result2[0], selectedIds ) !== -1 ) {
						 if (Result2[0] == el ) {
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
	function getAssignDetailsManually(){
		//var zone = $('#zone').val();
		var state = $('#state').val();
	//	var city = $('#city').val();
		if(state !=''){
			$.ajax({ url: '{{url("get-assign-details-manually")}}',
				data: { 'zone':zone,'state':state,'city':city},
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
			
	}
	function getAssignDetailsManually_bckup(){
		var zone = $('#zone').val();
		var state = $('#state').val();
		var city = $('#city').val();
		if(zone !=''){
			$.ajax({ url: '{{url("get-assign-details-manually")}}',
				data: { 'zone':zone,'state':state,'city':city},
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
			
	}
	function getAssignMob(id){
		$.ajax({
			url: '{{url("get-assign-mob")}}',
			data: {'id':id},
			success: function(response){
				$('#dealer_mob_number').val(response);
			}

		});
	}
	
	
	function callerUpdate(vehicleId,ownerId,callerId,caller_type,caller_name,caller_contact,caller_location,caller_landmark,vehicle_type,vehicle_movable,zone,state,city){
		$.ajax({ url: '{{url("caller-update")}}',
			data:{'vehicleId':vehicleId,'ownerId':ownerId,'callerId':callerId,'caller_type':caller_type, 'caller_name':caller_name,'caller_contact':caller_contact, 'caller_location':caller_location, 'caller_landmark':caller_landmark, 'vehicle_type':vehicle_type, 'vehicle_movable':vehicle_movable, 'zone':zone, 'state':state, 'city':city},
			success: function(response){
				var res =  response.split('~~');
				$('#callerId').val(res[0]);
				alert(res[1]);
			}

		});
	}
	
	function vehicleUpdate(vehicleId,reg_number1,chassis_number1,engine_number1,vehicle_model,vehicle_segment,purchase_date,add_blue_use,engine_emmission_type)
	{		
		$.ajax({ url: '{{url("vehicle-update")}}',
			data:{'vehicleId':vehicleId,'reg_number1':reg_number1,'chassis_number1':chassis_number1, 'engine_number1':engine_number1,'vehicle_model':vehicle_model, 'vehicle_segment':vehicle_segment, 'purchase_date':purchase_date, 'add_blue_use':add_blue_use, 'engine_emmission_type':engine_emmission_type},
			success: function(response){
				var res =  response.split('~~');
				$('#vehicleId').val(res[0]);
				alert(res[1]);
			}

		});
	}
	function ownerUpdate(vehicleId,ownerId,owner_name,owner_mob,owner_landline,owner_cat,owner_company)
	{		
		$.ajax({ url: '{{url("owner-update")}}',
			data:{'vehicleId':vehicleId,'ownerId':ownerId,'owner_name':owner_name,'owner_mob':owner_mob, 'owner_landline':owner_landline,'owner_cat':owner_cat, 'owner_company':owner_company},
			success: function(response){
				var res =  response.split('~~');
				$('#ownerId').val(res[0]);
				alert(res[1]);
			}

		});
	}
	function ownerContactUpdate(vehicleId,ownerId,owenerContactId,contact_name,owner_contact_mob)
	{		
		$.ajax({ url: '{{url("owner-contact-update")}}',
			data:{'vehicleId':vehicleId,'ownerId':ownerId,'owenerContactId':owenerContactId,'contact_name':contact_name,'owner_contact_mob':owner_contact_mob},
			success: function(response){
				var res =  response.split('~~');
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
	</script>
@endsection