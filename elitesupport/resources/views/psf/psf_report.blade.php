@extends("layouts.masterlayout")
@section('title','PSF Report')
@section('bodycontent')
<div class="content-wrapper mobcss">	 
	<div class="card">	            
	    <div class="card-body">
			<h4 class="card-title">PSF Report</h4>
	        <div class="clear"></div>			
            <hr>
			
			<form name="myForm" method="post" enctype="multipart/form-data" action="{{url('store-psf-report')}}">
	            <input type="hidden" name="_token" value="{{csrf_token()}}">
	            <div class="row">
                 	<div class="form-group col-md-3">
                        <label for="datefrom" >Date From</label>
						<span style="color: red;">*</span>
						<input type="text" name="datefrom" id="datefrom1" autocomplete="off" class="form-control" value="@isset($datefrom){{$datefrom}} @endisset" placeholder="Date From" required/>
                        <span id="datefrom_error" style="color:red"></span> 
                    </div>
                    <div class="form-group col-md-3">
                        <label for="dateto" >Date To</label>
						<span style="color: red;">*</span>
						<input type="text" name="dateto" id="dateto1" autocomplete="off" class="form-control" value="@isset($dateto){{$dateto}} @endisset" placeholder="Date To" required/>
                    </div>
					
                   
                </div>
            	<div class="clear"></div>
                <hr> 
                <div class="row">
                	 <div class="form-group col-md-3">
                        <input type="submit"name="submit" id="submit" value="Submit" class="btn-secondary">
                    </div>
                </div>
            </form>   
			<div class="clear"></div>
            <hr>
			@isset($rowData)						
				@if(sizeof(($rowData))>0)                      
					<div class="table-responsive">
						<table id="order-listing" class="table" border="1">
							<thead>
								<tr style="background-color: ##d3d6d2;">
									
									
									<th class="align-middle">PSF Call Type</th>
									<th class="align-middle">Job Card Number</th>
									<th class="align-middle">Vehicle Number</th>
									<th class="align-middle">Customer Name</th>
									<th class="align-middle">Feedback Received Number</th>
									<th class="align-middle">SAC Code</th>
									<th class="align-middle">Dealer Name</th>
									<th class="align-middle">Zone</th>
									<th class="align-middle">State</th>
									<th class="align-middle">City</th>
									<th class="align-middle">Feedback Of Remarks</th>
									<th class="align-middle">Reason Of Low Rating</th>
									<th class="align-middle">Feedback Status</th>
																		
									<th class="align-middle">Complaint Number</th>
									<th class="align-middle">Complaint Date</th>
									<th class="align-middle">Complaint Time</th>
									@isset($questionData)
										@foreach($questionData as $row)
											<th class="align-middle">{{$row->question}}</th>
										@endforeach
									@endisset
									<th class="align-middle">Created By</th>
									<th class="align-middle">Created Date & Time</th>
									<th class="align-middle">Dealer Feedback / Action Taken</th>
									<th class="align-middle">Dealer Remarks</th>
								</tr>
							</thead>
							<tbody>
									
										@foreach ($rowData as $row)
											<tr style="background-color: #d3d6d2;">
																							
												<td>{{$row->psf_call_type}}</td>
												<td>{{$row->job_card_number}}</td>
												<td>{{$row->Vehicle_number}}</td>
												<td>{{$row->Customer_name}}</td>
												<td>{{$row->followup_number}}</td>
												<td>{{$row->SAC_code}}</td>
												<td>{{$row->Dealer_name}}</td>
												<td>{{$row->zone}}</td>
												<td>{{$row->Dealer_state}}</td>
												<td>{{$row->Dealer_City}}</td>
												<td>{{$row->remarks}}</td>
												<td>{{$row->reason_of_low_rating}}</td>
												<td>{{$row->feedback_status}}</td>
																								
												<td>{{$row->complaint_no}}</td>
												<td>{{($row->complaint_no!='' || $row->complaint_no!=NULL)?date('d-m-Y',strtotime($row->complaintDate)):''}}</td>
												<td>{{($row->complaint_no!='' || $row->complaint_no!=NULL)?date('H:i:s',strtotime($row->complaintDate)):''}}</td>
												<td>{{$row->q1_ans}}</td>
												<td>{{$row->q2_ans}}</td>
												<td>{{$row->q3_ans}}</td>
												<td>{{$row->q4_ans}}</td>
												<td>{{$row->q5_ans}}</td>
												<td>{{$row->q6_ans}}</td>	
												<td>{{$row->updated_by}}</td>
												<td>{{$row->complaintDate}}</td>
												<td>{{($row->complaint_no!='' || $row->complaint_no!=NULL)?$row->status:''}}</td>
												<td>{{$row->dealer_remarks}}</td>
												
											</tr>
										@endforeach
										
									
									
								
							</tbody>
						</table>
					</div>
				@else
				<table>
					<tr>
						<td>
							No Data
						</td>
					</tr>
				</table>
				@endif
			@endisset
			<br>
	    </div>	            
	</div>
<script type="text/javascript">

$(document).ready(function () {	
	
	
	
});
</script>
@endsection
