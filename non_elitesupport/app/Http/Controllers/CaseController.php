<?php
 
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Session;
use Redirect;
use Mail;
use App\classes\ServerValidation;
use App\classes\AccessControl;
use Carbon\Carbon;
use Auth;
class CaseController  extends Controller{
    
    public function __construct(){
    	date_default_timezone_set('Asia/Kolkata');
		DB::select("SET sql_mode=''");
	}
	
	public function getccm(){
		try{
			$result=DB::select("SELECT distinct mode_name FROM mstr_contact_center_module order by mode_name;");
					foreach($result as $value)
					{
					Echo $str= $value->mode_name.',';
					}
		} catch (\Exception $ex) 
	    {
			$notification = array(
	                'message' => $ex->getMessage(),
	                'alert-type' => 'error'
	            );
            return back()->with($notification);
        }
	}
	public function getcomplainttype(Request $request){
		try{
			$comp = $request->input('comp');
		$result=DB::select("SELECT distinct compalaint_sub_type  FROM mstr_complaint_type where compalaint_type='".$comp."' order by compalaint_sub_type;");
					foreach($result as $value)
					{
					Echo $str= $value->compalaint_sub_type.',';
					}
		}catch (\Exception $ex){
			$notification = array(
                'message' => $ex->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
	}
	public function getDealer(Request $request){
		try{
			$zone = $request->input('zone');
			$state = $request->input('state');
			$city = $request->input('city');
			$product = $request->input('product');
			$result=DB::select("call getDealer_and_Code('".$zone."','".$state."','".$city."','".$product."')");
			foreach($result as $value)
			{
				Echo $str= $value->dealer;
			}
		}catch (\Exception $ex){
			$notification = array(
                'message' => $ex->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
	}
	public function getSubproduct(Request $request){
		try{
			$zone = $request->input('zone');
			$state = $request->input('state');
			$city = $request->input('city');
			$product = $request->input('product');
			$result=DB::select("call getDealer_and_Code('".$zone."','".$state."','".$city."','".$product."')");
			foreach($result as $value){
				Echo $str= $value->dealer;
			}
		}catch (\Exception $ex){
			$notification = array(
                'message' => $ex->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
	}
	public function getlocation(Request $request){
		try{
			$zone = $request->input('zone');
			//$result=DB::select("call getDealer_and_Code('".$zone."')");
			$result=DB::select("SELECT distinct city FROM mstr_state_cilty_zone  where zone='".$zone."' order by city");
			foreach($result as $value){
				Echo $str= $value->city.',';
			}
		}catch (\Exception $ex){
			$notification = array(
                'message' => $ex->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
	}
	public function updateCase($id){	
	 	try{
	 		
				$data['remark_type_table'] = DB::select("Select id, type from remark_type order by type ASC");
				
				$query = DB::table('cases as c')->select("c.id as caseId", "c.complaint_number", "c.vehicleId", "c.ownerId", "c.customer_contact_id", "c.callerId", "c.from_where", "c.to_where", "c.highway", "c.ticket_type", "c.aggregate", "c.vehicle_problem", "c.assign_to", "c.dealer_mob_number", "c.dealer_alt_mob_number", "c.remark_type", "c.disposition", "c.agent_remark", "c.standard_remark", "c.assign_remarks", "c.estimated_response_time", "c.actual_response_time", "c.tat_scheduled", "c.acceptance", "c.latitude", "c.longitude", "c.feedback_rating","c.feedback_desc","c.location","c.landmark","c.state as stateId","c.city as cityId","c.district", "c.vehicle_type", "c.vehicle_movable","c.restoration_type","c.response_delay_reason","c.source","c.restoration_delay","c.so_number","c.jobcard_number","c.actual_response_time_customer","c.tat_scheduled_customer","c.reason_reassign","c.SPOC","d.night_spoc_1_name","d.night_spoc_1_number","d.night_spoc_2_name","d.night_spoc_2_number", "v.vehicle", "v.vehicle_model", "v.reg_number", "v.chassis_number", "v.engine_number", "v.vehicle_segment", "v.purchase_date", "v.add_blue_use", "v.engine_emmission_type", "o.owner_name", "o.owner_mob", "o.owner_landline", "o.owner_cat", "o.owner_company","o.alse_mail","o.asm_mail", "oc.contact_name", "oc.mob","oc.owner_contact_email","cal.caller_type", "cal.caller_name", "cal.caller_contact", "cal.caller_language", "cal.caller_contact_alt", "s.state", "citt.city","c.assign_work_manager", "c.assign_work_manager_mobile", "c.followup_time", "c.created_at as createdDate")->leftJoin("mstr_vehicle as v","v.id","=","c.vehicleId")->leftJoin("mstr_owner as o","o.id","=","c.ownerId")->leftJoin("mstr_owner_contact as oc","oc.id","=","c.customer_contact_id","and oc.owner_id","=","c.ownerId")->leftJoin("mstr_caller as cal","cal.id","=","c.callerId","and cal.owner_id", "=", "c.ownerId")->leftJoin("mstr_caller_state as s","s.id","=","c.state")->leftJoin("mstr_caller_city as citt","citt.id","=","c.city")->leftJoin("mstr_dealer as d","d.id","=","c.assign_to")->where("c.id",$id)->get();

				$complaint_number = $query[0]->complaint_number;
				$reasingDateQuery = DB::select("select created_at from remarks where complaint_number='$complaint_number' and remark_type = 'Reassigned support' order by id desc;");
				$data['reassignDate'] = sizeof($reasingDateQuery)>0?$reasingDateQuery[0]->created_at:'';
				
				$data['responseDelayReason'] = DB::select("Select id, reason from response_delay_reason order by reason ASC");  
				$data['restoration_type_db'] = $query[0]->restoration_type;

				$data['so_number'] = $query[0]->so_number;
				$data['jobcard_number'] = $query[0]->jobcard_number;
				$data['actual_response_time_customer'] = $query[0]->actual_response_time_customer;
				$data['tat_scheduled_customer'] = $query[0]->tat_scheduled_customer;
				
				$data['response_delay_reason_db'] = $query[0]->response_delay_reason;
				$data['vehicleId'] = $query[0]->vehicleId;
				$data['caseId'] = $query[0]->caseId;
				$data['complaint_number'] = $query[0]->complaint_number;
				// $complaint_number = $query[0]->complaint_number;
				$data['from_where'] = $query[0]->from_where;
				$data['highway'] = $query[0]->highway;
				$data['to_where'] = $query[0]->to_where;
				$data['ticket_type'] = $query[0]->ticket_type;
				$data['aggregate'] = $query[0]->aggregate;
				$data['vehicle_problem'] = $query[0]->vehicle_problem;
				$data['assign_to'] = $query[0]->assign_to;
				$data['source'] = $query[0]->source;
				$data['followup_time'] = $query[0]->followup_time;
				$assign_to = $query[0]->assign_to;
				$stateId = $query[0]->stateId;
				$data['createdDate'] = $query[0]->createdDate;
				
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
				$data['caller_contact_alt'] = $query[0]->caller_contact_alt;
				$data['caller_language'] = $query[0]->caller_language;
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
				$data['assign_work_manager'] = $query[0]->assign_work_manager;
				$data['assign_work_manager_mobile'] = $query[0]->assign_work_manager_mobile;
				$data['restoration_delay'] = $query[0]->restoration_delay;
				$data['night_spoc_1_name'] = $query[0]->night_spoc_1_name;
				$data['night_spoc_1_number'] = $query[0]->night_spoc_1_number;
				$data['night_spoc_2_name'] = $query[0]->night_spoc_2_name;
				$data['night_spoc_2_number'] = $query[0]->night_spoc_2_number;
				$data['reason_reassign'] = $query[0]->reason_reassign;
				$data['SPOC'] = $query[0]->SPOC;
				$sqlStateNew = DB::select("Select id,state from mstr_caller_state where id =$stateId");
				$stateName123 = strtolower($sqlStateNew[0]->state);
				
				
				$dealerSql = DB::select("Select latitude,longitude,dealer_name from mstr_dealer where id = $assign_to");
				$latitude = $dealerSql[0]->latitude;
				$longitude = $dealerSql[0]->longitude;
				$data['assignedName'] = $dealerSql[0]->dealer_name;
				$data['sqlLatLong'] = DB::select("SELECT id,latitude,longitude, concat(dealer_name, ' - ', IFNULL(SC_City_Name,'')) as dealer_name FROM mstr_dealer where flag=1 order by dealer_name ASC");
				
				$data['history'] = DB::select("Select r.id, r.complaint_number, r.remark_type, r.employee_name, r.employee_id, r.dealer_mob_number, r.dealer_alt_mob_number, r.assign_to, r.disposition, r.agent_remark, r.assign_remarks, r.estimated_response_time, r.actual_response_time, r.tat_scheduled, r.acceptance, r.created_at,r.feedback_rating,r.feedback_desc, d.dealer_name from remarks as r left join mstr_dealer as d on d.id = r.assign_to where complaint_number=:complaint_number order by r.created_at desc",['complaint_number'=>$complaint_number]);
				
				$data['msu_history']=DB::select("Select id,restoration_type, actual_response_time, restoration_time, ticket_num, reg_no, chasi_no, eng_no, ticket_type, dealer_name, vin_no, status, msv_type, msv_reg_no, mechinic_name, mechinic_number, remark, start_time, response_time_No, restore_time_No, pause_time_No, end_time, url, vehicle_mode, updated_at , dealer_code, cc_status, aggregate,feedback_rating,feedback_desc from updatetickets where ticket_num = :complaint_number order by updated_at DESC",['complaint_number'=>$complaint_number]);

				$data['restoration_delay_data'] = DB::select("Select title from restoration_delay order by title ASC");
				$msuQuery = DB::select("SELECT restoration_type,actual_response_time,restoration_time FROM updatetickets where ticket_num='$complaint_number' order by id desc limit 1;");
 				if(sizeof($msuQuery)>0){
 					$data['msuActualResponseTime'] = $msuQuery[0]->actual_response_time!=''?$msuQuery[0]->actual_response_time:'';
 					$data['msuRestorationTime'] = $msuQuery[0]->restoration_time!=''?$msuQuery[0]->restoration_time:'';
 					$data['msuRestorationType'] = $msuQuery[0]->restoration_type!=''?$msuQuery[0]->restoration_type:'';
 				}else{
 					$data['msuActualResponseTime']='';
 					$data['msuRestorationTime']='';
 					$data['msuRestorationType']='';
 				}
			  	return view('updatecase',$data);
	 	}catch (\Exception $ex){
			$notification = array(
                'message' => $ex->getMessage().' Line: '.$ex->getLine(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
	 }
	public function caseList(){
		
	 	try{
	 		
			
		 	$userId = Auth::user()->sesUserId;
		 	$role = Auth::user()->role;
		 	$dealer_id = Auth::user()->dealer_id;
			 
		 	//$dealId = explode(",",$dealer_id);
		 	$dealId = $dealer_id;
			
			$currentQtr = DB::select("select NOW() as todate, (NOW() -INTERVAL 2 DAY) as fromdate;");
			// $DateTo = $currentQtr[0]->todate;
			// $DateFrom = $currentQtr[0]->fromdate;
			$DateTo = $currentQtr[0]->todate;
			$DateFrom = $currentQtr[0]->fromdate;
			$data['datefrom'] = $DateFrom;
			$data['dateto'] = $DateTo;
			 $sess_zone =Auth::user()->zone;
			 $sess_state =Auth::user()->state;
			 $sess_city =Auth::user()->city;
			 $sess_dealer =Auth::user()->dealer_id;
			if (Auth::user()->role == '29' || Auth::user()->role == '30' || Auth::user()->role == '87' ) {
				$data['rowData'] = DB::select("select c.id, c.complaint_number, c.vehicleId, c.ownerId, c.customer_contact_id, c.callerId, c.from_where, c.to_where, c.highway, c.ticket_type, c.aggregate, c.vehicle_problem, c.assign_to, c.dealer_mob_number, c.dealer_alt_mob_number, c.remark_type, c.disposition, c.agent_remark, c.standard_remark, c.assign_remarks, c.created_at, c.tat_scheduled, del.dealer_name, v.reg_number,v.chassis_number, o.owner_name, o.owner_company, oc.contact_name, cal.caller_name from cases as c left join mstr_caller as cal on cal.id = c.callerId left join mstr_owner_contact as oc on oc.id = c.customer_contact_id	left join mstr_owner as o on o.id = c.ownerId left join mstr_vehicle as v on v.id = c.vehicleId left join mstr_dealer as del on  del.id = c.assign_to where c.created_at >= '$DateFrom' and c.created_at <= '$DateTo' and c.complaint_number!=''  order by c.id desc");
				
			
			}else{
				
				$data['rowData'] = DB::select("select c.id, c.complaint_number, c.vehicleId, c.ownerId, c.customer_contact_id, c.callerId, c.from_where, c.to_where, c.highway, c.ticket_type, c.aggregate, c.vehicle_problem, c.assign_to, c.dealer_mob_number, c.dealer_alt_mob_number, c.remark_type, c.disposition, c.agent_remark, c.standard_remark, c.assign_remarks, c.created_at, c.tat_scheduled, del.dealer_name, v.reg_number,v.chassis_number, o.owner_name, o.owner_company, oc.contact_name, cal.caller_name from cases as c left join mstr_caller as cal on cal.id = c.callerId left join mstr_owner_contact as oc on oc.id = c.customer_contact_id	left join mstr_owner as o on o.id = c.ownerId left join mstr_vehicle as v on v.id = c.vehicleId left join mstr_dealer as del on  del.id = c.assign_to where c.created_at >= '$DateFrom' and c.created_at <= '$DateTo' and FIND_IN_SET(c.assign_to, '$sess_dealer')  and c.complaint_number!='' order by c.id desc");
				
			}
			$data['userId']=$userId;
		  	return view('case_list',$data);
	 	}catch (\Exception $ex){
			$notification = array(
                'message' => $ex->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }	  
	 }
	 
	public function storeCaseList(Request $request){
	 	try{
	 		
			$userId = Auth::user()->sesUserId;
		 	$role = Auth::user()->role;
		 	$dealer_id = Auth::user()->dealer_id;
		 	$dealId = explode(",",$dealer_id);
		 	$userId = Auth::user()->sesUserId;
		 	$role = Auth::user()->role;
		 	$location = Auth::user()->city;
		 	$complaint_type_id = Auth::user()->complaint_type_id;
		 	$product = Auth::user()->product;
		 	$brand = Auth::user()->brand;
		 	$DateFrom=$request->input('datefrom');
			$DateTo=$request->input('dateto');
			$submit=$request->input('submit');
			$sess_dealer =Auth::user()->dealer_id;
			$data['datefrom'] = $DateFrom;
			$data['dateto'] = $DateTo;
			if (Auth::user()->role == '29' || Auth::user()->role == '30' || Auth::user()->role == '87') {
				//DB::enableQueryLog();
				if($submit == 'Submit'){
					$data['rowData'] = DB::select("select c.id, c.complaint_number, c.vehicleId, c.ownerId, c.customer_contact_id, c.callerId, c.from_where, c.to_where, c.highway, c.ticket_type, c.aggregate, c.vehicle_problem, c.assign_to, c.dealer_mob_number, c.dealer_alt_mob_number, c.remark_type, c.disposition, c.agent_remark, c.standard_remark, c.assign_remarks, c.created_at, c.tat_scheduled, del.dealer_name, v.reg_number,v.chassis_number, o.owner_name, o.owner_company, oc.contact_name, cal.caller_name from cases as c 
					left join mstr_caller as cal on cal.id = c.callerId left join mstr_owner_contact as oc on oc.id = c.customer_contact_id	left join mstr_owner as o on o.id = c.ownerId left join mstr_vehicle as v on v.id = c.vehicleId left join mstr_dealer as del on  del.id = c.assign_to where c.created_at >= :DateFrom and c.created_at <= :DateTo and c.complaint_number!='' order by c.id desc",['DateFrom'=>$DateFrom,'DateTo'=>$DateTo]);
					//dd($data['rowData']);
					
				}
			}
			else{	
					if($submit == 'Submit'){
						
						$data['rowData'] = DB::select("select c.id, c.complaint_number, c.vehicleId, c.ownerId, c.customer_contact_id, c.callerId, c.from_where, c.to_where, c.highway, c.ticket_type, c.aggregate, c.vehicle_problem, c.assign_to, c.dealer_mob_number, c.dealer_alt_mob_number, c.remark_type, c.disposition, c.agent_remark, c.standard_remark, c.assign_remarks, c.created_at, c.tat_scheduled, del.dealer_name, v.reg_number,v.chassis_number, o.owner_name, o.owner_company, oc.contact_name, cal.caller_name from cases as c left join mstr_caller as cal on cal.id = c.callerId left join mstr_owner_contact as oc on oc.id = c.customer_contact_id	left join mstr_owner as o on o.id = c.ownerId left join mstr_vehicle as v on v.id = c.vehicleId left join mstr_dealer as del on  del.id = c.assign_to where c.created_at >= 
						:DateFrom and c.created_at <= :DateTo and FIND_IN_SET(c.assign_to, :sess_dealer) and c.complaint_number!='' order by c.id desc",['DateFrom'=>$DateFrom,'DateTo'=>$DateTo,'sess_dealer'=>$sess_dealer]);
						
						
					}else{
						
					}
				
			
				
			}
			$data['userId']=$userId;
		  	return view('case_list',$data);
	 	}catch (\Exception $ex){
			$notification = array(
                'message' => $ex->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }	  
	 }
	public function storeUpdateCases(Request $request){
	 	/* try{ */
			$remark_type = $request->input('remark_type');			
	 		date_default_timezone_set('Asia/Kolkata');
			$updated_at = date('Y-m-d H:i:s');
			$currentUserMail = Auth::user()->email;
			$loginId = Auth::user()->employee_id;
		 	$loginName = Auth::user()->name;
		 	$caseId = $request->input('caseId');
		 	$complaint_number = $request->input('complaint_number');
		 	$assign_to = $request->input('assign_to');
		 	$dealer_mob_number = $request->input('dealer_mob_number');
		 	$dealer_alt_mob_number = $request->input('dealer_alt_mob_number');
		 	
		 	$disposition = $request->input('disposition');
		 	$agent_remark = $request->input('agent_remark');
		 	$assign_remarks = $request->input('assign_remarks');
		 	$estimated_response_time = $request->input('estimated_response_time');
		 	$tat_scheduled = $request->input('tat_scheduled');
		 	$acceptance = $request->input('acceptance');
		 	$feedback_rating = $request->input('feedback_rating');
		 	$feedback_desc = addslashes($request->input('feedback_desc'));
		 	$owner_contact_mob = $request->input('owner_contact_mob');
			$reg_number1 = $request->input('reg_number1');
			$vehicleId = $request->input('vehicleId');
			$actual_response_time = $request->input('actual_response_time');
			$restoration_type = $request->input('restoration_type');
			$response_delay_reason = $request->input('response_delay_reason');
			// $assign_work_manager = $request->input('assign_work_manager');
			$assign_work_managerArr = $request->input('assign_work_manager');
			/* changed */
			$assign_work_managerArr = explode("**",$assign_work_managerArr);
			$assign_work_manager = $assign_work_managerArr[0];
			/* changed */
			$assign_work_manager_mobile = $request->input('assign_work_manager_mobile');
			$assign_work_manager_mobileMSUAPI = $request->input('assign_work_manager_mobile');
			$source = "NA";
			$followup_time = $request->input('followup_time');
			$restoration_delay = $request->input('restoration_delay');
			$aggregate = $request->input('aggregate');
			$caller_name = $request->input('caller_name');
			$caller_contact = $request->input('caller_contact');
			$owner_mob = $request->input('owner_mob');			
			$caller_location = $request->input('caller_location');
			$standard_remark = $request->input('standard_remark');
			/* *******BS6******* */
			$dealerBSSql = DB::select("Select bsvi,dealer_name, area_champion,region_champion from mstr_dealer where id = $assign_to");
			$bsvi = $dealerBSSql[0]->bsvi!=''?$dealerBSSql[0]->bsvi:'KRYSALIS_Vandhana1@ashokleyland.com';
			$bsvi = str_replace(":",",",$bsvi);
			$bsvi = str_replace(";",",",$bsvi);
			$bsvi = str_replace(" ","",$bsvi);
			$bsviEmail = $bsvi;
			$area_champion = $dealerBSSql[0]->area_champion!=''?$dealerBSSql[0]->area_champion:'KRYSALIS_Vandhana1@ashokleyland.com';
			$area_champion = str_replace(":",",",$area_champion);
			$area_champion = str_replace(";",",",$area_champion);
			$area_champion = str_replace(" ","",$area_champion);
			$area_championEmail = $area_champion;
			$region_champion = $dealerBSSql[0]->region_champion!=''?$dealerBSSql[0]->region_champion:'KRYSALIS_Vandhana1@ashokleyland.com';
			$region_champion = str_replace(":",",",$region_champion);
			$region_champion = str_replace(";",",",$region_champion);
			$region_champion = str_replace(" ","",$region_champion);
			$region_championEmail = $region_champion;

			$assignDealerName = $dealerBSSql[0]->dealer_name;

			$bsviEmailArr = explode(",",$bsviEmail);
			$area_championEmailArr = explode(",",$area_championEmail);
			$region_championEmailArr = explode(",",$region_championEmail);
			$reason_reassign = $request->input('reason_reassign');
			$SPOC = $request->input('SPOC');
			/* *******BS6******* */
			
			$query = DB::select("select c.id as caseId, c.complaint_number, c.vehicleId, c.ownerId, c.customer_contact_id, c.callerId, c.from_where, c.to_where, c.highway, c.ticket_type, c.aggregate, c.vehicle_problem, c.assign_to, c.dealer_mob_number, c.dealer_alt_mob_number, c.remark_type, c.disposition, c.agent_remark, c.standard_remark, c.assign_remarks, c.estimated_response_time, c.actual_response_time, c.tat_scheduled, c.acceptance, c.latitude, c.longitude,c.feedback_rating,c.feedback_desc,c.location,c.landmark,c.state as stateId,c.city as cityId,c.district,c.created_at,c.restoration_type,c.response_delay_reason, v.vehicle, v.vehicle_model, v.reg_number, v.chassis_number, v.engine_number, v.vehicle_segment, v.purchase_date, v.add_blue_use, v.engine_emmission_type, o.owner_name, o.owner_mob, o.owner_landline, o.owner_cat, o.owner_company, oc.contact_name, oc.mob,oc.owner_contact_email,cal.caller_type, cal.caller_name, cal.caller_contact, cal.vehicle_type, cal.vehicle_movable, s.state, city.city,group_concat(rem.assign_remarks order by rem.id desc separator '@@') as assign_remark_log,group_concat(rem.created_at order by rem.id desc separator '@@') as assign_remark_date_log,c.assign_work_manager,c.assign_work_manager_mobile from cases as c left join mstr_vehicle as v on v.id = c.vehicleId left join mstr_owner as o on o.id = c.ownerId  and o.id = c.ownerId left join mstr_owner_contact as oc on oc.id = c.customer_contact_id and oc.vehicle_id = c.vehicleId and oc.owner_id = c.ownerId left join mstr_caller as cal on cal.id = c.callerId  and cal.owner_id = c.ownerId  left join mstr_caller_state as s on s.id = c.state left join mstr_caller_city as city on city.id = c.city left join remarks as rem on rem.complaint_number = c.complaint_number  where c.complaint_number = '$complaint_number'");

			// $reasingDateQuery = DB::select("select created_at from remarks where complaint_number='$complaint_number' and remark_type = 'Reassigned support' order by id desc;");
			// $data['reassignDate'] = sizeof($reasingDateQuery)>0?$reasingDateQuery[0]->created_at:'';

			$regNo =$query[0]->reg_number;
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
			$db_restoration_type = $query[0]->restoration_type;
			$db_response_delay_reason = $query[0]->response_delay_reason;
			
			$db_acceptance = $query[0]->acceptance;
			$db_feedback_desc = $query[0]->feedback_desc;
			$db_feedback_rating = $query[0]->feedback_rating;
			$db_engine_emmission_type = $query[0]->engine_emmission_type;
			$db_assign_remarks = $query[0]->assign_remarks;
			$db_remark_type = $query[0]->remark_type;
			$db_created_at = $query[0]->created_at;
			$ticetId = $query[0]->caseId;
			if($db_contact_mob!=''){
				$followup_name = $db_contact_name.' (Owner)';
				$followups_number = $db_contact_mob;
			}else{
				$followup_name = $db_owner_name.' (Owner)';
				$followups_number = $db_owner_mob;
			}
			if($db_remark_type == 'Closed'){
				$notification = array(
					'message' => "Ticket already closed",
					'alert-type' => 'error'
				);
				return back()->with($notification);
			}
			/* **************************************Ticket Hold************************************************* */
			try {
				if($remark_type == 'Awaiting customer approval'){			
					$currentDateTime = date("Y-m-d H:i:s");
					$queryHold = DB::select("select id, complaint_number, remark_type from ticket_hold where remark_type = '$remark_type' and complaint_number = '$complaint_number'");
					if(sizeof($queryHold) == 0){
						//DB::select("Update ticket_hold set updated_at='$currentDateTime' where complaint_number = '$complaint_number'");
						DB::select("Insert into ticket_hold (complaint_number, remark_type) values ('$complaint_number','$remark_type')");
					}
				}else if($db_remark_type == 'Awaiting customer approval' && $remark_type != $db_remark_type){
					$currentDateTime = date("Y-m-d H:i:s");
					$queryHold = DB::select("select id, complaint_number, remark_type from ticket_hold where remark_type = '$db_remark_type' and complaint_number = '$complaint_number'");
					if(sizeof($queryHold)>0){
						DB::select("Update ticket_hold set updated_at='$currentDateTime' where complaint_number = '$complaint_number' and remark_type = '$db_remark_type'");
					}
				}
				/* *********************************************************************************************************** */
				if($remark_type == 'Awaiting customer Payment'){
					$currentDateTime = date("Y-m-d H:i:s");
					
					$queryHold = DB::select("select id, complaint_number, remark_type from ticket_hold where remark_type = '$remark_type' and complaint_number = '$complaint_number'");
					if(sizeof($queryHold) == 0){
						//DB::select("Update ticket_hold set updated_at='$currentDateTime' where complaint_number = '$complaint_number'");
						DB::select("Insert into ticket_hold (complaint_number, remark_type) values ('$complaint_number','$remark_type')");
					}
				}else if($db_remark_type == 'Awaiting customer Payment' && $remark_type != $db_remark_type){
					$currentDateTime = date("Y-m-d H:i:s");
					$queryHold = DB::select("select id, complaint_number, remark_type from ticket_hold where remark_type = '$db_remark_type' and complaint_number = '$complaint_number'");
					if(sizeof($queryHold)>0){
						DB::select("Update ticket_hold set updated_at='$currentDateTime' where complaint_number = '$complaint_number' and remark_type = '$db_remark_type'");
					}
				}
				/* *********************************************************************************************************** */
				if($remark_type == 'Awaiting parts from customer'){			
					$currentDateTime = date("Y-m-d H:i:s");
					$queryHold = DB::select("select id, complaint_number, remark_type from ticket_hold where remark_type = '$remark_type' and complaint_number = '$complaint_number'");
					if(sizeof($queryHold) == 0){
						//DB::select("Update ticket_hold set updated_at='$currentDateTime' where complaint_number = '$complaint_number'");
						DB::select("Insert into ticket_hold (complaint_number, remark_type) values ('$complaint_number','$remark_type')");
					}
				}else if($db_remark_type == 'Awaiting parts from customer' && $remark_type != $db_remark_type){
					$currentDateTime = date("Y-m-d H:i:s");
					$queryHold = DB::select("select id, complaint_number, remark_type from ticket_hold where remark_type = '$db_remark_type' and complaint_number = '$complaint_number'");
					if(sizeof($queryHold)>0){
						DB::select("Update ticket_hold set updated_at='$currentDateTime' where complaint_number = '$complaint_number' and remark_type = '$db_remark_type'");
					}
				}
				/* *********************************************************************************************************** */
				if($remark_type == 'Vehicle being Towed'){			
					$currentDateTime = date("Y-m-d H:i:s");
					$queryHold = DB::select("select id, complaint_number, remark_type from ticket_hold where remark_type = '$remark_type' and complaint_number = '$complaint_number'");
					if(sizeof($queryHold) == 0){
						//DB::select("Update ticket_hold set updated_at='$currentDateTime' where complaint_number = '$complaint_number'");
						DB::select("Insert into ticket_hold (complaint_number, remark_type) values ('$complaint_number','$remark_type')");
					}
				}else if($db_remark_type == 'Vehicle being Towed' && $remark_type != $db_remark_type){
					$currentDateTime = date("Y-m-d H:i:s");
					$queryHold = DB::select("select id, complaint_number, remark_type from ticket_hold where remark_type = '$db_remark_type' and complaint_number = '$complaint_number'");
					if(sizeof($queryHold)>0){
						DB::select("Update ticket_hold set updated_at='$currentDateTime' where complaint_number = '$complaint_number' and remark_type = '$db_remark_type'");
					}
				}
				/* *********************************************************************************************************** */
				if($remark_type == 'Gate Pass Pending From Customer End'){			
					$currentDateTime = date("Y-m-d H:i:s");
					$queryHold = DB::select("select id, complaint_number, remark_type from ticket_hold where remark_type = '$remark_type' and complaint_number = '$complaint_number'");
					if(sizeof($queryHold) == 0){
						//DB::select("Update ticket_hold set updated_at='$currentDateTime' where complaint_number = '$complaint_number'");
						DB::select("Insert into ticket_hold (complaint_number, remark_type) values ('$complaint_number','$remark_type')");
					}
				}else if($db_remark_type == 'Gate Pass Pending From Customer End' && $remark_type != $db_remark_type){
					$currentDateTime = date("Y-m-d H:i:s");
					$queryHold = DB::select("select id, complaint_number, remark_type from ticket_hold where remark_type = '$db_remark_type' and complaint_number = '$complaint_number'");
					if(sizeof($queryHold)>0){
						DB::select("Update ticket_hold set updated_at='$currentDateTime' where complaint_number = '$complaint_number' and remark_type = '$db_remark_type'");
					}
				}
				/* *********************************************************************************************************** */
			}catch (\Exception $aa){
				$aa  = $aa->getMessage();
				DB::table('updation_exception')->insert(['complaint_number'=>"$complaint_number",'type'=>"Ticket Hold",'exception'=>"$aa"]);
		   }
			/* **************************************Ticket Hold************************************************* */
			$add_tat_scheduled = strtotime($tat_scheduled.' + 10 minute');
			$new_tat_scheduled =  date('Y-m-d H:i:s', $add_tat_scheduled);
			$add_estimated_response_time = strtotime($estimated_response_time.' + 5 minute');
			$new_estimated_response_time =  date('Y-m-d H:i:s', $add_estimated_response_time);
			$assign_toNew = $assign_to!=''?$assign_to:0;
			$supportContPersonSql = DB::select("Select mobile,name from users where role in (76,113) and FIND_IN_SET($assign_toNew, dealer_id) and flag=1");
 			$supportContPerson = sizeof($supportContPersonSql)>0?$supportContPersonSql[0]->name:'NA';
 			$supportContPersonMob = sizeof($supportContPersonSql)>0?$supportContPersonSql[0]->mobile:'NA';
			$supportContPerson = !empty($assign_work_manager)?$assign_work_manager:$supportContPerson;
			$supportContPersonMob = !empty($assign_work_manager_mobile)?$assign_work_manager_mobile:$supportContPersonMob;

			/* $assign_remark_log = explode("@@",$query[0]->assign_remark_log);
			$assign_remark_date_log = explode("@@",$query[0]->assign_remark_date_log); */
			$vehicle_type = $request->input('vehicle_type');

			$so_number = $request->input('so_number');
			$jobcard_number = $request->input('jobcard_number');
			$actual_response_time_customer = $request->input('actual_response_time_customer');
			$tat_scheduled_customer = $request->input('tat_scheduled_customer');
			/* Followup Tables updates */
			try {
				
				if($remark_type == 'Closed'){
					$cogent_assign_followups = DB::table('cogent_assign_followups')->select(DB::raw('count(*) as count'))->where('complaint_number', '=', $complaint_number)->first()->count;
					
					$cogent_complete_followups = DB::table('cogent_complete_followups')->select(DB::raw('count(*) as count'))->where('complaint_number', '=', $complaint_number)->first()->count;
					$cogent_dealer_followups = DB::table('cogent_dealer_followups')->select(DB::raw('count(*) as count'))->where('complaint_number', '=', $complaint_number)->first()->count;
					if($cogent_assign_followups >0){
						DB::table('cogent_assign_followups')->where('complaint_number', $complaint_number)->update(['flag' => 'C','lock_status'=>'1']);
						DB::table('cogent_assign_followups_logs')->insert(['complaint_number'=>"$complaint_number",'flag'=>"C"]);
					}
					if($cogent_complete_followups >0){
						DB::table('cogent_complete_followups')->where('complaint_number', $complaint_number)->update(['flag' => 'C','lock_status'=>'1']);
						DB::table('cogent_complete_followups_logs')->insert(['complaint_number'=>"$complaint_number",'flag'=>"C"]);
					}
					if($cogent_dealer_followups >0){
						DB::table('cogent_dealer_followups')->where('complaint_number', $complaint_number)->update(['flag' => 'C','lock_status'=>'1']);
						DB::table('cogent_dealer_followups_logs')->insert(['complaint_number'=>"$complaint_number",'flag'=>"C"]);
					}
				}
				
			} catch (\Exception $ee123) {
				$ee123  = $ee123->getMessage();
				DB::table('updation_exception')->insert(['complaint_number'=>"$complaint_number",'type'=>"Cogent Followup Update Exc",'exception'=>"$ee123"]);
			}
			/* Followup Tables updates */
			if(Auth::user()->role == 29 || Auth::user()->role == 30 || Auth::user()->role == 87 || Auth::user()->role == 78){
				try {
					
					DB::table('cases')->where('id', $caseId)->update(['assign_to' => $assign_to,'dealer_mob_number' => $dealer_mob_number,'dealer_alt_mob_number' => $dealer_alt_mob_number,'remark_type' => $remark_type,'disposition' => $disposition,'agent_remark' => $agent_remark,'assign_remarks' => $assign_remarks,'estimated_response_time' => $estimated_response_time,'tat_scheduled' => $tat_scheduled,'acceptance' => $acceptance,'feedback_rating' => $feedback_rating,'feedback_desc' => $feedback_desc,'actual_response_time' => $actual_response_time,'restoration_type' => $restoration_type,'response_delay_reason' => $response_delay_reason,'assign_work_manager' => $assign_work_manager,'assign_work_manager_mobile' => $assign_work_manager_mobile,'source' => $source,'followup_time' => $followup_time,'restoration_delay' => $restoration_delay,'updated_at'=>$updated_at,'aggregate'=>$aggregate,'vehicle_type'=>$vehicle_type,'so_number'=>$so_number,'jobcard_number'=>$jobcard_number,'actual_response_time_customer'=>$actual_response_time_customer,'tat_scheduled_customer'=>$tat_scheduled_customer,'reason_reassign'=>$reason_reassign,'SPOC'=>$SPOC]);
				} catch (\Exception $bb){
					$bb  = $bb->getMessage();
					DB::table('updation_exception')->insert(['complaint_number'=>"$complaint_number",'type'=>"Admin Update Case",'exception'=>"$bb"]);
				}

				try {
					$assign_remarks = addslashes($assign_remarks);
					DB::table('remarks')->insert(['complaint_number'=>"$complaint_number",'remark_type'=>"$remark_type",'employee_name'=>"$loginName",'employee_id'=>"$currentUserMail",'dealer_mob_number'=>"$dealer_mob_number",'dealer_alt_mob_number'=>"$dealer_alt_mob_number",'assign_to'=>"$assign_to",'disposition'=>"$disposition",'agent_remark'=>"$agent_remark",'assign_remarks'=>"$assign_remarks",'estimated_response_time' => "$estimated_response_time",'tat_scheduled' => "$tat_scheduled",'acceptance' => "$acceptance",'feedback_rating' => "$feedback_rating",'feedback_desc' => "$feedback_desc",'actual_response_time' => "$actual_response_time"]); 
				}catch (\Exception $cc){
					$cc  = $cc->getMessage();
					DB::table('updation_exception')->insert(['complaint_number'=>"$complaint_number",'type'=>"Admin Insert Remark",'exception'=>"$cc"]);
			   	}
				/* if(!empty($tat_scheduled) && !empty($estimated_response_time) && !empty($db_tat_scheduled) && !empty($db_estimated_response_time) && (($db_estimated_response_time != $estimated_response_time) || ($db_tat_scheduled != $tat_scheduled))){ */
					
					//$dealCheckWM = DB::select("Select email,name,mobile from users where role in (76) and FIND_IN_SET($assign_to, dealer_id)");
					$dealCheckSM = DB::select("Select email,name,mobile from users where role in (7) and FIND_IN_SET($assign_to, dealer_id) and flag=1");
					$dealCheckP = DB::select("Select email,name,mobile from users where role in (6) and FIND_IN_SET($assign_to, dealer_id) and flag=1");
					/* if(sizeof($dealCheckWM)>0){
						$newAssignTo = $dealCheckWM[0]->name;
						$newDealerMobNumber = $dealCheckWM[0]->mobile;
					}else */ if(sizeof($dealCheckSM)>0){
						$newAssignTo = $dealCheckSM[0]->name;
						$newDealerMobNumber = $dealCheckSM[0]->mobile;
					}elseif(sizeof($dealCheckP)>0){
						$newAssignTo = $dealCheckP[0]->name;
						$newDealerMobNumber = $dealCheckP[0]->mobile;
					}else{
						$dealerDataSql = DB::select("Select dealer_name,phone from mstr_dealer where id = $assign_to");
						$newAssignTo = $dealerDataSql[0]->dealer_name;
						$newDealerMobNumber = $dealerDataSql[0]->phone;
					}
					
			}else{
				try {
					DB::table('cases')->where('id', $caseId)->update(['remark_type' => $remark_type,'assign_remarks' => $assign_remarks,'tat_scheduled' => $tat_scheduled,'estimated_response_time' => $estimated_response_time,'restoration_type' => $restoration_type,'response_delay_reason' => $response_delay_reason,'actual_response_time'=>"$actual_response_time",'source' => $source,'followup_time' => $followup_time,'restoration_delay' => $restoration_delay,'updated_at'=>$updated_at,'aggregate'=>$aggregate,'vehicle_type'=>$vehicle_type,'so_number'=>$so_number,'jobcard_number'=>$jobcard_number,'actual_response_time_customer'=>$actual_response_time_customer,'tat_scheduled_customer'=>$tat_scheduled_customer,'reason_reassign'=>$reason_reassign,'SPOC'=>$SPOC]);

				} catch (\Exception $dd) {
					$dd  = $dd->getMessage();
					DB::table('updation_exception')->insert(['complaint_number'=>"$complaint_number",'type'=>"Non-Admin Update Case",'exception'=>"$dd"]);
				}
				
				try {
					DB::table('remarks')->insert(['complaint_number'=>"$complaint_number",'remark_type'=>"$remark_type",'employee_name'=>"$loginName",'employee_id'=>"$currentUserMail",'dealer_mob_number'=>"$db_dealer_mob_number",'dealer_alt_mob_number'=>"$db_dealer_alt_mob_number",'assign_to'=>"$db_assign_to",'disposition'=>"$db_disposition",'agent_remark'=>"$db_agent_remark",'assign_remarks'=>"$assign_remarks",'estimated_response_time' => "$estimated_response_time",'tat_scheduled' => "$tat_scheduled",'acceptance' => "$db_acceptance",'feedback_rating' => "$db_feedback_rating",'feedback_desc' => "$db_feedback_desc",'actual_response_time' => "$actual_response_time"]);
				} catch (\Exception $ee) {
					$ee  = $ee->getMessage();
					DB::table('updation_exception')->insert(['complaint_number'=>"$complaint_number",'type'=>"Non-Admin Insert Remark",'exception'=>"$ee"]);
				}
				
				$dealCheckSM = DB::select("Select email,name,mobile from users where role in (7) and FIND_IN_SET($db_assign_to, dealer_id) and flag=1");
				$dealCheckP = DB::select("Select email,name,mobile from users where role in (6) and FIND_IN_SET($db_assign_to, dealer_id) and flag=1");
				if(sizeof($dealCheckSM)>0){
					$newAssignTo = $dealCheckSM[0]->name.' (Dealer)';
					$newDealerMobNumber = $dealCheckSM[0]->mobile;
				}elseif(sizeof($dealCheckP)>0){
					$newAssignTo = $dealCheckP[0]->name.' (Dealer)';
					$newDealerMobNumber = $dealCheckP[0]->mobile;
				}else{
					$dealerDataSql = DB::select("Select dealer_name,phone from mstr_dealer where id = $db_assign_to");
					$newAssignTo = $dealerDataSql[0]->dealer_name.' (Dealer)';
					$newDealerMobNumber = $dealerDataSql[0]->phone;
				}
				$sessionName = Auth::user()->name;
				$sessionMobile = Auth::user()->mobile;
					
			}
			$queryNew = DB::select("select c.id as caseId, c.complaint_number, c.vehicleId, c.ownerId, c.customer_contact_id, c.callerId, c.from_where, c.to_where, c.highway, c.ticket_type, c.aggregate, c.vehicle_problem, c.assign_to, c.dealer_mob_number, c.dealer_alt_mob_number, c.remark_type, c.disposition, c.agent_remark, c.standard_remark, c.assign_remarks, c.estimated_response_time, c.actual_response_time, c.tat_scheduled, c.acceptance, c.latitude, c.longitude,c.feedback_rating,c.feedback_desc,c.location,c.landmark,c.state as stateId,c.city as cityId,c.district,c.created_at,c.restoration_type,c.response_delay_reason, v.vehicle, v.vehicle_model, v.reg_number, v.chassis_number, v.engine_number, v.vehicle_segment, v.purchase_date, v.add_blue_use, v.engine_emmission_type, o.owner_name, o.owner_mob, o.owner_landline, o.owner_cat, o.owner_company, oc.contact_name, oc.mob,oc.owner_contact_email,cal.caller_type, cal.caller_name, cal.caller_contact, cal.vehicle_type, cal.vehicle_movable, s.state, city.city,group_concat(rem.assign_remarks order by rem.id desc separator '@@') as assign_remark_log,group_concat(rem.created_at order by rem.id desc separator '@@') as assign_remark_date_log,c.assign_work_manager,c.assign_work_manager_mobile from cases as c left join mstr_vehicle as v on v.id = c.vehicleId left join mstr_owner as o on o.id = c.ownerId  and o.id = c.ownerId left join mstr_owner_contact as oc on oc.id = c.customer_contact_id and oc.vehicle_id = c.vehicleId and oc.owner_id = c.ownerId left join mstr_caller as cal on cal.id = c.callerId  and cal.owner_id = c.ownerId  left join mstr_caller_state as s on s.id = c.state left join mstr_caller_city as city on city.id = c.city left join remarks as rem on rem.complaint_number = c.complaint_number  where c.complaint_number = '$complaint_number'");
			$assign_remark_log = explode("@@",$queryNew[0]->assign_remark_log);
			$assign_remark_date_log = explode("@@",$queryNew[0]->assign_remark_date_log);
			$assign_to = $assign_to!=''?$assign_to:$db_assign_to;
			if($remark_type != "Completed" || $remark_type == "Closed" || ($remark_type == 'Reassigned support' && $db_remark_type != $remark_type)){
				// dd('asdsadasd');
				$workMangerMobSQL =  DB::select("Select name,mobile from users where role in(1,76,113) and FIND_IN_SET($assign_to, dealer_id) and flag=1");
				$workMangerMob=$workMangerMobArr='';
				if(sizeof($workMangerMobSQL)>0){
					foreach ($workMangerMobSQL as $row) {
						$ticketCloseUpdateSMS = $row->mobile!=''?$row->mobile:'0000000000';
						$workMangerMobArr .= '91'.$ticketCloseUpdateSMS.',';
					}
				}
				$workMangerMob = rtrim($workMangerMobArr,',');
				$pwd = 'YajfWt@Z';
				$uid = '2000194089';
				$dealerNameQuery = DB::select("select dealer_name from mstr_dealer where id=$assign_to ");
				$dealerName = $dealerNameQuery[0]->dealer_name;
				$currentDateTime = date("Y-m-d H:i:s");
				if($assign_work_manager_mobile !=''){
					$assign_work_manager_mobile = $assign_work_manager_mobile!=''? $this->multiMobile($assign_work_manager_mobile):'910000000000';
					// $mobile=$assign_work_manager_mobile.',918105736911';
					$mobile=$assign_work_manager_mobile;
					//dd($mobile);
					//$complaint_number=123;
					/* $message= 'Dear Service team,
					Breakdown Complaint number '.$complaint_number.'Response '.$estimated_response_time.', Restoration Time - '.$tat_scheduled.'
					Thank You
					Ashok Leyland.'; */
					/* if($remark_type == 'Reassigned support' && $db_remark_type != $remark_type){
						$message= urlencode('AL Elite customer complaint number '.$complaint_number.' received on '.$currentDateTime.' is reassigned to '.$assign_to.'. 
						Customer Name – '.$db_owner_company.', Caller name- '.$caller_name.', Caller Number- '.$caller_contact.', Vehicle Reg No – '.$reg_number1.', Location- '.$caller_location.', Issue- '.$standard_remark.'.	
						Thank You
						Ashok Leyland Elite.');
					} */
					// if($remark_type != "Completed" || $remark_type == "Closed"){
					if($remark_type == "Closed"){
						/* $message= urlencode('AL Elite customer complaint number '.$complaint_number.' is closed on '.$currentDateTime.'  Response Time -'.$estimated_response_time.', Restoration Time -'.$tat_scheduled.', Customer Name - '.$db_owner_company.', Registration Number - '.$reg_number1.'.
						Thank You
						Ashok Leyland Elite.'); */
						$message= urlencode('Dear Service team, Breakdown Complaint number '.$complaint_number.' is closed by customer, Response '.$actual_response_time.', Restoration Time -'.$tat_scheduled.' Thank You Ashok Leyland.');
						try {
							// $url = "https://enterprise.smsgupshup.com/GatewayAPI/rest?method=SendMessage&send_to=$mobile&msg=$message&msg_type=TEXT&userid=$uid&auth_scheme=plain&password=$pwd&v=1.1&format=text";
							// $curl = curl_init();
							// curl_setopt_array($curl, array(
							// CURLOPT_URL => "https://enterprise.smsgupshup.com/GatewayAPI/rest?method=SendMessage&send_to=$mobile&msg=$message&msg_type=TEXT&userid=$uid&auth_scheme=plain&password=$pwd&v=1.1&format=text",
							// CURLOPT_RETURNTRANSFER => true,
							// CURLOPT_ENCODING => "",
							// CURLOPT_MAXREDIRS => 10,
							// CURLOPT_TIMEOUT => 30,
							// CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							// CURLOPT_CUSTOMREQUEST => "GET"
							// ));
							// curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
							// $response = curl_exec($curl);
							// $err = curl_error($curl);
							// curl_close($curl);
							// DB::table('sms_response')->insert(['url'=>"$url",'error'=>"$err",'response'=>"$response"]); 
						} catch (\Exception $ff) {
							$ff  = $ff->getMessage();
							DB::table('updation_exception')->insert(['complaint_number'=>"$complaint_number",'type'=>"Closed SMS for Team",'exception'=>"$ff"]);
						}
					}
					
					//$message=urlencode($message);
					
				}
				
				$owner_contact_mob = $owner_contact_mob != ''? $this->multiMobile($owner_contact_mob):'910000000000';		
				$dealer_mob_number = $dealer_mob_number != ''? $this->multiMobile($dealer_mob_number):'910000000000';		
				
				$caller_contact = $request->input('caller_contact');
				$owner_mob = $request->input('owner_mob');	
				$caller_contact = $caller_contact != ''?$this->multiMobile($caller_contact):'910000000000';		
				$owner_mob = $owner_mob != ''?$this->multiMobile($owner_mob):'910000000000';
				$assign_work_manager_mobile11 = $assign_work_manager_mobile != ''? $this->multiMobile($assign_work_manager_mobile):'910000000000';	
				// $mobileCust=$assign_work_manager_mobile11.','.$caller_contact.','.$owner_mob.',918105736911';
				$mobileCust=$assign_work_manager_mobile11.','.$caller_contact.','.$owner_mob;
					//dd($mobile);
					//$complaint_number=123;
				// if(($remark_type != "Completed" || $remark_type == "Closed") && $owner_mob !=''){
				if($remark_type == "Closed" && $mobileCust !=''){
					/* $message= 'Dear AL Select Customer,
					Breakdown complaint number '.$complaint_number.' for vehicle '.$reg_number1.' is resolved and closed.
					Thank You
					Ashok Leyland.'; */

					/* $message= urlencode('Dear AL ELITE Customer,Complaint number '.$complaint_number.' for your vehicle '.$reg_number1.' is resolved on '.$currentDateTime.'.Thank You Ashok Leyland Elite.'); */
					// $message= urlencode('Dear Customer, Breakdown complaint number '.$complaint_number.' for vehicle '.$reg_number1.' is resolved and closed. Thank You Ashok Leyland.');
					//$message=urlencode($message);

					try {

						$message= urlencode("Dear Customer, Breakdown Complaint number $complaint_number for your vehicle $reg_number1 is resolved on $currentDateTime. Share your feedback & Rate us in AL Care:-Link:-http://bit.ly/ALCARE_APP. Thank you Ashok Leyland");

						$this->sendSMSFuction($mobileCust,$message,'1607100000000289244',"Resolved SMS to customer",$complaint_number);
						// $url = "https://enterprise.smsgupshup.com/GatewayAPI/rest?method=SendMessage&send_to=$mobileCust&msg=$message&msg_type=TEXT&userid=$uid&auth_scheme=plain&password=$pwd&v=1.1&format=text";
						// $curl = curl_init();
						// curl_setopt_array($curl, array(
						// CURLOPT_URL => "https://enterprise.smsgupshup.com/GatewayAPI/rest?method=SendMessage&send_to=$mobileCust&msg=$message&msg_type=TEXT&userid=$uid&auth_scheme=plain&password=$pwd&v=1.1&format=text",
						// CURLOPT_RETURNTRANSFER => true,
						// CURLOPT_ENCODING => "",
						// CURLOPT_MAXREDIRS => 10,
						// CURLOPT_TIMEOUT => 30,
						// CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
						// CURLOPT_CUSTOMREQUEST => "GET"
						// ));
						// curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
						// $response = curl_exec($curl);
						// $err = curl_error($curl);
						// curl_close($curl);
						// DB::table('sms_response')->insert(['url'=>"$url",'error'=>"$err",'response'=>"$response"]);
					}  catch (\Exception $ff) {
						$ff  = $ff->getMessage();
						DB::table('updation_exception')->insert(['complaint_number'=>"$complaint_number",'type'=>"Closed SMS for Customer",'exception'=>"$ff"]);
					}
					//dd($response);
				}

				if(($remark_type == 'Reassigned support' && $db_remark_type != $remark_type) && $mobileCust !=''){
					

					/* $message= urlencode('Dear AL ELITE Customer,
					Your complaint number '.$complaint_number.' for vehicle '.$reg_number1.' is reassigned to '.$assignDealerName.' on '.$currentDateTime.'. WM number- '.$assign_work_manager_mobile.'.
					Regret for the inconvenience.	
					Thank You
					Ashok Leyland Elite.'); */
					//$message=urlencode($message);
					/* try {
						$url = "https://enterprise.smsgupshup.com/GatewayAPI/rest?method=SendMessage&send_to=$mobileCust&msg=$message&msg_type=TEXT&userid=$uid&auth_scheme=plain&password=$pwd&v=1.1&format=text";
						$curl = curl_init();
						curl_setopt_array($curl, array(
						CURLOPT_URL => "https://enterprise.smsgupshup.com/GatewayAPI/rest?method=SendMessage&send_to=$mobileCust&msg=$message&msg_type=TEXT&userid=$uid&auth_scheme=plain&password=$pwd&v=1.1&format=text",
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_ENCODING => "",
						CURLOPT_MAXREDIRS => 10,
						CURLOPT_TIMEOUT => 30,
						CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
						CURLOPT_CUSTOMREQUEST => "GET"
						));
						curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
						$response = curl_exec($curl);
						$err = curl_error($curl);
						curl_close($curl);
						DB::table('sms_response')->insert(['url'=>"$url",'error'=>"$err",'response'=>"$response"]); 
					} catch (\Exception $gg) {
						$gg  = $gg->getMessage();
						DB::table('updation_exception')->insert(['complaint_number'=>"$complaint_number",'type'=>"Reassigned SMS for Customer",'exception'=>"$gg"]);
					} */
					try {
						$dealerDataSql1 = DB::select("Select d.dealer_name,d.phone,d.sac_code,s.state as stateName,d.SC_City_Name as cityName,c.city as dealerCity from mstr_dealer as d left join mstr_state as s on s.id = d.state left join mstr_city as c on c.id = d.city  where d.id = $assign_to");
						$dealerCity='';
						if(sizeof($dealerDataSql1)>0){						
							$dealerCity = $dealerDataSql1[0]->dealerCity;
						}
						
						$message= urlencode("Dear Customer, Complaint number $complaint_number for vehicle $reg_number1 is reassigned to $assignDealerName, AO - $dealerCity on  $currentDateTime WM number- $assign_work_manager_mobile. To track live status of your breakdown ticket with tracking option using ALCare app. Click http://bit.ly/ALCARE_APP. Thank you Ashok Leyland");

						$this->sendSMSFuction($mobileCust,$message,'1607100000000289245',"Reassigned SMS to customer",$complaint_number);
					} catch (\Exception $gg) {
						$gg  = $gg->getMessage();
						DB::table('updation_exception')->insert(['complaint_number'=>"$complaint_number",'type'=>"Reassigned SMS for Customer",'exception'=>"$gg"]);
					}
					/* Send SMS to TSM */
						try {
							$tsmQuery =DB::select("select mobile from users where find_in_set($assign_to,dealer_id) and role = 1 and flag=1 limit 1");										
							if(sizeof($tsmQuery)>0){
								$tsmMob = $tsmQuery[0]->mobile;							
								// $mobileTSM=$this->multiMobile($tsmMob).',918105736911';
								$mobileTSM=$this->multiMobile($tsmMob);
								$stndRemark = $query[0]->standard_remark;
								$stndRemark = substr($stndRemark, 0, 20);
								$message= urlencode("Dear AL Team, Breakdown complaint number $complaint_number for vehicle $reg_number1 is reassigned to $assignDealerName, AO - $dealerCity on $currentDateTime. WM Mob No- $assign_work_manager_mobile. Customer Name - $db_owner_name, Caller Number-  $caller_contact, Issue : $stndRemark. Thank you Ashok Leyland");

								$this->sendSMSFuction($mobileTSM,$message,'1607100000000289249',"Reassigned SMS to TSM",$complaint_number);
							}
							
						}  catch (\Exception $ggw) {
							$ggw  = $ggw->getMessage();
							DB::table('updation_exception')->insert(['complaint_number'=>"$complaint_number",'type'=>"Reassigned SMS for TSM",'exception'=>"$ggw"]);
						}
					/* Send SMS to TSM */
				}
				/* Message Send */

					/* Email Send */
			
													/* Email Send Fields*/
			/* ********************* Owner ALSE/ASM/RSM******************** */
			$ownerMasterId = $query[0]->ownerId;
			$ownerCntact = DB::select("SELECT owner_contact_email FROM mstr_owner_contact where owner_id=$ownerMasterId and owner_contact_email!='' ");
			 $ownerContactEmail ='';
			 if(sizeof($ownerCntact)>0 ){
				foreach ($ownerCntact as $row) {
				   $ownerContactEmailDB=$row->owner_contact_email; 
				   $ownerContactEmailDB = str_replace(":",",",$ownerContactEmailDB); 
				   $ownerContactEmailDB = str_replace(";",",",$ownerContactEmailDB); 
				   $ownerContactEmailDB = str_replace(" ","",$ownerContactEmailDB); 
				   $ownerContactEmail .=$ownerContactEmailDB.','; 
				}
				$ownerContactEmail = rtrim($ownerContactEmail,',');
				$ownerContactEmail = explode(",",$ownerContactEmail);
			}else{
				$ownerContactEmail = array("KRYSALIS_Vandhana1@ashokleyland.com");
			}
			$owner_contact_email[] = $query[0]->owner_contact_email!=''?$query[0]->owner_contact_email:'KRYSALIS_Vandhana1@ashokleyland.com';
			$owenerSqlData = DB::select("Select alse_mail,asm_mail from mstr_owner where id = $ownerMasterId");
			if(sizeof($owenerSqlData)>0){
				$ownerALSEEmail = $owenerSqlData[0]->alse_mail; 
				$ownerALSEEmail = str_replace(":",",",$ownerALSEEmail); 
				$ownerALSEEmail = str_replace(";",",",$ownerALSEEmail); 
				$ownerALSEEmail = str_replace(" ","",$ownerALSEEmail); 
				$alseOwnerEmail = $ownerALSEEmail; 
				$ownerASMEmail=$owenerSqlData[0]->asm_mail; 
				$ownerASMEmail = str_replace(":",",",$ownerASMEmail);
				$ownerASMEmail = str_replace(";",",",$ownerASMEmail); 
				$ownerASMEmail = str_replace(" ","",$ownerASMEmail); 
				$asmOwnerEmail = $ownerASMEmail; 
			}
			$asmOwnerEmailArr = explode(",",$asmOwnerEmail); 
			$ccOwnerEmail = explode(",",$alseOwnerEmail); 
			$ccOwnerEmail = array_merge($ccOwnerEmail,$asmOwnerEmailArr);
			/* ********************* Owner ALSE/ASM/RSM******************** */

			$dealerNameQuery = DB::select("select dealer_name,sac_code,latitude,longitude from mstr_dealer where id=$assign_to ");
			$dealerName = $dealerNameQuery[0]->dealer_name;
			$sac_code = $dealerNameQuery[0]->sac_code;
			$dealerLatitude = $dealerNameQuery[0]->latitude;
			$dealerLongitude = $dealerNameQuery[0]->longitude;
			
			$matrix = DB::select("Select id, level_name, to_role, cc_role, hours from mstr_escalations where level =1");
			$to_role = $matrix[0]->to_role;
			$cc_role = $matrix[0]->cc_role;
			//echo "Select email,name from users where role in ($to_role) and FIND_IN_SET($assign_to, dealer_id)";die;
			$toUsersSql =  DB::select("Select email,name from users where role in ($to_role) and FIND_IN_SET($assign_to, dealer_id) and email !='' and flag=1");
			$ccUserSql =   DB::select("Select email,name from users where role in ($cc_role) and FIND_IN_SET($assign_to, dealer_id) and email !='' and flag=1");
			$alseUserSql = DB::select("Select name,email,mobile from users where role =1 and FIND_IN_SET($assign_to, dealer_id) and email !='' and flag=1");
			$alseName = $alseEmail='';
			if(sizeof($alseUserSql)>0){
				$alseName = $alseUserSql[0]->name;
				$alseUser = trim($alseUserSql[0]->email);
				$alseUser = str_replace(":",",",$alseUser);
				$alseUser = str_replace(";",",",$alseUser);
				$alseUser = str_replace(" ",",",$alseUser);
				$alseEmail = $alseUser;

			}
			//dd("Select email,name from users where role in ($cc_role) and dealer_id in ($assign_to)");
			$toUserArr=$ccUserArr ='';
			if(sizeof($toUsersSql)>0){
				foreach($toUsersSql as $row){
					$toUser = trim($row->email);
					if($toUser!=''){
						$toUser = str_replace(":",",",$toUser);
						$toUser = str_replace(";",",",$toUser);
						$toUser = str_replace(" ",",",$toUser);
						$toUserArr .= $toUser.",";
					}
					
				}
				$toUserArr = rtrim($toUserArr,',');
				$toUserArr = explode(",",$toUserArr);
			}else{
				$toUserArr = array("KRYSALIS_Vandhana1@ashokleyland.com");
			}
			if(sizeof($ccUserSql)>0){
				foreach($ccUserSql as $row){
					$ccUser = trim($row->email);
					if($ccUser!=''){
						$ccUser = str_replace(":",",",$ccUser);
						$ccUser = str_replace(";",",",$ccUser);
						$ccUser = str_replace(" ",",",$ccUser);
						$ccUserArr .= $ccUser.",";
					}
					
				}
				$ccUserArr = rtrim($ccUserArr,',');
				$ccUserArr = explode(",",$ccUserArr);
				//$addMail = 'ashutosh.rawat@cogenteservices.in';			
				//$ccUserArr [] = $addMail;
			
			}else{
				$ccUserArr = array("KRYSALIS_Vandhana1@ashokleyland.com");
			}
			$now = date('Y-m-d H:i:s');
			$ticketCreatedDateTime = $query[0]->created_at;
			
			//$responseDateTime = date('Y-m-d H:i:s', strtotime('+'.$actual_response_time.' minutes', strtotime($ticketCreatedDateTime)));
			$responseDateTime = $actual_response_time;  
			/* if($remark_type == "Completed" ){
				$restoration = date('Y-m-d H:i:s');
				$restoration = date('Y-m-d H:i:s');
			}else{
				$restoration = date('Y-m-d H:i:s');
				$restoration = date('Y-m-d H:i:s');
			} */

			$restoration =$tat_scheduled;
			if($db_engine_emmission_type=='BS6'){
				//$ccUserArr = array_merge($ccUserArr, $bsviEmailArr, $area_championEmailArr,$region_championEmailArr);
				$ccUserArr = array_merge($ccUserArr, $area_championEmailArr,$region_championEmailArr);
			}
			/* $ccUserArr = array_merge($ccUserArr,$ccOwnerEmail); */
			// if($remark_type == 'Reassigned support'){
			if($remark_type != 'Closed'){
				// $remark_type = 'Reassigned';
				$cloBody ='<p>Please find the below mentioned Break Down details.</p>
				<p>Kindly update the Response, Restoration and Closure details by using Dealer Portal using the link..</p>';
			}else{
				$remark_type=='Completed'?'Completion':'Closure';
				$cloBody ='<p>Below mentioned vehicle was restored on '.$restoration.' Hence ticket closed.</p>';
			}
			$remType = $remark_type;			
			$sbjct1 ="$remType Mail - AL Breakdown Ticket Details - $complaint_number | $regNo";
			$sbjct2 ="$remType Mail - AL Breakdown BSVI Ticket Details - $complaint_number | $regNo";
			$subject= ($db_engine_emmission_type=='BS6' && $db_engine_emmission_type!='')?$sbjct2:$sbjct1;
			/* $assign_remark_log = explode("@@",$query[0]->assign_remark_log);
			$assign_remark_date_log = explode("@@",$query[0]->assign_remark_date_log); */
			//$subject="ELITE Support Ticket - $complaint_number $remark_type";
			$body = '<p>Dear Team, </p>
			'.$cloBody.'
			<table border="1" style="font-family: sans-serif;">
				<tr>
					<td style=" text-align: left;">Customer Name</td>
					<td style="text-align: left;">'.$db_owner_name.'</td>
					
				</tr>
				<tr>
					<td style=" text-align: left;">Ticket Number</td>
					<td style="text-align: left;"><a href="'.url('update-case',['id' =>$ticetId]).'" >'.$complaint_number.'</a></td>		
					
				</tr>
				<tr>
					<td style=" text-align: left;">Call log date and Time</td>
					<td style="text-align: left;">'.$ticketCreatedDateTime.'</td>
					
				</tr>
				<tr>
					<td style=" text-align: left;">Caller Name</td>
					<td style="text-align: left;">'.$query[0]->caller_name.'</td>
					
				</tr>
				<tr>
					<td style=" text-align: left;">Caller Type</td>
					<td style="text-align: left;">'.$query[0]->caller_type.'</td>
					
				</tr>
				<tr>
					<td style=" text-align: left;">Caller Cont Number</td>
					<td style="text-align: left;">'.$query[0]->caller_contact.'</td>
					
				</tr>
				<tr>
					<td style=" text-align: left;">Registration Number</td>
					<td style="text-align: left;">'.$regNo.'</td>
					
				</tr>
				<tr>
					<td style=" text-align: left;">Breakdown Location</td>
					<td style="text-align: left;">'.$query[0]->location.'</td>
					
				</tr>
				<tr>
					<td style=" text-align: left;">Issue</td>
					<td style="text-align: left;">'.$query[0]->standard_remark.'</td>
					
				</tr>
				<tr>
					<td style=" text-align: left;">Support Centre Code</td>
					<td style="text-align: left;">'.$sac_code.'</td>
					
				</tr>
				<tr>
					<td style=" text-align: left;">Support Centre Name</td>
					<td style="text-align: left;">'.$dealerName.'</td>
					
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
					<td style=" text-align: left;">Support Cont Person</td>
					<td style="text-align: left;">'.$supportContPerson.'</td>
					
				</tr>
				<tr>
					<td style=" text-align: left;">Support Cont Number</td>
					<td style="text-align: left;">'.$supportContPersonMob.'</td>
					
				</tr>';
				if($remark_type != 'Closed'){
					$body .= '<tr>
						<td style=" text-align: left;">Latitude</td>
						<td style="text-align: left;">'.$query[0]->latitude.'</td>
					</tr>
					<tr>
						<td style=" text-align: left;">Longitude</td>
						<td style="text-align: left;">'.$query[0]->longitude.'</td>
					</tr>';
				}
				for($i=0;$i<sizeof($assign_remark_log);$i++){
					$date = date('d-m-Y H:i:s',strtotime($assign_remark_date_log[$i]));
					if($i == 0){
						$body .= '<tr><td style="text-align: left;">Latest Comment '.$date.'</td>';
					}else{
						$body .= '<tr><td style="text-align: left;">Previous Comment '.$date.'</td>';
					}
					$body .= '<td style="text-align: left;">'.str_replace("'","",$assign_remark_log[$i]).'</td></tr>';
				}					
				$body .='<tr>
					<td style=" text-align: left;">Actual Response Date and Time</td>
					<td style="text-align: left;">'.$responseDateTime.'</td>
					
				</tr>
				<tr>
					<td style=" text-align: left;">Restoration date and time</td>
					<td style="text-align: left;">'.$restoration.'</td>
				</tr>
				
			</table>
			
			<p>Regards,</p>
			<p>Ashok Leyland Helpline</p>';
			$data=['body'=>$body];
			$toUserArr = array_filter($toUserArr);
			$ccUserArr = array_filter($ccUserArr);
			try {
				DB::select("INSERT INTO email_status (type, subject, body, toMail, ccMail) VALUES ('Closed Email Send Fields', '$subject', '$body','".implode(",",$toUserArr)."','".implode(",",$ccUserArr)."')");

				Mail::send('assigned_email',["data"=>$data],function ($message) use ($toUserArr, $ccUserArr, $subject) {
					$message->to($toUserArr)->cc($ccUserArr)->bcc(['al.crmautomailers@cogenteservices.in'])->subject($subject);
					$message->from('ALHelpline@ashokleyland.com');
				});
				
			} catch (\Exception $ii){
				$ii  = $ii->getMessage();
				DB::table('updation_exception')->insert(['complaint_number'=>"$complaint_number",'type'=>"Mail for Team",'exception'=>"$ii"]);
		   }
			/* ********************************Email Send Fields***************************************************** */ 
			/* Customer Email Send Fields */ 
			/* *********************************************************************************************/
			$ccUserTeamArr ='';
			$ccUserTeamSql =   DB::select("Select email,name from users where role in (1,78,79) and FIND_IN_SET($assign_to, dealer_id) and email !='' and flag=1");
			if(sizeof($ccUserTeamSql)>0){
				foreach($ccUserTeamSql as $row){
					if($row->email!=''){
						$ccUser = trim($row->email);
						$ccUser = str_replace(":",",",$ccUser);
						$ccUser = str_replace(";",",",$ccUser);
						$ccUser = str_replace(" ",",",$ccUser);
						$ccUserTeamArr .= $ccUser.",";
						//$ccUserTeamArr[] = $row->email.",";
					}
				}
				$ccUserTeamArr = rtrim($ccUserTeamArr,',');
				$ccUserTeamArr = explode(",",$ccUserTeamArr);
			}else{
				$ccUserTeamArr = array("KRYSALIS_Vandhana1@ashokleyland.com");
			}
			$ccOwnerEmail = array_merge($ccOwnerEmail,$ccUserTeamArr);
			/* *********************************************************************************************/

			if($remark_type == 'Reassigned support'){
				$cloBody1 ='<p>Please find below mentioned the details for the AL Breakdown ticket reassigned.</p>';
			}else{				
				$cloBody1 ='<p>Please find below AL Breakdown ticket Closure Email.</p>';
			}
			$subject1="AL Breakdown Ticket - $complaint_number - $regNo - $remark_type";
			$body1 = '<p>Dear Customer, </p>
			'.$cloBody1.'
			<table border="1" style="font-family: sans-serif;">
				<tr>
					<td style=" text-align: left;">Customer Name</td>
					<td style="text-align: left;">'.$db_owner_name.'</td>					
				</tr>
				<tr>
					<td style=" text-align: left;">Ticket Number</td>
					<td style="text-align: left;"><a href="'.url('update-case',['id' =>$ticetId]).'" >'.$complaint_number.'</a></td>		
				
				</tr>
				<tr>
					<td style=" text-align: left;">Call log date and Time</td>
					<td style="text-align: left;">'.$ticketCreatedDateTime.'</td>
					
				</tr>
				<tr>
					<td style=" text-align: left;">Breakdown Location</td>
					<td style="text-align: left;">'.$query[0]->location.'</td>
					
				</tr>
				<tr>
					<td style=" text-align: left;">Issue</td>
					<td style="text-align: left;">'.$query[0]->standard_remark.'</td>
					
				</tr>
				<tr>
					<td style=" text-align: left;">Support Centre Code</td>
					<td style="text-align: left;">'.$sac_code.'</td>
					
				</tr>
				<tr>
					<td style=" text-align: left;">Support Centre Name</td>
					<td style="text-align: left;">'.$dealerName.'</td>
					
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
					<td style=" text-align: left;">Support Cont Person</td>
					<td style="text-align: left;">'.$supportContPerson.'</td>
					
				</tr>
				<tr>
					<td style=" text-align: left;">Support Cont Number</td>
					<td style="text-align: left;">'.$supportContPersonMob.'</td>
					
				</tr>
				<tr>
					<td style=" text-align: left;">Latitude</td>
					<td style="text-align: left;">'.$query[0]->latitude.'</td>
				</tr>
				<tr>
					<td style=" text-align: left;">Longitude</td>
					<td style="text-align: left;">'.$query[0]->longitude.'</td>
				</tr>';
					for($i=0;$i<sizeof($assign_remark_log);$i++){
						$date = date('d-m-Y H:i:s',strtotime($assign_remark_date_log[$i]));
						if($i == 0){
							$body1 .= '<tr><td style="text-align: left;">Latest Comment '.$date.'</td>';
						}else{
							$body1 .= '<tr><td style="text-align: left;">Previous Comment '.$date.'</td>';
						}
						$body1 .= '<td style="text-align: left;">'.str_replace("'","",$assign_remark_log[$i]).'</td></tr>';
					}				
					$body1 .='<tr>
					<td style=" text-align: left;">Restoration date and time</td>
					<td style="text-align: left;">'.$restoration.'</td>
				</tr>
				
				</table>
					
					<p>Regards,</p>
					<p>Ashok Leyland Helpline</p>';
					$data=['body'=>$body1];
					$ownerContactEmail = array_filter($ownerContactEmail);
					$ccOwnerEmail = array_filter($ccOwnerEmail);
					/* if($remark_type == "Closed" || ($remark_type == 'Reassigned support' && $db_remark_type != $remark_type)){
						try {
							Mail::send('assigned_email',["data"=>$data],function ($message) use ($ownerContactEmail, $ccOwnerEmail, $subject1) {
								$message->to($ownerContactEmail)->cc($ccOwnerEmail)->bcc(['ashutosh.rawat@cogenteservices.in','DeepakKumar.Mandal@ashokleyland.com','al.crmautomailers@cogenteservices.in'])->subject($subject1);
								$message->from('ALHelpline@ashokleyland.com');
							});
							DB::select("INSERT INTO email_status (type, subject, body, toMail, ccMail) VALUES ('Closed Email Send Customer', '$subject1', '$body1','".implode(",",$ownerContactEmail)."','".implode(",",$ccOwnerEmail)."')");
						} catch (\Exception $jj){
							$jj  = $jj->getMessage();
							DB::table('updation_exception')->insert(['complaint_number'=>"$complaint_number",'type'=>"Mail for Customer",'exception'=>"$jj"]);
					   }
					} */
					
				/* Customer Email Send Fields */ 


			}else{
				/* Email Send */
			
													/* Email Send Fields*/
			
			// ********************************Email Send Fields***************************************************** 
			// Customer Email Send Fields 
			/* *********************************************************************************************/
			$ccUserTeamArr ='';
			$ccUserTeamSql =   DB::select("Select email,name from users where role in (1,78,79) and FIND_IN_SET($assign_to, dealer_id) and email !='' and flag=1 ");
			if(sizeof($ccUserTeamSql)>0){
				foreach($ccUserTeamSql as $row){
					if($row->email!=''){
						$ccUser = trim($row->email);
						$ccUser = str_replace(":",",",$ccUser);
						$ccUser = str_replace(";",",",$ccUser);
						$ccUser = str_replace(" ",",",$ccUser);
						$ccUserTeamArr .= $ccUser.",";
						//$ccUserTeamArr[] = $row->email.",";
					}
				}
				$ccUserTeamArr = rtrim($ccUserTeamArr,',');
				$ccUserTeamArr = explode(",",$ccUserTeamArr);
			}else{
				$ccUserTeamArr = array("KRYSALIS_Vandhana1@ashokleyland.com");
			}
			
			/* *********************************************************************************************/
			$ownerMasterId = $query[0]->ownerId;
			$ownerCntact = DB::select("SELECT owner_contact_email FROM mstr_owner_contact where owner_id=$ownerMasterId and owner_contact_email!='' ");
 			$ownerContactEmail ='';
 			if(sizeof($ownerCntact)>0 ){
 				foreach ($ownerCntact as $row) {
					$ownerContactEmailDB=$row->owner_contact_email !=''?$row->owner_contact_email:'KRYSALIS_Vandhana1@ashokleyland.com'; 
					$ownerContactEmailDB = str_replace(":",",",$ownerContactEmailDB); 
					$ownerContactEmailDB = str_replace(";",",",$ownerContactEmailDB); 
					$ownerContactEmailDB = str_replace(" ","",$ownerContactEmailDB); 
					$ownerContactEmail .=$ownerContactEmailDB.','; 
 				}
 				$ownerContactEmail = rtrim($ownerContactEmail,',');
 				$ownerContactEmail = explode(",",$ownerContactEmail);
 			}else{
 				$ownerContactEmail = array("KRYSALIS_Vandhana1@ashokleyland.com");
 			}
			$owner_contact_email[] = $query[0]->owner_contact_email!=''?$query[0]->owner_contact_email:'KRYSALIS_Vandhana1@ashokleyland.com';
			$owenerSqlData = DB::select("Select alse_mail,asm_mail from mstr_owner where id = $ownerMasterId");
			if(sizeof($owenerSqlData)>0){
				$ownerALSEEmail = $owenerSqlData[0]->alse_mail!=''?$owenerSqlData[0]->alse_mail:'KRYSALIS_Vandhana1@ashokleyland.com'; 
				$ownerALSEEmail = str_replace(":",",",$ownerALSEEmail); 
				$ownerALSEEmail = str_replace(";",",",$ownerALSEEmail); 
				$ownerALSEEmail = str_replace(" ","",$ownerALSEEmail); 
				$alseOwnerEmail = $ownerALSEEmail; 
				//$alseOwnerEmail = $owenerSqlData[0]->alse_mail!=''?$owenerSqlData[0]->alse_mail:'KRYSALIS_Vandhana1@ashokleyland.com'; 
				//$asmOwnerEmail = $owenerSqlData[0]->asm_mail!=''?$owenerSqlData[0]->asm_mail:'KRYSALIS_Vandhana1@ashokleyland.com'; 
				$ownerASMEmail=$owenerSqlData[0]->asm_mail!=''?$owenerSqlData[0]->asm_mail:'KRYSALIS_Vandhana1@ashokleyland.com'; 
				$ownerASMEmail = str_replace(":",",",$ownerASMEmail);
				$ownerASMEmail = str_replace(";",",",$ownerASMEmail); 
				$ownerASMEmail = str_replace(" ","",$ownerASMEmail); 
				$asmOwnerEmail = $ownerASMEmail; 
			}
			$asmOwnerEmailArr = explode(",",$asmOwnerEmail); 
			$ccOwnerEmail = explode(",",$alseOwnerEmail); 
			$ccOwnerEmail = array_merge($ccOwnerEmail,$asmOwnerEmailArr);
			$ccOwnerEmail = array_merge($ccOwnerEmail,$ccUserTeamArr);
			
			$subject1="AL Breakdown Ticket - $complaint_number - $regNo";
					$body1 = '<p>Dear Customer, </p>
					<p>Please find below the vehicle status :</p>
					<table border="1" style="font-family: sans-serif">
						<tr>
							<td style="width: 60px; text-align: left;">Ticket Number</td>
							<td style="text-align: left;"><a href="'.url('update-case',['id' =>$ticetId]).'" >'.$complaint_number.'</a></td>
						</tr>
						<tr>
							<td style="width: 60px; text-align: left;">Ticket Status</td>
							<td style="text-align: left;">'.$remark_type.'</td>
						</tr>';
						for($i=0;$i<sizeof($assign_remark_log);$i++){
							$date = date('d-m-Y H:i:s',strtotime($assign_remark_date_log[$i]));
							if($i == 0){
								$body1 .= '<tr><td style="text-align: left;">Latest Comment '.$date.'</td>';
							}else{
								$body1 .= '<tr><td style="text-align: left;">Previous Comment '.$date.'</td>';
							}
							$body1 .= '<td style="text-align: left;">'.str_replace("'","",$assign_remark_log[$i]).'</td></tr>';
						}	
						$body1 .= '
					</table>
					
					<p>Regards,</p>
					<p>Ashok Leyland Helpline</p>';
					$data=['body'=>$body1];
					$ownerContactEmail = array_filter($ownerContactEmail);
					$ccOwnerEmail = array_filter($ccOwnerEmail);
					if(Auth::user()->user_type_id != '3'){
						if( ($db_assign_remarks != $assign_remarks) || ($db_remark_type != $remark_type) ){
							if($remark_type != 'Awaiting for dealer closer'){
								try {
									DB::select("INSERT INTO email_status (type, subject, body, toMail, ccMail) VALUES ('Update Email Send Customer', '$subject1', '$body1','".implode(",",$ownerContactEmail)."','".implode(",",$ccOwnerEmail)."')");

									/* Mail::send('assigned_email',["data"=>$data],function ($message) use ($ownerContactEmail, $ccOwnerEmail, $subject1) {
										$message->to($ownerContactEmail)->cc($ccOwnerEmail)->bcc(['ashutosh.rawat@cogenteservices.in','DeepakKumar.Mandal@ashokleyland.com','al.crmautomailers@cogenteservices.in','saurav.kandwal@cogenteservices.com','shashi.ranjan@cogenteservices.com'])->subject($subject1);
										$message->from('ALHelpline@ashokleyland.com');
									}); */
									
								}catch (\Exception $kk){
									$kk  = $kk->getMessage();
									DB::table('updation_exception')->insert(['complaint_number'=>"$complaint_number",'type'=>"mail for update to Customer",'exception'=>"$kk"]);
							   }
							}
						}else if($db_assign_to != $assign_to){
							if($remark_type != 'Awaiting for dealer closer'){
								try {
									DB::select("INSERT INTO email_status (type, subject, body, toMail, ccMail) VALUES ('Update Email Send Customer', '$subject1', '$body1','".implode(",",$ownerContactEmail)."','".implode(",",$ccOwnerEmail)."')");
									
									/* Mail::send('assigned_email',["data"=>$data],function ($message) use ($ownerContactEmail, $ccOwnerEmail, $subject1) {
										$message->to($ownerContactEmail)->cc($ccOwnerEmail)->bcc(['ashutosh.rawat@cogenteservices.in','DeepakKumar.Mandal@ashokleyland.com','al.crmautomailers@cogenteservices.in','saurav.kandwal@cogenteservices.com','shashi.ranjan@cogenteservices.com'])->subject($subject1);
										$message->from('ALHelpline@ashokleyland.com');
									}); */
									
								} catch (\Exception $ll){
									$ll  = $ll->getMessage();
									DB::table('updation_exception')->insert(['complaint_number'=>"$complaint_number",'type'=>"mail for update to Customer 123",'exception'=>"$ll"]);
							   }
							}
						}
					}
					
			/* Customer Email Send Fields */ 
			
			}
		$dealerNameQuery = DB::select("select dealer_name,sac_code from mstr_dealer where id=$assign_to ");
		$sac_code = $dealerNameQuery[0]->sac_code;
		$dealer_name = $dealerNameQuery[0]->dealer_name;
		$acceptance = $acceptance == '1'?'Yes':'No';
		$action_by = Auth::user()->name;
		$ticketCreatedDateTime = $query[0]->created_at;
		/* 12-10-2023 */
			if($remark_type == 'Closed'){
				$closedDateTime = date('Y-m-d H:i:s');
				$ticketCreatedDateTime = $closedDateTime;
			}else{
				$closedDateTime='';
			}
			$actual_response_time_customer = $request->input('actual_response_time_customer');
			$actual_restoration_customer = $request->input('tat_scheduled_customer');
			
			$now = Carbon::now();
			$t1 = Carbon::parse($now);
			$t2 = Carbon::parse($ticketCreatedDateTime);
			$diff = $t1->diff($t2);
			$timeSinceTicketCreated = 'Day: '.$diff->d.', Hour: '.$diff->h.', Minute: '.$diff->m;
			//dd($diff);
			
		/* 12-10-2023 */
		$jsonDataArray = array('timeSinceTicketCreated'=>$timeSinceTicketCreated,'actual_response_time_customer'=>$actual_response_time_customer,'actual_restoration_customer'=>$actual_restoration_customer,'closed_datetime'=>$closedDateTime,'ticket_no'=>$complaint_number,'assign_to'=>$sac_code,'dealer_name'=>$dealer_name,'assign_work_manager'=>$assign_work_manager,'assign_work_manager_mobile'=>$assign_work_manager_mobileMSUAPI,'remark_type'=>$remark_type,'disposition'=>$disposition,'agent_remark'=>$this->removeSpecialChar($agent_remark),'estimated_response_time'=>$estimated_response_time,'actual_response_time'=>$actual_response_time,'tat_scheduled'=>$tat_scheduled,'restoration_type'=>$restoration_type,'acceptance'=>$acceptance, 'followup_time'=>$followup_time,'feedback_rating'=>$feedback_rating,'feedback_desc'=>$feedback_desc,'assign_remarks'=>$this->removeSpecialChar($assign_remarks),'action_by'=>$action_by,'aggregate'=>$aggregate,'cc_status'=>$remark_type);
		
		$jsonData = json_encode($jsonDataArray);
		try {			
			DB::select("INSERT INTO msu_api_updation (complaint_number, remarks,type) VALUES ('$complaint_number', '$jsonData','Update')");
		} catch (\Exception $ffU) {
			$ffU = $ffU->getMessage();
			DB::table('updation_exception')->insert(['complaint_number'=>"$complaint_number",'type'=>"msu_api update",'exception'=>"$ffU"]);
		}
			
		/****************************MSU API**************************************/
		try {
			$i=0;
			do{
				$i++;
					$curl = curl_init();			
					curl_setopt_array($curl, array(
					// Live API 
					CURLOPT_URL => 'https://hz8tb0w051.execute-api.us-east-1.amazonaws.com/v1/reverse-data/ticket-update-cogent',
					// Uat API
					//CURLOPT_URL => 'https://y1keqvs2ge.execute-api.us-east-1.amazonaws.com/v2/reverse-data/ticket-update-cogent',
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => '',
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 0,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => 'POST',
					CURLOPT_POSTFIELDS => $jsonData,
					CURLOPT_HTTPHEADER => array(
						'Authorization: Basic bXN1Y29nZW50OmlZbVBSaDJubXA=',
						'Content-Type: application/json'
					),
				));
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				$response = curl_exec($curl);
				curl_close($curl);
				$isJson = is_array(json_decode($response,true));
				if($isJson){				
					$check=false;
				}else{
					if($i > 4){
						$check =false;
					}else{
						$check=true;
					}	
				}
			}while($check);
			if($isJson){				
				$responseDecode = json_decode($response);
				$apiResult123 = $responseDecode->Message;
				// $apiTicketNumber = $responseDecode->statusCode;
				DB::select("INSERT INTO updation_api_remarks (complaint_number, remarks) VALUES ('$complaint_number', '$apiResult123')");
			}else{
				DB::select("INSERT INTO updation_api_remarks (complaint_number, remarks) VALUES ('$complaint_number', 'Failed Update')");
			}			
				
			
		} catch (\Exception $llMSU){
			$llMSU  = $llMSU->getMessage();
			DB::table('updation_exception')->insert(['complaint_number'=>"$complaint_number",'type'=>"MSU API Updation",'exception'=>"$llMSU"]);
	   }	
		
		
		/****************************MSU API**************************************/
		/****************************Cogent Feedback**************************************/
		$caller_language = $request->input('caller_language')!=''?$request->input('caller_language'):'Hindi';	   
		if($remark_type == 'Dealer Feedback'){
			try {				
				$dealerQuery = DB::table('mstr_dealer')->select('shift_time')->where('id',$assign_to)->take(1)->get();
				$shiftTime = $dealerQuery[0]->shift_time!=''?$dealerQuery[0]->shift_time:'No';
				DB::table('cogent_dealer_followups')->insert(['complaint_number'=>"$complaint_number",'lang'=>"$caller_language",'shift_time'=>"$shiftTime"]);
			} catch (\Exception $dealExc){
				$dealExc  = $dealExc->getMessage();
				DB::table('updation_exception')->insert(['complaint_number'=>"$complaint_number",'type'=>"Dealer Feedback",'exception'=>"$dealExc"]);
			}	
		}
		if($remark_type == 'Completed'){
			try {					
				$dealerQuery = DB::table('mstr_dealer')->select('shift_time')->where('id',$assign_to)->take(1)->get();
				$shiftTime = $dealerQuery[0]->shift_time!=''?$dealerQuery[0]->shift_time:'No';
				DB::table('cogent_complete_followups')->insert(['complaint_number'=>"$complaint_number",'lang'=>"$caller_language",'shift_time'=>"$shiftTime"]);
			} catch (\Exception $compExc){
				$compExc  = $compExc->getMessage();
				DB::table('updation_exception')->insert(['complaint_number'=>"$complaint_number",'type'=>"Completed Feedback",'exception'=>"$compExc"]);
			}	
		}
		
		/****************************Cogent Feedback**************************************/
			
		 	$notification = array(
				'message' => "Updated Successfully",
				'alert-type' => "success"
			);
			return back()->with($notification);
		/* }catch (\Exception $ex){
	 		$notification = array(
                'message' => $ex->getMessage().'   '.$ex->getLine().'    '.$ex->getCode() ,
                'alert-type' => 'error'
            );
	    	return back()->with($notification);
		} */
	}
	 
	public function getCustomerDetails(Request $request){
		try{
			$phonenumbers = $request->input('ph');
			$getCustDetails = DB::table("mstr_customer as mc")->select('mc.id','mc.customerOrg','mcc.custname','mcc.email','mcc.id as contactId','mcc.mobile2 as mobile2')->where('mobile1',$phonenumbers)->orWhere('mobile2',$phonenumbers)->join('mstr_customer_contact as mcc','mcc.customerId','mc.id')->get();
			/*$getCustDetails = DB::select("select concat(id,',',region,',',primary_city,',',company_name,',',primary_telephone1,',',primary_contact_person,',',email) as fields from mstr_customer where primary_telephone1='".$phonenumbers."'");*/
			/*echo "<script>$(function () {
			$('#btnClosePopup').on('click', function() {
			$('#myModal').modal('hide');
			});
			});
			</script>";*/
			//dd($getCustDetails);
			if(sizeof($getCustDetails)>0){
				Echo '<div class="table-responsive">
					<table id="order-listing" class="table">
	                    <thead>
	                        <tr>
	                        	<th>#</th>
								<th>Contact person</th>
								<th>Email</th>																
								<th>Customer Organiation</th>		
								<th>Seconday Mobile</th>		
	                        </tr>
	                    </thead>
	                    <tbody>';
	                    $count=1;
						foreach($getCustDetails as $row)
						{
						$mobile2 = $row->mobile2!=''?$row->mobile2:'NA';
	                        Echo '<tr>
	                        	<td>'.$count.'</td>
								<td class="cls_custname" onclick="CustomerData('.$row->contactId.');" style="cursor:pointer;">'.$row->custname.'</td>
								<td class="cls_email" onclick="CustomerData('.$row->contactId.');" style="cursor:pointer;">'.$row->email.'</td>
								<td class="cls_customerOrg" onclick="CustomerData('.$row->contactId.');" style="cursor:pointer;">'.$row->customerOrg.'</td>                           
								<td class="cls_mobile2" onclick="CustomerData('.$row->contactId.');" style="cursor:pointer;">'.$mobile2.'</td>                           
	                            </tr>';
	                            $count++;
	                    }	                    
	                    echo '</tbody>
	                </table>
	            </div>';
			}else{
				echo $notFound =  "No Customer Found";
			}
			
		}catch (\Exception $ex){
	 		$notification = array(
                'message' => $ex->getMessage(),
                'alert-type' => 'error'
            );
	    	return back()->with($notification);
		}
	}
	
	public function getCustomerDetailsId(Request $request){
		try{
			$id  = $request->input('id');
			
			$sql = DB::select("select mc.id,mc.customerOrg,mcc.custname,mcc.email,mcc.segment,mcc.complaint_cat,mcc.region,mcc.vehicle,mcc.brand,mcc.dealer_code_asoc,mcc.mobile2 from mstr_customer mc join mstr_customer_contact mcc on mc.id = mcc.customerId where mcc.id =$id");			
			foreach ($sql as $row) {
				echo $row->custname.','.$row->email.','.$row->customerOrg.'~'.$row->segment.'~'.$row->complaint_cat.'~'.$row->region.'~'.$row->vehicle.'~'.$row->brand.'~'.$row->dealer_code_asoc.'~'.$row->mobile2;
			}
		}catch (\Exception $ex){
	 		$notification = array(
                'message' => $ex->getMessage(),
                'alert-type' => 'error'
            );
	    	return back()->with($notification);
		}
	}
	public function getSegmentId(Request $request){
		try{
			$segmentId = $request->input('segmentId');
			$segId = rtrim($segmentId,',');
			$sql = DB::select("select id,segment from product_segment where id in($segId)");			
			foreach ($sql as $row) {
				echo $row->id.'~'.$row->segment.',';
			}
		}catch (\Exception $ex){
	 		$notification = array(
                'message' => $ex->getMessage(),
                'alert-type' => 'error'
            );
	    	return back()->with($notification);
		}
	}
	public function getRegionId(Request $request){
		try{
			$regionId = $request->input('regionId');
			//$delId = rtrim($dealerCode,',');
			$sql = DB::select("select id,region from mstr_region where id ='$regionId'");			
			foreach ($sql as $row) {
				echo $row->id.'~'.$row->region.',';
			}
		}catch (\Exception $ex){
	 		$notification = array(
                'message' => $ex->getMessage(),
                'alert-type' => 'error'
            );
	    	return back()->with($notification);
		}
	}
	public function getComplaintCatId(Request $request){
		try{
			$complaint_cat = $request->input('complaint_cat');
			$complntId = explode(',',$complaint_cat);
			foreach($complntId as $row){
				$sql = DB::select("select id,complaint_type from mstr_complaint where id ='$row'");
				echo $sql[0]->id.'~'.$sql[0]->complaint_type.',';
			}
			/*$sql = DB::select("select id,complaint_type from mstr_complaint where id ='$complaint_cat'");			
			foreach ($sql as $row) {
				echo $row->id.'~'.$row->complaint_type.',';
			}*/
		}catch (\Exception $ex){
	 		$notification = array(
                'message' => $ex->getMessage(),
                'alert-type' => 'error'
            );
	    	return back()->with($notification);
		}
	}
	public function getBrandId(Request $request){
		try{
			$brand = $request->input('brand');
			$brandArr = explode(',',$brand);
			foreach($brandArr as $row){
				$sql = DB::select("select id,brand from mstr_brand where id ='$row' and flag='1'");
				echo $sql[0]->id.'~'.$sql[0]->brand.',';
			}
			
		}catch (\Exception $ex){
	 		$notification = array(
                'message' => $ex->getMessage(),
                'alert-type' => 'error'
            );
	    	return back()->with($notification);
		}
	}
	public function getDealerCodeAsocId(Request $request){
		try{
			$dealer_code_asoc = $request->input('dealer_code_asoc');
			$dealer_code_asocArr = explode(',',$dealer_code_asoc);
			foreach($dealer_code_asocArr as $row){
				$sql = DB::select("select id,dealer_name from mstr_dealer where id ='$row' ");
				echo $sql[0]->id.'~'.$sql[0]->dealer_name.',';
			}
			
		}catch (\Exception $ex){
	 		$notification = array(
                'message' => $ex->getMessage(),
                'alert-type' => 'error'
            );
	    	return back()->with($notification);
		}
	}
	public function getProductId(Request $request){
		try{
			$vehicle = $request->input('vehicle');
			$sql = DB::select("select id,vehicle from mstr_vehicle where id ='$vehicle' and flag = '1'");			
			foreach ($sql as $row) {
				echo $row->id.'~'.$row->vehicle.',';
			}
		}catch (\Exception $ex){
	 		$notification = array(
                'message' => $ex->getMessage(),
                'alert-type' => 'error'
            );
	    	return back()->with($notification);
		}
	}
	public function searchDealer(Request $request){
		try{
			$title  = $request->input('title');
			$sql = DB::select("SELECT id,dealer_name FROM mstr_dealer where dealer_name like '%$title%'");
			if(sizeof($sql)>0){
			 	foreach($sql as $row){
			 		echo "<option value=$row->id>$row->dealer_name</option>";
					
				}			 	  
			}else{
			 	echo "<option value='NA'>No Dealer Found</option>";
			}
			
		}catch (\Exception $ex){
	 		$notification = array(
                'message' => $ex->getMessage(),
                'alert-type' => 'error'
            );
	    	return back()->with($notification);
		}
	}
	public function getAssignUser(Request $request){
		try{
			
			$complaintcategory  = $request->input('complaintcategory');
			$sub_complaint_type  = $request->input('sub_complaint_type');
			$product  = $request->input('product');
			$segment  = $request->input('segment');
			$Zone  = $request->input('Zone');
			$City  = $request->input('City');
			$Dealer = $request->input('DealerIds');
			$dealerQuery = DB::table('mstr_dealer')->select('vecv_owned')->where('id',$Dealer)->get();
			$dealerPrivate =  (sizeof($dealerQuery) !=0)?$dealerQuery[0]->vecv_owned:'';
			if($complaintcategory != "NA" && $sub_complaint_type != "NA" && $product != "NA" && $segment != "NA" && $Zone != "NA" && $City != "NA"){
			for ($i=1;$i<=10;$i++) {
					if($dealerPrivate == '1'){
					
			$roleQuery = DB::select("SELECT escalated_to FROM mstr_escalations where complaint_type=$complaintcategory and FIND_IN_SET($sub_complaint_type,sub_complaint_type)  and escalation_stage=$i and vehicle=$product and  FIND_IN_SET($segment,segment)");
		}else{		
			$roleQuery = DB::select("SELECT escalated_to FROM mstr_dealer_escalations where complaint_type=$complaintcategory and FIND_IN_SET($sub_complaint_type,sub_complaint_type)  and escalation_stage=$i and vehicle=$product and FIND_IN_SET($segment,segment)");
			
		}	
			$role = (sizeof($roleQuery) !=0)?$roleQuery[0]->escalated_to:'empty';			
			$emp_query = DB::select("SELECT id,email,name,reporting_manager FROM users where role ='$role' and FIND_IN_SET($Zone,zone) and FIND_IN_SET($product,product) and FIND_IN_SET($segment,segment) and FIND_IN_SET($City,city) and flag=1");			
			if($dealerPrivate == '1'){
	 			$ccQuery = DB::select("SELECT escalated_to,cc_to FROM mstr_escalations where complaint_type=$complaintcategory and sub_complaint_type=$sub_complaint_type  and escalation_stage=$i and vehicle=$product and FIND_IN_SET($segment,segment)");
			}else{
				$ccQuery = DB::select("SELECT escalated_to,cc_to FROM mstr_dealer_escalations where complaint_type=$complaintcategory and sub_complaint_type=$sub_complaint_type  and escalation_stage=$i and vehicle=$product and segment=$segment");
			}
			
			 if(sizeof($emp_query) ==0 && $i==1){			 	//break;
			}else{
				 if (sizeof($emp_query) !=0) {
					 $ccMail = (sizeof($ccQuery) !=0)?$ccQuery[0]->cc_to:'0';
			 		$level = $i;
			 		break;
			 	}
			 	
			}
			}
			echo $case_owner = (sizeof($emp_query) !=0)?$emp_query[0]->name:'Open';
			}else{
				echo 'Fill Mandatory Fields';
			}
			
			
		}catch (\Exception $ex){
	 		$notification = array(
                'message' => $ex->getMessage(),
                'alert-type' => 'error'
            );
	    	return back()->with($notification);
		}
	}
	
	public function createCaseByApi(Request $request){
		date_default_timezone_set('Asia/Kolkata');
		header('Content-type: application/json');
		$date_now =date("dmY");
		//$_POST =  '{"case_details":[{"phonenumbers":"9111111111","City":"10","Zone":"2","contactperson":"Cust Contact1","customerMail":"custcontact1@dispostable.com","customerorg":"Velocis","case_type":"Complaint","brands":"3","complaintcategory":"1","sub_complaint_type":"6","center_module":"1","product":"2","segment":"5","vehicle_registration":"test vehicle_registration","vehicle_model":"test vehicle_model","chassis_number":"test chassis_number","description":"API Creation","customer_contact_id":"1","DealerId":"1"}]}';
		
		$_POST = file_get_contents('php://input');
		$responseData = json_decode($_POST, TRUE);
		//if(isset($_POST) && !empty($_POST) )	
		if(isset($_POST) && !empty($_POST) && $_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$phonenumbers = $City = $Zone =$contactperson = $email = $customerorg = $case_type = $brands = $complaintcategory = $sub_complaint_type = $center_module =$product=$segment = $Dealer =$vehicle_registration= $vehicle_model=$chassis_number=$emp_query=$case_status=$description = $observations =$actionstaken = $customer_contact_id =$file ='';
			$emp_query='';$case_status='';
		foreach ($responseData['case_details'] as $row){
			$phonenumbers = $row['phonenumbers'];		
			$City = $row['City'];
			$Zone = $row['Zone'];		
			$contactperson = $row['contactperson'];
			$email = $row['customerMail'];
			$customerorg = $row['customerorg'];			
			$case_type = $row['case_type'];
			$brands = $row['brands'];
			$complaintcategory = $row['complaintcategory'];
			$sub_complaint_type = $row['sub_complaint_type'];
			$center_module = $row['center_module'];
			$product = $row['product'];
			$segment = $row['segment'];
			$Dealer = $row['DealerId'];
			$vehicle_registration= $row['vehicle_registration'];
			$vehicle_model= $row['vehicle_model'];
			$chassis_number= $row['chassis_number'];
			/*$customercode = $row['customercode'];*/		
			/*$location = $row['location'];*/
			/*$designation = $row['designation'];*/
			
			$description = $row['description'];
			$observations = '';
			$actionstaken = '';
			$customer_contact_id = $row['customer_contact_id'];
		}
		$fileName = '';
		$sesName= 'API';
		$sesUsertype= '';	
		$ccMail=$level='';		
		$customerQuery = DB::table('mstr_customer_contact')->select('customerId','custname')->where('email',$email)->get();		
		$dealerQuery = DB::table('mstr_dealer')->select('vecv_owned')->where('id',$Dealer)->get();
		$dealerPrivate =  (sizeof($dealerQuery) !=0)?$dealerQuery[0]->vecv_owned:'';		
		$customer_id = (sizeof($customerQuery) !=0)?$customerQuery[0]->customerId:''; 
		$custname = (sizeof($customerQuery) !=0)?$customerQuery[0]->custname:''; 		
		$roleQuery=$ccQuery='';
		for ($i=1;$i<=10;$i++) {
		if($dealerPrivate == '1'){
			$roleQuery = DB::select("SELECT escalated_to FROM mstr_escalations where complaint_type=$complaintcategory and FIND_IN_SET($sub_complaint_type,sub_complaint_type)  and escalation_stage=$i and vehicle=$product and  FIND_IN_SET($segment,segment)");			
		}else{		
			$roleQuery = DB::select("SELECT escalated_to FROM mstr_dealer_escalations where complaint_type=$complaintcategory and FIND_IN_SET($sub_complaint_type,sub_complaint_type)  and escalation_stage=$i and vehicle=$product and FIND_IN_SET($segment,segment)");			
		}	
			$role = (sizeof($roleQuery) !=0)?$roleQuery[0]->escalated_to:'empty';			
			$emp_query = DB::select("SELECT id,email,name,reporting_manager FROM users where role ='$role' and FIND_IN_SET($Zone,zone) and FIND_IN_SET($product,product) and FIND_IN_SET($segment,segment) and FIND_IN_SET($City,city) and flag=1");
			if($dealerPrivate == '1'){
	 			$ccQuery = DB::select("SELECT escalated_to,cc_to FROM mstr_escalations where complaint_type=$complaintcategory and sub_complaint_type=$sub_complaint_type  and escalation_stage=$i and vehicle=$product and FIND_IN_SET($segment,segment)");
			}else{
				$ccQuery = DB::select("SELECT escalated_to,cc_to FROM mstr_dealer_escalations where complaint_type=$complaintcategory and sub_complaint_type=$sub_complaint_type  and escalation_stage=$i and vehicle=$product and segment=$segment");
			}			
			 if(sizeof($emp_query) ==0 && $i==1){			 	//break;
			}else{
				 if (sizeof($emp_query) !=0) {
					 $ccMail = (sizeof($ccQuery) !=0)?$ccQuery[0]->cc_to:'0';
			 		$level = $i;
			 		break;
			 	}
			 	
			}
		}
		$level = $level!=''?$level:'0';
		$case_owner = (sizeof($emp_query) !=0)?$emp_query[0]->id:'';
		$caseOwnerName=$caseOwnerEmail='';
		if($case_owner !=''){
			$caseOwnerId = DB::select("select name,email from users where id= '$case_owner' and flag=1");
			$caseOwnerName= $caseOwnerId[0]->name;
			$caseOwnerEmail= $caseOwnerId[0]->email;
			$case_status='Assigned';
		}else{
			$case_status='Open';
		}	
		
		$assign_email = (sizeof($emp_query) !=0)?$emp_query[0]->email:'ALHelpline@ashokleyland.com';
		
		$assign_name = (sizeof($emp_query) !=0)?$emp_query[0]->name:'';	
		$rep_mnger = (sizeof($emp_query) !=0)?$emp_query[0]->reporting_manager:'';
	 	$rep_mngerSql = DB::table("users")->select("email")->where("name",$rep_mnger)->where("flag","1")->get();
	 	$reporting_manager = (sizeof($rep_mngerSql) >0)?($rep_mngerSql[0]->email):'ALHelpline@ashokleyland.com';
		
		$resultCase = DB::select("call Case_Creation('".$level."','".$case_type."','".$center_module."','".$City."','".$Zone."','".$Dealer."','".$product."','".$segment."','".$brands."','".$customerorg."','".$contactperson."','".$phonenumbers."','".$email."','".$complaintcategory."','".$sub_complaint_type."','".addslashes($description)."','".addslashes($observations)."','".addslashes($actionstaken)."','".$assign_email."','".$sesUsertype."','".$vehicle_registration."','".$vehicle_model."','".$chassis_number."','".$customer_id."','".$case_owner."','".$case_status."','".$customer_contact_id."','".$sesName."','".$fileName."','".$caseOwnerName."')");
		$lastInsertedId =$resultCase[0]->lastInsertedId;
		
		$modeId = DB::table('mstr_contact_center_module')->select('mode_name')->where('id',$center_module)->get();
		$modeName = (sizeof($modeId) !=0)?$modeId[0]->mode_name:'';
		$subComplaintId = DB::table('mstr_sub_complaint')->select('sub_complaint_type')->where('id',$sub_complaint_type)->where('flag','1')->get();
		$subComplaintName = (sizeof($subComplaintId) !=0)?$subComplaintId[0]->sub_complaint_type:'';
		$complaint_number =$resultCase[0]->complaint_number;
		$assign_email = (sizeof($emp_query) !=0)?$emp_query[0]->email:'ALHelpline@ashokleyland.com';
		$assign_name = (sizeof($emp_query) !=0)?$emp_query[0]->name:'';			
		$cmplntCat = DB::table('mstr_complaint')->select('id','complaint_type')->where('id',$complaintcategory)->get();
		$complaintCategory = $cmplntCat[0]->complaint_type;
		$created = date('d-m-Y');
		$currentUserMail = '';
		
		$subject='Customer Complaint '.$complaint_number.' is assigned';
		$body = '<p>Dear '.$assign_name.',</p>
      	<p>Intimation of the complaint being logged.</p>
				<table border="1">
					<tr>
						<th style="width: 60px; text-align: left;">Case Date</th>
						<th style="width: 60px; text-align: left;">Complaint Number</th>
						<th style="width: 105px; text-align: left;">Customer Organisation</th>
						<th style="width: 105px; text-align: left;">Customer Name</th>
						<th style="width: 105px; text-align: left;">Customer Number</th>
						<th style="width: 105px; text-align: left;">Customer Email</th>
						<th style="width: 60px; text-align: left;">Mode of Capturing</th>
						<th style="width: 60px; text-align: left;">Complaint Received By</th>
						<th style="width: 105px; text-align: left;">Complaint Category</th>
						<th style="width: 105px; text-align: left;">Complaint Sub Category</th>
						<th style="text-align: left;">Complaint Description</th>						
					</tr>
					<tr>						
					<td style="text-align: left;">'.$created.'</td>
					<td style="text-align: left;"><a href="'.url('update-case',['id' =>$lastInsertedId]).'" >'.$complaint_number.'</a></td>
					<td style="text-align: left;">'.$customerorg.'</td>
					<td style="text-align: left;">'.$custname.'</td>
					<td style="text-align: left;">'.$phonenumbers.'</td>
					<td style="text-align: left;">'.$email.'</td>
					<td style="text-align: left;">'.$modeName.'</td>
					<td style="text-align: left;">'.$currentUserMail.'</td>
					<td style="text-align: left;">'.$complaintCategory.'</td>
					<td style="text-align: left;">'.$subComplaintName.'</td>
					<td style="text-align: left;">'.addslashes($description).'</td>						
					</tr>
				</table>
      	<br/><p style="text-decoration: underline;">Next steps:</p>
      	<p>1) Kindly acknowledge the complaint with customer through the same mode in which the complaint is received. (E.g., acknowledge over phone for the complaints received through phone or call center; acknowledge over e-mail for the complaints received through e-mail.)</p>
      	<p>2) Understand the concern in detail from customer & update the same in “Observations” cell in the portal.</p>
      	<p>3) Change the complaint status as “Acknowledged”, within 24 hours of receiving the complaint.</p>
      	<p>Thanks & Regards,<br/>
      	Complaint Management System</p>';
      	
      	$ccMailId='';
		$ccMailIdArray = explode(",",$ccMail);
		
		
		foreach($ccMailIdArray as $row){
		
			$getCCMail = DB::select("SELECT email FROM users where role ='$row' and flag=1 and FIND_IN_SET($Zone,zone) and FIND_IN_SET($product,product) and FIND_IN_SET($segment,segment) and FIND_IN_SET($City,city)");
			$ccMailId .= (sizeof($getCCMail) !=0)?$getCCMail[0]->email.",":'ALHelpline@ashokleyland.com,';
		}
		
      	 $ccMailId = rtrim($ccMailId,',');
		
      	$ticketCaseStatus = 'create';
		  DB::table('cronjob_mail')->insert(['case_status'=>$ticketCaseStatus, 'subject'=>$subject, 'body'=>$body, 'toMail'=>$assign_email, 'ccMail'=>$ccMailId, 'reporting_manager'=>$reporting_manager,'customer_mail_id'=>$email,'complaint_receiver'=>$currentUserMail,'case_owner_email'=>$caseOwnerEmail]);
	
	
	    echo '{"status":"Success","Message ":"'.$resultCase[0]->Message.'"}';
			die;
	    }
	}

		public function caseDeleted($id){
			$ticketSQL = DB::select("Select complaint_number from cases where id=$id");
			$complaint_number = $ticketSQL[0]->complaint_number;
			DB::select("DELETE FROM cases WHERE id=$id");
			DB::select("DELETE FROM remarks WHERE complaint_number='$complaint_number'");
			DB::select("DELETE FROM followups WHERE complaint_number='$complaint_number'");
			DB::select("DELETE FROM followups_info WHERE complaint_number='$complaint_number'");
			
			//$delData = DB::select("call delete_with_one('mstr_user_type','id','$id')");
			/* Send delete Status to MSU */
				$delete_status = 1;
				$jsonDataArray = array('delete_status'=>$delete_status,'timeSinceTicketCreated'=>'','actual_response_time_customer'=>'','actual_restoration_customer'=>'','closed_datetime'=>'','ticket_no'=>$complaint_number,'assign_to'=>'','dealer_name'=>'','assign_work_manager'=>'','assign_work_manager_mobile'=>'','remark_type'=>'','disposition'=>'','agent_remark'=>'','estimated_response_time'=>'','actual_response_time'=>'','tat_scheduled'=>'','restoration_type'=>'','acceptance'=>'', 'followup_time'=>'','feedback_rating'=>'','feedback_desc'=>'','assign_remarks'=>'','action_by'=>'','aggregate'=>'','cc_status'=>'');		
				$jsonData = json_encode($jsonDataArray);

				try {			
					DB::select("INSERT INTO msu_api_updation (complaint_number, remarks,type) VALUES ('$complaint_number', '$jsonData','Delete')");
				} catch (\Exception $ffU) {
					$ffU = $ffU->getMessage();
					DB::table('updation_exception')->insert(['complaint_number'=>"$complaint_number",'type'=>"msu_api Delete",'exception'=>"$ffU"]);
				}
				$curl = curl_init();			
				curl_setopt_array($curl, array(
					// Live API 
					CURLOPT_URL => 'https://hz8tb0w051.execute-api.us-east-1.amazonaws.com/v1/reverse-data/ticket-update-cogent',
					//CURLOPT_URL => 'https://y1keqvs2ge.execute-api.us-east-1.amazonaws.com/v2/reverse-data/ticket-update-cogent',
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => '',
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 0,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => 'POST',
					CURLOPT_POSTFIELDS => $jsonData,
					CURLOPT_HTTPHEADER => array(
						'Authorization: Basic bXN1Y29nZW50OmlZbVBSaDJubXA=',
						'Content-Type: application/json'
					),
				));
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);	
				$response = curl_exec($curl);
				$error_msg = curl_error($curl);
				curl_close($curl);
				$isJson = is_array(json_decode($response,true));
			/* Send delete Status to MSU */
			$notification = array(
					'message' => "$complaint_number Deleted Successfully",
					'alert-type' => "success"
			);
			return redirect()->route('case-list')->with($notification);
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
		public function Msuapi(Request $request)
		{
			//dd("hello");
			$rawData = file_get_contents("php://input");
			$responseData = json_decode($rawData, TRUE);
			
			
			try{
				$program = Updateticket::create([
					'ticket_num' => $responseData['ticket_no'],
					'reg_no' => $responseData['reg_no'],
					'chasi_no' => $responseData['chasi_no'],
					'eng_no' =>   $responseData['eng_no'],
					'ticket_type' => $responseData['ticket_type'],
					'vin_no' => $responseData['vin_no'],
					'status' =>   $responseData['status'],
					'msv_type' =>  $responseData['msv_type'],
					'msv_reg_no' => $responseData['msv_reg_no'],
					'mechinic_name' => $responseData['mechinic_name'],
					'mechinic_number' => $responseData['mechinic_number'],
					'dealer_name'  => $responseData['dealer_name'],
					'dealer_code'  => $responseData['dealer_code'],
					'remark' => $responseData['remark'],
					'start_time' =>   $responseData['start_time'],
					'response_time_No' => $responseData['response_time_No'],
					'restore_time_No' =>   $responseData['restore_time_No'],
					'pause_time_No' =>   $responseData['pause_time_No'],
					'end_time' => $responseData['end_time'],
					'url' => $responseData['url'],
					'vehicle_mode' => $responseData['vehicle_mode']
				]);
				if ($program->exists) {
				return response()->json(['result' => 'received successfully.',
										'ticket_no' => $responseData['ticket_no'], 'ack_no' => 'test Acknowledged”']);	
				} else {
					return response()->json(['result' => 'not received.',
										'ticket_no' => $responseData['ticket_no'], 'ack_no' => 'test Acknowledged”']);	
				}
		}catch (\Exception $ex){				
				return response()->json(['result' => $ex->getMessage(),
										'ticket_no' => $responseData['ticket_no'], 'ack_no' => 'test Acknowledged”']);
			}
		}
		public function removeSpecialChar($str) {
 
			// Using str_replace() function
			// to replace the word
			$str = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $str);
			$res = str_replace( array( '\'', '"',
			',' , ';', '<', '>' ), ' ', $str);
			$string = str_replace(array("\n", "\r"), '', $res);
			// Returning the result
			return $string;
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
