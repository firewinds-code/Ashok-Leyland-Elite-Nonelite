<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Session;
use Redirect;
use Mail;
use File;
use ZipArchive;
use Excel;
use Illuminate\Support\Facades\Hash;
use App\classes\ServerValidation;
use App\Imports\importExcel;
use Auth;
date_default_timezone_set('Asia/Kolkata');
class ImportExcelController extends Controller{
    public function __construct(){
		DB::enableQueryLog();
	}
    public function importExcel(){
       
       try{
            if(Auth::user()->role == '29' || Auth::user()->role == '30'){
                $data['rowData'] = DB::select("Select ID, SUPPORT_CENTER_CODE, SUPPORT_CENTER_TYPE, SUPPORT_CENTER_NAME, SC_PHONE_NUM, SC_OWNER_NAME_1, SC_OWNER_PHONE_NUM_1, SC_OWNER_MOBILE_NUM_1, SC_ADDRESS_1, SC_ADDRESS_2, SC_AREA_NAME, SC_DISTRICT_NAME, SC_CITY_NAME, SC_STATE_NAME, SC_PINCODE, SC_LAND_MARK, SC_REF_HIGHWAY, GQ, SERVICE_BOOKING, AO, RO, Zone, WORKS_MGR_NAME_1, WORKS_MGR_PHONE_1, WORKS_MGR_MOBILE_1, WORKS_MGR_NAME_2, WORKS_MGR_PHONE_2, WORKS_MGR_MOBILE_2, WM_E_Mail, SAC_Service_Head_GM_Name, SAC_Service_Head_GM_Mobile_Number, SAC_Service_Head_GM_Mail_id, SAC_Owner_Name, SAC_Owner_Mobile_Number, SAC_Owner_Mail_id, AL_SE_NAME, AL_SE_MOBILE, ALSE_EMAIL_ID, ASM, ASM_Contact_number, ASM_E_Mail, Regional_Service_Manager, RSM_Contact_number, RSM_Mail_Id, Zonal_Service_Manager, ZSM_Contact_number, ZSM_Mail_Id, Workshop_Parts_Executive, WPE_Contact_Number, WPE_E_Mail, Area_Parts_Manager, APM_Contact_Number, APM_E_Mail, Regional_Parts_Manager, RPM_Contact_Number, RPM_E_Mail, Zonal_Parts_Manager, ZPM_Contact_Number, ZPM_E_Mail, Zonal_Manager, ZM_E_Mail, WA, Working_Hrs, Mobile_Van, Latitude, Longitude, GST_Number, No_of_Bays, Dealer_ID, zoneID, StateID, CityID, created_at from user_raw_data");
                return view('m3_data',$data);
            }else{
                $notification = array(
                    'message' => "User not authorized to access this page",
                    'alert-type' => 'error'
                );
                return back()->with($notification);
            }
       }catch (\Exception $ex) {
            $notification = array(
                'message' => $ex->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }
    public function storeImportExcel(Request $request){
        
       try{
            if(Auth::user()->role == '29' || Auth::user()->role == '30' || Auth::user()->role == '87'){
                $request->validate([
                    'import_file' => 'required|mimes:csv|max:20202021448',
                ]);
                if($request->hasFile('import_file')){
                    $extension = $request->file('import_file')->getClientOriginalExtension();
                     if($extension != 'csv'){
                         $notification = array(
                             'message' => "File extension is not a CSV file",
                             'alert-type' => 'error'
                         );
                         return back()->with($notification);
                     }
                    DB::table('user_raw_data')->truncate();
                    $path = $request->file('import_file')->getRealPath();
                    $rowData = Excel::import(new importExcel, $path);                   
                }        
                $notification = array(
                    'message' => "M3 Data import successfully",
                    'alert-type' => 'success'
                );
                return back()->with($notification);
            }else{
                $notification = array(
                    'message' => "User not authorized to access this page",
                    'alert-type' => 'error'
                );
                return back()->with($notification);
            }
       }catch (\Exception $ex) {
            $notification = array(
                'message' => $ex->getMessage().'Line: '.$ex->getLine().' Code: '.$ex->getCode(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }
    public function importUserData(){
        try {
           $mapping = DB::select("call User_1");
           if($mapping[0]->Output == 1 ){
                $finalMapping = DB::select("call User_2");
                echo $finalMapping[0]->Output;
           }else{
               echo "Mapping Error"; 
           } 
        }catch (\Exception $ex) { 
            $notification = array(
                'message' => $ex->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }
    public function multiEmail($val){
        $val = rtrim($val,';');
        $val = rtrim($val,',');
        $val = rtrim($val,' ');
        $val = rtrim($val,':');
        $val = str_replace(":",",",$val);
        $val = str_replace(";",",",$val);
        $val = str_replace(" ",",",$val);
        return $val;
    }
}