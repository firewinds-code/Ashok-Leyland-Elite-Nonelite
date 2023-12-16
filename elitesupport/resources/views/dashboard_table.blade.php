
<div style="background:#fff;babox-shadow: 0 7px 13px -6px black;border-radius: 4px;margin-top:10px;width: 100%;padding: 7px;">		 
	<div class="table-responsive">	
		<table id="order-dashboard" class="table custom">
		<thead>
			<tr>
				<th>Complaint Number</th>
				<th>Company Name</th>
				<th>Dealer Name</th>
				<th>Status</th>
				<th>Restoration Time</th>
				<th>Date Of Complaint</th>
				<th>Registration Number</th>
				<th>TAT</th>
				<th>Actual Response Time</th>
			</tr>
		</thead>
		<tbody>
			@isset($rowData)
				
				@foreach ($rowData as $row)
				@php
					$currentDate = date("Y-m-d H:i:s");
							$aca_created_at = $row->aca_created_at!=''?$row->aca_created_at:'NA';
							$aca_updated_at = $row->aca_updated_at!=''?$row->aca_updated_at:$currentDate;
							if($aca_created_at !='NA' && $aca_updated_at !='NA'){
								$first_date = new DateTime($aca_created_at);
								$second_date = new DateTime($aca_updated_at);
								$difference = $first_date->diff($second_date);
								$acaTime = $difference->d.':'.$difference->h.':'.$difference->i;
							}else{
								$acaTime = 'NA';
							}
							$acp_created_at = $row->acp_created_at!=''?$row->acp_created_at:'NA';
							$acp_updated_at = $row->acp_updated_at!=''?$row->acp_updated_at:$currentDate;
							if($acp_created_at !='NA' && $acp_updated_at !='NA'){
								$first_date = new DateTime($acp_created_at);
								$second_date = new DateTime($acp_updated_at);
								$difference = $first_date->diff($second_date);
								$acpTime = $difference->d.':'.$difference->h.':'.$difference->i;
							}else{
								$acpTime = 'NA';
							}
							$apc_created_at = $row->apc_created_at!=''?$row->apc_created_at:'NA';
							$apc_updated_at = $row->apc_updated_at!=''?$row->apc_updated_at:$currentDate;
							if($apc_created_at !='NA' && $apc_updated_at !='NA'){
								$first_date = new DateTime($apc_created_at);
								$second_date = new DateTime($apc_updated_at);
								$difference = $first_date->diff($second_date);
								$apcTime = $difference->d.':'.$difference->h.':'.$difference->i;
							}else{
								$apcTime = 'NA';
							}
							$vbt_created_at = $row->vbt_created_at!=''?$row->vbt_created_at:'NA';
							$vbt_updated_at = $row->vbt_updated_at!=''?$row->vbt_updated_at:$currentDate;
							if($vbt_created_at !='NA' && $vbt_updated_at !='NA'){
								$first_date = new DateTime($vbt_created_at);
								$second_date = new DateTime($vbt_updated_at);
								$difference = $first_date->diff($second_date);
								$vbtTime = $difference->d.':'.$difference->h.':'.$difference->i;
							}else{
								$vbtTime = 'NA';
							}	
							
							$days =0; $hour = 0; $minute = 0;
							if($acaTime !='NA'){
								$acaTimeArr =  explode(":",$acaTime);
								$days += $acaTimeArr[0];
								$hour += $acaTimeArr[1];
								$minute += $acaTimeArr[2];
							}
							if($acpTime !='NA'){
								$acpTimeArr =  explode(":",$acpTime);
								$days += $acpTimeArr[0];
								$hour += $acpTimeArr[1];
								$minute += $acpTimeArr[2];
							}
							if($apcTime !='NA'){
								$apcTimeArr =  explode(":",$apcTime);
								$days += $apcTimeArr[0];
								$hour += $apcTimeArr[1];
								$minute += $apcTimeArr[2];
							}
							if($vbtTime !='NA'){
								$vbtTimeArr =  explode(":",$vbtTime);
								$days += $vbtTimeArr[0];
								$hour += $vbtTimeArr[1];
								$minute += $vbtTimeArr[2];
							}
							if(isset($row->created_at)){
								//$first_date = new DateTime($row->created_at);
								$first_date1 = $row->created_at;
								$first_date1 = date('Y-m-d H:i:s',strtotime('+'.$days.' days',strtotime($first_date1)));
								$first_date1 = date('Y-m-d H:i:s',strtotime('+'.$hour.' hour',strtotime($first_date1)));
								$first_date1 = date('Y-m-d H:i:s',strtotime('+'.$minute.' minute',strtotime($first_date1)));
								$first_date = new DateTime($first_date1);
								$second_date = new DateTime(date("Y-m-d H:i:s"));
								$difference = $first_date->diff($second_date);
								$tat_scheduled =($difference->d ).'-D '.($difference->h ).'-H	'.($difference->i ).'-M';
							}else{
								$tat_scheduled='NA';
							}
							if($tatData !=0 && $days!=0){
								$check = $tatData / 24;
							}else{
								$check = 0;
							}
				@endphp
				@if($days >= $check)
				<tr>
					@php  $id =$row->id;  @endphp
					<td class="cls_complaint_number"><a href="{{route('update-case.updateCase',['id' =>$id])}}" > {{$row->complaint_number}}</a></td>
					<td class="cls_id">{{$row->owner_name}}</td>
					<td class="cls_id">{{$row->dealer_name}}</td>
					<td class="cls_id">{{$row->remark_type}}</td>
					@if($row->tat_scheduled !='')
					<td class="cls_id">{{ date('d-m-Y H:i:s',strtotime($row->tat_scheduled))}}</td>
					@else
					<td class="cls_id">NA</td>
					@endif
					
					<td class="cls_id">{{ date('d-m-Y',strtotime($row->created_at))}}</td>
					<td class="cls_id">{{$row->reg_number}}</td>
					@php
						$remTypeArr = array('Arranging Parts Locally','Awaiting parts from AL','Awaiting AL Approval','Awaiting completion from Ancillary suppliers','Awaiting completion of contracted Job','Awaiting customer approval','Awaiting customer Payment','Awaiting Good will Approval','Awaiting parts from another dealer branch','Awaiting parts from customer','Dealer Feedback','Investigation in progress','Load transfer in progress','Man power not available','Mechanic left to BD spot','Mechanic reached BD spot','Moved to another vehicle on urgency','Public Holiday','Reassigned support','Response Delay','Response not Initiated','Restored by Self','Restored by Unknown support','Restored by Support','Vehicle being Towed','Vehicle reached support point','Work held up due to bandh','Work held up due to injury/accident','Work in progress','Workshop closed - Sunday','Assigned');
						/* if(in_array($row->remark_type,$remTypeArr)){
							$diff = abs(strtotime($row->created_at) - strtotime(date("Y-m-d H:i:s"))); 
							$first_date = new DateTime($row->created_at);
							$second_date = new DateTime(date("Y-m-d H:i:s"));
							$difference = $first_date->diff($second_date);
							$tat_scheduled =$difference->d.'-D '.$difference->h.'-H	'.$difference->i.'-M';
							
						}else{ */
							
					/* 	} */
					@endphp
					<td class="cls_id">{{$tat_scheduled}}</td>
					<td class="cls_id">{{ $row->actual_response_time !=''?date('d-m-Y H:i:s',strtotime($row->actual_response_time)):'NA' }}</td>
					   {{-- <td class="cls_id">{{$row->owner_name}}</td>
					   <td class="cls_id">{{$row->contact_name}}</td>
					   <td class="cls_id">{{$row->caller_name}}</td> --}}
				</tr>
				@endif
				@endforeach
			@endisset
		</tbody>
	</table>
	</div>
</div>

<script>
	$('#order-dashboard').DataTable({
		"pageLength": 5,
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
filename: 'Dashboard',
exportOptions: { modifier: { page: 'all'} }
},
	/*{
extend: 'csv',
text: 'CSV',
className: 'exportExcel',
filename: 'Test_Csv',
exportOptions: { modifier: { page: 'all'} }
},*/
{
extend: 'pdf',
text: 'PDF',
className: 'exportExcel',
filename: 'Dashboard',
exportOptions: { modifier: { page: 'all'} }
}]
});
</script>
	