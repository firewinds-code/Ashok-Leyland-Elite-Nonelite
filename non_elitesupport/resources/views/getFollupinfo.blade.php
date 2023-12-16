<style>
.bgclass{
	background: #d8d7d5;

}</style>
</hr></br>
<h4 class="card-title">Ticket Call Log</h4>
<div class="table-responsive">
	<table id="order-listing1" class="table" style="background: #d8d7d5">
		<thead>
			<tr>
				<th>Complaint Number</th>
				<th>Date Of Complaint</th>
				<th>Disposition</th>
				<th>Remarks</th>
				<th>Created By</th>
			</tr>
		</thead>
		<tbody>
			@isset($folloupsData)
			@php $count=1; @endphp
			@php $sessionUpdateCase=Session::get('sessionUpdateCase'); @endphp
			@foreach($folloupsData as $row)
			<tr>
				@php $id =$row['id']; @endphp
				<td class="cls_id bgclass">{{$row->complaint_number}}</td>
				<td class="cls_id bgclass">{{ date('d-m-Y',strtotime($row->created_at))}}</td>
				<td class="cls_id bgclass">{{$row->disposition}}</td>
				<td class="cls_id bgclass">{{$row->remarks}}</td>
				<td class="cls_id bgclass">{{$row->employee_name}}</td>

			</tr>
			@php $count++; @endphp
			@endforeach
			@endisset
		</tbody>
	</table>
</div>
<script>
	$(document).ready(function() {
		$('#order-listing1').DataTable({
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
					exportOptions: {
						modifier: {
							page: 'all'
						}
					}
				}

			]
		});
	});
</script>