@extends("layouts.masterlayout")
@section('title','User Type')
@section('bodycontent')
	<div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Manage User Type</h4>
                <div class="row">
                    <div class="col-md-12">
                    	<div id="insertUserType" >
						<form name="myForm" method="post" enctype="multipart/form-data" action="{{url('store-user-type')}}" onsubmit="return userType()">
                        	<input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="row">                                
                                <div class="form-group col-md-3">
                                    <label for="Name">User Type</label> <span style="color: red;">*</span> 
                                    <input type="text" name="user_type" class="form-control">
                                    <span id="user_type_error" style="color:red"></span> 
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
						<div id="updateUserType" style="display: none">
	<form name="myForm" method="post" enctype="multipart/form-data" action="{{url('update-user-type')}}" onsubmit="return userTypeUpdate()">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="row">                                
                                <div class="form-group col-md-3">
                                    <label for="Name">User Type</label> <span style="color: red;">*</span> 
                                    <input type="text" name="user_type" id="user_type" class="form-control">
                                    <span id="user_type_error" style="color:red"></span> 
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
									<input type="hidden" name="dataid" id="dataid">
                                </div>                                  
                            </div>
                            <div class="box-footer">
                                <span class="pull-right">
								<button type="button" onclick="reloadPage();" class="btn-secondary">Cancel</button>	
                                <input type="submit"name="submit" id="submit" value="Update" class="btn-secondary">
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
										<th>User Type</th> 
										<th>Status</th>                                       
										
                                    </tr>
                                </thead>
                                <tbody>
                                @isset($rowData)
                                @php $count=1; @endphp							
									@foreach($rowData as $row)
                                    <tr>
										<td>
											<i class="fa fa-pencil-square-o" aria-hidden="true" id="{{$row->id}}" data-position="left" data-tooltip="Edit" onclick="javascript:return EditUserType(this);" style="cursor: pointer;"></i>
											<a href="{{route('userType_delete.userTypeDelete', ['id' => $row->id])}}" onclick="return confirm('Do you want to delete?')">
												<i class="fa fa-trash-o" aria-hidden="true" style="cursor: pointer;"></i></a>
										</td>
                                        <td class="cls_usertype">{{$row->usertype}}</td>                                                                   
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
