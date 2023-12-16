@extends("layouts.masterlayout")
@section('title','Owner')
@section('bodycontent')
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
                <h4 class="card-title">Manage Owner</h4>
                <div class="row">
                    <div class="col-md-12">
                    	<div id="insertvehicle" >
							<form name="myForm" method="post" enctype="multipart/form-data" action="{{url('store-owner')}}" >
	                        	<input type="hidden" name="_token" value="{{csrf_token()}}">
	                        	<input  type="hidden" name="dataid" id="dataid"/>
	                            <div class="row">
									{{-- <div class="form-group col-md-3">
										<label for="vehicle_id">Registration Number</label> <span style="color: red;">*</span>
										<select name="vehicle_id" id="vehicle_id" class="form-control" required>
												<option value="">--Select--</option>
												@isset($ownerData)
													@foreach ($ownerData as $item)
														<option value="{{$item->id}}">{{$item->reg_number}}</option>
													@endforeach
												@endisset
										</select>
									</div> --}}
									<div class="form-group col-md-3">
										<label for="owner_name">Owner Name</label>
										<span style="color: red;">*</span>
										<input type="text" name="owner_name" id="owner_name"  placeholder="Owner Name" class="form-control" required>
									</div>
									<div class="form-group col-md-3">
										<label for="owner_mob">Owner Mobile</label>
										<!--<span style="color: red;">*</span>-->
										<input type="text" name="owner_mob" id="owner_mob"  placeholder="Owner Mobile" class="form-control" maxlength="10" />
									</div>
									<div class="form-group col-md-3">
										<label for="owner_landline">Owner LandLine</label>
										<input type="text" name="owner_landline" id="owner_landline"  placeholder="Owner LandLine" class="form-control" />
									</div>
									<div class="form-group col-md-3">
										<label for="owner_cat">Owner Category</label>
										<span style="color: red;">*</span>
										<input type="text" name="owner_cat" id="owner_cat"  placeholder="Owner Category" class="form-control" value="Select Category">
										{{-- <input type="hidden" name="owner_cat" id="owner_cat" value="Select Category"> --}}
									</div>
									<div class="form-group col-md-3">
										<label for="owner_company">Owner Company</label>
										<span style="color: red;">*</span>
										<input type="text" name="owner_company" id="owner_company"  placeholder="Owner Company" class="form-control" >
									</div>
									{{-- <div class="form-group col-md-3">
										<label for="alse_mail" >ALSE / ASM Email</label>
										<span style="color: red;">*</span>
										<input type="text" name="alse_mail" id="alse_mail" class="form-control"  placeholder="ALSE / ASM Email" required/>
									</div>
									<div class="form-group col-md-3">
										<label for="asm_mail" >RSM Email</label>
										<span style="color: red;">*</span>
										<input type="text" name="asm_mail" id="asm_mail" class="form-control"  placeholder="RSM Email" required/>
									</div> --}}
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
							<div class="row">
								<div class="col-md-5"><a class="btn btn-warning" href="{{ route('export-owner') }}" style="background-color: #e9e9e9;border: 1px solid #999;border-radius: 2px;">Excel</a></div>
								<div class="col-md-4"></div>
								<div class="col-md-3 col-md-offset-3">
								<input type="text" name="SearchVicle" id="SearchVicle" class="form-control" placeholder="Search by Owner Name" autocomplete="off"/>
								</div>
							</div>
							<table id="order-listing123" class="table">
                                <thead>
                                    <tr>
										<th style="display: none;">owner Id</th>
										<th>Actions</th>
										<th>Owner Name</th>
										<th>Owner Mobile</th>
										<th>Owner Company</th>
										<th>owner_landline</th>										
										<th style="display: none;">owner_cat</th>
										<th style="display: none;">reg_id</th>
										
                                    </tr>
                                </thead>
                                <tbody>
								<tbody id="tableDisabled">
                                @isset($rowData)
								
                                @php $count=1; @endphp
									@foreach($rowData as $row)
                                    <tr>
										<td style="display: none;">{{$row->id}}</td>
										<td>
											<i class="fa fa-pencil-square-o" aria-hidden="true" id="{{$row->id}}" data-position="left" data-tooltip="Edit" onclick="javascript:return editOwner(this);" style="cursor: pointer;"></i>
											<a href="{{route('owner_delete.ownerDelete', ['id' => $row->id])}}" onclick="return confirm('Do you want to delete?')">
												<i class="fa fa-trash-o" aria-hidden="true" style="cursor: pointer;"></i>
											</a>
										</td>
										<td class="cls_owner_name">{{$row->owner_name}}</td>
                                        <td class="cls_owner_mob">{{$row->owner_mob}}</td>
										<td class="cls_owner_company">{{$row->owner_company}}</td>
                                        <td class="cls_owner_landline">{{$row->owner_landline}}</td>
                                        <td class="cls_owner_cat" style="display: none;">{{$row->owner_cat}}</td>
                                        <td class="cls_vehicle_id" style="display: none;">0</td>
                                    </tr>
                                     @php $count++; @endphp	
                                    @endforeach
                                @endisset
                                    
                                </tbody>
								<div>
									<tbody id="tableEnabled"></tbody>
								</div>
                            </table>
                        </div>
						{{ $rowData->links('pagination.custom') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
 <script>
	function editOwner(el){
		var vehicle_id=$(el).parents('td').parents('tr').find('.cls_vehicle_id').text();
		var owner_name=$(el).parents('td').parents('tr').find('.cls_owner_name').text();
		var owner_mob=$(el).parents('td').parents('tr').find('.cls_owner_mob').text();
		var owner_company=$(el).parents('td').parents('tr').find('.cls_owner_company').text();
		var owner_landline=$(el).parents('td').parents('tr').find('.cls_owner_landline').text();
		var owner_cat=$(el).parents('td').parents('tr').find('.cls_owner_cat').text();
		var alse_mail=$(el).parents('td').parents('tr').find('.cls_alse_mail').text();
		var asm_mail=$(el).parents('td').parents('tr').find('.cls_asm_mail').text();
		
		$('#vehicle_id').val(vehicle_id);
		$('#owner_name').val(owner_name);
		$('#owner_mob').val(owner_mob);
		$('#owner_company').val(owner_company);
		$('#owner_landline').val(owner_landline);
		$('#owner_cat').val(owner_cat);
		$('#alse_mail').val(alse_mail);
		$('#asm_mail').val(asm_mail);
		$('#dataid').val(el.id);
	}
	$(document).ready(function () {
		$("#SearchVicle").keyup(function(){
			var inptData = $(this).val();
			$.ajax({
				url: '{{url("ajax-owner-report-data")}}',
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
	});
	
 </script>  

@endsection
