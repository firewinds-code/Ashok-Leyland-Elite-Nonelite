<?php

namespace App\Http\Controllers\Followup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Exception;
use Illuminate\Support\Carbon;
use Auth;

class CompleteFeedbackController extends Controller
{
    public function __construct(){
    	date_default_timezone_set('Asia/Kolkata');
		DB::select("SET sql_mode=''");
        DB::select("SET sql_safe_updates=0");
	}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try{
            //echo Carbon::now();
            $trialExpires = Carbon::now()->addMinutes(-15)->format('Y-m-d H:i:s');
            DB::table('cogent_complete_followups')
                 ->where('lock_status', '1')
                 ->whereIn('flag', ['0'])
                ->where('updated_at', '<=',$trialExpires)
                 ->update(['lock_status' => 0]);
            /* Followup */
                $trialExpiresFollowup = Carbon::now()->addMinutes(-30)->format('Y-m-d H:i:s');
                DB::table('cogent_complete_followups')
                ->where('lock_status', '1')
                ->whereIn('flag', ['1'])
                ->where(function ($query) {
                    $query->orwhere('first_disposition', 'Non-Contacted')
                        ->orwhere('followup_disposition', 'Non-Contacted');
                })
                //  ->where('DATE_ADD("updated_at", INTERVAL 30 minute)', '<=', now())
                ->where('updated_at', '<=',$trialExpiresFollowup)
                ->update(['lock_status' => 0]);
            /* Followup */
             $first = DB::table('cogent_complete_followups')
                 ->where('lock_status', '0')
                 ->whereNull('first_disposition')
                 ->count();
 
             $followup = DB::table('cogent_complete_followups')
                 ->where('lock_status', '0')
                 ->whereNotNull('first_disposition')
                 ->where(function ($query) {
                     $query->where('followup_disposition', '=', 'Not Contacted')
                         ->orWhereNull('followup_disposition');
                 })
                 // ->orWhereNull('followup_disposition')
                 // ->where('followup_disposition',  'Non Contacted')
                 ->count();
             //dd($followup . "=>" . $first);

              $count['dayCount']=DB::table('cogent_complete_followups')
                                ->where('lock_status', '0')
                                ->where('shift_time', 'Yes')
                                ->where('flag','!=', 'C')
                                ->count();
              $count['dayFreshCount']=DB::table('cogent_complete_followups')
                                ->where('lock_status', '0')
                                ->where('flag', '0')
                                ->where('shift_time', 'Yes')
                                // ->whereNull('first_disposition')
                                ->count();
              
              $count['dayFollowupCount']=DB::table('cogent_complete_followups')
                                        ->where('lock_status', '0')
                                        ->where('flag', '1')
                                        ->where('shift_time', 'Yes')
                                        // ->whereNotNull('first_disposition')
                                        ->count();
              

              $count['fullDayCount']=DB::table('cogent_complete_followups')
                                        ->where('lock_status', '0')
                                        ->where('shift_time', 'No')
                                        ->where('flag','!=', 'C')
                                        ->count();
              $count['fullDayFreshCount']=DB::table('cogent_complete_followups')
                                            ->where('lock_status', '0')
                                            ->where('flag', '0')
                                            ->where('shift_time', 'No')
                                            // ->whereNull('first_disposition')
                                            ->count();
              $count['fullDayFollowupCount']=DB::table('cogent_complete_followups')
                                                ->where('lock_status', '0')
                                                ->where('flag', '1')
                                                ->where('shift_time', 'No')
                                                // ->whereNotNull('first_disposition')
                                                ->count();

              // dd($count);
             
              $totalCountAssign = $this->totalCountAssign();
              $totalCountDealer = $this->totalCountDealer();
              $totalCountComplete = $this->totalCountComplete();


             return view('followup.complete', compact('first', 'followup','count','totalCountAssign','totalCountDealer','totalCountComplete'));

            // /dd($data);

        }catch(Exception $ex){
            dd($ex->getMessage());
        }
    }
    public function totalCountAssign(){
        $cnt =  DB::table('cogent_assign_followups')->where('flag','!=','C')->where('lock_status','!=','1')->count();
        return $cnt;
    }
    public function totalCountDealer(){
        $cnt =  DB::table('cogent_dealer_followups')->where('flag','!=','C')->where('lock_status','!=','1')->count();
        return $cnt;
    }
    public function totalCountComplete(){
        $cnt =  DB::table('cogent_complete_followups')->where('flag','!=','C')->where('lock_status','!=','1')->count();
        return $cnt;
    }
    public function getLangcount($lang,$shift_time,$type){
        if($type=='Fresh'){
            return $records=DB::table('cogent_complete_followups')
           
            ->where('lock_status', '0')
            ->where('flag', '0')
            ->where('shift_time', $shift_time)
            // ->whereNull('first_disposition')
            ->where('lang',$lang)
            ->count();
        }else{
            return $records=DB::table('cogent_complete_followups')
        ->where('lock_status', '0')
        ->where('flag', '1')
        ->where('shift_time', $shift_time)
        // ->whereNotNull('first_disposition')
        ->where('lang',$lang)
        ->count();

        }
        
    }

    public function dayFresh(Request $request)
    {
        try {
            $records = array();
            $langArr = ['Hindi','English','Malayalam','Kannad','Tamil','Telugu'];
            //dd($request->all());
            if($request->type1=="day" && $request->type2=="Fresh"){
                $type1=$request->type1;
                $type2=$request->type2;
                foreach($langArr as $v){
                    $records[$v] = $this->getLangcount($v,'Yes','Fresh');
                }
            }elseif($request->type1=="day" && $request->type2=="Followup"){
                //dd("ELSE");
                $type1=$request->type1;
                $type2=$request->type2;
                foreach($langArr as $v){
                    $records[$v] = $this->getLangcount($v,'Yes','Followup');
                }
            }else{
                //$type1=$request->type1;
                //$type2=$request->type2;
            }
            if($request->type1=="fullday" && $request->type2=="Fresh"){
                $type1=$request->type1;
                $type2=$request->type2;
                foreach($langArr as $v){
                    $records[$v] = $this->getLangcount($v,'No','Fresh');
                }
            }elseif($request->type1=="fullday" && $request->type2=="Followup"){
                //dd("ELSE");
                $type1=$request->type1; 
                $type2=$request->type2;
                foreach($langArr as $v){
                    $records[$v] = $this->getLangcount($v,'No','Followup');
                }
            }else{
                //$type1=$request->type1;
                //$type2=$request->type2;
            }

            
            //dd($records);
            if(sizeof($records)>0){
                $view = view("followup.ajax.daycomplete", compact('type1','type2','records'))->render();
                return response()->json(['html' => $view, 'status' => 'success'], 200);
            }else{
                return response()->json(['html' => "No Record Found.", 'status' => 'no'], 200);
            }
            
        } catch (Exception $ex) {
           // dd($ex->getMessage());
            return response()->json(['html' => '', 'status' => 'errors'], 200);
        }
    }
    public function langAjax(Request $request)
    {
        try {
            $getlang=$request->lang;
            if($request->type1=='day'){
                $shiftTime = 'Yes';
            }else{
                $shiftTime = 'No';
            }
           //$view = DB::table('cogent_complete_followups')->get()->toArray();
           if($request->type2=='Fresh'){
           $view=DB::table('cogent_complete_followups')
                    ->where('lock_status', '0')
                    ->where('flag', '0')
                    ->where('shift_time', $shiftTime)
                    // ->whereNull('first_disposition')
                    ->where('lang',$getlang)
                    ->orderBy('created_at', 'ASC')->take(1)
                    ->get()
                    ->toArray();
                   // dd($view);
           }else{

            $view=DB::table('cogent_complete_followups')
                    ->where('lock_status', '0')
                    ->where('flag', '1')
                    ->where('shift_time', $shiftTime)
                    // ->whereNotNull('first_disposition')
                    ->where('lang',$getlang)
                    ->orderBy('created_at', 'ASC')->take(1)
                    ->get()
                    ->toArray();

            }
            if(sizeof($view)>0){
                $nowTime=date('Y-m-d H:i:s');
                DB::table('cogent_complete_followups')
                ->where('id', $view[0]->id)
                ->update(['lock_status'=>'1','lock_time'=>$nowTime]);        

                $authId = Auth::user()->employee_id;
                $cmNumber = $view[0]->complaint_number;
                DB::table('chunk_followup_logs')->insert(['complaint_number'=>$cmNumber,'action_by'=>$authId,'type'=>'Complete Followup']);
                return response()->json(['html' => json_encode($view), 'status' => 'success'], 200);
            }else{
                return response()->json(['html' => '', 'status' => 'errors'], 200);
            }
            
        } catch (Exception $ex) {
           // dd($ex->getMessage());
            return response()->json(['html' => '', 'status' => 'errors'], 200);
        }
    }
     
    public function update(Request $request)
    {
        try {
           //dd($request->all());
            $disposition= $request->disposition;
            $sub_disposition= $request->sub_disposition;
            $complaint_number= $request->complaint_number;
            $query= DB::table('cogent_complete_followups')->select("shift_time")->where("complaint_number",$complaint_number)->get();
            $shift_time = $query[0]->shift_time;

        if($request->type1=='day' && $request->type2=='Fresh'){
            $updateArr = array(
                'first_disposition'=>$request->disposition,
                'first_sub_disposition'=>$request->sub_disposition,
                'first_resolution_code'=>$request->resolution_code,
                'first_attempt_by'=>Auth::user()->name,
                'first_attempt_at'=>date('Y-m-d H:i:s')
            );
            if($disposition =='Non-Contacted'){
                $updateArr['flag']='1';
                
            }else if($disposition =='Contacted'){
                if($sub_disposition == 'Closed - Customer Confirmation' || $sub_disposition == 'Closed - TSM/ASM Confirmation'){
                    $updateArr['flag']='C';
                }else{
                    $updateArr['flag']='1';

                }

            }else{
                $updateArr = array(
                    'first_disposition'=>$request->disposition,
                    'first_sub_disposition'=>$request->sub_disposition,
                    'first_resolution_code'=>$request->resolution_code,
                    'first_attempt_by'=>Auth::user()->name,
                    'first_attempt_at'=>date('Y-m-d H:i:s')
                );
            }
            /* $update =DB::table('cogent_complete_followups')->where('id',$request->dataid)->update($updateArr);
            //dd($update );
            $notification = array(
                'message' =>"Followup Submited Successfully.",
                'alert-type' => 'success'
            );
            return back()->with($notification); */
        }elseif($request->type1=='day' && $request->type2=='Followup'){

            $updateArr = array(
                'followup_disposition'=>$request->disposition,
                'followup_sub_disposition'=>$request->sub_disposition,
                'followup_resolution_code'=>$request->resolution_code,
                'followup_attempt_by'=>Auth::user()->name,
                'followup_attempt_at'=>date('Y-m-d H:i:s')
            );
            if($disposition =='Contacted' && ($sub_disposition == 'Closed - Customer Confirmation' || $sub_disposition == 'Closed - TSM/ASM Confirmation')){
                $updateArr['flag']='C';
            }
           /*  $update =DB::table('cogent_complete_followups')->where('id',$request->dataid)->update($updateArr);
            //dd($update );
            $notification = array(
                'message' =>"Followup Submited Successfully.",
                'alert-type' => 'success'
            );
            return back()->with($notification); */
            
        }else if($request->type1=='fullday' && $request->type2=='Fresh'){
            $updateArr = array(
                'first_disposition'=>$request->disposition,
                'first_sub_disposition'=>$request->sub_disposition,
                'first_resolution_code'=>$request->resolution_code,
                'first_attempt_by'=>Auth::user()->name,
                'first_attempt_at'=>date('Y-m-d H:i:s')
            );
            if($disposition =='Non-Contacted'){
                $updateArr['flag']='1';
                
            }else if($disposition =='Contacted'){
                if($sub_disposition == 'Closed - Customer Confirmation' || $sub_disposition == 'Closed - TSM/ASM Confirmation'){
                    $updateArr['flag']='C';
                }else{
                    $updateArr['flag']='1';

                }

            }else{
                $updateArr = array(
                    'first_disposition'=>$request->disposition,
                    'first_sub_disposition'=>$request->sub_disposition,
                    'first_resolution_code'=>$request->resolution_code,
                    'first_attempt_by'=>Auth::user()->name,
                    'first_attempt_at'=>date('Y-m-d H:i:s')
                );
            }
            
            /* $update =DB::table('cogent_complete_followups')->where('id',$request->dataid)->update($updateArr);
            //dd($update );
            $notification = array(
                'message' =>"Followup Submited Successfully.",
                'alert-type' => 'success'
            );
            return back()->with($notification); */
        }else if($request->type1=='fullday' && $request->type2=='Followup'){
            $updateArr = array(
                'followup_disposition'=>$request->disposition,
                'followup_sub_disposition'=>$request->sub_disposition,
                'followup_resolution_code'=>$request->resolution_code,
                'followup_attempt_by'=>Auth::user()->name,
                'followup_attempt_at'=>date('Y-m-d H:i:s')
            );
            if($disposition =='Contacted' && ($sub_disposition == 'Closed - Customer Confirmation' || $sub_disposition == 'Closed - TSM/ASM Confirmation')){
                $updateArr['flag']='C';
            }
            /* $update =DB::table('cogent_complete_followups')->where('id',$request->dataid)->update($updateArr);
            //dd($update );
            $notification = array(
                'message' =>"Followup Submited Successfully.",
                'alert-type' => 'success'
            );
            return back()->with($notification); */
           }else{
            $notification = array(
                'message' =>"Something went wrong",
                'alert-type' => 'error'
            );
            return back()->with($notification);
           }
           $update =DB::table('cogent_complete_followups')->where('id',$request->dataid)->update($updateArr);
           $insertArr = array(
                'complaint_number'=>$complaint_number,
                'lang'=>$request->language,
                'shift_time'=>$shift_time,
                'first_disposition'=>$request->disposition,
                'first_sub_disposition'=>$request->sub_disposition,
                'first_resolution_code'=>$request->resolution_code,
                'first_attempt_by'=>Auth::user()->name,
                'first_attempt_at'=>date('Y-m-d H:i:s'),
                'followup_disposition'=>$request->disposition,
                'followup_sub_disposition'=>$request->sub_disposition,
                'followup_resolution_code'=>$request->resolution_code,
                'followup_attempt_by'=>Auth::user()->name,            
                'followup_attempt_at'=>date('Y-m-d H:i:s')
            );
            DB::table('cogent_complete_followups_logs')->insert($insertArr);
            //dd($update );
            $notification = array(
                'message' =>"Followup Submited Successfully.",
                'alert-type' => 'success'
            );
            return back()->with($notification);
        } catch (Exception $ex) {
            //dd($ex->getMessage());
            return response()->json(['html' => 'Something went wrong!', 'status' => 'errors'], 200);
        }
    }

    
}
