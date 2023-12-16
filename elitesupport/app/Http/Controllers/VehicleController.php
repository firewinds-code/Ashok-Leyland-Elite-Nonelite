<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Session;
use Excel;
use Redirect;
use Mail;
use App\classes\ServerValidation;
use Auth;
use DataTables;
date_default_timezone_set('Asia/Kolkata');

class VehicleController extends Controller
{
    public function __construct(){
		DB::enableQueryLog();
				
	}
/* ----------------------brand---------------------------------  */	
	public function vehicle(Request $request){
		
		try{
			
				$data['ownerData'] = DB::select("Select id, owner_name from mstr_owner");
				/* $data['rowData']= DB::select("select v.id, v.vehicle, v.vehicle_model, v.reg_number, v.chassis_number, v.engine_number, v.vehicle_segment, v.purchase_date, v.add_blue_use, v.vehicle_type, v.is_vehicle_movable, v.engine_emmission_type,v.ownerId, v.flag, v.created_at, v.updated_at,o.owner_name from mstr_vehicle as v left join mstr_owner as o on o.id = v.ownerId order by id desc"); */
				$data['rowData']= DB::table('mstr_vehicle as v')->select('v.id', 'v.vehicle', 'v.vehicle_model', 'v.reg_number', 'v.chassis_number', 'v.engine_number', 'v.vehicle_segment', 'v.purchase_date', 'v.add_blue_use', 'v.vehicle_type', 'v.is_vehicle_movable', 'v.engine_emmission_type','v.ownerId', 'v.flag', 'v.created_at', 'v.updated_at', 'v.created_by', 'v.updated_by','o.owner_name')->leftjoin('mstr_owner as o', 'o.id', 'v.ownerId')->orderBy('v.id', 'desc')->paginate(50);
				/* $dataAjax = DB::table('mstr_vehicle as v')->select('v.id', 'v.vehicle', 'v.vehicle_model', 'v.reg_number', 'v.chassis_number', 'v.engine_number', 'v.vehicle_segment', 'v.purchase_date', 'v.add_blue_use', 'v.vehicle_type', 'v.is_vehicle_movable', 'v.engine_emmission_type','v.ownerId', 'v.flag', 'v.created_at', 'v.updated_at', 'v.created_by', 'v.updated_by','o.owner_name')->leftjoin('mstr_owner as o', 'o.id', 'v.ownerId')->orderBy('v.id', 'desc')->limit('10')->get();
				dd($dataAjax); */
				/* if ($request->ajax()) {
					$dataAjax = DB::table('mstr_vehicle as v')->select('v.id', 'v.vehicle_model', 'v.reg_number', 'v.chassis_number', 'v.engine_number', 'v.vehicle_segment', 'v.purchase_date', 'v.add_blue_use', 'v.vehicle_type', 'v.is_vehicle_movable', 'v.engine_emmission_type','v.ownerId', 'v.flag', 'v.created_at', 'v.updated_at', 'v.created_by', 'v.updated_by','o.owner_name')->leftjoin('mstr_owner as o', 'o.id', 'v.ownerId')->orderBy('v.id', 'desc')->limit('19000')->get();
					//dd($dataAjax);
					return Datatables::of($dataAjax)->addIndexColumn()
						->addColumn('action', function($row){
							//$btn = '<a href="javascript:void(0)" class="btn btn-primary btn-sm">View</a>';
							$btn ='<i class="fa fa-pencil-square-o" aria-hidden="true" id="{{$row->id}}" data-position="left" data-tooltip="Edit" onclick="javascript:return editvehicle(this);" style="cursor: pointer;"></i>';
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
						
						->rawColumns(['flbtn','action'])->setTotalRecords(100)
						//->removeColumn(['id','last_name'])
						->make(true);
				} */
				return view('vehicle',$data);
			
		}catch (\Exception $ex) {
			$notification = array(
                'message' => $ex->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
			
	}
	
	public function storeVehicle(Request $request){
		try{
			$serverValidation  = new ServerValidation();
			$vehicle_model = $request->input('vehicle_model');
			$reg_number = $request->input('reg_number');
			$chassis_number = $request->input('chassis_number');
			$engine_number = $request->input('engine_number');
			$vehicle_segment = $request->input('vehicle_segment');
			$purchase_date = $request->input('purchase_date');
			$add_blue_use = $request->input('add_blue_use');
			$engine_emmission_type = $request->input('engine_emmission_type');
			$ownerId = $request->input('ownerId');
			$dataid = $request->input('dataid');
			$flag = $request->input('flag');
			$updated_at = date('Y-m-d H:i:s');
			$sessionName = Auth::user()->name;
			if ($dataid == '') {
					DB::table('mstr_vehicle')->insert(['vehicle_model'=>$vehicle_model,'reg_number'=>$reg_number,'chassis_number'=>$chassis_number,'engine_number'=>$engine_number,'vehicle_segment'=>$vehicle_segment,'purchase_date'=>$purchase_date,'add_blue_use'=>$add_blue_use,'engine_emmission_type'=>$engine_emmission_type,'ownerId'=>$ownerId,'created_by'=>$sessionName,'flag'=>$flag]);
					$notification = array(
					'message' => 'Stored successfully',
					'alert-type' => 'success'
					);
				return back()->with($notification);
			}else{
				DB::table('mstr_vehicle')->where('id', $dataid)->update(['vehicle_model'=>$vehicle_model,'reg_number'=>$reg_number,'chassis_number'=>$chassis_number,'engine_number'=>$engine_number,'vehicle_segment'=>$vehicle_segment,'purchase_date'=>$purchase_date,'add_blue_use'=>$add_blue_use,'engine_emmission_type'=>$engine_emmission_type,'ownerId'=>$ownerId,'flag'=>$flag,'updated_by'=>$sessionName,'updated_at'=>$updated_at]);
				$notification = array(
					'message' => 'Updated successfully',
					'alert-type' => 'success'
				);
			}
		    return redirect()->route('vehicle')->with($notification);
		}catch (\Exception $ex) {
			$notification = array(
	                'message' => $ex->getMessage(),
	                'alert-type' => 'error'
	            );
             return redirect()->route('vehicle')->with($notification);
        }
	}
	
	
	public function vehicleDelete($id){
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
	public function getProductSegment(Request $request){
		try{
			
			$productId = $request->input('product_id');
			$result=DB::table("product_segment")->select('id','product_id', 'segment')->where('product_id',$productId)->get();
			foreach($result as $value){
				Echo $str= $value->id.','.$value->segment.'~';
			}
			
				
		}catch (\Exception $ex) {
			$notification = array(
                'message' => $ex->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
	}
	public function getMutliProductSegment(Request $request){
		try{			
			$productId = $request->input('product_id');
			
			//$result=DB::table("product_segment")->select('id','product_id', 'segment')->where('product_id',$productId)->get();
			$result=DB::select("select id,product_id,segment from product_segment where find_in_set(product_id,'".$productId."')");
			foreach($result as $value){
				Echo $str= $value->id.','.$value->segment.'~';
			}
			
				
		}catch (\Exception $ex) {
			$notification = array(
                'message' => $ex->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
	}
	public function getMutliProduct(Request $request){
		try{
							
				$result=DB::select("select concat(id,'~',vehicle)vehicle from mstr_vehicle");
				foreach($result as $value){
					Echo $str= $value->vehicle.',';
				}
			
				
		}catch (\Exception $ex) {
			$notification = array(
                'message' => $ex->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
	}
	public function ajaxVehicleReportData(Request $request){
		$keyword = $request->input('keyword');
		$query = DB::table('mstr_vehicle as v')->select('v.id', 'v.vehicle', 'v.vehicle_model', 'v.reg_number', 'v.chassis_number', 'v.engine_number', 'v.vehicle_segment', 'v.purchase_date', 'v.add_blue_use', 'v.vehicle_type', 'v.is_vehicle_movable', 'v.engine_emmission_type','v.ownerId', 'v.flag', 'v.created_at', 'v.updated_at', 'v.created_by', 'v.updated_by','o.owner_name')->leftjoin('mstr_owner as o', 'o.id', 'v.ownerId')->where('v.reg_number', 'like', '%' . $keyword . '%')->orWhere('v.chassis_number', 'like', '%' . $keyword . '%')->orWhere('o.owner_name', 'like', '%' . $keyword . '%')->paginate(2);
		if(sizeof($query)>0){
			$data = '';
			$msg = "Do you want to delete?";
			foreach($query as $row) {
				 $data .= '<tr><td>
				  <i class="fa fa-pencil-square-o" aria-hidden="true" id='.$row->id.' data-position="left" data-tooltip="Edit" onclick="javascript:return editvehicle(this);" style="cursor: pointer;"></i>
											<a href="'.route("vehicle_delete.vehicleDelete", ["id" => $row->id]).'" onclick="return confirm('.$msg.')">
												<i class="fa fa-trash-o" aria-hidden="true" style="cursor: pointer;"></i></a>
										</td>
										<td class="">'.$row->owner_name.'</td>
										<td class="cls_reg_number">'.$row->reg_number.'</td>
										<td class="cls_chassis_number">'.$row->chassis_number.'</td>
										<td >'.$row->created_by.'</td>
										<td >'.$row->created_at.'</td>
										<td >'.$row->updated_by.'</td>
										<td >'.$row->updated_at.'</td>
										<td class="cls_flag" >';
										if($row->flag=="1"){
											$data .= '<label class="badge badge-success">Active</label>';
										}else{
											$data .= '<label class="badge badge-danger">Inactive</label>';
										}
										$data .= '</td>  
										<td class="cls_engine_number" style="display: none;">'.$row->engine_number.'</td>
										<td class="cls_vehicle_segment" style="display: none;">'.$row->vehicle_segment.'</td>
										<td class="cls_purchase_date" style="display: none;">'.$row->purchase_date.'</td>
										<td class="cls_add_blue_use" style="display: none;">'.$row->add_blue_use.'</td>
										<td class="cls_engine_emmission_type" style="display: none;">'.$row->engine_emmission_type.'</td>
										<td class="cls_ownerId" style="display: none;">'.$row->ownerId.'</td>
										<td class="cls_vehicle_model" style="display: none;">'.$row->vehicle_model.'</td>
									</tr>';
			}
			
			echo $data; 
		  }    
	}
	public function export(){
		$rowData= DB::select("select o.owner_name,v.reg_number, v.chassis_number, v.engine_number,v.purchase_date, v.add_blue_use, v.is_vehicle_movable, v.engine_emmission_type,v.created_by, v.updated_by,v.flag from mstr_vehicle as v left join mstr_owner as o on o.id = v.ownerId order by v.id desc");

		$columns = array('Owner_Name', 'Registration_Number', 'Chassis_Number', 'Engine_Number','purchase_date','add_blue_use','is_vehicle_movable','engine_emmission_type','Created_Date', 'Updated_Date','Status');

		$fileName = 'Vehicle_Master_."' . date("Y-m-d h:m:0") . '".csv';
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
   /*   foreach($rowData as $row)
     {
		$customer_array[] = array(
			'Owner_Name'  => $row->owner_name,
			'Registration_Number'  => $row->reg_number,
			'Chassis_Number'  => $row->chassis_number,
			'Created_By'  => $row->created_by,
			'Created_Date'  => $row->created_at,
			'Updated_By'  => $row->updated_by,
			'Updated_Date'  => $row->updated_at,
			
		   );
     }
		//dd($customer_array);
     Excel::create('Vehicle Data', function($excel) use ($customer_array){
      $excel->setTitle('Vehicle Data');
      $excel->sheet('Vehicle Data', function($sheet) use ($customer_array){
       $sheet->fromArray($customer_array, null, 'A1', false, false);
      });
     })->download('xlsx'); */
	}

	
	
}
