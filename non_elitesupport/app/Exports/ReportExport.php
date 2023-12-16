<?php
  
namespace App\Exports;
  
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Auth;
use DB;
use DateTime;
class ReportExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $request;
    public function __construct($request)
    {
        $this->request = $request;
    }
    public function collection()
    {
        $request = $this->request; 
        $datefrom =$request['datefrom'];
        $dateto =$request['dateto'];
        $dealerImplode =$request['dealerImplode'];
        $statusImp =$request['statusImp'];
        $tat =$request['tat'];
        // dd($tat);
        //return User::select("id", "name", "email")->get();
        if(Auth::user()->role == '29' || Auth::user()->role == '30' || Auth::user()->role == '87'){ 
            $query= DB::select("select distinct(c.complaint_number) as complaint_number,assCr.employee_name as createdby,remClosed.employee_name as closedby,remComplete.employee_name as completedby,(case when c.latitude ='' then 'No' else 'Yes' end) as used_google_map,c.id as caseId, c.vehicleId, c.ownerId, c.customer_contact_id, c.callerId, c.from_where, c.to_where, c.highway, c.ticket_type, c.aggregate, c.vehicle_problem, c.assign_to, c.dealer_mob_number, c.dealer_alt_mob_number, c.remark_type, c.disposition, c.agent_remark, c.standard_remark, c.assign_remarks, c.estimated_response_time, c.actual_response_time, c.tat_scheduled, c.acceptance, c.latitude, c.longitude,c.feedback_rating,c.feedback_desc,c.location,c.landmark,c.state as stateId,c.city as cityId,c.district,c.created_at as complaintDate,c.updated_at as complaintUpdate,c.restoration_type,c.response_delay_reason,c.source,c.restoration_delay, v.vehicle, v.vehicle_model, v.reg_number, v.chassis_number, v.engine_number, v.vehicle_segment, v.purchase_date, v.add_blue_use, v.engine_emmission_type, o.owner_name, o.owner_mob, o.owner_landline, o.owner_cat, o.owner_company,o.alse_mail,o.asm_mail, oc.contact_name, oc.mob,oc.owner_contact_email,cal.caller_type, cal.caller_name, cal.caller_contact, cal.caller_language, c.vehicle_type, c.vehicle_movable, s.state, city.city, del.dealer_name,del.sac_code,del.dealer_type as dealer_type,delZone.region as delZoneName,delState.state as stateName,delCity.city as delCityName ,remComplete.created_at as completionDate,remClosed.created_at as closedDate,
            (SELECT CASE WHEN role=76 THEN concat(name,'~~',mobile)  ELSE 'NA~~NA' END as Support_Contact_Person	 FROM users where find_in_set(c.assign_to,dealer_id) and role=76 and flag=1  limit 1) as Support_Contact_Person,
            (SELECT CASE WHEN role=1 THEN concat(name,'~~',mobile) ELSE 'NA~~NA' END as alsedetails FROM users where flag=1 and role=1 and find_in_set(c.assign_to,dealer_id)  limit 1) as alsedetails,
            (select employee_name from remarks where id = (Select min(id) from remarks where complaint_number=c.complaint_number group by complaint_number)) as firstcallagent,
            (select created_at from remarks where id = (Select min(id) from remarks where complaint_number=c.complaint_number group by complaint_number)) as firstcallagentTime,
            (select employee_name from remarks where id = (Select max(id) from remarks where complaint_number=c.complaint_number group by complaint_number)) as lastcallagent,
            (select created_at from remarks where id = (Select max(id) from remarks where complaint_number=c.complaint_number group by complaint_number)) as lastcallagentTime,
            (select disposition from remarks where id = (Select max(id) from remarks where complaint_number=c.complaint_number group by complaint_number)) as lastcallagentdisposition,
            (select created_at from remarks where id = (Select max(id) from remarks where complaint_number=c.complaint_number group by complaint_number)) as maxAssignDate,
            (select employee_name from remarks where id = (Select max(id) from remarks where complaint_number=c.complaint_number group by complaint_number)) as lastupdatename,
            (select created_at from remarks where id = (Select max(id) from remarks where complaint_number=c.complaint_number group by complaint_number)) as lastupdatedate,
            (select count(*) as followupcount from remarks where complaint_number=c.complaint_number) as followupcount,
            concat('Days: ',timestampdiff(Day,c.created_at,IFNULL(remComplete.created_at, c.created_at)),', Hours: ',timestampdiff(Hour,c.created_at,IFNULL(remComplete.created_at, c.created_at))) as tat,remreas.created_at as reassignDate,ag.region as assignedDealerZone,
            aca.created_at as aca_created_at,aca.updated_at as aca_updated_at,
            acp.created_at as acp_created_at,acp.updated_at as acp_updated_at,
            apc.created_at as apc_created_at,apc.updated_at as apc_updated_at,
            vbt.created_at as vbt_created_at,vbt.updated_at as vbt_updated_at      
            from cases as c left join mstr_vehicle as v on v.id = c.vehicleId left join mstr_owner as o on o.id = c.ownerId and o.id = c.ownerId left join mstr_owner_contact as oc on oc.id = c.customer_contact_id and oc.owner_id = c.ownerId left join mstr_caller as cal on cal.id = c.callerId  and cal.owner_id = c.ownerId  left join mstr_caller_state as s on s.id = c.state left join mstr_caller_city as city on city.id = c.city left join mstr_dealer as del on del.id = c.assign_to left join mstr_region as delZone on delZone.id = del.zone left join mstr_state as delState on delState.id = del.state left join mstr_city as delCity on delCity.id = del.city left join remarks as remComplete on remComplete.complaint_number = c.complaint_number and remComplete.remark_type in ('Completed','Customer Confirmation Completed') left join remarks as remClosed on remClosed.complaint_number = c.complaint_number and remClosed.remark_type='Closed' left join remarks as assCr on assCr.complaint_number = c.complaint_number and CONVERT(DATE_FORMAT(c.created_at,'%Y-%m-%d-%H:%i:00'),DATETIME) = CONVERT(DATE_FORMAT(assCr.created_at,'%Y-%m-%d-%H:%i:00'),DATETIME) left join remarks as remreas on remreas.complaint_number = c.complaint_number and remreas.remark_type = 'Reassigned support' left join mstr_dealer as ad on ad.id = c.assign_to left join mstr_region as ag on ag.id = ad.zone  
            left join ticket_hold as aca on aca.complaint_number = c.complaint_number and aca.remark_type = 'Awaiting customer approval'
            left join ticket_hold as acp on acp.complaint_number = c.complaint_number and acp.remark_type = 'Awaiting customer Payment'
            left join ticket_hold as apc on apc.complaint_number = c.complaint_number and apc.remark_type = 'Awaiting parts from customer'
            left join ticket_hold as vbt on vbt.complaint_number = c.complaint_number and vbt.remark_type = 'Vehicle being Towed'
            where cast(c.created_at as date) between cast('$datefrom' as date) and cast('$dateto' as date) and c.assign_to in ($dealerImplode) and c.remark_type in ($statusImp) and c.created_at <=DATE_ADD(now() , INTERVAL - $tat HOUR) and c.complaint_number!='' group by complaint_number");       
            // return view('consolidated_report',$data);
          }
          else{ 
            
            
            $query = DB::select("select distinct(c.complaint_number) as complaint_number,assCr.employee_name as createdby,remClosed.employee_name as closedby,remComplete.employee_name as completedby,(case when c.latitude ='' then 'No' else 'Yes' end) as used_google_map,c.id as caseId, c.vehicleId, c.ownerId, c.customer_contact_id, c.callerId, c.from_where, c.to_where, c.highway, c.ticket_type, c.aggregate, c.vehicle_problem, c.assign_to, c.dealer_mob_number, c.dealer_alt_mob_number, c.remark_type, c.disposition, c.agent_remark, c.standard_remark, c.assign_remarks, c.estimated_response_time, c.actual_response_time, c.tat_scheduled, c.acceptance, c.latitude, c.longitude,c.feedback_rating,c.feedback_desc,c.location,c.landmark,c.state as stateId,c.city as cityId,c.district,c.created_at as complaintDate,c.updated_at as complaintUpdate,c.restoration_type,c.response_delay_reason,c.source,c.restoration_delay, v.vehicle, v.vehicle_model, v.reg_number, v.chassis_number, v.engine_number, v.vehicle_segment, v.purchase_date, v.add_blue_use, v.engine_emmission_type, o.owner_name, o.owner_mob, o.owner_landline, o.owner_cat, o.owner_company,o.alse_mail,o.asm_mail, oc.contact_name, oc.mob,oc.owner_contact_email,cal.caller_type, cal.caller_name, cal.caller_contact, cal.caller_language, c.vehicle_type, c.vehicle_movable, s.state, city.city, del.dealer_name,del.sac_code,del.dealer_type as dealer_type,delZone.region as delZoneName,delState.state as stateName,delCity.city as delCityName ,remComplete.created_at as completionDate,remClosed.created_at as closedDate,(SELECT CASE WHEN role=76 THEN concat(name,'~~',mobile) ELSE 'NA~~NA' END as Support_Contact_Person	 FROM users where role=76 and flag=1 and dealer_id in (c.assign_to) limit 1) as Support_Contact_Person,(SELECT CASE WHEN role=1 THEN concat(name,'~~',mobile) ELSE 'NA~~NA' END as alsedetails FROM users where role=1 and flag=1 and dealer_id in (c.assign_to) limit 1) as alsedetails,
            (select employee_name from remarks where id = (Select min(id) from remarks where complaint_number=c.complaint_number group by complaint_number)) as firstcallagent,
            (select created_at from remarks where id = (Select min(id) from remarks where complaint_number=c.complaint_number group by complaint_number)) as firstcallagentTime,
            (select employee_name from remarks where id = (Select max(id) from remarks where complaint_number=c.complaint_number group by complaint_number)) as lastcallagent,
            (select created_at from remarks where id = (Select max(id) from remarks where complaint_number=c.complaint_number group by complaint_number)) as lastcallagentTime,
            (select disposition from remarks where id = (Select max(id) from remarks where complaint_number=c.complaint_number group by complaint_number)) as lastcallagentdisposition,
            (select created_at from remarks where id = (Select max(id) from remarks where complaint_number=c.complaint_number group by complaint_number)) as maxAssignDate,
            (select employee_name from remarks where id = (Select max(id) from remarks where complaint_number=c.complaint_number group by complaint_number)) as lastupdatename,
            (select created_at from remarks where id = (Select max(id) from remarks where complaint_number=c.complaint_number group by complaint_number)) as lastupdatedate,
            (select count(*) as followupcount from remarks where complaint_number=c.complaint_number) as followupcount,
            concat('Days: ',timestampdiff(Day,c.created_at,IFNULL(remComplete.created_at, c.created_at)),', Hours: ',timestampdiff(Hour,c.created_at,IFNULL(remComplete.created_at, c.created_at))) as tat,remreas.created_at as reassignDate,ag.region as assignedDealerZone,
            aca.created_at as aca_created_at,aca.updated_at as aca_updated_at,
            acp.created_at as acp_created_at,acp.updated_at as acp_updated_at,
            apc.created_at as apc_created_at,apc.updated_at as apc_updated_at,
            vbt.created_at as vbt_created_at,vbt.updated_at as vbt_updated_at      
            from cases as c left join mstr_vehicle as v on v.id = c.vehicleId left join mstr_owner as o on o.id = c.ownerId and o.id = c.ownerId left join mstr_owner_contact as oc on oc.id = c.customer_contact_id and oc.owner_id = c.ownerId left join mstr_caller as cal on cal.id = c.callerId  and cal.owner_id = c.ownerId  left join mstr_caller_state as s on s.id = c.state left join mstr_caller_city as city on city.id = c.city left join mstr_dealer as del on del.id = c.assign_to left join mstr_region as delZone on delZone.id = del.zone left join mstr_state as delState on delState.id = del.state left join mstr_city as delCity on delCity.id = del.city left join remarks as remComplete on remComplete.complaint_number = c.complaint_number and remComplete.remark_type in ('Completed','Customer Confirmation Completed') left join remarks as remClosed on remClosed.complaint_number = c.complaint_number and remClosed.remark_type='Closed' left join remarks as assCr on assCr.complaint_number = c.complaint_number and CONVERT(DATE_FORMAT(c.created_at,'%Y-%m-%d-%H:%i:00'),DATETIME) = CONVERT(DATE_FORMAT(assCr.created_at,'%Y-%m-%d-%H:%i:00'),DATETIME) left join remarks as remreas on remreas.complaint_number = c.complaint_number and remreas.remark_type = 'Reassigned support' left join mstr_dealer as ad on ad.id = c.assign_to left join mstr_region as ag on ag.id = ad.zone 
            left join ticket_hold as aca on aca.complaint_number = c.complaint_number and aca.remark_type = 'Awaiting customer approval'
            left join ticket_hold as acp on acp.complaint_number = c.complaint_number and acp.remark_type = 'Awaiting customer Payment'
            left join ticket_hold as apc on apc.complaint_number = c.complaint_number and apc.remark_type = 'Awaiting parts from customer'
            left join ticket_hold as vbt on vbt.complaint_number = c.complaint_number and vbt.remark_type = 'Vehicle being Towed'
            where cast(c.created_at as date) between cast('$datefrom' as date) and cast('$dateto' as date) and c.assign_to in ($dealerImplode) and c.remark_type in ($statusImp) and c.created_at <=DATE_ADD(now() , INTERVAL - $tat HOUR) and c.complaint_number!='' group by complaint_number");
            // return view('consolidated_report',$data);
        }
       
        $rowData = '';
        
        foreach ($query as $row){
								
                                    $rowData .=$row->complaint_number.'&&';
									$rowData .=$row->remark_type.'&&';
									$rowData .=$row->feedback_rating.'&&';
									$rowData .=$row->feedback_desc.'&&';
									$rowData .=$row->ticket_type.'&&';
									$rowData .=$row->highway.'&&';
									$rowData .=$row->city.'&&';
									$rowData .=$row->state.'&&';
									$rowData .= $row->assignedDealerZone .'&&';
									$rowData .=$row->createdby!=''?$row->createdby.'&&':'NA&&';
									$rowData .=$row->complaintDate!=''?date('d-m-Y',strtotime($row->complaintDate)).'&&':'NA&&';
									$rowData .=$row->complaintDate!=''?date('H:i:s',strtotime($row->complaintDate)).'&&':'NA&&';
									$rowData .=$row->completedby!=''?$row->completedby.'&&':'NA&&';
									$rowData .=$row->completionDate!=''?date('d-m-Y',strtotime($row->completionDate)).'&&':'NA&&';
									$rowData .=$row->completionDate!=''?date('H:i:s',strtotime($row->completionDate)).'&&':'NA&&';
									$rowData .=$row->closedby!=''?$row->closedby.'&&':'NA&&';
									$rowData .=$row->closedDate!=''?date('d-m-Y',strtotime($row->closedDate)).'&&':'NA&&';
									$rowData .=$row->closedDate!=''?date('H:i:s',strtotime($row->closedDate)).'&&':'NA&&';
									 
										

										$remTypeArr = array('Arranging Parts Locally','Awaiting parts from AL','Awaiting AL Approval','Awaiting completion from Ancillary suppliers','Awaiting completion of contracted Job','Awaiting customer approval','Awaiting customer Payment','Awaiting Good will Approval','Awaiting parts from another dealer branch','Awaiting parts from customer','Dealer Feedback','Investigation in progress','Load transfer in progress','Man power not available','Mechanic left to BD spot','Mechanic reached BD spot','Moved to another vehicle on urgency','Public Holiday','Reassigned support','Response Delay','Response not Initiated','Restored by Self','Restored by Unknown support','Restored by Support','Vehicle being Towed','Vehicle reached support point','Work held up due to bandh','Work held up due to injury/accident','Work in progress','Workshop closed - Sunday','Assigned');
										
											
											if(isset($row->complaintDate)){
												$first_date = new DateTime($row->complaintDate);
												$second_date = new DateTime(date("Y-m-d H:i:s"));
												$difference = $first_date->diff($second_date);
												$timePassedSinceCallLog =$difference->d.':'.$difference->h.':'.$difference->i;
											}else{
												$timePassedSinceCallLog='NA';
											}
										
									
									$rowData .=$timePassedSinceCallLog.'&&';
									$rowData .=$row->estimated_response_time!=''?date('d-m-Y',strtotime($row->estimated_response_time)).'&&':'NA&&';
									$rowData .=$row->estimated_response_time!=''?date('H:i:s',strtotime($row->estimated_response_time)).'&&':'NA&&';
									$rowData .=$row->actual_response_time !=''?date('d-m-Y',strtotime($row->actual_response_time)).'&&':'NA&&';
									$rowData .=$row->actual_response_time !=''?date('H:i:s',strtotime($row->actual_response_time)).'&&':'NA&&';
									  										
										$actual_response_time_gap ='NA';
										if($row->actual_response_time !=''){
											$first_date = new DateTime($row->complaintDate);
											$second_date = new DateTime($row->actual_response_time);
											$difference = $first_date->diff($second_date);
											$actual_response_time_gap =$difference->d.'-D '.$difference->h.'-H '.$difference->i.'-M';
										}										
									
									$rowData .=$actual_response_time_gap.'&&';
									
										$tat_scheduledDate =$row->tat_scheduled!=''?date('d-m-Y',strtotime($row->tat_scheduled)):'NA';
										$tat_scheduledTime =$row->tat_scheduled!=''?date('H:i:s',strtotime($row->tat_scheduled)):'NA';
									
									$rowData .=$tat_scheduledDate.'&&';
									$rowData .=$tat_scheduledTime.'&&';
									
										$totalRestorationTime='NA';
										if($row->tat_scheduled!='' && $row->tat_scheduled!='NA'){
											
											$first_date = new DateTime($row->complaintDate);
											$second_date = new DateTime($row->tat_scheduled);
											$difference = $first_date->diff($second_date);
											$totalRestorationTime =$difference->d.'-D '.$difference->h.'-H '.$difference->i.'-M';
										}
									
									$rowData .=$totalRestorationTime.'&&';
									$rowData .=$row->caller_name.'&&';
									$rowData .=$row->caller_type.'&&';
									$rowData .=$row->caller_contact.'&&';
                                    $rowData .=$row->caller_language.'&&';
									$rowData .=$row->owner_name.'&&';
									$rowData .=$row->owner_mob.'&&';
									$rowData .=$row->reg_number.'&&';
									$rowData .=$row->chassis_number.'&&';
									$rowData .=$row->vehicle_model.'&&';
									$rowData .=$row->engine_number.'&&';
									$rowData .=$row->vehicle_segment.'&&';
									$rowData .=$row->purchase_date!=''?date('d-m-Y H:i:s',strtotime($row->purchase_date)).'&&':'NA&&';
									$rowData .=$row->add_blue_use.'&&';
									$rowData .=$row->engine_emmission_type.'&&';
									$rowData .='Al Select'.'&&';
									$rowData .=$row->owner_company.'&&';
									$rowData .=$row->ticket_type.'&&';
									$rowData .=$row->standard_remark.'&&';
									$rowData .=$row->vehicle_type.'&&' ;
									$rowData .=$row->vehicle_movable.'&&';
									$rowData .=$row->location.'&&';
									$rowData .=$row->city.'&&';
									$rowData .=$row->standard_remark.'&&';
									$rowData .=$row->sac_code.'&&';
									$rowData .=$row->aggregate.'&&';
									$rowData .=$row->dealer_type!=''?$row->dealer_type.'&&':'NA&&';
									$rowData .=$row->dealer_name.'&&';
									$rowData .=$row->stateName.'&&';
									$rowData .=$row->delCityName.'&&';
									
										$Support_Contact_Person = $row->Support_Contact_Person!=''?$row->Support_Contact_Person:'NA~~NA';
										$Support_Contact_PersonArr = explode("~~",$Support_Contact_Person);
									
									$rowData .=$Support_Contact_PersonArr[0].'&&';
									$rowData .=$Support_Contact_PersonArr[1].'&&';
									$rowData .=$row->complaintUpdate!=''?date('H:i:s',strtotime($row->complaintUpdate)).'&&':'NA&&';
									$rowData .=$row->remark_type.'&&';
									$rowData .=$row->assign_remarks.'&&';
									 
										$alsedetails = $row->alsedetails;
										if($alsedetails!=''){
											$alsedetailsArr = explode("~~",$alsedetails);
										}else{
											$alsedetailsArr = explode("~~","NA~~NA");
										}
										
									
									$rowData .=$alsedetailsArr[0].'&&';
									$rowData .=$alsedetailsArr[1].'&&';
									$rowData .=$row->firstcallagent.'&&';
									$rowData .=$row->lastcallagent.'&&';
									$rowData .=$row->lastcallagentTime.'&&';
									$rowData .=$row->firstcallagentTime.'&&';
									$rowData .=$row->complaintDate!=''?date('d-m-Y',strtotime($row->complaintDate)).'&&':'NA&&';
									$rowData .=$row->complaintDate!=''?date('H:i:s',strtotime($row->complaintDate)).'&&':'NA&&';
									  
										
										if($row->lastcallagentTime !=''){
											$currentDate = date('Y-m-d H:i:s');
											$first_date = new DateTime($row->lastcallagentTime);
											$second_date = new DateTime($currentDate);
											$difference = $first_date->diff($second_date);
											$followUpCall =$difference->d.'-D '.$difference->h.'-H '.$difference->i.'-M';
										
										}else{
											$followUpCall='NA';
										}
										
										
									
									$rowData .=$followUpCall.'&&';
									$rowData .=$row->lastcallagentdisposition.'&&';
									$rowData .=$row->used_google_map.'&&';
									$rowData .=$row->lastupdatename.'&&';
									$rowData .=$row->lastupdatedate!=''?date('d-m-Y H:i:s',strtotime($row->lastupdatedate)).'&&':'NA&&';
									$rowData .=$row->reassignDate!=''?date('d-m-Y',strtotime($row->reassignDate)).'&&':'NA&&';
									$rowData .=$row->reassignDate!=''?date('H:i:s',strtotime($row->reassignDate)).'&&':'NA&&';
									$rowData .=$row->restoration_type.'&&';
									$rowData .=$row->response_delay_reason.'&&';
									
										$completionDate=$completionDay=$completionHour=$completionMin='NA';
										if($row->completionDate !='' && !empty($row->completionDate)){
											

											$date1 =strtotime($row->complaintDate);
											$date2 =strtotime($row->completionDate);
											$completionDay = abs($date2 -$date1) / (60*60*24);
											$completionHour = abs($date2 -$date1) / (60*60);
										}
									
									$rowData .= $completionHour!='NA'?ceil($completionHour).'&&':'NA&&';
									$rowData .= $completionDay!='NA'?ceil($completionDay).'&&':'NA&&';
									
										if ($completionHour <= 4 && $completionHour !='NA'){
											$rowData .= "Within 4 Hrs.".'&&';
                                        }
										else if ($completionHour >4 && $completionHour <= 48 && $completionHour !='NA'){
                                        $rowData .="Above 4 hrs. to 48 hrs.".'&&';
                                    }
                                    else if ($completionHour >48 && $completionHour <= 72 && $completionHour !='NA'){
                                        $rowData .= "Above 48 hrs. to 72 hrs.".'&&';
                                    }
										else if ($completionHour > 72 && $completionHour <= 120 && $completionHour !='NA'){
											$rowData .= "Above 72 hrs. to 120 hrs.".'&&';
                                        }
										else if ($completionHour > 120 && $completionHour !='NA'){
											
												$defaulthrs = floor($completionHour/24);
												$hoursGet = $defaulthrs * 24;
											
											$rowData .= "Above $hoursGet hrs.".'&&';
                                        }
										else{
											$rowData .= "NA&&";
										}
									
									$rowData .=$row->source.'&&';
									$rowData .=$row->restoration_delay.'&&';
									$rowData .=$row->followupcount.'&&';
									
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
									 
									
									$rowData .=$acaTime.'&&';
									$rowData .=$acpTime.'&&';
									$rowData .=$apcTime.'&&';
									$rowData .=$vbtTime.'&&';
									
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
									
									
									$rowData .=$days.':'.$hour.':'.$minute.'&&';
                                    $rowData .= '~~';
                    }
                    $rowData = rtrim($rowData,'~~');
                    $rowDataArr = explode("~~",$rowData);
                    $final = array();
                    foreach($rowDataArr as $item){
                        $val = rtrim($item, '&&');
                        $valArr = explode('&&',$val);
                        $final[]=$valArr;

                    }
                //   dd($final);
        return collect($final);
    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ['Ticket Number','Ticket Status','Feedback Rating','Feedback Description','Vehicle Inside WS','NH','City','State','Assigned Dealer Zone','Created By','Call Log Date','Call Log Time','Completed By','Completed Date ','Completed Time','Closed By','Closed Date','Closed Time','Time passed since call log (Day:hour:minute)','Est. Response Date','Est. Response Time','Actual Response Date','Actual Response Time','Response Gap','Restoration Date','Restoration Time','Total Restoration Time','Caller Name','Caller Type','Caller Contact Number','Caller Language','Owner Name','Owner Mobile No','Registration Number','Chassis Number','Vehicle Model','Engine Number','Vehicle Segment','Purchase Date','AddBlue','EngineEmmisionType','Owner Category','Company Name','Ticket Type','Customer Remarks','Vehicle Status','IS Vehicle movable','Breakdown Location','District','Issue','Support Code','Aggregate','Support Center Type','Support Center Name','Support Center State','Support Center City','Support Contact Person','Support Contact Number','Last Remark Time','Remark Type','Latest Comments','ALSE Name','ALSE Contact Number','First Called_By','Last Called_By','Last Called On','First Called On','Support Center Assigned Date','Support Center Assigned time','Follow Up Hours Passed','Disposition','Used Google Map','Last Update By','Last Update Date','Reassign Date','Reassign Time','Restoration Type','Response Delay Reason','Total Restoration Time (Delay Time)','Days','Ageing','Source','Restoration Delay Reason','Total Follow Up','Awaiting Customer Approval','Awaiting Customer Payment','Awaiting Parts From Customer','Vehicle Being Towed','Total Hold'];
    }
}