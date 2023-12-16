@extends("layouts.masterlayout")
@section('title','Closed Complaint')
@section('bodycontent')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"> </script>
<script type="text/javascript" src="{{asset('js/google-chart.js')}}"> </script>
<script type="text/javascript">
function ajaxDealerSearch(complaintIds,productIds,segmentIds,regionIds,dateValues,stringValue,dealerIds){	
	
	if(dateValues!=''){
			var date =dateValues.split("~");
			var fromdate = date[0];
			var todate = date[1];
		}	
		@php
			$complaint_type = Session::get('complaint_type_id');
			$complaint_type_id = Session::get('complaint_type_id');
			$role = Auth::user()->role;
			$user_type_id = Session::get('user_type_id');
			$product = Session::get('product');
			$complaint_type_idArr = explode(',',$complaint_type_id);
			
			
		@endphp
		var role = '{{$role}}';
		var user_type_id = '{{$user_type_id}}';
		var product = '{{$product}}';
		var complaint_type =product_type= '';
		var sales = '';
		var service = '';
		var parts = '';
		var product = '';
		if(role == '29' || role == '30' || user_type_id =='2'){
			complaint_type='1,2,3,4';
			 product_type ='1,2';
			 sales = '1';
			 service = '2';
			 parts = '3';
			 product = '4';
		}else{	
					
			complaint_type = '{{$complaint_type}}';
			product_type = '{{$product}}';
			@php
			$sales=$service=$parts=$product='';	
			if(in_array('1',$complaint_type_idArr)){
				$sales='1';
			}else{
				$sales = '0';				
			}
			if(in_array('2',$complaint_type_idArr)){
				$service='2';
			}else{
				$service = '0';				
			}
			if(in_array('3',$complaint_type_idArr)){
				$parts='3';
			}else{
				$parts = '0';				
			}
			if(in_array('4',$complaint_type_idArr)){
				$product='4';
			}else{
				$product = '0';				
			}
			@endphp
			 sales = '{{$sales}}';
			 service = '{{$service}}';
			 parts = '{{$parts}}';
			 product = '{{$product}}';
		}
		
		$.ajax({  url: '{{url("ajax-tat-search")}}',
			data: { 'sales':sales,'service':service,'parts':parts,'product':product,'fromdate':fromdate,'todate':todate,'productIdnew':product_type,'segmentValue':segmentIds,'regionIdnew':regionIds,'dealerIds':dealerIds},
			beforeSend: function() {
				$('#ajaxLoader').show();
			},
			success: function(data) {
				$('#ajaxLoader').hide();
				var result = data.split(',');
				var sales_closed_count = result[0];
				var sales_sla = Math.round((result[1]/result[0])*100);
				
				
				if (sales_sla >=90 && sales_sla <=100){
					sales_sla ='<span><div style="background:#78b833;color:#fff;margin: 0 90px;">'+sales_sla+'%</div></span>';
					//$("#sales_sla").css({"background":"#78b833", "color":"#fff"});
				}else if(sales_sla >=70 && sales_sla <90){
					sales_sla ='<span><div style="background:#f7d302;color:#000;margin: 0 90px;">'+sales_sla+'%</div></span>';
					//$("#sales_sla").css({"background":"#f7d302", "color":"#000"});
				}
				else if(sales_sla>0 && sales_sla <70){
					sales_sla ='<span><div style="background:#c4001a;color:#fff;margin: 0 90px;">'+sales_sla+'%</div></span>';
					//$("#sales_sla").css({"background":"#c4001a", "color":"#fff"});
				}
				else{
					sales_sla ='-';
					//$("#sales_sla").css({"background":"#c4001a", "color":"#fff"});
				}
				var sales_average_TAT = Math.round(result[2]);
				sales_average_TAT = sales_average_TAT !=''?sales_average_TAT:'-';
				var service_closed_count = result[3];
				
				var service_sla = Math.round((result[4]/result[3])*100);
				if (service_sla >=90 && service_sla <=100){
					service_sla ='<span><div style="background:#78b833;color:#fff;margin: 0 90px;">'+service_sla+'%</div></span>';
					//$("#service_sla").css({"background":"#78b833", "color":"#fff"});
				}else if(service_sla >=70 && service_sla <90){
					service_sla ='<span><div style="background:#f7d302;color:#000;margin: 0 90px;">'+service_sla+'%</div></span>';
					//$("#service_sla").css({"background":"#f7d302", "color":"#000"});
				}
				else if(service_sla>0 && service_sla <70){
					service_sla ='<span><div style="background:#c4001a;color:#fff;margin: 0 90px;">'+service_sla+'%</div></span>';
					//$("#service_sla").css({"background":"#c4001a", "color":"#fff"});
				}else{
					service_sla ='-';
					//$("#service_sla").css({"background":"#c4001a", "color":"#fff"});
				}
				var service_average_TAT =Math.round(result[5]);
				var service_average_TAT = service_average_TAT !=''?service_average_TAT:'-';
				var parts_closed_count = result[6];
				var parts_sla = Math.round((result[7]/result[6])*100);
				if (parts_sla >=90 && parts_sla <=100){
					parts_sla ='<span><div style="background:#78b833;color:#fff;margin: 0 90px;">'+parts_sla+'%</div></span>';
					//$("#parts_sla").css({"background":"#78b833", "color":"#fff"});
				}else if(parts_sla >=70 && parts_sla <90){
					parts_sla ='<span><div style="background:#f7d302;color:#000;margin: 0 90px;">'+parts_sla+'%</div></span>';
					//$("#parts_sla").css({"background":"#f7d302", "color":"#000"});
				}
				else if(parts_sla>0 && parts_sla <70){
					parts_sla ='<span><div style="background:#c4001a;color:#fff;margin: 0 90px;">'+parts_sla+'%</div></span>';
					//$("#parts_sla").css({"background":"#c4001a", "color":"#fff"});
				}else{
					parts_sla ='-';
					//$("#parts_sla").css({"background":"#c4001a", "color":"#fff"});
				}
				var parts_average_TAT = Math.round(result[8]);
				parts_average_TAT = parts_average_TAT !=''?parts_average_TAT:'-';
				var product_closed_count = result[9];
				var product_sla = Math.round((result[10]/result[9])*100);
				if (product_sla >=90 && product_sla <=100){
					product_sla ='<span><div style="background:#78b833;color:#fff;margin: 0 90px;">'+product_sla+'%</div></span>';
					//$("#product_sla").css({"background":"#78b833", "color":"#fff"});
				}else if(product_sla >=70 && product_sla <90){
					product_sla ='<span><div style="background:#f7d302;color:#000;margin: 0 90px;">'+product_sla+'%</div></span>';
					//$("#product_sla").css({"background":"#f7d302", "color":"#000"});
				}
				else if(product_sla>0 && product_sla <70){
					product_sla ='<span><div style="background:#c4001a;color:#fff;margin: 0 90px;">'+product_sla+'%</div></span>';
					//$("#product_sla").css({"background":"#c4001a", "color":"#fff"});
				}else{
					product_sla ='-';
					//$("#product_sla").css({"background":"#c4001a", "color":"#fff"});
				}
				var product_average_TAT = result[11];
				product_average_TAT = product_average_TAT !=''?parseFloat(product_average_TAT).toFixed(1):'0.0';
				$('#sales_closed_count').html(sales_closed_count);
				$('#sales_sla').html(sales_sla);
				$('#sales_average_TAT').html(sales_average_TAT);
				$('#service_closed_count').html(service_closed_count);
				$('#service_sla').html(service_sla);
				$('#service_average_TAT').html(service_average_TAT);
				$('#parts_closed_count').html(parts_closed_count);
				$('#parts_sla').html(parts_sla);
				$('#parts_average_TAT').html(parts_average_TAT);
				$('#product_closed_count').html(product_closed_count);
				$('#product_sla').html(product_sla);
				$('#product_average_TAT').html(product_average_TAT);
			}
		});
		
		$.ajax({  url: '{{url("ajax-postsurvey-search")}}',
			data: {'complaint_type':complaint_type,'productIdnew':product_type,'segmentValue':segmentIds,'regionIdnew':regionIds,'fromdate':fromdate,'todate':todate,'dealerIds':dealerIds},
			beforeSend: function() {
				$('#ajaxLoader').show();
			},
			success: function(data) {
				$('#ajaxLoader').hide();
				var result = data.split('~');
				var postValue = '';
				if(result[0]>=70){
					postValue ='<span style="color:green;font-size: 108px;">'+result[0]+'<span style="font-size: 45px;">%</span></span><br><span><star style="font-size:24px">*</star> based on '+result[3]+' customer feedback(s), out of '+result[1]+' contacted.</span>';
				}else if(result[0]>=65 && result[0]<=69){
					postValue ='<span style="color:orange;font-size: 108px;">'+result[0]+'<span style="font-size: 45px;">%</span></span><br><span><star style="font-size:24px">*</star> based on '+result[3]+' customer feedback(s),  of '+result[1]+' contacted.</span>';
				}else if(result[0]<=64){
					postValue ='<span style="color:red;font-size: 108px;">'+result[0]+'<span style="font-size: 45px;">%</span></span><br><span><star style="font-size:24px">*</star> based on '+result[3]+' customer feedback(s), out of '+result[1]+' contacted.</span>';
				}
				$('#post_complaint_survey').html(postValue);
				
				
			}
		});
		
		$.ajax({  url: '{{url("ajax-topCategory-search")}}',
		data: {'complaint_type':complaint_type,'productIdnew':product_type,'segmentValue':segmentIds,'regionIdnew':regionIds,'fromdate':fromdate,'todate':todate,'dealerIds':dealerIds},
			beforeSend: function() {
				$('#ajaxLoader').show();
			},
			success: function(data) {
				$('#ajaxLoader').hide();
				$('#topCategorytabledata').html(data.html);						
			}
		});
		$.ajax({  url: '{{url("ajax-topcustomer-search")}}',
		data: {'complaint_type':complaint_type,'productIdnew':product_type,'segmentValue':segmentIds,'regionIdnew':regionIds,'fromdate':fromdate,'todate':todate,'dealerIds':dealerIds},
			beforeSend: function() {
				$('#ajaxLoader').show();
			},
			success: function(data) {
				$('#ajaxLoader').hide();
				$('#topCustomertabledata').html(data.html);						
			}
		});
	
}
function activeClass(cat,dateValue){
	$('#dateValue').val(dateValue);
	if(cat =='quater'){
		$('#quater').addClass('activeclass');
	}else{
		$('#quater').removeClass('activeclass');
	}
	if(cat =='half'){
		$('#half').addClass('activeclass');
	}else{
		$('#half').removeClass('activeclass');
	}
	if(cat =='one'){
		$('#oneyear').addClass('activeclass');
	}else{
		$('#oneyear').removeClass('activeclass');
	}
	if(cat =='two'){
		$('#twoyear').addClass('activeclass');
	}else{
		$('#twoyear').removeClass('activeclass');
	}
}
function activeClasstat(cat){	
	if(cat =='qtrtat'){
	
		$('#qtrtat').addClass('activeclass');
	}else{
		$('#qtrtat').removeClass('activeclass');
	}
	if(cat =='halftat'){
		
		$('#halftat').addClass('activeclass');
	}else{
		$('#halftat').removeClass('activeclass');
	}
	if(cat =='yeartat'){
		
		$('#yeartat').addClass('activeclass');
	}else{
		$('#yeartat').removeClass('activeclass');
	}
}
function activeClasssurvey(cat){	
	if(cat =='qtrsurvey'){
		
		$('#qtrsurvey').addClass('activeclass');
	}else{
		$('#qtrsurvey').removeClass('activeclass');
	}
	if(cat =='halfsurvey'){
		$('#halfsurvey').addClass('activeclass');
	}else{
		$('#halfsurvey').removeClass('activeclass');
	}
	if(cat =='yearsurvey'){
		$('#yearsurvey').addClass('activeclass');
	}else{
		$('#yearsurvey').removeClass('activeclass');
	}
}
function activeClasstcat(cat){	
	if(cat =='qtrtcat'){
	
		$('#qtrtcat').addClass('activeclass');
	}else{
		$('#qtrtcat').removeClass('activeclass');
	}
	if(cat =='halftcat'){
		$('#halftcat').addClass('activeclass');
	}else{
		$('#halftcat').removeClass('activeclass');
	}
	if(cat =='yeartcat'){
		$('#yeartcat').addClass('activeclass');
	}else{
		$('#yeartcat').removeClass('activeclass');
	}
}
function activeClasstcus(cat){		
	if(cat =='qtrtcus'){
		
		$('#qtrtcus').addClass('activeclass');
	}else{
		$('#qtrtcus').removeClass('activeclass');
	}
	if(cat =='halftcus'){
		$('#halftcus').addClass('activeclass');
	}else{
		$('#halftcus').removeClass('activeclass');
	}
	if(cat =='yeartcus'){
		$('#yeartcus').addClass('activeclass');
	}else{
		$('#yeartcus').removeClass('activeclass');
	}
	
}


$(document).ready(function() {
		@php
			$complaint_type = Session::get('complaint_type_id');
			$complaint_type_id = Session::get('complaint_type_id');
			$role = Auth::user()->role;
			$user_type_id = Session::get('user_type_id');
			$product = Session::get('product');
			$complaint_type_idArr = explode(',',$complaint_type_id);
			$product_idArr = explode(',',$product);
			
			
		@endphp
		var role = '{{$role}}';
		var user_type_id = '{{$user_type_id}}';
		var product = '{{$product}}';
		var complaint_type =product_type= '';
		var sales = '';
		var service = '';
		var parts = '';
		var product = '';
		if(role == '29' || role == '30' || user_type_id =='2'){
			complaint_type='1,2,3,4';
			product_type ='1,2';
			sales = '1';
			service = '2';
			parts = '3';
			product = '4';
			$('#complaint_type1').prop("checked", true);
			$('#complaint_type2').prop('checked', true);
			$('#complaint_type3').prop('checked', true);
			$('#complaint_type4').prop('checked', true);
			$('#productTruck').prop('checked', true);
			$('#productBus').prop('checked', true);
			$('#segmentTruckdatatable').show();
			$('#segmentBusdatatable').show();
		}else{				
			complaint_type = '{{$complaint_type_id}}';
			product_type = '{{$product}}';
			@php
			$sales=$service=$parts=$product='';	
			if(in_array('1',$complaint_type_idArr)){
				$sales='1';
			}else{
				$sales = '0';				
			}
			if(in_array('2',$complaint_type_idArr)){
				$service='2';
			}else{
				$service = '0';				
			}
			if(in_array('3',$complaint_type_idArr)){
				$parts='3';
			}else{
				$parts = '0';				
			}
			if(in_array('4',$complaint_type_idArr)){
				$product='4';
			}else{
				$product = '0';				
			}
			if(in_array('1',$product_idArr)){
				$truckBox='1';
			}else{
				$truckBox = '';				
			}
			if(in_array('2',$product_idArr)){
				$busBox='2';
			}else{
				$busBox = '';				
			}
			@endphp
			sales = '{{$sales}}';
			service = '{{$service}}';
			parts = '{{$parts}}';
			product = '{{$product}}';
			var salesIdChecked ='{{$sales}}';
			var serviceIdChecked ='{{$service}}';
			var partsIdChecked ='{{$parts}}';
			var productIdChecked ='{{$product}}';
			var truckBox ='{{$truckBox}}';
			var busBox ='{{$busBox}}';
			
			if(salesIdChecked !='0'){
				$('#complaint_type1').prop("checked", true);
				$("#complaint_type1").prop("disabled", false);
				$("#complaint_type1").val(salesIdChecked);
			}else{
				$('#complaint_type1').prop("checked", false);
				$("#complaint_type1").prop("disabled", true);
			}
			if(serviceIdChecked !='0'){
				$('#complaint_type2').prop("checked", true);
				$("#complaint_type2").prop("disabled", false);
				$("#complaint_type2").val(serviceIdChecked);
			}else{
				$('#complaint_type2').prop("checked", false);
				$("#complaint_type2").prop("disabled", true);
			}
			if(partsIdChecked !='0'){
				$('#complaint_type3').prop("checked", true);
				$("#complaint_type3").prop("disabled", false);
				$("#complaint_type3").val(partsIdChecked);
			}else{
				$('#complaint_type3').prop("checked", false);
				$("#complaint_type3").prop("disabled", true);
			}
			if(productIdChecked !='0'){
				$('#complaint_type4').prop("checked", true);
				$("#complaint_type4").prop("disabled", false);
			}else{
				$('#complaint_type4').prop("checked", false);
				$("#complaint_type4").prop("disabled", true);
			}
			if(truckBox !=''){
				$('#productTruck').prop("checked", true);
				$("#productTruck").prop("disabled", false);
				$('#segmentTruckdatatable').show();
				
			}else{
				$('#productTruck').prop("checked", false);
				$("#productTruck").prop("disabled", true);
				$('#segmentTruckdatatable').hide();
			}
			if(busBox !=''){
				$('#productBus').prop("checked", true);
				$("#productBus").prop("disabled", false);
				$('#segmentBusdatatable').show();
			}else{
				$('#productBus').prop("checked", false);
				$("#productBus").prop("disabled", true);
				$('#segmentBusdatatable').hide();
			}
		}
		
		$('#segment_truck1').prop('checked', true);
		$('#segment_truck2').prop('checked', true);
		$('#segment_truck3').prop('checked', true);
		$('#segment_truck8').prop('checked', true);
		$('#segment_truck9').prop('checked', true);
		
		
		$('#segment_bus5').prop('checked', true);
		$('#segment_bus6').prop('checked', true);
		$('#segment_bus7').prop('checked', true);
		$('#segment_bus10').prop('checked', true);
		$('#segment_bus11').prop('checked', true);
		
		$('#region1').prop('checked', true);
		$('#region2').prop('checked', true);
		$('#region3').prop('checked', true);
		$('#region4').prop('checked', true);
		$('#region5').prop('checked', true);
		
		
		
		var region_type='1,2,3,4,5';
		
		var segment_type='1,2,3,8,9,5,6,7,10,11';
		$('#complaintValue').val(complaint_type);
		$('#productValue').val(product_type);
		$('#segmentValue').val(segment_type);
		$('#regionValue').val(region_type);
		
		
		@php			
			$current_month = date('m');
			$current_quarter_start = ceil($current_month/4)*3+1; // get the starting month of the current quarter
			$fromDate = date("Y-m-d", mktime(0, 0, 0, $current_quarter_start, 1, date('Y') ));
			$toDate = date('Y-m-d', strtotime($fromDate. ' - 3 month'));
			$currentQuarterDate = $toDate.'~'.$fromDate;
		@endphp
		activeClass('quater','{{$currentQuarterDate}}');
		var fromdate = '{{$toDate}}';
		var todate = '{{$fromDate}}';	
		
		$.ajax({  url: '{{url("ajax-tat-search")}}',
			data: { 'sales':sales,'service':service,'parts':parts,'product':product,'fromdate':fromdate,'todate':todate,'productIdnew':product_type,'segmentValue':segment_type,'regionIdnew':region_type},
			success: function(data) {
							
				var result = data.split(',');
				var sales_closed_count = result[0];
				var sales_sla = Math.round((result[1]/result[0])*100);
				
				
				if (sales_sla >=90 && sales_sla <=100){
					sales_sla ='<span><div style="background:#78b833;color:#fff;margin: 0 90px;">'+sales_sla+'%</div></span>';
					//$("#sales_sla").css({"background":"#78b833", "color":"#fff"});
				}else if(sales_sla >=70 && sales_sla <90){
					sales_sla ='<span><div style="background:#f7d302;color:#000;margin: 0 90px;">'+sales_sla+'%</div></span>';
					//$("#sales_sla").css({"background":"#f7d302", "color":"#000"});
				}
				else if(sales_sla>0 && sales_sla <70){
					sales_sla ='<span><div style="background:#c4001a;color:#fff;margin: 0 90px;">'+sales_sla+'%</div></span>';
					//$("#sales_sla").css({"background":"#c4001a", "color":"#fff"});
				}
				else{
					sales_sla ='-';
					//$("#sales_sla").css({"background":"#c4001a", "color":"#fff"});
				}
				var sales_average_TAT = Math.round(result[2]);
				sales_average_TAT = sales_average_TAT !=''?sales_average_TAT:'-';
				var service_closed_count = result[3];
				
				var service_sla = Math.round((result[4]/result[3])*100);
				if (service_sla >=90 && service_sla <=100){
					service_sla ='<span><div style="background:#78b833;color:#fff;margin: 0 90px;">'+service_sla+'%</div></span>';
					//$("#service_sla").css({"background":"#78b833", "color":"#fff"});
				}else if(service_sla >=70 && service_sla <90){
					service_sla ='<span><div style="background:#f7d302;color:#000;margin: 0 90px;">'+service_sla+'%</div></span>';
					//$("#service_sla").css({"background":"#f7d302", "color":"#000"});
				}
				else if(service_sla>0 && service_sla <70){
					service_sla ='<span><div style="background:#c4001a;color:#fff;margin: 0 90px;">'+service_sla+'%</div></span>';
					//$("#service_sla").css({"background":"#c4001a", "color":"#fff"});
				}else{
					service_sla ='-';
					//$("#service_sla").css({"background":"#c4001a", "color":"#fff"});
				}
				var service_average_TAT =Math.round(result[5]);
				var service_average_TAT = service_average_TAT !=''?service_average_TAT:'-';
				var parts_closed_count = result[6];
				var parts_sla = Math.round((result[7]/result[6])*100);
				if (parts_sla >=90 && parts_sla <=100){
					parts_sla ='<span><div style="background:#78b833;color:#fff;margin: 0 90px;">'+parts_sla+'%</div></span>';
					//$("#parts_sla").css({"background":"#78b833", "color":"#fff"});
				}else if(parts_sla >=70 && parts_sla <90){
					parts_sla ='<span><div style="background:#f7d302;color:#000;margin: 0 90px;">'+parts_sla+'%</div></span>';
					//$("#parts_sla").css({"background":"#f7d302", "color":"#000"});
				}
				else if(parts_sla>0 && parts_sla <70){
					parts_sla ='<span><div style="background:#c4001a;color:#fff;margin: 0 90px;">'+parts_sla+'%</div></span>';
					//$("#parts_sla").css({"background":"#c4001a", "color":"#fff"});
				}else{
					parts_sla ='-';
					//$("#parts_sla").css({"background":"#c4001a", "color":"#fff"});
				}
				var parts_average_TAT = Math.round(result[8]);
				parts_average_TAT = parts_average_TAT !=''?parts_average_TAT:'-';
				var product_closed_count = result[9];
				var product_sla = Math.round((result[10]/result[9])*100);
				if (product_sla >=90 && product_sla <=100){
					product_sla ='<span><div style="background:#78b833;color:#fff;margin: 0 90px;">'+product_sla+'%</div></span>';
					//$("#product_sla").css({"background":"#78b833", "color":"#fff"});
				}else if(product_sla >=70 && product_sla <90){
					product_sla ='<span><div style="background:#f7d302;color:#000;margin: 0 90px;">'+product_sla+'%</div></span>';
					//$("#product_sla").css({"background":"#f7d302", "color":"#000"});
				}
				else if(product_sla>0 && product_sla <70){
					product_sla ='<span><div style="background:#c4001a;color:#fff;margin: 0 90px;">'+product_sla+'%</div></span>';
					//$("#product_sla").css({"background":"#c4001a", "color":"#fff"});
				}else{
					product_sla ='-';
					//$("#product_sla").css({"background":"#c4001a", "color":"#fff"});
				}
				var product_average_TAT = Math.round(result[11]);
				product_average_TAT = product_average_TAT !=''?product_average_TAT:'-';
				$('#sales_closed_count').html(sales_closed_count);
				$('#sales_sla').html(sales_sla);
				$('#sales_average_TAT').html(sales_average_TAT);
				$('#service_closed_count').html(service_closed_count);
				$('#service_sla').html(service_sla);
				$('#service_average_TAT').html(service_average_TAT);
				$('#parts_closed_count').html(parts_closed_count);
				$('#parts_sla').html(parts_sla);
				$('#parts_average_TAT').html(parts_average_TAT);
				$('#product_closed_count').html(product_closed_count);
				$('#product_sla').html(product_sla);
				$('#product_average_TAT').html(product_average_TAT);
			}
		});
		
		$.ajax({  url: '{{url("ajax-postsurvey-search")}}',
			data: {'complaint_type':complaint_type,'productIdnew':product_type,'segmentValue':segment_type,'regionIdnew':region_type,'fromdate':fromdate,'todate':todate},
			success: function(data) {				
				var result = data.split('~');
				var postValue = '';
				if(result[0]>=70){
					postValue ='<span style="color:green;font-size: 108px;">'+result[0]+'<span style="font-size: 45px;">%</span></span><br><span><star style="font-size:24px">*</star> based on '+result[3]+' customer feedback(s), out of '+result[1]+' contacted.</span>';
				}else if(result[0]>=65 && result[0]<=69){
					postValue ='<span style="color:orange;font-size: 108px;">'+result[0]+'<span style="font-size: 45px;">%</span></span><br><span><star style="font-size:24px">*</star> based on '+result[3]+' customer feedback(s),  of '+result[1]+' contacted.</span>';
				}else if(result[0]<=64){
					postValue ='<span style="color:red;font-size: 108px;">'+result[0]+'<span style="font-size: 45px;">%</span></span><br><span><star style="font-size:24px">*</star> based on '+result[3]+' customer feedback(s), out of '+result[1]+' contacted.</span>';
				}
				$('#post_complaint_survey').html(postValue);
				
				
			}
		});
		
		$.ajax({  url: '{{url("ajax-topCategory-search")}}',
		data: {'complaint_type':complaint_type,'productIdnew':product_type,'segmentValue':segment_type,'regionIdnew':region_type,'fromdate':fromdate,'todate':todate},
			success: function(data) {	
			console.log(data);			
				$('#topCategorytabledata').html(data.html);						
			}
		});
		$.ajax({  url: '{{url("ajax-topcustomer-search")}}',
		data: {'complaint_type':complaint_type,'productIdnew':product_type,'segmentValue':segment_type,'regionIdnew':region_type,'fromdate':fromdate,'todate':todate},
			success: function(data) {				
				$('#topCustomertabledata').html(data.html);						
			}
		});
		
		$('#southCount').html(0);
		$('#eastCount').html(0);
		$('#northCount').html(0);
		$('#centralCount').html(0);
		$('#westCount').html(0);	
		
		
	});
	function ajaxClosedSearch(complaint_type, product_type, segment_type, region_type, datefilter,midcontentheader){
		
		var complaintId=complaintValue=productId=productValue=segmentId=segmentValue=regionId=regionValue=fromdate=todate='';
		var complaintIdnew=	productIdnew=segmentIdnew=regionIdnew='';
		var sales=service=parts=product=postValue='';
			$('#post_complaint_survey').html(postValue)
			$('#complaint_type1:checked').each(function () {
				var values = $(this).val();
				sales += values;
			});
			$('#complaint_type2:checked').each(function () {
				var values = $(this).val();
				service += values;
			});
			$('#complaint_type3:checked').each(function () {
				var values = $(this).val();
				parts += values;
			});
			$('#complaint_type4:checked').each(function () {
				var values = $(this).val();
				product += values;
			});
			
			var complaint_type='1,2,3,4';
			var region_type='1,2,3,4,5';
			var product_type ='1,2';
			var segment_type='1,2,3,8,9,5,6,7,10,11';
		@php 
			$current_month = date('m');
			$current_quarter_start = ceil($current_month/4)*3+1; // get the starting month of the current quarter
			$fromDate = date("Y-m-d", mktime(0, 0, 0, $current_quarter_start, 1, date('Y') ));
			$toDate = date('Y-m-d', strtotime($fromDate. ' - 3 month'));
			$currentQuarterDate = $toDate.'~'.$fromDate;			
		@endphp
		if(datefilter==''){		
			datefilter1 = '{{$currentQuarterDate}}';
			midcontentheader = 'ok';
		}	
		if (complaint_type!='') {
			$('.complaintCheckbox:checked').each(function () {
				var values = $(this).val();
				complaintId += values+',';
			});
			var complaintIdnew = complaintId.substring(0, complaintId .length - 1);
			$('#complaintValue').val(complaintIdnew);
			var complaintValue = $('#complaintValue').val();
			
		}
		
		if (product_type!='') {
			$('.productCheckbox:checked').each(function () {
				var values = $(this).val();
				productId += values+',';
			});
			
			var productIdnew = productId.substring(0, productId .length - 1);
			$('#productValue').val(productIdnew);			
			var productValue = $('#productValue').val();
			
			if (productIdnew=='1') {
				$('#segmentBusdatatable').hide();
				$('#segmentTruckdatatable').show();
			} else if (productIdnew =='2') {
				$('#segmentTruckdatatable').hide();
				$('#segmentBusdatatable').show();
			} else if (productIdnew=='1,2') {
				$('#segmentTruckdatatable').show();
				$('#segmentBusdatatable').show();
			}else{
				$('#segmentTruckdatatable').hide();
				$('#segmentBusdatatable').hide();
			}
		}
		if (segment_type!='') {
			$('.segmentChechbox:checked').each(function () {
				var values = $(this).val();
				segmentId += values+',';
			});
			var segmentIdnew = segmentId.substring(0, segmentId .length - 1);			
			$('#segmentValue').val(segmentIdnew);
			var segmentValue = $('#segmentValue').val();
		}
		if (region_type!='') {
			$('.regionCheckbox:checked').each(function () {
				var values = $(this).val();
				regionId += values+',';
			});
			var regionIdnew = regionId.substring(0, regionId .length - 1);				
			$('#regionValue').val(regionIdnew);
			var regionValue = $('#regionIdnew').val();
		}
		
			if (datefilter!='') {
				var date =datefilter.split("~");
				var fromdate = date[0];
				var todate = date[1];			
			}
		
	
		ajaxTatSearch(sales,service,parts,product,fromdate,todate,productIdnew, segmentIdnew, regionIdnew);		
		ajaxPostSurveySearch(complaintIdnew, productIdnew, segmentIdnew, regionIdnew,fromdate,todate);
		ajaxTopCategorySearch(complaintIdnew, productIdnew, segmentIdnew, regionIdnew,fromdate,todate);
		ajaxTopCustomerSearch(complaintIdnew, productIdnew, segmentIdnew, regionIdnew,fromdate,todate);
	}
	function ajaxTatSearch(sales,service,parts,product,fromdate,todate, productValue, segmentValue, regionValue){
		
			$.ajax({  url: '{{url("ajax-tat-search")}}',
			data: { 'sales':sales,'service':service,'parts':parts,'product':product,'fromdate':fromdate,'todate':todate,'productIdnew':productValue,'segmentValue':segmentValue,'regionIdnew':regionValue},
				beforeSend: function() {
					$('#ajaxLoader').show();
				},
			success: function(data) {
				$('#ajaxLoader').hide();
				var result = data.split(',');
				var sales_closed_count = result[0];
				var sales_sla = Math.round((result[1]/result[0])*100);				
				
				if (sales_sla >=90 && sales_sla <=100){
					sales_sla ='<span><div style="background:#78b833;color:#fff;margin: 0 90px;">'+sales_sla+'%</div></span>';
					//$("#sales_sla").css({"background":"#78b833", "color":"#fff"});
				}else if(sales_sla >=70 && sales_sla <90){
					sales_sla ='<span><div style="background:#f7d302;color:#000;margin: 0 90px;">'+sales_sla+'%</div></span>';
					//$("#sales_sla").css({"background":"#f7d302", "color":"#000"});
				}
				else if(sales_sla>0 && sales_sla <70){
					sales_sla ='<span><div style="background:#c4001a;color:#fff;margin: 0 90px;">'+sales_sla+'%</div></span>';
					//$("#sales_sla").css({"background":"#c4001a", "color":"#fff"});
				}
				else{
					sales_sla ='-';
					//$("#sales_sla").css({"background":"#c4001a", "color":"#fff"});
				}
				var sales_average_TAT = Math.round(result[2]);
				sales_average_TAT = sales_average_TAT !=''?sales_average_TAT:'-';
				var service_closed_count = result[3];
				
				var service_sla = Math.round((result[4]/result[3])*100);
				if (service_sla >=90 && service_sla <=100){
					service_sla ='<span><div style="background:#78b833;color:#fff;margin: 0 90px;">'+service_sla+'%</div></span>';
					//$("#service_sla").css({"background":"#78b833", "color":"#fff"});
				}else if(service_sla >=70 && service_sla <90){
					service_sla ='<span><div style="background:#f7d302;color:#000;margin: 0 90px;">'+service_sla+'%</div></span>';
					//$("#service_sla").css({"background":"#f7d302", "color":"#000"});
				}
				else if(service_sla>0 && service_sla <70){
					service_sla ='<span><div style="background:#c4001a;color:#fff;margin: 0 90px;">'+service_sla+'%</div></span>';
					//$("#service_sla").css({"background":"#c4001a", "color":"#fff"});
				}else{
					service_sla ='-';
					//$("#service_sla").css({"background":"#c4001a", "color":"#fff"});
				}
				var service_average_TAT =Math.round(result[5]);
				var service_average_TAT = service_average_TAT !=''?service_average_TAT:'-';
				var parts_closed_count = result[6];
				var parts_sla = Math.round((result[7]/result[6])*100);
				if (parts_sla >=90 && parts_sla <=100){
					parts_sla ='<span><div style="background:#78b833;color:#fff;margin: 0 90px;">'+parts_sla+'%</div></span>';
					//$("#parts_sla").css({"background":"#78b833", "color":"#fff"});
				}else if(parts_sla >=70 && parts_sla <90){
					parts_sla ='<span><div style="background:#f7d302;color:#000;margin: 0 90px;">'+parts_sla+'%</div></span>';
					//$("#parts_sla").css({"background":"#f7d302", "color":"#000"});
				}
				else if(parts_sla>0 && parts_sla <70){
					parts_sla ='<span><div style="background:#c4001a;color:#fff;margin: 0 90px;">'+parts_sla+'%</div></span>';
					//$("#parts_sla").css({"background":"#c4001a", "color":"#fff"});
				}else{
					parts_sla ='-';
					//$("#parts_sla").css({"background":"#c4001a", "color":"#fff"});
				}
				var parts_average_TAT = Math.round(result[8]);
				parts_average_TAT = parts_average_TAT !=''?parts_average_TAT:'-';
				var product_closed_count = result[9];
				var product_sla = Math.round((result[10]/result[9])*100);
				if (product_sla >=90 && product_sla <=100){
					product_sla ='<span><div style="background:#78b833;color:#fff;margin: 0 90px;">'+product_sla+'%</div></span>';
					//$("#product_sla").css({"background":"#78b833", "color":"#fff"});
				}else if(product_sla >=70 && product_sla <90){
					product_sla ='<span><div style="background:#f7d302;color:#000;margin: 0 90px;">'+product_sla+'%</div></span>';
					//$("#product_sla").css({"background":"#f7d302", "color":"#000"});
				}
				else if(product_sla>0 && product_sla <70){
					product_sla ='<span><div style="background:#c4001a;color:#fff;margin: 0 90px;">'+product_sla+'%</div></span>';
					//$("#product_sla").css({"background":"#c4001a", "color":"#fff"});
				}else{
					product_sla ='-';
					//$("#product_sla").css({"background":"#c4001a", "color":"#fff"});
				}
				var product_average_TAT = Math.round(result[11]);
				
				product_average_TAT = product_average_TAT !=''?product_average_TAT:'-';
				$('#sales_closed_count').html(sales_closed_count);
				$('#sales_sla').html(sales_sla);
				$('#sales_average_TAT').html(sales_average_TAT);
				$('#service_closed_count').html(service_closed_count);
				$('#service_sla').html(service_sla);
				$('#service_average_TAT').html(service_average_TAT);
				$('#parts_closed_count').html(parts_closed_count);
				$('#parts_sla').html(parts_sla);
				$('#parts_average_TAT').html(parts_average_TAT);
				$('#product_closed_count').html(product_closed_count);
				$('#product_sla').html(product_sla);
				$('#product_average_TAT').html(product_average_TAT);
			}
		});
		}
	function ajaxComplaintSearch(complaint_type, product_type, segment_type, region_type, datefilter,midcontentheader){
		
		var complaintId=complaintValue=productId=productValue=segmentId=segmentValue=regionId=regionValue=fromdate=todate='';
		if (complaint_type!='') {
			$('.complaintCheckbox:checked').each(function () {
				var values = $(this).val();
				complaintId += values+',';
			});
			var complaintIdnew = complaintId.substring(0, complaintId .length - 1);
			$('#complaintValue').val(complaintIdnew);
			var complaintValue = $('#complaintValue').val();
			
		}
		if (product_type!='') {
			$('.productCheckbox:checked').each(function () {
				var values = $(this).val();
				productId += values+',';
			});
			
			var productIdnew = productId.substring(0, productId .length - 1);
			$('#productValue').val(productIdnew);			
			var productValue = $('#productValue').val();
			if (productIdnew=='1') {
				$('#segmentBusdatatable').hide();
				$('#segmentTruckdatatable').show();
			} else if (productIdnew =='2') {
				$('#segmentTruckdatatable').hide();
				$('#segmentBusdatatable').show();
			} else if (productIdnew=='1,2') {
				$('#segmentTruckdatatable').show();
				$('#segmentBusdatatable').show();
			}else{
				$('#segmentTruckdatatable').hide();
				$('#segmentBusdatatable').hide();
			}
		}
		if (segment_type!='') {
			$('.segmentChechbox:checked').each(function () {
				var values = $(this).val();
				segmentId += values+',';
			});
			var segmentIdnew = segmentId.substring(0, segmentId .length - 1);			
			$('#segmentValue').val(segmentIdnew);
			var segmentValue = $('#segmentValue').val();
		}
		if (region_type!='') {
			$('.regionCheckbox:checked').each(function () {
				var values = $(this).val();
				regionId += values+',';
			});
			var regionIdnew = regionId.substring(0, regionId .length - 1);				
			$('#regionValue').val(regionIdnew);
			var segmentValue = $('#segmentValue').val();
		}
		if (midcontentheader!=''){
			if (datefilter!='') {
				var date =datefilter.split("~");
				var fromdate = date[1];
				var todate = date[0];			
			}
		}
		
		$.ajax({  url: '{{url("ajax-complaint-search")}}',
		data: { 'complaintId':complaintValue,'productIdnew':productValue,'segmentValue':segmentValue,'regionIdnew':regionIdnew,'fromdate':fromdate,'todate':todate},
			beforeSend: function() {
				$('#ajaxLoader').show();
			},
			success: function(data) {
				$('#ajaxLoader').hide();
				$('#dashboardtabledata').html(data.html);				
				pieChartFunction(complaintValue,productIdnew,segmentValue,regionIdnew,datefilter,midcontentheader);				
			}
		});
		
	}
	function ajaxPostSurveySearch(complaint_type, product_type, segment_type, region_type,fromdate,todate){
		
		
		$.ajax({  url: '{{url("ajax-postsurvey-search")}}',
			data: {'complaint_type':complaint_type,'productIdnew':product_type,'segmentValue':segment_type,'regionIdnew':region_type,'fromdate':fromdate,'todate':todate},
			beforeSend: function() {
				$('#ajaxLoader').show();
			},
			success: function(data) {
				$('#ajaxLoader').hide();
				var result = data.split('~');
				var postValue = '';
				if(result[0]>=70){
					postValue ='<span style="color:green;font-size: 108px;">'+result[0]+'<span style="font-size: 45px;">%</span></span><br><span><star style="font-size:24px">*</star> based on '+result[3]+' customer feedback(s), out of '+result[1]+' contacted.</span>';
				}else if(result[0]>=65 && result[0]<=69){
					postValue ='<span style="color:orange;font-size: 108px;">'+result[0]+'<span style="font-size: 45px;">%</span></span><br><span><star style="font-size:24px">*</star> based on '+result[3]+' customer feedback(s),  of '+result[1]+' contacted.</span>';
				}else if(result[0]<=64){
					postValue ='<span style="color:red;font-size: 108px;">'+result[0]+'<span style="font-size: 45px;">%</span></span><br><span><star style="font-size:24px">*</star> based on '+result[3]+' customer feedback(s), out of '+result[1]+' contacted.</span>';
				}
				$('#post_complaint_survey').html(postValue);
				/*var result = data.split(',');
				var totalCompletedCases = result[0];
				var totalCompletedSurvey = result[1];
				
				google.charts.load('current', {'packages':['corechart']});
				google.charts.setOnLoadCallback(postComplaintSurvey);
				function postComplaintSurvey(x, y){			
					var data = new google.visualization.DataTable();
					data.addColumn('string', 'Topping');
					data.addColumn('number', 'Slices');
					data.addRows([
						['Post Complaint Survey',parseInt(totalCompletedSurvey)],
						['Total Cases', parseInt(totalCompletedCases)]
					]);
					// Set options for Sarah's pie chart.
					var options = {title:'',
					colors: ['#78b833','#E87A27'],
					pieHole: 0.4,
					width:'100%',
					height:'100%',
					'is3D':   false,pieSliceText: "value", tooltip: {
            text: 'value'
        }};
					// Instantiate and draw the chart for Sarah's pizza.
					var chart = new google.visualization.PieChart(document.getElementById('post_complaint_survey'));
					chart.draw(data, options);
				}
				*/
			}
		});
	}
	function ajaxTopCategorySearch(complaint_type, product_type, segment_type, region_type,fromdate,todate){
		$.ajax({  url: '{{url("ajax-topCategory-search")}}',
		data: {'complaint_type':complaint_type,'productIdnew':product_type,'segmentValue':segment_type,'regionIdnew':region_type,'fromdate':fromdate,'todate':todate},
			beforeSend: function() {
				$('#ajaxLoader').show();
			},
			success: function(data) {
				$('#ajaxLoader').hide();
				$('#topCategorytabledata').html(data.html);						
			}
		});
	}
	function ajaxTopCustomerSearch(complaint_type, product_type, segment_type, region_type,fromdate,todate){
		
		$.ajax({  url: '{{url("ajax-topcustomer-search")}}',
		data: {'complaint_type':complaint_type,'productIdnew':product_type,'segmentValue':segment_type,'regionIdnew':region_type,'fromdate':fromdate,'todate':todate},
			beforeSend: function() {
				$('#ajaxLoader').show();
			},
			success: function(data) {
				$('#ajaxLoader').hide();
				$('#topCustomertabledata').html(data.html);						
			}
		});
	}
		
	
 </script>
<div class="container-fluid mobcss" style="background: #e5e5e5;">
	<div class="card" style="background: #e5e5e5;">
		<div class="card-body" style="background: #e5e5e5;">
			<input type="hidden" name="dateValue" id="dateValue">
			<input type="hidden" name="complaintValue" id="complaintValue">
			<input type="hidden" name="productValue" id="productValue">
<input type="hidden" name="segmentValue" id="segmentValue">
<input type="hidden" name="regionValue" id="regionValue">
			<div class="row">
				<div class="col-lg-2" style="background: #fff;">
					<a class="nav-link" href="{{url('cms')}}"><p style="background: #111;color: #fff;" class="sidenav_p">Dashboard</p></a>
					<a href="{{url('open-complaint')}}" class="sidenav_a" >Open Complaints</a>
					<a href="{{url('closed-complaint')}}" class="sidenav_a" style="color: #000;background: #ccc;">Closed Complaints</a>
					<p style="border-top: 1px solid #ccc; margin: 10px 0;"></p>
					<p style="border-top: 1px solid #ccc;margin-bottom: 10px" ></p>
					<p class="sub_p">
						Complaint Type
						<span style="float: right;">
							
						</span>
						<div>
							@isset($complaint_data)
							@php $i=1; @endphp
							@foreach($complaint_data as $row)
							<div class="checkbox">
								
								<label><input type="checkbox" name="complaint_type[]" class="complaintCheckbox" id="complaint_type{{$i}}" value="{{$row->id}}" onchange="ajaxClosedSearch(this.value,'','','',dateValue.value,'')">{{$row->complaint_type}}</label>
							</div>
							@php $i++; @endphp
							@endforeach
							@endisset
						</div>
					</p>
					<p class="sub_p">
						Product
						<span style="float: right;">
							

						</span>
						<div>
							
							<div class="checkbox">
								
								<label><input type="checkbox" name="product[]" class="productCheckbox" id="productTruck" value="1" onclick="ajaxClosedSearch(complaintValue.value,this.value,'','',dateValue.value,'')"  >Truck</label>
							</div>
							<div class="checkbox">
								<label><input type="checkbox" name="product[]" class="productCheckbox" id="productBus" value="2" onclick="ajaxClosedSearch(complaintValue.value,this.value,'','',dateValue.value,'')"  >Bus</label>
							</div>

						</div>
					</p>
					<p class="sub_p">
						Segment
						<span style="float: right;">
							
						</span>
						
						<table>
							<tr>
								<td>
									<!--<div id="segment_checkbox"></div>-->
									<div id="segmentTruckdatatable" style="display: none">
										@isset($segmentTruckData)
										@foreach($segmentTruckData as $row)
										<div class="checkbox" style="font-size: 12px;">
											<label><input type="checkbox" name="segment[]" class="segmentChechbox" id="segment_truck{{$row->id}}" value="{{$row->id}}" onchange="ajaxClosedSearch(complaintValue.value,productValue.value,this.value,'',dateValue.value,'')">{{$row->segment}}</label>
										</div>
										@endforeach
										@endisset
									</div>
								</td>
								<td>
									<!--{{--<div id="segment_checkbox1"></div>--}}-->
									<div id="segmentBusdatatable" style="display: none">
										@isset($segmentBusData)
										@foreach($segmentBusData as $row)
										<div class="checkbox" style="font-size: 12px;">
											<label><input type="checkbox" name="segment[]" class="segmentChechbox" id="segment_bus{{$row->id}}" value="{{$row->id}}" onchange="ajaxClosedSearch(complaintValue.value,productValue.value,this.value,'',dateValue.value,'')">{{$row->segment}}</label>
										</div>
										@endforeach
										@endisset
									</div>
								</td>
							</tr>

						</table>

					</p>
					<p class="sub_p">
						Region
						<span style="float: right;">
							
						</span>
						
						<div>
							@isset($regionData)
							@foreach($regionData as $row)
							<div class="checkbox">
								<label><input type="checkbox" name="region[]" id="region{{$row->id}}" value="{{$row->id}}" class="regionCheckbox" onchange="ajaxClosedSearch(complaintValue.value,productValue.value,segmentValue.value,this.value,dateValue.value,'')">{{$row->region}}</label>
							</div>
							@endforeach
							@endisset
						</div>
					</p>
					@php
					$current_month = date('m');
					$current_quarter_start = ceil($current_month/4)*3+1; // get the starting month of the current quarter
	    			$fDate = date("Y-m-d", mktime(0, 0, 0, $current_quarter_start, 1, date('Y') ));
					$tDate = date('Y-m-d', strtotime($fromDate. ' - 3 month'));
					$fromDate = $tDate;
					$toDate = $fDate;
					
					$midcontentHeader = 'click';
					$currentQuarterDate = "'".$fromDate.'~'.$toDate."'";
					$sixmonth = 180;
					$oneYear =365;
					$twoYear =730;
					$currentDate = date('Y-m-d',strtotime( 'now' ));
					$twoQuaterDate = date('Y-m-d', strtotime($fromDate. ' - 6 month'));
					$fromtwoQuaterDate = "'".$twoQuaterDate.'~'.$fromDate."'";
					$oneYearDate = date('Y-m-d', strtotime($fromDate. ' - 12 month'));
					$fromOneYearDate = "'".$oneYearDate.'~'.$fromDate."'";
					$twoYearDate = date('Y-m-d', strtotime($toDate. ' - 24 month'));
					$fromTwoYearDate = "'".$twoYearDate.'~'.$fromDate."'";
					
					@endphp
					<p class="sub_p" style="font-size: 12px;">
						Period
						<span style="float: right;"></span><br>
						<div style="float:left">
						<a onclick="ajaxClosedSearch(complaintValue.value,productValue.value,segmentValue.value,regionValue.value,{{$currentQuarterDate}},{{$midcontentHeader}}); activeClass('quater',{{$currentQuarterDate}});" id="quater" class="middlecontent">Current Qtr</a><br>
						<a onclick="ajaxClosedSearch(complaintValue.value,productValue.value,segmentValue.value,regionValue.value,{{$fromtwoQuaterDate}},{{$midcontentHeader}}); activeClass('half',{{$fromtwoQuaterDate}});" id="half" class="middlecontent" style="float: left;">2Qtr</a><br>
						<a onclick="ajaxClosedSearch(complaintValue.value,productValue.value,segmentValue.value,regionValue.value,{{$fromOneYearDate}},{{$midcontentHeader}}); activeClass('one',{{$fromOneYearDate}});" id="oneyear" class="middlecontent" style="float: left;">4Qtr</a><br>
						</div>
					</p>
					<div style="clear: both"></div>
						<p class="sub_p">
							Area / Dealer
							<span style="float: right;">
								
							</span>
							<select id="dealerId" style="width: 100%;border-radius: 12px;border: 1px solid #ccc;" onchange="ajaxDealerSearch(complaintValue.value,productValue.value,segmentValue.value,regionValue.value,dateValue.value,'click',dealerId.value)">
								<option value="NA">--Select--</option>
								@isset($dealerData)
									@foreach($dealerData as $row)
										<option value="{{$row->id}}">{{$row->dealer_name}}</option>
									@endforeach
								@endisset
							</select>
					</p>
					
				</div>
				<div class="col-lg-10">
					<div class="row">
						<div class="col-lg-6">
							<div style="background: #fff;padding: 8px;border-radius: 4px;margin-bottom: 20px;height: 237px;box-shadow: 0 8px 6px -6px black;">
								<span style="font-size: 16px;">Turn Around Time</span>
								<!--<a id="yeartat"  onclick="ajaxTatSearch('1','2','3','4','{{$oneYearDate}}','{{$fromDate}}'); activeClasstat('yeartat');"   class="middlecontent">4Qtr</a>
								<a id="halftat" onclick="ajaxTatSearch('1','2','3','4','{{$twoQuaterDate}}','{{$fromDate}}'); activeClasstat('halftat');"  class="middlecontent">2Qtr</a>
								<a id="qtrtat" onclick="ajaxTatSearch('1','2','3','4','{{$fromDate}}','{{$toDate}}'); activeClasstat('qtrtat');"  class="middlecontent">Current Qtr</a>-->
								<table class="table dataTable no-footer" style="text-align: center;">
									<tr>
										<th  style="text-align: left;font-size: 14px;">Category</th>
										<th style="font-size: 14px;">Count</th>
										<th style="font-size: 14px;">SLA Compliled</th>
										<th style="font-size: 14px;">Average TAT</th>
									</tr>
									<tr>
										<td style="text-align: left;">Sales</td>
										<td id="sales_closed_count"></td>
										<td id="sales_sla"></td>
										<td id="sales_average_TAT"></td>
									</tr>
									<tr>
										<td style="text-align: left;">Service</td>
										<td id="service_closed_count"></td>
										<td id="service_sla"></td>
										<td id="service_average_TAT"></td>
									</tr>
									<tr>
										<td style="text-align: left;">Parts</td>
										<td id="parts_closed_count"></td>
										<td id="parts_sla"></td>
										<td id="parts_average_TAT"></td>
									</tr>
									<tr>
										<td style="text-align: left;">Product</td>
										<td id="product_closed_count"></td>
										<td id="product_sla"></td>
										<td id="product_average_TAT"></td>
									</tr>
								</table>
								
							</div>
						</div>
						<div class="col-lg-6">
							<div style="background: #fff;padding: 8px;border-radius: 4px;margin-bottom: 20px;box-shadow: 0 8px 6px -6px black;">
								<span  style="font-size: 16px;">Post Complaint Survey</span>	
								<!--<a id="yearsurvey" onclick="ajaxPostSurveySearch('{{$oneYearDate}}','{{$fromDate}}'); activeClasssurvey('yearsurvey');"  class="middlecontent">4Qtr</a>
								<a id="halfsurvey" onclick="ajaxPostSurveySearch('{{$twoQuaterDate}}','{{$fromDate}}'); activeClasssurvey('halfsurvey');" class="middlecontent">2Qtr</a>
								<a id="qtrsurvey" onclick="ajaxPostSurveySearch('{{$fromDate}}','{{$toDate}}'); activeClasssurvey('qtrsurvey');" class="middlecontent">Current Qtr</a>-->
								<div style="border-bottom: 1px solid #ccc;"></div>							
								<div id="post_complaint_survey" style="text-align: center;"></div>
								 
								
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6">
							<div style="background: #fff;padding: 8px;border-radius: 4px;box-shadow: 0 8px 6px -6px black;">
								<span  style="font-size: 16px;">Top Categories</span>
								<!--<a id="yeartcat" onclick="ajaxTopCategorySearch('{{$oneYearDate}}','{{$fromDate}}'); activeClasstcat('yeartcat');"  class="middlecontent">4Qtr</a>
								<a id="halftcat" onclick="ajaxTopCategorySearch('{{$twoQuaterDate}}','{{$fromDate}}'); activeClasstcat('halftcat');" class="middlecontent">2Qtr</a>
								<a id="qtrtcat" onclick="ajaxTopCategorySearch('{{$fromDate}}','{{$toDate}}'); activeClasstcat('qtrtcat');" class="middlecontent">Current Qtr</a>	-->
								<div style="border-bottom: 1px solid #ccc;"></div>			
								<div id="topCategorytabledata"></div>
								
							</div>
						</div>
						<div class="col-lg-6">
							<div style="background: #fff;padding: 8px;border-radius: 4px;box-shadow: 0 8px 6px -6px black;">
								<span  style="font-size: 16px;">Top Customers</span>
								<!--<a id="yeartcus" onclick="ajaxTopCustomerSearch('{{$oneYearDate}}','{{$fromDate}}'); activeClasstcus('yeartcus');"  class="middlecontent">4Qtr</a>
								<a id="halftcus" onclick="ajaxTopCustomerSearch('{{$twoQuaterDate}}','{{$fromDate}}'); activeClasstcus('halftcus');" class="middlecontent">2Qtr</a>
								<a id="qtrtcus" onclick="ajaxTopCustomerSearch('{{$fromDate}}','{{$toDate}}'); activeClasstcus('qtrtcus');" class="middlecontent">Current Qtr</a>	-->	
								<div style="border-bottom: 1px solid #ccc;"></div>		
								<div id="topCustomertabledata"></div>
								
							</div>
						</div>						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div style="clear:both;"></div>
<script type="text/javascript">
	$(document).ready(function () {
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

	});
</script>
@endsection
<style>
.activeclass{
	font-weight: 800 !important;
    text-decoration: none;
    border-bottom: 4px solid #000;
	
}
.horizontal-menu.fixed-on-scroll + .page-body-wrapper {
	padding-top: 4rem;
	background: #e5e5e5;
}
.middlecontent{
		float: right;font-size: 12px;margin-left: 10px;text-decoration: underline;
}
.tabledash{
		width: 80%;
		border: 1px solid #fff;
		margin: 0 auto;
		color: #fff;
}
.tabledash tr td{
		padding: 2px 19px;
		font-size: 12px;
}

.sidenav {
		height: 100%;
		width: 200px;
		position: relative;
		z-index: 1;
		left: 0;
		overflow-x: hidden;
		background: #ffffff;
}

.sidenav_a {
		padding: 6px 8px 6px 16px;
		text-decoration: none;
		font-size: 15px;
		color: #818181;
		display: block;
}
.sidenav_p {
		padding: 6px 8px 6px 16px;
		text-decoration: none;
		font-size: 20px;
		color: #111;
		display: block;
	}
	.sub_p {
		
		text-decoration: none;
		font-size: 15px !important;
		color: #818181;
		display: block;
	}

	.sidenav a:hover {
		color: #111;
	}

	.main {
		margin-left: 160px; /* Same as the width of the sidenav */
		font-size: 28px; /* Increased text to enable scrolling */
		padding: 0px 10px;
	}

	@media screen and (max-height: 450px) {
		.sidenav {

		}
		.sidenav a {
			font-size: 18px;
		}
	}
</style>