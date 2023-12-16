@extends("layouts.masterlayout")
@section('title','Customer Contact')
@section('bodycontent')
	<div class="content-wrapper mobcss">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Manage Customer Contact</h4>
                <div class="row">
                    <div class="col-md-12">
                    	<div id="insertvehicle" >
							<form name="myForm" method="post" enctype="multipart/form-data" action="{{url('store-owner-contact')}}" >
	                        	<input type="hidden" name="_token" value="{{csrf_token()}}">
	                        	<input  type="hidden" name="dataid" id="dataid"/>
	                            <div class="row">
									{{-- <div class="form-group col-md-3">
										<label for="vehicle_id">Registration Number</label> <span style="color: red;">*</span>
										<select name="vehicle_id" id="vehicle_id" class="form-control" onchange="vehicleChange(this.value,'')" required>
												<option value="">--Select--</option>
												@isset($vehicleData)
													@foreach ($vehicleData as $item)
														<option value="{{$item->id}}">{{$item->reg_number}}</option>
													@endforeach
												@endisset
										</select>
									</div> --}}
									<div class="form-group col-md-3">
										<label for="owner_id">Owner Name</label> <span style="color: red;">*</span>
										<select name="owner_id" id="owner_id" class="form-control" required>
												<option value="">--Select--</option>
												@foreach ($ownerData as $row)
													<option value="{{$row->id}}">{{$row->owner_name}}</option>
												@endforeach
										</select>
									</div>
									<div class="form-group col-md-3">
										<label for="contact_name">Contact Person</label>
										<span style="color: red;">*</span>
										<input type="text" name="contact_name" id="contact_name"  placeholder="Contact Person" class="form-control" required  />
									</div>
									<div class="form-group col-md-3">
										<label for="mob">Contact Number</label>
										<span style="color: red;">*</span>
										<input type="text" name="mob" id="mob"  placeholder="Contact Number" class="form-control" requeired />
									</div>
									<div class="form-group col-md-3">
										<label for="datefrom" >Email</label>
										<span style="color: red;">*</span>
										<input type="text" name="owner_contact_email" id="owner_contact_email" class="form-control"  placeholder="Email"  required/>
									</div>
	                            </div>
								@if(Auth::user()->role == '29' || Auth::user()->role == '30')
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
							<table id="order-listing" class="table">
                                <thead>
                                    <tr>
										<th>Actions</th>
										{{-- <th>Registration Number</th> --}}
										<th>Owner Name</th>
										<th>Contact Person</th>
										<th>Contact  Number</th>
										<th>Email</th>
										<th style="display: none;">vehicle_id </th>
										<th style="display: none;">owner_id</th>
										
                                    </tr>
                                </thead>
                                <tbody>
                                @isset($rowData)
                                @php $count=1; @endphp
									@foreach($rowData as $row)
                                    <tr>
										<td>
											<i class="fa fa-pencil-square-o" aria-hidden="true" id="{{$row->contactId}}" data-position="left" data-tooltip="Edit" onclick="javascript:return editOwnerContact(this);" style="cursor: pointer;"></i>
											<a href="{{route('owner_contact_delete.ownerContactDelete', ['id' => $row->contactId])}}" onclick="return confirm('Do you want to delete?')">
												<i class="fa fa-trash-o" aria-hidden="true" style="cursor: pointer;"></i>
											</a>
										</td>
										{{-- <td class="cls_reg_number">{{$row->reg_number}}</td> --}}
										<td class="cls_owner_name">{{$row->owner_name}}</td>
										<td class="cls_contact_name">{{$row->contact_name}}</td>
										<td class="cls_mob">{{$row->mob}}</td>
										<td class="cls_owner_contact_email">{{$row->owner_contact_email}}</td>
                                        <td class="cls_vehicle_id" style="display: none;">{{$row->vehicle_id}}</td>
                                        <td class="cls_owner_id" style="display: none;">{{$row->owner_id}}</td>
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
	function editOwnerContact(el,ell){
		var reg_number=$(el).parents('td').parents('tr').find('.cls_reg_number').text();
		var owner_name=$(el).parents('td').parents('tr').find('.cls_owner_name').text();
		
		var contact_name=$(el).parents('td').parents('tr').find('.cls_contact_name').text();
		var mob=$(el).parents('td').parents('tr').find('.cls_mob').text();
		var owner_contact_email=$(el).parents('td').parents('tr').find('.cls_owner_contact_email').text();
		var vehicle_id=$(el).parents('td').parents('tr').find('.cls_vehicle_id').text();
		var owner_id=$(el).parents('td').parents('tr').find('.cls_owner_id').text();
		
		$('#vehicle_id').val(vehicle_id);
		$('#owner_id').val(owner_id);
		//vehicleChange(vehicle_id,owner_id);
		$('#contact_name').val(contact_name);
		$('#mob').val(mob);
		$('#owner_contact_email').val(owner_contact_email);
		$('#dataid').val(el.id);
	}
    function vehicleChange(el,ell){
        var id = el;
		$.ajax({ url: '{{url("get-owner-name")}}',data: {'id':id},success: function(data) {
				var Result = data.split(",");var str = '';
				Result.pop();
				str += "<option value='' selected>--Select--</option>";
				for (item in Result){
				Result2 = Result[item].split("~~");
					if (ell!=''){
						if (Result2[0]==ell){
							str += "<option value='" + Result2[0] + "' selected>" + Result2[1] + "</option>";
						} 
						else{
							str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
						}
					}
					else{
						str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
					}
				}
				document.getElementById('owner_id').innerHTML =str;
			}
		});
    }
 </script>  

@endsection
