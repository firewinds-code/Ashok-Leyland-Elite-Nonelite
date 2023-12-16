@extends("layouts.masterlayout")
@section('title','Report')
@section('bodycontent')

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

	<div class="content-wrapper">	
	            <div class="card">
	            
	                <div class="card-body">
	                    <h4 class="card-title">Report Filter Elements</h4>
	                    <div class="clear"></div>
                        <hr> 
	                    <form name="myForm" method="post" enctype="multipart/form-data" action="{{url('dashboard-data')}}" onsubmit="return DashboardValidate()">
	                    <input type="hidden" name="_token" value="{{csrf_token()}}">
	                    
	                    	<div class="row">	                    	
                             	<div class="form-group col-md-3">
                                    <label for="datefrom" >Date From</label>
                                    <input type="text" name="datefrom" id="datefrom" autocomplete="off" class="form-control"  />
                                    <span id="datefrom_error" style="color:red"></span> 
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="dateto" >Date To</label>
                                    <input type="text" name="dateto" id="dateto" autocomplete="off" class="form-control"  />
                                    <span id="dateto_error" style="color:red"></span> 
                                </div>
                                 <div class="form-group col-md-3">
                                    <label for="complaintcategory" >Complaint Category</label>
                                    <select name="complaintcategory" id="complaintcategory" class="form-control" >
                                    	<option value="NA">--Select--</option>
                                    	@isset($complaintTypeData)
                                    		@foreach($complaintTypeData as $row)
                                    			<option value="{{$row->complaint_type}}">{{$row->complaint_type}}</option>
                                    		@endforeach
                                    	@endisset
                                    </select>
                                    <span id="complaintcategory_error" style="color:red"></span> 
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="Product" >Product</label>
                                    <select name="Product" id="Product" class="form-control" >
                                    	<option value="NA">--Select--</option>
                                    	@isset($vehicleData)
                                    		@foreach($vehicleData as $row)
                                    			<option value="{{$row->vehicle}}">{{$row->vehicle}}</option>
                                    		@endforeach
                                    	@endisset
                                    </select>
                                    <span id="Product_error" style="color:red"></span> 
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="Zone" >Region</label>
    								<select name="zone[]" id="zone" multiple class="form-control" onchange="Dealer_Zone_change(this.value,'')">
    								<option value="NA">--Select--</option>
    								@isset($zoneData)
											@foreach($zoneData as $Data)
												<option value="{{$Data->id}}">{{$Data->region}}</option>
											@endforeach
										@endisset</select>
                                    <span id="Zone_error" style="color:red"></span> 
                                </div> 
                                <div class="form-group col-md-3">
                                    <label for="State" >state</label>
                               		<select name="state[]" multiple id="state" class="form-control" onchange="Dealer_State_change(zone,this.value,'');"></select>
                                    <span id="State_error" style="color:red"></span> 
                                </div> 
                                <div class="form-group col-md-3">
                                    <label for="City" >City</label>
            						<select name="City[]" multiple id="City" class="form-control"><optgroup><option value="NA">--Select--</option></optgroup></select>
                                    <span id="City_error" style="color:red"></span> 
                                </div> 
                                
                                <div class="form-group col-md-3">
                                    <label for="Dealer" >Dealer</label>
   									<select name="Dealer" id="Dealer" class="form-control" onchange="fn_dealer_change(State,City,Zone,this,'');"></select>
                                    <span id="Dealer_error" style="color:red"></span> 
                                </div>
                                <div class="form-group col-md-3" id="tdDealerCode">
                                    <label for="DealerCode" >Dealer Code</label>
                                    <input type="text" name="DealerCode" id="DealerCode"class="form-control"  />
                                    <span id="DealerCode_error" style="color:red"></span> 
                                </div> 
                                
                                <div class="form-group col-md-3">
                                    <label for="Brand" >Brand</label>
                                    <input type="text" name="Brand" id="Brand" class="form-control"  />
                                    <span id="Brand_error" style="color:red"></span> 
                                </div>
                            </div>
                            <div class="clear"></div>
                        <hr> 
                            <div class="row">
                            	 <div class="form-group col-md-3">
                                    <input type="submit"name="submit" id="submit" value="Submit" class="btn-secondary">
                                </div>
                            </div>
                        </form>
                       
<div class="clear"></div>
                        <hr>                       
                        <div class="table-responsive">
                            <table id="example" class="table" style="background-color: #e7e9e8;">
                                <thead>
                                    <tr style="background-color: ##d3d6d2;">
                                    	<th >#</th>
										<th >Dealer Name</th>
										<th>Dealer Code</th> 
										<th>State</th>                                       
										<th>City</th>                                       
										<th>Region</th>
										<th style="text-align: right;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php $count=1; @endphp
                                @isset($dashboardData1)
                                					
									@foreach($dashboardData1 as $row)
                                    <tr>
                                    	<td class="cls_id">{{$count}}</td>
                                    	<td class="cls_dealername">{{$row->dealer}}</td>
                                        <td class="cls_dealercode">{{$row->dealercode}}</td>    
                                        <td class="cls_state">{{$row->state}}</td>              
                                        <td class="cls_city">{{$row->city}}</td>                                   
                                        <td class="cls_zone">{{$row->zone}}</td>                                   
                                        <td style="text-align: right">
                                        <i class="fa fa-pencil-square-o" aria-hidden="true" id="{{$row->id}}" data-position="left" data-tooltip="Edit" onclick="javascript:return EditDealer(this);" style="cursor: pointer;"></i>
                                        </td>
                                    </tr>
                                    @php $count++; @endphp
                                    @endforeach
                                @endisset
                                </tbody>
                            </table>
						</div>
						</br>
	            </div>
	            
	        </div>
@endsection
<script type="text/javascript">

        $(document).ready(function () {
        	
            $('#example').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                    extend: 'excel',
                    text: 'Excel',
                    className: 'exportExcel',
                    filename: 'Test_Excel',
                    exportOptions: { modifier: { page: 'all'} }
                },
                {
                    extend: 'csv',
                    text: 'CSV',
                    className: 'exportExcel',
                    filename: 'Test_Csv',
                    exportOptions: { modifier: { page: 'all'} }
                },
                {
                    extend: 'pdf',
                    text: 'PDF',
                    className: 'exportExcel',
                    filename: 'Test_Pdf',
                    exportOptions: { modifier: { page: 'all'} }
                }]
            });
 
        });
    </script>