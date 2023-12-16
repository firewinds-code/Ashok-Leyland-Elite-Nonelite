<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Session;
use Redirect;
use Mail;
use App\classes\ServerValidation;

date_default_timezone_set('Asia/Kolkata');
class SettingsController extends Controller
{
    public function __construct(){
		DB::enableQueryLog();
				
	}
/* ----------------------brand---------------------------------  */	
	public function fileSettings(){
		try{
			if(empty(Session::get('email'))){
				return redirect('/');
			}else{	
				$data['rowData']= DB::table('filesettings')->select('id','function_type', 'size', 'file_format')->get();			
				return view('settings',$data);
			}
				
		}catch (\Exception $ex) {
			$notification = array(
	                'message' => $ex->getMessage(),
	                'alert-type' => 'error'
	            );
            return redirect()->route('cms')->with($notification);
        }
			
	}
	
	public function storeFileSettings(Request $request){
		try {
			$serverValidation  = new ServerValidation();
			$function_type = $request->input('function_type');
			$size = $request->input('size');
			$file_format = $request->input('file_format');
			$dataid = $request->input('dataid');
			if ($serverValidation->is_empty($function_type) || $serverValidation->is_empty($size) || $serverValidation->is_empty($file_format)) {
				$notification = array(
				'message' => 'Please enter fields',
				'alert-type' => 'error'
				);
				return back()->with($notification);
			} 
			else{
				if($dataid == ''){
					$rowData= DB::table('filesettings')->select('id','function_type', 'size', 'file_format')->where('function_type',$function_type)->count();
					if ($rowData == 0) {
						DB::table('filesettings')->insert(['function_type'=>$function_type, 'size'=>$size, 'file_format'=>$file_format]);
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
					return redirect()->route('file-settings')->with($notification);
				}else{
					/*$rowData= DB::table('filesettings')->select('id','function_type', 'size', 'file_format')->where('id','!=',$dataid)->where('function_type',$function_type)->count();*/
					
						$updated_at = date('Y-m-d H:i:s');
						DB::table('filesettings')->where('id', $dataid)->update(['function_type'=>$function_type, 'size'=>$size, 'file_format'=>$file_format,'updated_at'=>$updated_at]);
						$notification = array(
						'message' => 'Updated successfully',
						'alert-type' => 'success'
						);
					
					return redirect()->route('file-settings')->with($notification);
				}
			}
		} catch (\Exception $ex) {
			$notification = array(
	                'message' => $ex->getMessage(),
	                'alert-type' => 'error'
	            );
				return redirect()->route('file-settings')->with($notification);
        }
	}
		
	public function fileSettingDelete($id)
	{
		//DB::table('mstr_user_type')->where('id', $id)->delete();
		try{
			$delData = DB::select("call delete_with_one('filesettings','id','$id')");
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
            return redirect()->route('brand')->with($notification);
        }	
	}
/* ----------------------End brand---------------------------------  */	
}
