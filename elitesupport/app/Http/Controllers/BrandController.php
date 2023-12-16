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
class BrandController extends Controller
{
    public function __construct(){
		DB::enableQueryLog();
				
	}
/* ----------------------brand---------------------------------  */	
	public function brand(){
		try{
			
				$data['rowData']= DB::table('mstr_brand')->select('id','brand','flag')->orderBy('id','desc')->get();			
				return view('brand',$data);
			
				
		}catch (\Exception $ex) {
			$notification = array(
	                'message' => $ex->getMessage(),
	                'alert-type' => 'error'
	            );
            return redirect()->route('brand')->with($notification);
        }
			
	}
	
	public function storeBrand(Request $request){
		try{
			$serverValidation  = new ServerValidation();
			$brand = $request->input('brand');
			
			if($serverValidation->is_empty($brand)){
					$notification = array(
			                'message' => 'Please enter brand',
			                'alert-type' => 'error'
		            	);
		            return back()->with($notification);
			}else{
				$rowData= DB::table('mstr_brand')->select('id','brand','flag')->where('brand',$brand)->count();
					
				if($rowData == 0){
					DB::table('mstr_brand')->insert(['brand'=>$brand]);
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
				
		        return redirect()->route('brand')->with($notification);	
			}	
		}catch (\Exception $ex) {
			$notification = array(
	                'message' => $ex->getMessage(),
	                'alert-type' => 'error'
	            );
            return redirect()->route('brand')->with($notification);
        }
	}
	public function updateBrand(Request $request){
		try{
			$serverValidation  = new ServerValidation();
			$brand = $request->input('brand');
			$flag = $request->input('flag');
			$id = $request->input('dataid');
			
			if($serverValidation->is_empty($brand)){
					$notification = array(
			                'message' => 'Please enter brand',
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
				//DB::enableQueryLog();
				$rowData= DB::table('mstr_brand')->select('id')->where('brand',$brand)->where('flag',$flag)->count();				
				//$query = DB::getQueryLog();
				//dd($query);
				
				$updated_at = date('Y-m-d H:i:s');
				if($rowData == 0){
					DB::table('mstr_brand')->where('id', $id)->update(['brand' => $brand,'flag' => $flag,'updated_at'=>$updated_at]); 
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
				return redirect()->route('brand')->with($notification);	
			}
		}catch (\Exception $ex) {
			$notification = array(
	                'message' => $ex->getMessage(),
	                'alert-type' => 'error'
	            );
            return redirect()->route('brand')->with($notification);
        }	
	}	
	
	public function brandDelete($id){
		//DB::table('mstr_user_type')->where('id', $id)->delete();
		try{
			$delData = DB::select("call delete_with_one('mstr_brand','id','$id')");
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
