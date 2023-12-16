@extends("layouts.masterlayout")
@section('title','Area')
@section('bodycontent')
	<div class="content-wrapper mobcss">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Manage Location</h4>
                <div class="row">
                    <div class="col-md-12">
                    	<div id="insertvehicle" >
							<form name="myForm" method="post" enctype="multipart/form-data" action="{{url('store-area')}}" onsubmit="return areaValidation()">
	                        	<input type="hidden" name="_token" value="{{csrf_token()}}">
	                        	<input  type="hidden" name="dataid" id="dataid"/>                        
	                            <div class="row">
									
									{{--<div class="form-group col-md-3"> 
										<label for="Name">Site Name</label> <span style="color: red;">*</span>
										<input type="text" name="site_name" id="site_name" class="form-control" placeholder="Site Name">
										<span id="site_name_error" style="color:red"></span>
									</div>--}}
									<div class="form-group col-md-3">
									<label for="Name">Region</label> <span style="color: red;">*</span>
										<select name="zone" id="zone" class="form-control" onchange="fn_Zone_change(this.value,'')">
											<optgroup>
												<option value="NA">--Select--</option>
													
												</optgroup>
										</select>
										<span id="zone_error" style="color:red"></span>
									</div>                                 
	                                <div class="form-group col-md-3">
									<label for="Name">State</label> <span style="color: red;">*</span>
	                                    <select name="state" id="state" class="form-control" onchange="fn_State_change(zone.value,this.value,'');">
	                                    	<optgroup><option value="NA">--Select--</option></optgroup>
	                                    </select>
	                                    <span id="state_error" style="color:red"></span> 
	                                </div>
									<div class="form-group col-md-3">
										<label for="Name">Location</label>
										<span style="color: red;">*</span>
										{{--<select name="City"  id="City" class="form-control">
											<optgroup>
												<option value="NA">--Select--</option></optgroup>
										</select>--}}
								<input type="text" name="City" id="City"  placeholder="Search" onkeyup="citySearch(zone.value,state.value,this.value)" class="form-control" autocomplete="off">
									<select id="CityAjax" name="CityAjax" style="display: none;" class="form-control"></select>
										<span id="city_error" style="color:red"></span>
									</div>
									{{--<div class="form-group col-md-3">
										<label for="Name">Site Code</label>
										<span style="color: red">*</span>
										<input type="text" name="site_code" id="site_code" class="form-control" placeholder="Site Code">
										<span id="site_code_error" style="color:red"></span>
									</div>--}}
	                                <div class="form-group col-md-3" id="td_Status" style="display: none;">
	                                    <label for="Name">Status</label>                                    
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
										<th>Actions</th>																			
										<th>Region</th>
										<th>State</th>
										<th>Site Name</th>
										 										                                     
										<th>Status</th>										
										<th style="display: none;">Region_id</th>
										<th style="display: none;">State_id</th>
										<th style="display: none;">City_ID</th>
										
                                    </tr>
                                </thead>
                                <tbody>
                                @isset($rowData)
                                @php $count=1; @endphp							
									@foreach($rowData as $row)
                                    <tr>
										<td>
											<i class="fa fa-pencil-square-o" aria-hidden="true" id="{{$row->Location_ID}}" data-position="left" data-tooltip="Edit" onclick="javascript:return editarea(this);" style="cursor: pointer;"></i>
											{{-- <a href="{{route('area_delete.areaDelete', ['id' => $row->Location_ID])}}" onclick="return confirm('Do you want to delete?')">
												<i class="fa fa-trash-o" aria-hidden="true" style="cursor: pointer;"></i>
											</a> --}}
										</td>								
										<td class="cls_zone">{{$row->region}}</td>
                                        <td class="cls_state">{{$row->state}}</td>
										<td class="cls_city">{{$row->City}}</td>
										
                                        <td class="cls_flag">@if($row->flag=='1')
                                        	<label class='badge badge-success'>Active</label>
                                        	@else
                                        	<label class="badge badge-danger">Inactive</label>
                                        	@endif
                                        </td>
                                        
                                        <td class="cls_regionId" style="display: none;">{{$row->Region_id}}</td>
                                        <td class="cls_stateId" style="display: none;">{{$row->State_id}}</td>
                                        <td class="cls_cityId" style="display: none;">{{$row->City_ID}}</td>
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
	function citySearch (zoneId, stateid, str)
	{
		
		$.ajax({			
			url:'{{url("search-city")}}',
			data:{"zoneId":zoneId,"stateId":stateid,"str":str},
			success:function(result){
				$("#CityAjax").show();
				$("#CityAjax").html(result);
				//$("#City").val("");
			}
		});		
	}

 </script>  

@endsection
