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


date_default_timezone_set('Asia/Kolkata');

class LocationController extends Controller
{
     public function __construct(){
		DB::enableQueryLog();
	}
	public function ticketCreation(){ 
		if(empty(Session::get('email'))){ 
			return redirect('/'); 
		} /* $data['vehicleModels'] = DB::select("Select id, vehicle_model, vehicle_segment, add_blue_use, engine_emmission_type from mstr_vehicle_models"); */ 
		$data['remark_type'] = DB::select("Select id, type from remark_type order by type ASC"); 
		$data['caller_state'] = DB::select("Select id, state from mstr_caller_state order by state ASC"); 
		$data['ownerData'] = DB::select("Select id, owner_name from mstr_owner order by owner_name ASC"); 
		$data['responseDelayReason'] = DB::select("Select id, reason from response_delay_reason order by reason ASC"); 
		$data['ownerContactData'] = DB::select("Select id, contact_name from mstr_owner_contact where contact_name!='' order by contact_name ASC"); 
		$data['region'] = DB::select("Select id,region from mstr_region"); 
		return view('search_location',$data); }
	public function ticketCreationCti(Request $request)
	{		
		$phone =  $request->phone;
		if($phone !=''){
			$data['vehicle_Data'] = DB::select("SELECT v.reg_number,v.chassis_number,v.engine_number FROM mstr_vehicle as v left join mstr_owner as o on v.ownerId = o.id left join mstr_caller as c on c.owner_id = o.id left join mstr_owner_contact as oc on oc.owner_id = o.id where (c.caller_contact='" . $request->phone . "' or o.owner_mob='" . $request->phone . "' or oc.mob='" . $request->phone . "')");
		}else{
			$data['vehicle_Data'] = 'No';
		}
		
		$data['region'] = DB::select("Select id,region from mstr_region");
		$data['caller_state'] = DB::select("Select id, state from mstr_caller_state order by state ASC"); 
		$data['ownerData'] = DB::select("Select id, owner_name from mstr_owner order by owner_name ASC");
		$data['responseDelayReason'] = DB::select("Select id, reason from response_delay_reason order by reason ASC"); 
		$data['ownerContactData'] = DB::select("Select id, contact_name from mstr_owner_contact where contact_name!='' order by contact_name ASC"); 
		$data['remark_type'] = DB::select("Select id, type from remark_type order by type ASC");
		return view('search_locationCti',$data);
	}
	public function getVehicleDetails(Request $request){
		try{
			$reg_number = $request->input('reg_number')!=''?$request->input('reg_number'):'0000NA';
			$chassis_number = $request->input('chassis_number')!=''?$request->input('chassis_number'):'0000NA';
			$engine_number = $request->input('engine_number')!=''?$request->input('engine_number'):'0000NA';			
			$query = DB::select("call findOwner('$reg_number','$chassis_number','$engine_number')");
			
			
			if($query[0]->cnt > 0){	
					
				echo $query[0]->vehicleId.'~~'.$query[0]->vehicle_model.'~~'.$query[0]->reg_number.'~~'.$query[0]->chassis_number.'~~'.$query[0]->engine_number.'~~'.$query[0]->vehicle_segment.'~~'.$query[0]->purchase_date.'~~'.$query[0]->add_blue_use.'~~'.$query[0]->vehicle_type.'~~'.$query[0]->engine_emmission_type.'~~'.$query[0]->ownerId.'~~'.$query[0]->owner_name.'~~'.$query[0]->owner_mob.'~~'.$query[0]->owner_landline.'~~'.$query[0]->owner_cat.'~~'.$query[0]->owner_company.'~~'.$query[0]->ownercontactid.'~~'.$query[0]->ownercontactmob.'~~'.''.'~~'.''.'~~'.''.'~~'.''.'~~'.''.'~~'.''.'~~'.''.'~~'.''.'~~'.''.'~~'.''.'~~'.''.'~~'.$query[0]->contact_name.'~~'.$query[0]->owner_contact_email.'~~'.$query[0]->alse_mail.'~~'.$query[0]->asm_mail;
			}else{
				echo 'no';
			}
				
			
		}catch (\Exception $ex) {
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
$message= urlencode('Dear AL Select Customer, Welcome to AL SELECT Support.
Please click '.$link.' to share your current location for our team to reach you.
Thank You
Ashok Leyland.');
/* https://enterprise.smsgupshup.com/GatewayAPI/rest?method=SendMessage&send_to=$mobile&msg=$link&msg_type=TEXT&userid=$uid&auth_scheme=plain&password=$pwd&v=1.1&format=text */
			
			//$url="http://site.ping4sms.com/api/smsapi?key=8513057134a1a165f5cdaafe785bc6cd&route=2&sender=EICHER&number=".$mob."&sms=".$message;
			/* $url="https://enterprise.smsgupshup.com/GatewayAPI/rest?method=SendMessage&send_to=".$mobile."&msg=".$message."&msg_type=TEXT&userid=".$uid."&auth_scheme=plain&password=".$pwd."&v=1.1&format=text";
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL,$url);
			//curl_setopt($ch,CURLOPT_POST, 0);
			curl_setopt($ch,CURLOPT_CUSTOMREQUEST, 'GET');
			curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
			curl_setopt($ch,CURLOPT_TIMEOUT, 20);
			$response = curl_exec($ch);
			// $res=str_replace('"','',$response);
			curl_close ($ch); 
			202.87.33.165
			*/
			
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
		echo $query[0]->latitude.'~~'.$query[0]->longitude ;
		
	}
	public function getNearestLatlong(Request $request){
		$lat = $request->input('lat');
		$long = $request->input('long');
		
		$return_arr = array();
		$sql = DB::select("SELECT id,latitude,longitude,dealer_name ,(3956 * 2 * ASIN(SQRT( POWER(SIN(( $lat - latitude) *  pi()/180 / 2), 2) +COS( $lat * pi()/180) * COS(latitude * pi()/180) * POWER(SIN(( $long - longitude) * pi()/180 / 2), 2) ))) as distance  
		from mstr_dealer having  distance <= 100 order by distance");
		foreach($sql as $row){
			$latitude = $row->latitude;
			$longitude = $row->longitude;
			$dealer_name = $row->dealer_name;
			$return_arr[] = array("latitude" => $latitude,
                    "longitude" => $longitude,
                    "dealer_name" => $dealer_name
				);
		}
		echo json_encode($return_arr);
		
	}
	public function getAssignDetails(Request $request){
		$lat = $request->input('lat');
		$long = $request->input('long');
		$sql = DB::select("SELECT id,latitude,longitude,dealer_name ,(3956 * 2 * ASIN(SQRT( POWER(SIN(( $lat - latitude) *  pi()/180 / 2), 2) +COS( $lat * pi()/180) * COS(latitude * pi()/180) * POWER(SIN(( $long - longitude) * pi()/180 / 2), 2) ))) as distance  
		from mstr_dealer having  distance <= 100 order by distance");
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
		$query = DB::select("select mobile from users where role = 76 and find_in_set($id,dealer_id) and flag=1");
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
	public function callerUpdate(Request $request)
	{
		try { 
			/* $vehicleId  = $request->input('vehicleId'); */
			$ownerId  = $request->input('ownerId');
			$callerId  = $request->input('callerId');
			$caller_type  = $request->input('caller_type');
			$caller_name  = $request->input('caller_name');
			$caller_contact  = $request->input('caller_contact');
			//$caller_location  = $request->input('caller_location');
			//$caller_landmark  = $request->input('caller_landmark');
			/* $vehicle_type  = $request->input('vehicle_type');
			$vehicle_movable  = $request->input('vehicle_movable'); */
			/* $zone  = $request->input('zone');
			$state  = $request->input('state');
			$city  = $request->input('city'); */
			/* $check = DB::select("Select id from mstr_caller where  owner_id = $ownerId");
			if(sizeof($check)<1){ */
				DB::select("Insert into mstr_caller (owner_id,caller_type,caller_name,caller_contact) values ($ownerId,'$caller_type','$caller_name','$caller_contact')");
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
		try{
			
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
			$lat = $request->input('latValue');
			$long = $request->input('longValue');
			
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
			$city = $request->input('city');
			$owner_name = $request->input('owner_name');  
			$owner_mob = $request->input('owner_mob');  
			$owner_company = $request->input('owner_company');  
			$contact_name = $request->input('contact_name');
			$vehicle_type = $request->input('vehicle_type');
			$vehicle_movable = $request->input('vehicle_movable');
			$engine_emmission_type = $request->input('engine_emmission_type');
			$currentUserMail = Session::get('email');
			//dd($actual_response_time);
			$supportContPersonSql = DB::select("Select mobile,name from users where role =76 and FIND_IN_SET($assign_to, dealer_id) and flag=1");
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
			$dealerBSSql = DB::select("Select bsvi,area_champion,region_champion from mstr_dealer where id = $assign_to");
			$bsvi = $dealerBSSql[0]->bsvi!=''?$dealerBSSql[0]->bsvi:'test@dispostable.com';
			$bsvi = str_replace(":",",",$bsvi);
			$bsvi = str_replace(";",",",$bsvi);
			$bsvi = str_replace(" ","",$bsvi);
			$bsviEmail = $bsvi;
			$area_champion = $dealerBSSql[0]->area_champion!=''?$dealerBSSql[0]->area_champion:'test@dispostable.com';
			$area_champion = str_replace(":",",",$area_champion);
			$area_champion = str_replace(";",",",$area_champion);
			$area_champion = str_replace(" ","",$area_champion);
			$area_championEmail = $area_champion;
			$region_champion = $dealerBSSql[0]->region_champion!=''?$dealerBSSql[0]->region_champion:'test@dispostable.com';
			$region_champion = str_replace(":",",",$region_champion);
			$region_champion = str_replace(";",",",$region_champion);
			$region_champion = str_replace(" ","",$region_champion);
			$region_championEmail = $region_champion;

			$bsviEmailArr = explode(",",$bsviEmail);
			$area_championEmailArr = explode(",",$area_championEmail);
			$region_championEmailArr = explode(",",$region_championEmail);
			/* *******BS6******* */
			$resultCase = DB::select("call Case_Creation('".$vehicleId."','".$ownerId."','".$owenerContactId."','".$city."','".$state."','".$callerId."','".$from_where."','".$to_where."','".$highway."','".$ticket_type."','".$aggregate."','".addslashes($vehicle_problem)."','".$currentUserMail."','".$assign_to."','".$dealer_mob_number."','".$dealer_alt_mob_number."','".$remark_type."','".$disposition."','".$agent_remark."','".addslashes($standard_remark)."','".addslashes($assign_remarks)."','".$estimated_response_time."','".$tat_scheduled."','".$acceptance."','".$lat."','".$long."','".$actual_response_time."','".$location."','".$landmark."','".$district."','".$vehicle_type."','".$vehicle_movable."','".$restoration_type."','".$response_delay_reason."')");
			$lastInsertedId =$resultCase[0]->lastInsertedId;
			$complaint_number =$resultCase[0]->complaint_number;
			$loginId = Session::get('employee_id');
		 	$loginName = Session::get('name');
			DB::table('remarks')->insert(['complaint_number'=>"$complaint_number",'remark_type'=>"$remark_type",'employee_name'=>"$loginName",'employee_id'=>"$currentUserMail",'dealer_mob_number'=>"$dealer_mob_number",'dealer_alt_mob_number'=>"$dealer_alt_mob_number",'assign_to'=>"$assign_to",'disposition'=>"$disposition",'agent_remark'=>"$agent_remark",'assign_remarks'=>"$assign_remarks",'estimated_response_time'=>"$estimated_response_time",'tat_scheduled'=>"$tat_scheduled",'acceptance'=>"$acceptance",'actual_response_time'=>"$actual_response_time"]);
			DB::table('escaltion_levels')->insert(['levels'=>"1",'complaint_number'=>"$complaint_number",'assign_to'=>"$assign_to",'Status'=>""]);
			DB::table('followups')->insert(['complaint_number' => "$complaint_number",'employee_name' => "$loginName", 'employee_id' => "$currentUserMail",'status'=>"$remark_type", 'dealer_mob_number' => "$dealer_mob_number", 'dealer_alt_mob_number' => "$dealer_alt_mob_number", 'assign_to' => "$assign_to", 'assign_type' => "",'vehicleId' => "$vehicleId", 'ownerId' => "$ownerId",'estimated_response_time' => "$estimated_response_time",'actual_response_time'=>"$actual_response_time",'followup_name'=>"$followup_name",'followups_number'=>"$followups_number" ]);
			/* Message Send */ 
			$workMangerMobSQL =  DB::select("Select name,mobile from users where role =76 and FIND_IN_SET($assign_to, dealer_id) and flag=1");
			$workMangerMob = sizeof($workMangerMobSQL)>0?$workMangerMobSQL[0]->mobile:'';
			$workMangername = sizeof($workMangerMobSQL)>0?$workMangerMobSQL[0]->name:'';
			$pwd = 'YajfWt@Z';
			$uid = '2000194089';
			$dealerDataSql = DB::select("Select d.dealer_name,d.phone,d.sac_code,s.state as stateName,c.city as cityName from mstr_dealer as d left join mstr_state as s on s.id = d.state left join mstr_city as c on c.id = d.city  where d.id = $assign_to");
			$code = $dealerDataSql[0]->sac_code;
			$dealerName = $dealerDataSql[0]->dealer_name;
			$cityName = $dealerDataSql[0]->cityName;
			$stateName = $dealerDataSql[0]->stateName;
			$phone = $dealerDataSql[0]->phone;
			$recQuery = DB::select("Select created_at from cases where  complaint_number = '$complaint_number'");
			$caseCreatedDate = $recQuery[0]->created_at;
			if($workMangerMob !=''){
				$mobile='91'.$owner_contact_mob.',91'.$workMangerMob;
$message= 'Dear Service team,
Customer complaint number '.$complaint_number.' recieved on '.$caseCreatedDate.' is assigned to '.$dealerName.'.  Company Name - '.$owner_company.', Caller name- '.$contact_name.', Number- '.$caller_contact.', RegNo - '.$reg_number1.', Location- '.$location.', Issue- '.$vehicle_problem.'
Thank You
Ashok Leyland.';
				$message=urlencode($message);
				
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
			}else{
				$wm = $workMangerMob!=''?$workMangerMob:'NA';		
				$mobile='91'.$owner_contact_mob.',91'.$dealer_mob_number;
				//dd($mobile);
				//$complaint_number=123;
				/* $message= 'Dear Service team, Customer complaint number '.$complaint_number.' for vehicle '.$reg_number1.' is assigned to '.$dealerName.': '.$dealer_mob_number.'. For Assistance Download- bit.ly/ALCARE_APP'; */
$message= 'Dear Customer,
Your Breakdown complaint number '.$complaint_number.' for vehicle-'.$reg_number1.' is assigned to-'.$dealerName.' WM number-'.$wm.'.
Thank You
Ashok Leyland.';

			
				$message=urlencode($message);
				
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
			}
			$now = date('Y-m-d H:i:s');
			/* Message Send */ 
			/* Email Send */
			
													/* Email Send Fields*/ 
		
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
				$ownerContactEmail = array("test@dispostable.com");
			} 
		   $owner_contact_email[] = $request->input('owner_contact_email')!=''?$request->input('owner_contact_email'):'test@dispostable.com';
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
			$alseEmail='test@dispostable.com';
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
				$toUserArr = array("test@dispostable.com");
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
				$ccUserArr = array("test@dispostable.com");
			}
			
			
			$addMail = 'kuldeep.Sharma@ashokleyland.com';
			//$addMail = 'ashutosh.rawat@cogenteservices.in';
			if($ticket_type == 'Accident Ticket'){
				$ccUserArr [] = $addMail;
			}
			if($engine_emmission_type=='BS6'){
				$ccUserArr = array_merge($ccUserArr, $bsviEmailArr, $area_championEmailArr,$region_championEmailArr);
			}
			$ccUserArr = array_merge($ccUserArr,$ccOwnerEmail);
			$sbjct1 ="AL SELECT Ticket Details-Fresh Mail $complaint_number | $reg_number1 ";
			$sbjct2 ="AL SELECT BSVI Ticket Details-Fresh Mail $complaint_number | $reg_number1 ";
			$subject= ($engine_emmission_type=='BS6' && $engine_emmission_type!='')?$sbjct2:$sbjct1;
			//$subject="SELECT Support Ticket - $complaint_number ";
			$body = '<p>Dear Team, </p>
			<p>Please find the below mentioned Break Down details.</p>
			<p>Kindly update the Response, Restoration and Closure details by using Dealer Portal using the link..</p>
			<table border="1" style="font-family: sans-serif;">
				<tr>
					<td style="text-align: left;">Customer Name</td>
					<td style="text-align: left;">'.$owner_company.'</td>
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
					<td style=" text-align: left;">Issue</td>
					<td style="text-align: left;">'.$vehicle_problem.'</td>
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
					<td style=" text-align: left;">ALSE Name</td>
					<td style="text-align: left;">'.$alseName.'</td>
				</tr>
				<tr>
					<td style=" text-align: left;">ALSE Contact Number</td>
					<td style="text-align: left;">'.$alsephone.'</td>
				</tr>
				<tr>
					<td style=" text-align: left;">Latest comments</td>
					<td style="text-align: left;">'.$assign_remarks.'</td>
				</tr>
			</table> 
			
			<p>Regards,</p>
			<p>SELECT Support Cell</p>';
			
			$data=['body'=>$body];
			/* if(sizeof($toUserArr)<=1  ){
				$toUserArr[] = 'test@dispostable.com';
				
			}
			if(sizeof($ccUserArr)<=1 ){
				$ccUserArr[] = 'test@dispostable.com';
			} */
			//dd($ccUserArr);
			//dd($toUserArr);
			//$toUserArr = sizeof($toUserArr)>0?$toUserArr:'test@dispostable.com';
			//$ccUserArr = $ccUserArr!=''?$ccUserArr:'test@dispostable.com';
			//dd($toUserArr);
			Mail::send('assigned_email',["data"=>$data],function ($message) use ($toUserArr, $ccUserArr, $subject) {
				$message->to($toUserArr)->cc($ccUserArr)->bcc(['ashutosh.rawat@cogenteservices.in','ravikiran.v@cogenteservices.com','siddegowda.s@cogenteservices.com','Panchakarla.SaiPra@ashokleyland.com'])->subject($subject);
				$message->from('select.support@ashokleyland.com');
			});
			$toUserArrImplode = implode(",",$toUserArr) ;
			$ccUserArrImplode = implode(",",$ccUserArr) ;
			DB::select("INSERT INTO email_status (type, subject, body, toMail, ccMail) VALUES ('Email Send Fields', '$subject', '$body','$toUserArrImplode','$ccUserArrImplode')");
			/* ********************************End Email Send Fields***************************************************** */ 
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
				$ccUserTeamArr = array("test@dispostable.com");
			}
			$ccOwnerEmail = array_merge($ccOwnerEmail,$ccUserTeamArr);
			
			/* *********************************************************************************************/
			$subject1="AL SELECT Support Ticket - $complaint_number | $reg_number1 ";
					$body1 = '<p>Dear Customer, </p>
					<p>Please find below mentioned the details for the SELECT Support ticket raised.</p>
					<table border="1" style="font-family: sans-serif;">
						<tr>
							<td style="text-align: left;">Customer Name</td>
							<td style="text-align: left;">'.$owner_company.'</td>
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
							<td style=" text-align: left;">Issue</td>
							<td style="text-align: left;">'.$vehicle_problem.'</td>
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
					<p>SELECT Support Cell</p>';
					$data=['body'=>$body1];
					
					Mail::send('assigned_email',["data"=>$data],function ($message) use ($ownerContactEmail, $ccOwnerEmail, $subject1) {
						$message->to($ownerContactEmail)->cc($ccOwnerEmail)->bcc(['ashutosh.rawat@cogenteservices.in','ravikiran.v@cogenteservices.com','siddegowda.s@cogenteservices.com','Panchakarla.SaiPra@ashokleyland.com'])->subject($subject1);
						$message->from('select.support@ashokleyland.com');
					});
					/* if( count(Mail::failures())>0){
						foreach(Mail::failures as $email_address){
							echo "$email_address <br />";							
						}
						dd("error");
					} */
					DB::select("INSERT INTO email_status (type, subject, body, toMail, ccMail) VALUES ('Email Send Customer', '$subject1', '$body1','".implode(",",$ownerContactEmail)."','".implode(",",$ccOwnerEmail)."')");
			/* Customer Email Send Fields */ 
			

		$notification = array(
			'message' => $resultCase[0]->Message,
			'alert-type' => $resultCase[0]->Action
		);
	    return redirect()->route('ticket-creation')->with($notification);
		} catch (\Exception $ex) {
			$notification = array(
			'message' => $ex->getMessage().'Line: '.$ex->getLine(),
			'alert-type' => 'error'
			);
			return back()->with($notification);
		}
	}


	public function getOwnerChange(Request $request){
		$id = $request->input('id');
		$query = DB::select("Select id, vehicle_id, owner_name, owner_mob, owner_landline, owner_cat, owner_company,alse_mail,asm_mail from mstr_owner where id=$id");
		
		foreach($query as $row){
			echo $row->id.'~~'.$row->vehicle_id.'~~'.$row->owner_name.'~~'.$row->owner_mob.'~~'.$row->owner_landline.'~~'.$row->owner_cat.'~~'.$row->owner_company.'~~'.$row->alse_mail.'~~'.$row->asm_mail.'##';
		}
	}
	public function getOwnerContactChange(Request $request){
		$owenerContactId = $request->input('owenerContactId');
		//$vehicleId = $request->input('vehicleId');
		
		$query = DB::select("Select id,  contact_name, mob,owner_contact_email from mstr_owner_contact where id = $owenerContactId");
		
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
				$message->from('select.support@ashokleyland.com', 'Select Support');
				$message->sender('select.support@ashokleyland.com', 'Select Support');
				$message->to('', '');
				$message->cc('ashutosh.rawat@cogenteservices.in', 'Ashutosh');
				$message->subject('AL SELECT Ticket Details-Fresh Mail CB212200001 | AS01JC2388 ');
			}); */
			$subject = 'Test Mail';
			$toUserArr = 'Service.jrdtrucks@gmail.com'.','.'rawat.ashutosh1@dispostable.com';
			$toUserArr = explode(',',$toUserArr);
			$ccUserArr = $toUserArr;
			Mail::send('assigned_email',["data"=>$data],function ($message) use ($toUserArr, $ccUserArr, $subject) {
				$message->to($toUserArr)->cc($ccUserArr)->bcc(['ashutosh.rawat@cogenteservices.in'])->subject($subject);
				$message->from('select.support@ashokleyland.com');
			});
			if(count(Mail::failures()) > 0)
			{
				echo"failed";
			}
			}
		catch (\Exception $ex) {
			dd($ex->getMessage());
		}
	}

	public function smsCheck(){
		try {
		$mobile='919818392956,919953199026';
		$pwd = 'YajfWt@Z';
		$uid = '2000194089';
		//echo 'Dear Service team, Customer complaint number 888888 recieved on 2021-04-27 is assigned to TestDealer.  Company Name - Testcompany, Caller name- Ashutosh, Number- 9999999999, RegNo - AXO123456, Location- TestLocation, Issue- Not start Thank You Ashok Leyland.';
		//echo '<br>';
		//echo 'Dear Service team, Breakdown Complaint number 666666, Response 2021-04-18, Restoration Time - 676776 Thank You Ashok Leyland.';
		//die;
		//Not Working
		/* $message= 'Dear Service team, Customer complaint number 888888 recieved on 2021-04-27 is assigned to TestDealer.  Company Name - Testcompany, Caller name- Ashutosh, Number- 9999999999, RegNo - AXO123456, Location- TestLocation, Issue- Not start Thank You Ashok Leyland.'; */
		// not working
		/* $message= 'Dear Service team, Breakdown Complaint number 666666, Response 2021-04-18, Restoration Time - 676776 Thank You Ashok Leyland.'; */
		// Working
		/* $message= 'Dear Customer,Your Breakdown complaint number 88888 for vehicle-78787878 is assigned to-hiugiygug WM number-9999999999. Thank You Ashok Leyland.'; */
		//working
		/* $message= 'Dear Al Select Customer,
		Breakdown complaint number 5656565 for vehicle ASD44433 is resolved and closed.
		Thank You
		Ashok Leyland.'; */

		/* $message= 'Dear Service team,
				Customer complaint number trsrtyry recieved on gsrtgtrs is assigned to tgsrgsrg.  Company Name - sgstrgstrg, Caller name- gtstgsg, Number- tgtsgtg, RegNo - tgtsgtsg, Location- stgstgtg, Issue- ddsgdg
				Thank You
				Ashok Leyland.';
	
				$message=urlencode($message); */
$message= urlencode('Dear AL Select Customer,
Welcome to AL SELECT Support. Please click kjsbvkjsrabksb to share your current location for our team to reach you.
Thank You
Ashok Leyland.');
				//$message=urlencode($message);
				//echo "https://enterprise.smsgupshup.com/GatewayAPI/rest?method=SendMessage&send_to=$mobile&msg=$message&msg_type=TEXT&userid=$uid&auth_scheme=plain&password=$pwd&v=1.1&format=text";die; 

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
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
				$response = curl_exec($curl);
				$err = curl_error($curl);
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
		$query = DB::Select("select d.dealer_name,d.sac_code,d.address,cs.state,u.name,u.mobile,r.role from mstr_dealer as d 
		left join mstr_caller_state as cs on cs.id= d.state	left join users as u on find_in_set(d.id,u.dealer_id) and u.role in (1,76,78,79,80,82,83,84) left join mstr_role as r on r.id = u.role where d.id = $dealerId and u.flag=1 and d.flag=1  order by r.role ASC");
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
}
