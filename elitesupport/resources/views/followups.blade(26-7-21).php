@extends("layouts.masterlayout")
@section('title','Active Calling List')
@section('bodycontent')
<div class="content-wrapper mobcss">
	<div class="card">
		<div class="card-body">
			<div class="row">
				<div class="col-md-6">
					<h4 class="card-title">New Call Log</h4>
				</div>
				<div class="col-md-12 ">
					<p>
						<span class="btn-primary pull-right" align="right" style="padding-left: 5px;padding-right: 5px;padding-top: 2px;padding-bottom: 2px;cursor: pointer;top: 13px;" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="false" aria-controls="multiCollapseExample1 multiCollapseExample2"> New call</span>
					</p></br>
					<div class="col">
						<div class="collapse multi-collapse" id="multiCollapseExample2" style="position: relative;top: 10px;">
							<div class="card card-body">
								<form name="myFormnew" method="POST" enctype="multipart/form-data" action="{{route('newcalllist')}}">
								<input type="hidden" name="_token" value="{{csrf_token()}}">
									<div class="row">
										<div class="form-group col-md-3">
											<label for="datefrom">Complaint number</label>
											<span style="color: red;">*</span>
											<input type="text" name="complaint_number_new" id="complaint_number_new" autocomplete="off" class="form-control" />
											<span id="complaint_number_new_error" style="color:red"></span>
										</div>
										@php $roleSelected = array(1,6,7,76,78,79,80); @endphp
										
										<div class="form-group col-md-3">
											<label for="call_to"> Call To</label>
											<span style="color: red;">*</span>
											<select name="call_to" class="form-control" id="call_to">
												<option value="">--Select--</option>
												
												@isset($roleData)
													@foreach ($roleData as $row)
													@if(in_array($row->id, $roleSelected))
														<option value="{{$row->id}}"> {{$row->role}}</option>
													@endif
													@endforeach
												@endisset
											</select>
											<span id="call_to_error" style="color:red"></span>
										</div>
										<div class="form-group col-md-3">
											<label for="datefrom">Time</label>
											<span style="color: red;">*</span>
											<input type="text" name="call_time" id="call_time" autocomplete="off" class="form-control" />
											<span id="call_time_error" style="color:red"></span>
										</div>
									</div></br>
									<div class="row">
										<div class="form-group col-md-3">
											<input type="submit" name="submitNew" id="submitNew" value="Submit" class="btn-secondary">
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
					<div class="clear"></div>
					<hr>
					<div style="background: #d8d7d5; padding: 15px">
						<form name="myForm" method="POST" enctype="multipart/form-data" action="{{url('store-folloup-info')}}">
							<input type="hidden" name="_token" value="{{csrf_token()}}">
							<input type="hidden" name="id">
							<div class="row">
								<div class="form-group col-md-3">
									<label for="datefrom">Complaint number</label>
									<span style="color: red;">*</span>
									<input type="text" name="complaint_number" id="complaint_number" autocomplete="off" class="form-control" />
									<span id="complaint_number_error" style="color:red"></span>
								</div>
								<div class="form-group col-md-3">
									<label for="attempt">Select Attempt</label>
									<span style="color: red;">*</span>
									<select name="attempt" class="form-control" id="attempt"> 
										<option value="">Select Attempt</option>
										<option value="1"> 1</option>
										<option value="2"> 2</option>
										<option value="3"> 3</option>
										<option value="4"> 4</option>
										<option value="5"> 5</option>
									</select>
									<span id="attempt_error" style="color:red"></span>
								</div>
								<div class="form-group col-md-3">
									<label for="attempt">Select Disposition</label>
									<span style="color: red;">*</span>
									<select name="disposition" class="form-control" id="disposition">
										<option value="not connected">Not Connected</option>
										<option value="connected">Connected</option>
										<option value="wrong number">Wrong Number</option>
										<option value="call not picked ">Call Not Picked</option>
										<option value="pending ">Pending </option>
										<option value="completed ">Completed </option>
									</select>
									<span id="disposition_error" style="color:red"></span>
								</div>
								<div class="form-group col-md-3">
									<label for="attempt">Enter Remarks</label>
									<span style="color: red;">*</span>
									<textarea name="remarks" id="remarks" class="form-control"></textarea>
									<span id="disposition_error" style="color:red"></span>
								</div>

							</div>
							<div class="clear"></div><br>

							<div class="row">
								<div class="form-group col-md-3">
									<input type="submit" name="submit" id="submit" value="Submit" class="btn-secondary">
								</div>
							</div>
						</form>
						<div id="log_list">
						</div>

					</div>
					<hr>
					<div class="table-responsive">
						<h4 class="card-title">Active Call List</h4>
						<div class="col-md-12" style="border: 1px solid #ccc">
							<div class="clear"></div>
							<form name="myForm" method="post" enctype="multipart/form-data" action="{{url('store-followups-form')}}">
								<input type="hidden" name="_token" value="{{csrf_token()}}">
								<div class="row">
									 <div class="form-group col-md-3">
										<label for="datefrom" >Date From</label>
										<span style="color: red;">*</span>
										<input type="text" name="datefrom" id="datefrom2" autocomplete="off" class="form-control" value="@isset($date){{$date}} @endisset" />
										<span id="datefrom_error" style="color:red"></span> 
									</div>
									<div class="form-group col-md-3">
										<label for="dateto" >Date To</label>
										<span style="color: red;">*</span>
										<input type="text" name="dateto" id="dateto2" autocomplete="off" class="form-control" value="@isset($dateto){{$dateto}} @endisset" />
										<span id="dateto_error" style="color:red"></span> 
									</div>
														
									<div class="form-group col-md-3">
										<label for="dateto" >Select Disposition</label>
										<span style="color: red;">*</span>
										<select name="disposition[]" multiple class="form-control" id="dispositionSearch">
											<option value="call not picked" {{in_array('call not picked',$dispositionSearch)?'selected':''}}>Call Not Picked</option>
											<option value="connected" {{in_array('connected',$dispositionSearch)?'selected':''}}>Connected</option>
											<option value="completed" {{in_array('completed',$dispositionSearch)?'selected':''}}>Completed </option>
											<option value="not connected" {{in_array('not connected',$dispositionSearch)?'selected':''}}>Not Connected</option>
											<option value="pending" {{in_array('pending',$dispositionSearch)?'selected':''}}>Pending </option>
											<option value="wrong number" {{in_array('wrong number',$dispositionSearch)?'selected':''}}>Wrong Number</option>
										</select>
									</div>					
									<div class="form-group col-md-3">
										<label for="dateto" >Ticket Status</label>
										<span style="color: red;">*</span>
										<select name="remark_type[]" id="remark_type" multiple class="form-control" required>
											@isset($remark_type)
											@php $i=0; @endphp
												@foreach ($remark_type as $row)
														<option value="{{$row->type}}" {{in_array($row->type,$remark_typeArr)?'selected':''}}>{{$row->type}}</option>
														@php $i++; @endphp
												@endforeach
											@endisset
										</select> 
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
							<br>
						</div>
						<br>
						<table id="order-listing" class="table">
							<thead>
								<tr>
									<th>Complaint Number</th>

									<th>Date Of Complaint</th>
									<th>Call Time</th>
									<th>Dealer Number</th>
									<th>Dealer Name</th>
									<th>Follow Name</th>
									<th>Follow Number</th>
									<th>Registration Number</th>
									<th>Ticket Status</th>
									<th>Call Status</th>
									<th>Created By</th>
									{{-- <th style="display: none">Caller Update Log</th> --}}
									
									<th style="display: none">Call Log Disposition</th>
									<th style="display: none">Call Log Created By</th>
									<th style="display: none">Call Log Remarks</th>
									<th style="display: none">Call Log Created Date</th>
									
									
									<th style="display: none">Call Log Disposition</th>
									<th style="display: none">Call Log Created By</th>
									<th style="display: none">Call Log Remarks</th>
									<th style="display: none">Call Log Created Date</th>

									
									<th style="display: none">Call Log Disposition</th>
									<th style="display: none">Call Log Created By</th>
									<th style="display: none">Call Log Remarks</th>
									<th style="display: none">Call Log Created Date</th>

									
									<th style="display: none">Call Log Disposition</th>
									<th style="display: none">Call Log Created By</th>
									<th style="display: none">Call Log Remarks</th>
									<th style="display: none">Call Log Created Date</th>

									
									<th style="display: none">Call Log Disposition</th>
									<th style="display: none">Call Log Created By</th>
									<th style="display: none">Call Log Remarks</th>
									<th style="display: none">Call Log Created Date</th>
								</tr>
							</thead>
							<tbody>
								@isset($finalData)
								@if($finalData !='')
								@php $count=1; @endphp
								@php $sessionUpdateCase=Session::get('sessionUpdateCase'); @endphp
								@foreach($finalData as $row)
								<tr>
									@php $id =$row['id']; @endphp
									<td class="cls_complaint_number">
										{{-- <a href="javascript:void(0)" id="{{$row['id']}}" complaint_number="{{$row['complaint_number']}}" class="setData"> {{$row['complaint_number']}}</a> --}}
										<a href="#" onclick="followupFunc('{{$row['id']}}','{{$row['complaint_number']}}')"> {{$row['complaint_number']}}</a>
									</td>
									<td class="cls_id">{{ date('d-m-Y',strtotime($row['created_at']))}}</td>
									<td class="cls_id">{{$row['estimated_response_time']}}</td>
									<td class="cls_id">{{$row['dealer_mob_number']}}</td>
									<td class="cls_id">{{$row['dealer_name']}}</td>
									<td class="cls_id">{{$row['followup_name']}}</td>
									<td class="cls_id">{{$row['followups_number']}}</td>
									<td class="cls_id">{{$row['reg_number']}}</td>
									<td class="cls_id">{{$row['status']}}</td>
									@if(!empty($row['call_log']))
									<td class="cls_id">{{$row['call_log']}}</td>
									@else
									<td class="cls_id">Fresh Case</td>
									@endif
									<td>{{$row['created_by']}}</td>
									@php 
										$k = 0;
										$fdisposition = explode("##",$row['fdisposition']); 
										$femployee_name = explode("##",$row['femployee_name']); 
										$fcreated_at = explode("##",$row['fcreated_at']); 
										$fremarks = explode("##",$row['fremarks']); 
										 
										@endphp
									{{-- @if($row['fdisposition']!='')
										
										<td style="display: none">
										@foreach($fdisposition as $row1)
											@php $fcreated_at1 = date('d-m-Y H:i:s',strtotime($fcreated_at[$k])); @endphp
											@php $fremarks1 = $fremarks[$k]; @endphp
											{{'Created By :'.$femployee_name[$k].', Dispostition :'.$row1.', Created Date :'.$fcreated_at1.', Remarks :'.$fremarks1.' |'}}
											<br>
											@php $k++; @endphp
										@endforeach
										</td>	
									@else
										<td style="display: none">NA</td>
									@endif --}}
									<td style="display: none">{{ !empty($fdisposition[0]) && $fdisposition[0]!=''?$fdisposition[0]:'NA' }}</td>
 									<td style="display: none">{{ !empty($femployee_name[0]) && $femployee_name[0]!=''?$femployee_name[0]:'NA' }}</td>
 									<td style="display: none">{{ !empty($fremarks[0]) && $fremarks[0]!=''?$fremarks[0]:'NA' }}</td>
 									<td style="display: none">{{ !empty($fcreated_at[0]) && $fcreated_at[0]!=''?$fcreated_at[0]:'NA' }}</td>
 
 									<td style="display: none">{{ !empty($fdisposition[1]) && $fdisposition[1]!=''?$fdisposition[1]:'NA' }}</td>
 									<td style="display: none">{{ !empty($femployee_name[1]) && $femployee_name[1]!=''?$femployee_name[1]:'NA' }}</td>
 									<td style="display: none">{{ !empty($fremarks[1]) && $fremarks[1]!=''?$fremarks[1]:'NA' }}</td>
 									<td style="display: none">{{ !empty($fcreated_at[1]) && $fcreated_at[1]!=''?$fcreated_at[1]:'NA' }}</td>
 
 									<td style="display: none">{{ !empty($fdisposition[2]) && $fdisposition[2]!=''?$fdisposition[2]:'NA' }}</td>
 									<td style="display: none">{{ !empty($femployee_name[2]) && $femployee_name[2]!=''?$femployee_name[2]:'NA' }}</td>
 									<td style="display: none">{{ !empty($fremarks[2]) && $fremarks[2]!=''?$fremarks[2]:'NA' }}</td>
 									<td style="display: none">{{ !empty($fcreated_at[2]) && $fcreated_at[2]!=''?$fcreated_at[2]:'NA' }}</td>
 
 									<td style="display: none">{{ !empty($fdisposition[3]) && $fdisposition[3]!=''?$fdisposition[3]:'NA' }}</td>
 									<td style="display: none">{{ !empty($femployee_name[3]) && $femployee_name[3]!=''?$femployee_name[3]:'NA' }}</td>
 									<td style="display: none">{{ !empty($fremarks[3]) && $fremarks[3]!=''?$fremarks[3]:'NA' }}</td>
 									<td style="display: none">{{ !empty($fcreated_at[3]) && $fcreated_at[3]!=''?$fcreated_at[3]:'NA' }}</td>
 
 									<td style="display: none">{{ !empty($fdisposition[4]) && $fdisposition[4]!=''?$fdisposition[4]:'NA' }}</td>
 									<td style="display: none">{{ !empty($femployee_name[4]) && $femployee_name[4]!=''?$femployee_name[4]:'NA' }}</td>
 									<td style="display: none">{{ !empty($fremarks[4]) && $fremarks[4]!=''?$fremarks[4]:'NA' }}</td>
 									<td style="display: none">{{ !empty($fcreated_at[4]) && $fcreated_at[4]!=''?$fcreated_at[4]:'NA' }}</td>

								</tr>
								@php $count++; @endphp
								@endforeach
								@endif
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
	$(document).ready(function() {
		$('#datefrom2').val("{{$date}}").datetimepicker({ maxDate: 0,format:'Y-m-d',timepicker:false});
   		$('#dateto2').val("{{$dateto}}").datetimepicker({ maxDate: 0,format:'Y-m-d',timepicker:false});
		$(".setData").on('click', function() {
			var complaint_number = $(this).attr("complaint_number");
			var id = $(this).attr("id");
			alert(complaint_number);
			console.log(complaint_number);
			$("form[name='myForm']").find("input[name='complaint_number']:first").val(complaint_number);
			$("form[name='myForm']").find("input[name='id']:first").val(id);

			$.ajax({
				url: "{{route('getFollupinfo')}}",
				type: 'POST',
				data: {
					"followup_id": id,
					"_token": "{{ csrf_token() }}",
				},
				success: function(response) {

					$('#log_list').html(response.html);
				},
				error: function(response) {
					window.console.log(response);
				}
			});

		})
	})
	function followupFunc(id,complaintNumber){
		$("form[name='myForm']").find("input[name='complaint_number']:first").val(complaintNumber);
			$("form[name='myForm']").find("input[name='id']:first").val(id);

			$.ajax({
				url: "{{route('getFollupinfo')}}",
				type: 'POST',
				data: {
					"followup_id": id,
					"_token": "{{ csrf_token() }}",
				},
				success: function(response) {

					$('#log_list').html(response.html);
				},
				error: function(response) {
					window.console.log(response);
				}
			});
	}

	$(document).ready(function() {
		$('#call_time').datetimepicker({
			format: 'Y-m-d H:i:s',
			timepicker: true
		});
	});
</script>
@endsection