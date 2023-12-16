<?php

namespace App;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Updateticket extends Model
{
    //use HasFactory;
    protected $fillable = [

        'ticket_num', 'reg_no', 'chasi_no', 'eng_no', 'ticket_type', 'dealer_name', 'vin_no', 'status', 'msv_type', 'msv_reg_no', 'mechinic_name', 'mechinic_number', 'remark', 'start_time', 'response_time_No', 'restore_time_No', 'pause_time_No', 'end_time', 'url', 'vehicle_mode', 'dealer_code', 'error', 'restoration_type', 'actual_response_time', 'restoration_time', 'response_delay_reason', 'restoration_delay','feedback_rating','feedback_desc','cc_status','aggregate'
    ];

}
