@extends("layouts.masterlayout")
@section('title','Product Master')
@section('bodycontent')
	<div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Product Master</h4>
                <div class="row">
                    <div class="col-md-12">
                    	<div id="insertvehicle" >
							<form name="myForm" method="post" enctype="multipart/form-data" action="{{url('product-vehicle')}}" onsubmit="return productValidation()">
	                        	<input type="hidden" name="_token" value="{{csrf_token()}}">                        
	                            <div class="row">                                
	                                <div class="form-group col-md-3">
	                                    <label for="Name">Product</label>
	                                    <input type="text" name="vehicle" class="form-control">
	                                    <span id="vehicle_error" style="color:red"></span> 
	                                </div>
	                                 <div class="form-group col-md-3">
	                                    <label for="Name">Model</label>
	                                    <input type="text" name="vehicle_subtype" class="form-control">
	                                    <span id="vehicle_subtype_error" style="color:red"></span> 
	                                </div>  
	                                <div class="form-group col-md-3">
	                                    <label for="Name">Segment</label>
	                                    <input type="text" name="segment" class="form-control">
	                                    <span id="segment_error" style="color:red"></span> 
	                                </div>                                 
	                                                                 
	                            </div>
	                            <div class="box-footer">
	                                <span class="pull-right">	
	                                <input type="submit"name="submit" id="submit" value="Submit" class="btn-secondary">
	                                </span>
	                            </div>
	                        </form>  
						</div> 
						<div id="updatevehicle" style="display: none">
							<form name="myForm" method="post" enctype="multipart/form-data" action="{{url('update-product')}}" onsubmit="return editProductForm()">
		                        <input type="hidden" name="_token" value="{{csrf_token()}}">
		                        <input type="hidden" name="dataid" id="dataid">
		                        <input type="hidden" name="modelId" id="modelId">
		                        <input type="hidden" name="segmentId" id="segmentId">
	                            <div class="row">                                
	                                <div class="form-group col-md-3">
	                                    <label for="Name">Product</label>
	                                    <input type="text" name="vehicle" id="vehicle" class="form-control">
	                                    <span id="vehicle_error" style="color:red"></span>
	                                </div>	                                
	                                <div class="form-group col-md-3">
	                                    <label for="Name">Segment</label>
	                                    <input type="text" name="segment" id="segment" class="form-control">
	                                    <span id="segment_error" style="color:red"></span> 
	                                </div> 
	                                <div class="form-group col-md-3">
	                                    <label for="Name">Model</label>
	                                    <input type="text" name="model" id="model" class="form-control">
	                                    <span id="model_error" style="color:red"></span>
	                                </div>
	                                <div class="form-group col-md-3" id="td_Status">
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
                                    	<th style="display: none;">Segment Id</th>
                                    	<th style="display: none;">Model Id</th>                                    	
										<th >#</th>
										<th>Product</th>
										<th>Segment</th>										 										                                     
										<th>Model</th> 										                                     
										<th>Status</th> 										                                     
										<th style="text-align: right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @isset($rowData)
                               
                                @php $count=1; @endphp							
									@foreach($rowData as $row)
                                    <tr>
                                    	<td style="display: none;" class="cls_segmentId">{{$row->segmentId}}</td>
                                    	<td style="display: none;" class="cls_modelId">{{$row->modelId}}</td>  
                                        <td class="cls_id">{{$count}}</td>
                                        <td class="cls_vehicle">{{$row->vehicle}}</td>
                                        <td class="cls_segment">{{$row->segment}}</td>
                                        <td class="cls_model">{{$row->model}}</td>                                        
                                        <td class="cls_flag">@if($row->flag=='1')
                                        	<label class='badge badge-success'>Active</label>
                                        	@else
                                        	<label class="badge badge-danger">Inactive</label>
                                        	@endif
                                        </td>  
                                        <td style="text-align: right">
                                        <i class="fa fa-pencil-square-o" aria-hidden="true" id="{{$row->id}}" data-position="left" data-tooltip="Edit" onclick="javascript:return editroduct(this);" style="cursor: pointer;"></i> <a href="{{route('vehicle_delete.vehicleDelete', ['id' => $row->id])}}" onclick="return confirm('Do you want to delete?')"> <i class="fa fa-trash-o" aria-hidden="true" style="cursor: pointer;"></i></a>
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
