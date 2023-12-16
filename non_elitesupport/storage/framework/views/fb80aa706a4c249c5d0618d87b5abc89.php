
<?php $__env->startSection('title','Active Calling List'); ?>
<?php $__env->startSection('bodycontent'); ?>
<div class="content-wrapper mobcss">
	<div class="card">
		<div class="card-body">
			<div class="row">
				
				<div class="col-md-12 ">
					</br>
					<div class="col">
						<div class="collapse multi-collapse" id="multiCollapseExample2" style="position: relative;top: 10px;">
							<div class="card card-body">
								
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
					<div class="clear"></div>
					<hr>
					
					<div class="table-responsive">
						<h4 class="card-title">Active Call List</h4>
						<div class="col-md-12" style="border: 1px solid #ccc">
							<div class="clear"></div>
							<form name="myForm" method="post" enctype="multipart/form-data" action="<?php echo e(url('store-followups-form')); ?>">
								<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
								<div class="row">
									 <div class="form-group col-md-3">
										<label for="datefrom" >Date From</label>
										<span style="color: red;">*</span>
										<input type="text" name="datefrom" id="datefrom2" autocomplete="off" class="form-control" value="<?php if(isset($date)): ?><?php echo e($date); ?> <?php endif; ?>" />
										<span id="datefrom_error" style="color:red"></span> 
									</div>
									<div class="form-group col-md-3">
										<label for="dateto" >Date To</label>
										<span style="color: red;">*</span>
										<input type="text" name="dateto" id="dateto2" autocomplete="off" class="form-control" value="<?php if(isset($dateto)): ?><?php echo e($dateto); ?> <?php endif; ?>" />
										<span id="dateto_error" style="color:red"></span> 
									</div>			
											
									<div class="form-group col-md-3">
										<label for="dateto" >Ticket Status</label>
										<span style="color: red;">*</span>
										<select name="remark_type[]" id="remark_type" multiple class="form-control" required>
											<?php if(isset($remark_type)): ?>
											<?php $i=0; ?>
												<?php $__currentLoopData = $remark_type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
														<?php if($row->type != 'Closed'): ?>
														<option value="<?php echo e($row->type); ?>" <?php echo e(in_array($row->type,$remark_typeArr)?'selected':''); ?>><?php echo e($row->type); ?></option>
														<?php $i++; ?>
														<?php endif; ?>
												<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
											<?php endif; ?>
										</select> 
									</div>						
								</div>
								<div class="clear"></div><br>
								<div class="row">
									 <div class="form-group col-md-3">
										<input type="submit" name="submit" id="submit" value="Submit" class="btn-secondary">
										
									</div>
								</div>
							</form>
							<br>
						</div>
						<br>
						<table id="caller-listing" class="table">
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
									<th>Created By</th>
									
									
									
								</tr>
							</thead>
							<tbody>
								<?php if(isset($finalData)): ?>
								<?php if($finalData !=''): ?>
								<?php $count=1; $currentDate = date('Y-m-d H:i:s'); ?>
								
								
								<?php $__currentLoopData = $finalData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<?php
									$rowId = $row['caseId'];
										if(!empty($row['followup_time'])){
											$checkTime = $row['followup_time'];
										}else if(!empty($row['actual_response_time'])){
											$checkTime = $row['actual_response_time'];
										}else{
											$checkTime = $row['case_estimated_response_time'];
										}
									?>
									
									<?php if($currentDate > $checkTime && $row['ticket_status'] != 'Completed'): ?>
 										<tr style="background-color: red;color: #ffffff;">
 									<?php elseif($row['ticket_status'] == 'Completed'): ?>
 										<tr style="background-color: #ffbf00;color: #ffffff;">
 									<?php else: ?>
 										<tr style="background-color: green;color: #ffffff;">
 									<?php endif; ?>
									<?php $id =$row['id']; ?>
									<td class="cls_complaint_number">
										
										
								<a href="<?php echo e(route('update-case.updateCase',['id' => $rowId])); ?>"> <?php echo e($row['complaint_number']); ?></a>
									</td>
									<td><?php echo e(date('d-m-Y',strtotime($row['created_at']))); ?></td>
									<td><?php echo e($checkTime); ?></td>
									<td><?php echo e($row['dealer_mob_number']); ?></td>
									<td><?php echo e($row['dealer_name']); ?></td>
									<td><?php echo e($row['assign_work_manager'] !=''?$row['assign_work_manager']:$row['followup_name']); ?></td>
									<td><?php echo e($row['assign_work_manager_mobile'] !=''?$row['assign_work_manager_mobile']:$row['followups_number']); ?></td>
									<td><?php echo e($row['reg_number']); ?></td>
									<td><?php echo e($row['ticket_status']); ?></td>
									<td><?php echo e($row['created_by']); ?></td>
									
									
									<?php 
										/* $k = 0;
										$fdisposition = explode("##",$row['fdisposition']); 
										$femployee_name = explode("##",$row['femployee_name']); 
										$fcreated_at = explode("##",$row['fcreated_at']); 
										$fremarks = explode("##",$row['fremarks']);  */
										 
										?>
									
									

								</tr>
								<?php $count++; ?>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								<?php endif; ?>
								<?php endif; ?>
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
		/* window.setTimeout(function () {
			window.location.reload();
		}, 30000); */
		$('#datefrom2').val("<?php echo e($date); ?>").datetimepicker({ maxDate: 0,format:'Y-m-d',timepicker:false});
   		$('#dateto2').val("<?php echo e($dateto); ?>").datetimepicker({ maxDate: 0,format:'Y-m-d',timepicker:false});
		/* $(".setData").on('click', function() {
			var complaint_number = $(this).attr("complaint_number");
			var id = $(this).attr("id");
			//alert(complaint_number);
			console.log(complaint_number);
			$("form[name='myForm']").find("input[name='complaint_number']:first").val(complaint_number);
			$("form[name='myForm']").find("input[name='id']:first").val(id);

			$.ajax({
				url: "<?php echo e(route('getFollupinfo')); ?>",
				type: 'POST',
				data: {
					"followup_id": id,
					"_token": "<?php echo e(csrf_token()); ?>",
				},
				success: function(response) {

					$('#log_list').html(response.html);
				},
				error: function(response) {
					window.console.log(response);
				}
			});

		}); */
		$('#caller-listing').DataTable({
				dom: 'Bfrtip',
				"order": [[ 2, "desc" ]],				
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
						filename: '<?php echo $__env->yieldContent("title"); ?>',
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
	})
	/* function followupFunc(id,complaintNumber){
		$("form[name='myForm']").find("input[name='complaint_number']:first").val(complaintNumber);
			$("form[name='myForm']").find("input[name='id']:first").val(id);

			$.ajax({
				url: "<?php echo e(route('getFollupinfo')); ?>",
				type: 'POST',
				data: {
					"followup_id": id,
					"_token": "<?php echo e(csrf_token()); ?>",
				},
				success: function(response) {

					$('#log_list').html(response.html);
				},
				error: function(response) {
					window.console.log(response);
				}
			});
	} */

	$(document).ready(function() {
		$('#call_time').datetimepicker({
			format: 'Y-m-d H:i:s',
			timepicker: true
		});
	});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make("layouts.masterlayout", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\wamp64\www\ashokleyland\non_elitesupport\resources\views/followups.blade.php ENDPATH**/ ?>