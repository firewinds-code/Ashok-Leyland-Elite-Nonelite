@extends("layouts.masterlayout")
@section('title','KPI Trend Report')
@section('bodycontent')
	<div class="content-wrapper mobcss">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">KPI Trend Report</h4>
				<div class="clear"></div>
				<hr>
				@php $current =  date("Y"); $next =date("y")+1;@endphp
				@if(Auth::user()->role  == '29' || Auth::user()->role  == '30' || Auth::user()->user_type_id =='2')
					<form name="myForm" method="post" enctype="multipart/form-data" action="{{url('store-kpi-report')}}" onsubmit="return topFocusValidate()">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="productId" id="productId" value="@isset($product){{$product}} @endisset">
						<input type="hidden" name="segmentId" id="segmentId" value="@isset($segment){{$segment}} @endisset">
						<input type="hidden" name="zoneId" id="zoneId" value="@isset($zoneVal){{$zoneVal}} @endisset">
						<input type="hidden" name="DealerId" id="DealerId" value="@isset($dealerVal){{$dealerVal}} @endisset">
						<input type="hidden" name="StatusId" id="StatusId" value="@isset($Status){{$Status}} @endisset">

						<div class="row">
							<div class="form-group col-md-3">
								<label for="year" >Year</label>
								<span style="color: red;">*</span>
								<select name="year" id="year" class="form-control">
									<option value="NA">Select</option>
									@for($i=2017,$j=18;$i<=$current,$j<=$next;$i++,$j++)

										<option value="{{$i.'-'.$j}}" @isset($yearVal){{$i.'-'.$j ==$yearVal?'selected':''}} @endisset>{{$i.'-'.$j}}</option>
									@endfor

								</select>
								<span id="brand_error" style="color:red"></span>
							</div>
							<div class="form-group col-md-3">
								<label for="Brand" >Brand</label>
								<span style="color: red;">*</span>
								<select name="brand[]" multiple id="brand" class="form-control">

									@foreach($brandData as $row)
										@if(isset($brandVal))
											@php $brndarr = explode(',',$brandVal);@endphp
											@if(in_array($row->id, $brndarr))
												<option value="{{$row->id}}" selected>{{$row->brand}} </option>
											@else
												<option value="{{$row->id}}">{{$row->brand}} </option>
											@endif
										@endif
										@if(!isset($brandVal))
											<option value="{{$row->id}}" >{{$row->brand}}</option>
										@endif
									@endforeach

								</select>
								<span id="brand_error" style="color:red"></span>
							</div>
							<div class="form-group col-md-3" id="td_product">
								<label for="product">Product</label>
								<span style="color: red;">*</span>
								<select name="product[]" multiple id="product" class="form-control" onchange="UserProductChanged(this.value,'')">

									@isset($vehicleData)
										@foreach($vehicleData as $proRow)
											@if(isset($product))
												@php $productarr = explode(',',$product);@endphp
												@if(in_array($proRow->id, $productarr))
													<option value="{{$proRow->id}}" selected>{{$proRow->vehicle}} </option>
												@else
													<option value="{{$proRow->id}}">{{$proRow->vehicle}} </option>
												@endif
											@endif
											@if(!isset($product))
												<option value="{{$proRow->id}}" >{{$proRow->vehicle}}</option>
											@endif
										@endforeach
									@endisset
								</select>
								<span id="product_error" style="color:red"></span>
							</div>
							<div class="form-group col-md-3" id="td_segment">
								<label for="Name">Product Segment</label>
								<span style="color: red;">*</span>
								<select name="segment[]" multiple id="segment" class="form-control">

								</select>
								<span id="segment_error" style="color:red"></span>
							</div>
							<div class="form-group col-md-3">
								<label for="Zone" >Region</label>
								<span style="color: red;">*</span>
								<select name="zone[]" id="zone1" multiple class="form-control" onchange="getDealerByZoneId(this.value,'');">

									@isset($zoneData)
										@foreach($zoneData as $Data)
											@if(isset($zoneVal))
												@php $zonearr=array();$zonearr = explode(',',$zoneVal); @endphp
												@if(in_array($Data->id, $zonearr))
													<option value="{{$Data->id}}" selected>{{$Data->region}} </option>
												@else
													<option value="{{$Data->id}}">{{$Data->region}} </option>
												@endif
											@endif
											@if(!isset($zoneVal))
												<option value="{{$Data->id}}" >{{$Data->region}}</option>
											@endif
										@endforeach
									@endisset
								</select>
								<span id="zone_error" style="color:red"></span>
							</div>
							<div class="form-group col-md-3">
								<label for="Dealer" >Dealer</label>
								<span style="color: red;">*</span>
								<select name="Dealer[]" multiple id="Dealer" class="form-control"></select>
								<span id="Dealer_error" style="color:red"></span>
							</div>
							<div class="form-group col-md-3">
								<label for="complaint_type">Complaint Type</label>
								<span style="color: red;">*</span>
								<select name="complaintType[]" multiple id="complaintType" class="form-control">

									@isset($complaintTypeData)
										@foreach($complaintTypeData as $row)
											@if(isset($complaintType))
												@php $complaintTypearr = explode(',',$complaintType);@endphp
												@if(in_array($row->id, $complaintTypearr))
													<option value="{{$row->id}}" selected>{{$row->complaint_type}} </option>
												@else
													<option value="{{$row->id}}">{{$row->complaint_type}} </option>
												@endif
											@endif
											@if(!isset($complaintType))
												<option value="{{$row->id}}" >{{$row->complaint_type}}</option>
											@endif
										@endforeach
									@endisset
								</select>
								<span id="complaint_type_error" style="color:red"></span>
							</div>
						</div>
						<div class="clear"></div>
						<hr>
						<div class="row">
							<div class="form-group col-md-3">
								<input type="submit"name="submit" id="submit" value="Submit" class="btn-secondary" onclick="getData()">
							</div>
						</div>
					</form>

				@else
					@php
						$location1 = Session::get('city');
                        $product1 = Session::get('product');
                        $brand1 = Session::get('brand');
                        
                        $loc =explode(',',$location1);
                        $pro =explode(',',$product1);
                        $brnd =explode(',',$brand1);
                        
					@endphp
					<form name="myForm" method="post" enctype="multipart/form-data" action="{{url('store-kpi-report')}}" onsubmit="return topFocusValidate()">
						<input type="hidden" name="_token" value="{{csrf_token()}}">
						<input type="hidden" name="productId" id="productId" value="@isset($product){{$product}} @endisset">
						<input type="hidden" name="segmentId" id="segmentId" value="@isset($segment){{$segment}} @endisset">
						<input type="hidden" name="zoneId" id="zoneId" value="@isset($zoneVal){{$zoneVal}} @endisset">
						<input type="hidden" name="DealerId" id="DealerId" value="@isset($dealerVal){{$dealerVal}} @endisset">
						<input type="hidden" name="StatusId" id="StatusId" value="@isset($Status){{$Status}} @endisset">

						<div class="row">
							<div class="form-group col-md-3">
								<label for="year" >Year</label>
								<span style="color: red;">*</span>
								<select name="year" id="year" class="form-control">
									<option value="NA">Select</option>
									@for($i=2017,$j=18;$i<=$current,$j<=$next;$i++,$j++)

										<option value="{{$i.'-'.$j}}" @isset($yearVal){{$i.'-'.$j ==$yearVal?'selected':''}} @endisset>{{$i.'-'.$j}}</option>
									@endfor

								</select>
								<span id="brand_error" style="color:red"></span>
							</div>
							<div class="form-group col-md-3">
								<label for="Brand" >Brand</label>
								<span style="color: #ff0000;">*</span>
								<select name="brand[]" multiple id="brand" class="form-control">

									@foreach($brandData as $row)
										@if(isset($brandVal))
											@php $brndarr = explode(',',$brandVal);@endphp
											@if(in_array($row->id, $brndarr))
												<option value="{{$row->id}}" selected>{{$row->brand}} </option>
											@else
												<option value="{{$row->id}}">{{$row->brand}} </option>
											@endif
										@endif
										@if(!isset($brandVal))
											@if(in_array($row->id,$brnd))
												<option value="{{$row->id}}" >{{$row->brand}}</option>
											@endif
										@endif
									@endforeach

								</select>
								<span id="brand_error" style="color:red"></span>
							</div>
							<div class="form-group col-md-3" id="td_product">
								<label for="product">Product</label>
								<span style="color: red;">*</span>
								<select name="product[]" multiple id="product" class="form-control" onchange="UserProductChanged(this.value,'')">

									@isset($vehicleData)
										@foreach($vehicleData as $proRow)
											@if(isset($product))
												@php $productarr = explode(',',$product);@endphp
												@if(in_array($proRow->id, $productarr))
													<option value="{{$proRow->id}}" selected>{{$proRow->vehicle}} </option>
												@else
													<option value="{{$proRow->id}}">{{$proRow->vehicle}} </option>
												@endif
											@endif
											@if(!isset($product))
												@if(in_array($proRow->id,$pro))
													<option value="{{$proRow->id}}" >{{$proRow->vehicle}}</option>
												@endif
											@endif
										@endforeach
									@endisset
								</select>
								<span id="product_error" style="color:red"></span>
							</div>
							<div class="form-group col-md-3" id="td_segment">
								<label for="Name">Product Segment</label>
								<span style="color: red;">*</span>
								<select name="segment[]" multiple id="segment" class="form-control">

								</select>
								<span id="segment_error" style="color:red"></span>
							</div>
							<div class="form-group col-md-3">
								<label for="Zone" >Region</label>
								<span style="color: red;">*</span>
								<select name="zone[]" id="zone1" multiple class="form-control" onchange="getDealerByZoneId(this.value,'');">

									@isset($zoneData)
										@foreach($zoneData as $Data)
											@if(isset($zoneVal))
												@php $zonearr=array();$zonearr = explode(',',$zoneVal); @endphp
												@if(in_array($Data->id, $zonearr))
													<option value="{{$Data->id}}" selected>{{$Data->region}} </option>
												@else
													<option value="{{$Data->id}}">{{$Data->region}} </option>
												@endif
											@endif
											@if(!isset($zoneVal))
												@if(in_array($Data->id,$loc))
													<option value="{{$Data->id}}" >{{$Data->region}}</option>
												@endif
											@endif
										@endforeach
									@endisset
								</select>
								<span id="zone_error" style="color:red"></span>
							</div>
							<div class="form-group col-md-3">
								<label for="Dealer" >Dealer</label>
								<span style="color: red;">*</span>
								<select name="Dealer[]" multiple id="Dealer" class="form-control"></select>
								<span id="Dealer_error" style="color:red"></span>
							</div>
							<div class="form-group col-md-3">
								<label for="complaint_type">Complaint Type</label>
								<span style="color: red;">*</span>
								<select name="complaintType[]" multiple id="complaintType" class="form-control">

									@isset($complaintTypeData)
										@foreach($complaintTypeData as $row)
											@if(isset($complaintType))
												@php $complaintTypearr = explode(',',$complaintType);@endphp
												@if(in_array($row->id, $complaintTypearr))
													<option value="{{$row->id}}" selected>{{$row->complaint_type}} </option>
												@else
													<option value="{{$row->id}}">{{$row->complaint_type}} </option>
												@endif
											@endif
											@if(!isset($complaintType))
												@if(in_array($row->id,$catId))
													<option value="{{$row->id}}" >{{$row->complaint_type}}</option>
												@endif
											@endif
										@endforeach
									@endisset
								</select>
								<span id="complaint_type_error" style="color:red"></span>
							</div>
						</div>
						<div class="clear"></div>
						<hr>
						<div class="row">
							<div class="form-group col-md-3">
								<input type="submit"name="submit" id="submit" value="Submit" class="btn-secondary" onclick="getData()">
							</div>
						</div>
					</form>
				@endif
				<div class="clear"></div><br>
				<div class="row">
					<div class="col-lg-12">
						<div class="table-responsive">
							<table id="order-listing1" class="table custom">
								<thead>

								<tr>
									<th>Category</th>
									<th>Details head</th>
									<th>Target</th>
									<th>Apr</th>
									<th>May</th>
									<th>Jun</th>
									<th class="tdHighlight">Q1</th>
									<th>Jul</th>
									<th>Aug</th>
									<th>Sep</th>
									<th class="tdHighlight">Q2</th>
									<th>Oct</th>
									<th>Now</th>
									<th>Dec</th>
									<th class="tdHighlight">Q3</th>
									<th>Jan</th>
									<th>Feb</th>
									<th>Mar</th>
									<th class="tdHighlight">Q4</th>
									<th style="display:none" ></th>
									<th style="display:none" ></th>
									<th style="display:none" ></th>
									<th class="tdHighlight">YTD</th>


								</tr>
								</thead>
								<tbody>

								<tr>
									<td>All categories</td>
									<td>No. of complaints Logged</td>
									<td></td>
									@isset($allCatLogged)
										@for($i=4,$j=0;$i<=15,$j<15;$i++,$j++)
											@php $mnth = !empty($allCatLogged[$j])?$allCatLogged[$j]->month:'0'; if($mnth ==1)$mnth=13;if($mnth ==2)$mnth=14;if($mnth ==3)$mnth=15; @endphp

											<td id="mnth_{{$i}}">{{!empty($allCatLogged[$j]->cnt)?$allCatLogged[$j]->cnt:0}}</td>
											@if($j==2)
												<td id="q1" class="tdHighlight">11</td>
											@elseif($j==5 )
												<td id="q2" class="tdHighlight">22</td>
											@elseif($j==8 )
												<td id="q3" class="tdHighlight">33</td>
											@elseif($j==11 )
												<td id="q4"  class="tdHighlight">44</td>
											@elseif($j==14 )
												<td id="ytd" class="tdHighlight"></td>
											@else

											@endif
										@endfor


									@endisset
								</tr>
								<tr>
									<td>All categories</td>
									<td>% acknowledged in 24 hrs</td>
									<td>98%</td>
									@isset($allCatAck)
										@for($i=4,$j=0;$i<=15,$j<15;$i++,$j++)
											@php $mnth = !empty($allCatAck[$j])?$allCatAck[$j]->months:'0'; if($mnth ==1)$mnth=13;if($mnth ==2)$mnth=14;if($mnth ==3)$mnth=15; @endphp
											@if($i==$mnth)
												<td id="ack_{{$mnth}}">{{$allCatAck[$j]->cnt}}</td>

											@endif
											@if($j==2)
												<td id="ack_q1" class="tdHighlight"></td>
											@elseif($j==5 )
												<td id="ack_q2" class="tdHighlight"></td>
											@elseif($j==8 )
												<td id="ack_q3" class="tdHighlight"></td>
											@elseif($j==11 )
												<td id="ack_q4" class="tdHighlight"></td>
											@elseif($j==14 )
												<td style="display: none;"></td>
												<td style="display: none;"></td>
												<td style="display: none;"></td>
												<td id="ack_ytd" class="tdHighlight"></td>
											@endif
										@endfor


									@endisset
								</tr>
								<tr>
									<td>All categories</td>
									<td>% of complaints closed in SLA</td>
									<td>90%</td>
									@isset($allCatSLA)
									
									
										@for($i=4,$j=0;$i<=15,$j<15;$i++,$j++)
											@php $mnth = !empty($allCatSLA[$j])?$allCatSLA[$j]->months:'0'; if($mnth ==1)$mnth=13;if($mnth ==2)$mnth=14;if($mnth ==3)$mnth=15; @endphp
											@if($i==$mnth)
												<td id="sla_{{$mnth}}">{{$allCatSLA[$j]->total}}</td>

											@endif
											@if($j==2)
												<td id="sla_q1" class="tdHighlight"></td>
											@elseif($j==5 )
												<td id="sla_q2" class="tdHighlight"></td>
											@elseif($j==8 )
												<td id="sla_q3" class="tdHighlight"></td>
											@elseif($j==11 )
												<td id="sla_q4" class="tdHighlight"></td>
											@elseif($j==14 )
												<td style="display: none;"></td>
												<td style="display: none;"></td>
												<td style="display: none;"></td>
												<td id="sla_ytd" class="tdHighlight"></td>
											@endif
										@endfor


									@endisset
								</tr>
								<tr>
									<td>All categories</td>
									<td>No of complaints open</td>
									<td></td>
									@isset($allCatOpen)
										@for($i=4,$j=0;$i<=15,$j<15;$i++,$j++)
											@php $mnth = !empty($allCatOpen[$j])?$allCatOpen[$j]->months:'0'; if($mnth ==1)$mnth=13;if($mnth ==2)$mnth=14;if($mnth ==3)$mnth=15; @endphp
											@if($i==$mnth)
												<td id="open_{{$mnth}}">{{$allCatOpen[$j]->total}}</td>

											@endif
											@if($j==2)
												<td id="open_q1" class="tdHighlight"></td>
											@elseif($j==5 )
												<td id="open_q2" class="tdHighlight"></td>
											@elseif($j==8 )
												<td id="open_q3" class="tdHighlight"></td>
											@elseif($j==11 )
												<td id="open_q4" class="tdHighlight"></td>
											@elseif($j==14 )
												<td style="display: none;"></td>
												<td style="display: none;"></td>
												<td style="display: none;"></td>
												<td id="open_ytd" class="tdHighlight"></td>
											@endif
										@endfor


									@endisset
								</tr>
								<tr>
									<td>All categories</td>
									<td>% of complaints Re-opened</td>
									<td>10%</td>
									@isset($allCatReopen)

										@for($i=4,$j=0;$i<=15,$j<15;$i++,$j++)
											@php $mnth = !empty($allCatReopen[$j])?$allCatReopen[$j]->months:'0'; if($mnth ==1)$mnth=13;if($mnth ==2)$mnth=14;if($mnth ==3)$mnth=15; @endphp
											@if($i==$mnth)
												<td id="reopen_{{$mnth}}">{{$allCatReopen[$j]->total}}</td>

											@endif
											@if($j==2)
												<td id="reopen_q1" class="tdHighlight"></td>
											@elseif($j==5 )
												<td id="reopen_q2" class="tdHighlight"></td>
											@elseif($j==8 )
												<td id="reopen_q3" class="tdHighlight"></td>
											@elseif($j==11 )
												<td id="reopen_q4" class="tdHighlight"></td>
											@elseif($j==14 )
												<td style="display: none;"></td>
												<td style="display: none;"></td>
												<td style="display: none;"></td>
												<td id="reopen_ytd" class="tdHighlight"></td>
											@endif
										@endfor


									@endisset
								</tr>
								<tr>
									<td>All categories</td>
									<td>No of Feedbacks Collected</td>
									<td></td>
									@isset($allCatFeedback)
										@for($i=4,$j=0;$i<=15,$j<15;$i++,$j++)
											@php $mnth = !empty($allCatFeedback[$j])?$allCatFeedback[$j]->months:'0'; if($mnth ==1)$mnth=13;if($mnth ==2)$mnth=14;if($mnth ==3)$mnth=15; @endphp
											@if($i==$mnth)
												<td id="feedback_{{$mnth}}">{{$allCatFeedback[$j]->total}}</td>

											@endif
											@if($j==2)
												<td id="feedback_q1" class="tdHighlight"></td>
											@elseif($j==5 )
												<td id="feedback_q2" class="tdHighlight"></td>
											@elseif($j==8 )
												<td id="feedback_q3" class="tdHighlight"></td>
											@elseif($j==11 )
												<td id="feedback_q4" class="tdHighlight"></td>
											@elseif($j==14 )
												<td style="display: none;"></td>
												<td style="display: none;"></td>
												<td style="display: none;"></td>
												<td id="feedback_ytd" class="tdHighlight"></td>
											@endif
										@endfor


									@endisset
								</tr>
								<tr>
									<td>All categories</td>
									<td>PCS Score</td>
									<td>70%</td>
									@isset($allCatPcs)

										@for($i=4,$j=0;$i<=15,$j<15;$i++,$j++)
											@php $mnth = !empty($allCatPcs[$j])?$allCatPcs[$j]->months:'0'; if($mnth ==1)$mnth=13;if($mnth ==2)$mnth=14;if($mnth ==3)$mnth=15; @endphp
											@if($i==$mnth)

												<span id="pcs_{{$mnth}}" style="display: none;" >{{$allCatPcs[$j]->feedback.'~~'.$allCatPcs[$j]->totalFeedback}}</span>
												<td >{{$allCatPcs[$j]->total!=''?$allCatPcs[$j]->total.'%':'0%'}}</td>

											@endif
											@if($j==2)
												<td id="pcs_q1" class="tdHighlight"></td>
											@elseif($j==5 )
												<td id="pcs_q2" class="tdHighlight"></td>
											@elseif($j==8 )
												<td id="pcs_q3" class="tdHighlight"></td>
											@elseif($j==11 )
												<td id="pcs_q4" class="tdHighlight"></td>
											@elseif($j==14 )
												<td style="display: none;"></td>
												<td style="display: none;"></td>
												<td style="display: none;"></td>
												<td id="pcs_ytd" class="tdHighlight"></td>
											@endif
										@endfor

									@endisset
								</tr>
								<!--********************************************************* -->
								@isset($allProLogged)


									<tr>
										<td>Product</td>
										<td>No. of complaints Logged</td>
										<td></td>
										@isset($allProLogged)
											@for($i=4,$j=0;$i<=15,$j<15;$i++,$j++)
												@php $mnth = !empty($allProLogged[$j])?$allProLogged[$j]->month:'0'; if($mnth ==1)$mnth=13;if($mnth ==2)$mnth=14;if($mnth ==3)$mnth=15; @endphp
												@if($i==$mnth)
													<td id="pro_{{$mnth}}">{{!empty($allProLogged[$j]->cnt)?$allProLogged[$j]->cnt:0}}</td>
												@endif
												@if($j==2)
													<td id="pro_q1" class="tdHighlight">11</td>
												@elseif($j==5 )
													<td id="pro_q2" class="tdHighlight">22</td>
												@elseif($j==8 )
													<td id="pro_q3" class="tdHighlight">33</td>
												@elseif($j==11 )
													<td id="pro_q4" class="tdHighlight">44</td>
												@elseif($j==14 )
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td style="display: none;"></td>

													<td id="pro_ytd" class="tdHighlight"></td>
												@else

												@endif

											@endfor

										@endisset
									</tr>
									<tr>
										<td>Product</td>
										<td>% acknowledged in 24 hrs</td>
										<td>98%</td>
										@isset($allProAck)
											@for($i=4,$j=0;$i<=15,$j<15;$i++,$j++)
												@php $mnth = !empty($allProAck[$j])?$allProAck[$j]->months:'0'; if($mnth ==1)$mnth=13;if($mnth ==2)$mnth=14;if($mnth ==3)$mnth=15; @endphp
												@if($i==$mnth)
													<td id="proack_{{$mnth}}">{{$allProAck[$j]->cnt}}</td>

												@endif
												@if($j==2)
													<td id="proack_q1" class="tdHighlight"></td>
												@elseif($j==5 )
													<td id="proack_q2" class="tdHighlight"></td>
												@elseif($j==8 )
													<td id="proack_q3" class="tdHighlight"></td>
												@elseif($j==11 )
													<td id="proack_q4" class="tdHighlight"></td>
												@elseif($j==14 )
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td id="proack_ytd" class="tdHighlight"></td>
												@endif
											@endfor


										@endisset
									</tr>
									<tr>
										<td>Product</td>
										<td>% of complaints closed in 30 days</td>
										<td>85%</td>
										@isset($allProSLA)

											@for($i=4,$j=0;$i<=15,$j<15;$i++,$j++)
												@php $mnth = !empty($allProSLA[$j])?$allProSLA[$j]->months:'0'; if($mnth ==1)$mnth=13;if($mnth ==2)$mnth=14;if($mnth ==3)$mnth=15; @endphp
												@if($i==$mnth)
													<td id="prosla_{{$mnth}}">{{$allProSLA[$j]->total}}</td>

												@endif
												@if($j==2)
													<td id="prosla_q1" class="tdHighlight"></td>
												@elseif($j==5 )
													<td id="prosla_q2" class="tdHighlight"></td>
												@elseif($j==8 )
													<td id="prosla_q3" class="tdHighlight"></td>
												@elseif($j==11 )
													<td id="prosla_q4" class="tdHighlight"></td>
												@elseif($j==14 )
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td style="display: none;"></td>

													<td id="prosla_ytd" class="tdHighlight"></td>
												@endif
											@endfor

										@endisset
									</tr>
									<tr>
										<td>Product</td>
										<td>No of complaints open</td>
										<td></td>
										@isset($allProOpen)
											@for($i=4,$j=0;$i<=15,$j<15;$i++,$j++)
												@php $mnth = !empty($allProOpen[$j])?$allProOpen[$j]->months:'0'; if($mnth ==1)$mnth=13;if($mnth ==2)$mnth=14;if($mnth ==3)$mnth=15; @endphp
												@if($i==$mnth)
													<td id="proopen_{{$mnth}}">{{$allProOpen[$j]->total}}</td>

												@endif
												@if($j==2)
													<td id="proopen_q1" class="tdHighlight"></td>
												@elseif($j==5 )
													<td id="proopen_q2" class="tdHighlight"></td>
												@elseif($j==8 )
													<td id="proopen_q3" class="tdHighlight"></td>
												@elseif($j==11 )
													<td id="proopen_q4" class="tdHighlight"></td>
												@elseif($j==14 )
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td id="proopen_ytd" class="tdHighlight"></td>
												@endif
											@endfor


										@endisset
									</tr>
									<tr>
										<td>Product</td>
										<td>% of complaints Re-opened</td>
										<td>10%</td>
										@isset($allProReopen)

											@for($i=4,$j=0;$i<=15,$j<15;$i++,$j++)
												@php $mnth = !empty($allProReopen[$j])?$allProReopen[$j]->months:'0'; if($mnth ==1)$mnth=13;if($mnth ==2)$mnth=14;if($mnth ==3)$mnth=15; @endphp
												@if($i==$mnth)
													<td id="proreopen_{{$mnth}}">{{$allProReopen[$j]->total}}</td>

												@endif
												@if($j==2)
													<td id="proreopen_q1" class="tdHighlight"></td>
												@elseif($j==5 )
													<td id="proreopen_q2" class="tdHighlight"></td>
												@elseif($j==8 )
													<td id="proreopen_q3" class="tdHighlight"></td>
												@elseif($j==11 )
													<td id="proreopen_q4" class="tdHighlight"></td>
												@elseif($j==14 )
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td id="proreopen_ytd" class="tdHighlight"></td>
												@endif
											@endfor


										@endisset
									</tr>
									<tr>
										<td>Product</td>
										<td>No of Feedbacks Collected</td>
										<td></td>
										@isset($allProFeedback)

											@for($i=4,$j=0;$i<=15,$j<15;$i++,$j++)
												@php $mnth = !empty($allProFeedback[$j])?$allProFeedback[$j]->months:'0'; if($mnth ==1)$mnth=13;if($mnth ==2)$mnth=14;if($mnth ==3)$mnth=15; @endphp
												@if($i==$mnth)
													<td id="profeedback_{{$mnth}}">{{$allProFeedback[$j]->total}}</td>

												@endif
												@if($j==2)
													<td id="profeedback_q1" class="tdHighlight"></td>
												@elseif($j==5 )
													<td id="profeedback_q2" class="tdHighlight"></td>
												@elseif($j==8 )
													<td id="profeedback_q3" class="tdHighlight"></td>
												@elseif($j==11 )
													<td id="profeedback_q4" class="tdHighlight"></td>
												@elseif($j==14 )
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td id="profeedback_ytd" class="tdHighlight"></td>
												@endif
											@endfor


										@endisset
									</tr>
									<tr>
										<td>Product</td>
										<td>PCS Score</td>
										<td>70%</td>
										@isset($allProPcs)

											@for($i=4,$j=0;$i<=15,$j<15;$i++,$j++)
												@php $mnth = !empty($allProPcs[$j])?$allProPcs[$j]->months:'0'; if($mnth ==1)$mnth=13;if($mnth ==2)$mnth=14;if($mnth ==3)$mnth=15; @endphp
												@if($i==$mnth)
													<span id="propcs_{{$mnth}}" style="display: none;">{{$allProPcs[$j]->feedback.'~~'.$allProPcs[$j]->totalFeedback}}</span>
													<td>{{$allProPcs[$j]->total!=''?$allProPcs[$j]->total.'%':'0%'}}</td>

												@endif
												@if($j==2)
													<td id="propcs_q1" class="tdHighlight"></td>
												@elseif($j==5 )
													<td id="propcs_q2" class="tdHighlight"></td>
												@elseif($j==8 )
													<td id="propcs_q3" class="tdHighlight"></td>
												@elseif($j==11 )
													<td id="propcs_q4" class="tdHighlight"></td>
												@elseif($j==14 )
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td id="propcs_ytd" class="tdHighlight"></td>
												@endif
											@endfor

										@endisset
									</tr>
								@endisset
								<!--********************************************************* -->
								@isset($allParLogged)


									<tr>
										<td>Parts</td>
										<td>No. of complaints Logged</td>
										<td></td>
										@isset($allParLogged)

											@for($i=4,$j=0;$i<=15,$j<15;$i++,$j++)
												@php $mnth = !empty($allParLogged[$j])?$allParLogged[$j]->month:'0'; if($mnth ==1)$mnth=13;if($mnth ==2)$mnth=14;if($mnth ==3)$mnth=15; @endphp
												@if($i==$mnth)
													<td id="part_{{$mnth}}">{{$allParLogged[$j]->cnt}}</td>
												@endif
												@if($j==2)
													<td id="part_q1" class="tdHighlight">11</td>
												@elseif($j==5 )
													<td id="part_q2" class="tdHighlight">22</td>
												@elseif($j==8 )
													<td id="part_q3" class="tdHighlight">33</td>
												@elseif($j==11 )
													<td id="part_q4" class="tdHighlight">44</td>
												@elseif($j==14 )
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td style="display: none;"></td>

													<td id="part_ytd" class="tdHighlight"></td>

												@endif
											@endfor

										@endisset
									</tr>
									<tr>
										<td>Parts</td>
										<td>% acknowledged in 24 hrs</td>
										<td>98%</td>
										@isset($allParAck)
											@for($i=4,$j=0;$i<=15,$j<15;$i++,$j++)
												@php $mnth = !empty($allParAck[$j])?$allParAck[$j]->months:'0'; if($mnth ==1)$mnth=13;if($mnth ==2)$mnth=14;if($mnth ==3)$mnth=15; @endphp
												@if($i==$mnth)
													<td id="parack_{{$mnth}}">{{$allParAck[$j]->cnt}}</td>

												@endif
												@if($j==2)
													<td id="parack_q1" class="tdHighlight"></td>
												@elseif($j==5 )
													<td id="parack_q2" class="tdHighlight"></td>
												@elseif($j==8 )
													<td id="parack_q3" class="tdHighlight"></td>
												@elseif($j==11 )
													<td id="parack_q4" class="tdHighlight"></td>
												@elseif($j==14 )
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td style="display: none;"></td>

													<td id="parack_ytd" class="tdHighlight"></td>
												@endif
											@endfor

										@endisset
									</tr>
									<tr>
										<td>Parts</td>
										<td>% of complaints closed in 5 days</td>
										<td>90%</td>
										@isset($allParSLA)
										
											@for($i=4,$j=0;$i<=15,$j<15;$i++,$j++)
												@php $mnth = !empty($allParSLA[$j])?$allParSLA[$j]->months:'0'; if($mnth ==1)$mnth=13;if($mnth ==2)$mnth=14;if($mnth ==3)$mnth=15; @endphp
												@if($i==$mnth)
													<td id="parsla_{{$mnth}}">{{$allParSLA[$j]->total}}</td>

												@endif
												@if($j==2)
													<td id="parsla_q1" class="tdHighlight"></td>
												@elseif($j==5 )
													<td id="parsla_q2" class="tdHighlight"></td>
												@elseif($j==8 )
													<td id="parsla_q3" class="tdHighlight"></td>
												@elseif($j==11 )
													<td id="parsla_q4" class="tdHighlight"></td>
												@elseif($j==14 )
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td style="display: none;"></td>

													<td id="parsla_ytd" class="tdHighlight"></td>
												@endif
											@endfor

										@endisset
									</tr>
									<tr>
										<td>Parts</td>
										<td>No of complaints open</td>
										<td></td>
										@isset($allParOpen)
											@for($i=4,$j=0;$i<=15,$j<15;$i++,$j++)
												@php $mnth = !empty($allParOpen[$j])?$allParOpen[$j]->months:'0'; if($mnth ==1)$mnth=13;if($mnth ==2)$mnth=14;if($mnth ==3)$mnth=15; @endphp
												@if($i==$mnth)
													<td id="paropen_{{$mnth}}">{{$allParOpen[$j]->total}}</td>

												@endif
												@if($j==2)
													<td id="paropen_q1" class="tdHighlight"></td>
												@elseif($j==5 )
													<td id="paropen_q2" class="tdHighlight"></td>
												@elseif($j==8 )
													<td id="paropen_q3" class="tdHighlight"></td>
												@elseif($j==11 )
													<td id="paropen_q4" class="tdHighlight"></td>
												@elseif($j==14 )
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td id="paropen_ytd" class="tdHighlight"></td>
												@endif
											@endfor


										@endisset
									</tr>
									<tr>
										<td>Parts</td>
										<td>% of complaints Re-opened</td>
										<td>10%</td>
										@isset($allParReopen)

											@for($i=4,$j=0;$i<=15,$j<15;$i++,$j++)
												@php $mnth = !empty($allParReopen[$j])?$allParReopen[$j]->months:'0'; if($mnth ==1)$mnth=13;if($mnth ==2)$mnth=14;if($mnth ==3)$mnth=15; @endphp
												@if($i==$mnth)
													<td id="parreopen_{{$mnth}}">{{$allParReopen[$j]->total}}</td>

												@endif
												@if($j==2)
													<td id="parreopen_q1" class="tdHighlight"></td>
												@elseif($j==5 )
													<td id="parreopen_q2" class="tdHighlight"></td>
												@elseif($j==8 )
													<td id="parreopen_q3" class="tdHighlight"></td>
												@elseif($j==11 )
													<td id="parreopen_q4" class="tdHighlight"></td>
												@elseif($j==14 )
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td id="parreopen_ytd" class="tdHighlight"></td>
												@endif
											@endfor


										@endisset
									</tr>
									<tr>
										<td>Parts</td>
										<td>No of Feedbacks Collected</td>
										<td></td>
										@isset($allParFeedback)

											@for($i=4,$j=0;$i<=15,$j<15;$i++,$j++)
												@php $mnth = !empty($allParFeedback[$j])?$allParFeedback[$j]->months:'0'; if($mnth ==1)$mnth=13;if($mnth ==2)$mnth=14;if($mnth ==3)$mnth=15; @endphp
												@if($i==$mnth)
													<td id="parfeedback_{{$mnth}}">{{$allParFeedback[$j]->total}}</td>

												@endif
												@if($j==2)
													<td id="parfeedback_q1" class="tdHighlight"></td>
												@elseif($j==5 )
													<td id="parfeedback_q2" class="tdHighlight"></td>
												@elseif($j==8 )
													<td id="parfeedback_q3" class="tdHighlight"></td>
												@elseif($j==11 )
													<td id="parfeedback_q4" class="tdHighlight"></td>
												@elseif($j==14 )
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td id="parfeedback_ytd" class="tdHighlight"></td>
												@endif
											@endfor


										@endisset
									</tr>
									<tr>
										<td>Parts</td>
										<td>PCS Score</td>
										<td>70%</td>
										@isset($allParPcs)

											@for($i=4,$j=0;$i<=15,$j<15;$i++,$j++)
												@php $mnth = !empty($allParPcs[$j])?$allParPcs[$j]->months:'0'; if($mnth ==1)$mnth=13;if($mnth ==2)$mnth=14;if($mnth ==3)$mnth=15; @endphp
												@if($i==$mnth)
													<span id="parpcs_{{$mnth}}" style="display: none;">{{$allParPcs[$j]->feedback.'~~'.$allParPcs[$j]->totalFeedback}}</span>
													<td>{{$allParPcs[$j]->total!=''?$allParPcs[$j]->total.'%':'0%'}}</td>

												@endif
												@if($j==2)
													<td id="parpcs_q1" class="tdHighlight"></td>
												@elseif($j==5 )
													<td id="parpcs_q2" class="tdHighlight"></td>
												@elseif($j==8 )
													<td id="parpcs_q3" class="tdHighlight"></td>
												@elseif($j==11 )
													<td id="parpcs_q4" class="tdHighlight"></td>
												@elseif($j==14 )
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td id="parpcs_ytd" class="tdHighlight"></td>
												@endif
											@endfor

										@endisset
									</tr>
								@endisset
								<!--********************************************************* -->
								@isset($allSerLogged)


									<tr>
										<td>Service</td>
										<td>No. of complaints Logged</td>
										<td></td>
										@isset($allSerLogged)
											@for($i=4,$j=0;$i<=15,$j<15;$i++,$j++)
												@php $mnth = !empty($allSerLogged[$j])?$allSerLogged[$j]->month:'0'; if($mnth ==1)$mnth=13;if($mnth ==2)$mnth=14;if($mnth ==3)$mnth=15; @endphp
												@if($i==$mnth)
													<td id="ser_{{$mnth}}">{{!empty($allSerLogged[$j]->cnt)?$allSerLogged[$j]->cnt:0}}</td>
												@endif
												@if($j==2)
													<td id="ser_q1" class="tdHighlight">11</td>
												@elseif($j==5 )
													<td id="ser_q2" class="tdHighlight">22</td>
												@elseif($j==8 )
													<td id="ser_q3" class="tdHighlight">33</td>
												@elseif($j==11 )
													<td id="ser_q4" class="tdHighlight">44</td>
												@elseif($j==14 )
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td id="ser_ytd" class="tdHighlight"></td>
												@else

												@endif
											@endfor


										@endisset
									</tr>
									<tr><td>Service</td>
										<td>% acknowledged in 24 hrs</td>
										<td>98%</td>
										@isset($allSerAck)
											@for($i=4,$j=0;$i<=15,$j<15;$i++,$j++)
												@php $mnth = !empty($allSerAck[$j])?$allSerAck[$j]->months:'0'; if($mnth ==1)$mnth=13;if($mnth ==2)$mnth=14;if($mnth ==3)$mnth=15; @endphp
												@if($i==$mnth)
													<td id="serack_{{$mnth}}">{{$allSerAck[$j]->cnt}}</td>

												@endif
												@if($j==2)
													<td id="serack_q1" class="tdHighlight"></td>
												@elseif($j==5 )
													<td id="serack_q2" class="tdHighlight"></td>
												@elseif($j==8 )
													<td id="serack_q3" class="tdHighlight"></td>
												@elseif($j==11 )
													<td id="serack_q4" class="tdHighlight"></td>
												@elseif($j==14 )
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td id="serack_ytd" class="tdHighlight"></td>
												@endif
											@endfor


										@endisset
									</tr>
									<tr><td>Service</td>
										<td>% Of complaints closed in 5 days</td>
										<td>90%</td>
										@isset($allSerSLA)
											@for($i=4,$j=0;$i<=15,$j<15;$i++,$j++)
												@php $mnth = !empty($allSerSLA[$j])?$allSerSLA[$j]->months:'0'; if($mnth ==1)$mnth=13;if($mnth ==2)$mnth=14;if($mnth ==3)$mnth=15; @endphp
												@if($i==$mnth)
													<td id="sersla_{{$mnth}}">{{$allSerSLA[$j]->total}}</td>

												@endif
												@if($j==2)
													<td id="sersla_q1" class="tdHighlight"></td>
												@elseif($j==5 )
													<td id="sersla_q2" class="tdHighlight"></td>
												@elseif($j==8 )
													<td id="sersla_q3" class="tdHighlight"></td>
												@elseif($j==11 )
													<td id="sersla_q4" class="tdHighlight"></td>
												@elseif($j==14 )
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td id="sersla_ytd" class="tdHighlight"></td>
												@endif
											@endfor


										@endisset
									</tr>
									<tr><td>Service</td>
										<td>No of complaints open</td>
										<td></td>
										@isset($allSerOpen)
											@for($i=4,$j=0;$i<=15,$j<15;$i++,$j++)
												@php $mnth = !empty($allSerOpen[$j])?$allSerOpen[$j]->months:'0'; if($mnth ==1)$mnth=13;if($mnth ==2)$mnth=14;if($mnth ==3)$mnth=15; @endphp
												@if($i==$mnth)
													<td id="seropen_{{$mnth}}">{{$allSerOpen[$j]->total}}</td>

												@endif
												@if($j==2)
													<td id="seropen_q1" class="tdHighlight"></td>
												@elseif($j==5 )
													<td id="seropen_q2" class="tdHighlight"></td>
												@elseif($j==8 )
													<td id="seropen_q3" class="tdHighlight"></td>
												@elseif($j==11 )
													<td id="seropen_q4" class="tdHighlight"></td>
												@elseif($j==14 )
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td id="seropen_ytd" class="tdHighlight"></td>
												@endif
											@endfor


										@endisset
									</tr>
									<tr><td>Service</td>
										<td>% of complaints Re-opened</td>
										<td>10%</td>
										@isset($allSerReopen)

											@for($i=4,$j=0;$i<=15,$j<15;$i++,$j++)
												@php $mnth = !empty($allSerReopen[$j])?$allSerReopen[$j]->months:'0'; if($mnth ==1)$mnth=13;if($mnth ==2)$mnth=14;if($mnth ==3)$mnth=15; @endphp
												@if($i==$mnth)
													<td id="serreopen_{{$mnth}}">{{$allSerReopen[$j]->total}}</td>

												@endif
												@if($j==2)
													<td id="serreopen_q1" class="tdHighlight"></td>
												@elseif($j==5 )
													<td id="serreopen_q2" class="tdHighlight"></td>
												@elseif($j==8 )
													<td id="serreopen_q3" class="tdHighlight"></td>
												@elseif($j==11 )
													<td id="serreopen_q4" class="tdHighlight"></td>
												@elseif($j==14 )
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td id="serreopen_ytd" class="tdHighlight"></td>
												@endif
											@endfor


										@endisset
									</tr>
									<tr><td>Service</td>
										<td>No of Feedbacks Collected</td>
										<td></td>
										@isset($allSerFeedback)

											@for($i=4,$j=0;$i<=15,$j<15;$i++,$j++)
												@php $mnth = !empty($allSerFeedback[$j])?$allSerFeedback[$j]->months:'0'; if($mnth ==1)$mnth=13;if($mnth ==2)$mnth=14;if($mnth ==3)$mnth=15; @endphp
												@if($i==$mnth)
													<td id="serfeedback_{{$mnth}}">{{$allSerFeedback[$j]->total}}</td>

												@endif
												@if($j==2)
													<td id="serfeedback_q1" class="tdHighlight"></td>
												@elseif($j==5 )
													<td id="serfeedback_q2" class="tdHighlight"></td>
												@elseif($j==8 )
													<td id="serfeedback_q3" class="tdHighlight"></td>
												@elseif($j==11 )
													<td id="serfeedback_q4" class="tdHighlight"></td>
												@elseif($j==14 )
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td id="serfeedback_ytd" class="tdHighlight"></td>
												@endif
											@endfor


										@endisset
									</tr>
									<tr><td>Service</td>
										<td>PCS Score</td>
										<td>70%</td>
										@isset($allSerPcs)

											@for($i=4,$j=0;$i<=15,$j<15;$i++,$j++)
												@php $mnth = !empty($allSerPcs[$j])?$allSerPcs[$j]->months:'0'; if($mnth ==1)$mnth=13;if($mnth ==2)$mnth=14;if($mnth ==3)$mnth=15; @endphp
												@if($i==$mnth)
													<span id="serpcs_{{$mnth}}" style="display: none;">{{$allSerPcs[$j]->feedback.'~~'.$allSerPcs[$j]->totalFeedback}}</span>
													<td>{{$allSerPcs[$j]->total!=''?$allSerPcs[$j]->total.'%':'0%'}}</td>

												@endif
												@if($j==2)
													<td id="serpcs_q1" class="tdHighlight"></td>
												@elseif($j==5 )
													<td id="serpcs_q2" class="tdHighlight"></td>
												@elseif($j==8 )
													<td id="serpcs_q3" class="tdHighlight"></td>
												@elseif($j==11 )
													<td id="serpcs_q4" class="tdHighlight"></td>
												@elseif($j==14 )
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td id="serpcs_ytd" class="tdHighlight"></td>
												@endif
											@endfor

										@endisset
									</tr>
								@endisset
								<!--********************************************************* -->
								@isset($allSaleLogged)

									<tr><td>Sales</td>
										<td>No. of complaints Logged</td>
										<td></td>
										@isset($allSaleLogged)
											@for($i=4,$j=0;$i<=15,$j<15;$i++,$j++)
												@php $mnth = !empty($allSaleLogged[$j])?$allSaleLogged[$j]->month:'0'; if($mnth ==1)$mnth=13;if($mnth ==2)$mnth=14;if($mnth ==3)$mnth=15; @endphp
												@if($i==$mnth)
													<td id="sale_{{$mnth}}">{{!empty($allSaleLogged[$j]->cnt)?$allSaleLogged[$j]->cnt:0}}</td>
												@endif
												@if($j==2)
													<td id="sale_q1" class="tdHighlight">11</td>
												@elseif($j==5 )
													<td id="sale_q2" class="tdHighlight">22</td>
												@elseif($j==8 )
													<td id="sale_q3" class="tdHighlight">33</td>
												@elseif($j==11 )
													<td id="sale_q4" class="tdHighlight" >44</td>
												@elseif($j==14 )
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td id="sale_ytd" class="tdHighlight"></td>
												@else

												@endif
											@endfor

												

										@endisset
									</tr>
									<tr><td>Sales</td>
										<td>% acknowledged in 24 hrs</td>
										<td>98%</td>
										@isset($allSaleAck)
											@for($i=4,$j=0;$i<=15,$j<15;$i++,$j++)
												@php $mnth = !empty($allSaleAck[$j])?$allSaleAck[$j]->months:'0'; if($mnth ==1)$mnth=13;if($mnth ==2)$mnth=14;if($mnth ==3)$mnth=15; @endphp
												@if($i==$mnth)
													<td id="saleack_{{$mnth}}">{{$allSaleAck[$j]->cnt}}</td>

												@endif
												@if($j==2)
													<td id="saleack_q1" class="tdHighlight"></td>
												@elseif($j==5 )
													<td id="saleack_q2" class="tdHighlight"></td>
												@elseif($j==8 )
													<td id="saleack_q3" class="tdHighlight"></td>
												@elseif($j==11 )
													<td id="saleack_q4" class="tdHighlight"></td>
												@elseif($j==14 )
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td id="saleack_ytd" class="tdHighlight"></td>
												@endif
											@endfor


										@endisset
									</tr>
									<tr><td>Sales</td>
										<td>% Of complaints closed in 5 days</td>
										<td>90%</td>
										@isset($allSaleSLA)
											@for($i=4,$j=0;$i<=15,$j<15;$i++,$j++)
												@php $mnth = !empty($allSaleSLA[$j])?$allSaleSLA[$j]->months:'0'; if($mnth ==1)$mnth=13;if($mnth ==2)$mnth=14;if($mnth ==3)$mnth=15; @endphp
												@if($i==$mnth)
													<td id="salesla_{{$mnth}}">{{$allSaleSLA[$j]->total}}</td>

												@endif
												@if($j==2)
													<td id="salesla_q1" class="tdHighlight"></td>
												@elseif($j==5 )
													<td id="salesla_q2" class="tdHighlight" ></td>
												@elseif($j==8 )
													<td id="salesla_q3" class="tdHighlight"></td>
												@elseif($j==11 )
													<td id="salesla_q4" class="tdHighlight"></td>
												@elseif($j==14 )
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td id="salesla_ytd" class="tdHighlight"></td>
												@endif
											@endfor


										@endisset
									</tr>
									<tr><td>Sales</td>
										<td>No of complaints open</td>
										<td></td>
										@isset($allSaleOpen)
											@for($i=4,$j=0;$i<=15,$j<15;$i++,$j++)
												@php $mnth = !empty($allSaleOpen[$j])?$allSaleOpen[$j]->months:'0'; if($mnth ==1)$mnth=13;if($mnth ==2)$mnth=14;if($mnth ==3)$mnth=15; @endphp
												@if($i==$mnth)
													<td id="saleopen_{{$mnth}}">{{$allSaleOpen[$j]->total}}</td>

												@endif
												@if($j==2)
													<td id="saleopen_q1" class="tdHighlight"></td>
												@elseif($j==5 )
													<td id="saleopen_q2" class="tdHighlight"></td>
												@elseif($j==8 )
													<td id="saleopen_q3" class="tdHighlight"></td>
												@elseif($j==11 )
													<td id="saleopen_q4" class="tdHighlight"></td>
												@elseif($j==14 )
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td id="saleopen_ytd" class="tdHighlight"></td>
												@endif
											@endfor


										@endisset
									</tr>
									<tr><td>Sales</td>
										<td>% of complaints Re-opened</td>
										<td>10%</td>
										@isset($allSaleReopen)

											@for($i=4,$j=0;$i<=15,$j<15;$i++,$j++)
												@php $mnth = !empty($allSaleReopen[$j])?$allSaleReopen[$j]->months:'0'; if($mnth ==1)$mnth=13;if($mnth ==2)$mnth=14;if($mnth ==3)$mnth=15; @endphp
												@if($i==$mnth)
													<td id="salereopen_{{$mnth}}">{{$allSaleReopen[$j]->total}}</td>

												@endif
												@if($j==2)
													<td id="salereopen_q1" class="tdHighlight"></td>
												@elseif($j==5 )
													<td id="salereopen_q2" class="tdHighlight"></td>
												@elseif($j==8 )
													<td id="salereopen_q3" class="tdHighlight"></td>
												@elseif($j==11 )
													<td id="salereopen_q4" class="tdHighlight"></td>
												@elseif($j==14 )
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td id="salereopen_ytd" class="tdHighlight"></td>
												@endif
											@endfor

										@endisset
									</tr>
									<tr><td>Sales</td>
										<td>No of Feedbacks Collected</td>
										<td></td>
										@isset($allSaleFeedback)

											@for($i=4,$j=0;$i<=15,$j<15;$i++,$j++)
												@php $mnth = !empty($allSaleFeedback[$j])?$allSaleFeedback[$j]->months:'0'; if($mnth ==1)$mnth=13;if($mnth ==2)$mnth=14;if($mnth ==3)$mnth=15; @endphp
												@if($i==$mnth)
													<td id="salefeedback_{{$mnth}}">{{$allSaleFeedback[$j]->total}}</td>

												@endif
												@if($j==2)
													<td id="salefeedback_q1" class="tdHighlight"></td>
												@elseif($j==5 )
													<td id="salefeedback_q2" class="tdHighlight"></td>
												@elseif($j==8 )
													<td id="salefeedback_q3" class="tdHighlight"></td>
												@elseif($j==11 )
													<td id="salefeedback_q4" class="tdHighlight"></td>
												@elseif($j==14 )
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td id="salefeedback_ytd" class="tdHighlight"></td>
												@endif
											@endfor


										@endisset
									</tr>
									<tr><td>Sales</td>
										<td>PCS Score</td>
										<td>70%</td>
										@isset($allSalePcs)

											@for($i=4,$j=0;$i<=15,$j<15;$i++,$j++)
												@php $mnth = !empty($allSalePcs[$j])?$allSalePcs[$j]->months:'0'; if($mnth ==1)$mnth=13;if($mnth ==2)$mnth=14;if($mnth ==3)$mnth=15; @endphp
												@if($i==$mnth)
													<span id="salepcs_{{$mnth}}" style="display: none;">{{$allSalePcs[$j]->feedback.'~~'.$allSalePcs[$j]->totalFeedback}}</span>
													<td >{{$allSalePcs[$j]->total!=''?$allSalePcs[$j]->total.'%':'0%'}}</td>

												@endif
												@if($j==2)
													<td id="salepcs_q1" class="tdHighlight"></td>
												@elseif($j==5 )
													<td id="salepcs_q2" class="tdHighlight"></td>
												@elseif($j==8 )
													<td id="salepcs_q3" class="tdHighlight"></td>
												@elseif($j==11 )
													<td id="salepcs_q4" class="tdHighlight"></td>
												@elseif($j==14 )
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td style="display: none;"></td>
													<td id="salepcs_ytd" class="tdHighlight"></td>
												@endif
											@endfor

										@endisset
									</tr>
								@endisset
								</tbody>
							</table>
						</div>
					</div>


				</div>


			</div>
		</div>
		<style>
			.table thead th {
				border-top: 1px solid #ddd;
				border-bottom-width: 1px;
				font-weight: 700;
				font-size: 0.75rem;
				color: #7987a1;
				letter-spacing: 0.031rem;
				padding: 0.312rem 0.937rem;
				vertical-align: middle;
			}
			.custom{
				text-align: center;
				border-collapse: collapse;

			}
			.custom td, .custom th {
				border: 1px solid #ddd;
				padding: 8px;
				white-space: nowrap;
				text-align: left;
			}
			.custom th {
				font-size: 14px !important;
			}
			.tdHighlight{
				background: #ccc;
				color: #fff !important;
			}
		</style>
		<script type="text/javascript">
			$(document).ready(function () {
				var product =$('#productId').val();
				var segment =$('#segmentId').val();
				var zone =$('#zoneId').val();
				var Dealer =$('#DealerId').val();
				if (product !='') {
					UserProductChanged(product,segment);
				}
				if (zone !='') {
					getDealerByZoneId(zone,Dealer);
				}
			});
			function UserProductChanged(el,ell){
				//alert(ell);
				var myarray= [];
				var favorite = [];
				if(ell!='')
				{
					var zz='';
				}
				else
				{
					$('#product :selected').each(function(i, sel)
					{
						favorite.push($(this).val());
					});
					var zz=favorite.join(",");

				}
				zz = zz !=''?zz:el;

				$.ajax({ url: '{{url("get-multi-product-segment")}}',
					data: { 'product_id':zz},
					success: function(data){
						// alert(data);// 1,Mining~2,C & I~3,On-Road~4,Special App~
						var Result = data.split("~");var str = '';
						Result.pop();
						var custIds = new Array(ell.trim());
						var selectedIds = custIds.join(',').split(',');
						for (item1 in Result) {
							var Result2 = Result[item1].split(",");
							if (ell!='') {
								if (jQuery.inArray( Result2[0], selectedIds ) !== -1 ) {

									str += "<option value='" + Result2[0] + "' selected>" +Result2[1] + "</option>";
								}
								else
								{
									str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
								}
							}
							else
							{
								str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
							}
						}
						document.getElementById('segment').innerHTML = str;
					}
				});
			}

			function getDealerByZoneId(el, ell)
			{

				var myarray= [];
				var favorite = [];
				if (ell!='') {
					var zz='';
				} else {
					$('#zone1 :selected').each(function(i, sel) {
						favorite.push($(this).val());
					});
					var zz=favorite.join(",");

				}
				zz = zz !=''?zz:el;
				$.ajax({
					url: '{{url("get-dealer-by-zone-id-report")}}',
					data :{'zone_id':zz},
					success: function(data) {

						var Result = data.split(",");
						var str='';
						Result.pop();
						var custIds = new Array(ell.trim());
						var selectedIds = custIds.join(',').split(',');
						for (item1 in Result) {
							var Result2 = Result[item1].split("~");
							if (ell!='') {
								if (jQuery.inArray( Result2[0], selectedIds ) !== -1 ) {

									str += "<option value='" + Result2[0] + "' selected>" +Result2[1] + "</option>";
								} else {
									str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
								}
							} else {
								str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
							}

						}
						document.getElementById('Dealer').innerHTML = str;
					}
				});
			}
		</script>

		<script type="text/javascript">

			$(document).ready(function() {
				/*----------------------------------------**************************************************-----------------------------*/
				$('#mnth_16').hide();
				$('#mnth_17').hide();
				$('#mnth_18').hide();
				// Standard initialisation
				var mnth4 = $("#order-listing1 #mnth_4").text()!=''?$("#order-listing1 #mnth_4").text():'0';
				var mnth5 = $("#order-listing1 #mnth_5").text()!=''?$("#order-listing1 #mnth_5").text():'0';
				var mnth6 = $("#order-listing1 #mnth_6").text()!=''?$("#order-listing1 #mnth_6").text():'0';
				var mnth7 = $("#order-listing1 #mnth_7").text()!=''?$("#order-listing1 #mnth_7").text():'0';
				var mnth8 = $("#order-listing1 #mnth_8").text()!=''?$("#order-listing1 #mnth_8").text():'0';
				var mnth9 = $("#order-listing1 #mnth_9").text()!=''?$("#order-listing1 #mnth_9").text():'0';
				var mnth10 = $("#order-listing1 #mnth_10").text()!=''?$("#order-listing1 #mnth_10").text():'0';
				var mnth11 = $("#order-listing1 #mnth_11").text()!=''?$("#order-listing1 #mnth_11").text():'0';
				var mnth12 = $("#order-listing1 #mnth_12").text()!=''?$("#order-listing1 #mnth_12").text():'0';
				var mnth13 = $("#order-listing1 #mnth_13").text()!=''?$("#order-listing1 #mnth_13").text():'0';
				var mnth14 = $("#order-listing1 #mnth_14").text()!=''?$("#order-listing1 #mnth_14").text():'0';
				var mnth15 = $("#order-listing1 #mnth_15").text()!=''?$("#order-listing1 #mnth_15").text():'0';

				var q1 = parseInt(mnth4)+parseInt(mnth5)+parseInt(mnth6);
				$("#order-listing1 #q1").text(q1);
				var q2 = parseInt(mnth7)+parseInt(mnth8)+parseInt(mnth9);

				$("#order-listing1 #q2").text(q2);
				var q3 = parseInt(mnth10)+parseInt(mnth11)+parseInt(mnth12);
				$("#order-listing1 #q3").text(q3);
				var q4 = parseInt(mnth13)+parseInt(mnth14)+parseInt(mnth15);
				$("#order-listing1 #q4").text(q4);
				var ytd = (q1)+(q2)+(q3)+(q4);
				$("#order-listing1 #ytd").text(ytd);

				/*----------------------------------------**************************************************-----------------------------*/

				var ack4 = $("#order-listing1 #ack_4").text()!=''?$("#order-listing1 #ack_4").text():'0';
				var ack5 = $("#order-listing1 #ack_5").text()!=''?$("#order-listing1 #ack_5").text():'0';
				var ack6 = $("#order-listing1 #ack_6").text()!=''?$("#order-listing1 #ack_6").text():'0';
				var ack7 = $("#order-listing1 #ack_7").text()!=''?$("#order-listing1 #ack_7").text():'0';
				var ack8 = $("#order-listing1 #ack_8").text()!=''?$("#order-listing1 #ack_8").text():'0';
				var ack9 = $("#order-listing1 #ack_9").text()!=''?$("#order-listing1 #ack_9").text():'0';
				var ack10 = $("#order-listing1 #ack_10").text()!=''?$("#order-listing1 #ack_10").text():'0';
				var ack11 = $("#order-listing1 #ack_11").text()!=''?$("#order-listing1 #ack_11").text():'0';
				var ack12 = $("#order-listing1 #ack_12").text()!=''?$("#order-listing1 #ack_12").text():'0';
				var ack13 = $("#order-listing1 #ack_13").text()!=''?$("#order-listing1 #ack_13").text():'0';
				var ack14 = $("#order-listing1 #ack_14").text()!=''?$("#order-listing1 #ack_14").text():'0';
				var ack15 = $("#order-listing1 #ack_15").text()!=''?$("#order-listing1 #ack_15").text():'0';

				var ack4Value = isNaN(Math.round((parseInt(ack4)/ parseInt(mnth4))*100))?'':Math.round((parseInt(ack4)/ parseInt(mnth4))*100) ;
				var ack5Value = isNaN(Math.round((parseInt(ack5)/ parseInt(mnth5))*100))?'':Math.round((parseInt(ack5)/ parseInt(mnth5))*100);
				var ack6Value = isNaN(Math.round((parseInt(ack6)/ parseInt(mnth6))*100))?'':Math.round((parseInt(ack6)/ parseInt(mnth6))*100);
				var ack7Value = isNaN(Math.round((parseInt(ack7)/ parseInt(mnth7))*100))?'':Math.round((parseInt(ack7)/ parseInt(mnth7))*100);
				var ack8Value = isNaN(Math.round((parseInt(ack8)/ parseInt(mnth8))*100))?'':Math.round((parseInt(ack8)/ parseInt(mnth8))*100) ;
				var ack9Value = isNaN(Math.round((parseInt(ack9)/ parseInt(mnth9))*100))?'':Math.round((parseInt(ack9)/ parseInt(mnth9))*100) ;
				var ack10Value = isNaN(Math.round((parseInt(ack10)/ parseInt(mnth10))*100))?'':Math.round((parseInt(ack10)/ parseInt(mnth10))*100);
				var ack11Value = isNaN(Math.round((parseInt(ack11)/ parseInt(mnth11))*100))?'':Math.round((parseInt(ack11)/ parseInt(mnth11))*100);
				var ack12Value = isNaN(Math.round((parseInt(ack12)/ parseInt(mnth12))*100))?'':Math.round((parseInt(ack12)/ parseInt(mnth12))*100);
				var ack13Value = isNaN(Math.round((parseInt(ack13)/ parseInt(mnth13))*100))?'':Math.round((parseInt(ack13)/ parseInt(mnth13))*100);
				var ack14Value = isNaN(Math.round((parseInt(ack14)/ parseInt(mnth14))*100))?'':Math.round((parseInt(ack14)/ parseInt(mnth14))*100) ;
				var ack15Value = isNaN(Math.round((parseInt(ack15)/ parseInt(mnth15))*100))?'':Math.round((parseInt(ack15)/ parseInt(mnth15))*100) ;

				ack4Value = ack4Value!=''?ack4Value:'0';
				ack5Value = ack5Value!=''?ack5Value:'0';
				ack6Value = ack6Value!=''?ack6Value:'0';
				ack7Value = ack7Value!=''?ack7Value:'0';
				ack8Value = ack8Value!=''?ack8Value:'0';
				ack9Value = ack9Value!=''?ack9Value:'0';
				ack10Value = ack10Value!=''?ack10Value:'0';
				ack11Value = ack11Value!=''?ack11Value:'0';
				ack12Value = ack12Value!=''?ack12Value:'0';
				ack13Value = ack13Value!=''?ack13Value:'0';
				ack14Value = ack14Value!=''?ack14Value:'0';
				ack15Value = ack15Value!=''?ack15Value:'0';

				$("#order-listing1 #ack_4").text(ack4Value+'%');
				$("#order-listing1 #ack_5").text(ack5Value+'%');
				$("#order-listing1 #ack_6").text(ack6Value+'%');
				$("#order-listing1 #ack_7").text(ack7Value+'%');
				$("#order-listing1 #ack_8").text(ack8Value+'%');
				$("#order-listing1 #ack_9").text(ack9Value+'%');
				$("#order-listing1 #ack_10").text(ack10Value+'%');
				$("#order-listing1 #ack_11").text(ack11Value+'%');
				$("#order-listing1 #ack_12").text(ack12Value+'%');
				$("#order-listing1 #ack_13").text(ack13Value+'%');
				$("#order-listing1 #ack_14").text(ack14Value+'%');
				$("#order-listing1 #ack_15").text(ack15Value+'%');
				var ack_avg_q1=(parseInt(ack4)+parseInt(ack5)+parseInt(ack6))/(q1);
				var ack_avg_q2=(parseInt(ack7)+parseInt(ack8)+parseInt(ack9))/(q2);
				var ack_avg_q3=(parseInt(ack10)+parseInt(ack11)+parseInt(ack12))/(q3);
				var ack_avg_q4=(parseInt(ack13)+parseInt(ack14)+parseInt(ack15))/(q4);
				var ack_avg_ytd =(parseInt(ack4)+parseInt(ack5)+parseInt(ack6) + parseInt(ack7)+parseInt(ack8)+parseInt(ack9) + parseInt(ack10)+parseInt(ack11)+parseInt(ack12) + parseInt(ack13)+parseInt(ack14)+parseInt(ack15))/ytd;
				
				var ack_q1 = isNaN(Math.round(ack_avg_q1*100))?'0':Math.round(ack_avg_q1*100);
				$("#order-listing1 #ack_q1").text(ack_q1+'%');
				var ack_q2 =isNaN(Math.round(ack_avg_q2*100))?'0':Math.round(ack_avg_q2*100);
				$("#order-listing1 #ack_q2").text(ack_q2+'%');
				var ack_q3 = isNaN(Math.round(ack_avg_q3*100))?'0':Math.round(ack_avg_q3*100);
				$("#order-listing1 #ack_q3").text(ack_q3+'%');
				var ack_q4 = isNaN(Math.round(ack_avg_q4*100))?'0':Math.round(ack_avg_q4*100);
				$("#order-listing1 #ack_q4").text(ack_q4+'%');
				var ack_ytd = isNaN(Math.round(ack_avg_ytd*100))?'0':Math.round(ack_avg_ytd*100);
				$("#order-listing1 #ack_ytd").text(ack_ytd+'%');

				/*----------------------------------------**************************************************-----------------------------*/
				var sla4 = $("#order-listing1 #sla_4").text()!=''?$("#order-listing1 #sla_4").text():'0';
				var sla5 = $("#order-listing1 #sla_5").text()!=''?$("#order-listing1 #sla_5").text():'0';
				var sla6 = $("#order-listing1 #sla_6").text()!=''?$("#order-listing1 #sla_6").text():'0';
				var sla7 = $("#order-listing1 #sla_7").text()!=''?$("#order-listing1 #sla_7").text():'0';
				var sla8 = $("#order-listing1 #sla_8").text()!=''?$("#order-listing1 #sla_8").text():'0';
				var sla9 = $("#order-listing1 #sla_9").text()!=''?$("#order-listing1 #sla_9").text():'0';
				var sla10 = $("#order-listing1 #sla_10").text()!=''?$("#order-listing1 #sla_10").text():'0';
				var sla11 = $("#order-listing1 #sla_11").text()!=''?$("#order-listing1 #sla_11").text():'0';
				var sla12 = $("#order-listing1 #sla_12").text()!=''?$("#order-listing1 #sla_12").text():'0';
				var sla13 = $("#order-listing1 #sla_13").text()!=''?$("#order-listing1 #sla_13").text():'0';
				var sla14 = $("#order-listing1 #sla_14").text()!=''?$("#order-listing1 #sla_14").text():'0';
				var sla15 = $("#order-listing1 #sla_15").text()!=''?$("#order-listing1 #sla_15").text():'0';
				
				var sla4Value = isNaN(Math.round((parseInt(sla4)/ parseInt(mnth4)))*100)?'':Math.round((parseInt(sla4)/ parseInt(mnth4))*100);
				
				var sla5Value = isNaN(Math.round((parseInt(sla5)/ parseInt(mnth5)))*100)?'':Math.round((parseInt(sla5)/ parseInt(mnth5))*100);
				var sla6Value = isNaN(Math.round((parseInt(sla6)/ parseInt(mnth6)))*100)?'':Math.round((parseInt(sla6)/ parseInt(mnth6))*100);
				var sla7Value = isNaN(Math.round((parseInt(sla7)/ parseInt(mnth7)))*100)?'':Math.round((parseInt(sla7)/ parseInt(mnth7))*100);
				var sla8Value = isNaN(Math.round((parseInt(sla8)/ parseInt(mnth8)))*100)?'':Math.round((parseInt(sla8)/ parseInt(mnth8))*100);
				var sla9Value = isNaN(Math.round((parseInt(sla9)/ parseInt(mnth9)))*100)?'':Math.round((parseInt(sla9)/ parseInt(mnth9))*100);
				var sla10Value =isNaN(Math.round( (parseInt(sla10)/ parseInt(mnth10)))*100)?'':Math.round( (parseInt(sla10)/ parseInt(mnth10))*100);
				var sla11Value =isNaN(Math.round( (parseInt(sla11)/ parseInt(mnth11)))*100)?'':Math.round( (parseInt(sla11)/ parseInt(mnth11))*100);
				var sla12Value = isNaN(Math.round((parseInt(sla12)/ parseInt(mnth12)))*100)?'':Math.round((parseInt(sla12)/ parseInt(mnth12))*100);
				var sla13Value = isNaN(Math.round((parseInt(sla13)/ parseInt(mnth13)))*100)?'':Math.round((parseInt(sla13)/ parseInt(mnth13))*100);
				var sla14Value = isNaN(Math.round((parseInt(sla14)/ parseInt(mnth14)))*100)?'':Math.round((parseInt(sla14)/ parseInt(mnth14))*100);
				var sla15Value = isNaN(Math.round((parseInt(sla15)/ parseInt(mnth15)))*100)?'':Math.round((parseInt(sla15)/ parseInt(mnth15))*100);

				sla4Value = sla4Value!=''?sla4Value:'0';
				sla5Value = sla5Value!=''?sla5Value:'0';
				sla6Value = sla6Value!=''?sla6Value:'0';
				sla7Value = sla7Value!=''?sla7Value:'0';
				sla8Value = sla8Value!=''?sla8Value:'0';
				sla9Value = sla9Value!=''?sla9Value:'0';
				sla10Value = sla10Value!=''?sla10Value:'0';
				sla11Value = sla11Value!=''?sla11Value:'0';
				sla12Value = sla12Value!=''?sla12Value:'0';
				sla13Value = sla13Value!=''?sla13Value:'0';
				sla14Value = sla14Value!=''?sla14Value:'0';
				sla15Value = sla15Value!=''?sla15Value:'0';

				$("#order-listing1 #sla_4").text(sla4Value+'%');
				$("#order-listing1 #sla_5").text(sla5Value+'%');
				$("#order-listing1 #sla_6").text(sla6Value+'%');
				$("#order-listing1 #sla_7").text(sla7Value+'%');
				$("#order-listing1 #sla_8").text(sla8Value+'%');
				$("#order-listing1 #sla_9").text(sla9Value+'%');
				$("#order-listing1 #sla_10").text(sla10Value+'%');
				$("#order-listing1 #sla_11").text(sla11Value+'%');
				$("#order-listing1 #sla_12").text(sla12Value+'%');
				$("#order-listing1 #sla_13").text(sla13Value+'%');
				$("#order-listing1 #sla_14").text(sla14Value+'%');
				$("#order-listing1 #sla_15").text(sla15Value+'%');
				var sla_avg_q1=(parseInt(sla4)+parseInt(sla5)+parseInt(sla6))/(q1);
				var sla_avg_q2=(parseInt(sla7)+parseInt(sla8)+parseInt(sla9))/(q2);
				var sla_avg_q3=(parseInt(sla10)+parseInt(sla11)+parseInt(sla12))/(q3);
				var sla_avg_q4=(parseInt(sla13)+parseInt(sla14)+parseInt(sla15))/(q4);
				var sla_avg_ytd =(parseInt(sla4)+parseInt(sla5)+parseInt(sla6) + parseInt(sla7)+parseInt(sla8)+parseInt(sla9) + parseInt(sla10)+parseInt(sla11)+parseInt(sla12) + parseInt(sla13)+parseInt(sla14)+parseInt(sla15))/ytd;
				var sla_q1 = isNaN(Math.round(sla_avg_q1*100))?'0':Math.round(sla_avg_q1*100);
				$("#order-listing1 #sla_q1").text(sla_q1+'%');
				var sla_q2 = isNaN(Math.round(sla_avg_q2*100))?'0':Math.round(sla_avg_q2*100);
				$("#order-listing1 #sla_q2").text(sla_q2+'%');
				var sla_q3 = isNaN(Math.round(sla_avg_q3*100))?'0':Math.round(sla_avg_q3*100);
				$("#order-listing1 #sla_q3").text(sla_q3+'%');
				var sla_q4 = isNaN(Math.round(sla_avg_q4*100))?'0':Math.round(sla_avg_q4*100);
				$("#order-listing1 #sla_q4").text(sla_q4+'%');
				var sla_ytd = isNaN(Math.round(sla_avg_ytd*100))?'0':Math.round(sla_avg_ytd*100);
				$("#order-listing1 #sla_ytd").text(sla_ytd+'%');

				/*----------------------------------------**************************************************-----------------------------*/

				var open4 = $("#order-listing1 #open_4").text()!=''?$("#order-listing1 #open_4").text():'0';
				var open5 = $("#order-listing1 #open_5").text()!=''?$("#order-listing1 #open_5").text():'0';
				var open6 = $("#order-listing1 #open_6").text()!=''?$("#order-listing1 #open_6").text():'0';
				var open7 = $("#order-listing1 #open_7").text()!=''?$("#order-listing1 #open_7").text():'0';
				var open8 = $("#order-listing1 #open_8").text()!=''?$("#order-listing1 #open_8").text():'0';
				var open9 = $("#order-listing1 #open_9").text()!=''?$("#order-listing1 #open_9").text():'0';
				var open10 = $("#order-listing1 #open_10").text()!=''?$("#order-listing1 #open_10").text():'0';
				var open11 = $("#order-listing1 #open_11").text()!=''?$("#order-listing1 #open_11").text():'0';
				var open12 = $("#order-listing1 #open_12").text()!=''?$("#order-listing1 #open_12").text():'0';
				var open13 = $("#order-listing1 #open_13").text()!=''?$("#order-listing1 #open_13").text():'0';
				var open14 = $("#order-listing1 #open_14").text()!=''?$("#order-listing1 #open_14").text():'0';
				var open15 = $("#order-listing1 #open_15").text()!=''?$("#order-listing1 #open_15").text():'0';

				var openq1 = parseInt(open4)+parseInt(open5)+parseInt(open6);
				$("#order-listing1 #open_q1").text(openq1);
				var openq2 = parseInt(open7)+parseInt(open8)+parseInt(open9);

				$("#order-listing1 #open_q2").text(openq2);
				var openq3 = parseInt(open10)+parseInt(open11)+parseInt(open12);
				$("#order-listing1 #open_q3").text(openq3);
				var openq4 = parseInt(open13)+parseInt(open14)+parseInt(open15);
				$("#order-listing1 #open_q4").text(openq4);
				var openytd = parseInt(openq1)+parseInt(openq2)+parseInt(openq3)+parseInt(openq4);
				$("#order-listing1 #open_ytd").text(openytd);

				/*----------------------------------------**************************************************-----------------------------*/

				var reopen4 = $("#order-listing1 #reopen_4").text()!=''?$("#order-listing1 #reopen_4").text():'0';
				var reopen5 = $("#order-listing1 #reopen_5").text()!=''?$("#order-listing1 #reopen_5").text():'0';
				var reopen6 = $("#order-listing1 #reopen_6").text()!=''?$("#order-listing1 #reopen_6").text():'0';
				var reopen7 = $("#order-listing1 #reopen_7").text()!=''?$("#order-listing1 #reopen_7").text():'0';
				var reopen8 = $("#order-listing1 #reopen_8").text()!=''?$("#order-listing1 #reopen_8").text():'0';
				var reopen9 = $("#order-listing1 #reopen_9").text()!=''?$("#order-listing1 #reopen_9").text():'0';
				var reopen10 = $("#order-listing1 #reopen_10").text()!=''?$("#order-listing1 #reopen_10").text():'0';
				var reopen11 = $("#order-listing1 #reopen_11").text()!=''?$("#order-listing1 #reopen_11").text():'0';
				var reopen12 = $("#order-listing1 #reopen_12").text()!=''?$("#order-listing1 #reopen_12").text():'0';
				var reopen13 = $("#order-listing1 #reopen_13").text()!=''?$("#order-listing1 #reopen_13").text():'0';
				var reopen14 = $("#order-listing1 #reopen_14").text()!=''?$("#order-listing1 #reopen_14").text():'0';
				var reopen15 = $("#order-listing1 #reopen_15").text()!=''?$("#order-listing1 #reopen_15").text():'0';

				var reopen4Value =isNaN(Math.round((parseInt(reopen4)/ parseInt(mnth4))*100))?'':Math.round((parseInt(reopen4)/ parseInt(mnth4))*100);
				var reopen5Value = isNaN(Math.round((parseInt(reopen5)/ parseInt(mnth5))*100))?'':Math.round((parseInt(reopen5)/ parseInt(mnth5))*100);
				var reopen6Value = isNaN(Math.round((parseInt(reopen6)/ parseInt(mnth6))*100))?'':Math.round((parseInt(reopen6)/ parseInt(mnth6))*100);
				var reopen7Value = isNaN(Math.round((parseInt(reopen7)/ parseInt(mnth7))*100))?'':Math.round((parseInt(reopen7)/ parseInt(mnth7))*100);
				var reopen8Value = isNaN(Math.round((parseInt(reopen8)/ parseInt(mnth8))*100))?'':Math.round((parseInt(reopen8)/ parseInt(mnth8))*100);
				var reopen9Value = isNaN(Math.round((parseInt(reopen9)/ parseInt(mnth9))*100))?'':Math.round((parseInt(reopen9)/ parseInt(mnth9))*100);
				var reopen10Value = isNaN(Math.round((parseInt(reopen10)/ parseInt(mnth10))*100))?'':Math.round((parseInt(reopen10)/ parseInt(mnth10))*100);
				var reopen11Value = isNaN(Math.round((parseInt(reopen11)/ parseInt(mnth11))*100))?'':Math.round((parseInt(reopen11)/ parseInt(mnth11))*100);
				var reopen12Value = isNaN(Math.round((parseInt(reopen12)/ parseInt(mnth12))*100))?'':Math.round((parseInt(reopen12)/ parseInt(mnth12))*100);
				var reopen13Value = isNaN(Math.round((parseInt(reopen13)/ parseInt(mnth13))*100))?'':Math.round((parseInt(reopen13)/ parseInt(mnth13))*100);
				var reopen14Value = isNaN(Math.round((parseInt(reopen14)/ parseInt(mnth14))*100))?'':Math.round((parseInt(reopen14)/ parseInt(mnth14))*100);
				var reopen15Value = isNaN(Math.round((parseInt(reopen15)/ parseInt(mnth15))*100))?'':Math.round((parseInt(reopen15)/ parseInt(mnth15))*100);

				reopen4Value = reopen4Value!=''?reopen4Value:'0';
				reopen5Value = reopen5Value!=''?reopen5Value:'0';
				reopen6Value = reopen6Value!=''?reopen6Value:'0';
				reopen7Value = reopen7Value!=''?reopen7Value:'0';
				reopen8Value = reopen8Value!=''?reopen8Value:'0';
				reopen9Value = reopen9Value!=''?reopen9Value:'0';
				reopen10Value = reopen10Value!=''?reopen10Value:'0';
				reopen11Value = reopen11Value!=''?reopen11Value:'0';
				reopen12Value = reopen12Value!=''?reopen12Value:'0';
				reopen13Value = reopen13Value!=''?reopen13Value:'0';
				reopen14Value = reopen14Value!=''?reopen14Value:'0';
				reopen15Value = reopen15Value!=''?reopen15Value:'0';

				$("#order-listing1 #reopen_4").text(reopen4Value+'%');
				$("#order-listing1 #reopen_5").text(reopen5Value+'%');
				$("#order-listing1 #reopen_6").text(reopen6Value+'%');
				$("#order-listing1 #reopen_7").text(reopen7Value+'%');
				$("#order-listing1 #reopen_8").text(reopen8Value+'%');
				$("#order-listing1 #reopen_9").text(reopen9Value+'%');
				$("#order-listing1 #reopen_10").text(reopen10Value+'%');
				$("#order-listing1 #reopen_11").text(reopen11Value+'%');
				$("#order-listing1 #reopen_12").text(reopen12Value+'%');
				$("#order-listing1 #reopen_13").text(reopen13Value+'%');
				$("#order-listing1 #reopen_14").text(reopen14Value+'%');
				$("#order-listing1 #reopen_15").text(reopen15Value+'%');
				
				var reopen_avg_q1=(parseInt(reopen4)+parseInt(reopen5)+parseInt(reopen6))/(q1);
				var reopen_avg_q2=(parseInt(reopen7)+parseInt(reopen8)+parseInt(reopen9))/(q2);
				var reopen_avg_q3=(parseInt(reopen10)+parseInt(reopen11)+parseInt(reopen12))/(q3);
				var reopen_avg_q4=(parseInt(reopen13)+parseInt(reopen14)+parseInt(reopen15))/(q4);
				var reopen_avg_ytd =(parseInt(reopen4)+parseInt(reopen5)+parseInt(reopen6) + parseInt(reopen7)+parseInt(reopen8)+parseInt(reopen9) + parseInt(reopen10)+parseInt(reopen11)+parseInt(reopen12) + parseInt(reopen13)+parseInt(reopen14)+parseInt(reopen15))/ytd;
				
				
				var reopen_q1 =isNaN(Math.round(reopen_avg_q1*100))?'0':Math.round(reopen_avg_q1*100);
				$("#order-listing1 #reopen_q1").text(reopen_q1+'%');
				var reopen_q2 = isNaN(Math.round(reopen_avg_q2*100))?'0':Math.round(reopen_avg_q2*100);
				$("#order-listing1 #reopen_q2").text(reopen_q2+'%');
				var reopen_q3 = isNaN(Math.round(reopen_avg_q3*100))?'0':Math.round(reopen_avg_q3*100);
				$("#order-listing1 #reopen_q3").text(reopen_q3+'%');
				var reopen_q4 = isNaN(Math.round(reopen_avg_q4*100))?'0':Math.round(reopen_avg_q4*100);
				$("#order-listing1 #reopen_q4").text(reopen_q4+'%');
				var reopen_ytd = isNaN(Math.round(reopen_avg_ytd*100))?'0':Math.round(reopen_avg_ytd*100);
				$("#order-listing1 #reopen_ytd").text(reopen_ytd+'%');

				/*----------------------------------------**************************************************-----------------------------*/

				var feedback4 = $("#order-listing1 #feedback_4").text()!=''?$("#order-listing1 #feedback_4").text():'0';
				var feedback5 = $("#order-listing1 #feedback_5").text()!=''?$("#order-listing1 #feedback_5").text():'0';
				var feedback6 = $("#order-listing1 #feedback_6").text()!=''?$("#order-listing1 #feedback_6").text():'0';
				var feedback7 = $("#order-listing1 #feedback_7").text()!=''?$("#order-listing1 #feedback_7").text():'0';
				var feedback8 = $("#order-listing1 #feedback_8").text()!=''?$("#order-listing1 #feedback_8").text():'0';
				var feedback9 = $("#order-listing1 #feedback_9").text()!=''?$("#order-listing1 #feedback_9").text():'0';
				var feedback10 = $("#order-listing1 #feedback_10").text()!=''?$("#order-listing1 #feedback_10").text():'0';
				var feedback11 = $("#order-listing1 #feedback_11").text()!=''?$("#order-listing1 #feedback_11").text():'0';
				var feedback12 = $("#order-listing1 #feedback_12").text()!=''?$("#order-listing1 #feedback_12").text():'0';
				var feedback13 = $("#order-listing1 #feedback_13").text()!=''?$("#order-listing1 #feedback_13").text():'0';
				var feedback14 = $("#order-listing1 #feedback_14").text()!=''?$("#order-listing1 #feedback_14").text():'0';
				var feedback15 = $("#order-listing1 #feedback_15").text()!=''?$("#order-listing1 #feedback_15").text():'0';

				var feedbackq1 = parseInt(feedback4)+parseInt(feedback5)+parseInt(feedback6);
				$("#order-listing1 #feedback_q1").text(feedbackq1);
				var feedbackq2 = parseInt(feedback7)+parseInt(feedback8)+parseInt(feedback9);

				$("#order-listing1 #feedback_q2").text(feedbackq2);
				var feedbackq3 = parseInt(feedback10)+parseInt(feedback11)+parseInt(feedback12);
				$("#order-listing1 #feedback_q3").text(feedbackq3);
				var feedbackq4 = parseInt(feedback13)+parseInt(feedback14)+parseInt(feedback15);
				$("#order-listing1 #feedback_q4").text(feedbackq4);
				var feedbackytd = feedbackq1+feedbackq2+feedbackq3+feedbackq4;
				$("#order-listing1 #feedback_ytd").text(feedbackytd);

				/*----------------------------------------**************************************************-----------------------------*/

				/*var pcs4 = $("#order-listing1 #pcs_4").text()!=''?$("#order-listing1 #pcs_4").text():'0';
				var pcs5 = $("#order-listing1 #pcs_5").text()!=''?$("#order-listing1 #pcs_5").text():'0';
				var pcs6 = $("#order-listing1 #pcs_6").text()!=''?$("#order-listing1 #pcs_6").text():'0';
				var pcs7 = $("#order-listing1 #pcs_7").text()!=''?$("#order-listing1 #pcs_7").text():'0';
				var pcs8 = $("#order-listing1 #pcs_8").text()!=''?$("#order-listing1 #pcs_8").text():'0';
				var pcs9 = $("#order-listing1 #pcs_9").text()!=''?$("#order-listing1 #pcs_9").text():'0';
				var pcs10 = $("#order-listing1 #pcs_10").text()!=''?$("#order-listing1 #pcs_10").text():'0';
				var pcs11 = $("#order-listing1 #pcs_11").text()!=''?$("#order-listing1 #pcs_11").text():'0';
				var pcs12 = $("#order-listing1 #pcs_12").text()!=''?$("#order-listing1 #pcs_12").text():'0';
				var pcs13 = $("#order-listing1 #pcs_13").text()!=''?$("#order-listing1 #pcs_13").text():'0';
				var pcs14 = $("#order-listing1 #pcs_14").text()!=''?$("#order-listing1 #pcs_14").text():'0';
				var pcs15 = $("#order-listing1 #pcs_15").text()!=''?$("#order-listing1 #pcs_15").text():'0';*/
				var pcs4 = $('#pcs_4').text();
				var pcs5 = $('#pcs_5').text();
				var pcs6 = $('#pcs_6').text();
				var pcs7 = $('#pcs_7').text();
				var pcs8 = $('#pcs_8').text();
				var pcs9 = $('#pcs_9').text();
				var pcs10 = $('#pcs_10').text();
				var pcs11 = $('#pcs_11').text();
				var pcs12 = $('#pcs_12').text();
				var pcs13 = $('#pcs_13').text();
				var pcs14 = $('#pcs_14').text();
				var pcs15 = $('#pcs_15').text();

				pcs4 =pcs4.split("~~");
				pcs5 =pcs5.split("~~");
				pcs6 =pcs6.split("~~");
				pcs7 =pcs7.split("~~");
				pcs8 =pcs8.split("~~");
				pcs9 =pcs9.split("~~");
				pcs10 =pcs10.split("~~");
				pcs11 =pcs11.split("~~");
				pcs12 =pcs12.split("~~");
				pcs13 =pcs13.split("~~");
				pcs14 =pcs14.split("~~");
				pcs15 =pcs15.split("~~");
				var q1Feedback = parseInt(pcs4[0])+parseInt(pcs5[0])+parseInt(pcs6[0]);
				var q1TotalFeedback = parseInt(pcs4[1])+parseInt(pcs5[1])+parseInt(pcs6[1]);
				q1TotalFeedback=q1TotalFeedback!=0?q1TotalFeedback:1;				
				var q1Avg = isNaN(Math.round((q1Feedback/q1TotalFeedback)*100))?'0':Math.round((q1Feedback/q1TotalFeedback)*100);			
				$("#order-listing1 #pcs_q1").text(q1Avg+'%');

				var q2Feedback = parseInt(pcs7[0])+parseInt(pcs8[0])+parseInt(pcs9[0]);
				var q2TotalFeedback = parseInt(pcs7[1])+parseInt(pcs8[1])+parseInt(pcs9[1]);
				q2TotalFeedback=q2TotalFeedback!=0?q2TotalFeedback:1;
				var q2Avg = isNaN(Math.round((q2Feedback/q2TotalFeedback)*100))?'0':Math.round((q2Feedback/q2TotalFeedback)*100);
				$("#order-listing1 #pcs_q2").text(q2Avg+'%');

				var q3Feedback = parseInt(pcs10[0])+parseInt(pcs11[0])+parseInt(pcs12[0]);
				var q3TotalFeedback = parseInt(pcs10[1])+parseInt(pcs11[1])+parseInt(pcs12[1]);
				q3TotalFeedback=q3TotalFeedback!=0?q3TotalFeedback:1;
				var q3Avg = isNaN(Math.round((q3Feedback/q3TotalFeedback)*100))?'0':Math.round((q3Feedback/q3TotalFeedback)*100);
				$("#order-listing1 #pcs_q3").text(q3Avg+'%');

				var q4Feedback = parseInt(pcs13[0])+parseInt(pcs14[0])+parseInt(pcs15[0]);
				var q4TotalFeedback = parseInt(pcs13[1])+parseInt(pcs14[1])+parseInt(pcs15[1]);
				q4TotalFeedback=q4TotalFeedback!=0?q4TotalFeedback:1;
				var q4Avg = isNaN(Math.round((q4Feedback/q4TotalFeedback)*100))?'0':Math.round((q4Feedback/q4TotalFeedback)*100);
				$("#order-listing1 #pcs_q4").text(q4Avg+'%');

				var ytdFeedback = (q1Feedback)+(q2Feedback)+(q3Feedback)+(q4Feedback);
				var ytdTotalFeedback = (parseInt(pcs4[1])+parseInt(pcs5[1])+parseInt(pcs6[1]) )+(parseInt(pcs7[1])+parseInt(pcs8[1])+parseInt(pcs9[1]) )+(parseInt(pcs10[1])+parseInt(pcs11[1])+parseInt(pcs12[1]) )+(parseInt(pcs13[1])+parseInt(pcs14[1])+parseInt(pcs15[1]) );
				ytdTotalFeedback=ytdTotalFeedback!=0?ytdTotalFeedback:1;
				
				var ytdAvg = isNaN(Math.round((ytdFeedback/ytdTotalFeedback)*100))?'0':Math.round((ytdFeedback/ytdTotalFeedback)*100);
				$("#order-listing1 #pcs_ytd").text(ytdAvg+'%');

				/* ****************************************Product*******************************************************/
				$('#pro_16').hide();
				$('#pro_17').hide();
				$('#pro_18').hide();
				var pro4 = $("#order-listing1 #pro_4").text()!=''?$("#order-listing1 #pro_4").text():'0';
				var pro5 = $("#order-listing1 #pro_5").text()!=''?$("#order-listing1 #pro_5").text():'0';
				var pro6 = $("#order-listing1 #pro_6").text()!=''?$("#order-listing1 #pro_6").text():'0';
				var pro7 = $("#order-listing1 #pro_7").text()!=''?$("#order-listing1 #pro_7").text():'0';
				var pro8 = $("#order-listing1 #pro_8").text()!=''?$("#order-listing1 #pro_8").text():'0';
				var pro9 = $("#order-listing1 #pro_9").text()!=''?$("#order-listing1 #pro_9").text():'0';
				var pro10 = $("#order-listing1 #pro_10").text()!=''?$("#order-listing1 #pro_10").text():'0';
				var pro11 = $("#order-listing1 #pro_11").text()!=''?$("#order-listing1 #pro_11").text():'0';
				var pro12 = $("#order-listing1 #pro_12").text()!=''?$("#order-listing1 #pro_12").text():'0';
				var pro13 = $("#order-listing1 #pro_13").text()!=''?$("#order-listing1 #pro_13").text():'0';
				var pro14 = $("#order-listing1 #pro_14").text()!=''?$("#order-listing1 #pro_14").text():'0';
				var pro15 = $("#order-listing1 #pro_15").text()!=''?$("#order-listing1 #pro_15").text():'0';

				var proq1 = parseInt(pro4)+parseInt(pro5)+parseInt(pro6);
				$("#order-listing1 #pro_q1").text(proq1);
				var proq2 = parseInt(pro7)+parseInt(pro8)+parseInt(pro9);

				$("#order-listing1 #pro_q2").text(proq2);
				var proq3 = parseInt(pro10)+parseInt(pro11)+parseInt(pro12);
				$("#order-listing1 #pro_q3").text(proq3);
				var proq4 = parseInt(pro13)+parseInt(pro14)+parseInt(pro15);
				$("#order-listing1 #pro_q4").text(proq4);
				var proytd = (proq1)+(proq2)+(proq3)+(proq4);
				$("#order-listing1 #pro_ytd").text(proytd);

				/*----------------------------------------**************************************************-----------------------------*/

				var proack4 = $("#order-listing1 #proack_4").text()!=''?$("#order-listing1 #proack_4").text():'0';
				var proack5 = $("#order-listing1 #proack_5").text()!=''?$("#order-listing1 #proack_5").text():'0';
				var proack6 = $("#order-listing1 #proack_6").text()!=''?$("#order-listing1 #proack_6").text():'0';
				var proack7 = $("#order-listing1 #proack_7").text()!=''?$("#order-listing1 #proack_7").text():'0';
				var proack8 = $("#order-listing1 #proack_8").text()!=''?$("#order-listing1 #proack_8").text():'0';
				var proack9 = $("#order-listing1 #proack_9").text()!=''?$("#order-listing1 #proack_9").text():'0';
				var proack10 = $("#order-listing1 #proack_10").text()!=''?$("#order-listing1 #proack_10").text():'0';
				var proack11 = $("#order-listing1 #proack_11").text()!=''?$("#order-listing1 #proack_11").text():'0';
				var proack12 = $("#order-listing1 #proack_12").text()!=''?$("#order-listing1 #proack_12").text():'0';
				var proack13 = $("#order-listing1 #proack_13").text()!=''?$("#order-listing1 #proack_13").text():'0';
				var proack14 = $("#order-listing1 #proack_14").text()!=''?$("#order-listing1 #proack_14").text():'0';
				var proack15 = $("#order-listing1 #proack_15").text()!=''?$("#order-listing1 #proack_15").text():'0';

				var proack4Value =isNaN(Math.round((parseInt(proack4)/ parseInt(pro4))*100))?'':Math.round((parseInt(proack4)/ parseInt(pro4))*100);
				var proack5Value = isNaN(Math.round((parseInt(proack5)/ parseInt(pro5))*100))?'':Math.round((parseInt(proack5)/ parseInt(pro5))*100);
				var proack6Value = isNaN(Math.round((parseInt(proack6)/ parseInt(pro6))*100))?'':Math.round((parseInt(proack6)/ parseInt(pro6))*100);
				var proack7Value = isNaN(Math.round((parseInt(proack7)/ parseInt(pro7))*100))?'':Math.round((parseInt(proack7)/ parseInt(pro7))*100);
				var proack8Value = isNaN(Math.round((parseInt(proack8)/ parseInt(pro8))*100))?'':Math.round((parseInt(proack8)/ parseInt(pro8))*100);
				var proack9Value = isNaN(Math.round((parseInt(proack9)/ parseInt(pro9))*100))?'':Math.round((parseInt(proack9)/ parseInt(pro9))*100);
				var proack10Value = isNaN(Math.round((parseInt(proack10)/ parseInt(pro10))*100))?'':Math.round((parseInt(proack10)/ parseInt(pro10))*100);
				var proack11Value = isNaN(Math.round((parseInt(proack11)/ parseInt(pro11))*100))?'':Math.round((parseInt(proack11)/ parseInt(pro11))*100);
				var proack12Value = isNaN(Math.round((parseInt(proack12)/ parseInt(pro12))*100))?'':Math.round((parseInt(proack12)/ parseInt(pro12))*100);
				var proack13Value = isNaN(Math.round((parseInt(proack13)/ parseInt(pro13))*100))?'':Math.round((parseInt(proack13)/ parseInt(pro13))*100);
				var proack14Value = isNaN(Math.round((parseInt(proack14)/ parseInt(pro14))*100))?'':Math.round((parseInt(proack14)/ parseInt(pro14))*100);
				var proack15Value = isNaN(Math.round((parseInt(proack15)/ parseInt(pro15))*100))?'':Math.round((parseInt(proack15)/ parseInt(pro15))*100);

				proack4Value = proack4Value!=''?proack4Value:'0';
				proack5Value = proack5Value!=''?proack5Value:'0';
				proack6Value = proack6Value!=''?proack6Value:'0';
				proack7Value = proack7Value!=''?proack7Value:'0';
				proack8Value = proack8Value!=''?proack8Value:'0';
				proack9Value = proack9Value!=''?proack9Value:'0';
				proack10Value = proack10Value!=''?proack10Value:'0';
				proack11Value = proack11Value!=''?proack11Value:'0';
				proack12Value = proack12Value!=''?proack12Value:'0';
				proack13Value = proack13Value!=''?proack13Value:'0';
				proack14Value = proack14Value!=''?proack14Value:'0';
				proack15Value = proack15Value!=''?proack15Value:'0';

				$("#order-listing1 #proack_4").text(proack4Value+'%');
				$("#order-listing1 #proack_5").text(proack5Value+'%');
				$("#order-listing1 #proack_6").text(proack6Value+'%');
				$("#order-listing1 #proack_7").text(proack7Value+'%');
				$("#order-listing1 #proack_8").text(proack8Value+'%');
				$("#order-listing1 #proack_9").text(proack9Value+'%');
				$("#order-listing1 #proack_10").text(proack10Value+'%');
				$("#order-listing1 #proack_11").text(proack11Value+'%');
				$("#order-listing1 #proack_12").text(proack12Value+'%');
				$("#order-listing1 #proack_13").text(proack13Value+'%');
				$("#order-listing1 #proack_14").text(proack14Value+'%');
				$("#order-listing1 #proack_15").text(proack15Value+'%');
				
				var proack_avg_q1=(parseInt(proack4)+parseInt(proack5)+parseInt(proack6))/(proq1);
				var proack_avg_q2=(parseInt(proack7)+parseInt(proack8)+parseInt(proack9))/(proq2);
				var proack_avg_q3=(parseInt(proack10)+parseInt(proack11)+parseInt(proack12))/(proq3);
				var proack_avg_q4=(parseInt(proack13)+parseInt(proack14)+parseInt(proack15))/(proq4);
				var proack_avg_ytd =(parseInt(proack4)+parseInt(proack5)+parseInt(proack6) + parseInt(proack7)+parseInt(proack8)+parseInt(proack9) + parseInt(proack10)+parseInt(proack11)+parseInt(proack12) + parseInt(proack13)+parseInt(proack14)+parseInt(proack15))/proytd;
				
				var proack_q1 =isNaN(Math.round(proack_avg_q1*100))?'0':Math.round(proack_avg_q1*100);
				$("#order-listing1 #proack_q1").text(proack_q1+'%');
				var proack_q2 = isNaN(Math.round(proack_avg_q2*100))?'0':Math.round(proack_avg_q2*100);
				$("#order-listing1 #proack_q2").text(proack_q2+'%');
				var proack_q3 = isNaN(Math.round(proack_avg_q3*100))?'0':Math.round(proack_avg_q3*100);
				$("#order-listing1 #proack_q3").text(proack_q3+'%');
				var proack_q4 = isNaN(Math.round(proack_avg_q4*100))?'0':Math.round(proack_avg_q4*100);
				$("#order-listing1 #proack_q4").text(proack_q4+'%');
				var proack_ytd = isNaN(Math.round(proack_avg_ytd*100))?'0':Math.round(proack_avg_ytd*100);
				$("#order-listing1 #proack_ytd").text(proack_ytd+'%');

				/*----------------------------------------**************************************************-----------------------------*/
				var prosla4 = $("#order-listing1 #prosla_4").text()!=''?$("#order-listing1 #prosla_4").text():'0';
				var prosla5 = $("#order-listing1 #prosla_5").text()!=''?$("#order-listing1 #prosla_5").text():'0';
				var prosla6 = $("#order-listing1 #prosla_6").text()!=''?$("#order-listing1 #prosla_6").text():'0';
				var prosla7 = $("#order-listing1 #prosla_7").text()!=''?$("#order-listing1 #prosla_7").text():'0';
				var prosla8 = $("#order-listing1 #prosla_8").text()!=''?$("#order-listing1 #prosla_8").text():'0';
				var prosla9 = $("#order-listing1 #prosla_9").text()!=''?$("#order-listing1 #prosla_9").text():'0';
				var prosla10 = $("#order-listing1 #prosla_10").text()!=''?$("#order-listing1 #prosla_10").text():'0';
				var prosla11 = $("#order-listing1 #prosla_11").text()!=''?$("#order-listing1 #prosla_11").text():'0';
				var prosla12 = $("#order-listing1 #prosla_12").text()!=''?$("#order-listing1 #prosla_12").text():'0';
				var prosla13 = $("#order-listing1 #prosla_13").text()!=''?$("#order-listing1 #prosla_13").text():'0';
				var prosla14 = $("#order-listing1 #prosla_14").text()!=''?$("#order-listing1 #prosla_14").text():'0';
				var prosla15 = $("#order-listing1 #prosla_15").text()!=''?$("#order-listing1 #prosla_15").text():'0';
				
				var prosla4Value = isNaN(Math.round((parseInt(prosla4)/ parseInt(pro4))*100))?'':Math.round((parseInt(prosla4)/ parseInt(pro4))*100) ;
				var prosla5Value = isNaN(Math.round((parseInt(prosla5)/ parseInt(pro5))*100))?'':Math.round((parseInt(prosla5)/ parseInt(pro5))*100) ;
				var prosla6Value = isNaN(Math.round((parseInt(prosla6)/ parseInt(pro6))*100))?'':Math.round((parseInt(prosla6)/ parseInt(pro6))*100) ;
				var prosla7Value = isNaN(Math.round((parseInt(prosla7)/ parseInt(pro7))*100))?'':Math.round((parseInt(prosla7)/ parseInt(pro7))*100) ;
				var prosla8Value = isNaN(Math.round((parseInt(prosla8)/ parseInt(pro8))*100))?'':Math.round((parseInt(prosla8)/ parseInt(pro8))*100) ;
				var prosla9Value = isNaN(Math.round((parseInt(prosla9)/ parseInt(pro9))*100))?'':Math.round((parseInt(prosla9)/ parseInt(pro9))*100) ;
				var prosla10Value = isNaN(Math.round((parseInt(prosla10)/ parseInt(pro10))*100))?'':Math.round((parseInt(prosla10)/ parseInt(pro10))*100) ;
				var prosla11Value = isNaN(Math.round((parseInt(prosla11)/ parseInt(pro11))*100))?'':Math.round((parseInt(prosla11)/ parseInt(pro11))*100) ;
				var prosla12Value = isNaN(Math.round((parseInt(prosla12)/ parseInt(pro12))*100))?'':Math.round((parseInt(prosla12)/ parseInt(pro12))*100) ;
				var prosla13Value = isNaN(Math.round((parseInt(prosla13)/ parseInt(pro13))*100))?'':Math.round((parseInt(prosla13)/ parseInt(pro13))*100) ;
				var prosla14Value = isNaN(Math.round((parseInt(prosla14)/ parseInt(pro14))*100))?'':Math.round((parseInt(prosla14)/ parseInt(pro14))*100) ;
				var prosla15Value = isNaN(Math.round((parseInt(prosla15)/ parseInt(pro15))*100))?'':Math.round((parseInt(prosla15)/ parseInt(pro15))*100) ;

				prosla4Value = prosla4Value!=''?prosla4Value:'0';
				prosla5Value = prosla5Value!=''?prosla5Value:'0';
				prosla6Value = prosla6Value!=''?prosla6Value:'0';
				prosla7Value = prosla7Value!=''?prosla7Value:'0';
				prosla8Value = prosla8Value!=''?prosla8Value:'0';
				prosla9Value = prosla9Value!=''?prosla9Value:'0';
				prosla10Value = prosla10Value!=''?prosla10Value:'0';
				prosla11Value = prosla11Value!=''?prosla11Value:'0';
				prosla12Value = prosla12Value!=''?prosla12Value:'0';
				prosla13Value = prosla13Value!=''?prosla13Value:'0';
				prosla14Value = prosla14Value!=''?prosla14Value:'0';
				prosla15Value = prosla15Value!=''?prosla15Value:'0';

				$("#order-listing1 #prosla_4").text(prosla4Value+'%');
				$("#order-listing1 #prosla_5").text(prosla5Value+'%');
				$("#order-listing1 #prosla_6").text(prosla6Value+'%');
				$("#order-listing1 #prosla_7").text(prosla7Value+'%');
				$("#order-listing1 #prosla_8").text(prosla8Value+'%');
				$("#order-listing1 #prosla_9").text(prosla9Value+'%');
				$("#order-listing1 #prosla_10").text(prosla10Value+'%');
				$("#order-listing1 #prosla_11").text(prosla11Value+'%');
				$("#order-listing1 #prosla_12").text(prosla12Value+'%');
				$("#order-listing1 #prosla_13").text(prosla13Value+'%');
				$("#order-listing1 #prosla_14").text(prosla14Value+'%');
				$("#order-listing1 #prosla_15").text(prosla15Value+'%');
				
				var prosla_avg_q1=(parseInt(prosla4)+parseInt(prosla5)+parseInt(prosla6))/(proq1);
				var prosla_avg_q2=(parseInt(prosla7)+parseInt(prosla8)+parseInt(prosla9))/(proq2);
				var prosla_avg_q3=(parseInt(prosla10)+parseInt(prosla11)+parseInt(prosla12))/(proq3);
				var prosla_avg_q4=(parseInt(prosla13)+parseInt(prosla14)+parseInt(prosla15))/(proq4);
				var prosla_avg_ytd =(parseInt(prosla4)+parseInt(prosla5)+parseInt(prosla6) + parseInt(prosla7)+parseInt(prosla8)+parseInt(prosla9) + parseInt(prosla10)+parseInt(prosla11)+parseInt(prosla12) + parseInt(prosla13)+parseInt(prosla14)+parseInt(prosla15))/proytd;
				
				var prosla_q1 = isNaN(Math.round(prosla_avg_q1*100))?'0':Math.round(prosla_avg_q1*100);
				$("#order-listing1 #prosla_q1").text(prosla_q1+'%');
				var prosla_q2 = isNaN(Math.round(prosla_avg_q2*100))?'0':Math.round(prosla_avg_q2*100) ;
				$("#order-listing1 #prosla_q2").text(prosla_q2+'%');
				var prosla_q3 = isNaN(Math.round(prosla_avg_q3*100))?'0':Math.round(prosla_avg_q3*100) ;
				$("#order-listing1 #prosla_q3").text(prosla_q3+'%');
				var prosla_q4 = isNaN(Math.round(prosla_avg_q4*100))?'0':Math.round(prosla_avg_q4*100);
				$("#order-listing1 #prosla_q4").text(prosla_q4+'%');
				var prosla_ytd = isNaN(Math.round(prosla_avg_ytd*100))?'0':Math.round(prosla_avg_ytd*100);
				$("#order-listing1 #prosla_ytd").text(prosla_ytd+'%');

				/*----------------------------------------**************************************************-----------------------------*/

				var proopen4 = $("#order-listing1 #proopen_4").text()!=''?$("#order-listing1 #proopen_4").text():'0';
				var proopen5 = $("#order-listing1 #proopen_5").text()!=''?$("#order-listing1 #proopen_5").text():'0';
				var proopen6 = $("#order-listing1 #proopen_6").text()!=''?$("#order-listing1 #proopen_6").text():'0';
				var proopen7 = $("#order-listing1 #proopen_7").text()!=''?$("#order-listing1 #proopen_7").text():'0';
				var proopen8 = $("#order-listing1 #proopen_8").text()!=''?$("#order-listing1 #proopen_8").text():'0';
				var proopen9 = $("#order-listing1 #proopen_9").text()!=''?$("#order-listing1 #proopen_9").text():'0';
				var proopen10 = $("#order-listing1 #proopen_10").text()!=''?$("#order-listing1 #proopen_10").text():'0';
				var proopen11 = $("#order-listing1 #proopen_11").text()!=''?$("#order-listing1 #proopen_11").text():'0';
				var proopen12 = $("#order-listing1 #proopen_12").text()!=''?$("#order-listing1 #proopen_12").text():'0';
				var proopen13 = $("#order-listing1 #proopen_13").text()!=''?$("#order-listing1 #proopen_13").text():'0';
				var proopen14 = $("#order-listing1 #proopen_14").text()!=''?$("#order-listing1 #proopen_14").text():'0';
				var proopen15 = $("#order-listing1 #proopen_15").text()!=''?$("#order-listing1 #proopen_15").text():'0';

				var proopenq1 = parseInt(proopen4)+parseInt(proopen5)+parseInt(proopen6);
				$("#order-listing1 #proopen_q1").text(proopenq1);
				var proopenq2 = parseInt(proopen7)+parseInt(proopen8)+parseInt(proopen9);

				$("#order-listing1 #proopen_q2").text(proopenq2);
				var proopenq3 = parseInt(proopen10)+parseInt(proopen11)+parseInt(proopen12);
				$("#order-listing1 #proopen_q3").text(proopenq3);
				var proopenq4 = parseInt(proopen13)+parseInt(proopen14)+parseInt(proopen15);
				$("#order-listing1 #proopen_q4").text(proopenq4);
				var proopenytd = (proopenq1)+(proopenq2)+(proopenq3)+(proopenq4);
				$("#order-listing1 #proopen_ytd").text(proopenytd);

				/*----------------------------------------**************************************************-----------------------------*/

				var proreopen4 = $("#order-listing1 #proreopen_4").text()!=''?$("#order-listing1 #proreopen_4").text():'0';
				var proreopen5 = $("#order-listing1 #proreopen_5").text()!=''?$("#order-listing1 #proreopen_5").text():'0';
				var proreopen6 = $("#order-listing1 #proreopen_6").text()!=''?$("#order-listing1 #proreopen_6").text():'0';
				var proreopen7 = $("#order-listing1 #proreopen_7").text()!=''?$("#order-listing1 #proreopen_7").text():'0';
				var proreopen8 = $("#order-listing1 #proreopen_8").text()!=''?$("#order-listing1 #proreopen_8").text():'0';
				var proreopen9 = $("#order-listing1 #proreopen_9").text()!=''?$("#order-listing1 #proreopen_9").text():'0';
				var proreopen10 = $("#order-listing1 #proreopen_10").text()!=''?$("#order-listing1 #proreopen_10").text():'0';
				var proreopen11 = $("#order-listing1 #proreopen_11").text()!=''?$("#order-listing1 #proreopen_11").text():'0';
				var proreopen12 = $("#order-listing1 #proreopen_12").text()!=''?$("#order-listing1 #proreopen_12").text():'0';
				var proreopen13 = $("#order-listing1 #proreopen_13").text()!=''?$("#order-listing1 #proreopen_13").text():'0';
				var proreopen14 = $("#order-listing1 #proreopen_14").text()!=''?$("#order-listing1 #proreopen_14").text():'0';
				var proreopen15 = $("#order-listing1 #proreopen_15").text()!=''?$("#order-listing1 #proreopen_15").text():'0';

				var proreopen4Value =isNaN(Math.round((parseInt(proreopen4)/ parseInt(pro4))*100))?'':Math.round((parseInt(proreopen4)/ parseInt(pro4))*100) ;
				var proreopen5Value =isNaN(Math.round((parseInt(proreopen5)/ parseInt(pro5))*100))?'':Math.round((parseInt(proreopen5)/ parseInt(pro5))*100) ;
				var proreopen6Value =isNaN(Math.round((parseInt(proreopen6)/ parseInt(pro6))*100))?'':Math.round((parseInt(proreopen6)/ parseInt(pro6))*100) ;
				var proreopen7Value = isNaN(Math.round((parseInt(proreopen7)/ parseInt(pro7))*100))?'':Math.round((parseInt(proreopen7)/ parseInt(pro7))*100)  ;
				var proreopen8Value =isNaN( Math.round((parseInt(proreopen8)/ parseInt(pro8))*100))?'':Math.round((parseInt(proreopen8)/ parseInt(pro8))*100) ;
				var proreopen9Value =isNaN( Math.round((parseInt(proreopen9)/ parseInt(pro9))*100))?'':Math.round((parseInt(proreopen9)/ parseInt(pro9))*100) ;
				var proreopen10Value = isNaN(Math.round((parseInt(proreopen10)/ parseInt(pro10))*100))?'':Math.round((parseInt(proreopen10)/ parseInt(pro10))*100)  ;
				var proreopen11Value =isNaN( Math.round((parseInt(proreopen11)/ parseInt(pro11))*100))?'':Math.round((parseInt(proreopen11)/ parseInt(pro11))*100) ;
				var proreopen12Value =isNaN( Math.round((parseInt(proreopen12)/ parseInt(pro12))*100))?'':Math.round((parseInt(proreopen12)/ parseInt(pro12))*100)  ;
				var proreopen13Value = isNaN(Math.round((parseInt(proreopen13)/ parseInt(pro13))*100))?'':Math.round((parseInt(proreopen13)/ parseInt(pro13))*100)  ;
				var proreopen14Value =isNaN( Math.round((parseInt(proreopen14)/ parseInt(pro14))*100) )?'':Math.round((parseInt(proreopen14)/ parseInt(pro14))*100) ;
				var proreopen15Value =isNaN( Math.round((parseInt(proreopen15)/ parseInt(pro15))*100))?'':Math.round((parseInt(proreopen15)/ parseInt(pro15))*100)  ;

				proreopen4Value = proreopen4Value!=''?proreopen4Value:'0';
				proreopen5Value = proreopen5Value!=''?proreopen5Value:'0';
				proreopen6Value = proreopen6Value!=''?proreopen6Value:'0';
				proreopen7Value = proreopen7Value!=''?proreopen7Value:'0';
				proreopen8Value = proreopen8Value!=''?proreopen8Value:'0';
				proreopen9Value = proreopen9Value!=''?proreopen9Value:'0';
				proreopen10Value = proreopen10Value!=''?proreopen10Value:'0';
				proreopen11Value = proreopen11Value!=''?proreopen11Value:'0';
				proreopen12Value = proreopen12Value!=''?proreopen12Value:'0';
				proreopen13Value = proreopen13Value!=''?proreopen13Value:'0';
				proreopen14Value = proreopen14Value!=''?proreopen14Value:'0';
				proreopen15Value = proreopen15Value!=''?proreopen15Value:'0';

				$("#order-listing1 #proreopen_4").text(proreopen4Value+'%');
				$("#order-listing1 #proreopen_5").text(proreopen5Value+'%');
				$("#order-listing1 #proreopen_6").text(proreopen6Value+'%');
				$("#order-listing1 #proreopen_7").text(proreopen7Value+'%');
				$("#order-listing1 #proreopen_8").text(proreopen8Value+'%');
				$("#order-listing1 #proreopen_9").text(proreopen9Value+'%');
				$("#order-listing1 #proreopen_10").text(proreopen10Value+'%');
				$("#order-listing1 #proreopen_11").text(proreopen11Value+'%');;
				$("#order-listing1 #proreopen_12").text(proreopen12Value+'%');
				$("#order-listing1 #proreopen_13").text(proreopen13Value+'%');
				$("#order-listing1 #proreopen_14").text(proreopen14Value+'%');
				$("#order-listing1 #proreopen_15").text(proreopen15Value+'%');
				
				var proreopen_avg_q1=(parseInt(proreopen4)+parseInt(proreopen5)+parseInt(proreopen6))/(proq1);
				var proreopen_avg_q2=(parseInt(proreopen7)+parseInt(proreopen8)+parseInt(proreopen9))/(proq2);
				var proreopen_avg_q3=(parseInt(proreopen10)+parseInt(proreopen11)+parseInt(proreopen12))/(proq3);
				var proreopen_avg_q4=(parseInt(proreopen13)+parseInt(proreopen14)+parseInt(proreopen15))/(proq4);
				var proreopen_avg_ytd =(parseInt(proreopen4)+parseInt(proreopen5)+parseInt(proreopen6) + parseInt(proreopen7)+parseInt(proreopen8)+parseInt(proreopen9) + parseInt(proreopen10)+parseInt(proreopen11)+parseInt(proreopen12) + parseInt(proreopen13)+parseInt(proreopen14)+parseInt(proreopen15))/proytd;
				
				var proreopen_q1 = isNaN(Math.round(proreopen_avg_q1*100))?'0':Math.round(proreopen_avg_q1*100);
				$("#order-listing1 #proreopen_q1").text(proreopen_q1+'%');
				var proreopen_q2 =isNaN(Math.round(proreopen_avg_q2*100))?'0':Math.round(proreopen_avg_q2*100);
				$("#order-listing1 #proreopen_q2").text(proreopen_q2+'%');
				var proreopen_q3 =isNaN(Math.round(proreopen_avg_q3*100))?'0':Math.round(proreopen_avg_q3*100);
				$("#order-listing1 #proreopen_q3").text(proreopen_q3+'%');
				var proreopen_q4 = isNaN(Math.round(proreopen_avg_q4*100))?'0':Math.round(proreopen_avg_q4*100);
				$("#order-listing1 #proreopen_q4").text(proreopen_q4+'%');
				var proreopen_ytd = isNaN(Math.round(proreopen_avg_ytd*100))?'0':Math.round(proreopen_avg_ytd*100);
				$("#order-listing1 #proreopen_ytd").text(proreopen_ytd+'%');

				/*----------------------------------------**************************************************-----------------------------*/

				var profeedback4 = $("#order-listing1 #profeedback_4").text()!=''?$("#order-listing1 #profeedback_4").text():'0';
				var profeedback5 = $("#order-listing1 #profeedback_5").text()!=''?$("#order-listing1 #profeedback_5").text():'0';
				var profeedback6 = $("#order-listing1 #profeedback_6").text()!=''?$("#order-listing1 #profeedback_6").text():'0';
				var profeedback7 = $("#order-listing1 #profeedback_7").text()!=''?$("#order-listing1 #profeedback_7").text():'0';
				var profeedback8 = $("#order-listing1 #profeedback_8").text()!=''?$("#order-listing1 #profeedback_8").text():'0';
				var profeedback9 = $("#order-listing1 #profeedback_9").text()!=''?$("#order-listing1 #profeedback_9").text():'0';
				var profeedback10 = $("#order-listing1 #profeedback_10").text()!=''?$("#order-listing1 #profeedback_10").text():'0';
				var profeedback11 = $("#order-listing1 #profeedback_11").text()!=''?$("#order-listing1 #profeedback_11").text():'0';
				var profeedback12 = $("#order-listing1 #profeedback_12").text()!=''?$("#order-listing1 #profeedback_12").text():'0';
				var profeedback13 = $("#order-listing1 #profeedback_13").text()!=''?$("#order-listing1 #profeedback_13").text():'0';
				var profeedback14 = $("#order-listing1 #profeedback_14").text()!=''?$("#order-listing1 #profeedback_14").text():'0';
				var profeedback15 = $("#order-listing1 #profeedback_15").text()!=''?$("#order-listing1 #profeedback_15").text():'0';

				var profeedbackq1 = parseInt(profeedback4)+parseInt(profeedback5)+parseInt(profeedback6);
				$("#order-listing1 #profeedback_q1").text(profeedbackq1);
				var profeedbackq2 = parseInt(profeedback7)+parseInt(profeedback8)+parseInt(profeedback9);

				$("#order-listing1 #profeedback_q2").text(profeedbackq2);
				var profeedbackq3 = parseInt(profeedback10)+parseInt(profeedback11)+parseInt(profeedback12);
				$("#order-listing1 #profeedback_q3").text(profeedbackq3);
				var profeedbackq4 = parseInt(profeedback13)+parseInt(profeedback14)+parseInt(profeedback15);
				$("#order-listing1 #profeedback_q4").text(profeedbackq4);
				var profeedbackytd = (profeedbackq1)+(profeedbackq2)+(profeedbackq3)+(profeedbackq4);
				$("#order-listing1 #profeedback_ytd").text(profeedbackytd);

				/*----------------------------------------**************************************************-----------------------------*/

				/*var propcs4 = $("#order-listing1 #propcs_4").text()!=''?$("#order-listing1 #propcs_4").text():'0';
				var propcs5 = $("#order-listing1 #propcs_5").text()!=''?$("#order-listing1 #propcs_5").text():'0';
				var propcs6 = $("#order-listing1 #propcs_6").text()!=''?$("#order-listing1 #propcs_6").text():'0';
				var propcs7 = $("#order-listing1 #propcs_7").text()!=''?$("#order-listing1 #propcs_7").text():'0';
				var propcs8 = $("#order-listing1 #propcs_8").text()!=''?$("#order-listing1 #propcs_8").text():'0';
				var propcs9 = $("#order-listing1 #propcs_9").text()!=''?$("#order-listing1 #propcs_9").text():'0';
				var propcs10 = $("#order-listing1 #propcs_10").text()!=''?$("#order-listing1 #propcs_10").text():'0';
				var propcs11 = $("#order-listing1 #propcs_11").text()!=''?$("#order-listing1 #propcs_11").text():'0';
				var propcs12 = $("#order-listing1 #propcs_12").text()!=''?$("#order-listing1 #propcs_12").text():'0';
				var propcs13 = $("#order-listing1 #propcs_13").text()!=''?$("#order-listing1 #propcs_13").text():'0';
				var propcs14 = $("#order-listing1 #propcs_14").text()!=''?$("#order-listing1 #propcs_14").text():'0';
				var propcs15 = $("#order-listing1 #propcs_15").text()!=''?$("#order-listing1 #propcs_15").text():'0';*/
				var propcs4 = $('#propcs_4').text();
				var propcs5 = $('#propcs_5').text();
				var propcs6 = $('#propcs_6').text();
				var propcs7 = $('#propcs_7').text();
				var propcs8 = $('#propcs_8').text();
				var propcs9 = $('#propcs_9').text();
				var propcs10 = $('#propcs_10').text();
				var propcs11 = $('#propcs_11').text();
				var propcs12 = $('#propcs_12').text();
				var propcs13 = $('#propcs_13').text();
				var propcs14 = $('#propcs_14').text();
				var propcs15 = $('#propcs_15').text();

				propcs4 =propcs4.split("~~");
				propcs5 =propcs5.split("~~");
				propcs6 =propcs6.split("~~");
				propcs7 =propcs7.split("~~");
				propcs8 =propcs8.split("~~");
				propcs9 =propcs9.split("~~");
				propcs10 =propcs10.split("~~");
				propcs11 =propcs11.split("~~");
				propcs12 =propcs12.split("~~");
				propcs13 =propcs13.split("~~");
				propcs14 =propcs14.split("~~");
				propcs15 =propcs15.split("~~");

				var q1Feedback = parseInt(propcs4[0])+parseInt(propcs5[0])+parseInt(propcs6[0]);
				var q1TotalFeedback = parseInt(propcs4[1])+parseInt(propcs5[1])+parseInt(propcs6[1]);
				q1TotalFeedback=q1TotalFeedback!=0?q1TotalFeedback:1;
				var q1Avg = isNaN(Math.round((q1Feedback/q1TotalFeedback)*100))?'0':Math.round((q1Feedback/q1TotalFeedback)*100);
				$("#order-listing1 #propcs_q1").text(q1Avg+'%');

				var q2Feedback = parseInt(propcs7[0])+parseInt(propcs8[0])+parseInt(propcs9[0]);
				var q2TotalFeedback = parseInt(propcs7[1])+parseInt(propcs8[1])+parseInt(propcs9[1]);
				q2TotalFeedback=q2TotalFeedback!=0?q2TotalFeedback:1;				
				var q2Avg = isNaN(Math.round((q2Feedback/q2TotalFeedback)*100))?'0':Math.round((q2Feedback/q2TotalFeedback)*100);
				$("#order-listing1 #propcs_q2").text(q2Avg+'%');

				var q3Feedback = parseInt(propcs10[0])+parseInt(propcs11[0])+parseInt(propcs12[0]);
				var q3TotalFeedback = parseInt(propcs10[1])+parseInt(propcs11[1])+parseInt(propcs12[1]);
				q3TotalFeedback=q3TotalFeedback!=0?q3TotalFeedback:1;
				var q3Avg = 	isNaN(Math.round((q3Feedback/q3TotalFeedback)*100))?'0':Math.round((q3Feedback/q3TotalFeedback)*100);
				$("#order-listing1 #propcs_q3").text(q3Avg+'%');

				var q4Feedback = parseInt(propcs13[0])+parseInt(propcs14[0])+parseInt(propcs15[0]);
				var q4TotalFeedback = parseInt(propcs13[1])+parseInt(propcs14[1])+parseInt(propcs15[1]);
				q4TotalFeedback=q4TotalFeedback!=0?q4TotalFeedback:1;
				var q4Avg = 	isNaN(Math.round((q4Feedback/q4TotalFeedback)*100))?'0':Math.round((q4Feedback/q4TotalFeedback)*100);
				$("#order-listing1 #propcs_q4").text(q4);

				var ytdFeedback = (q1Feedback)+(q2Feedback)+(q3Feedback)+(q4Feedback);
				var ytdTotalFeedback = (parseInt(propcs4[1])+parseInt(propcs5[1])+parseInt(propcs6[1]) )+(parseInt(propcs7[1])+parseInt(propcs8[1])+parseInt(propcs9[1]) )+(parseInt(propcs10[1])+parseInt(propcs11[1])+parseInt(propcs12[1]) )+(parseInt(propcs13[1])+parseInt(propcs14[1])+parseInt(propcs15[1]) );
				ytdTotalFeedback=ytdTotalFeedback!=0?ytdTotalFeedback:1;
				var ytdAvg = 	isNaN(Math.round((ytdFeedback/ytdTotalFeedback)*100))?'0':Math.round((ytdFeedback/ytdTotalFeedback)*100);
				$("#order-listing1 #propcs_ytd").text(ytdAvg+'%');
				/* ****************************************Product*******************************************************/
				/* ****************************************Parts*******************************************************/
				$('#part_16').hide();
				$('#part_17').hide();
				$('#part_18').hide();
				var par4 = $("#order-listing1 #part_4").text()!=''?$("#order-listing1 #part_4").text():'0';
				var par5 = $("#order-listing1 #part_5").text()!=''?$("#order-listing1 #part_5").text():'0';
				var par6 = $("#order-listing1 #part_6").text()!=''?$("#order-listing1 #part_6").text():'0';
				var par7 = $("#order-listing1 #part_7").text()!=''?$("#order-listing1 #part_7").text():'0';
				var par8 = $("#order-listing1 #part_8").text()!=''?$("#order-listing1 #part_8").text():'0';
				var par9 = $("#order-listing1 #part_9").text()!=''?$("#order-listing1 #part_9").text():'0';
				var par10 = $("#order-listing1 #part_10").text()!=''?$("#order-listing1 #part_10").text():'0';
				var par11 = $("#order-listing1 #part_11").text()!=''?$("#order-listing1 #part_11").text():'0';
				var par12 = $("#order-listing1 #part_12").text()!=''?$("#order-listing1 #part_12").text():'0';
				var par13 = $("#order-listing1 #part_13").text()!=''?$("#order-listing1 #part_13").text():'0';
				var par14 = $("#order-listing1 #part_14").text()!=''?$("#order-listing1 #part_14").text():'0';
				var par15 = $("#order-listing1 #part_15").text()!=''?$("#order-listing1 #part_15").text():'0';

				var parq1 = parseInt(par4)+parseInt(par5)+parseInt(par6);

				$("#order-listing1 #part_q1").text(parq1);
				var parq2 = parseInt(par7)+parseInt(par8)+parseInt(par9);

				$("#order-listing1 #part_q2").text(parq2);
				var parq3 = parseInt(par10)+parseInt(par11)+parseInt(par12);
				$("#order-listing1 #part_q3").text(parq3);
				var parq4 = parseInt(par13)+parseInt(par14)+parseInt(par15);
				$("#order-listing1 #part_q4").text(parq4);
				var parytd = (parq1)+(parq2)+(parq3)+(parq4);
				$("#order-listing1 #part_ytd").text(parytd);

				/*----------------------------------------**************************************************-----------------------------*/

				var parack4 = $("#order-listing1 #parack_4").text()!=''?$("#order-listing1 #parack_4").text():'0';
				var parack5 = $("#order-listing1 #parack_5").text()!=''?$("#order-listing1 #parack_5").text():'0';
				var parack6 = $("#order-listing1 #parack_6").text()!=''?$("#order-listing1 #parack_6").text():'0';
				var parack7 = $("#order-listing1 #parack_7").text()!=''?$("#order-listing1 #parack_7").text():'0';
				var parack8 = $("#order-listing1 #parack_8").text()!=''?$("#order-listing1 #parack_8").text():'0';
				var parack9 = $("#order-listing1 #parack_9").text()!=''?$("#order-listing1 #parack_9").text():'0';
				var parack10 = $("#order-listing1 #parack_10").text()!=''?$("#order-listing1 #parack_10").text():'0';
				var parack11 = $("#order-listing1 #parack_11").text()!=''?$("#order-listing1 #parack_11").text():'0';
				var parack12 = $("#order-listing1 #parack_12").text()!=''?$("#order-listing1 #parack_12").text():'0';
				var parack13 = $("#order-listing1 #parack_13").text()!=''?$("#order-listing1 #parack_13").text():'0';
				var parack14 = $("#order-listing1 #parack_14").text()!=''?$("#order-listing1 #parack_14").text():'0';
				var parack15 = $("#order-listing1 #parack_15").text()!=''?$("#order-listing1 #parack_15").text():'0';

				var parack4Value = isNaN(Math.round((parseInt(parack4)/ parseInt(par4))*100))?'':Math.round((parseInt(parack4)/ parseInt(par4))*100);
				var parack5Value = isNaN(Math.round((parseInt(parack5)/ parseInt(par5))*100))?'':Math.round((parseInt(parack5)/ parseInt(par5))*100);
				var parack6Value = isNaN(Math.round((parseInt(parack6)/ parseInt(par6))*100))?'':Math.round((parseInt(parack6)/ parseInt(par6))*100);
				
				var parack7Value = isNaN(Math.round((parseInt(parack7)/ parseInt(par7))*100))?'':Math.round((parseInt(parack7)/ parseInt(par7))*100);
				var parack8Value = isNaN(Math.round((parseInt(parack8)/ parseInt(par8))*100))?'':Math.round((parseInt(parack8)/ parseInt(par8))*100);
				var parack9Value = isNaN(Math.round((parseInt(parack9)/ parseInt(par9))*100))?'':Math.round((parseInt(parack9)/ parseInt(par9))*100);
				var parack10Value = isNaN(Math.round((parseInt(parack10)/ parseInt(par10))*100))?'':Math.round((parseInt(parack10)/ parseInt(par10))*100);
				var parack11Value = isNaN(Math.round((parseInt(parack11)/ parseInt(par11))*100))?'':Math.round((parseInt(parack11)/ parseInt(par11))*100);
				var parack12Value = isNaN(Math.round((parseInt(parack12)/ parseInt(par12))*100))?'':Math.round((parseInt(parack12)/ parseInt(par12))*100);
				var parack13Value = isNaN(Math.round((parseInt(parack13)/ parseInt(par13))*100))?'':Math.round((parseInt(parack13)/ parseInt(par13))*100);
				var parack14Value = isNaN(Math.round((parseInt(parack14)/ parseInt(par14))*100))?'':Math.round((parseInt(parack14)/ parseInt(par14))*100);
				var parack15Value = isNaN(Math.round((parseInt(parack15)/ parseInt(par15))*100))?'':Math.round((parseInt(parack15)/ parseInt(par15))*100);

				parack4Value = parack4Value!=''?parack4Value:'0';
				parack5Value = parack5Value!=''?parack5Value:'0';
				parack6Value = parack6Value!=''?parack6Value:'0';
				parack7Value = parack7Value!=''?parack7Value:'0';
				parack8Value = parack8Value!=''?parack8Value:'0';
				parack9Value = parack9Value!=''?parack9Value:'0';
				parack10Value = parack10Value!=''?parack10Value:'0';
				parack11Value = parack11Value!=''?parack11Value:'0';
				parack12Value = parack12Value!=''?parack12Value:'0';
				parack13Value = parack13Value!=''?parack13Value:'0';
				parack14Value = parack14Value!=''?parack14Value:'0';
				parack15Value = parack15Value!=''?parack15Value:'0';

				$("#order-listing1 #parack_4").text(parack4Value+'%');
				$("#order-listing1 #parack_5").text(parack5Value+'%');
				$("#order-listing1 #parack_6").text(parack6Value+'%');
				$("#order-listing1 #parack_7").text(parack7Value+'%');
				$("#order-listing1 #parack_8").text(parack8Value+'%');
				$("#order-listing1 #parack_9").text(parack9Value+'%');
				$("#order-listing1 #parack_10").text(parack10Value+'%');
				$("#order-listing1 #parack_11").text(parack11Value+'%');
				$("#order-listing1 #parack_12").text(parack12Value+'%');
				$("#order-listing1 #parack_13").text(parack13Value+'%');
				$("#order-listing1 #parack_14").text(parack14Value+'%');
				$("#order-listing1 #parack_15").text(parack15Value+'%');
				
				var parack_avg_q1=(parseInt(parack4)+parseInt(parack5)+parseInt(parack6))/(parq1);
				var parack_avg_q2=(parseInt(parack7)+parseInt(parack8)+parseInt(parack9))/(parq2);
				var parack_avg_q3=(parseInt(parack10)+parseInt(parack11)+parseInt(parack12))/(parq3);
				var parack_avg_q4=(parseInt(parack13)+parseInt(parack14)+parseInt(parack15))/(parq4);
				var parack_avg_ytd =(parseInt(parack4)+parseInt(parack5)+parseInt(parack6) + parseInt(parack7)+parseInt(parack8)+parseInt(parack9) + parseInt(parack10)+parseInt(parack11)+parseInt(parack12) + parseInt(parack13)+parseInt(parack14)+parseInt(parack15))/parytd;
				
				var parack_q1 =isNaN( Math.round(parack_avg_q1*100))?'0':Math.round(parack_avg_q1*100);
				$("#order-listing1 #parack_q1").text(parack_q1+'%');
				var parack_q2 =isNaN( Math.round(parack_avg_q2*100))?'0':Math.round(parack_avg_q2*100);
				$("#order-listing1 #parack_q2").text(parack_q2+'%');
				var parack_q3 = isNaN( Math.round(parack_avg_q3*100))?'0':Math.round(parack_avg_q3*100);
				$("#order-listing1 #parack_q3").text(parack_q3+'%');
				var parack_q4 = isNaN( Math.round(parack_avg_q4*100))?'0':Math.round(parack_avg_q4*100);
				$("#order-listing1 #parack_q4").text(parack_q4+'%');
				var parack_ytd = isNaN( Math.round(parack_avg_ytd*100))?'0':Math.round(parack_avg_ytd*100);
				$("#order-listing1 #parack_ytd").text(parack_ytd+'%');

				/*----------------------------------------**************************************************-----------------------------*/
				var parsla4 = $("#order-listing1 #parsla_4").text()!=''?$("#order-listing1 #parsla_4").text():'0';
				var parsla5 = $("#order-listing1 #parsla_5").text()!=''?$("#order-listing1 #parsla_5").text():'0';
				var parsla6 = $("#order-listing1 #parsla_6").text()!=''?$("#order-listing1 #parsla_6").text():'0';
				var parsla7 = $("#order-listing1 #parsla_7").text()!=''?$("#order-listing1 #parsla_7").text():'0';
				var parsla8 = $("#order-listing1 #parsla_8").text()!=''?$("#order-listing1 #parsla_8").text():'0';
				var parsla9 = $("#order-listing1 #parsla_9").text()!=''?$("#order-listing1 #parsla_9").text():'0';
				var parsla10 = $("#order-listing1 #parsla_10").text()!=''?$("#order-listing1 #parsla_10").text():'0';
				var parsla11 = $("#order-listing1 #parsla_11").text()!=''?$("#order-listing1 #parsla_11").text():'0';
				var parsla12 = $("#order-listing1 #parsla_12").text()!=''?$("#order-listing1 #parsla_12").text():'0';
				var parsla13 = $("#order-listing1 #parsla_13").text()!=''?$("#order-listing1 #parsla_13").text():'0';
				var parsla14 = $("#order-listing1 #parsla_14").text()!=''?$("#order-listing1 #parsla_14").text():'0';
				var parsla15 = $("#order-listing1 #parsla_15").text()!=''?$("#order-listing1 #parsla_15").text():'0';

				var parsla4Value =isNaN( Math.round((parseInt(parsla4)/ parseInt(par4))*100) )?'':Math.round((parseInt(parsla4)/ parseInt(par4))*100);
				var parsla5Value =isNaN( Math.round((parseInt(parsla5)/ parseInt(par5))*100))?'':Math.round((parseInt(parsla5)/ parseInt(par5))*100) ;
				var parsla6Value =isNaN( Math.round((parseInt(parsla6)/ parseInt(par6))*100) )?'':Math.round((parseInt(parsla6)/ parseInt(par6))*100);
				var parsla7Value = isNaN(Math.round((parseInt(parsla7)/ parseInt(par7))*100) )?'':Math.round((parseInt(parsla7)/ parseInt(par7))*100) ;
				var parsla8Value = isNaN(Math.round((parseInt(parsla8)/ parseInt(par8))*100))?'':Math.round((parseInt(parsla8)/ parseInt(par8))*100) ;
				var parsla9Value = isNaN(Math.round((parseInt(parsla9)/ parseInt(par9))*100) )?'':Math.round((parseInt(parsla9)/ parseInt(par9))*100);
				var parsla10Value =isNaN( Math.round((parseInt(parsla10)/ parseInt(par10))*100))?'':Math.round((parseInt(parsla10)/ parseInt(par10))*100);
				var parsla11Value = isNaN(Math.round((parseInt(parsla11)/ parseInt(par11))*100))?'':Math.round((parseInt(parsla11)/ parseInt(par11))*100);
				var parsla12Value = isNaN(Math.round((parseInt(parsla12)/ parseInt(par12))*100))?'':Math.round((parseInt(parsla12)/ parseInt(par12))*100);
				var parsla13Value =isNaN( Math.round((parseInt(parsla13)/ parseInt(par13))*100))?'':Math.round((parseInt(parsla13)/ parseInt(par13))*100);
				var parsla14Value = isNaN(Math.round((parseInt(parsla14)/ parseInt(par14))*100))?'':Math.round((parseInt(parsla14)/ parseInt(par14))*100);
				var parsla15Value =isNaN( Math.round((parseInt(parsla15)/ parseInt(par15))*100))?'':Math.round((parseInt(parsla15)/ parseInt(par15))*100);

				parsla4Value = parsla4Value!=''?parsla4Value:'0';
				parsla5Value = parsla5Value!=''?parsla5Value:'0';
				parsla6Value = parsla6Value!=''?parsla6Value:'0';
				parsla7Value = parsla7Value!=''?parsla7Value:'0';
				parsla8Value = parsla8Value!=''?parsla8Value:'0';
				parsla9Value = parsla9Value!=''?parsla9Value:'0';
				parsla10Value = parsla10Value!=''?parsla10Value:'0';
				parsla11Value = parsla11Value!=''?parsla11Value:'0';
				parsla12Value = parsla12Value!=''?parsla12Value:'0';
				parsla13Value = parsla13Value!=''?parsla13Value:'0';
				parsla14Value = parsla14Value!=''?parsla14Value:'0';
				parsla15Value = parsla15Value!=''?parsla15Value:'0';

				$("#order-listing1 #parsla_4").text(parsla4Value+'%');
				$("#order-listing1 #parsla_5").text(parsla5Value+'%');
				$("#order-listing1 #parsla_6").text(parsla6Value+'%');
				$("#order-listing1 #parsla_7").text(parsla7Value+'%');
				$("#order-listing1 #parsla_8").text(parsla8Value+'%');
				$("#order-listing1 #parsla_9").text(parsla9Value+'%');
				$("#order-listing1 #parsla_10").text(parsla10Value+'%');
				$("#order-listing1 #parsla_11").text(parsla11Value+'%');
				$("#order-listing1 #parsla_12").text(parsla12Value+'%');
				$("#order-listing1 #parsla_13").text(parsla13Value+'%');
				$("#order-listing1 #parsla_14").text(parsla14Value+'%');
				$("#order-listing1 #parsla_15").text(parsla15Value+'%');
				
				var parsla_avg_q1=(parseInt(parsla4)+parseInt(parsla5)+parseInt(parsla6))/(parq1);
				var parsla_avg_q2=(parseInt(parsla7)+parseInt(parsla8)+parseInt(parsla9))/(parq2);
				var parsla_avg_q3=(parseInt(parsla10)+parseInt(parsla11)+parseInt(parsla12))/(parq3);
				var parsla_avg_q4=(parseInt(parsla13)+parseInt(parsla14)+parseInt(parsla15))/(parq4);
				var parsla_avg_ytd =(parseInt(parsla4)+parseInt(parsla5)+parseInt(parsla6) + parseInt(parsla7)+parseInt(parsla8)+parseInt(parsla9) + parseInt(parsla10)+parseInt(parsla11)+parseInt(parsla12) + parseInt(parsla13)+parseInt(parsla14)+parseInt(parsla15))/parytd;
				
				var parsla_q1 = isNaN(Math.round(parsla_avg_q1*100))?'0':Math.round(parsla_avg_q1*100);
				$("#order-listing1 #parsla_q1").text(parsla_q1+'%');
				var parsla_q2 = isNaN(Math.round(parsla_avg_q2*100))?'0':Math.round(parsla_avg_q2*100);
				$("#order-listing1 #parsla_q2").text(parsla_q2+'%');
				var parsla_q3 =isNaN(Math.round(parsla_avg_q3*100))?'0':Math.round(parsla_avg_q3*100);
				$("#order-listing1 #parsla_q3").text(parsla_q3+'%');
				var parsla_q4 = isNaN(Math.round(parsla_avg_q4*100))?'0':Math.round(parsla_avg_q4*100);
				$("#order-listing1 #parsla_q4").text(parsla_q4+'%');
				var parsla_ytd = isNaN(Math.round(parsla_avg_ytd*100))?'0':Math.round(parsla_avg_ytd*100);
				$("#order-listing1 #parsla_ytd").text(parsla_ytd+'%');

				/*----------------------------------------**************************************************-----------------------------*/

				var paropen4 = $("#order-listing1 #paropen_4").text()!=''?$("#order-listing1 #paropen_4").text():'0';
				var paropen5 = $("#order-listing1 #paropen_5").text()!=''?$("#order-listing1 #paropen_5").text():'0';
				var paropen6 = $("#order-listing1 #paropen_6").text()!=''?$("#order-listing1 #paropen_6").text():'0';
				var paropen7 = $("#order-listing1 #paropen_7").text()!=''?$("#order-listing1 #paropen_7").text():'0';
				var paropen8 = $("#order-listing1 #paropen_8").text()!=''?$("#order-listing1 #paropen_8").text():'0';
				var paropen9 = $("#order-listing1 #paropen_9").text()!=''?$("#order-listing1 #paropen_9").text():'0';
				var paropen10 = $("#order-listing1 #paropen_10").text()!=''?$("#order-listing1 #paropen_10").text():'0';
				var paropen11 = $("#order-listing1 #paropen_11").text()!=''?$("#order-listing1 #paropen_11").text():'0';
				var paropen12 = $("#order-listing1 #paropen_12").text()!=''?$("#order-listing1 #paropen_12").text():'0';
				var paropen13 = $("#order-listing1 #paropen_13").text()!=''?$("#order-listing1 #paropen_13").text():'0';
				var paropen14 = $("#order-listing1 #paropen_14").text()!=''?$("#order-listing1 #paropen_14").text():'0';
				var paropen15 = $("#order-listing1 #paropen_15").text()!=''?$("#order-listing1 #paropen_15").text():'0';

				var paropenq1 = parseInt(paropen4)+parseInt(paropen5)+parseInt(paropen6);
				$("#order-listing1 #paropen_q1").text(paropenq1);
				var paropenq2 = parseInt(paropen7)+parseInt(paropen8)+parseInt(paropen9);

				$("#order-listing1 #paropen_q2").text(paropenq2);
				var paropenq3 = parseInt(paropen10)+parseInt(paropen11)+parseInt(paropen12);
				$("#order-listing1 #paropen_q3").text(paropenq3);
				var paropenq4 = parseInt(paropen13)+parseInt(paropen14)+parseInt(paropen15);
				$("#order-listing1 #paropen_q4").text(paropenq4);
				var paropenytd = (paropenq1)+(paropenq2)+(paropenq3)+(paropenq4);
				$("#order-listing1 #paropen_ytd").text(paropenytd);

				/*----------------------------------------**************************************************-----------------------------*/

				var parreopen4 = $("#order-listing1 #parreopen_4").text()!=''?$("#order-listing1 #parreopen_4").text():'0';
				var parreopen5 = $("#order-listing1 #parreopen_5").text()!=''?$("#order-listing1 #parreopen_5").text():'0';
				var parreopen6 = $("#order-listing1 #parreopen_6").text()!=''?$("#order-listing1 #parreopen_6").text():'0';
				var parreopen7 = $("#order-listing1 #parreopen_7").text()!=''?$("#order-listing1 #parreopen_7").text():'0';
				var parreopen8 = $("#order-listing1 #parreopen_8").text()!=''?$("#order-listing1 #parreopen_8").text():'0';
				var parreopen9 = $("#order-listing1 #parreopen_9").text()!=''?$("#order-listing1 #parreopen_9").text():'0';
				var parreopen10 = $("#order-listing1 #parreopen_10").text()!=''?$("#order-listing1 #parreopen_10").text():'0';
				var parreopen11 = $("#order-listing1 #parreopen_11").text()!=''?$("#order-listing1 #parreopen_11").text():'0';
				var parreopen12 = $("#order-listing1 #parreopen_12").text()!=''?$("#order-listing1 #parreopen_12").text():'0';
				var parreopen13 = $("#order-listing1 #parreopen_13").text()!=''?$("#order-listing1 #parreopen_13").text():'0';
				var parreopen14 = $("#order-listing1 #parreopen_14").text()!=''?$("#order-listing1 #parreopen_14").text():'0';
				var parreopen15 = $("#order-listing1 #parreopen_15").text()!=''?$("#order-listing1 #parreopen_15").text():'0';
				
				var parreopen4Value = isNaN(Math.round((parseInt(parreopen4)/ parseInt(par4))*100))?'':Math.round((parseInt(parreopen4)/ parseInt(par4))*100);
				var parreopen5Value = isNaN(Math.round((parseInt(parreopen5)/ parseInt(par5))*100))?'':Math.round((parseInt(parreopen5)/ parseInt(par5))*100);
				
				var parreopen6Value =isNaN(Math.round((parseInt(parreopen6)/ parseInt(par6))*100))?'':Math.round((parseInt(parreopen6)/ parseInt(par6))*100);
				var parreopen7Value = isNaN(Math.round((parseInt(parreopen7)/ parseInt(par7))*100))?'':Math.round((parseInt(parreopen7)/ parseInt(par7))*100);
				var parreopen8Value = isNaN(Math.round((parseInt(parreopen8)/ parseInt(par8))*100))?'':Math.round((parseInt(parreopen8)/ parseInt(par8))*100);
				var parreopen9Value = isNaN(Math.round((parseInt(parreopen9)/ parseInt(par9))*100))?'':Math.round((parseInt(parreopen9)/ parseInt(par9))*100);
				var parreopen10Value =isNaN( Math.round((parseInt(parreopen10)/ parseInt(par10))*100))?'':Math.round((parseInt(parreopen10)/ parseInt(par10))*100);
				var parreopen11Value = isNaN(Math.round((parseInt(parreopen11)/ parseInt(par11))*100))?'':Math.round((parseInt(parreopen11)/ parseInt(par11))*100);
				var parreopen12Value = isNaN(Math.round((parseInt(parreopen12)/ parseInt(par12))*100))?'':Math.round((parseInt(parreopen12)/ parseInt(par12))*100);
				var parreopen13Value =isNaN( Math.round((parseInt(parreopen13)/ parseInt(par13))*100))?'':Math.round((parseInt(parreopen13)/ parseInt(par13))*100);
				var parreopen14Value = isNaN(Math.round((parseInt(parreopen14)/ parseInt(par14))*100))?'':Math.round((parseInt(parreopen14)/ parseInt(par14))*100);
				var parreopen15Value = isNaN(Math.round((parseInt(parreopen15)/ parseInt(par15))*100))?'':Math.round((parseInt(parreopen15)/ parseInt(par15))*100);

				parreopen4Value = parreopen4Value!=''?parreopen4Value:'0';
				parreopen5Value = parreopen5Value!=''?parreopen5Value:'0';
				parreopen6Value = parreopen6Value!=''?parreopen6Value:'0';
				parreopen7Value = parreopen7Value!=''?parreopen7Value:'0';
				parreopen8Value = parreopen8Value!=''?parreopen8Value:'0';
				parreopen9Value = parreopen9Value!=''?parreopen9Value:'0';
				parreopen10Value = parreopen10Value!=''?parreopen10Value:'0';
				parreopen11Value = parreopen11Value!=''?parreopen11Value:'0';
				parreopen12Value = parreopen12Value!=''?parreopen12Value:'0';
				parreopen13Value = parreopen13Value!=''?parreopen13Value:'0';
				parreopen14Value = parreopen14Value!=''?parreopen14Value:'0';
				parreopen15Value = parreopen15Value!=''?parreopen15Value:'0';	

				$("#order-listing1 #parreopen_4").text(parreopen4Value+'%');
				$("#order-listing1 #parreopen_5").text(parreopen5Value+'%');
				$("#order-listing1 #parreopen_6").text(parreopen6Value+'%');
				$("#order-listing1 #parreopen_7").text(parreopen7Value+'%');
				$("#order-listing1 #parreopen_8").text(parreopen8Value+'%');
				$("#order-listing1 #parreopen_9").text(parreopen9Value+'%');
				$("#order-listing1 #parreopen_10").text(parreopen10Value+'%');
				$("#order-listing1 #parreopen_11").text(parreopen11Value+'%');
				$("#order-listing1 #parreopen_12").text(parreopen12Value+'%');
				$("#order-listing1 #parreopen_13").text(parreopen13Value+'%');
				$("#order-listing1 #parreopen_14").text(parreopen14Value+'%');
				$("#order-listing1 #parreopen_15").text(parreopen15Value+'%');
				
				var parreopen_avg_q1=(parseInt(parreopen4)+parseInt(parreopen5)+parseInt(parreopen6))/(parq1);
				var parreopen_avg_q2=(parseInt(parreopen7)+parseInt(parreopen8)+parseInt(parreopen9))/(parq2);
				var parreopen_avg_q3=(parseInt(parreopen10)+parseInt(parreopen11)+parseInt(parreopen12))/(parq3);
				var parreopen_avg_q4=(parseInt(parreopen13)+parseInt(parreopen14)+parseInt(parreopen15))/(parq4);
				var parreopen_avg_ytd =(parseInt(parreopen4)+parseInt(parreopen5)+parseInt(parreopen6) + parseInt(parreopen7)+parseInt(parreopen8)+parseInt(parreopen9) + parseInt(parreopen10)+parseInt(parreopen11)+parseInt(parreopen12) + parseInt(parreopen13)+parseInt(parreopen14)+parseInt(parreopen15))/parytd;
				
				var parreopen_q1 = isNaN(Math.round(parreopen_avg_q1*100))?'0':Math.round(parreopen_avg_q1*100);
				$("#order-listing1 #parreopen_q1").text(parreopen_q1+'%');
				var parreopen_q2 = isNaN(Math.round(parreopen_avg_q2*100))?'0':Math.round(parreopen_avg_q2*100);
				$("#order-listing1 #parreopen_q2").text(parreopen_q2+'%');
				var parreopen_q3 =isNaN(Math.round(parreopen_avg_q3*100))?'0':Math.round(parreopen_avg_q3*100);
				$("#order-listing1 #parreopen_q3").text(parreopen_q3+'%');
				var parreopen_q4 = isNaN(Math.round(parreopen_avg_q4*100))?'0':Math.round(parreopen_avg_q4*100);
				$("#order-listing1 #parreopen_q4").text(parreopen_q4+'%');
				var parreopen_ytd = isNaN(Math.round(parreopen_avg_ytd*100))?'0':Math.round(parreopen_avg_ytd*100);
				$("#order-listing1 #parreopen_ytd").text(parreopen_ytd+'%');

				/*----------------------------------------**************************************************-----------------------------*/

				var parfeedback4 = $("#order-listing1 #parfeedback_4").text()!=''?$("#order-listing1 #parfeedback_4").text():'0';
				var parfeedback5 = $("#order-listing1 #parfeedback_5").text()!=''?$("#order-listing1 #parfeedback_5").text():'0';
				var parfeedback6 = $("#order-listing1 #parfeedback_6").text()!=''?$("#order-listing1 #parfeedback_6").text():'0';
				var parfeedback7 = $("#order-listing1 #parfeedback_7").text()!=''?$("#order-listing1 #parfeedback_7").text():'0';
				var parfeedback8 = $("#order-listing1 #parfeedback_8").text()!=''?$("#order-listing1 #parfeedback_8").text():'0';
				var parfeedback9 = $("#order-listing1 #parfeedback_9").text()!=''?$("#order-listing1 #parfeedback_9").text():'0';
				var parfeedback10 = $("#order-listing1 #parfeedback_10").text()!=''?$("#order-listing1 #parfeedback_10").text():'0';
				var parfeedback11 = $("#order-listing1 #parfeedback_11").text()!=''?$("#order-listing1 #parfeedback_11").text():'0';
				var parfeedback12 = $("#order-listing1 #parfeedback_12").text()!=''?$("#order-listing1 #parfeedback_12").text():'0';
				var parfeedback13 = $("#order-listing1 #parfeedback_13").text()!=''?$("#order-listing1 #parfeedback_13").text():'0';
				var parfeedback14 = $("#order-listing1 #parfeedback_14").text()!=''?$("#order-listing1 #parfeedback_14").text():'0';
				var parfeedback15 = $("#order-listing1 #parfeedback_15").text()!=''?$("#order-listing1 #parfeedback_15").text():'0';

				var parfeedbackq1 = parseInt(parfeedback4)+parseInt(parfeedback5)+parseInt(parfeedback6);
				$("#order-listing1 #parfeedback_q1").text(parfeedbackq1);
				var parfeedbackq2 = parseInt(parfeedback7)+parseInt(parfeedback8)+parseInt(parfeedback9);

				$("#order-listing1 #parfeedback_q2").text(parfeedbackq2);
				var parfeedbackq3 = parseInt(parfeedback10)+parseInt(parfeedback11)+parseInt(parfeedback12);
				$("#order-listing1 #parfeedback_q3").text(parfeedbackq3);
				var parfeedbackq4 = parseInt(parfeedback13)+parseInt(parfeedback14)+parseInt(parfeedback15);
				$("#order-listing1 #parfeedback_q4").text(parfeedbackq4);
				var parfeedbackytd = (parfeedbackq1)+(parfeedbackq2)+(parfeedbackq3)+(parfeedbackq4);
				$("#order-listing1 #parfeedback_ytd").text(parfeedbackytd);

				/*----------------------------------------**************************************************-----------------------------*/

				/*var parpcs4 = $("#order-listing1 #parpcs_4").text()!=''?$("#order-listing1 #parpcs_4").text():'0';
				var parpcs5 = $("#order-listing1 #parpcs_5").text()!=''?$("#order-listing1 #parpcs_5").text():'0';
				var parpcs6 = $("#order-listing1 #parpcs_6").text()!=''?$("#order-listing1 #parpcs_6").text():'0';
				var parpcs7 = $("#order-listing1 #parpcs_7").text()!=''?$("#order-listing1 #parpcs_7").text():'0';
				var parpcs8 = $("#order-listing1 #parpcs_8").text()!=''?$("#order-listing1 #parpcs_8").text():'0';
				var parpcs9 = $("#order-listing1 #parpcs_9").text()!=''?$("#order-listing1 #parpcs_9").text():'0';
				var parpcs10 = $("#order-listing1 #parpcs_10").text()!=''?$("#order-listing1 #parpcs_10").text():'0';
				var parpcs11 = $("#order-listing1 #parpcs_11").text()!=''?$("#order-listing1 #parpcs_11").text():'0';
				var parpcs12 = $("#order-listing1 #parpcs_12").text()!=''?$("#order-listing1 #parpcs_12").text():'0';
				var parpcs13 = $("#order-listing1 #parpcs_13").text()!=''?$("#order-listing1 #parpcs_13").text():'0';
				var parpcs14 = $("#order-listing1 #parpcs_14").text()!=''?$("#order-listing1 #parpcs_14").text():'0';
				var parpcs15 = $("#order-listing1 #parpcs_15").text()!=''?$("#order-listing1 #parpcs_15").text():'0';*/

				var parpcs4 = $('#parpcs_4').text();
				var parpcs5 = $('#parpcs_5').text();
				var parpcs6 = $('#parpcs_6').text();
				var parpcs7 = $('#parpcs_7').text();
				var parpcs8 = $('#parpcs_8').text();
				var parpcs9 = $('#parpcs_9').text();
				var parpcs10 = $('#parpcs_10').text();
				var parpcs11 = $('#parpcs_11').text();
				var parpcs12 = $('#parpcs_12').text();
				var parpcs13 = $('#parpcs_13').text();
				var parpcs14 = $('#parpcs_14').text();
				var parpcs15 = $('#parpcs_15').text();

				parpcs4 =parpcs4.split("~~");
				parpcs5 =parpcs5.split("~~");
				parpcs6 =parpcs6.split("~~");
				parpcs7 =parpcs7.split("~~");
				parpcs8 =parpcs8.split("~~");
				parpcs9 =parpcs9.split("~~");
				parpcs10 =parpcs10.split("~~");
				parpcs11 =parpcs11.split("~~");
				parpcs12 =parpcs12.split("~~");
				parpcs13 =parpcs13.split("~~");
				parpcs14 =parpcs14.split("~~");
				parpcs15 =parpcs15.split("~~");

				var q1Feedback = parseInt(parpcs4[0])+parseInt(parpcs5[0])+parseInt(parpcs6[0]);
				var q1TotalFeedback = parseInt(parpcs4[1])+parseInt(parpcs5[1])+parseInt(parpcs6[1]);
				q1TotalFeedback=q1TotalFeedback!=0?q1TotalFeedback:1;
				var q1Avg = isNaN(Math.round((q1Feedback/q1TotalFeedback)*100))?'0':Math.round((q1Feedback/q1TotalFeedback)*100);
				$("#order-listing1 #parpcs_q1").text(q1Avg+'%');

				var q2Feedback = parseInt(parpcs7[0])+parseInt(parpcs8[0])+parseInt(parpcs9[0]);
				var q2TotalFeedback = parseInt(parpcs7[1])+parseInt(parpcs8[1])+parseInt(parpcs9[1]);
				q2TotalFeedback=q2TotalFeedback!=0?q2TotalFeedback:1;
				var q2Avg = isNaN(Math.round((q2Feedback/q2TotalFeedback)*100))?'0':Math.round((q2Feedback/q2TotalFeedback)*100);
				$("#order-listing1 #parpcs_q2").text(q2Avg+'%');

				var q3Feedback = parseInt(parpcs10[0])+parseInt(parpcs11[0])+parseInt(parpcs12[0]);
				var q3TotalFeedback = parseInt(parpcs10[1])+parseInt(parpcs11[1])+parseInt(parpcs12[1]);
				q3TotalFeedback=q3TotalFeedback!=0?q3TotalFeedback:1;
				var q3Avg = isNaN(Math.round((q3Feedback/q3TotalFeedback)*100))?'0':Math.round((q3Feedback/q3TotalFeedback)*100);
				$("#order-listing1 #parpcs_q3").text(q3Avg+'%');

				var q4Feedback = parseInt(parpcs13[0])+parseInt(parpcs14[0])+parseInt(parpcs15[0]);
				var q4TotalFeedback = parseInt(parpcs13[1])+parseInt(parpcs14[1])+parseInt(parpcs15[1]);
				q4TotalFeedback=q4TotalFeedback!=0?q4TotalFeedback:1;
				var q4Avg = isNaN(Math.round((q4Feedback/q4TotalFeedback)*100))?'0':Math.round((q4Feedback/q4TotalFeedback)*100);;
				$("#order-listing1 #parpcs_q4").text(q4Avg+'%');

				var ytdFeedback = (q1Feedback)+(q2Feedback)+(q3Feedback)+(q4Feedback);
				var ytdTotalFeedback = (parseInt(parpcs4[1])+parseInt(parpcs5[1])+parseInt(parpcs6[1]) )+(parseInt(parpcs7[1])+parseInt(parpcs8[1])+parseInt(parpcs9[1]) )+(parseInt(parpcs10[1])+parseInt(parpcs11[1])+parseInt(parpcs12[1]) )+(parseInt(parpcs13[1])+parseInt(parpcs14[1])+parseInt(parpcs15[1]) );
				ytdTotalFeedback=ytdTotalFeedback!=0?ytdTotalFeedback:1;
				var ytdAvg = 	isNaN(Math.round((ytdFeedback/ytdTotalFeedback)*100))?'0':Math.round((ytdFeedback/ytdTotalFeedback)*100);
				$("#order-listing1 #parpcs_ytd").text(ytdAvg+'%');
				/* ****************************************Parts*******************************************************/
				/* ****************************************Service*******************************************************/
				$('#ser_16').hide();
				$('#ser_17').hide();
				$('#ser_18').hide();
				var ser4 = $("#order-listing1 #ser_4").text()!=''?$("#order-listing1 #ser_4").text():'0';
				var ser5 = $("#order-listing1 #ser_5").text()!=''?$("#order-listing1 #ser_5").text():'0';
				var ser6 = $("#order-listing1 #ser_6").text()!=''?$("#order-listing1 #ser_6").text():'0';
				var ser7 = $("#order-listing1 #ser_7").text()!=''?$("#order-listing1 #ser_7").text():'0';
				var ser8 = $("#order-listing1 #ser_8").text()!=''?$("#order-listing1 #ser_8").text():'0';
				var ser9 = $("#order-listing1 #ser_9").text()!=''?$("#order-listing1 #ser_9").text():'0';
				var ser10 = $("#order-listing1 #ser_10").text()!=''?$("#order-listing1 #ser_10").text():'0';
				var ser11 = $("#order-listing1 #ser_11").text()!=''?$("#order-listing1 #ser_11").text():'0';
				var ser12 = $("#order-listing1 #ser_12").text()!=''?$("#order-listing1 #ser_12").text():'0';
				var ser13 = $("#order-listing1 #ser_13").text()!=''?$("#order-listing1 #ser_13").text():'0';
				var ser14 = $("#order-listing1 #ser_14").text()!=''?$("#order-listing1 #ser_14").text():'0';
				var ser15 = $("#order-listing1 #ser_15").text()!=''?$("#order-listing1 #ser_15").text():'0';

				var serq1 = parseInt(ser4)+parseInt(ser5)+parseInt(ser6);
				$("#order-listing1 #ser_q1").text(serq1);
				var serq2 = parseInt(ser7)+parseInt(ser8)+parseInt(ser9);

				$("#order-listing1 #ser_q2").text(serq2);
				var serq3 = parseInt(ser10)+parseInt(ser11)+parseInt(ser12);
				$("#order-listing1 #ser_q3").text(serq3);
				var serq4 = parseInt(ser13)+parseInt(ser14)+parseInt(ser15);
				$("#order-listing1 #ser_q4").text(serq4);
				var serytd = (serq1)+(serq2)+(serq3)+(serq4);
				$("#order-listing1 #ser_ytd").text(serytd);

				/*----------------------------------------**************************************************-----------------------------*/

				var serack4 = $("#order-listing1 #serack_4").text()!=''?$("#order-listing1 #serack_4").text():'0';
				var serack5 = $("#order-listing1 #serack_5").text()!=''?$("#order-listing1 #serack_5").text():'0';
				var serack6 = $("#order-listing1 #serack_6").text()!=''?$("#order-listing1 #serack_6").text():'0';
				var serack7 = $("#order-listing1 #serack_7").text()!=''?$("#order-listing1 #serack_7").text():'0';
				var serack8 = $("#order-listing1 #serack_8").text()!=''?$("#order-listing1 #serack_8").text():'0';
				var serack9 = $("#order-listing1 #serack_9").text()!=''?$("#order-listing1 #serack_9").text():'0';
				var serack10 = $("#order-listing1 #serack_10").text()!=''?$("#order-listing1 #serack_10").text():'0';
				var serack11 = $("#order-listing1 #serack_11").text()!=''?$("#order-listing1 #serack_11").text():'0';
				var serack12 = $("#order-listing1 #serack_12").text()!=''?$("#order-listing1 #serack_12").text():'0';
				var serack13 = $("#order-listing1 #serack_13").text()!=''?$("#order-listing1 #serack_13").text():'0';
				var serack14 = $("#order-listing1 #serack_14").text()!=''?$("#order-listing1 #serack_14").text():'0';
				var serack15 = $("#order-listing1 #serack_15").text()!=''?$("#order-listing1 #serack_15").text():'0';

				var serack4Value = isNaN(Math.round((parseInt(serack4)/ parseInt(ser4))*100))?'':Math.round((parseInt(serack4)/ parseInt(ser4))*100);
				var serack5Value = isNaN(Math.round((parseInt(serack5)/ parseInt(ser5))*100))?'':Math.round((parseInt(serack5)/ parseInt(ser5))*100);
				var serack6Value = isNaN(Math.round((parseInt(serack6)/ parseInt(ser6))*100))?'':Math.round((parseInt(serack6)/ parseInt(ser6))*100);
				var serack7Value = isNaN(Math.round((parseInt(serack7)/ parseInt(ser7))*100))?'':Math.round((parseInt(serack7)/ parseInt(ser7))*100);
				var serack8Value = isNaN(Math.round((parseInt(serack8)/ parseInt(ser8))*100))?'':Math.round((parseInt(serack8)/ parseInt(ser8))*100);
				var serack9Value = isNaN(Math.round((parseInt(serack9)/ parseInt(ser9))*100))?'':Math.round((parseInt(serack9)/ parseInt(ser9))*100);
				var serack10Value = isNaN(Math.round((parseInt(serack10)/ parseInt(ser10))*100))?'':Math.round((parseInt(serack10)/ parseInt(ser10))*100);
				var serack11Value = isNaN(Math.round((parseInt(serack11)/ parseInt(ser11))*100))?'':Math.round((parseInt(serack11)/ parseInt(ser11))*100);
				var serack12Value = isNaN(Math.round((parseInt(serack12)/ parseInt(ser12))*100))?'': Math.round((parseInt(serack12)/ parseInt(ser12))*100);
				var serack13Value = isNaN(Math.round((parseInt(serack13)/ parseInt(ser13))*100))?'':Math.round((parseInt(serack13)/ parseInt(ser13))*100);
				var serack14Value = isNaN(Math.round((parseInt(serack14)/ parseInt(ser14))*100))?'':Math.round((parseInt(serack14)/ parseInt(ser14))*100);
				var serack15Value = isNaN(Math.round((parseInt(serack15)/ parseInt(ser15))*100))?'':Math.round((parseInt(serack15)/ parseInt(ser15))*100);

				serack4Value = serack4Value!=''?serack4Value:'0';
				serack5Value = serack5Value!=''?serack5Value:'0';
				serack6Value = serack6Value!=''?serack6Value:'0';
				serack7Value = serack7Value!=''?serack7Value:'0';
				serack8Value = serack8Value!=''?serack8Value:'0';
				serack9Value = serack9Value!=''?serack9Value:'0';
				serack10Value = serack10Value!=''?serack10Value:'0';
				serack11Value = serack11Value!=''?serack11Value:'0';
				serack12Value = serack12Value!=''?serack12Value:'0';
				serack13Value = serack13Value!=''?serack13Value:'0';
				serack14Value = serack14Value!=''?serack14Value:'0';
				serack15Value = serack15Value!=''?serack15Value:'0';

				$("#order-listing1 #serack_4").text(serack4Value+'%');
				$("#order-listing1 #serack_5").text(serack5Value+'%');
				$("#order-listing1 #serack_6").text(serack6Value+'%');
				$("#order-listing1 #serack_7").text(serack7Value+'%');
				$("#order-listing1 #serack_8").text(serack8Value+'%');
				$("#order-listing1 #serack_9").text(serack9Value+'%');
				$("#order-listing1 #serack_10").text(serack10Value+'%');
				$("#order-listing1 #serack_11").text(serack11Value+'%');
				$("#order-listing1 #serack_12").text(serack12Value+'%');
				$("#order-listing1 #serack_13").text(serack13Value+'%');
				$("#order-listing1 #serack_14").text(serack14Value+'%');
				$("#order-listing1 #serack_15").text(serack15Value+'%');
				
				var serack_avg_q1=(parseInt(serack4)+parseInt(serack5)+parseInt(serack6))/(serq1);
				var serack_avg_q2=(parseInt(serack7)+parseInt(serack8)+parseInt(serack9))/(serq2);
				var serack_avg_q3=(parseInt(serack10)+parseInt(serack11)+parseInt(serack12))/(serq3);
				var serack_avg_q4=(parseInt(serack13)+parseInt(serack14)+parseInt(serack15))/(serq4);
				var serack_avg_ytd =(parseInt(serack4)+parseInt(serack5)+parseInt(serack6) + parseInt(serack7)+parseInt(serack8)+parseInt(serack9) + parseInt(serack10)+parseInt(serack11)+parseInt(serack12) + parseInt(serack13)+parseInt(serack14)+parseInt(serack15))/serytd;
				
				var serack_q1 = isNaN(Math.round(serack_avg_q1*100))?'0':Math.round(serack_avg_q1*100);
				$("#order-listing1 #serack_q1").text(serack_q1+'%');
				var serack_q2 = isNaN(Math.round(serack_avg_q2*100))?'0':Math.round(serack_avg_q2*100);
				$("#order-listing1 #serack_q2").text(serack_q2+'%');
				var serack_q3 = isNaN(Math.round(serack_avg_q3*100))?'0':Math.round(serack_avg_q3*100);
				$("#order-listing1 #serack_q3").text(serack_q3+'%');
				var serack_q4 = isNaN(Math.round(serack_avg_q4*100))?'0':Math.round(serack_avg_q4*100);
				$("#order-listing1 #serack_q4").text(serack_q4+'%');
				var serack_ytd = isNaN(Math.round(serack_avg_ytd*100))?'0':Math.round(serack_avg_ytd*100);
				$("#order-listing1 #serack_ytd").text(serack_ytd+'%');

				/*----------------------------------------**************************************************-----------------------------*/
				var sersla4 = $("#order-listing1 #sersla_4").text()!=''?$("#order-listing1 #sersla_4").text():'0';
				var sersla5 = $("#order-listing1 #sersla_5").text()!=''?$("#order-listing1 #sersla_5").text():'0';
				var sersla6 = $("#order-listing1 #sersla_6").text()!=''?$("#order-listing1 #sersla_6").text():'0';
				var sersla7 = $("#order-listing1 #sersla_7").text()!=''?$("#order-listing1 #sersla_7").text():'0';
				var sersla8 = $("#order-listing1 #sersla_8").text()!=''?$("#order-listing1 #sersla_8").text():'0';
				var sersla9 = $("#order-listing1 #sersla_9").text()!=''?$("#order-listing1 #sersla_9").text():'0';
				var sersla10 = $("#order-listing1 #sersla_10").text()!=''?$("#order-listing1 #sersla_10").text():'0';
				var sersla11 = $("#order-listing1 #sersla_11").text()!=''?$("#order-listing1 #sersla_11").text():'0';
				var sersla12 = $("#order-listing1 #sersla_12").text()!=''?$("#order-listing1 #sersla_12").text():'0';
				var sersla13 = $("#order-listing1 #sersla_13").text()!=''?$("#order-listing1 #sersla_13").text():'0';
				var sersla14 = $("#order-listing1 #sersla_14").text()!=''?$("#order-listing1 #sersla_14").text():'0';
				var sersla15 = $("#order-listing1 #sersla_15").text()!=''?$("#order-listing1 #sersla_15").text():'0';

				var sersla4Value = isNaN(Math.round((parseInt(sersla4)/ parseInt(ser4))*100))?'':Math.round((parseInt(sersla4)/ parseInt(ser4))*100);
				var sersla5Value = isNaN(Math.round((parseInt(sersla5)/ parseInt(ser5))*100))?'':Math.round((parseInt(sersla5)/ parseInt(ser5))*100) ;
				var sersla6Value = isNaN(Math.round((parseInt(sersla6)/ parseInt(ser6))*100))?'':Math.round((parseInt(sersla6)/ parseInt(ser6))*100);
				var sersla7Value = isNaN(Math.round((parseInt(sersla7)/ parseInt(ser7))*100))?'':Math.round((parseInt(sersla7)/ parseInt(ser7))*100) ;
				var sersla8Value = isNaN(Math.round((parseInt(sersla8)/ parseInt(ser8))*100))?'':Math.round((parseInt(sersla8)/ parseInt(ser8))*100);
				var sersla9Value = isNaN(Math.round((parseInt(sersla9)/ parseInt(ser9))*100))?'':Math.round((parseInt(sersla9)/ parseInt(ser9))*100);
				var sersla10Value = isNaN(Math.round((parseInt(sersla10)/ parseInt(ser10))*100))?'':Math.round((parseInt(sersla10)/ parseInt(ser10))*100);
				var sersla11Value = isNaN(Math.round((parseInt(sersla11)/ parseInt(ser11))*100))?'':Math.round((parseInt(sersla11)/ parseInt(ser11))*100) ;
				var sersla12Value = isNaN(Math.round((parseInt(sersla12)/ parseInt(ser12))*100))?'':Math.round((parseInt(sersla12)/ parseInt(ser12))*100) ;
				var sersla13Value = isNaN(Math.round((parseInt(sersla13)/ parseInt(ser13))*100))?'':Math.round((parseInt(sersla13)/ parseInt(ser13))*100) ;
				var sersla14Value = isNaN(Math.round((parseInt(sersla14)/ parseInt(ser14))*100))?'':Math.round((parseInt(sersla14)/ parseInt(ser14))*100) ;
				var sersla15Value = isNaN(Math.round((parseInt(sersla15)/ parseInt(ser15))*100))?'':Math.round((parseInt(sersla15)/ parseInt(ser15))*100);

				sersla4Value = sersla4Value!=''?sersla4Value:'0';
				sersla5Value = sersla5Value!=''?sersla5Value:'0';
				sersla6Value = sersla6Value!=''?sersla6Value:'0';
				sersla7Value = sersla7Value!=''?sersla7Value:'0';
				sersla8Value = sersla8Value!=''?sersla8Value:'0';
				sersla9Value = sersla9Value!=''?sersla9Value:'0';
				sersla10Value = sersla10Value!=''?sersla10Value:'0';
				sersla11Value = sersla11Value!=''?sersla11Value:'0';
				sersla12Value = sersla12Value!=''?sersla12Value:'0';
				sersla13Value = sersla13Value!=''?sersla13Value:'0';
				sersla14Value = sersla14Value!=''?sersla14Value:'0';
				sersla15Value = sersla15Value!=''?sersla15Value:'0';

				$("#order-listing1 #sersla_4").text(sersla4Value +'%');
				$("#order-listing1 #sersla_5").text(sersla5Value +'%');
				$("#order-listing1 #sersla_6").text(sersla6Value +'%');
				$("#order-listing1 #sersla_7").text(sersla7Value +'%');
				$("#order-listing1 #sersla_8").text(sersla8Value +'%');
				$("#order-listing1 #sersla_9").text(sersla9Value +'%');
				$("#order-listing1 #sersla_10").text(sersla10Value +'%');
				$("#order-listing1 #sersla_11").text(sersla11Value +'%');
				$("#order-listing1 #sersla_12").text(sersla12Value +'%');
				$("#order-listing1 #sersla_13").text(sersla13Value +'%');
				$("#order-listing1 #sersla_14").text(sersla14Value +'%');
				$("#order-listing1 #sersla_15").text(sersla15Value +'%');
				
				var sersla_avg_q1=(parseInt(sersla4)+parseInt(sersla5)+parseInt(sersla6))/(serq1);
				var sersla_avg_q2=(parseInt(sersla7)+parseInt(sersla8)+parseInt(sersla9))/(serq2);
				var sersla_avg_q3=(parseInt(sersla10)+parseInt(sersla11)+parseInt(sersla12))/(serq3);
				var sersla_avg_q4=(parseInt(sersla13)+parseInt(sersla14)+parseInt(sersla15))/(serq4);
				var sersla_avg_ytd =(parseInt(sersla4)+parseInt(sersla5)+parseInt(sersla6) + parseInt(sersla7)+parseInt(sersla8)+parseInt(sersla9) + parseInt(sersla10)+parseInt(sersla11)+parseInt(sersla12) + parseInt(sersla13)+parseInt(sersla14)+parseInt(sersla15))/serytd;
				
				var sersla_q1 = isNaN(Math.round(sersla_avg_q1*100))?'0':Math.round(sersla_avg_q1*100);
				$("#order-listing1 #sersla_q1").text(sersla_q1+'%');
				var sersla_q2 = isNaN(Math.round(sersla_avg_q2*100))?'0':Math.round(sersla_avg_q2*100);
				$("#order-listing1 #sersla_q2").text(sersla_q2+'%');
				var sersla_q3 = isNaN(Math.round(sersla_avg_q3*100))?'0':Math.round(sersla_avg_q3*100);
				$("#order-listing1 #sersla_q3").text(sersla_q3+'%');
				var sersla_q4 = isNaN(Math.round(sersla_avg_q4*100))?'0':Math.round(sersla_avg_q4*100);
				$("#order-listing1 #sersla_q4").text(sersla_q4+'%');
				var sersla_ytd = isNaN(Math.round(sersla_avg_ytd*100))?'0':Math.round(sersla_avg_ytd*100);
				$("#order-listing1 #sersla_ytd").text(sersla_ytd+'%');

				/*----------------------------------------**************************************************-----------------------------*/

				var seropen4 = $("#order-listing1 #seropen_4").text()!=''?$("#order-listing1 #seropen_4").text():'0';
				var seropen5 = $("#order-listing1 #seropen_5").text()!=''?$("#order-listing1 #seropen_5").text():'0';
				var seropen6 = $("#order-listing1 #seropen_6").text()!=''?$("#order-listing1 #seropen_6").text():'0';
				var seropen7 = $("#order-listing1 #seropen_7").text()!=''?$("#order-listing1 #seropen_7").text():'0';
				var seropen8 = $("#order-listing1 #seropen_8").text()!=''?$("#order-listing1 #seropen_8").text():'0';
				var seropen9 = $("#order-listing1 #seropen_9").text()!=''?$("#order-listing1 #seropen_9").text():'0';
				var seropen10 = $("#order-listing1 #seropen_10").text()!=''?$("#order-listing1 #seropen_10").text():'0';
				var seropen11 = $("#order-listing1 #seropen_11").text()!=''?$("#order-listing1 #seropen_11").text():'0';
				var seropen12 = $("#order-listing1 #seropen_12").text()!=''?$("#order-listing1 #seropen_12").text():'0';
				var seropen13 = $("#order-listing1 #seropen_13").text()!=''?$("#order-listing1 #seropen_13").text():'0';
				var seropen14 = $("#order-listing1 #seropen_14").text()!=''?$("#order-listing1 #seropen_14").text():'0';
				var seropen15 = $("#order-listing1 #seropen_15").text()!=''?$("#order-listing1 #seropen_15").text():'0';

				var seropenq1 = parseInt(seropen4)+parseInt(seropen5)+parseInt(seropen6);
				$("#order-listing1 #seropen_q1").text(seropenq1);
				var seropenq2 = parseInt(seropen7)+parseInt(seropen8)+parseInt(seropen9);

				$("#order-listing1 #seropen_q2").text(seropenq2);
				var seropenq3 = parseInt(seropen10)+parseInt(seropen11)+parseInt(seropen12);
				$("#order-listing1 #seropen_q3").text(seropenq3);
				var seropenq4 = parseInt(seropen13)+parseInt(seropen14)+parseInt(seropen15);
				$("#order-listing1 #seropen_q4").text(seropenq4);
				var seropenytd = (seropenq1)+(seropenq2)+(seropenq3)+(seropenq4);
				$("#order-listing1 #seropen_ytd").text(seropenytd);

				/*----------------------------------------**************************************************-----------------------------*/

				var serreopen4 = $("#order-listing1 #serreopen_4").text()!=''?$("#order-listing1 #serreopen_4").text():'0';
				var serreopen5 = $("#order-listing1 #serreopen_5").text()!=''?$("#order-listing1 #serreopen_5").text():'0';
				var serreopen6 = $("#order-listing1 #serreopen_6").text()!=''?$("#order-listing1 #serreopen_6").text():'0';
				var serreopen7 = $("#order-listing1 #serreopen_7").text()!=''?$("#order-listing1 #serreopen_7").text():'0';
				var serreopen8 = $("#order-listing1 #serreopen_8").text()!=''?$("#order-listing1 #serreopen_8").text():'0';
				var serreopen9 = $("#order-listing1 #serreopen_9").text()!=''?$("#order-listing1 #serreopen_9").text():'0';
				var serreopen10 = $("#order-listing1 #serreopen_10").text()!=''?$("#order-listing1 #serreopen_10").text():'0';
				var serreopen11 = $("#order-listing1 #serreopen_11").text()!=''?$("#order-listing1 #serreopen_11").text():'0';
				var serreopen12 = $("#order-listing1 #serreopen_12").text()!=''?$("#order-listing1 #serreopen_12").text():'0';
				var serreopen13 = $("#order-listing1 #serreopen_13").text()!=''?$("#order-listing1 #serreopen_13").text():'0';
				var serreopen14 = $("#order-listing1 #serreopen_14").text()!=''?$("#order-listing1 #serreopen_14").text():'0';
				var serreopen15 = $("#order-listing1 #serreopen_15").text()!=''?$("#order-listing1 #serreopen_15").text():'0';

				var serreopen4Value = isNaN(Math.round((parseInt(serreopen4)/ parseInt(ser4))*100))?'':Math.round((parseInt(serreopen4)/ parseInt(ser4))*100);
				var serreopen5Value = isNaN(Math.round((parseInt(serreopen5)/ parseInt(ser5))*100))?'':Math.round((parseInt(serreopen5)/ parseInt(ser5))*100);
				var serreopen6Value =isNaN( Math.round((parseInt(serreopen6)/ parseInt(ser6))*100))?'':Math.round((parseInt(serreopen6)/ parseInt(ser6))*100);
				var serreopen7Value = isNaN(Math.round((parseInt(serreopen7)/ parseInt(ser7))*100))?'':Math.round((parseInt(serreopen7)/ parseInt(ser7))*100) ;
				var serreopen8Value = isNaN(Math.round((parseInt(serreopen8)/ parseInt(ser8))*100))?'':Math.round((parseInt(serreopen8)/ parseInt(ser8))*100) ;
				var serreopen9Value = isNaN(Math.round((parseInt(serreopen9)/ parseInt(ser9))*100))?'':Math.round((parseInt(serreopen9)/ parseInt(ser9))*100) ;
				var serreopen10Value = isNaN(Math.round((parseInt(serreopen10)/ parseInt(ser10))*100))?'':Math.round((parseInt(serreopen10)/ parseInt(ser10))*100) ;
				var serreopen11Value = isNaN(Math.round((parseInt(serreopen11)/ parseInt(ser11))*100))?'':Math.round((parseInt(serreopen11)/ parseInt(ser11))*100);
				var serreopen12Value =isNaN( Math.round((parseInt(serreopen12)/ parseInt(ser12))*100))?'':Math.round((parseInt(serreopen12)/ parseInt(ser12))*100) ;
				var serreopen13Value = isNaN(Math.round((parseInt(serreopen13)/ parseInt(ser13))*100))?'':Math.round((parseInt(serreopen13)/ parseInt(ser13))*100) ;
				var serreopen14Value = isNaN(Math.round((parseInt(serreopen14)/ parseInt(ser14))*100))?'':Math.round((parseInt(serreopen14)/ parseInt(ser14))*100) ;
				var serreopen15Value = isNaN(Math.round((parseInt(serreopen15)/ parseInt(ser15))*100))?'':Math.round((parseInt(serreopen15)/ parseInt(ser15))*100) ;

				serreopen4Value = serreopen4Value!=''?serreopen4Value:'0';
				serreopen5Value = serreopen5Value!=''?serreopen5Value:'0';
				serreopen6Value = serreopen6Value!=''?serreopen6Value:'0';
				serreopen7Value = serreopen7Value!=''?serreopen7Value:'0';
				serreopen8Value = serreopen8Value!=''?serreopen8Value:'0';
				serreopen9Value = serreopen9Value!=''?serreopen9Value:'0';
				serreopen10Value = serreopen10Value!=''?serreopen10Value:'0';
				serreopen11Value = serreopen11Value!=''?serreopen11Value:'0';
				serreopen12Value = serreopen12Value!=''?serreopen12Value:'0';
				serreopen13Value = serreopen13Value!=''?serreopen13Value:'0';
				serreopen14Value = serreopen14Value!=''?serreopen14Value:'0';
				serreopen15Value = serreopen15Value!=''?serreopen15Value:'0';

				$("#order-listing1 #serreopen_4").text(serreopen4Value +'%');
				$("#order-listing1 #serreopen_5").text(serreopen5Value +'%');
				$("#order-listing1 #serreopen_6").text(serreopen6Value +'%');
				$("#order-listing1 #serreopen_7").text(serreopen7Value +'%');
				$("#order-listing1 #serreopen_8").text(serreopen8Value +'%');
				$("#order-listing1 #serreopen_9").text(serreopen9Value +'%');
				$("#order-listing1 #serreopen_10").text(serreopen10Value +'%');
				$("#order-listing1 #serreopen_11").text(serreopen11Value +'%');
				$("#order-listing1 #serreopen_12").text(serreopen12Value +'%');
				$("#order-listing1 #serreopen_13").text(serreopen13Value +'%');
				$("#order-listing1 #serreopen_14").text(serreopen14Value +'%');
				$("#order-listing1 #serreopen_15").text(serreopen15Value +'%');
				
				var serreopen_avg_q1=(parseInt(serreopen4)+parseInt(serreopen5)+parseInt(serreopen6))/(serq1);
				var serreopen_avg_q2=(parseInt(serreopen7)+parseInt(serreopen8)+parseInt(serreopen9))/(serq2);
				var serreopen_avg_q3=(parseInt(serreopen10)+parseInt(serreopen11)+parseInt(serreopen12))/(serq3);
				var serreopen_avg_q4=(parseInt(serreopen13)+parseInt(serreopen14)+parseInt(serreopen15))/(serq4);
				var serreopen_avg_ytd =(parseInt(serreopen4)+parseInt(serreopen5)+parseInt(serreopen6) + parseInt(serreopen7)+parseInt(serreopen8)+parseInt(serreopen9) + parseInt(serreopen10)+parseInt(serreopen11)+parseInt(serreopen12) + parseInt(serreopen13)+parseInt(serreopen14)+parseInt(serreopen15))/serytd;
				
				var serreopen_q1 = isNaN(Math.round(serreopen_avg_q1*100))?'0':Math.round(serreopen_avg_q1*100);
				$("#order-listing1 #serreopen_q1").text(serreopen_q1+'%');
				var serreopen_q2 = isNaN(Math.round(serreopen_avg_q2*100))?'0':Math.round(serreopen_avg_q2*100);
				$("#order-listing1 #serreopen_q2").text(serreopen_q2+'%');
				var serreopen_q3 = isNaN(Math.round(serreopen_avg_q3*100))?'0':Math.round(serreopen_avg_q3*100);
				$("#order-listing1 #serreopen_q3").text(serreopen_q3+'%');
				var serreopen_q4 = isNaN(Math.round(serreopen_avg_q4*100))?'0':Math.round(serreopen_avg_q4*100);
				$("#order-listing1 #serreopen_q4").text(serreopen_q4+'%');
				var serreopen_ytd = isNaN(Math.round(serreopen_avg_ytd*100))?'0':Math.round(serreopen_avg_ytd*100);
				$("#order-listing1 #serreopen_ytd").text(serreopen_ytd+'%');

				/*----------------------------------------**************************************************-----------------------------*/

				var serfeedback4 = $("#order-listing1 #serfeedback_4").text()!=''?$("#order-listing1 #serfeedback_4").text():'0';
				var serfeedback5 = $("#order-listing1 #serfeedback_5").text()!=''?$("#order-listing1 #serfeedback_5").text():'0';
				var serfeedback6 = $("#order-listing1 #serfeedback_6").text()!=''?$("#order-listing1 #serfeedback_6").text():'0';
				var serfeedback7 = $("#order-listing1 #serfeedback_7").text()!=''?$("#order-listing1 #serfeedback_7").text():'0';
				var serfeedback8 = $("#order-listing1 #serfeedback_8").text()!=''?$("#order-listing1 #serfeedback_8").text():'0';
				var serfeedback9 = $("#order-listing1 #serfeedback_9").text()!=''?$("#order-listing1 #serfeedback_9").text():'0';
				var serfeedback10 = $("#order-listing1 #serfeedback_10").text()!=''?$("#order-listing1 #serfeedback_10").text():'0';
				var serfeedback11 = $("#order-listing1 #serfeedback_11").text()!=''?$("#order-listing1 #serfeedback_11").text():'0';
				var serfeedback12 = $("#order-listing1 #serfeedback_12").text()!=''?$("#order-listing1 #serfeedback_12").text():'0';
				var serfeedback13 = $("#order-listing1 #serfeedback_13").text()!=''?$("#order-listing1 #serfeedback_13").text():'0';
				var serfeedback14 = $("#order-listing1 #serfeedback_14").text()!=''?$("#order-listing1 #serfeedback_14").text():'0';
				var serfeedback15 = $("#order-listing1 #serfeedback_15").text()!=''?$("#order-listing1 #serfeedback_15").text():'0';

				var serfeedbackq1 = parseInt(serfeedback4)+parseInt(serfeedback5)+parseInt(serfeedback6);
				$("#order-listing1 #serfeedback_q1").text(serfeedbackq1);
				var serfeedbackq2 = parseInt(serfeedback7)+parseInt(serfeedback8)+parseInt(serfeedback9);

				$("#order-listing1 #serfeedback_q2").text(serfeedbackq2);
				var serfeedbackq3 = parseInt(serfeedback10)+parseInt(serfeedback11)+parseInt(serfeedback12);
				$("#order-listing1 #serfeedback_q3").text(serfeedbackq3);
				var serfeedbackq4 = parseInt(serfeedback13)+parseInt(serfeedback14)+parseInt(serfeedback15);
				$("#order-listing1 #serfeedback_q4").text(serfeedbackq4);
				var serfeedbackytd = (serfeedbackq1)+(serfeedbackq2)+(serfeedbackq3)+(serfeedbackq4);
				$("#order-listing1 #serfeedback_ytd").text(serfeedbackytd);

				/*----------------------------------------**************************************************-----------------------------*/

				/*var serpcs4 = $("#order-listing1 #serpcs_4").text()!=''?$("#order-listing1 #serpcs_4").text():'0';
				var serpcs5 = $("#order-listing1 #serpcs_5").text()!=''?$("#order-listing1 #serpcs_5").text():'0';
				var serpcs6 = $("#order-listing1 #serpcs_6").text()!=''?$("#order-listing1 #serpcs_6").text():'0';
				var serpcs7 = $("#order-listing1 #serpcs_7").text()!=''?$("#order-listing1 #serpcs_7").text():'0';
				var serpcs8 = $("#order-listing1 #serpcs_8").text()!=''?$("#order-listing1 #serpcs_8").text():'0';
				var serpcs9 = $("#order-listing1 #serpcs_9").text()!=''?$("#order-listing1 #serpcs_9").text():'0';
				var serpcs10 = $("#order-listing1 #serpcs_10").text()!=''?$("#order-listing1 #serpcs_10").text():'0';
				var serpcs11 = $("#order-listing1 #serpcs_11").text()!=''?$("#order-listing1 #serpcs_11").text():'0';
				var serpcs12 = $("#order-listing1 #serpcs_12").text()!=''?$("#order-listing1 #serpcs_12").text():'0';
				var serpcs13 = $("#order-listing1 #serpcs_13").text()!=''?$("#order-listing1 #serpcs_13").text():'0';
				var serpcs14 = $("#order-listing1 #serpcs_14").text()!=''?$("#order-listing1 #serpcs_14").text():'0';
				var serpcs15 = $("#order-listing1 #serpcs_15").text()!=''?$("#order-listing1 #serpcs_15").text():'0';*/

				var serpcs4 = $('#serpcs_4').text();
				var serpcs5 = $('#serpcs_5').text();
				var serpcs6 = $('#serpcs_6').text();
				var serpcs7 = $('#serpcs_7').text();
				var serpcs8 = $('#serpcs_8').text();
				var serpcs9 = $('#serpcs_9').text();
				var serpcs10 = $('#serpcs_10').text();
				var serpcs11 = $('#serpcs_11').text();
				var serpcs12 = $('#serpcs_12').text();
				var serpcs13 = $('#serpcs_13').text();
				var serpcs14 = $('#serpcs_14').text();
				var serpcs15 = $('#serpcs_15').text();

				serpcs4 =serpcs4.split("~~");
				serpcs5 =serpcs5.split("~~");
				serpcs6 =serpcs6.split("~~");
				serpcs7 =serpcs7.split("~~");
				serpcs8 =serpcs8.split("~~");
				serpcs9 =serpcs9.split("~~");
				serpcs10 =serpcs10.split("~~");
				serpcs11 =serpcs11.split("~~");
				serpcs12 =serpcs12.split("~~");
				serpcs13 =serpcs13.split("~~");
				serpcs14 =serpcs14.split("~~");
				serpcs15 =serpcs15.split("~~");

				var q1Feedback = parseInt(serpcs4[0])+parseInt(serpcs5[0])+parseInt(serpcs6[0]);
				var q1TotalFeedback = parseInt(serpcs4[1])+parseInt(serpcs5[1])+parseInt(serpcs6[1]);
				q1TotalFeedback=q1TotalFeedback!=0?q1TotalFeedback:1;
				var q1Avg = isNaN(Math.round((q1Feedback/q1TotalFeedback)*100))?'0':Math.round((q1Feedback/q1TotalFeedback)*100);
				$("#order-listing1 #serpcs_q1").text(q1Avg+'%');

				var q2Feedback = parseInt(serpcs7[0])+parseInt(serpcs8[0])+parseInt(serpcs9[0]);
				var q2TotalFeedback = parseInt(serpcs7[1])+parseInt(serpcs8[1])+parseInt(serpcs9[1]);
				q2TotalFeedback=q2TotalFeedback!=0?q2TotalFeedback:1;
				var q2Avg = isNaN(Math.round((q2Feedback/q2TotalFeedback)*100))?'0':Math.round((q2Feedback/q2TotalFeedback)*100);
				$("#order-listing1 #serpcs_q2").text(q2Avg+'%');

				var q3Feedback = parseInt(serpcs10[0])+parseInt(serpcs11[0])+parseInt(serpcs12[0]);
				var q3TotalFeedback = parseInt(serpcs10[1])+parseInt(serpcs11[1])+parseInt(serpcs12[1]);
				q3TotalFeedback=q3TotalFeedback!=0?q3TotalFeedback:1;
				var q3Avg = isNaN(Math.round((q3Feedback/q3TotalFeedback)*100))?'0':Math.round((q3Feedback/q3TotalFeedback)*100);
				$("#order-listing1 #serpcs_q3").text(q3Avg+'%');

				var q4Feedback = parseInt(serpcs13[0])+parseInt(serpcs14[0])+parseInt(serpcs15[0]);
				var q4TotalFeedback = parseInt(serpcs13[1])+parseInt(serpcs14[1])+parseInt(serpcs15[1]);
				q4TotalFeedback=q4TotalFeedback!=0?q4TotalFeedback:1;
				var q4Avg = isNaN(Math.round((q4Feedback/q4TotalFeedback)*100))?'0':Math.round((q4Feedback/q4TotalFeedback)*100);
				$("#order-listing1 #serpcs_q4").text(q4Avg+'%');

				var ytdFeedback = (q1Feedback)+(q1Feedback)+(q1Feedback)+(q1Feedback);
				var ytdTotalFeedback = (parseInt(serpcs4[1])+parseInt(serpcs5[1])+parseInt(serpcs6[1]) )+(parseInt(serpcs7[1])+parseInt(serpcs8[1])+parseInt(serpcs9[1]) )+(parseInt(serpcs10[1])+parseInt(serpcs11[1])+parseInt(serpcs12[1]) )+(parseInt(serpcs13[1])+parseInt(serpcs14[1])+parseInt(serpcs15[1]) );
				ytdTotalFeedback = ytdTotalFeedback!=0?ytdTotalFeedback:1;
				var ytdAvg = isNaN(Math.round((ytdFeedback/ytdTotalFeedback)*100))?'0':Math.round((ytdFeedback/ytdTotalFeedback)*100);
				$("#order-listing1 #serpcs_ytd").text(ytdAvg+'%');
				/* ****************************************Service*******************************************************/
				/* ****************************************Sales*******************************************************/
				$('#sale_16').hide();
				$('#sale_17').hide();
				$('#sale_18').hide();
				var sale4 = $("#order-listing1 #sale_4").text()!=''?$("#order-listing1 #sale_4").text():'0';
				var sale5 = $("#order-listing1 #sale_5").text()!=''?$("#order-listing1 #sale_5").text():'0';
				var sale6 = $("#order-listing1 #sale_6").text()!=''?$("#order-listing1 #sale_6").text():'0';
				var sale7 = $("#order-listing1 #sale_7").text()!=''?$("#order-listing1 #sale_7").text():'0';
				var sale8 = $("#order-listing1 #sale_8").text()!=''?$("#order-listing1 #sale_8").text():'0';
				var sale9 = $("#order-listing1 #sale_9").text()!=''?$("#order-listing1 #sale_9").text():'0';
				var sale10 = $("#order-listing1 #sale_10").text()!=''?$("#order-listing1 #sale_10").text():'0';
				var sale11 = $("#order-listing1 #sale_11").text()!=''?$("#order-listing1 #sale_11").text():'0';
				var sale12 = $("#order-listing1 #sale_12").text()!=''?$("#order-listing1 #sale_12").text():'0';
				var sale13 = $("#order-listing1 #sale_13").text()!=''?$("#order-listing1 #sale_13").text():'0';
				var sale14 = $("#order-listing1 #sale_14").text()!=''?$("#order-listing1 #sale_14").text():'0';
				var sale15 = $("#order-listing1 #sale_15").text()!=''?$("#order-listing1 #sale_15").text():'0';

				var saleq1 = parseInt(sale4)+parseInt(sale5)+parseInt(sale6);
				$("#order-listing1 #sale_q1").text(saleq1);
				var saleq2 = parseInt(sale7)+parseInt(sale8)+parseInt(sale9);

				$("#order-listing1 #sale_q2").text(saleq2);
				var saleq3 = parseInt(sale10)+parseInt(sale11)+parseInt(sale12);
				$("#order-listing1 #sale_q3").text(saleq3);
				var saleq4 = parseInt(sale13)+parseInt(sale14)+parseInt(sale15);
				$("#order-listing1 #sale_q4").text(saleq4);
				var saleytd = (saleq1)+(saleq2)+(saleq3)+(saleq4);
				$("#order-listing1 #sale_ytd").text(saleytd);

				/*----------------------------------------**************************************************-----------------------------*/

				var saleack4 = $("#order-listing1 #saleack_4").text()!=''?$("#order-listing1 #saleack_4").text():'0';
				var saleack5 = $("#order-listing1 #saleack_5").text()!=''?$("#order-listing1 #saleack_5").text():'0';
				var saleack6 = $("#order-listing1 #saleack_6").text()!=''?$("#order-listing1 #saleack_6").text():'0';
				var saleack7 = $("#order-listing1 #saleack_7").text()!=''?$("#order-listing1 #saleack_7").text():'0';
				var saleack8 = $("#order-listing1 #saleack_8").text()!=''?$("#order-listing1 #saleack_8").text():'0';
				var saleack9 = $("#order-listing1 #saleack_9").text()!=''?$("#order-listing1 #saleack_9").text():'0';
				var saleack10 = $("#order-listing1 #saleack_10").text()!=''?$("#order-listing1 #saleack_10").text():'0';
				var saleack11 = $("#order-listing1 #saleack_11").text()!=''?$("#order-listing1 #saleack_11").text():'0';
				var saleack12 = $("#order-listing1 #saleack_12").text()!=''?$("#order-listing1 #saleack_12").text():'0';
				var saleack13 = $("#order-listing1 #saleack_13").text()!=''?$("#order-listing1 #saleack_13").text():'0';
				var saleack14 = $("#order-listing1 #saleack_14").text()!=''?$("#order-listing1 #saleack_14").text():'0';
				var saleack15 = $("#order-listing1 #saleack_15").text()!=''?$("#order-listing1 #saleack_15").text():'0';

				var saleack4Value = isNaN(Math.round((parseInt(saleack4)/ parseInt(sale4))*100))?'':Math.round((parseInt(saleack4)/ parseInt(sale4))*100);
				var saleack5Value = isNaN(Math.round((parseInt(saleack5)/ parseInt(sale5))*100))?'':Math.round((parseInt(saleack5)/ parseInt(sale5))*100);
				var saleack6Value = isNaN(Math.round((parseInt(saleack6)/ parseInt(sale6))*100))?'':Math.round((parseInt(saleack6)/ parseInt(sale6))*100);
				var saleack7Value = isNaN(Math.round((parseInt(saleack7)/ parseInt(sale7))*100))?'':Math.round((parseInt(saleack7)/ parseInt(sale7))*100);
				var saleack8Value = isNaN(Math.round((parseInt(saleack8)/ parseInt(sale8))*100))?'':Math.round((parseInt(saleack8)/ parseInt(sale8))*100) ;
				var saleack9Value = isNaN(Math.round((parseInt(saleack9)/ parseInt(sale9))*100))?'':Math.round((parseInt(saleack9)/ parseInt(sale9))*100);
				var saleack10Value = isNaN(Math.round((parseInt(saleack10)/ parseInt(sale10))*100))?'':Math.round((parseInt(saleack10)/ parseInt(sale10))*100);
				var saleack11Value = isNaN(Math.round((parseInt(saleack11)/ parseInt(sale11))*100))?'': Math.round((parseInt(saleack11)/ parseInt(sale11))*100);
				var saleack12Value = isNaN(Math.round((parseInt(saleack12)/ parseInt(sale12))*100))?'': Math.round((parseInt(saleack12)/ parseInt(sale12))*100);
				var saleack13Value = isNaN(Math.round((parseInt(saleack13)/ parseInt(sale13))*100))?'':Math.round((parseInt(saleack13)/ parseInt(sale13))*100) ;
				var saleack14Value = isNaN(Math.round((parseInt(saleack14)/ parseInt(sale14))*100))?'':Math.round((parseInt(saleack14)/ parseInt(sale14))*100) ;
				var saleack15Value = isNaN(Math.round((parseInt(saleack15)/ parseInt(sale15))*100))?'':Math.round((parseInt(saleack15)/ parseInt(sale15))*100) ;

				saleack4Value = saleack4Value!=''?saleack4Value:'0';
				saleack5Value = saleack5Value!=''?saleack5Value:'0';
				saleack6Value = saleack6Value!=''?saleack6Value:'0';
				saleack7Value = saleack7Value!=''?saleack7Value:'0';
				saleack8Value = saleack8Value!=''?saleack8Value:'0';
				saleack9Value = saleack9Value!=''?saleack9Value:'0';
				saleack10Value = saleack10Value!=''?saleack10Value:'0';
				saleack11Value = saleack11Value!=''?saleack11Value:'0';
				saleack12Value = saleack12Value!=''?saleack12Value:'0';
				saleack13Value = saleack13Value!=''?saleack13Value:'0';
				saleack14Value = saleack14Value!=''?saleack14Value:'0';
				saleack15Value = saleack15Value!=''?saleack15Value:'0';

				$("#order-listing1 #saleack_4").text(saleack4Value +'%');
				$("#order-listing1 #saleack_5").text(saleack5Value +'%');
				$("#order-listing1 #saleack_6").text(saleack6Value +'%');
				$("#order-listing1 #saleack_7").text(saleack7Value +'%');
				$("#order-listing1 #saleack_8").text(saleack8Value +'%');
				$("#order-listing1 #saleack_9").text(saleack9Value +'%');
				$("#order-listing1 #saleack_10").text(saleack10Value +'%');
				$("#order-listing1 #saleack_11").text(saleack11Value +'%');
				$("#order-listing1 #saleack_12").text(saleack12Value +'%');
				$("#order-listing1 #saleack_13").text(saleack13Value +'%');
				$("#order-listing1 #saleack_14").text(saleack14Value +'%');
				$("#order-listing1 #saleack_15").text(saleack15Value +'%');
				
				var saleack_avg_q1=(parseInt(saleack4)+parseInt(saleack5)+parseInt(saleack6))/(saleq1);
				var saleack_avg_q2=(parseInt(saleack7)+parseInt(saleack8)+parseInt(saleack9))/(saleq2);
				var saleack_avg_q3=(parseInt(saleack10)+parseInt(saleack11)+parseInt(saleack12))/(saleq3);
				var saleack_avg_q4=(parseInt(saleack13)+parseInt(saleack14)+parseInt(saleack15))/(saleq4);
				var saleack_avg_ytd =(parseInt(saleack4)+parseInt(saleack5)+parseInt(saleack6) + parseInt(saleack7)+parseInt(saleack8)+parseInt(saleack9) + parseInt(saleack10)+parseInt(saleack11)+parseInt(saleack12) + parseInt(saleack13)+parseInt(saleack14)+parseInt(saleack15))/saleytd;
				
				var saleack_q1 = isNaN(Math.round(saleack_avg_q1*100))?'0':Math.round(saleack_avg_q1*100);
				$("#order-listing1 #saleack_q1").text(saleack_q1+'%');
				var saleack_q2 = isNaN(Math.round(saleack_avg_q2*100))?'0':Math.round(saleack_avg_q2*100);
				$("#order-listing1 #saleack_q2").text(saleack_q2+'%');
				var saleack_q3 = isNaN(Math.round(saleack_avg_q3*100))?'0':Math.round(saleack_avg_q3*100);
				$("#order-listing1 #saleack_q3").text(saleack_q3+'%');
				var saleack_q4 = isNaN(Math.round(saleack_avg_q4*100))?'0':Math.round(saleack_avg_q4*100);
				$("#order-listing1 #saleack_q4").text(saleack_q4+'%');
				var saleack_ytd =isNaN(Math.round(saleack_avg_ytd*100))?'0':Math.round(saleack_avg_ytd*100);
				$("#order-listing1 #saleack_ytd").text(saleack_ytd+'%');

				/*----------------------------------------**************************************************-----------------------------*/
				var salesla4 = $("#order-listing1 #salesla_4").text()!=''?$("#order-listing1 #salesla_4").text():'0';
				var salesla5 = $("#order-listing1 #salesla_5").text()!=''?$("#order-listing1 #salesla_5").text():'0';
				var salesla6 = $("#order-listing1 #salesla_6").text()!=''?$("#order-listing1 #salesla_6").text():'0';
				var salesla7 = $("#order-listing1 #salesla_7").text()!=''?$("#order-listing1 #salesla_7").text():'0';
				var salesla8 = $("#order-listing1 #salesla_8").text()!=''?$("#order-listing1 #salesla_8").text():'0';
				var salesla9 = $("#order-listing1 #salesla_9").text()!=''?$("#order-listing1 #salesla_9").text():'0';
				var salesla10 = $("#order-listing1 #salesla_10").text()!=''?$("#order-listing1 #salesla_10").text():'0';
				var salesla11 = $("#order-listing1 #salesla_11").text()!=''?$("#order-listing1 #salesla_11").text():'0';
				var salesla12 = $("#order-listing1 #salesla_12").text()!=''?$("#order-listing1 #salesla_12").text():'0';
				var salesla13 = $("#order-listing1 #salesla_13").text()!=''?$("#order-listing1 #salesla_13").text():'0';
				var salesla14 = $("#order-listing1 #salesla_14").text()!=''?$("#order-listing1 #salesla_14").text():'0';
				var salesla15 = $("#order-listing1 #salesla_15").text()!=''?$("#order-listing1 #salesla_15").text():'0';

				var salesla4Value = isNaN(Math.round((parseInt(salesla4)/ parseInt(sale4))*100))?'':Math.round((parseInt(salesla4)/ parseInt(sale4))*100);
				var salesla5Value = isNaN(Math.round((parseInt(salesla5)/ parseInt(sale5))*100 ))?'':Math.round((parseInt(salesla5)/ parseInt(sale5))*100);
				var salesla6Value = isNaN(Math.round((parseInt(salesla6)/ parseInt(sale6))*100 ))?'':Math.round((parseInt(salesla6)/ parseInt(sale6))*100);
				var salesla7Value = isNaN(Math.round((parseInt(salesla7)/ parseInt(sale7))*100 ))?'':Math.round((parseInt(salesla7)/ parseInt(sale7))*100);
				var salesla8Value = isNaN(Math.round((parseInt(salesla8)/ parseInt(sale8))*100 ))?'':Math.round((parseInt(salesla8)/ parseInt(sale8))*100);
				var salesla9Value = isNaN(Math.round((parseInt(salesla9)/ parseInt(sale9))*100 ))?'':Math.round((parseInt(salesla9)/ parseInt(sale9))*100);
				var salesla10Value = isNaN(Math.round((parseInt(salesla10)/ parseInt(sale10))*100 ))?'':Math.round((parseInt(salesla10)/ parseInt(sale10))*100);
				var salesla11Value = isNaN(Math.round((parseInt(salesla11)/ parseInt(sale11))*100 ))?'':Math.round((parseInt(salesla11)/ parseInt(sale11))*100);
				var salesla12Value = isNaN(Math.round((parseInt(salesla12)/ parseInt(sale12))*100 ))?'':Math.round((parseInt(salesla12)/ parseInt(sale12))*100);
				var salesla13Value = isNaN(Math.round((parseInt(salesla13)/ parseInt(sale13))*100 ))?'':Math.round((parseInt(salesla13)/ parseInt(sale13))*100);
				var salesla14Value = isNaN(Math.round((parseInt(salesla14)/ parseInt(sale14))*100 ))?'':Math.round((parseInt(salesla14)/ parseInt(sale14))*100);
				var salesla15Value = isNaN(Math.round((parseInt(salesla15)/ parseInt(sale15))*100 ))?'':Math.round((parseInt(salesla15)/ parseInt(sale15))*100);

				salesla4Value = salesla4Value!=''?salesla4Value:'0';
				salesla5Value = salesla5Value!=''?salesla5Value:'0';
				salesla6Value = salesla6Value!=''?salesla6Value:'0';
				salesla7Value = salesla7Value!=''?salesla7Value:'0';
				salesla8Value = salesla8Value!=''?salesla8Value:'0';
				salesla9Value = salesla9Value!=''?salesla9Value:'0';
				salesla10Value = salesla10Value!=''?salesla10Value:'0';
				salesla11Value = salesla11Value!=''?salesla11Value:'0';
				salesla12Value = salesla12Value!=''?salesla12Value:'0';
				salesla13Value = salesla13Value!=''?salesla13Value:'0';
				salesla14Value = salesla14Value!=''?salesla14Value:'0';
				salesla15Value = salesla15Value!=''?salesla15Value:'0';

				$("#order-listing1 #salesla_4").text(salesla4Value+'%');
				$("#order-listing1 #salesla_5").text(salesla5Value+'%');
				$("#order-listing1 #salesla_6").text(salesla6Value+'%');
				$("#order-listing1 #salesla_7").text(salesla7Value+'%');
				$("#order-listing1 #salesla_8").text(salesla8Value+'%');
				$("#order-listing1 #salesla_9").text(salesla9Value+'%');
				$("#order-listing1 #salesla_10").text(salesla10Value+'%');
				$("#order-listing1 #salesla_11").text(salesla11Value+'%');
				$("#order-listing1 #salesla_12").text(salesla12Value+'%');
				$("#order-listing1 #salesla_13").text(salesla13Value+'%');
				$("#order-listing1 #salesla_14").text(salesla14Value+'%');
				$("#order-listing1 #salesla_15").text(salesla15Value+'%');
				
				var salesla_avg_q1=(parseInt(salesla4)+parseInt(salesla5)+parseInt(salesla6))/(saleq1);
				var salesla_avg_q2=(parseInt(salesla7)+parseInt(salesla8)+parseInt(salesla9))/(saleq2);
				var salesla_avg_q3=(parseInt(salesla10)+parseInt(salesla11)+parseInt(salesla12))/(saleq3);
				var salesla_avg_q4=(parseInt(salesla13)+parseInt(salesla14)+parseInt(salesla15))/(saleq4);
				var salesla_avg_ytd =(parseInt(salesla4)+parseInt(salesla5)+parseInt(salesla6) + parseInt(salesla7)+parseInt(salesla8)+parseInt(salesla9) + parseInt(salesla10)+parseInt(salesla11)+parseInt(salesla12) + parseInt(salesla13)+parseInt(salesla14)+parseInt(salesla15))/saleytd;
				
				var salesla_q1 =isNaN( Math.round(salesla_avg_q1*100))?'0':Math.round(salesla_avg_q1*100);
				$("#order-listing1 #salesla_q1").text(salesla_q1+'%');
				var salesla_q2 = isNaN( Math.round(salesla_avg_q2*100))?'0':Math.round(salesla_avg_q2*100);
				$("#order-listing1 #salesla_q2").text(salesla_q2+'%');
				var salesla_q3 = isNaN( Math.round(salesla_avg_q3*100))?'0':Math.round(salesla_avg_q3*100);
				$("#order-listing1 #salesla_q3").text(salesla_q3+'%');
				var salesla_q4 = isNaN( Math.round(salesla_avg_q4*100))?'0':Math.round(salesla_avg_q4*100);
				$("#order-listing1 #salesla_q4").text(salesla_q4+'%');
				var salesla_ytd = isNaN( Math.round(salesla_avg_ytd*100))?'0':Math.round(salesla_avg_ytd*100);
				$("#order-listing1 #salesla_ytd").text(salesla_ytd+'%');

				/*----------------------------------------**************************************************-----------------------------*/

				var saleopen4 = $("#order-listing1 #saleopen_4").text()!=''?$("#order-listing1 #saleopen_4").text():'0';
				var saleopen5 = $("#order-listing1 #saleopen_5").text()!=''?$("#order-listing1 #saleopen_5").text():'0';
				var saleopen6 = $("#order-listing1 #saleopen_6").text()!=''?$("#order-listing1 #saleopen_6").text():'0';
				var saleopen7 = $("#order-listing1 #saleopen_7").text()!=''?$("#order-listing1 #saleopen_7").text():'0';
				var saleopen8 = $("#order-listing1 #saleopen_8").text()!=''?$("#order-listing1 #saleopen_8").text():'0';
				var saleopen9 = $("#order-listing1 #saleopen_9").text()!=''?$("#order-listing1 #saleopen_9").text():'0';
				var saleopen10 = $("#order-listing1 #saleopen_10").text()!=''?$("#order-listing1 #saleopen_10").text():'0';
				var saleopen11 = $("#order-listing1 #saleopen_11").text()!=''?$("#order-listing1 #saleopen_11").text():'0';
				var saleopen12 = $("#order-listing1 #saleopen_12").text()!=''?$("#order-listing1 #saleopen_12").text():'0';
				var saleopen13 = $("#order-listing1 #saleopen_13").text()!=''?$("#order-listing1 #saleopen_13").text():'0';
				var saleopen14 = $("#order-listing1 #saleopen_14").text()!=''?$("#order-listing1 #saleopen_14").text():'0';
				var saleopen15 = $("#order-listing1 #saleopen_15").text()!=''?$("#order-listing1 #saleopen_15").text():'0';

				var saleopenq1 = parseInt(saleopen4)+parseInt(saleopen5)+parseInt(saleopen6);
				$("#order-listing1 #saleopen_q1").text(saleopenq1);
				var saleopenq2 = parseInt(saleopen7)+parseInt(saleopen8)+parseInt(saleopen9);

				$("#order-listing1 #saleopen_q2").text(saleopenq2);
				var saleopenq3 = parseInt(saleopen10)+parseInt(saleopen11)+parseInt(saleopen12);
				$("#order-listing1 #saleopen_q3").text(saleopenq3);
				var saleopenq4 = parseInt(saleopen13)+parseInt(saleopen14)+parseInt(saleopen15);
				$("#order-listing1 #saleopen_q4").text(saleopenq4);
				var saleopenytd = (saleopenq1)+(saleopenq2)+(saleopenq3)+(saleopenq4);
				$("#order-listing1 #saleopen_ytd").text(saleopenytd);

				/*----------------------------------------**************************************************-----------------------------*/

				var salereopen4 = $("#order-listing1 #salereopen_4").text()!=''?$("#order-listing1 #salereopen_4").text():'0';
				var salereopen5 = $("#order-listing1 #salereopen_5").text()!=''?$("#order-listing1 #salereopen_5").text():'0';
				var salereopen6 = $("#order-listing1 #salereopen_6").text()!=''?$("#order-listing1 #salereopen_6").text():'0';
				var salereopen7 = $("#order-listing1 #salereopen_7").text()!=''?$("#order-listing1 #salereopen_7").text():'0';
				var salereopen8 = $("#order-listing1 #salereopen_8").text()!=''?$("#order-listing1 #salereopen_8").text():'0';
				var salereopen9 = $("#order-listing1 #salereopen_9").text()!=''?$("#order-listing1 #salereopen_9").text():'0';
				var salereopen10 = $("#order-listing1 #salereopen_10").text()!=''?$("#order-listing1 #salereopen_10").text():'0';
				var salereopen11 = $("#order-listing1 #salereopen_11").text()!=''?$("#order-listing1 #salereopen_11").text():'0';
				var salereopen12 = $("#order-listing1 #salereopen_12").text()!=''?$("#order-listing1 #salereopen_12").text():'0';
				var salereopen13 = $("#order-listing1 #salereopen_13").text()!=''?$("#order-listing1 #salereopen_13").text():'0';
				var salereopen14 = $("#order-listing1 #salereopen_14").text()!=''?$("#order-listing1 #salereopen_14").text():'0';
				var salereopen15 = $("#order-listing1 #salereopen_15").text()!=''?$("#order-listing1 #salereopen_15").text():'0';

				var salereopen4Value =isNaN( Math.round((parseInt(salereopen4)/ parseInt(sale4))*100))?'':Math.round((parseInt(salereopen4)/ parseInt(sale4))*100) ;
				var salereopen5Value = isNaN(Math.round((parseInt(salereopen5)/ parseInt(sale5))*100) )?'':Math.round((parseInt(salereopen5)/ parseInt(sale5))*100);
				var salereopen6Value = isNaN(Math.round((parseInt(salereopen6)/ parseInt(sale6))*100) )?'':Math.round((parseInt(salereopen6)/ parseInt(sale6))*100);
				var salereopen7Value = isNaN(Math.round((parseInt(salereopen7)/ parseInt(sale7))*100) )?'':Math.round((parseInt(salereopen7)/ parseInt(sale7))*100);
				var salereopen8Value = isNaN(Math.round((parseInt(salereopen8)/ parseInt(sale8))*100) )?'':Math.round((parseInt(salereopen8)/ parseInt(sale8))*100);
				var salereopen9Value = isNaN(Math.round((parseInt(salereopen9)/ parseInt(sale9))*100) )?'':Math.round((parseInt(salereopen9)/ parseInt(sale9))*100);
				var salereopen10Value = isNaN(Math.round((parseInt(salereopen10)/ parseInt(sale10))*100))?'':Math.round((parseInt(salereopen10)/ parseInt(sale10))*100);
				var salereopen11Value = isNaN(Math.round((parseInt(salereopen11)/ parseInt(sale11))*100))?'':Math.round((parseInt(salereopen11)/ parseInt(sale11))*100);
				var salereopen12Value = isNaN(Math.round((parseInt(salereopen12)/ parseInt(sale12))*100))?'':Math.round((parseInt(salereopen12)/ parseInt(sale12))*100);
				var salereopen13Value =isNaN( Math.round((parseInt(salereopen13)/ parseInt(sale13))*100))?'':Math.round((parseInt(salereopen13)/ parseInt(sale13))*100);
				var salereopen14Value = isNaN(Math.round((parseInt(salereopen14)/ parseInt(sale14))*100))?'':Math.round((parseInt(salereopen14)/ parseInt(sale14))*100);
				var salereopen15Value = isNaN(Math.round((parseInt(salereopen15)/ parseInt(sale15))*100))?'':Math.round((parseInt(salereopen15)/ parseInt(sale15))*100);

				salereopen4Value = salereopen4Value!=''?salereopen4Value:'0';
				salereopen5Value = salereopen5Value!=''?salereopen5Value:'0';
				salereopen6Value = salereopen6Value!=''?salereopen6Value:'0';
				salereopen7Value = salereopen7Value!=''?salereopen7Value:'0';
				salereopen8Value = salereopen8Value!=''?salereopen8Value:'0';
				salereopen9Value = salereopen9Value!=''?salereopen9Value:'0';
				salereopen10Value = salereopen10Value!=''?salereopen10Value:'0';
				salereopen11Value = salereopen11Value!=''?salereopen11Value:'0';
				salereopen12Value = salereopen12Value!=''?salereopen12Value:'0';
				salereopen13Value = salereopen13Value!=''?salereopen13Value:'0';
				salereopen14Value = salereopen14Value!=''?salereopen14Value:'0';
				salereopen15Value = salereopen15Value!=''?salereopen15Value:'0';

				$("#order-listing1 #salereopen_4").text(salereopen4Value+'%');
				$("#order-listing1 #salereopen_5").text(salereopen5Value+'%');
				$("#order-listing1 #salereopen_6").text(salereopen6Value+'%');
				$("#order-listing1 #salereopen_7").text(salereopen7Value+'%');
				$("#order-listing1 #salereopen_8").text(salereopen8Value+'%');
				$("#order-listing1 #salereopen_9").text(salereopen9Value+'%');
				$("#order-listing1 #salereopen_10").text(salereopen10Value+'%');
				$("#order-listing1 #salereopen_11").text(salereopen11Value+'%');
				$("#order-listing1 #salereopen_12").text(salereopen12Value+'%');
				$("#order-listing1 #salereopen_13").text(salereopen13Value+'%');
				$("#order-listing1 #salereopen_14").text(salereopen14Value+'%');
				$("#order-listing1 #salereopen_15").text(salereopen15Value+'%');
				
				var salereopen_avg_q1=(parseInt(salereopen4)+parseInt(salereopen5)+parseInt(salereopen6))/(saleq1);
				var salereopen_avg_q2=(parseInt(salereopen7)+parseInt(salereopen8)+parseInt(salereopen9))/(saleq2);
				var salereopen_avg_q3=(parseInt(salereopen10)+parseInt(salereopen11)+parseInt(salereopen12))/(saleq3);
				var salereopen_avg_q4=(parseInt(salereopen13)+parseInt(salereopen14)+parseInt(salereopen15))/(saleq4);
				var salereopen_avg_ytd =(parseInt(salereopen4)+parseInt(salereopen5)+parseInt(salereopen6) + parseInt(salereopen7)+parseInt(salereopen8)+parseInt(salereopen9) + parseInt(salereopen10)+parseInt(salereopen11)+parseInt(salereopen12) + parseInt(salereopen13)+parseInt(salereopen14)+parseInt(salereopen15))/saleytd;
				
				var salereopen_q1 =isNaN( Math.round(salereopen_avg_q1*100))?'0':Math.round(salereopen_avg_q1*100);
				$("#order-listing1 #salereopen_q1").text(salereopen_q1+'%');
				var salereopen_q2 = isNaN( Math.round(salereopen_avg_q2*100))?'0':Math.round(salereopen_avg_q2*100);
				$("#order-listing1 #salereopen_q2").text(salereopen_q2+'%');
				var salereopen_q3 =isNaN( Math.round(salereopen_avg_q3*100))?'0':Math.round(salereopen_avg_q3*100);
				$("#order-listing1 #salereopen_q3").text(salereopen_q3+'%');
				var salereopen_q4 = isNaN( Math.round(salereopen_avg_q4*100))?'0':Math.round(salereopen_avg_q4*100);
				$("#order-listing1 #salereopen_q4").text(salereopen_q4+'%');
				var salereopen_ytd = isNaN( Math.round(salereopen_avg_ytd*100))?'0':Math.round(salereopen_avg_ytd*100);
				$("#order-listing1 #salereopen_ytd").text(salereopen_ytd+'%');

				/*----------------------------------------**************************************************-----------------------------*/

				var salefeedback4 = $("#order-listing1 #salefeedback_4").text()!=''?$("#order-listing1 #salefeedback_4").text():'0';
				var salefeedback5 = $("#order-listing1 #salefeedback_5").text()!=''?$("#order-listing1 #salefeedback_5").text():'0';
				var salefeedback6 = $("#order-listing1 #salefeedback_6").text()!=''?$("#order-listing1 #salefeedback_6").text():'0';
				var salefeedback7 = $("#order-listing1 #salefeedback_7").text()!=''?$("#order-listing1 #salefeedback_7").text():'0';
				var salefeedback8 = $("#order-listing1 #salefeedback_8").text()!=''?$("#order-listing1 #salefeedback_8").text():'0';
				var salefeedback9 = $("#order-listing1 #salefeedback_9").text()!=''?$("#order-listing1 #salefeedback_9").text():'0';
				var salefeedback10 = $("#order-listing1 #salefeedback_10").text()!=''?$("#order-listing1 #salefeedback_10").text():'0';
				var salefeedback11 = $("#order-listing1 #salefeedback_11").text()!=''?$("#order-listing1 #salefeedback_11").text():'0';
				var salefeedback12 = $("#order-listing1 #salefeedback_12").text()!=''?$("#order-listing1 #salefeedback_12").text():'0';
				var salefeedback13 = $("#order-listing1 #salefeedback_13").text()!=''?$("#order-listing1 #salefeedback_13").text():'0';
				var salefeedback14 = $("#order-listing1 #salefeedback_14").text()!=''?$("#order-listing1 #salefeedback_14").text():'0';
				var salefeedback15 = $("#order-listing1 #salefeedback_15").text()!=''?$("#order-listing1 #salefeedback_15").text():'0';

				var salefeedbackq1 = parseInt(salefeedback4)+parseInt(salefeedback5)+parseInt(salefeedback6);
				$("#order-listing1 #salefeedback_q1").text(salefeedbackq1);
				var salefeedbackq2 = parseInt(salefeedback7)+parseInt(salefeedback8)+parseInt(salefeedback9);

				$("#order-listing1 #salefeedback_q2").text(salefeedbackq2);
				var salefeedbackq3 = parseInt(salefeedback10)+parseInt(salefeedback11)+parseInt(salefeedback12);
				$("#order-listing1 #salefeedback_q3").text(salefeedbackq3);
				var salefeedbackq4 = parseInt(salefeedback13)+parseInt(salefeedback14)+parseInt(salefeedback15);
				$("#order-listing1 #salefeedback_q4").text(salefeedbackq4);
				var salefeedbackytd = (salefeedbackq1)+(salefeedbackq2)+(salefeedbackq3)+(salefeedbackq4);
				$("#order-listing1 #salefeedback_ytd").text(salefeedbackytd);

				/*----------------------------------------**************************************************-----------------------------*/

				/*var salepcs4 = $("#order-listing1 #salepcs_4").text()!=''?$("#order-listing1 #salepcs_4").text():'0';
				var salepcs5 = $("#order-listing1 #salepcs_5").text()!=''?$("#order-listing1 #salepcs_5").text():'0';
				var salepcs6 = $("#order-listing1 #salepcs_6").text()!=''?$("#order-listing1 #salepcs_6").text():'0';
				var salepcs7 = $("#order-listing1 #salepcs_7").text()!=''?$("#order-listing1 #salepcs_7").text():'0';
				var salepcs8 = $("#order-listing1 #salepcs_8").text()!=''?$("#order-listing1 #salepcs_8").text():'0';
				var salepcs9 = $("#order-listing1 #salepcs_9").text()!=''?$("#order-listing1 #salepcs_9").text():'0';
				var salepcs10 = $("#order-listing1 #salepcs_10").text()!=''?$("#order-listing1 #salepcs_10").text():'0';
				var salepcs11 = $("#order-listing1 #salepcs_11").text()!=''?$("#order-listing1 #salepcs_11").text():'0';
				var salepcs12 = $("#order-listing1 #salepcs_12").text()!=''?$("#order-listing1 #salepcs_12").text():'0';
				var salepcs13 = $("#order-listing1 #salepcs_13").text()!=''?$("#order-listing1 #salepcs_13").text():'0';
				var salepcs14 = $("#order-listing1 #salepcs_14").text()!=''?$("#order-listing1 #salepcs_14").text():'0';
				var salepcs15 = $("#order-listing1 #salepcs_15").text()!=''?$("#order-listing1 #salepcs_15").text():'0';*/

				var salepcs4 = $('#salepcs_4').text();
				var salepcs5 = $('#salepcs_5').text();
				var salepcs6 = $('#salepcs_6').text();
				var salepcs7 = $('#salepcs_7').text();
				var salepcs8 = $('#salepcs_8').text();
				var salepcs9 = $('#salepcs_9').text();
				var salepcs10 = $('#salepcs_10').text();
				var salepcs11 = $('#salepcs_11').text();
				var salepcs12 = $('#salepcs_12').text();
				var salepcs13 = $('#salepcs_13').text();
				var salepcs14 = $('#salepcs_14').text();
				var salepcs15 = $('#salepcs_15').text();

				salepcs4 =salepcs4.split("~~");
				salepcs5 =salepcs5.split("~~");
				salepcs6 =salepcs6.split("~~");
				salepcs7 =salepcs7.split("~~");
				salepcs8 =salepcs8.split("~~");
				salepcs9 =salepcs9.split("~~");
				salepcs10 =salepcs10.split("~~");
				salepcs11 =salepcs11.split("~~");
				salepcs12 =salepcs12.split("~~");
				salepcs13 =salepcs13.split("~~");
				salepcs14 =salepcs14.split("~~");
				salepcs15 =salepcs15.split("~~");

				var q1Feedback = parseInt(salepcs4[0])+parseInt(salepcs5[0])+parseInt(salepcs6[0]);
				var q1TotalFeedback = parseInt(salepcs4[1])+parseInt(salepcs5[1])+parseInt(salepcs6[1]);
				q1TotalFeedback=q1TotalFeedback!=0?q1TotalFeedback:1;
				var q1Avg = isNaN(Math.round((q1Feedback/q1TotalFeedback)*100))?'0':Math.round((q1Feedback/q1TotalFeedback)*100);
				$("#order-listing1 #salepcs_q1").text(q1Avg+'%');
				
				var q2Feedback = parseInt(salepcs7[0])+parseInt(salepcs8[0])+parseInt(salepcs9[0]);
				var q2TotalFeedback = parseInt(salepcs7[1])+parseInt(salepcs8[1])+parseInt(salepcs9[1]);
				q2TotalFeedback=q2TotalFeedback!=0?q2TotalFeedback:1;
				var q2Avg = isNaN(Math.round((q2Feedback/q2TotalFeedback)*100))?'0':Math.round((q2Feedback/q2TotalFeedback)*100);
				$("#order-listing1 #salepcs_q2").text(q2Avg+'%');
				
				var q3Feedback = parseInt(salepcs10[0])+parseInt(salepcs11[0])+parseInt(salepcs12[0]);
				var q3TotalFeedback = parseInt(salepcs10[1])+parseInt(salepcs11[1])+parseInt(salepcs12[1]);
				q3TotalFeedback=q3TotalFeedback!=0?q3TotalFeedback:1;
				var q3Avg = isNaN(Math.round((q3Feedback/q3TotalFeedback)*100))?'0':Math.round((q3Feedback/q3TotalFeedback)*100);
				$("#order-listing1 #salepcs_q3").text(q3Avg+'%');
				
				var q4Feedback = parseInt(salepcs13[0])+parseInt(salepcs14[0])+parseInt(salepcs15[0]);
				var q4TotalFeedback = parseInt(salepcs13[1])+parseInt(salepcs14[1])+parseInt(salepcs15[1]);
				q4TotalFeedback=q4TotalFeedback!=0?q4TotalFeedback:1;
				var q4Avg = isNaN(Math.round((q4Feedback/q4TotalFeedback)*100))?'0':Math.round((q4Feedback/q4TotalFeedback)*100);
				$("#order-listing1 #salepcs_q4").text(q4Avg+'%');

				var ytdFeedback = parseInt(salepcs4[0])+parseInt(salepcs5[0])+parseInt(salepcs6[0])+parseInt(salepcs7[0])+parseInt(salepcs8[0])+parseInt(salepcs9[0])	+parseInt(salepcs10[0])+parseInt(salepcs11[0])+parseInt(salepcs12[0])+parseInt(salepcs13[0])+parseInt(salepcs14[0])+parseInt(salepcs15[0]);
				var ytdTotalFeedback = (parseInt(salepcs4[1])+parseInt(salepcs5[1])+parseInt(salepcs6[1]) )+(parseInt(salepcs7[1])+parseInt(salepcs8[1])+parseInt(salepcs9[1]) )+(parseInt(salepcs10[1])+parseInt(salepcs11[1])+parseInt(salepcs12[1]) )+(parseInt(salepcs13[1])+parseInt(salepcs14[1])+parseInt(salepcs15[1]) );
				ytdTotalFeedback=ytdTotalFeedback!=0?ytdTotalFeedback:1;
				var ytdAvg = isNaN(Math.round((ytdFeedback/ytdTotalFeedback)*100))?'0':Math.round((ytdFeedback/ytdTotalFeedback)*100);
				$("#order-listing1 #salepcs_ytd").text(ytdAvg+'%');
				/* ****************************************Sales*******************************************************/

			} );


		</script>
		<script>
			/*$('#order-listing1 thead th[colspan]').wrapInner( '<span/>' ).append( '&nbsp;' );*/
			$(document).ready(function() {
				  var table = $('#order-listing1').DataTable({
					"pageLength": 10,
					dom: 'Bfrtip',
					"language": {
						"paginate": {
							"previous": "<",
							"next": ">"
						}
					},
					buttons: [{
						extend: 'excel',
						text: 'Excel',
						className: 'exportExcel',
						filename: 'KPI_Trend_Report',
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9,10,11,12,13,14,15,16,17,18,22]
						}
					},
					{
						
						extend: 'pdfHtml5',
						title: 'KPI_Trend_Report',
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9,10,11,12,13,14,15,16,17,18,22]
						},
						customize: function(doc) {

							doc.styles.tableBodyEven = {
								alignment: 'center',
								
							}
							doc.styles.tableBodyOdd = {
								alignment: 'center',
								
							}
							
							age = table.column(6).data().toArray();
							/* var objLayout = {};
							objLayout['hLineWidth'] = function(i) { return .5; };							
							objLayout['hLineColor'] = function(i) { return '#000'; };				
							doc.content[1].layout =objLayout; */
							/* doc.content[1].table.body[0][0].alignment = 'left';
							doc.content[1].table.body[1][0].alignment = 'left'; */
							
							if(age.length == 35){
								for(var i=1;i<8;i++){
									doc.content[1].table.body[i][0].fillColor  ='#d8d7d5';
									doc.content[1].table.body[i][1].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][2].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][3].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][4].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][5].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][6].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][7].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][8].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][9].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][10].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][11].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][12].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][13].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][14].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][15].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][16].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][17].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][18].fillColor = '#d8d7d5';
								}
							
							/* doc.content[1].table.body[14][0].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][1].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][2].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][3].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][4].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][5].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][6].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][7].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][8].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][9].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][10].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][11].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][12].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][13].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][14].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][15].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][16].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][17].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][18].fillColor = '#d8d7d5'; */
							for(var i=15;i<22;i++){
							doc.content[1].table.body[i][0].fillColor = '#d8d7d5';
							doc.content[1].table.body[i][1].fillColor = '#d8d7d5';
							doc.content[1].table.body[i][2].fillColor = '#d8d7d5';
							doc.content[1].table.body[i][3].fillColor = '#d8d7d5';
							doc.content[1].table.body[i][4].fillColor = '#d8d7d5';
							doc.content[1].table.body[i][5].fillColor = '#d8d7d5';
							doc.content[1].table.body[i][6].fillColor = '#d8d7d5';
							doc.content[1].table.body[i][7].fillColor = '#d8d7d5';
							doc.content[1].table.body[i][8].fillColor = '#d8d7d5';
							doc.content[1].table.body[i][9].fillColor = '#d8d7d5';
							doc.content[1].table.body[i][10].fillColor = '#d8d7d5';
							doc.content[1].table.body[i][11].fillColor = '#d8d7d5';
							doc.content[1].table.body[i][12].fillColor = '#d8d7d5';
							doc.content[1].table.body[i][13].fillColor = '#d8d7d5';
							doc.content[1].table.body[i][14].fillColor = '#d8d7d5';
							doc.content[1].table.body[i][15].fillColor = '#d8d7d5';
							doc.content[1].table.body[i][16].fillColor = '#d8d7d5';
							doc.content[1].table.body[i][17].fillColor = '#d8d7d5';
							doc.content[1].table.body[i][18].fillColor = '#d8d7d5';
							}
							/* doc.content[1].table.body[28][0].fillColor = '#d8d7d5';
							doc.content[1].table.body[28][1].fillColor = '#d8d7d5';
							doc.content[1].table.body[28][2].fillColor = '#d8d7d5';
							doc.content[1].table.body[28][3].fillColor = '#d8d7d5';
							doc.content[1].table.body[28][4].fillColor = '#d8d7d5';
							doc.content[1].table.body[28][5].fillColor = '#d8d7d5';
							doc.content[1].table.body[28][6].fillColor = '#d8d7d5';
							doc.content[1].table.body[28][7].fillColor = '#d8d7d5';
							doc.content[1].table.body[28][8].fillColor = '#d8d7d5';
							doc.content[1].table.body[28][9].fillColor = '#d8d7d5';
							doc.content[1].table.body[28][10].fillColor = '#d8d7d5';
							doc.content[1].table.body[28][11].fillColor = '#d8d7d5';
							doc.content[1].table.body[28][12].fillColor = '#d8d7d5';
							doc.content[1].table.body[28][13].fillColor = '#d8d7d5';
							doc.content[1].table.body[28][14].fillColor = '#d8d7d5';
							doc.content[1].table.body[28][15].fillColor = '#d8d7d5';
							doc.content[1].table.body[28][16].fillColor = '#d8d7d5';
							doc.content[1].table.body[28][17].fillColor = '#d8d7d5';
							doc.content[1].table.body[28][18].fillColor = '#d8d7d5'; */
							for(var i=29;i<36;i++){
							doc.content[1].table.body[i][0].fillColor = '#d8d7d5';
							doc.content[1].table.body[i][1].fillColor = '#d8d7d5';
							doc.content[1].table.body[i][2].fillColor = '#d8d7d5';
							doc.content[1].table.body[i][3].fillColor = '#d8d7d5';
							doc.content[1].table.body[i][4].fillColor = '#d8d7d5';
							doc.content[1].table.body[i][5].fillColor = '#d8d7d5';
							doc.content[1].table.body[i][6].fillColor = '#d8d7d5';
							doc.content[1].table.body[i][7].fillColor = '#d8d7d5';
							doc.content[1].table.body[i][8].fillColor = '#d8d7d5';
							doc.content[1].table.body[i][9].fillColor = '#d8d7d5';
							doc.content[1].table.body[i][10].fillColor = '#d8d7d5';
							doc.content[1].table.body[i][11].fillColor = '#d8d7d5';
							doc.content[1].table.body[i][12].fillColor = '#d8d7d5';
							doc.content[1].table.body[i][13].fillColor = '#d8d7d5';
							doc.content[1].table.body[i][14].fillColor = '#d8d7d5';
							doc.content[1].table.body[i][15].fillColor = '#d8d7d5';
							doc.content[1].table.body[i][16].fillColor = '#d8d7d5';
							doc.content[1].table.body[i][17].fillColor = '#d8d7d5';
							doc.content[1].table.body[i][18].fillColor = '#d8d7d5';
							}
							}
							
							if(age.length == 14){
							/* doc.content[1].table.body[7][0].fillColor = '#919296';
							doc.content[1].table.body[7][1].fillColor = '#919296';
							doc.content[1].table.body[7][2].fillColor = '#919296';
							doc.content[1].table.body[7][3].fillColor = '#919296';
							doc.content[1].table.body[7][4].fillColor = '#919296';
							doc.content[1].table.body[7][5].fillColor = '#919296';
							doc.content[1].table.body[7][6].fillColor = '#919296';
							doc.content[1].table.body[7][7].fillColor = '#919296';
							doc.content[1].table.body[7][8].fillColor = '#919296';
							doc.content[1].table.body[7][9].fillColor = '#919296';
							doc.content[1].table.body[7][10].fillColor = '#919296';
							doc.content[1].table.body[7][11].fillColor = '#919296';
							doc.content[1].table.body[7][12].fillColor = '#919296';
							doc.content[1].table.body[7][13].fillColor = '#919296';
							doc.content[1].table.body[7][14].fillColor = '#919296';
							doc.content[1].table.body[7][15].fillColor = '#919296';
							doc.content[1].table.body[7][16].fillColor = '#919296';
							doc.content[1].table.body[7][17].fillColor = '#919296';
							doc.content[1].table.body[7][18].fillColor = '#919296'; */
							for(var i=1;i<8;i++){
									doc.content[1].table.body[i][0].fillColor  ='#d8d7d5';
									doc.content[1].table.body[i][1].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][2].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][3].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][4].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][5].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][6].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][7].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][8].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][9].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][10].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][11].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][12].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][13].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][14].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][15].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][16].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][17].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][18].fillColor = '#d8d7d5';
								}
							/* doc.content[1].table.body[14][0].fillColor = '#919296';
							doc.content[1].table.body[14][1].fillColor = '#919296';
							doc.content[1].table.body[14][2].fillColor = '#919296';
							doc.content[1].table.body[14][3].fillColor = '#919296';
							doc.content[1].table.body[14][4].fillColor = '#919296';
							doc.content[1].table.body[14][5].fillColor = '#919296';
							doc.content[1].table.body[14][6].fillColor = '#919296';
							doc.content[1].table.body[14][7].fillColor = '#919296';
							doc.content[1].table.body[14][8].fillColor = '#919296';
							doc.content[1].table.body[14][9].fillColor = '#919296';
							doc.content[1].table.body[14][10].fillColor = '#919296';
							doc.content[1].table.body[14][11].fillColor = '#919296';
							doc.content[1].table.body[14][12].fillColor = '#919296';
							doc.content[1].table.body[14][13].fillColor = '#919296';
							doc.content[1].table.body[14][14].fillColor = '#919296';
							doc.content[1].table.body[14][15].fillColor = '#919296';
							doc.content[1].table.body[14][16].fillColor = '#919296';
							doc.content[1].table.body[14][17].fillColor = '#919296';
							doc.content[1].table.body[14][18].fillColor = '#919296'; */
							}
							
							if(age.length == 21){
							/* doc.content[1].table.body[7][0].fillColor = '#d8d7d5';
							doc.content[1].table.body[7][1].fillColor = '#d8d7d5';
							doc.content[1].table.body[7][2].fillColor = '#d8d7d5';
							doc.content[1].table.body[7][3].fillColor = '#d8d7d5';
							doc.content[1].table.body[7][4].fillColor = '#d8d7d5';
							doc.content[1].table.body[7][5].fillColor = '#d8d7d5';
							doc.content[1].table.body[7][6].fillColor = '#d8d7d5';
							doc.content[1].table.body[7][7].fillColor = '#d8d7d5';
							doc.content[1].table.body[7][8].fillColor = '#d8d7d5';
							doc.content[1].table.body[7][9].fillColor = '#d8d7d5';
							doc.content[1].table.body[7][10].fillColor = '#d8d7d5';
							doc.content[1].table.body[7][11].fillColor = '#d8d7d5';
							doc.content[1].table.body[7][12].fillColor = '#d8d7d5';
							doc.content[1].table.body[7][13].fillColor = '#d8d7d5';
							doc.content[1].table.body[7][14].fillColor = '#d8d7d5';
							doc.content[1].table.body[7][15].fillColor = '#d8d7d5';
							doc.content[1].table.body[7][16].fillColor = '#d8d7d5';
							doc.content[1].table.body[7][17].fillColor = '#d8d7d5';
							doc.content[1].table.body[7][18].fillColor = '#d8d7d5'; */
							for(var i=1;i<8;i++){
									doc.content[1].table.body[i][0].fillColor  ='#d8d7d5';
									doc.content[1].table.body[i][1].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][2].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][3].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][4].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][5].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][6].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][7].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][8].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][9].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][10].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][11].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][12].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][13].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][14].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][15].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][16].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][17].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][18].fillColor = '#d8d7d5';
								}
							/* doc.content[1].table.body[14][0].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][1].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][2].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][3].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][4].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][5].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][6].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][7].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][8].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][9].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][10].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][11].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][12].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][13].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][14].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][15].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][16].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][17].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][18].fillColor = '#d8d7d5'; */
							
							/* doc.content[1].table.body[21][0].fillColor = '#d8d7d5';
							doc.content[1].table.body[21][1].fillColor = '#d8d7d5';
							doc.content[1].table.body[21][2].fillColor = '#d8d7d5';
							doc.content[1].table.body[21][3].fillColor = '#d8d7d5';
							doc.content[1].table.body[21][4].fillColor = '#d8d7d5';
							doc.content[1].table.body[21][5].fillColor = '#d8d7d5';
							doc.content[1].table.body[21][6].fillColor = '#d8d7d5';
							doc.content[1].table.body[21][7].fillColor = '#d8d7d5';
							doc.content[1].table.body[21][8].fillColor = '#d8d7d5';
							doc.content[1].table.body[21][9].fillColor = '#d8d7d5';
							doc.content[1].table.body[21][10].fillColor = '#d8d7d5';
							doc.content[1].table.body[21][11].fillColor = '#d8d7d5';
							doc.content[1].table.body[21][12].fillColor = '#d8d7d5';
							doc.content[1].table.body[21][13].fillColor = '#d8d7d5';
							doc.content[1].table.body[21][14].fillColor = '#d8d7d5';
							doc.content[1].table.body[21][15].fillColor = '#d8d7d5';
							doc.content[1].table.body[21][16].fillColor = '#d8d7d5';
							doc.content[1].table.body[21][17].fillColor = '#d8d7d5';
							doc.content[1].table.body[21][18].fillColor = '#d8d7d5';
							*/	
								for(var i=15;i<22;i++){
									doc.content[1].table.body[i][0].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][1].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][2].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][3].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][4].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][5].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][6].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][7].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][8].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][9].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][10].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][11].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][12].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][13].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][14].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][15].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][16].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][17].fillColor = '#d8d7d5';
									doc.content[1].table.body[i][18].fillColor = '#d8d7d5';
								}							
							}
							
							if(age.length == 28){
							/* doc.content[1].table.body[7][0].fillColor = '#919296';
							doc.content[1].table.body[7][1].fillColor = '#919296';
							doc.content[1].table.body[7][2].fillColor = '#919296';
							doc.content[1].table.body[7][3].fillColor = '#919296';
							doc.content[1].table.body[7][4].fillColor = '#919296';
							doc.content[1].table.body[7][5].fillColor = '#919296';
							doc.content[1].table.body[7][6].fillColor = '#919296';
							doc.content[1].table.body[7][7].fillColor = '#919296';
							doc.content[1].table.body[7][8].fillColor = '#919296';
							doc.content[1].table.body[7][9].fillColor = '#919296';
							doc.content[1].table.body[7][10].fillColor = '#919296';
							doc.content[1].table.body[7][11].fillColor = '#919296';
							doc.content[1].table.body[7][12].fillColor = '#919296';
							doc.content[1].table.body[7][13].fillColor = '#919296';
							doc.content[1].table.body[7][14].fillColor = '#919296';
							doc.content[1].table.body[7][15].fillColor = '#919296';
							doc.content[1].table.body[7][16].fillColor = '#919296';
							doc.content[1].table.body[7][17].fillColor = '#919296';
							doc.content[1].table.body[7][18].fillColor = '#919296'; */
							
							for(var i=1;i<8;i++){
								doc.content[1].table.body[i][0].fillColor  ='#d8d7d5';
								doc.content[1].table.body[i][1].fillColor = '#d8d7d5';
								doc.content[1].table.body[i][2].fillColor = '#d8d7d5';
								doc.content[1].table.body[i][3].fillColor = '#d8d7d5';
								doc.content[1].table.body[i][4].fillColor = '#d8d7d5';
								doc.content[1].table.body[i][5].fillColor = '#d8d7d5';
								doc.content[1].table.body[i][6].fillColor = '#d8d7d5';
								doc.content[1].table.body[i][7].fillColor = '#d8d7d5';
								doc.content[1].table.body[i][8].fillColor = '#d8d7d5';
								doc.content[1].table.body[i][9].fillColor = '#d8d7d5';
								doc.content[1].table.body[i][10].fillColor = '#d8d7d5';
								doc.content[1].table.body[i][11].fillColor = '#d8d7d5';
								doc.content[1].table.body[i][12].fillColor = '#d8d7d5';
								doc.content[1].table.body[i][13].fillColor = '#d8d7d5';
								doc.content[1].table.body[i][14].fillColor = '#d8d7d5';
								doc.content[1].table.body[i][15].fillColor = '#d8d7d5';
								doc.content[1].table.body[i][16].fillColor = '#d8d7d5';
								doc.content[1].table.body[i][17].fillColor = '#d8d7d5';
								doc.content[1].table.body[i][18].fillColor = '#d8d7d5';
							}
							/* doc.content[1].table.body[14][0].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][1].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][2].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][3].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][4].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][5].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][6].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][7].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][8].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][9].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][10].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][11].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][12].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][13].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][14].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][15].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][16].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][17].fillColor = '#d8d7d5';
							doc.content[1].table.body[14][18].fillColor = '#d8d7d5'; */
							
							/* doc.content[1].table.body[21][0].fillColor = '#d8d7d5';
							doc.content[1].table.body[21][1].fillColor = '#d8d7d5';
							doc.content[1].table.body[21][2].fillColor = '#d8d7d5';
							doc.content[1].table.body[21][3].fillColor = '#d8d7d5';
							doc.content[1].table.body[21][4].fillColor = '#d8d7d5';
							doc.content[1].table.body[21][5].fillColor = '#d8d7d5';
							doc.content[1].table.body[21][6].fillColor = '#d8d7d5';
							doc.content[1].table.body[21][7].fillColor = '#d8d7d5';
							doc.content[1].table.body[21][8].fillColor = '#d8d7d5';
							doc.content[1].table.body[21][9].fillColor = '#d8d7d5';
							doc.content[1].table.body[21][10].fillColor = '#d8d7d5';
							doc.content[1].table.body[21][11].fillColor = '#d8d7d5';
							doc.content[1].table.body[21][12].fillColor = '#d8d7d5';
							doc.content[1].table.body[21][13].fillColor = '#d8d7d5';
							doc.content[1].table.body[21][14].fillColor = '#d8d7d5';
							doc.content[1].table.body[21][15].fillColor = '#d8d7d5';
							doc.content[1].table.body[21][16].fillColor = '#d8d7d5';
							doc.content[1].table.body[21][17].fillColor = '#d8d7d5';
							doc.content[1].table.body[21][18].fillColor = '#d8d7d5'; */
							for(var i=15;i<22;i++){
								doc.content[1].table.body[i][0].fillColor = '#d8d7d5';
								doc.content[1].table.body[i][1].fillColor = '#d8d7d5';
								doc.content[1].table.body[i][2].fillColor = '#d8d7d5';
								doc.content[1].table.body[i][3].fillColor = '#d8d7d5';
								doc.content[1].table.body[i][4].fillColor = '#d8d7d5';
								doc.content[1].table.body[i][5].fillColor = '#d8d7d5';
								doc.content[1].table.body[i][6].fillColor = '#d8d7d5';
								doc.content[1].table.body[i][7].fillColor = '#d8d7d5';
								doc.content[1].table.body[i][8].fillColor = '#d8d7d5';
								doc.content[1].table.body[i][9].fillColor = '#d8d7d5';
								doc.content[1].table.body[i][10].fillColor = '#d8d7d5';
								doc.content[1].table.body[i][11].fillColor = '#d8d7d5';
								doc.content[1].table.body[i][12].fillColor = '#d8d7d5';
								doc.content[1].table.body[i][13].fillColor = '#d8d7d5';
								doc.content[1].table.body[i][14].fillColor = '#d8d7d5';
								doc.content[1].table.body[i][15].fillColor = '#d8d7d5';
								doc.content[1].table.body[i][16].fillColor = '#d8d7d5';
								doc.content[1].table.body[i][17].fillColor = '#d8d7d5';
								doc.content[1].table.body[i][18].fillColor = '#d8d7d5';
							}
							
							/* doc.content[1].table.body[28][0].fillColor = '#d8d7d5';
							doc.content[1].table.body[28][1].fillColor = '#d8d7d5';
							doc.content[1].table.body[28][2].fillColor = '#d8d7d5';
							doc.content[1].table.body[28][3].fillColor = '#d8d7d5';
							doc.content[1].table.body[28][4].fillColor = '#d8d7d5';
							doc.content[1].table.body[28][5].fillColor = '#d8d7d5';
							doc.content[1].table.body[28][6].fillColor = '#d8d7d5';
							doc.content[1].table.body[28][7].fillColor = '#d8d7d5';
							doc.content[1].table.body[28][8].fillColor = '#d8d7d5';
							doc.content[1].table.body[28][9].fillColor = '#d8d7d5';
							doc.content[1].table.body[28][10].fillColor = '#d8d7d5';
							doc.content[1].table.body[28][11].fillColor = '#d8d7d5';
							doc.content[1].table.body[28][12].fillColor = '#d8d7d5';
							doc.content[1].table.body[28][13].fillColor = '#d8d7d5';
							doc.content[1].table.body[28][14].fillColor = '#d8d7d5';
							doc.content[1].table.body[28][15].fillColor = '#d8d7d5';
							doc.content[1].table.body[28][16].fillColor = '#d8d7d5';
							doc.content[1].table.body[28][17].fillColor = '#d8d7d5';
							doc.content[1].table.body[28][18].fillColor = '#d8d7d5'; */
							
							}
							
							for (var i = 0; i < age.length; i++) {										
								doc.content[1].table.body[i+1][6].fillColor = '#919296';	
								doc.content[1].table.body[i+1][10].fillColor = '#919296';	
								doc.content[1].table.body[i+1][14].fillColor = '#919296';	
								doc.content[1].table.body[i+1][18].fillColor = '#919296';	
								doc.content[1].table.body[i+1][19].fillColor = '#919296';
							}	
						},
						orientation : 'landscape',
						
					}
					]
				});
			});
		</script>
		
@endsection
