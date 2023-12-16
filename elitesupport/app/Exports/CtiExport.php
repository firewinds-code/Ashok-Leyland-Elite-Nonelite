<?php

namespace App\Exports;

use App\Models\PsfModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\CTITICKET;


class CtiExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct()
    {

    }
    public function collection()
    {

        $report =CTITICKET::select('ticket_number', 'remarks', 'reason_of_non_acceptance', 'updated_by_name', 'contact_number', 'role', 'created_at',  'agent_status', 'agent_remarks', 'updated_agent', 'agent_update_date')->get();

         return collect($report);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return   ['Ticket Number', 'Dealer remarks', 'Reason Of Non Acceptance', 'Updated By Name', 'Contact Number', 'Role', 'Dealer Update Date','Agent Status', 'Agent Remarks', 'Updated By Agent', 'Agent Update Date'];

    }
}
