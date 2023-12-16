<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportPsfModel extends Model
{
    use HasFactory;

    protected $table = 'psf_info';
    protected $fillable = [
 'customer', 'customer_voice', 'reg_no', 'driver_mobile','quotation_type','customer_service_contact', 'chassis_no','plant_name','invoice_date','gate_pass_date','customer_code','header_order_type','psf_call_type','VIN', 'job_card_number', 'job_card_date', 'Vehicle_number', 'Customer_name', 'Customer_number', 'Dealer_name', 'Dealer_City', 'Dealer_state', 'SAC_code', 'zone'];
}


