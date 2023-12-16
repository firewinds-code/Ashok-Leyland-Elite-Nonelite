<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Session;
use Redirect;
use Mail;
use App\classes\ServerValidation;

date_default_timezone_set('Asia/Kolkata');
class EscalationController extends Controller
{
    public function __construct(){
		DB::enableQueryLog();
		DB::select("SET sql_mode=''");
	}
	/* ----------------------complaint-type---------------------------------  */	
	public function escalation()
	{
		try{
			
				$data['roleData'] = DB::select("Select id, role from mstr_role order by role ASC");
				$data['rowData'] = DB::select("select e.id, e.level, e.level_name, e.to_role, e.cc_role, e.hours from mstr_escalations as e ");
				$rowData = DB::select("select e.id, e.level, e.level_name, e.to_role, e.cc_role, e.hours from mstr_escalations as e ");

				$ccRoleName=$toRoleName='';
				
  				$size = sizeof($data['rowData']);
  				for($i=0;$i<$size;$i++){  					
  					$ccRoleQuery =DB::select("select GROUP_CONCAT(role) as ccRole from mstr_role  where id  in (".$rowData[$i]->cc_role.")");  					
  					$ccRoleName .= $ccRoleQuery[0]->ccRole.'~';
  				}
				for($i=0;$i<$size;$i++){  					
					$toRoleQuery =DB::select("select GROUP_CONCAT(role) as ccRole from mstr_role  where id  in (".$rowData[$i]->to_role.")");  					
					$toRoleName .= $toRoleQuery[0]->ccRole.'~';
				}
				$data['toRoleName'] = rtrim($toRoleName,'~');
				$data['ccRoleName'] = rtrim($ccRoleName,'~');
				return view('escalation',$data);
			
				
		}catch (\Exception $ex) {
			$notification = array(
	                'message' => $ex->getMessage(),
	                'alert-type' => 'error'
	            );
				return redirect()->route('escalation')->with($notification);
        }
			
	}
	
	public function storeEscalation(Request $request){
		try{
			$serverValidation  = new ServerValidation();
			$level = $request->input('level');
			$level_name = $request->input('level_name');
			$to_role = $request->input('to_role');
			$cc_role = $request->input('cc_role');
			$hours = $request->input('hours');
			$to_roleArray = $cc_roleArray='';
			if($to_role != 'NA'){
				foreach($to_role as $row){$to_roleArray.= $row.',';}
			}
			$toRole = rtrim($to_roleArray,',');
			if($cc_role != 'NA'){
				foreach($cc_role as $row){$cc_roleArray.= $row.',';}
			}
			$ccRole = rtrim($cc_roleArray,',');
			$dataid = $request->input('DataID');
			if ($dataid =='') {
				DB::table('mstr_escalations')->insert(['level'=>$level,'level_name'=>$level_name,'to_role'=>$toRole,'cc_role'=>$ccRole,'hours'=>$hours]);
				$notification = array(
				'message' => 'Stored successfully',
				'alert-type' => 'success'
				);
			}else{
				$updated_at = date('Y-m-d H:i:s');
				DB::table('mstr_escalations')->where('id', $dataid)->update(['level'=>$level,'level_name'=>$level_name,'to_role'=>$toRole,'cc_role'=>$ccRole,'hours'=>$hours,'updated_at'=>$updated_at]);
				$notification = array(
					'message' => 'Updated successfully',
					'alert-type' => 'success'
				);
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

	
	public function escalationDelete($id){		
		try{
			
			$delData = DB::select("call delete_with_one('mstr_escalations','id','$id')");
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
	public function ajaxRole(){
		$result=DB::select("select concat(id,'~',role) as data from mstr_role order by role ASC");
		foreach($result as $value){
			Echo $str= $value->data.','; 
		}
	}
	
		
}
