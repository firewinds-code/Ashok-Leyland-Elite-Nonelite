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
class PsfReportExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct()
    {

    }
    public function collection()
    {

        $query = "select pi.job_card_number, ps.action_by, ps.action_by_id, ps.created_at, ps.created_at,
        pq1.question as q1, pq2.question as q2, pq3.question as q3, pq4.question as q4, pq5.question as q5,
        pq6.question as q6, pi.q1_ans as ans1, pi.q2_ans as ans2, pi.q3_ans as ans3, pi.q4_ans as ans4, pi.q5_ans as ans5,
        pi.q6_ans as ans6, pi.additional_ques, pi.additional_que_ans, pi.remarks ,pi.feedback_status, pi.reason_of_low_rating,
        pi.feedback_given_by, pi.status, pi.complaint_no, ps.action_by_id as psf_updated,ps.json_records
        from psf_info pi left join psf_info_logs ps on pi.id = ps.psf_info_id
        left join psf_question pq1 on pq1.id = pi.q1
        left join psf_question pq2 on pq2.id = pi.q2
        left join psf_question pq3 on pq3.id = pi.q3
        left join psf_question pq4 on pq4.id = pi.q4
        left join psf_question pq5 on pq5.id = pi.q5
        left join psf_question pq6 on pq6.id = pi.q6
        where pi.complaint_no is not null or pi.complaint_no != ''";

        $report = DB::select($query);
        $rowData = [];
        $k=0;
        foreach ($report as $row){
           
            $jsonReq= json_decode($row->json_records);
			$k++;
            $dealerStatus = isset($jsonReq->status)?$jsonReq->status:'';
            $rowData[$k][]= $row->job_card_number;
            $rowData[$k][]= $row->action_by;
            $rowData[$k][]= $row->action_by_id;
            $rowData[$k][]= $row->created_at;
            $rowData[$k][]= $row->q1;
            $rowData[$k][]= $row->q2;
            $rowData[$k][]= $row->q3;
            $rowData[$k][]= $row->q4;
            $rowData[$k][]= $row->q5;
            $rowData[$k][]= $row->q6;
            $rowData[$k][]= $row->ans1;
            $rowData[$k][]= $row->ans2;
            $rowData[$k][]= $row->ans3;
            $rowData[$k][]= $row->ans4;
            $rowData[$k][]= $row->ans5;
            $rowData[$k][]= $row->ans6;
            $rowData[$k][]= $row->additional_ques;
            $rowData[$k][]= $row->additional_que_ans;
            $rowData[$k][]= $row->remarks;
            $rowData[$k][]= $row->feedback_status;
            $rowData[$k][]= $row->reason_of_low_rating;
            $rowData[$k][]= $row->feedback_given_by;
            $rowData[$k][]= $dealerStatus;
            $rowData[$k][]= $row->complaint_no;
            $rowData[$k][]= $row->psf_updated;
        
        }
       
        return collect($rowData);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
       /*  return   ['Job Card Number', 'Action By', 'Action By Id', 'Created Date & Time',
        'Question1', 'Question2' , 'Question3', 'Question4', 'Question5',
        'Question6', 'Answer1', 'Answer2', 'Answer3', 'Answer4', 'Answer5',
        'Answer6', 'Additional Question', 'Additional Answer', 'Remarks' ,'Feedback Status', 'Reason Of Low Rating',
        'Feedback Given By', 'Dealer Feedback / Action Taken', 'Complaint No']; */

        return   ['Job Card Number', 'Action By', 'Action By Id', 'Created Date & Time',
        'Question1', 'Question2' , 'Question3', 'Question4', 'Question5',
        'Question6', 'Answer1', 'Answer2', 'Answer3', 'Answer4', 'Answer5',
        'Answer6', 'Additional Question', 'Additional Answer', 'Remarks' ,'Feedback Status', 'Reason Of Low Rating',
        'Feedback Given By', 'Dealer Feedback / Action Taken', 'Complaint No', 'Psf Updated'];

    }
}
