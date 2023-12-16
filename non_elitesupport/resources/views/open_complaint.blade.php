@extends("layouts.masterlayout")
@section('title','Open Complaint')
@section('bodycontent')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"> </script>
<script type="text/javascript" src="{{asset('js/google-chart.js')}}"> </script>
<script type="text/javascript">
function ajaxDealerSearch(complaintIds,productIds,segmentIds,regionIds,stringValue,dealerIds){
	
	@php
			$complaint_type = Session::get('complaint_type_id');
			$complaint_type_id = Session::get('complaint_type_id');
			$role = Session::get('role');
			$user_type_id = Session::get('user_type_id');
			$product = Session::get('product');
			$complaint_type_idArr = explode(',',$complaint_type_id);
		@endphp
		var role = '{{$role}}';
		var user_type_id = '{{$user_type_id}}';
		var product = '{{$product}}';
		var complaint_type =product_type= '';
		var salesIdChecked ='';
		var serviceIdChecked ='';
		var partsIdChecked ='';
		var productIdChecked ='';
		if(role == '29' || role == '30' || user_type_id =='2'){
			complaint_type='1,2,3,4';
			product_type ='1,2';
			salesIdChecked ='1';
			serviceIdChecked ='1';
			partsIdChecked ='1';
			productIdChecked ='1';
		}else{			
			complaint_type = '{{$complaint_type}}';
			product_type = '{{$product}}';
			@php
			$sales=$service=$parts=$product='';	
			if(in_array('1',$complaint_type_idArr)){
				$sales='1';
			}else{
				$sales = '';				
			}
			if(in_array('2',$complaint_type_idArr)){
				$service='2';
			}else{
				$service = '';				
			}
			if(in_array('3',$complaint_type_idArr)){
				$parts='3';
			}else{
				$parts = '';				
			}
			if(in_array('4',$complaint_type_idArr)){
				$product='4';
			}else{
				$product = '';				
			}
			@endphp
			
			salesIdChecked ='{{$sales}}';
			serviceIdChecked ='{{$service}}';
			partsIdChecked ='{{$parts}}';
			productIdChecked ='{{$product}}';
		}	
	$.ajax({  url: '{{url("ajax-bar-table-search")}}',
		data: { 'complaintId':complaintIds,'productIdnew':productIds,'segmentValue':segmentIds,'regionIdnew':regionIds,'dealerIds':dealerIds},
		beforeSend: function() {
			$('#ajaxLoader').show();
		},
			success: function(data) {
				$('#ajaxLoader').hide();
				$('#dashboardtabledata').html(data.html);
				if(salesIdChecked > 0 || serviceIdChecked > 0 || partsIdChecked > 0 || productIdChecked > 0){
					
					var Sales_1_5= Sales_6_15=Sales_above_15=Service_1_5=Service_6_15=Service_above_15='';	
		var Parts_1_5= Parts_6_15=Parts_above_15=Product_1_30=Product_30_45=Product_above_45='';
		$.ajax({  url: '{{url("ajax-bar-search")}}',
		data: { 'salesIdChecked':salesIdChecked,'serviceIdChecked':serviceIdChecked,'partsIdChecked':partsIdChecked,'productIdChecked':productIdChecked,'complaintValue':complaintIds,'productValue':productIds,'segmentValue':segmentIds,'regionIdnew':regionIds,'dealerIds':dealerIds},
			beforeSend: function() {
				$('#ajaxLoader').show();
			},
			success: function(data){
				$('#ajaxLoader').hide();
				var result = data.split(',');
				
				Sales_1_5 =result[0];
				Sales_6_15 =result[1];
				Sales_above_15 =result[2];
				Service_1_5 =result[3];
				Service_6_15 =result[4];
				Service_above_15 =result[5];
				Parts_1_5 =result[6];
				Parts_6_15 =result[7];
				Parts_above_15 =result[8];
				Product_1_30 =result[9];
				Product_30_45 =result[10];
				Product_above_45 =result[11];
				google.load("visualization", '1.1', { packages: ['corechart'] });
				google.setOnLoadCallback(drawChart);
				function drawChart() {					
				    var general_complaint = google.visualization.arrayToDataTable([
				      ['', '1 to 5', '6 To 15', 'above15'],
				      ['Sales', parseInt(Sales_1_5), parseInt(Sales_6_15), parseInt(Sales_above_15)],
				      ['Service', parseInt(Service_1_5), parseInt(Service_6_15), parseInt(Service_above_15)],
				      ['Parts', parseInt(Parts_1_5), parseInt(Parts_6_15), parseInt(Parts_above_15)]
				    ]); 
				    /*var regionBar = google.visualization.arrayToDataTable([
				      ['', '1 to 5', '6 To 15', 'above15'],
				      ['Sales', 8, 2, 0],
				      ['Service', 2, 0, 2],
				      ['Parts', 4, 1, 0]
				    ]);*/
				    var product_complaint = google.visualization.arrayToDataTable([
				      ['', '1 to 30', '30 To 45', 'above45'],
				      ['Products', parseInt(Product_1_30), parseInt(Product_30_45), parseInt(Product_above_45)]
				    ]);

				    var options = {
				        width: '100%',
				        height: '100%',
				        colors: ['#78b833','#F7D302','#c4001a'],
				        legend: { position: 'top', maxLines: 3 },
				        bar: { groupWidth: '20%' },
				        isStacked: true,
				    };
				    var options1 = {
				        width: '100%',
				        height: '100%',
				        colors: ['#78b833','#F7D302','#c4001a'],
				        legend: { position: 'top', maxLines: 3 },
				        bar: { groupWidth: '7%' },
				        isStacked: true,
				    };

				    var view = new google.visualization.DataView(general_complaint);
				    view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);
				    var chart = new google.visualization.ColumnChart(document.getElementById('general_complaint'));    
				    google.visualization.events.addListener(chart, 'select', function () {
				        highlightBar(chart, options, view);
				    });
				    chart.draw(general_complaint, options);
				    
				   /* var view1 = new google.visualization.DataView(regionBar);
				    var chart1 = new google.visualization.ColumnChart(document.getElementById('regionBar'));    
				    google.visualization.events.addListener(chart1, 'select', function () {
				        highlightBar(chart1, options, view1);
				    });
				    chart1.draw(regionBar, options);*/
				    
				    var view2 = new google.visualization.DataView(product_complaint);
				    var chart2 = new google.visualization.ColumnChart(document.getElementById('product_complaint'));    
				    google.visualization.events.addListener(chart2, 'select', function () {
				        highlightBar(chart2, options1, view2);
				    });
				    chart2.draw(product_complaint, options1);
				}


				function highlightBar(chart, options, view) {
				    var selection = chart.getSelection();
				    if (selection.length) {
				        var row = selection[0].row;
				        var column = selection[0].column;


				        //1.insert style role column to highlight selected column 
				        var styleRole = {
				            type: 'string',
				            role: 'style',
				            calc: function (dt, i) {
				                return (i == row) ? 'stroke-color: #000000; stroke-width: 2' : null;
				            }
				        };
				        var indexes = [0, 1, 2, 3];
				        var styleColumn = findStyleRoleColumn(view)
				        if (styleColumn != -1 && column > styleColumn)
				            indexes.splice(column, 0, styleRole);
				        else
				            indexes.splice(column + 1, 0, styleRole);
				        view.setColumns(indexes);
				        //2.redraw the chart
				        chart.draw(view, options);
				    }
				}

				function findStyleRoleColumn(view) {
				    for (var i = 0; i < view.getNumberOfColumns() ; i++) {
				        if (view.getColumnRole(i) == "style") {
				            return i;
				        }
				    }
				    return -1;
				}	
			}
		});
				}				
								
			}
		});
}		
	$(document).ready(function() {
		@php
			$complaint_type = Session::get('complaint_type_id');
			$complaint_type_id = Session::get('complaint_type_id');
			$role = Session::get('role');
			$user_type_id = Session::get('user_type_id');
			$product = Session::get('product');
			$complaint_type_idArr = explode(',',$complaint_type_id);
			$product_idArr = explode(',',$product);
		@endphp
		var role = '{{$role}}';
		var user_type_id = '{{$user_type_id}}';
		var product = '{{$product}}';
		var complaint_type =product_type= '';
		var salesIdChecked ='';
		var serviceIdChecked ='';
		var partsIdChecked ='';
		var productIdChecked ='';
		if(role == '29' || role == '30' || user_type_id =='2'){
			complaint_type='1,2,3,4';
			product_type ='1,2';
			salesIdChecked ='1';
			serviceIdChecked ='1';
			partsIdChecked ='1';
			productIdChecked ='1';
			$('#complaint_type1').prop("checked", true);
			$('#complaint_type2').prop("checked", true);
			$('#complaint_type3').prop("checked", true);
			$('#complaint_type4').prop("checked", true);
			$('#productTruck').prop('checked', true);
		$('#productBus').prop('checked', true);
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
		$('#segmentTruckdatatable').show();
		$('#segmentBusdatatable').show();
		$('#region1').prop('checked', true);
		$('#region2').prop('checked', true);
		$('#region3').prop('checked', true);
		$('#region4').prop('checked', true);
		$('#region5').prop('checked', true);
		}else{
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
			complaint_type = '{{$complaint_type}}';
			product_type = '{{$product}}';
			@php
			$sales=$service=$parts=$product='';	
			if(in_array('1',$complaint_type_idArr)){
				$sales='1';
			}else{
				$sales = '';				
			}
			if(in_array('2',$complaint_type_idArr)){
				$service='2';
			}else{
				$service = '';				
			}
			if(in_array('3',$complaint_type_idArr)){
				$parts='3';
			}else{
				$parts = '';				
			}
			if(in_array('4',$complaint_type_idArr)){
				$product='4';
			}else{
				$product = '';				
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
			salesIdChecked ='{{$sales}}';
			serviceIdChecked ='{{$service}}';
			partsIdChecked ='{{$parts}}';
			productIdChecked ='{{$product}}';
			var truckBox ='{{$truckBox}}';
			var busBox ='{{$busBox}}';
			
			if(salesIdChecked !=''){
				$('#complaint_type1').prop("checked", true);
				$("#complaint_type1").prop("disabled", false);
				$("#complaint_type1").val(salesIdChecked);
			}else{
				$('#complaint_type1').prop("checked", false);
				$("#complaint_type1").prop("disabled", true);
			}
			if(serviceIdChecked !=''){
				$('#complaint_type2').prop("checked", true);
				$("#complaint_type2").prop("disabled", false);
				$("#complaint_type2").val(serviceIdChecked);
			}else{
				$('#complaint_type2').prop("checked", false);
				$("#complaint_type2").prop("disabled", true);
			}
			if(partsIdChecked !=''){
				$('#complaint_type3').prop("checked", true);
				$("#complaint_type3").prop("disabled", false);
				$("#complaint_type3").val(partsIdChecked);
			}else{
				$('#complaint_type3').prop("checked", false);
				$("#complaint_type3").prop("disabled", true);
			}
			if(productIdChecked !=''){
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
		var region_type='1,2,3,4,5';		
		var segment_type='1,2,3,8,9,5,6,7,10,11';
		$('#complaintValue').val(complaint_type);		
		$('#productValue').val(product_type);
		$('#segmentValue').val(segment_type);
		$('#regionValue').val(region_type);
		
		$.ajax({  url: '{{url("ajax-bar-table-search")}}',
		data: { 'complaintId':complaint_type,'productIdnew':product_type,'segmentValue':segment_type,'regionIdnew':region_type},
			beforeSend: function() {
				$('#ajaxLoader').show();
			},
			success: function(data) {
				$('#ajaxLoader').hide();
				$('#dashboardtabledata').html(data.html);
				if(salesIdChecked > 0 || serviceIdChecked > 0 || partsIdChecked > 0 || productIdChecked > 0){
					
					barChartFunction(salesIdChecked,serviceIdChecked,partsIdChecked,productIdChecked,complaint_type,product_type,segment_type,region_type);
				}				
								
			}
		});
		
	});
	
	function ajaxComplaintSearch(complaint_type, product_type, segment_type, region_type, datefilter,midcontentheader){
	
		var complaintId=complaintValue=productId=productValue=segmentId=segmentValue=regionId=regionValue=fromdate=todate='';
		complaintIdnew=productIdnew=segmentIdnew=regionIdnew='';
		var salesId = $('#complaint_type1').val();
		var serviceId = $('#complaint_type2').val();
		var partsId = $('#complaint_type3').val();
		var productIdleft = $('#complaint_type4').val();
		
		var salesIdChecked = $('#complaint_type1:checkbox:checked').length ;		
		var serviceIdChecked = $('#complaint_type2:checkbox:checked').length ;
		var partsIdChecked = $('#complaint_type3:checkbox:checked').length ;
		var productIdChecked = $('#complaint_type4:checkbox:checked').length ;
		
		@php
			$complaint_type = Session::get('complaint_type_id');
			$role = Session::get('role');
			$user_type_id = Session::get('user_type_id');
			$product = Session::get('product');
		@endphp
		var role = '{{$role}}';
		var user_type_id = '{{$user_type_id}}';
		var product = '{{$product}}';
		var complaint_type =product_type= '';
		if(role == '29' || role == '30' || user_type_id =='2'){
			complaint_type='1,2,3,4';
			 product_type ='1,2';
		}else{			
			complaint_type = '{{$complaint_type}}';
			product_type = '{{$product}}';
		}
		var region_type='1,2,3,4,5';
		
		var segment_type='1,2,3,8,9,5,6,7,10,11';
		
		
		if (complaint_type!='') {
			$('.complaintCheckbox:checked').each(function () {
				var values = $(this).val();
				complaintId += values+',';
			});
			complaintIdnew = complaintId.substring(0, complaintId .length - 1);
			$('#complaintValue').val(complaintIdnew);
			var complaintValue = $('#complaintValue').val();
			
		}
		
		if (product_type!='') {
			$('.productCheckbox:checked').each(function () {
				var values = $(this).val();
				productId += values+',';
			});
			
			productIdnew = productId.substring(0, productId .length - 1);
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
			segmentIdnew = segmentId.substring(0, segmentId .length - 1);			
			$('#segmentValue').val(segmentIdnew);
			var segmentValue = $('#segmentValue').val();
		}
		if (region_type!='') {
			$('.regionCheckbox:checked').each(function () {
				var values = $(this).val();
				regionId += values+',';
			});
			regionIdnew = regionId.substring(0, regionId .length - 1);				
			$('#regionValue').val(regionIdnew);
			var regionValue = $('#regionValue').val();
		}
		
		
		$.ajax({  url: '{{url("ajax-bar-table-search")}}',
		data: { 'complaintId':complaintIdnew,'productIdnew':productIdnew,'segmentValue':segmentIdnew,'regionIdnew':regionIdnew},
			beforeSend: function() {
				$('#ajaxLoader').show();
			},
			success: function(data) {
				$('#ajaxLoader').hide();
				$('#dashboardtabledata').html(data.html);
				if(salesIdChecked > 0 || serviceIdChecked > 0 || partsIdChecked > 0 || productIdChecked > 0){
					
					barChartFunction(salesIdChecked,serviceIdChecked,partsIdChecked,productIdChecked,complaintIdnew,productIdnew,segmentIdnew,regionIdnew);
				}				
								
			}
		});
		
	}
	
	function barChartFunction(salesIdChecked,serviceIdChecked,partsIdChecked,productIdChecked,complaintValue,productValue,segmentValue,regionValue)
	{ 
	
		var Sales_1_5= Sales_6_15=Sales_above_15=Service_1_5=Service_6_15=Service_above_15='';	
		var Parts_1_5= Parts_6_15=Parts_above_15=Product_1_30=Product_30_45=Product_above_45='';
		$.ajax({  url: '{{url("ajax-bar-search")}}',
		data: { 'salesIdChecked':salesIdChecked,'serviceIdChecked':serviceIdChecked,'partsIdChecked':partsIdChecked,'productIdChecked':productIdChecked,'complaintValue':complaintValue,'productValue':productValue,'segmentValue':segmentValue,'regionIdnew':regionValue},
			beforeSend: function() {
				$('#ajaxLoader').show();
			},
			success: function(data){
				$('#ajaxLoader').hide();
				var result = data.split(',');	
				//var result = '0,0,0,0,0,0,0,0,0,0,0,1';
				//result = result.split(',');					
				Sales_1_5 =result[0];
				Sales_6_15 =result[1];
				Sales_above_15 =result[2];
				Service_1_5 =result[3];
				Service_6_15 =result[4];
				Service_above_15 =result[5];
				Parts_1_5 =result[6];
				Parts_6_15 =result[7];
				Parts_above_15 =result[8];
				Product_1_30 =result[9];
				Product_30_45 =result[10];
				Product_above_45 =result[11];
				google.load("visualization", '1.1', { packages: ['corechart'] });
				google.setOnLoadCallback(drawChart);
				function drawChart() {					
				    var general_complaint = google.visualization.arrayToDataTable([
				      ['', '1 to 5', '6 To 15', 'above15'],
				      ['Sales', parseInt(Sales_1_5), parseInt(Sales_6_15), parseInt(Sales_above_15)],
				      ['Service', parseInt(Service_1_5), parseInt(Service_6_15), parseInt(Service_above_15)],
				      ['Parts', parseInt(Parts_1_5), parseInt(Parts_6_15), parseInt(Parts_above_15)]
				    ]); 
				    /*var regionBar = google.visualization.arrayToDataTable([
				      ['', '1 to 5', '6 To 15', 'above15'],
				      ['Sales', 8, 2, 0],
				      ['Service', 2, 0, 2],
				      ['Parts', 4, 1, 0]
				    ]);*/
				    var product_complaint = google.visualization.arrayToDataTable([
				      ['', '1 to 30', '30 To 45', 'above45'],
				      ['Products', parseInt(Product_1_30), parseInt(Product_30_45), parseInt(Product_above_45)]
				    ]);

				    var options = {
				        width: '100%',
				        height: '100%',
				        colors: ['#78b833','#F7D302','#c4001a'],
				        legend: { position: 'top', maxLines: 3 },
				        bar: { groupWidth: '20%' },
				        isStacked: true,
				    };
				    var options1 = {
				        width: '100%',
				        height: '100%',
				        colors: ['#78b833','#F7D302','#c4001a'],
				        legend: { position: 'top', maxLines: 3 },
				        bar: { groupWidth: '7%' },
				        isStacked: true,
				    };

				    var view = new google.visualization.DataView(general_complaint);
				    view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);
				    var chart = new google.visualization.ColumnChart(document.getElementById('general_complaint'));    
				    google.visualization.events.addListener(chart, 'select', function () {
				        highlightBar(chart, options, view);
				    });
				    chart.draw(general_complaint, options);
				    
				   /* var view1 = new google.visualization.DataView(regionBar);
				    var chart1 = new google.visualization.ColumnChart(document.getElementById('regionBar'));    
				    google.visualization.events.addListener(chart1, 'select', function () {
				        highlightBar(chart1, options, view1);
				    });
				    chart1.draw(regionBar, options);*/
				    
				    var view2 = new google.visualization.DataView(product_complaint);
				    var chart2 = new google.visualization.ColumnChart(document.getElementById('product_complaint'));    
				    google.visualization.events.addListener(chart2, 'select', function () {
				        highlightBar(chart2, options1, view2);
				    });
				    chart2.draw(product_complaint, options1);
				}


				function highlightBar(chart, options, view) {
				    var selection = chart.getSelection();
				    if (selection.length) {
				        var row = selection[0].row;
				        var column = selection[0].column;


				        //1.insert style role column to highlight selected column 
				        var styleRole = {
				            type: 'string',
				            role: 'style',
				            calc: function (dt, i) {
				                return (i == row) ? 'stroke-color: #000000; stroke-width: 2' : null;
				            }
				        };
				        var indexes = [0, 1, 2, 3];
				        var styleColumn = findStyleRoleColumn(view)
				        if (styleColumn != -1 && column > styleColumn)
				            indexes.splice(column, 0, styleRole);
				        else
				            indexes.splice(column + 1, 0, styleRole);
				        view.setColumns(indexes);
				        //2.redraw the chart
				        chart.draw(view, options);
				    }
				}

				function findStyleRoleColumn(view) {
				    for (var i = 0; i < view.getNumberOfColumns() ; i++) {
				        if (view.getColumnRole(i) == "style") {
				            return i;
				        }
				    }
				    return -1;
				}	
			}
		});	
		

	}
	
	
 </script>
<div class="container-fluid mobcss" style="background: #e5e5e5;">
	<div class="card" style="background: #e5e5e5;">
		<div class="card-body" style="background: #e5e5e5;">
			<input type="hidden" name="complaintValue" id="complaintValue">
			<input type="hidden" name="productValue" id="productValue">
<input type="hidden" name="segmentValue" id="segmentValue">
<input type="hidden" name="regionValue" id="regionValue">
			<div class="row">
				<div class="col-lg-2" style="background: #fff;">
					<a class="nav-link" href="{{url('cms')}}"><p style="background: #111;color: #fff;" class="sidenav_p">Dashboard</p></a>
					<a href="{{url('open-complaint')}}" class="sidenav_a" style="color: #000;background: #ccc;">Open Complaints</a>
					<a href="{{url('closed-complaint')}}" class="sidenav_a">Closed Complaints</a>
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
								
								<label><input type="checkbox" name="complaint_type[]" class="complaintCheckbox" id="complaint_type{{$i}}" value="{{$row->id}}" onchange="ajaxComplaintSearch(this.value,'','','','','')">{{$row->complaint_type}}</label>
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
								
								<label><input type="checkbox" name="product[]" class="productCheckbox" id="productTruck" value="1" onclick="ajaxComplaintSearch(complaintValue.value,this.value,'','','','')"  >Truck</label>
							</div>
							<div class="checkbox">
								<label><input type="checkbox" name="product[]" class="productCheckbox" id="productBus" value="2" onclick="ajaxComplaintSearch(complaintValue.value,this.value,'','','','')"  >Bus</label>
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
											<label><input type="checkbox" name="segment[]" class="segmentChechbox" id="segment_truck{{$row->id}}" value="{{$row->id}}" onchange="ajaxComplaintSearch(complaintValue.value,productValue.value,this.value,'','','')">{{$row->segment}}</label>
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
											<label><input type="checkbox" name="segment[]" class="segmentChechbox" id="segment_bus{{$row->id}}" value="{{$row->id}}" onchange="ajaxComplaintSearch(complaintValue.value,productValue.value,this.value,'','','')">{{$row->segment}}</label>
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
								<label><input type="checkbox" name="region[]" id="region{{$row->id}}" value="{{$row->id}}" class="regionCheckbox" onchange="ajaxComplaintSearch(complaintValue.value,productValue.value,segmentValue.value,this.value,'','')">{{$row->region}}</label>
							</div>
							@endforeach
							@endisset
						</div>
					</p>
					<div style="clear: both"></div>
					<p class="sub_p">
						Area / Dealer
						<span style="float: right;">
							
						</span>
						<select id="dealerId" style="width: 100%;border-radius: 12px;border: 1px solid #ccc;" onchange="ajaxDealerSearch(complaintValue.value,productValue.value,segmentValue.value,regionValue.value,'click',dealerId.value)">
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
					@php
					$current_quarter = ceil(date('n') / 3);
					$toDate = date('Y-m-d', strtotime(date('Y') . '-' . (($current_quarter * 3) - 2) . '-1'));
					$fromDate = date('Y-m-d', strtotime(date('Y') . '-' . (($current_quarter * 3)) . '-1'));
					$currentQuarterDate = "'".$fromDate.'~'.$toDate."'";
					$midcontentHeader = 'click';
					$sixmonth = 180;
					$oneYear =365;
					$twoYear =730;
					$currentDate = date('Y-m-d',strtotime( 'now' ));
					$twoQuaterDate = date('Y-m-d',strtotime( "-$sixmonth days" ));
					$fromtwoQuaterDate = "'".$currentDate.'~'.$twoQuaterDate."'";
					$oneYearDate = date('Y-m-d',strtotime( "-$oneYear days" ));
					$fromOneYearDate = "'".$currentDate.'~'.$oneYearDate."'";
					$twoYearDate = date('Y-m-d',strtotime( "-$twoYear days" ));
					$fromTwoYearDate = "'".$currentDate.'~'.$twoYearDate."'";
					@endphp
					<div class="row">
						<div class="col-lg-6">
							<div style="background: #fff;padding: 8px;border-radius: 4px;">
								<span style="font-size: 16px;">Ageing - General Complaints</span>
								<div style="border-bottom: 1px solid #ccc;"></div>	
								<div id="general_complaint"></div>
							</div>
						</div>
						<!--{{--<div class="col-lg-4">
							<div style="background: #fff;padding: 8px;border-radius: 4px;">
								<span>Agieng - Region</span>
								<a  activeClass('two');" id="twoyear" class="middlecontent">West</a>
								<a  activeClass('two');" id="twoyear" class="middlecontent">South</a>
								<a  activeClass('one');" id="oneyear"  class="middlecontent">North</a>
								<a  activeClass('half');" id="half" class="middlecontent">East</a>
								<a  activeClass('quater');" id="quater" class="middlecontent">Central</a>
								<div id="regionBar"></div>
							</div>
						</div>--}}-->
						<div class="col-lg-6">
							<div style="background: #fff;padding: 8px;border-radius: 4px;">
								<span style="font-size: 16px;">Ageing - Product Complaints</span>
								<div style="border-bottom: 1px solid #ccc;"></div>	
								<div id="product_complaint"></div>
							</div>
						</div>						
					</div>
					
					<div class="row">
						<div class="col-lg-12">
							<div id="dashboardtabledata"></div>	
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
	
	font-weight: 700 !important;
	
}
	.horizontal-menu.fixed-on-scroll + .page-body-wrapper {
	padding-top: 4rem;
	background: #e5e5e5;
}
.middlecontent{
		float: right;font-size: 10px;margin-right: 10px;text-decoration: underline;
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