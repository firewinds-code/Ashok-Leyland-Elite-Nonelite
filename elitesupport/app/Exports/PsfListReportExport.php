<?php

namespace App\Exports;

use App\Models\PsfModel;
use App\Models\PsfQuestion;
use App\Models\PsfLogModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Auth;
use DB;
use DateTime;
class PsfListReportExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct()
    {

    }
    public function collection()
    {

        $query = "SELECT  VIN,SAC_code,job_card_number,complaint_no,job_card_date,Vehicle_number,Customer_name,Customer_number,Dealer_name,status FROM psf_info where  (feedback_status ='' or feedback_status is null)";
        /* (status != 'Closed' or status is NULL) */

        $report = DB::select($query);

         return collect($report);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return   ['VIN','Sac Code','JC Number','Complaint No','JC Date',' Vehicle Number','Customer Name','Customer Number','Dealer Name','Dealer Status'];

    }
}
