@extends("layouts.masterlayout")
@section('title','Customer Master')
@section('bodycontent')
	<div class="content-wrapper mobcss">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Manage Customer</h4>
                <div class="row">
                    <div class="col-md-12">
                    	<div id="insertBrand" >
							<form name="myForm" method="post" enctype="multipart/form-data" action="{{url('store-customer')}}" onsubmit="return customerValidate()">
	                        	<input type="hidden" name="_token" value="{{csrf_token()}}">
	                        	<input type="hidden" name="dataid" id="dataid"/>
	                            <div class="row">
									<div class="form-group col-md-3">
										<label for="customerID">Customer ID</label>
										<span style="color: red;">*</span>
										<input type="text" name="customerID" id="customerID" class="form-control" placeholder="Customer ID">
										<span id="customerID_error" style="color:red"></span>
									</div>
									<div class="form-group col-md-3">
										<label for="customerOrg">Customer Organisation</label>
										<span style="color: red;">*</span>
										<input type="text" name="customerOrg" id="customerOrg" class="form-control" placeholder="Customer Organisation">															<span id="customerOrg_error" style="color:red"></span>
									</div>                                
									<div class="form-group col-md-3">
										<label for="Name">Product</label>
										<span style="color: red;">*</span>
										<select name="vehicle" id="vehicle" class="form-control" onchange="fn_product_change(this.value,'','','')">
											<optgroup>
											<option value="NA">--Select--</option>
											@isset($product_details)
												@foreach($product_details as $productRow)
													<option value="{{$productRow->id}}">{{$productRow->vehicle}}</option>
												@endforeach
											@endisset
											</optgroup>
										</select>
										<span id="product_error" style="color:red"></span>
									</div>
									<div class="form-group col-md-3">
									<label for="Segment">Customer Segment</label>
										<span style="color: red;">*</span>
										<select name="segment[]" multiple id="segment" class="form-control">
												<optgroup><option value="NA">--Select--</option></optgroup>
										</select>
										<span id="segment_error" style="color:red"></span>
									</div> 
									<div class="form-group col-md-3">
										<label for="Address">Address</label>
										<span style="color: red;">*</span>
										<textarea name="address" rows="2" cols="39" id="address" class="form-control" placeholder="Address"></textarea>
										<span id="address_error" style="color:red"></span>
									</div>
									<div class="form-group col-md-3">
										<label for="Region">Region</label>
										<span style="color: red;">*</span>
										<select name="zone" id="zone" class="form-control" onchange="Dealer_Zone_change(this.value,'')">
											<optgroup>
												<option value="NA">--Select--</option>
												@isset($regionData)
													@foreach($regionData as $regionRow)
														<option value="{{$regionRow->id}}">{{$regionRow->region}}</option>
													@endforeach
												@endisset
											</optgroup>
										</select>
										<span id="Zone_error" style="color:red"></span>
									</div>
									<div class="form-group col-md-3">
										<label for="State" >State</label>
										<span style="color: red;">*</span>
										<select name="state" id="state" class="form-control" onchange="Dealer_State_change(zone,this.value,'');"><option value="NA">--Select--</option></select>
										<span id="State_error" style="color:red"></span>
									</div>
									<div class="form-group col-md-3">
										<label for="City">Location</label>
										<span style="color: red;">*</span>
										<select name="City" id="City" class="form-control">
											<optgroup>
												<option value="NA">--Select--</option></optgroup></select>
										<span id="City_error" style="color:red"></span>
									</div>
									<div class="form-group col-md-3">
									<label for="pincode">Pin Code</label>
										<span style="color: red;">*</span>
										<input type="text" maxlength="50" name="pincode" id="pincode" class="form-control" placeholder="Pin Code">
										<span id="pincode_error" style="color:red"></span>
									</div>
									{{--<div class="form-group col-md-3">
										<label for="sales_account_manager">Sales Account Manager</label>
										<span style="color: red;">*</span>
										<input type="text" maxlength="50" name="sales_account_manager" id="sales_account_manager" class="form-control" placeholder="Sales Account Manager">
										<span id="sales_account_manager_error" style="color:red"></span>
									</div>--}}
									<div class="form-group col-md-3">
										<label for="sales_POC_1">Sales POC 1</label>
										<span style="color: red;">*</span>
										
										<select name="sales_POC_1" id="sales_POC_1" class="form-control">
											<optgroup>
												<option value="NA">--Select--</option>
												@isset($user_details)
													@foreach($user_details as $userRow)
														<option value="{{$userRow->id}}">{{$userRow->name}}</option>
													@endforeach
												@endisset
											</optgroup>
										</select>
									<span id="sales_POC_1_error" style="color:red"></span>
									</div>
									<div class="form-group col-md-3">
										<label for="sales_POC_2">Sales POC 2</label>
										<select name="sales_POC_2" id="sales_POC_2" class="form-control">
											<optgroup>
												<option value="">--Select--</option>
												@isset($user_details)
													@foreach($user_details as $userRow)
														<option value="{{$userRow->id}}">{{$userRow->name}}</option>
													@endforeach
												@endisset
											</optgroup>
										</select>
										<span id="sales_POC_2_error" style="color:red"></span>
									</div>
									<div class="form-group col-md-3"></div>
									<div class="form-group col-md-3"></div>
									<div class="form-group col-md-3">
										<label for="segment1">Segment 1</label>
										<span style="color: red;">*</span>
										
										<select name="segment1" id="segment1" class="form-control">
												<optgroup><option value="NA">--Select--</option></optgroup>
										</select>
										<span id="segment1_error" style="color:red"></span>
									</div>
									<div class="form-group col-md-3">
										<label for="segment2">Segment 2</label>
										
										<select name="segment2" id="segment2" class="form-control">
												<optgroup><option value="NA">--Select--</option></optgroup>
										</select>
										<span id="segment2_error" style="color:red"></span>
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
										<th style="display: none;">productId</th>
										<th style="display: none;">segmentId</th>
										<th style="display: none;">zoneId</th>
										<th style="display: none;">stateId</th>
										<th style="display: none;">CityId</th>
										<th style="display: none;">Sales POC 1</th>
										<th style="display: none;">Sales POC 2</th>
										<th style="display: none;">Segment 1</th> 										                                       
										<th style="display: none;">Segment 2</th>	
										<th>Actions</th> 										                                       
										<th >Customer ID</th> 										                                       
										<th >Customer Organisation</th> 										                                       
										<th>Product</th>
										<th>Address</th>
										<th>Region</th> 										                                       
										<th>State</th>
										<th>Site</th> 										                                       
										<th>Pin Code</th>					
                                    </tr>
                                </thead>
                                <tbody>
	                                @isset($rowData)	                                
	                                	@php $count=1; @endphp							
										@foreach($rowData as $row)
	                                    <tr>	                                        
											<td class="cls_productId" style="display: none;">{{$row->productId}}</td>
											<td class="cls_segmentId" style="display: none;">{{$row->segmentId}}</td>
											<td class="cls_zoneId" style="display: none;">{{$row->zoneId}}</td>
											<td class="cls_stateId" style="display: none;">{{$row->stateId}}</td>
											<td class="cls_CityId" style="display: none;">{{$row->CityId}}</td>
	                                        <td class="cls_sales_POC_1" style="display: none;">{{$row->sales_POC_1}}</td>
											<td class="cls_sales_POC_2" style="display: none;">{{$row->sales_POC_2}}</td>
											<td class="cls_segment1" style="display: none;">{{$row->segment1}}</td>
											<td class="cls_segment2" style="display: none;">{{$row->segment2}}</td>
											<td>
												<i class="fa fa-pencil-square-o" aria-hidden="true" id="{{$row->id}}" data-position="left" data-tooltip="Edit" onclick="javascript:return Editcustomer(this);" style="cursor: pointer;"></i>
												<a href="{{route('customer_delete.customerDelete', ['id' => $row->id])}}" onclick="return confirm('Do you want to delete?')">
											<i class="fa fa-trash-o" aria-hidden="true" style="cursor: pointer;"></i>
												</a>
												<a href="{{route('customer_contact.customerContact', ['id' => $row->id])}}">
											<i class="fa fa-users" aria-hidden="true" style="cursor: pointer;"></i>
												</a>
											</td>	                                        
											<td class="cls_customerID">{{$row->customerID}}</td>
											<td class="cls_customerOrg">{{$row->customerOrg}}</td>
											<td>{{$row->vehicle}}</td>
											<td class="cls_address">{{$row->address}}</td>
											<td>{{$row->zone}}</td>
											<td>{{$row->state}}</td>
											<td>{{$row->city}}</td>
											<td class="cls_pincode">{{$row->pincode}}</td>
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
