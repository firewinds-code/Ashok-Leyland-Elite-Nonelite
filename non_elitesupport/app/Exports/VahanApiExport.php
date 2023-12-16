<?php
  
namespace App\Exports;
  
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Auth;
use DB;
use DateTime;
class VahanApiExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
  
    public function collection()
    {
        $query= DB::table("elitesupport.vahan_details")
        ->select('registration_number','json_req','created_at')
        ->get();
    //    dd($query);
        $rowData = [];
        $k=0;
		
        foreach ($query as $row){
			$k++;
						
			
			// $rowData[$k][]=$row->registration_number;
			$jsonReq= json_decode($row->json_req);
			
            $client_id = isset($jsonReq->client_id) && $jsonReq->client_id!=null?$jsonReq->client_id:'';
            if($client_id !=''){
                $rowData[$k][]= $jsonReq->client_id;
                $rowData[$k][]= $jsonReq->rc_number;
                $rowData[$k][]= $jsonReq->registration_date;
                $rowData[$k][]= $jsonReq->owner_name;
                $rowData[$k][]= $jsonReq->father_name;
                $rowData[$k][]= $jsonReq->present_address;
                $rowData[$k][]= $jsonReq->permanent_address;
                $rowData[$k][]= $jsonReq->mobile_number;
                $rowData[$k][]= $jsonReq->vehicle_category;
                $rowData[$k][]= $jsonReq->vehicle_chasi_number;
                $rowData[$k][]= $jsonReq->vehicle_engine_number;
                $rowData[$k][]= $jsonReq->maker_description;
                $rowData[$k][]= $jsonReq->maker_model;
                $rowData[$k][]= $jsonReq->body_type;
                $rowData[$k][]= $jsonReq->fuel_type;
                $rowData[$k][]= $jsonReq->color;
                $rowData[$k][]= $jsonReq->norms_type;
                $rowData[$k][]= $jsonReq->fit_up_to;
                $rowData[$k][]= $jsonReq->financer;
                $rowData[$k][]= $jsonReq->financed;
                $rowData[$k][]= $jsonReq->insurance_company;
                $rowData[$k][]= $jsonReq->insurance_policy_number;
                $rowData[$k][]= $jsonReq->insurance_upto;
                $rowData[$k][]= $jsonReq->manufacturing_date;
                $rowData[$k][]= $jsonReq->manufacturing_date_formatted;
                $rowData[$k][]= $jsonReq->registered_at;
                $rowData[$k][]= $jsonReq->latest_by;
                $rowData[$k][]= $jsonReq->less_info;
                $rowData[$k][]= $jsonReq->tax_upto;
                $rowData[$k][]= $jsonReq->tax_paid_upto;
                $rowData[$k][]= $jsonReq->cubic_capacity;
                $rowData[$k][]= $jsonReq->vehicle_gross_weight;
                $rowData[$k][]= $jsonReq->no_cylinders;
                $rowData[$k][]= $jsonReq->seat_capacity;
                $rowData[$k][]= $jsonReq->sleeper_capacity;
                $rowData[$k][]= $jsonReq->standing_capacity;
                $rowData[$k][]= $jsonReq->wheelbase;
                $rowData[$k][]= $jsonReq->unladen_weight;
                $rowData[$k][]= $jsonReq->vehicle_category_description;
                $rowData[$k][]= $jsonReq->pucc_number;
                $rowData[$k][]= $jsonReq->pucc_upto;
                $rowData[$k][]= $jsonReq->permit_number;
                $rowData[$k][]= $jsonReq->permit_issue_date;
                $rowData[$k][]= $jsonReq->permit_valid_from;
                $rowData[$k][]= $jsonReq->permit_valid_upto;
                $rowData[$k][]= $jsonReq->permit_type;
                $rowData[$k][]= $jsonReq->national_permit_number;
                $rowData[$k][]= $jsonReq->national_permit_upto;
                $rowData[$k][]= $jsonReq->national_permit_issued_by;
                $rowData[$k][]= $jsonReq->non_use_status;
                $rowData[$k][]= $jsonReq->non_use_from;
                $rowData[$k][]= $jsonReq->non_use_to;
                $rowData[$k][]= $jsonReq->blacklist_status;
                $rowData[$k][]= $jsonReq->noc_details;
                $rowData[$k][]= $jsonReq->owner_number;
                $rowData[$k][]= $jsonReq->rc_status;
                $rowData[$k][]= $jsonReq->masked_name;
                $rowData[$k][]= $jsonReq->challan_details;
                $rowData[$k][]= $jsonReq->variant;
                $rowData[$k][]= $row->created_at;
            }/* else{
                $rowData[$k][] ='';
            } */
            
		}
		// dd($rowData);
		
                    

                   
                
        return collect($rowData);
    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return [ 'client id','rc number','registration date','owner name','father name','present address','permanent address','mobile number','vehicle category','vehicle chasi number','vehicle engine number','maker description','maker model','body type','fuel type','color','norms type','fit up to','financer','financed','insurance company','insurance policy number','insurance upto','manufacturing date','manufacturing date formatted','registered at','latest by','less info','tax upto','tax paid upto','cubic capacity','vehicle gross weight','no cylinders','seat capacity','sleeper capacity','standing capacity','wheelbase','unladen weight','vehicle category description','pucc number','pucc upto','permit number','permit issue date','permit valid from','permit valid upto','permit type','national permit number','national permit upto','national permit issued by','non use status','non use from','non use to','blacklist status','noc details','owner number','rc status','masked name','challan details','variant','Created Date'];
    }
}