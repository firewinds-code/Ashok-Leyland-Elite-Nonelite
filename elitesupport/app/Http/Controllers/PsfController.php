<?php

namespace App\Http\Controllers;

use App\Models\PsfModel;
use App\Models\PsfQuestion;
use App\Models\PsfLogModel;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PsfReportExport;
use App\Exports\PsfListReportExport;
date_default_timezone_set('Asia/Kolkata');
class PsfController extends Controller
{

   public $psf;

   public $psfQuestion;

   public $psflog;

    public function __construct(PsfModel $psf,PsfQuestion $psfQuestion, PsfLogModel $psflog)
    {
        $this->psf = $psf;
        $this->psfQuestion = $psfQuestion;
        $this->psflog = $psflog;
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            // $record  = $this->psf::where('status','!=','Closed')->orwhereNull('status')->get();
            // $record  = DB::select("SELECT * FROM psf_info where cast(created_at as date) !='2023-08-01' and (status != 'Closed' or status is NULL)");
            // return view('psf.psf_list',compact('record'));
            return view('psf.psf_list');
        } catch (\Exception $ex) {
            return view('psf.psf_list')->with('error','Something Went Wrong !');
        }
    }
    public function storePSFList(Request $request){
        try {
            // $datefrom = date('Y-m-d',strtotime($request->datefrom));
            // $dateto = date('Y-m-d',strtotime($request->dateto));
            // $data['datefrom']=  $request->datefrom;
            // $data['dateto']=  $request->dateto;
            $job_card_number =  $request->job_card_number;
            $data['job_card_number']=  $request->job_card_number;
           
           /*  $data['record']  = DB::select("SELECT * FROM psf_info where (status != 'Closed' or status is NULL) and gate_pass_date between '$datefrom' and '$dateto' "); */
           /*  $data['record']  = DB::select("SELECT * FROM psf_info where (status != 'Closed' or status is NULL) and STR_TO_DATE(gate_pass_date,'%d-%m-%Y') between '$datefrom' and '$dateto' "); */

           $data['record']  = DB::select("SELECT * FROM psf_info where /* (status != 'Closed' or status is NULL) and */ job_card_number = '$job_card_number' ");
            return view('psf.psf_list',$data);
        } catch (\Exception $th) {
            return view('psf.psf_list')->with('error','Something Went Wrong !');
        }
       
}

     /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, $id)
    {
       try {
        $record_id = \Crypt::decrypt($id);
        $record  = PsfModel::select('complaint_no','reason_of_low_rating','low_rating_remarks','feedback_status','feedback_given_by','psf_call_type','id','SAC_code','Customer_name','Customer_number','followup_number','status','disposition','sub_disposition','q1', 'q1_ans', 'q2', 'q2_ans', 'q3', 'q3_ans', 'q4', 'q4_ans', 'q5', 'q5_ans', 'q6', 'q6_ans', 'remarks','dealer_remarks')->where('id',$record_id)->get()->first();
        $questions = $this->psfQuestion::get();
        return view('psf.psf_update',compact('questions','record'));
        } catch (\Exception $ex) {
            dd($ex->getMessage());
            return view('psf.psf_update')->with('error','Something Went Wrong !');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){
        /* Generate complaint Number */
            // try {
                
                $psfInfoQuery = $this->psf::select( 'id', 'VIN', 'job_card_number', 'job_card_date', 'Vehicle_number', 'Customer_name', 'Customer_number', 'Dealer_name', 'Dealer_City', 'Dealer_state', 'SAC_code', 'zone', 'psf_call_type', 'plant_name', 'invoice_date', 'gate_pass_date', 'customer_code', 'customer', 'header_order_type', 'quotation_type', 'chassis_no', 'reg_no', 'customer_voice', 'driver_mobile', 'customer_service_contact', 'q1', 'q1_ans', 'q2', 'q2_ans', 'q3', 'q3_ans', 'q4', 'q4_ans', 'q5', 'q5_ans', 'q6', 'q6_ans', 'remarks', 'feedback_status', 'reason_of_low_rating', 'low_rating_remarks', 'feedback_given_by', 'status', 'complaint_no', 'disposition', 'sub_disposition', 'created_by', 'updated_by', 'dealer_remarks', 'created_at', 'updated_at')->find($request->psf_info_id);
                
                $reason_of_low_ratingNew =  $psfInfoQuery->reason_of_low_rating;
                $row = $this->psf::select('id','zone','complaint_no','SAC_code')->find($request->psf_info_id);
                $complaint = ($row->complaint_no != '' || $row->complaint_no != null) ? $row->complaint_no : '';
                if($request->q5_ans <=3 && $complaint == '' && $request->q5_ans !=''){
                    $prefix = 'PSF';                
                    // $query = DB::select("SELECT * FROM psf_info where complaint_no like '%PSF%' order by complaint_no desc limit 1");                
                    $zone = $row->zone!=''?$row->zone:'Blank';
                    $psfId = $row->id;
                    $zone = mb_substr($zone, 0, 1); // take first char
                    $complaint = $prefix.$zone.date('m').date('y').$psfId;
                    // if(!isset($query)){
                        // if(sizeof($query) ==0){
                        //     // dd("2");
                        //     // $complaint ='PSF'.$zone.date('m').date('y').'0001';
                        //     $complaint ='PSF'.$zone.date('m').date('y').$psfId;
                        // }else{
                        //     // dd("3");
                        //     // $complaint_no = $query[0]->complaint_no;
                        //     // $number = substr($complaint_no, 8);
                        //     // $number =  $query[0]->complaint_no;
                        //     // dd($number);
                        //     // $complaintno = str_pad(intval($number) + 1, strlen($number), '0', STR_PAD_LEFT);    //  increment 0001 to 0002 like this
                        //     // $complaint = 'PSF'.$zone.date('m').date('y').$complaintno;
                        //     $complaint = 'PSF'.$zone.date('m').date('y').$psfId;
                    // }
                    /* Triger Fresh Mail */
                        $sacCode = $row->SAC_code!=''?$row->SAC_code:'0';
                        $deaQuery = DB::table('mstr_dealer')->select('id')->where('sac_code',$sacCode)->where('flag','1')->get();
                        $dealId = sizeof($deaQuery)>0?$deaQuery[0]->id:'0';

                        /* get to anc cc from mstr_escalation level 1 */
                            $freshlevelQuery = DB::table("mstr_escalations")->select('to_role','cc_role')->where('level','1')->get();
                            $to_role = $freshlevelQuery[0]->to_role;
                            $cc_role = $freshlevelQuery[0]->cc_role;
                        /* get to anc cc from mstr_escalation level 1 */
                        

                        $toUsersSql =  DB::select("Select email,name from users where role in ($to_role) and FIND_IN_SET($dealId, dealer_id) and email !='' and flag=1 ");
                        $ccUserSql =   DB::select("Select email,name from users where role in ($cc_role) and FIND_IN_SET($dealId, dealer_id) and email !='' and flag=1 ");            
                        $toUserArr=$ccUserArr ='';
                        if(sizeof($toUsersSql)>0){
                            foreach($toUsersSql as $row){
                                if($row->email!=''){
                                    $toUser = trim($row->email);
                                    $toUser = str_replace(":",",",$toUser);
                                    $toUser = str_replace(";",",",$toUser);
                                    $toUser = str_replace(" ",",",$toUser);
                                    $toUserArr .= $toUser.",";
                                }
                            }
                            $toUserArr = rtrim($toUserArr,',');
                            $toUserArr = explode(",",$toUserArr);
                        }else{
                            $toUserArr = array("test@dispostable.com");
                        }
                        if(sizeof($ccUserSql)>0){
                            foreach($ccUserSql as $row){
                                if($row->email!=''){
                                    $ccUser = trim($row->email);
                                    $ccUser = str_replace(":",",",$ccUser);
                                    $ccUser = str_replace(";",",",$ccUser);
                                    $ccUser = str_replace(" ",",",$ccUser);
                                    $ccUserArr .= $ccUser.",";
                                }
                            }
                            $ccUserArr = rtrim($ccUserArr,',');
                            $ccUserArr = explode(",",$ccUserArr);
                        }else{
                            $ccUserArr = array("test@dispostable.com");
                        }
                        $toUserArr = array_filter($toUserArr);
                        $additionEmailCC = array("Saravanan.J@ashokleyland.com","KRY_Sanu@ashokleyland.com");
                        $mergeArray = array_merge($ccUserArr,$additionEmailCC);
                        $ccUserArr = array_filter($mergeArray);
                        
                        
                        $currentDateTime = date('Y-m-d H:i:s');            
                        //dd($$request->psf_info_id);
                        try {
                            $reason_of_low_rating = $psfInfoQuery->reason_of_low_rating;
                           
                           
                            $customerName = $psfInfoQuery->Customer_name;
                            $sacCode = $psfInfoQuery->SAC_code;
                            $dealerName = $psfInfoQuery->Dealer_name;
                            $dealerCity = $psfInfoQuery->Dealer_City;
                            $vehicleNumber = $psfInfoQuery->Vehicle_number;
                            $chassisNo = $psfInfoQuery->chassis_no;
                            $customerNumber = $psfInfoQuery->Customer_number;
                            $job_card_number = $psfInfoQuery->job_card_number;
                            $gate_pass_date = $psfInfoQuery->gate_pass_date;
                            $complaint_no = $psfInfoQuery->complaint_no;
                            $Customer_number = $psfInfoQuery->Customer_number;
                            $followup_number = $request->followup_number;
                            $lowRating = $request->ratingq5_ans!=""?implode(",",$request->ratingq5_ans):"";
                            $currentDateTime = date('Y-m-d H:i:s');
                            $subject ="PSF Ticket Details-Fresh Mail: $complaint";
                            $body = '<p>Dear Team, </p>
                            <p>Please find the details of the ticket where the customer has expressed dissatisfaction in the service rendered by us in the Post Service Feedback (PSF) survey.</p>
                            <p>Kindly resolve the customer issue, obtain the satisfaction note and update the closure details in Helpline portal.</p>
                            <table border="1" style="font-family: sans-serif;">
                                <tr>
                                    <td style="text-align: left;">Customer Name</td>
                                    <td style="text-align: left;">'.$customerName.'</td>
                                </tr>                                
                                <tr>
                                    <td style="text-align: left;">Complaint Ticket Number</td>
                                    <td style="text-align: left;">'.$complaint.'</td>
                                </tr>                                
                                <tr>
                                    <td style="text-align: left;">Outlet code</td>
                                    <td style="text-align: left;">'.$sacCode.'</td>
                                </tr>                                
                                <tr>
                                    <td style="text-align: left;"> Outlet name</td>
                                    <td style="text-align: left;">'.$dealerName.'</td>
                                </tr>                                
                                <tr>
                                    <td style="text-align: left;"> Outlet location</td>
                                    <td style="text-align: left;">'.$dealerCity.'</td>
                                </tr>                                
                                <tr>
                                    <td style="text-align: left;"> Vehicle Reg No.</td>
                                    <td style="text-align: left;">'.$vehicleNumber.'</td>
                                </tr>                                
                                <tr>
                                    <td style="text-align: left;"> Chassis Number</td>
                                    <td style="text-align: left;">'.$chassisNo.'</td>
                                </tr>                                
                                <tr>
                                    <td style="text-align: left;"> Jobcard Number</td>
                                    <td style="text-align: left;">'.$job_card_number.'</td>
                                </tr>

                                <tr>
                                    <td style="text-align: left;"> Jobcard Gatepass Date</td>
                                    <td style="text-align: left;">'.$gate_pass_date.'</td>
                                </tr>                                
                                <tr>
                                    <td style="text-align: left;"> Complaint raised Date</td>
                                    <td style="text-align: left;">'.date('d-m-Y',strtotime($currentDateTime)).'</td>
                                </tr>                                
                                <tr>
                                    <td style="text-align: left;"> Complaint raised Time</td>
                                    <td style="text-align: left;">'.date('H:i:s',strtotime($currentDateTime)).'</td>
                                </tr>                                
                                <tr>
                                    <td style="text-align: left;">Compliant Source</td>
                                    <td style="text-align: left;">PSF Survey</td>
                                </tr> 
                                <tr>
                                    <td style="text-align: left;">Feedback Received Number</td>
                                    <td style="text-align: left;">'.$followup_number.'</td>
                                </tr>                                
                                <tr>
                                    <td style="text-align: left;">Customer Voice-Reason of Dissatisfaction</td>
                                    <td style="text-align: left;">'.$lowRating.'</td>
                                </tr>                               
                            </table>
                            <p>Regards,</p>
                            <p>PSF Team</p>';
                            $data=['body'=>$body];
                            
                            /* Mail::send('assigned_email',["data"=>$data],function ($message) use ($toUserArr, $ccUserArr, $subject) {
                                $message->to(['abhishek.mudgal@cogenteservices.com','ashutosh.rawat@cogenteservices.in','al.crmautomailers@cogenteservices.in'])->subject($subject);
                                $message->from('ALHelpline@ashokleyland.com');
                            }); */
                            
                            Mail::send('assigned_email',["data"=>$data],function ($message) use ($toUserArr, $ccUserArr, $subject) {
                                $message->to($toUserArr)->cc($ccUserArr)->bcc(['al.crmautomailers@cogenteservices.in','subhrodev.sarkar@cogenteservices.com'])->subject($subject);
                                $message->from('ALHelpline@ashokleyland.com');
                            });
                            
                            $toUserArrImplode = implode(",",$toUserArr) ;
                            $ccUserArrImplode = implode(",",$ccUserArr) ;
                            
                        } catch (\Exception $jj) {
                            $excp = $jj->getMessage();
                            $excp123 = $jj->getLine();
                            //echo "DOne";
                            //dd($jj->getMessage());
                            DB::table('creation_exception')->insert(['complaint_number'=>"$complaint",'type'=>"PSF Fresh Complaint Email",'exception'=>"$excp: $excp123"]);
                        }
                        try {
                            DB::select("INSERT INTO escaltion_psf_levels (complaint_no, levels) VALUES ('$complaint', '1')");
                            DB::select("INSERT INTO email_status (type, subject, body, toMail, ccMail) VALUES ('PSF Fresh Complaint', '$subject', '$body','$toUserArrImplode','$ccUserArrImplode')");
                        }  catch (\Exception $error) {
                            $excpError = $error->getMessage();
                            $excpError123 = $error->getLine();
                            //echo "DOne";
                            //dd($jj->getMessage());
                            DB::table('creation_exception')->insert(['complaint_number'=>"$complaint",'type'=>"PSF Fresh Complaint Email",'exception'=>"$excpError: $excpError123"]);
                        }
                    /* Triger Fresh Mail */
                }
            // } catch (\Exception $ex) {
            //     $msg = $ex->getMessage();
            //     DB::table('creation_exception')->insert(['complaint_number'=>"PSF Creation Complaint Error",'type'=>"Line 477 to 496",'exception'=>"$msg"]);
            // }           
        /* Generate complaint Number */            
        $record = [
            "q1" => $request->q1,"q1_ans" => $request->q1_ans,
            "q2" => $request->q2,"q2_ans" => $request->q2_ans,
            "q3" =>$request->q3,"q3_ans" => $request->q3_ans,
            "q4" =>$request->q4,"q4_ans" =>$request->q4_ans,
            "q5" =>$request->q5,"q5_ans" =>$request->q5_ans,
            "q6" => $request->q6,"q6_ans" => $request->q6_ans,
            "remarks" => $request->remarks,
            "status" => $request->status!=''?$request->status:'Pending',
            // "sac_code" =>$request->sac_code,
            "followup_number" => $request->followup_number,
            "disposition" => $request->disposition, "sub_disposition" => $request->sub_disposition,
            'complaint_no' => $complaint!=''?$complaint:null,
            'feedback_given_by' => $request->feedback_given_by,
            'feedback_status' => $request->feedback_status,
            'low_rating_remarks' => $request->other_remarks,
            "reason_of_low_rating" => $request->ratingq5_ans!=''?implode(",",$request->ratingq5_ans):'',
            "updated_by" => Auth::user()->name
        ];
        try{
            if($reason_of_low_ratingNew != ''){
                unset($record['reason_of_low_rating']);
                $condtion = $this->psf::where('id',$request->psf_info_id)->update($record);
            }else{
                $condtion = $this->psf::where('id',$request->psf_info_id)->update($record);
            }
           
            if($condtion)
            {
                unset($request['_token']);
                $log = ['psf_info_id' => $request->psf_info_id, 'json_records' => json_encode($request->all()), 'action_by'=> Auth::user()->name, 'action_by_id' => Auth::user()->employee_id];
                $insert = $this->psflog::create($log);
                $request->session()->flash('success', 'Survey Updated Successfully !!');
                return redirect()->back();
            }
        }catch (\Exception $aa) {
            $aaq = $aa->getMessage();
            DB::table('creation_exception')->insert(['complaint_number'=>"PSF Creation Error",'type'=>"Line 477 to 496",'exception'=>"$aaq"]);
            dd($aa->getMessage());
        }
            
        
        
    }





    public function updateDealer(Request $request)
    {
        /* Generate complaint Number */
        try {
            $row = $this->psf::select('zone','complaint_no')->find($request->psf_info_id);
            $complaint = ($row->complaint_no != '' || $row->complaint_no != null) ? $row->complaint_no : '';
            if($request->q5_ans <=3 && $complaint == '')
            {

                $prefix = 'PSF';
                // $query = $this->psf::where('complaint_no', 'like', $prefix.'%')
                // $query = $this->psf::where('complaint_no', 'like', '% '.$prefix.'%')
                // ->orderBy('id','desc')
                // ->select('complaint_no')->first();
                $query = DB::select("SELECT * FROM psf_info where complaint_no like '%PSF%' order by complaint_no desc limit 1");
                // dd($query[0]->complaint_no);
                $zone = $row->zone!=''?$row->zone:'Blank';
                $zone = mb_substr($zone, 0, 1); // take first char
                if(!isset($query)){
                    // dd("2");
                    $complaint ='PSF'.$zone.date('m').date('y').'0001';
                }else{
                    // dd("3");
                    $complaint_no = $query[0]->complaint_no;
                    $number = substr($complaint_no, 8);
                    // dd($number);
                    $complaintno = str_pad(intval($number) + 1, strlen($number), '0', STR_PAD_LEFT);    //  increment 0001 to 0002 like this
                    $complaint = 'PSF'.$zone.date('m').date('y').$complaintno;
                }
                 /* if($complaint_no != '' || $complaint_no != Null){
                    $complaint = ($row->complaint_no != '' || $row->complaint_no != null) ? $row->complaint_no : '';
                 }else{
                    $complaint =  $complaint + 1;
                 } */
            }
            // dd($complaint);
            /* Generate complaint Number */


            $record = [
                // "q1" => $request->q1,"q1_ans" => $request->q1_ans,
                // "q2" => $request->q2,"q2_ans" => $request->q2_ans,
                // "q3" =>$request->q3,"q3_ans" => $request->q3_ans,
                // "q4" =>$request->q4,"q4_ans" =>$request->q4_ans,
                // "q5" =>$request->q5,"q5_ans" =>$request->q5_ans,
                // "q6" => $request->q6,"q6_ans" => $request->q6_ans,
                "remarks" => $request->remarks,
                "status" => $request->status,
                "followup_number" => $request->followup_number,
                // "disposition" => $request->disposition, 
                // "sub_disposition" => $request->sub_disposition,
                // 'complaint_no' => $complaint,
                'feedback_given_by' => $request->feedback_given_by,
                // 'low_rating_remarks' => $request->other_remarks,
                // "reason_of_low_rating" => $request->ratingq5_ans,
                "dealer_remarks" => $request->dealer_remarks,
                "updated_by" => Auth::user()->name];
                $condtion = $this->psf::where('id',$request->psf_info_id)->update($record);
                if($condtion)
                {
                    unset($request['_token']);
                    $log = ['psf_info_id' => $request->psf_info_id, 'json_records' => json_encode($request->all()), 'action_by'=> Auth::user()->name, 'action_by_id' => Auth::user()->employee_id];
                    $insert = $this->psflog::create($log);
                    $request->session()->flash('success', 'Survey Updated Successfully !!');
                    return redirect()->back();
                }
        } catch (\Exception $ex) {
            $msg = $ex->getMessage();
            $request->session()->flash('error',$msg);
            return redirect()->back();
        }
    }






    /**
     * Display the specified resource.
     */
    public function show(PsfModel $psf)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function surveyList(Request $request)
    {
        try {
            $dealerId = Auth::user()->dealer_id;
            $dealerId = $dealerId !=''?$dealerId:'0';
            $dealScaCode = DB::select("select sac_code from mstr_dealer where id in ($dealerId)");
            $scCode='';
            foreach($dealScaCode as $row){
                $scCode .="'".$row->sac_code."',";
            }
            $scCode = rtrim($scCode,",");
            // $record  = $this->psf::where('status','!=','Closed')->whereNull('complaint_no')->get();
            $record = DB::select("SELECT * FROM psf_info where SAC_code in ($scCode) and (status != 'Closed'  or status is null ) and (complaint_no is null or complaint_no = '') and (feedback_status is not null or feedback_status != '') ");
            // dd($record);
            $title = 'Survey List';
            return view('psf.psf_dealer_list',compact('record','title'));
        } catch (\Exception $ex) {
            return view('psf.psf_dealer_list')->with('error','Something Went Wrong !');
        }
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function complaintList(Request $request)
    {
        try {
            $dealerId = Auth::user()->dealer_id;
            $dealerId = $dealerId !=''?$dealerId:'0';
            $dealScaCode = DB::select("select sac_code from mstr_dealer where id in ($dealerId)");
            $scCode='';
            foreach($dealScaCode as $row){
                $scCode .="'".$row->sac_code."',";
            }
            $scCode = rtrim($scCode,",");
            // dd($scCode);
            // $record  = $this->psf::where('status','!=','Closed')->whereNotNull('complaint_no')->get();
            /* $record = DB::select("SELECT * FROM psf_info where SAC_code in ($scCode) and (status != 'Closed') and (complaint_no is not null or complaint_no !='')"); */
            $record = DB::select("SELECT p.*,l.complaintDate FROM psf_info as p
            left join (select psf_info_id,min(created_at) as  complaintDate from psf_info_logs  group by psf_info_id) l on p.id =l.psf_info_id
             where p.SAC_code in ($scCode) and (p.status != 'Closed') and (p.complaint_no is not null or p.complaint_no !='')");
            $title = 'Complaint List';
            return view('psf.psf_dealer_list',compact('record','title'));
        } catch (\Exception $ex) {
            return view('psf.psf_dealer_list')->with('error','Something Went Wrong !');
        }
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function surveyForm(Request $request, $id)
    {
        try {
            $record_id = \Crypt::decrypt($id);
            $record  = PsfModel::select('id','complaint_no','reason_of_low_rating','low_rating_remarks','feedback_status','feedback_given_by','psf_call_type','id','SAC_code','Customer_name','Customer_number','status','disposition','sub_disposition','q1', 'q1_ans', 'q2', 'q2_ans', 'q3', 'q3_ans', 'q4', 'q4_ans', 'q5', 'q5_ans', 'q6', 'q6_ans', 'remarks','followup_number')->where('id',$record_id)->get()->first();
            $questions = $this->psfQuestion::get();
            $title = 'Survey List';
            return view('psf.psf_dealer_survey',compact('questions','record','title'));
            } catch (\Exception $ex) {
                dd($ex->getMessage());
                return view('psf.psf_dealer_survey')->with('error','Something Went Wrong !');
            }
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function complaintForm(Request $request, $id)
    {

        try {
            $record_id = \Crypt::decrypt($id);
            $record  = PsfModel::select('id','complaint_no','reason_of_low_rating','low_rating_remarks','feedback_status','feedback_given_by','psf_call_type','id','SAC_code','Customer_name','Customer_number','status','disposition','sub_disposition','q1', 'q1_ans', 'q2', 'q2_ans', 'q3', 'q3_ans', 'q4', 'q4_ans', 'q5', 'q5_ans', 'q6', 'q6_ans', 'remarks','dealer_remarks','followup_number')->where('id',$record_id)->get()->first();
            $questions = $this->psfQuestion::get();
            $title = 'Complaint List';
            return view('psf.psf_dealer_survey',compact('questions','record','title'));
            } catch (\Exception $ex) {
                dd($ex->getMessage());
                return view('psf.psf_dealer_survey')->with('error','Something Went Wrong !');
            }
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PsfModel $psf)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PsfModel $psf)
    {
        //
    }

    public function psfReport(){
        return view('psf.psf_report');
    }
    public function storePsfReport(Request $request){
        try {
            $datefrom = $request->datefrom." 00:00:01";
            $dateto = $request->dateto." 23:59:59";
            // $datefrom = date('d-m-Y',strtotime($request->datefrom));
            // $dateto = date('d-m-Y',strtotime($request->dateto));
            $data['datefrom']=  $request->datefrom;  
            $data['dateto']=  $request->dateto;  
            
            

            /*  */
            $dealerId = Auth::user()->dealer_id;
            $dealerId = $dealerId !=''?$dealerId:'0';
            $dealScaCode = DB::select("select sac_code from mstr_dealer where id in ($dealerId)");
            $scCode='';
            foreach($dealScaCode as $row){
                $scCode .="'".$row->sac_code."',";
            }
            $scCode = rtrim($scCode,",");
            $role = Auth::user()->role;
            $dubleQuate = '"';
            /*  */
            if($role == 29 ||  $role == 30){
                $data['rowData']= DB::select("select p.id, p.VIN, p.job_card_number, p.job_card_date, p.Vehicle_number, p.Customer_name, p.Customer_number, p.Dealer_name, p.Dealer_City, p.Dealer_state, p.SAC_code, p.zone, p.psf_call_type, p.plant_name, p.invoice_date, p.gate_pass_date, p.customer_code, p.customer, p.header_order_type, p.quotation_type, p.chassis_no, p.reg_no, p.customer_voice, p.driver_mobile, p.customer_service_contact, ques1.question as question1, p.q1_ans, ques2.question as question2, p.q2_ans, ques3.question as question3, p.q3_ans, ques4.question as question4, p.q4_ans, ques5.question as question5, p.q5_ans, ques6.question as question6, p.q6_ans, p.remarks, p.feedback_status, p.reason_of_low_rating, p.low_rating_remarks, p.feedback_given_by, p.status, p.complaint_no, p.disposition, p.sub_disposition, p.created_by, p.updated_by, p.dealer_remarks, p.created_at, p.updated_at, l.created_at as complaintDate, p.followup_number from psf_info as p 
                left join psf_question as ques1 on p.q1 = ques1.id 
                left join psf_question as ques2 on p.q2 = ques2.id 
                left join psf_question as ques3 on p.q3 = ques3.id 
                left join psf_question as ques4 on p.q4 = ques4.id 
                left join psf_question as ques5 on p.q5 = ques5.id
                left join (select psf_info_id,min(created_at) created_at from psf_info_logs  group by psf_info_id) l on p.id =l.psf_info_id
                
                left join psf_question as ques6 on p.q6 = ques6.id where l.created_at between '$datefrom' and '$dateto' and p.feedback_status is not NULL");
            }else{
                
                $data['rowData']= DB::select("select p.id, p.VIN, p.job_card_number, p.job_card_date, p.Vehicle_number, p.Customer_name, p.Customer_number, p.Dealer_name, p.Dealer_City, p.Dealer_state, p.SAC_code, p.zone, p.psf_call_type, p.plant_name, p.invoice_date, p.gate_pass_date, p.customer_code, p.customer, p.header_order_type, p.quotation_type, p.chassis_no, p.reg_no, p.customer_voice, p.driver_mobile, p.customer_service_contact, ques1.question as question1, p.q1_ans, ques2.question as question2, p.q2_ans, ques3.question as question3, p.q3_ans, ques4.question as question4, p.q4_ans, ques5.question as question5, p.q5_ans, ques6.question as question6, p.q6_ans, p.remarks, p.feedback_status, p.reason_of_low_rating, p.low_rating_remarks, p.feedback_given_by, p.status, p.complaint_no, p.disposition, p.sub_disposition, p.created_by, p.updated_by, p.dealer_remarks, p.created_at, p.updated_at, l.created_at as complaintDate, p.followup_number from psf_info as p 
                left join psf_question as ques1 on p.q1 = ques1.id 
                left join psf_question as ques2 on p.q2 = ques2.id 
                left join psf_question as ques3 on p.q3 = ques3.id 
                left join psf_question as ques4 on p.q4 = ques4.id 
                left join psf_question as ques5 on p.q5 = ques5.id
                left join (select psf_info_id,min(created_at) created_at from psf_info_logs  group by psf_info_id) l on p.id =l.psf_info_id
                left join psf_question as ques6 on p.q6 = ques6.id where p.SAC_code in ($scCode) and l.created_at between '$datefrom' and '$dateto' and p.feedback_status is not NULL");
            }
            $data['questionData']= DB::select("select id,question from psf_question");
            
        return view('psf.psf_report',$data);
        } catch (\Exception $th) {
            
            return view('psf.psf_report')->with('error','Something Went Wrong !');
        }
    }
    public function psfReportForComplaintNo(Request $request)
    {
          try {
            $fileName = 'Complaint_Log_Report.xlsx';
            return Excel::download(new PsfReportExport, $fileName);
          } catch (\Exception $ex) {
            dd( $ex->getMessage());
            $notification = array(
				'message' => "Something Went Wrong !",
				'alert-type' => 'error'
				);
				return back()->with($notification);
          }


    }
    public function psfListReport(Request $request)
    {
          try {
            $fileName = 'PSF_List_Report.xlsx';
            return Excel::download(new PsfListReportExport, $fileName);
          } catch (\Exception $ex) {
            $notification = array(
				'message' => "Something Went Wrong !",
				'alert-type' => 'error'
				);
				return back()->with($notification);
          }


    }
}
