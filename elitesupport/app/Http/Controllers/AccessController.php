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
class AccessController extends Controller
{
    public function __construct(){
		DB::enableQueryLog();
				
	}
/* ----------------------brand---------------------------------  */	
	public function access(){
		try{
				
				$data['rowData']= DB::table("mstr_access")->select('mstr_access.id as id','mstr_access.usertype_id as usertype_id', 'mstr_access.userrole as userrole', 'mstr_access.escalate_to as escalate_to', 'mstr_access.escalate_cc as escalate_cc','mstr_access.create_user as create_user', 'mstr_access.update_complaint as update_complaint', 'mstr_access.close_complaint as close_complaint', 'mstr_access.post_complaint_survey as post_complaint_survey', 'mstr_access.re_opening as re_opening', 'mstr_user_type.usertype as usertype','mstr_access.approval as approval','mstr_access.menu_new_case as menu_new_case','mstr_access.menu_update_case as menu_update_case','mstr_access.menu_report as menu_report','mstr_access.menu_dashboard as menu_dashboard','mstr_role.role as userRoleName')->join('mstr_user_type','mstr_user_type.id','=','mstr_access.usertype_id')->join('mstr_role','mstr_role.id','=','mstr_access.userrole')->orderBy('mstr_access.id','desc')->get();	
				$data['roleUserTypeData']=DB::table("mstr_user_type")->select('id','usertype')->distinct('id')->orderBy('usertype')->get();			
				return view('access',$data);
			
				
		}catch (\Exception $ex) {
			$notification = array(
	                'message' => $ex->getMessage(),
	                'alert-type' => 'error'
	            );
            return redirect()->route('access')->with($notification);
        }
			
	}
	
	public function storeAccess(Request $request){
		try{
			$serverValidation  = new ServerValidation();
			//dd($request->input());
			$usertype_id = $request->input('usertype_id');
			$userrole = $request->input('role');
			$re_opening = $request->input('re_opening');
			$escalate_to = $request->input('escalate_to');
			$escalate_cc = $request->input('escalate_cc');
			$create_user = $request->input('create_user');
			$update_complaint = $request->input('update_complaint');
			$close_complaint = $request->input('close_complaint');
			$post_complaint_survey = $request->input('post_complaint_survey');			
			$approval = $request->input('approval');
			$menu_new_case = $request->input('menu_new_case');
			$menu_update_case = $request->input('menu_update_case');
			$menu_report = $request->input('menu_report');
			$menu_dashboard = $request->input('menu_dashboard');
				
			$dataid = $request->input('dataid');	
			
			if ($usertype_id =='NA') {
					$notification = array(
			                'message' => 'Please enter user type',
			                'alert-type' => 'error'
		            	);
		            
			} else if ($userrole =='NA' ||$serverValidation->is_empty($userrole) ) {
					$notification = array(
					'message' => 'Please enter role',
					'alert-type' => 'error'
					);
					
			}else if ($escalate_to =='NA') {
					$notification = array(
					'message' => 'Please enter Escalation To',
					'alert-type' => 'error'
					);
			}else if ($escalate_cc =='NA') {
					$notification = array(
					'message' => 'Please enter Escalation CC',
					'alert-type' => 'error'
					);
					
			}else if ($menu_report =='NA') {
				$notification = array(
				'message' => 'Please enter report',
				'alert-type' => 'error'
				);
				
			} else if ($menu_dashboard =='NA') {
				$notification = array(
				'message' => 'Please enter dashboard',
				'alert-type' => 'error'
				);
					
			}else{
				if ($dataid =='') {
					$rowData= DB::table('mstr_access')->select('id')->where('usertype_id',$usertype_id)->where('userrole',$userrole)->count();
					if ($rowData == 0) {
						DB::table('mstr_access')->insert(['usertype_id'=>$usertype_id, 'userrole'=>$userrole, 're_opening'=>$re_opening, 'escalate_to'=>$escalate_to, 'escalate_cc'=>$escalate_cc, 'create_user'=>$create_user, 'update_complaint'=>$update_complaint, 'close_complaint'=>$close_complaint, 'approval'=>$approval, 'menu_new_case'=>$menu_new_case, 'menu_update_case'=>$menu_update_case, 'menu_report'=>$menu_report, 'menu_dashboard'=>$menu_dashboard, 'post_complaint_survey'=>$post_complaint_survey]);
						$notification = array(
						'message' => 'Stored successfully',
						'alert-type' => 'success'
						);
					}else{
						$notification = array(
						'message' => 'Duplicate Data',
						'alert-type' => 'error'
						);
					}
				}else{
					$updated_at = date('Y-m-d H:i:s');
					$rowData= DB::table('mstr_access')->select('id')->where('usertype_id',$usertype_id)->where('userrole',$userrole)->where('id','!=',$dataid)->count();
					if ($rowData == 0) {
					DB::table('mstr_access')->where('id', $dataid)->update(['usertype_id'=>$usertype_id, 'userrole'=>$userrole, 're_opening'=>$re_opening, 'escalate_to'=>$escalate_to, 'escalate_cc'=>$escalate_cc, 'create_user'=>$create_user, 'update_complaint'=>$update_complaint, 'close_complaint'=>$close_complaint, 'approval'=>$approval, 'menu_new_case'=>$menu_new_case, 'menu_update_case'=>$menu_update_case, 'menu_report'=>$menu_report, 'menu_dashboard'=>$menu_dashboard, 'post_complaint_survey'=>$post_complaint_survey,'updated_at'=>$updated_at]);
					$notification = array(
					'message' => 'Updated successfully',
					'alert-type' => 'success'
					);
					}else{
						$notification = array(
						'message' => 'Duplicate Data',
						'alert-type' => 'error'
						);
					}
				}
			}
			return redirect()->route('access')->with($notification);	
		}catch (\Exception $ex) {
			$notification = array(
	                'message' => $ex->getMessage(),
	                'alert-type' => 'error'
	            );
				return redirect()->route('access')->with($notification);
        }
	}
		
	
	public function accessDelete($id)
	{
		//DB::table('mstr_user_type')->where('id', $id)->delete();
		try{
			$delData = DB::select("call delete_with_one('mstr_access','id','$id')");
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
            return redirect()->route('access')->with($notification);
        }	
	}
	public function checkMSUFormat(){
		try {
			
			$data['rowData']= DB::select("select c.complaint_number,m.remarks as jsonRemarks, c.remarks as msuRemarks from creation_api_remarks as c join msu_api as m on c.complaint_number=m.complaint_number where cast(c.created_at as date)=cast(now() as date)");
			return view('checkmsu',$data);
		}catch (\Exception $ex){
			$notification = array(
                'message' => $ex->getMessage().' Line: '.$ex->getLine(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }

	}
/* ----------------------End brand---------------------------------  */	
}
