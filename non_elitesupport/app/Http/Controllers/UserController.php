<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Session;
use Redirect;
use Mail;
use Excel;
use Illuminate\Support\Facades\Hash;
use App\classes\ServerValidation;
use Auth;
use DataTables;

date_default_timezone_set('Asia/Kolkata');
class UserController extends Controller
{
    public function __construct(){
		DB::enableQueryLog();
		
				
	}
	
	public function forgetPassword(Request $request){
		try{
			return view('forget_password');
		}catch (\Exception $ex) {
			$notification = array(
	                'message' => $ex->getMessage(),
	                'alert-type' => 'error'
	            );
            return redirect()->route('/')->with($notification);
        }
	}
	
	public function forgetPwdSubmit(Request $request){
		try{
			$useremail = $request->input('useremail');
			//$query = DB::select("SELECT name FROM users WHERE email='$useremail'");
			$user = new\App\User;
			$query = $user::where('email', '=', $useremail)->count();
			if($query > 0 ){
				$password = rand(999, 99999);
				//DB::select("UPDATE users SET password='$password' WHERE email='$useremail'");
				$pwd = Hash::make($password);
				DB::table('users')->where('email', $useremail)->update(['password' => $pwd]);
				$subject='Forgot Password';
				$data=['password'=>$password];
				$mail = $useremail;
				Mail::send('forget_password_email',["data"=>$data],function ($message) use ($mail,$subject) {
					$message->to($mail)->subject($subject);
					$message->from('customercenter@vecv.in');
				});
				$notification = array(
	                'message' => 'Random password generated and send to your mail id',
	                'alert-type' => 'success'
	            );
	            return redirect()->route('forget-password')->with($notification);
			}else{
				$notification = array(
	                'message' => 'Invalid email id',
	                'alert-type' => 'error'
	            );
	       	     return redirect()->route('forget-password')->with($notification);
			}
		}catch (\Exception $ex) {
			$notification = array(
	                'message' => $ex->getMessage(),
	                'alert-type' => 'error'
	            );
            return redirect()->route('forget-password')->with($notification);
        }
	}
	
	/* public function dologin(Request $request){
		try{
			
			// $this->clearLoginAttempts($request);
			$serverValidation  = new ServerValidation();
			$userid = $request->input('email');			
			$password =  $request->input('password');	
			$pwd = $password;	
			//dd($userid);
			
			
			if($serverValidation->is_empty($userid)){
				$notification = array(
		                'message' => 'Please enter Email',
		                'alert-type' => 'error'
	            	);
					return redirect()->route('dashboard')->with($notification);	
			}
			
			if($serverValidation->is_empty($pwd)){
				$notification = array(
		                'message' => 'Please enter password',
		                'alert-type' => 'error'
	            	);
					return redirect()->route('dashboard')->with($notification);	
			}
			
			//$data = DB::select("SELECT id,name,email FROM users where email='".$userid."' and password='".$pwd."';");
			
			$data = DB::select("select * from  users A join 
 			(select employee_id,max(role) role from  users where flag =1
 			group by employee_id)B on  A.employee_id =B.employee_id and A.role =B.role
 			where  A.flag =1 and A.employee_id=:userid",["userid"=>"$userid"]);
			if(sizeof($data) == 0){
				$notification = array(
					'message' => "Login Id isn't exist",
					'alert-type' => 'error'
				);
				return back()->with($notification);
			}
			
		
			$db_password = $data[0]->password;
			$dbDealerId = $data[0]->dealer_id!=''?$data[0]->dealer_id:0;
			$db_dealer_id = explode(",",$dbDealerId);
			$dbDealerId = $db_dealer_id[0];
			$dealerquery = DB::select("Select dealer_name from mstr_dealer where id=:dbDealerId",["dbDealerId"=>$dbDealerId]);
			$dealerName = sizeof($dealerquery)>0?$dealerquery[0]->dealer_name:'Dealer';
			
			
		   	//if(!$data->isEmpty()){
		   	if(Hash::check($pwd,$db_password)){
				$request->session()->regenerate();
		  		$role='';				  
		   		foreach($data as $value){
					$id = $value->id;
					$name = $value->name;
					$email = $value->email;
					$employee_id = $value->employee_id;
					$state = $value->state;
					$city = $value->city;
					$zone = $value->zone;
					$user_type_id = $value->user_type_id;
					$role = $value->role;
					$mobile = $value->mobile;
					$dealer_id = $value->dealer_id;
					Session::put('sesUserId', $id);
					Session::put('name', $name);
					Session::put('email', $email);
					Session::put('user_type_id', $user_type_id);
					Session::put('role', $role);
					Session::put('employee_id', $employee_id);
					Session::put('state', $state);
					Session::put('city', $city);
					Session::put('zone', $zone);
					Session::put('mobile', $mobile);
					Session::put('dealer_id', $dealer_id);
					Session::put('dealerName', $dealerName);
		   		}
				  
		   		$accessData = DB::table("mstr_access")->select('id', 'usertype_id', 'userrole', 'menu_new_case', 'menu_update_case', 'menu_report', 'menu_dashboard', 're_opening', 'escalate_to', 'escalate_cc', 'approval', 'create_user', 'update_complaint', 'close_complaint', 'post_complaint_survey')->where('userrole',$role)->get();
		   		if(sizeof($accessData)>0){
		   			foreach($accessData as $accessRow){
		   				Session::put('sessionNewCase', $accessRow->menu_new_case);
		   				Session::put('sessionUpdateCase', $accessRow->menu_update_case);
		   				Session::put('sessionReport', $accessRow->menu_report);
		   				Session::put('sessionDashboard', $accessRow->menu_dashboard);
		   				Session::put('sessionEscalateTo', $accessRow->escalate_to);
		   				Session::put('sessionEscalateCC', $accessRow->escalate_cc);
		   				Session::put('sessionCreateUser', $accessRow->create_user);
		   				Session::put('sessionReOpening', $accessRow->re_opening);
		   				Session::put('sessionApproval', $accessRow->approval);
		   				Session::put('sessionUpdateComplaint', $accessRow->update_complaint);
		   				Session::put('sessionCloseComplaint', $accessRow->close_complaint);
		   				Session::put('sessionPostComplaintSurvey', $accessRow->post_complaint_survey);
		   				
		   			}
		   			
		   		}
		   		
		   		$notification = array(
	                'message' => 'Login Successful !',
	                'alert-type' => 'success'
            	);
            	//if(Session::get('role') == '29' || Session::get('role') == '30' || Session::get('role') =='87'){
            	if(Session::get('user_type_id') == '5'){
					return redirect()->route('ticket-creation')->with($notification);
				}else if( Session::get('user_type_id') == '3'){
					return redirect()->route('case-list')->with($notification);
				}else{
					return redirect()->route('dashboard')->with($notification);
				}
		   	}else{		   		   		
		   		$notification = array(
	                'message' => 'Error! User Id and Password is wrong!',
	                'alert-type' => 'error'
	            );
				return back()->with($notification);
		   	}
		}catch (\Exception $ex) {
			$notification = array(
	                'message' => $ex->getMessage().'Line: '.$ex->getLine(),
	                'alert-type' => 'error'
	            );
				return back()->with($notification);	
        }
	} */
	/* public function logout(){
		Session::flush();
		return redirect('/');	
	} */
/* ----------------------Users---------------------------------  */	
	public function users(Request $request){ 
		try{
			
			//DB::enableQueryLog();
			/* $data['rowData'] = DB::table('users')->select('users.id as id','users.employee_id as employee_id','users.assign_complaint as assign_complaint','users.dealer_id as dealer_id', 'users.name as name', 'users.last_name as last_name', 'users.email', 'users.user_type_id as user_Type_id', 'users.role as role', 'users.state as state', 'users.city as city', 'users.zone as zone', 'users.mobile as mobile', 'users.flag as flag','mstr_user_type.usertype as usertype','mstr_dealer.sac_code','mstr_dealer.dealer_name as dealer_name','mstr_role.role as role_name')->leftjoin('mstr_user_type','mstr_user_type.id','=','users.user_type_id')->leftjoin('mstr_dealer','mstr_dealer.id','=','users.dealer_id')->leftjoin('mstr_role','mstr_role.id','=','users.role')->where('users.flag','1')->paginate(20);
			
			$rowData = DB::table('users')->select('users.id as id','users.employee_id as employee_id','users.assign_complaint as assign_complaint','users.dealer_id as dealer_id', 'users.name as name', 'users.last_name as last_name', 'users.email', 'users.user_type_id as user_Type_id', 'users.role as role', 'users.state as state', 'users.city as city', 'users.zone as zone', 'users.mobile as mobile', 'users.flag as flag','mstr_user_type.usertype as usertype','mstr_dealer.sac_code','mstr_dealer.dealer_name as dealer_name','mstr_role.role as role_name')->leftjoin('mstr_user_type','mstr_user_type.id','=','users.user_type_id')->leftjoin('mstr_dealer','mstr_dealer.id','=','users.dealer_id')->leftjoin('mstr_role','mstr_role.id','=','users.role')->where('users.flag','1')->paginate(20); */
 			//DB::enableQueryLog();	
 			/* $sac_codeAr = '';
 			$rowCount = sizeof($rowData);
			
 			for($i=0;$i<$rowCount;$i++){	
				//$delId = rtrim($rowData[$i]->dealer_id,',');
				$delId = $rowData[$i]->dealer_id!=''?rtrim($rowData[$i]->dealer_id,','):'0';
 				$sac_code =DB::select("select GROUP_CONCAT(sac_code) as sac_code from mstr_dealer  where id in (".$delId.")");
 				$sac_codeAr .= $sac_code[0]->sac_code.'~~';
 			}
			 
 			$data['sac_code']= rtrim($sac_codeAr,'~~'); */
			 //dd($data['sac_code']);
			
			$data['dealerData'] = DB::table('mstr_dealer as d')->select('d.id','d.dealer_name')->orderBy('d.dealer_name','ASC')->get();
			
			$data['roleUserTypeData']=DB::table("mstr_user_type")->select('id','usertype')->where('mstr_user_type.flag','1')->get();
			$data['complaint_data']= DB::table('mstr_complaint')->select('id','complaint_type')->get();	
			$data['regionData']= DB::table('mstr_region')->select('id','region')->orderBy('region','ASC')->get();
			$data['userData']= DB::table('users')->select('id','name','employee_id')->get();
			//$data = DB::table('users')->select('id','name','email')->get();
			
			if ($request->ajax()) {
				$dataAjax = DB::table('users')->select('users.id as id','users.employee_id as employee_id','users.assign_complaint as assign_complaint','users.dealer_id as dealer_id', 'users.name as name', 'users.last_name as last_name', 'users.email', 'users.user_type_id as user_Type_id', 'users.role as role', 'users.state as state', 'users.city as city', 'users.zone as zone', 'users.mobile as mobile', 'users.flag as flag','mstr_user_type.usertype as usertype','mstr_dealer.sac_code','mstr_dealer.dealer_name as dealer_name','mstr_role.role as role_name')->leftjoin('mstr_user_type','mstr_user_type.id','=','users.user_type_id')->leftjoin('mstr_dealer','mstr_dealer.id','=','users.dealer_id')->leftjoin('mstr_role','mstr_role.id','=','users.role')->where('users.flag','1')->get();
				return Datatables::of($dataAjax)->addIndexColumn()
					->addColumn('action', function($row){
						//$btn = '<a href="javascript:void(0)" class="btn btn-primary btn-sm">View</a>';
						$btn ='<i class="fa fa-pencil-square-o" aria-hidden="true" id="'.$row->id.'" data-position="left" data-tooltip="Edit" onclick="javascript:return editUser(this);" style="cursor: pointer;"></i>';
						return $btn;
					})		
					->addColumn('flbtn', function($row){
						if($row->flag=='1'){
							$flbtn1 = '<label class="badge badge-success">Active</label>';
							return $flbtn1;
						}
						else{
							$flbtn1 = '<label class="badge badge-danger">Inactive</label>';
							return $flbtn1;
						}
						
					})
					->addColumn('sacbtn', function($row){
						$dealerId = $row->dealer_id!=''?rtrim($row->dealer_id,','):'0';
						$sac_code =DB::select("select sac_code from mstr_dealer  where id in ($dealerId)");
						$sacCode='';
						if(sizeof($sac_code)>0){
							foreach ($sac_code as  $row) {
								$sacCode .= $row->sac_code.',';
							}
							$sacCode = rtrim($sacCode,',');
							return $sacCode;
						}else{
							$sacCode='NA';
							return $sacCode;
						}
					})
					->rawColumns(['sacbtn','flbtn','action'])
					//->removeColumn(['id','last_name'])
					->make(true);
			}

			//dd($data);
	



			return view('users',$data);
			

		}catch (\Exception $ex) {
			$notification = array(
	                'message' => $ex->getMessage(),
	                'alert-type' => 'error'
	            );
            return redirect()->route('users')->with($notification);
		}
	}
	public function storeUsers(Request $request){
		//dd($request->input());
		
		$utid  =  $request->input('userTypeId');
		$utidForm  =  $request->input('usertype_id');
		$zoneArray=$stateArray=$cityArray=$brandArray='';
		$serverValidation  = new ServerValidation();
		$employee_id = $request->input('employee_id');
		$dealer_id = $dealer_id_arr = '';
		$dealer_id = $request->input('dealer_id');
		$dealer_id_arr = $request->input('dealer_id_arr');
		
		
		$name = $request->input('name');
		$last_name = $request->input('last_name');
		$email = $request->input('email');
		$password = $request->input('password');
		$password = Hash::make($password);
		$usertype_id = $utid !=''?$utid:$utidForm;
		
		$role = $request->input('role');
		$mobile =  $request->input('phonenumbers');
		$Zone = $request->input('zone');
		$State = $request->input('state');
		$City = $request->input('City');
		$Zone = $Zone !=''? $Zone:'NA';
		$State = $State !=''? $State:'NA';
		$City = $City !=''? $City:'NA';		
		$dataid = $request->input('dataid');
		$flag = $request->input('flag');
		$zoneArray=$stateArray=$cityArray=$dealerArray='';
		
		if($Zone != 'NA'){
			foreach($Zone as $rowZone){$zoneArray.= $rowZone.',';}
		}
		if($State != 'NA'){
			foreach($State as $rowState){$stateArray.= $rowState.',';}
		}
		if($City != 'NA'){
			foreach($City as $rowCity){$cityArray.= $rowCity.',';}
		}
		if(is_array($dealer_id_arr)){
			if(sizeof($dealer_id_arr)>0){
				foreach($dealer_id_arr as $row){$dealerArray .= $row.',';}
			}
		}else{
			foreach($dealer_id as $row){$dealerArray .= $row.',';}
		}
		$dealerArray =rtrim($dealerArray,',');
		$state =$city=$zone=$stateArr=$cityArr=$zoneArr=$dealIdArr="";
		if($usertype_id =='3'){
			$dealerQuery = DB::table('mstr_dealer')->select('zone','state','city')->where('id',$dealer_id)->get();
			$stateArr .= $dealerQuery[0]->state.',';
			$cityArr .= $dealerQuery[0]->city.',';
			$zoneArr .= $dealerQuery[0]->zone.',';	
		}
		$stateArr = rtrim($stateArr,',');
		$cityArr = rtrim($cityArr,',');
		$zoneArr = rtrim($zoneArr,',');
		$state = ($stateArray !='')?rtrim($stateArray,','):$stateArr;
		$city = ($cityArray !='')?rtrim($cityArray,','):$cityArr;
		$zone = ($zoneArray !='')?rtrim($zoneArray,','):$zoneArr;
		//$dealIdArr = ($dealerArray !='')?rtrim($dealerArray,','):$dealer_id;
		$delId = $dealerArray;
		//dd($delId);
		if($serverValidation->is_empty($employee_id)){
			$notification = array(
				'message' => 'Please enter employee Id',
				'alert-type' => 'error'
			);
			return back()->with($notification);
		}else if($serverValidation->is_empty($name) ){
			$notification = array(
				'message' => 'Please enter name',
				'alert-type' => 'error'
			);
			return back()->with($notification);
		}else if($serverValidation->is_empty($email) ){
			$notification = array(
				'message' => 'Please enter email',
				'alert-type' => 'error'
			);
			return back()->with($notification);
		}else if($usertype_id =="NA" ){
			$notification = array(
				'message' => 'Please enter usertype',
				'alert-type' => 'error'
			);
			return back()->with($notification);
		}else if($role == "NA"  ){
			$notification = array(
				'message' => 'Please enter role',
				'alert-type' => 'error'
			); 
			return back()->with($notification);
		}/* else if($serverValidation->is_email($email)){
			$notification = array(
                'message' => 'Please enter valid email',
                'alert-type' => 'error'
        	);
            return back()->with($notification);
		} */
		if($dataid == ''){
			//DB::enableQueryLog(); 
			$rowData= DB::table('users')->select('id','employee_id','email')->where('employee_id',$employee_id)->orWhere('email',$email)->count(); 
			//$query = DB::getQueryLog();
			//dd($query);
			//dd(sizeof($rowData));
			/* if($rowData == 0){
				
				if($serverValidation->is_empty($password) ){
					$notification = array(
			                'message' => 'Please enter password',
			                'alert-type' => 'error'
		            	);
		            return back()->with($notification);
				}
				if($serverValidation->is_empty($mobile) ){
					$notification = array(
		                'message' => 'Please enter mobile',
		                'alert-type' => 'error'
		        	);
			        return back()->with($notification);
				}else{
					$userMob = DB::table('users')->select('mobile')->where('mobile',$mobile)->count();
					$customerMob = DB::table('mstr_customer_contact')->select('mobile1','mobile2')->where('mobile1',$mobile)->orWhere('mobile2',$mobile)->count();
					if($userMob>0 || $customerMob>0){
						$notification = array(
			                'message' => 'Mobile is duplicated',
			                'alert-type' => 'error'
			        	);
			        	return back()->with($notification);
					}
				}
				 */
				
				DB::table('users')->insert(['employee_id'=>"$employee_id",'name'=>"$name",'last_name'=>"$last_name",'email'=>"$email",'password'=>"$password",'mobile'=>"$mobile",'user_type_id'=>"$usertype_id",'state'=>"$state",'role'=>"$role",'city'=>"$city" ,'zone'=>"$zone",'dealer_id'=>"$delId",'flag'=>"$flag"]); 
				$notification = array(
	                'message' => 'Stored successfully',
	                'alert-type' => 'success'
	            );
			/* }else{
				$notification = array(
	                'message' => 'Duplicate Data', 
	                'alert-type' => 'error'
	            );				
			} */
			 return redirect()->route('users')->with($notification);	
		}else{
			
			$updated_at = date('Y-m-d H:i:s');
			//DB::enableQueryLog();	
			
			//$query = DB::getQueryLog();
			//dd($query);
			//dd($rowData);
			
			$updated_at = date('Y-m-d H:i:s');
			DB::table('users')->where('id', $dataid)->update(['employee_id'=>"$employee_id",'name'=>"$name",'last_name'=>"$last_name",'email'=>"$email",'mobile'=>"$mobile",'state'=>"$state",'role'=>"$role",'city'=>"$city",'zone'=>"$zone",'dealer_id'=>"$delId",'flag'=>"$flag",'updated_at'=>"$updated_at"]);
			$notification = array(
				'message' => 'Updated successfully',
				'alert-type' => 'success'
			);
            return redirect()->route('users')->with($notification);
		}
	}
	public function usersDelete($id){
		//DB::table('users')->where('id', $id)->delete();
		if($id !='13'){
			$delData = DB::select("call delete_with_one('users','id','$id')");
			$notification = array(
				'message' => $delData[0]->Message,
				'alert-type' => $delData[0]->Action
			);
		}else{
			$notification = array(
				'message' => "Sorry",
				'alert-type' => "error"
			);
		}
		return back()->with($notification);
	}
	/* public function resetPassword(){
		try{
			
			
			return view('reset_password');
		}catch (\Exception $ex) {
			$notification = array(
	                'message' => $ex->getMessage(),
	                'alert-type' => 'error'
	            );
            return redirect()->route('/')->with($notification);
        }	
	}
	public function storeResetPassword(Request $request){
		try{
			
			
			$employee_id = Auth::user()->employee_id;
			$current_pwd = $request->input('current_password');
			$new_password = $request->input('new_password');
			$confirm_password = $request->input('confirm_password');
			$data = DB::table("users")->select('password')->where('employee_id','=',$employee_id )->get();
			$db_password = $data[0]->password;
			
			//$crnt_pwd = Hash::make($current_pwd);
			if(Hash::check($current_pwd,$db_password)){
				if($new_password == $confirm_password){
					$new_pwd = Hash::make($new_password);
					DB::table("users")->where('employee_id','=',$employee_id )->update(['password'=>$new_pwd]);
					$notification = array(
		                'message' => "Passowrd changed successfully",
		                'alert-type' => 'success'
		            );
	             	return back()->with($notification);
				}else{
				$notification = array(
	                'message' => "Passowrd doesn't match",
	                'alert-type' => 'error'
	            );
	             return back()->with($notification);
			}
			}else{
				$notification = array(
	                'message' => "Current Passowrd doesn't match",
	                'alert-type' => 'error'
	            );
	             return back()->with($notification);
			}
			
			return view('reset_password');
		}catch (\Exception $ex) {
			$notification = array(
	                'message' => $ex->getMessage(),
	                'alert-type' => 'error'
	            );
            return redirect()->route('/')->with($notification);
        }	
	} */
/* ----------------------End Users---------------------------------  */	
/* ----------------------UserType---------------------------------  */	
	public function userType(){
		try{
			
				$data['rowData']= DB::table('mstr_user_type')->select('id','usertype','flag')->orderBy('id','desc')->get();			
				return view('user_type',$data);
			
				
		}catch (\Exception $ex) {
			$notification = array(
	                'message' => $ex->getMessage(),
	                'alert-type' => 'error'
	            );
            return redirect()->route('user_type')->with($notification);
        }
			
	}
	public function storeUserType(Request $request){
		$serverValidation  = new ServerValidation();
		$userType = $request->input('user_type');
		if($serverValidation->is_empty($userType)){
				$notification = array(
		                'message' => 'Please enter UserType',
		                'alert-type' => 'error'
	            	);
	            return back()->with($notification);
		}else{
			$rowData= DB::table('mstr_user_type')->select('id','usertype','flag')->where('usertype',$userType)->count();
			
			if($rowData == 0){
				DB::table('mstr_user_type')->insert(['usertype'=>$userType]);
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
			
	        return redirect()->route('user-type')->with($notification);	
		}
		
				
				
	}
	public function updateUserType(Request $request){
		$serverValidation  = new ServerValidation();
		$userType = $request->input('user_type');
		$flag = $request->input('flag');
		$id = $request->input('dataid');
		
		if($serverValidation->is_empty($userType)){
				$notification = array(
		                'message' => 'Please enter UserType',
		                'alert-type' => 'error'
	            	);
	            return back()->with($notification);
		}else if($flag =="NA"){
			$notification = array(
		                'message' => 'Please select status',
		                'alert-type' => 'error'
	            	);
	            return back()->with($notification);
		}else{
			$rowData= DB::table('mstr_user_type')->select('id','usertype','flag')->where('usertype',$userType)->where('flag',$flag)->count();
			
			if($rowData == 0){
				$updated_at = date('Y-m-d H:i:s');
				DB::table('mstr_user_type')->where('id', $id)->update(['usertype' => $userType,'flag' => $flag,'updated_at'=>$updated_at]);
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
			
	        return redirect()->route('user-type')->with($notification);	
		}	
	}	
	public function userTypeDelete($id){
		//DB::table('mstr_user_type')->where('id', $id)->delete();
		$delData = DB::select("call delete_with_one('mstr_user_type','id','$id')");
		$notification = array(
		        'message' => $delData[0]->Message,
		        'alert-type' => $delData[0]->Action
		);
	            return back()->with($notification);
	}
/* ----------------------End UserType---------------------------------  */

/* ----------------------Role---------------------------------  */
	public function getRole(Request $request)
	{
		$id = $request->input('id');
		$result=DB::table('mstr_role')->select('id','role')->where('usertype_id',$id)->get();
		
			foreach ($result as $value) {
				Echo $str= $value->id.'~'.$value->role.',';
			}
	}	
	public function role(){
		try{
			
				//SELECT U.usertype,R.usertype_id,R.role,R.flag FROM volvo.mstr_user_type U JOIN volvo.mstr_role R on U.id=R.usertype_id	
				$data['userTypeData']= DB::table('mstr_user_type')->select('id', 'usertype')->get();			
				//$data['roleData']= DB::table('mstr_role')->select('id', 'usertype_id', 'role', 'flag')->get();			
				$data['roleData']= DB::table('mstr_role')->select('mstr_user_type.usertype as usertype','mstr_role.id as id','mstr_role.usertype_id as usertype_id', 'mstr_role.role as role', 'mstr_role.flag as flag', 'mstr_role.complaint_type as complaint_type')->join('mstr_user_type','mstr_user_type.id','=','mstr_role.usertype_id')->where('role','!=','Super Admin')->orderBy('mstr_role.id','DESC')->get();	
				$data['complaint_data']= DB::table('mstr_complaint')->select('id','complaint_type')->get();			
				return view('role',$data);
			
				
		}catch (\Exception $ex) {
			$notification = array(
	                'message' => $ex->getMessage(),
	                'alert-type' => 'error'
	            );
            return redirect()->route('role')->with($notification);
        }
			
	}
	public function storeRole(Request $request){
		$serverValidation  = new ServerValidation();
		$usertype_id = $request->input('usertype_id');
		$complaint_type = $request->input('complaint_type');
		$role = $request->input('role');	
		
		if ($usertype_id == 'NA' || $serverValidation->is_empty($role)) {
			$notification = array(
				'message' => 'Please enter fields',
				'alert-type' => 'error'
			);
		}else{
			DB::table('mstr_role')->insert(['usertype_id'=>$usertype_id, 'role'=>$role, 'complaint_type'=>$complaint_type]);
			$notification = array(
				'message' => 'Stored successfully',
				'alert-type' => 'success'
			);
		return redirect()->route('role')->with($notification);	
		}	
	}
	public function updateRole(Request $request){
		$serverValidation  = new ServerValidation();
		$usertype_id = $request->input('usertype_id');
		$role = $request->input('role');	
		$flag = $request->input('flag');		
		$id = $request->input('dataid');
		
		
		if ($usertype_id =='NA' || $serverValidation->is_empty($role)) {
			$notification = array(
	                'message' => 'Please enter fields',
	                'alert-type' => 'error'
            	);
           
		}else if($flag =="NA"){
			$notification = array(
		                'message' => 'Please select status',
		                'alert-type' => 'error'
	            	);
	           
		}else{
				$updated_at = date('Y-m-d H:i:s');
				DB::table('mstr_role')->where('id', $id)->update(['usertype_id' => $usertype_id, 'role' => $role, 'flag' => $flag,'updated_at'=>$updated_at]);
				$notification = array(
	                'message' => 'Updated successfully',
	                'alert-type' => 'success'
	            ); 
		}	
		 return redirect()->route('role')->with($notification);	
	}	
	public function roleDelete($id){
		//DB::table('mstr_user_type')->where('id', $id)->delete();
		
		$delData = DB::select("call delete_with_one('mstr_role','id','$id')");
		$notification = array(
		        'message' => $delData[0]->Message,
		        'alert-type' => $delData[0]->Action
		);
        return back()->with($notification);
	}
/* ----------------------End Role---------------------------------  */	
	public function getstate2(Request $request)
	{
		$p_region_id = $request->input('zone');
		$result=DB::select("call getstate2('".$p_region_id."')");
		foreach($result as $value){
			Echo $str= $value->state.',';
		}
	}
	public function getZone2(Request $request)
	{
		$result=DB::select("call getzone2()");
					foreach($result as $value)
					{
					Echo $str= $value->region.',';
					}
	}
	public function notAutherized(){
		return view('not_autherized');
	}
	public function searchUser(Request $request)
	{
		try {
			$str  = $request->input('str');
			$sql = DB::select("SELECT employee_id,name from users where  name like '%$str%'");
			if (sizeof($sql)>0) {
				$selected ='';
				foreach ($sql as $row) {					
					if ($str == $row->name) {
						$selected = 'selected';
					}
					echo '<option value="'.$row->name.'"  '.$selected.'>'.$row->name.' ('.$row->employee_id.')</option>';
				}
			}else{
				echo "<option value='NA'>No User Found</option>";
			}

		} catch (\Exception $ex) {
			$notification = array(
			'message' => $ex->getMessage(),
			'alert-type' => 'error'
			);
			return back()->with($notification);
		}
	}
	public function getReportingManagerName(Request $request)
	{
		try {
			$reporting_manager  = $request->input('reporting_manager');
			echo $reporting_manager;die;
			/*$sql = DB::select("SELECT name from users where  name = '$reporting_manager'");
			if (sizeof($sql)>0) {
				foreach ($sql as $row) {
					echo $row->name;
				}
			} else {
				echo "****";
			}*/

		} catch (\Exception $ex) {
			$notification = array(
			'message' => $ex->getMessage(),
			'alert-type' => 'error'
			);
			return back()->with($notification);
		}
	}
	public function checkMobileDuplicate(Request $request)
	{
		try {
			$mobile  = $request->input('mob');
			$userId  = $request->input('userId');
			$custContactId  = $request->input('custContactId');
			
			if($mobile !=''){
				if($userId =='' && $custContactId ==''){
					$userMob = DB::select("SELECT mobile from users where  mobile = '$mobile'");
					$customerMob = DB::select("SELECT mobile1,mobile2 from mstr_customer_contact where  mobile1 = '$mobile' or mobile2 = '$mobile'");
				}
				if($userId !='' && $custContactId ==''){
					$userMob = DB::select("SELECT mobile from users where id !='$userId' and mobile = '$mobile'");
					$customerMob = DB::select("SELECT mobile1,mobile2 from mstr_customer_contact where  mobile1 = '$mobile' or mobile2 = '$mobile'");
				}
				if($userId =='' && $custContactId !=''){
					$userMob = DB::select("SELECT mobile from users where id !='$userId' and mobile = '$mobile'");
					$customerMob = DB::select("SELECT mobile1,mobile2 from mstr_customer_contact where id!='$custContactId' and (mobile1 = '$mobile' or mobile2 = '$mobile')");
				}
				
				if(sizeof($userMob)>0 || sizeof($customerMob)>0){
					echo "Mobile is duplicated";
				}else{
					echo "not";
				}
			}else{
				echo "Please enter Mobile";
			}
			

		} catch (\Exception $ex) {
			$notification = array(
			'message' => $ex->getMessage(),
			'alert-type' => 'error'
			);
			return back()->with($notification);
		}
	}
	public function ajaxUserReportData(Request $request){
		$keyword = $request->input('keyword');
		$fieldtype = $request->input('fieldtype');
 		$searchkey ='';
 		if($fieldtype == 'code'){
 			$query = DB::select("select id from mstr_dealer where sac_code ='$keyword'");
 			if(sizeof($query) > 0){
 				$dealIdVal = $query[0]->id;
 				//$searchkey='->whereRaw("FIND_IN_SET($dealIdVal,users.dealer_id)")';
 				$searchkey="and FIND_IN_SET($dealIdVal,users.dealer_id)";
 			}
 		}elseif($fieldtype == 'Name'){
 			$searchkey="and users.name like '%$keyword%'";			
 		}elseif($fieldtype == 'EmployeeId'){
 			$searchkey="and users.employee_id like '%$keyword%'";			
 		}elseif($fieldtype == 'Role'){
 			$query = DB::select("select id from mstr_role where role =$keyword");
 			if(sizeof($query) > 0){
 				$roleIdVal = $query[0]->id;
 				$searchkey="and users.role =$roleIdVal ";
 			}
 		}elseif($fieldtype == 'Mobile'){
 			$searchkey="and users.mobile like '%$keyword%'";			
 		
 		}elseif($fieldtype == 'Email'){
 			$searchkey="and users.email like '%$keyword%'";			
 		}
 		
 		//DB::enableQueryLog();
 		$query = DB::select("select users.id as id, users.employee_id as employee_id, users.assign_complaint as assign_complaint, users.dealer_id as dealer_id, users.name as name, users.last_name as last_name, users.email as email, users.user_type_id as user_Type_id, users.role as role, users.state as state, users.city as city, users.zone as zone, users.mobile as mobile, users.flag as flag, mstr_user_type.usertype as usertype, mstr_dealer.dealer_name as dealer_name, mstr_role.role as role_name from users left join mstr_user_type on mstr_user_type.id = users.user_type_id left join mstr_dealer on mstr_dealer.id = users.dealer_id left join mstr_role on mstr_role.id = users.role where users.flag = 1 ".$searchkey." order by employee_id ASC limit 40 offset 0");
 		$rowData = DB::select("select users.id as id, users.employee_id as employee_id, users.assign_complaint as assign_complaint, users.dealer_id as dealer_id, users.name as name, users.last_name as last_name, users.email as email, users.user_type_id as user_Type_id, users.role as role, users.state as state, users.city as city, users.zone as zone, users.mobile as mobile, users.flag as flag, mstr_user_type.usertype as usertype, mstr_dealer.dealer_name as dealer_name, mstr_role.role as role_name from users left join mstr_user_type on mstr_user_type.id = users.user_type_id left join mstr_dealer on mstr_dealer.id = users.dealer_id left join mstr_role on mstr_role.id = users.role where users.flag = 1 ".$searchkey." order by employee_id ASC limit 40 offset 0");

		/* $query = DB::table('users')->select('users.id as id','users.employee_id as employee_id','users.assign_complaint as assign_complaint','users.dealer_id as dealer_id', 'users.name as name', 'users.last_name as last_name', 'users.email as email', 'users.user_type_id as user_Type_id', 'users.role as role', 'users.state as state', 'users.city as city', 'users.zone as zone', 'users.mobile as mobile','users.flag as flag','mstr_user_type.usertype as usertype','mstr_dealer.dealer_name as dealer_name','mstr_role.role as role_name')->leftjoin('mstr_user_type','mstr_user_type.id','=','users.user_type_id')->leftjoin('mstr_dealer','mstr_dealer.id','=','users.dealer_id')->leftjoin('mstr_role','mstr_role.id','=','users.role')->where('users.employee_id', 'like', '%' . $keyword . '%')->orWhere('users.name', 'like', '%' . $keyword . '%')->orWhere('users.email', 'like', '%' . $keyword . '%')->orWhere('mstr_user_type.usertype', 'like', '%' . $keyword . '%')->orWhere('users.mobile', 'like', '%' . $keyword . '%')->orWhere('mstr_role.role', 'like', '%' . $keyword . '%')->orWhere('mstr_dealer.sac_code', 'like', '%' . $keyword . '%')->where('users.flag','1')->paginate(20);

		$rowData = DB::table('users')->select('users.id as id','users.employee_id as employee_id','users.assign_complaint as assign_complaint','users.dealer_id as dealer_id', 'users.name as name', 'users.last_name as last_name', 'users.email as email', 'users.user_type_id as user_Type_id', 'users.role as role', 'users.state as state', 'users.city as city', 'users.zone as zone', 'users.mobile as mobile','users.flag as flag','mstr_user_type.usertype as usertype','mstr_dealer.dealer_name as dealer_name','mstr_role.role as role_name')->leftjoin('mstr_user_type','mstr_user_type.id','=','users.user_type_id')->leftjoin('mstr_dealer','mstr_dealer.id','=','users.dealer_id')->leftjoin('mstr_role','mstr_role.id','=','users.role')->where('users.employee_id', 'like', '%' . $keyword . '%')->orWhere('users.name', 'like', '%' . $keyword . '%')->orWhere('users.email', 'like', '%' . $keyword . '%')->orWhere('mstr_user_type.usertype', 'like', '%' . $keyword . '%')->orWhere('users.mobile', 'like', '%' . $keyword . '%')->orWhere('mstr_dealer.sac_code', 'like','%'.$keyword.'%')->where('users.flag','1')->paginate(20); */
 			//DB::enableQueryLog();	
		$sac_codeAr = '';
		$rowCount = sizeof($rowData);
		
		for($i=0;$i<$rowCount;$i++){
			//$delId = rtrim($rowData[$i]->dealer_id,',');				
			$delId = $rowData[$i]->dealer_id!=''?rtrim($rowData[$i]->dealer_id,','):'0';
			$sac_code =DB::select("select GROUP_CONCAT(sac_code) as sac_code from mstr_dealer  where id in (".$delId.")");
			$sac_codeAr .= $sac_code[0]->sac_code.'~~';
		}
		$sac_code= rtrim($sac_codeAr,'~~');
		$sac_codeArr = explode('~~',$sac_code);
	
		if(sizeof($query)>0){
			$data = '';
			$cnt = 0;
			$msg = "Do you want to delete?";
			foreach($query as $row) {
			 	$data .= '<tr>
				<td class="d-none">'.$row->id.'</td>	
				<td class="cls_last_name d-none">'.$row->last_name.'</td>	
				<td class="cls_username d-none">'.$row->name.'</td>	
				<td class="cls_usertype_id d-none">'.$row->user_Type_id.'</td>	
				
				<td class="cls_state d-none">'.$row->state.'</td>
				<td class="cls_zone d-none">'.$row->zone.'</td>
				<td class="cls_city d-none">'.$row->city.'</td>	
				<td  class="cls_roleName d-none">'.$row->role.'</td>
				<td class="cls_dealer_id d-none">'.$row->dealer_id.'</td>
				<td>
				<i class="fa fa-pencil-square-o" aria-hidden="true" id='.$row->id.' data-position="left" data-tooltip="Edit" onclick="javascript:return editUser(this);" style="cursor: pointer;"></i>
				<a href="'.route("users_delete.usersDelete", ["id" => $row->id]).'" onclick="return confirm('.$msg.')">
				<i class="fa fa-trash-o" aria-hidden="true" style="cursor: pointer;"></i></a>
				</td>
				
				<td class="cls_employee_id">'.$row->employee_id.'</td>
				<td class="cls_name">'.$row->name.'</td>
				<td class="cls_usertype d-none">'.$row->usertype.'</td>
				<td>'.$row->role_name.'</td>
				<td class="cls_mobile">'.$row->mobile.'</td>
				<td class="cls_email">'.$row->email.'</td>
				<td class="cls_flag">';
				if($row->flag=='1'){
					$data .= "<label class='badge badge-success'>Active</label>";
				}
				else{
					$data .= '<label class="badge badge-danger">Inactive</label>';
				}
				$data .='</td>';
				$dealerId = $row->dealer_id!=''?rtrim($row->dealer_id,','):'0';
				$sac_code =DB::select("select sac_code from mstr_dealer  where id in ($dealerId)");
				$sacCode='';
				if(sizeof($sac_code)>0){
					foreach ($sac_code as  $row) {
						$sacCode .= $row->sac_code.',';
					}
					$sacCode = rtrim($sacCode,',');
				}else{
					$sacCode='NA';
				}
				$data .='<td>'.$sacCode.'</td>
				</tr>';
				$cnt++;
			}
			
			echo $data; 
		  }    
	}
	public function exportUser(){
		$rowData= DB::table('users')->select('users.id as id','users.employee_id as employee_id','users.assign_complaint as assign_complaint','users.dealer_id as dealer_id', 'users.name as name', 'users.last_name as last_name', 'users.email', 'users.user_type_id as user_Type_id', 'users.role as role', 'users.state as state', 'users.city as city', 'users.zone as zone', 'users.mobile as mobile','users.flag as flag','mstr_user_type.usertype as usertype','mstr_dealer.dealer_name as dealer_name','mstr_role.role as role_name')->leftjoin('mstr_user_type','mstr_user_type.id','=','users.user_type_id')->leftjoin('mstr_dealer','mstr_dealer.id','=','users.dealer_id')->leftjoin('mstr_role','mstr_role.id','=','users.role')->where('users.flag','1')->get();
 		//DB::enableQueryLog();	
		$sac_codeAr = '';
		$rowCount = sizeof($rowData);
		
		for($i=0;$i<$rowCount;$i++){
			$delId = rtrim($rowData[$i]->dealer_id,',');
			$delId = !empty($delId)?$delId:0;
			
			$sac_code =DB::select("select GROUP_CONCAT(sac_code) as sac_code from mstr_dealer  where id in ($delId)");
			$sac_codeAr .= sizeof($sac_code)>0?$sac_code[0]->sac_code.'~~':'NA~~';
		}
		$sac_code= rtrim($sac_codeAr,'~~');
		$sac_codeArr = explode('~~',$sac_code);

		$user_array[] = array('Employee_Id', 'Name', 'User_Type', 'Role', 'Mobile', 'Email', 'Flag','SAC_Code');
		$cnt=0;
		foreach($rowData as $row){
			$user_array[] = array(
				'Employee_Id'  => $row->employee_id,
				'Name'  => $row->name,
				'User_Type'  => $row->usertype,
				'Role'  => $row->role_name,
				'Mobile'  => $row->mobile,
				'Email'  => $row->email,
				'Flag'  => $row->flag,
				'SAC_Code'  => $sac_codeArr[$cnt],

			);

			$cnt++;
		}
		//dd($user_array);
		Excel::create('User Data', function($excel) use ($user_array){
			$excel->setTitle('User Data');
			$excel->sheet('User Data', function($sheet) use ($user_array){
				$sheet->fromArray($user_array, null, 'A1', false, false);
			});
		})->download('xlsx');
	}
	public function changePassword(){
		try {
			
			
			$data['userData']= DB::select("Select employee_id from users where flag=1");			
			return view('change_password',$data);
		} catch (\Exception $ex) {
			$notification = array(
			'message' => $ex->getMessage(),
			'alert-type' => 'error'
			);
			return back()->with($notification);
		}
	}
	public function storeChangePassword(Request $request){
		try {
			$employee_id = $request->input('employee_id');
			$password = $request->input('password');
			
			$pwd = Hash::make($password);
			$checkUser = DB::select("Select * from users where employee_id='$employee_id' and flag=1");
			if(sizeof($checkUser)>0){
				DB::table('users')->where('employee_id', $employee_id)->update(['password' => $pwd]);
				
				$notification = array(
					'message' => "Password changed successfully",
					'alert-type' => 'success'
					);
				return back()->with($notification);
			}else{
				$notification = array(
					'message' => "There is no user",
					'alert-type' => 'error'
					);
				return back()->with($notification);
			}
		} catch (\Exception $ex) {
			$notification = array(
			'message' => $ex->getMessage(),
			'alert-type' => 'error'
			);
			return back()->with($notification);
		}
	}
}
