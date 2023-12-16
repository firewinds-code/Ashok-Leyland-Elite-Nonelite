@extends("layouts.masterlayout")
@section('title','Area')
@section('bodycontent')
	<div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Manage Area</h4>
                <div class="row">
                    <div class="col-md-12">
                    	
						<form name="myForm" method="post" enctype="multipart/form-data" action="{{url('store-city')}}">
                        	<input type="hidden" name="_token" value="{{csrf_token()}}">
                            <input type="hidden" name="DataID" id="DataID">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="region">Zone</label> <span style="color: red;">*</span>
                                    <select name="region_id" id="region_id" class="form-control" onchange="fn_zone_change(this.value,'')"  required >
                                        <option value="">--Select--</option>
                                        @isset($regionData)
                                            @foreach ($regionData as $item)
                                                <option value="{{$item->id}}">{{$item->region}}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="state">Region</label> <span style="color: red;">*</span>
                                    <select name="state_id" id="state_id"  class="form-control" required></select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="city">Area</label> <span style="color: red;">*</span>
                                    <input type="text" name="city" id="city"  class="form-control" placeholder="Area" required>
                                </div>
                            </div>

                            @if(Auth::user()->role  == '29' || Auth::user()->role  == '30')
                            <div class="box-footer">
                                <span class="pull-right">
									<button type="button" onclick="reloadPage();" class="btn-secondary">Cancel</button>	
                                <input type="submit"name="submit" id="submit" value="Submit" class="btn-secondary">
                                </span>
                            </div>
                            @endif
                        </form>  
						
                        <div class="clear"></div>
                        <hr>
                        <div class="table-responsive">
                            <table id="order-listing" class="table">
                                <thead>
                                    <tr>
										<th>Actions</th>
										<th>Zone</th>
										<th>Region</th>
										<th>Area</th>
										<th style="display: none">region id</th>
										<th style="display: none">state id</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @isset($rowData)
                                @php $count=1; @endphp
									@foreach($rowData as $row)
                                    <tr>
										<td>
											<i class="fa fa-pencil-square-o" aria-hidden="true" id="{{$row->id}}" data-position="left" data-tooltip="Edit" onclick="javascript:return editCity(this);" style="cursor: pointer;"></i>
											<a href="{{route('city_delete.cityDelete', ['id' => $row->id])}}" onclick="return confirm('Do you want to delete?')">
												<i class="fa fa-trash-o" aria-hidden="true" style="cursor: pointer;"></i></a>
										</td>
                                        <td>{{$row->region}}</td>
                                        <td>{{$row->state}}</td>
                                        <td class="cls_city">{{$row->city}}</td>
                                        <td class="cls_region_id" style="display: none">{{$row->region_id}}</td>
                                        <td class="cls_state_id" style="display: none">{{$row->state_id}}</td>
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
       function editCity(el){
            var state_id = $(el).parents('td').parents('tr').find('.cls_state_id').text();
            var region_id = $(el).parents('td').parents('tr').find('.cls_region_id').text();
            var city = $(el).parents('td').parents('tr').find('.cls_city').text();
            fn_zone_change(region_id,state_id);
            $('#region_id').val(region_id);
            $('#state_id').val(state_id);
            $('#city').val(city);
            $('#DataID').val(el.id);
       }
       function fn_zone_change(zoneId,ell){
		$.ajax({ url: '{{url("get-zone-change")}}',
				data: { 'zoneId':zoneId},
				success: function(response){
                    
					var Result = response.split(",");var str = '';
					Result.pop();
					str += "<option value='NA'>--Select--</option>";
					for (item1 in Result) {
					var Result2 = Result[item1].split("~~");
					if (ell!='') {
						if ( Result2[0] == ell ) {
								str += "<option value='" + Result2[0] + "' selected>" + Result2[1] + "</option>";
							} 
							else
							{
								str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
							}
					}else{
						str += "<option value='" + Result2[0]+ "'>" + Result2[1] + "</option>";
					}
				}
				document.getElementById('state_id').innerHTML = str;
				}
		});
	}
	
   </script>

@endsection
