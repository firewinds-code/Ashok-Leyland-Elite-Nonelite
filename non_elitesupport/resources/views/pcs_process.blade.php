@extends("layouts.masterlayout")
@section('title','PCS Process Report')
@section('bodycontent')

	<div class="content-wrapper mobcss">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">PCS Process Report</h4>
				<div class="clear"></div>
				<hr>
				@php $current =  date("Y"); $next =date("y")+1;@endphp
				@if(Session::get('role') == '29' || Session::get('role') == '30' || Session::get('user_type_id') =='2')
					<form name="myForm" method="post" enctype="multipart/form-data" action="{{url('store-pcs-process')}}" onsubmit="return pcsValidate()">
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
										<option value="{{$i.'-'.$j}}" @isset($yearVal){{$i.'-'.$j ==$yearVal?'selected':''}} @endisset>
											{{$i.'-'.$j}}
										</option>
									@endfor
								</select>
								<span id="year_error" style="color:red"></span>
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

						</div>
						<div class="clear"></div>
						<hr>
						<div class="row">
							<div class="form-group col-md-3">
								<input type="submit"name="submit" id="submit" value="Submit" class="btn-secondary" >
							</div>
						</div>
					</form>

				@else
					@php
						$location1 = Session::get('city');
                        $product1 = Session::get('product');
                        $brand1 = Session::get('brand');
                        $complaint_type_id = Session::get('complaint_type_id');
                        $loc =explode(',',$location1);
                        $pro =explode(',',$product1);
                        $brnd =explode(',',$brand1);
                        $catId =explode(',',$complaint_type_id);
					@endphp
					<form name="myForm" method="post" enctype="multipart/form-data" action="{{url('store-pcs-process')}}" onsubmit="return pcsValidate1()">
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
								<select name="year" id="year1" class="form-control">
									<option value="NA">Select</option>
									@for($i=2017,$j=18;$i<=$current,$j<=$next;$i++,$j++)

										<option value="{{$i.'-'.$j}}" @isset($yearVal){{$i.'-'.$j ==$yearVal?'selected':''}} @endisset>{{$i.'-'.$j}}</option>
									@endfor

								</select>
								<span id="year_error1" style="color:red"></span>
							</div>

							<div class="form-group col-md-3">
								<label for="Zone" >Region</label>
								<span style="color: red;">*</span>
								<select name="zone[]" id="zone2" multiple class="form-control" onchange="getDealerByZoneId(this.value,'');">
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
								<span id="zone_error1" style="color:red"></span>
							</div>
							<div class="form-group col-md-3">
								<label for="Dealer" >Dealer</label>
								<span style="color: red;">*</span>
								<select name="Dealer[]" multiple id="Dealer1" class="form-control"></select>
								<span id="Dealer_error1" style="color:red"></span>
							</div>
						</div>
						<div class="clear"></div>
						<hr>
						<div class="row">
							<div class="form-group col-md-3">
								<input type="submit"name="submit" id="submit" value="Submit" class="btn-secondary" >
							</div>
						</div>
					</form>
				@endif
				<div class="clear"></div><br>
				<div class="row">
					<div class="col-lg-12">
						<div class="table-responsive">
							<table id="pcs-listing" class="table custom">
								<thead>
								<tr>
									<th></th>
									<th>Apr</th>
									<th>May</th>
									<th>Jun</th>
									<th>Jul</th>
									<th>Aug</th>
									<th>Sep</th>
									<th>Oct</th>
									<th>Nov</th>
									<th>Dec</th>
									<th>Jan</th>
									<th>Feb</th>
									<th>Mar</th>
									<th>YTD</th>
								</tr>
								</thead>
								<tbody>
								<tr>
									<td >No. of complaints <span style="display: none;">c</span>Logged</td>
									@isset($allCatLogged)
										@for($i=4,$j=0;$i<=15,$j<12;$i++,$j++)
											@php $mnth = !empty($allCatLogged[$j])?$allCatLogged[$j]->month:'0'; if($mnth ==1)$mnth=13;if($mnth ==2)$mnth=14;if($mnth ==3)$mnth=15; @endphp
											@php if($i==13){$i=1;}elseif($i==14){$i=2;}elseif($i==15){$i=3;}else{
									$i=$i;} @endphp
											<td id="mnth_{{$i}}" onclick="monthDetails( '{{$i}}','{{$yVal}}','{{1}}','{{$zoneVal}}','{{$dealerVal}}')" style="color: #7571f9;cursor: pointer;">{{$allCatLogged[$j]->cnt!=''?$allCatLogged[$j]->cnt:'0'}}</td>
											@if($j==11 )
												<td id="ytd"></td>
											@endif
										@endfor
									@endisset
								</tr>

								<tr>
									<td>No. of complaints completed</td>
									@isset($allClosed)
										@for($i=4,$j=0;$i<=15,$j<12;$i++,$j++)
											@php $mnth = !empty($allClosed[$j])?$allClosed[$j]->month:'0'; if($mnth ==1)$mnth=13;if($mnth ==2)$mnth=14;if($mnth ==3)$mnth=15; @endphp
											@php if($i==13){$i=1;}elseif($i==14){$i=2;}elseif($i==15){$i=3;}else{
									$i=$i;} @endphp
											<td id="closed_{{$i}}" onclick="monthDetails( '{{$i}}','{{$yVal}}','{{2}}','{{$zoneVal}}','{{$dealerVal}}' )" style="color: #7571f9;cursor: pointer;">{{$allClosed[$j]->cnt!=''?$allClosed[$j]->cnt:'0'}}</td>
											@if($j==11 )
												<td id="closed_ytd"></td>
											@endif
										@endfor
									@endisset
								</tr>
								<tr>
									<td>No. of complaints PCS not initiated</td>

									@isset($allNotInitiated)

										@for($i=4,$j=0;$i<=15,$j<12;$i++,$j++)
											@php $mnth = !empty($allNotInitiated[$j])?$allNotInitiated[$j]->month:'0'; if($mnth ==1)$mnth=13;if($mnth ==2)$mnth=14;if($mnth ==3)$mnth=15; @endphp
											@php if($i==13){$i=1;}elseif($i==14){$i=2;}elseif($i==15){$i=3;} @endphp
											<td id="notInitiated_{{$i}}" onclick="monthDetails( '{{$i}}','{{$yVal}}','{{3}}','{{$zoneVal}}','{{$dealerVal}}' )" style="color: #7571f9;cursor: pointer;">{{$allNotInitiated[$j]->pcs_status!=''?$allNotInitiated[$j]->pcs_status:'0'}}</td>
											@if($j==11 )
												<td id="notInitiated_ytd"></td>
											@endif
										@endfor
									@endisset
								</tr>
								<tr>
									<td>No. of PCS calls under followup</td>
									@isset($allFollowing)
										@for($i=4,$j=0;$i<=15,$j<12;$i++,$j++)
											@php $mnth = !empty($allFollowing[$j])?$allFollowing[$j]->month:'0'; if($mnth ==1)$mnth=13;if($mnth ==2)$mnth=14;if($mnth ==3)$mnth=15; @endphp
											@php if($i==13){$i=1;}elseif($i==14){$i=2;}elseif($i==15){$i=3;}else{
									$i=$i;} @endphp
											<td id="following_{{$i}}" onclick="monthDetails( '{{$i}}','{{$yVal}}','{{4}}','{{$zoneVal}}','{{$dealerVal}}' )" style="color: #7571f9;cursor: pointer;">{{$allFollowing[$j]->pcs_status!=''?$allFollowing[$j]->pcs_status:'0'}}</td>
											@if($j==11 )
												<td id="following_ytd"></td>
											@endif
										@endfor
									@endisset
								</tr>
								<tr>
									<td>No. of PCS calls completed</td>
									@isset($allCompleted)
										@for($i=4,$j=0;$i<=15,$j<12;$i++,$j++)
											@php $mnth = !empty($allCompleted[$j])?$allCompleted[$j]->month:'0'; if($mnth ==1)$mnth=13;if($mnth ==2)$mnth=14;if($mnth ==3)$mnth=15; @endphp
											@php if($i==13){$i=1;}elseif($i==14){$i=2;}elseif($i==15){$i=3;}else{
									$i=$i;} @endphp
											<td id="completed_{{$i}}" onclick="monthDetails( '{{$i}}','{{$yVal}}','{{5}}','{{$zoneVal}}','{{$dealerVal}}' )" style="color: #7571f9;cursor: pointer;">{{$allCompleted[$j]->pcs_status!=''?$allCompleted[$j]->pcs_status:'0'}}</td>
											@if($j==11 )
												<td id="completed_ytd"></td>
											@endif
										@endfor
									@endisset
								</tr>
								<tr>
									<td>No. of PCS calls dropped</td>
									@isset($alldrop)

										@for($i=4,$j=0;$i<=15,$j<12;$i++,$j++)
											@php $mnth = !empty($alldrop[$j])?$alldrop[$j]->month:'0'; if($mnth ==1)$mnth=13;if($mnth ==2)$mnth=14;if($mnth ==3)$mnth=15; @endphp
											@php if($i==13){$i=1;}elseif($i==14){$i=2;}elseif($i==15){$i=3;}else{
									$i=$i;} @endphp
											<td id="drop_{{$i}}" onclick="monthDetails( '{{$i}}','{{$yVal}}','{{6}}','{{$zoneVal}}','{{$dealerVal}}' )" style="color: #7571f9;cursor: pointer;">{{$alldrop[$j]->pcs_status!=''?$alldrop[$j]->pcs_status:'0'}}</td>
											@if($j==11 )
												<td id="drop_ytd"></td>
											@endif
										@endfor
									@endisset
								</tr>
								</tbody>
							</table>
						</div>
						<br>

						<div id="tabelData"></div>
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
	</style>
	<script type="text/javascript">

		$(document).ready(function() {

			$('#pcs-listing thead th[colspan]').wrapInner( '<span/>' ).append( '&nbsp;' );
			/*----------------------------------------**************************************************-----------------------------*/
			$('#mnth_16').hide();
			$('#mnth_17').hide();
			$('#mnth_18').hide();
			// Standard initialisation
			var mnth4 = $("#pcs-listing #mnth_4").text()!=''?$("#pcs-listing #mnth_4").text():'0';
			var mnth5 = $("#pcs-listing #mnth_5").text()!=''?$("#pcs-listing #mnth_5").text():'0';
			var mnth6 = $("#pcs-listing #mnth_6").text()!=''?$("#pcs-listing #mnth_6").text():'0';
			var mnth7 = $("#pcs-listing #mnth_7").text()!=''?$("#pcs-listing #mnth_7").text():'0';
			var mnth8 = $("#pcs-listing #mnth_8").text()!=''?$("#pcs-listing #mnth_8").text():'0';
			var mnth9 = $("#pcs-listing #mnth_9").text()!=''?$("#pcs-listing #mnth_9").text():'0';
			var mnth10 = $("#pcs-listing #mnth_10").text()!=''?$("#pcs-listing #mnth_10").text():'0';
			var mnth11 = $("#pcs-listing #mnth_11").text()!=''?$("#pcs-listing #mnth_11").text():'0';
			var mnth12 = $("#pcs-listing #mnth_12").text()!=''?$("#pcs-listing #mnth_12").text():'0';
			var mnth13 = $("#pcs-listing #mnth_1").text()!=''?$("#pcs-listing #mnth_1").text():'0';
			var mnth14 = $("#pcs-listing #mnth_2").text()!=''?$("#pcs-listing #mnth_2").text():'0';
			var mnth15 = $("#pcs-listing #mnth_3").text()!=''?$("#pcs-listing #mnth_3").text():'0';

			var ytd =parseInt(mnth4)+parseInt(mnth5)+parseInt(mnth6) + parseInt(mnth7)+parseInt(mnth8)+parseInt(mnth9) + parseInt(mnth10)+parseInt(mnth11)+parseInt(mnth12)+parseInt(mnth13)+parseInt(mnth14)+parseInt(mnth15);

			$("#pcs-listing #ytd").text(ytd);

			/*----------------------------------------**************************************************-----------------------------*/

			$('#closed_16').hide();
			$('#closed_17').hide();
			$('#closed_18').hide();
			// Standard initialisation
			var closed4 = $("#pcs-listing #closed_4").text()!=''?$("#pcs-listing #closed_4").text():'0';
			var closed5 = $("#pcs-listing #closed_5").text()!=''?$("#pcs-listing #closed_5").text():'0';
			var closed6 = $("#pcs-listing #closed_6").text()!=''?$("#pcs-listing #closed_6").text():'0';
			var closed7 = $("#pcs-listing #closed_7").text()!=''?$("#pcs-listing #closed_7").text():'0';
			var closed8 = $("#pcs-listing #closed_8").text()!=''?$("#pcs-listing #closed_8").text():'0';
			var closed9 = $("#pcs-listing #closed_9").text()!=''?$("#pcs-listing #closed_9").text():'0';
			var closed10 = $("#pcs-listing #closed_10").text()!=''?$("#pcs-listing #closed_10").text():'0';
			var closed11 = $("#pcs-listing #closed_11").text()!=''?$("#pcs-listing #closed_11").text():'0';
			var closed12 = $("#pcs-listing #closed_12").text()!=''?$("#pcs-listing #closed_12").text():'0';
			var closed13 = $("#pcs-listing #closed_1").text()!=''?$("#pcs-listing #closed_1").text():'0';
			var closed14 = $("#pcs-listing #closed_2").text()!=''?$("#pcs-listing #closed_2").text():'0';
			var closed15 = $("#pcs-listing #closed_3").text()!=''?$("#pcs-listing #closed_3").text():'0';

			var closed_ytd =parseInt(closed4)+parseInt(closed5)+parseInt(closed6) + parseInt(closed7)+parseInt(closed8)+parseInt(closed9) + parseInt(closed10)+parseInt(closed11)+parseInt(closed12)+parseInt(closed13)+parseInt(closed14)+parseInt(closed15);

			$("#pcs-listing #closed_ytd").text(closed_ytd);

			/*----------------------------------------**************************************************-----------------------------*/
			$('#notInitiated_16').hide();
			$('#notInitiated_17').hide();
			$('#notInitiated_18').hide();
			// Standard initialisation
			var notInitiated4 = $("#pcs-listing #notInitiated_4").text()!=''?$("#pcs-listing #notInitiated_4").text():'0';
			var notInitiated5 = $("#pcs-listing #notInitiated_5").text()!=''?$("#pcs-listing #notInitiated_5").text():'0';
			var notInitiated6 = $("#pcs-listing #notInitiated_6").text()!=''?$("#pcs-listing #notInitiated_6").text():'0';
			var notInitiated7 = $("#pcs-listing #notInitiated_7").text()!=''?$("#pcs-listing #notInitiated_7").text():'0';
			var notInitiated8 = $("#pcs-listing #notInitiated_8").text()!=''?$("#pcs-listing #notInitiated_8").text():'0';
			var notInitiated9 = $("#pcs-listing #notInitiated_9").text()!=''?$("#pcs-listing #notInitiated_9").text():'0';
			var notInitiated10 = $("#pcs-listing #notInitiated_10").text()!=''?$("#pcs-listing #notInitiated_10").text():'0';
			var notInitiated11 = $("#pcs-listing #notInitiated_11").text()!=''?$("#pcs-listing #notInitiated_11").text():'0';
			var notInitiated12 = $("#pcs-listing #notInitiated_12").text()!=''?$("#pcs-listing #notInitiated_12").text():'0';
			var notInitiated13 = $("#pcs-listing #notInitiated_1").text()!=''?$("#pcs-listing #notInitiated_1").text():'0';
			var notInitiated14 = $("#pcs-listing #notInitiated_2").text()!=''?$("#pcs-listing #notInitiated_2").text():'0';
			var notInitiated15 = $("#pcs-listing #notInitiated_3").text()!=''?$("#pcs-listing #notInitiated_3").text():'0';

			var notInitiatedytd =parseInt(notInitiated4)+parseInt(notInitiated5)+parseInt(notInitiated6) + parseInt(notInitiated7)+parseInt(notInitiated8)+parseInt(notInitiated9) + parseInt(notInitiated10)+parseInt(notInitiated11)+parseInt(notInitiated12)+parseInt(notInitiated13)+parseInt(notInitiated14)+parseInt(notInitiated15);

			$("#pcs-listing #notInitiated_ytd").text(notInitiatedytd);

			/*----------------------------------------**************************************************-----------------------------*/
			$('#following_16').hide();
			$('#following_17').hide();
			$('#following_18').hide();
			// Standard initialisation
			var following4 = $("#pcs-listing #following_4").text()!=''?$("#pcs-listing #following_4").text():'0';
			var following5 = $("#pcs-listing #following_5").text()!=''?$("#pcs-listing #following_5").text():'0';
			var following6 = $("#pcs-listing #following_6").text()!=''?$("#pcs-listing #following_6").text():'0';
			var following7 = $("#pcs-listing #following_7").text()!=''?$("#pcs-listing #following_7").text():'0';
			var following8 = $("#pcs-listing #following_8").text()!=''?$("#pcs-listing #following_8").text():'0';
			var following9 = $("#pcs-listing #following_9").text()!=''?$("#pcs-listing #following_9").text():'0';
			var following10 = $("#pcs-listing #following_10").text()!=''?$("#pcs-listing #following_10").text():'0';
			var following11 = $("#pcs-listing #following_11").text()!=''?$("#pcs-listing #following_11").text():'0';
			var following12 = $("#pcs-listing #following_12").text()!=''?$("#pcs-listing #following_12").text():'0';
			var following13 = $("#pcs-listing #following_1").text()!=''?$("#pcs-listing #following_1").text():'0';
			var following14 = $("#pcs-listing #following_2").text()!=''?$("#pcs-listing #following_2").text():'0';
			var following15 = $("#pcs-listing #following_3").text()!=''?$("#pcs-listing #following_3").text():'0';

			var followingytd =parseInt(following4)+parseInt(following5)+parseInt(following6) + parseInt(following7)+parseInt(following8)+parseInt(following9) + parseInt(following10)+parseInt(following11)+parseInt(following12)+parseInt(following13)+parseInt(following14)+parseInt(following15);

			$("#pcs-listing #following_ytd").text(followingytd);


			/*----------------------------------------**************************************************-----------------------------*/
			$('#completed_16').hide();
			$('#completed_17').hide();
			$('#completed_18').hide();
			// Standard initialisation
			var completed4 = $("#pcs-listing #completed_4").text()!=''?$("#pcs-listing #completed_4").text():'0';
			var completed5 = $("#pcs-listing #completed_5").text()!=''?$("#pcs-listing #completed_5").text():'0';
			var completed6 = $("#pcs-listing #completed_6").text()!=''?$("#pcs-listing #completed_6").text():'0';
			var completed7 = $("#pcs-listing #completed_7").text()!=''?$("#pcs-listing #completed_7").text():'0';
			var completed8 = $("#pcs-listing #completed_8").text()!=''?$("#pcs-listing #completed_8").text():'0';
			var completed9 = $("#pcs-listing #completed_9").text()!=''?$("#pcs-listing #completed_9").text():'0';
			var completed10 = $("#pcs-listing #completed_10").text()!=''?$("#pcs-listing #completed_10").text():'0';
			var completed11 = $("#pcs-listing #completed_11").text()!=''?$("#pcs-listing #completed_11").text():'0';
			var completed12 = $("#pcs-listing #completed_12").text()!=''?$("#pcs-listing #completed_12").text():'0';
			var completed13 = $("#pcs-listing #completed_1").text()!=''?$("#pcs-listing #completed_1").text():'0';
			var completed14 = $("#pcs-listing #completed_2").text()!=''?$("#pcs-listing #completed_2").text():'0';
			var completed15 = $("#pcs-listing #completed_3").text()!=''?$("#pcs-listing #completed_3").text():'0';

			var completedytd =parseInt(completed4)+parseInt(completed5)+parseInt(completed6) + parseInt(completed7)+parseInt(completed8)+parseInt(completed9) + parseInt(completed10)+parseInt(completed11)+parseInt(completed12)+parseInt(completed13)+parseInt(completed14)+parseInt(completed15);

			$("#pcs-listing #completed_ytd").text(completedytd);
			/*----------------------------------------**************************************************-----------------------------*/
			$('#drop_16').hide();
			$('#drop_17').hide();
			$('#drop_18').hide();
			// Standard initialisation
			var drop4 = $("#pcs-listing #drop_4").text()!=''?$("#pcs-listing #drop_4").text():'0';
			var drop5 = $("#pcs-listing #drop_5").text()!=''?$("#pcs-listing #drop_5").text():'0';
			var drop6 = $("#pcs-listing #drop_6").text()!=''?$("#pcs-listing #drop_6").text():'0';
			var drop7 = $("#pcs-listing #drop_7").text()!=''?$("#pcs-listing #drop_7").text():'0';
			var drop8 = $("#pcs-listing #drop_8").text()!=''?$("#pcs-listing #drop_8").text():'0';
			var drop9 = $("#pcs-listing #drop_9").text()!=''?$("#pcs-listing #drop_9").text():'0';
			var drop10 = $("#pcs-listing #drop_10").text()!=''?$("#pcs-listing #drop_10").text():'0';
			var drop11 = $("#pcs-listing #drop_11").text()!=''?$("#pcs-listing #drop_11").text():'0';
			var drop12 = $("#pcs-listing #drop_12").text()!=''?$("#pcs-listing #drop_12").text():'0';
			var drop13 = $("#pcs-listing #drop_1").text()!=''?$("#pcs-listing #drop_1").text():'0';
			var drop14 = $("#pcs-listing #drop_2").text()!=''?$("#pcs-listing #drop_2").text():'0';
			var drop15 = $("#pcs-listing #drop_3").text()!=''?$("#pcs-listing #drop_3").text():'0';

			var dropytd =parseInt(drop4)+parseInt(drop5)+parseInt(drop6) + parseInt(drop7)+parseInt(drop8)+parseInt(drop9) + parseInt(drop10)+parseInt(drop11)+parseInt(drop12)+parseInt(drop13)+parseInt(drop14)+parseInt(drop15);

			$("#pcs-listing #drop_ytd").text(dropytd);
			/*----------------------------------------**************************************************-----------------------------*/

		} );
		$(document).ready(function () {
			var product =$('#productId').val();
			var segment =$('#segmentId').val();
			var zone =$('#zoneId').val();
			var Dealer =$('#DealerId').val();
			if (product !='') {
				User_product_change(product,segment);
			}
			if (zone !='') {
				getDealerByZoneId(zone,Dealer);
			}
			$('#pcs-listing').DataTable({
				dom: 'Bfrtip',
				fixedHeader: true,
				
				"scrollX": true,
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
					filename: '@yield("title")',
					exportOptions: { modifier: { page: 'all'} }
				},
					{
						extend: 'pdf',
						text: 'PDF',
						className: 'exportExcel',
						filename: '@yield("title")',
						exportOptions: { modifier: { page: 'all'} }
					}	/*,
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
					}*/
				]
			});
		});


		function getDealerByZoneId(el,ell){

			var myarray= [];
			var favorite = [];
			if(ell!='')
			{
				var zz='';
			}
			else
			{
				$('#zone1 :selected').each(function(i, sel)
				{
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
					var delIds = custIds.join(',').split(',');
					for (item1 in Result) {
						var Result2 = Result[item1].split("~");
						if (ell!='') {
							if (jQuery.inArray( Result2[0], delIds ) !== -1 ) {
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
		function monthDetails(monthVal,yearVal,indx,zone,dealer){
			$('#monthDiv').show();

				$.ajax({ url : '{{url("pcs-month-report")}}',data :{'mnthVal':monthVal,'yearVal':yearVal,'indxVal':indx,'zone':zone,'dealer':dealer},
				success:function(data){
					//alert(data);
					console.log(data);
					$('#tabelData').html(data.html);
				}
			});
		}
	</script>
@endsection
