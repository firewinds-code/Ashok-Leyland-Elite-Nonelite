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

// ini_set("max_exceution_time", 360);
set_time_limit(36000);

date_default_timezone_set('Asia/Kolkata');
class CronJobController  extends Controller{
	public function __construct()
    {
        DB::enableQueryLog();
        DB::select("SET sql_mode=''");
        DB::select("set sql_safe_updates = 0");
		
    }

	public function autoEscalate(){ 
		// dd("scsscs");
		$currentDateTime = date('Y-m-d H:i:s');
		$emailTrigger = '';
		$currentTime = date('H');
		$currentTimeAMPM = date('h');
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
		$addQuery = '';
		/* $tHoldQuery = DB::select("select distinct complaint_number from ticket_hold where created_at !='' and updated_at !='' and flag=0"); */
		$tHoldQuery = DB::select("select distinct th.complaint_number,c.remark_type from ticket_hold as th left join cases c on c.complaint_number=th.complaint_number where th.created_at is not null and th.updated_at is not null and c.remark_type  not in ('Work Completed','Customer Confirmation Due', 'Customer Confirmation Completed', 'Customer Feedback','Ticket Closed','Closed' ,'Completed','Awaiting customer approval','Awaiting customer Payment','Awaiting parts from customer','Vehicle being Towed','Gate Pass Pending From Customer End') and c.complaint_number <> '' and th.flag=0");
		
		if(sizeof($tHoldQuery)>0){
			foreach($tHoldQuery as $row){				
				$complaint_number = $row->complaint_number;
				$caseHoldQuery = DB::select("Select created_at from cases where complaint_number='$complaint_number'");				
				$caseCreatedHoldTime = $caseHoldQuery[0]->created_at;				
				$getHoldTime = $this->getHoldFunction($complaint_number);				
				$getHoldTimeArr = explode("~~",$getHoldTime);				
				$subHour  = $getHoldTimeArr[0];
				$subMinute = $getHoldTimeArr[1];
				$subDays = $getHoldTimeArr[2];
				$statusVal = $getHoldTimeArr[3];
				/* $caseCreatedHoldTime = date('Y-m-d H:i:s',strtotime('+'.$subDays.' days',strtotime($caseCreatedHoldTime)));
				$caseCreatedHoldTime = date('Y-m-d H:i:s',strtotime('+'.$subHour.' hour',strtotime($caseCreatedHoldTime)));
				$caseCreatedHoldTime = date('Y-m-d H:i:s',strtotime('+'.$subMinute.' minute',strtotime($caseCreatedHoldTime)));
				$addQuery .= " or ((hour('$caseCreatedHoldTime') >=18 and hour('$caseCreatedHoldTime') < 19) or ((case when hour('$caseCreatedHoldTime')>12 then  hour('$caseCreatedHoldTime')-12 else hour('$caseCreatedHoldTime') end) >=6 and (case when hour('$caseCreatedHoldTime')>12 then  hour('$caseCreatedHoldTime')-12 else hour('$caseCreatedHoldTime') end) < 7))"."~~~~"; */
				$addQuery .= " or (complaint_number ='$complaint_number')"."~~~~";
			}
			$addQuery = str_replace("~~~~"," ",$addQuery);
		}
		/* $rowQuery =DB::select("select id, complaint_number, vehicleId, ownerId, customer_contact_id, callerId, from_where, to_where, highway, ticket_type, 
		vehicle_problem, assign_to, dealer_mob_number, dealer_alt_mob_number, remark_type, disposition, agent_remark,
	   standard_remark, assign_remarks, estimated_response_time, tat_scheduled, acceptance, latitude, longitude,created_at from cases where ((hour(created_at) >=7 and hour(created_at) < 8) ) and  remark_type  not in ('Closed') and complaint_number <> '' order by id desc	"); */
		
		$rowQuery =DB::select("select id, complaint_number, vehicleId, ownerId, customer_contact_id, callerId, from_where, to_where, highway, ticket_type, city,
		vehicle_problem, assign_to, dealer_mob_number, dealer_alt_mob_number, remark_type, disposition, agent_remark,
		standard_remark, assign_remarks, estimated_response_time, tat_scheduled, acceptance, latitude, longitude,created_at from cases where (((hour(created_at) >=$currentTimeLess and hour(created_at) < $currentTime) or ((case when hour(created_at)>12 then  hour(created_at)-12 else hour(created_at) end) >=$currentTimeAMPMLess and (case when hour(created_at)>12 then  hour(created_at)-12 else hour(created_at) end) < $currentTimeAMPM)) $addQuery) and  remark_type  not in ('Work Completed','Customer Confirmation Due', 'Customer Confirmation Completed', 'Customer Feedback','Ticket Closed','Closed' ,'Completed','Awaiting customer approval','Awaiting customer Payment','Awaiting parts from customer','Vehicle being Towed','Gate Pass Pending From Customer End') and complaint_number <> '' order by id desc");
		//dd($rowQuery);
		// 'Work Completed','Customer Confirmation Due', 'Customer Confirmation Completed', 'Customer Feedback','Ticket Closed','Closed' ,'Completed'
		$maxLevelSql = DB::select("Select id,level, level_name, to_role, cc_role, hours, created_at, updated_at from mstr_escalations order by level desc");
		$maxLevel = $maxLevelSql[0]->level;
		$levelQuery = DB::select("Select id,level, level_name, to_role, cc_role, hours, created_at, updated_at from mstr_escalations");
		foreach($rowQuery as $row){
			$complaint_number = $row->complaint_number;
			$created_at = $row->created_at;
			/* Ticket Hold Code */
			$tHoldQuery = DB::select("select distinct complaint_number from ticket_hold where created_at is not null and updated_at is not null and flag=0 and complaint_number = '$complaint_number'");
			if(sizeof($tHoldQuery)>0){					
				$getHoldTime = $this->getHoldFunction($complaint_number);
				$getHoldTimeArr = explode("~~",$getHoldTime);
				$subHour  = $getHoldTimeArr[0];
				$subMinute = $getHoldTimeArr[1];
				$subDays = $getHoldTimeArr[2];
				$statusVal = $getHoldTimeArr[3];
				$created_at = date('Y-m-d H:i:s',strtotime('+'.$subDays.' days',strtotime($created_at)));
				$created_at = date('Y-m-d H:i:s',strtotime('+'.$subHour.' hour',strtotime($created_at)));
				$created_at = date('Y-m-d H:i:s',strtotime('+'.$subMinute.' minute',strtotime($created_at)));
			}
			/* Ticket Hold Code */
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
			$cityIdVal = $row->city;
			$escLevelSql = DB::select("Select levels, complaint_number from escaltion_levels where complaint_number='$complaint_number'");
			$level='';
			if(sizeof($escLevelSql)>0){
				$level = $escLevelSql[0]->levels;
			}
			
			$dealerDataSql = DB::select("Select d.dealer_name,d.phone,d.sac_code,s.state as stateName,c.city as cityName,c.city as dealerAOCity,d.latitude,d.longitude from mstr_dealer as d left join mstr_state as s on s.id = d.state left join mstr_city as c on c.id = d.city  where d.id = $assign_to");
			$assignDealerCode = $dealerDataSql[0]->sac_code;
			$assignDealerName = $dealerDataSql[0]->dealer_name;
			$AOCityName = $dealerDataSql[0]->cityName;
			$dealerAOCity = $dealerDataSql[0]->dealerAOCity;
			$dealerLatitude = $dealerDataSql[0]->latitude;
			$dealerLongitude = $dealerDataSql[0]->longitude;
			$query = DB::select("select c.id as caseId, c.complaint_number, c.vehicleId, c.ownerId, c.customer_contact_id, c.callerId, c.from_where, c.to_where, c.highway, c.ticket_type, c.vehicle_problem, c.assign_to, c.dealer_mob_number, c.dealer_alt_mob_number, c.remark_type, c.disposition, c.agent_remark, c.standard_remark, c.assign_remarks, c.estimated_response_time, c.actual_response_time, c.tat_scheduled, c.acceptance, c.latitude, c.longitude,c.feedback_rating,c.feedback_desc,c.location,c.landmark,c.state as stateId,c.city as cityId,c.district,c.created_at, c.vehicle_type,c.assign_work_manager_mobile, v.vehicle, v.vehicle_model, v.reg_number, v.chassis_number, v.engine_number, v.vehicle_segment, v.purchase_date, v.add_blue_use, v.engine_emmission_type, o.owner_name, o.owner_mob, o.owner_landline, o.owner_cat, o.owner_company,o.alse_mail,o.asm_mail, oc.contact_name, oc.mob,oc.owner_contact_email,cal.caller_type, cal.caller_name, cal.caller_contact, cal.vehicle_movable, s.state, city.city,group_concat(rem.assign_remarks order by rem.id desc separator '@@') as assign_remark_log,group_concat(rem.created_at order by rem.id desc separator '@@') as assign_remark_date_log from cases as c left join mstr_vehicle as v on v.id = c.vehicleId left join mstr_owner as o on o.id = c.ownerId  and o.id = c.ownerId left join mstr_owner_contact as oc on oc.id = c.customer_contact_id and oc.vehicle_id = c.vehicleId and oc.owner_id = c.ownerId left join mstr_caller as cal on cal.id = c.callerId  and cal.owner_id = c.ownerId  left join mstr_caller_state as s on s.id = c.state left join mstr_caller_city as city on city.id = c.city left join remarks as rem on rem.complaint_number = c.complaint_number  where c.complaint_number = '$complaint_number'");
					$latitude =$query[0]->latitude;
					$longitude =$query[0]->longitude;
					$regNo =$query[0]->reg_number;
					$location =$query[0]->location;
					$db_estimated_response_time = $query[0]->estimated_response_time;
					$db_actual_response_time = $query[0]->actual_response_time;
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
					$db_remark_type = $query[0]->remark_type;
					$ticetId = $query[0]->caseId;
					$standard_remark = $query[0]->standard_remark;
					/* $alse_mail = $query[0]->alse_mail;
					$asm_mail = $query[0]->asm_mail; */
					$alse_mail = 'KRYSALIS_Vandhana1@ashokleyland.com';
					$asm_mail = 'KRYSALIS_Vandhana1@ashokleyland.com';
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
				/* ---------------------Hold Time reduce-------------------------------------------- */
				$checkHoldTicket = DB::select("Select id, complaint_number, remark_type from ticket_hold where complaint_number ='$complaint_number'");
				$statusVal = '';
				if(sizeof($checkHoldTicket)>0){
					
					$getHoldTime = $this->getHoldFunction($complaint_number);
					$getHoldTimeArr = explode("~~",$getHoldTime);
					$subHour  = $getHoldTimeArr[0];
					$subMinute = $getHoldTimeArr[1];
					$subDays = $getHoldTimeArr[2];
					$statusVal = $getHoldTimeArr[3];
					$addTime = date('Y-m-d H:i:s',strtotime('+'.$subDays.' days',strtotime($addTime)));
					$addTime = date('Y-m-d H:i:s',strtotime('+'.$subHour.' hour',strtotime($addTime)));
					$addTime = date('Y-m-d H:i:s',strtotime('+'.$subMinute.' minute',strtotime($addTime)));
					
				}
				//dd($addTime);
				/* ---------------------Hold Time reduce-------------------------------------------- */
				
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
								$toUserArr .= "KRYSALIS_Vandhana1@ashokleyland.com".",";
							}
						}
						$toUserArr = rtrim($toUserArr,',');
						$toUserArr = explode(",",$toUserArr);
					}else{
						$toUserArr = array("KRYSALIS_Vandhana1@ashokleyland.com");
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
								$ccUserArr .= "KRYSALIS_Vandhana1@ashokleyland.com".",";
							}
						}
						$ccUserArr = rtrim($ccUserArr,',');
						$ccUserArr = explode(",",$ccUserArr);
					}else{
						$ccUserArr = array("KRYSALIS_Vandhana1@ashokleyland.com");
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
						/* ---------------------Hold Time reduce-------------------------------------------- */
						$getHoldTime = $this->getHoldFunction($complaint_number);
						$getHoldTimeArr = explode("~~",$getHoldTime);
						$subHour  = $getHoldTimeArr[0];
						$subMinute = $getHoldTimeArr[1];
						
						$subDaysToHr = $getHoldTimeArr[2] * 24;
						$levName = $levName - ($subHour + $subDaysToHr);
						/* ---------------------Hold Time reduce-------------------------------------------- */
 						
						 
					}else if($level < $maxLevel){
						$levNameArr = explode(" ",$level_name);
						$levName = $levNameArr[0];
						
					}
					$supportContPersonSql = DB::select("Select mobile,name from users where role in (76,113) and FIND_IN_SET($assign_to, dealer_id) and flag=1");
					$supportContPerson = sizeof($supportContPersonSql)>0?$supportContPersonSql[0]->name:'NA';
					$supportContPersonMob = sizeof($supportContPersonSql)>0?$supportContPersonSql[0]->mobile:'NA';	

					/* **********************************Addon**************************************************** */
					$escLvlQuery = DB::select("SELECT levels FROM escaltion_levels where complaint_number ='$complaint_number'");
					$actualLevlVal = $escLvlQuery[0]->levels;
					
					if($actualLevlVal  == 1){
						$emailTrigger = 'ok';
					}else if($actualLevlVal > 1){
						$escLogQuery = DB::select("SELECT subject FROM escalation_log where complaint_number = '$complaint_number' and level >=2 order by id desc limit 1");
						if(sizeof($escLogQuery)>0){
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

					$subjct1 ="Breakdown Ticket Details-Above $levName Hrs $complaint_number - $db_remark_type";
					$subjct2 ="BSVI Breakdown Ticket Details-Above $levName Hrs $complaint_number - $db_remark_type";
					$subject=$engine_emmission_type=='BS6'?$subjct2:$subjct1;

					$restorationDate = $db_tat_scheduled!=''?date("d-m-Y",strtotime($db_tat_scheduled)):"";
					$restorationTime = $db_tat_scheduled!=''?date("H:i:s",strtotime($db_tat_scheduled)):"";
					/* ******************************************************************************************** */
						
						$ownerALSEEmail = $alse_mail; 
						/* $ownerALSEEmail = str_replace(":",",",$ownerALSEEmail); 
						$ownerALSEEmail = str_replace(";",",",$ownerALSEEmail); 
						$ownerALSEEmail = str_replace(" ","",$ownerALSEEmail);  */
						$alseOwnerEmail = $ownerALSEEmail; 
						$ownerASMEmail = $asm_mail; 
						/* $ownerASMEmail = str_replace(":",",",$ownerASMEmail);
						$ownerASMEmail = str_replace(";",",",$ownerASMEmail); 
						$ownerASMEmail = str_replace(" ","",$ownerASMEmail);  */
						$asmOwnerEmail = $ownerASMEmail; 
					
					/* $asmOwnerEmailArr = explode(",",$asmOwnerEmail); 
					$ccOwnerEmail = explode(",",$alseOwnerEmail); 
					
					$ccOwnerEmail = array_merge($ccOwnerEmail,$asmOwnerEmailArr);
					$ccUserArr = array_merge($ccUserArr,$ccOwnerEmail); */
					
					/* ******************************************************************************************** */
					/* *******BS6******* */
			if($engine_emmission_type=='BS6'){
				$dealerBSSql = DB::select("Select bsvi,area_champion,region_champion from mstr_dealer where id = $assign_to");
				$bsvi = $dealerBSSql[0]->bsvi!=''?$dealerBSSql[0]->bsvi:'KRYSALIS_Vandhana1@ashokleyland.com';
				$bsvi = str_replace(":",",",$bsvi);
				$bsvi = str_replace(";",",",$bsvi);
				$bsvi = str_replace(" ","",$bsvi);
				$bsviEmail = $bsvi;
				$area_champion = 'KRYSALIS_Vandhana1@ashokleyland.com';
				$area_champion = str_replace(":",",",$area_champion);
				$area_champion = str_replace(";",",",$area_champion);
				$area_champion = str_replace(" ","",$area_champion);
				$area_championEmail = $area_champion;
				$region_champion = 'KRYSALIS_Vandhana1@ashokleyland.com';
				$region_champion = str_replace(":",",",$region_champion);
				$region_champion = str_replace(";",",",$region_champion);
				$region_champion = str_replace(" ","",$region_champion);
				$region_championEmail = $region_champion;

				$bsviEmailArr = explode(",",$bsviEmail);
				$area_championEmailArr = explode(",",$area_championEmail);
				$region_championEmailArr = explode(",",$region_championEmail);
				if($levName >=72){
					$ccUserArr = array_merge($ccUserArr, $bsviEmailArr, $area_championEmailArr,$region_championEmailArr);
				}else{
					$ccUserArr = array_merge($ccUserArr, $area_championEmailArr,$region_championEmailArr);
				}
				
			}
			/* *******BS6******* */
			/* *******dealer lat Long******* */
			
			/* *******dealer lat Long******* */
			$dbActualResponseDate =$db_actual_response_time!=""?date("d-m-Y",strtotime($db_actual_response_time)):"";
			$dbActualResponseTime =$db_actual_response_time!=""?date("H:i:s",strtotime($db_actual_response_time)):"";

					$body = '<p>Dear Team, </p>
					<p>Please find the below mentioned Breakdown Details</p>
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
					<td style=" text-align: left;">Latitude</td>
					<td style="text-align: left;">'.$latitude.'</td>
				</tr>
				<tr>
					<td style=" text-align: left;">Longitude</td>
					<td style="text-align: left;">'.$longitude.'</td>
				</tr>
				<tr>  
					<td style=" text-align: left;">Complaint Reported</td>
					<td style="text-align: left;">'.$standard_remark.'</td>					
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
					<td style=" text-align: left;">Dealer Latitude</td>
					<td style="text-align: left;">'.$dealerLatitude.'</td>
				</tr>
				<tr>
					<td style=" text-align: left;">Dealer Longitude</td>
					<td style="text-align: left;">'.$dealerLongitude.'</td>
				</tr>
				<tr>  
					<td style=" text-align: left;">Response Date</td>
					<td style="text-align: left;">'.$dbActualResponseDate.'</td>					
				</tr>
				<tr>  
					<td style=" text-align: left;">Response Time</td>
					<td style="text-align: left;">'.$dbActualResponseTime.'</td>					
				</tr>
				<tr>  
					<td style=" text-align: left;">Restoration Date</td>
					<td style="text-align: left;">'.$restorationDate.'</td>					
				</tr>
				<tr>  
					<td style=" text-align: left;">Restoration Time</td>
					<td style="text-align: left;">'.$restorationTime.'</td>					
				</tr>
				
				';
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
			<p>Ashok Leyland Helpline</p>
					<br/><p style="text-decoration: underline;">Note:</p>
					<p>This is a system generated email, please do not reply</p>';
					
					$data=['body'=>$body];
					//dd($emailTrigger);
					try {
						if($emailTrigger == 'ok'){
							Mail::send('assigned_email',["data"=>$data],function ($message) use ($toUserArr, $ccUserArr, $subject) {
								$message->to(array_filter($toUserArr))->cc(array_filter($ccUserArr))->bcc(['al.crmautomailers@cogenteservices.in'])->subject($subject);
								$message->from('ALHelpline@ashokleyland.com');
							});
						}
					} catch (\Exception $ex){
						$excep = $ex->getMessage();
						DB::table('escalation_error')->insert(['complaint_number'=>$complaint_number,'error'=>$excep]);
					}
					
					if($emailTrigger == 'ok'){
						/* Send SMS to TSM */
							try {
								$tsmQuery =DB::select("select mobile from users where find_in_set($assign_to,dealer_id) and role = 1 and flag=1 limit 1");										
								if(sizeof($tsmQuery)>0){
									$tsmMob = $tsmQuery[0]->mobile;
									$assign_work_manager_mobile = $query[0]->assign_work_manager_mobile;
									// $mobile=$this->multiMobile($tsmMob).',918105736911';
									$mobile=$this->multiMobile($tsmMob);
									if($levName == '48'){
										$stndRemark = $query[0]->standard_remark;
										$stndRemark = substr($stndRemark, 0, 20);
										$message = urlencode("Dear AL Team,	Breakdown complaint number $complaint_number for vehicle $regNo is assigned to $assignDealerName, AO - $dealerAOCity has crossed 48hrs. WM Mob No-$assign_work_manager_mobile. Customer Name - $db_owner_company, Caller Number-  $db_caller_name, Issue : $stndRemark. Thank you Ashok Leyland");

										$this->sendSMSFuction($mobile,$message,"1607100000000289247","Email Escalation",$complaint_number);
									}
								}
							} catch (\Exception $ex){
								DB::table('escalation_error')->insert(['complaint_number'=>$complaint_number,'error'=>$ex]);
							}
							
						/* Send SMS to TSM */
						DB::table('escaltion_levels')->where('complaint_number', $complaint_number)->update(['levels' => $level,'updated_at'=>"$currentDateTime"]);
						
						DB::table('escalation_log')->insert(['level'=>$level,'complaint_number'=>$complaint_number,'to_user'=>implode(",",$toUserArr),'cc_user'=>implode(",",$ccUserArr),'subject'=>$subject,'body'=>$body]);

						$ticketHoldQuery = DB::select("Select esc_level,flag from ticket_hold where complaint_number='$complaint_number' ");
						
						if(sizeof($ticketHoldQuery)> 0 ){
							$esc_level = $ticketHoldQuery[0]->esc_level;
							//$actualLevlVal = $escLvlQuery[0]->levels == 8?$escLvlQuery[0]->levels:$escLvlQuery[0]->levels+1;
							DB::select("set sql_safe_updates = 0");
							DB::select("Update ticket_hold set esc_level='$level',flag='1'  where complaint_number = '$complaint_number' and created_at is not null and updated_at is not null");
						}						
					}
				}
			}
		}		
	}

	public function autoEscalateTest(){ 
		
	}
	
	public function getHoldFunction($complaint_number){

		$ticketHoldQuery = DB::select("Select id, complaint_number, remark_type, created_at, updated_at,esc_level,flag from ticket_hold where complaint_number='$complaint_number' ");
		if(sizeof($ticketHoldQuery)>0){
			$esc_level = $ticketHoldQuery[0]->esc_level;
			$flag = $ticketHoldQuery[0]->flag;
		
		
			$dayVal = $hrs = $min = 0;
			$statusVal = 'Ok';
			$escLvlQuery = DB::select("SELECT levels FROM escaltion_levels where complaint_number ='$complaint_number'");
			if($esc_level == ''){
				//$actualLevlVal = $escLvlQuery[0]->levels == 8?$escLvlQuery[0]->levels:$escLvlQuery[0]->levels+1;
				$actualLevlVal = sizeof($escLvlQuery)>0?$escLvlQuery[0]->levels:1;
				
				DB::select("Update ticket_hold set esc_level='$actualLevlVal' where complaint_number = '$complaint_number'");
			}
		$tHoldQuery = DB::select("Select id, complaint_number, remark_type, created_at, updated_at,esc_level,flag from ticket_hold where complaint_number='$complaint_number' ");
		$escLevel = $tHoldQuery[0]->esc_level;
		foreach($ticketHoldQuery as $row){
			$created_at = $row->created_at;
			$updated_at = $row->updated_at;
			$date1 = strtotime($created_at); 
			$date2 = strtotime($updated_at); 

			// Formulate the Difference between two dates
			$diff = abs($date2 - $date1); 
			$years = floor($diff / (365*60*60*24)); 
			$months = floor(($diff - $years * 365*60*60*24)
			/ (30*60*60*24)); 
			$days = floor(($diff - $years * 365*60*60*24 - 
			$months*30*60*60*24)/ (60*60*24));
			$hours = floor(($diff - $years * 365*60*60*24 
			- $months*30*60*60*24 - $days*60*60*24)
			/ (60*60)); 

			$minutes = floor(($diff - $years * 365*60*60*24 
			- $months*30*60*60*24 - $days*60*60*24 
			- $hours*60*60)/ 60); 
			$hrs = $hrs + $hours;
			$min = $min + $minutes;
			$dayVal = $dayVal + $days;
		}
		return $hrs.'~~'.$min.'~~'.$dayVal.'~~'.$statusVal.'~~'.$escLevel.'~~'.$flag;
	}else{
		$hrs=$min=$dayVal=$statusVal=$escLevel=$flag=0;
		return $hrs.'~~'.$min.'~~'.$dayVal.'~~'.$statusVal.'~~'.$escLevel.'~~'.$flag;
	}		
	}

	/**************************Ticket Hold Mail*********************************/
 
	public function ticketHoldMail(Request $request){
 		
		$ldate = date('d-m-Y H:i:s');
		//creating the download file
		
		$fileName = "download_$ldate.csv";
		
		$columnNames  =  [
				"Complaint Number",
				"Status",
				"Employee Name",
				"Employee Id",
				"Deealer Mobile",
				"Assign Remarks",
				"Created Date",
			];
				
		$file = fopen('public/export/'.$fileName, 'w');
		//dd($file);
		fputcsv($file, $columnNames);
		$sql = DB::select("SELECT * FROM remarks where cast(created_at as date) >= cast('2021-05-01' as date) and remark_type='Awaiting parts from AL' order by created_at");
		if(sizeof($sql)>0){
			foreach($sql as $row){
				fputcsv($file, [
					$row->complaint_number,
					$row->remark_type,
					$row->employee_name,
					$row->employee_id,
					$row->dealer_mob_number,
					$row->assign_remarks,
					$row->created_at,
				]);
			}
		}
		
		fclose($file);
		
		$subject = 'Awaiting Parts From Al (01-05-2021 - '.$ldate.')';
		try{
			Mail::send('hold_template', [], function($message) use ($fileName,$subject){
				$message->to('ashutosh.rawat@cogenteservices.in')->subject($subject);
				$message->attach('public/export/' . $fileName);
				$message->from('ALHelpline@ashokleyland.com');
			});
		}catch(JWTException $exception){
			$this->serverstatuscode = "0";
			$this->serverstatusdes = $exception->getMessage();
		}
		
		return response()->json(compact('this'));
 }
/**************************Ticket Hold Mail*********************************/

	public function autoMSU(){
		try {
			
			/* $sql = DB::select("SELECT c.complaint_number,m.remarks FROM creation_api_remarks as c left join msu_api as m on c.complaint_number=m.complaint_number where c.complaint_number= 'STDB232457539';"); */
			/* $sql = DB::select("SELECT c.complaint_number,m.remarks FROM creation_api_remarks as c left join msu_api as m on c.complaint_number=m.complaint_number where (c.remarks='Failed')"); */
			$sql = DB::select("SELECT c.complaint_number,m.remarks FROM creation_api_remarks as c left join msu_api as m on c.complaint_number=m.complaint_number where (c.remarks='Failed') and cast(c.created_at as date)=cast(now() as date)");
			// $sql = DB::select("SELECT c.complaint_number,m.remarks FROM creation_api_remarks as c left join msu_api as m on c.complaint_number=m.complaint_number where (c.remarks='Failed') and c.complaint_number='STDB232496877'");
			// $sql = DB::select("SELECT c.complaint_number,m.remarks FROM creation_api_remarks as c left join msu_api as m on c.complaint_number=m.complaint_number where (c.remarks='Failed') and cast(c.created_at as date)='2023-09-25'");
		$updated_at = date('Y-m-d H:i:s'); 
		
		if(sizeof($sql)>0){
			foreach($sql as $row){
				$complaint_number = $row->complaint_number;
				$remarks = $row->remarks;
				// dd($remarks);
				// $jsonRemarks = json_decode($remarks);
				$jsonRemarks = str_replace(array("\r","\n","\t"),'',$remarks);
				// $jsonRemarks = json_encode(json_decode($jsonRemarks));
				// $dealer_remarks = $jsonRemarks[0]['delaer_remark'];
				// dd($jsonRemarks);
				
				$curl = curl_init();
					curl_setopt_array($curl, array(
					CURLOPT_URL => 'http://10.60.64.225/msu/cogCreateTicket',
					//CURLOPT_URL => 'https://hz8tb0w051.execute-api.us-east-1.amazonaws.com/v1/cog-create-ticket',
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => '',
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 0,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => 'POST',
					CURLOPT_POSTFIELDS => $jsonRemarks,
					CURLOPT_HTTPHEADER => array(
					'Authorization: Basic bXN1Y29nZW50OmlZbVBSaDJubXA=',
					'Content-Type: application/json'
					),
					));
					curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
					$response = curl_exec($curl);
					// echo $complaint_number;
					//  dd($response);	
					curl_close($curl);
					
					$isJson = is_array(json_decode($response,true));
					if($isJson){
						
						$responseDecode = json_decode($response);
						$apiResult = $responseDecode->result;
						$apiTicketNumber = $responseDecode->ticket_no;
						DB::select("INSERT INTO auto_msu (complaint_number, remarks,json_req) VALUES ('$apiTicketNumber', '$apiResult','$remarks')");
						DB::table("creation_api_remarks")->where("complaint_number",$complaint_number)->update(['remarks'=>$apiResult,'updated_at'=>$updated_at]);
					}else{
						DB::select("INSERT INTO auto_msu (complaint_number, remarks,json_req) VALUES ('$complaint_number', 'Failed','$remarks')");
					}
			}
		}
		} catch (\Exception $ex){
			$notification = array(
				'message' => $ex->getMessage().'Line: '.$ex->getLine(),
				'alert-type' => 'error'
			);
			dd($notification);
			return back()->with($notification);
		}	  
	}

	public function msuFailedMail(){
		$yesterdayDate = date("Y-m-d", strtotime("yesterday"));
		$sql = DB::select("SELECT c.complaint_number,c.remarks as remarks,m.remarks as jsonreq,c.created_at FROM creation_api_remarks as c join msu_api as m on c.complaint_number=m.complaint_number where (c.remarks='Failed' or c.remarks='Submit Failed') and cast(c.created_at as date)=cast(DATE_SUB(now(), INTERVAL 1 DAY) as date)");
		if(sizeof($sql)>0){
			$subject = "MSU Failed Ticket Details: $yesterdayDate";
			$body = '<p>Dear Team, </p>			
			<p>Please find the below failed tickets while ticket is created. </p>
			<table border="1" style="font-family: sans-serif;">';
			foreach ($sql as $row) {
				$complaint_number = $row->complaint_number;
				$remarks = $row->remarks;
				$created_at = $row->created_at;
				$jsonreq = $row->jsonreq;

				
				$body .='<tr>
					<td style="text-align: left;">Complaint Number</td>
					<td style="text-align: left;">'.$complaint_number.'</td>
				</tr>
				<tr>
					<td style=" text-align: left;">Response</td>
					<td style="text-align: left;">'.$remarks.'</td>
				</tr>
				<tr>
					<td style=" text-align: left;">Request</td>
					<td style="text-align: left;">'.$jsonreq.'</td>
				</tr>
				<tr>
					<td style=" text-align: left;">Date</td>
					<td style="text-align: left;">'.$created_at.'</td>
				</tr>';
			}
			$body .='</table>			
			<p>Regards,</p>
			<p>Standard Support Cell</p>';
			$data=['body'=>$body];
			$toUserArr = array("Suresh.J@ashokleyland.com","Subashbabu.B@ashokleyland.com","JuniaGnanaselvi.J@ashokleyland.com");
			$ccUserArr = array("ALcogentteam@cogenteservices.com");
			Mail::send('assigned_email',["data"=>$data],function ($message) use ($toUserArr, $ccUserArr, $subject) {
				$message->to($toUserArr)->cc($ccUserArr)->subject($subject);
				$message->from('ALHelpline@ashokleyland.com');
			});

			// ->bcc(['ashutosh.rawat@cogenteservices.in','DeepakKumar.Mandal@ashokleyland.com','al.crmautomailers@cogenteservices.in'])
		}

	}
	public function multiMobile($val){
		$val =$val!=''?$val:'910000000000';
        $val = rtrim($val,';');
        $val = rtrim($val,',');
        $val = rtrim($val,' ');
        $val = rtrim($val,':');
        $val = str_replace(":",",",$val);
        $val = str_replace(";",",",$val);
        $val = str_replace(" ",",",$val);
        $val = str_replace("-",",",$val);
		$valArr = explode(',',$val);
		$newVal = '';
		foreach($valArr as $row){
			$newVal .= '91'.$row.',';
		}
		$newVal = rtrim($newVal,',');
        return $newVal;
    }

	/* *********************** SMS Function *********************************************************/
	public function sendSMSFuction($mobile,$message,$tempId,$type,$complaint_number){
		
		try {
			// die("eeede");
			
			$acntKey = 'b305cbd7865f4ec69469efcbddb59768';
			$urlSMSCogent = "http://site.ping4sms.com/api/smsapi?key=$acntKey&route=2&sender=ASHLEY&number=$mobile&sms=$message&templateid=$tempId";
			$curlCogentSMS = curl_init();
			curl_setopt_array($curlCogentSMS, array(
			CURLOPT_URL => $urlSMSCogent,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET"
			));
			curl_setopt($curlCogentSMS, CURLOPT_SSL_VERIFYPEER, false);
			$responseCogentAPI = curl_exec($curlCogentSMS);
			
			$err = curl_error($curlCogentSMS);
			curl_close($curlCogentSMS);
			DB::table('sms_response')->insert(['url'=>"$urlSMSCogent",'error'=>"$err",'response'=>"$responseCogentAPI"]);
		} catch (\Exception $ii) {
			DB::table('creation_exception')->insert(['complaint_number'=>"$complaint_number",'type'=>"$type",'exception'=>"$ii->getMessage()"]);
		}
		
	}
	/* *********************** SMS Function *********************************************************/
}