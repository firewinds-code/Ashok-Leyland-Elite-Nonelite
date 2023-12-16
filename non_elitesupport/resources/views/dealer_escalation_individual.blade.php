@extends("layouts.masterlayout")
@section('title','Manage Dealer Escalation')
@section('bodycontent')
	<div class="content-wrapper mobcss">
        <div class="card">
            <div class="card-body">
	            <div class="row">
		            <div class="form-group col-md-6">
		                <h4 class="card-title">Manage Dealer Escalation</h4> 
		            </div>
		            <div class="form-group col-md-6" style="text-align: right;">
	                    <a href="{{redirect()->back()->getTargetUrl() }}" class="btn-secondary" style="padding: 5px;">Back</a>
	                </div>
	            </div>
                <div class="row">
                    <div class="col-md-12">
                    	<div id="insertDealer">
						<form name="myForm" method="post" enctype="multipart/form-data" action="{{url('store-dealer-escalation')}}" onsubmit="return escalationFormValidate()">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="hidden" name="DataID" id="DataID">
                            <div class="row"> 
                            <div class="form-group col-md-3">
                                <label for="matrix_identifier">Matrix Identifier</label>
                                <span style="color:red">*</span>
                                <input type="text" name="matrix_identifier" id="matrix_identifier" value="{{$matrix_identifier}}" class="form-control" disabled="">
                                <input type="hidden" name="matrix_identifier" value="{{$matrix_identifier}}" >
                                <span id="matrix_identifier_error" style="color:red"></span> 
                            </div>
							<div class="form-group col-md-3">
	                            <label for="complaint_type" >Complaint Type</label> <span style="color: red">*</span>
	                            <input type="hidden" name="complaint_type" value="{{$complaint_type}}" >
	                        	<select name="complaint_type" id="complaint_type" class="form-control" onchange="getSubComplaint(this.value,'')" disabled="">
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
                                <label for="sub_complaint_type">Sub Complaint Type</label> <span style="color: red">*</span>
                                <input type="hidden" name="sub_complaint_type[]" value="{{$sub_complaint_type}}" >
                                <select name="sub_complaint_type[]" multiple id="sub_complaint_type" class="form-control" disabled="">
									<optgroup><option value="NA">--Select--</option></optgroup>
                                </select>
                                <span id="complaintcategory_error" style="color:red"></span> 
                            </div>
                        <div class="form-group col-md-3">
                            <label for="vehicle" >Product</label> <span style="color: red">*</span>
                            <input type="hidden" name="vehicle" value="{{$vehicle}}" >
                            <select name="vehicle" id="vehicle" class="form-control" onchange="User_product_change(this.value,'')" disabled="">
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
                            <label for="Name">Segment</label> <span style="color: red">*</span>
                            <input type="hidden" name="segment[]" value="{{$segment}}" >
                            <select name="segment[]" multiple id="segment" class="form-control" disabled="">
								<optgroup><option value="NA">--Select--</option></optgroup>
							</select>
                            <span id="segment_error" style="color:red"></span> 
                        </div> 
						                  
                        <div class="form-group col-md-3">
                            <label for="escalation_stage">Escalation Stage</label> <span style="color: red">*</span>
                            <select name="escalation_stage" id="escalation_stage" class="form-control">
                            	<optgroup>
                            		<option value="NA">--Select--</option>                                	
                            		@for($i=1;$i<=10;$i++)
                            			<option value="{{$i}}">{{$i}}</option>
                            		@endfor                                
                            	</optgroup>
                            </select>
                            <span id="escalation_stage_error" style="color:red"></span> 
                        </div>
                        <div class="form-group col-md-3">
                            <label for="day">Days</label> <span style="color: red">*</span>
                            <select name="day" id="day" class="form-control">
                            	<optgroup>
                            		<option value="NA">--Select--</option>                                	
                            		@for($i=1;$i<=50;$i++)
                            			<option value="{{$i}}">{{$i}}</option>
                            		@endfor                                
                            	</optgroup>
                            </select>
                            <span id="day_error" style="color:red"></span> 
                        </div>
						<div class="form-group col-md-3">
                            <label for="escalated_to">Escalation To</label> <span style="color: red">*</span>
                            <select name="escalated_to" id="escalated_to" class="form-control">
                            	<optgroup>
                            		<option value="NA">--Select--</option>
                                	@isset($to_role_details)
                                		@foreach($to_role_details as $row)
                                			<option value="{{$row->id}}">{{$row->role}}</option>
                                		@endforeach
                                	@endisset
                            	</optgroup>
                            </select>
                            <span id="escalated_to_error" style="color:red"></span> 
                        </div>
                        <div class="form-group col-md-4">
                            <label for="cc_to">CC To</label> <span style="color: red">*</span>
                            <select name="cc_to[]" multiple id="cc_to" class="form-control" style="height: 138px;">
								@isset($cc_role_details)
									@foreach($cc_role_details as $row)
										<option value="{{$row->id}}">{{$row->role}}</option>
									@endforeach
								@endisset
                            </select>
                            <span id="cc_to_error" style="color:red"></span> 
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
                        @php
							$count=0;
							$ccName = explode("~",$ccRoleName);
							$multiSegmentName = explode("~",$multiSegmentName);
						@endphp                    
						<div class="table-responsive">
							<table id="order-listing" class="table" data-order='[[ 10, "asc" ]]'>
				                <thead>
				                    <tr>
				                    	                   	
										<th>Actions</th>
										<th>Complaint Type</th> 
										<!--<th>Sub Complaint Type</th>--> 
										<th>Product</th>						                                      
										<!--<th>Segment</th>-->						                                      
										<th>Escalation Stage</th>                                       
										<th>Days</th>                                       
										<th>Escalation To</th>
										<th>CC To</th>
										<th>Segment</th>
										<th style="display: none;">sub_complaint_type_name</th>	
										<th style="display: none;">complaint_ID</th>	
				                    	<th style="display: none;">Sub complaint_ID</th>	
				                    	<th style="display: none;">vehicle_ID</th>                    	
				                    	<th style="display: none;">Escalated_To_ID</th>	
				                    	<th style="display: none;">Cc_To_ID</th>                    	
				                    	<th style="display: none;">Segment</th> 									
				                    </tr>
				                </thead>
				                <tbody>
				                @isset($escalateData)
				                
				               							
									@foreach($escalateData as $row)					
				                    <tr>
										<td>
											<i class="fa fa-pencil-square-o" aria-hidden="true" id="{{$row->Escalation_id}}" data-position="left" data-tooltip="Edit" onclick="javascript:return EditEscalation(this);" style="cursor: pointer;"></i>
											<a href="{{route('dealer_escalation_delete.dealerEscalationDelete', ['id' => $row->Escalation_id])}}" onclick="return confirm('Do you want to delete?')">
												<i class="fa fa-trash-o" aria-hidden="true" style="cursor: pointer;"></i>
											</a>
										</td>
				                        
				                        <td>{{$row->complaint_type}}</td>                                                                   
				                        <!--<td>{{$row->sub_complaint_type_name}}</td>-->                                                                   
				                        <td>{{$row->vehicle}}</td>                                                                                        
				                        <!--<td>{{$row->segmentName}}</td> -->                                                                                       
				                        <td class="cls_escalation_stage">{{$row->escalation_stage}}</td>                                                                   
				                        <td class="cls_day">{{$row->day}}</td>                                                                   
				                        <td>{{$row->Escalated_To}}</td>
				                        <td>{{$ccName[$count]}}</td>
				                        <td>{{$multiSegmentName[$count]}}</td>
				                        <td style="display: none;">{{$row->sub_complaint_type_name}}</td> 
				                        <td class="cls_complaint_ID" style="display: none;">{{$row->complaint_ID}}</td>
				                        <td class="cls_sub_complaint_type" style="display: none;">{{$row->sub_complaint_type}}</td>
				                        <td class="cls_vehicle_ID" style="display: none;">{{$row->vehicle_ID}}</td> 
				                        <td class="cls_Escalated_To_ID" style="display: none;">{{$row->Escalated_To_ID}}</td>
				                        <td class="cls_Cc_To_ID" style="display: none;">{{$row->Cc_To_ID}}</td>
				                        <td class="cls_segment" style="display: none;">{{$row->segment}}</td>			                       
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
$( document ).ready(function() {
    getCcRole('');
    var segment = {!! json_encode($segment) !!};
  var sub_complaint_type = {!! json_encode($sub_complaint_type) !!};
  var complaint_type = {{$complaint_type}};   
  var vehicle = {{$vehicle}};   
   
  // alert(segment);
   getSubComplaint(complaint_type,sub_complaint_type);
  	
	User_product_change(vehicle,segment);
	$('#vehicle').val(vehicle);
   $('#complaint_type').val({{$complaint_type}});
});

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