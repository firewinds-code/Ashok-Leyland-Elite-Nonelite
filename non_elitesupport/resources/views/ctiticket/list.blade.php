@extends("layouts.masterlayout")
@section('title','CTI Ticket List')
@section('bodycontent')
	<div class="content-wrapper mobcss">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">CTI Ticket List</h4>
                <div class="row">
                    <div class="col-md-12">
                        <div class="clear"></div>
                        <div class="table-responsive">
                            <table id="psf-list" class="table">
                                <thead>
                                    <tr>
                                        <th>Sr.No</th>
                                        <th>Ticket Number</th>
                                        <th>Reason Of Non Acceptance</th>
                                        <th>Updated By Name</th>
                                        <th>Contact Number</th>
                                        <th>Role</th>
                                        <th>Remarks</th>
                                        <th>Agent Update Date</th>
                                        <th>Updated Agent</th>
                                        <th>Agent Status</th>
                                        <th>Agent Remarks</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($lists)
                                    @php $i = 1; @endphp
                                    @foreach($lists as $list)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ $list->ticket_number }}</td>
                                        <td>{{ $list->reason_of_non_acceptance }}</td>
                                        <td>{{ $list->updated_by_name }}</td>
                                        <td>{{ $list->contact_number }}</td>
                                        <td>{{ $list->role }}</td>
                                        <td>{{ $list->remarks }}</td>
                                        <td>{{ $list->agent_update_date }}</td>
                                        <td>{{ $list->updated_agent }}</td>
                                        <td>{{ $list->agent_status }}</td>
                                        <td>{{ $list->agent_remarks }}</td>
                                        <td>{{ $list->created_at }}</td>
                                    </tr>
                                    @php $i++; @endphp
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
   <script>
    $(document).ready(function (){
    $('#psf-list').DataTable({
		   		"order":[],
				dom: 'Bfrtip',
				"language": {
					"paginate": {
						"previous": "<",
						"next": ">"
					}
				},
				buttons: [{
						extend: 'excel',
						text: 'Excel',
						className: 'exportExcel',
						filename: '@yield("title")',
						exportOptions: { modifier: { page: 'all'} }
					}]
			});

    });
   </script>

@endsection
