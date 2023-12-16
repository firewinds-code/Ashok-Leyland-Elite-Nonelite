<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Session;
use Redirect;
use Mail;
use App\classes\ServerValidation;

date_default_timezone_set('Asia/Kolkata');
class OwnerController extends Controller
{
    public function __construct(){
		DB::enableQueryLog();
				
	}
/* ----------------------Owner---------------------------------  */
public function ajaxOwnerReportData(Request $request){
	$keyword = $request->input('keyword');
	$query = DB::table('mstr_owner')->select('id','owner_name', 'owner_mob', 'owner_landline', 'owner_cat', 'owner_company', 'flag')->where('owner_name', 'like', '%' . $keyword . '%')->paginate(20);
	if(sizeof($query)>0){
		$data = '';
		$msg = "Do you want to delete?";
		foreach($query as $row) {
			 $data .= '<tr><td>
			  <i class="fa fa-pencil-square-o" aria-hidden="true" id='.$row->id.' data-position="left" data-tooltip="Edit" onclick="javascript:return editOwner(this);" style="cursor: pointer;"></i>
										
									</td>
									<td class="cls_owner_name">'.$row->owner_name.'</td>
									<td class="cls_owner_mob">'.$row->owner_mob.'</td>
									<td class="cls_owner_company">'.$row->owner_company.'</td>
									<td class="cls_owner_landline">'.$row->owner_landline.'</td>
									<td class="cls_owner_cat" style="display: none;">'.$row->owner_cat.'</td>
									<td class="cls_vehicle_id" style="display: none;">0</td>
									</tr>';
		}
		
		echo $data; 
	  }    
}
public function exportOwner(){
	$rowData= DB::select("select owner_name, owner_mob, owner_landline, owner_cat, owner_company, flag from mstr_owner");

	$columns = array('owner_name', 'owner_mob', 'owner_landline', 'owner_cat', 'owner_company', 'flag');

	$fileName = 'Owner_Master_."' . date("Y-m-d h:m:0") . '".csv';
	$headers = array(
		"Content-type"        => "text/csv",
		"Content-Disposition" => "attachment; filename=$fileName",
		"Pragma"              => "no-cache",
		"Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
		"Expires"             => "0"
	);
	// dd($rowData);
	$callback = function () use ($rowData, $columns) {
		$file = fopen('php://output', 'w');
		fputcsv($file, $columns);
		$i = 1;


		foreach ($rowData as $task) {
			$row = array();

			// dd($task);
			foreach ($task as $key => $value) {
				//$row['SrNo']    = $i;


				$row[$key]    = $value;
			}
			fputcsv($file, $row);
			$i++;
		}

		fclose($file);
	};
	return response()->stream($callback, 200, $headers);

}
	public function ownerView(){
		try{
			
				$data['ownerData']= DB::table("mstr_vehicle")->select('id','reg_number')->get();
				// $data['rowData'] = DB::select("Select o.id as id, o.vehicle_id, o.owner_name, o.owner_mob, o.owner_landline, o.owner_cat, o.owner_company, o.alse_mail, o.asm_mail,o.rsm_mail, v.reg_number from mstr_owner o left join mstr_vehicle v on v.id = o.vehicle_id");
				$data['rowData']= DB::table('mstr_owner as o')->select('o.id as id','o.owner_name','o.owner_mob','o.owner_landline','o.owner_cat','o.owner_company','o.alse_mail','o.asm_mail','o.rsm_mail','v.reg_number')->leftjoin('mstr_vehicle as v', 'v.id', 'o.vehicle_id')->paginate(20);

				return view('owner',$data);
			
				
		}catch (\Exception $ex) {
			$notification = array(
	                'message' => $ex->getMessage(),
	                'alert-type' => 'error'
	            );
            return back()->with($notification);
        }
			
	}
	
	public function storeOwner(Request $request){
		try{
			$serverValidation  = new ServerValidation();
			//dd($request->input());
			$vehicle_id = $request->input('vehicle_id');
			$owner_name = $request->input('owner_name');
			$owner_mob = $request->input('owner_mob');
			$owner_landline = $request->input('owner_landline');
			$owner_cat = $request->input('owner_cat');
			$owner_company = $request->input('owner_company');
			$alse_mail = $request->input('alse_mail');
			$asm_mail = $request->input('asm_mail');
			$dataid = $request->input('dataid');	
			$data['ownerData']= DB::table("mstr_vehicle")->select('id','reg_number')->get();
			if ($dataid =='') {
					DB::table('mstr_owner')->insert(['vehicle_id'=>$vehicle_id, 'owner_name'=>$owner_name, 'owner_mob'=>$owner_mob, 'owner_landline'=>$owner_landline, 'owner_cat'=>$owner_cat, 'owner_company'=>$owner_company, 'alse_mail'=>$alse_mail, 'asm_mail'=>$asm_mail]);
					$notification = array(
						'message' => 'Stored successfully',
						'alert-type' => 'success'
					);
				
			}else{
				$updated_at = date('Y-m-d H:i:s');
				DB::table('mstr_owner')->where('id', $dataid)->update(['vehicle_id'=>$vehicle_id, 'owner_name'=>$owner_name, 'owner_mob'=>$owner_mob, 'owner_landline'=>$owner_landline, 'owner_cat'=>$owner_cat, 'owner_company'=>$owner_company, 'alse_mail'=>$alse_mail, 'asm_mail'=>$asm_mail,'updated_at'=>$updated_at]);
				$notification = array(
					'message' => 'Updated successfully',
					'alert-type' => 'success'
				);
			}
		
			return back()->with($notification);	
		}catch (\Exception $ex) {
			$notification = array(
	                'message' => $ex->getMessage(),
	                'alert-type' => 'error'
	            );
				return back()->with($notification);
        }
	}
		
	
	public function ownerDelete($id){
		try{
			$delData = DB::select("call delete_with_one('mstr_owner','id','$id')");
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
/* ----------------------End Owner---------------------------------  */	

public function ownerContactView(){
	try{
		
			$data['vehicleData']= DB::table("mstr_vehicle")->select('id','reg_number')->get();
			$data['ownerData']= DB::table("mstr_owner")->select('id','owner_name')->get();
			$data['rowData'] = DB::select("Select oc.id as contactId, oc.vehicle_id, oc.owner_id, oc.contact_name, oc.mob,oc.owner_contact_email,v.reg_number,o.id as ownerId, o.owner_name as owner_name from mstr_owner_contact oc left join mstr_vehicle v on v.id = oc.vehicle_id left join mstr_owner o on o.id = oc.owner_id");
			return view('owner_contact',$data);
		
			
	}catch (\Exception $ex) {
		$notification = array(
				'message' => $ex->getMessage(),
				'alert-type' => 'error'
			);
		return back()->with($notification);
	}
		
}

public function storeOwnerContact(Request $request){
	try{
		$serverValidation  = new ServerValidation();
		//dd($request->input());
		$vehicle_id = $request->input('vehicle_id');
		$owner_id = $request->input('owner_id');
		$contact_name = $request->input('contact_name');
		$owner_contact_email = $request->input('owner_contact_email');
		$mob = $request->input('mob');
		$dataid = $request->input('dataid');	
		$data['ownerData']= DB::table("mstr_vehicle")->select('id','reg_number')->get();
		if ($dataid =='') {
				DB::table('mstr_owner_contact')->insert(['vehicle_id'=>$vehicle_id, 'owner_id'=>$owner_id, 'contact_name'=>$contact_name, 'mob'=>$mob, 'owner_contact_email'=>$owner_contact_email]);
				$notification = array(
					'message' => 'Stored successfully',
					'alert-type' => 'success'
				);
			
		}else{
			$updated_at = date('Y-m-d H:i:s');
			DB::table('mstr_owner_contact')->where('id', $dataid)->update(['vehicle_id'=>$vehicle_id, 'owner_id'=>$owner_id, 'contact_name'=>$contact_name, 'mob'=>$mob, 'owner_contact_email'=>$owner_contact_email,'updated_at'=>$updated_at]);
			$notification = array(
				'message' => 'Updated successfully',
				'alert-type' => 'success'
			);
		}	
		return back()->with($notification);	
	}catch (\Exception $ex) {
		$notification = array(
				'message' => $ex->getMessage(),
				'alert-type' => 'error'
			);
			return back()->with($notification);
	}
}
	

public function ownerContactDelete($id){
	try{
		$delData = DB::select("call delete_with_one('mstr_owner_contact','id','$id')");
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
public function getOwnerName(Request $request){
	$id = $request->input('id');
	$sql = DB::select("Select id, owner_name from mstr_owner where vehicle_id = $id");
	foreach($sql as $row){
		echo $row->id.'~~'.$row->owner_name.',';
	}
}
}
