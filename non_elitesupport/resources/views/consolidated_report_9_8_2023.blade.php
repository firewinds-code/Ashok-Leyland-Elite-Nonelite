@extends("layouts.masterlayout")
@section('title','Consolidated Report')
@section('bodycontent')
<div class="content-wrapper mobcss">	 
	<div class="card">	            
	    <div class="card-body">
			<h4 class="card-title">Consolidated Report</h4>
	        <div class="clear"></div>			
            <hr>
			
			<form name="myForm" method="post" enctype="multipart/form-data" action="{{url('store-consolidated-report')}}">
	            <input type="hidden" name="_token" value="{{csrf_token()}}">
	            <input type="hidden" name="zoneId" id="zoneId" value="@isset($zoneImplode){{$zoneImplode}}@endisset">
	            <input type="hidden" name="stateId" id="stateId" value="@isset($stateImplode){{$stateImplode}}@endisset">
	            <input type="hidden" name="cityId" id="cityId" value="@isset($cityImplode){{$cityImplode}}@endisset">
	            <input type="hidden" name="dealerId" id="dealerId" value="@isset($dealerImplode){{$dealerImplode}}@endisset">
	            <div class="row">
                 	<div class="form-group col-md-3">
                        <label for="datefrom" >Date From</label>
						<span style="color: red;">*</span>
						<input type="text" name="datefrom" id="datefromConsolidated" autocomplete="off" class="form-control" value="@isset($datefrom){{$datefrom}} @endisset" placeholder="Date From" required/>
                        <span id="datefrom_error" style="color:red"></span> 
                    </div>
                    <div class="form-group col-md-3">
                        <label for="dateto" >Date To</label>
						<span style="color: red;">*</span>
						<input type="text" name="dateto" id="datetoConsolidated" autocomplete="off" class="form-control" value="@isset($dateto){{$dateto}} @endisset" placeholder="Date To" required/>
                    </div>
					
					<div class="form-group col-md-3">
						<label for="ticketStatus" >Ticket Status</label>
						<span style="color: red;">*</span>
						<select name="ticketStatus[]" multiple id="ticketStatus" class="form-control" required>
							@if(isset($statusData))
								@foreach ($statusData as $row)
								@if(isset($ticketStatus))
								
								<option value="{{$row->type}}" {{in_array($row->type,$ticketStatus)?"Selected":""}}>{{$row->type}}</option>
								@else
								<option value="{{$row->type}}">{{$row->type}}</option>
								@endif
								
								@endforeach
							@endif
						</select>
                    </div>
					<div class="form-group col-md-3">
						<label for="City">TAT (Hours) </label> <span style="color: red;">*</span>
						<select name="tat" id="tat" class="form-control" required>
							<option value="">--Select--</option>							
							
							<option value="12"  @isset($tat) {{'12' == $tat?'Selected':""}} @endisset>12</option>
							<option value="24" @isset($tat) {{'24' == $tat?'Selected':""}} @endisset>24</option>
							<option value="48" @isset($tat) {{'48' == $tat?'Selected':""}} @endisset>48</option>
							<option value="60" @isset($tat) {{'60' == $tat?'Selected':""}} @endisset>60</option>
							<option value="72" @isset($tat) {{'72' == $tat?'Selected':""}} @endisset>72</option>
							<option value="96" @isset($tat) {{'96' == $tat?'Selected':""}} @endisset>96</option>
							<option value="120" @isset($tat) {{'120' == $tat?'Selected':""}} @endisset>120</option>
							<option value="0"  @isset($tat) {{'0' == $tat?'Selected':""}} @endisset>All</option>
						</select> 
					</div>
                    <div class="form-group col-md-3">
						<label for="Region">Zone</label> <span style="color: red;">*</span> 
						{{-- <select name="zone" id="zone" class="form-control" onchange="On_Dealer_Zone(this.value,'')" required> --}}
						<select name="zone[]" multiple id="zone" class="form-control" onchange="fn_zone_change(this.value,'')" required>
							@isset($regionData)
								@foreach($regionData as $regionRow)
									@if(isset($zone))
									<option value="{{$regionRow->id}}" {{in_array($regionRow->id,$zone)?"Selected":""}}>{{$regionRow->region}}</option>
									@else
									<option value="{{$regionRow->id}}" >{{$regionRow->region}}</option>
									@endif
									
								@endforeach
							@endisset
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
							<th>Vehicle Inside WS	</th>
							<th>NH	</th>
							<th>City</th>
							<th>State</th>
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
							<th>Time passed since call log (Day:hour:minute)</th>
							<th>SO Number</th>
							<th>Jobcard Number</th>
							<th>Est. Response Date	</th>
							<th>Est. Response Time	</th>
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
							<th>Owner Name	</th>
							<th>Owner Mobile No	</th>
							<th>Registration Number	</th>
							<th>Chassis Number	</th>
							<th>Vehicle Model	</th>
							<th>Engine Number	</th>
							<th>Vehicle Segment	</th>
							<th>Purchase Date	</th>
							<th>AddBlue	</th>
							<th>EngineEmmisionType	</th>
							<th>Owner Category	</th>
							<th>Company Name	</th>
							<th>Ticket Type	</th>
							<th>Customer Remarks	</th>
							<th>Vehicle Status	</th>
							<th>IS Vehicle movable	</th>
							<th>Breakdown Location	</th>
							<th>District	</th>
							<th>Issue	</th>
							<th>Support Code	</th>
							<th>Aggregate	</th>
							<th>Support Center Type	</th>
							<th>Support Center Name	</th>
							
							<th>Support Contact Person	</th>
							<th>Support Contact Number	</th>
							<th>Last Remark Time	</th>
							<th>Remark Type	</th>
							<th>Latest Comments	</th>
							<th>ALSE Name	</th>
							<th>ALSE Contact Number	</th>
							<th>First Called_By	</th>
							<th>Last Called_By	</th>
							<th>Last Called On	</th>
							<th>First Called On	</th>
							<th>Support Center Assigned Date	</th>
							<th>Support Center Assigned time	</th>
							<th>Follow Up Hours Passed	</th>
							<th>Disposition	</th>
							<th>Used Google Map	</th>
							<th>Last Update By	</th>
							<th>Last Update Date	</th>
							<th>Reassign Date</th>
							<th>Reassign Time</th>
							<th>Restoration Type</th>
							<th>Response Delay Reason</th>
							<th>Total Restoration Time (Delay Time)</th>
							<th>Days</th>
							<th>Ageing</th>
							<th>Source</th>
							<th>Restoration Delay Reason</th>
							<th>Total Follow Up</th>
							<th>Awaiting Customer Approval</th>
							<th>Awaiting Customer Payment</th>
							<th>Awaiting Parts From Customer</th>
							<th>Vehicle Being Towed</th>
							<th>Total Hold</th>
						</tr>
					</thead> 
					<tbody>
						@isset($consolidatedReport)	 
							{{-- {{dd($consolidatedReport)}} --}}
							@foreach ($consolidatedReport as $row)
								<tr style="background-color: #d3d6d2;">
									<td>{{$row->complaint_number}}</td>
									<td>{{$row->remark_type}}</td>
									<td>{{$row->feedback_rating}}</td>
									<td>{{$row->feedback_desc}}</td>
									<td>{{$row->ticket_type}}</td>
									<td>{{$row->highway}}</td>
									<td>{{$row->city}}</td>
									<td>{{$row->state}}</td>
									<td>{{ $row->assignedDealerZone }}</td>
									<td>{{$row->stateName}}</td>
									<td>{{$row->delCityName}}</td>
									<td>{{$row->createdby!=''?$row->createdby:'NA'}}</td>
									<td>{{$row->complaintDate!=''?date('d-m-Y',strtotime($row->complaintDate)):'NA'}}</td>
									<td>{{$row->complaintDate!=''?date('H:i:s',strtotime($row->complaintDate)):'NA'}}</td>
									<td>{{$row->completedby!=''?$row->completedby:'NA'}}</td>
									<td>{{$row->completionDate!=''?date('d-m-Y',strtotime($row->completionDate)):'NA'}}</td>
									<td>{{$row->completionDate!=''?date('H:i:s',strtotime($row->completionDate)):'NA'}}</td>
									<td>{{$row->closedby!=''?$row->closedby:'NA'}}</td>
									<td>{{$row->closedDate!=''?date('d-m-Y',strtotime($row->closedDate)):'NA'}}</td>
									<td>{{$row->closedDate!=''?date('H:i:s',strtotime($row->closedDate)):'NA'}}</td>
									@php  
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
											
											if(isset($row->complaintDate)){
												$first_date = new DateTime($row->complaintDate);
												$second_date = new DateTime(date("Y-m-d H:i:s"));
												$difference = $first_date->diff($second_date);
												$timePassedSinceCallLog =$difference->d.':'.$difference->h.':'.$difference->i;
											}else{
												$timePassedSinceCallLog='NA';
											}
										/* } */
									@endphp
									<td>{{$timePassedSinceCallLog}}</td>
									<td>{{$row->so_number}}</td>
									<td>{{$row->jobcard_number}}</td>
									<td>{{date('d-m-Y',strtotime($row->estimated_response_time))}}</td>
									<td>{{date('H:i:s',strtotime($row->estimated_response_time))}}</td>
									<td>{{$row->actual_response_time !=''?date('d-m-Y',strtotime($row->actual_response_time)):'NA'}}</td>
									<td>{{$row->actual_response_time !=''?date('H:i:s',strtotime($row->actual_response_time)):'NA'}}</td>
									<td>{{($row->actual_response_time_customer !='' && $row->actual_response_time_customer !='NA')?date('d-m-Y',strtotime($row->actual_response_time_customer)):''}}</td>
									<td>{{($row->actual_response_time_customer !='' && $row->actual_response_time_customer !='NA')?date('H:i:s',strtotime($row->actual_response_time_customer)):''}}</td>
									@php  										
										$actual_response_time_gap ='NA';
										if($row->actual_response_time !=''){
											$first_date = new DateTime($row->complaintDate);
											$second_date = new DateTime($row->actual_response_time);
											$difference = $first_date->diff($second_date);
											$actual_response_time_gap =$difference->d.'-D '.$difference->h.'-H '.$difference->i.'-M';
										}										
									@endphp
									<td>{{$actual_response_time_gap}}</td>
									@php
										$tat_scheduledDate =$row->tat_scheduled!=''?date('d-m-Y',strtotime($row->tat_scheduled)):'NA';
										$tat_scheduledTime =$row->tat_scheduled!=''?date('H:i:s',strtotime($row->tat_scheduled)):'NA';
										$tat_scheduled_customerDate =($row->tat_scheduled_customer!='' && $row->tat_scheduled_customer!='NA')?date('d-m-Y',strtotime($row->tat_scheduled_customer)):'';
										$tat_scheduled_customerTime =($row->tat_scheduled_customer!='' && $row->tat_scheduled_customer!='NA')?date('H:i:s',strtotime($row->tat_scheduled_customer)):'';
									@endphp
									<td>{{$tat_scheduledDate}}</td>
									<td>{{$tat_scheduledTime}}</td>
									<td>{{$tat_scheduled_customerDate}}</td>
									<td>{{$tat_scheduled_customerTime}}</td>
									@php
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
									@endphp
									<td>{{$totalRestorationTime}}</td>
									<td>{{$row->caller_name}}</td>
									<td>{{$row->caller_type}}</td>
									<td>{{$row->caller_contact}}</td>
									<td>{{$row->caller_language}}</td>
									<td>{{$row->owner_name}}</td>
									<td>{{$row->owner_mob}}</td>
									<td>{{$row->reg_number}}</td>
									<td>{{$row->chassis_number}}</td>
									<td>{{$row->vehicle_model}}</td>
									<td>{{$row->engine_number}}</td>
									<td>{{$row->vehicle_segment}}</td>
									<td>{{date('d-m-Y H:i:s',strtotime($row->purchase_date))}}</td>
									<td>{{$row->add_blue_use}}</td>
									<td>{{$row->engine_emmission_type}}</td>
									<td>Al Select</td>
									<td>{{$row->owner_company}}</td>
									<td>{{$row->ticket_type}}</td>
									<td>{{$row->standard_remark}}</td>
									<td>{{$row->vehicle_type}}</td> 
									<td>{{$row->vehicle_movable}}</td>
									<td>{{$row->location}}</td>
									<td>{{$row->city}}</td>
									<td>{{$row->standard_remark}}</td>
									<td>{{$row->sac_code}}</td>
									<td>{{$row->aggregate}}</td>
									<td>{{$row->dealer_type!=''?$row->dealer_type:'NA'}}</td>
									<td>{{$row->dealer_name}}</td>
									
									@php
										$Support_Contact_Person = $row->Support_Contact_Person!=''?$row->Support_Contact_Person:'NA~~NA';
										$Support_Contact_PersonArr = explode("~~",$Support_Contact_Person);
									@endphp
									<td>{{$Support_Contact_PersonArr[0]}}</td>
									<td>{{$Support_Contact_PersonArr[1]}}</td>
									<td>{{$row->complaintUpdate!=''?date('H:i:s',strtotime($row->complaintUpdate)):''}}</td>
									<td>{{$row->remark_type}}</td>
									<td>{{$row->assign_remarks}}</td>
									@php 
										$alsedetails = $row->alsedetails;
										if($alsedetails!=''){
											$alsedetailsArr = explode("~~",$alsedetails);
										}else{
											$alsedetailsArr = explode("~~","NA~~NA");
										}
										
									@endphp
									<td>{{$alsedetailsArr[0]}}</td>
									<td>{{$alsedetailsArr[1]}}</td>
									<td>{{$row->firstcallagent}}</td>
									<td>{{$row->lastcallagent}}</td>
									<td>{{$row->lastcallagentTime}}</td>
									<td>{{$row->firstcallagentTime}}</td>
									<td>{{date('d-m-Y',strtotime($row->complaintDate))}}</td>
									<td>{{date('H:i:s',strtotime($row->complaintDate))}}</td>
									@php  
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
										
										
									@endphp
									<td>{{$followUpCall}}</td>
									<td>{{$row->lastcallagentdisposition}}</td>
									<td>{{$row->used_google_map}}</td>
									<td>{{$row->lastupdatename}}</td>
									<td>{{date('d-m-Y H:i:s',strtotime($row->lastupdatedate))}}</td>
									<td>{{$row->reassignDate!=''?date('d-m-Y',strtotime($row->reassignDate)):'NA'}}</td>
									<td>{{$row->reassignDate!=''?date('H:i:s',strtotime($row->reassignDate)):'NA'}}</td>
									<td>{{$row->restoration_type}}</td>
									<td>{{$row->response_delay_reason}}</td>
									@php
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
									@endphp
									<td>{{ $completionHour!='NA'?ceil($completionHour):'NA' }}</td>
									<td>{{ $completionDay!='NA'?ceil($completionDay):'NA' }}</td>
									<td>
										@if ($completionHour <= 4 && $completionHour !='NA')
											{{ "Within 4 Hrs." }}
										@elseif ($completionHour >4 && $completionHour <= 48 && $completionHour !='NA')
											{{ "Above 4 hrs. to 48 hrs." }}
										@elseif ($completionHour >48 && $completionHour <= 72 && $completionHour !='NA')
											{{ "Above 48 hrs. to 72 hrs." }}
										@elseif ($completionHour > 72 && $completionHour <= 120 && $completionHour !='NA')
											{{ "Above 72 hrs. to 120 hrs." }}
										@elseif ($completionHour > 120 && $completionHour !='NA')
											@php
												$defaulthrs = floor($completionHour/24);
												$hoursGet = $defaulthrs * 24;
											@endphp
											{{ "Above $hoursGet hrs." }}
										@else
											{{ "NA" }}
										@endif
									</td>
									<td>{{$row->source}}</td>
									<td>{{$row->restoration_delay}}</td>
									<td>{{$row->followupcount}}</td>
									@php
									$currentDate = date("Y-m-d H:i:s");
									$aca_created_at = $row->aca_created_at!=''?$row->aca_created_at:'NA';
									$aca_updated_at = $row->aca_updated_at!=''?$row->aca_updated_at:$currentDate;
									if($aca_created_at !='NA' && $aca_updated_at !='NA'){
										$first_date = new DateTime($aca_created_at);
										$second_date = new DateTime($aca_updated_at);
										$difference = $first_date->diff($second_date);
										$acaTime = $difference->d.':'.$difference->h.':'.$difference->i;
									}else{
										$acaTime = 'NA';
									}
									$acp_created_at = $row->acp_created_at!=''?$row->acp_created_at:'NA';
									$acp_updated_at = $row->acp_updated_at!=''?$row->acp_updated_at:$currentDate;
									if($acp_created_at !='NA' && $acp_updated_at !='NA'){
										$first_date = new DateTime($acp_created_at);
										$second_date = new DateTime($acp_updated_at);
										$difference = $first_date->diff($second_date);
										$acpTime = $difference->d.':'.$difference->h.':'.$difference->i;
									}else{
										$acpTime = 'NA';
									}
									$apc_created_at = $row->apc_created_at!=''?$row->apc_created_at:'NA';
									$apc_updated_at = $row->apc_updated_at!=''?$row->apc_updated_at:$currentDate;
									if($apc_created_at !='NA' && $apc_updated_at !='NA'){
										$first_date = new DateTime($apc_created_at);
										$second_date = new DateTime($apc_updated_at);
										$difference = $first_date->diff($second_date);
										$apcTime = $difference->d.':'.$difference->h.':'.$difference->i;
									}else{
										$apcTime = 'NA';
									}
									$vbt_created_at = $row->vbt_created_at!=''?$row->vbt_created_at:'NA';
									$vbt_updated_at = $row->vbt_updated_at!=''?$row->vbt_updated_at:$currentDate;
									if($vbt_created_at !='NA' && $vbt_updated_at !='NA'){
										$first_date = new DateTime($vbt_created_at);
										$second_date = new DateTime($vbt_updated_at);
										$difference = $first_date->diff($second_date);
										$vbtTime = $difference->d.':'.$difference->h.':'.$difference->i;
									}else{
										$vbtTime = 'NA';
									}
									@endphp 
									
									<td>{{$acaTime}}</td>
									<td>{{$acpTime}}</td>
									<td>{{$apcTime}}</td>
									<td>{{$vbtTime}}</td>
									@php
										$days =0; $hour = 0; $minute = 0;
										if($acaTime !='NA'){
											$acaTimeArr =  explode(":",$acaTime);
											$days += $acaTimeArr[0];
											$hour += $acaTimeArr[1];
											$minute += $acaTimeArr[2];
										}
										if($acpTime !='NA'){
											$acpTimeArr =  explode(":",$acpTime);
											$days += $acpTimeArr[0];
											$hour += $acpTimeArr[1];
											$minute += $acpTimeArr[2];
										}
										if($apcTime !='NA'){
											$apcTimeArr =  explode(":",$apcTime);
											$days += $apcTimeArr[0];
											$hour += $apcTimeArr[1];
											$minute += $apcTimeArr[2];
										}
										if($vbtTime !='NA'){
											$vbtTimeArr =  explode(":",$vbtTime);
											$days += $vbtTimeArr[0];
											$hour += $vbtTimeArr[1];
											$minute += $vbtTimeArr[2];
										}
									@endphp
									{{-- <td>{{'Days: '.$days.', Hours: '.$hour.', Minutes: '.$minute}}</td> --}}
									<td>{{$days.':'.$hour.':'.$minute}}</td>
								</tr>
							@endforeach
							
							@if(empty($consolidatedReport))
							<tr>
								<td colspan="17">
									{{"No Data Found"}}
								</td>
							</tr>
							   @endif
						@endisset
						
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
		$.ajax({ url: '{{url("get-multiple-zone-change")}}',
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
	$.ajax({ url: '{{url("get-multiple-state-id-city")}}',
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
	
	$.ajax({ url: '{{url("get-city-change-dealer")}}',
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
@endsection
