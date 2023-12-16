@extends("layouts.masterlayout")
@section('title','Role')
@section('bodycontent')
	<div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Manage User Roles</h4>
                <div class="row">
                    <div class="col-md-12">
                    	<div id="insertrole" >
	<form name="myForm" method="post" enctype="multipart/form-data" action="{{url('store-role')}}" onsubmit="return roleValidation()">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="row">
                            	<div class="form-group col-md-3">
                                    <label for="Name">User Type</label> <span style="color: red;">*</span>
                                    <select name="usertype_id" id="ins_usertype_id" tabindex="1" class="form-control">
										<optgroup>
											<option Value="NA">--select--</option>
											@isset($userTypeData)
												@foreach($userTypeData as $row)
													<option Value="{{$row->id}}">{{$row->usertype}}</option>
												@endforeach
											@endisset
											
										</optgroup>
									</select>
									<span id="usertype_id_error" style="color:red"></span>
                                </div>                                
                                <div class="form-group col-md-3">
                                    <label for="Name">Role</label> <span style="color: red;">*</span>
                                    <input type="text" name="role" id="ins_role" class="form-control">
                                    <span id="role_error" style="color:red"></span> 
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
						</div> 
						<div id="updaterole" style="display: none">
    <form name="myForm" method="post" enctype="multipart/form-data" action="{{url('update-role')}}" onsubmit="return roleUpdat()">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="hidden" name="dataid" id="dataid">
                            <div class="row">
                            <div class="form-group col-md-3">
                                    <label for="Name">User Type</label>  <span style="color: red;">*</span>
                                    <select name="usertype_id" id="usertype_id" tabindex="1" class="form-control">
										<optgroup>
											<option Value="NA">--select--</option>
											@isset($userTypeData)
												@foreach($userTypeData as $row)
													<option Value="{{$row->id}}">{{$row->usertype}}</option>
												@endforeach
											@endisset
											
										</optgroup>
									</select>
									<span id="usertype_idd_error" style="color:red"></span>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="Name">Role</label> <span style="color: red;">*</span>
                                    <input type="text" name="role" id="role" class="form-control">
                                    <span id="rolee_error" style="color:red"></span> 
                                </div> 
								{{-- <div class="form-group col-md-3" id="complaint_type_div">
                                    <label for="complaint_type">Complaint Category</label> <span style="color: red;">*</span>
                                    <select  name="complaint_type[]" multiple id="complaint_cat" class="form-control">
                                    	<optgroup>
											<option Value="NA">--select--</option>
											@isset($complaint_data)
												@foreach($complaint_data as $rowComplaint)
													<option Value="{{$rowComplaint->id}}">{{$rowComplaint->complaint_type}}</option>
												@endforeach												
											@endisset								
										</optgroup>
                                    </select>
                                    <span id="complaint_cat_error" style="color:red"></span> 
                                </div> --}}
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
                            @if(Auth::user()->role == '29' || Auth::user()->role == '30')
                            <div class="box-footer">
                                <span class="pull-right">
								<button type="button" onclick="reloadPage();" class="btn-secondary">Cancel</button>
                                <input type="submit"name="submit" id="submit" value="Update" class="btn-secondary">
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
										
										<th class="d-none">usertype_id</th>
										<th class="d-none">complaint_type</th>
										<th>Actions</th>
										<th>User Type</th> 
										<th>Role</th> 
										<th>Status</th>
										
                                    </tr>
                                </thead>
                                <tbody>
                                @isset($roleData)
                                @php $count=1; @endphp
									@foreach($roleData as $row)
                                    <tr>
                                        <td class="cls_usertype_id d-none">{{$row->usertype_id}}</td>
                                        <td class="cls_complaint_type d-none">{{$row->complaint_type}}</td>
										<td>
											<i class="fa fa-pencil-square-o" aria-hidden="true" id="{{$row->id}}" data-position="left" data-tooltip="Edit" onclick="javascript:return Editrole(this);" style="cursor: pointer;"></i>
											{{-- <a href="{{route('role_delete.roleDelete', ['id' => $row->id])}}" onclick="return confirm('Do you want to delete?')">
												<i class="fa fa-trash-o" aria-hidden="true" style="cursor: pointer;"></i></a> --}}
										</td>
                                        <td>{{$row->usertype}}</td>
                                        <td class="cls_role">{{$row->role}}</td>
                                        <td class="cls_flag">@if($row->flag=='1')
                                        	<label class='badge badge-success'>Active</label>
                                        	@else
                                        	<label class="badge badge-danger">Inactive</label>
                                        	@endif
                                        </td>
                                        
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
   

@endsection
