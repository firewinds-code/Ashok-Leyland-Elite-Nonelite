<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use Redirect;
use Mail;
use Illuminate\Support\Facades\Hash;
use App\classes\ServerValidation;
use App\Followup;
use App\Followupinfo;


date_default_timezone_set('Asia/Kolkata');

class FollowupController extends Controller
{
    public function __construct()
    {
        DB::enableQueryLog();
    }


    public function index()
    {
        if (empty(Session::get('email'))) {
            return redirect('/'); 
        }
       
        $date = date('Y-m-d', strtotime('-4 days'));
        $folloupsData = Followup::select('followups.*','followups_info.disposition as call_log')->leftjoin('followups_info','followups_info.followup_id', '=', 'followups.id')->where('followups.created_at', '>=', $date)->orderBy('followups_info.id','DESC')->get();
        //dd($folloupsData);
        $finalDatas = array();
        $finalData= array();
        $roleData = DB::select("Select id, role from mstr_role order by role ASC");
        foreach ($folloupsData as $flp) {
            $finalDatas['complaint_number'] = $flp->complaint_number;
            $finalDatas['dealer_mob_number'] = $flp->dealer_mob_number;
            $mstr_dealer = DB::select("Select dealer_name from mstr_dealer where id='" . $flp->assign_to . "'");
           
            $mstr_vehicle_models = DB::select("Select reg_number from mstr_vehicle where id=$flp->vehicleId");
            $finalDatas['dealer_name'] = $mstr_dealer[0]->dealer_name;
            $finalDatas['reg_number'] = (sizeof($mstr_vehicle_models)>0 && $mstr_vehicle_models[0]->reg_number)?$mstr_vehicle_models[0]->reg_number:'NA';
            $finalDatas['complaint_number'] = $flp->complaint_number;
            $finalDatas['created_at'] = $flp->created_at;
            $finalDatas['created_by'] = $flp->employee_name;
            $finalDatas['status'] = $flp->status;
            $finalDatas['followup_name'] = $flp->followup_name;
            $finalDatas['followups_number'] = $flp->followups_number;
            $finalDatas['call_log'] = $flp->call_log;
            //$finalDatas['status']=$flp->status;
            $finalDatas['id'] = $flp->id;
            
           /*  $timestamp = strtotime($flp->created_at) + strtotime($flp->estimated_response_time);
            $newtimestamp = strtotime($flp->created_at . ' + ' . $flp->estimated_response_time . ' minute'); */
            //$finalDatas['estimated_response_time'] = date('d/m H:i:', $newtimestamp);
            $finalDatas['estimated_response_time'] = $flp->estimated_response_time;
            $finalData[] = $finalDatas;
        }
        return view('followups')->with(compact('finalData','roleData'));
    }

    public function storeFolloupInfo(Request $request)
    {
        //dd($_POST);
        try {
            $currentUserMail = Session::get('email');
            $loginId = Session::get('employee_id');
            $loginName = Session::get('name');
            DB::table('followups_info')->insert(['complaint_number' => "$request->complaint_number", 'employee_name' => "$loginName", 'employee_id' => "$currentUserMail", 'disposition' => "$request->disposition", 'remarks' => "$request->remarks", 'followup_id' => "$request->id",  'attempt' => "$request->attempt"]);
            $notification = array(
                'message' => 'Data Save Successfully',
                'alert-type' => 'success'
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

    public function getFollupinfo(Request $request)
    {
        if (empty(Session::get('email'))) {
            return redirect('/');
        }
        $folloupsData = Followupinfo::where('followup_id', $request->followup_id)->get();
        //dd($folloupsData);
        $view = view("getFollupinfo",compact('folloupsData'))->render();
        //eturn response()->json(['html'=>$view]);
        return \Response::json(['html'=>$view]);
    }

    public function newCallList(Request $request)
    {
       //dd($_POST);

       //$complain_number=$request->complaint_number_new;
       $complainData = DB::select("Select * from cases where complaint_number='" . $request->complaint_number_new . "'");
       //dd( $complainData);
       $assign_to=$complainData[0]->assign_to;
       $loginName = Session::get('name');
       $currentUserMail = Session::get('email');
       $role=$request->call_to;
       //echo "Select mobile from users where role=$role and FIND_IN_SET($assign_to,dealer_id)";die;
       $followupData=DB::select("Select mobile,name from users where role=$role and FIND_IN_SET($assign_to,dealer_id)");
       ///dd($followupData);
       /*  if($request->call_to=="dealer")
        {
            $mobile = DB::select("Select phone from mstr_dealer where id='" . $complainData[0]->assign_to . "'");
            $mstr_vehicle_models = DB::select("Select mstr_vehicle.reg_number as reg_number from mstr_vehicle where mstr_vehicle.vehicle_model='" . $flp->vehicleId . "'");
        } */
            $dealer_mob_number=$complainData[0]->dealer_mob_number;
            $dealer_alt_mob_number=$complainData[0]->dealer_alt_mob_number;
            $estimated_response_time=$complainData[0]->estimated_response_time;
            $vehicleId=$complainData[0]->vehicleId;
            $name=$followupData[0]->name;
            $mobile=$followupData[0]->mobile;
       //dd($complainData[0]->estimated_response_time);
       echo DB::table('followups')->insert(['complaint_number' => "$request->complaint_number_new",'employee_name' => "$loginName", 'employee_id' => "$currentUserMail", 'dealer_mob_number' => " $dealer_mob_number", 'dealer_alt_mob_number' => "$dealer_alt_mob_number", 'assign_to' => "$assign_to", 'assign_type' => "$role",'estimated_response_time' => "$estimated_response_time",'followup_name' => "$name",'followups_number' => "$mobile",'vehicleId' => "$vehicleId" ]);
       $notification = array(
        'message' => 'Data Save Successfully',
        'alert-type' => 'success'
    );
    return redirect()->route('followups')->with($notification);
    }
}
