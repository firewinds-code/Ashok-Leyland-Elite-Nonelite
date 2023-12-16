<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Session;
use Redirect;
use Mail;
use App\classes\ServerValidation;

date_default_timezone_set('Asia/Kolkata');
class RegionController extends Controller
{
    public function __construct(){
		DB::enableQueryLog();
				
	}
/* ----------------------caller---------------------------------  */	
	public function regionView(){
		try{
			
                $data['rowData'] = DB::select("Select id,region from mstr_region");
				return view('zone',$data);
			
				
		}catch (\Exception $ex) {
			$notification = array(
	                'message' =>  "Something went Wrong",
	                'alert-type' => 'error'
	            );
            return back()->with($notification);
        }
			
	}
	
	public function storeZone(Request $request){
		try{
			$serverValidation  = new ServerValidation();
			//dd($request->input());
			$region = $request->input('region');
			
			$dataid = $request->input('DataID');
            $data['rowData'] = DB::select("Select id,region from mstr_region");            
			if ($dataid =='') {
					$checkRegion = DB::select("Select id,region from mstr_region where region='$region'");
					if(sizeof($checkRegion) > 0){
						$msg = array(
							'message' => 'Region Already Exist',
							'alert-type' => 'error'
						);
						return back()->with($msg);
					}
					DB::table('mstr_region')->insert(['region'=>$region]); 
					$notification = array(
						'message' => 'Stored successfully',
						'alert-type' => 'success'
					);
				
			}else{
				$updated_at = date('Y-m-d H:i:s');
				DB::table('mstr_region')->where('id', $dataid)->update(['region'=>$region,'updated_at'=>$updated_at]);
				$notification = array(
					'message' => 'Updated successfully',
					'alert-type' => 'success'
				);
			}
			return back()->with($notification);	
		}catch (\Exception $ex) {
			$notification = array(
	                'message' =>  "Something went Wrong",
	                'alert-type' => 'error'
	            );
				return back()->with($notification);
        }
	}
		
	
	public function zoneDelete($id){
		try{
			$delData = DB::select("call delete_with_one('mstr_region','id','$id')");
			$notification = array(
		        'message' => $delData[0]->Message,
		        'alert-type' => $delData[0]->Action
			);
			return back()->with($notification);
		}catch (\Exception $ex) {
			$notification = array(
	                'message' =>  "Something went Wrong",
	                'alert-type' => 'error'
	            );
            return back()->with($notification);
        }	
	}

    public function stateView(){
		try{
			
                $data['regionData'] = DB::select("Select id,region from mstr_region");
                $data['rowData'] = DB::select("Select s.id,s.region_id,s.state,r.region from mstr_state s left join mstr_region r on r.id = s.region_id");
				return view('state',$data);
			
				
		}catch (\Exception $ex) {
			$notification = array(
	                'message' => "Something went Wrong",
	                'alert-type' => 'error'
	            );
            return back()->with($notification);
        }
			
	}
    public function storeState(Request $request){
		try{
			$serverValidation  = new ServerValidation();
			//dd($request->input());
			$region_id = $request->input('region_id');
			$state = $request->input('state');
			
			$dataid = $request->input('DataID');
            $data['rowData'] = DB::select("Select id,region from mstr_region");
			if ($dataid =='') {
				$checkState = DB::select("Select region_id,state from mstr_state where region_id='$region_id' and state='$state'");
				if(sizeof($checkState) > 0){
					$msg = array(
						'message' => 'State Already Exist',
						'alert-type' => 'error'
					);
					return back()->with($msg);
				}

				DB::table('mstr_state')->insert(['region_id'=>$region_id,'state'=>$state]);
				$notification = array(
					'message' => 'Stored successfully',
					'alert-type' => 'success'
				);
				
			}else{
				$updated_at = date('Y-m-d H:i:s');
				DB::table('mstr_state')->where('id', $dataid)->update(['region_id'=>$region_id,'state'=>$state,'updated_at'=>$updated_at]);
				$notification = array(
					'message' => 'Updated successfully',
					'alert-type' => 'success'
				);
			}
			return back()->with($notification);	
		}catch (\Exception $ex) {
			$notification = array(
	                'message' =>  "Something went Wrong",
	                'alert-type' => 'error'
	            );
				return back()->with($notification);
        }
	}
    public function stateDelete($id){
		try{
			$delData = DB::select("call delete_with_one('mstr_state','id','$id')");
			$notification = array(
		        'message' => $delData[0]->Message,
		        'alert-type' => $delData[0]->Action
			);
			return back()->with($notification);
		}catch (\Exception $ex) {
			$notification = array(
	                'message' =>  "Something went Wrong",
	                'alert-type' => 'error'
	            );
            return back()->with($notification);
        }	
	}

    public function cityView(){
		try{
			
                $data['regionData'] = DB::select("Select id,region from mstr_region");
                $data['rowData'] = DB::select("Select c.id, c.region_id, c.state_id, c.city,r.region,s.state from mstr_city c left join mstr_region r on r.id = c.region_id left join mstr_state s on s.id = c.state_id");
				return view('city',$data);
			
				
		}catch (\Exception $ex) {
			$notification = array(
	                'message' =>  "Something went Wrong",
	                'alert-type' => 'error'
	            );
            return back()->with($notification);
        }
			
	}
	public function storeCity(Request $request){
		try{
			$serverValidation  = new ServerValidation();
			//dd($request->input());
			$region_id = $request->input('region_id');
			$state_id = $request->input('state_id');
			$city = $request->input('city');
			
			$dataid = $request->input('DataID');
            $data['rowData'] = DB::select("Select id,region from mstr_region");
			if ($dataid =='') {
				$checkCity = DB::select("Select region_id,state_id,city from mstr_city where region_id='$region_id' and state_id='$state_id' and city='$city'");
				if(sizeof($checkCity) > 0){
					$msg = array(
						'message' => 'City Already Exist',
						'alert-type' => 'error'
					);
					return back()->with($msg);
				}

					DB::table('mstr_city')->insert(['region_id'=>$region_id,'state_id'=>$state_id,'city'=>$city]);
					$notification = array(
						'message' => 'Stored successfully',
						'alert-type' => 'success'
					);
				
			}else{
				$updated_at = date('Y-m-d H:i:s');
				DB::table('mstr_city')->where('id', $dataid)->update(['region_id'=>$region_id,'state_id'=>$state_id,'city'=>$city,'updated_at'=>$updated_at]);
				$notification = array(
					'message' => 'Updated successfully',
					'alert-type' => 'success'
				);
			}
			return back()->with($notification);	
		}catch (\Exception $ex) {
			$notification = array(
	                'message' =>  "Something went Wrong",
	                'alert-type' => 'error'
	            );
				return back()->with($notification);
        }
	}
    public function cityDelete($id){
		try{
			$delData = DB::select("call delete_with_one('mstr_city','id','$id')");
			$notification = array(
		        'message' => $delData[0]->Message,
		        'alert-type' => $delData[0]->Action
			);
			return back()->with($notification);
		}catch (\Exception $ex) {
			$notification = array(
	                'message' => "Something went Wrong",
	                'alert-type' => 'error'
	            );
            return back()->with($notification);
        }	
	}

    public function getZoneChange(Request $request){
        $zoneId= $request->input('zoneId');
        $sql = DB::select("Select id,region_id,state from mstr_state where region_id =$zoneId");
        foreach ($sql as $row) {
            echo $row->id.'~~'.$row->state.',';
        }
         
    }
    public function getMultipleZoneChange(Request $request){
        $zoneId= $request->input('zoneId');
        
		$result=DB::select("select concat(id,'~',state)state from mstr_state WHERE `region_id` IN ($zoneId) order by state ASC");
		foreach($result as $value){
			Echo $str= $value->state.',';
		}
    }
    public function getCallerStateChange(Request $request){
        $stateId= $request->input('stateId');
        $sql = DB::select("Select id,city from mstr_caller_city where state_id =:stateId order by city ASC",["stateId"=>$stateId]);	
        foreach ($sql as $row) {
            echo $row->id.'~~'.$row->city.',';
        }
    }
	public function getAssignDealerStateChange(Request $request){
        $stateId= $request->input('stateId');
        $sqlState = DB::select("Select id,state from mstr_caller_state where id =:stateId order by state ASC",["stateId"=>$stateId]);
		$stateName = strtolower($sqlState[0]->state);
		
		$dealerStateName = DB::select("SELECT id, concat(dealer_name, ' - ', IFNULL(SC_City_Name,'')) as dealer_name FROM mstr_dealer where SC_State_Name like '%$stateName%' and flag=1 order by dealer_name ASC");
       // $sql = DB::select("Select id,city from mstr_caller_city where state_id =$stateId");
        foreach ($dealerStateName as $row) {
            echo $row->id.'~~'.$row->dealer_name.',';
        }
    }
		
/* ----------------------End caller---------------------------------  */
}
