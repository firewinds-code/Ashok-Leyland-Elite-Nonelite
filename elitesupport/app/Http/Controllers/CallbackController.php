<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Validator;

use App\Updateticket;
use Auth;
use DB;
date_default_timezone_set('Asia/Kolkata');
class CallbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        try {
            $validator = Validator::make($request->all(),[

                'ticket_no' => 'required',
                // 'reg_no' => 'required',
                // 'chasi_no' => 'required',
                // 'eng_no' =>  'required',
                // 'ticket_type' => 'required',
                // 'dealer_name' => 'required',
                // 'vin_no' => 'required',
                // 'status' =>    'required',
                // 'dealer_code' =>    'required',
    
                
                // 'msv_type' =>  'required',
                // 'msv_reg_no' => 'required',
                // 'mechinic_name' => 'required',
                // 'mechinic_number' => 'required',
                // // 'remark' => 'required',
                // 'start_time' =>  'required',
                // //'response_time_No' => 'required',
                // //'restore_time_No' =>  'required',
                // //'pause_time_No' =>  'required',
                // //'end_time' => 'required',
                // // 'url' => 'required',
                // 'vehicle_mode' => 'required',
                // //'restoration_type' => 'required',
                // //'actual_response_time' => 'required',
                // //'restoration_time' => 'required'
            ]);
    
            if($validator->fails()){
                return response()->json($validator->errors());
            }
            /* Update Vehicle Master */
            $ticket_no = $request->ticket_no;
            $query =  DB::table('cases')->select('vehicleId','remark_type','assign_remarks','restoration_type','actual_response_time','tat_scheduled','response_delay_reason','aggregate','restoration_delay')->where('complaint_number',$ticket_no)->get();
            if(sizeof($query)==0){
                return response()->json(["Status"=>"Error","Message"=>"Ticket not found"],404);
            }
            $vehicleId  = sizeof($query)>0?$query[0]->vehicleId:'NA0000';
            $db_remark_type = $query[0]->remark_type;
            $db_assign_remarks = $query[0]->assign_remarks;
            $db_restoration_type = $query[0]->restoration_type;
            $db_actual_response_time = $query[0]->actual_response_time;
            $db_tat_scheduled = $query[0]->tat_scheduled;
            $db_response_delay_reason = $query[0]->response_delay_reason;
            $db_aggregate = $query[0]->aggregate;
            $db_restoration_delay = $query[0]->restoration_delay;
            
            $mstr_vehicleQuery = DB::table('mstr_vehicle')->select('reg_number','chassis_number','engine_number')->where('id',$vehicleId)->get();
            
            if(sizeof($mstr_vehicleQuery)>0){
                foreach($mstr_vehicleQuery as $row){
                    $reg_number = $row->reg_number;
                    $chassis_number = $row->chassis_number;
                    $engine_number = $row->engine_number;
                    $reg_no =  $request->reg_no;
                    $chasi_no =  $request->chasi_no;
                    $eng_no =  $request->eng_no;
                    if($reg_no !='' && $reg_number != $reg_no){
                        DB::table('mstr_vehicle')->where('id', $vehicleId)->update(['reg_number' => $reg_no]);
                    }
                    if($chasi_no !='' && $chassis_number != $chasi_no){
                        DB::table('mstr_vehicle')->where('id', $vehicleId)->update(['chassis_number' => $chasi_no]);
                    }
                    if($eng_no !='' && $engine_number != $eng_no){
                        DB::table('mstr_vehicle')->where('id', $vehicleId)->update(['engine_number' => $eng_no]);
                    }                    
                }
            }
            if($request->cc_status !=''){
                $cc_status = $request->cc_status;
                if($cc_status == 'Completed'){
                    $arr = array(                    
                        'complaint_number' => $request->ticket_no,
                        'remark_type' => $request->cc_status,
                        'employee_name' => "Al Live",
                        'employee_id' =>   "Al Live",
                        'dealer_mob_number' => "",
                        'dealer_alt_mob_number' => "",
                        'assign_to' =>   $request->dealer_name,
                        'disposition' =>   "",
                        'agent_remark' => $this->removeSpecialChar($request->remark),
                        'assign_remarks' => $this->removeSpecialChar($request->remark),
                        'tat_scheduled' => $request->restoration_time,
                        'feedback_rating' => $request->feedback_rating,
                        'feedback_desc' => $request->feedback_desc,
                        'actual_response_time' => $request->actual_response_time                    
                    );
                    DB::table('remarks')->insert($arr);
                } 
                DB::table('cases')->where('complaint_number', $ticket_no)->update(['remark_type' => $cc_status]);
                
            }
            if($request->remark !=''){
                $msuremark = $this->removeSpecialChar($request->remark);
                DB::table('cases')->where('complaint_number', $ticket_no)->update(['assign_remarks' => $msuremark]);
            }
            if($request->restoration_type !=''){
                $msurestoration_type = $request->restoration_type;
                DB::table('cases')->where('complaint_number', $ticket_no)->update(['restoration_type' => $msurestoration_type]);
            }
            if($request->actual_response_time !=''){
                $msuactual_response_time = $request->actual_response_time;
                DB::table('cases')->where('complaint_number', $ticket_no)->update(['actual_response_time' => $msuactual_response_time]);
            }
            if($request->restoration_time !=''){
                $msurestoration_time = $request->restoration_time;
                DB::table('cases')->where('complaint_number', $ticket_no)->update(['tat_scheduled' => $msurestoration_time]);
            }
            if($request->response_delay_reason !=''){
                $msuresponse_delay_reason = $request->response_delay_reason;
                DB::table('cases')->where('complaint_number', $ticket_no)->update(['response_delay_reason' => $msuresponse_delay_reason]);
            }
            if($request->aggregate !=''){
                $msuaggregate = $request->aggregate;
                DB::table('cases')->where('complaint_number', $ticket_no)->update(['aggregate' => $msuaggregate]);
            }
            if($request->restoration_delay !=''){
                $msurestoration_delay = $request->restoration_delay;
                DB::table('cases')->where('complaint_number', $ticket_no)->update(['restoration_delay' => $msurestoration_delay]);
            }
            if($request->feedback_rating !=''){
                $msufeedback_rating = $request->feedback_rating;
                DB::table('cases')->where('complaint_number', $ticket_no)->update(['feedback_rating' => $msufeedback_rating]);
            }
            if($request->feedback_desc !=''){
                $msufeedback_desc = $request->feedback_desc;
                DB::table('cases')->where('complaint_number', $ticket_no)->update(['feedback_desc' => $msufeedback_desc]);
            }
            /* Update Vehicle Master */
    
            $program = Updateticket::create([
    
                'ticket_num' => $request->ticket_no,
                'reg_no' => $request->reg_no,
                'chasi_no' => $request->chasi_no,
                'eng_no' =>   $request->eng_no,
                'ticket_type' => $request->ticket_type,
                'vin_no' => $request->vin_no,
                'status' =>   $request->status,
                'dealer_code' =>   $request->dealer_code,
                'msv_type' =>  $request->msv_type,
                'msv_reg_no' => $request->msv_reg_no,
                'mechinic_name' => $request->mechinic_name,
                'mechinic_number' => $request->mechinic_number,
                'dealer_name'  => $request->dealer_name,
                'remark' => $this->removeSpecialChar($request->remark),
                'start_time' =>   $request->start_time,
                'response_time_No' => $request->response_time_No,
                'restore_time_No' =>   $request->restore_time_No,
                'pause_time_No' =>   $request->pause_time_No,
                'end_time' => $request->end_time,
                'url' => $request->url,
                'vehicle_mode' => $request->vehicle_mode,
                'restoration_type' => $request->restoration_type,
                'actual_response_time' => $request->actual_response_time,
                'restoration_time' => $request->restoration_time,
                'response_delay_reason' => $request->response_delay_reason,
                'restoration_delay' => $request->restoration_delay,
                'cc_status' => $request->cc_status,
                'aggregate' => $request->aggregate,
                'feedback_rating' => $request->feedback_rating,
                'feedback_desc' => $request->feedback_desc
    
             ]);
    
            return response()->json(['result' => 'received successfully.', 'ticket_no' => $request->ticket_no, 'ack_no' => '1']);
        } catch (\Exception $th) {
            $exc = $th->getMessage();
            $ticket_no = $request->ticket_no;
            DB::table('updation_exception')->insert(['complaint_number'=>"$ticket_no",'type'=>"Call BAck Exception",'exception'=>"$exc"]);
            return response()->json(['result' => 'Error','Meesage' => $exc]);
        }
    }
    public function removeSpecialChar($str) {
 
		// Using str_replace() function
		// to replace the word
		$res = str_replace( array( '\'', '"', ';', '<', '>' ), ' ', $str);
		$string = str_replace(array("\n", "\r"), '', $res);
		// Returning the result
		return $string;
	}
   
}
