@extends("layouts.masterlayout")
@section('title','All Ticket List')
@section('bodycontent')
	<div class="content-wrapper mobcss">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">All Ticket List</h4>
                <div class="row">
                    <div class="col-md-12">
                        <div class="clear"></div>
                        <hr> 
                        <form name="myForm" method="post" enctype="multipart/form-data" action="{{url('store-case-list')}}">
				            <input type="hidden" name="_token" value="{{csrf_token()}}">
				            <div class="row">
			                 	<div class="form-group col-md-3">
			                        <label for="datefrom" >Date From</label>
									<span style="color: red;">*</span>
									<input type="text" name="datefrom" id="datefrom2" autocomplete="off" class="form-control" value="@isset($datefrom){{$datefrom}} @endisset" />
			                        <span id="datefrom_error" style="color:red"></span> 
			                    </div>
			                    <div class="form-group col-md-3">
			                        <label for="dateto" >Date To</label>
									<span style="color: red;">*</span>
									<input type="text" name="dateto" id="dateto2" autocomplete="off" class="form-control" value="@isset($dateto){{$dateto}} @endisset" />
			                        <span id="dateto_error" style="color:red"></span> 
			                    </div>					
									
			                </div>
			            	<div class="clear"></div><br>
			                
			                <div class="row">
			                	 <div class="form-group col-md-3">
			                        <input type="submit" name="submit" id="submit" value="Submit" class="btn-secondary">
									{{-- <input type="submit" name="submit" id="close" value="Close" class="btn-secondary"> --}}
			                    </div>
								
			                </div>
			            </form>  
            <hr>                       
                        <div class="table-responsive">
                            <table id="order-ticket" class="table">
                                <thead>
                                    <tr>
										@if (Auth::user()->role  == '29' || Auth::user()->role  == '30')
 										<th>Action</th>
 										@endif
										<th>Complaint Number</th>
										<th>Company Name</th>
										<th>Dealer Name</th>
										<th>Status</th>
										<th>Restoration Time</th>
										<th>Date Of Complaint</th>
										<th>Registration Number</th>
										<th>Chassis Number</th>
										<th>TAT</th>
										{{-- 
										<th>Owner Name</th>
										<th>Contact Person</th>
										<th>Caller Name</th> --}}
									</tr>
                                </thead>
                                <tbody>
	                                @isset($rowData)
	                                @php $count=1; @endphp	
	                                 @php  $sessionUpdateCase=Session::get('sessionUpdateCase');  @endphp
										@foreach($rowData as $row)
	                                    <tr>
											@if (Auth::user()->role  == '29' || Auth::user()->role  == '30')
												<td>
													<a href="{{route('case-deleted.caseDeleted', ['id' => $row->id])}}" onclick="return confirm('Do you want to delete?')">
													<i class="fa fa-trash-o" aria-hidden="true" style="cursor: pointer;"></i></a>
												</td>
 											@endif
	                                    	@php  $id =$row->id;  @endphp
	                                        <td class="cls_complaint_number"><a href="{{route('update-case.updateCase',['id' =>$id])}}" > {{$row->complaint_number}}</a></td>
											<td class="cls_id">{{$row->owner_company}}</td>
	                                        <td class="cls_id">{{$row->dealer_name}}</td>
	                                        <td class="cls_id">{{$row->remark_type}}</td>
	                                        <td class="cls_id">{{$row->tat_scheduled}}</td>
											
	                                        <td class="cls_id">{{ date('d-m-Y',strtotime($row->created_at))}}</td>
											<td class="cls_id">{{$row->reg_number}}</td>
											<td class="cls_id">{{$row->chassis_number}}</td>
											@php
											
											
												$remTypeArr = array('Arranging Parts Locally','Awaiting parts from AL','Awaiting AL Approval','Awaiting completion from Ancillary suppliers','Awaiting completion of contracted Job','Awaiting customer approval','Awaiting customer Payment','Awaiting Good will Approval','Awaiting parts from another dealer branch','Awaiting parts from customer','Dealer Feedback','Investigation in progress','Load transfer in progress','Man power not available','Mechanic left to BD spot','Mechanic reached BD spot','Moved to another vehicle on urgency','Public Holiday','Reassigned support','Response Delay','Response not Initiated','Restored by Self','Restored by Unknown support','Restored by Support','Vehicle being Towed','Vehicle reached support point','Work held up due to bandh','Work held up due to injury/accident','Work in progress','Workshop closed - Sunday','Assigned');
												/* if(in_array($row->remark_type,$remTypeArr)){
													$diff = abs(strtotime($row->created_at) - strtotime(date("Y-m-d h:i:s"))); 
													$first_date = new DateTime($row->created_at);
													$second_date = new DateTime(date("Y-m-d h:i:s"));
													$difference = $first_date->diff($second_date);
													$tat_scheduled =$difference->d.'-D '.$difference->h.'-H	'.$difference->i.'-M';
													
												}else{ */
													if(isset($row->created_at)){
														$first_date = new DateTime($row->created_at);
														$second_date = new DateTime(date("Y-m-d H:i:s"));
														$difference = $first_date->diff($second_date);
														$tat_scheduled =$difference->d.'-D '.$difference->h.'-H	'.$difference->i.'-M';
													}else{
														$tat_scheduled='NA';
													}
												/* } */
											@endphp
											<td class="cls_id">{{$tat_scheduled}}</td>
	                                       	{{-- <td class="cls_id">{{$row->owner_name}}</td>
	                                       	<td class="cls_id">{{$row->contact_name}}</td>
	                                       	<td class="cls_id">{{$row->caller_name}}</td> --}}
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
   <script>
   $(document).ready(function () 
 {
 	
   	$('#datefrom2').val("{{$datefrom}}").datetimepicker({ maxDate: 0,format:'Y-m-d',timepicker:false});
   	$('#dateto2').val("{{$dateto}}").datetimepicker({ maxDate: 0,format:'Y-m-d',timepicker:false});
	
	   $('#order-ticket').DataTable({
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
					}/*,
							{
						extend: 'csv',
						text: 'CSV',
						className: 'exportExcel',
						filename: 'Test_Csv',
						exportOptions: { modifier: { page: 'all'} }
					},
							{
						extend: 'pdf',
						text: 'PDF',
						className: 'exportExcel',segment
						filename: 'Test_Pdf',
						exportOptions: { modifier: { page: 'all'} }
					}*/]
			});
	
});
   </script>

@endsection
