<?php
namespace App\Imports;
use App\Models\ImportPsfModel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DB;

class ImportPsf implements ToModel,WithHeadingRow
{

    public function model(array   $row)
    {
       return new ImportPsfModel([
            'VIN' => trim($row['vin']),
            'job_card_number' => trim($row['job_card_no']),
            'job_card_date' => trim($row['jobcard_date']),
            'Vehicle_number' => trim($row['vehicle_number']),
            'Customer_name' => trim($row['customer_name']),
            'Customer_number' => trim($row['customer_mobile']),
            'Dealer_name' => trim($row['dealer_name']),
            'Dealer_City' => trim($row['dealer_city']),
            'Dealer_state' => trim($row['dealer_state']),
            'SAC_code' => trim($row['sac_code']),
            'zone' => trim($row['dealer_zone']),
            'psf_call_type' => trim($row['psf_call_type']),
            'plant_name' => trim($row['plant_name']),
            'invoice_date' => trim($row['invoice_date']),
            'gate_pass_date' => trim($row['gate_pass_date']),
            'customer_code' => trim($row['customer_code']),
            'header_order_type' => trim($row['header_order_type']),
            'chassis_no' => trim($row['chassis_no']),
            'customer_service_contact' => trim($row['customer_service_contact']),
            'quotation_type' => trim($row['quotation_type']),
            'driver_mobile' => trim($row['driver_mobile']),
            'reg_no' => trim($row['reg_no']),
            'customer_voice' => trim($row['customer_voice']),
            'customer' => trim($row['customer'])
       ]);

    }


}
