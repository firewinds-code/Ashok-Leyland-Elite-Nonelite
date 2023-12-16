@extends("layouts.masterlayout")
@section('title','Manage Communication')
@section('bodycontent')
	<div class="content-wrapper mobcss">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Manage Communication</h4>
                <div class="row">
                    <div class="col-md-12">
                    	<div id="insertDealer">
						<form name="myForm" method="post" enctype="multipart/form-data" action="{{url('store-communication')}}" onsubmit="return communicationFormValidate()">
	                        <input type="hidden" name="_token" value="{{csrf_token()}}">
	                        <input type="hidden" name="DataID" id="DataID">
                            <div class="row">                            
								<!--<div class="form-group col-md-3">
		                            <label for="complaint_type" >Complaint Type</label>
		                        	<select name="complaint_type" id="complaint_type" class="form-control" onchange="getSubComplaint(this.value,'')">
		                            	<option value="NA">--Select--</option>
		                            	@isset($complaint_details)
		                            		@foreach($complaint_details as $row)                                			
												<option value="{{$row->id}}">{{$row->complaint_type}}</option>
		                            		@endforeach
		                            	@endisset
		                            </select>
		                            <span id="complaint_type_error" style="color:red"></span> 
		                        </div>
		                        <div class="form-group col-md-3">
	                                <label for="sub_complaint_type">Sub Complaint Type</label>
	                                <select name="sub_complaint_type" id="sub_complaint_type" class="form-control">
	                                	<optgroup><option value="NA">--Select--</option></optgroup>
	                                </select>
	                                <span id="complaintcategory_error" style="color:red"></span> 
	                            </div>
		                         <div class="form-group col-md-3">
                            <label for="vehicle" >Product</label>
                            <select name="vehicle" id="vehicle" class="form-control" onchange="User_product_change(this.value,'')">
                            	<option value="NA">--Select--</option>
                            	@isset($vehicle_details)
                            		@foreach($vehicle_details as $row)
                            			<option value="{{$row->id}}">{{$row->vehicle}}</option>
                            		@endforeach
                            	@endisset
                            </select>
                            <span id="vehicle_error" style="color:red"></span> 
                        </div>
                        <div class="form-group col-md-3" id="td_segment">
                            <label for="Name">Segment</label>	                                    
                            <select name="segment" id="segment" class="form-control">
								<optgroup><option value="NA">--Select--</option></optgroup>
							</select>
                            <span id="segment_error" style="color:red"></span> 
                        </div>  
								<div class="form-group col-md-3">
		                            <label for="Region">Region</label>
		                            <select name="region" id="region" class="form-control" onchange="zone_change(this.value,'')">
		                            	<optgroup>
		                            		<option value="NA">--Select--</option>
		                            	@isset($region_details)
		                            		@foreach($region_details as $row)
		                            			<option value="{{$row->id}}">{{$row->region}}</option>
		                            		@endforeach
		                            	@endisset
		                            	</optgroup>
		                            </select>
		                            <span id="region_error" style="color:red"></span> 
		                        </div>
		                        <div class="form-group col-md-3">
		                            <label for="Location">Location</label>
		                            <select name="City" id="City" class="form-control">
		                            	<optgroup>
		                            		<option value="NA">--Select--</option>                                	
		                            	</optgroup>
		                            </select>
		                            <span id="City_error" style="color:red"></span> 
		                        </div>  -->                      
		                        <!--<div class="form-group col-md-3">
		                            <label for="communication_stage">Communication Stage</label>
		                            <select name="communication_stage" id="communication_stage" class="form-control">
		                            	<optgroup>
		                            		<option value="NA">--Select--</option>                                	
		                            		@for($i=1;$i<=10;$i++)
		                            			<option value="{{$i}}">{{$i}}</option>
		                            		@endfor                                
		                            	</optgroup>
		                            </select>
		                            <span id="communication_stage_error" style="color:red"></span> 
		                        </div>
		                        <div class="form-group col-md-3">
		                            <label for="day">Days</label>
		                            <select name="day" id="day" class="form-control">
		                            	<optgroup>
		                            		<option value="NA">--Select--</option>                                	
		                            		@for($i=1;$i<=50;$i++)
		                            			<option value="{{$i}}">{{$i}}</option>
		                            		@endfor                                
		                            	</optgroup>
		                            </select>
		                            <span id="day_error" style="color:red"></span> 
		                        </div>-->
								<div class="form-group col-md-3">
		                            <label for="Escalation To">Case Status</label>
		                            <select name="case_status" id="case_status" class="form-control">
		                            	<optgroup>
		                            		<option value="NA">--Select--</option>
		                            		<option value="caseCreate">Create</option>
		                            		<option value="caseUpdate">Update</option>
		                            		<option value="caseClose">Close</option>
			                            		
			                                	
		                            	</optgroup>
		                            </select>
		                            <span id="escalated_to_error" style="color:red"></span> 
		                        </div>
		                        <div class="form-group col-md-3">
		                            <label for="Escalation To">Escalation To</label>
		                            <select name="escalated_to[]" multiple id="escalated_to" class="form-control">
		                            	<optgroup>
		                            		<option value="NA">--Select--</option>
		                                	@isset($role_details)
		                                		@foreach($role_details as $row)
		                                			<option value="{{$row->id}}">{{$row->role}}</option>
		                                		@endforeach
		                                	@endisset
		                            	</optgroup>
		                            </select>
		                            <span id="escalated_to_error" style="color:red"></span> 
		                        </div>
		                        <div class="form-group col-md-3">
		                            <label for="CC To">CC To</label>
		                            <select name="cc_to[]" multiple id="cc_to" class="form-control">
		                            	<optgroup>
		                            		<option value="NA">--Select--</option>
		                                	@isset($role_details)
		                                		@foreach($role_details as $row)
		                                			<option value="{{$row->id}}">{{$row->role}}</option>
		                                		@endforeach
		                                	@endisset
		                            	</optgroup>
		                            </select>
		                            <span id="cc_to_error" style="color:red"></span> 
		                        </div>
		                        <!--<div class="form-group col-md-3">
		                            <label for="Email Message">Email Message</label>
		                            <textarea  name="email_msg" id="email_msg" class="form-control"></textarea>
		                            <span id="email_msg_error" style="color:red"></span> 
		                        </div>
		                        <div class="form-group col-md-3">
		                            <label for="SMS Message">SMS Message</label>
		                            <textarea  name="sms_msg" id="sms_msg" class="form-control"></textarea>
		                            <span id="sms_msg_error" style="color:red"></span> 
		                        </div>	-->							
                            </div>
                            <div class="box-footer">
                                <span class="pull-right">	
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
										<th style="display: none;">Escalated_To_ID</th>	
				                    	<th style="display: none;">Cc_To_ID</th>				                    	                   	
										<th >#</th>										                                 
										<th >Case Status</th>										                                 
										<th>Escalation To</th>                                       
										<th>CC To</th>
										<th style="text-align: right">Actions</th>
				                    </tr>
				                </thead>
				                <tbody>
					                @isset($rowData)               
					                @php $count=1; @endphp							
										@foreach($rowData as $row)
					                    <tr>
					                    	<td class="cls_Escalated_To_ID" style="display: none;">{{$row->Escalated_To_ID}}</td>
					                        <td class="cls_Cc_To_ID" style="display: none;">{{$row->Cc_To_ID}}</td>
					                        <td class="cls_id">{{$count}}</td>
					                        <td class="cls_id">{{$row->case_status}}</td>
					                        <td>{{$row->Escalated_To}}</td>                                                                   
					                        <td>{{$row->Cc_To}}</td>					                        
					                        <td style="text-align: right">
						                        <i class="fa fa-pencil-square-o" aria-hidden="true" id="{{$row->communication_id}}" data-position="left" data-tooltip="Edit" onclick="javascript:return EditCommunication(this);" style="cursor: pointer;"></i> 
						                        <a href="{{route('communication_delete.communicationDelete', ['id' => $row->communication_id])}}" onclick="return confirm('Do you want to delete?')"> <i class="fa fa-trash-o" aria-hidden="true" style="cursor: pointer;"></i>
						                        </a>
					                        </td>
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

function zone_change(el,ell){
	
	var zoneId = el;
	$.ajax({ url: '{{url("get-city-zone-id")}}',data :{'zone_id':zoneId},
			   	success: function(data) {			   		
				   var Result = data.split(",");
				   var str='';
				   Result.pop();
				   for (item1 in Result){
				   			var Result2 = Result[item1].split("~");
				   			/*if (jQuery.inArray(Result2[0], zoneId)!='-1')
							{
							str += "<option value='" + Result[item] + "' selected>" + Result[item] + "</option>";
							} 
							else
							{
							str += "<option value='" + Result[item] + "'>" + Result[item] + "</option>";
							}
				   			*/
				   			//str += "<option value='" + Result2[0]+ "'>" + Result2[1] + "</option>";
				   			
				   		 if (ell!=''){
				   		 	
				  			if (Result2[0]==ell)
							{
							str += "<option value='" + Result2[0] + "' selected>" + Result2[1] + "</option>";
							} 
							else
							{
							str += "<option value='" +Result2[0] + "'>" + Result2[1] + "</option>";
							}
					}
					 else
					  {
						 str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
					 }	
				   			
							
					}					   
				   document.getElementById('City').innerHTML = "<optgroup><option value='NA'>--Select--</option>" + str + "</optgroup>";
			   	}
		   	});	
	
}
</script>
@endsection