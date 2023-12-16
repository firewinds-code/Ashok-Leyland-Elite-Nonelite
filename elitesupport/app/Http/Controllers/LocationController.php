<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Session;
use Redirect;
use Mail;
use File;
use ZipArchive;
use Illuminate\Support\Facades\Hash;
use App\classes\ServerValidation;
use Auth;
use DateTime;
use App\Models\CTITICKET;
use App\Exports\CtiExport;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
date_default_timezone_set('Asia/Kolkata');

class LocationController extends Controller
{
     public function __construct(){
		DB::enableQueryLog(); 
	}
	public function ticketCreation(){ 
		
		$data['remark_type'] = DB::select("Select id, type from remark_type order by type ASC"); 
		$data['caller_state'] = DB::select("Select id, state from mstr_caller_state order by state ASC"); 
		$data['ownerData'] = DB::select("Select id, owner_name from mstr_owner order by owner_name ASC"); 
		$data['responseDelayReason'] = DB::select("Select id, reason from response_delay_reason order by reason ASC"); 
		$data['ownerContactData'] = DB::select("Select id, contact_name from mstr_owner_contact where contact_name!='' order by contact_name ASC"); 
		$data['region'] = DB::select("Select id,region from mstr_region"); 
		return view('search_location',$data); 
	}
	
	public function getVehicleDetails(Request $request){
		try{
			$reg_number = $request->input('reg_number')!=''?$request->input('reg_number'):'000000NA';
			$chassis_number = $request->input('chassis_number')!=''?$request->input('chassis_number'):'000000NA';
			$engine_number = $request->input('engine_number')!=''?$request->input('engine_number'):'000000NA';			
			/* echo "Select v.id as vehicleId, v.vehicle_model, v.reg_number, v.chassis_number, v.engine_number, v.vehicle_segment, v.purchase_date, v.add_blue_use, v.vehicle_type, v.is_vehicle_movable,v.engine_emmission_type, o.id as ownerId,o.owner_name as owner_name, o.owner_mob, o.owner_landline, o.owner_cat, o.owner_company,o.alse_mail,o.asm_mail,group_concat(oc.id separator ' !! ' ) as ownercontactid,oc.mob as ownercontactmob,oc.owner_contact_email,group_concat(oc.contact_name separator ' !! ' ) as contact_name from mstr_vehicle v left join mstr_owner o on v.ownerId = o.id and o.flag=1 left join mstr_owner_contact oc on o.id=oc.owner_id and oc.flag=1 
			where (v.reg_number='$reg_number' or v.chassis_number = '$chassis_number' or v.engine_number='$chassis_number') and v.flag=1";die; */
			DB::select("set sql_mode =''");
			$query = DB::select("Select v.id as vehicleId, v.vehicle_model, v.reg_number, v.chassis_number, v.engine_number, v.vehicle_segment, v.purchase_date, v.add_blue_use, v.vehicle_type, v.is_vehicle_movable,v.engine_emmission_type, o.id as ownerId,o.owner_name as owner_name, o.owner_mob, o.owner_landline, o.owner_cat, o.owner_company,o.alse_mail,o.asm_mail,group_concat(oc.id separator ' !! ' ) as ownercontactid,oc.mob as ownercontactmob,oc.owner_contact_email,group_concat(oc.contact_name separator ' !! ' ) as contact_name from mstr_vehicle v left join mstr_owner o on v.ownerId = o.id and o.flag=1 left join mstr_owner_contact oc on o.id=oc.owner_id and oc.flag=1 
			where (v.reg_number=:reg_number or v.chassis_number = :chassis_number or v.engine_number=:engine_number) and v.flag=1",["reg_number"=>$reg_number,"chassis_number"=>$chassis_number,"engine_number"=>$engine_number]);
			
			/* if(sizeof($query) > 0){	 */
			if(!empty($query[0]->reg_number)){	
					
				echo $query[0]->vehicleId.'~~'.$query[0]->vehicle_model.'~~'.$query[0]->reg_number.'~~'.$query[0]->chassis_number.'~~'.$query[0]->engine_number.'~~'.$query[0]->vehicle_segment.'~~'.$query[0]->purchase_date.'~~'.$query[0]->add_blue_use.'~~'.$query[0]->vehicle_type.'~~'.$query[0]->engine_emmission_type.'~~'.$query[0]->ownerId.'~~'.$query[0]->owner_name.'~~'.$query[0]->owner_mob.'~~'.$query[0]->owner_landline.'~~'.$query[0]->owner_cat.'~~'.$query[0]->owner_company.'~~'.$query[0]->ownercontactid.'~~'.$query[0]->ownercontactmob.'~~'.''.'~~'.''.'~~'.''.'~~'.''.'~~'.''.'~~'.''.'~~'.''.'~~'.''.'~~'.''.'~~'.''.'~~'.''.'~~'.$query[0]->contact_name.'~~'.$query[0]->owner_contact_email.'~~'.$query[0]->alse_mail.'~~'.$query[0]->asm_mail;die;
			}else{
				echo 'no';die;
			}
				
			
		}catch (\Exception $ex) {
			dd($ex->getMessage());
			$notification = array(
                'message' => $ex->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
	}
	public function sendLatlongLink(Request $request){
		try{
			$phone = $request->input('phone');
			//$sessionId = session()->getId();
			$sessionId = Session::getId();
			$pwd = 'YajfWt@Z';
			$uid = '2000194089';
			$auth_key ='aw4q23sdwq23edas312';
			$is_file='no';
			$sender_id = 'ASHLEY';
			$sms_number=1;
			$link = route('search-location-mob.searchLocationMob', ['phone' => $phone, 'sessionId' => $sessionId]);
			$number_list= '+91'.$phone;
			$mob=$phone;
			$mobile='91'.$phone;
			//$message= urlencode('Hello Please click given link to get your location '.$link);
			/* $message= urlencode('Dear AL Select Customer, Welcome to AL ELITE Support.
			Please click '.$link.' to share your current location for our team to reach you.
			Thank You
			Ashok Leyland.'); */

			$message= urlencode('Welcome to AL ELITE Support. Please click '.$link.' to share your current location to reach you. Thank YouAshok Leyland Elite.');
			$curl = curl_init();
			curl_setopt_array($curl, array(
			CURLOPT_URL => "https://enterprise.smsgupshup.com/GatewayAPI/rest?method=SendMessage&send_to=$mobile&msg=$message&msg_type=TEXT&userid=$uid&auth_scheme=plain&password=$pwd&v=1.1&format=text",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET"
			//   CURLOPT_POSTFIELDS => "{\"body\":{\"SRNumber\":\"19091809966\"}}",
			//CURLOPT_POSTFIELDS => "{\"body\":{\"SRNumber\":\"$UID\"}}",
			));
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			$response = curl_exec($curl);
			$err = curl_error($curl);
			curl_close($curl);
			echo "Link sent successfully"."@~~@".$sessionId;
		}catch (\Exception $ex) {
			$notification = array(
                'message' => $ex->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
	}
	
	public function searchLocationMob($phone, $sessionId){
		try{
			$data['phoneNumber'] = $phone;
			$data['sessionId'] = $sessionId;
			return view('thanku',$data); 
		}catch (\Exception $ex) {
			$notification = array(
				'message' => $ex->getMessage(),
				'alert-type' => 'error'
			);
			return back()->with($notification);
		}
	}
	public function insertLatlong(Request $request){
		$lat = $request->input('lat');
		$long = $request->input('long');
		$phoneNumber = $request->input('phoneNumber');
		$sessionId = $request->input('sessionId');
		$flag = 0;		
		DB::table('custlocation')->insert(['ContactNo'=>$phoneNumber, 'latitude'=>$lat, 'longitude'=>$long, 'session_id'=>$sessionId, 'flag'=>$flag]);
		echo "Inserted";
	}
	public function getLatlongMap(Request $request){
		$phone = $request->input('phone');
		$sessionId = $request->input('sessionId');
		$query = DB::select("Select id, ContactNo, latitude, longitude, session_id, flag from custlocation where ContactNo= $phone and session_id = '$sessionId' order by id desc limit 1");
		//dd($query);
		echo $query[0]->latitude.'~~'.$query[0]->longitude ;
		
	}
	public function getNearestLatlong(Request $request){
		$lat = $request->input('lat');
		$long = $request->input('long');
		
		$return_arr = array();
		
		$sql = DB::select("SELECT id,latitude,longitude,dealer_name ,(3956 * 2 * ASIN(SQRT( POWER(SIN(( $lat - latitude) *  pi()/180 / 2), 2) +COS( $lat * pi()/180) * COS(latitude * pi()/180) * POWER(SIN(( $long - longitude) * pi()/180 / 2), 2) ))) *2 as distance, SC_City_Name 
		from mstr_dealer  where flag=1 having  distance <= 100 order by distance limit 10;");
		
		if(sizeof($sql) == 0){
			$sql = DB::select("SELECT id,latitude,longitude,dealer_name ,(3956 * 2 * ASIN(SQRT( POWER(SIN(( $lat - latitude) *  pi()/180 / 2), 2) +COS( $lat * pi()/180) * COS(latitude * pi()/180) * POWER(SIN(( $long - longitude) * pi()/180 / 2), 2) ))) *2 as distance, SC_City_Name
			from mstr_dealer  where flag=1  and latitude is not null order by distance limit 5");
		}
		
		foreach($sql as $row){
			$latitude = $row->latitude;
			$longitude = $row->longitude;
			$dealer_name = $row->dealer_name;
			$SC_City_Name = $row->SC_City_Name;
			$return_arr[] = array("latitude" => $latitude,
                    "longitude" => $longitude,
                    "dealer_name" => $dealer_name,
					"SC_City_Name" => $SC_City_Name
				);
		}
		echo json_encode($return_arr);
		
	}
	public function getAssignDetails(Request $request){
		$lat = $request->input('lat');
		$long = $request->input('long');
		$sql = DB::select("SELECT id,latitude,longitude,dealer_name ,(3956 * 2 * ASIN(SQRT( POWER(SIN(( $lat - latitude) *  pi()/180 / 2), 2) +COS( $lat * pi()/180) * COS(latitude * pi()/180) * POWER(SIN(( $long - longitude) * pi()/180 / 2), 2) ))) as distance  
		from mstr_dealer having  distance <= 100 and flag=1 order by distance");
		foreach($sql as $row){
			$id = $row->id;
			$dealer_name = $row->dealer_name;
			echo $id.'~~'.$dealer_name.',';
		}
	}
	public function getAssignMob(Request $request){
		$id = $request->input('id');
		/* $query = DB::select("Select id,phone from mstr_dealer where id = $id");
		$mob = $query[0]->phone;
		echo $mob; */
		$query = DB::select("select mobile from users where role in (76,113) and find_in_set($id,dealer_id) and flag=1");
		$mob = $query[0]->mobile;
		if($mob!=''){
			echo $mob;
		}else{
			echo 'No';
		}
	}
	public function getStateChange(Request $request){
		$stateId = $request->input('stateId');
		$query = DB::Select("Select id,district from mstr_district where state_id=$stateId");
		foreach($query as $row){
			echo $row->id.'~~'.$row->district.',';
		}
	}
	public function getCity(Request $request)
	{
		try{
			$stateId = $request->input('s_id');
			$districtId = $request->input('d_id');
			
			$sql = DB::select("SELECT concat(id,'~',city)city from mstr_city where state_id=$stateId and district_id=$districtId");
			foreach ($sql as $value) {
				echo $value->city.',';
			}
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
			$stateId  = $request->input('stateId');
			$districtid  = $request->input('districtid');
			$str  = $request->input('str');
			$sql = DB::select("SELECT id,city from mstr_city where state_id='$stateId' and district_id='$districtid'");
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
	public function callerUpdate(Request $request){
		try { 
			/* $vehicleId  = $request->input('vehicleId'); */
			$ownerId  = $request->input('ownerId');
			$callerId  = $request->input('callerId');
			$caller_type  = $request->input('caller_type');
			$caller_name  = $request->input('caller_name');
			$caller_contact  = $request->input('caller_contact');
			$caller_language  = $request->input('caller_language');
			//$caller_location  = $request->input('caller_location');
			//$caller_landmark  = $request->input('caller_landmark');
			/* $vehicle_type  = $request->input('vehicle_type');
			$vehicle_movable  = $request->input('vehicle_movable'); */
			/* $zone  = $request->input('zone');
			$state  = $request->input('state');
			$city  = $request->input('city'); */
			/* $check = DB::select("Select id from mstr_caller where  owner_id = $ownerId");
			if(sizeof($check)<1){ */
				//DB::select("Insert into mstr_caller (owner_id,caller_type,caller_name,caller_contact) values ($ownerId,'$caller_type','$caller_name','$caller_contact')");
				DB::table('mstr_caller')->insert(['owner_id'=>$ownerId,'caller_type'=>$caller_type,'caller_name'=>$caller_name,'caller_contact'=>$caller_contact,'caller_language'=>$caller_language]);
				$id = DB::getPdo()->lastInsertId();
				echo $id.'~~'."Caller Inserted";
				
			/* }else{
				DB::select("Update mstr_caller set caller_type = '$caller_type',caller_name= '$caller_name',caller_contact='$caller_contact' where  owner_id = $ownerId and id=$callerId");
				echo $callerId.'~~'."Caller Updated";
				
			} */
		} catch (\Exception $ex) {
			$notification = array(
			'message' => $ex->getMessage(),
			'alert-type' => 'error'
			);
			return back()->with($notification);
		}
	}
	public function vehicleUpdate(Request $request)
	{
		try {
			$vehicleId  = $request->input('vehicleId')!=''?$request->input('vehicleId'):0;
			$reg_number1  = $request->input('reg_number1');
			$chassis_number1  = $request->input('chassis_number1');
			$engine_number1  = $request->input('engine_number1');
			$vehicle_model  = $request->input('vehicle_model');
			$vehicle_segment  = $request->input('vehicle_segment');
			$purchase_date  = $request->input('purchase_date');
			$add_blue_use  = $request->input('add_blue_use');
			$engine_emmission_type  = $request->input('engine_emmission_type');	
			$owner_id  = $request->input('owner_id');	
			$vehicle_model = str_replace("'","",$vehicle_model);	
			$check = DB::select("Select id from mstr_vehicle where id = $vehicleId");
			
			if(sizeof($check)<1){
				
				DB::select("Insert into mstr_vehicle (ownerId,vehicle_model, reg_number, chassis_number, engine_number, vehicle_segment, purchase_date, add_blue_use, engine_emmission_type) values ('$owner_id','$vehicle_model','$reg_number1','$chassis_number1','$engine_number1','$vehicle_segment','$purchase_date','$add_blue_use','$engine_emmission_type')");
				$id = DB::getPdo()->lastInsertId();
				echo $id.'~~'."Vehicle Inserted";
			}else{
				DB::select("Update mstr_vehicle set vehicle_model = '$vehicle_model',vehicle_segment= '$vehicle_segment',purchase_date='$purchase_date',add_blue_use='$add_blue_use',engine_emmission_type='$engine_emmission_type' where id = $vehicleId");
				echo $vehicleId.'~~'."Vehicle Updated";
				
			}
			
			

		} catch (\Exception $ex) {
			$notification = array(
			'message' => $ex->getMessage(),
			'alert-type' => 'error'
			);
			return back()->with($notification);
		}
	}
	public function ownerUpdate(Request $request)
	{
		try {
			//$vehicleId  = $request->input('vehicleId')!=''?$request->input('vehicleId'):0;
			$ownerId  = $request->input('ownerId')!=''?$request->input('ownerId'):0;
			$owner_name  = $request->input('owner_name');
			$owner_mob  = $request->input('owner_mob');
			$owner_landline  = $request->input('owner_landline');
			$owner_cat  = $request->input('owner_cat');
			$owner_company  = $request->input('owner_company');
			$alse_mail  = $request->input('alse_mail');
			$asm_mail  = $request->input('asm_mail');
			
				
			$check = DB::select("Select id from mstr_owner where id=$ownerId");
			
			if(sizeof($check)<1){
				DB::select("Insert into mstr_owner (owner_name, owner_mob, owner_landline, owner_cat, owner_company,alse_mail,asm_mail) values ('$owner_name','$owner_mob','$owner_landline','$owner_cat','$owner_company','$alse_mail','$asm_mail')");
				$id = DB::getPdo()->lastInsertId();
				echo $id.'~~'."Owner Inserted";
			}else{
				DB::select("Update mstr_owner set owner_name = '$owner_name',owner_mob= '$owner_mob',owner_landline='$owner_landline',owner_cat='$owner_cat',owner_company='$owner_company',alse_mail='$alse_mail',asm_mail='$asm_mail' where id = $ownerId");
				echo $ownerId.'~~'."Owner Updated";
				
			}
			
			

		} catch (\Exception $ex) {
			$notification = array(
			'message' => $ex->getMessage(),
			'alert-type' => 'error'
			); 
			return back()->with($notification);
		}
	}
	public function ownerContactUpdate(Request $request)
	{
		try{
			$vehicleId  = $request->input('vehicleId')!=''?$request->input('vehicleId'):0;
			$ownerId  = $request->input('ownerId')!=''?$request->input('ownerId'):0;
			$owenerContactId  = $request->input('owenerContactId')!=''?$request->input('owenerContactId'):0;
			$owner_contact_email  = $request->input('owner_contact_email')!=''?$request->input('owner_contact_email'):0;
			$contact_name  = $request->input('contact_name');
			$owner_contact_mob  = $request->input('owner_contact_mob');
			
			$check = DB::select("Select id from mstr_owner_contact where owner_id=$ownerId and id =$owenerContactId ");
			if(sizeof($check)<1){
				DB::select("Insert into mstr_owner_contact (vehicle_id, owner_id,contact_name, mob,owner_contact_email) values ('$vehicleId','$ownerId','$contact_name','$owner_contact_mob','$owner_contact_email')");
				$id = DB::getPdo()->lastInsertId();
				echo $id.'~~'."Cantact Inserted";
			}else{
				DB::select("Update mstr_owner_contact set contact_name = '$contact_name', mob = '$owner_contact_mob',owner_contact_email='$owner_contact_email' where owner_id = $ownerId and id=$owenerContactId ");
				echo $owenerContactId.'~~'."Cantact Updated";
			}
		} catch (\Exception $ex) {
			$notification = array(
			'message' => $ex->getMessage(),
			'alert-type' => 'error'
			);
			return back()->with($notification);
		}
	}
	public function ticketCreationData(Request $request)
	{
		
		$reg_number = $request->input('reg_number1')!=''?$request->input('reg_number1'):'000000NA';			
		$checkVehiclleTicket = DB::select("select count(*) as cnt from cases as c left join mstr_vehicle as v on c.vehicleId =v.id where (v.reg_number ='$reg_number') and c.remark_type !='Closed'");		
		if($checkVehiclleTicket[0]->cnt > 0 ){
			$notification = array(
				'message' => "Vehicle already registered",
				'alert-type' => "error"
			);
			return back()->with($notification);
		}
									
			
			$vehicleId = $request->input('vehicleId');
			$ownerId = $request->input('ownerId');
			$owenerContactId = $request->input('owenerContactId');
			$callerId = $request->input('callerId');
			$from_where = $request->input('from_where');
			$to_where = $request->input('to_where');
			$highway = $request->input('highway');
			$ticket_type = $request->input('ticket_type');
			$aggregate = $request->input('aggregate');
			$vehicle_problem = $request->input('vehicle_problem');
			$assign_to = $request->input('assign_to');
			$dealer_mob_number = $request->input('dealer_mob_number');
			$dealer_alt_mob_number = $request->input('dealer_alt_mob_number');
			$remark_type = $request->input('remark_type');
			$disposition = $request->input('disposition');
			$agent_remark = $request->input('agent_remark');
			$standard_remark = $request->input('standard_remark');
			$assign_remarks = $request->input('assign_remarks');
			$restoration_type = $request->input('restoration_type');
			$response_delay_reason = $request->input('response_delay_reason');
			
			$estimated_response_time = $request->input('estimated_response_time'); 
			$tat_scheduled = $request->input('tat_scheduled');
			$acceptance = $request->input('acceptance');
			$latValue = $request->input('latValue');
			$longValue = $request->input('longValue');
			$lat = $request->input('lat');
			$long = $request->input('long');
			$lat = $latValue !=''?$latValue:$lat;
			$long = $longValue !=''?$longValue:$long;
			$lat = $lat !=''?number_format((float)$lat, 5, '.', ''):'';
			$long = $long !=''?number_format((float)$long, 5, '.', ''):'';
			
			$owner_contact_mob = $request->input('owner_contact_mob');
			$actual_response_time = $request->input('actual_response_time');
			$caller_name = $request->input('caller_name');
			$caller_type = $request->input('caller_type');
			$caller_contact = $request->input('caller_contact');
			$reg_number1 = $request->input('reg_number1');

			$location = $request->input('location');
			$landmark = $request->input('landmark');
			$vehicle_problem = addslashes($request->input('vehicle_problem'));
			$state = $request->input('state');
			$district = $request->input('district');
			$city = $request->input('city')!=''?$request->input('city'):'0';
			$owner_name = $request->input('owner_name');  
			$owner_mob = $request->input('owner_mob');  
			$owner_company = $request->input('owner_company');  
			$owner_cat = $request->input('owner_cat');  
			$contact_name = $request->input('contact_name');
			$vehicle_type = $request->input('vehicle_type');
			$vehicle_movable = $request->input('vehicle_movable');
			$engine_emmission_type = $request->input('engine_emmission_type');
			// $assign_work_manager = $request->input('assign_work_manager');
			$assign_work_managerArr = $request->input('assign_work_manager');
			/* changed */
			$assign_work_managerArr = explode("**",$assign_work_managerArr);
			$assign_work_manager = $assign_work_managerArr[0];
			/* changed */
			$assign_work_manager_mobile = $request->input('assign_work_manager_mobile');


			$contact_name = $request->input('contact_name');
			$owner_contact_mob = $request->input('owner_contact_mob');

			$currentUserMail = Auth::user()->email;
			$source = $request->input('source');
			$followup_time = $request->input('followup_time');

			/* Check If dealer is Active or not */
			$checkDealerActiveOrNot = DB::select("Select * from mstr_dealer where id = $assign_to and flag=0");
			if(sizeof($checkDealerActiveOrNot)>0){
				$errMessage = array(
					'message' => "Selected Dealer is Inactive",
					'alert-type' => "error"
				);
				return back()->with($errMessage);
			}
			/* Check If dealer is Active or not */

			//dd($actual_response_time);
			$supportContPersonSql = DB::select("Select mobile,name from users where role in (76,113) and FIND_IN_SET($assign_to, dealer_id) and flag=1");
 			$supportContPerson = sizeof($supportContPersonSql)>0?$supportContPersonSql[0]->name:'NA';
 			$supportContPersonMob = sizeof($supportContPersonSql)>0?$supportContPersonSql[0]->mobile:'NA';
			if($owner_contact_mob!=''){  
				$followup_name = $contact_name.' (Owner)';
				$followups_number = $owner_contact_mob;
			}else{
				$followup_name = $owner_name.' (Owner)';
				$followups_number = $owner_mob;
			}
			/* *******BS6******* */
			try{
				$dealerBSSql = DB::select("Select bsvi,area_champion,region_champion from mstr_dealer where id = $assign_to");
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

				$bsviEmailArr = explode(",",$bsviEmail);
				$area_championEmailArr = explode(",",$area_championEmail);
				$region_championEmailArr = explode(",",$region_championEmail);
			}catch (\Exception $aa) {
				$aaq = $aa->getMessage();
				DB::table('creation_exception')->insert(['complaint_number'=>"before Complaitn register",'type'=>"Line 477 to 496",'exception'=>"$aaq"]);
			}
			$actual_response_time_customer = $request->input('actual_response_time_customer');
			$tat_scheduled_customer = $request->input('tat_scheduled_customer');

			/* *******BS6******* */
			try {
				$actual_response_time_customer = $actual_response_time_customer!=''?$actual_response_time_customer:'';
				$tat_scheduled_customer = $tat_scheduled_customer!=''?$tat_scheduled_customer:'';

				$resultCase = DB::select("call Case_Creation('".$vehicleId."','".$ownerId."','".$owenerContactId."','".$city."','".$state."','".$callerId."','".$from_where."','".$to_where."','".$highway."','".$ticket_type."','".$aggregate."','".addslashes($vehicle_problem)."','".$currentUserMail."','".$assign_to."','".$dealer_mob_number."','".$dealer_alt_mob_number."','".$remark_type."','".$disposition."','".$agent_remark."','".addslashes($standard_remark)."','".addslashes($assign_remarks)."','".$estimated_response_time."','".$tat_scheduled."','".$acceptance."','".$lat."','".$long."','".$actual_response_time."','".$location."','".$landmark."','".$district."','".$vehicle_type."','".$vehicle_movable."','".$restoration_type."','".$response_delay_reason."','".$assign_work_manager."','".$assign_work_manager_mobile."','".$source."','".$followup_time."','".$actual_response_time_customer."','".$tat_scheduled_customer."')");
				$lastInsertedId =$resultCase[0]->lastInsertedId;
				$complaint_number =$resultCase[0]->complaint_number;
				$loginId = Auth::user()->employee_id;
				$loginName = Auth::user()->name;
			}catch (\Exception $bb) {	
				$bbq = $bb->getMessage();			
				DB::table('creation_exception')->insert(['complaint_number'=>"$complaint_number",'type'=>"Ticket Insertion",'exception'=>"$bbq"]);
				$notification = array(
					'message' => $bb->getMessage(),
					'alert-type' => 'error'
				);
				return back()->with($notification);
			}
			try {			
				$acceptance = $acceptance!=''?$acceptance:1;
				$assign_remarks = addslashes($assign_remarks);	
				DB::table('remarks')->insert(['complaint_number'=>"$complaint_number",'remark_type'=>"$remark_type",'employee_name'=>"$loginName",'employee_id'=>"$currentUserMail",'dealer_mob_number'=>"$dealer_mob_number",'assign_to'=>"$assign_to",'disposition'=>"$disposition",'agent_remark'=>"$agent_remark",'assign_remarks'=>"$assign_remarks",'estimated_response_time'=>"$estimated_response_time",'tat_scheduled'=>"$tat_scheduled",'acceptance'=>"$acceptance",'actual_response_time'=>"$actual_response_time"]);
			} catch (\Exception $cc) {	
				$ccq = $cc->getMessage();			
				DB::table('creation_exception')->insert(['complaint_number'=>"$complaint_number",'type'=>"remarks Insertion",'exception'=>"$ccq"]);
			}

			try {
				DB::table('escaltion_levels')->insert(['levels'=>"1",'complaint_number'=>"$complaint_number",'assign_to'=>"$assign_to",'Status'=>""]);
			}catch (\Exception $dd) {	
				
				$ddq = $dd->getMessage();
				DB::table('creation_exception')->insert(['complaint_number'=>"$complaint_number",'type'=>"escaltion_levels Insertion",'exception'=>"$ddq"]);
			}
			try {
				DB::table('followups')->insert(['complaint_number' => "$complaint_number",'employee_name' => "$loginName", 'employee_id' => "$currentUserMail",'status'=>"$remark_type", 'dealer_mob_number' => "$dealer_mob_number", 'dealer_alt_mob_number' => "$dealer_alt_mob_number", 'assign_to' => "$assign_to", 'assign_type' => "",'vehicleId' => "$vehicleId", 'ownerId' => "$ownerId",'estimated_response_time' => "$estimated_response_time",'actual_response_time'=>"$actual_response_time",'followup_name'=>"$followup_name",'followups_number'=>"$followups_number" ]);
			}catch (\Exception $ee) {	
				$eeq = $ee->getMessage();
				DB::table('creation_exception')->insert(['complaint_number'=>"$complaint_number",'type'=>"followups Insertion",'exception'=>"$eeq"]);
			}

			/* ******************* API ************************** */
			
					try {
						$workMangerMobSQL =  DB::select("Select name,mobile from users where role in (76,113) and FIND_IN_SET($assign_to, dealer_id) and flag=1");
						$workMangerMob = sizeof($workMangerMobSQL)>0?$workMangerMobSQL[0]->mobile:'';
						$workMangername = sizeof($workMangerMobSQL)>0?$workMangerMobSQL[0]->name:'';
						$pwd = 'YajfWt@Z';
						$uid = '2000194089';
						$cityMasterSql = DB::select("select city from mstr_caller_city where id=$city");
						$cityName = sizeof($cityMasterSql)>0?$cityMasterSql[0]->city:'NA';
						$dealerDataSql = DB::select("Select d.latitude,d.longitude,d.dealer_name,d.phone,d.sac_code,s.state as stateName,c.city as cityName from mstr_dealer as d left join mstr_state as s on s.id = d.state left join mstr_city as c on c.id = d.city  where d.id = $assign_to");
						$code = $dealerDataSql[0]->sac_code;
						$dealerName = $dealerDataSql[0]->dealer_name;
						//$cityName = $dealerDataSql[0]->cityName;
						$stateName = $dealerDataSql[0]->stateName;
						$phone = $dealerDataSql[0]->phone;
						$latitudeAPI = $dealerDataSql[0]->latitude;
						$longitudeAPI = $dealerDataSql[0]->longitude;
						$recQuery = DB::select("Select created_at from cases where  complaint_number = '$complaint_number'");
						$caseCreatedDate = $recQuery[0]->created_at;
						
						$ownerQuery = DB::select("Select owner_company,owner_name from mstr_owner where id=$ownerId");
						$owner_company = sizeof($ownerQuery)>0?$ownerQuery[0]->owner_company:'NA';
						$owner_nameMSU = sizeof($ownerQuery)>0?$ownerQuery[0]->owner_name:'NA';
						$chassis_number1 = $request->input('chassis_number1');
						$engine_number1 = $request->input('engine_number1');
						$vehicle_model = $request->input('vehicle_model');
						$purchase_date = $request->input('purchase_date');
						$owner_landline = $request->input('owner_landline');
						$owner_mob = $request->input('owner_mob');
						$alse_mail = $request->input('alse_mail');
						$asm_mail = $request->input('asm_mail');
						$engine_emmission_type = $request->input('engine_emmission_type');
						/* Get State Name */
						$getStateNameQuery = DB::select("Select id, state from mstr_caller_state where id=$state");
						$getStateName = sizeof($getStateNameQuery)>0?$getStateNameQuery[0]->state:'NA';
						/* Get State Name */
						
						$deaQuery = DB::select("Select dealer_name from mstr_dealer where id = $assign_to");			
						$dealer_nameAPI = sizeof($deaQuery)>0?$deaQuery[0]->dealer_name:'';
						
						$vehicle_segment = $request->input('vehicle_segment');

						/* New */					
						$contact_nameId = $request->input('contact_name');
						$mstr_owner_contact_query = DB::select("Select contact_name from mstr_owner_contact where id = $contact_nameId");
						$contactNameVal = sizeof($mstr_owner_contact_query)>0?$mstr_owner_contact_query[0]->contact_name:'NA';
						
					}catch (\Exception $ee) {	
						$eeq11 = $ee->getMessage();
						DB::table('creation_exception')->insert(['complaint_number'=>"$complaint_number",'type'=>"Before MSU logs",'exception'=>"$eeq11"]);
					}
					/* $mstr_owner_query = DB::select("Select owner_company from mstr_owner where id = $ownerId");
					$owner_companyVal = sizeof($mstr_owner_query)>0?$mstr_owner_query[0]->owner_company:'NA'; */
					/* New */
					// $purchase_date = $purchase_date !=''?$purchase_date.' 00:00:00':'';

					
					/* Add ticket creation Date */
						$getCreateionDate = DB::select("select created_at from cases  where complaint_number = '$complaint_number'");
						$dateCreatedDate = $getCreateionDate[0]->created_at;
					/* Add ticket creation Date */
					/* 12-10-2023 */
						$tsmQuery = DB::select("select name,mobile from users where find_in_set($assign_to,dealer_id) and role = 1  and flag=1 limit 1");
						if(sizeof($tsmQuery)>0){
							$tsmName = $tsmQuery[0]->name;
							$tsmMobile = $tsmQuery[0]->mobile;
						}else{
							$tsmName = '';
							$tsmMobile = '';
						}
						$caller_language  = $request->input('caller_language')!=''?$request->input('caller_language'):'Hindi';
					/* 12-10-2023 */
					$reqMSUAPI = '{
						"tsm_name":"'.$tsmName.'",
						"tsm_mobile":"'.$tsmMobile.'",
						"assigned_date_tme":"'.$dateCreatedDate.'",
						"caller_language":"'.$caller_language.'",
						"created_by":"'.$loginId.'",
						"ticketCreatedTime":"'.$dateCreatedDate.'",
						"ticket_no":"'.$complaint_number.'",
						"reg_no":"'.$reg_number1.'",
						"chasi_no":"'.$chassis_number1.'",
						"eng_no":"'.$engine_number1.'",
						"vehicle_model":"'.$this->removeSpecialChar($vehicle_model).'",
						"vehicle_seg":"'.$vehicle_segment.'",
						"long_hal":"Long Haulage",
						"lattitude":"'.$lat.'",
						"longitude":"'.$long.'",
						"purchase_date":"'.$purchase_date.'",
						"eng_emi_type":"'.$engine_emmission_type.'",
						"owner_name":"'.$owner_nameMSU.'",
						"owner_mobile":"'.$owner_mob.'",
						"owner_lno":"'.$owner_landline.'",
						"owner_company":"'.$owner_company.'",
						"owner_category":"'.$owner_cat.'",
						"email":"'.$alse_mail.'",
						"asm_email":"'.$asm_mail.'",
						"caller_info":"Driver/Caller Info",
						"caller_type":"'.$caller_type.'",
						"caller_name":"'.$caller_name.'",
						"caller_no":"'.$caller_contact.'",
						"vehicle_from":"'.$from_where.'",
						"vehicle_to":"'.$to_where.'",
						"bd_location":"'.$location.'",
						"bd_lm":"'.$landmark.'",
						"bd_state":"'.$getStateName.'",
						"bd_district":"'.$cityName.'",
						"bd_city":"'.$district.'",
						"bd_hway":"'.$highway.'",
						"vehicle_type":"'.$vehicle_type.'",
						"vehicle_condition":"'.$vehicle_movable.'",
						"ticket_type":"'.$ticket_type.'",
						"vehicle_problem":"'.$vehicle_problem.'",
						"dealer_name":"'.$dealerName.'",
						"dealer_wmname":"'.$assign_work_manager.'",
						"dealer_wmmobileno":"'.$assign_work_manager_mobile.'",
						"dealer_workstatus":"'.$remark_type.'",
						"delaer_remark":"'.$this->removeSpecialChar($assign_remarks).'",
						"addBlueUse":"'.$request->input('add_blue_use').'",
						"aggregate":"'.$request->input('aggregate').'",
						"dealercode":"'.$code.'",
						"disposition":"'.$request->input('disposition').'",
						"agentRemarks":"'.$agent_remark.'",
						"acceptance":"'.$request->input('acceptance').'",
						"standardRemark":"'.$this->removeSpecialChar($request->input('standard_remark')).'",
						"contact_person_mail":"'.$request->input('owner_contact_email').'",
						"contact_person_name":"'.$contactNameVal.'",
						"contact_person_phone":"'.$request->input('owner_contact_mob').'",

						"ticket_status":"'.$request->input('remark_type').'",
						"estimated_response_time":"'.$request->input('estimated_response_time').'",
						"source":"'.$request->input('source').'",
						"followup_time":"'.$request->input('followup_time').'"
					}';
					try {						
						DB::select("INSERT INTO msu_api (complaint_number, remarks) VALUES ('$complaint_number', '$reqMSUAPI')");
					} catch (\Exception $ff) {
						$ffq = $ff->getMessage();
						DB::table('creation_exception')->insert(['complaint_number'=>"$complaint_number",'type'=>"msu_api",'exception'=>"$ffq"]);
					}
					// old API URL: https://y1keqvs2ge.execute-api.us-east-1.amazonaws.com/v1/create-ticket
					$startTime = date("Y-m-d H:i:s");
				try {
					$curl = curl_init();
					curl_setopt_array($curl, array(
					  CURLOPT_URL => 'http://10.60.64.225/msu/cogCreateTicket',
					  //CURLOPT_URL => 'https://y1keqvs2ge.execute-api.us-east-1.amazonaws.com/v1/create-ticket',
					  CURLOPT_RETURNTRANSFER => true,
					  CURLOPT_ENCODING => '',
					  CURLOPT_MAXREDIRS => 10,
					  CURLOPT_TIMEOUT => 0,
					  CURLOPT_FOLLOWLOCATION => true,
					  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					  CURLOPT_CUSTOMREQUEST => 'POST',
					  CURLOPT_POSTFIELDS =>'{
						"tsm_name":"'.$tsmName.'",
						"tsm_mobile":"'.$tsmMobile.'",
						"assigned_date_tme":"'.$dateCreatedDate.'",
						"caller_language":"'.$caller_language.'",
						"created_by":"'.$loginId.'",
						"ticketCreatedTime":"'.$dateCreatedDate.'",
						"ticket_no":"'.$complaint_number.'",
						"reg_no":"'.$reg_number1.'",
						"chasi_no":"'.$chassis_number1.'",
						"eng_no":"'.$engine_number1.'",
						"vehicle_model":"'.$this->removeSpecialChar($vehicle_model).'",
						"vehicle_seg":"'.$vehicle_segment.'",
						"long_hal":"Long Haulage",
						"lattitude":"'.$lat.'",
						"longitude":"'.$long.'",
						"purchase_date":"'.$purchase_date.'",
						"eng_emi_type":"'.$engine_emmission_type.'",
						"owner_name":"'.$owner_nameMSU.'",
						"owner_mobile":"'.$owner_mob.'",
						"owner_lno":"'.$owner_landline.'",
						"owner_company":"'.$owner_company.'",
						"owner_category":"'.$owner_cat.'",
						"email":"'.$alse_mail.'",
						"asm_email":"'.$asm_mail.'",
						"caller_info":"Driver/Caller Info",
						"caller_type":"'.$caller_type.'",
						"caller_name":"'.$caller_name.'",
						"caller_no":"'.$caller_contact.'",
						"vehicle_from":"'.$from_where.'",
						"vehicle_to":"'.$to_where.'",
						"bd_location":"'.$location.'",
						"bd_lm":"'.$landmark.'",
						"bd_state":"'.$getStateName.'",
						"bd_district":"'.$cityName.'",
						"bd_city":"'.$district.'",
						"bd_hway":"'.$highway.'",
						"vehicle_type":"'.$vehicle_type.'",
						"vehicle_condition":"'.$vehicle_movable.'",
						"ticket_type":"'.$ticket_type.'",
						"vehicle_problem":"'.$vehicle_problem.'",
						"dealer_name":"'.$dealerName.'",
						"dealer_wmname":"'.$assign_work_manager.'",
						"dealer_wmmobileno":"'.$assign_work_manager_mobile.'",
						"dealer_workstatus":"'.$remark_type.'",
						"delaer_remark":"'.$this->removeSpecialChar($assign_remarks).'",
						"addBlueUse":"'.$request->input('add_blue_use').'",
						"aggregate":"'.$request->input('aggregate').'",
						"dealercode":"'.$code.'",
						"disposition":"'.$request->input('disposition').'",
						"agentRemarks":"'.$agent_remark.'",
						"acceptance":"'.$request->input('acceptance').'",
						"standardRemark":"'.$this->removeSpecialChar($request->input('standard_remark')).'",
						"contact_person_mail":"'.$request->input('owner_contact_email').'",
						"contact_person_name":"'.$contactNameVal.'",
						"contact_person_phone":"'.$request->input('owner_contact_mob').'",

						"ticket_status":"'.$request->input('remark_type').'",
						"estimated_response_time":"'.$request->input('estimated_response_time').'",
						"source":"'.$request->input('source').'",
						"followup_time":"'.$request->input('followup_time').'",
						"aggregate":"'.$request->input('aggregate').'"
					}',
					CURLOPT_HTTPHEADER => array(
					'Authorization: Basic bXN1Y29nZW50OmlZbVBSaDJubXA=',
					'Content-Type: application/json'
					),
					));
					curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
					$response = curl_exec($curl);
		
					curl_close($curl);
					// $startTime = date("Y-m-d H:i:s");
					$endTime = date("Y-m-d H:i:s");
	
					$datetime1 = new DateTime($startTime);
					$datetime2 = new DateTime($endTime);
					$interval = $datetime1->diff($datetime2);
					$delayTime = $interval->format('%H:%i:%s');
					
					
					$isJson = is_array(json_decode($response,true));
					if($isJson){
					//if($response){
						$responseDecode = json_decode($response);
						$apiResult = $responseDecode->result;
						$apiTicketNumber = $responseDecode->ticket_no;
						DB::select("INSERT INTO creation_api_remarks (complaint_number, remarks) VALUES ('$apiTicketNumber', '$apiResult')");
					}else{
						DB::select("INSERT INTO creation_api_remarks (complaint_number, remarks) VALUES ('$complaint_number', 'Failed')");
					}
					
				}catch (\Exception $gg) {
					
					$ggq = $gg->getMessage();
					DB::table('creation_exception')->insert(['complaint_number'=>"$complaint_number",'type'=>"call MSU",'exception'=>"$ggq"]);
				}
				/* Call Update MSU API */
				$dealerNameQuery = DB::select("select dealer_name,sac_code from mstr_dealer where id=$assign_to ");
				$sac_code = $dealerNameQuery[0]->sac_code;
				$dealer_name = $dealerNameQuery[0]->dealer_name;
				$action_by = $loginName;
				$acceptance = $acceptance == '1'?'Yes':'No';
				$jsonDataArray = array('ticket_no'=>$complaint_number,'assign_to'=>$sac_code,'dealer_name'=>$dealer_name,'assign_work_manager'=>$assign_work_manager,'assign_work_manager_mobile'=>$assign_work_manager_mobile,'remark_type'=>$remark_type,'disposition'=>$disposition,'agent_remark'=>$this->removeSpecialChar($agent_remark),'estimated_response_time'=>$estimated_response_time,'actual_response_time'=>$actual_response_time,'tat_scheduled'=>$tat_scheduled,'restoration_type'=>$restoration_type,'acceptance'=>$acceptance, 'followup_time'=>$followup_time,'feedback_rating'=>"NA",'feedback_desc'=>"NA",'assign_remarks'=>$this->removeSpecialChar($assign_remarks),'action_by'=>$action_by,'aggregate'=>$aggregate,'cc_status'=>$remark_type);
				$jsonData = json_encode($jsonDataArray);
				try {
					DB::select("INSERT INTO msu_api_updation (complaint_number, remarks,type) VALUES ('$complaint_number', '$jsonData','Update')");
				} catch (\Exception $ffUpdate) {
					$ffUExc = $ffUpdate->getMessage();
					DB::table('updation_exception')->insert(['complaint_number'=>"$complaint_number",'type'=>"msu_api update",'exception'=>"$ffUExc"]);
				}
				try {
					$i=0;
					do{
						$i++;
						$curl = curl_init();					
						curl_setopt_array($curl, array(
							// Live API 
							CURLOPT_URL => 'https://hz8tb0w051.execute-api.us-east-1.amazonaws.com/v1/reverse-data/ticket-update-cogent',
							// UAT API
							// CURLOPT_URL => 'https://y1keqvs2ge.execute-api.us-east-1.amazonaws.com/v2/reverse-data/ticket-update-cogent',
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
						$responseUpdate = curl_exec($curl);
						curl_close($curl);
						$isJsonUpdate = is_array(json_decode($responseUpdate,true));
						if($isJsonUpdate){				
							$check=false;
						}else{
							if($i > 4){
								$check =false;
							}else{
								$check=true;
							}	
						}
					}while($check);
					if($isJsonUpdate){				
						$responseDecodeUpdate = json_decode($responseUpdate);
						// $apiResultUpdate = $responseDecodeUpdate->body;
						// $apiTicketNumber = $responseDecodeUpdate->statusCode;
						DB::select("INSERT INTO updation_api_remarks (complaint_number, remarks) VALUES ('$complaint_number', 'Updated Successfully')");
					}else{
						DB::select("INSERT INTO updation_api_remarks (complaint_number, remarks) VALUES ('$complaint_number', 'Failed Update')");
					}
					
				} catch (\Exception $llMSU){
					$llMSU  = $llMSU->getMessage();
					DB::table('updation_exception')->insert(['complaint_number'=>"$complaint_number",'type'=>"MSU API Updation",'exception'=>"$llMSU"]);
				}	
				/* Call Update MSU API */
				try {
					$response = "Requset API ".$reqMSUAPI." Delay Time: ".$delayTime." MSU API Response ($complaint_number): ".$response." \n";
				
					$dtResponse = fopen("log.txt","a");
					echo fwrite($dtResponse,$response);
					fclose($dtResponse);
				}catch (\Exception $aqw) {
					$aqwq = $aqw->getMessage();
					DB::table('creation_exception')->insert(['complaint_number'=>"$complaint_number",'type'=>"call MSU File ",'exception'=>"$aqwq"]);
				}
				
			/* ******************* API ************************** */

			
			/* Message Send */ 
			$workMangerMobSQL =  DB::select("Select name,mobile from users where role in (76,113) and FIND_IN_SET($assign_to, dealer_id) and flag=1");
			$workMangerMob = sizeof($workMangerMobSQL)>0?$workMangerMobSQL[0]->mobile:'';
			$workMangername = sizeof($workMangerMobSQL)>0?$workMangerMobSQL[0]->name:'';
			$pwd = 'YajfWt@Z';
			$uid = '2000194089';
			$dealerDataSql = DB::select("Select d.dealer_name,d.phone,d.sac_code,s.state as stateName,d.SC_City_Name as cityName,d.latitude,d.longitude,c.city as dealerCity from mstr_dealer as d left join mstr_state as s on s.id = d.state left join mstr_city as c on c.id = d.city  where d.id = $assign_to");
			$code = $dealerDataSql[0]->sac_code;
			$dealerName = $dealerDataSql[0]->dealer_name;
			$cityName = $dealerDataSql[0]->cityName;
			$dealerCity = $dealerDataSql[0]->dealerCity;
			$stateName = $dealerDataSql[0]->stateName;
			$phone = $dealerDataSql[0]->phone;
			$phone = $dealerDataSql[0]->phone;
			$dealerLatitude = $dealerDataSql[0]->latitude;
			$dealerLongitude = $dealerDataSql[0]->longitude;
			$recQuery = DB::select("Select created_at from cases where  complaint_number = '$complaint_number'");
			$caseCreatedDate = $recQuery[0]->created_at;

			$assign_work_manager_mobileCheck = $assign_work_manager_mobile !=''? $this->multiMobile($assign_work_manager_mobile): $this->multiMobile($workMangerMob);
			$currentDateTime = date('Y-m-d H:i:s');
			if($assign_work_manager_mobileCheck !=''){
				//$mobile='91'.$owner_contact_mob.',91'.$workMangerMob;
				// $mobile=$this->multiMobile($owner_contact_mob).','.$assign_work_manager_mobileCheck.',918105736911';
				$mobile=$this->multiMobile($owner_contact_mob).','.$assign_work_manager_mobileCheck;
				/* $message= 'Dear Service team,
				Customer complaint number '.$complaint_number.' received on '.$caseCreatedDate.' is assigned to '.$dealerName.'.
				Company Name - '.$owner_company.', Caller name- '.$caller_name.', Number- '.$caller_contact.', RegNo - '.$reg_number1.' Location- '.$location.', Issue- '.$standard_remark.'
				Thank You
				Ashok Leyland.'; */

				$message= urlencode('AL Elite customer complaint number '.$complaint_number.' received on '.$caseCreatedDate.' is assigned to '.$dealerName.'. Customer Name - '.$owner_nameMSU.', Caller name- '.$caller_name.', Caller Number- '.$caller_contact.', Vehicle Reg No – '.$reg_number1.', Location- '.$location.', Issue- '.$standard_remark.'. Thank You Ashok Leyland Elite.');
				try {

					// $message= urlencode('Dear Customer,Complaint number '.$complaint_number.' for vehicle '.$reg_number1.' is assigned to '.$dealerName.', AO - '.$cityName.' on  '.$currentDateTime.'. WM number- '.$assign_work_manager_mobileCheck.'.To track live status of your breakdown ticket with tracking option using ALCare app. Click http://bit.ly/ALCARE_APP. Thank you Ashok Leyland');

					// $this->sendSMSFuction($mobile,$message,'1607100000000289242',"Creation SMS to Team",$complaint_number);


				//$message=urlencode($message);
				$url = "https://enterprise.smsgupshup.com/GatewayAPI/rest?method=SendMessage&send_to=$mobile&msg=$message&msg_type=TEXT&userid=$uid&auth_scheme=plain&password=$pwd&v=1.1&format=text";
				$curl = curl_init();
				curl_setopt_array($curl, array(
				CURLOPT_URL => "https://enterprise.smsgupshup.com/GatewayAPI/rest?method=SendMessage&send_to=$mobile&msg=$message&msg_type=TEXT&userid=$uid&auth_scheme=plain&password=$pwd&v=1.1&format=text",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET"
				//   CURLOPT_POSTFIELDS => "{\"body\":{\"SRNumber\":\"19091809966\"}}",
				//CURLOPT_POSTFIELDS => "{\"body\":{\"SRNumber\":\"$UID\"}}",
				));
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				$response = curl_exec($curl);
				$err = curl_error($curl);
				curl_close($curl);
				DB::table('sms_response')->insert(['url'=>"$url",'error'=>"$err",'response'=>"$response"]);
			} catch (\Exception $hh) {
				$hhq =$hh->getMessage();
					DB::table('creation_exception')->insert(['complaint_number'=>"$complaint_number",'type'=>"Team SMS",'exception'=>"$hhq"]);
				}
			}
			if($caller_contact !=''){
				$wm = $workMangerMob!=''?$workMangerMob:'NA';
				// $mobile='91'.$caller_contact.',918105736911';
				$mobile='91'.$caller_contact;
				
				/* $message= 'Dear Customer,
				Your Breakdown complaint number '.$complaint_number.' for vehicle-'.$reg_number1.' is assigned to-'.$dealerName.' WM number-'.$assign_work_manager_mobileCheck.'.
				Thank You
				Ashok Leyland.'; */
				//$message= urlencode('Dear AL ELITE Customer, Your complaint number '.$complaint_number.' for vehicle '.$reg_number1.' is assigned to '.$dealerName.', on '.$currentDateTime.'.WM number- '.$assign_work_manager_mobileCheck.'. Thank You Ashok Leyland Elite.');
				try {
					$message= urlencode('Dear Customer,Complaint number '.$complaint_number.' for vehicle '.$reg_number1.' is assigned to '.$dealerName.', AO - '.$dealerCity.' on  '.$currentDateTime.'. WM number- '.$assign_work_manager_mobileCheck.'.To track live status of your breakdown ticket with tracking option using ALCare app. Click http://bit.ly/ALCARE_APP. Thank you Ashok Leyland');

					$this->sendSMSFuction($mobile,$message,'1607100000000289242',"Creation SMS to customer",$complaint_number);
					//$message=urlencode($message);
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
				} catch (\Exception $ii) {
					$iiq = $ii->getMessage();
					DB::table('creation_exception')->insert(['complaint_number'=>"$complaint_number",'type'=>"Customer SMS",'exception'=>"$iiq"]);
				}
			}
			/* TSM SMS */
				try {
					$tsmQuery =DB::select("select mobile from users where find_in_set($assign_to,dealer_id) and role = 1 and flag=1 limit 1");										
						if(sizeof($tsmQuery)>0){
							$tsmMob = $tsmQuery[0]->mobile;
							// $mobile=$this->multiMobile($tsmMob).',918105736911';
							$mobile=$this->multiMobile($tsmMob);
							$latlong = $lat.' '.$long;
							$message= urlencode("Dear AL Team, Complaint number $complaint_number for vehicle $reg_number1 is assigned to $dealerName,AO - $dealerCity on $currentDateTime. WM Mob No-$assign_work_manager_mobileCheck. Customer Name - $owner_nameMSU, Caller Number-  $caller_contact, Issue : $standard_remark. Lat/Long:  $latlong. Thank you Ashok Leyland");

							$this->sendSMSFuction($mobile,$message,'1607100000000289246',"Creation SMS to customer",$complaint_number);
						}


					
				} catch (\Exception $hh1) {
					$hh1q =$hh1->getMessage();
						DB::table('creation_exception')->insert(['complaint_number'=>"$complaint_number",'type'=>"TSM SMS",'exception'=>"$hh1q"]);
				}
			/* TSM SMS */
			$now = date('Y-m-d H:i:s');
			/* Message Send */ 
			/* Email Send */
			
													/* Email Send Fields*/ 
			$msuOutletMail = array(7958,8222,28279,26879,3629,45525,23058,3618,35018,3614,90643,90642,90644,8249);
		
			/* ********************* Owner ALSE/ASM/RSM******************** */	
			$ownerCntact = DB::select("SELECT owner_contact_email FROM mstr_owner_contact where owner_id=$ownerId and owner_contact_email!='' ");
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
		   $owner_contact_email[] = $request->input('owner_contact_email')!=''?$request->input('owner_contact_email'):'KRYSALIS_Vandhana1@ashokleyland.com';
		   $owenerSqlData = DB::select("Select alse_mail,asm_mail from mstr_owner where id = $ownerId");
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
			$matrix = DB::select("Select id, level_name, to_role, cc_role, hours from mstr_escalations where level =1");
			$to_role = $matrix[0]->to_role;
			$cc_role = $matrix[0]->cc_role;
			//echo "Select email,name from users where role in ($to_role) and FIND_IN_SET($assign_to, dealer_id)";die;
			$toUsersSql =  DB::select("Select email,name from users where role in ($to_role) and FIND_IN_SET($assign_to, dealer_id) and email !='' and flag=1 ");
			$ccUserSql =   DB::select("Select email,name from users where role in ($cc_role) and FIND_IN_SET($assign_to, dealer_id) and email !='' and flag=1 ");
			$alseUserSql = DB::select("Select name,email,mobile from users where role =1 and FIND_IN_SET($assign_to, dealer_id) and email !='' and flag=1 ");
			$alseName = 'NA';
			$alseEmail='KRYSALIS_Vandhana1@ashokleyland.com';
			$alsephone='NA';
			if(sizeof($alseUserSql)>0){
				$alseName = $alseUserSql[0]->name;
				$alseUser = trim($alseUserSql[0]->email);
				$alsephone = trim($alseUserSql[0]->mobile);//Added by MB 5/5/21
				$alseUser = str_replace(":",",",$alseUser);
				$alseUser = str_replace(";",",",$alseUser);
				$alseUser = str_replace(" ",",",$alseUser);
				$alseEmail = $alseUser;

			}
			//dd("Select email,name from users where role in ($cc_role) and dealer_id in ($assign_to)");
			$toUserArr=$ccUserArr ='';
			if(sizeof($toUsersSql)>0){
				foreach($toUsersSql as $row){
					if($row->email!=''){
						$toUser = trim($row->email);
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
					if($row->email!=''){
						$ccUser = trim($row->email);
						$ccUser = str_replace(":",",",$ccUser);
						$ccUser = str_replace(";",",",$ccUser);
						$ccUser = str_replace(" ",",",$ccUser);
						$ccUserArr .= $ccUser.",";
						//$ccUserArr[] = $row->email.",";
					}
				}
				$ccUserArr = rtrim($ccUserArr,',');
				$ccUserArr = explode(",",$ccUserArr);
			}else{
				$ccUserArr = array("KRYSALIS_Vandhana1@ashokleyland.com");
			}
			
			
			$addMail = 'kuldeep.Sharma@ashokleyland.com';
			//$addMail = 'ashutosh.rawat@cogenteservices.in';
			if($ticket_type == 'Accident Ticket'){
				$ccUserArr [] = $addMail;
			}
			if(in_array(trim($code),$msuOutletMail)){
				$ccUserArr [] = 'Subashbabu.B@ashokleyland.com';
				$ccUserArr [] = 'Kry_venkatesh@ashokleyland.com';
			}
			if($engine_emmission_type=='BS6'){
				//$ccUserArr = array_merge($ccUserArr, $bsviEmailArr, $area_championEmailArr,$region_championEmailArr);
				$ccUserArr = array_merge($ccUserArr, $area_championEmailArr,$region_championEmailArr);
			}
			$ccUserArr = array_merge($ccUserArr,$ccOwnerEmail);
			$sbjct1 ="AL Elite Ticket Details-Fresh Mail $complaint_number | $reg_number1 - $remark_type";
			$sbjct2 ="AL Elite BSVI Ticket Details-Fresh Mail $complaint_number | $reg_number1 - $remark_type";
			// $sbjct1 ="AL SELECT Ticket Details-Fresh Mail $complaint_number | $reg_number1";
			// $sbjct2 ="AL SELECT BSVI Ticket Details-Fresh Mail $complaint_number | $reg_number1";
			$subject= ($engine_emmission_type=='BS6' && $engine_emmission_type!='')?$sbjct2:$sbjct1;
			//$subject="ELITE Support Ticket - $complaint_number ";
			$body = '<p>Dear Team, </p>
			<p>Please find the below mentioned Break Down details.</p>
			<p>Kindly update the Response, Restoration and Closure details by using Dealer Portal using the link..</p>
			<table border="1" style="font-family: sans-serif;">
				<tr>
					<td style="text-align: left;">Customer Name</td>
					<td style="text-align: left;">'.$owner_nameMSU.'</td>
				</tr>
				<tr>
					<td style=" text-align: left;">Ticket Number</td>
					<td style="text-align: left;"><a href="'.url('update-case',['id' =>$lastInsertedId]).'" >'.$complaint_number.'</a></td>
				</tr>
				<tr>
					<td style=" text-align: left;">Call log date and Time</td>
					<td style="text-align: left;">'.$now.'</td>
				</tr>
				<tr>
					<td style=" text-align: left;">Caller Name</td>
					<td style="text-align: left;">'.$caller_name.'</td>
				</tr>
				<tr>
					<td style=" text-align: left;">Caller Type</td>
					<td style="text-align: left;">'.$caller_type.'</td>
				</tr>
				<tr>
					<td style=" text-align: left;">Caller Cont Number</td>
					<td style="text-align: left;">'.$caller_contact.'</td>
				</tr>
				<tr>
					<td style=" text-align: left;">Registration Number</td>
					<td style="text-align: left;">'.$reg_number1.'</td>
				</tr>
				<tr>
					<td style=" text-align: left;">Breakdown Location</td>
					<td style="text-align: left;">'.$location.'</td>
				</tr>
				<tr>
					<td style=" text-align: left;">Latitude</td>
					<td style="text-align: left;">'.$lat.'</td>
				</tr>
				<tr>
					<td style=" text-align: left;">Longitude</td>
					<td style="text-align: left;">'.$long.'</td>
				</tr>
				<tr>
					<td style=" text-align: left;">Issue</td>
					<td style="text-align: left;">'.$standard_remark.'</td>
				</tr>
				<tr>
					<td style=" text-align: left;">Support Centre Code</td>
					<td style="text-align: left;">'.$code.'</td>
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
					<td style=" text-align: left;">Support City</td>
					<td style="text-align: left;">'.$cityName.'</td>
				</tr>
				<tr>
					<td style=" text-align: left;">Support State</td>
					<td style="text-align: left;">'.$stateName.'</td>
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
					<td style=" text-align: left;">TSM Name</td>
					<td style="text-align: left;">'.$alseName.'</td>
				</tr>
				<tr>
					<td style=" text-align: left;">TSM Contact Number</td>
					<td style="text-align: left;">'.$alsephone.'</td>
				</tr>
				<tr>
					<td style=" text-align: left;">Latest comments</td>
					<td style="text-align: left;">'.$assign_remarks.'</td>
				</tr>
				
			</table> 
			
			<p>Regards,</p>
			<p>Ashok Leyland Helpline</p>';
			
			$data=['body'=>$body];
			$toUserArr = array_filter($toUserArr);
			$ccUserArr = array_filter($ccUserArr);
			try {
			$toUserArrImplode = implode(",",$toUserArr) ;
			$ccUserArrImplode = implode(",",$ccUserArr) ;
			DB::select("INSERT INTO email_status (type, subject, body, toMail, ccMail) VALUES ('Email Send Fields', '$subject', '$body','$toUserArrImplode','$ccUserArrImplode')");
				
			Mail::send('assigned_email',["data"=>$data],function ($message) use ($toUserArr, $ccUserArr, $subject) {
				$message->to($toUserArr)->cc($ccUserArr)->bcc(['al.crmautomailers@cogenteservices.in'])->subject($subject);
				$message->from('elitesupport@ashokleyland.com');
			});
			
			
		} catch (\Exception $jj) {
			$excp = $jj->getMessage();
			//echo "DOne";
			//dd($jj->getMessage());
			DB::table('creation_exception')->insert(['complaint_number'=>"$complaint_number",'type'=>"Team Mail",'exception'=>"$excp"]);
		}
			/* *********End Email Send Fields********** */ 
			/* Customer Email Send Fields */
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
					}
				}
				$ccUserTeamArr = rtrim($ccUserTeamArr,',');
				$ccUserTeamArr = explode(",",$ccUserTeamArr);
			}else{
				$ccUserTeamArr = array("KRYSALIS_Vandhana1@ashokleyland.com");
			}
			if(in_array(trim($code),$msuOutletMail)){
				$ccOwnerEmail [] = 'Subashbabu.B@ashokleyland.com';
				$ccOwnerEmail [] = 'Kry_venkatesh@ashokleyland.com';
			}
			$ccOwnerEmail = array_merge($ccOwnerEmail,$ccUserTeamArr);
			
			/* *********************************************************************************************/
			$subject1="AL ELITE Support Ticket - $complaint_number | $reg_number1 ";
					$body1 = '<p>Dear Customer, </p>
					<p>Please find below mentioned the details for the ELITE Support ticket raised.</p>
					<table border="1" style="font-family: sans-serif;">
						<tr>
							<td style="text-align: left;">Customer Name</td>
							<td style="text-align: left;">'.$owner_nameMSU.'</td>
						</tr>
						<tr>
							<td style=" text-align: left;">Ticket Number</td>
							<td style="text-align: left;"><a href="'.url('update-case',['id' =>$lastInsertedId]).'" >'.$complaint_number.'</a></td>
						</tr>
						<tr>
							<td style=" text-align: left;">Call log date and Time</td>
							<td style="text-align: left;">'.$now.'</td>
						</tr>
						<tr>
							<td style=" text-align: left;">Caller Name</td>
							<td style="text-align: left;">'.$caller_name.'</td>
						</tr>
						<tr>
							<td style=" text-align: left;">Caller Type</td>
							<td style="text-align: left;">'.$caller_type.'</td>
						</tr>
						<tr>
							<td style=" text-align: left;">Caller Cont Number</td>
							<td style="text-align: left;">'.$caller_contact.'</td>
							
						</tr>
						<tr>
							<td style=" text-align: left;">Registration Number</td>
							<td style="text-align: left;">'.$reg_number1.'</td>
						</tr>
						<tr>
							<td style=" text-align: left;">Breakdown Location</td>
							<td style="text-align: left;">'.$location.'</td>
						</tr>
						<tr>
							<td style=" text-align: left;">Latitude</td>
							<td style="text-align: left;">'.$lat.'</td>
						</tr>
						<tr>
							<td style=" text-align: left;">Longitude</td>
							<td style="text-align: left;">'.$long.'</td>
						</tr>
						<tr>
							<td style=" text-align: left;">Issue</td>
							<td style="text-align: left;">'.$standard_remark.'</td>
						</tr>
						<tr>
							<td style=" text-align: left;">Support Centre Code</td>
							<td style="text-align: left;">'.$code.'</td>
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
							<td style=" text-align: left;">Support City</td>
							<td style="text-align: left;">'.$cityName.'</td>
						</tr>
						<tr>
							<td style=" text-align: left;">Support State</td>
							<td style="text-align: left;">'.$stateName.'</td>
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
							<td style=" text-align: left;">Estimated response time</td>
							<td style="text-align: left;">'.$estimated_response_time.'</td>
						</tr>
						
					</table>
					
				<p>Regards,</p>
					<p>Ashok Leyland Helpline</p>';
					$data=['body'=>$body1];
					$ownerContactEmail = array_filter($ownerContactEmail);
					$ccOwnerEmail = array_filter($ccOwnerEmail);
					try {

					DB::select("INSERT INTO email_status (type, subject, body, toMail, ccMail) VALUES ('Email Send Customer', '$subject1', '$body1','".implode(",",$ownerContactEmail)."','".implode(",",$ccOwnerEmail)."')");

					Mail::send('assigned_email',["data"=>$data],function ($message) use ($ownerContactEmail, $ccOwnerEmail, $subject1) {
						$message->to($ownerContactEmail)->cc($ccOwnerEmail)->bcc(['al.crmautomailers@cogenteservices.in'])->subject($subject1);
						$message->from('elitesupport@ashokleyland.com');
					});
					
					
				} catch (\Exception $kk) {
					$kkq  = $kk->getMessage();
					DB::table('creation_exception')->insert(['complaint_number'=>"$complaint_number",'type'=>"Customer Mail",'exception'=>"$kkq"]);
				}
			/* Customer Email Send Fields */ 
			
				/* Cogent Followup */
			try {
				$caller_language  = $request->input('caller_language')!=''?$request->input('caller_language'):'Hindi';
				$dealerQuery = DB::table('mstr_dealer')->select('shift_time')->where('id',$assign_to)->take(1)->get();
				$shiftTime = $dealerQuery[0]->shift_time!=''?$dealerQuery[0]->shift_time:'No';
				DB::table('cogent_assign_followups')->insert(['complaint_number'=>"$complaint_number",'lang'=>"$caller_language",'shift_time'=>"$shiftTime"]);
			} catch (\Exception $as) {	
				$as = $as->getMessage();			
				DB::table('creation_exception')->insert(['complaint_number'=>"$complaint_number",'type'=>"Assign Followup",'exception'=>"$as"]);
			}
			/* Cogent Followup */
		$notification = array(
			'message' => $resultCase[0]->Message,
			'alert-type' => $resultCase[0]->Action
		);
	    return redirect()->route('ticket-creation')->with($notification);
		/* 	} catch (\Exception $ex) {
				$notification = array(
				'message' => $ex->getMessage().'Line: '.$ex->getLine(),
				'alert-type' => 'error'
				);
				return back()->with($notification);
		} */
	}

	public function randNumber(){
		$randNum = '"'.rand(1000000000,1000).'"';
		return $randNum;
	}
	public function getOwnerChange(Request $request){
		$id = $request->input('id');
		$query = DB::select("Select id, vehicle_id, owner_name, owner_mob, owner_landline, owner_cat, owner_company,alse_mail,asm_mail from mstr_owner where id=:id",["id"=>$id]);
		
		foreach($query as $row){
			echo $row->id.'~~'.$row->vehicle_id.'~~'.$row->owner_name.'~~'.$row->owner_mob.'~~'.$row->owner_landline.'~~'.$row->owner_cat.'~~'.$row->owner_company.'~~'.$row->alse_mail.'~~'.$row->asm_mail.'##';
		}
	}
	public function getOwnerContactChange(Request $request){
		$owenerContactId = $request->input('owenerContactId');
		//$vehicleId = $request->input('vehicleId');
		
		$query = DB::select("Select id,  contact_name, mob,owner_contact_email from mstr_owner_contact where id = :owenerContactId",["owenerContactId"=>$owenerContactId]);
		
		foreach($query as $row){
			echo $row->id.'~~'.$row->contact_name.'~~'.$row->mob.'~~'.$row->owner_contact_email.',';
		}
	}
	public function getOwnerChangeCaller(Request $request){
		$ownerId = $request->input('ownerId');
		$vehicleId = $request->input('vehicleId');
		$query = DB::select("Select id,caller_type, caller_name, caller_contact, caller_location, caller_landmark, vehicle_type, vehicle_movable, zone, state, city from mstr_caller where vehicle_id=$vehicleId and owner_id = $ownerId");
		if(sizeof($query)>0){
			foreach($query as $row){
				echo $row->id.'~~'.$row->caller_type.'~~'.$row->caller_name.'~~'.$row->caller_contact.'~~'.$row->caller_location.'~~'.$row->caller_landmark.'~~'.$row->vehicle_type.'~~'.$row->vehicle_movable.'~~'.$row->zone.'~~'.$row->state.'~~'.$row->city.',';
			}
		}else{
			echo 'no';
		}
		
	}

	public function getAssignDetailsManually(Request $request){
		
		$stateId= $request->input('state');
        $sqlState = DB::select("Select id,state from mstr_caller_state where id =$stateId");
		$stateName = strtolower($sqlState[0]->state);
		$dealerStateName = DB::select("SELECT id, concat(dealer_name, ' - ', SC_City_Name) as dealer_name FROM mstr_dealer  where flag=1");
		/* $dealerStateName = DB::select("SELECT id, concat(dealer_name, ' - ', SC_City_Name) as dealer_name FROM mstr_dealer where SC_State_Name like '%$stateName'"); */
        foreach ($dealerStateName as $row) {
            echo $row->id.'~~'.$row->dealer_name.',';
        }
	}
	public function getAssignDetailsManually_bckp(Request $request){
		$zone = $request->input('zone');
		$state = $request->input('state');
		$sql = DB::select("Select id , dealer_name from mstr_dealer where zone=$zone and state=$state ");
		foreach($sql as $row){
			$id = $row->id;
			$dealer_name = $row->dealer_name;
			echo $id.'~~'.$dealer_name.',';
		}
	}

	public function mailFunction(){ 
		try {
			$assign_remark_log = 'attend the vehicle and make vehicle onroad.,attend the vehicle and make vehicle onroad.,attend the vehicle and make vehicle onroad.,attend the vehicle and make vehicle onroad.,attend the vehicle and make vehicle onroad.,attend the vehicle and make vehicle onroad.,attend the vehicle and make vehicle onroad.,Fuel Pump Issue,Fuel Pump Issue,Fuel Pump Issue,Fuel Pump Issue';
			$assign_remark_date_log = '2021-05-03 16:24:53,2021-05-03 16:24:33,2021-05-03 16:23:48,2021-05-03 16:23:08,2021-05-03 16:23:02,2021-05-03 16:22:15,2021-05-03 16:22:07,2021-05-02 15:32:48,2021-05-02 14:30:16,2021-05-02 12:05:36,2021-05-01 18:45:10';
			$assign_remark_log =explode(",",$assign_remark_log);
			$assign_remark_date_log =explode(",",$assign_remark_date_log);
			$body = 'Test Message';
					
			$data=['body'=>$body];
			/* Mail::send('assigned_email', ["data"=>$data], function ($message) {
				$message->from('elitesupport@ashokleyland.com', 'ELITE Support');
				$message->sender('elitesupport@ashokleyland.com', 'ELITE Support');
				$message->to('', '');
				$message->cc('ashutosh.rawat@cogenteservices.in', 'Ashutosh');
				$message->subject('AL SELECT Ticket Details-Fresh Mail CB212200001 | AS01JC2388 ');
			}); */
			
			$subject = 'Test Mail';
			$toUserArr = 'ashutosh.rawat@cogenteservices.in'.','.'subashbabu.b@ashokleyland.com';
			$toUserArr = explode(',',$toUserArr);
			$ccUserArr = $toUserArr;
			Mail::send('assigned_email',["data"=>$data],function ($message) use ($toUserArr, $ccUserArr, $subject) {
				$message->to($toUserArr)->cc($ccUserArr)->bcc(['ashutosh.rawat@cogenteservices.in'])->subject($subject);
				//$message->from('ALHelpline@ashokleyland.com');
				$message->from('elitesupport@ashokleyland.com');
			});
			dd("sent");
			/* if(count(Mail::failures()) > 0)
			{
				echo"failed";
			} */
			}
		catch (\Exception $ex) {
			dd($ex->getMessage());
		}
	}

	public function smsCheck(){
		try {
		$mobile='919774774031';
		$pwd = 'YajfWt@Z';
		$uid = '2000194089';
		
		$message= urlencode('Dear AL ELITE Customer, Your complaint number dscsdcscs for vehicle sdcsdcsdc is assigned to csdcdscs, on WM number- 123 Thank You Ashok Leyland Elite.');
				//$message=urlencode($message);
				//echo "https://enterprise.smsgupshup.com/GatewayAPI/rest?method=SendMessage&send_to=$mobile&msg=$message&msg_type=TEXT&userid=$uid&auth_scheme=plain&password=$pwd&v=1.1&format=text";die; 

				$curl = curl_init();
				curl_setopt_array($curl, array(
				CURLOPT_URL => "https://enterprise.smsgupshup.com/GatewayAPI/rest?method=SendMessage&send_to=$mobile&msg=$message&msg_type=TEXT&userid=$uid&auth_scheme=plain&password=$pwd&v=1.1&format=text",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET"
				//   CURLOPT_POSTFIELDS => "{\"body\":{\"SRNumber\":\"19091809966\"}}",
				//CURLOPT_POSTFIELDS => "{\"body\":{\"SRNumber\":\"$UID\"}}",
				));
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				$response = curl_exec($curl);
				//$err = curl_error($curl);
				curl_close($curl);
				dd($response);
		} catch (\Exception $ex){
			dd($ex->getMessage());
	   }
	}
	public function downloadZip(){
		$zip = new ZipArchive;
		$file= public_path().'\css\ashokleyland.zip';
		/* $zipFileName = ''
		if($zip->open(public_path($fileName), ZipArchive::CREATE) === TRUE){
			$files = File::files(public_path('css\ashokleyland'));
		} */
		$headers = array(
			'content-Type' =>'application/octet-stream',
		);
		return response()->download($file,$headers);

	}

	public function copyPaste(){
		
		$data['contentData'] = DB::select("select content from copy_paste");
		
		return view('copy_paste',$data);
	}
	
	public function storeCopyPaste(Request $request){
		
		$content = $request->input('content');

		DB::table('copy_paste')->insert(['content'=>$content]);
		$notification = array(
			'message' => 'Inserted',
			'alert-type' => 'success'
		);
		return back()->with($notification);
	}
	public function uploadFile(){
		
		$data['contentData'] = DB::select("select file_name from upload_file");
		return view('upload_file',$data);
	}
	
	public function storeUploadFile(Request $request){
		
		$file = $request->file('attachment');
		$fileName = '';
		if($file !==null){
			$fileName = $file->getClientOriginalName();
			$file->move(public_path('file_upload'), $file->getClientOriginalName());
		}else{
			$fileName = '';
		}

		DB::table('upload_file')->insert(['file_name'=>$fileName]);
		$notification = array(
			'message' => 'Uploaded Succesfully',
			'alert-type' => 'success'
		);
		return back()->with($notification);
	}

	public function dealerSearchFunction(Request $request){
		$dealerId = $request->input('dealerId');
		$role = Auth::user()->role;
		if($role == 29 ||  $role == 30){
			$query = DB::Select("select d.dealer_name,d.sac_code,d.address,cs.state,u.name,u.mobile,r.role from mstr_dealer as d 
			left join mstr_state as cs on cs.id= d.state	left join users as u on find_in_set(d.id,u.dealer_id) and u.role in (1,6,7,76,78,79,80,82,83,84,113) left join mstr_role as r on r.id = u.role where d.id = $dealerId and u.flag=1 and d.flag=1  order by r.priority ASC");
		}else{
			$query = DB::Select("select d.dealer_name,d.sac_code,d.address,cs.state,u.name,u.mobile,r.role from mstr_dealer as d 
			left join mstr_state as cs on cs.id= d.state	left join users as u on find_in_set(d.id,u.dealer_id) and u.role in (1,6,7,76,78,79,82,83,84,113) left join mstr_role as r on r.id = u.role where d.id = $dealerId and u.flag=1 and d.flag=1  order by r.priority ASC");
		}
		$rowData ='';
		foreach($query as $row){
			$rowData .= $row->dealer_name.'~~'.$row->sac_code.'~~'.$row->address.'~~'.$row->state.'~~'.$row->name.'~~'.$row->mobile.'~~'.$row->role.'##';
		}
		echo $rowData;
	}
	public function checkRegistrationTicket(Request $request){
		$reg_number = $request->input('reg_number')!=''?$request->input('reg_number'):'000000NA';
		$chassis_number = $request->input('chassis_number')!=''?$request->input('chassis_number'):'000000NA';
		$engine_number = $request->input('engine_number')!=''?$request->input('engine_number'):'000000NA';
		$checkVehiclleTicket = DB::select("select count(*) as cnt from cases as c left join mstr_vehicle as v on c.vehicleId =v.id where (v.reg_number ='$reg_number' or v.chassis_number='$chassis_number' or v.engine_number='$engine_number') and c.remark_type !='Closed'");
		if($checkVehiclleTicket[0]->cnt > 0 ){
			echo "Yes";
		}else{
			echo "No";
		}
	}

	public function getAssignWorkManager(Request $request){
		$id = $request->input('id');
		//dd("vdvdf");
		/* $query = DB::select("Select id,phone from mstr_dealer where id = $id");
		$mob = $query[0]->phone;
		echo $mob; */
		$query = DB::select("select id,mobile,name from users where role in (76,113) and find_in_set(:id,dealer_id) and flag=1 order by cast(role as unsigned)",["id"=>$id]);
		if(sizeof($query)>0){			
			foreach($query as $row){
				$mob = $row->mobile;
				$name = $row->name;
				$id = $row->id;
				echo $mob.'~~'.$name.'~~'.$id.',';
			}
		}else{
			echo 'No';
		}
		
	}
	public function getAssignWorkManagerMobile(Request $request){
		$username = $request->input('username');
		/* $query = DB::select("Select id,phone from mstr_dealer where id = $id");
		$mob = $query[0]->phone;
		echo $mob; */
		$query = DB::select("select mobile from users where id=:username and flag=1",["username"=>$username]);
		if(sizeof($query)>0){			
			$mob = $query[0]->mobile;
			echo $mob;
		}else{
			echo 'No';
		}
		
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
	public function multiMobileTen($val){
		$val =$val!=''?$val:'0000000000';
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
			$newVal .= $row.',';
		}
		$newVal = rtrim($newVal,',');
        return $newVal;
    }
	public function testMSU(){
		/*  */
		$jsonDataArray = array('ticket_no'=>'test123','assign_to'=>'Test123','assign_work_manager'=>'Test123','assign_work_manager_mobile'=>'Test123','remark_type'=>'Test123','disposition'=>'Test123','agent_remark'=>'Test123','estimated_response_time'=>'Test123','actual_response_time'=>'Test123','tat_scheduled'=>'Test123','restoration_type'=>'Test123','acceptance'=>'Test123', 'followup_time'=>'Test123','feedback_rating'=>'Test123','feedback_desc'=>'Test123','assign_remarks'=>'Test123');
		$jsonData = json_encode($jsonDataArray);
		// dd($jsonData);
			$curl = curl_init();
			
			curl_setopt_array($curl, array(
				// Live API 
				// CURLOPT_URL => 'https://hz8tb0w051.execute-api.us-east-1.amazonaws.com/v1/reverse-data/ticket-update-cogent',
				// CURLOPT_URL => 'https://y1keqvs2ge.execute-api.us-east-1.amazonaws.com/v2/reverse-data/ticket-update-cogent',
				CURLOPT_URL => 'https://hz8tb0w051.execute-api.us-east-1.amazonaws.com/v1/reverse-data/ticket-update-cogent',
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
			$err = curl_error($curl);
			curl_close($curl);
			dd($response);
		/*  */

		$curl = curl_init();
 
		curl_setopt_array($curl, array(
		//CURLOPT_URL => 'https://hz8tb0w051.execute-api.us-east-1.amazonaws.com/v1/cog-create-ticket',
		// CURLOPT_URL => 'https://y1keqvs2ge.execute-api.us-east-1.amazonaws.com/v1/create-ticket',
		CURLOPT_URL => 'http://10.60.64.225/msu/cogCreateTicket',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS =>'{
		"ticket_no": "AV2122051192",
		"reg_no": "AP39TS1006",
		"chasi_no": "MB1H3VLD8MRLK3342",
		"eng_no": "MLEZ700083",
		"vehicle_model": "NE2825N/39 T TIP",
		"vehicle_seg": "TIPPER",
		"long_hal": "Long Haulage",
		"lattitude": "",
		"longitude": "",
		"purchase_date": "2021-02-05 00:00:00",
		"eng_emi_type": "BS6",
		"owner_name": "Megha Engineering And Infrastructures Ltd",
		"owner_mobile": "",
		"owner_lno": "",
		"owner_company": "Megha Engineering And Infrastructures Ltd",
		"owner_category": "Select Customer",
		"email": "",
		"asm_email": "",
		"caller_info": "Driver/Caller Info",
		"caller_type": "Owner",
		"caller_name": "Suraj",
		"caller_no": "9632887410",
		"vehicle_from": "Belagavi",
		"vehicle_to": "Chennai",
		"bd_location": "Attibele",
		"bd_lm": "Collefe",
		"bd_state": "Chennai",
		"bd_district": "Mandya",
		"bd_city": "Chennai",
		"bd_hway": "NH4",
		"vehicle_type": "NA",
		"vehicle_condition": "NA",
		"ticket_type": "Vehicle in workshop",
		"vehicle_problem": "NA",
		"dealer_name": "TVS - Madhavaram",
		"dealer_wmname": "Manivannan; Karthick",
		"dealer_wmmobileno": "9952064490-9003181049",
		"dealer_workstatus": "Awaiting AL Approval",
		"dealer_remark": "",
		"addBlueUse":"",
		"aggregate":"",
		"delaer code":"",
		"disposition":"",
		"agentRemarks":"",
		"acceptance":"",
		"standardRemark":"",
		"feedbackRating":"",
		"feedbackDiscription":""
		}
		',
		CURLOPT_HTTPHEADER => array(
			'Authorization: Basic bXN1Y29nZW50OmlZbVBSaDJubXA=',
			'Content-Type: application/json'
		),
		));
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		//
		dd($response);
		if($response){
			dd($response);
		}else{
			dd($err);
		}
		
	}

	public function removeSpecialChar($str) {
 
		// Using str_replace() function
		// to replace the word
		$str = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $str);
		$res = str_replace( array( '\'', '"',
		',' , ';', '<', '>' ), ' ', $str);
		$string = str_replace(array("\n", "\r","\t",'"'), '', $res);
		// Returning the result
		return $string;
	}
	public function getNightSpoc(Request $request){
		$id = $request->input('id');
		
		$query = DB::select("SELECT night_spoc_1_name,night_spoc_1_number,night_spoc_2_name,night_spoc_2_number FROM mstr_dealer where id=:id;",["id"=>$id]);
		if(sizeof($query)>0){
			$spoc1=$spoc2='';		
			foreach($query as $row){
				// $night_spoc_2_name = $row->night_spoc_2_name;
				$night_spoc_1_name = $row->night_spoc_1_name;
				$night_spoc_1_number = $row->night_spoc_1_number;
				$spoc1 .= $night_spoc_1_name.'~~'.$night_spoc_1_number.',';
				$night_spoc_2_name = $row->night_spoc_2_name;
				$night_spoc_2_number = $row->night_spoc_2_number;
				$spoc2 .= $night_spoc_2_name.'~~'.$night_spoc_2_number.',';
			}
			$spoc1 = rtrim($spoc1,',');
			$spoc2 = rtrim($spoc2,',');
			echo $spoc1.'&&'.$spoc2;
			
		}else{
			echo 'No';
		}
		
	}

	public function getVahan(Request $request){
		try {
			$regNo = $_GET['reg_number'];
			$curl = curl_init(); 
			curl_setopt_array($curl, array(
				
				// CURLOPT_URL => 'http://10.200.225.138:8080/QtyCPortal/rest/services/getToken', // UAT URL
				CURLOPT_URL => 'http://10.200.225.133:9010/CustomerPortal/rest/services/getToken', // Live
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => '{
					"AppName":"Cogent",
					"token":"LXIPrUtZXNdvST8n"
				}',
				CURLOPT_HTTPHEADER => array(
					'Content-Type: application/json'
				),
			));
			
			$response = curl_exec($curl);
			// echo "hello";
			// echo $response;die;
			
			$encData = json_decode($response, true);
			$encDataVar =  isset($encData['encryptedString'])?$encData['encryptedString']:'';
			if($encDataVar == ''){
				echo "tokenError";die;
			}
			// echo $encDataVar;die;
			curl_close($curl);
			
			$curl2 = curl_init();
			$num = array("id_number"=>$regNo);
			$numJson = json_encode($num);
			curl_setopt_array($curl2, array(
				// CURLOPT_URL => 'http://10.200.225.138:8080/QtyCPortal/rest/services/getVahanDetails',  // UAT URL
				CURLOPT_URL => 'http://10.200.225.133:9010/CustomerPortal/rest/services/getVahanDetails', // Live URL
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => $numJson,
				CURLOPT_HTTPHEADER => array(
					'AppName:Cogent',
					'Content-Type: application/json',
					'Authorization:' . $encDataVar
				),
			));
			
			$response2 = curl_exec($curl2);
			
			curl_close($curl2);
			// echo json_encode($response2);die;
			// echo '<pre>';
			if($response2 == ''){
				echo "tokenError";die;
			}
			
			$dt = json_decode($response2);
			$dt1 =json_decode($dt->message);
			$jsonData = json_encode($dt1->data, true);
			echo json_encode($dt1->data, true); 
		}catch (\Exception $aa){
			$aa  = $aa->getMessage();
			DB::table('creation_exception')->insert(['complaint_number'=>"Get Vahan",'type'=>"Get Vahan API",'exception'=>"$aa"]);
	   	}
	   	try{			
			/* $vehicle_chasi_number = substr($dt1->data->vehicle_chasi_number, 0, 3);			
			$vehicle_category = $dt1->data->vehicle_category;
			if($vehicle_chasi_number == 'MB1' && $vehicle_category == 'LGV'){
				
			}else if($vehicle_chasi_number == 'MB1' || $vehicle_chasi_number == 'ASHOK LEYLAND LTD'){ */
				DB::table('vahan_details')->insert(['registration_number'=>$regNo,'json_req'=>$jsonData]);
				die;
			/* } */
	   	}catch (\Exception $aabc){
			$aabc  = $aabc->getMessage();
			DB::table('creation_exception')->insert(['complaint_number'=>"Get Vahan",'type'=>"Get Vahan Insert",'exception'=>"$aabc"]);
   		}
	}

	public function dialertest(){
		$dataArr = array(
			"phonenumber"=> '9953199026',
			"phone2"=> '9717422339',
			"phone3"=> '9717422339',
			"phone4"=> '9717422339',
			"phone5"=> '9717422339',
			"phone6"=> '9717422339',
			"ticketnumber"=> '123456789',
			"registrationnumber"=> '123456789',
			"customerlanguage"=> 'Hindi',
			"dealername"=> 'Test Dealer',
			"skillname"=> "AL_Ticket_Creation",
			"status"=> "NEW"
		);
		
		$payload = array(
			"campname"=>"AL_Ticket_Creation",
			"qname"=>"AL_Ticket_Creation",
			"listname"=>"AL_Ticket_Creation",
			"data"=>[$dataArr]
		);
		// dd(json_encode($payload,true));
		$curlDialer = curl_init();				
		curl_setopt_array($curlDialer, array(
		CURLOPT_URL => 'https://lb.cogentlab.com:8449/admin/leadpush',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => json_encode($payload,true),
		CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json',
			'Authorization: Basic YXNob2tsZXlsYW5kLmNvbToyNTg1YzY3c0Y1ekpQRjZjbTc1dXZXTlE='
		),
		));
		curl_setopt($curlDialer, CURLOPT_SSL_VERIFYPEER, false);
		if (curl_errno($curlDialer)) {
			$error_msg = curl_error($curlDialer);
			dd($error_msg);
		}
		$responseDialer = curl_exec($curlDialer);
		curl_close($curlDialer);
		dd($responseDialer);
		
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
	/* *********************** CTI page *********************************************************/
		public function ticketsExport()
		{
			return Excel::download(new CtiExport, 'ticket"'.time().'".xlsx');
		}


		public function getTicket(Request $request)
		{
			try {
				if($request->ajax())
				{
					$tickets = CTITICKET::where(function($query){
						$query->whereNull('agent_remarks')->whereNull('agent_status')->where('flag',0);
					})->orderBy('id','ASC')->first();
				if(!empty($tickets))
					{
						$ticketLock = CTITICKET::where('ticket_number',$tickets->ticket_number)->where('id',$tickets->id)->update(['flag'=>1]); //ticketLocked
						return response()->json(['success'=>true, 'form' => view('ctiticket.agentform',compact('tickets'))->render()]);
					}
				return response()->json(['error'=>true, 'message' => 'New Ticket Does Not Exists !']);
				}
				return view('ctiticket.ticketpage');
			} catch (\Exception $ex) {
				return back()->with('message', 'Something Went Wrong !');
			}

		}

		public function ticketUpdate(Request $request)
		{
			try {

				$ticket = ['ticket_number' => $request->ticket_no, 'remarks' =>$request->remarks,
							'reason_of_non_acceptance' =>$request->reason_of_non_acceptance,
							'updated_by_name' => $request->updated_by_name, 'contact_number' => $request->contact_no,
							'role' => $request->role];
					if(CTITICKET::where('ticket_number',$request->ticket_no)->exists())
					{
							session()->flash('success', 'Ticket successfully updated.');
							$updated = CTITICKET::where('ticket_number',$request->ticket_no)->update($ticket);
					}else{
							session()->flash('success', 'Ticket successfully Created.');
							$insert = CTITICKET::insert($ticket);
					}
						return view('ctiticket.thankyou');
			} catch (\Exception $ex) {
				session()->flash('error', 'Something Went Wrong !.');
				return view('ctiticket.form',compact('tickets','ticket'));

			}
		}


		public function updatedByAgent(Request $request)
		{
			try {
				$tickets = CTITICKET::where('ticket_number', $request->ticket_no)->first();
				$ticket = ['agent_remarks' =>$request->agent_remarks,
				'agent_status' =>$request->agent_status,
				'updated_agent' => Auth::user()->employee_id,
				'agent_update_date' => Carbon::now()->format('Y-m-d H:i:s'),
				'ticket_number' => $tickets->ticket_number, 'remarks' =>$tickets->remarks,
				'reason_of_non_acceptance' =>$tickets->reason_of_non_acceptance,
				'updated_by_name' => $tickets->updated_by_name, 'contact_number' => $tickets->contact_number,
				'role' => $tickets->role];

				if(CTITICKET::where('ticket_number',$request->ticket_no)->exists())
					{
						$updated = CTITICKET::where('ticket_number',$request->ticket_no)->update($ticket);
						return response()->json(['success'=> true, 'message' => 'Ticket Updated Successfully !']);
					}
					return response()->json(['error'=> true, 'message' => 'Ticket Updated Failed !']);
				} catch (\Exception $ex) {
					return response()->json(['error'=> true, 'message' => 'something Went Wrong !']);
				}
		}

		public function getTicketByAgent($ticketId)
		{
			try{
				$ticket = $ticketId;
				if(CTITICKET::where('ticket_number',$ticket)->exists())
				{
					$tickets = CTITICKET::where('ticket_number', $ticket)->first();
					return view('ctiticket.agentform',compact('tickets','ticket'));
				}
				return view('ctiticket.agentform',compact('ticket'));
			} catch (\Exception $ex) {
				return back()->with('error', 'something went wrong !');
			}

		}
		public function createCTI($ticketId)
		{
			try {
				$ticket = $ticketId;
				if(CTITICKET::where('ticket_number',$ticket)->exists())
				{
					$tickets = CTITICKET::where('ticket_number', $ticket)->first();
					return view('ctiticket.thankyou',compact('tickets'));
				}
				return view('ctiticket.form',compact('ticket'));
				} catch (\Exception $ex) {
					return back()->with('error', 'something went wrong !');
				}
		}
		public function ticketList()
		{
			$lists = CTITICKET::all();
			return view('ctiticket.list',compact('lists'));
		}
	/* *********************** CTI page *********************************************************/
}
