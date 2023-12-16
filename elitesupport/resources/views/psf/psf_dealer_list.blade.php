@extends("layouts.masterlayout")
@section('title','PSF Surevey List')
@section('bodycontent')
	<div class="content-wrapper mobcss">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">PSF @isset($title) {{ $title }} @endisset</h4>
                <div class="row">
                    <div class="col-md-12">
                        <div class="clear"></div>
                        <div class="table-responsive">
                            <table id="psf-list" class="table">
                                <thead>
                                    <tr>
                                        <th>VIN</th>
                                        <th>Sac Code</th>
                                        <th>JC Number</th>
                                        @if(Request::segment(1) == 'dealer-complaint-list')
                                        <th>Complaint Number</th>
                                        <th>Complaint Date</th>
                                        @endif
                                        <th>JC Date</th>
                                        <th>Vehicle Number</th>
                                        <th>Customer Name</th>
                                        <th>Customer Number</th>
                                        <th>Dealer Name</th>
                                        <th>Feedback Status</th>
                                        <th>Customer Feedback</th>
									</tr>
                                </thead>
                                <tbody>
                                    @isset($record)
                                    @foreach($record as $list)
                                    <tr>
                                        <td>{{ $list->VIN }}</td>
                                        <td>{{ $list->SAC_code }}</td>
                                        <td>{{ $list->job_card_number }}</td>
                                        @if(Request::segment(1) == 'dealer-complaint-list')
                                        <td>{{ $list->complaint_no!=''?$list->complaint_no:'NA' }}</td>
                                        <td>{{ $list->complaintDate!=''?$list->complaintDate:'NA' }}</td>
                                        @endif
                                        <td>{{ $list->job_card_date }}</td>
                                        <td>{{ $list->Vehicle_number }}</td>
                                        <td>{{ $list->Customer_name }}</td>
                                        <td>{{ $list->Customer_number }}</td>
                                        <td>{{ $list->Dealer_name }}</td>
                                        <td> {{ $list->feedback_status!=''?$list->feedback_status:'Feedback' }}
                                        </td>
                                        <td>
                                            @if(Request::segment(1) == 'dealer-complaint-list')
                                            <a href="{{ url('dealer-complaint-query/'. Crypt::encrypt($list->id)) }}" class="btn btn-primary">{{ __('Click') }}</a>
                                            @else
                                            <a href="{{ url('dealer-survey-query/'. Crypt::encrypt($list->id)) }}" class="btn btn-primary">{{ __('View') }}</a>
                                            @endif
                                        </td>
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
