<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Session;
use Redirect;
use Mail;
use DateTime;
use App\classes\ServerValidation;
use App\classes\AccessControl;
date_default_timezone_set('Asia/Kolkata');
class CronJobController  extends Controller{
	public function autoEscalate(){
		$emailTrigger = '';
		$currentTime = date('H');
		$currentTimeAMPM = date('h');
		/* $currentTime = 8;
		$currentTimeAMPM =8; */
		
		if($currentTime == 1){
			$currentTimeLess =12;
			$currentTime =12.59;
		}else{
			$currentTimeLess = $currentTime -1;
		}
		if($currentTimeAMPM == 1){
			$currentTimeAMPMLess = 0;
		}else{
			$currentTimeAMPMLess = $currentTimeAMPM - 1;
		}
		/* $rowQuery =DB::select("select id, complaint_number, vehicleId, ownerId, customer_contact_id, callerId, from_where, to_where, highway, ticket_type, 
		vehicle_problem, assign_to, dealer_mob_number, dealer_alt_mob_number, remark_type, disposition, agent_remark,
	   standard_remark, assign_remarks, estimated_response_time, tat_scheduled, acceptance, latitude, longitude,created_at from cases where ((hour(created_at) >=7 and hour(created_at) < 8) ) and  remark_type  not in ('Closed') and complaint_number <> '' order by id desc	"); */
		
		$rowQuery =DB::select("select id, complaint_number, vehicleId, ownerId, customer_contact_id, callerId, from_where, to_where, highway, ticket_type, 
		vehicle_problem, assign_to, dealer_mob_number, dealer_alt_mob_number, remark_type, disposition, agent_remark,
	   standard_remark, assign_remarks, estimated_response_time, tat_scheduled, acceptance, latitude, longitude,created_at from cases where ((hour(created_at) >=$currentTimeLess and hour(created_at) < $currentTime) or ((case when hour(created_at)>12 then  hour(created_at)-12 else hour(created_at) end) >=$currentTimeAMPMLess and (case when hour(created_at)>12 then  hour(created_at)-12 else hour(created_at) end) < $currentTimeAMPM)) and  remark_type  not in ('Work Completed','Customer Confirmation Due', 'Customer Confirmation Completed', 'Customer Feedback','Ticket Closed','Closed' ,'Completed') and complaint_number <> '' order by id desc");
		//dd($rowQuery);
		// 'Work Completed','Customer Confirmation Due', 'Customer Confirmation Completed', 'Customer Feedback','Ticket Closed','Closed' ,'Completed'
		$maxLevelSql = DB::select("Select id,level, level_name, to_role, cc_role, hours, created_at, updated_at from mstr_escalations order by id desc");
		$maxLevel = $maxLevelSql[0]->level;
		$levelQuery = DB::select("Select id,level, level_name, to_role, cc_role, hours, created_at, updated_at from mstr_escalations");
		foreach($rowQuery as $row){
			$complaint_number = $row->complaint_number;
			$created_at = $row->created_at;
			
			$currentDate = date('Y-m-d H:i:s');
			$date1 = $created_at;
			$date2 = $currentDate;
			$timestamp1 = strtotime($date1);
			$timestamp2 = strtotime($date2);
			$maxLevelEscalation = ROUND(abs($timestamp2 - $timestamp1)/(60*60));
			for($i=0;$i < sizeof($levelQuery);$i++){
				if($maxLevelEscalation < 12){
					$levelName = 1;
					DB::select("set sql_safe_updates = 0");
					DB::select("Update escaltion_levels set levels = $levelName,updated_at='$currentDate' where complaint_number = '$complaint_number' ");
				}else if($maxLevelEscalation >= 120 ){
					$levelName = 8;
					DB::select("set sql_safe_updates = 0");
					DB::select("Update escaltion_levels set levels = $levelName,updated_at='$currentDate' where complaint_number = '$complaint_number'");
				}else if($maxLevelEscalation >= $levelQuery[$i]->hours && $maxLevelEscalation < $levelQuery[$i+1]->hours ){
					$levelName = $levelQuery[$i-1]->level;
					DB::select("set sql_safe_updates = 0");
					DB::select("Update escaltion_levels set levels = $levelName,updated_at='$currentDate' where complaint_number = '$complaint_number'");
				}
			}
			
		}

		foreach($rowQuery as $row){
			$id = $row->id;
			$complaint_number = $row->complaint_number;
			$vehicleId = $row->vehicleId;
			$emsnTypeSql = DB::select("Select engine_emmission_type from mstr_vehicle where id='$vehicleId'");
			$engine_emmission_type =  (sizeof($emsnTypeSql)>0 && $emsnTypeSql[0]->engine_emmission_type!='')?$emsnTypeSql[0]->engine_emmission_type:'NA';
			$assign_to = $row->assign_to;
			$created_at = $row->created_at;
			$escLevelSql = DB::select("Select levels, complaint_number from escaltion_levels where complaint_number='$complaint_number'");
			$level='';
			if(sizeof($escLevelSql)>0){
				$level = $escLevelSql[0]->levels;
			}
			$dealerDataSql = DB::select("Select d.dealer_name,d.phone,d.sac_code,s.state as stateName,c.city as cityName from mstr_dealer as d left join mstr_state as s on s.id = d.state left join mstr_city as c on c.id = d.city  where d.id = $assign_to");
			$assignDealerCode = $dealerDataSql[0]->sac_code;
			$assignDealerName = $dealerDataSql[0]->dealer_name;
			$AOCityName = $dealerDataSql[0]->cityName;
			$query = DB::select("select c.id as caseId, c.complaint_number, c.vehicleId, c.ownerId, c.customer_contact_id, c.callerId, c.from_where, c.to_where, c.highway, c.ticket_type, c.vehicle_problem, c.assign_to, c.dealer_mob_number, c.dealer_alt_mob_number, c.remark_type, c.disposition, c.agent_remark, c.standard_remark, c.assign_remarks, c.estimated_response_time, c.actual_response_time, c.tat_scheduled, c.acceptance, c.latitude, c.longitude,c.feedback_rating,c.feedback_desc,c.location,c.landmark,c.state as stateId,c.city as cityId,c.district,c.created_at, c.vehicle_type, v.vehicle, v.vehicle_model, v.reg_number, v.chassis_number, v.engine_number, v.vehicle_segment, v.purchase_date, v.add_blue_use, v.engine_emmission_type, o.owner_name, o.owner_mob, o.owner_landline, o.owner_cat, o.owner_company,o.alse_mail,o.asm_mail, oc.contact_name, oc.mob,oc.owner_contact_email,cal.caller_type, cal.caller_name, cal.caller_contact, cal.vehicle_movable, s.state, city.city,group_concat(rem.assign_remarks order by rem.id desc separator '@@') as assign_remark_log,group_concat(rem.created_at order by rem.id desc separator '@@') as assign_remark_date_log from cases as c left join mstr_vehicle as v on v.id = c.vehicleId left join mstr_owner as o on o.id = c.ownerId  and o.id = c.ownerId left join mstr_owner_contact as oc on oc.id = c.customer_contact_id and oc.vehicle_id = c.vehicleId and oc.owner_id = c.ownerId left join mstr_caller as cal on cal.id = c.callerId  and cal.owner_id = c.ownerId  left join mstr_caller_state as s on s.id = c.state left join mstr_caller_city as city on city.id = c.city left join remarks as rem on rem.complaint_number = c.complaint_number  where c.complaint_number = '$complaint_number'");
					$regNo =$query[0]->reg_number;
					$location =$query[0]->location;
					$db_estimated_response_time = $query[0]->estimated_response_time;
					$db_tat_scheduled = $query[0]->tat_scheduled;
					$db_contact_name = $query[0]->contact_name;
					$db_contact_mob = $query[0]->mob;
					$db_owner_name = $query[0]->owner_name;
					$db_owner_company = $query[0]->owner_company;
					$db_owner_mob = $query[0]->owner_mob;
					$db_assign_to = $query[0]->assign_to;
					$db_dealer_mob_number = $query[0]->dealer_mob_number;
					$db_dealer_alt_mob_number = $query[0]->dealer_alt_mob_number;
					$db_disposition = $query[0]->disposition;
					$db_agent_remark = $query[0]->agent_remark;
					$db_highway = $query[0]->highway;
					$db_city = $query[0]->city;
					$db_vehicle_model = $query[0]->vehicle_model;
					$db_chassis_number = $query[0]->chassis_number;
					$db_vehicle_type = $query[0]->vehicle_type;
					$db_created_at = $query[0]->created_at;
					$db_caller_name = $query[0]->caller_name;
					$db_caller_type = $query[0]->caller_type;
					$db_caller_contact = $query[0]->caller_contact;
					
					$db_acceptance = $query[0]->acceptance;
					$db_feedback_desc = $query[0]->feedback_desc;
					$db_feedback_rating = $query[0]->feedback_rating;
					$db_engine_emmission_type = $query[0]->engine_emmission_type;
					$ticetId = $query[0]->caseId;
					$alse_mail = $query[0]->alse_mail;
					$asm_mail = $query[0]->asm_mail;
					$assign_remark_log = explode("@@",$query[0]->assign_remark_log);
			 		$assign_remark_date_log = explode("@@",$query[0]->assign_remark_date_log);
			
			if($level <= $maxLevel && $level!=''){
				if($level == $maxLevel){
					$level = $level;
				}else if($level < $maxLevel){
					$level = $level+1;
				}
				$matrix = DB::select("Select id, level_name, to_role, cc_role, hours, created_at, updated_at from mstr_escalations where level =$level");
				$hours = $matrix[0]->hours;
				$level_name = $matrix[0]->level_name;
				$addTime = date('Y-m-d H:i:s',strtotime('+'.$hours.' hour',strtotime($created_at)));
				$currentDateTime = date('Y-m-d H:i:s');
				
				if($currentDateTime > $addTime){
					$to_role = $matrix[0]->to_role;
					$cc_role = $matrix[0]->cc_role;
					if($engine_emmission_type == 'BS6'){
						$cc_role =$cc_role.',91';
					}
					
					$toUsersSql =  DB::select("Select email,name from users where role in ($to_role) and FIND_IN_SET($assign_to, dealer_id) and flag=1");
					$ccUserSql =   DB::select("Select email,name from users where role in ($cc_role) and FIND_IN_SET($assign_to, dealer_id) and flag=1");
					
					$toUserArr=$ccUserArr ='';
					if(sizeof($toUsersSql)>0){
						foreach($toUsersSql as $row){
							if($row->email !=''){
								$toUser = trim($row->email);
								$toUser = str_replace(":",",",$toUser);
								$toUser = str_replace(";",",",$toUser);
								$toUser = str_replace(" ","",$toUser);
								$toUserArr .= $toUser.",";
							}else{
								$toUserArr .= "test@dispostable.com".",";
							}
						}
						$toUserArr = rtrim($toUserArr,',');
						$toUserArr = explode(",",$toUserArr);
					}else{
						$toUserArr = array("test@dispostable.com");
					}
					if(sizeof($ccUserSql)>0){
						foreach($ccUserSql as $row){
							
							if($row->email !=''){
								$ccUser = trim($row->email);
								$ccUser = str_replace(":",",",$ccUser);
								$ccUser = str_replace(";",",",$ccUser);
								$ccUser = str_replace(" ","",$ccUser);
								$ccUserArr .= $ccUser.",";
								
							}else{
								$ccUserArr .= "test@dispostable.com".",";
							}
						}
						$ccUserArr = rtrim($ccUserArr,',');
						$ccUserArr = explode(",",$ccUserArr);
					}else{
						$ccUserArr = array("test@dispostable.com");
					}
					$levName='';
					if($level == $maxLevel){
						$level = $level;
						$currentDate = date('Y-m-d H:i:s');
						
						$date1 = $db_created_at;
 						$date2 = $currentDate;
 						$timestamp1 = strtotime($date1);
 						$timestamp2 = strtotime($date2);
 						$maxLevelEscalation = ROUND(abs($timestamp2 - $timestamp1)/(60*60)); 
 						//$defaulthrs = 120;
						 $defaulthrs = floor($maxLevelEscalation/24);
						 $levName = $defaulthrs * 24;					
 						
						 
					}else if($level < $maxLevel){
						$levNameArr = explode(" ",$level_name);
						$levName = $levNameArr[0];
						
					}
					$supportContPersonSql = DB::select("Select mobile,name from users where role =76 and FIND_IN_SET($assign_to, dealer_id) and flag=1");
					$supportContPerson = sizeof($supportContPersonSql)>0?$supportContPersonSql[0]->name:'NA';
					$supportContPersonMob = sizeof($supportContPersonSql)>0?$supportContPersonSql[0]->mobile:'NA';	

					/* **********************************Addon**************************************************** */
					$escLvlQuery = DB::select("SELECT levels FROM escaltion_levels where complaint_number ='$complaint_number'");
					$actualLevlVal = $escLvlQuery[0]->levels;
					if($actualLevlVal  == 1){
						$emailTrigger = 'ok';
					}else if($actualLevlVal > 1){
						$escLogQuery = DB::select("SELECT subject FROM escalation_log where complaint_number = '$complaint_number' and level >=2 order by id desc limit 1");
						if(sizeof($escLogQuery)){
							$escSubjectVal = $escLogQuery[0]->subject;						
							$checkVal = $levName.' Hrs';						
							if (strpos($escSubjectVal, $checkVal) == false) {
								$emailTrigger = 'ok';
							}else{
								$emailTrigger = '';
							}
						}else{
							$emailTrigger = 'ok';
						}
						
					}else{
						$emailTrigger = '';
					}
					
					/* **********************************Addon**************************************************** */

					$subjct1 ="SELECT Ticket Details-Above $levName Hrs $complaint_number";
					$subjct2 ="BSVI SELECT Ticket Details-Above $levName Hrs $complaint_number";
					$subject=$engine_emmission_type=='BS6'?$subjct2:$subjct1;

					$restorationDate = $db_tat_scheduled!=''?date("d-m-Y",strtotime($db_tat_scheduled)):"NA";
					$restorationTime = $db_tat_scheduled!=''?date("H:i:s",strtotime($db_tat_scheduled)):"NA";
					/* ******************************************************************************************** */
						
						$ownerALSEEmail = $alse_mail; 
						$ownerALSEEmail = str_replace(":",",",$ownerALSEEmail); 
						$ownerALSEEmail = str_replace(";",",",$ownerALSEEmail); 
						$ownerALSEEmail = str_replace(" ","",$ownerALSEEmail); 
						$alseOwnerEmail = $ownerALSEEmail; 
						$ownerASMEmail = $asm_mail; 
						$ownerASMEmail = str_replace(":",",",$ownerASMEmail);
						$ownerASMEmail = str_replace(";",",",$ownerASMEmail); 
						$ownerASMEmail = str_replace(" ","",$ownerASMEmail); 
						$asmOwnerEmail = $ownerASMEmail; 
					
					$asmOwnerEmailArr = explode(",",$asmOwnerEmail); 
					$ccOwnerEmail = explode(",",$alseOwnerEmail); 
					
					$ccOwnerEmail = array_merge($ccOwnerEmail,$asmOwnerEmailArr);
					$ccUserArr = array_merge($ccUserArr,$ccOwnerEmail);
					
					/* ******************************************************************************************** */
					/* *******BS6******* */
			if($engine_emmission_type=='BS6'){
			$dealerBSSql = DB::select("Select bsvi,area_champion,region_champion from mstr_dealer where id = $assign_to");
			$bsvi = $dealerBSSql[0]->bsvi!=''?$dealerBSSql[0]->bsvi:'test@dispostable.com';
			$bsvi = str_replace(":",",",$bsvi);
			$bsvi = str_replace(";",",",$bsvi);
			$bsvi = str_replace(" ","",$bsvi);
			$bsviEmail = $bsvi;
			$area_champion = $dealerBSSql[0]->area_champion!=''?$dealerBSSql[0]->area_champion:'test@dispostable.com';
			$area_champion = str_replace(":",",",$area_champion);
			$area_champion = str_replace(";",",",$area_champion);
			$area_champion = str_replace(" ","",$area_champion);
			$area_championEmail = $area_champion;
			$region_champion = $dealerBSSql[0]->region_champion!=''?$dealerBSSql[0]->region_champion:'test@dispostable.com';
			$region_champion = str_replace(":",",",$region_champion);
			$region_champion = str_replace(";",",",$region_champion);
			$region_champion = str_replace(" ","",$region_champion);
			$region_championEmail = $region_champion;

			$bsviEmailArr = explode(",",$bsviEmail);
			$area_championEmailArr = explode(",",$area_championEmail);
			$region_championEmailArr = explode(",",$region_championEmail);
			$ccUserArr = array_merge($ccUserArr, $bsviEmailArr, $area_championEmailArr,$region_championEmailArr);
			}
			/* *******BS6******* */


					$body = '<p>Dear Team, </p>
					<p>Please find the below mentioned Break Down Details</p>
					<p>Kindly update the Response, Restoration and closure details by using Dealer Portal using the link. </p>
					<table border="1" style="font-family: sans-serif;">
				<tr>
					<td style=" text-align: left;">Customer Name</td>
					<td style="text-align: left;">'.$db_owner_company.'</td>
					
				</tr>
				<tr>
					<td style=" text-align: left;">Ticket Number</td>
					<td style="text-align: left;"><a href="'.url('update-case',['id' =>$ticetId]).'" >'.$complaint_number.'</a></td>		
					
				</tr>
				<tr>
					<td style=" text-align: left;">Breakdown Location</td>
					<td style="text-align: left;">'.$location.'</td>		
					
				</tr>
				<tr>
					<td style=" text-align: left;">NH</td>
					<td style="text-align: left;">'.$db_highway.'</td>		
					
				</tr>
				<tr>
					<td style=" text-align: left;">AO</td>
					<td style="text-align: left;">'.$AOCityName.'</td>		
					
				</tr>
				<tr>
					<td style=" text-align: left;">Registration Number</td>
					<td style="text-align: left;">'.$regNo.'</td>
					
				</tr>
				<tr>
					<td style=" text-align: left;">Vehicle Model</td>
					<td style="text-align: left;">'.$db_vehicle_model.'</td>
					
				</tr>
				<tr>
					<td style=" text-align: left;">Chassis Number</td>
					<td style="text-align: left;">'.$db_chassis_number.'</td>
					
				</tr>
				<tr>
					<td style=" text-align: left;">Vehicle Type</td>
					<td style="text-align: left;">'.$db_vehicle_type.'</td>
					
				</tr>
				<tr>
					<td style=" text-align: left;">Call Log Date</td>
					<td style="text-align: left;">'.date("d-m-Y",strtotime($db_created_at)).'</td>
					
				</tr>
				<tr>
					<td style=" text-align: left;">Call Log Time</td>
					<td style="text-align: left;">'.date("H:i:s",strtotime($db_created_at)).'</td>					
				</tr>
				<tr>
					<td style=" text-align: left;">Caller Name</td>
					<td style="text-align: left;">'.$db_caller_name.'</td>					
				</tr>
				<tr>
					<td style=" text-align: left;">Caller Type</td>
					<td style="text-align: left;">'.$db_caller_type.'</td>					
				</tr>
				<tr>
					<td style=" text-align: left;">Caller Contact</td>
					<td style="text-align: left;">'.$db_caller_contact.'</td>					
				</tr>
				<tr>
					<td style=" text-align: left;">Support Cont Person</td>
					<td style="text-align: left;">'.$supportContPerson.'</td>					
				</tr>
				<tr>
					<td style=" text-align: left;">Support Cont Number</td>
					<td style="text-align: left;">'.$supportContPersonMob.'</td>					
				</tr>
				<tr>
					<td style=" text-align: left;">Support Centre Code</td>
					<td style="text-align: left;">'.$assignDealerCode.'</td>
				</tr>
				<tr>
					<td style=" text-align: left;">Support Centre Name</td>
					<td style="text-align: left;">'.$assignDealerName.'</td>
				</tr>
				<tr>  
					<td style=" text-align: left;">Response Date</td>
					<td style="text-align: left;">'.date("d-m-Y",strtotime($db_estimated_response_time)).'</td>					
				</tr>
				<tr>  
					<td style=" text-align: left;">Response Time</td>
					<td style="text-align: left;">'.date("H:i:s",strtotime($db_estimated_response_time)).'</td>					
				</tr>
				<tr>  
					<td style=" text-align: left;">Restoration Date</td>
					<td style="text-align: left;">'.$restorationDate.'</td>					
				</tr>
				<tr>  
					<td style=" text-align: left;">Restoration Time</td>
					<td style="text-align: left;">'.$restorationTime.'</td>					
				</tr>';
				for($i=0;$i<sizeof($assign_remark_log);$i++){
					$date = date('d-m-Y H:i:s',strtotime($assign_remark_date_log[$i]));
					if($i == 0){
						$body .= '<tr><td style="text-align: left;">Latest Comment '.$date.'</td>';
					}else{
						$body .= '<tr><td style="text-align: left;">Previous Comment '.$date.'</td>';
					}
					$body .= '<td style="text-align: left;">'.$assign_remark_log[$i].'</td></tr>';
				}					
				$body .='
				
			</table>
			<p>Regards,</p>
			<p>SELECT Support Cell</p>
					<br/><p style="text-decoration: underline;">Note:</p>
					<p>This is a system generated email, please do not reply</p>';
					
					$data=['body'=>$body];
					try {
						if($emailTrigger == 'ok'){
							Mail::send('assigned_email',["data"=>$data],function ($message) use ($toUserArr, $ccUserArr, $subject) {
								$message->to(array_filter($toUserArr))->cc(array_filter($ccUserArr))->bcc(['ashutosh.rawat@cogenteservices.in','ravikiran.v@cogenteservices.com','siddegowda.s@cogenteservices.com','Panchakarla.SaiPra@ashokleyland.com','madhur.bhati@cogenteservices.com'])->subject($subject);
								$message->from('select.support@ashokleyland.com');
							});
						}
					} catch (\Exception $ex){
						DB::table('escalation_error')->insert(['complaint_number'=>$complaint_number,'error'=>$ex]);
					}
					
					if($emailTrigger == 'ok'){
						DB::table('escaltion_levels')->where('complaint_number', $complaint_number)->update(['levels' => $level,'updated_at'=>"$currentDateTime"]);
						DB::table('escalation_log')->insert(['level'=>$level,'complaint_number'=>$complaint_number,'to_user'=>implode(",",$toUserArr),'cc_user'=>implode(",",$ccUserArr),'subject'=>$subject,'body'=>$body]);
					}
				}
				

			}
			
			

			


		}
		
	}
	public function testEscalate(){
		$currentTime = date('H');
		$currentTimeLess = $currentTime -1;
		
		$rowQuery =DB::select("select id, complaint_number, vehicleId, ownerId, customer_contact_id, callerId, from_where, to_where, highway, ticket_type, 
		 vehicle_problem, assign_to, dealer_mob_number, dealer_alt_mob_number, remark_type, disposition, agent_remark,
		standard_remark, assign_remarks, estimated_response_time, tat_scheduled, acceptance, latitude, longitude,created_at from cases where hour(created_at) >=17 and hour(created_at) < 18 and  remark_type  not in ('Work Completed','Customer Confirmation Due', 'Customer Confirmation Completed', 'Customer Feedback','Ticket Closed','Closed' ,'Completed') and complaint_number <>'' order by id desc");
		
		$maxLevelSql = DB::select("Select id,level, level_name, to_role, cc_role, hours, created_at, updated_at from mstr_escalations order by id desc");
		$maxLevel = $maxLevelSql[0]->level;
		
		foreach($rowQuery as $row){
			$id = $row->id;
			$complaint_number = $row->complaint_number;
			$vehicleId = $row->vehicleId;
			$emsnTypeSql = DB::select("Select engine_emmission_type from mstr_vehicle where id='$vehicleId'");
			$engine_emmission_type =  (sizeof($emsnTypeSql)>0 && $emsnTypeSql[0]->engine_emmission_type!='')?$emsnTypeSql[0]->engine_emmission_type:'NA';
			$assign_to = $row->assign_to;
			$created_at = $row->created_at;
			$escLevelSql = DB::select("Select levels, complaint_number from escaltion_levels where complaint_number='$complaint_number'");
			$level='';
			if(sizeof($escLevelSql)>0){
				$level = $escLevelSql[0]->levels;
			}
			$dealerDataSql = DB::select("Select d.dealer_name,d.phone,d.sac_code,s.state as stateName,c.city as cityName from mstr_dealer as d left join mstr_state as s on s.id = d.state left join mstr_city as c on c.id = d.city  where d.id = $assign_to");
			$assignDealerCode = $dealerDataSql[0]->sac_code;
			$assignDealerName = $dealerDataSql[0]->dealer_name;
			$query = DB::select("select c.id as caseId, c.complaint_number, c.vehicleId, c.ownerId, c.customer_contact_id, c.callerId, c.from_where, c.to_where, c.highway, c.ticket_type, c.vehicle_problem, c.assign_to, c.dealer_mob_number, c.dealer_alt_mob_number, c.remark_type, c.disposition, c.agent_remark, c.standard_remark, c.assign_remarks, c.estimated_response_time, c.actual_response_time, c.tat_scheduled, c.acceptance, c.latitude, c.longitude,c.feedback_rating,c.feedback_desc,c.location,c.landmark,c.state as stateId,c.city as cityId,c.district,c.created_at, c.vehicle_type, v.vehicle, v.vehicle_model, v.reg_number, v.chassis_number, v.engine_number, v.vehicle_segment, v.purchase_date, v.add_blue_use, v.engine_emmission_type, o.owner_name, o.owner_mob, o.owner_landline, o.owner_cat, o.owner_company,o.alse_mail,o.asm_mail, oc.contact_name, oc.mob,oc.owner_contact_email,cal.caller_type, cal.caller_name, cal.caller_contact, cal.vehicle_movable, s.state, city.city,group_concat(rem.assign_remarks order by rem.id desc separator '@@') as assign_remark_log,group_concat(rem.created_at order by rem.id desc separator '@@') as assign_remark_date_log from cases as c left join mstr_vehicle as v on v.id = c.vehicleId left join mstr_owner as o on o.id = c.ownerId  and o.id = c.ownerId left join mstr_owner_contact as oc on oc.id = c.customer_contact_id and oc.vehicle_id = c.vehicleId and oc.owner_id = c.ownerId left join mstr_caller as cal on cal.id = c.callerId  and cal.owner_id = c.ownerId  left join mstr_caller_state as s on s.id = c.state left join mstr_caller_city as city on city.id = c.city left join remarks as rem on rem.complaint_number = c.complaint_number  where c.complaint_number = '$complaint_number'");
					$regNo =$query[0]->reg_number;
					$location =$query[0]->location;
					$db_estimated_response_time = $query[0]->estimated_response_time;
					$db_tat_scheduled = $query[0]->tat_scheduled;
					$db_contact_name = $query[0]->contact_name;
					$db_contact_mob = $query[0]->mob;
					$db_owner_name = $query[0]->owner_name;
					$db_owner_company = $query[0]->owner_company;
					$db_owner_mob = $query[0]->owner_mob;
					$db_assign_to = $query[0]->assign_to;
					$db_dealer_mob_number = $query[0]->dealer_mob_number;
					$db_dealer_alt_mob_number = $query[0]->dealer_alt_mob_number;
					$db_disposition = $query[0]->disposition;
					$db_agent_remark = $query[0]->agent_remark;
					$db_highway = $query[0]->highway;
					$db_city = $query[0]->city;
					$db_vehicle_model = $query[0]->vehicle_model;
					$db_chassis_number = $query[0]->chassis_number;
					$db_vehicle_type = $query[0]->vehicle_type;
					$db_created_at = $query[0]->created_at;
					$db_caller_name = $query[0]->caller_name;
					$db_caller_type = $query[0]->caller_type;
					$db_caller_contact = $query[0]->caller_contact;
					
					$db_acceptance = $query[0]->acceptance;
					$db_feedback_desc = $query[0]->feedback_desc;
					$db_feedback_rating = $query[0]->feedback_rating;
					$db_engine_emmission_type = $query[0]->engine_emmission_type;
					$ticetId = $query[0]->caseId;
					$alse_mail = $query[0]->alse_mail;
					$asm_mail = $query[0]->asm_mail;
					$assign_remark_log = explode("@@",$query[0]->assign_remark_log);
			 		$assign_remark_date_log = explode("@@",$query[0]->assign_remark_date_log);
					 
			if($level <= $maxLevel && $level!=''){
				
				if($level == $maxLevel){
					$level = $level;
				}else if($level < $maxLevel){
					$level = $level+1;
				}
				$matrix = DB::select("Select id, level_name, to_role, cc_role, hours, created_at, updated_at from mstr_escalations where level =$level");
				$hours = $matrix[0]->hours;
				$level_name = $matrix[0]->level_name;
				$addTime = date('Y-m-d H:i:s',strtotime('+'.$hours.' hour',strtotime($created_at)));
				$currentDateTime = date('Y-m-d H:i:s');
				
				if($currentDateTime > $addTime){
					$to_role = $matrix[0]->to_role;
					$cc_role = $matrix[0]->cc_role;
					if($engine_emmission_type == 'BS6'){
						$cc_role =$cc_role.',91';
					}
					
					$toUsersSql =  DB::select("Select email,name from users where role in ($to_role) and FIND_IN_SET($assign_to, dealer_id) and flag=1");
					$ccUserSql =   DB::select("Select email,name from users where role in ($cc_role) and FIND_IN_SET($assign_to, dealer_id) and flag=1");
					
					$toUserArr=$ccUserArr ='';
					if(sizeof($toUsersSql)>0){
						foreach($toUsersSql as $row){
							if($row->email !=''){
								$toUser = trim($row->email);
								$toUser = str_replace(":",",",$toUser);
								$toUser = str_replace(";",",",$toUser);
								$toUser = str_replace(" ","",$toUser);
								$toUserArr .= $toUser.",";
							}else{
								$toUserArr .= "test@dispostable.com".",";
							}
						}
						$toUserArr = rtrim($toUserArr,',');
						$toUserArr = explode(",",$toUserArr);
					}else{
						$toUserArr = array("test@dispostable.com");
					}
					if(sizeof($ccUserSql)>0){
						foreach($ccUserSql as $row){
							
							if($row->email !=''){
								$ccUser = trim($row->email);
								$ccUser = str_replace(":",",",$ccUser);
								$ccUser = str_replace(";",",",$ccUser);
								$ccUser = str_replace(" ","",$ccUser);
								$ccUserArr .= $ccUser.",";
								
							}else{
								$ccUserArr .= "test@dispostable.com".",";
							}
						}
						$ccUserArr = rtrim($ccUserArr,',');
						$ccUserArr = explode(",",$ccUserArr);
					}else{
						$ccUserArr = array("test@dispostable.com");
					}
					$levName='';
					
					if($level == $maxLevel){
						$level = $level;
						$currentDate = date('Y-m-d H:i:s');
						
						$date1 = $db_created_at;
 						$date2 = $currentDate;
 						$timestamp1 = strtotime($date1);
 						$timestamp2 = strtotime($date2);
 						$maxLevelEscalation = ROUND(abs($timestamp2 - $timestamp1)/(60*60)); 
 						//$defaulthrs = 120;
						 $defaulthrs = floor($maxLevelEscalation/24);
						 $levName = $defaulthrs * 24;					
 						/* while(1){
							$defaulthrs1=$defaulthrs+24;
 							if($maxLevelEscalation >$defaulthrs && $maxLevelEscalation < $defaulthrs1){
 								$levName = $defaulthrs;
 								break;
 							}
 							$defaulthrs = $defaulthrs1;
 						} */
						 
					}else if($level < $maxLevel){
						$levNameArr = explode(" ",$level_name);
						$levName = $levNameArr[0];
						
					}
					echo $levName.'<br>';
					$supportContPersonSql = DB::select("Select mobile,name from users where role =76 and FIND_IN_SET($assign_to, dealer_id) and flag=1");
					$supportContPerson = sizeof($supportContPersonSql)>0?$supportContPersonSql[0]->name:'NA';
					$supportContPersonMob = sizeof($supportContPersonSql)>0?$supportContPersonSql[0]->mobile:'NA';

					$subjct1 ="SELECT Ticket Details-Above $levName Hrs $complaint_number";
					$subjct2 ="BSVI SELECT Ticket Details-Above $levName Hrs $complaint_number";
					$subject=$engine_emmission_type=='BS6'?$subjct2:$subjct1;

					$restorationDate = $db_tat_scheduled!=''?date("d-m-Y",strtotime($db_tat_scheduled)):"NA";
					$restorationTime = $db_tat_scheduled!=''?date("H:i:s",strtotime($db_tat_scheduled)):"NA";
					/* ******************************************************************************************** */
						
						$ownerALSEEmail = $alse_mail; 
						$ownerALSEEmail = str_replace(":",",",$ownerALSEEmail); 
						$ownerALSEEmail = str_replace(";",",",$ownerALSEEmail); 
						$ownerALSEEmail = str_replace(" ","",$ownerALSEEmail); 
						$alseOwnerEmail = $ownerALSEEmail; 
						$ownerASMEmail = $asm_mail; 
						$ownerASMEmail = str_replace(":",",",$ownerASMEmail);
						$ownerASMEmail = str_replace(";",",",$ownerASMEmail); 
						$ownerASMEmail = str_replace(" ","",$ownerASMEmail); 
						$asmOwnerEmail = $ownerASMEmail; 
					
					$asmOwnerEmailArr = explode(",",$asmOwnerEmail); 
					$ccOwnerEmail = explode(",",$alseOwnerEmail); 
					
					$ccOwnerEmail = array_merge($ccOwnerEmail,$asmOwnerEmailArr);
					$ccUserArr = array_merge($ccUserArr,$ccOwnerEmail);
					
					/* ******************************************************************************************** */
					/* *******BS6******* */
			if($engine_emmission_type=='BS6'){
			$dealerBSSql = DB::select("Select bsvi,area_champion,region_champion from mstr_dealer where id = $assign_to");
			$bsvi = $dealerBSSql[0]->bsvi!=''?$dealerBSSql[0]->bsvi:'test@dispostable.com';
			$bsvi = str_replace(":",",",$bsvi);
			$bsvi = str_replace(";",",",$bsvi);
			$bsvi = str_replace(" ","",$bsvi);
			$bsviEmail = $bsvi;
			$area_champion = $dealerBSSql[0]->area_champion!=''?$dealerBSSql[0]->area_champion:'test@dispostable.com';
			$area_champion = str_replace(":",",",$area_champion);
			$area_champion = str_replace(";",",",$area_champion);
			$area_champion = str_replace(" ","",$area_champion);
			$area_championEmail = $area_champion;
			$region_champion = $dealerBSSql[0]->region_champion!=''?$dealerBSSql[0]->region_champion:'test@dispostable.com';
			$region_champion = str_replace(":",",",$region_champion);
			$region_champion = str_replace(";",",",$region_champion);
			$region_champion = str_replace(" ","",$region_champion);
			$region_championEmail = $region_champion;

			$bsviEmailArr = explode(",",$bsviEmail);
			$area_championEmailArr = explode(",",$area_championEmail);
			$region_championEmailArr = explode(",",$region_championEmail);
			$ccUserArr = array_merge($ccUserArr, $bsviEmailArr, $area_championEmailArr,$region_championEmailArr);
			}
			/* *******BS6******* */
			

					$body = '<p>Dear Team, </p>
					<p>Please find the below mentioned Break Down Details</p>
					<p>Kindly update the Response, Restoration and closure details by using Dealer Portal using the link. </p>
					<table border="1" style="font-family: sans-serif;">
				<tr>
					<td style=" text-align: left;">Customer Name</td>
					<td style="text-align: left;">'.$db_owner_company.'</td>
					
				</tr>
				<tr>
					<td style=" text-align: left;">Ticket Number</td>
					<td style="text-align: left;"><a href="'.url('update-case',['id' =>$ticetId]).'" >'.$complaint_number.'</a></td>		
					
				</tr>
				<tr>
					<td style=" text-align: left;">Breakdown Location</td>
					<td style="text-align: left;">'.$location.'</td>		
					
				</tr>
				<tr>
					<td style=" text-align: left;">NH</td>
					<td style="text-align: left;">'.$db_highway.'</td>		
					
				</tr>
				<tr>
					<td style=" text-align: left;">AO</td>
					<td style="text-align: left;">'.$db_city.'</td>		
					
				</tr>
				<tr>
					<td style=" text-align: left;">Registration Number</td>
					<td style="text-align: left;">'.$regNo.'</td>
					
				</tr>
				<tr>
					<td style=" text-align: left;">Vehicle Model</td>
					<td style="text-align: left;">'.$db_vehicle_model.'</td>
					
				</tr>
				<tr>
					<td style=" text-align: left;">Chassis Number</td>
					<td style="text-align: left;">'.$db_chassis_number.'</td>
					
				</tr>
				<tr>
					<td style=" text-align: left;">Vehicle Type</td>
					<td style="text-align: left;">'.$db_vehicle_type.'</td>
					
				</tr>
				<tr>
					<td style=" text-align: left;">Call Log Date</td>
					<td style="text-align: left;">'.date("d-m-Y",strtotime($db_created_at)).'</td>
					
				</tr>
				<tr>
					<td style=" text-align: left;">Call Log Time</td>
					<td style="text-align: left;">'.date("H:i:s",strtotime($db_created_at)).'</td>					
				</tr>
				<tr>
					<td style=" text-align: left;">Caller Name</td>
					<td style="text-align: left;">'.$db_caller_name.'</td>					
				</tr>
				<tr>
					<td style=" text-align: left;">Caller Type</td>
					<td style="text-align: left;">'.$db_caller_type.'</td>					
				</tr>
				<tr>
					<td style=" text-align: left;">Caller Contact</td>
					<td style="text-align: left;">'.$db_caller_contact.'</td>					
				</tr>
				<tr>
					<td style=" text-align: left;">Support Cont Person</td>
					<td style="text-align: left;">'.$supportContPerson.'</td>					
				</tr>
				<tr>
					<td style=" text-align: left;">Support Cont Number</td>
					<td style="text-align: left;">'.$supportContPersonMob.'</td>					
				</tr>
				<tr>
					<td style=" text-align: left;">Support Centre Code</td>
					<td style="text-align: left;">'.$assignDealerCode.'</td>
				</tr>
				<tr>
					<td style=" text-align: left;">Support Centre Name</td>
					<td style="text-align: left;">'.$assignDealerName.'</td>
				</tr>
				<tr>  
					<td style=" text-align: left;">Response Date</td>
					<td style="text-align: left;">'.date("d-m-Y",strtotime($db_estimated_response_time)).'</td>					
				</tr>
				<tr>  
					<td style=" text-align: left;">Response Time</td>
					<td style="text-align: left;">'.date("H:i:s",strtotime($db_estimated_response_time)).'</td>					
				</tr>
				<tr>  
					<td style=" text-align: left;">Restoration Date</td>
					<td style="text-align: left;">'.$restorationDate.'</td>					
				</tr>
				<tr>  
					<td style=" text-align: left;">Restoration Time</td>
					<td style="text-align: left;">'.$restorationTime.'</td>					
				</tr>
				<tr>';
				for($i=0;$i<sizeof($assign_remark_log);$i++){
					$date = date('d-m-Y H:i:s',strtotime($assign_remark_date_log[$i]));
					if($i == 0){
						$body .= '<tr><td style="text-align: left;">Latest Comment '.$date.'</td>';
					}else{
						$body .= '<tr><td style="text-align: left;">Previous Comment '.$date.'</td>';
					}
					$body .= '<td style="text-align: left;">'.$assign_remark_log[$i].'</td></tr>';
				}					
				$body .='
				<tr>
					
				</tr>
			</table>
			<p>Regards,</p>
			<p>SELECT Support Cell</p>
					<br/><p style="text-decoration: underline;">Note:</p>
					<p>This is a system generated email, please do not reply</p>';
					
					$data=['body'=>$body];
					/* try {
						Mail::send('assigned_email',["data"=>$data],function ($message) use ($toUserArr, $ccUserArr, $subject) {
							$message->to(array_filter($toUserArr))->cc(array_filter($ccUserArr))->bcc(['ashutosh.rawat@cogenteservices.in','madhur.bhati@cogenteservices.com','Panchakarla.SaiPra@ashokleyland.com'])->subject($subject);
							$message->from('select.support@ashokleyland.com');
						});
					} catch (\Exception $ex){
						DB::table('escalation_error')->insert(['complaint_number'=>$complaint_number,'error'=>$ex]);
					}
					
					
					DB::table('escaltion_levels')->where('complaint_number', $complaint_number)->update(['levels' => $level,'updated_at'=>"$currentDateTime"]);
					DB::table('escalation_log')->insert(['level'=>$level,'complaint_number'=>$complaint_number,'to_user'=>implode(",",$toUserArr),'cc_user'=>implode(",",$ccUserArr),'subject'=>$subject,'body'=>$body]); */
				}
				

			}
			
			

			


		}
		
	}
}	 