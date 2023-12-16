<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Session;
use Redirect;
use Mail;
use App\classes\ServerValidation;

date_default_timezone_set('Asia/Kolkata');
class CustomerMasterController extends Controller
{
    public function __construct(){
		DB::enableQueryLog();
				
	}
	/* ----------------------complaint-type---------------------------------  */	
public function CustomerMaster(){
		try{
			
				$data['product_details']= DB::table('mstr_vehicle')->select('id','vehicle')->where('flag','1')->get();							
				$data['region_details']= DB::table('mstr_region')->select('id','region')->get();			
				$data['city_details']= DB::table('mstr_city')->select('id','city')->orderby('city')->get();		
				$data['segment_details']= DB::table('product_segment')->select('id','segment')->get();		
				$data['mode_details']= DB::table('mstr_contact_center_module')->select('id','mode_name')->where('flag','1')->get();		
				//$data['user_details']= DB::table('users')->select('id','name')->where('user_type_id','1')->where('flag','1')->get();
				$data['user_details']=  DB::select("SELECT id,name FROM users where assign_complaint='1' and role in (select id from mstr_role where FIND_IN_SET('1',complaint_type))");						
				$data['rowData']= DB::table('mstr_customer as c')->select('c.id','c.customerID as customerID','c.customerOrg as customerOrg','c.product as productId','c.segment as segmentId','c.address as address','c.zone as zoneId','c.state as stateId','c.City as CityId','c.pincode as pincode','c.sales_account_manager as sales_account_manager','c.sales_POC_1 as sales_POC_1','c.sales_POC_2 as sales_POC_2','c.segment1 as segment1','c.segment2 as segment2','veh.vehicle as vehicle','seg.segment as segment','r.region as zone','st.state as state','ct.city as city')->leftjoin('mstr_vehicle as veh','c.product','=','veh.id')->leftjoin('product_segment as seg','c.segment','=','seg.id')->leftjoin('mstr_region as r','r.id','=','c.zone')->leftjoin('mstr_city as ct','ct.id','=','c.City')->leftjoin('mstr_state as st','st.id','=','c.state')->get();	
				return view('customer_master',$data);
			
				
		}catch (\Exception $ex) {
			$notification = array(
	                'message' => $ex->getMessage(),
	                'alert-type' => 'error'
	            );
				return redirect()->route('customer-master')->with($notification);
        }
			
	}
	
	public function storeCustomer(Request $request){
		try{
			$serverValidation  = new ServerValidation();
			$customerID = $request->input('customerID');
			$customerOrg = $request->input('customerOrg');
			$product = $request->input('vehicle');
			$segment = $request->input('segment');
			$address = $request->input('address');
			$zone = $request->input('zone');
			$state = $request->input('state');
			$City = $request->input('City');
			$pincode = $request->input('pincode');
			
			$sales_POC_1 = $request->input('sales_POC_1');
			$sales_POC_2 = $request->input('sales_POC_2');
			$segment1 = $request->input('segment1');
			$segment2 = $request->input('segment2');
			$segmentArray='';
			
			$dataid = $request->input('dataid');			
			if ($serverValidation->is_empty($customerID)) {
				$notification = array(
				'message' => 'Please enter customer ID',
			    'alert-type' => 'error'
		        );
		        return back()->with($notification);
			}
			if ($serverValidation->is_empty($customerOrg)) {
				$notification = array(
				'message' => 'Please enter customer organisation',
			    'alert-type' => 'error'
		        );
		        return back()->with($notification);
			}
			if ($product=="NA") {
				$notification = array(
				'message' => 'Please enter product',
			    'alert-type' => 'error'
		        );
		        return back()->with($notification);
			}
			if ($segment == "NA") {
				$notification = array(
				'message' => 'Please enter segment',
			    'alert-type' => 'error'
		        );
		        return back()->with($notification);
			}
			if ($serverValidation->is_empty($address)) {
				$notification = array(
				'message' => 'Please enter address',
			    'alert-type' => 'error'
		        );
		        return back()->with($notification);
			}
			if ($zone=="NA") {
				$notification = array(
				'message' => 'Please enter zone',
			    'alert-type' => 'error'
		        );
		        return back()->with($notification);
			}
			if ($serverValidation->is_empty($state)) {
				$notification = array(
				'message' => 'Please enter State',
			    'alert-type' => 'error'
		        );
		        return back()->with($notification);
			}
			if ($serverValidation->is_empty($City)) {
				$notification = array(
				'message' => 'Please enter City',
			    'alert-type' => 'error'
		        );
		        return back()->with($notification);
			}
			if ($serverValidation->is_empty($pincode)) {
				$notification = array(
				'message' => 'Please enter pincode',
			    'alert-type' => 'error'
		        );
		        return back()->with($notification);
			}
			if ($serverValidation->is_empty($sales_POC_1)) {
				$notification = array(
				'message' => 'Please enter sales POC 1',
			    'alert-type' => 'error'
		        );
		        return back()->with($notification);
			}
			/*if ($serverValidation->is_empty($sales_POC_2)) {
				$notification = array(
				'message' => 'Please enter sales POC 2',
			    'alert-type' => 'error'
		        );
		        return back()->with($notification);
			}*/
			if ($serverValidation->is_empty($segment1)) {
				$notification = array(
				'message' => 'Please enter segment 1',
			    'alert-type' => 'error'
		        );
		        return back()->with($notification);
			}
			/*if ($serverValidation->is_empty($segment2)) {
				$notification = array(
				'message' => 'Please enter segment 2',
			    'alert-type' => 'error'
		        );
		        return back()->with($notification);
			}*/
			if($segment !="NA"){
				foreach($segment as $rowsegment){$segmentArray.= $rowsegment.',';}				
			}
			$segmentMulti = rtrim($segmentArray,',');
			
			if ($dataid =='') {
				$rowData= DB::table('mstr_customer')->select('id')->where('customerID',$customerID )->where('customerOrg',$customerOrg )->where('product',$product)->where('segment',$segmentMulti )->where('address',$address )->where('zone',$zone)->where('state',$state)->where('City',$City)->where('pincode',$pincode)->where('sales_POC_1',$sales_POC_1)->where('sales_POC_2',$sales_POC_2)->where('segment1',$segment1)->where('segment2',$segment2)->count();
					if ($rowData == 0) {
						DB::table('mstr_customer')->insert(['customerID'=>$customerID,'customerOrg'=>$customerOrg,'product'=>$product,'segment'=>$segmentMulti,'address'=>$address,'zone'=>$zone,'state'=>$state,'City'=>$City,'pincode'=>$pincode,'sales_POC_1'=>$sales_POC_1,'sales_POC_2'=>$sales_POC_2,'segment1'=>$segment1,'segment2'=>$segment2]);
						
						$notification = array(
						'message' => 'Stored successfully',
						'alert-type' => 'success'
						);
					} 
					else {
						$notification = array(
						'message' => 'Duplicate Data',
						'alert-type' => 'error'
						);

					}
			}else{
					
				$rowData= DB::table('mstr_customer')->select('id')->where('customerID',$customerID )->where('customerOrg',$customerOrg )->where('product',$product)->where('product',$product )->where('segment',$segmentMulti )->where('address',$address )->where('zone',$zone)->where('state',$state)->where('City',$City)->where('pincode',$pincode)->where('sales_POC_1',$sales_POC_1)->where('sales_POC_2',$sales_POC_2)->where('segment1',$segment1)->where('segment2',$segment2)->count();
					$updated_at = date('Y-m-d H:i:s');
					if ($rowData == 0) {
						DB::table('mstr_customer')->where('id', $dataid)->update(['customerID'=>$customerID,'customerOrg'=>$customerOrg,'product'=>$product,'segment'=>$segmentMulti,'address'=>$address,'zone'=>$zone,'state'=>$state,'City'=>$City,'pincode'=>$pincode,'sales_POC_1'=>$sales_POC_1,'sales_POC_2'=>$sales_POC_2,'segment1'=>$segment1,'segment2'=>$segment2,'updated_at'=>$updated_at]);
						//DB::enableQueryLog();
						//$query = DB::getQueryLog();
						//dd($query);
						$notification = array(
							'message' => 'Updated successfully',
							'alert-type' => 'success'
						);

					} else {
						$notification = array(
							'message' => 'Duplicate Data',
							'alert-type' => 'error'
						);
					}
			}
				
			return redirect()->route('customer-master')->with($notification);	
				
		}catch (\Exception $ex) {
			$notification = array(
	                'message' => $ex->getMessage(),
	                'alert-type' => 'error'
	            );
				return redirect()->route('customer-master')->with($notification);
        }
	}

	
	public function customerDelete($id){		
		try{
			$delData = DB::select("call delete_with_one('mstr_customer','id','$id')");
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
				return redirect()->route('customer-master')->with($notification);
        }	
	}
	public function customerContact($id){		
		try{
			
			$data['brandData']= DB::table('mstr_brand')->select( 'id','brand')->distinct('brand')->orderBy('brand')->where('flag','1')->get();
			$data['product_details']= DB::table('mstr_vehicle')->select('id','vehicle')->where('flag','1')->get();	
			$data['city_details']= DB::table('mstr_city')->select('id','city')->orderby('city')->get();
			$data['complaint_details']= DB::table('mstr_complaint')->select('id','complaint_type')->orderby('complaint_type')->get();
			$data['region_details']= DB::table('mstr_region')->select('id','region')->orderby('region')->get();
			$data['scope_of_services_details']= DB::table('mstr_scope_of_services')->select('id','scope_of_services')->orderby('scope_of_services')->get();
			$data['support_type_details']= DB::table('mstr_support_type')->select('id','type')->orderby('type')->get();
			$data['dealer_details']= DB::table('mstr_dealer as d')->select('d.id','d.dealer_code','d.dealer_name','r.region')->leftjoin('mstr_region as r','r.id','d.zone')->get();			
			$masterCustomerName= DB::table('mstr_customer')->select('customerID','customerOrg')->where('id',$id)->get();
			$data['masterCustomerName'] = $masterCustomerName[0]->customerID.'_'. $masterCustomerName[0]->customerOrg;
			$data['customerId'] = $id;
			//DB::enableQueryLog();
			$data['rowData'] = DB::table('mstr_customer_contact as mcc')->select('mcc.id', 'mcc.customerId', 'mcc.custname', 'mcc.custrole', 'mcc.locate', 'mcc.support_type', 'mcc.scope_of_services', 'mcc.city', 'mcc.mobile1', 'mcc.mobile2', 'mcc.email', 'mcc.pri_sec', 'mcc.dealer_code_asoc', 'mcc.segment', 'mcc.vehicle', 'mcc.brand','c.city as cityname','mcc.region','mcc.complaint_cat','cmlnt.complaint_type','r.region as regionName')->leftjoin('mstr_city as c','c.id','mcc.city')->leftjoin('mstr_complaint as cmlnt','cmlnt.id','mcc.complaint_cat')->leftjoin('mstr_region as r','r.id','mcc.region')->where('mcc.customerId',$id)->get();
			//$query = DB::getQueryLog();
			//dd($query);
			return view('customer_contact',$data);
		}catch (\Exception $ex) {
			$notification = array(
	                'message' => $ex->getMessage(),
	                'alert-type' => 'error'
	            );
				return redirect()->route('customer-master')->with($notification);
        }	
	}
	public function storeCustomerContact(Request $request)
	{
		try {
			$serverValidation  = new ServerValidation();
			$customerId = $request->input('customerId');
			$custname = $request->input('custname');
			$custrole = $request->input('custrole');
			$locate = $request->input('locate');
			$support_type = $request->input('support_type');
			$scope_of_services = $request->input('scope_of_services');
			$city = $request->input('city');
			$mobile1 = $request->input('mobile1');
			$mobile2 = $request->input('mobile2');
			$email = $request->input('email');
			$pri_sec = $request->input('pri_sec');
			$dealer_code_asoc= $request->input('dealer_code_asoc');
			$segment = $request->input('segment');			
			$dataid = $request->input('dataid');
			$brand = $request->input('brand');
			$vehicle = $request->input('vehicle');
			$complaint_cat = $request->input('complaint_cat');
			$region = $request->input('region');
			$dealerArray=$segmentArray=$brandArray=$scopeOfServiceArray=$complaint_catArray='';
			if($dealer_code_asoc !='NA'){
				foreach($dealer_code_asoc as $rowDealer){ $dealerArray.=$rowDealer.',';}
			}
			$dealerCode = rtrim($dealerArray,',');
			if($segment !='NA'){
				foreach($segment as $rowSegment){ $segmentArray.=$rowSegment.',';}
			}
			$segmentArr = rtrim($segmentArray,',');
			if($brand !='NA'){
				foreach($brand as $rowbrand){ $brandArray.=$rowbrand.',';}
			}
			$brandArr = rtrim($brandArray,',');
			if($scope_of_services !='NA'){
				foreach($scope_of_services as $rowScopeOfService){ $scopeOfServiceArray.=$rowScopeOfService.',';}
			}
			$scopeOfServiceArr = rtrim($scopeOfServiceArray,',');
			if($complaint_cat !='NA'){
				foreach($complaint_cat as $row){ $complaint_catArray.=$row.',';}
			}
			$complaint_catArr = rtrim($complaint_catArray,',');
			if ($serverValidation->is_empty($custname)) {
				$notification = array(
				'message' => 'Please enter customer name',
				'alert-type' => 'error'
				);
				return back()->with($notification);
			}
			if ($custrole=="NA") {
				$notification = array(
				'message' => 'Please enter role',
				'alert-type' => 'error'
				);
				return back()->with($notification);
			}
			
			if ($serverValidation->is_empty($locate)) {
				$notification = array(
				'message' => 'Please enter locate',
				'alert-type' => 'error'
				);
				return back()->with($notification);
			}
			
			if ($scopeOfServiceArr ===NULL) {
				$notification = array(
				'message' => 'Please enter scope of services',
				'alert-type' => 'error'
				);
				return back()->with($notification);
			}
			$mob2 = $mobile2!=''?$mobile2:'na';
			if ($dataid =='') {
				/*if($serverValidation->is_empty($mobile1) ){
				$notification = array(
	                'message' => 'Please enter mobile',
	                'alert-type' => 'error'
	        	);
		        return back()->with($notification);
				}else{
					$userMob = DB::select("SELECT mobile from users where  mobile = '$mobile1' or mobile = '$mob2'");
					$customerMob = DB::select("SELECT mobile1,mobile2 from mstr_customer_contact where mobile1 = '$mobile1' or mobile2 = '$mobile1' or mobile1 = '$mob2' or mobile2 = '$mob2'");
					if(sizeof($userMob)>0 || sizeof($customerMob)>0){
						$notification = array(
			                'message' => 'Mobile is duplicated',
			                'alert-type' => 'error'
			        	);
			        	return back()->with($notification);
					}
				}*/
					$rowData= DB::table('mstr_customer_contact')->select('id')->where('customerId',$customerId )->where('custname',$custname )->where('custrole',$custrole)->where('locate',$locate )->where('support_type',$support_type)->where('scope_of_services',$scopeOfServiceArr )->where('city',$city)->where('email',$email)->where('pri_sec',$pri_sec)->where('dealer_code_asoc',$dealerCode)->where('segment',$segmentArr)->where('brand',$brandArr)->where('vehicle',$vehicle)->where('complaint_cat',$complaint_catArr)->where('region',$region)->count();
				
					if ($rowData == 0) {
						DB::table('mstr_customer_contact')->insert(['customerId'=>$customerId,'custname'=>$custname,'custrole'=>$custrole,'locate'=>$locate,'support_type'=>$support_type,'scope_of_services'=>$scopeOfServiceArr,'city'=>$city,'mobile1'=>$mobile1,'mobile2'=>$mobile2,'email'=>$email,'pri_sec'=>$pri_sec,'dealer_code_asoc'=>$dealerCode,'segment'=>$segmentArr,'brand'=>$brandArr,'vehicle'=>$vehicle,'complaint_cat'=>$complaint_catArr,'region'=>$region]);
						$notification = array(
						'message' => 'Stored successfully',
						'alert-type' => 'success'
						);
					} else {
						$notification = array(
						'message' => 'Duplicate Data',
						'alert-type' => 'error'
						);

					}
				
				
				return back()->with($notification);
			} else {
				/*	$mob2 =$mobile2!=''?$mobile2:'';
				if($serverValidation->is_empty($mobile1) ){
				$notification = array(
	                'message' => 'Please enter mobile',
	                'alert-type' => 'error'
	        	);
		        return back()->with($notification);
				}else{
					$userMob = DB::select("SELECT mobile from users where  mobile = '$mobile1' or mobile = '$mob2'");
					$customerMob = DB::select("SELECT mobile1,mobile2 from mstr_customer_contact where id != '$dataid' and (mobile1 = '$mobile1' or mobile2 = '$mobile1' or mobile1 = '$mob2' or mobile2 = '$mob2')");
					if(sizeof($userMob)>0 || sizeof($customerMob)>0){
						$notification = array(
			                'message' => 'Mobile is duplicated',
			                'alert-type' => 'error'
			        	);
			        	return back()->with($notification);
					}
				}*/
				
				$rowData= DB::table('mstr_customer_contact')->select('id')->where('customerId',$customerId )->where('custname',$custname )->where('custrole',$custrole)->where('locate',$locate )->where('support_type',$support_type)->where('scope_of_services',$scopeOfServiceArr )->where('city',$city)->where('email',$email)->where('pri_sec',$pri_sec)->where('dealer_code_asoc',$dealerCode)->where('segment',$segmentArr)->where('brand',$brandArr)->where('vehicle',$vehicle)->where('complaint_cat',$complaint_catArr)->where('region',$region)->count();
				$updated_at = date('Y-m-d H:i:s');
				if ($rowData == 0) {
					DB::table('mstr_customer_contact')->where('id', $dataid)->update(['customerId'=>$customerId,'custname'=>$custname,'custrole'=>$custrole,'locate'=>$locate,'support_type'=>$support_type,'scope_of_services'=>$scopeOfServiceArr,'city'=>$city,'mobile1'=>$mobile1,'mobile2'=>$mobile2,'email'=>$email,'pri_sec'=>$pri_sec,'dealer_code_asoc'=>$dealerCode,'segment'=>$segmentArr,'brand'=>$brandArr,'vehicle'=>$vehicle,'complaint_cat'=>$complaint_catArr,'region'=>$region,'updated_at'=>$updated_at]);				
					$notification = array(
					'message' => 'Updated successfully',
					'alert-type' => 'success'
					);
				} else {
					$notification = array(
					'message' => 'Duplicate Data',
					'alert-type' => 'error'
					);
				}
			}

			return back()->with($notification);

		} catch (\Exception $ex) {
			$notification = array(
			'message' => $ex->getMessage(),
			'alert-type' => 'error'
			);
			return back()->with($notification);
		}
	}
	public function customerContactDelete($id)
	{
		try {
			$delData = DB::select("call delete_with_one('mstr_customer_contact','id','$id')");
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
			return back()->with($notification);
		}
	}
	public function getScopeService(Request $request){		
		$result=DB::select("select id,scope_of_services from mstr_scope_of_services");
		foreach($result as $value){
			Echo $str= $value->id.'~'.$value->scope_of_services.',';
		}
	}
	public function getSupportType(Request $request){		
		$result=DB::select("select id,type from mstr_support_type");
		foreach($result as $value){
			Echo $str= $value->id.'~'.$value->type.',';
		}
	}
	public function getComplaintCat(Request $request){		
		$result=DB::select("select id,complaint_type from mstr_complaint");
		foreach($result as $value){
			Echo $str= $value->id.'~'.$value->complaint_type.',';
		}
	}
	/* ----------------------End complaint-type---------------------------------  */	
}
