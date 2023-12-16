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
        $remark_type = DB::select("Select id, type from remark_type order by type ASC");
        $remark_typeArr='';
        $tictStatus = array(35);
        foreach($remark_type as $row){
            if(!in_array($row->id,$tictStatus)){
                $remark_typeArr .=$row->type.",";
            }
        }
        $remark_typeArr = rtrim($remark_typeArr,",");
        $remark_typeArr = explode(",",$remark_typeArr);        
        $date = date('Y-m-d', strtotime('-2 days'));
        $dateto = date('Y-m-d');
        /* $folloupsData = Followup::select('followups.*','followups_info.disposition as call_log')->leftjoin('followups_info','followups_info.followup_id', '=', 'followups.id')->where('followups.created_at', '>=', $date)->where('followups.status','!=','Closed')->orWhere('followups_info.disposition','!=','completed')->orderBy('followups_info.id','DESC')->get(); */
        
        $folloupsData = DB::select("select followups.*, followups_info.disposition as call_log,
        (select group_concat(fi.disposition separator '##') from followups_info as fi where followups.id =fi.followup_id and followups.complaint_number = fi.complaint_number and fi.followup_id!='') as fdisposition,
        (select group_concat(fi.employee_name separator '##') from followups_info fi where followups.id =fi.followup_id and followups.complaint_number = fi.complaint_number) as femployee_name,
        (select group_concat(fi.created_at separator '##') from followups_info fi where followups.id =fi.followup_id and followups.complaint_number = fi.complaint_number) as fcreated_at,
        (select group_concat(fi.remarks separator '##') from followups_info fi where followups.id =fi.followup_id and followups.complaint_number = fi.complaint_number) as fremarks
        from followups left join followups_info on followups_info.followup_id = followups.id where date(followups.created_at) >= '$date' and (followups.status != 'Closed' or followups_info.disposition != 'completed') order by followups_info.id desc ");
        //dd(DB::getQueryLog()) ;
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
            $finalDatas['fdisposition'] = $flp->fdisposition;
            $finalDatas['femployee_name'] = $flp->femployee_name;
            $finalDatas['fcreated_at'] = $flp->fcreated_at;
            $finalDatas['fremarks'] = $flp->fremarks;
            
           /*  $timestamp = strtotime($flp->created_at) + strtotime($flp->estimated_response_time);
            $newtimestamp = strtotime($flp->created_at . ' + ' . $flp->estimated_response_time . ' minute'); */
            //$finalDatas['estimated_response_time'] = date('d/m H:i:', $newtimestamp);
            $finalDatas['estimated_response_time'] = $flp->estimated_response_time;
            $finalData[] = $finalDatas;
        }
        $dispositionSearch = array('call not picked','connected','not connected','pending','wrong number');
        return view('followups')->with(compact('finalData','roleData','date','dateto','remark_type','dispositionSearch','remark_typeArr'));
    }
    public function storeFollowupsForm(Request $request){
        
        $remark_type = DB::select("Select id, type from remark_type order by type ASC");
        $DateFrom=$request->input('datefrom');
        $dateto=$request->input('dateto');
        $disposition=$request->input('disposition');
        $remark_type=DB::select("Select id, type from remark_type order by type ASC");
        $date = $DateFrom; 
        $dispositionSearch = $disposition;
        $remark_typeArr = $request->input('remark_type');
        $followupsStatus='';
        foreach($remark_typeArr as $row){
            $followupsStatus .= "'".$row."',";
        }
        $followupsStatus = rtrim($followupsStatus,",");
        $followupsInfoDisposition='';
        foreach($disposition as $row){
            $followupsInfoDisposition .= "'".$row."',";
        }
        $followupsInfoDisposition = rtrim($followupsInfoDisposition,",");
        $folloupsData = DB::select("select followups.*, followups_info.disposition as call_log,
        (select group_concat(fi.disposition separator '##') from followups_info as fi where followups.id =fi.followup_id and followups.complaint_number = fi.complaint_number and fi.followup_id!='') as fdisposition,
        (select group_concat(fi.employee_name separator '##') from followups_info fi where followups.id =fi.followup_id and followups.complaint_number = fi.complaint_number) as femployee_name,
        (select group_concat(fi.created_at separator '##') from followups_info fi where followups.id =fi.followup_id and followups.complaint_number = fi.complaint_number) as fcreated_at,
        (select group_concat(fi.remarks separator '##') from followups_info fi where followups.id =fi.followup_id and followups.complaint_number = fi.complaint_number) as fremarks
        from followups left join followups_info on followups_info.followup_id = followups.id where followups.created_at >= '$DateFrom' and followups.created_at <= '$dateto' and followups.status in ($followupsStatus) or followups_info.disposition in ($followupsInfoDisposition) order by followups_info.id desc");
        $finalDatas = array();
        $finalData= array();
        $roleData = DB::select("Select id, role from mstr_role order by role ASC");
        //dd($folloupsData);
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
            $finalDatas['id'] = $flp->id;
            $finalDatas['fdisposition'] = $flp->fdisposition;
            $finalDatas['femployee_name'] = $flp->femployee_name;
            $finalDatas['fcreated_at'] = $flp->fcreated_at;
            $finalDatas['fremarks'] = $flp->fremarks;
            $finalDatas['estimated_response_time'] = $flp->estimated_response_time;
            $finalData[] = $finalDatas;
        }

        return view('followups')->with(compact('finalData','roleData','date','dateto','remark_type','dispositionSearch','remark_typeArr'));
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
    public function generalTicket(){
        if (empty(Session::get('email')) && (Session::get('role') == '29' || Session::get('role') == '30' || Session::get('role') == '87')) {
            return redirect('/'); 
        }
        $data['general_prim_dispostion'] = DB::select("Select id,disposition from general_prim_dispostion order by disposition ASC");
        return view('general_ticket')->with($data);
    }
    public function generalTicketStore(Request $request){
        if (empty(Session::get('email')) && (Session::get('role') == '29' || Session::get('role') == '30' || Session::get('role') == '87')) {
            return redirect('/'); 
        }
        $caller_name = $request->input('caller_name');
        $caller_number = $request->input('caller_number');
        $comments = $request->input('comments');
        $disposition = $request->input('disposition');
        $disposition1 = $request->input('disposition1');
        
        DB::table('general_ticket')->insert(['caller_name'=>$caller_name,'caller_number'=>$caller_number,'comments'=>$comments,'disposition'=>$disposition,'disposition1'=>$disposition1]);
        $notification = array(
            'message' => 'Stored successfully',
            'alert-type' => 'success'
        );
        return back()->with($notification);	;
    }
    public function generalTicketList(){
        if (empty(Session::get('email')) && (Session::get('role') == '29' || Session::get('role') == '30' || Session::get('role') == '87')) {
            return redirect('/'); 
        }
        $data['rowData'] = DB::select("select g.id, g.caller_name, g.caller_number, g.comments, d.disposition as prim_disposition, d1.disposition as sec_disposition,g.created_at from general_ticket as g left join general_prim_dispostion as d on d.id = g.disposition left join general_sec_dispostion as d1 on d1.id = g.disposition1 where g.is_deleted = 1");
        return view('general_ticket_list')->with($data);
    }
    public function primDisposition(Request $request){
        $id = $request->input('id');
        $query = DB::select("Select id,disposition from general_sec_dispostion where prim_id=$id order by disposition ASC");
        $rowData = '';
        foreach($query as $row){
            $rowData .= $row->id.'~~'.$row->disposition.'##';
        }
        echo $rowData;
    }
}
