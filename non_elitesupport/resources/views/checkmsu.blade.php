@extends("layouts.masterlayout")
@section('title','Check MSU Json Format')
@section('bodycontent')
	<div class="content-wrapper mobcss">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Check MSU Json Format</h4>
                <div class="row">
                    <div class="col-md-12">
                    	
                        <hr>                       
						<div class="table-responsive">
							<table id="order-listing" class="table">
                                <thead>
                                    <tr>
										<th>Complaint Number</th>						
										<th>MSU Response</th>						
										<th>Json Format</th>
										<th>Data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @isset($rowData)
								
                                @php $count=1; @endphp							
									@foreach($rowData as $row)
                                    <tr>
										<td>{{$row->complaint_number}}</td>
										<td>{{$row->msuRemarks}}</td>
										@php 
										$data = $row->jsonRemarks;
										$isJson = is_array(json_decode($data,true));
										@endphp
										@if($isJson)
											<td>True</td>
										@else
											<td>False</td>
										@endif
										<td>{{$row->jsonRemarks}}</td>
                                    </tr>
                                     @php $count++; @endphp	
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
 

@endsection
