@extends("layouts.masterlayout")
@section('title','Prevention Action')
@section('bodycontent')
	<div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Prevention Action</h4>
                <div class="row">
                    <div class="col-md-12">

                        <hr>                       
                        <div class="table-responsive">
                            <table id="order-listing" class="table custom">
                                <thead>
                                    <tr>
										<th>Preventive action ref. no.</th>
										<th>Applicable complaint ref. nos.</th>
										<th>Applicable sub-category of complaint</th>
										<th>Description of the issue</th>
										<th style="display: none;">Full Description of the issue</th>
										<th>Root cause</th>
										<th>Preventive action planned</th>
										<th>Responsible person</th>
										<th>Target date</th>
										<th>Action update</th>
										<th>Completion date</th>
										<th>Status</th>
										
                                    </tr>
                                </thead>
                                <tbody>
                                @isset($preActionSql)
                                    @php $dt =date('Y-m-d',strtotime("1111-01-01 00:00:00"));  @endphp

									@foreach($preActionSql as $row)

                                    <tr>
                                        <td class="cls_role"></td>
                                        <td class="cls_role">{{$row->complaint_number}}</td>
                                        <td class="cls_role">{{$row->sub_complaint_type}}</td>
                                        <td class="cls_role">{{substr($row->description,0,20) . '...'}}</td>
                                        <td class="cls_role" style="display:none">{{$row->description}}</td>
                                        <td class="cls_role">{{$row->root_cause}}</td>
                                        <td class="cls_role">{{$row->preventive_action}}</td>
                                        <td class="cls_role">{{$row->Responsible_person}}</td>
                                        <td class="cls_role">{{(date('Y-m-d',strtotime($row->target_date)) != $dt )?$row->target_date:'' }}</td>
                                        <td class="cls_role">{{$row->updated_at}}</td>
                                        <td class="cls_role">{{$row->complition_date}}</td>
                                        <td class="cls_role">{{$row->case_status}}</td>
                                    </tr>
                                    @endforeach
                                @endisset
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   <style>
       .custom{
           text-align: center;
           border-collapse: collapse;

       }
       .custom td, .custom th {
           border: 1px solid #ddd;
           padding: 8px;
           white-space: nowrap;
           text-align: left;
       }
       .custom th {
           font-size: 14px !important;
       }
   </style>

@endsection
