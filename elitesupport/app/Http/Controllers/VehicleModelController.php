<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Session;
use Redirect;
use Mail;
use Illuminate\Support\Facades\Hash;
use App\classes\ServerValidation;
use Auth;

date_default_timezone_set('Asia/Kolkata');

class VehicleModelController extends Controller
{
     public function __construct(){
		DB::enableQueryLog();
	}
	public function vehicleModel(){
		try{
          
            $data['vehicleModels'] = DB::select("Select id,ownerId, vehicle_model, vehicle_segment, add_blue_use, engine_emmission_type from mstr_vehicle_models");
            return view('vehicle_models',$data);
        }catch (\Exception $ex) {
			$notification = array(
			'message' => $ex->getMessage(),
			'alert-type' => 'error'
			);
			return back()->with($notification);
	    }
    }
    public function storeVehicleModels(Request $request){
        
        $vehicle_model = $request->input('vehicle_model');
        $vehicle_segment = $request->input('vehicle_segment');
        $add_blue_use = $request->input('add_blue_use');
        $engine_emmission_type = $request->input('engine_emmission_type');
        $ownerId = $request->input('ownerId');
        $dataid = $request->input('dataid');
        if($dataid ==''){
            DB::table('mstr_vehicle_models')->insert(['ownerId'=>"$ownerId",'vehicle_model'=>"$vehicle_model",'vehicle_segment'=>"$vehicle_segment",'add_blue_use'=>"$add_blue_use",'engine_emmission_type'=>"$engine_emmission_type"]); 
            $notification = array(
                'message' => 'Stored successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('vehicle-models')->with($notification);
        }else{
            $updated_at = date('Y-m-d H:i:s');
            DB::table('mstr_vehicle_models')->where('id', $dataid)->update(['vehicle_model'=>"$vehicle_model",'vehicle_segment'=>"$vehicle_segment",'add_blue_use'=>"$add_blue_use",'engine_emmission_type'=>"$engine_emmission_type",'updated_at'=>"$updated_at"]);
			$notification = array(
				'message' => 'Updated successfully',
				'alert-type' => 'success'
			);
            return redirect()->route('vehicle-models')->with($notification);
        }

    }
    public function vehicleModelDelete($id){
		try{
			$delData = DB::select("call delete_with_one('mstr_vehicle_models','id','$id')");
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
    public function getVehicleModels(Request $request){
        $id = $request->input("id");
        $query = DB::select("Select id, vehicle_model, vehicle_segment, add_blue_use, engine_emmission_type from mstr_vehicle_models where id = $id");
         echo $query[0]->vehicle_segment.'~~'.$query[0]->add_blue_use.'~~'.$query[0]->engine_emmission_type;
    }
}   
