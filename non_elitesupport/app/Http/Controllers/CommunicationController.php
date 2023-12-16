<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Session;
use Redirect;
use Mail;
use App\classes\ServerValidation;
use Auth;
date_default_timezone_set('Asia/Kolkata');
class CommunicationController extends Controller
{
    public function __construct(){
		DB::enableQueryLog();
				
	}
	/* ----------------------complaint-type---------------------------------  */	
public function communication()
{
		try{
			
				$data['complaint_details']= DB::table('mstr_complaint')->select('id','complaint_type')->get();				
				$data['region_details']= DB::table('mstr_region')->select('id','region')->get();							
				$data['vehicle_details']= DB::table('mstr_vehicle')->select('id','vehicle')->get();
				$data['city_details']= DB::table('mstr_city')->select('id','city')->orderby('city')->get();					
				$data['role_details']= DB::table('mstr_role')->select('id','role')->where('flag','1')->get();	
									
				
				$data['rowData']= DB::table('mstr_communication as E')->select('E.id as communication_id','E.case_status','E.communication_stage','E.day','E.sub_complaint_type','E.email_msg','E.sms_msg','U1.id as Escalated_To_ID','U2.id as Cc_To_ID','U1.role as Escalated_To','U2.role as Cc_To','E.segment as segment')->leftjoin('mstr_role as U1','E.escalated_to','=','U1.id')->leftjoin('mstr_role as U2','E.cc_to','=','U2.id')->orderBy('E.id','desc')->get();	
				return view('communication',$data);
			
				
		}catch (\Exception $ex) {
			$notification = array(
	                'message' => $ex->getMessage(),
	                'alert-type' => 'error'
	            );
				return redirect()->route('communication')->with($notification);
        }
			
	}
	
	public function storeCommunication(Request $request){
		try{
			$serverValidation  = new ServerValidation();
			$complaint_type = $request->input('complaint_type');
			$sub_complaint_type = $request->input('sub_complaint_type');
			$vehicle = $request->input('vehicle');
			$segment = $request->input('segment');
			$region = $request->input('region');
			$location = $request->input('City');
			$communication_stage = $request->input('communication_stage');
			$day = $request->input('day');
			$case_status = $request->input('case_status');
			$escalated_to = $request->input('escalated_to');
			$cc_to = $request->input('cc_to');
			$email_msg = $request->input('email_msg');
			$sms_msg = $request->input('sms_msg');			
			$dataid = $request->input('DataID');
			$escalated_toArray='';			
			$cc_toArray='';			
			if($escalated_to != 'NA'){
				foreach($escalated_to as $rowescalated_to){
					$escalated_toArray.= $rowescalated_to.',';
				}
			}
			if($cc_to != 'NA'){
				foreach($cc_to as $rowcc_to){
					$cc_toArray.= $rowcc_to.',';
				}
			}
		
			if ($dataid =='') {
				$escalated_to= rtrim($escalated_toArray,',');
				$cc_to= rtrim($cc_toArray,',');
			$rowData= DB::table('mstr_communication')->select('id')->where('escalated_to',$escalated_to)->where('cc_to',$cc_to)->where('case_status',$case_status)->count();
					if ($rowData == 0) {
						DB::table('mstr_communication')->insert(['escalated_to'=>$escalated_to,'cc_to'=>$cc_to,'case_status'=>$case_status]);
						
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
					$escalated_to= rtrim($escalated_toArray,',');
					$cc_to= rtrim($cc_toArray,',');
					$rowData= DB::table('mstr_communication')->select('id')->where('escalated_to',$escalated_to)->where('cc_to',$cc_to)->where('case_status',$case_status)->count();
					$updated_at = date('Y-m-d H:i:s');
					if ($rowData == 0) {						
						DB::table('mstr_communication')->where('id', $dataid)->update(['escalated_to'=>$escalated_to,'cc_to'=>$cc_to,'case_status'=>$case_status,'updated_at'=>$updated_at]);
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
				
			return redirect()->route('communication')->with($notification);	
				
		}catch (\Exception $ex) {
			$notification = array(
	                'message' => $ex->getMessage(),
	                'alert-type' => 'error'
	            );
				return redirect()->route('communication')->with($notification);
        }
	}

	
	public function communicationDelete($id){		
		try{
			$delData = DB::select("call delete_with_one('mstr_communication','id','$id')");
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
				return redirect()->route('communication')->with($notification);
        }	
	}
	/* ----------------------End complaint-type---------------------------------  */	
}
