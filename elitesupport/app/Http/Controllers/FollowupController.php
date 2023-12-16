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
use Auth;

date_default_timezone_set('Asia/Kolkata');

class FollowupController extends Controller
{
    public function __construct()
    { 
        DB::enableQueryLog();
        DB::select("SET sql_mode=''");
        DB::select("SET sql_safe_updates=0");
    }


    public function index()
    {
       
        $remark_type = DB::select("Select id, type from remark_type where type !='Closed' order by type ASC");
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
       
        
        /* $folloupsData = DB::select("select followups.*, followups_info.disposition as call_log,
        (select group_concat(fi.disposition separator '##') from followups_info as fi where followups.id =fi.followup_id and followups.complaint_number = fi.complaint_number and fi.followup_id!='' order by fi.id desc) as fdisposition,
        (select group_concat(fi.employee_name separator '##') from followups_info fi where followups.id =fi.followup_id and followups.complaint_number = fi.complaint_number order by fi.id desc) as femployee_name,
        (select group_concat(fi.created_at separator '##') from followups_info fi where followups.id =fi.followup_id and followups.complaint_number = fi.complaint_number order by fi.id desc) as fcreated_at,
        (select group_concat(fi.remarks separator '##') from followups_info fi  where followups.id =fi.followup_id and followups.complaint_number = fi.complaint_number order by fi.id desc) as fremarks, c.estimated_response_time as case_estimated_response_time,c.assign_work_manager,c.assign_work_manager_mobile,c.remark_type as ticket_status,c.actual_response_time,c.followup_time,c.id as caseId
        from followups left join followups_info on followups_info.complaint_number = followups.complaint_number left join cases as c on c.complaint_number =followups.complaint_number where date(followups.created_at) >= '$date' and c.remark_type not in ('Closed','Completed') and c.complaint_number!='' group by followups.complaint_number order by c.id desc"); */
        $folloupsData = DB::select("select followups.*, c.estimated_response_time as case_estimated_response_time,c.assign_work_manager,c.assign_work_manager_mobile,c.remark_type as ticket_status,c.actual_response_time,c.followup_time,c.id as caseId
        from followups left join cases as c on c.complaint_number =followups.complaint_number where date(followups.created_at) >= '$date' and c.remark_type not in ('Closed','Completed') and c.complaint_number!='' group by followups.complaint_number order by c.id desc");
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
            /* $finalDatas['call_log'] = $flp->call_log; */
            //$finalDatas['status']=$flp->status;
            $finalDatas['id'] = $flp->id;
            /* $finalDatas['fdisposition'] = $flp->fdisposition;
            $finalDatas['femployee_name'] = $flp->femployee_name;
            $finalDatas['fcreated_at'] = $flp->fcreated_at;
            $finalDatas['fremarks'] = $flp->fremarks; */
            $finalDatas['followup_time'] = $flp->followup_time;
            $finalDatas['actual_response_time'] = $flp->actual_response_time;
            
           /*  $timestamp = strtotime($flp->created_at) + strtotime($flp->estimated_response_time);
            $newtimestamp = strtotime($flp->created_at . ' + ' . $flp->estimated_response_time . ' minute'); */
            //$finalDatas['estimated_response_time'] = date('d/m H:i:', $newtimestamp);
            $finalDatas['case_estimated_response_time'] = $flp->case_estimated_response_time;
            $finalDatas['assign_work_manager'] = $flp->assign_work_manager;
            $finalDatas['assign_work_manager_mobile'] = $flp->assign_work_manager_mobile;
            $finalDatas['ticket_status'] = $flp->ticket_status;
            $finalDatas['caseId'] = $flp->caseId;
            $finalData[] = $finalDatas;
        }
        $dispositionSearch = array('call not picked','connected','not connected','pending','wrong number');
        return view('followups')->with(compact('finalData','roleData','date','dateto','remark_type','dispositionSearch','remark_typeArr'));
    }
    public function storeFollowupsForm(Request $request){
        
        $remark_type = DB::select("Select id, type from remark_type order by type ASC");
        $DateFrom=$request->input('datefrom');
        $dateto=$request->input('dateto');
       /*  $disposition=$request->input('disposition'); */
        $remark_type=DB::select("Select id, type from remark_type order by type ASC");
        $date = $DateFrom; 
       /*  $dispositionSearch = $disposition; */
        $remark_typeArr = $request->input('remark_type');
        $followupsStatus='';
        foreach($remark_typeArr as $row){
            $followupsStatus .= "'".$row."',";
        }
        $followupsStatus = rtrim($followupsStatus,",");
        /* $followupsInfoDisposition='';
        foreach($disposition as $row){
            $followupsInfoDisposition .= "'".$row."',";
        }
        $followupsInfoDisposition = rtrim($followupsInfoDisposition,","); */
        
       /*  $folloupsData = DB::select("select followups.*, followups_info.disposition as call_log,
        (select group_concat(fi.disposition separator '##') from followups_info as fi where followups.id =fi.followup_id and followups.complaint_number = fi.complaint_number and fi.followup_id!='') as fdisposition,
        (select group_concat(fi.employee_name separator '##') from followups_info fi where followups.id =fi.followup_id and followups.complaint_number = fi.complaint_number) as femployee_name,
        (select group_concat(fi.created_at separator '##') from followups_info fi where followups.id =fi.followup_id and followups.complaint_number = fi.complaint_number) as fcreated_at,
        (select group_concat(fi.remarks separator '##') from followups_info fi where followups.id =fi.followup_id and followups.complaint_number = fi.complaint_number) as fremarks, c.estimated_response_time as case_estimated_response_time,c.assign_work_manager,c.assign_work_manager_mobile,c.remark_type as ticket_status,c.actual_response_time,c.followup_time,c.id as caseId
        from followups left join followups_info on followups_info.followup_id = followups.id left join cases as c on c.complaint_number =followups.complaint_number  where followups.created_at >= '$DateFrom' and followups.created_at <= '$dateto' and c.remark_type in ($followupsStatus) and c.complaint_number!='' group by followups.complaint_number order by c.id desc"); */
        $folloupsData = DB::select("select followups.*, c.estimated_response_time as case_estimated_response_time,c.assign_work_manager,c.assign_work_manager_mobile,c.remark_type as ticket_status,c.actual_response_time,c.followup_time,c.id as caseId
        from followups left join cases as c on c.complaint_number =followups.complaint_number  where followups.created_at >= '$DateFrom' and followups.created_at <= '$dateto' and c.remark_type in ($followupsStatus) and c.complaint_number!='' group by followups.complaint_number order by c.id desc");
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
            /* $finalDatas['call_log'] = $flp->call_log; */
            $finalDatas['id'] = $flp->id;
           /*  $finalDatas['fdisposition'] = $flp->fdisposition;
            $finalDatas['femployee_name'] = $flp->femployee_name;
            $finalDatas['fcreated_at'] = $flp->fcreated_at;
            $finalDatas['fremarks'] = $flp->fremarks; */
            $finalDatas['case_estimated_response_time'] = $flp->case_estimated_response_time;
            $finalDatas['assign_work_manager'] = $flp->assign_work_manager;
            $finalDatas['assign_work_manager_mobile'] = $flp->assign_work_manager_mobile;
            $finalDatas['ticket_status'] = $flp->ticket_status;
            $finalDatas['followup_time'] = $flp->followup_time;
            $finalDatas['actual_response_time'] = $flp->actual_response_time;
            $finalDatas['caseId'] = $flp->caseId;
            $finalData[] = $finalDatas;
        }

        return view('followups')->with(compact('finalData','roleData','date','dateto','remark_type','remark_typeArr'));
    }
    public function storeFolloupInfo(Request $request)
    {
        //dd($_POST);
        try {
            $currentUserMail = Auth::user()->email;
            $loginId = Auth::user()->employee_id;
            $loginName = Auth::user()->name;
            DB::table('followups_info')->insert(['complaint_number' => "$request->complaint_number", 'employee_name' => "$loginName", 'employee_id' => "$currentUserMail", 'disposition' => "$request->disposition", 'remarks' => "$request->remarks", 'followup_id' => "$request->id",  'attempt' => "$request->attempt"]);
            $notification = array(
                'message' => 'Data Save Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('followups')->with($notification);
        } catch (\Exception $ex) {
            $notification = array(
                'message' => $ex->getMessage(),
                'alert-type' => 'error'
            );
            return redirect()->route('followups')->with($notification);
        }
    }

    public function getFollupinfo(Request $request)
    {
       
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
       $loginName = Auth::user()->name;
       $currentUserMail = Auth::user()->email;
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
        if (empty(Auth::user()->email) && (Auth::user()->role == '29' || Auth::user()->role == '30' || Auth::user()->role == '87')) {
            return redirect('/'); 
        }
        $data['general_prim_dispostion'] = DB::select("Select id,disposition from general_prim_dispostion where id > 5 order by disposition ASC");
        return view('general_ticket')->with($data);
    }
    public function generalTicketStore(Request $request){
        if (empty(Auth::user()->email) && (Auth::user()->role == '29' || Auth::user()->role == '30' || Auth::user()->role == '87')) {
            return redirect('/'); 
        }
        $caller_name = $request->input('caller_name');
        $caller_number = $request->input('caller_number');
        $comments = $request->input('comments');
        $disposition = $request->input('disposition');
        $disposition1 = $request->input('disposition1');
        $created_by = Auth::user()->name;
        
        DB::table('general_ticket')->insert(['caller_name'=>$caller_name,'caller_number'=>$caller_number,'comments'=>$comments,'disposition'=>$disposition,'disposition1'=>$disposition1,'created_by'=>$created_by]);
        $notification = array(
            'message' => 'Stored successfully',
            'alert-type' => 'success'
        );
        return back()->with($notification);	;
    }
    public function generalTicketList(){
        if (empty(Auth::user()->email) && (Auth::user()->role == '29' || Auth::user()->role == '30' || Auth::user()->role == '87')) {
            return redirect('/'); 
        }
        $dateFrom = date('Y-m-d', strtotime('-5 days'));
        $dateTo = date('Y-m-d');
        $data['dateFrom'] = $dateFrom;
        $data['dateTo'] = $dateTo;
        $data['rowData'] = DB::select("select g.id, g.caller_name, g.caller_number, g.comments, d.disposition as prim_disposition, d1.disposition as sec_disposition,g.created_at,g.created_by from general_ticket as g left join general_prim_dispostion as d on d.id = g.disposition left join general_sec_dispostion as d1 on d1.id = g.disposition1 where g.is_deleted = 1 and cast(date(g.created_at) as date) >= '$dateFrom'");
        return view('general_ticket_list')->with($data);
    }
    public function storeGeneralTicket(Request $request){
        $dateFrom = $request->input('datefrom');
        $dateTo = $request->input('dateto');
        $data['dateFrom'] = $dateFrom;
        $data['dateTo'] = $dateTo;
        $data['rowData'] = DB::select("select g.id, g.caller_name, g.caller_number, g.comments, d.disposition as prim_disposition, d1.disposition as sec_disposition,g.created_at,g.created_by from general_ticket as g left join general_prim_dispostion as d on d.id = g.disposition left join general_sec_dispostion as d1 on d1.id = g.disposition1 where g.is_deleted = 1 and date(g.created_at) >= :dateFrom and date(g.created_at) <= :dateTo",["dateFrom"=>$dateFrom,"dateTo"=>$dateTo]);
        //dd( $data['rowData']);
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
