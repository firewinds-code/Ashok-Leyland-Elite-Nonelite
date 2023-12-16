<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Session;
use Redirect;
use Mail;
use App\classes\ServerValidation;
use App\classes\AccessControl;

date_default_timezone_set('Asia/Kolkata');
class NewCaseController  extends Controller
{
    public function __construct(){
		DB::enableQueryLog();
		$this->middleware(function ($request, $next) {
	        $sesUserTypeId = Session::get('user_type_id');
			$sesRole = Session::get('role');				
			$accessObj = new AccessControl();				
			/*$accessData= $accessObj->dataAccessFetch($sesUserTypeId,$sesRole);			
			if($accessData[0]->menu_new_case =='No'){
				return redirect()->route('not-autherized');
			}*/
        	return $next($request);
    	});	
	}
	public function newCase(Request $request){
		$accessView ='';
		if(Session::get('sessionNewCase') =='No' || empty(Session::get('sessionNewCase'))){
		 	$accessView ='No';
		}else{
		 	$accessView='Yes';
		}
		if($accessView =='Yes' || strtolower(Session::get('role')) == 'super admin'){
			$data['centerData']= DB::table('mstr_contact_center_module')->select('id','mode_name')->get();
			$data['vehicleData']= DB::table('mstr_vehicle')->select('vehicle')->distinct()->get(['vehicle']);
			$data['region']= DB::table('mstr_state_cilty_zone')->select('zone')->distinct()->get(['zone']);
			$data['brandData']= DB::table('mstr_brand')->select('id','brand')->get();
			$data['stateData']= DB::table('mstr_state_cilty_zone')->distinct()->get(['state']);
			$data['complaintTypeData']= DB::table('mstr_complaint')->distinct()->get(['complaint_type']);
	       return view('newcase',$data);
	    }else{
		 	return redirect()->route('not-autherized');
		}
	}
	
	public function storeNewCase(Request $request){
		try
		{
		$serverValidation  = new ServerValidation();
		$case_type = $request->input('case_type');
		$center_module = $request->input('center_module');
		$State = $request->input('State');
		$City = $request->input('City');
		$Zone = $request->input('Zone');
		$Dealer = $request->input('Dealer');
		$product = $request->input('product');$brands = $request->input('brands');
		$customercode = $request->input('customercode');
		$customerorg = $request->input('customerorg');$contactperson = $request->input('contactperson');
		$phonenumbers = $request->input('phonenumbers');$email = $request->input('email');
		$location = $request->input('location');
		$designation = $request->input('designation');
		$complaintcategory = $request->input('complaintcategory');
		$description = $request->input('description');
		$observations = $request->input('observations');$actionstaken = $request->input('actionstaken');
		$vehicle_registration= $request->input('vehicle_registration');
		$vehicle_model= $request->input('vehicle_model');
		$chassis_number= $request->input('chassis_number');
		$dop= $request->input('dop');
		$dos= $request->input('dos');
		if($case_type=='NA')
		{
				$notification = array('message' => 'Please enter Case type!','alert-type' => 'error');
	            return back()->with($notification);
		}
		if($center_module=='NA')
		{
				$notification = array('message' => 'Please enter Center Module!','alert-type' => 'error');
	            return back()->with($notification);
		}
		if($serverValidation->is_empty($State))
		{
				$notification = array('message' => 'Please enter State!','alert-type' => 'error');
	            return back()->with($notification);
		}
		if($serverValidation->is_empty($City))
		{
				$notification = array('message' => 'Please enter City!','alert-type' => 'error');
	            return back()->with($notification);
		}
		if($serverValidation->is_empty($Zone))
		{
				$notification = array('message' => 'Please enter Zone!','alert-type' => 'error');
	            return back()->with($notification);
		}
		if($serverValidation->is_empty($Dealer))
		{
				$notification = array('message' => 'Please enter Dealer!','alert-type' => 'error');
	            return back()->with($notification);
		}if($serverValidation->is_empty($product))
		{
				$notification = array('message' => 'Please enter Product!','alert-type' => 'error');
	            return back()->with($notification);
		}
		if($serverValidation->is_empty($brands))
		{
				$notification = array('message' => 'Please enter Brands!','alert-type' => 'error');
	            return back()->with($notification);
		}
		if($serverValidation->is_empty($phonenumbers))
		{
				$notification = array('message' => 'Please enter PhoneNo.!','alert-type' => 'error');
	            return back()->with($notification);
		}
		if($serverValidation->is_empty($email))
		{
				$notification = array('message' => 'Please enter E-mail!','alert-type' => 'error');
	            return back()->with($notification);
		}
		$sesName= Session::get('name');
		$sesUsertype= Session::get('usertype');
		
		$resultCase = DB::select("call insert_cases('".$case_type."','".$center_module."','".$State."','".$City."','".$Zone."','".$Dealer."','".$product."','".$brands."','".$customercode."','".$customerorg."','".$contactperson."','".$phonenumbers."','".$email."','".$location."','".$designation."','','".$complaintcategory."','".$description."','".$observations."','".$actionstaken."','".$sesName."','".$sesUsertype."','".$vehicle_registration."','".$vehicle_model."','".$chassis_number."','".$dop."','".$dos."')");
		$notification = array(
			'message' => $resultCase[0]->Message,
			'alert-type' => $resultCase[0]->Action
		);
	    return redirect()->route('new-case')->with($notification);
	    }
	    catch (\Exception $ex) 
	    {
			$notification = array(
	                'message' => $ex->getMessage(),
	                'alert-type' => 'error'
	            );
            return back()->with($notification);
        }
	}
	public function getccm()
	{
		$result=DB::select("SELECT distinct mode_name FROM mstr_contact_center_module order by mode_name;");
					foreach($result as $value)
					{
					Echo $str= $value->mode_name.',';
					}
	}
	public function getcomplainttype(Request $request)
	{
		$comp = $request->input('comp');
		$result=DB::select("SELECT distinct compalaint_sub_type  FROM mstr_complaint_type where compalaint_type='".$comp."' order by compalaint_sub_type;");
					foreach($result as $value)
					{
					Echo $str= $value->compalaint_sub_type.',';
					}
	}
	public function getDealer(Request $request)
	{
		$zone = $request->input('zone');
		$state = $request->input('state');
		$city = $request->input('city');
		$product = $request->input('product');
		$result=DB::select("call getDealer_and_Code('".$zone."','".$state."','".$city."','".$product."')");
		foreach($result as $value)
		{
		Echo $str= $value->dealer;
		}
	}
	public function getSubproduct(Request $request)
	{
		$zone = $request->input('zone');
		$state = $request->input('state');
		$city = $request->input('city');
		$product = $request->input('product');
		$result=DB::select("call getDealer_and_Code('".$zone."','".$state."','".$city."','".$product."')");
					foreach($result as $value)
					{
					Echo $str= $value->dealer;
					}
	}
	
}
