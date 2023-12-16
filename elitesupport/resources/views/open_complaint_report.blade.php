@extends("layouts.masterlayout")
@section('title','Open Complaint Report')
@section('bodycontent')
<div class="content-wrapper mobcss">	
	<div class="card">	            
	    <div class="card-body">
			<h4 class="card-title">Open Complaint Report</h4>
	        <div class="clear"></div>			
            <hr>
			
			<form name="myForm" method="post" enctype="multipart/form-data" action="{{url('store-open-complaint-report')}}" onsubmit="return consolidatedReportValidate()">
	            <input type="hidden" name="_token" value="{{csrf_token()}}">
				<input type="hidden" name="productId" id="productId" value="@isset($product){{$product}} @endisset">
				<input type="hidden" name="segmentId" id="segmentId" value="@isset($segment){{$segment}} @endisset">
				<input type="hidden" name="zoneId" id="zoneId" value="@isset($zone){{$zone}} @endisset">
				<input type="hidden" name="DealerId" id="DealerId" value="@isset($Dealer){{$Dealer}} @endisset">
				<input type="hidden" name="StatusId" id="StatusId" value="@isset($Status){{$Status}} @endisset">
				           
	            <div class="row">	                    	
                 	<div class="form-group col-md-3">
                        <label for="datefrom" >Date From</label>
						<span style="color: red;">*</span>
						<input type="text" name="datefrom" id="datefrom1" autocomplete="off" class="form-control" value="@isset($datefrom){{$datefrom}} @endisset" />
                        <span id="datefrom_error" style="color:red"></span> 
                    </div>
                    <div class="form-group col-md-3">
                        <label for="dateto" >Date To</label>
						<span style="color: red;">*</span>
						<input type="text" name="dateto" id="dateto1" autocomplete="off" class="form-control" value="@isset($dateto){{$dateto}} @endisset" />
                        <span id="dateto_error" style="color:red"></span> 
                    </div>
								
					<div class="form-group col-md-3">
						<label for="Brand" >Brand</label>
						<span style="color: red;">*</span>								
						<select name="brand[]" multiple id="brand" class="form-control">
							
							@foreach($brandData as $row)
								@if(isset($brand))
									@php $brndarr = explode(',',$brand);@endphp	
									@if(in_array($row->id, $brndarr))	
										<option value="{{$row->id}}" selected>{{$row->brand}} </option>
									@else
										<option value="{{$row->id}}">{{$row->brand}} </option>
									@endif
								@endif
								@if(!isset($brand))
									<option value="{{$row->id}}" >{{$row->brand}}</option>
								@endif
							@endforeach
							
						</select>
						<span id="brand_error" style="color:red"></span>
					</div>
					<div class="form-group col-md-3" id="td_product">
						<label for="product">Product</label>
						<span style="color: red;">*</span>
						<select name="product[]" multiple id="product" class="form-control" onchange="UserProductChanged(this.value,'')">
							
							@isset($vehicleData)
								@foreach($vehicleData as $proRow)									
									@if(isset($product))
										@php $productarr = explode(',',$product);@endphp
										@if(in_array($proRow->id, $productarr))
											<option value="{{$proRow->id}}" selected>{{$proRow->vehicle}} </option>
										@else
											<option value="{{$proRow->id}}">{{$proRow->vehicle}} </option>
										@endif
									@endif
									@if(!isset($product))
										<option value="{{$proRow->id}}" >{{$proRow->vehicle}}</option>
									@endif
								@endforeach
							@endisset
						</select>
						<span id="product_error" style="color:red"></span>
					</div>
					<div class="form-group col-md-3" id="td_segment">
						<label for="Name">Product Segment</label>
						<span style="color: red;">*</span>
						<select name="segment[]" multiple id="segment" class="form-control">
							
						</select>
						<span id="segment_error" style="color:red"></span>
					</div>
                    <div class="form-group col-md-3">
                        <label for="Zone" >Region</label>
						<span style="color: red;">*</span>
						<select name="zone[]" id="zone1" multiple class="form-control" onchange="getDealerByZoneId(this.value,'');">
							
							@isset($zoneData)
								@foreach($zoneData as $Data)									
									@if(isset($zone))
									@php $zonearr=array();$zonearr = explode(',',$zone); @endphp
										@if(in_array($Data->id, $zonearr))
											<option value="{{$Data->id}}" selected>{{$Data->region}} </option>
										@else
											<option value="{{$Data->id}}">{{$Data->region}} </option>
										@endif
									@endif
									@if(!isset($zone))
										<option value="{{$Data->id}}" >{{$Data->region}}</option>
									@endif
								@endforeach
							@endisset
						</select>
                        <span id="zone_error" style="color:red"></span> 
                    </div> 
                    <div class="form-group col-md-3">
                        <label for="Dealer" >Dealer</label>
						<span style="color: red;">*</span>
						<select name="Dealer[]" multiple id="Dealer" class="form-control"></select>
                        <span id="Dealer_error" style="color:red"></span> 
                    </div>
                    <div class="form-group col-md-3">
						<label for="Status" >Status</label>
						<span style="color: red;">*</span>
						<select name="Status[]" multiple id="Status" class="form-control">
							
							@if(isset($Status))							
								@php $Statusarr=array();$Statusarr = explode(',',$Status); 	@endphp
								@if(in_array("'Open'", $Statusarr))
									<option value="Open" selected>Open</option>
								@endif
								@if(in_array("'Completed'", $Statusarr))
									<option value="Completed" selected>Completed</option>
								@endif
								@if(in_array("'Closed'", $Statusarr))
									<option value="Closed" selected>Closed</option>
								@endif
							@endif
							@if(!isset($Status))
							<option value="Open" >Open</option>
							<!--<option value="Completed">Completed</option>
							<option value="Closed">Closed</option>-->
							@endif
						</select>
						<span id="Status_error" style="color:red"></span> 
                    </div>
                </div>
            	<div class="clear"></div>
                <hr> 
                <div class="row">
                	 <div class="form-group col-md-3">
                        <input type="submit"name="submit" id="submit" value="Submit" class="btn-secondary">
                    </div>
                </div>
            </form>   
			<div class="clear"></div>
            <hr>                       
            <div class="table-responsive">
				<table id="order-listing" class="table" border="1">
                    <thead>
                        <tr style="background-color: ##d3d6d2;">                                    	
							<th >Case #</th>
							<th>Case Type</th> 
							<th>Customer Org</th>                                       
							<th>Contact person</th>                                       
							<th>Phone number</th>
							<th>E-mail</th>
							<th>Mode of complaint</th>
							<th>Complaint Category</th>
							<th>Sub category</th>
							<th>Brand</th>
							<th>Product</th>
							<th>Segment</th>
							<th>Region</th>
							<th>Dealer</th>
							<th>Location</th>
							<th>Assigned To</th>
							<th>Case Description</th>
							<th style="display: none;">Full Case Description</th>
							<th>Observation / Findings</th>
							<th style="display: none;">Full Observation / Findings</th>
							<th>Actions taken</th>
							<th style="display: none;">Full Actions taken</th>
							<th>Status</th>
							<th>Date of complaint registration</th>
							<th>Date of acknowledgement</th>
							
							<th>Acknowledgement TAT</th>
							
							<th>Open days (Ageing)</th>
							<th>Acknowledged on time?</th>
							<th>Open beyond SLA?</th>
							<th>Vehicle Model</th>
							<th>Chassis Number</th>
							<th>Vehicle Registration</th>
                        </tr>
                    </thead>
                    <tbody>
						@isset($openComplaintReport)						
							@foreach ($openComplaintReport as $row)
								@php $actionTaken =explode(',',$row->action_comment);$length=20; @endphp
						
								<tr style="background-color: #d3d6d2;">
									<td>{{$row->complaint_number}}</td>
									<td>{{$row->case_type}}</td>
									<td>{{$row->customerOrg}}</td>
									<td>{{$row->custname}}</td>
									<td>{{$row->mobile1}}</td>
									<td>{{$row->Cust_email}}</td>									
									<td>{{$row->mode_name}}</td>
									<td>{{$row->complaint_type}}</td>
									<td>{{$row->sub_complaint_type}}</td>
									<td>{{$row->brandName}}</td>
									<td>{{$row->vehicle}}</td>
									<td>{{$row->segment}}</td>
									<td>{{$row->region}}</td>
									<td>{{$row->dealer_name}}</td>
									<td>{{$row->city}}</td>
									<td>{{$row->assignTo}}</td>
									@php $description = substr($row->description, 0, $length); @endphp
									<td>{{$description.'...'}}</td>
									<td style="display: none;">{{$row->description}}</td>
									@php $observation = substr($row->observation, 0, $length); @endphp
									<td>{{$observation.'...'}}</td>
									<td style="display:none; ">{{$row->observation}}</td>						
									@php $action_comment = substr($row->action_comment, 0, $length); @endphp
									<td>
									{{$action_comment.'...'}}										
									</td>
									<td style="display: none;">									
										@foreach($actionTaken as $actionRow)										
											{{$actionRow}}<br>										
										@endforeach
									</td>
									<td>{{$row->case_status}}</td>
									<td>{{$row->CaseCreated}}</td>
									<td>{{$row->Acknowledged_date!=''?$row->Acknowledged_date:'NA'}}</td>
									<td>
										@if($row->Acknowledged_date !=null)
											@php																			
												$from = strtotime($row->CaseCreated);
												$today = strtotime($row->Acknowledged_date);
												$difference = $today - $from;
												$acknlgeTat =  ceil($difference / 86400);
											@endphp
											{{$acknlgeTat.' days'}}
										@else
											{{'NA'}}
										@endif
									</td>
																	
									<td>
										
											@php
											$from = strtotime($row->CaseCreated);
											
											$today = time();
											$difference = $today - $from;
											
											$openDays =  ceil($difference / 86400);
											@endphp
											{{$openDays.' days'}}
										
									</td>
									<td>
										@if($row->Acknowledged_date !='')
											@php
											$acknowledgedSLAday='';
											$from = strtotime($row->CaseCreated);
											$today = strtotime($row->Acknowledged_date);
											$difference = $today - $from;
											$days =  ceil($difference / 86400);
											@endphp
											@if($row->acknowledged_SLA > $days)
												{{'Yes'}}
											@else
												{{'No'}}
												
											@endif
										@else
											{{'No'}}
										@endif
									</td>
									
									<td>{{$row->Total_OpenO_2!=''?$row->Total_OpenO_2:'NA'}}</td>
									<td>{{$row->vehcalModel}}</td>
									<td>{{$row->chassis_number}}</td>
									<td>{{$row->vehicle_registration}}</td>
								</tr>
							@endforeach
							
							
						@endisset
                   
                    </tbody>
                </table>
			</div>
			<br>
	    </div>	            
	</div>
<script type="text/javascript">

$(document).ready(function () {	
	var product =$('#productId').val();
	var segment =$('#segmentId').val();
	var zone =$('#zoneId').val();
	var Dealer =$('#DealerId').val();
	if (product !='') {
		UserProductChanged(product,segment);
	}
	if (zone !='') {
		getDealerByZoneId(zone,Dealer);
	}
});

function UserProductChanged(el,ell){
	//alert(ell);
	var myarray= [];
	var favorite = [];
	if(ell!='')
	{
		var zz='';
	}
	else
	{
		$('#product :selected').each(function(i, sel)
		{
			favorite.push($(this).val());
		});
		var zz=favorite.join(",");

	}
	zz = zz !=''?zz:el;

	$.ajax({ url: '{{url("get-multi-product-segment")}}',
		data: { 'product_id':zz},
		success: function(data){
			// alert(data);// 1,Mining~2,C & I~3,On-Road~4,Special App~
			var Result = data.split("~");var str = '';
			Result.pop();
			var custIds = new Array(ell.trim());
			var selectedIds = custIds.join(',').split(',');
			for (item1 in Result) {
				var Result2 = Result[item1].split(",");
				if (ell!='') {
					if (jQuery.inArray( Result2[0], selectedIds ) !== -1 ) {
						str += "<option value='" + Result2[0] + "' selected>" +Result2[1] + "</option>";
					}
					else
					{
						str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
					}
				}
				else
				{
					str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
				}
			}
			document.getElementById('segment').innerHTML = str;
		}
	});
}
	function getDealerByZoneId(el,ell){
		
		var myarray= [];
			var favorite = [];
			if(ell!='')
			{           
			var zz='';
			}
			else
			{
				 $('#zone1 :selected').each(function(i, sel)
	            { 
	    			favorite.push($(this).val());
				});
				var zz=favorite.join(",");
				
			}
			zz = zz !=''?zz:el;	
		$.ajax({ 
			url: '{{url("get-dealer-by-zone-id-report")}}',
			data :{'zone_id':zz},
			success: function(data) {
							
				var Result = data.split(",");
				var str='';
				Result.pop();
				var custIds = new Array(ell.trim());
				var selectedIds = custIds.join(',').split(',');
				for (item1 in Result) {
					var Result2 = Result[item1].split("~");
					if (ell!='') {
						if (jQuery.inArray( Result2[0], selectedIds ) !== -1 ) {
							str += "<option value='" + Result2[0] + "' selected>" +Result2[1] + "</option>";
						} else {
							str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
						}
					} else {
						str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
					}

				}
				document.getElementById('Dealer').innerHTML =str;
			}
		});
	}
	/*$(document).ready(function () {
		alert("DFFE");
		$('#example').DataTable({
			dom: 'Bfrtip',
			buttons: [{
					extend: 'excel',
					text: 'Excel',
					className: 'exportExcel',
					filename: 'Test_Excel',
					exportOptions: { modifier: { page: 'all'} }
				},
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
					className: 'exportExcel',
					filename: 'Test_Pdf',
					exportOptions: { modifier: { page: 'all'} }
				}]
		});

	});*/
</script>
@endsection
