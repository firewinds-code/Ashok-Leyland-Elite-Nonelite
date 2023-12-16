<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Session;
use Redirect;
use Mail;
use App\classes\ServerValidation;
use App\classes\AccessControl;
use Auth;

date_default_timezone_set('Asia/Kolkata');
class DealerController extends Controller
{
    public function __construct(){
		DB::enableQueryLog();
		$this->middleware(function ($request, $next) {
			$sesemail = Auth::user()->email;			
			if(empty($sesemail)) {
				return redirect('/');
			}
			return $next($request);
		});				
	}
	
	public function dealer(){
		try{
			$starttime = '07:00';  // your start time
			$endtime = '24:00';  // End time
			$duration = '30';  // split by 30 mins
			$data['time_slots'] = $this->prepare_time_slots($starttime, $endtime, $duration);
			// /dd($data['time_slots']);
			$data['productData']= DB::table('mstr_vehicle')->select( 'id','vehicle')->distinct('vehicle')->orderBy('vehicle')->get();
			$data['dealerData']= DB::table('mstr_dealer')->select( 'id','dealer_name')->get();

			$data['rowData']= DB::table('mstr_dealer as d')->select( 'd.id','d.plant_code','d.sac_code','d.dealer_name','d.address','d.state', 'd.city', 'd.zone','d.pincode','d.phone','d.latitude','d.longitude','d.mail','d.working_mon_fri','d.working_sat','d.working_sun','d.working_hours','d.sunday_working','d.dealer_type','r.region','d.SC_State_Name','d.SC_City_Name','d.bsvi','d.area_champion','d.region_champion','d.flag','s.state as stateName','c.city as cityName')->leftjoin('mstr_region as r','r.id','d.zone')->leftjoin('mstr_state as s','s.id','d.state')->leftjoin('mstr_city as c','c.id','d.city')->orderBy('id','desc')->get();
			
			$data['regionData']= DB::table('mstr_region')->select('id','region')->get();
			return view('dealer',$data);
		}catch (\Exception $ex) {
			$notification = array(
                'message' => $ex->getMessage(),
                'alert-type' => 'error'
            );
			return redirect()->route('dealer')->with($notification);
        }
			
	} 
	public function prepare_time_slots($starttime, $endtime, $duration){
	 
		$time_slots = array();
		$start_time    = strtotime($starttime); //change to strtotime
		$end_time      = strtotime($endtime); //change to strtotime
		$add_mins  = $duration * 60;
		while ($start_time < $end_time) // loop between time
		{
			$in = date("H:i", $start_time);
			$timestamp = $start_time + 60*60*9;
			$out = date('H:i', $timestamp);
		   $time_slots[] = $in.' to '.$out;
		   $start_time += $add_mins; // to check endtime
		}
	
		return $time_slots;
	}
	public function storeDealer(Request $request){
		try{
			$zoneArray=$stateArray=$cityArray=$brandArray=$productArray='';
			$serverValidation  = new ServerValidation();
		
			$DealerName = $request->input('DealerName');
			$phone = $request->input('phone');
			$address = $request->input('address');
			$zone = $request->input('zone');
			$state = $request->input('state'); 
			$city = $request->input('city');
			$pincode = $request->input('pincode');
			$latitude = $request->input('latitude');
			$longitude = $request->input('longitude');
			$dealer_type = $request->input('dealer_type');

			$plant_code = $request->input('plant_code');
			$sac_code = $request->input('sac_code');
			$mail = $request->input('mail');
			$working_mon_fri = $request->input('working_mon_fri');
			$working_sat = $request->input('working_sat');
			$working_sun = $request->input('working_sun');
			$working_hours = $request->input('working_hours');
			$sunday_working = $request->input('sunday_working');

			$SC_State_Name = $request->input('SC_State_Name');
			$SC_City_Name = $request->input('SC_City_Name');
			$bsvi = $request->input('bsvi');
			$area_champion = $request->input('area_champion');
			$region_champion = $request->input('region_champion');
			$flag = $request->input('flag');



			$DataID = $request->input('DataID');
			
			if ($DataID =='') {
				
				DB::table('mstr_dealer')->insert(['dealer_name'=>$DealerName, 'address'=>$address, 'zone'=>$zone, 'state'=>$state, 'city'=>$city, 'pincode'=>$pincode,'phone'=>$phone,'latitude'=>$latitude,'longitude'=>$longitude,'plant_code'=>$plant_code,'sac_code'=>$sac_code,'mail'=>$mail,'working_mon_fri'=>$working_mon_fri,'working_sat'=>$working_sat,'working_sun'=>$working_sun,'working_hours'=>$working_hours,'sunday_working'=>$sunday_working,'dealer_type'=>$dealer_type,'SC_State_Name'=>$SC_State_Name,'SC_City_Name'=>$SC_City_Name,'bsvi'=>$bsvi,'area_champion'=>$area_champion,'region_champion'=>$region_champion,'flag'=>$flag]);
				$lastInsertId = DB::getPdo()->lastInsertId();
				//dd($lastInsertId );
				$userDataQuery = DB::select("update users set dealer_id=concat(dealer_id,',',$lastInsertId) where user_type_id=1;");
				$notification = array(
					'message' => 'Stored successfully',
					'alert-type' => 'success'
				);
			}else { 
				$updated_at = date('Y-m-d H:i:s');
				DB::table('mstr_dealer')->where('id', $DataID)->update(['dealer_name'=>$DealerName, 'address'=>$address, 'zone'=>$zone, 'state'=>$state, 'city'=>$city, 'pincode'=>$pincode,'phone'=>$phone,'latitude'=>$latitude,'longitude'=>$longitude,'plant_code'=>$plant_code,'sac_code'=>$sac_code,'mail'=>$mail,'working_mon_fri'=>$working_mon_fri,'working_sat'=>$working_sat,'working_sun'=>$working_sun,'working_hours'=>$working_hours,'sunday_working'=>$sunday_working,'dealer_type'=>$dealer_type,'updated_at'=>$updated_at,'SC_State_Name'=>$SC_State_Name,'SC_City_Name'=>$SC_City_Name,'bsvi'=>$bsvi,'area_champion'=>$area_champion,'region_champion'=>$region_champion,'flag'=>$flag]);
				$notification = array(
					'message' => 'Updated successfully',
					'alert-type' => 'success'
				);
			}
			return redirect()->route('dealer')->with($notification);
		} catch (\Exception $ex) {
			$notification = array(
			'message' => $ex->getMessage(),
			'alert-type' => 'error'
			);
			return redirect()->route('dealer')->with($notification);
		}		
	}
	public function dealerDelete($id){
		try{
			$delData = DB::select("call delete_with_one('mstr_dealer','id','$id')");
			$notification = array(
			'message' => $delData[0]->Message,
			'alert-type' => $delData[0]->Action
			);
			return back()->with($notification);
		} catch (\Exception $ex) {
			$notification = array(
			'message' => $ex->getMessage(),
			'alert-type' => 'error'
			);
			return redirect()->route('dealer')->with($notification);
		}
	}
	public function getState()
	{
		try{
			$result=DB::select("call getstate()");
			foreach ($result as $value) {
				Echo $str= $value->state.',';
			}
		}catch (\Exception $ex) {
			$notification = array(
			'message' => $ex->getMessage(),
			'alert-type' => 'error'
			);
			return redirect()->route('dealer')->with($notification);
		}
	}
	public function getCity(Request $request)
	{
		try{
			$RID = $request->input('r_id');
			$stateID = $request->input('s_id');
			$result=DB::select("call getcity('".$RID."','".$stateID."')");
			foreach ($result as $value) {
				Echo $str= $value->city.',';
			}
		}catch (\Exception $ex) {
			$notification = array(
			'message' => $ex->getMessage(),
			'alert-type' => 'error'
			);
			return redirect()->route('dealer')->with($notification);
		}
	}
	public function getZone(Request $request)
	{
		try{
			$state = $request->input('state');
			$city = $request->input('city');
			$result=DB::select("call getzone('".$state."','".$city."')");
			foreach ($result as $value) {
				Echo $str= $value->zone.',';
			}
		}catch (\Exception $ex) {
			$notification = array(
			'message' => $ex->getMessage(),
			'alert-type' => 'error'
			);
			return redirect()->route('dealer')->with($notification);
		}
	}
	public function getDealerName(Request $request)
	{
		try {
			$state = $request->input('state');
			$city = $request->input('city');
			$zone = $request->input('zone');
			$result=DB::select("call getDealerName('".$state."','".$city."','".$zone."')");
			foreach ($result as $value) {
				Echo $str= $value->dealer_name.',';
			}
		}catch (\Exception $ex) {
			$notification = array(
			'message' => $ex->getMessage(),
			'alert-type' => 'error'
			);
			return redirect()->route('dealer')->with($notification);
		}
	}
	public function getDealerCode(Request $request)
	{
		try{
			$state = $request->input('state');
			$city = $request->input('city');
			$zone = $request->input('zone');
			$DealerName = $request->input('DN');
			$result=DB::select("call getDealerCode('".$state."','".$city."','".$zone."','".$DealerName."')");
			foreach ($result as $value) {
				Echo $str= $value->dealer_code;
			}
		}catch (\Exception $ex) {
			$notification = array(
			'message' => $ex->getMessage(),
			'alert-type' => 'error'
			);
			return redirect()->route('dealer')->with($notification);
		}
	}
	
	public function getMultiDealer(Request $request){
		$result=DB::select("select concat(id,'~',dealer_name)rowdata from mstr_dealer");
		foreach($result as $value){
			Echo $str= $value->rowdata.',';
		}
	}public function getBrand(Request $request){
		//$result=DB::select("call getBrand()");
		$result=DB::select("select concat(id,'~',brand)brand from mstr_brand");
		foreach($result as $value){
			Echo $str= $value->brand.',';
		}
	}
	public function getProduct(Request $request){
		//$result=DB::select("call getProduct()");
		$result=DB::select("select concat(id,'~',vehicle)vehicle from mstr_vehicle");
		foreach($result as $value){
			Echo $str= $value->vehicle.','; 
		}
	} 
	
	public function getMultiIdState(Request $request){
		$zone = $request->input('zone');
		$result=DB::select("select concat(s.id,'~',s.state) as state from mstr_state as s WHERE s.region_id IN ($zone) order by s.state ASC");
		foreach($result as $value){
			Echo $str= $value->state.',';
		}
	}
	public function getMultiIdRegOffice(Request $request){
		$zone = $request->input('zone');
		$result=DB::select("select concat(id,'~',reg_office )reg_office  from mstr_reg_office WHERE region_id =$zone");
		foreach($result as $value){
			Echo $str= $value->reg_office.',';
		}
	}
	
	/* public function getMultiIdCity(Request $request){
		$zone = $request->input('r_id');
		$State = $request->input('s_id');
		$result=DB::select("select loc.city_id,ct.city from mstr_location loc join mstr_city ct on ct.id = loc.city_id  and find_in_set(loc.region_id,'".$zone."') and find_in_set(loc.state_id,'".$State."') and loc.flag='1';");
		foreach($result as $value){
			Echo $str= $value->city_id.'~'.$value->city.',';
		}
	} */
	public function getMultiIdCity(Request $request){
		$zone = $request->input('r_id');
		$State = $request->input('s_id');
		$result=DB::select("select id,city from mstr_city where find_in_set(region_id,'".$zone."') and find_in_set(state_id,'".$State."') order by city ASC");
		foreach($result as $value){
			Echo $str= $value->id.'~'.$value->city.',';
		}
	}
	public function cityChangeGetDealer(Request $request){
		$zone = $request->input('r_id');
		$State = $request->input('s_id');
		$city = $request->input('c_id');
		
		$result=DB::select("select id,dealer_name,sac_code from mstr_dealer where find_in_set(zone,'".$zone."') and find_in_set(state,'".$State."') and find_in_set(city,'".$city."' ) order by dealer_name ASC");
		foreach($result as $value){
			Echo $str= $value->id.'~'.$value->dealer_name.'-'.$value->sac_code.',';
		}
	}
	public function getStateIdCity(Request $request){
		$zone = $request->input('r_id');
		$state = $request->input('s_id');
		$result=DB::select("select id,city from mstr_city where region_id = $zone and state_id=$state order by city ASC");
		foreach($result as $value){
			Echo $str= $value->id.'~'.$value->city.',';
		}
	}
	public function getMultipleStateIdCity(Request $request){
		$zone = $request->input('r_id');
		$state = $request->input('s_id');
		$result=DB::select("select id,city from mstr_city where region_id IN ($zone) and state_id IN ($state) order by city ASC");
		foreach($result as $value){
			Echo $str= $value->id.'~'.$value->city.',';
		}
	}
	public function getCityChangeDealer(Request $request){
		$zone = $request->input('zone');
		$region = $request->input('region');
		$city = $request->input('city');
		$dealer_id = Auth::user()->dealer_id;
		if(Auth::user()->role == '87' || Auth::user()->role == '29' || Auth::user()->role == '30'){
			$result=DB::select("select id,dealer_name from mstr_dealer where zone IN ($zone) and state IN ($region) and city IN ($city)  order by dealer_name ASC");
		}else{
			$result=DB::select("select id,dealer_name from mstr_dealer where zone IN ($zone) and state IN ($region) and city IN ($city) and id in ($dealer_id)  order by dealer_name ASC");
		}
		foreach($result as $value){
			//if($value->dealer_name !=''){
				Echo $str= $value->id.'~'.$value->dealer_name.',';
			//}
			
		}
	}
	public function getCityByRegion(Request $request){
		$zone = $request->input('r_id');
		$State = $request->input('s_id');		
		$result=DB::select("select loc.city_id,ct.city from mstr_location loc join mstr_city ct on ct.id = loc.city_id  and loc.region_id=$zone and loc.state_id=$State and loc.flag='1';");
		foreach($result as $value){
			Echo $str= $value->city_id.'~'.$value->city.',';
		}
	}
	public function getAreaOffice(Request $request){
		$zone = $request->input('r_id');
		$state = $request->input('s_id');		
		$result=DB::select("Select id,area from mstr_area_office where zone_id= $zone and reg_office_id=$state");
		foreach($result as $value){
			Echo $str= $value->id.'~'.$value->area.',';
		}
	}
	
	public function getMultiIdZone(Request $request){
		$zone = $request->input('r_id');
		$State = $request->input('s_id');
		$result=DB::select("select concat(id,'~',zone)zone from mstr_region WHERE `id` IN ($zone)");
		foreach($result as $value){
			Echo $str= $value->id.'~'.$value->city.',';
		}
	}
	public function getMultiZone(Request $request){		
		$result=DB::select("select concat(id,'~',region)region from mstr_region order by region ASC");
		foreach($result as $value){
			Echo $str= $value->region.',';
		}
	}
	public function getDealerByZoneId(Request $request){
		$zone_id = $request->input('zone_id');			
		$result=DB::select("select concat(id,'~',dealer_name)as fields from mstr_dealer where Find_in_set(zone,'".$zone_id."') ");
		foreach($result as $value){
			Echo $str= $value->fields.',';
		}
	}
	public function getDealerByZoneIdReport(Request $request){
		$zone_id = $request->input('zone_id');
		
		
		$result=DB::select("select concat(id,'~',dealer_name)as fields from mstr_dealer where (dealer_type='Primary Dealer' or dealer_type='Sales Office') and Find_in_set(zone,'".$zone_id."') ");
		foreach($result as $value){
			Echo $str= $value->fields.',';
		}
	}
	public function getCityZoneId(Request $request){
		$zone_id = $request->input('zone_id');	
		/*$result=DB::select("select concat(id,'~',city)as fields from mstr_city where region_id='".$zone_id."'");
		foreach($result as $value){
			Echo $str= $value->fields.',';
		}*/
		$result=DB::select("select loc.city_id,ct.city from mstr_location loc join mstr_city ct on ct.id = loc.city_id  and find_in_set(loc.region_id,'".$zone_id."')  and loc.flag='1';");
		foreach($result as $value){
			Echo $str= $value->city_id.'~'.$value->city.',';
		}
	}
	public function getCityByDealerId(Request $request)
	{
		$dealer_id = $request->input('dealer_id');	
		$cityQuery=DB::select("select city from mstr_dealer where id='".$dealer_id."'");
		$cityIdArray= explode(',',$cityQuery[0]->city);
		$str='';
		foreach ($cityIdArray as $cityId) {
			$city=DB::select("select concat(id,'~',city)as fields from mstr_city where id='".$cityId."'");
				
			echo $str= $city[0]->fields.',';
		}
		
	}
	public function bulkUpdate(Request $request){		
		try{
			
			
			if(Auth::user()->role == '29' || Auth::user()->role == '30' || Auth::user()->role == '87'){
				
				if($request->hasFile('import_file')){					
                    Excel::load($request->file('import_file')->getRealPath(), function ($reader) {						
                        foreach ($reader->toArray() as $key => $row) {							
                            $sac_code = trim($row['sac_code']);
                            $bsvi = trim($row['bsvi']);
                            $area_champion = trim($row['area_champion']);
                            $region_champion = trim($row['region_champion']);
							DB::select("SET SQL_SAFE_UPDATES=0");
                            DB::select("update mstr_dealer set bsvi='$bsvi',area_champion='$area_champion', region_champion='$region_champion' where sac_code='$sac_code'");							
                        }
                    });					
                } 
			}			
			$notification = array(
				'message' => 'BSVI, Area and Region Champion Updated successfully',
				'alert-type' => 'success'
			);
			return redirect()->route('dealer')->with($notification);
		}catch (\Exception $ex) {
			$notification = array(
			'message' => $ex->getMessage(),
			'alert-type' => 'error'
			);
			return redirect()->route('dealer')->with($notification);
		}
	}
	
}
