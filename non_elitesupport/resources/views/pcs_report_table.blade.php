<div class="table-responsive">
<table id="order-listing2" class="table custom">
<thead>
	<tr>
		<th>Case #</th>
		<th>Case Type</th>
		<th>Customer Org</th>
		<th>Contact person</th>
		<th>Phone number</th>
		<th>Complaint Category</th>
		<th>Sub category</th>
		<th>Brand</th>
		<th>Product</th>
		<th>Segment</th>
		<th>Region</th>
		<th>Dealer</th>
		<th>Location</th>
		<th>Assigned To</th>
		<th>Reason for dropping</th>
		<th>Date of complaint registration</th>
		<th>Case Description</th>
		<th style="display: none">Full Case Description</th>
		<th>Date of acknowledgement</th>
		<th>Mode of closure</th>
		<th>Communication give to customer</th>
		<th style="display: none">Full Communication give to customer</th>
		<th>Date of completion</th>
		<th>PCS status</th>
		<th>PCS Dropped Reason</th>

	</tr>
</thead>
<tbody>

			@foreach($sql as $row)
			<tr>
				<td>{{$row->complaint_number}}</td>
				<td>{{$row->case_type}}</td>
				<td>{{$row->org}}</td>
				<td>{{$row->custname}}</td>
				<td>{{$row->mobile1}}</td>
				<td>{{$row->complaint_type}}</td>
				<td>{{$row->sub_complaint_type}}</td>
				<td>{{$row->brand}}</td>
				<td>{{$row->vehicle}}</td>
				<td>{{$row->segment}}</td>
				<td>{{$row->region}}</td>
				<td>{{$row->dealer_name}}</td>
				<td>{{$row->city}}</td>
				<td>{{$row->assignedTo}}</td>
				<td>{{$row->resonDrop}}</td>
				<td>{{$row->dateRegistration}}</td>
				<td >{{substr($row->description, 0, 20) . '...'}}</td>
				<td style="display: none;">{{$row->description}}</td>				
				<td>{{$row->acknowledgeDate}}</td>
				<td>{{$row->mode_name}}</td>
				<td >{{substr($row->communication_customer, 0, 20) . '...'}}</td>
				<td style="display: none;">{{$row->communication_customer}}</td>
				<td>{{$row->complitionDate}}</td>
				<td>{{$row->pcs_status}}</td>
				<td>{{$row->pcs_dropped_reason}}</td>
			</tr>
			@endforeach
</tbody>
</table>
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
<script>
	$('#order-listing2').DataTable({
		"pageLength": 10,
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
filename: 'PCS_status_drilldown_report',
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
extend: 'pdfHtml5',
orientation : 'landscape',pageSize: 'A0',    //A0 is the largest A5 smallest(A0,A1,A2,A3,legal,A4,A5,letter))
text: 'PDF',
className: 'exportExcel',
filename: 'PCS_status_drilldown_report',
exportOptions: { modifier: { page: 'all'},

 }
}]
});
</script>
	