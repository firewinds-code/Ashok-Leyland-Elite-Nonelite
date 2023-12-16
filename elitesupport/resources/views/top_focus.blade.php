@extends("layouts.masterlayout")
@section('title','Top Focus Report')
@section('bodycontent')
<div class="content-wrapper mobcss">	
	<div class="card">	            
	    <div class="card-body">
			<h4 class="card-title">Top Focus Report</h4>
	        <div class="clear"></div>			
            <hr>
			
			<form name="myForm" method="post" enctype="multipart/form-data" action="{{url('store-top-focus-report')}}" onsubmit="return topFocusValidate()">
	            <input type="hidden" name="_token" value="{{csrf_token()}}">
				<input type="hidden" name="productId" id="productId" value="@isset($product){{$product}} @endisset">
				<input type="hidden" name="segmentId" id="segmentId" value="@isset($segment){{$segment}} @endisset">
				<input type="hidden" name="zoneId" id="zoneId" value="@isset($zone){{$zone}} @endisset">
				<input type="hidden" name="DealerId" id="DealerId" value="@isset($Dealer){{$Dealer}} @endisset">
				<input type="hidden" name="StatusId" id="StatusId" value="@isset($Status){{$Status}} @endisset">
				           
	            <div class="row">
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
						<select name="zone[]" id="zone1" multiple class="form-control" onchange="getDealerByZoneIdRep(this.value,'');">
							
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
            <div class="row">
				<div class="col-lg-6">
					<div class="table-responsive">	
						 <table id="order-listing1" class="table">
							<thead>
								<tr>
									<th>Category</th>
									<th style="text-align: center;white-space: nowrap;">@if(!empty($crntfrom))
									@php  
									
									$date=date_create($crntfrom); 
									$m4 =  date_format($date,"m") ;
									$qtr='';
									if($m4 >=4 && $m4 <=6){
										$qtr = '1';
									}else if($m4 >=7 && $m4 <=9){
										$qtr = '2';
									}else if($m4 >=10 && $m4 <=12){
										$qtr = '3';
									}else if($m4 >=1 && $m4 <4){
										$qtr = '4';
									}  
								    if (date_format($date,"m") >= 4) {
								        $financial_year = 'Q'.$qtr.': '.(date_format($date,"y")).'-'.(date_format($date,"y")+1);
								    } else {//On or Before March (FY is previous year - current year)
								        $financial_year = 'Q'.$qtr.': '.(date_format($date,"y")-1).'-'.date_format($date,"y");
								    } @endphp {{$financial_year}} @else {{'Curr. Qtr'}} @endif</th>
									<th style="text-align: center;white-space: nowrap;">@if(!empty($lastfrom))
									@php  
									$date=date_create($lastfrom);
									$m4 =  date_format($date,"m") ;
									$qtr='';
									if($m4 >=4 && $m4 <=6){
										$qtr = '1';
									}else if($m4 >=7 && $m4 <=9){
										$qtr = '2';
									}else if($m4 >=10 && $m4 <=12){
										$qtr = '3';
									}else if($m4 >=1 && $m4 <4){
										$qtr = '4';
									}    
								    if (date_format($date,"m") >= 4) {//On or After April (FY is current year - next year)
								        $financial_year = 'Q'.$qtr.': '.(date_format($date,"y")).'-'.(date_format($date,"y")+1);
								    } else {//On or Before March (FY is previous year - current year)
								        $financial_year = 'Q'.$qtr.': '.(date_format($date,"y")-1).'-'.date_format($date,"y");
								    } @endphp {{$financial_year}} @else {{'Last Qtr'}} @endif</th>
									<th style="text-align: center;white-space: nowrap;">@if(!empty($prevfrom))
									@php  
									$date=date_create($prevfrom); 
									$m4 =  date_format($date,"m") ;
									$qtr='';
									if($m4 >=4 && $m4 <=6){
										$qtr = '1';
									}else if($m4 >=7 && $m4 <=9){
										$qtr = '2';
									}else if($m4 >=10 && $m4 <=12){
										$qtr = '3';
									}else if($m4 >=1 && $m4 <4){
										$qtr = '4';
									}     
								    if (date_format($date,"m") >= 4) {//On or After April (FY is current year - next year)
								        $financial_year = 'Q'.$qtr.': '.(date_format($date,"y")).'-'.(date_format($date,"y")+1);
								    } else {//On or Before March (FY is previous year - current year)
								        $financial_year = 'Q'.$qtr.': '.(date_format($date,"y")-1).'-'.date_format($date,"y");
								    } @endphp {{$financial_year}} @else {{'Prev Qtr'}} @endif</th>
									<th style="text-align: center;white-space: nowrap;">@if(!empty($prevfrom1))
									@php  
									$date=date_create($prevfrom1); 
									$m4 =  date_format($date,"m") ;
									$qtr='';
									if($m4 >=4 && $m4 <=6){
										$qtr = '1';
									}else if($m4 >=7 && $m4 <=9){
										$qtr = '2';
									}else if($m4 >=10 && $m4 <=12){
										$qtr = '3';
									}else if($m4 >=1 && $m4 <4){
										$qtr = '4';
									}    
								    if (date_format($date,"m") >= 4) {//On or After April (FY is current year - next year)
								        $financial_year = 'Q'.$qtr.': '.(date_format($date,"y")).'-'.(date_format($date,"y")+1);
								    } else {//On or Before March (FY is previous year - current year)
								        $financial_year = 'Q'.$qtr.': '.(date_format($date,"y")-1).'-'.date_format($date,"y");
								    } @endphp {{$financial_year}} @else {{'Prev Qtr'}} @endif</th>
								</tr>
							</thead>
							<tbody>
								@isset($topCategories)
									
									@foreach ($topCategories as $row)
										<tr>
											<td class="cls_zone">{{$row->sub_complaint_type}}</td>
											<td class="cls_state" style="text-align: center;">{{$row->current_qtr}}</td>
											<td class="cls_state" style="text-align: center;">{{$row->last_qtr}}</td>
											<td class="cls_state" style="text-align: center;">{{$row->prev1_qtr}}</td>
											<td class="cls_state" style="text-align: center;">{{$row->prev2_qtr}}</td>
											
										</tr>
									@endforeach
								@endisset
							</tbody>
						</table>
						</div>
				</div>
				
				<div class="col-lg-6">
					<div class="table-responsive">	
						 <table id="order-listing1" class="table">
							<thead>
								<tr>
									<th>Customer</th>
									<th style="text-align: center;white-space: nowrap;">@if(!empty($crntfrom))
									@php  
									$date=date_create($crntfrom); 
									$m4 =  date_format($date,"m") ;
									$qtr='';
									if($m4 >=4 && $m4 <=6){
										$qtr = '1';
									}else if($m4 >=7 && $m4 <=9){
										$qtr = '2';
									}else if($m4 >=10 && $m4 <=12){
										$qtr = '3';
									}else if($m4 >=1 && $m4 <4){
										$qtr = '4';
									} 
								    if (date_format($date,"m") >= 4) {
								        $financial_year = 'Q'.$qtr.': '.(date_format($date,"y")).'-'.(date_format($date,"y")+1);
								    } else {
								        $financial_year = 'Q'.$qtr.': '.(date_format($date,"y")-1).'-'.date_format($date,"y");
								    } @endphp {{$financial_year}} @else {{'Curr. Qtr'}} @endif</th>
									<th style="text-align: center;white-space: nowrap;">@if(!empty($lastfrom))
									@php  
									$date1=date_create($lastfrom);
									$m1 =  date_format($date1,"m") ;
									
									$qtr='';
									if($m1 >=4 && $m1 <=6){
										$qtr = '1';
									}else if($m1 >=7 && $m1 <=9){
										$qtr = '2';
									}else if($m1 >=10 && $m1 <=12){
										$qtr = '3';
									}else if($m1 >=1 && $m1 <4){
										$qtr = '4';
									}
										
								    if (date_format($date1,"m") >= 4) {
								        $financial_year = 'Q'.$qtr.': '.'Q'.$qtr.(date_format($date1,"y")).'-'.(date_format($date1,"y")+1);
								    } else {
								        $financial_year = 'Q'.$qtr.': '.(date_format($date1,"y")-1).'-'.date_format($date1,"y");
								    } @endphp {{$financial_year}} @else {{'Last Qtr'}} @endif</th>
									<th style="text-align: center;white-space: nowrap;">@if(!empty($prevfrom))
									@php  
									$date2=date_create($prevfrom);
									
									$m2 =  date_format($date2,"m");
									$qtr='';
									
									if($m2 >=4 && $m2 <=6){
										$qtr = '1';
									}else if($m2 >=7 && $m2 <=9){
										$qtr = '2';
									}else if($m2 >=10 && $m2 <=12){
										$qtr = '3';
									}else if($m2 >=1 && $m2 <4){
										$qtr = '4';
									} 
								    if (date_format($date2,"m") >= 4) {
								        $financial_year = 'Q'.$qtr.': '.(date_format($date2,"y")).'-'.(date_format($date2,"y")+1);
								    } else {
								        $financial_year = 'Q'.$qtr.': '.(date_format($date2,"y")-1).'-'.date_format($date2,"y");
								    } @endphp {{$financial_year}} @else {{'Prev Qtr'}} @endif</th>
									<th style="text-align: center;white-space: nowrap;">@if(!empty($prevfrom1))
									@php  
									$date=date_create($prevfrom1); 
									$m3 =  date_format($date,"m") ;
									$qtr='';
									if($m3 >=4 && $m3 <=6){
										$qtr = '1';
									}else if($m3 >=7 && $m3 <=9){
										$qtr = '2';
									}else if($m3 >=10 && $m3 <=12){
										$qtr = '3';
									}else if($m3 >=1 && $m3 <4){
										$qtr = '4';
									}  
								    if (date_format($date,"m") >= 4) {
								        $financial_year = 'Q'.$qtr.': '.(date_format($date,"y")).'-'.(date_format($date,"y")+1);
								    } else {
								        $financial_year = 'Q'.$qtr.': '.(date_format($date,"y")-1).'-'.date_format($date,"y");
								    } @endphp {{$financial_year}} @else {{'Prev Qtr'}} @endif</th>									
								</tr>
							</thead>
							<tbody>
								@isset($topCustomer)
									
									@foreach ($topCustomer as $row)
										<tr>
											<td class="cls_zone">{{$row->customerOrg}}</td>
											<td class="cls_state" style="text-align: center;">{{$row->current_qtr}}</td>
											<td class="cls_state" style="text-align: center;">{{$row->last_qtr}}</td>
											<td class="cls_state" style="text-align: center;">{{$row->prev1_qtr}}</td>
											<td class="cls_state" style="text-align: center;">{{$row->prev2_qtr}}</td>
										</tr>
									@endforeach
									
								@endisset
							</tbody>
						</table>
						</div>
				</div>
			</div>
           
			</br>
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
		getDealerByZoneIdRep(zone,Dealer);
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
	
	function getDealerByZoneIdRep(el,ell){

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
							str += "<option value='" + Result2[0] + "' selected>" + Result2[1] + "</option>";
						} else {
							str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
						}

					} else {
						str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
					}

				}
				document.getElementById('Dealer').innerHTML = str;				
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
