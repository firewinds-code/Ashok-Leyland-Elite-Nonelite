@extends("layouts.masterlayout")
@section('title','Access')
@section('bodycontent')
	<div class="content-wrapper mobcss">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Manage Access</h4>
                <div class="row">
                    <div class="col-md-12">
                    	<div id="insertBrand" >
						<form name="myForm" method="post" enctype="multipart/form-data" action="{{url('store-access')}}" onsubmit="return accessValidation()">
	                        <input type="hidden" name="_token" value="{{csrf_token()}}">
							<input type="hidden" name="dataid" id="dataid"> 
	                            <div class="row">                                
									<div class="form-group col-md-3">
										<label for="usertype_id">User Type</label> <span style="color: red;">*</span> 
										<select name="usertype_id" id="usertype_id" tabindex="1" class="form-control" onchange="fn_role_change(this.value,'');">
											<optgroup>
												<option Value="NA">--select--</option>
												@isset($roleUserTypeData)
													@foreach($roleUserTypeData as $row)
														<option Value="{{$row->id}}">{{$row->usertype}}</option>
													@endforeach
												@endisset
											</optgroup>
										</select>
										<span id="usertype_id_error" style="color:red"></span>
									</div>
									<div class="form-group col-md-3">
										<label for="role">Role</label>
										<select name="role" id="role" class="form-control"></select>
										<span id="role_error" style="color:red"></span>
									</div>
									{{--<div class="form-group col-md-3">
										<label for="location">Location</label> <span style="color: red;">*</span> 
										<select name="location" id="location" class="form-control">
											<option value="NA">Select</option>
											<option value="panindia">Pan India</option>
											<option value="region">Region</option>
										</select>
										<span id="location_error" style="color:red"></span>
									</div>--}}
									<div class="form-group col-md-3">
										<label for="escalate_to">Escalation To</label> <span style="color: red;">*</span> 
										<select name="escalate_to" id="escalate_to" class="form-control">
											<option value="NA">Select</option>
											<option value="Yes">Yes</option>
											<option value="No">No</option>
										</select>
										<span id="escalate_to_error" style="color:red"></span>
									</div>
									<div class="form-group col-md-3">
										<label for="escalate_cc">Escalation CC</label> <span style="color: red;">*</span> 
										<select name="escalate_cc" id="escalate_cc" class="form-control">
											<option value="NA">Select</option>
											<option value="Yes">Yes</option>
											<option value="No">No</option>
										</select>
										<span id="escalate_cc_error" style="color:red"></span>
									</div>
									{{--<!--<div class="form-group col-md-3">
										<label for="create_user">Create User</label> <span style="color: red;">*</span> 
										<select name="create_user" id="create_user" class="form-control">
											<option value="NA">Select</option>
											<option value="Yes">Yes</option>
											<option value="No">No</option>
										</select>
										<span id="create_user_error" style="color:red"></span>
									</div>
									<div class="form-group col-md-3">
										<label for="update_complaint">Update Complaint</label>											
										<select name="update_complaint" id="update_complaint" class="form-control">
											<option value="NA">Select</option>
											<option value="Yes">Yes</option>
											<option value="No">No</option>
										</select>
										<span id="update_complaint_error" style="color:red"></span>
									</div>-->--}}									
									<div class="form-group col-md-3">
										<label for="menu_new_case">New Case</label> <span style="color: red;">*</span> 
										<select name="menu_new_case" id="menu_new_case" class="form-control">
											<option value="NA">Select</option>
											<option value="Yes">Yes</option>
											<option value="No">No</option>
										</select>
										<span id="menu_new_case_error" style="color:red"></span>
									</div>
									<div class="form-group col-md-3">
										<label for="post_complaint_survey">Approval</label> <span style="color: red;">*</span> 
										<select name="approval" id="approval" class="form-control">
											<option value="NA">Select</option>
											<option value="Yes">Yes</option>
											<option value="No">No</option>
										</select>
										<span id="approval_error" style="color:red"></span>
									</div>
									<div class="form-group col-md-3">
										<label for="menu_update_case">Update Case</label> <span style="color: red;">*</span> 
										<select name="menu_update_case" id="menu_update_case" class="form-control">
											<option value="NA">Select</option>
											<option value="Yes">Yes</option>
											<option value="No">No</option>
										</select>
										<span id="menu_update_case_error" style="color:red"></span>
									</div>
									<div class="form-group col-md-3">
										<label for="post_complaint_survey">Post Complaint Survey</label> <span style="color: red;">*</span> 
										<select name="post_complaint_survey" id="post_complaint_survey" class="form-control">
											<option value="NA">Select</option>
											<option value="Yes">Yes</option>
											<option value="No">No</option>
										</select>
										<span id="post_complaint_survey_error" style="color:red"></span>
									</div>
									<div class="form-group col-md-3">
										<label for="re_opening">Re Opening</label> <span style="color: red;">*</span> 
										<select name="re_opening" id="re_opening" class="form-control">
											<option value="NA">Select</option>
											<option value="Yes">Yes</option>
											<option value="No">No</option>
										</select>
										<span id="re_opening_error" style="color:red"></span>
									</div>
									<div class="form-group col-md-3">
										<label for="close_complaint">Close Complaint</label> <span style="color: red;">*</span>
										<select name="close_complaint" id="close_complaint" class="form-control">
											<option value="NA">Select</option>
											<option value="Yes">Yes</option>
											<option value="No">No</option>
										</select>
										<span id="close_complaint_error" style="color:red"></span>
									</div>								
									<div class="form-group col-md-3">
										<label for="menu_report">Report</label> <span style="color: red;">*</span> 
										<select name="menu_report" id="menu_report" class="form-control">
											<option value="NA">Select</option>
											<option value="Yes">Yes</option>
											<option value="No">No</option>
										</select>
										<span id="menu_report_error" style="color:red"></span>
									</div>
									<div class="form-group col-md-3">
										<label for="menu_dashboard">Dashboard</label> <span style="color: red;">*</span> 
										<select name="menu_dashboard" id="menu_dashboard" class="form-control">
											<option value="NA">Select</option>
											<option value="Yes">Yes</option>
											<option value="No">No</option>
										</select>
										<span id="menu_dashboard_error" style="color:red"></span>
									</div>                          
	                            </div>
	                            <div class="box-footer">
	                                <span class="pull-right">
									<button type="button" onclick="reloadPage();" class="btn-secondary">Cancel</button>	
	                                <input type="submit"name="submit" id="submit" value="Submit" class="btn-secondary">
	                                </span>
	                            </div>
                        </form>  
						</div> 
				               
                        <div class="clear"></div>
                        <hr>                       
                        <div class="table-responsive">
                            <table id="order-listing" class="table">
                                <thead>
                                    <tr>
										
										<th class="d-none">usertype_id</th>
										<th class="d-none">userrole</th>
										<th class="d-none">Approval</th>
										<th class="d-none">Update Case</th>
										<th class="d-none">Post Complaint Survey </th>
										<th class="d-none">Re Opening</th>
										<th class="d-none">Close Complaint</th>
										<th>Actions</th> 
										<th>User Type</th> 
										<th>Role</th>										                                      
										<th>Escalation To</th>                                       
										<th>Escalation CC</th>
										<th>New Case</th>
										<th>Report</th>
										<th>Dashboard</th>
										
                                    </tr>
                                </thead>
                                <tbody>
                                @isset($rowData)
								
                                @php $count=1; @endphp							
									@foreach($rowData as $row)
                                    <tr>
                                        
                                        <td class="cls_usertype d-none">{{$row->usertype_id}}</td>
                                        <td class="cls_userrole d-none">{{$row->userrole}}</td>
                                        <td class="cls_approval d-none">{{$row->approval}}</td>
                                        <td class="cls_menu_update_case d-none">{{$row->menu_update_case}}</td>
                                        <td class="cls_post_complaint_survey d-none">{{$row->post_complaint_survey}}</td>
                                        <td class="cls_re_opening d-none">{{$row->re_opening}}</td>
                                        <td class="cls_close_complaint d-none">{{$row->close_complaint}}</td>
										<td>
											<i class="fa fa-pencil-square-o" aria-hidden="true" id="{{$row->id}}" data-position="left" data-tooltip="Edit" onclick="javascript:return Editaccess(this);" style="cursor: pointer;"></i>
											<a href="{{route('access_delete.accessDelete', ['id' => $row->id])}}" onclick="return confirm('Do you want to delete?')">
												<i class="fa fa-trash-o" aria-hidden="true" style="cursor: pointer;"></i>
											</a>
										</td>         
                                        <td>{{$row->usertype}}</td>                                                                                                          
                                        <td>{{$row->userRoleName}}</td>
                                        <td class="cls_escalate_to">{{$row->escalate_to}}</td>                                                                   
                                        <td class="cls_escalate_cc">{{$row->escalate_cc}}</td>                                        
                                        <td class="cls_new_case">{{$row->menu_new_case}}</td>                                        
										<td class="cls_menu_report">{{$row->menu_report}}</td>
                                        <td class="cls_menu_dashboard">{{$row->menu_dashboard}}</td>
                                        
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
	   	function fn_role_change(el, ell){
			
			id=el;
			if (ell!='')
			{
				id=el;
			}
			else
			{}
		   	$.ajax({ url: '{{url("get-role")}}',data :{'id':id},
			   	success: function(data) {
				   var Result = data.split(",");var str = '';
				   Result.pop();
				   for (item in Result) {
				   	var Result2 = Result[item].split("~");
					   if (ell!='') 
					   {
						   
						   if (ell==Result2[0])
						   {
							   str += "<option value='" + Result2[0] + "' selected>" + Result2[1] + "</option>";
						   } 
						   else
						   {
							   str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
						   }
					   } else {
						   str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
					   }
				   }
				   document.getElementById('role').innerHTML = "<optgroup><option value='NA'>--Select--</option>" + str + "</optgroup>";
			   	}

		   	});
	   	}
		function Editaccess(el){
			$('#usertype_id').val($(el).parents('td').parents('tr').find('.cls_usertype').text());
			$('#userrole').val($(el).parents('td').parents('tr').find('.cls_userrole').text());
			var user = $(el).parents('td').parents('tr').find('.cls_usertype').text();
			var role = $(el).parents('td').parents('tr').find('.cls_userrole').text();
			fn_role_change(user,role);
			$('#re_opening').val($(el).parents('td').parents('tr').find('.cls_re_opening').text());
			$('#escalate_to').val($(el).parents('td').parents('tr').find('.cls_escalate_to').text());
			$('#escalate_cc').val($(el).parents('td').parents('tr').find('.cls_escalate_cc').text());
			$('#create_user').val($(el).parents('td').parents('tr').find('.cls_create_user').text());
			$('#update_complaint').val($(el).parents('td').parents('tr').find('.cls_update_complaint').text());
			$('#close_complaint').val($(el).parents('td').parents('tr').find('.cls_close_complaint').text());
			$('#post_complaint_survey').val($(el).parents('td').parents('tr').find('.cls_post_complaint_survey').text());
			
			$('#approval').val($(el).parents('td').parents('tr').find('.cls_approval').text());
			$('#menu_new_case').val($(el).parents('td').parents('tr').find('.cls_new_case').text());
			$('#menu_update_case').val($(el).parents('td').parents('tr').find('.cls_menu_update_case').text());
			$('#menu_report').val($(el).parents('td').parents('tr').find('.cls_menu_report').text());
			$('#menu_dashboard').val($(el).parents('td').parents('tr').find('.cls_menu_dashboard').text());
			$('#dataid').val(el.id);
		}
   </script>
@endsection
