
<?php $__env->startSection('title','Create Ticket'); ?>
<?php $__env->startSection('bodycontent'); ?>
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
				            <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
				            <div class="row" style="margin-bottom: 10px;">
			                 	<div class="form-group col-md-3">
			                        <label for="datefrom" >Registration Number</label>
									<input type="text" name="reg_number" id="reg_number" class="form-control" placeholder="Registration Number" />
			                        <span id="reg_number_error" style="color:red"></span> 
			                    </div>
			                 	<div class="form-group col-md-3">
			                        <label for="datefrom" >Chassis Number</label>
									<input type="text" name="chassis_number" id="chassis_number" class="form-control" onblur="checkChassisNumber1()" placeholder="Chassis Number" />
			                        <span id="chassis_number_error" style="color:red"></span> 
			                    </div>
			                 	<div class="form-group col-md-3">
			                        <label for="datefrom" >Engine Number</label>
									<input type="text" name="engine_number" id="engine_number" class="form-control" placeholder="Engine Number" />
			                        <span id="engine_number_error" style="color:red"></span> 
			                    </div>
			                 	
			                	 <div class="form-group col-md-3">
			                        <a class="btn-secondary" onclick="getData(reg_number.value,chassis_number.value,engine_number.value);" style="color: #fff;padding: 5px;border-radius: 10px;position: relative;top: 30px;" id="getsearch">Search</a>
									<a class="btn-secondary" onclick="reloadPage();" style="color: #fff;padding: 5px;border-radius: 10px;position: relative;top: 30px;">Reset</a>
									
			                    </div>
			                </div> 
			            </form>
						<hr>
						<div class="ribbon">Vehicle Details</div>
						<form name="myForm" id="myForm" method="post" enctype="multipart/form-data" action="<?php echo e(url('ticket-creation-data')); ?>" onsubmit="return formSubmit()">
							<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
							<input type="hidden" name="vehicleId" id="vehicleId">
							<input type="hidden" name="ownerId" id="ownerId">
							
							<input type="hidden" name="callerId" id="callerId">
							<input type="hidden" name="latValue" id="latValue">
							<input type="hidden" name="longValue" id="longValue">
							<input type="hidden" name="vahan_status" id="vahan_status">
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
									<input type="text" name="chassis_number1" id="chassis_number1" class="form-control"  placeholder="Chassis Number" onblur="checkChassisNumber()"/>
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
									<input type="text" name="purchase_date" id="purchase_date" autocomplete="off" class="form-control" value="<?php if(isset($purchase_date)): ?><?php echo e($purchase_date); ?> <?php endif; ?>"  placeholder="Purchase Date" />
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
									
			                    </div>
								<div class="col-sm-12 text-center">
									<a class="btn-secondary" id="vehicleEdit" onclick="vehicleUpdate(ownerId.value,reg_number1.value,chassis_number1.value,engine_number1.value,vehicle_model.value,vehicle_segment.value,purchase_date.value,add_blue_use.value,engine_emmission_type.value);" style="display: none; color: #fff;padding: 5px;border-radius: 10px;position: relative;top: 10px;">Save Vehicle</a>
									<img src="<?php echo e(asset('images/left.gif')); ?>" width="5%" id="vehicleIndication" style="display:none" /> 
								</div>
							</div>
							
							<hr>
							<div class="ribbon">Owner Details</div>
							<div class="row" >
								<div class="form-group col-md-3">
			                        <label for="owner_name" >Owner/Company</label>
									<span style="color: red;">*</span>
									
									<input type="text" name="owner_name" id="owner_name_text" class="form-control"  placeholder="Name" style="display:none" disabled/>
									<select name="owner_name" id="owner_name" class="form-control"  placeholder="Name" onchange="ownerContactNameData(this.value)" required>
										<option value="">--Select--</option>
										
									</select>
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="owner_mob" >Mobile Number</label>
									<input type="text" name="owner_mob" id="owner_mob" class="form-control"  placeholder="Mobile Number" maxlength="10" readonly/>
									
			                    </div> 
								
								<input type="hidden" name="owner_company" id="owner_company" class="form-control"  placeholder="Company Name"/>
								
								<div class="form-group col-md-3" style="display:none">
			                        <label for="alse_mail" >ALSE / ASM Email</label>
									<input type="text" name="alse_mail" id="alse_mail" class="form-control"  placeholder="ALSE Email" value="test@dispostable.com"/>
									
			                    </div>
								<div class="form-group col-md-3" style="display:none">
			                        <label for="asm_mail" >RSM Email</label>
									<input type="text" name="asm_mail" id="asm_mail" class="form-control"  placeholder="RSM Email" value="test@dispostable.com"/>
									
			                    </div>
							</div>
							
							<div class="row" >
								<div class="container-fluid">
									<div class="col-sm-12 text-center">
										<a class="btn-secondary" id="ownerEdit" onclick="ownerUpdate(owner_name_text.value,owner_mob.value);" style="display: none; color: #fff;padding: 5px;border-radius: 10px;position: relative;top: 10px;">Save Owner</a>
										<img src="<?php echo e(asset('images/left.gif')); ?>" width="5%" id="ownerIndication" style="display:none"/>
									</div>
								</div>
							</div>
							<hr>
							
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
										<img src="<?php echo e(asset('images/left.gif')); ?>" width="5%" id="callerIndication" style="display:none" />
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
										<?php if(isset($caller_state)): ?>
											<?php $__currentLoopData = $caller_state; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
												<option value="<?php echo e($row->id); ?>"><?php echo e($row->state); ?></option>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
										<?php endif; ?>
									</select>
			                    </div>
									
								<div class="form-group col-md-3">
			                        <label for="city" >District</label>
									
									<select id="cityCaller" name="city" class="form-control">
										<option value="">--Select--</option>
									</select>
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
										
										<option value="Breakdown Ticket">Breakdown Ticket</option>
										<option value="Vehicle in workshop">Vehicle in workshop</option>
									</select>  
			                    </div>
								
								
							</div>
							
							<hr>
							<div class="ribbon">Vehicle Breakdown Ticket Details <?php if(Auth::user()->role == '29' || Auth::user()->role == '30' || Auth::user()->role == '87'): ?> <span style="float: right;"><a href="#" id="myBtn" style="color:#fff;text-decoration: underline;">Dealer Info</a></span> <?php endif; ?></div>
 							
 							<div id="myModal" class="modal">
 							
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
   							
							<div class="row">
								
									<div class="form-group col-md-6">
										<label for="datefrom" >Ticket Assign To</label>
										<select name="assign_to" id="assign_to" class="form-control" onchange="getAssignMob(this.value),getAssignWorkManager(this.value,''),getNightSpoc(this.value,'')" required>
											<option value="">--Select--</option>
										</select>
									</div>
									<input type="hidden" name="dealer_mob_number" id="dealer_mob_number1"/>
									<input type="hidden" name="dealer_alt_mob_number" id="dealer_alt_mob_number" />
									
									<div class="form-group col-md-3">
										<label for="datefrom" >Work Manager</label>
										<select name="assign_work_manager" id="assign_work_manager" class="form-control" onchange="getAssignWorkManagerMobile(this.value)" required>
										</select>
									</div>
									<div class="form-group col-md-3">
										<label for="datefrom" >Work Manager Mobile</label>
										<input type="text" name="assign_work_manager_mobile" id="assign_work_manager_mobile" class="form-control"  placeholder="Mobile Number" readonly onkeyup="checkWorkManagerMobile()" />
										<span id="folio-invalid" style="color:#ff0000;display:none">Invalid mobile No</span>
										
									</div>
									<?php
										$currentTime = date("Hi");										
									?>
									<?php if($currentTime > 2000 || $currentTime < 800): ?>
										<div class="form-group col-md-3">
											<label for="night_spoc_1_name" >Night SPOC 1 Name</label>
											<input name="night_spoc_1_name" id="night_spoc_1_name" class="form-control" readonly/>
											
										</div>
										<div class="form-group col-md-3">
											<label for="night_spoc_1_number" >Night SPOC 1 Number</label>										
											<input name="night_spoc_1_number" id="night_spoc_1_number" class="form-control" readonly/>
										</div>

										<div class="form-group col-md-3">
											<label for="night_spoc_2_name" >Night SPOC 2 Name</label>
											<input name="night_spoc_2_name" id="night_spoc_2_name" class="form-control" readonly/>
											
										</div>
										<div class="form-group col-md-3">
											<label for="night_spoc_2_number" >Night SPOC 2 Number</label>										
											<input name="night_spoc_2_number" id="night_spoc_2_number" class="form-control" readonly/>
										</div>
									<?php endif; ?>
									<div class="form-group col-md-3">
										<label for="remark_type" >Ticket Status</label><span style="color: red;">*</span>
										<select name="remark_type" id="remark_type" class="form-control" required>
											<option value="">--Select--</option>
											<?php if(isset($remark_type)): ?>
												<?php $__currentLoopData = $remark_type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
													<?php $allowStatus = array(36,39); ?>
													<?php if((Auth::user()->role == '29' || Auth::user()->role == '30' || Auth::user()->role == '87') && in_array($row->id,$allowStatus)): ?>
														<option value="<?php echo e($row->type); ?>"><?php echo e($row->type); ?></option>
													<?php else: ?>
														<?php $tictStatus = array(32,33,34,35,36,13); ?>
														<?php if(!in_array($row->id,$tictStatus) && in_array($row->id,$allowStatus)): ?>
															<option value="<?php echo e($row->type); ?>"><?php echo e($row->type); ?></option>
														<?php endif; ?>
													<?php endif; ?>
												<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
												
											<?php endif; ?>
											
										</select> 
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
									
									
									<div class="form-group col-md-3">
										<label for="datefrom" >Est. Response Time</label>
										<span style="color: red;">*</span>
										<input type="text" name="estimated_response_time" id="estimated_response_time" autocomplete="off" class="form-control" >
										
									</div>
								
								<div class="form-group col-md-3">
			                        <label for="datefrom" >Actual Response Time as per Dealer</label>
									<input type="text" name="actual_response_time" id="actual_response_time" autocomplete="off" class="form-control" >
									
			                    </div>
								<div class="form-group col-md-3">
			                        <label for="datefrom" >Actual Restoration Time as per Dealer</label>
									<input type="text" name="tat_scheduled" id="tat_scheduled" autocomplete="off" class="form-control" >
									
			                    </div>
								<?php if(Auth::user()->role == '29' || Auth::user()->role == '30' || Auth::user()->role == '87'): ?>
									<div class="form-group col-md-3">
										<label for="datefrom" >Actual Response Time as per Customer</label>
										<input type="text" name="actual_response_time_customer" id="actual_response_time_customer" autocomplete="off" class="form-control" >
									</div>
									<div class="form-group col-md-3">
										<label for="datefrom" >Actual Restoration Time as per Customer</label>
										<input type="text" name="tat_scheduled_customer" id="tat_scheduled_customer" autocomplete="off" class="form-control" >
									</div>
								<?php endif; ?>
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
										<?php $__currentLoopData = $responseDelayReason; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<option value="<?php echo e($row->reason); ?>"><?php echo e($row->reason); ?></option>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
										
									</select>
								</div>
								<div class="form-group col-md-3">
			                        <label for="datefrom" >Acceptance</label>
									<div class="radio">
										<label><input type="radio" name="acceptance" value="1" checked >Yes</label>
										<label><input type="radio" name="acceptance" value="0">No</label>
									</div>
			                    </div>
								
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
			                        <label for="datefrom" >Remarks ( Don't put it special char and press enter for new line )<sup style="color: red;">*</sup></label>
									<textarea name="assign_remarks" id="assign_remarks" cols="30" rows="5" class="form-control" onkeydown="return (event.keyCode!=13 && event.keyCode!=222);"></textarea>
			                        <span id="engine_number_error" style="color:red"></span> 
			                    </div>
							</div>
							<br/>
							<div class="row" style="margin-bottom: 10px;" id="submitDiv">
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
	<script src="<?php echo e(asset('datapicker/js/jquery.datetimepicker.js')); ?>"></script>
	<link rel="stylesheet" href="<?php echo e(asset('datapicker/css/jquery.datetimepicker.min.css')); ?>">

 <script>
/* window.onload = () => {
 const myInput = document.getElementById('assign_remarks');
 myInput.onpaste = e => e.preventDefault();
} */
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
			$('#submitDiv').hide();
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
 		$.ajax({ url: '<?php echo e(url("dealer-search-function")); ?>',
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
		$.ajax({ url: '<?php echo e(url("get-caller-state-change")); ?>',
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
		 
		$.ajax({ url: '<?php echo e(url("get-assign-dealer-state-change")); ?>',
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
		$.ajax({ url: '<?php echo e(url("get-zone-change")); ?>',
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
		
		$.ajax({ url: '<?php echo e(url("get-state-id-city")); ?>',
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

		$('#actual_response_time_customer').datetimepicker(options).attr('readonly','readonly');
		$('#tat_scheduled_customer').datetimepicker(options).attr('readonly','readonly');
	});
	 function fn_state_change(stateId,ell){
		$.ajax({ url: '<?php echo e(url("get-stateChange")); ?>',
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
		$.ajax({ url: '<?php echo e(url("get-city")); ?>',
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
	function checkChassisNumber1(){
		var chassis_number1 = $('#chassis_number').val();
		if(chassis_number1 !=''){
			const chassis_numberCheck = chassis_number1 != null?chassis_number1.substring(0,3):null;
			var chassisLength = chassis_number1.length;		
			if(chassis_numberCheck != 'MB1' && chassisLength == '17'){
				alert("Enter Chassis Number isn't a Ashokleyland!!! Ticket creation restricted");
				$('#submit').hide();
				$('#getsearch').hide();
			}else{
				$('#submit').show();
				$('#getsearch').show();
			}
		}
	}
	function checkChassisNumber(){
		var chassis_number1 = $('#chassis_number1').val();
		const chassis_numberCheck = chassis_number1 != null?chassis_number1.substring(0,3):null;
		var chassisLength = chassis_number1.length;
		if(chassis_number1 !=''){
			if(chassis_numberCheck != 'MB1' && chassisLength == '17'){
				alert("Enter Chassis Number isn't a Ashokleyland!!! Ticket creation restricted")
				$('#submit').hide();
				$('#callerEdit').hide();
				$('#vehicleEdit').hide();
				$('#ownerEdit').hide();
			}else{
				$('#submit').show();
				$('#callerEdit').show();
				$('#vehicleEdit').show();
				$('#ownerEdit').show();
			}
		}
	}
	// function getData(reg_number,chassis_number,engine_number){  //  Without Vahana API
		
	// 	/* Store Vehicle info to table */
	// 		// $("#ajaxLoader").show();
	// 		// $.ajax({ url: '<?php echo e(url("get-vahan")); ?>',
	// 		// 	data: { 'reg_number':reg_number},
	// 		// 	success: function(result1){
	// 		// 		$("#ajaxLoader").hide();
	// 		// 		if(result1 == 'tokenError'){
	// 		// 			alert("VAHAN server error. Enter data manually and create ticket for Ashok Leyland vehicle only!!!");
	// 		// 		}
	// 		// 		if( result1== 'notcreate'){
	// 		// 			alert("Enter Chassis Number isn't a Ashokleyland!!! Ticket creation restricted");
	// 		// 			$('#submit').hide();
	// 		// 			$('#callerEdit').hide();
	// 		// 			$('#vehicleEdit').hide();
	// 		// 			$('#ownerEdit').hide();
	// 		// 		}
	// 		// 		if( result1== 'lcv'){
	// 		// 			alert("Entered registration number is AL LCV model vehicle!!! Ticket creation restricted");
	// 		// 			$('#submit').hide();
	// 		// 			$('#callerEdit').hide();
	// 		// 			$('#vehicleEdit').hide();
	// 		// 			$('#ownerEdit').hide();
	// 		// 		}
	// 		// 		if(result1 == 'noData'){
	// 		// 			alert("Data not available in VAHAN for this registration no. Check once again then enter data manually and create ticket for Ashok Leyland vehicle only!!!");
	// 		// 			$('#reg_number1').prop('readonly', false);
	// 		// 			$('#chassis_number1').prop('readonly', false);
	// 		// 			$('#engine_number1').prop('readonly', false);
	// 		// 			$('#vehicle_model').prop('readonly', false);
	// 		// 			$("#vehicle_segment").prop('readonly', false);
	// 		// 			$("#purchase_date").prop('readonly', false);
	// 		// 			$("#add_blue_use").prop('readonly', false);
	// 		// 			$('#engine_emmission_type1').prop('readonly', false);
	// 		// 			$('#owner_name_text').prop('readonly', false);
	// 		// 			$('#owner_mob').prop('readonly', false);

						
						
	// 		// 			$('#owner_name').hide();
	// 		// 			$('#engine_emmission_type').hide();
	// 		// 			$('#contact_name_select').hide();
	// 		// 			$('#owner_name_text').show();
	// 		// 			$("#owner_name").prop('disabled', true);
	// 		// 			$("#engine_emmission_type").prop('disabled', true);
	// 		// 			$("#engine_emmission_type1").prop('disabled', false);
	// 		// 			$("#contact_name_text").prop('disabled', false);
	// 		// 			$("#owner_name_text").prop('disabled', false);
						
	// 		// 			$("#contact_name_select").prop('disabled', true);
	// 		// 			$("#owner_mob").prop('readonly', false);
	// 		// 			$("#owner_landline").prop('readonly', false);
	// 		// 			$("#owner_cat").prop('readonly', false);
	// 		// 			$("#owner_contact_mob").prop('readonly', false);
	// 		// 			$("#owner_contact_email").prop('readonly', false);
	// 		// 			$('#vahan_status').val('');


	// 		// 			$('#submit').show();
	// 		// 			$('#callerEdit').show();
	// 		// 			$('#vehicleEdit').show();
	// 		// 			$('#ownerEdit').show();
	// 		// 			$('#ownerContactEdit').show();
	// 		// 			$('#engine_emmission_type1').show();
	// 		// 			$('#contact_name_text').show();
	// 		// 		}
	// 		// 	} 
	// 		// });
	// 	/* Store Vehicle info to table */
	// 	$.ajax({ url: '<?php echo e(url("check-registration-ticket")); ?>',
	// 		data: { 'reg_number':reg_number,'chassis_number':chassis_number,'engine_number':engine_number},
	// 		success: function(ReultAjax){
				
	// 			if(ReultAjax =='Yes'){
	// 				toastr.info("Given vehicle ticket is open");
	// 				$('#submit').hide();
					
	// 			}else{
					
	// 				$('#submit').show();
	// 				$.ajax({ url: '<?php echo e(url("get-vehicle-details")); ?>',
	// 					data: { 'reg_number':reg_number,'chassis_number':chassis_number,'engine_number':engine_number},
	// 					success: function(result){
	// 						console.log(result);
	// 						if(result =='no'){
	// 							// alert("There is no Vehicle");
	// 							// $('#callerEdit').show();
	// 							// $('#vehicleEdit').show();
	// 							$.ajax({ url: '<?php echo e(url("check-elite-reg")); ?>',
	// 								data: { 'reg_number':reg_number,'chassis_number':chassis_number,'engine_number':engine_number},
	// 								success: function(res){
	// 									if(res == "Yes"){
	// 										alert("Redirect to Elite Support");
	// 										window.location.href = "https://helpline.ashokleyland.com/elitesupport/autologin?id=<?php echo e(base64_encode(Auth::user()->id)); ?>";
	// 									}else{

	// 										// $('#callerEdit').show();
	// 										// $('#vehicleEdit').show();
	// 										// $('#ownerEdit').show();
	// 										// $('#ownerContactEdit').show();
	// 										// $('#engine_emmission_type1').show();
	// 										// $('#contact_name_text').show();
											
	// 										// $('#owner_name').hide();
	// 										// $('#engine_emmission_type').hide();
	// 										// $('#contact_name_select').hide();
	// 										// $('#owner_name_text').show();
	// 										// $("#owner_name").prop('disabled', true);
	// 										// $("#engine_emmission_type").prop('disabled', true);
	// 										// $("#engine_emmission_type1").prop('disabled', false);
	// 										// $("#contact_name_text").prop('disabled', false);
	// 										// $("#owner_name_text").prop('disabled', false);
											
	// 										// $("#contact_name_select").prop('disabled', true);
	// 										// $("#owner_mob").prop('readonly', false);
	// 										// $("#owner_landline").prop('readonly', false);
	// 										// $("#owner_cat").prop('readonly', false);
	// 										// $("#owner_contact_mob").prop('readonly', false);
	// 										// $("#owner_contact_email").prop('readonly', false);
	// 										/************************* Vahan API Enable ******************************/
	// 											// $.ajax({ url: '<?php echo e(url("get-vahan")); ?>',
	// 											// 	data: { 'reg_number':reg_number},
	// 											// 	success: function(res1){
														
	// 											// 		if(res1 == 'tokenError'){
															
	// 											// 			$('#reg_number1').prop('readonly', false);
	// 											// 			$('#chassis_number1').prop('readonly', false);
	// 											// 			$('#engine_number1').prop('readonly', false);
	// 											// 			$('#vehicle_model').prop('readonly', false);
	// 											// 			$("#vehicle_segment").prop('readonly', false);
	// 											// 			$("#purchase_date").prop('readonly', false);
	// 											// 			$("#add_blue_use").prop('readonly', false);
	// 											// 			$('#engine_emmission_type1').prop('readonly', false);
	// 											// 			$('#owner_name_text').prop('readonly', false);
	// 											// 			$('#owner_mob').prop('readonly', false);													
															
	// 											// 			$('#owner_name').hide();
	// 											// 			$('#engine_emmission_type').hide();
	// 											// 			$('#contact_name_select').hide();
	// 											// 			$('#owner_name_text').show();
	// 											// 			$("#owner_name").prop('disabled', true);
	// 											// 			$("#engine_emmission_type").prop('disabled', true);
	// 											// 			$("#engine_emmission_type1").prop('disabled', false);
	// 											// 			$("#contact_name_text").prop('disabled', false);
	// 											// 			$("#owner_name_text").prop('disabled', false);
															
	// 											// 			$("#contact_name_select").prop('disabled', true);
	// 											// 			$("#owner_mob").prop('readonly', false);
	// 											// 			$("#owner_landline").prop('readonly', false);
	// 											// 			$("#owner_cat").prop('readonly', false);
	// 											// 			$("#owner_contact_mob").prop('readonly', false);
	// 											// 			$("#owner_contact_email").prop('readonly', false);
	// 											// 			$('#vahan_status').val('');

	// 											// 			$('#reg_number1').val('');
	// 											// 			$('#chassis_number1').val('');
	// 											// 			$('#engine_number1').val('');
	// 											// 			$('#vehicle_model').val('');
	// 											// 			$("#vehicle_segment").val('');
	// 											// 			$("#purchase_date").val('');
	// 											// 			$("#add_blue_use").val('');
	// 											// 			$('#engine_emmission_type1').val('');
	// 											// 			$('#owner_name_text').val('');
	// 											// 			$('#owner_mob').val('');

	// 											// 			$('#submit').show();
	// 											// 			$('#callerEdit').show();
	// 											// 			$('#vehicleEdit').show();
	// 											// 			$('#ownerEdit').show();
	// 											// 			$('#ownerContactEdit').show();
	// 											// 			$('#engine_emmission_type1').show();
	// 											// 			$('#contact_name_text').show();
															
	// 											// 		}else{
															
	// 											// 			const myJSON = JSON.parse(res1);
	// 											// 			const reg_number1 = myJSON.rc_number;
															
	// 											// 			const chassis_number1 = myJSON.vehicle_chasi_number;
															
	// 											// 			const engine_number1 = myJSON.vehicle_engine_number;
	// 											// 			const vehicle_model = myJSON.maker_model;
	// 											// 			const maker_description = myJSON.maker_description;
	// 											// 			const vehicle_segment = '';
	// 											// 			const purchase_date ='';
	// 											// 			const engine_emmission_type1 = (myJSON.norms_type =='BHARAT STAGE VI')?'BS6':'Non BS6';
	// 											// 			const owner_name_text = myJSON.owner_name;
	// 											// 			const owner_mob = myJSON.mobile_number;
	// 											// 			const vehicle_category = myJSON.vehicle_category;
	// 											// 			const add_blue_use = engine_emmission_type1 == 'BS6'?'Yes':'No';
	// 											// 			const chassis_numberCheck = chassis_number1 != null?chassis_number1.substring(0,3):null;
	// 											// 			if(chassis_numberCheck == 'MB1' && vehicle_category == 'LGV' && chassis_number1 != null){
																
	// 											// 				alert("Entered registration number is AL LCV model vehicle!!! Ticket creation restricted");
	// 											// 				$('#vahan_status').val('');
	// 											// 				$('#submit').hide();
	// 											// 				$('#callerEdit').hide();
	// 											// 				$('#vehicleEdit').hide();
	// 											// 				$('#ownerEdit').hide();
	// 											// 				$('#ownerContactEdit').hide();
	// 											// 				$('#engine_emmission_type1').hide();
	// 											// 				$('#contact_name_text').hide();

	// 											// 				$('#reg_number1').val('');
	// 											// 				$('#chassis_number1').val('');
	// 											// 				$('#engine_number1').val('');
	// 											// 				$('#vehicle_model').val('');
	// 											// 				$("#vehicle_segment").val('');
	// 											// 				$("#purchase_date").val('');
	// 											// 				$("#add_blue_use").val('');
	// 											// 				$('#engine_emmission_type1').val('');
	// 											// 				$('#owner_name_text').val('');
	// 											// 				$('#owner_mob').val('');
	// 											// 			}else if((chassis_numberCheck == 'MB1' || maker_description == 'ASHOK LEYLAND LTD') && chassis_number1 != null){
																
	// 											// 				$('#vahan_status').val('Yes');
	// 											// 				$('#submit').show();
	// 											// 				$('#reg_number1').val(reg_number1);
	// 											// 				$('#chassis_number1').val(chassis_number1);
	// 											// 				$('#engine_number1').val(engine_number1);
	// 											// 				$('#vehicle_model').val(vehicle_model);
	// 											// 				$("#vehicle_segment").val(vehicle_segment);
	// 											// 				$("#purchase_date").val(purchase_date);
	// 											// 				$("#add_blue_use").val(add_blue_use);
	// 											// 				$('#engine_emmission_type1').val(engine_emmission_type1);
	// 											// 				$('#owner_name_text').val(owner_name_text);
	// 											// 				$('#owner_mob').val(owner_mob);

	// 											// 				/* Fields */
	// 											// 				$('#reg_number1').prop('readonly', true);
	// 											// 				$('#chassis_number1').prop('readonly', true);
	// 											// 				$('#engine_number1').prop('readonly', true);
	// 											// 				$('#vehicle_model').prop('readonly', true);
	// 											// 				if(vehicle_segment ==''){
	// 											// 					$("#vehicle_segment").prop('readonly', false);
	// 											// 				}else{
	// 											// 					$("#vehicle_segment").prop('readonly', true);
	// 											// 				}
	// 											// 				if(purchase_date ==''){
	// 											// 					$("#purchase_date").prop('readonly', false);
	// 											// 				}else{
	// 											// 					$("#purchase_date").prop('readonly', true);
	// 											// 				}	
																
	// 											// 				$("#add_blue_use").prop('readonly', true);
	// 											// 				$('#engine_emmission_type1').prop('readonly', true);
	// 											// 				$('#owner_name_text').prop('readonly', true);
	// 											// 				$('#owner_mob').prop('readonly', true);

	// 											// 				$('#callerEdit').show();
	// 											// 				$('#vehicleEdit').show();
	// 											// 				$('#ownerEdit').show();
	// 											// 				$('#ownerContactEdit').show();
	// 											// 				$('#engine_emmission_type1').show();
	// 											// 				$('#contact_name_text').show();
																
	// 											// 				$('#owner_name').hide();
	// 											// 				$('#engine_emmission_type').hide();
	// 											// 				$('#contact_name_select').hide();
	// 											// 				$('#owner_name_text').show();
	// 											// 				$("#owner_name").prop('disabled', true);
	// 											// 				$("#engine_emmission_type").prop('disabled', true);
	// 											// 				$("#engine_emmission_type1").prop('disabled', false);
	// 											// 				$("#contact_name_text").prop('disabled', false);
	// 											// 				$("#owner_name_text").prop('disabled', false);
																
	// 											// 				$("#contact_name_select").prop('disabled', true);
	// 											// 				$("#owner_mob").prop('readonly', false);
	// 											// 				$("#owner_landline").prop('readonly', false);
	// 											// 				$("#owner_cat").prop('readonly', false);
	// 											// 				$("#owner_contact_mob").prop('readonly', false);
	// 											// 				$("#owner_contact_email").prop('readonly', false);
	// 											// 				/* Fields */


																
	// 											// 			}else if(chassis_number1 == null){
	// 											// 				alert("Data not available in VAHAN for this registration no. Check once again then enter data manually and create ticket for Ashok Leyland vehicle only!!!");
	// 											// 					$('#reg_number1').prop('readonly', false);
	// 											// 					$('#chassis_number1').prop('readonly', false);
	// 											// 					$('#engine_number1').prop('readonly', false);
	// 											// 					$('#vehicle_model').prop('readonly', false);
	// 											// 					$("#vehicle_segment").prop('readonly', false);
	// 											// 					$("#purchase_date").prop('readonly', false);
	// 											// 					$("#add_blue_use").prop('readonly', false);
	// 											// 					$('#engine_emmission_type1').prop('readonly', false);
	// 											// 					$('#owner_name_text').prop('readonly', false);
	// 											// 					$('#owner_mob').prop('readonly', false);

																	
																	
	// 											// 					$('#owner_name').hide();
	// 											// 					$('#engine_emmission_type').hide();
	// 											// 					$('#contact_name_select').hide();
	// 											// 					$('#owner_name_text').show();
	// 											// 					$("#owner_name").prop('disabled', true);
	// 											// 					$("#engine_emmission_type").prop('disabled', true);
	// 											// 					$("#engine_emmission_type1").prop('disabled', false);
	// 											// 					$("#contact_name_text").prop('disabled', false);
	// 											// 					$("#owner_name_text").prop('disabled', false);
																	
	// 											// 					$("#contact_name_select").prop('disabled', true);
	// 											// 					$("#owner_mob").prop('readonly', false);
	// 											// 					$("#owner_landline").prop('readonly', false);
	// 											// 					$("#owner_cat").prop('readonly', false);
	// 											// 					$("#owner_contact_mob").prop('readonly', false);
	// 											// 					$("#owner_contact_email").prop('readonly', false);
	// 											// 					$('#vahan_status').val('');

	// 											// 					$('#reg_number1').val('');
	// 											// 					$('#chassis_number1').val('');
	// 											// 					$('#engine_number1').val('');
	// 											// 					$('#vehicle_model').val('');
	// 											// 					$("#vehicle_segment").val('');
	// 											// 					$("#purchase_date").val('');
	// 											// 					$("#add_blue_use").val('');
	// 											// 					$('#engine_emmission_type1').val('');
	// 											// 					$('#owner_name_text').val('');
	// 											// 					$('#owner_mob').val('');


	// 											// 					$('#submit').show();
	// 											// 					$('#callerEdit').show();
	// 											// 					$('#vehicleEdit').show();
	// 											// 					$('#ownerEdit').show();
	// 											// 					$('#ownerContactEdit').show();
	// 											// 					$('#engine_emmission_type1').show();
	// 											// 					$('#contact_name_text').show();
	// 											// 			}else if(chassis_numberCheck != 'MB1' && chassis_number1 != null){
																
	// 											// 				/* alert("Data not available in VAHAN for this registration no. Check once again then enter data manually and create ticket for Ashok Leyland vehicle only!!!"); */
	// 											// 				alert("Entered registration number is not a AL vehicle!!! Ticket creation restricted");

	// 											// 				/* Fields */

	// 											// 					/* $('#reg_number1').val(reg_number1);
	// 											// 					$('#chassis_number1').val(chassis_number1);
	// 											// 					$('#engine_number1').val(engine_number1);
	// 											// 					$('#vehicle_model').val(vehicle_model);
	// 											// 					$("#vehicle_segment").val(vehicle_segment);
	// 											// 					$("#purchase_date").val(purchase_date);
	// 											// 					$("#add_blue_use").val(add_blue_use);
	// 											// 					$('#engine_emmission_type1').val(engine_emmission_type1);
	// 											// 					$('#owner_name_text').val(owner_name_text);
	// 											// 					$('#owner_mob').val(owner_mob); */
	// 											// 					$('#reg_number1').prop('readonly', false);
	// 											// 					$('#chassis_number1').prop('readonly', false);
	// 											// 					$('#engine_number1').prop('readonly', false);
	// 											// 					$('#vehicle_model').prop('readonly', false);
	// 											// 					$("#vehicle_segment").prop('readonly', false);
	// 											// 					$("#purchase_date").prop('readonly', false);
	// 											// 					$("#add_blue_use").prop('readonly', false);
	// 											// 					$('#engine_emmission_type1').prop('readonly', false);
	// 											// 					$('#owner_name_text').prop('readonly', false);
	// 											// 					$('#owner_mob').prop('readonly', false);

																	
																	
	// 											// 					$('#owner_name').hide();
	// 											// 					$('#engine_emmission_type').hide();
	// 											// 					$('#contact_name_select').hide();
	// 											// 					$('#owner_name_text').show();
	// 											// 					$("#owner_name").prop('disabled', true);
	// 											// 					$("#engine_emmission_type").prop('disabled', true);
	// 											// 					$("#engine_emmission_type1").prop('disabled', false);
	// 											// 					$("#contact_name_text").prop('disabled', false);
	// 											// 					$("#owner_name_text").prop('disabled', false);
																	
	// 											// 					$("#contact_name_select").prop('disabled', true);
	// 											// 					$("#owner_mob").prop('readonly', false);
	// 											// 					$("#owner_landline").prop('readonly', false);
	// 											// 					$("#owner_cat").prop('readonly', false);
	// 											// 					$("#owner_contact_mob").prop('readonly', false);
	// 											// 					$("#owner_contact_email").prop('readonly', false);
	// 											// 					$('#vahan_status').val('');

	// 											// 					$('#reg_number1').val('');
	// 											// 					$('#chassis_number1').val('');
	// 											// 					$('#engine_number1').val('');
	// 											// 					$('#vehicle_model').val('');
	// 											// 					$("#vehicle_segment").val('');
	// 											// 					$("#purchase_date").val('');
	// 											// 					$("#add_blue_use").val('');
	// 											// 					$('#engine_emmission_type1').val('');
	// 											// 					$('#owner_name_text').val('');
	// 											// 					$('#owner_mob').val('');

	// 											// 					$('#submit').hide();
	// 											// 					$('#callerEdit').hide();
	// 											// 					$('#vehicleEdit').hide();
	// 											// 					$('#ownerEdit').hide();
	// 											// 					$('#ownerContactEdit').hide();
	// 											// 					$('#engine_emmission_type1').hide();
	// 											// 					$('#contact_name_text').hide();
	// 											// 				/* Fields */
	// 											// 			}
															
	// 											// 		}
														
	// 											// 	}
	// 											// });
											
	// 										/************************* Vahan API Enable End ******************************/
	// 										/************************* Vahan API Disabled******************************/
	// 											$('#reg_number1').prop('readonly', false);
	// 											$('#chassis_number1').prop('readonly', false);
	// 											$('#engine_number1').prop('readonly', false);
	// 											$('#vehicle_model').prop('readonly', false);
	// 											$("#vehicle_segment").prop('readonly', false);
	// 											$("#purchase_date").prop('readonly', false);
	// 											$("#add_blue_use").prop('readonly', false);
	// 											$('#engine_emmission_type1').prop('readonly', false);
	// 											$('#owner_name_text').prop('readonly', false);
	// 											$('#owner_mob').prop('readonly', false);

												
												
	// 											$('#owner_name').hide();
	// 											$('#engine_emmission_type').hide();
	// 											$('#contact_name_select').hide();
	// 											$('#owner_name_text').show();
	// 											$("#owner_name").prop('disabled', true);
	// 											$("#engine_emmission_type").prop('disabled', true);
	// 											$("#engine_emmission_type1").prop('disabled', false);
	// 											$("#contact_name_text").prop('disabled', false);
	// 											$("#owner_name_text").prop('disabled', false);
												
	// 											$("#contact_name_select").prop('disabled', true);
	// 											$("#owner_mob").prop('readonly', false);
	// 											$("#owner_landline").prop('readonly', false);
	// 											$("#owner_cat").prop('readonly', false);
	// 											$("#owner_contact_mob").prop('readonly', false);
	// 											$("#owner_contact_email").prop('readonly', false);
	// 											$('#vahan_status').val('');

	// 											$('#reg_number1').val('');
	// 											$('#chassis_number1').val('');
	// 											$('#engine_number1').val('');
	// 											$('#vehicle_model').val('');
	// 											$("#vehicle_segment").val('');
	// 											$("#purchase_date").val('');
	// 											$("#add_blue_use").val('');
	// 											$('#engine_emmission_type1').val('');
	// 											$('#owner_name_text').val('');
	// 											$('#owner_mob').val('');


	// 											$('#submit').show();
	// 											$('#callerEdit').show();
	// 											$('#vehicleEdit').show();
	// 											$('#ownerEdit').show();
	// 											$('#ownerContactEdit').show();
	// 											$('#engine_emmission_type1').show();
	// 											$('#contact_name_text').show();
	// 										/************************* Vahan API Disabled End ******************************/
	// 									}

	// 								}
	// 							});
								
	// 						}else{
	// 							$('#callerEdit').show();
	// 							//$('#vehicleEdit').show();
	// 							var res = result.split('~~');
	// 							$('#vehicleId').val(res[0]);
	// 							$('#chassis_number').val(res[3]);
	// 							$('#engine_number').val(res[4]);
	// 							$('#reg_number1').val(res[2]);
	// 							$('#chassis_number1').val(res[3]);
	// 							$('#engine_number1').val(res[4]);
	// 							$('#vehicle_model').val(res[1]);
	// 							$('#vehicle_segment').val(res[5]);
	// 							$('#purchase_date').val(res[6]);
	// 							$('#add_blue_use').val(res[7]);
	// 							$('#engine_emmission_type').val(res[9]);
	// 							// $('#owner_name').val(res[10]);
	// 							$('#owner_name').append(`<option value="${res[10]}" selected>${res[11]}</option>`);
	// 							var ownerName = res[11];
	// 							var ownerId = res[10];
	// 							var contact_name = res[29];
	// 							$('#owner_mob').val(res[12]);
	// 							$('#owner_landline').val(res[13]);
	// 							$('#owner_cat').val(res[14]);
	// 							$('#owner_company').val(res[15]);
	// 							$('#ownerId').val(res[10]);
	// 							$('#alse_mail').val(res[31]);
	// 							$('#asm_mail').val(res[32]);
	// 							var ownerId = res[10];
	// 							$('#owenerContactId').val(res[16]);
	// 							$('#owner_contact_mob').val(res[17]);  
	// 							$('#contact_name_select').val(res[16]);
	// 							$('#owner_contact_email').val(res[30]);
	// 						}
								
	// 					}
	// 				});					
	// 			}					
	// 		}
	// 	});
		
	
 	// }
	function getData(reg_number,chassis_number,engine_number){  //  Vahan API Call

		/* Store Vehicle info to table */
			// $("#ajaxLoader").show();
			// $.ajax({ url: '<?php echo e(url("get-vahan")); ?>',
			// 	data: { 'reg_number':reg_number},
			// 	success: function(result1){
			// 		$("#ajaxLoader").hide();
			// 		if(result1 == 'tokenError'){
			// 			alert("VAHAN server error. Enter data manually and create ticket for Ashok Leyland vehicle only!!!");
			// 		}
			// 		if( result1== 'notcreate'){
			// 			alert("Enter Chassis Number isn't a Ashokleyland!!! Ticket creation restricted");
			// 			$('#submit').hide();
			// 			$('#callerEdit').hide();
			// 			$('#vehicleEdit').hide();
			// 			$('#ownerEdit').hide();
			// 		}
			// 		if( result1== 'lcv'){
			// 			alert("Entered registration number is AL LCV model vehicle!!! Ticket creation restricted");
			// 			$('#submit').hide();
			// 			$('#callerEdit').hide();
			// 			$('#vehicleEdit').hide();
			// 			$('#ownerEdit').hide();
			// 		}
			// 		if(result1 == 'noData'){
			// 			alert("Data not available in VAHAN for this registration no. Check once again then enter data manually and create ticket for Ashok Leyland vehicle only!!!");
			// 			$('#reg_number1').prop('readonly', false);
			// 			$('#chassis_number1').prop('readonly', false);
			// 			$('#engine_number1').prop('readonly', false);
			// 			$('#vehicle_model').prop('readonly', false);
			// 			$("#vehicle_segment").prop('readonly', false);
			// 			$("#purchase_date").prop('readonly', false);
			// 			$("#add_blue_use").prop('readonly', false);
			// 			$('#engine_emmission_type1').prop('readonly', false);
			// 			$('#owner_name_text').prop('readonly', false);
			// 			$('#owner_mob').prop('readonly', false);

						
						
			// 			$('#owner_name').hide();
			// 			$('#engine_emmission_type').hide();
			// 			$('#contact_name_select').hide();
			// 			$('#owner_name_text').show();
			// 			$("#owner_name").prop('disabled', true);
			// 			$("#engine_emmission_type").prop('disabled', true);
			// 			$("#engine_emmission_type1").prop('disabled', false);
			// 			$("#contact_name_text").prop('disabled', false);
			// 			$("#owner_name_text").prop('disabled', false);
						
			// 			$("#contact_name_select").prop('disabled', true);
			// 			$("#owner_mob").prop('readonly', false);
			// 			$("#owner_landline").prop('readonly', false);
			// 			$("#owner_cat").prop('readonly', false);
			// 			$("#owner_contact_mob").prop('readonly', false);
			// 			$("#owner_contact_email").prop('readonly', false);
			// 			$('#vahan_status').val('');


			// 			$('#submit').show();
			// 			$('#callerEdit').show();
			// 			$('#vehicleEdit').show();
			// 			$('#ownerEdit').show();
			// 			$('#ownerContactEdit').show();
			// 			$('#engine_emmission_type1').show();
			// 			$('#contact_name_text').show();
			// 		}
			// 	} 
			// });
		/* Store Vehicle info to table */

		//console.log(reg_number);

		if(reg_number == '' && chassis_number == '' && engine_number == ''){
			alert("Please Enter the Registration Number");
			$('#reg_number').focus();
		}else{
			$.ajax({ url: '<?php echo e(url("check-registration-ticket")); ?>',
				data: { 'reg_number':reg_number,'chassis_number':chassis_number,'engine_number':engine_number},
				success: function(ReultAjax){	
					console.log(ReultAjax);			
					if(ReultAjax =='Yes'){
						toastr.info("Given vehicle ticket is open");
						$('#submit').hide();					
					}else{					
						$('#submit').show();
						$.ajax({ url: '<?php echo e(url("check-elite-reg")); ?>',
							data: { 'reg_number':reg_number,'chassis_number':chassis_number,'engine_number':engine_number},
							success: function(res){							
								if(res == "Yes"){
									alert("Redirect to Elite Support");
									window.location.href = "https://helpline.ashokleyland.com/elitesupport/autologin?id=<?php echo e(base64_encode(Auth::user()->id)); ?>";
								}else{
									$.ajax({ url: '<?php echo e(url("get-vehicle-details")); ?>',
										data: { 'reg_number':reg_number,'chassis_number':chassis_number,'engine_number':engine_number},
										success: function(result){
											console.log("result",result);
											var resVahan = result.split('##');
											
											if(resVahan[0] =='no'){
												if(reg_number !=''){
													$.ajax({ url: '<?php echo e(url("get-vahan")); ?>',
														data: { 'reg_number':reg_number},
														success: function(res1){
															
															
															if(res1 == 'tokenError'){
																alert("VAHAN server error. Enter data manually and create ticket for Ashok Leyland vehicle only!!!");
																$('#chassis_number1').prop('readonly', false);
																$('#engine_number1').prop('readonly', false);
																$('#vehicle_model').prop('readonly', false);
																$("#vehicle_segment").prop('readonly', false);
																$("#purchase_date").prop('readonly', false);
																$("#add_blue_use").prop('readonly', false);
																$('#engine_emmission_type1').prop('readonly', false);
																$('#owner_name_text').prop('readonly', false);
																$('#owner_mob').prop('readonly', false);
																
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
																$('#vahan_status').val('');


																$('#submit').show();
																$('#callerEdit').show();
																$('#vehicleEdit').show();
																$('#ownerEdit').show();
																$('#ownerContactEdit').show();
																$('#engine_emmission_type1').show();
																$('#contact_name_text').show();
															}else if( res1== 'notcreate'){
																alert("Enter Chassis Number isn't a Ashokleyland!!! Ticket creation restricted");
																$('#submit').hide();
																$('#callerEdit').hide();
																$('#vehicleEdit').hide();
																$('#ownerEdit').hide();
															}else if( res1== 'lcv'){
																alert("Entered registration number is AL LCV model vehicle!!! Ticket creation restricted");
																$('#submit').hide();
																$('#callerEdit').hide();
																$('#vehicleEdit').hide();
																$('#ownerEdit').hide();
															}else if(res1 == 'noData'){
																alert("Data not available in VAHAN for this registration no. Check once again then enter data manually and create ticket for Ashok Leyland vehicle only!!!");
																$('#reg_number1').prop('readonly', false);
																$('#chassis_number1').prop('readonly', false);
																$('#engine_number1').prop('readonly', false);
																$('#vehicle_model').prop('readonly', false);
																$("#vehicle_segment").prop('readonly', false);
																$("#purchase_date").prop('readonly', false);
																$("#add_blue_use").prop('readonly', false);
																$('#engine_emmission_type1').prop('readonly', false);
																$('#owner_name_text').prop('readonly', false);
																$('#owner_mob').prop('readonly', false);

																
																
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
																$('#vahan_status').val('');


																$('#submit').show();
																$('#callerEdit').show();
																$('#vehicleEdit').show();
																$('#ownerEdit').show();
																$('#ownerContactEdit').show();
																$('#engine_emmission_type1').show();
																$('#contact_name_text').show();
															}else{
																
																const myJSON = JSON.parse(res1);
																const reg_number1 = myJSON.rc_number;
																
																const chassis_number1 = myJSON.vehicle_chasi_number;
																
																const engine_number1 = myJSON.vehicle_engine_number;
																const vehicle_model = myJSON.maker_model;
																const maker_description = myJSON.maker_description;
																const vehicle_segment = '';
																const purchase_date ='';
																const engine_emmission_type1 = (myJSON.norms_type =='BHARAT STAGE VI')?'BS6':'Non BS6';
																const owner_name_text = myJSON.owner_name;
																const owner_mob = myJSON.mobile_number;
																const vehicle_category = myJSON.vehicle_category;
																const add_blue_use = engine_emmission_type1 == 'BS6'?'Yes':'No';
																const chassis_numberCheck = chassis_number1 != null?chassis_number1.substring(0,3):null;
																if(chassis_numberCheck == 'MB1' && vehicle_category == 'LGV' && chassis_number1 != null){
																	alert("Entered registration number is AL LCV model vehicle!!! Ticket creation restricted");
																	$('#vahan_status').val('');
																	$('#submit').hide();
																	$('#callerEdit').hide();
																	$('#vehicleEdit').hide();
																	$('#ownerEdit').hide();
																	$('#ownerContactEdit').hide();
																	$('#engine_emmission_type1').hide();
																	$('#contact_name_text').hide();
																}else if((chassis_numberCheck == 'MB1' || maker_description == 'ASHOK LEYLAND LTD') && chassis_number1 != null){
																	
																	$('#vahan_status').val('Yes');
																	$('#submit').show();
																	$('#reg_number1').val(reg_number1);
																	$('#chassis_number1').val(chassis_number1);
																	$('#engine_number1').val(engine_number1);
																	$('#vehicle_model').val(vehicle_model);
																	$("#vehicle_segment").val(vehicle_segment);
																	$("#purchase_date").val(purchase_date);
																	$("#add_blue_use").val(add_blue_use);
																	$('#engine_emmission_type1').val(engine_emmission_type1);
																	$('#owner_name_text').val(owner_name_text);
																	$('#owner_mob').val(owner_mob);

																	/* Fields */
																	$('#reg_number1').prop('readonly', true);
																	$('#chassis_number1').prop('readonly', true);
																	$("#chassis_number1").removeAttr("onblur");
																	$('#engine_number1').prop('readonly', true);
																	$('#vehicle_model').prop('readonly', true);
																	if(vehicle_segment ==''){
																		$("#vehicle_segment").prop('readonly', false);
																	}else{
																		$("#vehicle_segment").prop('readonly', true);
																	}
																	if(purchase_date ==''){
																		$("#purchase_date").prop('readonly', false);
																	}else{
																		$("#purchase_date").prop('readonly', true);
																	}	
																	
																	$("#add_blue_use").prop('readonly', true);
																	$('#engine_emmission_type1').prop('readonly', true);
																	$('#owner_name_text').prop('readonly', true);
																	$('#owner_mob').prop('readonly', true);

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
																	/* Fields */


																	
																}else if(chassis_number1 == null){
																	alert("Data not available in VAHAN for this registration no. Check once again then enter data manually and create ticket for Ashok Leyland vehicle only!!!");
																	$('#reg_number1').prop('readonly', false);
																		$('#chassis_number1').prop('readonly', false);
																		$('#engine_number1').prop('readonly', false);
																		$('#vehicle_model').prop('readonly', false);
																		$("#vehicle_segment").prop('readonly', false);
																		$("#purchase_date").prop('readonly', false);
																		$("#add_blue_use").prop('readonly', false);
																		$('#engine_emmission_type1').prop('readonly', false);
																		$('#owner_name_text').prop('readonly', false);
																		$('#owner_mob').prop('readonly', false);
																		
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
																		$('#vahan_status').val('');


																		$('#submit').show();
																		$('#callerEdit').show();
																		$('#vehicleEdit').show();
																		$('#ownerEdit').show();
																		$('#ownerContactEdit').show();
																		$('#engine_emmission_type1').show();
																		$('#contact_name_text').show();
																}else if(chassis_numberCheck != 'MB1' && chassis_number1 != null){
																	alert("Entered registration number is not a AL vehicle!!! Ticket creation restricted");
																		$('#reg_number1').prop('readonly', false);
																		$('#chassis_number1').prop('readonly', false);
																		$('#engine_number1').prop('readonly', false);
																		$('#vehicle_model').prop('readonly', false);
																		$("#vehicle_segment").prop('readonly', false);
																		$("#purchase_date").prop('readonly', false);
																		$("#add_blue_use").prop('readonly', false);
																		$('#engine_emmission_type1').prop('readonly', false);
																		$('#owner_name_text').prop('readonly', false);
																		$('#owner_mob').prop('readonly', false);

																		
																		
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
																		$('#vahan_status').val('');


																		$('#submit').hide();
																		$('#callerEdit').hide();
																		$('#vehicleEdit').hide();
																		$('#ownerEdit').hide();
																		$('#ownerContactEdit').hide();
																		$('#engine_emmission_type1').hide();
																		$('#contact_name_text').hide();
																	/* Fields */
																}
																
															}
															
														}
													});
												}else{
													$('#reg_number1').prop('readonly', false);
													$('#chassis_number1').prop('readonly', false);
													$('#engine_number1').prop('readonly', false);
													$('#vehicle_model').prop('readonly', false);
													$("#vehicle_segment").prop('readonly', false);
													$("#purchase_date").prop('readonly', false);
													$("#add_blue_use").prop('readonly', false);
													$('#engine_emmission_type1').prop('readonly', false);
													$('#owner_name_text').prop('readonly', false);
													$('#owner_mob').prop('readonly', false);												
													
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
													$('#vahan_status').val('');


													$('#submit').show();
													$('#callerEdit').show();
													$('#vehicleEdit').show();
													$('#ownerEdit').show();
													$('#ownerContactEdit').show();
													$('#engine_emmission_type1').show();
													$('#contact_name_text').show();
												}
											}else{
												if(resVahan[0] == 'notcreate'){
													alert("Enter Chassis Number isn't a Ashokleyland!!! Ticket creation restricted");
													$('#submit').hide();
													$('#callerEdit').hide();
													$('#vehicleEdit').hide();
													$('#ownerEdit').hide();
												}else if(resVahan[0] == 'lcv'){
													alert("Entered registration number is AL LCV model vehicle!!! Ticket creation restricted");
													$('#submit').hide();
													$('#callerEdit').hide();
													$('#vehicleEdit').hide();
													$('#ownerEdit').hide();
												}else if(resVahan[0] == 'noData'){
													alert("Data not available in VAHAN for this registration no. Check once again then enter data manually and create ticket for Ashok Leyland vehicle only!!!");
													$('#reg_number1').prop('readonly', false);
													$('#chassis_number1').prop('readonly', false);
													$('#engine_number1').prop('readonly', false);
													$('#vehicle_model').prop('readonly', false);
													$("#vehicle_segment").prop('readonly', false);
													$("#purchase_date").prop('readonly', false);
													$("#add_blue_use").prop('readonly', false);
													$('#engine_emmission_type1').prop('readonly', false);
													$('#owner_name_text').prop('readonly', false);
													$('#owner_mob').prop('readonly', false);												
													
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
													$('#vahan_status').val('');


													$('#submit').show();
													$('#callerEdit').show();
													$('#vehicleEdit').show();
													$('#ownerEdit').show();
													$('#ownerContactEdit').show();
													$('#engine_emmission_type1').show();
													$('#contact_name_text').show();
												}else if(resVahan[0] == 'vahanData'){	
																			
													var vehicle_segment = '';
													var purchase_date = '';											
													$('#submit').show();
													$('#reg_number1').val(resVahan[1]);
													$('#chassis_number1').val(resVahan[2]);
													$('#engine_number1').val(resVahan[3]);
													$('#vehicle_model').val(resVahan[4]);
													
													$("#add_blue_use").val(resVahan[10]);
													$('#engine_emmission_type1').val(resVahan[6]);
													$('#owner_name_text').val(resVahan[7]);
													$('#owner_mob').val(resVahan[8]);

													/* Fields */
													$('#reg_number1').prop('readonly', true);
													$('#chassis_number1').prop('readonly', true);
													$("#chassis_number1").removeAttr("onblur");
													$('#engine_number1').prop('readonly', true);
													$('#vehicle_model').prop('readonly', true);
													if(vehicle_segment ==''){
														$("#vehicle_segment").prop('readonly', false);
													}else{
														$("#vehicle_segment").prop('readonly', true);
													}
													if(purchase_date ==''){
														$("#purchase_date").prop('readonly', false);
													}else{
														$("#purchase_date").prop('readonly', true);
													}	
													
													$("#add_blue_use").prop('readonly', true);
													$('#engine_emmission_type1').prop('readonly', true);
													$('#owner_name_text').prop('readonly', true);
													$('#owner_mob').prop('readonly', true);

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
												}else{
													$('#reg_number1').prop('readonly', true);
													$('#chassis_number1').prop('readonly', true);
													$("#chassis_number1").removeAttr("onblur");
													$('#engine_number1').prop('readonly', true);
													$('#vehicle_model').prop('readonly', true);	
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
													/* $('#owner_name').val(res[10]); */
													var ownerName = res[11];
													// alert(res[10]);
													$('#owner_name').append(`<option value="${res[10]}" selected>${res[11]}</option>`);
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
												
										}
									});	

								}
							}
						});											
					}					
				}
			});
		}


	}


 function ownerContactNameData(id){
	$('#ownerId').val(id);
	$.ajax({ url: '<?php echo e(url("get-owner-change")); ?>',
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
	$.ajax({ url: '<?php echo e(url("get-owner-contact-change")); ?>',
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
		$.ajax({ url: '<?php echo e(url("send-latlong-link")); ?>',
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
/* ********************************** Night Spoc Person *************************************************** */
function getNightSpoc(id,ell){
			var currentTime='<?php echo e($currentTime); ?>';
			if(currentTime > 2000 || currentTime < 800){
				$.ajax({ url: '<?php echo e(url("get-night-spoc")); ?>',
					data: { 'id':id},
					success: function(response){
						console.log(response);
						
						var arr = response.split("&&");
						var str = '';
						var str2 = '';
						var spoc1Arr = arr[0].split("~~");
						
						var spoc2 = arr[1].split("~~");
						// spoc1Arr.pop();
						$('#night_spoc_1_name').val(spoc1Arr[0]);
						$('#night_spoc_1_number').val(spoc1Arr[1]);	
						$('#night_spoc_2_name').val(spoc2[0]);
						$('#night_spoc_2_number').val(spoc2[1]);
						
					}
				});
			}
			
		}
/* ********************************** Night Spoc Person *************************************************** */
 
 </script>
<script type="text/javascript">
	var lat='';
	function my_function(){
		var phone = phoneNumber.value;
		
		if(phone!=''){
			var sessionId = ($('#sessionId').val())!=''?$('#sessionId').val():0;
			$.ajax({ url: '<?php echo e(url("get-latlong-map")); ?>',
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
						//"icon": svgMarker,
						icon: {
							// url: 'https://webcrm.cogentlab.com/dicv/public/images/bb_user.png'
							url: '<?php echo e(asset('images/al_user.png')); ?>'
						},
						title:"User Location"
					});
					addMarker(lat,long,'');
					getAssignDetails(lat,long,'');
				}
			});
		}
		
	}
	function isLatitude(lat) {
		
		if((lat =='NA' || lat =='N') || (lat =='na' || lat =='n') ){
			return true;
		}
		if(lat !=''){
			return isFinite(lat) && Math.abs(lat) <= 90;
		}else{
			return false;
		}
		
	}

	function isLongitude(lng) {
		
		if((lng =='NA' || lng =='N') || (lng =='na' || lng =='n')){
			return true;
		}
		if(lng !=''){
			return isFinite(lng) && Math.abs(lng) <= 180;
		}else{
			return false;
		}
		
	}

	// function manualGoogleMap_Without_Route(lat,long){  // Without Route
	function manualGoogleMap(lat,long){  // Without Route
		if(isLatitude(lat) && isLongitude(long)){
			/* $('#latValue').val(lat);
			$('#longValue').val(long);
			$('#lat').prop('required',false);
			$('#long').prop('required',false);
			$('#latDiv').hide();
			$('#longDiv').hide(); */
			$('#callerEdit').show();
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
			$('#callerEdit').hide();
			toastr.info("Invalid Lat and Long");
		}
					
	}
	
	// function addMarker_Without_Route(lat,long,el) {  // Without ROute
	function addMarker(lat,long,el) {  // Without ROute
 		var infowindow = new google.maps.InfoWindow({});
 		var global_markers = []; 
 		$.ajax({ url: '<?php echo e(url("get-nearest-latlong")); ?>',dataType: 'JSON',
 				data: { 'lat':lat,'long':long},
 				success: function(response){
 					markers = response;
 					for (var i = 0; i < markers.length; i++) {
 						// obtain the attribues of each marker
 						var latitude = parseFloat(markers[i].latitude);
 						var lng = parseFloat(markers[i].longitude);
 						var trailhead_name = markers[i].dealer_name+'('+ markers[i].SC_City_Name +')';
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
		$.ajax({ url: '<?php echo e(url("get-assign-details")); ?>',
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
		
		$.ajax({ url: '<?php echo e(url("get-assign-details-manually")); ?>',
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
			url: '<?php echo e(url("get-assign-mob")); ?>',
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
			url: '<?php echo e(url("get-assign-workManager")); ?>',
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
						var Result3= Result2[1]+'**'+Result2[2];
						if (ell!='') {
							if ( Result2[1] == ell ) {
									str += "<option value='" + Result3 + "' selected>"+cnt+' '+ Result2[1] + "</option>";
								} 
								else
								{
									str += "<option value='" + Result3 + "'>"+cnt+' ' + Result2[1] + "</option>";
								}
						}else{
							str += "<option value='" + Result3+ "'>"+cnt+' ' + Result2[1] + "</option>";
						}
						cnt++;
					}
					document.getElementById('assign_work_manager').innerHTML = str;
				}
			}
		});
	}
	function getAssignWorkManagerMobile(username){
		var arr = username.split('**');
		var id = arr[1];
		if(username !=''){
			$.ajax({
				url: '<?php echo e(url("get-assign-workManager-mobile")); ?>',
				type:'POST',
				data: {'username':id},
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
							/* $('#folio-invalid').hide();
							$('#submit').show(); */
						}else{
							$("#assign_work_manager_mobile").removeAttr("readonly");
							$('#assign_work_manager_mobile').val(response);
							$('#assign_work_manager_mobile1').val(response);
							/* $('#folio-invalid').show();
							$('#submit').hide(); */
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
			/* $('#submit').show();
			$('#folio-invalid').hide(); */
		}else{
			$("#assign_work_manager_mobile").removeAttr("readonly");
			$('#assign_work_manager_mobile').val(mobileNum);
			$('#assign_work_manager_mobile1').val(mobileNum);
			/* $('#folio-invalid').show();
			$('#submit').hide(); */
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
			if(caller_type !='' && caller_contact !='' && caller_name !=''){
				if(caller_language != ''){
					$.ajax({ url: '<?php echo e(url("caller-update")); ?>',
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
				toastr.info("Please Fill Mandate Fields");
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
				var engine_emmission_type1 = $('#engine_emmission_type1').val();
				engine_emmission_type = engine_emmission_type1 != ''?engine_emmission_type1:engine_emmission_type;
				
				if(reg_number1 == ''){
					toastr.info("Please fill registration number");		
				}else if(vehicle_model == ''){
					toastr.info("Please fill vehicle model");
				}/* else if(vehicle_segment == ''){
					toastr.info("Please fill vehicle segment");
				}else if(purchase_date == ''){
					toastr.info("Please fill purchase date");
				} */else if(add_blue_use == ''){
					toastr.info("Please fill add blue use");
				}else if(engine_emmission_type == ''){
					toastr.info("Please fill engine emmission type");
				}else{
					$.ajax({ url: '<?php echo e(url("vehicle-update")); ?>',
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
			$.ajax({ url: '<?php echo e(url("owner-update")); ?>',
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
			
			$.ajax({ url: '<?php echo e(url("owner-contact-update")); ?>',
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
			url: '<?php echo e(url("get-vehicle-models")); ?>',
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

	function my_function_Route(){
		var phone = phoneNumber.value;
		
		if(phone!=''){
			var sessionId = ($('#sessionId').val())!=''?$('#sessionId').val():0;
			$.ajax({ url: '<?php echo e(url("get-latlong-map")); ?>',
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

    // function manualGoogleMap(lat, long) { // With Route
    function manualGoogleMap_Route(lat, long) { // With Route
		if(isLatitude(lat) && isLongitude(long)){
			$('#callerEdit').show();
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
						url: '<?php echo e(asset('images/al_user.png')); ?>'
					},
					title: "User Location",
				});
				addMarker(lat, long, "");
				//getAssignDetails(lat,long,'');
			}
		}else{
			$('#callerEdit').hide();
			toastr.info("Invalid Lat and Long");
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
		
		 let ajaxOption=$.ajax({ url: '<?php echo e(url("get-nearest-latlong")); ?>',dataType: 'JSON',
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
    //  async function addMarker(lat, long, el) {   //  With Route
     async function addMarkerRoute(lat, long, el) {   //  With Route

        const fromloc = {
            lat: parseFloat(lat),
            lng: parseFloat(long)
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
						var trailhead_name = markers[i].dealer_name+'('+ markers[i].SC_City_Name +')';
						
						var myLatlng = new google.maps.LatLng(latitude, lng);
						/*********************************Distance Code**********************************************/
						var distanceInMeters = "";
						var dis = "";
						const toloc = {
							lat: parseFloat(latitude),
							lng: parseFloat(lng)
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
										url: '<?php echo e(asset('images/favicon.png')); ?>'
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.masterlayout", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\wamp64\www\ashokleyland\non_elitesupport\resources\views/search_location.blade.php ENDPATH**/ ?>