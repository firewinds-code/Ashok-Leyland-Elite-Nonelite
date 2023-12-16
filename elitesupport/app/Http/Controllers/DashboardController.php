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
use Auth;
class DashboardController extends Controller
{
	public function __construct()
	{
		DB::select("SET sql_mode=''");
	}
	public function dashboardData(Request $request)
	{		
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
		return view('dashboard',$data);
		//return redirect()->route('dashboard')->with(['dashboardData1'=>$dashboardData1,'stateData'=>$stateData]);
		//return redirect('dashboard')->with('dashboardData1', $dashboardData1);
	}
	public function dashboard()
	{
		try {
				//dd(Auth::user()->role);
				$accessView ='';
				$state = Auth::user()->state;
				$data['regionData'] = DB::select("Select id,region from mstr_region");
				$data['statusData'] = DB::select("Select id,type from remark_type");
				if(Auth::user()->role == '87' || Auth::user()->role == '29' || Auth::user()->role == '30'){					
					$data['zone']= array(1,2,3,4,5,6,7);
				}else{
					$zone = Auth::user()->zone;	
					$data['zone']= explode(",",$zone);
				}
				
				$data['tat']= 0; 
				$data['dateto']= date('Y-m-d');
				$data['datefrom']= date("Y-m-d", strtotime("-1 months"));

				$typeArr = array();
					$remarkTypeSql = DB::table("remark_type")->select("type")->whereNotIn('type',array('Completed','Closed'))->get()->toArray();
					if(sizeof($remarkTypeSql)>0){
						for($p=0;$p<sizeof($remarkTypeSql);$p++){
							$typeArr[] = $remarkTypeSql[$p]->type;
						}
					}
					$data['ticketStatus']= $typeArr;
				/* $data['ticketStatus']= array('Arranging Parts Locally','Awaiting parts from AL','Awaiting AL Approval','Awaiting completion from Ancillary suppliers','Awaiting completion of contracted Job','Awaiting customer approval','Awaiting customer Payment','Awaiting Good will Approval','Awaiting parts from another dealer branch','Awaiting parts from customer','Dealer Feedback','Investigation in progress','Load transfer in progress','Man power not available','Mechanic left to BD spot','Mechanic reached BD spot','Moved to another vehicle on urgency','Public Holiday','Reassigned support','Response Delay','Response not Initiated','Restored by Self','Restored by Unknown support','Restored by Support','Vehicle being Towed','Vehicle reached support point','Work held up due to bandh','Work held up due to injury/accident','Work in progress','Workshop closed - Sunday','Assigned'); */
				// ,'Completed','Customer Confirmation Due','Customer Confirmation Completed','Customer Feedback','Closed'
				if(Auth::user()->role == '87' || Auth::user()->role == '29' || Auth::user()->role == '30'){
					$data['dealerData']=DB::table("mstr_dealer")->select('id','dealer_name')->get();
					$stateSql = DB::select("SELECT group_concat(id) as stateid FROM mstr_state");
 					$data['stateId'] = $stateSql[0]->stateid;
 					$citySql = DB::select("SELECT group_concat(id) as cityId FROM mstr_city");
 					$data['cityId'] = $citySql[0]->cityId;
 					$dealerSql = DB::select("SELECT id FROM mstr_dealer where flag=1");
 					$delId ='';
 					foreach($dealerSql as $row){ 
 						$delId .= $row->id.',';
 					}
 					$delArrId = rtrim($delId,",");
 					$data['dealerAllId'] = $delArrId;
				}else{
					$data['dealerData']=DB::table("mstr_dealer")->select('id','dealer_name')->where('state',$state)->get();
        		}
				
				return view('dashboard',$data);
			
		} catch (\Exception $ex) {
			$notification = array(
			'message' => $ex->getMessage(),
			'alert-type' => 'error'
			);
			return back()->with($notification);
		}
	}
	
	public function ajaxComplaintSearch(Request $request){

		$datefrom1 = $request->input('datefrom1')!=''?$request->input('datefrom1'):'0';
		$dateto1 = $request->input('dateto1')!=''?$request->input('dateto1'):'0';
		$AllZone = $request->input('AllZone')!=''?$request->input('AllZone'):'0';
		$Allstate = $request->input('Allstate')!=''?$request->input('Allstate'):'0';
		$Allcity = $request->input('Allcity')!=''?$request->input('Allcity'):'0';
		$Alldealer = $request->input('Alldealer')!=''?$request->input('Alldealer'):'0';
		$dealerId = $request->input('dealerId')!=''?$request->input('dealerId'):'0';
		$AllticketStatus = $request->input('AllticketStatus')!=''?$request->input('AllticketStatus'):'0';
		$tat = $request->input('tat')!=''?$request->input('tat'):'0';
		$location =Auth::user()->city;
		$data['tatData'] = $tat;
		$data['rowData'] = DB::select("Select c.id, c.complaint_number, c.vehicleId, c.ownerId, c.customer_contact_id, c.callerId, c.from_where, c.to_where, c.highway,c.ticket_type, c.aggregate,c.vehicle_problem, c.assign_to, c.dealer_mob_number, c.dealer_alt_mob_number, c.remark_type, c.disposition, c.agent_remark, c.standard_remark, c.assign_remarks, c.created_at,c.updated_at,c.tat_scheduled,c.actual_response_time,del.dealer_name,v.reg_number,o.owner_name,o.owner_company,oc.contact_name,cal.caller_name,
		aca.created_at as aca_created_at,aca.updated_at as aca_updated_at,
		acp.created_at as acp_created_at,acp.updated_at as acp_updated_at,
		apc.created_at as apc_created_at,apc.updated_at as apc_updated_at,
		vbt.created_at as vbt_created_at,vbt.updated_at as vbt_updated_at  
		from cases as c left join mstr_caller as cal on cal.id = c.callerId left join mstr_owner_contact as oc on oc.id = c.customer_contact_id left join mstr_owner as o on o.id=c.ownerId left join mstr_vehicle as v on v.id = c.vehicleId left join mstr_dealer as del on del.id = c.assign_to
		left join ticket_hold as aca on aca.complaint_number = c.complaint_number and aca.remark_type = 'Awaiting customer approval'
      left join ticket_hold as acp on acp.complaint_number = c.complaint_number and acp.remark_type = 'Awaiting customer Payment'
      left join ticket_hold as apc on apc.complaint_number = c.complaint_number and apc.remark_type = 'Awaiting parts from customer'
      left join ticket_hold as vbt on vbt.complaint_number = c.complaint_number and vbt.remark_type = 'Vehicle being Towed'
		where c.assign_to in($Alldealer) and c.remark_type in ($AllticketStatus) and cast(c.created_at as date) between cast('$datefrom1' as date) and cast('$dateto1' as date) and c.created_at <=DATE_ADD(now() , INTERVAL - $tat HOUR) and c.complaint_number!='' order by c.id desc");
		$view = view("dashboard_table",$data)->render();
		return response()->json(['html'=>$view,]);
	}
	public function ajaxTicketType(Request $request){

		$datefrom1 = $request->input('datefrom1')!=''?$request->input('datefrom1'):'0';
		$dateto1 = $request->input('dateto1')!=''?$request->input('dateto1'):'0';
		$AllZone = $request->input('AllZone')!=''?$request->input('AllZone'):'0';
		$Allstate = $request->input('Allstate')!=''?$request->input('Allstate'):'0';
		$Allcity = $request->input('Allcity')!=''?$request->input('Allcity'):'0';
		$Alldealer = $request->input('Alldealer')!=''?$request->input('Alldealer'):'0';
		$dealerId = $request->input('dealerId')!=''?$request->input('dealerId'):'0';
		$AllticketStatus = $request->input('AllticketStatus')!=''?$request->input('AllticketStatus'):'0';
		$tat = $request->input('tat')!=''?$request->input('tat'):'0';
		$location = Auth::user()->city;
				
		$rowData= DB::select("select ticket_type as ticket_type,count(*) as Counttickettype from cases where assign_to in($Alldealer) and remark_type in ($AllticketStatus) and cast(created_at as date) between cast(:datefrom1 as date) and cast(:dateto1 as date) and created_at <=DATE_ADD(now() , INTERVAL - :tat HOUR) and complaint_number!='' group by ticket_type",["datefrom1"=>$datefrom1,"dateto1"=>$dateto1,"tat"=>$tat]);
		$fetchData = '<table>';
		foreach($rowData as $row){
			$fetchData .='<tr><td style="padding-right: 44px;"><b>'.$row->ticket_type.'</b></td><td>'.$row->Counttickettype.'</td></tr>';
		}
		$fetchData .= '</table>';
		echo $fetchData;

	}
	
	public function ajaxPieSearch(Request $request){

		$datefrom1 = $request->input('datefrom1')!=''?$request->input('datefrom1'):'0';
		$dateto1 = $request->input('dateto1')!=''?$request->input('dateto1'):'0';
		$AllZone = $request->input('AllZone')!=''?$request->input('AllZone'):'0';
		$Allstate = $request->input('Allstate')!=''?$request->input('Allstate'):'0';
		$Allcity = $request->input('Allcity')!=''?$request->input('Allcity'):'0';
		$Alldealer = $request->input('Alldealer')!=''?$request->input('Alldealer'):'0';
		$dealerId = $request->input('dealerId')!=''?$request->input('dealerId'):'0';
		$AllticketStatus = $request->input('AllticketStatus')!=''?$request->input('AllticketStatus'):'0';
		$tat = $request->input('tat')!=''?$request->input('tat'):'0';
		$location = Auth::user()->city;
		
		//$tableQuery = DB::select("select remark_type as ticketStatus,count(*) as countStatus from cases where assign_to in($Alldealer) and remark_type in ($AllticketStatus) and created_at >= '$datefrom1' and created_at <= '$dateto1' and created_at <=DATE_ADD(now() , INTERVAL - $tat HOUR) and complaint_number!='' group by remark_type;");
		$tableQuery = DB::select("select remark_type as ticketStatus,count(*) as countStatus from cases where assign_to in($Alldealer) and remark_type in ($AllticketStatus) and cast(created_at as date) >= :datefrom1 and cast(created_at as date) <= :dateto1 and created_at <=DATE_ADD(now() , INTERVAL - :tat HOUR) and complaint_number!='' group by remark_type",["datefrom1"=>$datefrom1,"dateto1"=>$dateto1,'tat'=>$tat]);
		//dd($tableQuery);
		foreach ($tableQuery as $row) {
			echo $row->ticketStatus.','.$row->countStatus.'~';
		}
		
	}
	

	function get_this_quarter() {
	    $current_month = date('m');
	    $current_quarter_start = ceil($current_month/4)*3+1; // get the starting month of the current quarter
	    $start_date = date("Y-m-d", mktime(0, 0, 0, $current_quarter_start, 1, date('Y') ));
	    $end_date = date("Y-m-d", mktime(0, 0, 0, $current_quarter_start+3, 1, date('Y') ));
	    // by adding or subtracting from $current_quarter_start within the mktime function you can get any quarter of any year you want.
	    return array($start_date, $end_date);
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
				$actualLevlVal = $escLvlQuery[0]->levels;
				
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
}