<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Session;
use Redirect;
use Mail;
use App\classes\ServerValidation;

date_default_timezone_set('Asia/Kolkata');
class ProductController extends Controller
{
    public function __construct(){
		DB::enableQueryLog();
				
	}
/* ----------------------brand---------------------------------  */	
	public function product(){
		try{
			if(empty(Session::get('email'))){
				return redirect('/');
			}else{					
				
				$data['rowData']= DB::table('mstr_vehicle as t1')->select('t1.id','t1.vehicle','t1.flag' ,'t2.id as segmentId','t2.segment','t3.id as modelId','t3.model' )->join('product_segment as t2','t1.id','=','t2.product_id')->join('product_model as t3','t1.id','=','t3.product_id')->get();			
				return view('product',$data);
			}
				
		}catch (\Exception $ex) {
			$notification = array(
	                'message' => $ex->getMessage(),
	                'alert-type' => 'error'
	            );
            return back()->with($notification);
        }
			
	}
	
	public function storeProduct(Request $request){
		try{
			$serverValidation  = new ServerValidation();
			$vehicle = $request->input('vehicle');
			$model = $request->input('vehicle_subtype');
			$segment = $request->input('segment');
			
			if($serverValidation->is_empty($vehicle) || $serverValidation->is_empty($model) || $serverValidation->is_empty($segment)){
				$notification = array(
	                'message' => 'Please enter related fields',
	                'alert-type' => 'error'
            	);
	            return back()->with($notification);
			}else{
				
				DB::table('mstr_vehicle')->insert(['vehicle'=>$vehicle]);
				$product_id = DB::getPdo()->lastInsertId();
				
				DB::table('product_segment')->insert(['product_id'=>$product_id,'segment'=>$segment]);
				$segment_id = DB::getPdo()->lastInsertId();
				
				DB::table('product_model')->insert(['product_id'=>$product_id,'segment_id'=>$segment_id,'model'=>$model]);
				$notification = array(
	                'message' => 'Stored successfully',
	                'alert-type' => 'success'
	            );
			}				
		    return redirect()->route('product')->with($notification);	
			
		}catch (\Exception $ex) {
			$notification = array(
	                'message' => $ex->getMessage(),
	                'alert-type' => 'error'
	            );
             return redirect()->route('vehicle')->with($notification);
        }
	}
	public function updateProduct(Request $request){
		try{
			$serverValidation  = new ServerValidation();
			$vehicle = $request->input('vehicle');
			$flag = $request->input('flag');
			$model = $request->input('model');
			$segment = $request->input('segment');	
			$modelId = $request->input('modelId');
			$segmentId = $request->input('segmentId');		
			$id = $request->input('dataid');
			
			if($serverValidation->is_empty($vehicle) || $serverValidation->is_empty($model) || $serverValidation->is_empty($segment)){
					$notification = array(
			                'message' => 'Please enter related fields',
			                'alert-type' => 'error'
		            	);
		         return redirect()->route('vehicle')->with($notification);
			}else{
				//$rowData= DB::table('mstr_vehicle')->select('id')->where('vehicle',$vehicle)->where('vehicle_subtype',$vehicle_subtype)->count();	
				$updated_at = date('Y-m-d H:i:s');
				//if($rowData == 0){
					DB::table('mstr_vehicle')->where('id', $id)->update(['vehicle'=>$vehicle,'flag'=>$flag,'updated_at'=>$updated_at]);
					DB::table('product_segment')->where('id', $segmentId)->where('product_id', $id)->update(['segment'=>$segment,'updated_at'=>$updated_at]);
					DB::table('product_model')->where('id', $modelId)->where('product_id', $id)->where('segment_id', $segmentId)->update(['model'=>$model,'updated_at'=>$updated_at]);
					$notification = array(
		                'message' => 'Updated successfully',
		                'alert-type' => 'success'
		            ); 
	           /* }else{
					$notification = array(
		                'message' => 'Duplicate Data',
		                'alert-type' => 'error'
		            );
				}	*/					
		        return redirect()->route('product')->with($notification);
			}	
		}catch (\Exception $ex) {
			$notification = array(
	                'message' => $ex->getMessage(),
	                'alert-type' => 'error'
	            );
             return redirect()->route('product')->with($notification);
        }
	}	
	
	public function productDelete($id){
		//DB::table('mstr_user_type')->where('id', $id)->delete();
		try{
			$delData = DB::select("call delete_with_one('mstr_vehicle','id','$id')");
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
            return back()->with($notification);
        }
	}
/* ----------------------End brand---------------------------------  */	
}
