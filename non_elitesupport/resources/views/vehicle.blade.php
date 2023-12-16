@extends("layouts.masterlayout")
@section('title','Vehicle Master')
@section('bodycontent')
{{-- <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> --}}
<style>
	// Pager pagination
.my-active span{
	background: linear-gradient(to bottom,#fff 0,#dcdcdc 100%) !important;
	color: white !important;
	border-color: #5cb85c !important;
			
}

.pager {
  padding-left: 0;
  margin: 20px 0;
  text-align: right;
  list-style: none;
}
.pager li {
  display: inline;
}
.pager li > a,
.pager li > span {
  display: inline-block;
  padding: 5px 14px;
  background-color: #fff;
  border: 1px solid #ddd;
  /* border-radius: 15px; */
}
.pager li > a:hover,
.pager li > a:focus {
  text-decoration: none;
  background-color: #eee;
}
.pager .next > a,
.pager .next > span {
  float: right;
}
.pager .previous > a,
.pager .previous > span {
  float: left;
}
.pager .disabled > a,
.pager .disabled > a:hover,
.pager .disabled > a:focus,
.pager .disabled > span {
  color: #777;
  cursor: not-allowed;
  background-color: #fff;
}
.pager {
  padding-left: 0;
  margin: @line-height-computed 0;
  list-style: none;
  text-align: :right;
  &:extend(.clearfix all);
  li {
    display: inline;
    > a,
    > span {
      display: inline-block;
      padding: 5px 14px;
      background-color: @pager-bg;
      border: 1px solid @pager-border;
      border-radius: @pager-border-radius;
    }

    > a:hover,
    > a:focus {
      text-decoration: none;
      background-color: @pager-hover-bg;
    }
  }

  .next {
    > a,
    > span {
      float: right;
    }
  }

  .previous {
    > a,
    > span {
      float: left;
    }
  }

  .disabled {
    > a,
    > a:hover,
    > a:focus,
    > span {
      color: @pager-disabled-color;
      background-color: @pager-bg;
      cursor: @cursor-disabled;
    }
  }
  table.dataTable tbody td {
		word-break: break-word;
		vertical-align: top;
	}
}
</style>
	<div class="content-wrapper mobcss">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Manage Vehicle</h4>
                <div class="row">
                    <div class="col-md-12">
                    	<div id="insertvehicle" >
							<form name="myForm" method="post" enctype="multipart/form-data" action="{{url('store-vehicle')}}" onsubmit="return vehicleValidation()">
	                        	<input type="hidden" name="_token" value="{{csrf_token()}}">
	                        	<input type="hidden" name="dataid" id="dataid">
	                            <div class="row">
									<div class="form-group col-md-3">
	                                    <label for="vehicle_model">Vehicle Model</label> <span style="color: red;">*</span>
										<input type="text" name="vehicle_model" id="vehicle_model" class="form-control" placeholder="Vehicle Model" required />
										
	                                </div> 
									<div class="form-group col-md-3">
	                                    <label for="reg_number">Registration Number</label> <span style="color: red;">*</span>
	                                    <input type="text" name="reg_number" id="reg_number" class="form-control" placeholder="Registration Number" required /> 
	                                </div>
									<div class="form-group col-md-3">
	                                    <label for="chassis_number">Owner Name</label> <span style="color: red;">*</span>
										<select name="ownerId" id="ownerId" class="form-control" required>
											<option value="">--Select</option>
											@foreach ($ownerData as $row)
												<option value="{{$row->id}}">{{$row->owner_name}}</option>
											@endforeach
										</select> 
	                                </div> 
									<div class="form-group col-md-3">
	                                    <label for="chassis_number">Chassis Number</label> <span style="color: red;">*</span>
	                                    <input type="text" name="chassis_number" id="chassis_number" class="form-control" placeholder="Chassis Number" required /> 
	                                </div> 
									<div class="form-group col-md-3">
	                                    <label for="engine_number">Engine Number</label> <span style="color: red;">*</span>
	                                    <input type="text" name="engine_number" id="engine_number" class="form-control" placeholder="Engine Number" required /> 
	                                </div> 
									<div class="form-group col-md-3">
	                                    <label for="vehicle_segment">Vehicle Segment</label> <span style="color: red;">*</span>
	                                    <input type="text" name="vehicle_segment" id="vehicle_segment" class="form-control" placeholder="Vehicle Segment" required /> 
	                                </div> 
									<div class="form-group col-md-3">
	                                    <label for="purchase_date">Purchase Date</label> <span style="color: red;">*</span>
	                                    <input type="text" name="purchase_date" id="purchase_date" autocomplete="off" class="form-control" value="@isset($purchase_date){{$purchase_date}} @endisset"  placeholder="Purchase Date" />
	                                </div> 
	                                <div class="form-group col-md-3">
	                                    <label for="add_blue_use">Add Blue Use</label> <span style="color: red;">*</span>
										<select name="add_blue_use" id="add_blue_use" class="form-control">
											<option value="NA">--Select--</option>
											<option value="Yes">Yes</option>
											<option value="No">No</option>
										</select> 
	                                </div> 
	                                <div class="form-group col-md-3">
	                                    <label for="engine_emmission_type">Engine Emmission Type</label> <span style="color: red;">*</span>
										<select name="engine_emmission_type" id="engine_emmission_type"  class="form-control" required>
											<option value="">--Select--</option>
											<option value="BS6">BS-6</option>
											<option value="Non BS6">Non BS-6</option>
										</select>
	                                    {{-- <input type="text" name="engine_emmission_type" id="engine_emmission_type" class="form-control" placeholder="Engine Emmission Type" required />  --}}
	                                </div> 
	                                <div class="form-group col-md-3" id="td_Status">
										<label for="Name">Status</label> <span style="color: red;">*</span>
										<select name="flag" id="flag" tabindex="1" class="form-control">
											<optgroup>
												<option Value="NA">--select--</option>
												<option Value="1">Active</option>
												<option Value="0">Inactive</option>
												
											</optgroup>
										</select>
										<span id="flag_error" style="color:red"></span>
									</div>
	                            </div>
								@if(Auth::user()->role== '29' || Auth::user()->role == '30')
	                            <div class="box-footer">
	                                <span class="pull-right">
									<button type="button" onclick="reloadPage();" class="btn-secondary">Cancel</button>
	                                <input type="submit"name="submit" id="submit" value="Submit" class="btn-secondary">
	                                </span>
	                            </div>
								@endif
	                        </form>  
						</div> 
						
                        <div class="clear"></div>
                        <hr>                       
                        <div class="table-responsive">
							<div class="row">
								<div class="col-md-5"><a class="btn btn-warning" href="{{ route('export') }}" style="background-color: #e9e9e9;border: 1px solid #999;border-radius: 2px;">Excel</a></div>
								<div class="col-md-4"></div>
								<div class="col-md-3 col-md-offset-3">
								<input type="text" name="SearchVicle" id="SearchVicle" class="form-control" placeholder="Search" autocomplete="off"/>
								</div>
							</div>
                            <table id="order-listing1" class="table">
                                <thead>
                                    <tr>
										<th>Actions</th>
										<th>Owner Name</th>
										<th>Registration Number</th>
										<th>Chassis Number</th>
										{{-- <th>Created By</th> --}}
										<th>Created Date</th>
										{{-- <th>Updated BY</th> --}}
										<th>Updated Date</th>
										<th>Status</th> 
										<th  style="display: none;">Engine Number</th>
										<th  style="display: none;">Vehicle Segment</th>
										<th  style="display: none;">Purchase Date</th>
										<th  style="display: none;">Add Blue Use</th>
										<th  style="display: none;">Engine Emmission Type</th>
										<th  style="display: none;">owner id</th>
										<th  style="display: none;">vehicle model</th>
                                    </tr>
                                </thead>
								<div >
                                <tbody id="tableDisabled">
                                @isset($rowData)
                              
                                @php $count=1; @endphp
									@foreach($rowData as $row)
                                    <tr>
										<td>
											<i class="fa fa-pencil-square-o" aria-hidden="true" id="{{$row->id}}" data-position="left" data-tooltip="Edit" onclick="javascript:return editvehicle(this);" style="cursor: pointer;"></i>
											<a href="{{route('vehicle_delete.vehicleDelete', ['id' => $row->id])}}" onclick="return confirm('Do you want to delete?')">
												<i class="fa fa-trash-o" aria-hidden="true" style="cursor: pointer;"></i></a>
										</td>
                                        <td class="">{{$row->owner_name}}</td>
                                        <td class="cls_reg_number">{{$row->reg_number}}</td>
										<td class="cls_chassis_number">{{$row->chassis_number}}</td>
                                        {{-- <td>{{$row->created_by !=''?$row->created_by:''}}</td> --}}
 										<td>{{$row->created_at !=''?date('d-m-Y H:i:s',strtotime($row->created_at)):''}}</td>
 										{{-- <td>{{$row->updated_by !=''?$row->updated_by:''}}</td> --}}
 										<td>{{$row->updated_at !=''?date('d-m-Y H:i:s',strtotime($row->updated_at)):''}}</td>
										<td class="cls_flag" >@if($row->flag=='1')
                                        	<label class='badge badge-success'>Active</label>
                                        	@else
                                        	<label class="badge badge-danger">Inactive</label>
                                        	@endif
                                        </td>  
                                        <td class="cls_engine_number" style="display: none;">{{$row->engine_number}}</td>
                                        <td class="cls_vehicle_segment" style="display: none;">{{$row->vehicle_segment}}</td>
                                        <td class="cls_purchase_date" style="display: none;">{{$row->purchase_date}}</td>
                                        <td class="cls_add_blue_use" style="display: none;">{{$row->add_blue_use}}</td>
                                        <td class="cls_engine_emmission_type" style="display: none;">{{$row->engine_emmission_type}}</td>
                                        <td class="cls_ownerId" style="display: none;">{{$row->ownerId}}</td>
                                        <td class="cls_vehicle_model" style="display: none;">{{$row->vehicle_model}}</td>
                                        
										
                                    </tr>
                                     @php $count++; @endphp	
                                    @endforeach
									
									
									
                                @endisset

                                </tbody>
								</div>
								<div>
									<tbody id="tableEnabled"></tbody>
								</div>
								
                            </table>
							{{-- <div class="col-md-12">
								<table class="table table-bordered vehicle_datatable">
									<thead>
										<tr>
											<th class="d-none">id</th>	
											<th>Actions</th>
											<th>Owner Name</th>
											<th>Registration Number</th>
											<th>Chassis Number</th>
											<th>Created By</th>
											<th>Created Date</th>
											<th>Updated BY</th>
											<th>Updated Date</th>
											<th>Status</th> 
											<th class="d-none">Engine Number</th>
											<th class="d-none">Vehicle Segment</th>
											<th class="d-none">Purchase Date</th>
											<th class="d-none">Add Blue Use</th>
											<th class="d-none">Engine Emmission Type</th>
											<th class="d-none">owner id</th>
											<th class="d-none">vehicle model</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div> --}}
                        </div>
						{{ $rowData->links('pagination.custom') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
 <script>
	 $(document).ready(function () {
		/* Ajax Datatable */
		 var table = $('.vehicle_datatable').DataTable({
			dom: 'Bfrtip',
			lengthMenu:[100,'All'],
			processing: true,
			serverSide: false,
			ajax: "{{ route('vehicle') }}",
			method: "GET",
			
				columns: [
					{data: 'id', name: 'id',class: 'd-none'},
					{data: 'action', name: 'action', orderable: false, searchable: false},
					{data: 'owner_name', name: 'owner_name'},
					{data: 'reg_number', name: 'reg_number', class: 'cls_reg_number'},
					{data: 'chassis_number', name: 'chassis_number', class: 'cls_chassis_number'},
					{data: 'created_by', name: 'created_by'},
					{data: 'created_at', name: 'created_at'},
					{data: 'updated_by', name: 'updated_by'},
					{data: 'updated_at', name: 'updated_at'},
					{data: 'flbtn', name: 'flbtn', class:'cls_flag'},
					{data: 'engine_number', name: 'engine_number', class: 'cls_engine_number d-none'},
					{data: 'vehicle_segment', name: 'vehicle_segment', class: 'cls_vehicle_segment d-none'},
					{data: 'purchase_date', name: 'purchase_date', class: 'cls_purchase_date d-none'},
					{data: 'add_blue_use', name: 'add_blue_use', class: 'cls_add_blue_use d-none'},
					{data: 'engine_emmission_type', name: 'engine_emmission_type', class: 'cls_engine_emmission_type d-none'},
					{data: 'ownerId', name: 'ownerId', class: 'cls_ownerId d-none'},
					{data: 'vehicle_model', name: 'vehicle_model', class: 'cls_vehicle_model d-none'}
				],
				buttons: [
					{ 
						extend: 'excelHtml5', 
						exportOptions: { 
							//columns: [ 2,10,11,12,13,14,15,17 ] ,
							modifier: { search: 'none',page: 'all' }						
						}
					}
					
				]
			}); 
		
		/* Ajax Datatable */
		
			$("#SearchVicle").keyup(function(){
			var inptData = $(this).val();
			$.ajax({
				url: '{{url("ajax-vehicle-report-data")}}',
				data: {'keyword':inptData},
				success: function(data){
					console.log(data);
					$("#tableDisabled").hide();
					$("#tableEnabled").show();
					$("#tableEnabled").html(data);
					$("#SearchVicle").css("background","#FFF");
				}
			});
		});
		$('#purchase_date').datetimepicker({ format:'Y-m-d',timepicker:false});
		$('#order-listing1').DataTable({
				dom: 'Bfrtip',
				"pageLength": 50,
				"paging":   false,
				"searching": false,
				"language": {
					"paginate": {
						"previous": "<",
						"next": ">"
					}
				}
		});
	});

	function editvehicle(el){
		//alert($(el).parents('td').parents('tr').find('.cls_model').text());
		var vehicle_model = $(el).parents('td').parents('tr').find('.cls_vehicle_model').text();
		var reg_number = $(el).parents('td').parents('tr').find('.cls_reg_number').text();
		var chassis_number = $(el).parents('td').parents('tr').find('.cls_chassis_number').text();
		var purchase_date = $(el).parents('td').parents('tr').find('.cls_purchase_date').text();
		var engine_number = $(el).parents('td').parents('tr').find('.cls_engine_number').text();
		var vehicle_segment = $(el).parents('td').parents('tr').find('.cls_vehicle_segment').text();
		var add_blue_use = $(el).parents('td').parents('tr').find('.cls_add_blue_use').text();
		var engine_emmission_type = $(el).parents('td').parents('tr').find('.cls_engine_emmission_type').text();
		var ownerId = $(el).parents('td').parents('tr').find('.cls_ownerId').text();
		$('#td_Status').show();
		var flg=$(el).parents('td').parents('tr').find('.cls_flag').text();
		if(flg.trim()=='Active'){
			flg='1';
		}else{
			flg='0';
		}
		$('#flag').val(flg);
		//alert(segmentId);
		$('#vehicle_model').val(vehicle_model);
		//funcVehicleModel(vehicle_model);
		$('#reg_number').val(reg_number);
		$('#chassis_number').val(chassis_number);
		$('#purchase_date').val(purchase_date);
		$('#engine_number').val(engine_number);
		$('#vehicle_segment').val(vehicle_segment);
		$('#add_blue_use').val(add_blue_use);
		$('#engine_emmission_type').val(engine_emmission_type);
		$('#ownerId').val(ownerId);
		/* $('#vehicle_segment').attr('disabled','disabled');
		$('#add_blue_use').attr('disabled','disabled');
		$('#engine_emmission_type').attr('disabled','disabled'); */

		
		$('#dataid').val(el.id);
		fn_product_change(vehicleId,segmentId,'','');
	}	
	function funcVehicleModel(param){
		$.ajax({
			url: '{{url("get-vehicle-models")}}',
			data: {'id': param},
			success: function(result){
				var res = result.split('~~');
				var vehicle_segment = res[0];
				var add_blue_use = res[1];
				var engine_emmission_type = res[2];
				$('#vehicle_segment').val(vehicle_segment);
				$('#add_blue_use').val(add_blue_use);
				$('#engine_emmission_type').val(engine_emmission_type);
				$('#vehicle_segment').attr('disabled','disabled');
				$('#add_blue_use').attr('disabled','disabled');
				$('#engine_emmission_type').attr('disabled','disabled');

			}
		})
	}
 </script>

@endsection
