@extends("layouts.masterlayout")
@section('title','Manage Escalation')
@section('bodycontent')
	<div class="content-wrapper mobcss">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Manage Escalation</h4>
                <div class="row">
                    <div class="col-md-12">
                    	<div id="insertDealer" >
						<form name="myForm" method="post" enctype="multipart/form-data" action="{{url('store-escalation')}}" onsubmit="return escalationFormValidate()">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="hidden" name="DataID" id="DataID">
                            <div class="row"> 
								<div class="form-group col-md-3">
									<label for="level">Level</label>
									<span style="color:red">*</span>
									<input type="text" name="level" id="level" placeholder="Level" class="form-control">
									<span id="level_error" style="color:red"></span> 
								</div>
								<div class="form-group col-md-3">
									<label for="level_name">Level Name</label>
									<span style="color:red">*</span>
									<input type="text" name="level_name" id="level_name" placeholder="Level Name" class="form-control">
									<span id="level_name_error" style="color:red"></span> 
								</div>
								<div class="form-group col-md-3">
									<label for="hours" >Hours</label> <span style="color:red">*</span>
									<select name="hours" id="hours" class="form-control">
										<option value="0">0</option>
										<option value="12">12</option>
										<option value="24">24</option>
										<option value="48">48</option>
										<option value="60">60</option>
										<option value="72">72</option>
										<option value="96">96</option>
										<option value="120">120</option>
									</select>
									<span id="hours_error" style="color:red"></span> 
								</div>
								<div class="form-group col-md-3">
									<label for="to_role" >To Role</label> <span style="color:red">*</span>
									<select name="to_role[]" id="to_role" multiple class="form-control" style="height: 150px">
										@isset($roleData)
											@foreach($roleData as $row)
												<option value="{{$row->id}}">{{$row->role}}</option>
											@endforeach
										@endisset
									</select>
									<span id="to_role_error" style="color:red"></span> 
								</div>
								<div class="form-group col-md-3">
									<label for="cc_role" >CC Role</label> <span style="color:red">*</span>
									<select name="cc_role[]" id="cc_role" multiple class="form-control" style="height: 150px">
										@isset($roleData)
											@foreach($roleData as $row)
												<option value="{{$row->id}}">{{$row->role}}</option>
											@endforeach
										@endisset
									</select>
									<span id="cc_role_error" style="color:red"></span> 
								</div>
								
                            </div>
							@if(Auth::user()->role  == '29' || Auth::user()->role  == '30')
                            <div class="box-footer">
                                <span class="pull-right">
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
										<th >Action</th>
										<th >Level</th>
										<th>Level Name</th>
										<th>To Role</th>
										<th>Cc Role</th>
										<th>Hours</th>
				                    	<th style="display: none;">to_role</th>	
				                    	<th style="display: none;">cc_role</th>
				                    </tr>
				                </thead>
								<tbody>
								@isset($rowData)
								@php 
								$toName = explode("~",$toRoleName);
								
								$ccName = explode("~",$ccRoleName);	
								@endphp
								@php $count=0; @endphp
								@foreach($rowData as $row)
								<tr>
									<td>
										<i class="fa fa-pencil-square-o" aria-hidden="true" id="{{$row->id}}" data-position="left" data-tooltip="Edit" onclick="javascript:return editEscalation(this);" style="cursor: pointer;"></i>
										<a href="{{route('escalation_delete.escalationDelete', ['id' => $row->id])}}" onclick="return confirm('Do you want to delete?')">
										<i class="fa fa-trash-o" aria-hidden="true" style="cursor: pointer;"></i></a>
									</td>
									<td class="level">{{$row->level}}</td>
									<td class="level_name">{{$row->level_name}}</td>
									
									<td class="to_role_name">{{$toName[$count]}}</td>
									<td class="cc_role_name">{{$ccName[$count]}}</td>
									<td class="hours">{{$row->hours}}</td>
									<td class="to_role" style="display: none;">{{$row->to_role}}</td>	
									<td class="cc_role" style="display: none;">{{$row->cc_role}}</td>
	                        
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
function editEscalation(el){
	$('#DataID').val(el.id);
	var level =$(el).parents('td').parents('tr').find('.level').text();
	var level_name =$(el).parents('td').parents('tr').find('.level_name').text();
	var to_role =$(el).parents('td').parents('tr').find('.to_role').text();
	var cc_role =$(el).parents('td').parents('tr').find('.cc_role').text();
	var hours =$(el).parents('td').parents('tr').find('.hours').text();
	$('#level').val(level);
	$('#level_name').val(level_name);
	
	$('#hours').val(hours);

	$.ajax({ url: '{{url("ajax-role")}}',
		success: function(data) {
			var Result = data.split(",");
			var str='';
			var str1='';
			var toRole = to_role.split(',');
			var ccRole = cc_role.split(',');
			Result.pop();
			for (item1 in Result){
				var Result2 = Result[item1].split("~");
				if (jQuery.inArray(Result2[0], toRole)!='-1')
				{
				str += "<option value='" +Result2[0] + "' selected>" +Result2[1] + "</option>";
				} 
				else
				{
				str += "<option value='" +Result2[0] + "'>" +Result2[1] + "</option>";
				}
				
			}
			document.getElementById('to_role').innerHTML = str ;

			for (item1 in Result){
				var Result2 = Result[item1].split("~");
				if (jQuery.inArray(Result2[0], ccRole)!='-1')
				{
				str1 += "<option value='" +Result2[0] + "' selected>" +Result2[1] + "</option>";
				} 
				else
				{
				str1 += "<option value='" +Result2[0] + "'>" +Result2[1] + "</option>";
				}
				
			}
			document.getElementById('cc_role').innerHTML = str1 ;
		}	   
	});	

}
</script>
@endsection