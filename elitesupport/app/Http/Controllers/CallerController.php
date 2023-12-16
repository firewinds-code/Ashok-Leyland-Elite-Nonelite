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
class CallerController extends Controller
{
    public function __construct(){
		DB::enableQueryLog();
				
	}
/* ----------------------caller---------------------------------  */	
	public function getCallerView(){
		try{
			
                $data['region'] = DB::select("Select id,region from mstr_region");
				$data['vehicleData']= DB::table("mstr_vehicle")->select('id','reg_number')->get();
				$data['ownerData']= DB::table("mstr_owner")->select('id','owner_name')->get();
				$data['rowData'] = DB::select("Select c.id as id, c.vehicle_id, c.owner_id, c.caller_type, c.caller_name, c.caller_contact, c.caller_location, c.caller_landmark, c.zone, c.state, c.city,o.owner_name from mstr_caller c left join mstr_owner o on o.id = c.owner_id");
				return view('caller',$data);
			
				
		}catch (\Exception $ex) {
			$notification = array(
	                'message' => $ex->getMessage(),
	                'alert-type' => 'error'
	            );
            return back()->with($notification);
        }
			
	}
	
	public function storeCaller(Request $request){
		try{
			$serverValidation  = new ServerValidation();
			//dd($request->input());
			/* $vehicle_id = $request->input('vehicle_id'); */
			$owner_id = $request->input('owner_id');
			$caller_type = $request->input('caller_type');
			$caller_name = $request->input('caller_name');
			$caller_contact = $request->input('caller_contact');
			/* $caller_location = $request->input('caller_location');
			$caller_landmark = $request->input('caller_landmark');
			$vehicle_type = $request->input('vehicle_type');
			$vehicle_movable = $request->input('vehicle_movable');
			$zone = $request->input('zone');
			$state = $request->input('state');
			$city = $request->input('city'); */
			$dataid = $request->input('dataid');	
			$data['callerData']= DB::table("mstr_vehicle")->select('id','reg_number')->get();
			if ($dataid =='') {
					DB::table('mstr_caller')->insert(['owner_id'=>$owner_id, 'caller_type'=>$caller_type, 'caller_name'=>$caller_name, 'caller_contact'=>$caller_contact]);
					$notification = array(
						'message' => 'Stored successfully',
						'alert-type' => 'success'
					);
				
			}else{
				$updated_at = date('Y-m-d H:i:s');
				DB::table('mstr_caller')->where('id', $dataid)->update(['owner_id'=>$owner_id, 'caller_type'=>$caller_type, 'caller_name'=>$caller_name, 'caller_contact'=>$caller_contact,'updated_at'=>$updated_at]);
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
		
	
	public function callerDelete($id){
		try{
			$delData = DB::select("call delete_with_one('mstr_caller','id','$id')");
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
/* ----------------------End caller---------------------------------  */	


}
