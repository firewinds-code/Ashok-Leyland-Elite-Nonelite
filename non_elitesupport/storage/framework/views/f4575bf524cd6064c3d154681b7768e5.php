
<?php $__env->startSection('title','Standard Consolidated Report'); ?>
<?php $__env->startSection('bodycontent'); ?>
<div class="content-wrapper mobcss">	 
	<div class="card">	            
	    <div class="card-body">
			<h4 class="card-title">Consolidated Report</h4>
	        <div class="clear"></div>			
            <hr>
			
			<form name="myForm" method="post" enctype="multipart/form-data" action="<?php echo e(url('store-consolidated-report')); ?>">
	            <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
	            <input type="hidden" name="zoneId" id="zoneId" value="<?php if(isset($zoneImplode)): ?><?php echo e($zoneImplode); ?><?php endif; ?>">
	            <input type="hidden" name="stateId" id="stateId" value="<?php if(isset($stateImplode)): ?><?php echo e($stateImplode); ?><?php endif; ?>">
	            <input type="hidden" name="cityId" id="cityId" value="<?php if(isset($cityImplode)): ?><?php echo e($cityImplode); ?><?php endif; ?>">
	            <input type="hidden" name="dealerId" id="dealerId" value="<?php if(isset($dealerImplode)): ?><?php echo e($dealerImplode); ?><?php endif; ?>">
	            <div class="row">
                 	<div class="form-group col-md-3">
                        <label for="datefrom" >Date From</label>
						<span style="color: red;">*</span>
						<input type="text" name="datefrom" id="datefromConsolidated" autocomplete="off" class="form-control" value="<?php if(isset($datefrom)): ?><?php echo e($datefrom); ?> <?php endif; ?>" placeholder="Date From" required/>
                        <span id="datefrom_error" style="color:red"></span> 
                    </div>
                    <div class="form-group col-md-3">
                        <label for="dateto" >Date To</label>
						<span style="color: red;">*</span>
						<input type="text" name="dateto" id="datetoConsolidated" autocomplete="off" class="form-control" value="<?php if(isset($dateto)): ?><?php echo e($dateto); ?> <?php endif; ?>" placeholder="Date To" required/>
                    </div>
					
					<div class="form-group col-md-3">
						<label for="ticketStatus" >Ticket Status</label>
						<span style="color: red;">*</span>
						<select name="ticketStatus[]" multiple id="ticketStatus" class="form-control" required>
							<?php if(isset($statusData)): ?>
								<?php $__currentLoopData = $statusData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<?php if(isset($ticketStatus)): ?>
								
								<option value="<?php echo e($row->type); ?>" <?php echo e(in_array($row->type,$ticketStatus)?"Selected":""); ?>><?php echo e($row->type); ?></option>
								<?php else: ?>
								<option value="<?php echo e($row->type); ?>"><?php echo e($row->type); ?></option>
								<?php endif; ?>
								
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							<?php endif; ?>
						</select>
                    </div>
					<div class="form-group col-md-3">
						<label for="City">TAT (Hours) </label> <span style="color: red;">*</span>
						<select name="tat" id="tat" class="form-control" required>
							<option value="">--Select--</option>							
							
							<option value="12"  <?php if(isset($tat)): ?> <?php echo e('12' == $tat?'Selected':""); ?> <?php endif; ?>>12</option>
							<option value="24" <?php if(isset($tat)): ?> <?php echo e('24' == $tat?'Selected':""); ?> <?php endif; ?>>24</option>
							<option value="48" <?php if(isset($tat)): ?> <?php echo e('48' == $tat?'Selected':""); ?> <?php endif; ?>>48</option>
							<option value="60" <?php if(isset($tat)): ?> <?php echo e('60' == $tat?'Selected':""); ?> <?php endif; ?>>60</option>
							<option value="72" <?php if(isset($tat)): ?> <?php echo e('72' == $tat?'Selected':""); ?> <?php endif; ?>>72</option>
							<option value="96" <?php if(isset($tat)): ?> <?php echo e('96' == $tat?'Selected':""); ?> <?php endif; ?>>96</option>
							<option value="120" <?php if(isset($tat)): ?> <?php echo e('120' == $tat?'Selected':""); ?> <?php endif; ?>>120</option>
							<option value="0"  <?php if(isset($tat)): ?> <?php echo e('0' == $tat?'Selected':""); ?> <?php endif; ?>>All</option>
						</select> 
					</div>
                    <div class="form-group col-md-3">
						<label for="Region">Zone</label> <span style="color: red;">*</span> 
						
						<select name="zone[]" multiple id="zone" class="form-control" onchange="fn_zone_change(this.value,'')" required>
							<?php if(isset($regionData)): ?>
								<?php $__currentLoopData = $regionData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $regionRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<?php if(isset($zone)): ?>
									<option value="<?php echo e($regionRow->id); ?>" <?php echo e(in_array($regionRow->id,$zone)?"Selected":""); ?>><?php echo e($regionRow->region); ?></option>
									<?php else: ?>
									<option value="<?php echo e($regionRow->id); ?>" ><?php echo e($regionRow->region); ?></option>
									<?php endif; ?>
									
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							<?php endif; ?>
						</select>
					</div>
					 <div class="form-group col-md-3">
						<label for="State" >Region</label> <span style="color: red;">*</span>
						<select name="state[]" multiple id="state" class="form-control" onchange="Dealer_State_change(zone.value,this.value,'')" required></select>
					</div>
					 <div class="form-group col-md-3">
					   <label for="City">Area</label> <span style="color: red;">*</span>
						<select name="city[]" multiple id="city" class="form-control" onchange="getCityChangeDealer(zone.value,state.value,this.value,'')" required></select>
					</div>
					<div class="form-group col-md-3">
						<label for="dealer">Dealer</label> <span style="color: red;">*</span>
						<select name="dealer[]" multiple id="dealer" class="form-control"   required>
							
						</select>
					</div>
					
                   
                </div>
            	<div class="clear"></div>
                <hr> 
                <div class="row">
                	 <div class="form-group col-md-3">
                        <input type="submit"name="submit" id="submit" value="Submit" class="btn-secondary">
                    </div>
                </div>
            </form>   
			<div class="clear"></div>
            <hr>                       
            <div class="table-responsive">
				<table id="order-listing" class="table" border="1">
					<thead>
						<tr style="background-color: ##d3d6d2;">
							<th>Ticket Number</th>
							<th>Ticket Status</th>
							<th>Feedback Rating</th>
							<th>Feedback Description</th>
							
							
							<th>Zonal Office</th>
							<th>Regional Office	</th>
							<th>Area Office	</th>
							<th>Created By	</th>
							<th>Call Log Date	</th>
							<th>Call Log Time	</th>
							<th>Completed By</th>
							<th>Completed Date </th>
							<th>Completed Time</th>
							<th>Closed By</th>
							<th>Closed Date</th>
							<th>Closed Time</th>
							
							<th>Time Passed Since Call Log (D:H:M)</th>
							<th>SO Number</th>
							<th>Jobcard Number</th>
							<th>Reason For Reassignment</th>
							<th>SPOC</th>
							
							<th>Actual Response Date as per Dealer</th>
							<th>Actual Response Time as per Dealer</th>
							<th>Actual Response Date as per Customer</th>
							<th>Actual Response Time as per Customer</th>
							<th>Response Gap	</th>
							<th>Actual Restoration Date Dealer</th>
							<th>Actual Restoration Time Dealer</th>
							<th>Actual Restoration Date Customer	</th>
							<th>Actual Restoration Time Customer	</th>
							<th>Total Restoration Time	</th>
							<th>Caller Name	</th>
							<th>Caller Type	</th>
							<th>Caller Contact Number	</th>
							<th>Caller Language	</th>
							<th>Company Name	</th>
							<th>Owner Mobile No	</th>
							<th>Registration Number	</th>
							<th>Chassis Number	</th>
							<th>Vehicle Model	</th>
							<th>Engine Number	</th>
							<th>Vehicle Segment	</th>
							<th>Sale Date	</th>
							
							<th>EngineEmmisionType	</th>
							<th>Customer Type</th>
							
							<th>Ticket Type	</th>
							
							<th>Vehicle Status	</th>
							<th>IS Vehicle movable	</th>
							<th>Breakdown Location	</th>
							
							<th>Complaint Reported	</th>
							<th>SAC Code	</th>
							<th>Aggregate	</th>
							<th>SAC Type	</th>
							<th>SAC Name	</th>
							
							<th>SAC Contact Person	</th>
							<th>SAC Contact Number	</th>
							<th>Last Remark Time	</th>
							
							<th>Latest Comments	</th>
							<th>TSM Name	</th>
							<th>TSM Contact Number	</th>
							<th>First Called By	</th>
							<th>Last Called By	</th>
							<th>Last Called On	</th>
							<th>First Called On	</th>
							<th>Second Called On	</th>
							<th>SAC Assigned Date	</th>
							<th>SAC Assigned time	</th>
							<th>Follow Up Hours Passed	</th>
							<th>Disposition	</th>
							
							<th>Last Update By	</th>
							<th>Last Update Date	</th>
							<th>SAC Reassigned Date</th>
							<th>SAC Reassigned Time</th>
							<th>Restoration Type</th>
							<th>Response Delay Reason</th>
							
							<th>Source</th>
							<th>Restoration Delay Reason</th>
							<th>Total Follow Up</th>
							<th>Awaiting Customer Approval</th>
							<th>Awaiting Customer Payment</th>
							<th>Awaiting Parts From Customer</th>
							<th>Vehicle Being Towed</th>
							<th>Gate Pass Pending From Customer End</th>
							<th>Total Hold</th>
							<th>Latitude</th>
							<th>Longitude</th>
							
						</tr>
					</thead> 
					<tbody>
						<?php if(isset($consolidatedReport)): ?>	 
							
							<?php $__currentLoopData = $consolidatedReport; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<tr style="background-color: #d3d6d2;">
									<td><?php echo e($row->complaint_number); ?></td>
									<td><?php echo e($row->remark_type); ?></td>
									<td><?php echo e($row->feedback_rating); ?></td>
									<td><?php echo e($row->feedback_desc); ?></td>
									
									
									<td><?php echo e($row->assignedDealerZone); ?></td>
									<td><?php echo e($row->stateName); ?></td>
									<td><?php echo e($row->delCityName); ?></td>
									<td><?php echo e($row->createdby!=''?$row->createdby:''); ?></td>
									<td><?php echo e($row->complaintDate!=''?date('d-m-Y',strtotime($row->complaintDate)):''); ?></td>
									<td><?php echo e($row->complaintDate!=''?date('H:i:s',strtotime($row->complaintDate)):''); ?></td>
									<td><?php echo e($row->completedby!=''?$row->completedby:''); ?></td>
									<td><?php echo e($row->completionDate!=''?date('d-m-Y',strtotime($row->completionDate)):''); ?></td>
									<td><?php echo e($row->completionDate!=''?date('H:i:s',strtotime($row->completionDate)):''); ?></td>
									<td><?php echo e($row->closedby!=''?$row->closedby:''); ?></td>
									<td><?php echo e($row->closedDate!=''?date('d-m-Y',strtotime($row->closedDate)):''); ?></td>
									<td><?php echo e($row->closedDate!=''?date('H:i:s',strtotime($row->closedDate)):''); ?></td>
									<?php  
										/* $complaintDate =$row->complaintDate;
										$currentDate = date('Y-m-d H:i:s');
										$first_date = new DateTime($complaintDate);
										$second_date = new DateTime($currentDate);
										$difference = $first_date->diff($second_date);
										$timePassedSinceCallLog =$difference->d.'-D '.$difference->h.'-H	'.$difference->i.'-M'; */

										$remTypeArr = array('Arranging Parts Locally','Awaiting parts from AL','Awaiting AL Approval','Awaiting completion from Ancillary suppliers','Awaiting completion of contracted Job','Awaiting customer approval','Awaiting customer Payment','Awaiting Good will Approval','Awaiting parts from another dealer branch','Awaiting parts from customer','Dealer Feedback','Investigation in progress','Load transfer in progress','Man power not available','Mechanic left to BD spot','Mechanic reached BD spot','Moved to another vehicle on urgency','Public Holiday','Reassigned support','Response Delay','Response not Initiated','Restored by Self','Restored by Unknown support','Restored by Support','Vehicle being Towed','Vehicle reached support point','Work held up due to bandh','Work held up due to injury/accident','Work in progress','Workshop closed - Sunday','Assigned');
										/* if(in_array($row->remark_type,$remTypeArr)){
											$diff = abs(strtotime($row->complaintDate) - strtotime(date("Y-m-d h:i:s"))); 
											$first_date = new DateTime($row->complaintDate);
											$second_date = new DateTime(date("Y-m-d h:i:s"));
											$difference = $first_date->diff($second_date);
											$timePassedSinceCallLog =$difference->d.'-D '.$difference->h.'-H '.$difference->i.'-M';
											
										}else{ */
											
											if(isset($row->complaintDate) && $row->closedDate !=''){
												$first_date = new DateTime($row->complaintDate);
												// $second_date = new DateTime(date("Y-m-d H:i:s"));
												$second_date = new DateTime($row->closedDate);
												$difference = $first_date->diff($second_date);
												$timePassedSinceCallLog =$difference->d.':'.$difference->h.':'.$difference->i;
											}else{
												// $timePassedSinceCallLog='';
												$diff = abs(strtotime($row->complaintDate) - strtotime(date("Y-m-d h:i:s"))); 
												$first_date = new DateTime($row->complaintDate);
												$second_date = new DateTime(date("Y-m-d h:i:s"));
												$difference = $first_date->diff($second_date);
												$timePassedSinceCallLog =$difference->d.':'.$difference->h.':'.$difference->i;
											}
										/* } */
									?>
									<td><?php echo e($timePassedSinceCallLog); ?></td>
									
									<td><?php echo e($row->so_number); ?></td>
									<td><?php echo e($row->jobcard_number); ?></td>
									<td><?php echo e($row->reason_reassign); ?></td>
									<td><?php echo e($row->SPOC); ?></td>
									
									<td><?php echo e($row->actual_response_time !=''?date('d-m-Y',strtotime($row->actual_response_time)):''); ?></td>
									<td><?php echo e($row->actual_response_time !=''?date('H:i:s',strtotime($row->actual_response_time)):''); ?></td>
									<td><?php echo e(($row->actual_response_time_customer !='' && $row->actual_response_time_customer !='NA')?date('d-m-Y',strtotime($row->actual_response_time_customer)):''); ?></td>
									<td><?php echo e(($row->actual_response_time_customer !='' && $row->actual_response_time_customer !='NA')?date('H:i:s',strtotime($row->actual_response_time_customer)):''); ?></td>
									<?php  										
										$actual_response_time_gap ='';
										if($row->actual_response_time !=''){
											$first_date = new DateTime($row->complaintDate);
											$second_date = new DateTime($row->actual_response_time);
											$difference = $first_date->diff($second_date);
											$actual_response_time_gap =$difference->d.'-D '.$difference->h.'-H '.$difference->i.'-M';
										}										
									?>
									<td><?php echo e($actual_response_time_gap); ?></td>
									<?php
										$tat_scheduledDate =$row->tat_scheduled!=''?date('d-m-Y',strtotime($row->tat_scheduled)):'';
										$tat_scheduledTime =$row->tat_scheduled!=''?date('H:i:s',strtotime($row->tat_scheduled)):'';
										$tat_scheduled_customerDate =($row->tat_scheduled_customer!='' && $row->tat_scheduled_customer!='NA')?date('d-m-Y',strtotime($row->tat_scheduled_customer)):'';
										$tat_scheduled_customerTime =($row->tat_scheduled_customer!='' && $row->tat_scheduled_customer!='NA')?date('H:i:s',strtotime($row->tat_scheduled_customer)):'';
									?>
									<td><?php echo e($tat_scheduledDate); ?></td>
									<td><?php echo e($tat_scheduledTime); ?></td>
									<td><?php echo e($tat_scheduled_customerDate); ?></td>
									<td><?php echo e($tat_scheduled_customerTime); ?></td>
									<?php
										$totalRestorationTime='NA';
										if($row->tat_scheduled!='' && $row->tat_scheduled!='NA'){
											/* $date1_ts = strtotime($row->complaintDate);
											$date2_ts = strtotime($row->completionDate);
											$totalRestorationTime = abs($date2_ts - $date1_ts)/(60*60); */
											$first_date = new DateTime($row->complaintDate);
											$second_date = new DateTime($row->tat_scheduled);
											$difference = $first_date->diff($second_date);
											$totalRestorationTime =$difference->d.'-D '.$difference->h.'-H '.$difference->i.'-M';
										}
									?>
									<td><?php echo e($totalRestorationTime); ?></td>
									<td><?php echo e($row->caller_name); ?></td>
									<td><?php echo e($row->caller_type); ?></td>
									<td><?php echo e($row->caller_contact); ?></td>
									<td><?php echo e($row->caller_language); ?></td>
									<td><?php echo e($row->owner_name); ?></td>
									<td><?php echo e($row->owner_mob); ?></td>
									<td><?php echo e($row->reg_number); ?></td>
									<td><?php echo e($row->chassis_number); ?></td>
									<td><?php echo e($row->vehicle_model); ?></td>
									<td><?php echo e($row->engine_number); ?></td>
									<td><?php echo e($row->vehicle_segment); ?></td>
									<td><?php echo e($row->purchase_date !=''?date('d-m-Y',strtotime($row->purchase_date)):''); ?></td>
									
									<td><?php echo e($row->engine_emmission_type); ?></td>
									<td>Standard Customer</td>
									
									<td><?php echo e($row->ticket_type); ?></td>
									
									<td><?php echo e($row->vehicle_type); ?></td> 
									<td><?php echo e($row->vehicle_movable); ?></td>
									<td><?php echo e($row->location); ?></td>
									
									<td><?php echo e($row->standard_remark); ?></td>
									<td><?php echo e($row->sac_code); ?></td>
									<td><?php echo e($row->aggregate); ?></td>
									<td><?php echo e($row->dealer_type!=''?$row->dealer_type:'NA'); ?></td>
									<td><?php echo e($row->dealer_name); ?></td>
									
									<?php
										$Support_Contact_Person = $row->Support_Contact_Person!=''?$row->Support_Contact_Person:'NA~~NA';
										$Support_Contact_PersonArr = explode("~~",$Support_Contact_Person);
									?>
									<td><?php echo e($Support_Contact_PersonArr[0]); ?></td>
									<td><?php echo e($Support_Contact_PersonArr[1]); ?></td>
									<td><?php echo e($row->complaintUpdate!=''?date('H:i:s',strtotime($row->complaintUpdate)):''); ?></td>
									
									<td><?php echo e($row->assign_remarks); ?></td>
									<?php 
										$alsedetails = $row->alsedetails;
										if($alsedetails!=''){
											$alsedetailsArr = explode("~~",$alsedetails);
										}else{
											$alsedetailsArr = explode("~~","NA~~NA");
										}
										
									?>
									<td><?php echo e($alsedetailsArr[0]); ?></td>
									<td><?php echo e($alsedetailsArr[1]); ?></td>
									<td><?php echo e($row->firstcallagent); ?></td>
									<td><?php echo e($row->lastcallagent); ?></td>
									<td><?php echo e($row->lastcallagentTime); ?></td>
									<td><?php echo e($row->firstcallagentTime); ?></td>
									<td><?php echo e($row->secondcallagentTime); ?></td>
									<td><?php echo e(date('d-m-Y',strtotime($row->complaintDate))); ?></td>
									<td><?php echo e(date('H:i:s',strtotime($row->complaintDate))); ?></td>
									<?php  
										/* $lastcallagentTime =$row->lastcallagentTime;
										
										$date1_ts = strtotime($lastcallagentTime);
										$date2_ts = strtotime($currentDate);
										$diff = $date2_ts - $date1_ts;
										$followUpCall =  round($diff / 86400); */
										if($row->lastcallagentTime !=''){
											$currentDate = date('Y-m-d H:i:s');
											$first_date = new DateTime($row->lastcallagentTime);
											$second_date = new DateTime($currentDate);
											$difference = $first_date->diff($second_date);
											$followUpCall =$difference->d.'-D '.$difference->h.'-H '.$difference->i.'-M';
										
										}else{
											$followUpCall='NA';
										}
										
										
									?>
									<td><?php echo e($followUpCall); ?></td>
									<td><?php echo e($row->lastcallagentdisposition); ?></td>
									
									<td><?php echo e($row->lastupdatename); ?></td>
									<td><?php echo e(date('d-m-Y H:i:s',strtotime($row->lastupdatedate))); ?></td>
									<td><?php echo e($row->reassignDate!=''?date('d-m-Y',strtotime($row->reassignDate)):''); ?></td>
									<td><?php echo e($row->reassignDate!=''?date('H:i:s',strtotime($row->reassignDate)):''); ?></td>
									<td><?php echo e($row->restoration_type); ?></td>
									<td><?php echo e($row->response_delay_reason); ?></td>
									<?php
										$completionDate=$completionDay=$completionHour=$completionMin='NA';
										if($row->completionDate !='' && !empty($row->completionDate)){
											/* $first_date = new DateTime($row->complaintDate);
											$second_date = new DateTime($row->completionDate);
											$difference = $first_date->diff($second_date);
											$completionDay =$difference->d;
											$completionHour =$difference->h;
											$completionMin =$difference->i; */

											$date1 =strtotime($row->complaintDate);
											$date2 =strtotime($row->completionDate);
											$completionDay = abs($date2 -$date1) / (60*60*24);
											$completionHour = abs($date2 -$date1) / (60*60);
										}
									?>
									
									<td><?php echo e($row->source); ?></td>
									<td><?php echo e($row->restoration_delay); ?></td>
									<td><?php echo e($row->followupcount); ?></td>
									<?php
									$currentDate = date("Y-m-d H:i:s");
									$aca_created_at = $row->aca_created_at!=''?$row->aca_created_at:'NA';
									$aca_updated_at = $row->aca_updated_at!=''?$row->aca_updated_at:$currentDate;
									if($aca_created_at !='NA' && $aca_updated_at !='NA'){
										$first_date = new DateTime($aca_created_at);
										$second_date = new DateTime($aca_updated_at);
										$difference = $first_date->diff($second_date);
										$hr = $difference->d*24 +$difference->h;
										// $acaTime = $difference->d.':'.$difference->h.':'.$difference->i;
										$acaTime = $hr.':'.$difference->i.':00';
										
									}else{
										$acaTime = '0';
									}
									$acp_created_at = $row->acp_created_at!=''?$row->acp_created_at:'NA';
									$acp_updated_at = $row->acp_updated_at!=''?$row->acp_updated_at:$currentDate;
									if($acp_created_at !='NA' && $acp_updated_at !='NA'){
										$first_date = new DateTime($acp_created_at);
										$second_date = new DateTime($acp_updated_at);
										$difference = $first_date->diff($second_date);
										// $acpTime = $difference->d.':'.$difference->h.':'.$difference->i;
										$hr = $difference->d*24 +$difference->h;
										$acpTime = $hr.':'.$difference->i.':00';
									}else{
										$acpTime = '0';
									}
									$apc_created_at = $row->apc_created_at!=''?$row->apc_created_at:'NA';
									$apc_updated_at = $row->apc_updated_at!=''?$row->apc_updated_at:$currentDate;
									if($apc_created_at !='NA' && $apc_updated_at !='NA'){
										$first_date = new DateTime($apc_created_at);
										$second_date = new DateTime($apc_updated_at);
										$difference = $first_date->diff($second_date);
										// $apcTime = $difference->d.':'.$difference->h.':'.$difference->i;
										$hr = $difference->d*24 +$difference->h;
										$apcTime = $hr.':'.$difference->i.':00';
									}else{
										$apcTime = '0';
									}
									$vbt_created_at = $row->vbt_created_at!=''?$row->vbt_created_at:'NA';
									$vbt_updated_at = $row->vbt_updated_at!=''?$row->vbt_updated_at:$currentDate;
									if($vbt_created_at !='NA' && $vbt_updated_at !='NA'){
										$first_date = new DateTime($vbt_created_at);
										$second_date = new DateTime($vbt_updated_at);
										$difference = $first_date->diff($second_date);
										// $vbtTime = $difference->d.':'.$difference->h.':'.$difference->i;
										$hr = $difference->d*24 +$difference->h;
										$vbtTime = $hr.':'.$difference->i.':00';
									}else{
										$vbtTime = '0';
									}
									$gppce_created_at = $row->gppce_created_at!=''?$row->gppce_created_at:'NA';
									$gppce_updated_at = $row->gppce_updated_at!=''?$row->gppce_updated_at:$currentDate;
									if($gppce_created_at !='NA' && $gppce_updated_at !='NA'){
										$first_date = new DateTime($gppce_created_at);
										$second_date = new DateTime($gppce_updated_at);
										$difference = $first_date->diff($second_date);
										$gppceTime = $difference->d.':'.$difference->h.':'.$difference->i;
									}else{
										$gppceTime = '0';
									}
									?> 
									
									<td><?php echo e($acaTime); ?></td>
									<td><?php echo e($acpTime); ?></td>
									<td><?php echo e($apcTime); ?></td>
									<td><?php echo e($vbtTime); ?></td>
									<td><?php echo e($gppceTime); ?></td>
									<?php
										$days =0; $hour = 0; $minute = 0;
										if($acaTime !='0'){
											$acaTimeArr =  explode(":",$acaTime);
											$days += $acaTimeArr[0];
											$hour += $acaTimeArr[1];
											$minute += $acaTimeArr[2];
										}
										if($acpTime !='0'){
											$acpTimeArr =  explode(":",$acpTime);
											$days += $acpTimeArr[0];
											$hour += $acpTimeArr[1];
											$minute += $acpTimeArr[2];
										}
										if($apcTime !='0'){
											$apcTimeArr =  explode(":",$apcTime);
											$days += $apcTimeArr[0];
											$hour += $apcTimeArr[1];
											$minute += $apcTimeArr[2];
										}
										if($vbtTime !='0'){
											$vbtTimeArr =  explode(":",$vbtTime);
											$days += $vbtTimeArr[0];
											$hour += $vbtTimeArr[1];
											$minute += $vbtTimeArr[2];
										}
										if($gppceTime !='0'){
											$gppceTimeArr =  explode(":",$gppceTime);
											$days += $gppceTimeArr[0];
											$hour += $gppceTimeArr[1];
											$minute += $gppceTimeArr[2];
										}
										// $days = $days + $hour;

										$minutesCustome = $hour;
										$hourscustome = floor($minutesCustome / 60);
										$minCustome = $minutesCustome - ($hourscustome * 60);
										$days = $days+$hourscustome;
									?>
									
									<td><?php echo e($days.':'.$minCustome.':00'); ?></td>
									<?php
									$lat = $row->latitude;
									$long = $row->longitude;
										$lat = $lat !=''?number_format((float)$lat, 5, '.', ''):'';
										$long = $long !=''?number_format((float)$long, 5, '.', ''):'';
									?>
									<td><?php echo e($lat); ?></td>
									<td><?php echo e($long); ?></td>
									<?php 
										
										$totalResponseTime='NA';
										if($row->reassignDate =='' || $row->reassignDate =='NA'){
											if(!empty($row->actual_response_time_customer) && $row->actual_response_time_customer != 'NA'){
												// if($row->actual_response_time != '' || $row->actual_response_time != 'NA'){
													$first_date = new DateTime($row->complaintDate);
													$second_date = new DateTime($row->actual_response_time_customer);
													$difference = $first_date->diff($second_date);
													// $actual_response_time_gap =$difference->d.'-D '.$difference->h.'-H '.$difference->i.'-M';
													if($row->complaintDate > $row->actual_response_time_customer){
														$totalResponseTime = '-'.$difference->d *24 + $difference->h + ($difference->i/60);
													}else{
														$totalResponseTime = $difference->d *24 + $difference->h + ($difference->i/60);
													}
												// }
												
											}else{
												if(!empty($row->actual_response_time) && $row->actual_response_time != 'NA'){
													$first_date = new DateTime($row->complaintDate);
													$second_date = new DateTime($row->actual_response_time);
													$difference = $first_date->diff($second_date);
													// $actual_response_time_gap =$difference->d.'-D '.$difference->h.'-H '.$difference->i.'-M';
													if($row->complaintDate > $row->actual_response_time){
														$totalResponseTime = '-'.$difference->d *24 + $difference->h + ($difference->i/60);
													}else{
														$totalResponseTime = $difference->d *24 + $difference->h + ($difference->i/60);
													}
												}
												
											}
										}else{
											if(!empty($row->actual_response_time_customer) && $row->actual_response_time_customer != 'NA'){
												// if($row->actual_response_time != '' || $row->actual_response_time != 'NA'){
													$first_date = new DateTime($row->actual_response_time_customer);
													$second_date = new DateTime($row->reassignDate);
													$difference = $first_date->diff($second_date);
													// $actual_response_time_gap =$difference->d.'-D '.$difference->h.'-H '.$difference->i.'-M';

													if($row->reassignDate > $row->actual_response_time_customer){
														$totalResponseTime = '-'.$difference->d *24 + $difference->h + ($difference->i/60);
													}else{
														$totalResponseTime = $difference->d *24 + $difference->h + ($difference->i/60);
													}
													
												// }
												// $totalResponseTime = actual response time by dealer datetime - reassigned datetime
											}else{
												if(!empty($row->actual_response_time) && $row->actual_response_time != 'NA'){
													$first_date = new DateTime($row->actual_response_time);
													$second_date = new DateTime($row->reassignDate);
													$difference = $first_date->diff($second_date);
													// $actual_response_time_gap =$difference->d.'-D '.$difference->h.'-H '.$difference->i.'-M';

													if($row->reassignDate > $row->actual_response_time){
														$totalResponseTime = '-'.$difference->d *24 + $difference->h + ($difference->i/60);
													}else{
														$totalResponseTime = $difference->d *24 + $difference->h + ($difference->i/60);
													}
													
												}
												// $totalResponseTime = actual response time by customer datetime - reassigned datetime
											}	
										}
										
										$totalRestorationTime ='NA';
										
										if($row->reassignDate =='' || $row->reassignDate =='NA'){
											if(!empty($row->tat_scheduled_customer)  && $row->tat_scheduled_customer != 'NA'){
												// if($row->tat_scheduled != '' || $row->tat_scheduled != 'NA'){
													$first_date = new DateTime($row->complaintDate);
													$second_date = new DateTime($row->tat_scheduled_customer);
													$difference = $first_date->diff($second_date);
													// $tat_scheduled_gap =$difference->d.'-D '.$difference->h.'-H '.$difference->i.'-M';
													$totalDaysHours = $days +($hour/60);
													$totalRestorationTime = ($difference->d *24 + $difference->h + ($difference->i/60) - $totalDaysHours);
													
													
												// }
												
											}else{
												if(!empty($row->tat_scheduled)  && $row->tat_scheduled != 'NA'){
													$first_date = new DateTime($row->complaintDate);
													$second_date = new DateTime($row->tat_scheduled);
													$difference = $first_date->diff($second_date);
													// $tat_scheduled_gap =$difference->d.'-D '.$difference->h.'-H '.$difference->i.'-M';
													$totalDaysHours = $days +($hour/60);
													$totalRestorationTime = ($difference->d *24 + $difference->h + ($difference->i/60) - $totalDaysHours);
												}
												
											}
										}else{
											if(!empty($row->tat_scheduled_customer)  && $row->tat_scheduled_customer != 'NA'){
												// if($row->tat_scheduled != '' || $row->tat_scheduled != 'NA'){
													$first_date = new DateTime($row->tat_scheduled_customer);
													$second_date = new DateTime($row->reassignDate);
													$difference = $first_date->diff($second_date);
													// $tat_scheduled_gap =$difference->d.'-D '.$difference->h.'-H '.$difference->i.'-M';
													$totalDaysHours = $days +($hour/60);
													$totalRestorationTime = ($difference->d *24 + $difference->h + ($difference->i/60) - $totalDaysHours);
												// }
												// $totalRestorationTime = actual response time by dealer datetime - reassigned datetime
											}else{
												if(!empty($row->tat_scheduled)  && $row->tat_scheduled != 'NA'){
													$first_date = new DateTime($row->tat_scheduled);
													$second_date = new DateTime($row->reassignDate);
													$difference = $first_date->diff($second_date);
													// $tat_scheduled_gap =$difference->d.'-D '.$difference->h.'-H '.$difference->i.'-M';
													$totalDaysHours = $days +($hour/60);
													$totalRestorationTime = ($difference->d *24 + $difference->h + ($difference->i/60) - $totalDaysHours);
												}
												// $totalRestorationTime = actual response time by customer datetime - reassigned datetime
											}	
										}
									?>
									
									<?php 
										$totalResponseTime = $totalResponseTime!='NA'?round($totalResponseTime,2):'NA';
										$totalRestorationTime = $totalRestorationTime!='NA'?round($totalRestorationTime,2):'NA';
										if($totalResponseTime == 'NA'){
											$responseTimeSlab ='';
										}else if($totalResponseTime < 0){
											$responseTimeSlab = '-ve Value';
										}else if($totalResponseTime >=0 && $totalResponseTime <= 4 ){
											$responseTimeSlab ='0-4 hrs';
										}elseif($totalResponseTime > 4 && $totalResponseTime <= 8 ){
											$responseTimeSlab ='4-8 hrs';
										}elseif($totalResponseTime > 8 && $totalResponseTime <= 12 ){
											$responseTimeSlab ='8-12 hrs';
										}elseif($totalResponseTime > 12 && $totalResponseTime <= 24 ){
											$responseTimeSlab ='12-24 hrs';
										}elseif($totalResponseTime > 24 && $totalResponseTime <= 36 ){
											$responseTimeSlab ='24-36 hrs';
										}elseif($totalResponseTime > 36 && $totalResponseTime <= 48 ){
											$responseTimeSlab ='36-48 hrs';
										}elseif($totalResponseTime > 48){
											$responseTimeSlab ='> 48 hrs';
										}else{
											$responseTimeSlab='';
										}

										if($totalRestorationTime == 'NA'){
											$restoationTimeSlab ='';
										}else if($totalRestorationTime < 0){
											$restoationTimeSlab = '-ve Value';
										}else if($totalRestorationTime >=0 && $totalRestorationTime <= 4 ){
											$restoationTimeSlab ='0-4 hrs';
										}elseif($totalRestorationTime > 4 && $totalRestorationTime <= 8 ){
											$restoationTimeSlab ='4-8 hrs';
										}elseif($totalRestorationTime > 8 && $totalRestorationTime <= 12 ){
											$restoationTimeSlab ='8-12 hrs';
										}elseif($totalRestorationTime > 12 && $totalRestorationTime <= 24 ){
											$restoationTimeSlab ='12-24 hrs';
										}elseif($totalRestorationTime > 24 && $totalRestorationTime <= 36 ){
											$restoationTimeSlab ='24-36 hrs';
										}elseif($totalRestorationTime > 36 && $totalRestorationTime <= 48 ){
											$restoationTimeSlab ='36-48 hrs';
										}elseif($totalRestorationTime > 48){
											$restoationTimeSlab ='> 48 hrs';
										}else{
											$restoationTimeSlab ='';
										}
									?>
									
								</tr>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							
							<?php if(empty($consolidatedReport)): ?>
							<tr>
								<td colspan="17">
									<?php echo e("No Data Found"); ?>

								</td>
							</tr>
							   <?php endif; ?>
						<?php endif; ?>
						
					</tbody>
				</table>
			</div>
			<br>
	    </div>	            
	</div>
<script type="text/javascript">

	$(document).ready(function () {	
		
		$('#datefromConsolidated').datetimepicker(
			{ maxDate: 0,format:'Y-m-d',timepicker:false}
		);
		$('#datetoConsolidated').datetimepicker(
			{ maxDate: 0,format:'Y-m-d',timepicker:false}
		);
		
		var zone =$('#zoneId').val();
		var state =$('#stateId').val();
		var city =$('#cityId').val();
		var dealer =$('#dealerId').val();
		fn_zone_change(zone,state);
		Dealer_State_change(zone,state,city);
		getCityChangeDealer(zone,state,city,dealer);
		
	});
	function fn_zone_change(zoneId,ell){
		var myarray= [];
				var favorite = [];
				if(ell!='')
				{
				$('#zone :selected').each(function(i, sel)
				{ 
					//favorite.push(ell);
				});
				
				//var zz=favorite.join(",");
				var zz=zoneId;
				}
				else
				{
					$('#zone :selected').each(function(i, sel){
					favorite.push($(this).val());
					});
					var zz=favorite.join(",");
				}
			$.ajax({ url: '<?php echo e(url("get-multiple-zone-change")); ?>',
				data: { 'zoneId':zz},
				success: function(data){
					var Result = data.split(",");var str = '';
					Result.pop();
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
					document.getElementById('state').innerHTML =str;
			}
			});
		}
	function Dealer_State_change(el,ell,elll)
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
		
		
		/*if(ell!=''){var state = el;}*/	
		$.ajax({ url: '<?php echo e(url("get-multiple-state-id-city")); ?>',
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
		document.getElementById('city').innerHTML = str;
		}});
	}
	function getCityChangeDealer(zone,region,area,dealer){
		var favorite = [];
		var AllZone_ = [];
		var AllState_ = [];
		var AllCity_ = [];
		var AllDealer_ = [];
				if(dealer!=''){
					var AllZone = zone;
					var AllState=region;
					var AllCity=area;
					var AllDealer=dealer;
				}
				else{
					$('#zone :selected').each(function(i, sel){ 
						AllZone_.push($(this).val());
					});
					var AllZone = AllZone_.join(',');
					
					$('#state :selected').each(function(i, sel){ 
						AllState_.push($(this).val());
					});
					var AllState = AllState_.join(',');

					$('#city :selected').each(function(i, sel){ 
						AllCity_.push($(this).val());
					});
					var AllCity = AllCity_.join(',');
				}
		
		$.ajax({ url: '<?php echo e(url("get-city-change-dealer")); ?>',
		data: { 'zone':AllZone,'region':AllState,'city':AllCity },
		success: function(data){
			console.log(data);
		var Result = data.split(",");var str = '';
		var value = dealer.split(",");
		Result.pop();
		for (item in Result){
				Result2 = Result[item].split("~");
				if(dealer!=''){
					if (jQuery.inArray(Result2[0], value)!='-1'){
						str += "<option value='" + Result2[0] + "' selected>" + Result2[1] + "</option>";	
					}else{
						str += "<option value='" + Result2[0] + "'>" +Result2[1] + "</option>";
					}	
				}else{
					str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
				}
			}
			document.getElementById('dealer').innerHTML = str;
		}});
	}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.masterlayout", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\wamp64\www\ashokleyland\non_elitesupport\resources\views/consolidated_report.blade.php ENDPATH**/ ?>