<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Session;
use Redirect;
use Mail;
use App\classes\ServerValidation;

date_default_timezone_set('Asia/Kolkata');
class DealerEscalationController extends Controller
{
    public function __construct(){
		DB::enableQueryLog();
		DB::select("SET sql_mode=''");
	}
	/* ----------------------complaint-type---------------------------------  */	
public function dealerEscalation()
{
		try{
			
				$data['complaint_details']= DB::table('mstr_complaint')->select('id','complaint_type')->get();				
				$data['region_details']= DB::table('mstr_region')->select('id','region')->get();							
				$data['vehicle_details']= DB::table('mstr_vehicle')->select('id','vehicle')->get();
				$data['city_details']= DB::table('mstr_city')->select('id','city')->orderby('city')->get();					
				//$data['role_details']= DB::table('mstr_role')->select('id','role')->where('flag','1')->get();	
				$data['to_role_details']= DB::select("select r.id, r.role from mstr_role r  left join mstr_access acc on r.id = acc.userrole and acc.escalate_to !='No' where flag = 1 order by r.role asc");	
				$data['cc_role_details']= DB::select("select r.id, r.role from mstr_role r  left join mstr_access acc on r.id = acc.userrole and acc.escalate_cc !='No' where flag = 1 order by r.role asc");	
									
				
			$data['rowData']= DB::select("select M.id as 'Escalation_ID',M.escalation_stage as 'escalation_stage',M.matrix_identifier as 'matrix_identifier',M.day as 'day',V.id as 'vehicle_ID',V.vehicle as 'vehicle', M.segment as 'Segment_ID',R.id as 'escalated_to_ID',R.role as 'escalated_to',M.cc_to as 'cc_to_ID',C.id as 'complaint_type_ID',C.complaint_type as 'complaint_type',M.sub_complaint_type as 'sub_complaint_type_ID' from mstr_dealer_escalations M join mstr_vehicle V on M.vehicle =V.id and V.flag ='1' join mstr_role R on M.escalated_to =R.id and R.flag ='1'  join mstr_complaint C on M.complaint_type =C.id group by M.matrix_identifier");			
				return view('dealer_escalation',$data);
			
				
		}catch (\Exception $ex) {
			$notification = array(
	                'message' => $ex->getMessage(),
	                'alert-type' => 'error'
	            );
				return back()->with($notification);
        }
			
	}
	
	public function storeDealerEscalation(Request $request){
		try{
			$serverValidation  = new ServerValidation();
			$complaint_type = $request->input('complaint_type');
			$matrix_identifier = $request->input('matrix_identifier');
			$sub_complaint_type = $request->input('sub_complaint_type');
			$vehicle = $request->input('vehicle');
			$segment = $request->input('segment');
			$region = $request->input('region');
			$location = $request->input('City');
			$escalation_stage = $request->input('escalation_stage');
			$day = $request->input('day');
			$escalated_to = $request->input('escalated_to');
			$cc_to = $request->input('cc_to');
			$ccToArray=$sub_complaint_typeArray=$segmentArray='';
			if($cc_to != 'NA'){
				foreach($cc_to as $rowCCto){$ccToArray.= $rowCCto.',';}
			}
			if($sub_complaint_type != 'NA'){
				foreach($sub_complaint_type as $row_sub_complaint_type){$sub_complaint_typeArray.= $row_sub_complaint_type.',';}
			}
			if($segment != 'NA'){
				foreach($segment as $row_segment){$segmentArray.= $row_segment.',';}
			}
			$cc_to = rtrim($ccToArray,',');
			$sub_complaint_type = rtrim($sub_complaint_typeArray,',');
			$segment = rtrim($segmentArray,',');
			$dataid = $request->input('DataID');			
			
			if ($dataid =='') {
				
			$rowData= DB::table('mstr_dealer_escalations')->select('id')->where('matrix_identifier',$matrix_identifier )->where('complaint_type',$complaint_type )->where('sub_complaint_type',$sub_complaint_type )->where('vehicle',$vehicle)->where('segment',$segment)->where('escalation_stage',$escalation_stage  )->count();
					if ($rowData == 0) {
						DB::table('mstr_dealer_escalations')->insert(['matrix_identifier'=>$matrix_identifier,'complaint_type'=>$complaint_type,'sub_complaint_type'=>$sub_complaint_type,'vehicle'=>$vehicle,'segment'=>$segment,'escalation_stage'=>$escalation_stage,'region'=>$region,'location'=>$location,'day'=>$day,'escalated_to'=>$escalated_to,'cc_to'=>$cc_to]);
						
						$notification = array(
						'message' => 'Stored successfully',
						'alert-type' => 'success'
						);
					} 
					else {
						$notification = array(
						'message' => 'Duplicate Data',
						'alert-type' => 'error'
						);

					}
			}else{
					
					$rowData= DB::table('mstr_dealer_escalations')->select('id')->where('matrix_identifier',$matrix_identifier )->where('complaint_type',$complaint_type )->where('sub_complaint_type',$sub_complaint_type )->where('vehicle',$vehicle)->where('segment',$segment)->where('day',$day )->where('escalation_stage',$escalation_stage  )->where('escalated_to',$escalated_to)->where('cc_to',$cc_to)->count();
					$updated_at = date('Y-m-d H:i:s');
					if ($rowData == 0) {						
						DB::table('mstr_dealer_escalations')->where('id', $dataid)->update(['matrix_identifier'=>$matrix_identifier,'complaint_type'=>$complaint_type,'sub_complaint_type'=>$sub_complaint_type,'vehicle'=>$vehicle,'segment'=>$segment,'escalation_stage'=>$escalation_stage,'region'=>$region,'location'=>$location,'day'=>$day,'escalated_to'=>$escalated_to,'cc_to'=>$cc_to,'updated_at'=>$updated_at]);
						//DB::enableQueryLog();
						//$query = DB::getQueryLog();
						//dd($query);
						$notification = array(
							'message' => 'Updated successfully',
							'alert-type' => 'success'
						);

					} else {
						$notification = array(
							'message' => 'Duplicate Data',
							'alert-type' => 'error'
						);
					}
			}
				
			return back()->with($notification);	
				
		}catch (\Exception $ex) {
			$notification = array(
	                'message' => $ex->getMessage(),
	                'alert-type' => 'error'
	            );
				return back()->with($notification);
        }
	}

	
	public function dealerEscalationDelete($id){
		try{			
			$delData = DB::select("call delete_with_one('mstr_dealer_escalations','id','$id')");
			$notification = array(
		        'message' => $delData[0]->Message,
		        'alert-type' => $delData[0]->Action
			);
		            return back()->with($notification);
		}catch (\Exception $ex) {
			$notification = array(
                'message' => $ex->getMessage(),
                'alert-type' => 'error'
            );
				return back()->with($notification);
        }	
	}
	public function dealerEscalationIndividual($ids){		
		try{
		 	$fieldsArr = explode('~',$ids);
		 	
            $data['matrix_identifier'] = $fieldsArr[0];
            $data['complaint_type'] = $fieldsArr[1];
            $complaint_type = $fieldsArr[1];
            $data['sub_complaint_type'] = $fieldsArr[2];
            $sub_complaint_type = $fieldsArr[2];
            $data['vehicle'] = $fieldsArr[3];
            $data['segment'] = $fieldsArr[4];
            $segment = $fieldsArr[4];
            $data['complaint_details_value']= DB::select("select id, complaint_type from mstr_complaint where id in ($complaint_type)");
            $data['sub_complaint_details_value']= DB::select("select id, sub_complaint_type from mstr_sub_complaint where id in ($sub_complaint_type)");
            $data['segment_value']= DB::select("select id, segment from product_segment where id in ($segment)");
			$data['complaint_details']= DB::table('mstr_complaint')->select('id','complaint_type')->get();				
			$data['region_details']= DB::table('mstr_region')->select('id','region')->get();							
			$data['vehicle_details']= DB::table('mstr_vehicle')->select('id','vehicle')->get();
			$data['city_details']= DB::table('mstr_city')->select('id','city')->orderby('city')->get();					
			//$data['role_details']= DB::table('mstr_role')->select('id','role')->where('flag','1')->get();	
			$data['to_role_details']= DB::select("select r.id, r.role from mstr_role r  left join mstr_access acc on r.id = acc.userrole and acc.escalate_to !='No' where flag = 1 order by r.role asc");	
			$data['cc_role_details']= DB::select("select r.id, r.role from mstr_role r  left join mstr_access acc on r.id = acc.userrole and acc.escalate_cc !='No' where flag = 1 order by r.role asc");	
			$rowCount = DB::table('mstr_dealer_escalations as E')->select('E.id as Escalation_id','E.escalation_stage','E.day','E.sub_complaint_type','SC.sub_complaint_type as sub_complaint_type_name','C.id as complaint_ID','C.complaint_type','V.id as vehicle_ID','V.vehicle','R.id as Region_ID','R.Region','L.id as City_ID','L.city','U1.id as Escalated_To_ID','U1.role as Escalated_To','E.segment as segment','E.cc_to as Cc_To_ID','sg.segment as segmentName')->leftjoin('mstr_complaint as C','E.complaint_type','=','C.id')->leftjoin('mstr_vehicle as V','E.vehicle','=','V.id')->leftjoin('mstr_region as R','E.region','=','R.id')->leftjoin('mstr_city as L','E.location','=','L.id')->leftjoin('mstr_role as U1','E.escalated_to','=','U1.id')->leftjoin('mstr_sub_complaint as SC','E.sub_complaint_type','=','SC.id')->leftjoin('product_segment as sg','sg.id','=','E.segment')->where('E.complaint_type',$fieldsArr[1])->where('E.sub_complaint_type',$fieldsArr[2])->where('E.vehicle',$fieldsArr[3])->where('E.segment',$fieldsArr[4])->orderBy('E.id','desc')->count();
			
			$rowCCData = DB::table('mstr_dealer_escalations as E')->select('E.id as Escalation_id','E.escalation_stage','E.day','E.sub_complaint_type','SC.sub_complaint_type as sub_complaint_type_name','C.id as complaint_ID','C.complaint_type','V.id as vehicle_ID','V.vehicle','R.id as Region_ID','R.Region','L.id as City_ID','L.city','U1.id as Escalated_To_ID','U1.role as Escalated_To','E.segment as segment','E.cc_to as Cc_To_ID','sg.segment as segmentName')->leftjoin('mstr_complaint as C','E.complaint_type','=','C.id')->leftjoin('mstr_vehicle as V','E.vehicle','=','V.id')->leftjoin('mstr_region as R','E.region','=','R.id')->leftjoin('mstr_city as L','E.location','=','L.id')->leftjoin('mstr_role as U1','E.escalated_to','=','U1.id')->leftjoin('mstr_sub_complaint as SC','E.sub_complaint_type','=','SC.id')->leftjoin('product_segment as sg','sg.id','=','E.segment')->where('E.complaint_type',$fieldsArr[1])->where('E.sub_complaint_type',$fieldsArr[2])->where('E.vehicle',$fieldsArr[3])->where('E.segment',$fieldsArr[4])->orderBy('E.id','desc')->get();
		$ccRoleName=$multiSegmentName='';
  				
  				for($i=0;$i<$rowCount;$i++){  					
  					$ccRoleQuery =DB::select("select GROUP_CONCAT(role) as ccRole from mstr_role  where id  in (".$rowCCData[$i]->Cc_To_ID.")");  					
  					$ccRoleName .= $ccRoleQuery[0]->ccRole.'~';
  				}
  				for($i=0;$i<$rowCount;$i++){  					
  					$segmentQuery =DB::select("select GROUP_CONCAT(segment) as multiSegment from product_segment  where id  in (".$rowCCData[$i]->segment.")");  					
  					$multiSegmentName .= $segmentQuery[0]->multiSegment.'~';
  				}
		$data['multiSegmentName'] = rtrim($multiSegmentName,'~');
		$data['ccRoleName'] = rtrim($ccRoleName,'~');
			$data['escalateData'] = DB::table('mstr_dealer_escalations as E')->select('E.id as Escalation_id','E.escalation_stage','E.day','E.sub_complaint_type','SC.sub_complaint_type as sub_complaint_type_name','C.id as complaint_ID','C.complaint_type','V.id as vehicle_ID','V.vehicle','R.id as Region_ID','R.Region','L.id as City_ID','L.city','U1.id as Escalated_To_ID','U1.role as Escalated_To','E.segment as segment','E.cc_to as Cc_To_ID','sg.segment as segmentName')->leftjoin('mstr_complaint as C','E.complaint_type','=','C.id')->leftjoin('mstr_vehicle as V','E.vehicle','=','V.id')->leftjoin('mstr_region as R','E.region','=','R.id')->leftjoin('mstr_city as L','E.location','=','L.id')->leftjoin('mstr_role as U1','E.escalated_to','=','U1.id')->leftjoin('mstr_sub_complaint as SC','E.sub_complaint_type','=','SC.id')->leftjoin('product_segment as sg','sg.id','=','E.segment')->where('E.complaint_type',$fieldsArr[1])->where('E.sub_complaint_type',$fieldsArr[2])->where('E.vehicle',$fieldsArr[3])->where('E.segment',$fieldsArr[4])->orderBy('E.id','desc')->get();			
			return view('dealer_escalation_individual',$data);
		    
		}catch (\Exception $ex) {
			$notification = array(
                'message' => $ex->getMessage(),
                'alert-type' => 'error'
            );
				return back()->with($notification);
        }	
	}
	/* ----------------------End complaint-type---------------------------------  */	
}
