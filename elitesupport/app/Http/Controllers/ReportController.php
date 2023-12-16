<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Session;
use Redirect;
use Mail;
use App\classes\ServerValidation;
use App\classes\AccessControl;
use App\Exports\VahanApiExport;
use Maatwebsite\Excel\Facades\Excel;
use Auth;
date_default_timezone_set('Asia/Kolkata');
class ReportController extends Controller
{
    public function __construct(){
		DB::enableQueryLog();
		DB::select("SET sql_mode=''");
	}
	public function reportData(Request $request){

		if(Auth::user()->role == '29' || Auth::user()->role == '30'){
			$DateFrom=$request->input('datefrom');
			$DateTo=$request->input('dateto');
			$State=$request->input('State');
			$city=$request->input('City');
			$zone=$request->input('Zone');
			$dealer=$request->input('Dealer');
			$dealerCode=$request->input('DealerCode');
			$Product=$request->input('Product');
			$Brand=$request->input('Brand');
			$DateFrom = $DateFrom!=''?$DateFrom:date('Y-m-d');
			$DateTo = $DateTo!=''?$DateTo:date('Y-m-d');
			$State = $State!=''?$State:'';
			$State = $State!=''?$State:'';
			$city = $city!=''?$city:'';
			$zone = $zone!=''?$zone:'';
			$dealer = $dealer!=''?$dealer:'';
			$dealerCode = $dealerCode!=''?$dealerCode:'';
			$Product = $Product!=''?$Product:'';
			$Brand = $Brand!=''?$Brand:'';
			$Product = $Product!=''?$Product:'';
			$data['dashboardData1']= DB::select("call Dashboardcases('$DateFrom','$DateTo','$State','$city','$zone','$Product','$Brand','$dealer','$dealerCode')");

			$data['stateData']= DB::table('mstr_state_cilty_zone')->distinct()->get(['state']);
	      	return view('report',$data);
		}else{
		 	return  back()->with($notification);
		}

	}
	public function report(){
		try{
			if ( Auth::user()->role != '29' || Auth::user()->role != '30') {
				$notification = array(
				'message' => $ex->getMessage(),
				'alert-type' => 'error'
				);
				return back()->with($notification);
			}
			if(empty(Auth::user()->email)){
				return redirect('/');
			}else{
				$date = date('Y-m-d');
				$data['dashboardData1']= DB::select("call Dashboardcases('$date','$date','','','','','','','')");
				//$data['zoneData']=DB::select("call getzone2()");
				$data['zoneData']=DB::table("mstr_region")->select('id','region')->get();
				$data['complaintTypeData']= DB::table('mstr_complaint')->distinct()->get(['complaint_type']);
				$data['vehicleData']= DB::table('mstr_vehicle')->select('vehicle')->distinct()->get(['vehicle']);
				return view('report',$data);
			}

		}catch (\Exception $ex) {
			$notification = array(
	                'message' => $ex->getMessage(),
	                'alert-type' => 'error'
	            );
            return back()->with($notification);
        }

	}
	public function consolidatedReport(){
		try{
			
				
				$date = date('Y-m-d');
        $zone = Auth::user()->zone;
        $state = Auth::user()->state;
        $dealer_id = Auth::user()->dealer_id;
				
				$data['statusData'] = DB::select("Select id,type from remark_type order by type ASC");
        if( Auth::user()->role == '29' || Auth::user()->role == '30'){
          $data['regionData'] = DB::select("Select id,region from mstr_region order by region ASC");
          $data['dealerData']=DB::table("mstr_dealer")->select('id','dealer_name')->get();
        }else{
          $data['regionData'] = DB::select("Select id,region from mstr_region where id in ($zone) order by region ASC");
          //$data['dealerData']=DB::table("mstr_dealer")->select('id','dealer_name')->where('state',$state)->get();
          $data['dealerData']=DB::select("Select id,dealer_name from mstr_dealer where id in ($dealer_id) order by dealer_name ASC");
        }
				return view('consolidated_report',$data);
			
		}catch (\Exception $ex) {			
			$notification = array(
	                'message' => $ex->getMessage(),
	                'alert-type' => 'error'
	            );
            return back()->with($notification);
        }
			
	}
	public function storeConsolidatedReport(Request $request){
		try {
     // dd($request->input());
			$datefrom = $request->input('datefrom');
			$dateto = $request->input('dateto');
			$zone = $request->input('zone');
			$state = $request->input('state');
			$city = $request->input('city');
			$dealer = $request->input('dealer');
			$ticketStatus = $request->input('ticketStatus');
      $tat = $request->input('tat');
     // dd($tat);
      $statusArr='';
      foreach ($ticketStatus as $row) {
        $statusArr .= '"'.$row.'",';
      }
      
      $statusImp = rtrim($statusArr,',');
      $zoneImplode = implode(',',$request->input('zone'));
			$stateImplode = implode(',',$request->input('state'));
			$cityImplode = implode(',',$request->input('city'));
			$dealerImplode = implode(',',$request->input('dealer'));

      $data['zone'] = $zone;
      $data['zoneImplode'] = trim($zoneImplode);
      $data['stateImplode'] = $stateImplode;
      $data['cityImplode'] = $cityImplode;
      $data['dealerImplode'] = $dealerImplode;
			$data['dealer'] = $dealer;
			$data['datefrom'] = $datefrom;
			$data['dateto'] = $dateto;
			$data['ticketStatus'] = $ticketStatus;
			$data['tat'] = $tat;
      $data['statusData'] = DB::select("Select id,type from remark_type");
			
      $sess_dealer =Auth::user()->dealer_id;

		if(Auth::user()->role == '29' || Auth::user()->role == '30' || Auth::user()->role == '87'){ 
      $data['regionData'] = DB::select("Select id,region from mstr_region order by region ASC"); 
     
			$data['consolidatedReport'] = DB::select("select distinct(c.complaint_number) as complaint_number,assCr.employee_name as createdby,remClosed.employee_name as closedby,remComplete.employee_name as completedby,(case when c.latitude ='' then 'No' else 'Yes' end) as used_google_map,c.id as caseId, c.vehicleId, c.ownerId, c.customer_contact_id, c.callerId, c.from_where, c.to_where, c.highway, c.ticket_type, c.aggregate, c.vehicle_problem, c.assign_to, c.dealer_mob_number, c.dealer_alt_mob_number, c.remark_type, c.disposition, c.agent_remark, c.standard_remark, c.assign_remarks, c.estimated_response_time, c.actual_response_time, c.tat_scheduled, c.acceptance, c.latitude, c.longitude,c.feedback_rating,c.feedback_desc,c.location,c.landmark,c.state as stateId,c.city as cityId,c.district,c.created_at as complaintDate,c.updated_at as complaintUpdate,c.restoration_type,c.response_delay_reason,c.source,c.restoration_delay,c.so_number,c.jobcard_number,c.actual_response_time_customer,c.tat_scheduled_customer, v.vehicle, v.vehicle_model, v.reg_number, v.chassis_number, v.engine_number, v.vehicle_segment, v.purchase_date, v.add_blue_use, v.engine_emmission_type, o.owner_name, o.owner_mob, o.owner_landline, o.owner_cat, o.owner_company,o.alse_mail,o.asm_mail, oc.contact_name, oc.mob,oc.owner_contact_email,cal.caller_type, cal.caller_name, cal.caller_contact, cal.caller_language, c.vehicle_type, c.vehicle_movable,c.reason_reassign,c.SPOC, s.state, city.city, del.dealer_name,del.sac_code,del.dealer_type as dealer_type,delZone.region as delZoneName,delState.state as stateName,delCity.city as delCityName ,remComplete.created_at as completionDate,remClosed.created_at as closedDate,
      (SELECT CASE WHEN role=76 THEN concat(name,'~~',mobile)  ELSE 'NA~~NA' END as Support_Contact_Person	 FROM users where find_in_set(c.assign_to,dealer_id) and role=76 and flag=1  limit 1) as Support_Contact_Person,
      (SELECT CASE WHEN role=1 THEN concat(name,'~~',mobile) ELSE 'NA~~NA' END as alsedetails FROM users where flag=1 and role=1 and find_in_set(c.assign_to,dealer_id)  limit 1) as alsedetails,
      (select employee_name from remarks where id = (Select min(id) from remarks where complaint_number=c.complaint_number group by complaint_number)) as firstcallagent,
      (SELECT created_at FROM remarks where complaint_number = c.complaint_number ORDER BY id ASC LIMIT 1, 1) as secondcallagentTime,
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
      vbt.created_at as vbt_created_at,vbt.updated_at as vbt_updated_at,
      gppce.created_at as gppce_created_at,gppce.updated_at as gppce_updated_at
      from cases as c 
      left join mstr_vehicle as v on v.id = c.vehicleId 
      left join mstr_owner as o on o.id = c.ownerId and o.id = c.ownerId 
      left join mstr_owner_contact as oc on oc.id = c.customer_contact_id and oc.owner_id = c.ownerId 
      left join mstr_caller as cal on cal.id = c.callerId  
      left join mstr_caller_state as s on s.id = c.state 
      left join mstr_caller_city as city on city.id = c.city 
      left join mstr_dealer as del on del.id = c.assign_to 
      left join mstr_region as delZone on delZone.id = del.zone 
      left join mstr_state as delState on delState.id = del.state 
      left join mstr_city as delCity on delCity.id = del.city 
      left join remarks as remComplete on remComplete.complaint_number = c.complaint_number and remComplete.remark_type in ('Completed','Customer Confirmation Completed') 
      left join remarks as remClosed on remClosed.complaint_number = c.complaint_number and remClosed.remark_type='Closed' 
      left join remarks as assCr on assCr.complaint_number = c.complaint_number and CONVERT(DATE_FORMAT(c.created_at,'%Y-%m-%d-%H:%i:00'),DATETIME) = CONVERT(DATE_FORMAT(assCr.created_at,'%Y-%m-%d-%H:%i:00'),DATETIME) 
      left join remarks as remreas on remreas.complaint_number = c.complaint_number and remreas.remark_type = 'Reassigned support' 
      left join mstr_dealer as ad on ad.id = c.assign_to 
      left join mstr_region as ag on ag.id = ad.zone  
      left join ticket_hold as aca on aca.complaint_number = c.complaint_number and aca.remark_type = 'Awaiting customer approval'
      left join ticket_hold as acp on acp.complaint_number = c.complaint_number and acp.remark_type = 'Awaiting customer Payment'
      left join ticket_hold as apc on apc.complaint_number = c.complaint_number and apc.remark_type = 'Awaiting parts from customer'
      left join ticket_hold as vbt on vbt.complaint_number = c.complaint_number and vbt.remark_type = 'Vehicle being Towed'
      left join ticket_hold as gppce on gppce.complaint_number = c.complaint_number and gppce.remark_type = 'Gate Pass Pending From Customer End'
      where cast(c.created_at as date) between cast('$datefrom' as date) and cast('$dateto' as date) and c.assign_to in ($dealerImplode) and c.remark_type in ($statusImp) and c.created_at <=DATE_ADD(now() , INTERVAL - $tat HOUR) and c.complaint_number!='' group by complaint_number"); 
		

     
			
			return view('consolidated_report',$data);
		}
		else{ 
      
			$ses_zone = Auth::user()->zone;
			$location = Auth::user()->city;
      $data['regionData'] = DB::select("Select id,region from mstr_region where id in ($ses_zone) order by region ASC");
			$data['consolidatedReport'] = DB::select("select distinct(c.complaint_number) as complaint_number,assCr.employee_name as createdby,remClosed.employee_name as closedby,remComplete.employee_name as completedby,(case when c.latitude ='' then 'No' else 'Yes' end) as used_google_map,c.id as caseId, c.vehicleId, c.ownerId, c.customer_contact_id, c.callerId, c.from_where, c.to_where, c.highway, c.ticket_type, c.aggregate, c.vehicle_problem, c.assign_to, c.dealer_mob_number, c.dealer_alt_mob_number, c.remark_type, c.disposition, c.agent_remark, c.standard_remark, c.assign_remarks, c.estimated_response_time, c.actual_response_time, c.tat_scheduled, c.acceptance, c.latitude, c.longitude,c.feedback_rating,c.feedback_desc,c.location,c.landmark,c.state as stateId,c.city as cityId,c.district,c.created_at as complaintDate,c.updated_at as complaintUpdate,c.restoration_type,c.response_delay_reason,c.source,c.restoration_delay,c.so_number,c.jobcard_number,c.actual_response_time_customer,c.tat_scheduled_customer, v.vehicle, v.vehicle_model, v.reg_number, v.chassis_number, v.engine_number, v.vehicle_segment, v.purchase_date, v.add_blue_use, v.engine_emmission_type, o.owner_name, o.owner_mob, o.owner_landline, o.owner_cat, o.owner_company,o.alse_mail,o.asm_mail, oc.contact_name, oc.mob,oc.owner_contact_email,cal.caller_type, cal.caller_name, cal.caller_contact, cal.caller_language, c.vehicle_type, c.vehicle_movable,c.reason_reassign,c.SPOC, s.state, city.city, del.dealer_name,del.sac_code,del.dealer_type as dealer_type,delZone.region as delZoneName,delState.state as stateName,delCity.city as delCityName ,remComplete.created_at as completionDate,remClosed.created_at as closedDate,(SELECT CASE WHEN role=76 THEN concat(name,'~~',mobile) ELSE 'NA~~NA' END as Support_Contact_Person	 FROM users where role=76 and flag=1 and dealer_id in (c.assign_to) limit 1) as Support_Contact_Person,(SELECT CASE WHEN role=1 THEN concat(name,'~~',mobile) ELSE 'NA~~NA' END as alsedetails FROM users where role=1 and flag=1 and dealer_id in (c.assign_to) limit 1) as alsedetails,
      (select employee_name from remarks where id = (Select min(id) from remarks where complaint_number=c.complaint_number group by complaint_number)) as firstcallagent,
      (SELECT created_at FROM remarks where complaint_number = c.complaint_number ORDER BY id ASC LIMIT 1, 1) as secondcallagentTime,
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
      vbt.created_at as vbt_created_at,vbt.updated_at as vbt_updated_at,
      gppce.created_at as gppce_created_at,gppce.updated_at as gppce_updated_at
      from cases as c left join mstr_vehicle as v on v.id = c.vehicleId left join mstr_owner as o on o.id = c.ownerId and o.id = c.ownerId left join mstr_owner_contact as oc on oc.id = c.customer_contact_id and oc.owner_id = c.ownerId left join mstr_caller as cal on cal.id = c.callerId   left join mstr_caller_state as s on s.id = c.state left join mstr_caller_city as city on city.id = c.city left join mstr_dealer as del on del.id = c.assign_to left join mstr_region as delZone on delZone.id = del.zone left join mstr_state as delState on delState.id = del.state left join mstr_city as delCity on delCity.id = del.city left join remarks as remComplete on remComplete.complaint_number = c.complaint_number and remComplete.remark_type in ('Completed','Customer Confirmation Completed') left join remarks as remClosed on remClosed.complaint_number = c.complaint_number and remClosed.remark_type='Closed' left join remarks as assCr on assCr.complaint_number = c.complaint_number and CONVERT(DATE_FORMAT(c.created_at,'%Y-%m-%d-%H:%i:00'),DATETIME) = CONVERT(DATE_FORMAT(assCr.created_at,'%Y-%m-%d-%H:%i:00'),DATETIME) left join remarks as remreas on remreas.complaint_number = c.complaint_number and remreas.remark_type = 'Reassigned support' left join mstr_dealer as ad on ad.id = c.assign_to left join mstr_region as ag on ag.id = ad.zone 
      left join ticket_hold as aca on aca.complaint_number = c.complaint_number and aca.remark_type = 'Awaiting customer approval'
      left join ticket_hold as acp on acp.complaint_number = c.complaint_number and acp.remark_type = 'Awaiting customer Payment'
      left join ticket_hold as apc on apc.complaint_number = c.complaint_number and apc.remark_type = 'Awaiting parts from customer'
      left join ticket_hold as vbt on vbt.complaint_number = c.complaint_number and vbt.remark_type = 'Vehicle being Towed'
      left join ticket_hold as gppce on gppce.complaint_number = c.complaint_number and gppce.remark_type = 'Gate Pass Pending From Customer End'
      where cast(c.created_at as date) between cast('$datefrom' as date) and cast('$dateto' as date) and c.assign_to in ($dealerImplode) and c.remark_type in ($statusImp) and c.created_at <=DATE_ADD(now() , INTERVAL - $tat HOUR) and c.complaint_number!='' group by complaint_number");
      
			return view('consolidated_report',$data);
		}
			

		} catch (\Exception $ex) {
			$notification = array(
			'message' => $ex->getMessage().' Line : '.$ex->getLine(),
			'alert-type' => 'error'
			);
			return back()->with($notification);
		}
	}
	public function dealerActivityReport(){
		try{
			
      
				
				$date = date('Y-m-d');
        $zone = Auth::user()->zone;
        $state = Auth::user()->state;
        $dealer_id = Auth::user()->dealer_id;
				
				$data['statusData'] = DB::select("Select id,type from remark_type");
        if( Auth::user()->role == '29' || Auth::user()->role == '30'){
          $data['regionData'] = DB::select("Select id,region from mstr_region order by region ASC");
          $data['dealerData']=DB::table("mstr_dealer")->select('id','dealer_name')->where('flag','1')->orderBy('dealer_name','ASC')->get();
        }else{
          $data['regionData'] = DB::select("Select id,region from mstr_region where id in ($zone)");
          //$data['dealerData']=DB::table("mstr_dealer")->select('id','dealer_name')->where('state',$state)->get();
          $data['dealerData']=DB::select("Select id,dealer_name from mstr_dealer where id in ($dealer_id) and flag=1 order by dealer_name ASC");
        }
				return view('dealer_activity_report',$data);
			

		}catch (\Exception $ex) {			
			$notification = array(
	                'message' => $ex->getMessage(),
	                'alert-type' => 'error'
	            );
            return back()->with($notification);
        }
			
	}
	public function storeDealerActivityReport(Request $request){
		try {
     // dd($request->input());
			$datefrom = $request->input('datefrom');
			$dateto = $request->input('dateto');
			$zone = $request->input('zone');
			$state = $request->input('state');
			$city = $request->input('city');
			$dealer = $request->input('dealer');
			$ticketStatus = $request->input('ticketStatus');
      $tat = $request->input('tat');
     // dd($tat);
      $statusArr='';
      foreach ($ticketStatus as $row) {
        $statusArr .= '"'.$row.'",';
      }
      
      $statusImp = rtrim($statusArr,',');
      $zoneImplode = implode(',',$request->input('zone'));
			$stateImplode = implode(',',$request->input('state'));
			$cityImplode = implode(',',$request->input('city'));
			$dealerImplode = implode(',',$request->input('dealer'));

      $data['zone'] = $zone;
      $data['zoneImplode'] = trim($zoneImplode);
      $data['stateImplode'] = $stateImplode;
      $data['cityImplode'] = $cityImplode;
      $data['dealerImplode'] = $dealerImplode;
			$data['dealer'] = $dealer;
			$data['datefrom'] = $datefrom;
			$data['dateto'] = $dateto;
			$data['ticketStatus'] = $ticketStatus;
			$data['tat'] = $tat;
      $data['statusData'] = DB::select("Select id,type from remark_type");
			
      $sess_dealer =Auth::user()->dealer_id;

		if(Auth::user()->role == '29' || Auth::user()->role == '30'){ 
      $data['regionData'] = DB::select("Select id,region from mstr_region");
     
			$data['consolidatedReport'] = DB::select("select distinct(c.complaint_number) as complaint_number,assCr.employee_name as createdby,remClosed.employee_name as closedby,remComplete.employee_name as completedby,(case when c.latitude ='' then 'No' else 'Yes' end) as used_google_map,c.id as caseId, c.vehicleId, c.ownerId, c.customer_contact_id, c.callerId, c.from_where, c.to_where, c.highway, c.ticket_type, c.aggregate, c.vehicle_problem, c.assign_to, c.dealer_mob_number, c.dealer_alt_mob_number, c.remark_type, c.disposition, c.agent_remark, c.standard_remark, c.assign_remarks, c.estimated_response_time, c.actual_response_time, c.tat_scheduled, c.acceptance, c.latitude, c.longitude,c.feedback_rating,c.feedback_desc,c.location,c.landmark,c.state as stateId,c.city as cityId,c.district,c.created_at as complaintDate,c.updated_at as complaintUpdate,c.restoration_type,c.response_delay_reason, v.vehicle, v.vehicle_model, v.reg_number, v.chassis_number, v.engine_number, v.vehicle_segment, v.purchase_date, v.add_blue_use, v.engine_emmission_type, o.owner_name, o.owner_mob, o.owner_landline, o.owner_cat, o.owner_company,o.alse_mail,o.asm_mail, oc.contact_name, oc.mob,oc.owner_contact_email,cal.caller_type, cal.caller_name, cal.caller_contact, c.vehicle_type, c.vehicle_movable,c.reason_reassign,c.SPOC, s.state, city.city, del.dealer_name,del.sac_code,del.dealer_type as dealer_type,delZone.region as delZoneName,delState.state as stateName,delCity.city as delCityName ,remComplete.created_at as completionDate,remClosed.created_at as closedDate,
      (SELECT CASE WHEN role=76 THEN concat(name,'~~',mobile) WHEN role=7 THEN concat(name,'~~',mobile) WHEN role=6 THEN concat(name,'~~',mobile) ELSE 'NA~~NA' END as Support_Contact_Person	 FROM users where find_in_set(c.assign_to,dealer_id)  limit 1) as Support_Contact_Person,
      (SELECT CASE WHEN role=1 THEN concat(name,'~~',mobile) ELSE 'NA~~NA' END as alsedetails FROM users where find_in_set(c.assign_to,dealer_id)  limit 1) as alsedetails,
      (select employee_name from remarks where id = (Select min(id) from remarks where complaint_number=c.complaint_number group by complaint_number)) as firstcallagent,
      (select created_at from remarks where id = (Select min(id) from remarks where complaint_number=c.complaint_number group by complaint_number)) as firstcallagentTime,
      (select employee_name from remarks where id = (Select max(id) from remarks where complaint_number=c.complaint_number group by complaint_number)) as lastcallagent,
      (select created_at from remarks where id = (Select max(id) from remarks where complaint_number=c.complaint_number group by complaint_number)) as lastcallagentTime,
      (select disposition from remarks where id = (Select max(id) from remarks where complaint_number=c.complaint_number group by complaint_number)) as lastcallagentdisposition,
      (select created_at from remarks where id = (Select max(id) from remarks where complaint_number=c.complaint_number group by complaint_number)) as maxAssignDate,
      (select employee_name from remarks where id = (Select max(id) from remarks where complaint_number=c.complaint_number group by complaint_number)) as lastupdatename,
      (select created_at from remarks where id = (Select max(id) from remarks where complaint_number=c.complaint_number group by complaint_number)) as lastupdatedate,
      concat('Days: ',timestampdiff(Day,c.created_at,IFNULL(remComplete.created_at, c.created_at)),', Hours: ',timestampdiff(Hour,c.created_at,IFNULL(remComplete.created_at, c.created_at))) as tat,remreas.created_at as reassignDate  from cases as c left join mstr_vehicle as v on v.id = c.vehicleId left join mstr_owner as o on o.id = c.ownerId and o.id = c.ownerId left join mstr_owner_contact as oc on oc.id = c.customer_contact_id and oc.owner_id = c.ownerId left join mstr_caller as cal on cal.id = c.callerId   left join mstr_caller_state as s on s.id = c.state left join mstr_caller_city as city on city.id = c.city left join mstr_dealer as del on del.id = c.assign_to left join mstr_region as delZone on delZone.id = del.zone left join mstr_state as delState on delState.id = del.state left join mstr_city as delCity on delCity.id = del.city left join remarks as remComplete on remComplete.complaint_number = c.complaint_number and remComplete.remark_type like('%Completed%') left join remarks as remClosed on remClosed.complaint_number = c.complaint_number and remClosed.remark_type='Closed' left join remarks as assCr on assCr.complaint_number = c.complaint_number and c.created_at = assCr.created_at left join remarks as remreas on remreas.complaint_number = c.complaint_number and remreas.remark_type = 'Reassigned support' left join remarks as rem on rem.complaint_number = c.complaint_number left join users as usrs on find_in_set(rem.assign_to,usrs.dealer_id) where cast(c.created_at as date) between cast('$datefrom' as date) and cast('$dateto' as date) and c.assign_to in ($dealerImplode) and rem.remark_type in ($statusImp) and c.created_at <=DATE_ADD(now() , INTERVAL - $tat HOUR) and c.complaint_number!='' and usrs.user_type_id=3 group by complaint_number"); 
		
			
			return view('dealer_activity_report',$data);
		}
		else{ 
      
			$ses_zone = Auth::user()->zone;
			$location = Auth::user()->city;
      $data['regionData'] = DB::select("Select id,region from mstr_region where id in ($ses_zone)");
			$data['consolidatedReport'] = DB::select("select distinct(c.complaint_number) as complaint_number,assCr.employee_name as createdby,remClosed.employee_name as closedby,remComplete.employee_name as completedby,(case when c.latitude ='' then 'No' else 'Yes' end) as used_google_map,c.id as caseId, c.vehicleId, c.ownerId, c.customer_contact_id, c.callerId, c.from_where, c.to_where, c.highway, c.ticket_type, c.aggregate, c.vehicle_problem, c.assign_to, c.dealer_mob_number, c.dealer_alt_mob_number, c.remark_type, c.disposition, c.agent_remark, c.standard_remark, c.assign_remarks, c.estimated_response_time, c.actual_response_time, c.tat_scheduled, c.acceptance, c.latitude, c.longitude,c.feedback_rating,c.feedback_desc,c.location,c.landmark,c.state as stateId,c.city as cityId,c.district,c.created_at as complaintDate,c.updated_at as complaintUpdate,c.restoration_type,c.response_delay_reason, v.vehicle, v.vehicle_model, v.reg_number, v.chassis_number, v.engine_number, v.vehicle_segment, v.purchase_date, v.add_blue_use, v.engine_emmission_type, o.owner_name, o.owner_mob, o.owner_landline, o.owner_cat, o.owner_company,o.alse_mail,o.asm_mail, oc.contact_name, oc.mob,oc.owner_contact_email,cal.caller_type, cal.caller_name, cal.caller_contact, c.vehicle_type, c.vehicle_movable,c.reason_reassign,c.SPOC, s.state, city.city, del.dealer_name,del.sac_code,del.dealer_type as dealer_type,delZone.region as delZoneName,delState.state as stateName,delCity.city as delCityName ,remComplete.created_at as completionDate,remClosed.created_at as closedDate,(SELECT CASE WHEN role=76 THEN concat(name,'~~',mobile) WHEN role=7 THEN concat(name,'~~',mobile) WHEN role=6 THEN concat(name,'~~',mobile) ELSE 'NA~~NA' END as Support_Contact_Person	 FROM users where dealer_id in (c.assign_to) limit 1) as Support_Contact_Person,(SELECT CASE WHEN role=1 THEN concat(name,'~~',mobile) ELSE 'NA~~NA' END as alsedetails FROM users where dealer_id in (c.assign_to) limit 1) as alsedetails,
      (select employee_name from remarks where id = (Select min(id) from remarks where complaint_number=c.complaint_number group by complaint_number)) as firstcallagent,
      (select created_at from remarks where id = (Select min(id) from remarks where complaint_number=c.complaint_number group by complaint_number)) as firstcallagentTime,
      (select employee_name from remarks where id = (Select max(id) from remarks where complaint_number=c.complaint_number group by complaint_number)) as lastcallagent,
      (select created_at from remarks where id = (Select max(id) from remarks where complaint_number=c.complaint_number group by complaint_number)) as lastcallagentTime,
      (select disposition from remarks where id = (Select max(id) from remarks where complaint_number=c.complaint_number group by complaint_number)) as lastcallagentdisposition,
      (select created_at from remarks where id = (Select max(id) from remarks where complaint_number=c.complaint_number group by complaint_number)) as maxAssignDate,
      (select employee_name from remarks where id = (Select max(id) from remarks where complaint_number=c.complaint_number group by complaint_number)) as lastupdatename,
      (select created_at from remarks where id = (Select max(id) from remarks where complaint_number=c.complaint_number group by complaint_number)) as lastupdatedate,
      concat('Days: ',timestampdiff(Day,c.created_at,IFNULL(remComplete.created_at, c.created_at)),', Hours: ',timestampdiff(Hour,c.created_at,IFNULL(remComplete.created_at, c.created_at))) as tat,remreas.created_at as reassignDate from cases as c left join mstr_vehicle as v on v.id = c.vehicleId left join mstr_owner as o on o.id = c.ownerId and o.id = c.ownerId left join mstr_owner_contact as oc on oc.id = c.customer_contact_id and oc.owner_id = c.ownerId left join mstr_caller as cal on cal.id = c.callerId   left join mstr_caller_state as s on s.id = c.state left join mstr_caller_city as city on city.id = c.city left join mstr_dealer as del on del.id = c.assign_to left join mstr_region as delZone on delZone.id = del.zone left join mstr_state as delState on delState.id = del.state left join mstr_city as delCity on delCity.id = del.city left join remarks as remComplete on remComplete.complaint_number = c.complaint_number and remComplete.remark_type like('%Completed%') left join remarks as remClosed on remClosed.complaint_number = c.complaint_number and remClosed.remark_type='Closed' left join remarks as assCr on assCr.complaint_number = c.complaint_number and c.created_at = assCr.created_at left join remarks as remreas on remreas.complaint_number = c.complaint_number and remreas.remark_type = 'Reassigned support' left join remarks as rem on rem.complaint_number = c.complaint_number left join users as usrs on find_in_set(rem.assign_to,usrs.dealer_id) where cast(c.created_at as date) between cast('$datefrom' as date) and cast('$dateto' as date) and c.assign_to in ($dealerImplode) and rem.remark_type in ($statusImp) and c.created_at <=DATE_ADD(now() , INTERVAL - $tat HOUR) and c.complaint_number!='' and usrs.user_type_id =3 group by complaint_number");
			return view('dealer_activity_report',$data);
		}
			

		} catch (\Exception $ex) {
			$notification = array(
			'message' => $ex->getMessage().' Line : '.$ex->getLine(),
			'alert-type' => 'error'
			);
			return back()->with($notification);
		}
	}
	public function dealerSummaryReport(){
		try{
			
				if(Auth::user()->role == '29' || Auth::user()->role == '30' || Auth::user()->user_type_id =='2'){
					$date = date('Y-m-d');
					$data['complaintTypeData']= DB::table('mstr_complaint')->select('id','complaint_type')->get();
					$data['vehicleData']= DB::table('mstr_vehicle')->select('id','vehicle')->get();
					return view('dealer_summary_report',$data);
				}else{
					$location1 = Auth::user()->zone;
					$product1 = Auth::user()->product;
					$brand1 = Auth::user()->brand;
					//$complaint_type_id = Auth::user()->complaint_type_id;
					$loc =explode(',',$location1);
					$pro =explode(',',$product1);
					$brnd =explode(',',$brand1);
					//$catId =explode(',',$complaint_type_id);
					$date = date('Y-m-d');
					//$data['complaintTypeData']= DB::table('mstr_complaint')->select('id','complaint_type')->where('id','in',$pro)->get();
					//$data['vehicleData']= DB::table('mstr_vehicle')->select('id','vehicle')->where('id','in',$catId)->get();
					$data['complaintTypeData']= DB::select("select id,complaint_type from mstr_complaint where id in ($complaint_type_id)" );
					$data['vehicleData']= DB::select("select id,vehicle from mstr_vehicle where id in ($product1)" );
					
					
					return view('dealer_summary_report',$data);
				}
				
			
				
		}catch (\Exception $ex) {			
			$notification = array(
	                'message' => $ex->getMessage(),
	                'alert-type' => 'error'
	            );
            return back()->with($notification);
        }
			
	}
	public function storeDealerSummaryReport(Request $request){
		try {
			$datefrom = $request->input('datefrom');
			$dateto = $request->input('dateto');
			
			$product = $request->input('product');
			$segment = $request->input('segment');
			$complaintType = $request->input('complaintType');
			
			$results = array();
			$productArray=$segmentArray=$complaintTypeArray='';
			if ($product !== NULL)
				foreach ($product as $row){ $productArray .= $row.','; } $product = rtrim($productArray,',');
			if ($segment !== NULL)
				foreach ($segment as $row){ $segmentArray .= $row.','; } $segment = rtrim($segmentArray,',');
			if ($complaintType !== NULL)
				foreach ($complaintType as $row){ $complaintTypeArray .= $row.',';    } $complaintType = rtrim($complaintTypeArray,',');		

       $segValArr =  DB::select("select segment from product_segment where id in ($segment)");
       $proValArr =  DB::select("select vehicle from mstr_vehicle where id in ($product)");
       $compValArr =  DB::select("select complaint_type from mstr_complaint where id in ($complaintType)");

       $segval=$proval=$compltType = '';
       foreach($segValArr as $row){
           $segval .= $row->segment.',';
       }
       $segmentVal = rtrim($segval,',');

       foreach($proValArr as $row){
           $proval .= $row->vehicle.',';
       }
       $productVal = rtrim($proval,',');
       foreach($compValArr as $row){
           $compltType .= $row->complaint_type.',';
       }
       $compVal = rtrim($compltType,',');

       $data['segmentVal'] = $segmentVal;
       $data['productVal'] = $productVal;
       $data['compVal'] = $compVal;

    if(Auth::user()->role == '29' || Auth::user()->role == '30' || Auth::user()->user_type_id =='2'){
		
        $data['dealerSummaryReport'] = DB::select("select 
        a.id as dealerid,a.region,a.dealer_name,a.complaint_Count as 'Complaint_Count',
		ROUND((ifnull(b.Total_3, 0)/ a.complaint_Count)* 100)  as 'ACK_SLA_PERCENTAGE',
		ifnull(b.Total_3, 0)  as 'TOTAL_ACK_SLA',
		ROUND((ifnull(c.Total_Closed_3, 0)/ a.complaint_Count)* 100) as 'Closed_WithinSLA_PERCENTAGE',
		ifnull(c.Total_Closed_3, 0) as 'TOTAL_Closed_WithinSLA',Total_Closed_3, Total_ClosedO_3,
        ROUND((ifnull(d.Total_ClosedO_3, 0)/ a.complaint_Count)* 100) as 'Closed_OutSideSLA_PERCENTAGE',
		ifnull(d.Total_ClosedO_3, 0) as 'TOTAL_Closed_OutSideSLA',
		ifnull(e.Total_Rating_Count, 0) as 'Rating_Count',
		ROUND((ifnull(f.Total_Open_3, 0)/ a.complaint_Count)* 100) as 'Open_WithinSLA_PERCENTAGE',
		ifnull(f.Total_Open_3, 0) as 'TOTAL_Open_WithinSLA',
		ifnull(f.Total_Open_3,0) as 'Open_WithinSLA', 
        ifnull(g.Total_OpenO_3,0) as 'Open_OutSideSLA',
		ROUND((ifnull(g.Total_OpenO_3, 0)/ a.complaint_Count)* 100) as 'Open_OutSideSLA_PERCENTAGE', 
        ifnull(g.Total_OpenO_3, 0) as 'TOTAL_Open_OutSideSLA',
		ROUND((ifnull(h.Total_Reopen_3, 0)/ a.complaint_Count)* 100) as 'ReOpen_PERCENTAGE' ,
        ifnull(h.Total_Reopen_3, 0) as 'TOTAL_ReOpen' ,
		ROUND((ifnull(tcs.totalCompletedSurvey, 0)/ e.Total_Rating_Count)* 100) as 'pcs_score',
		ifnull(tcs.totalCompletedSurvey, 0) as totalCompletedSurvey,
		ifnull(e.Total_Rating_Count, 0) as Total_Rating_Count
    from 
      (
        (
          select r.id,region,dealer_name,count(*) 'complaint_Count' 
          from 
            cases c join mstr_region r on c.zone = r.id 
            join mstr_dealer d on c.dealer = d.id and (d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office')
          where 
            c.zone = 1 and cast(c.created_at as date) between cast('$datefrom' as date) and cast('$dateto' as date) and c.product in ($product) 
            and c.segment in ($segment) and c.complaint_category in ($complaintType ) and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped') group by c.zone,c.dealer order by complaint_Count desc           
        ) 
        UNION ALL 
          (
            select r.id,region,dealer_name,count(*) 'complaint_Count' 
			from 
              cases c join mstr_region r on c.zone = r.id join mstr_dealer d on c.dealer = d.id and (d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office')
            where 
              c.zone = 2 and cast(c.created_at as date) between cast('$datefrom' as date) and cast('$dateto' as date) and c.product in ($product) 
            and c.segment in ($segment) and c.complaint_category in ($complaintType ) and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped') group by c.zone,c.dealer order by complaint_Count desc             
          ) 
        UNION ALL 
          (
            select r.id,region,dealer_name,count(*) 'complaint_Count'  
            from 
              cases c join mstr_region r on c.zone = r.id join mstr_dealer d on c.dealer = d.id and (d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office')
            where 
              c.zone = 3 and cast(c.created_at as date) between cast('$datefrom' as date) and cast('$dateto' as date) and c.product in ($product) 
            and c.segment in ($segment) and c.complaint_category in ($complaintType ) and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped') group by c.zone, c.dealer order by complaint_Count desc            
          ) 
        UNION ALL 
          (
            select r.id,region,dealer_name,count(*) 'complaint_Count'   
            from 
              cases c join mstr_region r on c.zone = r.id join mstr_dealer d on c.dealer = d.id and (d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office')
            where 
              c.zone = 4 and cast(c.created_at as date) between cast('$datefrom' as date) and cast('$dateto' as date) and c.product in ($product) 
            and c.segment in ($segment) and c.complaint_category in ($complaintType )  and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped') group by c.zone,c.dealer order by complaint_Count desc            
          ) 
        UNION ALL 
          (
            select r.id,region,dealer_name,count(*) 'complaint_Count'  
            from cases c join mstr_region r on c.zone = r.id join mstr_dealer d on c.dealer = d.id and (d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office')
            where 
              c.zone = 5 and cast(c.created_at as date) between cast('$datefrom' as date) and cast('$dateto' as date) and c.product in ($product) 
            and c.segment in ($segment) and c.complaint_category in ($complaintType ) and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped') group by c.zone,c.dealer order by complaint_Count desc             
          )
      ) a 
    left join(
        (
          select region,dealer_name,count(*) 'totalCompletedCases' 
          from 
            cases c join mstr_region r on c.zone = r.id join mstr_dealer d on c.dealer = d.id and (d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office')
          where 
            c.zone = 1 and (c.case_status='Completed' or c.case_status='Closed') and cast(c.created_at as date) between cast('$datefrom' as date) 
            and cast('$dateto' as date) and c.product in ($product) and c.segment in ($segment) and c.complaint_category in ($complaintType ) and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped') group by c.zone,c.dealer order by 
            totalCompletedCases desc           
        ) 
        UNION ALL 
        (
            select region,dealer_name,count(*) 'totalCompletedCases' 
            from 
              cases c join mstr_region r on c.zone = r.id join mstr_dealer d on c.dealer = d.id and (d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office')
            where 
              c.zone = 2 and (c.case_status='Completed' or c.case_status='Closed') and cast(c.created_at as date) between cast('$datefrom' as date) 
            and cast('$dateto' as date) and c.product in ($product) and c.segment in ($segment) and c.complaint_category in ($complaintType ) and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped') group by c.zone, c.dealer order by 
            totalCompletedCases desc
        ) 
        UNION ALL 
        (
            select region,dealer_name,count(*) 'totalCompletedCases' 
            from 
              cases c join mstr_region r on c.zone = r.id join mstr_dealer d on c.dealer = d.id and (d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office')
            where 
            c.zone = 3 and (c.case_status='Completed' or c.case_status='Closed') and cast(c.created_at as date) between cast('$datefrom' as date) 
            and cast('$dateto' as date) and c.product in ($product) and c.segment in ($segment) and c.complaint_category in ($complaintType ) and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped') group by c.zone, c.dealer order by 
            totalCompletedCases desc            
        ) 
        UNION ALL 
        (
            select region,dealer_name,count(*) 'totalCompletedCases' 
            from 
              cases c join mstr_region r on c.zone = r.id join mstr_dealer d on c.dealer = d.id and (d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office')
            where 
            c.zone = 4 and (c.case_status='Completed' or c.case_status='Closed') and cast(c.created_at as date) between cast('$datefrom' as date) 
            and cast('$dateto' as date) and c.product in ($product) and c.segment in ($segment) and c.complaint_category in ($complaintType )  and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped') group by c.zone, c.dealer order by 
            totalCompletedCases desc 
		) 
        UNION ALL 
        (
            select region,dealer_name,count(*) 'totalCompletedCases' 
            from 
              cases c join mstr_region r on c.zone = r.id join mstr_dealer d on c.dealer = d.id and (d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office')
            where 
            c.zone = 5 and (c.case_status='Completed' or c.case_status='Closed') and cast(c.created_at as date) between cast('$datefrom' as date) 
            and cast('$dateto' as date) and c.product in ($product) and c.segment in ($segment) and c.complaint_category in ($complaintType ) and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped') group by c.zone, c.dealer order by 
            totalCompletedCases desc 
           
        )
    ) tcc on a.region = tcc.region and a.dealer_name = tcc.dealer_name
    left join(
        (
          select region,dealer_name,count(*) 'totalCompletedSurvey' 
          from 
            cases c join mstr_region r on c.zone = r.id join mstr_dealer d on c.dealer = d.id and (d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office')
          where 
            c.zone = 1 and (c.case_status='Closed' or c.case_status='Completed') and (c.rating='9' or c.rating='10') and cast(c.created_at as date) between cast('$datefrom' as date) 
            and cast('$dateto' as date) and c.product in ($product) and c.segment in ($segment) and c.complaint_category in ($complaintType ) and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped') group by c.zone, c.dealer order by 
            totalCompletedSurvey desc           
        ) 
        UNION ALL 
        (
            select 
              region,dealer_name,count(*) 'totalCompletedSurvey' 
            from 
              cases c join mstr_region r on c.zone = r.id join mstr_dealer d on c.dealer = d.id and (d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office')
            where 
            c.zone = 2 and (c.case_status='Closed' or c.case_status='Completed') and (c.rating='9' or c.rating='10') and cast(c.created_at as date) between cast('$datefrom' as date) 
            and cast('$dateto' as date) and c.product in ($product) and c.segment in ($segment) and c.complaint_category in ($complaintType ) and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped') group by c.zone, c.dealer order by 
            totalCompletedSurvey desc
        ) 
        UNION ALL 
          (
            select region,dealer_name,count(*) 'totalCompletedSurvey' 
            from 
              cases c join mstr_region r on c.zone = r.id join mstr_dealer d on c.dealer = d.id and (d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office')
            where 
            c.zone = 3 and (c.case_status='Closed' or c.case_status='Completed') and (c.rating='9' or c.rating='10') and cast(c.created_at as date) between cast('$datefrom' as date) 
            and cast('$dateto' as date) and c.product in ($product) and c.segment in ($segment) and c.complaint_category in ($complaintType ) and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped') group by c.zone, c.dealer order by 
            totalCompletedSurvey desc 
          ) 
        UNION ALL 
        (
            select region,dealer_name,count(*) 'totalCompletedSurvey' 
			from 
			cases c join mstr_region r on c.zone = r.id join mstr_dealer d on c.dealer = d.id and (d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office') 
			where c.zone = 4 and (c.case_status='Closed' or c.case_status='Completed') and (c.rating='9' or c.rating='10') and cast(c.created_at as date) between cast('$datefrom' as date) and cast('$dateto' as date) and c.product in ($product) and c.segment in ($segment) and c.complaint_category in ($complaintType) and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped') group by c.zone, c.dealer order by totalCompletedSurvey desc            
        ) 
        UNION ALL 
        (
            select region,dealer_name,count(*) 'totalCompletedSurvey' 
			from 
			cases c join mstr_region r on c.zone = r.id join mstr_dealer d on c.dealer = d.id and(d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office') 
			where c.zone = 5 and (c.case_status='Closed' or c.case_status='Completed') and (c.rating='9' or c.rating='10') and cast(c.created_at as date) between cast('$datefrom' as date) and cast('$dateto' as date) and c.product in ($product) and c.segment in ($segment) and c.complaint_category in ($complaintType) and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped') group by c.zone, c.dealer order by totalCompletedSurvey desc            
        )
    ) tcs on a.region = tcs.region and a.dealer_name = tcs.dealer_name
    left join 
	( 
		select region, dealer_name, count(*) as Total_3 
		from
		( 
			select region, dealer_name, complaint_number, case when Total_1 >= 1 then 1 ELSE 0 END as 'Total_2' 
			from 
			( 
				SELECT region, dealer_name, r.complaint_number, sum( CASE WHEN ( c.complaint_category = 1 and TIMESTAMPDIFF(HOUR, c.created_at, r.created_at)<=24) THEN 1 WHEN ( c.complaint_category = 2 and TIMESTAMPDIFF(HOUR, c.created_at, r.created_at)<=24 ) THEN 1 WHEN ( c.complaint_category = 3 and TIMESTAMPDIFF(HOUR, c.created_at, r.created_at)<=24 ) THEN 1 WHEN ( c.complaint_category = 4 and TIMESTAMPDIFF(HOUR, c.created_at, r.created_at)<=24 ) THEN 1 ELSE 0 END ) AS Total_1 
				FROM 
				remarks r left join cases c on c.complaint_number = r.complaint_number left join mstr_region r1 on c.zone = r1.id left join mstr_dealer d on c.dealer = d.id and (d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office') left join mstr_complaint cmplnt on cmplnt.id = c.complaint_category 
				where r.id in (select max(id) from remarks where case_status = 'Acknowledged' group by complaint_number) and cast(c.created_at as date) between cast('$datefrom' as date) and cast('$dateto' as date) and c.product in ($product) and c.segment in ($segment) and c.complaint_category in ($complaintType ) and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped') group by region, dealer_name, r.complaint_number 
			) a1 group by region, dealer_name, complaint_number 
		) b1 where Total_2 <> 0 group by region, dealer_name
    ) b on a.region = b.region and a.dealer_name = b.dealer_name 
    left join (
        select region, dealer_name, count(*) as Total_Closed_3 
        from 
        (
            select region, dealer_name, complaint_number, case when Total_Closed_1 >= 1 then 1 ELSE 0 END as 'Total_Closed_2' 
            from 
            ( 
				SELECT region, dealer_name, r.complaint_number, 
				sum( CASE WHEN( c.complaint_category = 1 and ceiling((TIME_TO_SEC(timeDIFF(rem2.created_at, c.created_at))/3600)/24) <= 5) THEN 1 
				WHEN ( c.complaint_category = 2 and ceiling((TIME_TO_SEC(timeDIFF(rem2.created_at, c.created_at))/3600)/24) <= 5 ) THEN 1 
				WHEN ( c.complaint_category = 3 and ceiling((TIME_TO_SEC(timeDIFF(rem2.created_at, c.created_at))/3600)/24) <= 5 ) THEN 1 
				WHEN ( c.complaint_category = 4 and ceiling((TIME_TO_SEC(timeDIFF(rem2.created_at, c.created_at))/3600)/24) <= 30 ) THEN 1 ELSE 0 END 
				) AS Total_Closed_1 
				FROM 
				remarks r left join cases c on c.complaint_number = r.complaint_number left join mstr_region r1 on c.zone = r1.id left join (select complaint_number,created_at from remarks where id in (select max(id) from remarks where case_status = 'Completed' group by complaint_number)) as rem2 on rem2.complaint_number=c.complaint_number left join mstr_dealer d on c.dealer = d.id and (d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office') left join mstr_complaint cmplnt on cmplnt.id = c.complaint_category where c.case_status in ('Completed','Closed') and cast(c.created_at as date) between cast('$datefrom' as date) and cast('$dateto' as date) and c.product in ($product) and c.segment in ($segment) and c.complaint_category in ($complaintType ) and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped') group by region, dealer_name, r.complaint_number
            ) a2 group by region, dealer_name, complaint_number
		) b2 
        where 
		Total_Closed_2 <> 0 group by region, dealer_name
	) c on a.region = c.region and a.dealer_name = c.dealer_name 
    left join (
        select region, dealer_name, count(*) as Total_ClosedO_3 
        from 
        (
            select region, dealer_name, complaint_number, case when Total_ClosedO_1 >= 1 then 1 ELSE 0 END as 'Total_ClosedO_2' 
            from 
            (
                SELECT region, dealer_name, r.complaint_number, 
                  sum(
                    CASE WHEN (
                      c.complaint_category = 1 
                      and ceiling((TIME_TO_SEC(timeDIFF(rem2.created_at, c.created_at))/3600)/24) > 5 
                    ) THEN 1 WHEN (
                      c.complaint_category = 2 
                      and ceiling((TIME_TO_SEC(timeDIFF(rem2.created_at, c.created_at))/3600)/24) > 5 
                    ) THEN 1 WHEN (
                      c.complaint_category = 3 
                      and ceiling((TIME_TO_SEC(timeDIFF(rem2.created_at, c.created_at))/3600)/24) > 5
                    ) THEN 1 WHEN (
                      c.complaint_category = 4 
                      and ceiling((TIME_TO_SEC(timeDIFF(rem2.created_at, c.created_at))/3600)/24) > 30 
                    ) THEN 1 ELSE 0 END
                  ) AS Total_ClosedO_1 
                FROM 
                  remarks r 
                  left join cases c on c.complaint_number = r.complaint_number 
                  left join mstr_region r1 on c.zone = r1.id 
                  left join (select complaint_number,created_at from  remarks where id in (select max(id) from  remarks where case_status = 'Completed' group by complaint_number)) as rem2 on rem2.complaint_number=c.complaint_number left join mstr_dealer d on c.dealer = d.id and (d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office') left join mstr_complaint cmplnt on cmplnt.id = c.complaint_category 
                where 
                  c.case_status in('Completed','Closed') and cast(c.created_at as date) between cast('$datefrom' as date) and cast('$dateto' as date) and c.product in ($product) and c.segment in ($segment) and c.complaint_category in ($complaintType) and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped') group by region, dealer_name, r.complaint_number
			) a3 group by region, dealer_name, complaint_number
        ) b3 
		where 
		Total_ClosedO_2 <> 0 group by region, dealer_name
	) d on a.region = d.region and a.dealer_name = d.dealer_name 
    left join (
        select region, dealer_name, sum(Rating_Count) as 'Total_Rating_Count' 
        from 
        (
            select region, dealer_name, Rating, count(*) as Rating_Count 
            from 
            cases c join mstr_region r on c.zone = r.id join mstr_dealer d on c.dealer = d.id and(d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office')
            where 
			Rating <> '' and (c.case_status='Closed' or c.case_status='Completed') and cast(c.created_at as date) between cast('$datefrom' as date) and cast('$dateto' as date) and c.product in($product) and c.segment in ($segment) and c.complaint_category in ($complaintType) and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped') group by c.zone, c.dealer, c.Rating
		) a4 group by region, dealer_name
	) e on a.region = e.region and a.dealer_name = e.dealer_name 
	left join (
        select region, dealer_name, count(*) as Total_Open_3 
        from 
          (
            select region, dealer_name, complaint_number, case when Total_Open_1 >= 1 then 1 ELSE 0 END as 'Total_Open_2' 
            from 
            (
                SELECT region, dealer_name, c.complaint_number, 
				sum(
                    CASE WHEN (
                      c.complaint_category = 1 
                      and ceiling((TIME_TO_SEC(timeDIFF(now(), c.created_at))/3600)/24) <= 5
                    ) THEN 1 WHEN (
                      c.complaint_category = 2 
                      and ceiling((TIME_TO_SEC(timeDIFF(now(), c.created_at))/3600)/24) <= 5
                    ) THEN 1 WHEN (
                      c.complaint_category = 3 
                      and ceiling((TIME_TO_SEC(timeDIFF(now(), c.created_at))/3600)/24) <= 5
                    ) THEN 1 WHEN (
                      c.complaint_category = 4 
                      and ceiling((TIME_TO_SEC(timeDIFF(now(), c.created_at))/3600)/24) <= 30
                    ) THEN 1 ELSE 0 END
				) AS Total_Open_1 
                FROM 
                cases c left join mstr_region r1 on c.zone = r1.id left join mstr_dealer d on c.dealer = d.id and(d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office') left join mstr_complaint cmplnt on cmplnt.id = c.complaint_category 
                where 
				c.case_status in( 'Open', 'Assigned', 'Acknowledged', 'Waiting for Customer', 'InProgress', 'ReAssigned', 'tobedropped') and cast(c.created_at as date) between cast('$datefrom' as date) and cast('$dateto' as date) and c.product in ($product) and c.segment in ($segment) and c.complaint_category in ($complaintType ) and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped') group by region, dealer_name, c.complaint_number
              ) a4 group by region, dealer_name, complaint_number
        ) b4 
        where 
        Total_Open_2 <> 0 group by region, dealer_name
	) f on a.region = f.region and a.dealer_name = f.dealer_name 
	left join (
        select region, dealer_name, count(*) as Total_OpenO_3 
        from 
        (
            select region, dealer_name, complaint_number, case when Total_OpenO_1 >= 1 then 1 ELSE 0 END as 'Total_OpenO_2' 
            from 
            (
                SELECT region, dealer_name, c.complaint_number, 
                sum(
                    CASE WHEN (
                      c.complaint_category = 1 
                      and ceiling((TIME_TO_SEC(timeDIFF(now(), c.created_at))/3600)/24) > 5
                    ) THEN 1 WHEN (
                      c.complaint_category = 2 
                      and ceiling((TIME_TO_SEC(timeDIFF(now(), c.created_at))/3600)/24) > 5
                    ) THEN 1 WHEN (
                      c.complaint_category = 3 
                      and ceiling((TIME_TO_SEC(timeDIFF(now(), c.created_at))/3600)/24) > 5
                    ) THEN 1 WHEN (
                      c.complaint_category = 4 
                      and ceiling((TIME_TO_SEC(timeDIFF(now(), c.created_at))/3600)/24) > 30
                    ) THEN 1 ELSE 0 END
                ) AS Total_OpenO_1 
                FROM 
                cases c left join mstr_region r1 on c.zone = r1.id left join mstr_dealer d on c.dealer = d.id and(d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office') left join mstr_complaint cmplnt on cmplnt.id = c.complaint_category 
                where 
					c.case_status in( 'Open', 'Assigned', 'Acknowledged', 'Waiting for Customer', 'InProgress', 'ReAssigned', 'tobedropped') and cast(c.created_at as date) between cast('$datefrom' as date) and cast('$dateto' as date) and c.product in($product) and c.segment in ($segment) and c.complaint_category in ($complaintType) and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped') group by region, dealer_name, c.complaint_number
			) a5 
            group by region, dealer_name, complaint_number
          ) b5 
        where 
          Total_OpenO_2 <> 0 group by region, dealer_name
	) g on a.region = g.region and a.dealer_name = g.dealer_name 
	left join( 
		select region, dealer_name, count(*) as Total_Reopen_3 
			from( 
				select region, dealer_name, complaint_number, case when Total_ReOpen_1 >= 1 then 1 ELSE 0 END as 'Total_ReOpen_2' 
				from( 
					SELECT region, dealer_name, c.complaint_number, count(*) AS Total_ReOpen_1 
					FROM 
					cases c left join mstr_region r1 on c.zone = r1.id left join mstr_dealer d on c.dealer = d.id and (d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office') left join mstr_complaint cmplnt on cmplnt.id = c.complaint_category 
					where 
					c.case_status in ('reopen') and cast(c.created_at as date) between cast('$datefrom' as date) and cast('$dateto' as date) and c.product in ($product) and c.segment in ($segment) and c.complaint_category in ($complaintType) and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped') group by region, dealer_name, c.complaint_number 
				) a6 group by region, dealer_name, complaint_number 
			) b6 group by region, dealer_name
    ) h on a.region = h.region and a.dealer_name = h.dealer_name order by a.region asc");
	
	
	  

                $data['product'] = $product;
                $data['segment'] = $segment;
                $data['complaintType'] = $complaintType;
                $data['datefrom'] = $datefrom;
                $data['dateto'] = $dateto;

                $date = date('Y-m-d');
                $data['complaintTypeData']= DB::table('mstr_complaint')->select('id','complaint_type')->get();
                $data['vehicleData']= DB::table('mstr_vehicle')->select('id','vehicle')->get();
                return view('dealer_summary_report',$data);

    }
else{
	$location1 = Auth::user()->city;
	$product1 = Auth::user()->product;
	$brand1 = Auth::user()->brand;
	$complaint_type_id = Auth::user()->complaint_type_id;
	$complaintType = $complaint_type_id;
	
	$data['dealerSummaryReport'] = DB::select("select 
	a.id as dealerid,
	a.region, 
	a.dealer_name, 
	a.complaint_Count as 'Complaint_Count', 

	ROUND((ifnull(b.Total_3, 0)/ a.complaint_Count)* 100)  as 'ACK_SLA_PERCENTAGE', 
	ifnull(b.Total_3, 0)  as 'TOTAL_ACK_SLA',
	ROUND((ifnull(c.Total_Closed_3, 0)/ a.complaint_Count)* 100) as 'Closed_WithinSLA_PERCENTAGE', 
	Total_Closed_3, Total_ClosedO_3,
	ROUND((ifnull(d.Total_ClosedO_3, 0)/ a.complaint_Count)* 100) as 'Closed_OutSideSLA_PERCENTAGE', 
	ifnull(e.Total_Rating_Count, 0) as 'Rating_Count', 
	ifnull(d.Total_ClosedO_3, 0) as 'TOTAL_Closed_OutSideSLA',
	ifnull(e.Total_Rating_Count, 0) as 'Rating_Count',
	ROUND((ifnull(f.Total_Open_3, 0)/ a.complaint_Count)* 100) as 'Open_WithinSLA_PERCENTAGE', 
	ifnull(f.Total_Open_3,0) as 'Open_WithinSLA',
	ifnull(g.Total_OpenO_3,0) as 'Open_OutSideSLA',
	ROUND((ifnull(g.Total_OpenO_3, 0)/ a.complaint_Count)* 100) as 'Open_OutSideSLA_PERCENTAGE', 
	ROUND((ifnull(h.Total_Reopen_3, 0)/ a.complaint_Count)* 100) as 'ReOpen_PERCENTAGE' ,
	ifnull(h.Total_Reopen_3, 0) as 'TOTAL_ReOpen' ,
	ROUND((ifnull(tcs.totalCompletedSurvey, 0)/ tcc.totalCompletedCases)* 100) as 'pcs_score',
	ifnull(tcs.totalCompletedSurvey, 0) as totalCompletedSurvey,
	ifnull(e.Total_Rating_Count, 0) as Total_Rating_Count
from 
  (
    (
      select r.id, region, dealer_name, count(*) 'complaint_Count' 
	  from 
	  cases c join mstr_region r on c.zone = r.id join mstr_dealer d on c.dealer = d.id and(d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office') where 
	  c.location in ($location1) and c.zone = 1 and cast(c.created_at as date) between cast('$datefrom' as date) and cast('$dateto' as date) and c.product in ($product1) and c.segment in ($segment) and c.brand in ($brand1) and c.complaint_category in ($complaintType) and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped') group by c.zone, c.dealer order by complaint_Count desc      
    ) 
    UNION ALL 
	(
        select r.id, region, dealer_name, count(*) 'complaint_Count' 
		from 
		cases c join mstr_region r on c.zone = r.id join mstr_dealer d on c.dealer = d.id and(d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office') 
		where 
		c.location in ($location1) and c.zone = 2 and cast(c.created_at as date) between cast('$datefrom' as date) and cast('$dateto' as date) and c.product in ($product1) and c.segment in ($segment) and c.brand in ($brand1) and c.complaint_category in ($complaintType) and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped') group by c.zone, c.dealer order by complaint_Count desc         
	) 
    UNION ALL 
	(
        select r.id, region, dealer_name, count(*) 'complaint_Count' 
		from 
		cases c join mstr_region r on c.zone = r.id join mstr_dealer d on c.dealer = d.id and(d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office') 
		where 
		c.location in ($location1) and c.zone = 3 and cast(c.created_at as date) between cast('$datefrom' as date) and cast('$dateto' as date) and c.product in ($product1) and c.segment in ($segment) and c.brand in ($brand1) and c.complaint_category in ($complaintType) and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped') group by c.zone, c.dealer order by complaint_Count desc         
	) 
    UNION ALL 
	(
        select r.id, region, dealer_name, count(*) 'complaint_Count' 
		from 
		cases c join mstr_region r on c.zone = r.id join mstr_dealer d on c.dealer = d.id and(d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office') 
		where 
		c.location in ($location1) and c.zone = 4 and cast(c.created_at as date) between cast('$datefrom' as date) and cast('$dateto' as date) and c.product in ($product1) and c.segment in ($segment) and c.brand in ($brand1) and c.complaint_category in ($complaintType) and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped') group by c.zone, c.dealer order by complaint_Count desc 
	) 
    UNION ALL 
	(
        select r.id, region, dealer_name, count(*) 'complaint_Count' 
		from 
		cases c join mstr_region r on c.zone = r.id join mstr_dealer d on c.dealer = d.id and(d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office') 
		where 
		c.location in ($location1) and c.zone = 5 and cast(c.created_at as date) between cast('$datefrom' as date) and cast('$dateto' as date) and c.product in ($product1) and c.segment in ($segment) and c.brand in ($brand1) and c.complaint_category in ($complaintType) and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped') group by c.zone, c.dealer order by complaint_Count desc 
	)
) a 
  left join  (
	(
      select region,dealer_name,count(*) 'totalCompletedCases' 
	  from 
	  cases c join mstr_region r on c.zone = r.id join mstr_dealer d on c.dealer = d.id and(d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office') where 
	  c.zone = 1 and (c.case_status='Completed' or c.case_status='Closed') and c.location in ($location1) and c.brand in ($brand1) and cast(c.created_at as date) between cast('$datefrom' as date) and cast('$dateto' as date) and c.product in ($product1) and c.segment in ($segment) and c.complaint_category in ($complaintType) and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped') group by c.zone, c.dealer order by totalCompletedCases desc 
    ) 
    UNION ALL 
      (
        select region,dealer_name,count(*) 'totalCompletedCases' 
		from 
		cases c join mstr_region r on c.zone = r.id join mstr_dealer d on c.dealer = d.id and(d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office') 
		where c.zone = 2 and (c.case_status='Completed' or c.case_status='Closed') and c.location in ($location1) and c.brand in ($brand1) and cast(c.created_at as date) between cast('$datefrom' as date) and cast('$dateto' as date) and c.product in ($product1) and c.segment in ($segment) and c.complaint_category in ($complaintType) and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped') group by c.zone, c.dealer order by totalCompletedCases desc 
      ) 
    UNION ALL 
      (
        select region,dealer_name,count(*) 'totalCompletedCases' 
		from 
		cases c join mstr_region r on c.zone = r.id join mstr_dealer d on c.dealer = d.id and(d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office') 
		where c.zone = 3 and (c.case_status='Completed' or c.case_status='Closed') and c.location in ($location1) and c.brand in ($brand1) and cast(
		c.created_at as date) between cast('$datefrom' as date) and cast('$dateto' as date) and c.product in ($product1) and c.segment in ($segment) and c.complaint_category in ($complaintType) and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped') group by c.zone, c.dealer order by totalCompletedCases desc 
      ) 
    UNION ALL 
	(
        select region,dealer_name,count(*) 'totalCompletedCases' 
		from 
		cases c join mstr_region r on c.zone = r.id join mstr_dealer d on c.dealer = d.id and(d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office') 
		where c.zone = 4 and (c.case_status='Completed' or c.case_status='Closed') and c.location in ($location1) and c.brand in ($brand1) and cast(c.created_at as date) between cast('$datefrom' as date) and cast('$dateto' as date) and c.product in ($product1) and c.segment in ($segment) and c.complaint_category in ($complaintType) and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped') group by c.zone, c.dealer order by totalCompletedCases desc 
	) 
    UNION ALL 
	(
        select region,dealer_name,count(*) 'totalCompletedCases' 
		from 
		cases c join mstr_region r on c.zone = r.id join mstr_dealer d on c.dealer = d.id and(d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office') 
		where 
		c.zone = 5 and (c.case_status='Completed' or c.case_status='Closed') and c.location in ($location1) and c.brand in ($brand1) and cast(c.created_at as date) between cast('$datefrom' as date) and cast('$dateto' as date) and c.product in ($product1) and c.segment in ($segment) and c.complaint_category in ($complaintType) and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped') group by c.zone, c.dealer order by totalCompletedCases desc 
       
	)
  ) tcc on a.region = tcc.region and a.dealer_name = tcc.dealer_name
  left join  (
	(
      select region,dealer_name,count(*) 'totalCompletedSurvey' 
      from 
        cases c 
        join mstr_region r on c.zone = r.id 
        join mstr_dealer d on c.dealer = d.id and (d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office')
      where 
        c.zone = 1 
		and (c.case_status='Completed' or c.case_status='Closed') and (c.rating='9' or c.rating='10') and c.location in ($location1) and c.brand in ($brand1)
        and cast(c.created_at as date) between cast('$datefrom' as date) 
        and cast('$dateto' as date) 
        and c.product in ($product1) 
        and c.segment in ($segment) 
        and c.complaint_category in ($complaintType ) and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped')
      group by 
        c.zone, 
        c.dealer 
        order by 
          totalCompletedSurvey desc 
      
    ) 
    UNION ALL 
      (
        select 
          region,dealer_name,count(*) 'totalCompletedSurvey' 
        from 
          cases c 
          join mstr_region r on c.zone = r.id 
          join mstr_dealer d on c.dealer = d.id and (d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office')
        where 
          c.zone = 2 
		 and (c.case_status='Completed' or c.case_status='Closed') and (c.rating='9' or c.rating='10') and c.location in ($location1) and c.brand in ($brand1)
          and cast(c.created_at as date) between cast('$datefrom' as date) 
        and cast('$dateto' as date) 
        and c.product in ($product1) 
        and c.segment in ($segment) 
        and c.complaint_category in ($complaintType ) and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped')
        group by 
          c.zone, 
          c.dealer 
        order by 
          totalCompletedSurvey desc 
        
      ) 
    UNION ALL 
      (
        select 
          region,dealer_name,count(*) 'totalCompletedSurvey' 
        from 
          cases c 
          join mstr_region r on c.zone = r.id 
          join mstr_dealer d on c.dealer = d.id and (d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office')
        where 
          c.zone = 3 
		  and (c.case_status='Completed' or c.case_status='Closed') and (c.rating='9' or c.rating='10') and c.location in ($location1) and c.brand in ($brand1)
          and cast(c.created_at as date) between cast('$datefrom' as date) 
        and cast('$dateto' as date) 
        and c.product in ($product1) 
        and c.segment in ($segment) 
        and c.complaint_category in ($complaintType ) and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped')
        group by 
          c.zone, 
          c.dealer 
         order by 
          totalCompletedSurvey desc 
        
      ) 
    UNION ALL 
      (
        select 
          region,dealer_name,count(*) 'totalCompletedSurvey' 
        from 
          cases c 
          join mstr_region r on c.zone = r.id 
          join mstr_dealer d on c.dealer = d.id and (d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office')
        where 
          c.zone = 4 
		  and (c.case_status='Completed' or c.case_status='Closed') and (c.rating='9' or c.rating='10') and c.location in ($location1) and c.brand in ($brand1)
          and cast(c.created_at as date) between cast('$datefrom' as date) 
        and cast('$dateto' as date) 
        and c.product in ($product1) 
        and c.segment in ($segment) 
        and c.complaint_category in ($complaintType )  and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped')
        group by 
          c.zone, 
          c.dealer 
        order by 
          totalCompletedSurvey desc 
       
      ) 
    UNION ALL 
      (
        select 
          region,dealer_name,count(*) 'totalCompletedSurvey' 
        from 
          cases c 
          join mstr_region r on c.zone = r.id 
          join mstr_dealer d on c.dealer = d.id and (d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office')
        where 
          c.zone = 5 
		  and (c.case_status='Completed' or c.case_status='Closed') and (c.rating='9' or c.rating='10') and c.location in ($location1) and c.brand in ($brand1)
          and cast(c.created_at as date) between cast('$datefrom' as date) 
        and cast('$dateto' as date) 
        and c.product in ($product1) 
        and c.segment in ($segment) 
        and c.complaint_category in ($complaintType ) and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped')
        group by 
          c.zone, 
          c.dealer 
        order by 
          totalCompletedSurvey desc 
       
      )
  ) tcs on a.region = tcs.region and a.dealer_name = tcs.dealer_name
  
  
  left join (
    select 
      region, 
      dealer_name, 
      count(*) as Total_3 
    from 
      (
        select 
          region, 
          dealer_name, 
          complaint_number, 
          case when Total_1 >= 1 then 1 ELSE 0 END as 'Total_2' 
        from 
          (
            SELECT 
              region, 
              dealer_name, 
              r.complaint_number, 
              sum(
                CASE WHEN (
                  c.complaint_category = 1 
                      and TIMESTAMPDIFF(HOUR, c.created_at, r.created_at)<=24
                ) THEN 1 WHEN (
                  c.complaint_category = 2 
                      and TIMESTAMPDIFF(HOUR, c.created_at, r.created_at)<=24
                ) THEN 1 WHEN (
                  c.complaint_category = 3 
                      and TIMESTAMPDIFF(HOUR, c.created_at, r.created_at)<=24
                ) THEN 1 WHEN (
                  c.complaint_category = 4 
                      and TIMESTAMPDIFF(HOUR, c.created_at, r.created_at)<=24
                ) THEN 1 ELSE 0 END
              ) AS Total_1 
            FROM 
              remarks r 
              left join cases c on c.complaint_number = r.complaint_number 
              left join mstr_region r1 on c.zone = r1.id 
              left join mstr_dealer d on c.dealer = d.id and (d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office')
              left join mstr_complaint cmplnt on cmplnt.id = c.complaint_category 
            where c.location in ($location1) and  
              r.id in (select max(id) from remarks where case_status = 'Acknowledged' group by complaint_number)
             and cast(c.created_at as date) between cast('$datefrom' as date) 
        and cast('$dateto' as date) 
        and c.product in ($product1) 
        and c.segment in ($segment) and c.brand in ($brand1) 
        and c.complaint_category in ($complaintType ) and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped')
            group by 
              region, 
              dealer_name, 
              r.complaint_number
          ) a1 
        group by 
          region, 
          dealer_name, 
          complaint_number
      ) b1 
    where 
      Total_2 <> 0 
      
    group by 
      region, 
      dealer_name
  ) b on a.region = b.region 
  and a.dealer_name = b.dealer_name 
  left join (
     select region, dealer_name, count(*) as Total_Closed_3 
    from 
      (
        select region, dealer_name, complaint_number, case when Total_Closed_1 >= 1 then 1 ELSE 0 END as 'Total_Closed_2' 
        from 
          (
           SELECT region, dealer_name, r.complaint_number,  
              sum(
                CASE WHEN (
                  c.complaint_category = 1 
                      and ceiling((TIME_TO_SEC(timeDIFF(rem2.created_at, c.created_at))/3600)/24) <= 5
                ) THEN 1 WHEN (
                  c.complaint_category = 2 
                      and ceiling((TIME_TO_SEC(timeDIFF(rem2.created_at, c.created_at))/3600)/24) <= 5
                ) THEN 1 WHEN (
                  c.complaint_category = 3 
                      and ceiling((TIME_TO_SEC(timeDIFF(rem2.created_at, c.created_at))/3600)/24) <= 5 
                ) THEN 1 WHEN (
                  c.complaint_category = 4 
                      and ceiling((TIME_TO_SEC(timeDIFF(rem2.created_at, c.created_at))/3600)/24) <= 30
                ) THEN 1 ELSE 0 END
              ) AS Total_Closed_1 
            FROM 
              remarks r 
              left join cases c on c.complaint_number = r.complaint_number 
              left join mstr_region r1 on c.zone = r1.id 
              left join (select complaint_number,created_at from remarks where id in (select max(id) from remarks where case_status = 'Completed' group by complaint_number)) as rem2 on rem2.complaint_number=c.complaint_number
              left join mstr_dealer d on c.dealer = d.id and (d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office')
              left join mstr_complaint cmplnt on cmplnt.id = c.complaint_category 
            where c.location in ($location1) and c.case_status in ('Completed','Closed') and cast(c.created_at as date) between cast('$datefrom' as date) 
        and cast('$dateto' as date) and c.product in ($product1) and c.segment in ($segment) and c.brand in ($brand1) and c.complaint_category in ($complaintType ) and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped')
            group by 
              region, 
              dealer_name, 
              r.complaint_number
          ) a2 
        group by 
          region, 
          dealer_name, 
          complaint_number
      ) b2 
    where 
      Total_Closed_2 <> 0 
     
    group by 
      region, 
      dealer_name
  ) c on a.region = c.region 
  and a.dealer_name = c.dealer_name 
  left join (
    select 
      region, 
      dealer_name, 
      count(*) as Total_ClosedO_3 
    from 
      (
        select 
          region, 
          dealer_name, 
          complaint_number, 
          case when Total_ClosedO_1 >= 1 then 1 ELSE 0 END as 'Total_ClosedO_2' 
        from 
          (
            SELECT 
              region, 
              dealer_name, 
              r.complaint_number, 
              sum(
                CASE WHEN (
                  c.complaint_category = 1 
                      and ceiling((TIME_TO_SEC(timeDIFF(rem2.created_at, c.created_at))/3600)/24) > 5 
                ) THEN 1 WHEN (
                  c.complaint_category = 2 
                      and ceiling((TIME_TO_SEC(timeDIFF(rem2.created_at, c.created_at))/3600)/24) > 5 
                ) THEN 1 WHEN (
                  c.complaint_category = 3 
                      and ceiling((TIME_TO_SEC(timeDIFF(rem2.created_at, c.created_at))/3600)/24) > 5
                ) THEN 1 WHEN (
                  c.complaint_category = 4 
                      and ceiling((TIME_TO_SEC(timeDIFF(rem2.created_at, c.created_at))/3600)/24) > 30 
                ) THEN 1 ELSE 0 END
              ) AS Total_ClosedO_1 
            FROM 
              remarks r 
              left join cases c on c.complaint_number = r.complaint_number 
              left join mstr_region r1 on c.zone = r1.id 
                  left join (select complaint_number,created_at from  remarks where id in (select max(id) from  remarks where case_status = 'Completed' group by complaint_number)) as rem2 on rem2.complaint_number=c.complaint_number
              left join mstr_dealer d on c.dealer = d.id and (d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office')
              left join mstr_complaint cmplnt on cmplnt.id = c.complaint_category 
            where c.location in ($location1) and 
             c.case_status in ('Completed','Closed')  
              and cast(c.created_at as date) between cast('$datefrom' as date) 
        and cast('$dateto' as date) 
        and c.product in ($product1) 
        and c.segment in ($segment) and c.brand in ($brand1) 
        and c.complaint_category in ($complaintType ) and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped')
            group by 
              region, 
              dealer_name, 
              r.complaint_number
          ) a3 
        group by 
          region, 
          dealer_name, 
          complaint_number
      ) b3 
    where 
      Total_ClosedO_2 <> 0 
     
    group by 
      region, 
      dealer_name
  ) d on a.region = d.region 
  and a.dealer_name = d.dealer_name 
  left join (
    select 
      region, 
      dealer_name, 
      sum(Rating_Count) as 'Total_Rating_Count' 
    from 
      (
        select 
          region, 
          dealer_name, 
          Rating, 
          count(*) as Rating_Count 
        from 
          cases c 
          join mstr_region r on c.zone = r.id 
          join mstr_dealer d on c.dealer = d.id and (d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office')
        where c.location in ($location1) and  
          Rating <> '' and (c.case_status='Closed' or c.case_status='Completed')          
          and cast(c.created_at as date) between cast('$datefrom' as date) 
        and cast('$dateto' as date) 
        and c.product in ($product1) 
        and c.segment in ($segment) and c.brand in ($brand1)
        and c.complaint_category in ($complaintType ) and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped')
        group by 
          c.zone, 
          c.dealer, 
          c.Rating
      ) a4 
    group by 
      region, 
      dealer_name
  ) e on a.region = e.region 
  and a.dealer_name = e.dealer_name 
  left join (
    select 
      region, 
      dealer_name, 
      count(*) as Total_Open_3 
    from 
      (
        select 
          region, 
          dealer_name, 
          complaint_number, 
          case when Total_Open_1 >= 1 then 1 ELSE 0 END as 'Total_Open_2' 
        from 
          (
            SELECT 
              region, 
              dealer_name, 
              r.complaint_number, 
              sum(
                CASE WHEN (
                  c.complaint_category = 1 
                  and ceiling((TIME_TO_SEC(timeDIFF(now(), c.created_at))/3600)/24) <= 5
                ) THEN 1 WHEN (
                  c.complaint_category = 2 
                  and ceiling((TIME_TO_SEC(timeDIFF(now(), c.created_at))/3600)/24) <= 5
                ) THEN 1 WHEN (
                  c.complaint_category = 3 
                  and ceiling((TIME_TO_SEC(timeDIFF(now(), c.created_at))/3600)/24) <= 5
                ) THEN 1 WHEN (
                  c.complaint_category = 4 
                  and ceiling((TIME_TO_SEC(timeDIFF(now(), c.created_at))/3600)/24) <= 30
                ) THEN 1 ELSE 0 END
              ) AS Total_Open_1 
            FROM 
              remarks r 
              left join cases c on c.complaint_number = r.complaint_number 
              left join mstr_region r1 on c.zone = r1.id 
              left join mstr_dealer d on c.dealer = d.id and (d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office')
              left join mstr_complaint cmplnt on cmplnt.id = c.complaint_category 
            where  c.location in ($location1) and 
              r.case_status in ('Open', 'Assigned', 'Acknowledged','Waiting for Customer', 'InProgress','ReAssigned', 'tobedropped') and cast(c.created_at as date) between cast('$datefrom' as date) and cast('$dateto' as date) and c.product in ($product1) 
        and c.segment in ($segment) and c.brand in ($brand1) and c.complaint_category in ($complaintType )  and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped')
            group by 
              region, 
              dealer_name, 
              r.complaint_number
          ) a4 
        group by 
          region, 
          dealer_name, 
          complaint_number
      ) b4 
    where 
      Total_Open_2 <> 0 
     
    group by 
      region, 
      dealer_name
  ) f on a.region = f.region 
  and a.dealer_name = f.dealer_name 
  left join (
    select 
      region, 
      dealer_name, 
      count(*) as Total_OpenO_3 
    from 
      (
        select 
          region, 
          dealer_name, 
          complaint_number, 
          case when Total_OpenO_1 >= 1 then 1 ELSE 0 END as 'Total_OpenO_2' 
        from 
          (
            SELECT 
              region, 
              dealer_name, 
              r.complaint_number, 
              sum(
                CASE WHEN (
                  c.complaint_category = 1 
                      and ceiling((TIME_TO_SEC(timeDIFF(now(), c.created_at))/3600)/24) > 5
                ) THEN 1 WHEN (
                  c.complaint_category = 2 
                      and ceiling((TIME_TO_SEC(timeDIFF(now(), c.created_at))/3600)/24) > 5
                ) THEN 1 WHEN (
                  c.complaint_category = 3 
                      and ceiling((TIME_TO_SEC(timeDIFF(now(), c.created_at))/3600)/24) > 5
                ) THEN 1 WHEN (
                  c.complaint_category = 4 
                      and ceiling((TIME_TO_SEC(timeDIFF(now(), c.created_at))/3600)/24) > 30
                ) THEN 1 ELSE 0 END
              ) AS Total_OpenO_1 
            FROM 
              remarks r 
              left join cases c on c.complaint_number = r.complaint_number 
              left join mstr_region r1 on c.zone = r1.id 
              left join mstr_dealer d on c.dealer = d.id and (d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office')
              left join mstr_complaint cmplnt on cmplnt.id = c.complaint_category 
            where  c.location in ($location1) and 
              r.case_status in (
                'Open', 'Assigned', 'Acknowledged', 
                'Waiting for Customer', 'InProgress', 
                'ReAssigned', 'tobedropped'
              )  and cast(c.created_at as date) between cast('$datefrom' as date) 
        and cast('$dateto' as date) 
        and c.product in ($product1) 
        and c.segment in ($segment) and c.brand in ($brand1) 
        and c.complaint_category in ($complaintType )  and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped')
            group by 
              region, 
              dealer_name, 
              r.complaint_number
          ) a5 
        group by 
          region, 
          dealer_name, 
          complaint_number
      ) b5 
    where 
      Total_OpenO_2 <> 0 
     
    group by 
      region, 
      dealer_name
  ) g on a.region = g.region 
  and a.dealer_name = g.dealer_name 
  left join (
    select 
      region, 
      dealer_name, 
      count(*) as Total_Reopen_3 
    from 
      (
        select 
          region, 
          dealer_name, 
          complaint_number, 
          case when Total_ReOpen_1 >= 1 then 1 ELSE 0 END as 'Total_ReOpen_2' 
        from 
          (
            SELECT 
              region, 
              dealer_name, 
              c.complaint_number, 
              count(*) AS Total_ReOpen_1 
            FROM 
              cases c
              left join mstr_region r1 on c.zone = r1.id 
              left join mstr_dealer d on c.dealer = d.id and (d.dealer_type='Primary Dealer' or d.dealer_type='Sales Office')
              left join mstr_complaint cmplnt on cmplnt.id = c.complaint_category 
            where  c.location in ($location1) and 
              c.case_status in ('reopen') 
             and cast(c.created_at as date) between cast('$datefrom' as date) and cast('$dateto' as date) and c.product in ($product1) 
        and c.segment in ($segment) and c.brand in ($brand1) and c.complaint_category in ($complaintType )  and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped')
            group by 
              region, 
              dealer_name, 
              c.complaint_number
          ) a6 
        group by 
          region, 
          dealer_name, 
          complaint_number
      ) b6 
    group by 
      region, 
      dealer_name
  ) h on a.region = h.region 
  and a.dealer_name = h.dealer_name order by a.region asc");
  
			$data['product'] = $product;
			$data['segment'] = $segment;
			$data['complaintType'] = $complaintType;
			$data['datefrom'] = $datefrom;
			$data['dateto'] = $dateto;
			
			$date = date('Y-m-d');
			$product1 = Auth::user()->product;
			$complaint_type_id = Auth::user()->complaint_type_id;
			
			/* $data['complaintTypeData']= DB::table('mstr_complaint')->select('id','complaint_type')->get();
			$data['vehicleData']= DB::table('mstr_vehicle')->select('id','vehicle')->get(); */
			$data['complaintTypeData']= DB::select("select id,complaint_type from mstr_complaint where id in ($complaint_type_id)" );
			$data['vehicleData']= DB::select("select id,vehicle from mstr_vehicle where id in ($product1)" );
			return view('dealer_summary_report',$data);

}	
			
		} catch (\Exception $ex) {
			$notification = array(
			'message' => $ex->getMessage().' Line : '.$ex->getLine(),
			'alert-type' => 'error'
			);
			return back()->with($notification);
		}
	}
	public function pcsProcess(){
		try{
		
				$date = date('Y-m-d');			
				$data['zoneData']=DB::table("mstr_region")->select('id','region')->get();				
				return view('pcs_process',$data);
			
				
		}catch (\Exception $ex) {			
			$notification = array(
	                'message' => $ex->getMessage(),
	                'alert-type' => 'error'
	            );
            return back()->with($notification);
        }
			
	}
    public function storePcsProcess(Request $request){
        try {
            $year = $request->input('year');
            $data['yearVal'] = $year;
            $y = explode("-", $year);
            $fYear = $y[0];
            $lYear = $y[1];
            $zone = $request->input('zone');
            $Dealer = $request->input('Dealer');
            $nonAdmin = $request->input('nonAdmin');
            $results = array();
            $productArray = $segmentArray = $complaintTypeArray = $zoneArray = $DealerArray = $brandArray = '';

            if ($zone !== NULL)
                foreach ($zone as $row) {
                    $zoneArray .= $row . ',';
                }
            $zone = rtrim($zoneArray, ',');
            if ($Dealer !== NULL)
                foreach ($Dealer as $row) {
                    $DealerArray .= $row . ',';
                }
            $Dealer = rtrim($DealerArray, ',');

            $data['yVal'] = $fYear . '@#' . $lYear;
            $data['dealerVal'] = $Dealer;
            $data['zoneVal'] = $zone;
            $data['nonAdmin'] = $nonAdmin;
            $data['allCatLogged'] = DB::select("select  m.months as month,t.year,t.cnt from tempmonths m left join(SELECT month(created_at)as month,year(created_at) year, count(*) as cnt FROM cases where case_status!='Dropped' and ((year(created_at)=$fYear and month(created_at)>=4) or (right(year(created_at),2)=$lYear and month(created_at)<=3)) and zone in ($zone) and dealer in ($Dealer) group by  year(created_at),month(created_at) )t on  m.months=t.month  left join tempmonths f on m.months=f.months order by f.id;");

            $data['allClosed'] = DB::select("select m.months as month,t.year,t.cnt from tempmonths m left join(SELECT month(c.created_at)as month,year(c.created_at) year, SUM(CASE WHEN (c.case_status='Completed') THEN 1 WHEN (c.case_status='Closed') THEN 1 WHEN (c.case_status='reopen') THEN 1 ELSE 0 END) as cnt FROM cases as c where (c.case_status='Closed' or c.case_status='Completed' or c.case_status='reopen') and ((year(c.created_at)=$fYear and month(c.created_at)>=4) or (right(year(c.created_at),2)=$lYear and month(c.created_at)<=3)) and zone in ($zone)and dealer in ($Dealer) group by year(c.created_at),month(c.created_at) )t on m.months=t.month left join tempmonths f on m.months=f.months order by f.id;");

            /*$data['allNotInitiated'] = DB::select("select m.months as month,t.year,t.pcs_status from tempmonths m left join(SELECT month(c.created_at)as month,year(c.created_at) year, SUM(CASE WHEN c.case_status = 'Completed'  and (c.rating = '' or c.rating is null) and (now() >= c.updated_at + INTERVAL 24 HOUR and now() <= c.updated_at + INTERVAL 72 HOUR) THEN 1 ELSE 0 END) as pcs_status FROM cases as c where ((year(c.created_at)=$fYear and month(c.created_at)>=4) or (right(year(c.created_at),2)=$lYear and month(c.created_at)<=3)) and zone in ($zone)and dealer in ($Dealer) group by year(c.created_at),month(c.created_at) )t on m.months=t.month left join tempmonths f on m.months=f.months order by f.id;");*/


            /*$data['allNotInitiated'] = DB::select("select m.months as month,t.year,t.pcs_status from tempmonths m left join(SELECT month(c.created_at)as month,year(c.created_at) year, SUM(CASE WHEN c.case_status  in ('Completed') and (c.rating = '' or c.rating is null)  and (c.pcs_attempts='' or c.pcs_attempts is null)   THEN 1 ELSE 0 END) as pcs_status FROM cases as c where ((year(c.created_at)=$fYear and month(c.created_at)>=4) or (right(year(c.created_at),2)=$lYear and month(c.created_at)<=3)) and zone in ($zone)and dealer in ($Dealer) group by year(c.created_at),month(c.created_at) )t on m.months=t.month left join tempmonths f on m.months=f.months order by f.id;");*/

            $data['allNotInitiated'] = DB::select("select m.months as month,t.year,t.pcs_status from tempmonths m left join(SELECT month(c.created_at)as month,year(c.created_at) year, SUM(CASE WHEN c.case_status = 'Completed' and (c.rating = '' or c.rating is null) and now() <= c.updated_at + INTERVAL 24 HOUR THEN 1
        WHEN c.case_status = 'Completed'  and (c.rating = '' or c.rating is null) and (now() >= c.updated_at + INTERVAL 24 HOUR and now() <= c.updated_at + INTERVAL 72 HOUR) THEN 1        
        WHEN c.case_status = 'Closed'  and (c.pcs_attempts='' or c.pcs_attempts is null) and (c.rating = '' or c.rating is null) and (now() >= c.updated_at + INTERVAL 72 HOUR) THEN 1        
        WHEN c.case_status = 'Completed'  and (c.pcs_attempts='' or c.pcs_attempts is null) and (c.rating = '' or c.rating is null) and (now() >= c.updated_at + INTERVAL 72 HOUR) THEN 1 ELSE 0 END) as pcs_status FROM cases as c where ((year(c.created_at)=$fYear and month(c.created_at)>=4) or (right(year(c.created_at),2)=$lYear and month(c.created_at)<=3)) and zone in ($zone)and dealer in ($Dealer) group by year(c.created_at),month(c.created_at) )t on m.months=t.month left join tempmonths f on m.months=f.months order by f.id;");

            $data['allFollowing'] = DB::select("select m.months as month,t.year,t.pcs_status from tempmonths m left join(SELECT month(c.created_at)as month,year(c.created_at) year, SUM(CASE WHEN c.case_status = 'Closed'  and (c.rating = '' or c.rating is null) and (c.pcs_attempts in ('1,2')) THEN 1 ELSE 0 END) as pcs_status FROM cases as c where ((year(c.created_at)=$fYear and month(c.created_at)>=4) or (right(year(c.created_at),2)=$lYear and month(c.created_at)<=3)) and zone in ($zone)and dealer in ($Dealer) group by year(c.created_at),month(c.created_at) )t on m.months=t.month left join tempmonths f on m.months=f.months order by f.id;");

            $data['allCompleted'] = DB::select("select m.months as month,t.year,t.pcs_status from tempmonths m left join(SELECT month(c.created_at)as month,year(c.created_at) year, SUM(CASE WHEN c.case_status = 'Closed' and (c.rating != '' or c.rating is not null) and c.pcs_attempts!=3 THEN 1 ELSE 0 END) as pcs_status FROM cases as c where ((year(c.created_at)=$fYear and month(c.created_at)>=4) or (right(year(c.created_at),2)=$lYear and month(c.created_at)<=3)) and zone in ($zone) and c.complaint_number not in (SELECT cas.complaint_number FROM cases as cas where cas.case_status = 'Closed' and (cas.pcs_attempts='' or cas.pcs_attempts is null) and (cas.rating = '' or cas.rating is null) and (now() >= cas.updated_at + INTERVAL 72 HOUR))  and dealer in ($Dealer) group by year(c.created_at),month(c.created_at) )t on m.months=t.month left join tempmonths f on m.months=f.months order by f.id;");

            $data['alldrop'] = DB::select("select m.months as month,t.year,t.pcs_status from tempmonths m left join(SELECT month(c.created_at)as month,year(c.created_at) year, SUM(CASE WHEN c.case_status = 'Closed'  and (c.rating = '' or c.rating is null) and (c.pcs_attempts = '3') THEN  1 ELSE 0 END) as pcs_status FROM cases as c where ((year(c.created_at)=$fYear and month(c.created_at)>=4) or (right(year(c.created_at),2)=$lYear and month(c.created_at)<=3)) and zone in ($zone)and dealer in ($Dealer) group by year(c.created_at),month(c.created_at) )t on m.months=t.month left join tempmonths f on m.months=f.months order by f.id;");
            /*$data['alldrop'] = DB::select("select m.months as month,t.year,t.pcs_status from tempmonths m left join(SELECT month(c.created_at)as month,year(c.created_at) year, SUM(CASE WHEN (c.case_status = 'Closed' or c.case_status = 'Dropped') and (c.rating = '' or c.rating is null) and (c.pcs_attempts = '3') THEN  1 ELSE 0 END) as pcs_status FROM cases as c where ((year(c.created_at)=$fYear and month(c.created_at)>=4) or (right(year(c.created_at),2)=$lYear and month(c.created_at)<=3)) and zone in ($zone)and dealer in ($Dealer) group by year(c.created_at),month(c.created_at) )t on m.months=t.month left join tempmonths f on m.months=f.months order by f.id;");*/
            $data['zoneData'] = DB::table("mstr_region")->select('id', 'region')->get();
            $date = date('Y-m-d');
            return view('pcs_process', $data);
        } catch (\Exception $ex) {
            $notification = array(
                'message' => $ex->getMessage() . ' Line : ' . $ex->getLine(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }
	public function pcsMonthReport(Request $request){
		try{
			$mnthVal = $request->input('mnthVal');
			$yearVal = $request->input('yearVal');
			$indxVal = $request->input('indxVal');
			$zone = $request->input('zone');
			$dealer = $request->input('dealer');
			$yVal = explode('@#',$yearVal);
			$crntYear='';
			$crntMnth = $yVal[0];
			$nxtMnth = '20'.$yVal[1];
			
			if($mnthVal ==1){
				$crntYear = $nxtMnth;
				$mnthVal=1;
			} else if ($mnthVal == 2){
				$crntYear = $nxtMnth;
				$mnthVal=2;
			} else if ($mnthVal ==3){
				$crntYear = $nxtMnth;
				$mnthVal=3;
			}else{
				$crntYear = $crntMnth;
			}
			//$sql = array();
			if($indxVal ==1){
				$data['sql'] =DB::select("SELECT c.complaint_number,c.case_type, c.created_at as dateRegistration,c.description,c.communication_customer,cust.customerOrg as org,cc.custname,cc.mobile1,comp.complaint_type,subcomp.sub_complaint_type,brnd.brand,veh.vehicle,seg.segment,reg.region,del.dealer_name,loc.city,u.name as assignedTo,r.created_at as acknowledgeDate,module.mode_name,r1.created_at as complitionDate,(CASE		
       WHEN c.case_status  not in ('Completed','Closed','Dropped')  THEN 'Not Due'
        WHEN c.case_status = 'Completed' and (c.rating = '' or c.rating is null) and now() <= c.updated_at + INTERVAL 24 HOUR THEN 'Due'
        WHEN c.case_status = 'Completed'  and (c.rating = '' or c.rating is null) and (now() >= c.updated_at + INTERVAL 24 HOUR and now() <= c.updated_at + INTERVAL 72 HOUR) THEN 'Not Started'        
        WHEN c.case_status = 'Closed'  and (c.pcs_attempts='' or c.pcs_attempts is null) and (c.rating = '' or c.rating is null) and (now() >= c.updated_at + INTERVAL 72 HOUR) THEN 'Over Due'        
        WHEN c.case_status = 'Completed'  and (c.pcs_attempts='' or c.pcs_attempts is null) and (c.rating = '' or c.rating is null) and (now() >= c.updated_at + INTERVAL 72 HOUR) THEN 'Over Due'        
        WHEN c.case_status = 'Closed'  and (c.rating = '' or c.rating is null) and (c.pcs_attempts in ('1,2')) THEN 'Under follow-up'        
        WHEN c.case_status = 'Closed'  and (c.rating = '' or c.rating is null) and (c.pcs_attempts = '3') THEN 'Dropped'        
		WHEN c.case_status = 'Dropped'  and (c.rating = '' or c.rating is null) THEN 'Case Dropped'        
        WHEN c.case_status = 'Closed'   and c.rating != '' THEN 'Closed'
        ELSE ''
    END) AS 'pcs_status',r2.comment as resonDrop,c.no_rating_reason as pcs_dropped_reason

FROM cases as c 
LEFT JOIN mstr_customer as cust on cust.id=c.customer_id left join mstr_customer_contact as cc on cc.id=c.customer_contact_id 
left join mstr_complaint as comp on comp.id=c.complaint_category left join mstr_sub_complaint as subcomp on subcomp.id=c.sub_complaint_type left join mstr_brand as brnd on brnd.id=c.brand left join mstr_vehicle as veh on veh.id=c.product left join product_segment as seg on seg.id=c.segment left join mstr_region as reg on reg.id=c.zone left join mstr_dealer as del on del.id=c.dealer
left join mstr_city as loc on loc.id=c.location left join users as u on u.id=c.assigned_to left join mstr_contact_center_module as module on module.id=c.contact_center_module left join remarks as r on r.complaint_number=c.complaint_number and r.case_status='Acknowledged' left join remarks as r1 on r1.complaint_number=c.complaint_number and (r1.case_status='Completed' or r1.case_status='Closed') left join remarks as r2 on r2.complaint_number=c.complaint_number and r2.case_status='Dropped' where year(c.created_at)=$crntYear and month(c.created_at)=$mnthVal and c.zone in ($zone)and c.dealer in ($dealer) and c.case_status not in (select cas.case_status from cases as cas where cas.case_status ='Dropped') group by c.complaint_number;");
$view = view("pcs_report_table",$data)->render();
return response()->json(['html'=>$view,]);
			}
	if ($indxVal ==2) {
		$data['sql'] =DB::select("SELECT c.complaint_number,c.case_type, c.created_at as dateRegistration,c.description,c.communication_customer,cust.customerOrg as org,cc.custname,cc.mobile1,comp.complaint_type,subcomp.sub_complaint_type,brnd.brand,veh.vehicle,seg.segment,reg.region,del.dealer_name,loc.city,u.name as assignedTo,r.created_at as acknowledgeDate,module.mode_name,r1.created_at as complitionDate,(CASE		
       WHEN c.case_status  not in ('Completed','Closed','Dropped')  THEN 'Not Due'
        WHEN c.case_status = 'Completed' and (c.rating = '' or c.rating is null) and now() <= c.updated_at + INTERVAL 24 HOUR THEN 'Due'
        WHEN c.case_status = 'Completed'  and (c.rating = '' or c.rating is null) and (now() >= c.updated_at + INTERVAL 24 HOUR and now() <= c.updated_at + INTERVAL 72 HOUR) THEN 'Not Started'        
        WHEN c.case_status = 'Closed'  and (c.pcs_attempts='' or c.pcs_attempts is null) and (c.rating = '' or c.rating is null) and (now() >= c.updated_at + INTERVAL 72 HOUR) THEN 'Over Due'        
        WHEN c.case_status = 'Completed'  and (c.pcs_attempts='' or c.pcs_attempts is null) and (c.rating = '' or c.rating is null) and (now() >= c.updated_at + INTERVAL 72 HOUR) THEN 'Over Due'        
        WHEN c.case_status = 'Closed'  and (c.rating = '' or c.rating is null) and (c.pcs_attempts in ('1,2')) THEN 'Under follow-up'        
        WHEN c.case_status = 'Closed'  and (c.rating = '' or c.rating is null) and (c.pcs_attempts = '3') THEN 'Dropped'        
		WHEN c.case_status = 'Dropped'  and (c.rating = '' or c.rating is null) THEN 'Case Dropped'        
        WHEN c.case_status = 'Closed'   and c.rating != '' THEN 'Closed'
        ELSE ''
    END) AS 'pcs_status',r2.comment as resonDrop,c.no_rating_reason as pcs_dropped_reason

FROM cases as c 
LEFT JOIN mstr_customer as cust on cust.id=c.customer_id left join mstr_customer_contact as cc on cc.id=c.customer_contact_id 
left join mstr_complaint as comp on comp.id=c.complaint_category left join mstr_sub_complaint as subcomp on subcomp.id=c.sub_complaint_type left join mstr_brand as brnd on brnd.id=c.brand left join mstr_vehicle as veh on veh.id=c.product left join product_segment as seg on seg.id=c.segment left join mstr_region as reg on reg.id=c.zone left join mstr_dealer as del on del.id=c.dealer
left join mstr_city as loc on loc.id=c.location left join users as u on u.id=c.assigned_to left join mstr_contact_center_module as module on module.id=c.contact_center_module left join remarks as r on r.complaint_number=c.complaint_number and r.case_status='Acknowledged' left join remarks as r1 on r1.complaint_number=c.complaint_number and (r1.case_status='Completed') left join remarks as r2 on r2.complaint_number=c.complaint_number and r2.case_status='Dropped' where (c.case_status='Closed' or c.case_status='Completed' or c.case_status='reopen') and year(c.created_at)=$crntYear and month(c.created_at)=$mnthVal and c.zone in ($zone)and c.dealer in ($dealer) group by c.complaint_number;");
$view = view("pcs_report_table",$data)->render();
return response()->json(['html'=>$view,]);
			}
			
			if ($indxVal ==3) {

				$data['sql'] =DB::select("SELECT c.complaint_number,c.case_type, c.created_at as dateRegistration,c.description,c.communication_customer,cust.customerOrg as org,cc.custname,cc.mobile1,comp.complaint_type,subcomp.sub_complaint_type,brnd.brand,veh.vehicle,seg.segment,reg.region,del.dealer_name,loc.city,u.name as assignedTo,r.created_at as acknowledgeDate,module.mode_name,r1.created_at as complitionDate,(CASE		
       WHEN c.case_status  not in ('Completed','Closed','Dropped')  THEN 'Not Due'
        WHEN c.case_status = 'Completed' and (c.rating = '' or c.rating is null) and now() <= c.updated_at + INTERVAL 24 HOUR THEN 'Due'
        WHEN c.case_status = 'Completed'  and (c.rating = '' or c.rating is null) and (now() >= c.updated_at + INTERVAL 24 HOUR and now() <= c.updated_at + INTERVAL 72 HOUR) THEN 'Not Started'        
        WHEN c.case_status = 'Closed'  and (c.pcs_attempts='' or c.pcs_attempts is null) and (c.rating = '' or c.rating is null) and (now() >= c.updated_at + INTERVAL 72 HOUR) THEN 'Over Due'        
        WHEN c.case_status = 'Completed'  and (c.pcs_attempts='' or c.pcs_attempts is null) and (c.rating = '' or c.rating is null) and (now() >= c.updated_at + INTERVAL 72 HOUR) THEN 'Over Due'        
        WHEN c.case_status = 'Closed'  and (c.rating = '' or c.rating is null) and (c.pcs_attempts in ('1,2')) THEN 'Under follow-up'        
        WHEN c.case_status = 'Closed'  and (c.rating = '' or c.rating is null) and (c.pcs_attempts = '3') THEN 'Dropped'        
		WHEN c.case_status = 'Dropped'  and (c.rating = '' or c.rating is null) THEN 'Case Dropped'        
        WHEN c.case_status = 'Closed'   and c.rating != '' THEN 'Closed'
        ELSE ''
    END) AS 'pcs_status',r2.comment as resonDrop,c.no_rating_reason as pcs_dropped_reason

FROM cases as c 
LEFT JOIN mstr_customer as cust on cust.id=c.customer_id left join mstr_customer_contact as cc on cc.id=c.customer_contact_id 
left join mstr_complaint as comp on comp.id=c.complaint_category left join mstr_sub_complaint as subcomp on subcomp.id=c.sub_complaint_type left join mstr_brand as brnd on brnd.id=c.brand left join mstr_vehicle as veh on veh.id=c.product left join product_segment as seg on seg.id=c.segment left join mstr_region as reg on reg.id=c.zone left join mstr_dealer as del on del.id=c.dealer
left join mstr_city as loc on loc.id=c.location left join users as u on u.id=c.assigned_to left join mstr_contact_center_module as module on module.id=c.contact_center_module left join remarks as r on r.complaint_number=c.complaint_number and r.case_status='Acknowledged' left join remarks as r1 on r1.complaint_number=c.complaint_number and (r1.case_status='Completed' or r1.case_status='Closed') left join remarks as r2 on r2.complaint_number=c.complaint_number and r2.case_status='Dropped' where ((c.case_status = 'Completed' and (c.rating = '' or c.rating is null) and now() <= c.updated_at + INTERVAL 24 HOUR ) or (c.case_status = 'Completed'  and (c.rating = '' or c.rating is null) and (now() >= c.updated_at + INTERVAL 24 HOUR and now() <= c.updated_at + INTERVAL 72 HOUR)) or (c.case_status = 'Closed'  and (c.pcs_attempts='' or c.pcs_attempts is null) and (c.rating = '' or c.rating is null) and (now() >= c.updated_at + INTERVAL 72 HOUR)) or (c.case_status = 'Completed'  and (c.pcs_attempts='' or c.pcs_attempts is null) and (c.rating = '' or c.rating is null)) and (now() >= c.updated_at + INTERVAL 72 HOUR)) and year(c.created_at)=$crntYear and month(c.created_at)=$mnthVal and c.zone in ($zone)and c.dealer in ($dealer) group by c.complaint_number;");
$view = view("pcs_report_table",$data)->render();
return response()->json(['html'=>$view,]);
			}
			 if ($indxVal ==4) {
				 $data['sql'] =DB::select("SELECT c.complaint_number,c.case_type, c.created_at as dateRegistration,c.description,c.communication_customer,cust.customerOrg as org,cc.custname,cc.mobile1,comp.complaint_type,subcomp.sub_complaint_type,brnd.brand,veh.vehicle,seg.segment,reg.region,del.dealer_name,loc.city,u.name as assignedTo,r.created_at as acknowledgeDate,module.mode_name,r1.created_at as complitionDate,(CASE		
       WHEN c.case_status  not in ('Completed','Closed','Dropped')  THEN 'Not Due'
        WHEN c.case_status = 'Completed' and (c.rating = '' or c.rating is null) and now() <= c.updated_at + INTERVAL 24 HOUR THEN 'Due'
        WHEN c.case_status = 'Completed'  and (c.rating = '' or c.rating is null) and (now() >= c.updated_at + INTERVAL 24 HOUR and now() <= c.updated_at + INTERVAL 72 HOUR) THEN 'Not Started'        
        WHEN c.case_status = 'Closed'  and (c.pcs_attempts='' or c.pcs_attempts is null) and (c.rating = '' or c.rating is null) and (now() >= c.updated_at + INTERVAL 72 HOUR) THEN 'Over Due'        
        WHEN c.case_status = 'Completed'  and (c.pcs_attempts='' or c.pcs_attempts is null) and (c.rating = '' or c.rating is null) and (now() >= c.updated_at + INTERVAL 72 HOUR) THEN 'Over Due'        
        WHEN c.case_status = 'Closed'  and (c.rating = '' or c.rating is null) and (c.pcs_attempts in ('1,2')) THEN 'Under follow-up'        
        WHEN c.case_status = 'Closed'  and (c.rating = '' or c.rating is null) and (c.pcs_attempts = '3') THEN 'Dropped'        
		WHEN c.case_status = 'Dropped'  and (c.rating = '' or c.rating is null) THEN 'Case Dropped'        
        WHEN c.case_status = 'Closed'   and c.rating != '' THEN 'Closed'
        ELSE ''
    END) AS 'pcs_status',r2.comment as resonDrop,c.no_rating_reason as pcs_dropped_reason

FROM cases as c 
LEFT JOIN mstr_customer as cust on cust.id=c.customer_id left join mstr_customer_contact as cc on cc.id=c.customer_contact_id 
left join mstr_complaint as comp on comp.id=c.complaint_category left join mstr_sub_complaint as subcomp on subcomp.id=c.sub_complaint_type left join mstr_brand as brnd on brnd.id=c.brand left join mstr_vehicle as veh on veh.id=c.product left join product_segment as seg on seg.id=c.segment left join mstr_region as reg on reg.id=c.zone left join mstr_dealer as del on del.id=c.dealer
left join mstr_city as loc on loc.id=c.location left join users as u on u.id=c.assigned_to left join mstr_contact_center_module as module on module.id=c.contact_center_module left join remarks as r on r.complaint_number=c.complaint_number and r.case_status='Acknowledged' left join remarks as r1 on r1.complaint_number=c.complaint_number and (r1.case_status='Completed' or r1.case_status='Closed') left join remarks as r2 on r2.complaint_number=c.complaint_number and r2.case_status='Dropped' where (c.case_status = 'Closed'  and (c.rating = '' or c.rating is null) and (c.pcs_attempts in ('1,2'))) and year(c.created_at)=$crntYear and month(c.created_at)=$mnthVal and c.zone in ($zone) and c.dealer in ($dealer) group by c.complaint_number;");
$view = view("pcs_report_table",$data)->render();
return response()->json(['html'=>$view,]);
			}
			if ($indxVal ==5) {
				

				$data['sql'] =DB::select("SELECT c.complaint_number,c.case_type, c.created_at as dateRegistration,c.description,c.communication_customer,cust.customerOrg as org,cc.custname,cc.mobile1,comp.complaint_type,subcomp.sub_complaint_type,brnd.brand,veh.vehicle,seg.segment,reg.region,del.dealer_name,loc.city,u.name as assignedTo,r.created_at as acknowledgeDate,module.mode_name,r1.created_at as complitionDate,(CASE
       WHEN c.case_status  not in ('Completed','Closed','Dropped')  THEN 'Not Due'
        WHEN c.case_status = 'Completed' and (c.rating = '' or c.rating is null) and now() <= c.updated_at + INTERVAL 24 HOUR THEN 'Due'
        WHEN c.case_status = 'Completed'  and (c.rating = '' or c.rating is null) and (now() >= c.updated_at + INTERVAL 24 HOUR and now() <= c.updated_at + INTERVAL 72 HOUR) THEN 'Not Started'
        WHEN c.case_status = 'Closed'  and (c.pcs_attempts='' or c.pcs_attempts is null) and (c.rating = '' or c.rating is null) and (now() >= c.updated_at + INTERVAL 72 HOUR) THEN 'Over Due'
        WHEN c.case_status = 'Completed'  and (c.pcs_attempts='' or c.pcs_attempts is null) and (c.rating = '' or c.rating is null) and (now() >= c.updated_at + INTERVAL 72 HOUR) THEN 'Over Due'
        WHEN c.case_status = 'Closed'  and (c.rating = '' or c.rating is null) and (c.pcs_attempts in ('1,2')) THEN 'Under follow-up'
        WHEN c.case_status = 'Closed'  and (c.rating = '' or c.rating is null) and (c.pcs_attempts = '3') THEN 'Dropped'
		WHEN c.case_status = 'Dropped'  and (c.rating = '' or c.rating is null) THEN 'Case Dropped'
        WHEN c.case_status = 'Closed'   and c.rating != '' THEN 'Closed'
        ELSE ''
    END) AS 'pcs_status',r2.comment as resonDrop,c.no_rating_reason as pcs_dropped_reason

FROM cases as c
LEFT JOIN mstr_customer as cust on cust.id=c.customer_id left join mstr_customer_contact as cc on cc.id=c.customer_contact_id
left join mstr_complaint as comp on comp.id=c.complaint_category left join mstr_sub_complaint as subcomp on subcomp.id=c.sub_complaint_type left join mstr_brand as brnd on brnd.id=c.brand left join mstr_vehicle as veh on veh.id=c.product left join product_segment as seg on seg.id=c.segment left join mstr_region as reg on reg.id=c.zone left join mstr_dealer as del on del.id=c.dealer
left join mstr_city as loc on loc.id=c.location left join users as u on u.id=c.assigned_to left join mstr_contact_center_module as module on module.id=c.contact_center_module left join remarks as r on r.complaint_number=c.complaint_number and r.case_status='Acknowledged' left join remarks as r1 on r1.complaint_number=c.complaint_number and (r1.case_status='Completed' or r1.case_status='Closed') left join remarks as r2 on r2.complaint_number=c.complaint_number and r2.case_status='Dropped' where (c.case_status = 'Closed'  and (c.rating != '' or c.rating is not null) and c.pcs_attempts!=3) and (year(c.created_at)=$crntYear and month(c.created_at)=$mnthVal) and c.zone in ($zone)and c.dealer in ($dealer) group by c.complaint_number;");
$view = view("pcs_report_table",$data)->render();
return response()->json(['html'=>$view,]);
			}
			if ($indxVal ==6) {
				$data['sql'] =DB::select("SELECT c.complaint_number,c.case_type, c.created_at as dateRegistration,c.description,c.communication_customer,cust.customerOrg as org,cc.custname,cc.mobile1,comp.complaint_type,subcomp.sub_complaint_type,brnd.brand,veh.vehicle,seg.segment,reg.region,del.dealer_name,loc.city,u.name as assignedTo,r.created_at as acknowledgeDate,module.mode_name,r1.created_at as complitionDate,(CASE		
       WHEN c.case_status  not in ('Completed','Closed','Dropped')  THEN 'Not Due'
        WHEN c.case_status = 'Completed' and (c.rating = '' or c.rating is null) and now() <= c.updated_at + INTERVAL 24 HOUR THEN 'Due'
        WHEN c.case_status = 'Completed'  and (c.rating = '' or c.rating is null) and (now() >= c.updated_at + INTERVAL 24 HOUR and now() <= c.updated_at + INTERVAL 72 HOUR) THEN 'Not Started'        
        WHEN c.case_status = 'Closed'  and (c.pcs_attempts='' or c.pcs_attempts is null) and (c.rating = '' or c.rating is null) and (now() >= c.updated_at + INTERVAL 72 HOUR) THEN 'Over Due'        
        WHEN c.case_status = 'Completed'  and (c.pcs_attempts='' or c.pcs_attempts is null) and (c.rating = '' or c.rating is null) and (now() >= c.updated_at + INTERVAL 72 HOUR) THEN 'Over Due'        
        WHEN c.case_status = 'Closed'  and (c.rating = '' or c.rating is null) and (c.pcs_attempts in ('1,2')) THEN 'Under follow-up'        
        WHEN c.case_status = 'Closed'  and (c.rating = '' or c.rating is null) and (c.pcs_attempts = '3') THEN 'Dropped'        
		WHEN c.case_status = 'Dropped'  and (c.rating = '' or c.rating is null) THEN 'Case Dropped'        
        WHEN c.case_status = 'Closed'   and c.rating != '' THEN 'Closed'
        ELSE ''
    END) AS 'pcs_status',r2.comment as resonDrop,c.no_rating_reason as pcs_dropped_reason

FROM cases as c 
LEFT JOIN mstr_customer as cust on cust.id=c.customer_id left join mstr_customer_contact as cc on cc.id=c.customer_contact_id 
left join mstr_complaint as comp on comp.id=c.complaint_category left join mstr_sub_complaint as subcomp on subcomp.id=c.sub_complaint_type left join mstr_brand as brnd on brnd.id=c.brand left join mstr_vehicle as veh on veh.id=c.product left join product_segment as seg on seg.id=c.segment left join mstr_region as reg on reg.id=c.zone left join mstr_dealer as del on del.id=c.dealer
left join mstr_city as loc on loc.id=c.location left join users as u on u.id=c.assigned_to left join mstr_contact_center_module as module on module.id=c.contact_center_module left join remarks as r on r.complaint_number=c.complaint_number and r.case_status='Acknowledged' left join remarks as r1 on r1.complaint_number=c.complaint_number and (r1.case_status='Completed' or r1.case_status='Closed') left join remarks as r2 on r2.complaint_number=c.complaint_number and r2.case_status='Dropped' where (c.case_status = 'Closed'  and (c.rating = '' or c.rating is null) and (c.pcs_attempts = '3'))  and (year(c.created_at)=$crntYear and month(c.created_at)=$mnthVal)  and c.zone in ($zone)and c.dealer in ($dealer) group by c.complaint_number;");
$view = view("pcs_report_table",$data)->render();
return response()->json(['html'=>$view,]);
			}
			
		}catch (\Exception $ex) {
			$notification = array(
			'message' => $ex->getMessage().' Line : '.$ex->getLine(),
			'alert-type' => 'error'
			);
			return back()->with($notification);
		}
	}
	public function preventiveAction(){
        try{
           
            $data['preActionSql'] = DB::select("Select c.complaint_number,c.case_status,c.description,c.root_cause,c.preventive_action,c.target_date,c.updated_at, sc.sub_complaint_type, u.name as Responsible_person,r.created_at as complition_date from cases c left join mstr_sub_complaint as sc on sc.id=c.sub_complaint_type left join users as u on u.id=c.assigned_to left join remarks as r on r.complaint_number = c.complaint_number and (r.case_status='Completed' or r.case_status='Closed') order by c.id desc");
            return view('prevention_action',$data);

        }catch (\Exception $ex){
            $notification = array(
                'message' => $ex->getMessage().' Line : '.$ex->getLine(),
                'alert-type' => 'error'
            );
        }
    }
	public function quatersDate($date){
		$fromDate = $toDate='';
		$current_month = date('m');
		$current_year = date('Y');
		$givenYear = date('Y', strtotime($date));
		$givenMonth = date('m', strtotime($date));
		if($givenYear == $current_year){
			if($givenMonth>=1 && $givenMonth<=3){
				$fromDate = $givenYear.'-01-01';
				$toDate = $givenYear.'-03-31';
			}
			if($givenMonth>=4 && $givenMonth<=6){
				$fromDate = $givenYear.'-04-01';
				$toDate = $givenYear.'-06-30';
			}
			if($givenMonth>=7 && $givenMonth<=9){
				$fromDate = $givenYear.'-07-01';
				$toDate = $givenYear.'-09-30';
			}
			if($givenMonth>=10 && $givenMonth<=12){
				$fromDate = $givenYear.'-10-01';
				$toDate = $givenYear.'-12-31';
			}
		}
		if($givenYear < $current_year){
			if($givenMonth>=1 && $givenMonth<=3){
				$fromDate = $givenYear.'-01-01';
				$toDate = $givenYear.'-03-31';
			}
			if($givenMonth>=4 && $givenMonth<=6){
				$fromDate = $givenYear.'-04-01';
				$toDate = $givenYear.'-06-30';
			}
			if($givenMonth>=7 && $givenMonth<=9){
				$fromDate = $givenYear.'-07-01';
				$toDate = $givenYear.'-09-30';
			}
			if($givenMonth>=10 && $givenMonth<=12){
				$fromDate = $givenYear.'-10-01';
				$toDate = $givenYear.'-12-31';
			}
		}
		return $fromDate.'~'.$toDate;
	}

  public function ticketReport(){
    try {
      return view('ticket_report');
    } 
    catch (\Exception $ex){
      $notification = array(
          'message' => $ex->getMessage().' Line: '.$ex->getLine(),
          'alert-type' => 'error'
      );
      return back()->with($notification);
    }
   
  }
  public function storeTicketReport(Request $request){
    try{
      
      $complaint_number_search = $request->input('complaint_number_search');
      
       $data['remark_type_table'] = DB::select("Select id, type from remark_type");
       /* $data['vehicleModels'] = DB::select("Select id, vehicle_model, vehicle_segment, add_blue_use, engine_emmission_type from mstr_vehicle_models"); */
       $data['divshows'] = 'true';
       $data['complaint_number_search'] =  $complaint_number_search;
        
       $query = DB::select("select c.id as caseId, c.complaint_number, c.vehicleId, c.ownerId, c.customer_contact_id, c.callerId, c.from_where, c.to_where, c.highway, c.ticket_type, c.aggregate, c.vehicle_problem, c.assign_to, c.dealer_mob_number, c.dealer_alt_mob_number, c.remark_type, c.disposition, c.agent_remark, c.standard_remark, c.assign_remarks, c.estimated_response_time, c.actual_response_time, c.tat_scheduled, c.acceptance, c.latitude, c.longitude,c.feedback_rating,c.feedback_desc,c.location,c.landmark,c.state as stateId,c.city as cityId,c.district, c.vehicle_type, c.vehicle_movable, v.vehicle, v.vehicle_model, v.reg_number, v.chassis_number, v.engine_number, v.vehicle_segment, v.purchase_date, v.add_blue_use, v.engine_emmission_type, o.owner_name, o.owner_mob, o.owner_landline, o.owner_cat, o.owner_company,o.alse_mail,o.asm_mail, oc.contact_name, oc.mob,oc.owner_contact_email,cal.caller_type, cal.caller_name, cal.caller_contact, s.state, city.city,c.created_at as ticketCreatedDate,rem.created_at as closedDate,(Select employee_name from remarks where id = (Select min(id) as id from remarks where complaint_number=c.complaint_number limit 1)) as caseCreatedBy,(Select sac_code from mstr_dealer where id = c.assign_to) as dealerCode,(Select dealer_name from mstr_dealer where id = c.assign_to) as dealerName, (select name from users where find_in_set(c.assign_to,dealer_id) and role = 1  and flag=1 limit 1) as ALSEName,(select mobile from users where find_in_set(c.assign_to,dealer_id) and role = 1 and flag=1 limit 1) as ALSEMobile
        from cases as c left join mstr_vehicle as v on v.id = c.vehicleId left join mstr_owner as o on o.id = c.ownerId and o.id = c.ownerId 
       left join mstr_owner_contact as oc on oc.id = c.customer_contact_id and oc.owner_id = c.ownerId left join mstr_caller as cal on cal.id = c.callerId  left join mstr_caller_state as s on s.id = c.state left join mstr_caller_city as city on city.id = c.city left join remarks as rem on rem.complaint_number = c.complaint_number and rem.remark_type='Closed' where c.complaint_number = :complaint_number_search",["complaint_number_search"=>$complaint_number_search]);
        
       $data['ALSEName'] = $query[0]->ALSEName!=''?$query[0]->ALSEName:'NA';
       $data['ALSEMobile'] = $query[0]->ALSEMobile!=''?$query[0]->ALSEMobile:'NA';
       $data['caseCreatedBy'] = $query[0]->caseCreatedBy!=''?$query[0]->caseCreatedBy:'NA';
       $data['closedDate'] = $query[0]->closedDate!=''?$query[0]->closedDate:'NA';
       $data['ticketCreatedDate'] = $query[0]->ticketCreatedDate;
       $ticketCreatedDate= $query[0]->ticketCreatedDate;
       $data['vehicleId'] = $query[0]->vehicleId;
       $data['caseId'] = $query[0]->caseId;
       $data['complaint_number'] = $query[0]->complaint_number;
       $complaint_number = $query[0]->complaint_number;
       $data['from_where'] = $query[0]->from_where;
       $data['highway'] = $query[0]->highway;
       $data['to_where'] = $query[0]->to_where;
       $data['ticket_type'] = $query[0]->ticket_type;
       $data['aggregate'] = $query[0]->aggregate;
       $data['vehicle_problem'] = $query[0]->vehicle_problem;
       $data['assign_to'] = $query[0]->assign_to;
       $assign_to = $query[0]->assign_to;
       $dealerName = $query[0]->dealerName;
       $dealerCode = $query[0]->dealerCode;
       
       $data['supportCenterCodeName'] = $dealerCode.' - '.$dealerName;
       $data['estimated_response_time'] = $query[0]->estimated_response_time;
       $data['actual_response_time'] = $query[0]->actual_response_time;
       $data['tat_scheduled'] = $query[0]->tat_scheduled;
       $data['acceptance'] = $query[0]->acceptance;
       $data['dealer_mob_number'] = $query[0]->dealer_mob_number;
       $data['dealer_alt_mob_number'] = $query[0]->dealer_alt_mob_number;
       $data['remark_type'] = $query[0]->remark_type;
       $data['disposition'] = $query[0]->disposition;
       $data['agent_remark'] = $query[0]->agent_remark;
       $data['standard_remark'] = $query[0]->standard_remark;
       $data['assign_remarks'] = $query[0]->assign_remarks;
       $data['vehicle'] = $query[0]->vehicle;
       $data['vehicle_model'] = $query[0]->vehicle_model;
       $data['reg_number'] = $query[0]->reg_number;
       $data['chassis_number'] = $query[0]->chassis_number;
       $data['engine_number'] = $query[0]->engine_number;
       $data['vehicle_segment'] = $query[0]->vehicle_segment;
       $data['purchase_date'] = $query[0]->purchase_date;
       $data['add_blue_use'] = $query[0]->add_blue_use;
       $data['engine_emmission_type'] = $query[0]->engine_emmission_type;
       $data['owner_name'] = $query[0]->owner_name;
       $data['owner_mob'] = $query[0]->owner_mob;
       $data['owner_landline'] = $query[0]->owner_landline;
       $data['owner_cat'] = $query[0]->owner_cat;
       $data['owner_company'] = $query[0]->owner_company;
       $data['contact_name'] = $query[0]->contact_name;
       $data['mob'] = $query[0]->mob;
       $data['owner_contact_email'] = $query[0]->owner_contact_email;
       $data['caller_type'] = $query[0]->caller_type;
       $data['caller_name'] = $query[0]->caller_name;
       $data['caller_contact'] = $query[0]->caller_contact;
       $data['location'] = $query[0]->location;
       $data['landmark'] = $query[0]->landmark;
       $data['vehicle_type'] = $query[0]->vehicle_type;
       $data['vehicle_movable'] = $query[0]->vehicle_movable;
       $data['district'] = $query[0]->district;
       $data['state'] = $query[0]->state;
       $data['city'] = $query[0]->city;
       $data['latitude'] = $query[0]->latitude;
       $data['longitude'] = $query[0]->longitude;
       $data['feedback_rating'] = $query[0]->feedback_rating;
       $data['feedback_desc'] = $query[0]->feedback_desc;
       $data['alse_mail'] = $query[0]->alse_mail;
       $data['asm_mail'] = $query[0]->asm_mail;
       $now = Carbon::now();
        $t1 = Carbon::parse($now);
        $t2 = Carbon::parse($ticketCreatedDate);
        $diff = $t1->diff($t2);
        $timeSinceTicketCreated = 'Day: '.$diff->d.', Hour: '.$diff->h.', Minute: '.$diff->m;
        //dd($diff);
        $data['timeSinceTicketCreated'] = $timeSinceTicketCreated;
      
       $dealerSql = DB::select("Select latitude,longitude,dealer_name from mstr_dealer where id = :assign_to",["assign_to"=>$assign_to]);
       $latitude = $dealerSql[0]->latitude;
       $longitude = $dealerSql[0]->longitude;
       $data['assignedName'] = $dealerSql[0]->dealer_name;
       
       $data['history'] = DB::select("Select r.id, r.complaint_number, r.remark_type, r.employee_name, r.employee_id, r.dealer_mob_number, r.dealer_alt_mob_number, r.assign_to, r.disposition, r.agent_remark, r.assign_remarks, r.estimated_response_time,r.actual_response_time, r.tat_scheduled, r.acceptance, r.created_at,r.feedback_rating,r.feedback_desc, d.dealer_name from remarks as r left join mstr_dealer as d on d.id = r.assign_to  where complaint_number=:complaint_number order by r.created_at desc",["complaint_number"=>$complaint_number]);
         return view('ticket_report',$data);
    }catch (\Exception $ex){
      $notification = array(
               'message' => $ex->getMessage().' Line: '.$ex->getLine(),
               'alert-type' => 'error'
           );
           return back()->with($notification);
       }
  }

  public function complaintNumber(Request $request){
    $keyword = $request->input('keyword');
    if (Auth::user()->role == '29' || Auth::user()->role == '30' ) {
      $query = DB::select("SELECT complaint_number FROM cases WHERE complaint_number like '".$keyword."%' ORDER BY complaint_number LIMIT 0,6");
    }else{
      $query = DB::select("SELECT complaint_number FROM cases WHERE complaint_number like '".$keyword."%' and FIND_IN_SET(assign_to, '$sess_dealer') ORDER BY complaint_number LIMIT 0,6");
    }
    if(sizeof($query)>0){
        
        $data = '<ul id="country-list">';
        foreach($query as $row) {
          $compNumber = trim($row->complaint_number);
          $data .= "<li onClick=selectCountry('$compNumber'); style='cursor: pointer;'>$compNumber</li>";
        }
        echo $data; 
      }    
  }

  public function getTicketReport($get_complaint_number){ 
    try{
     
      $complaint_number_search = $get_complaint_number;
      
       $data['remark_type_table'] = DB::select("Select id, type from remark_type");
       /* $data['vehicleModels'] = DB::select("Select id, vehicle_model, vehicle_segment, add_blue_use, engine_emmission_type from mstr_vehicle_models"); */
       $data['divshows'] = 'true';
       $data['complaint_number_search'] =  $complaint_number_search;
        
       $query = DB::select("select c.id as caseId, c.complaint_number, c.vehicleId, c.ownerId, c.customer_contact_id, c.callerId, c.from_where, c.to_where, c.highway, c.ticket_type, c.aggregate, c.vehicle_problem, c.assign_to, c.dealer_mob_number, c.dealer_alt_mob_number, c.remark_type, c.disposition, c.agent_remark, c.standard_remark, c.assign_remarks, c.estimated_response_time, c.actual_response_time, c.tat_scheduled, c.acceptance, c.latitude, c.longitude,c.feedback_rating,c.feedback_desc,c.location,c.landmark,c.state as stateId,c.city as cityId,c.district, c.vehicle_type, c.vehicle_movable, v.vehicle, v.vehicle_model, v.reg_number, v.chassis_number, v.engine_number, v.vehicle_segment, v.purchase_date, v.add_blue_use, v.engine_emmission_type, o.owner_name, o.owner_mob, o.owner_landline, o.owner_cat, o.owner_company,o.alse_mail,o.asm_mail, oc.contact_name, oc.mob,oc.owner_contact_email,cal.caller_type, cal.caller_name, cal.caller_contact, s.state, city.city,c.created_at as ticketCreatedDate,rem.created_at as closedDate,(Select employee_name from remarks where id = (Select min(id) as id from remarks where complaint_number=c.complaint_number)) as caseCreatedBy,(Select sac_code from mstr_dealer where id = c.assign_to) as dealerCode,(Select dealer_name from mstr_dealer where id = c.assign_to) as dealerName, (select name from users where find_in_set(c.assign_to,dealer_id) and role = 1 ) as ALSEName,(select mobile from users where find_in_set(c.assign_to,dealer_id) and role = 1 ) as ALSEMobile
        from cases as c left join mstr_vehicle as v on v.id = c.vehicleId left join mstr_owner as o on o.id = c.ownerId and o.id = c.ownerId 
       left join mstr_owner_contact as oc on oc.id = c.customer_contact_id and oc.owner_id = c.ownerId left join mstr_caller as cal on cal.id = c.callerId left join mstr_caller_state as s on s.id = c.state left join mstr_caller_city as city on city.id = c.city left join remarks as rem on rem.complaint_number = c.complaint_number and rem.remark_type='Closed' where c.complaint_number = '$complaint_number_search'");
        
       $data['ALSEName'] = $query[0]->ALSEName!=''?$query[0]->ALSEName:'NA';
       $data['ALSEMobile'] = $query[0]->ALSEMobile!=''?$query[0]->ALSEMobile:'NA';
       $data['caseCreatedBy'] = $query[0]->caseCreatedBy!=''?$query[0]->caseCreatedBy:'NA';
       $data['closedDate'] = $query[0]->closedDate!=''?$query[0]->closedDate:'NA';
       $data['ticketCreatedDate'] = $query[0]->ticketCreatedDate;
       $ticketCreatedDate= $query[0]->ticketCreatedDate;
       $data['vehicleId'] = $query[0]->vehicleId;
       $data['caseId'] = $query[0]->caseId;
       $data['complaint_number'] = $query[0]->complaint_number;
       $complaint_number = $query[0]->complaint_number;
       $data['from_where'] = $query[0]->from_where;
       $data['highway'] = $query[0]->highway;
       $data['to_where'] = $query[0]->to_where;
       $data['ticket_type'] = $query[0]->ticket_type;
       $data['aggregate'] = $query[0]->aggregate;
       $data['vehicle_problem'] = $query[0]->vehicle_problem;
       $data['assign_to'] = $query[0]->assign_to;
       $assign_to = $query[0]->assign_to;
       $dealerName = $query[0]->dealerName;
       $dealerCode = $query[0]->dealerCode;
       
       $data['supportCenterCodeName'] = $dealerCode.' - '.$dealerName;
       $data['estimated_response_time'] = $query[0]->estimated_response_time;
       $data['actual_response_time'] = $query[0]->actual_response_time;
       $data['tat_scheduled'] = $query[0]->tat_scheduled;
       $data['acceptance'] = $query[0]->acceptance;
       $data['dealer_mob_number'] = $query[0]->dealer_mob_number;
       $data['dealer_alt_mob_number'] = $query[0]->dealer_alt_mob_number;
       $data['remark_type'] = $query[0]->remark_type;
       $data['disposition'] = $query[0]->disposition;
       $data['agent_remark'] = $query[0]->agent_remark;
       $data['standard_remark'] = $query[0]->standard_remark;
       $data['assign_remarks'] = $query[0]->assign_remarks;
       $data['vehicle'] = $query[0]->vehicle;
       $data['vehicle_model'] = $query[0]->vehicle_model;
       $data['reg_number'] = $query[0]->reg_number;
       $data['chassis_number'] = $query[0]->chassis_number;
       $data['engine_number'] = $query[0]->engine_number;
       $data['vehicle_segment'] = $query[0]->vehicle_segment;
       $data['purchase_date'] = $query[0]->purchase_date;
       $data['add_blue_use'] = $query[0]->add_blue_use;
       $data['engine_emmission_type'] = $query[0]->engine_emmission_type;
       $data['owner_name'] = $query[0]->owner_name;
       $data['owner_mob'] = $query[0]->owner_mob;
       $data['owner_landline'] = $query[0]->owner_landline;
       $data['owner_cat'] = $query[0]->owner_cat;
       $data['owner_company'] = $query[0]->owner_company;
       $data['contact_name'] = $query[0]->contact_name;
       $data['mob'] = $query[0]->mob;
       $data['owner_contact_email'] = $query[0]->owner_contact_email;
       $data['caller_type'] = $query[0]->caller_type;
       $data['caller_name'] = $query[0]->caller_name;
       $data['caller_contact'] = $query[0]->caller_contact;
       $data['location'] = $query[0]->location;
       $data['landmark'] = $query[0]->landmark;
       $data['vehicle_type'] = $query[0]->vehicle_type;
       $data['vehicle_movable'] = $query[0]->vehicle_movable;
       $data['district'] = $query[0]->district;
       $data['state'] = $query[0]->state;
       $data['city'] = $query[0]->city;
       $data['latitude'] = $query[0]->latitude;
       $data['longitude'] = $query[0]->longitude;
       $data['feedback_rating'] = $query[0]->feedback_rating;
       $data['feedback_desc'] = $query[0]->feedback_desc;
       $data['alse_mail'] = $query[0]->alse_mail;
       $data['asm_mail'] = $query[0]->asm_mail;
       $now = Carbon::now();
        $t1 = Carbon::parse($now);
        $t2 = Carbon::parse($ticketCreatedDate);
        $diff = $t1->diff($t2);
        $timeSinceTicketCreated = 'Day: '.$diff->d.', Hour: '.$diff->h.', Minute: '.$diff->m;
        //dd($diff);
        $data['timeSinceTicketCreated'] = $timeSinceTicketCreated;
      
       $dealerSql = DB::select("Select latitude,longitude,dealer_name from mstr_dealer where id = $assign_to");
       $latitude = $dealerSql[0]->latitude;
       $longitude = $dealerSql[0]->longitude;
       $data['assignedName'] = $dealerSql[0]->dealer_name;
       
       $data['history'] = DB::select("Select r.id, r.complaint_number, r.remark_type, r.employee_name, r.employee_id, r.dealer_mob_number, r.dealer_alt_mob_number, r.assign_to, r.disposition, r.agent_remark, r.assign_remarks, r.estimated_response_time, r.tat_scheduled, r.acceptance, r.created_at,r.feedback_rating,r.feedback_desc, d.dealer_name from remarks as r left join mstr_dealer as d on d.id = r.assign_to where complaint_number='$complaint_number' order by r.created_at desc");
         return view('ticket_report',$data);
    }catch (\Exception $ex){
      $notification = array(
               'message' => $ex->getMessage().' Line: '.$ex->getLine(),
               'alert-type' => 'error'
           );
           return back()->with($notification);
       }
  }
  public function VahanAPiReport(){
    try {
      $dataR = date('Y-m-d H:i:s');
      return Excel::download(new VahanAPiExport(), "VahanAPI_Report_$dataR.xlsx");
    } catch (\Exception $th) {
      dd($th->getMessage());
    }
  }
  public function consolidatedClosedReport(){
		try{
			
				
				$date = date('Y-m-d');
        $zone = Auth::user()->zone;
        $state = Auth::user()->state;
        $dealer_id = Auth::user()->dealer_id;
				
				$data['statusData'] = DB::select("Select id,type from remark_type order by type ASC");
        if( Auth::user()->role == '29' || Auth::user()->role == '30'){
          $data['regionData'] = DB::select("Select id,region from mstr_region order by region ASC");
          $data['dealerData']=DB::table("mstr_dealer")->select('id','dealer_name')->get();
        }else{
          $data['regionData'] = DB::select("Select id,region from mstr_region where id in ($zone) order by region ASC");
          //$data['dealerData']=DB::table("mstr_dealer")->select('id','dealer_name')->where('state',$state)->get();
          $data['dealerData']=DB::select("Select id,dealer_name from mstr_dealer where id in ($dealer_id) order by dealer_name ASC");
        }
				return view('consolidated_closed_report',$data);
			
		}catch (\Exception $ex) {			
			$notification = array(
	                'message' => $ex->getMessage(),
	                'alert-type' => 'error'
	            );
            return back()->with($notification);
        }
			
	}
	public function storeConsolidatedClosedReport(Request $request){
    try {
     // dd($request->input());
			$datefrom = $request->input('datefrom');
			$dateto = $request->input('dateto');
			$zone = $request->input('zone');
			$state = $request->input('state');
			$city = $request->input('city');
			$dealer = $request->input('dealer');
			$ticketStatus = $request->input('ticketStatus');
      $tat = $request->input('tat');
      // dd($tat);
      $statusArr='';
      foreach ($ticketStatus as $row) {
        $statusArr .= '"'.$row.'",';
      }
      
      $statusImp = rtrim($statusArr,',');
      $zoneImplode = implode(',',$request->input('zone'));
			$stateImplode = implode(',',$request->input('state'));
			$cityImplode = implode(',',$request->input('city'));
			$dealerImplode = implode(',',$request->input('dealer'));

      $data['zone'] = $zone;
      $data['zoneImplode'] = trim($zoneImplode);
      $data['stateImplode'] = $stateImplode;
      $data['cityImplode'] = $cityImplode;
      $data['dealerImplode'] = $dealerImplode;
			$data['dealer'] = $dealer;
			$data['datefrom'] = $datefrom;
			$data['dateto'] = $dateto;
			$data['ticketStatus'] = $ticketStatus;
			$data['tat'] = $tat;
      $data['statusData'] = DB::select("Select id,type from remark_type");
			
      $sess_dealer =Auth::user()->dealer_id;

      if(Auth::user()->role == '29' || Auth::user()->role == '30' || Auth::user()->role == '87'){ 
        $data['regionData'] = DB::select("Select id,region from mstr_region order by region ASC"); 
      
        $data['consolidatedReport'] = DB::select("select distinct(c.complaint_number) as complaint_number,assCr.employee_name as createdby,remClosed.employee_name as closedby,remComplete.employee_name as completedby,(case when c.latitude ='' then 'No' else 'Yes' end) as used_google_map,c.id as caseId, c.vehicleId, c.ownerId, c.customer_contact_id, c.callerId, c.from_where, c.to_where, c.highway, c.ticket_type, c.aggregate, c.vehicle_problem, c.assign_to, c.dealer_mob_number, c.dealer_alt_mob_number, c.remark_type, c.disposition, c.agent_remark, c.standard_remark, c.assign_remarks, c.estimated_response_time, c.actual_response_time, c.tat_scheduled, c.acceptance, c.latitude, c.longitude,c.feedback_rating,c.feedback_desc,c.location,c.landmark,c.state as stateId,c.city as cityId,c.district,c.created_at as complaintDate,c.updated_at as complaintUpdate,c.restoration_type,c.response_delay_reason,c.source,c.restoration_delay,c.so_number,c.jobcard_number,c.actual_response_time_customer,c.tat_scheduled_customer,c.reason_reassign,c.SPOC, v.vehicle, v.vehicle_model, v.reg_number, v.chassis_number, v.engine_number, v.vehicle_segment, v.purchase_date, v.add_blue_use, v.engine_emmission_type, o.owner_name, o.owner_mob, o.owner_landline, o.owner_cat, o.owner_company,o.alse_mail,o.asm_mail, oc.contact_name, oc.mob,oc.owner_contact_email,cal.caller_type, cal.caller_name, cal.caller_contact, cal.caller_language, c.vehicle_type, c.vehicle_movable, s.state, city.city, del.dealer_name,del.sac_code,del.dealer_type as dealer_type,delZone.region as delZoneName,delState.state as stateName,delCity.city as delCityName ,remComplete.created_at as completionDate,remClosed.created_at as closedDate,
        (SELECT CASE WHEN role=76 THEN concat(name,'~~',mobile)  ELSE 'NA~~NA' END as Support_Contact_Person	 FROM users where find_in_set(c.assign_to,dealer_id) and role=76 and flag=1  limit 1) as Support_Contact_Person,
        (SELECT CASE WHEN role=1 THEN concat(name,'~~',mobile) ELSE 'NA~~NA' END as alsedetails FROM users where flag=1 and role=1 and find_in_set(c.assign_to,dealer_id)  limit 1) as alsedetails,
        (select employee_name from remarks where id = (Select min(id) from remarks where complaint_number=c.complaint_number group by complaint_number)) as firstcallagent,
        (SELECT created_at FROM remarks where complaint_number = c.complaint_number ORDER BY id ASC LIMIT 1, 1) as secondcallagentTime,
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
        vbt.created_at as vbt_created_at,vbt.updated_at as vbt_updated_at,
        gppce.created_at as gppce_created_at,gppce.updated_at as gppce_updated_at
        from cases as c 
        left join mstr_vehicle as v on v.id = c.vehicleId 
        left join mstr_owner as o on o.id = c.ownerId and o.id = c.ownerId 
        left join mstr_owner_contact as oc on oc.id = c.customer_contact_id and oc.owner_id = c.ownerId 
        left join mstr_caller as cal on cal.id = c.callerId 
        left join mstr_caller_state as s on s.id = c.state 
        left join mstr_caller_city as city on city.id = c.city 
        left join mstr_dealer as del on del.id = c.assign_to 
        left join mstr_region as delZone on delZone.id = del.zone 
        left join mstr_state as delState on delState.id = del.state 
        left join mstr_city as delCity on delCity.id = del.city 
        left join remarks as remComplete on remComplete.complaint_number = c.complaint_number and remComplete.remark_type in ('Completed','Customer Confirmation Completed') 
        left join remarks as remClosed on remClosed.complaint_number = c.complaint_number and remClosed.remark_type='Closed' 
        left join remarks as assCr on assCr.complaint_number = c.complaint_number and CONVERT(DATE_FORMAT(c.created_at,'%Y-%m-%d-%H:%i:00'),DATETIME) = CONVERT(DATE_FORMAT(assCr.created_at,'%Y-%m-%d-%H:%i:00'),DATETIME) 
        left join remarks as remreas on remreas.complaint_number = c.complaint_number and remreas.remark_type = 'Reassigned support' 
        left join mstr_dealer as ad on ad.id = c.assign_to 
        left join mstr_region as ag on ag.id = ad.zone  
        left join ticket_hold as aca on aca.complaint_number = c.complaint_number and aca.remark_type = 'Awaiting customer approval'
        left join ticket_hold as acp on acp.complaint_number = c.complaint_number and acp.remark_type = 'Awaiting customer Payment'
        left join ticket_hold as apc on apc.complaint_number = c.complaint_number and apc.remark_type = 'Awaiting parts from customer'
        left join ticket_hold as vbt on vbt.complaint_number = c.complaint_number and vbt.remark_type = 'Vehicle being Towed'
        left join ticket_hold as gppce on gppce.complaint_number = c.complaint_number and gppce.remark_type = 'Gate Pass Pending From Customer End'
        where  cast(remClosed.created_at as date) between cast('$datefrom' as date) and cast('$dateto' as date) and c.assign_to in ($dealerImplode) and c.remark_type in ($statusImp) and c.created_at <=DATE_ADD(now() , INTERVAL - $tat HOUR) and c.complaint_number!='' group by complaint_number"); 
        return view('consolidated_closed_report',$data);
      }
      else{
        $ses_zone = Auth::user()->zone;
        $location = Auth::user()->city;
        $data['regionData'] = DB::select("Select id,region from mstr_region where id in ($ses_zone) order by region ASC");
        $data['consolidatedReport'] = DB::select("select distinct(c.complaint_number) as complaint_number,assCr.employee_name as createdby,remClosed.employee_name as closedby,remComplete.employee_name as completedby,(case when c.latitude ='' then 'No' else 'Yes' end) as used_google_map,c.id as caseId, c.vehicleId, c.ownerId, c.customer_contact_id, c.callerId, c.from_where, c.to_where, c.highway, c.ticket_type, c.aggregate, c.vehicle_problem, c.assign_to, c.dealer_mob_number, c.dealer_alt_mob_number, c.remark_type, c.disposition, c.agent_remark, c.standard_remark, c.assign_remarks, c.estimated_response_time, c.actual_response_time, c.tat_scheduled, c.acceptance, c.latitude, c.longitude,c.feedback_rating,c.feedback_desc,c.location,c.landmark,c.state as stateId,c.city as cityId,c.district,c.created_at as complaintDate,c.updated_at as complaintUpdate,c.restoration_type,c.response_delay_reason,c.source,c.restoration_delay,c.so_number,c.jobcard_number,c.actual_response_time_customer,c.tat_scheduled_customer,c.reason_reassign,c.SPOC, v.vehicle, v.vehicle_model, v.reg_number, v.chassis_number, v.engine_number, v.vehicle_segment, v.purchase_date, v.add_blue_use, v.engine_emmission_type, o.owner_name, o.owner_mob, o.owner_landline, o.owner_cat, o.owner_company,o.alse_mail,o.asm_mail, oc.contact_name, oc.mob,oc.owner_contact_email,cal.caller_type, cal.caller_name, cal.caller_contact, cal.caller_language, c.vehicle_type, c.vehicle_movable, s.state, city.city, del.dealer_name,del.sac_code,del.dealer_type as dealer_type,delZone.region as delZoneName,delState.state as stateName,delCity.city as delCityName ,remComplete.created_at as completionDate,remClosed.created_at as closedDate,(SELECT CASE WHEN role=76 THEN concat(name,'~~',mobile) ELSE 'NA~~NA' END as Support_Contact_Person	 FROM users where role=76 and flag=1 and dealer_id in (c.assign_to) limit 1) as Support_Contact_Person,(SELECT CASE WHEN role=1 THEN concat(name,'~~',mobile) ELSE 'NA~~NA' END as alsedetails FROM users where role=1 and flag=1 and dealer_id in (c.assign_to) limit 1) as alsedetails,
        (select employee_name from remarks where id = (Select min(id) from remarks where complaint_number=c.complaint_number group by complaint_number)) as firstcallagent,
        (SELECT created_at FROM remarks where complaint_number = c.complaint_number ORDER BY id ASC LIMIT 1, 1) as secondcallagentTime,
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
        vbt.created_at as vbt_created_at,vbt.updated_at as vbt_updated_at,
        gppce.created_at as gppce_created_at,gppce.updated_at as gppce_updated_at
        from cases as c left join mstr_vehicle as v on v.id = c.vehicleId left join mstr_owner as o on o.id = c.ownerId and o.id = c.ownerId left join mstr_owner_contact as oc on oc.id = c.customer_contact_id and oc.owner_id = c.ownerId left join mstr_caller as cal on cal.id = c.callerId  left join mstr_caller_state as s on s.id = c.state left join mstr_caller_city as city on city.id = c.city left join mstr_dealer as del on del.id = c.assign_to left join mstr_region as delZone on delZone.id = del.zone left join mstr_state as delState on delState.id = del.state left join mstr_city as delCity on delCity.id = del.city left join remarks as remComplete on remComplete.complaint_number = c.complaint_number and remComplete.remark_type in ('Completed','Customer Confirmation Completed') left join remarks as remClosed on remClosed.complaint_number = c.complaint_number and remClosed.remark_type='Closed' left join remarks as assCr on assCr.complaint_number = c.complaint_number and CONVERT(DATE_FORMAT(c.created_at,'%Y-%m-%d-%H:%i:00'),DATETIME) = CONVERT(DATE_FORMAT(assCr.created_at,'%Y-%m-%d-%H:%i:00'),DATETIME) left join remarks as remreas on remreas.complaint_number = c.complaint_number and remreas.remark_type = 'Reassigned support' left join mstr_dealer as ad on ad.id = c.assign_to left join mstr_region as ag on ag.id = ad.zone 
        left join ticket_hold as aca on aca.complaint_number = c.complaint_number and aca.remark_type = 'Awaiting customer approval'
        left join ticket_hold as acp on acp.complaint_number = c.complaint_number and acp.remark_type = 'Awaiting customer Payment'
        left join ticket_hold as apc on apc.complaint_number = c.complaint_number and apc.remark_type = 'Awaiting parts from customer'
        left join ticket_hold as vbt on vbt.complaint_number = c.complaint_number and vbt.remark_type = 'Vehicle being Towed'
        left join ticket_hold as gppce on gppce.complaint_number = c.complaint_number and gppce.remark_type = 'Gate Pass Pending From Customer End'
        where cast(remClosed.created_at as date) between cast('$datefrom' as date) and cast('$dateto' as date) and c.assign_to in ($dealerImplode) and c.remark_type in ($statusImp) and c.created_at <=DATE_ADD(now() , INTERVAL - $tat HOUR) and c.complaint_number!='' group by complaint_number");
        
        return view('consolidated_closed_report',$data);
      }
			

		} catch (\Exception $ex) {
			$notification = array(
			'message' => $ex->getMessage().' Line : '.$ex->getLine(),
			'alert-type' => 'error'
			);
			return back()->with($notification);
		}
	}
}
