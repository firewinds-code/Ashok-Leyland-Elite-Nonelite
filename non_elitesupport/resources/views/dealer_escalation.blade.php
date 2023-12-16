@extends("layouts.masterlayout")
@section('title','Manage Dealer Escalation')
@section('bodycontent')
	<div class="content-wrapper mobcss">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Manage Dealer Escalation</h4>
                <div class="row">
                    <div class="col-md-12">
                    	<div id="insertDealer" >
						<form name="myForm" method="post" enctype="multipart/form-data" action="{{url('store-dealer-escalation')}}" onsubmit="return escalationFormValidate()">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="hidden" name="DataID" id="DataID">
                            <div class="row"> 
                            <div class="form-group col-md-3">
                                <label for="matrix_identifier">Matrix Identifier</label>
                                <span style="color:red">*</span>
                                <input type="text" name="matrix_identifier" id="matrix_identifier" placeholder="Matrix Identifier" class="form-control">
                                <span id="matrix_identifier_error" style="color:red"></span> 
                            </div>
							<div class="form-group col-md-3">
	                            <label for="complaint_type" >Complaint Type</label>  <span style="color:red">*</span>
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
                                <label for="sub_complaint_type">Sub Complaint Type</label>  <span style="color:red">*</span>
                                <select name="sub_complaint_type[]" multiple id="sub_complaint_type" class="form-control">
                                	<optgroup><option value="NA">--Select--</option></optgroup>
                                </select>
                                <span id="sub_complaint_type_error" style="color:red"></span> 
                            </div>
                        <div class="form-group col-md-3">
                            <label for="vehicle" >Product</label>  <span style="color:red">*</span>
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
                            <label for="Name">Segment</label>  <span style="color:red">*</span>
                            <select name="segment[]" multiple id="segment" class="form-control">
								<optgroup><option value="NA">--Select--</option></optgroup>
							</select>
                            <span id="segment_error" style="color:red"></span> 
                        </div> 
						                    
                        <div class="form-group col-md-3">
                            <label for="escalation_stage">Escalation Stage</label>  <span style="color:red">*</span>
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
                            <label for="day">Days</label>  <span style="color:red">*</span>
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
                            <label for="escalated_to">Escalation To</label>  <span style="color:red">*</span>
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
                            <label for="cc_to">CC To</label>  <span style="color:red">*</span>
                            <select name="cc_to[]" multiple id="cc_to" class="form-control" style="height: 138px;">
                            	<optgroup>
                            		<option value="NA">--Select--</option>
                                	@isset($cc_role_details)
                                		@foreach($cc_role_details as $row)
                                			<option value="{{$row->id}}">{{$row->role}}</option>
                                		@endforeach
                                	@endisset
                            	</optgroup>
                            </select>
                            <span id="cc_to_error" style="color:red"></span> 
                        </div>								
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
				                    	<th style="display: none;">complaint_ID</th>	
				                    	<th style="display: none;">Sub complaint_ID</th>	
				                    	<th style="display: none;">vehicle_ID</th>                    	
				                    	<th style="display: none;">Escalated_To_ID</th>	
				                    	<th style="display: none;">Cc_To_ID</th>                    	
				                    	<th style="display: none;">Segment</th>                    	
										<th >#</th>
										<th >Matrix Identifier</th>
										<th>Complaint Type</th> 
										<!--<th>Sub Complaint Type</th>--> 
										<th>Product</th>						                                      
										<!--<th>Segment</th>-->
				                    </tr>
				                </thead>
				                <tbody>
				                @isset($rowData)
				              
				                @php $count=1; @endphp							
									@foreach($rowData as $row)					
				                    <tr>
				                    @php $escIndividual = $row->Escalation_ID; @endphp
					                        <td class="cls_complaint_ID" style="display: none;">{{$row->complaint_type_ID}}</td>
					                        <td class="cls_sub_complaint_type" style="display: none;">{{$row->sub_complaint_type_ID}}</td>
					                        <td class="cls_vehicle_ID" style="display: none;">{{$row->vehicle_ID}}</td>
					                        <td class="cls_Escalated_To_ID" style="display: none;">{{$row->escalated_to_ID}}</td> 
					                        <td class="cls_Cc_To_ID" style="display: none;">{{$row->cc_to_ID}}</td>
					                        <td class="cls_segment" style="display: none;">{{$row->Segment_ID}}</td>	                        
					                         
					                         @php $ids = $row->matrix_identifier.'~'.$row->complaint_type_ID.'~'.$row->sub_complaint_type_ID.'~'.$row->vehicle_ID.'~'.$row->Segment_ID;  @endphp                                   
					                        <td class="cls_id">{{$count}}</td>                                 
					                        <td><a href="{{route('dealer-escalation-individual.dealerEscalationIndividual',['ids' =>$ids ])}}" >{{$row->matrix_identifier}}</a> </td>
					                        <td><a href="{{route('dealer-escalation-individual.dealerEscalationIndividual',['ids' =>$ids ])}}" >{{$row->complaint_type}}</a> </td>
					                        <!--{{--<td><a href="{{route('dealer-escalation-individual.dealerEscalationIndividual',['ids' =>$ids ])}}" >{{$row->Sub_complaint_type}}</a></td>--}}-->
					                        <td><a href="{{route('dealer-escalation-individual.dealerEscalationIndividual',['ids' =>$ids ])}}" >{{$row->vehicle}}</a></td>
					                        <!--{{--<td><a href="{{route('dealer-escalation-individual.dealerEscalationIndividual',['ids' =>$ids ])}}" >{{$row->Segment}}</a></td>--}}-->	                       
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