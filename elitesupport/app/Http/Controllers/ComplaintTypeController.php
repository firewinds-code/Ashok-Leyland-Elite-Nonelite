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
class ComplaintTypeController extends Controller
{
    public function __construct(){
		DB::enableQueryLog();
				
	}
	/* ----------------------complaint-type---------------------------------  */	
public function complaintType()
{
		try{
			
				$data['complaint_data']= DB::table('mstr_complaint')->select('id','complaint_type')->get();			
				$data['rowData']= DB::table('mstr_complaint as t1')->select('t1.id','t1.complaint_type','t2.id as sub_complaint_type_id','t2.complaint_type_id','t2.sub_complaint_type','t2.flag')->join('mstr_sub_complaint as t2','t1.id','=','t2.complaint_type_id')->orderBy('t1.id','desc')->get();			
				return view('complaint_type',$data);
			
				
		}catch (\Exception $ex) {
			$notification = array(
	                'message' => $ex->getMessage(),
	                'alert-type' => 'error'
	            );
				return redirect()->route('complaint-type')->with($notification);
        }
			
	}
	
	public function storeComplaint(Request $request){
		try{
			$serverValidation  = new ServerValidation();
			$complaint_type = $request->input('complaint_type');
			$sub_complaint_type = $request->input('sub_complaint_type');
			$flag = $request->input('flag');
			$dataid = $request->input('dataid');			
			if ($complaint_type == 'NA') {
					$notification = array(
					'message' => 'Please enter complaint type',
			                'alert-type' => 'error'
		            	);
		            return back()->with($notification);
			}else if($serverValidation->is_empty($sub_complaint_type)){
				$notification = array(
					'message' => 'Please enter Sub complaint type',
			                'alert-type' => 'error'
		            	);
		            return back()->with($notification);
			}else{
				if ($dataid =='') {
		$rowData= DB::table('mstr_sub_complaint')->select('id')->where('complaint_type_id',$complaint_type)->where('sub_complaint_type',$sub_complaint_type)->count();
					if ($rowData == 0) {
						DB::table('mstr_sub_complaint')->insert(['complaint_type_id'=>$complaint_type,'sub_complaint_type'=>$sub_complaint_type]);
						
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
					
					$rowData= DB::table('mstr_sub_complaint')->select('id')->where('complaint_type_id',$complaint_type)->where('sub_complaint_type',$sub_complaint_type)->where('flag',$flag)->count();
					$updated_at = date('Y-m-d H:i:s');
					if ($rowData == 0) {
						
						DB::table('mstr_sub_complaint')->where('id', $dataid)->update(['complaint_type_id' => $complaint_type,'sub_complaint_type' => $sub_complaint_type,'flag' => $flag,'updated_at'=>$updated_at]);
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
				
				return redirect()->route('complaint-type')->with($notification);	
			}	
		}catch (\Exception $ex) {
			$notification = array(
	                'message' => $ex->getMessage(),
	                'alert-type' => 'error'
	            );
				return redirect()->route('complaint-type')->with($notification);
        }
	}

	
	public function complaintTypeDelete($id){
		
		try{
			$delData = DB::select("call delete_with_one('mstr_sub_complaint','id','$id')");
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
				return redirect()->route('complaint-type')->with($notification);
        }	
	}
	public function getSubComplaint(Request $request)
	{
		try{
			$complaint_type_id = $request->input('complaint_type_id');
		
			//$result=DB::table("mstr_sub_complaint")->select('id','sub_complaint_type')->where('complaint_type_id',$complaint_type_id)->where('flag','1')->get();
			$result=DB::select("select id,sub_complaint_type from mstr_sub_complaint where complaint_type_id='".$complaint_type_id."' and flag='1'");
			
			foreach ($result as $value) {
				Echo $str= $value->id.'~'.$value->sub_complaint_type.',';
			}
		}catch (\Exception $ex) {
			$notification = array(
			'message' => $ex->getMessage(),
			'alert-type' => 'error'
			);
			return redirect()->route('complaint-type')->with($notification);
		}
	}
	/* ----------------------End complaint-type---------------------------------  */	
}
