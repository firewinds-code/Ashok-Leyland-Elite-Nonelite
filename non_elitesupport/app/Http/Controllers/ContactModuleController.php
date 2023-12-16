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
class ContactModuleController extends Controller
{
    public function __construct(){
		DB::enableQueryLog();
				
	}
/* ----------------------brand---------------------------------  */	
	public function contactModule(){
		try{
			if(empty(Session::get('email'))){
				return redirect('/');
			}else{	
				$data['rowData']= DB::table('mstr_contact_center_module')->select('id','mode_name','flag')->orderBy('id','desc')->get();			
				return view('contact_module',$data);
			}
				
		}catch (\Exception $ex) {
			$notification = array(
	                'message' => $ex->getMessage(),
	                'alert-type' => 'error'
	            );
            return back()->with($notification);
        }
			
	}
	
	public function storeContactModule(Request $request){
		$serverValidation  = new ServerValidation();
		$modeName = $request->input('mode_name');
		
		if($serverValidation->is_empty($modeName)){
				$notification = array(
		                'message' => 'Please enter mode name',
		                'alert-type' => 'error'
	            	);
	            return back()->with($notification);
		}else{
			$rowData= DB::table('mstr_contact_center_module')->select('id','mode_name')->where('mode_name',$modeName)->count();
			
			if($rowData == 0){
				DB::table('mstr_contact_center_module')->insert(['mode_name'=>$modeName]);
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
			
	        return redirect()->route('contact-module')->with($notification);	
		}	
	}
	public function updateContactModule(Request $request){
		$serverValidation  = new ServerValidation();
		$modeName = $request->input('mode_name');		
		$flag = $request->input('flag');		
		$id = $request->input('dataid');
		
		if($serverValidation->is_empty($modeName)){
				$notification = array(
		                'message' => 'Please enter mode name',
		                'alert-type' => 'error'
	            	);
	            return back()->with($notification);
		}else{
			$rowData= DB::table('mstr_contact_center_module')->select('id','mode_name')->where('mode_name',$modeName)->where('flag',$flag)->count();			
			if($rowData == 0){
				$updated_at = date('Y-m-d H:i:s');
				DB::table('mstr_contact_center_module')->where('id', $id)->update(['mode_name' => $modeName,'flag' => $flag,'updated_at'=>$updated_at]);
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
			return redirect()->route('contact-module')->with($notification);
		}	
	}	
	
	public function contactModuleDelete($id){
		//DB::table('mstr_user_type')->where('id', $id)->delete();
		$delData = DB::select("call delete_with_one('mstr_contact_center_module','id','$id')");
		$notification = array(
	        'message' => $delData[0]->Message,
	        'alert-type' => $delData[0]->Action
		);
	            return back()->with($notification);
	}
/* ----------------------End brand---------------------------------  */	
}
