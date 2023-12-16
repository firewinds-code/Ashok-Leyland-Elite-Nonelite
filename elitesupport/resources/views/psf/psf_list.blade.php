@extends("layouts.masterlayout")
@section('title','PSF Survey List')
@section('bodycontent')


	<div class="content-wrapper mobcss">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">PSF Survey List</h4>
                <form name="myForm" method="post" enctype="multipart/form-data" action="{{url('store-psf-list')}}">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="datefrom" >Job Card Number</label>
                            <span style="color: red;">*</span>
                            <input type="text" name="job_card_number" id="job_card_number" autocomplete="off" class="form-control" value="@isset($job_card_number){{$job_card_number}} @endisset" placeholder="Job Card Number" required/>
                            <span id="datefrom_error" style="color:red"></span> 
                        </div>
                         {{-- <div class="form-group col-md-3">
                            <label for="datefrom" >Date From</label>
                            <span style="color: red;">*</span>
                            <input type="text" name="datefrom" id="datefromList" autocomplete="off" class="form-control" value="@isset($datefrom){{$datefrom}} @endisset" placeholder="Date From" required/>
                            <span id="datefrom_error" style="color:red"></span> 
                        </div>
                        <div class="form-group col-md-3">
                            <label for="dateto" >Date To</label>
                            <span style="color: red;">*</span>
                            <input type="text" name="dateto" id="datetoList" autocomplete="off" class="form-control" value="@isset($dateto){{$dateto}} @endisset" placeholder="Date To" required/>
                        </div> --}}
                        
                       
                    </div></br>
                    
                    
                    <div class="row">
                         <div class="form-group col-md-3">
                            <input type="submit"name="submit" id="submit" value="Submit" class="btn-secondary">
                        </div>
                    </div>
                </form>
                <hr> 
                <div class="row">
                    <div class="col-md-12">
                        <div class="clear"></div>
                        <div class="table-responsive">
                            <table id="psf-list" class="table">
                                <thead>
                                    <tr>
                                        <th> VIN </th>
                                        <th> Sac Code </th>
                                        <th>JC Number</th>
                                        <th>Complaint No</th>
                                        <th>JC Date</th>
                                        <th> Vehicle Number</th>
                                        <th>Customer Name</th>
                                        <th>Customer Number</th>
                                        <th>Dealer Name</th>
                                        <th>Dealer Status</th>
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
                                        <td>{{ $list->complaint_no }}</td>
                                        <td>{{ $list->job_card_date }}</td>
                                        <td>{{ $list->Vehicle_number }}</td>
                                        <td>{{ $list->Customer_name }}</td>
                                        <td>{{ $list->Customer_number }}</td>
                                        <td>{{ $list->Dealer_name }}</td>
                                        <td> {{ $list->status!=''?$list->status:'Pending' }}</td>
                                        <td>{{ $list->feedback_status!=''?$list->feedback_status:'NA' }}</td>
                                        <td><a href="{{ url('update-psf-query/'. Crypt::encrypt($list->id)) }}" class="btn btn-primary" target="blank">{{ __('View') }}</a></td>
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
    $('#datefromList,#datetoList').datetimepicker({ format:'Y-m-d',timepicker:false});
    $('#psf-list').DataTable({
		   		"order":[],
				dom: 'Bfrtip',
                // processing:true,
                // serverSide:true,
                paging: true,
                pageLength: 200,
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
