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
class AreaController extends Controller
{
    public function __construct(){
		DB::enableQueryLog();
				
	}
/* ----------------------brand---------------------------------  */	
	public function area(){
		try{
			
				$data['rowData']= DB::select('SELECT M.id as Location_ID, flag,site_code,site_name,R.id as Region_id,region,S.id as State_id,state,C.id as City_ID,City FROM mstr_location M join mstr_region R on M.region_id =R.id join mstr_state S on M.state_id =S.id join mstr_city C on M.city_id =C.id order by M.id desc');
				$data['regionData']= DB::table('mstr_region')->select('id','region')->get();
				return view('area',$data);
			
				
		}catch (\Exception $ex) {
			$notification = array(
	                'message' => $ex->getMessage(),
	                'alert-type' => 'error'
	            );
            return back()->with($notification);
        }
			
	}
	
	public function storeArea(Request $request){
		try{
			$serverValidation  = new ServerValidation();
			
			$site_name='';
			$state = $request->input('state');
			$cityInput = $request->input('City');
			$CityAjax = $request->input('CityAjax');
			$zone = $request->input('zone');
			$flag = $request->input('flag');
			$dataid = $request->input('dataid');
			$site_code='';
			$city = '';
			if ($CityAjax =='NA' && $cityInput !='') {
				DB::table('mstr_city')->insert(['region_id'=>$zone,'state_id'=>$state,'city'=>$cityInput]);
				$city = DB::getPdo()->lastInsertId();
			}else{
				$city =$CityAjax;
			}
			
			if( $state=='NA' || $zone== 'NA' ){
					$notification = array(
			                'message' => 'Please enter related fields',
			                'alert-type' => 'error'
		            	);
		            return back()->with($notification);
			}else{
				
				if($dataid == ''){
					$rowData= DB::select('call insertStateCityZone("'.$site_code.'","'.$site_name.'","'.$state.'","'.$city.'","'.$zone.'")');	
					
						$notification = array(
			                'message' => $rowData[0]->Message,
			                'alert-type' => $rowData[0]->Action
			            );
			        return redirect()->route('area')->with($notification);
				}else{					
					$rowData= DB::select('call updateStateCityZone("'.$dataid.'","'.$site_code.'","'.$site_name.'","'.$state.'","'.$city.'","'.$zone.'","'.$flag.'")');						
					$notification = array(
		                'message' => $rowData[0]->Message,
		                'alert-type' => $rowData[0]->Action
		            );
			        return redirect()->route('area')->with($notification);
				}
				
		        	
			}	
		}catch (\Exception $ex) {
			$notification = array(
	                'message' => $ex->getMessage(),
	                'alert-type' => 'error'
	            );
             return redirect()->route('area')->with($notification);
        }
	}
	
	
	public function areaDelete($id){
		
		//DB::table('mstr_user_type')->where('id', $id)->delete();
		try{
			$delData = DB::select("call delete_with_one('mstr_location','id','$id')");
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
	public function searchCity(Request $request)
	{
		try {
			$zoneId  = $request->input('zoneId');
			$stateId  = $request->input('stateId');
			$str  = $request->input('str');
			$sql = DB::select("SELECT id,city from mstr_city where region_id='$zoneId' and state_id='$stateId' and city like '%$str%'");
			if (sizeof($sql)>0) {
				foreach ($sql as $row) {
					echo "<option value=$row->id>$row->city</option>";

				}
			} else {
				echo "<option value='NA'>No City Found</option>";
			}

		} catch (\Exception $ex) {
			$notification = array(
			'message' => $ex->getMessage(),
			'alert-type' => 'error'
			);
			return back()->with($notification);
		}
	}
/* -------------------------------------------------------  */	
}
