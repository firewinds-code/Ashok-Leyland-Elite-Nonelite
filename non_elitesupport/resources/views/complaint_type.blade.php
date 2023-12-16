@extends("layouts.masterlayout")
@section('title','Complaint Category')
@section('bodycontent')
	<div class="content-wrapper mobcss">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Manage Complaint Type</h4>
                <div class="row">
                    <div class="col-md-12">
                    	<div id="insertBrand" >
							<form name="myForm" method="post" enctype="multipart/form-data" action="{{url('store-complaint')}}" onsubmit="return complaintValidate()">
	                        	<input type="hidden" name="_token" value="{{csrf_token()}}">
	                        	<input type="hidden" name="dataid" id="dataid"/>
	                            <div class="row">                                
	                                <div class="form-group col-md-3">
	                                    <label for="Name">Complaint Category</label> <span style="color: red;">*</span>                            
	                                    <select  name="complaint_type" id="complaint_type" class="form-control">
	                                    	<optgroup>
												<option Value="NA">--select--</option>
												@isset($complaint_data)
													@foreach($complaint_data as $rowComplaint)
														<option Value="{{$rowComplaint->id}}">{{$rowComplaint->complaint_type}}</option>
													@endforeach												
												@endisset								
											</optgroup>
	                                    </select>
	                                    <span id="complaint_type_error" style="color:red"></span> 
	                                </div>
	                                <div class="form-group col-md-3">
	                                    <label for="Name">Sub Complaint Type</label> <span style="color: red;">*</span>
	                                    <input type="text" name="sub_complaint_type" id="sub_complaint_type" class="form-control">
	                                    <span id="sub_complaint_type_error" style="color:red"></span> 
	                                </div>
	                                <div class="form-group col-md-3" id="td_Status" style="display: none;">
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
										
										<th style="display: none;">Complaint Category Id</th> 
										<th>Actions</th>										                                       
										<th>Complaint Category</th> 										                                       
										<th>Sub Complaint Type</th> 										                                       
										<th>Status</th> 										                                       
										
                                    </tr>
                                </thead>
                                <tbody>
	                                @isset($rowData)
	                                	@php $count=1; @endphp							
										@foreach($rowData as $row)
	                                    <tr>
											
	                                        <td class="cls_complaint_type" style="display: none;">{{$row->complaint_type_id}}</td>
											<td>
												<i class="fa fa-pencil-square-o" aria-hidden="true" id="{{$row->sub_complaint_type_id}}" data-position="left" data-tooltip="Edit" onclick="javascript:return Editcomplaint_type(this);" style="cursor: pointer;"></i>
												<a href="{{route('complaint_type_delete.complaintTypeDelete', ['id' => $row->sub_complaint_type_id])}}" onclick="return confirm('Do you want to delete?')">
													<i class="fa fa-trash-o" aria-hidden="true" style="cursor: pointer;"></i></a>
											</td>
	                                        <td>{{$row->complaint_type}}</td>
	                                        <td class="cls_sub_complaint_type">{{$row->sub_complaint_type}}</td>
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
