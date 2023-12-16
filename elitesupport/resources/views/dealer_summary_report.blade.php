@extends("layouts.masterlayout")
@section('title','Customer Complaints - Dealer Summary')
@section('bodycontent')

<div class="content-wrapper mobcss">	
	<div class="card">	            
	    <div class="card-body">
			<h4 class="card-title">Dealer Summary Report</h4>
	        <div class="clear"></div>			
            <hr>			
			<form name="myForm" method="post" enctype="multipart/form-data" action="{{url('store-dealer-summary-report')}}" onsubmit="return dealerSummaryReportValidate()">
	            <input type="hidden" name="_token" value="{{ csrf_token() }}">
				<input type="hidden" name="productId" id="productId" value="@isset($product){{$product}} @endisset">
				<input type="hidden" name="segmentId" id="segmentId" value="@isset($segment){{$segment}} @endisset">
				<input type="hidden" name="complaintTypeId" id="complaintTypeId" value="@isset($complaintType){{$complaintType}} @endisset">
				<input type="hidden" name="segVal" id="segVal" value="@isset($segVal){{$segVal}} @endisset">

	            <div class="row">	                    	
                 	<div class="form-group col-md-3">
                        <label for="datefrom" >Date From</label>
						<span style="color: red;">*</span>
						<input type="text" name="datefrom" id="datefrom1" autocomplete="off" placeholder="Date From" class="form-control" value="@isset($datefrom){{$datefrom}} @endisset" />
                        <span id="datefrom_error" style="color:red"></span> 
                    </div>
                    <div class="form-group col-md-3">
                        <label for="dateto" >Date To</label>
						<span style="color: red;">*</span>
						<input type="text" name="dateto" id="dateto1" autocomplete="off" placeholder="Date To" class="form-control" value="@isset($dateto){{$dateto}} @endisset" />
                        <span id="dateto_error" style="color:red"></span> 
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
                        <input type="submit"name="submit" id="submit" value="Submit" class="btn-secondary">
                    </div>
                </div>
            </form>   
			<div class="clear"></div>
            <hr>

            <div class="table-responsive">

				<table id="span-listing" class="table" border="1">
                    <thead>
                        <tr style="background-color: #d3d6d2;">
							<th >Region</th>
							<th>Dealer</th>
							<th>No. of complaints Logged</th>
							<th>% of complaints acknowledged within SLA</th>
							<th>% of complaints closed within SLA</th>
							<th>% of complaints closed beyond SLA</th>
							<th>No. of Feedbacks Collected</th>
							<th>PCS Score</th>
							<th>No Of complaints open within SLA</th>
							<th>No Of complaints open beyond SLA</th>
							<th>% of complaints Re-opened</th>
							
                        </tr>
                    </thead>
                    <tbody>
					@isset($dealerSummaryReport)
						@if(!empty($dealerSummaryReport))
                        @php $regionArr='';$size = sizeof($dealerSummaryReport);$region=''; @endphp
						@for($i=0;$i<$size;$i++)
                            @php
                                $region .= $dealerSummaryReport[$i]->region.',';
								//$region=rtrim($region,',');
                                $regionArr = explode(',',$region);
                            @endphp
                        @endfor

						@php
                            $i=1;$j=1;$k=1;$l=1;$m=1;
							$sCL=0;$ACK_SLA_PERCENTAGE=0;$Closed_WithinSLA_PERCENTAGE=0;$Closed_OutSideSLA_PERCENTAGE=0;
							$Rating_Count=0;$pcs_score=0;$Open_WithinSLA=0;$Open_OutSideSLA=0;$ReOpen_PERCENTAGE=0;

							$nsCL=0;$nACK_SLA_PERCENTAGE=0;$nClosed_WithinSLA_PERCENTAGE=0;$nClosed_OutSideSLA_PERCENTAGE=0;
							$nRating_Count=0;$npcs_score=0;$nOpen_WithinSLA=0;$nOpen_OutSideSLA=0;$nReOpen_PERCENTAGE=0;

							$esCL=0;$eACK_SLA_PERCENTAGE=0;$eClosed_WithinSLA_PERCENTAGE=0;$eClosed_OutSideSLA_PERCENTAGE=0;
							$eRating_Count=0;$epcs_score=0;$eOpen_WithinSLA=0;$eOpen_OutSideSLA=0;$eReOpen_PERCENTAGE=0;

							$csCL=0;$cACK_SLA_PERCENTAGE=0;$cClosed_WithinSLA_PERCENTAGE=0;$cClosed_OutSideSLA_PERCENTAGE=0;
							$cRating_Count=0;$cpcs_score=0;$cOpen_WithinSLA=0;$cOpen_OutSideSLA=0;$cReOpen_PERCENTAGE=0;

							$wsCL=0;$wACK_SLA_PERCENTAGE=0;$wClosed_WithinSLA_PERCENTAGE=0;$wClosed_OutSideSLA_PERCENTAGE=0;
							$wRating_Count=0;$wpcs_score=0;$wOpen_WithinSLA=0;$wOpen_OutSideSLA=0;$wReOpen_PERCENTAGE=0;

							$totalCompletedSurvey=$Total_Rating_Count=$ntotalCompletedSurvey=$nTotal_Rating_Count=$ctotalCompletedSurvey=$cTotal_Rating_Count=$etotalCompletedSurvey=$eTotal_Rating_Count=$wtotalCompletedSurvey=$wTotal_Rating_Count=0;

							$pantotalCompletedSurvey =$panTotal_Rating_Count=$panTotalLogged=$panACK_SLA_PERCENTAGE=$panTotal_Rating_Count=$panClosed_WithinSLA_PERCENTAGE=$panClosed_OutSideSLA_PERCENTAGE=$panReOpen_PERCENTAGE=0;

                            $counts = array_count_values($regionArr);
                           	$southCount = !empty($counts['South'])?$counts['South']:'';
                           	$northCount = !empty($counts['North'])?$counts['North']:'';
                           	$eastCount = !empty($counts['East'])?$counts['East']:'';
                           	$centralCount = !empty($counts['Central'])?$counts['Central']:'';
                           	$westCount = !empty($counts['West'])?$counts['West']:'';
                        @endphp

						@foreach ($dealerSummaryReport as $row)
								@php
									$pantotalCompletedSurvey = ($pantotalCompletedSurvey + $row->totalCompletedSurvey);
									$panTotal_Rating_Count = ($panTotal_Rating_Count + $row->Total_Rating_Count);
									$panTotalLogged =  $panTotalLogged + ($row->Complaint_Count);
									$panACK_SLA_PERCENTAGE =  $panACK_SLA_PERCENTAGE + ($row->TOTAL_ACK_SLA);
									$panClosed_WithinSLA_PERCENTAGE =  $panClosed_WithinSLA_PERCENTAGE + ($row->Total_Closed_3);
									$panClosed_OutSideSLA_PERCENTAGE =  $panClosed_OutSideSLA_PERCENTAGE + ($row->Total_ClosedO_3);
									$panReOpen_PERCENTAGE =  $panReOpen_PERCENTAGE + ($row->TOTAL_ReOpen);


								@endphp
							@if($row->dealerid == 1)
								@php  	$sCL = $sCL + ($row->Complaint_Count);
										//$ACK_SLA_PERCENTAGE = ($ACK_SLA_PERCENTAGE + $row->ACK_SLA_PERCENTAGE);
										$ACK_SLA_PERCENTAGE = ($ACK_SLA_PERCENTAGE + $row->TOTAL_ACK_SLA);
										/*$Closed_WithinSLA_PERCENTAGE = ($Closed_WithinSLA_PERCENTAGE + $row->Closed_WithinSLA_PERCENTAGE);
										$Closed_OutSideSLA_PERCENTAGE = ($Closed_OutSideSLA_PERCENTAGE + $row->Closed_OutSideSLA_PERCENTAGE);
										$ReOpen_PERCENTAGE = ($ReOpen_PERCENTAGE + $row->ReOpen_PERCENTAGE);*/
										$Closed_WithinSLA_PERCENTAGE = ($Closed_WithinSLA_PERCENTAGE + $row->Total_Closed_3);
										$Closed_OutSideSLA_PERCENTAGE = ($Closed_OutSideSLA_PERCENTAGE + $row->Total_ClosedO_3);
										$ReOpen_PERCENTAGE = ($ReOpen_PERCENTAGE + $row->TOTAL_ReOpen);
										$totalCompletedSurvey = ($totalCompletedSurvey + $row->totalCompletedSurvey);
										$Total_Rating_Count = ($Total_Rating_Count + $row->Total_Rating_Count);
										$Rating_Count =$Rating_Count+ $row->Rating_Count;
										$Open_WithinSLA =$Open_WithinSLA+ $row->Open_WithinSLA;
										$Open_OutSideSLA =$Open_OutSideSLA+ $row->Open_OutSideSLA;
                                @endphp
								<tr style="background-color: #fff;" >
									<td>{{$row->region}}</td>
									<td>{{$row->dealer_name}}</td>
									<td id="{{$row->region}}_Complaint_Count_{{$i}}">{{$row->Complaint_Count}}</td>
									<td id="{{$row->region}}_ACK_SLA_PERCENTAGE_{{$i}}">{{$row->ACK_SLA_PERCENTAGE.'%'}}</td>
									<td id="{{$row->region}}_Closed_WithinSLA_PERCENTAGE_{{$i}}">{{$row->Closed_WithinSLA_PERCENTAGE.'%'}}</td>
									<td id="{{$row->region}}_Closed_OutSideSLA_PERCENTAGE_{{$i}}">{{$row->Closed_OutSideSLA_PERCENTAGE.'%'}}</td>
									<td id="{{$row->region}}_Rating_Count_{{$i}}">{{$row->Rating_Count}}</td>
									<td id="{{$row->region}}_pcs_score_{{$i}}">{{$row->pcs_score!=''?$row->pcs_score.'%':'0'}}</td>
									<td id="{{$row->region}}_Open_WithinSLA_{{$i}}">{{$row->Open_WithinSLA!=''?$row->Open_WithinSLA:'0'}}</td>
									<td id="{{$row->region}}_Open_OutSideSLA_{{$i}}">{{$row->Open_OutSideSLA}}</td>
									<td id="{{$row->region}}_ReOpen_PERCENTAGE_{{$i}}">{{$row->ReOpen_PERCENTAGE.'%'}}</td>
								</tr>
								@php  $i++; @endphp
								@if($i>$southCount && $southCount!='')

									@php
									
										$Total_Rating_Count = $Total_Rating_Count!=0?$Total_Rating_Count:1;
                                        $pcs_score = round(($totalCompletedSurvey/$Total_Rating_Count)*100);
                                        /*$ACK_SLA_PERCENTAGE = $ACK_SLA_PERCENTAGE/$southCount;
                                        $Closed_WithinSLA_PERCENTAGE = ($Closed_WithinSLA_PERCENTAGE)/$southCount;
                                        $Closed_OutSideSLA_PERCENTAGE = ($Closed_OutSideSLA_PERCENTAGE)/$southCount;
                                        $ReOpen_PERCENTAGE= ($ReOpen_PERCENTAGE)/$southCount;*/
                                        $ACK_SLA_PERCENTAGE = ($ACK_SLA_PERCENTAGE/$sCL)*100;
                                        $Closed_WithinSLA_PERCENTAGE = ($Closed_WithinSLA_PERCENTAGE/$sCL)*100;
                                        $Closed_OutSideSLA_PERCENTAGE = ($Closed_OutSideSLA_PERCENTAGE/$sCL)*100;
                                        $ReOpen_PERCENTAGE= ($ReOpen_PERCENTAGE/$sCL)*100;
									@endphp
									<tr style="background-color: #d3d6d2;" >
										<td style="color: transparent;">South</td>
										<td>Region summary</td>
										<td id="south_logged">{{($sCL!='' or $sCL!='0')?$sCL:'0'}}</td>
										<td id="south_ack">{{($ACK_SLA_PERCENTAGE!='' or $ACK_SLA_PERCENTAGE!='0')?round($ACK_SLA_PERCENTAGE).'%':'0%'}}</td>
										<td id="south_closedwithinsla">{{($Closed_WithinSLA_PERCENTAGE!='' or $Closed_WithinSLA_PERCENTAGE!='0')?round($Closed_WithinSLA_PERCENTAGE).'%':'0%'}}</td>
										<td id="south_closedoutsidesla">{{($Closed_OutSideSLA_PERCENTAGE!='' or $Closed_OutSideSLA_PERCENTAGE!='0')?round($Closed_OutSideSLA_PERCENTAGE).'%':'0%'}}</td>
										<td id="south_ratingcount">{{($Rating_Count!='' or $Rating_Count!='0')?$Rating_Count:'0'}}</td>
										<td id="south_pcs">{{$pcs_score!=0?round($pcs_score).'%':'0'}}</td>
										<td id="south_openwithinsla">{{($Open_WithinSLA!='' or $Open_WithinSLA!='0')?$Open_WithinSLA:'0'}}</td>
										<td id="south_openoutsidesla">{{($Open_OutSideSLA!='' or $Open_OutSideSLA!='0')?$Open_OutSideSLA:'0'}}</td>
										<td id="south_percentage">{{($ReOpen_PERCENTAGE!='' or $ReOpen_PERCENTAGE!='0')?round($ReOpen_PERCENTAGE).'%':'0%'}}</td>
									</tr>
								@endif
							@endif
							@if($row->dealerid == 2)
								@php  	$nsCL = $nsCL + ($row->Complaint_Count);
										$nACK_SLA_PERCENTAGE = ($nACK_SLA_PERCENTAGE + $row->TOTAL_ACK_SLA);
										$nClosed_WithinSLA_PERCENTAGE = ($nClosed_WithinSLA_PERCENTAGE + $row->Total_Closed_3);
										$nClosed_OutSideSLA_PERCENTAGE = ($nClosed_OutSideSLA_PERCENTAGE + $row->Total_ClosedO_3);
										$nReOpen_PERCENTAGE = ($nReOpen_PERCENTAGE + $row->ReOpen_PERCENTAGE);
										//$npcs_score = ($npcs_score + $row->pcs_score);
										$ntotalCompletedSurvey = ($ntotalCompletedSurvey + $row->totalCompletedSurvey);
										$nTotal_Rating_Count = ($nTotal_Rating_Count + $row->Total_Rating_Count);
										$nRating_Count =$nRating_Count+ $row->Rating_Count;
										$nOpen_WithinSLA =$nOpen_WithinSLA+ $row->Open_WithinSLA;
										$nOpen_OutSideSLA =$nOpen_OutSideSLA+ $row->Open_OutSideSLA;
										
								@endphp
								<tr style="background-color: #fff;" >
									<td>{{$row->region}}</td>
									<td>{{$row->dealer_name}}</td>
									<td id="{{$row->region}}_Complaint_Count_{{$j}}">{{$row->Complaint_Count}}</td>
									<td id="{{$row->region}}_ACK_SLA_PERCENTAGE_{{$j}}">{{$row->ACK_SLA_PERCENTAGE.'%'}}</td>
									<td id="{{$row->region}}_Closed_WithinSLA_PERCENTAGE_{{$j}}">{{$row->Closed_WithinSLA_PERCENTAGE.'%'}}</td>
									<td id="{{$row->region}}_Closed_OutSideSLA_PERCENTAGE_{{$j}}">{{$row->Closed_OutSideSLA_PERCENTAGE.'%'}}</td>
									<td id="{{$row->region}}_Rating_Count_{{$j}}">{{$row->Rating_Count}}</td>
									<td id="{{$row->region}}_pcs_score_{{$j}}">{{$row->pcs_score!=''?$row->pcs_score.'%':'0'}}</td>
									<td id="{{$row->region}}_Open_WithinSLA_{{$j}}">{{$row->Open_WithinSLA!=''?$row->Open_WithinSLA:'0'}}</td>
									<td id="{{$row->region}}_Open_OutSideSLA_{{$j}}">{{$row->Open_OutSideSLA}}</td>
									<td id="{{$row->region}}_ReOpen_PERCENTAGE_{{$j}}">{{$row->ReOpen_PERCENTAGE.'%'}}</td>
								</tr>
								@php $j++; @endphp
								@if($j>$northCount && $northCount!='')
									@php
										$nACK_SLA_PERCENTAGE = ($nACK_SLA_PERCENTAGE/$nsCL)*100;
										$nClosed_WithinSLA_PERCENTAGE = ($nClosed_WithinSLA_PERCENTAGE/$nsCL)*100;
										$nClosed_OutSideSLA_PERCENTAGE = ($nClosed_OutSideSLA_PERCENTAGE/$nsCL)*100;
										//$npcs_score = ($npcs_score)/$northCount;
										$nTotal_Rating_Count = $nTotal_Rating_Count!=0?$nTotal_Rating_Count:1;
										$npcs_score = round(($ntotalCompletedSurvey/$nTotal_Rating_Count)*100);
										 $nReOpen_PERCENTAGE= ($nReOpen_PERCENTAGE/$nsCL)*100;
									@endphp
									<tr style="background-color: #d3d6d2;" >
										<td id="" style="color: transparent;">North</td>
										<td id="">Region summary</td>
										<td id="north_logged">{{($nsCL!='' or $nsCL!='0')?$nsCL:'0'}}</td>
										<td id="north_ack">{{($nACK_SLA_PERCENTAGE!='' or $nACK_SLA_PERCENTAGE!='0')?round($nACK_SLA_PERCENTAGE).'%':'0%'}}</td>
										<td id="north_closedwithinsla">{{($nClosed_WithinSLA_PERCENTAGE!='' or $nClosed_WithinSLA_PERCENTAGE!='0')?round($nClosed_WithinSLA_PERCENTAGE).'%':'0%'}}</td>
										<td id="north_closedoutsidesla">{{($nClosed_OutSideSLA_PERCENTAGE!='' or $nClosed_OutSideSLA_PERCENTAGE!='0')?round($nClosed_OutSideSLA_PERCENTAGE).'%':'0%'}}</td>
										<td id="north_ratingcount">{{($nRating_Count!='' or $nRating_Count!='0')?$nRating_Count:'0'}}</td>
										<td id="north_pcs">{{$npcs_score!=0?round($npcs_score).'%':'0'}}</td>
										<td id="north_openwithinsla">{{($nOpen_WithinSLA!='' or $nOpen_WithinSLA!='0')?$nOpen_WithinSLA:'0'}}</td>
										<td id="north_openoutsidesla">{{($nOpen_OutSideSLA!='' or $nOpen_OutSideSLA!='0')?$nOpen_OutSideSLA:'0'}}</td>
										<td id="north_percentage">{{($nReOpen_PERCENTAGE!='' or $nReOpen_PERCENTAGE!='0')?round($nReOpen_PERCENTAGE).'%':'0%'}}</td>
									</tr>
								@endif
							@endif
							@if($row->dealerid == 3)

								@php  	$esCL = $esCL + ($row->Complaint_Count);
										$eACK_SLA_PERCENTAGE = ($eACK_SLA_PERCENTAGE + $row->TOTAL_ACK_SLA);
										$eClosed_WithinSLA_PERCENTAGE = ($eClosed_WithinSLA_PERCENTAGE + $row->Total_Closed_3);
										$eClosed_OutSideSLA_PERCENTAGE = ($eClosed_OutSideSLA_PERCENTAGE + $row->Total_ClosedO_3);
										$eReOpen_PERCENTAGE = ($eReOpen_PERCENTAGE + $row->ReOpen_PERCENTAGE);
										//$epcs_score = ($epcs_score + $row->pcs_score);
										$etotalCompletedSurvey = ($etotalCompletedSurvey + $row->totalCompletedSurvey);
										$eTotal_Rating_Count = ($eTotal_Rating_Count + $row->Total_Rating_Count);
										$eRating_Count =$eRating_Count+ $row->Rating_Count;
										$eOpen_WithinSLA =$eOpen_WithinSLA+ $row->Open_WithinSLA;
										$eOpen_OutSideSLA =$eOpen_OutSideSLA+ $row->Open_OutSideSLA;
								@endphp
								<tr style="background-color: #fff;" >
									<td>{{$row->region}}</td>
									<td>{{$row->dealer_name}}</td>
									<td id="{{$row->region}}_Complaint_Count_{{$k}}">{{$row->Complaint_Count}}</td>
									<td id="{{$row->region}}_ACK_SLA_PERCENTAGE_{{$k}}">{{$row->ACK_SLA_PERCENTAGE.'%'}}</td>
									<td id="{{$row->region}}_Closed_WithinSLA_PERCENTAGE_{{$k}}">{{$row->Closed_WithinSLA_PERCENTAGE.'%'}}</td>
									<td id="{{$row->region}}_Closed_OutSideSLA_PERCENTAGE_{{$k}}">{{$row->Closed_OutSideSLA_PERCENTAGE.'%'}}</td>
									<td id="{{$row->region}}_Rating_Count_{{$k}}">{{$row->Rating_Count}}</td>
									<td id="{{$row->region}}_pcs_score_{{$k}}">{{$row->pcs_score!=''?$row->pcs_score.'%':'0'}}</td>
									<td id="{{$row->region}}_Open_WithinSLA_{{$k}}">{{$row->Open_WithinSLA!=''?$row->Open_WithinSLA:'0'}}</td>
									<td id="{{$row->region}}_Open_OutSideSLA_{{$k}}">{{$row->Open_OutSideSLA}}</td>
									<td id="{{$row->region}}_ReOpen_PERCENTAGE_{{$k}}">{{$row->ReOpen_PERCENTAGE.'%'}}</td>
								</tr>
								@php $k++; @endphp

								@if($k>$eastCount && $eastCount!='')

									@php
										$eACK_SLA_PERCENTAGE = round(($eACK_SLA_PERCENTAGE/$esCL)*100);
										$eClosed_WithinSLA_PERCENTAGE = round(($eClosed_WithinSLA_PERCENTAGE/$esCL)*100);
										$eClosed_OutSideSLA_PERCENTAGE = round(($eClosed_OutSideSLA_PERCENTAGE/$esCL)*100);
										//$epcs_score = ($epcs_score)/$eastCount;
										$eTotal_Rating_Count = $eTotal_Rating_Count!=0?$eTotal_Rating_Count:1;
										$epcs_score = round(($etotalCompletedSurvey/$eTotal_Rating_Count)*100);
										 $eReOpen_PERCENTAGE= ($eReOpen_PERCENTAGE/$esCL)*100;
									@endphp
									<tr style="background-color: #d3d6d2;" >
										<td style="color: transparent;">East</td>
										<td>Region summary</td>
										<td id="east_logged">{{($esCL!='' or $esCL!='0')?$esCL:'0'}}</td>
										<td id="east_ack">{{($eACK_SLA_PERCENTAGE!='' or $eACK_SLA_PERCENTAGE!='0')?($eACK_SLA_PERCENTAGE).'%':'0%'}}</td>
										<td id="east_closedwithinsla">{{($eClosed_WithinSLA_PERCENTAGE!='' or $eClosed_WithinSLA_PERCENTAGE!='0')?($eClosed_WithinSLA_PERCENTAGE).'%':'0%'}}</td>
										<td id="east_closedoutsidesla">{{($eClosed_OutSideSLA_PERCENTAGE!='' or $eClosed_OutSideSLA_PERCENTAGE!='0')?($eClosed_OutSideSLA_PERCENTAGE).'%':'0%'}}</td>
										<td id="east_ratingcount">{{($eRating_Count!='' or $eRating_Count!='0')?$eRating_Count:'0'}}</td>
										<td id="east_pcs">{{$epcs_score!=0?round($epcs_score).'%':'0%'}}</td>
										<td id="east_openwithinsla">{{($eOpen_WithinSLA!='' or $eOpen_WithinSLA!='0')?$eOpen_WithinSLA:'0'}}</td>
										<td id="east_openoutsidesla">{{($eOpen_OutSideSLA!='' or $eOpen_OutSideSLA!='0')?$eOpen_OutSideSLA:'0'}}</td>
										<td id="east_percentage">{{($eReOpen_PERCENTAGE!='' or $eReOpen_PERCENTAGE!='0')?round($eReOpen_PERCENTAGE).'%':'0%'}}</td>
									</tr>
								@endif
							@endif
							@if($row->dealerid == 4)
								@php  	$csCL = $csCL + ($row->Complaint_Count);
										$cACK_SLA_PERCENTAGE = ($cACK_SLA_PERCENTAGE + $row->TOTAL_ACK_SLA);
										$cClosed_WithinSLA_PERCENTAGE = ($cClosed_WithinSLA_PERCENTAGE + $row->Total_Closed_3);
										$cClosed_OutSideSLA_PERCENTAGE = ($cClosed_OutSideSLA_PERCENTAGE + $row->Total_ClosedO_3);
										$cReOpen_PERCENTAGE = ($cReOpen_PERCENTAGE + $row->ReOpen_PERCENTAGE);
										//$cpcs_score = ($cpcs_score + $row->pcs_score);
										$ctotalCompletedSurvey = ($ctotalCompletedSurvey + $row->totalCompletedSurvey);
										$cTotal_Rating_Count = ($cTotal_Rating_Count + $row->Total_Rating_Count);
										$cRating_Count =$cRating_Count+ $row->Rating_Count;
										$cOpen_WithinSLA =$cOpen_WithinSLA+ $row->Open_WithinSLA;
										$cOpen_OutSideSLA =$cOpen_OutSideSLA+ $row->Open_OutSideSLA;
								@endphp
								<tr style="background-color: #fff;" >
									<td>{{$row->region}}</td>
									<td>{{$row->dealer_name}}</td>
									<td id="{{$row->region}}_Complaint_Count_{{$l}}">{{$row->Complaint_Count}}</td>
									<td id="{{$row->region}}_ACK_SLA_PERCENTAGE_{{$l}}">{{$row->ACK_SLA_PERCENTAGE.'%'}}</td>
									<td id="{{$row->region}}_Closed_WithinSLA_PERCENTAGE_{{$l}}">{{$row->Closed_WithinSLA_PERCENTAGE.'%'}}</td>
									<td id="{{$row->region}}_Closed_OutSideSLA_PERCENTAGE_{{$l}}">{{$row->Closed_OutSideSLA_PERCENTAGE.'%'}}</td>
									<td id="{{$row->region}}_Rating_Count_{{$l}}">{{$row->Rating_Count}}</td>
									<td id="{{$row->region}}_pcs_score_{{$l}}">{{($row->pcs_score!='')?$row->pcs_score.'%':'0'}}</td>
									<td id="{{$row->region}}_Open_WithinSLA_{{$l}}">{{$row->Open_WithinSLA!=''?$row->Open_WithinSLA:'0'}}</td>
									<td id="{{$row->region}}_Open_OutSideSLA_{{$l}}">{{$row->Open_OutSideSLA}}</td>
									<td id="{{$row->region}}_ReOpen_PERCENTAGE_{{$l}}">{{$row->ReOpen_PERCENTAGE.'%'}}</td>
								</tr>
								@php $l++; @endphp
								@if($l>$centralCount && $centralCount!='')
									@php
										$cACK_SLA_PERCENTAGE = round(($cACK_SLA_PERCENTAGE/$csCL)*100);
										$cClosed_WithinSLA_PERCENTAGE = ($cClosed_WithinSLA_PERCENTAGE/$csCL)*100;
										$cClosed_OutSideSLA_PERCENTAGE = ($cClosed_OutSideSLA_PERCENTAGE/$csCL)*100;
										//$cpcs_score = ($cpcs_score)/$centralCount;
										$cTotal_Rating_Count = $cTotal_Rating_Count!=0?$cTotal_Rating_Count:1;
										$cpcs_score = round(($ctotalCompletedSurvey/$cTotal_Rating_Count)*100);
										 $cReOpen_PERCENTAGE= ($cReOpen_PERCENTAGE/$csCL)*100;
									@endphp
									<tr style="background-color: #d3d6d2;" >
										<td style="color: transparent;">Central</td>
										<td>Region summary</td>
										<td id="central_logged">{{($csCL!='' or $csCL!='0')?$csCL:'0'}}</td>
										<td id="central_ack">{{($cACK_SLA_PERCENTAGE!='0' or $cACK_SLA_PERCENTAGE!='')?($cACK_SLA_PERCENTAGE).'%':'0%'}}</td>
										<td id="central_closedwithinsla">{{($cClosed_WithinSLA_PERCENTAGE!='' or $cClosed_WithinSLA_PERCENTAGE!='0')?round($cClosed_WithinSLA_PERCENTAGE).'%':'0%'}}</td>
										<td id="central_closedoutsidesla">{{($cClosed_OutSideSLA_PERCENTAGE!='' or $cClosed_OutSideSLA_PERCENTAGE!='0')?round($cClosed_OutSideSLA_PERCENTAGE).'%':'0%'}}</td>
										<td id="central_ratingcount">{{($cRating_Count!='' or $cRating_Count!='0')?$cRating_Count:'0'}}</td>
										<td id="central_pcs">{{$cpcs_score!=0?round($cpcs_score).'%':'0%'}}</td>
										<td id="central_openwithinsla">{{($cOpen_WithinSLA!='' or $cOpen_WithinSLA!='0')?$cOpen_WithinSLA:'0'}}</td>
										<td id="central_openoutsidesla">{{($cOpen_OutSideSLA!='' or $cOpen_OutSideSLA!='0')?$cOpen_OutSideSLA:'0'}}</td>
										<td id="central_percentage">{{($cReOpen_PERCENTAGE!='' or $cReOpen_PERCENTAGE!='0')?round($cReOpen_PERCENTAGE).'%':'0%'}}</td>
									</tr>
								@endif
							@endif
							@if($row->dealerid == 5)
								@php  	$wsCL = $wsCL + ($row->Complaint_Count);
										$wACK_SLA_PERCENTAGE = ($wACK_SLA_PERCENTAGE + $row->TOTAL_ACK_SLA);
										$wClosed_WithinSLA_PERCENTAGE = ($wClosed_WithinSLA_PERCENTAGE + $row->Total_Closed_3);
										$wClosed_OutSideSLA_PERCENTAGE = ($wClosed_OutSideSLA_PERCENTAGE + $row->Total_ClosedO_3);
										$wReOpen_PERCENTAGE = ($wReOpen_PERCENTAGE + $row->ReOpen_PERCENTAGE);
										$wtotalCompletedSurvey = ($wtotalCompletedSurvey + $row->totalCompletedSurvey);
										$wTotal_Rating_Count = ($wTotal_Rating_Count + $row->Total_Rating_Count);
										//$wpcs_score = ($wpcs_score + $row->pcs_score);
										$wRating_Count =$wRating_Count+ $row->Rating_Count;
										$wOpen_WithinSLA =$wOpen_WithinSLA+ $row->Open_WithinSLA;
										$wOpen_OutSideSLA =$wOpen_OutSideSLA+ $row->Open_OutSideSLA;
								@endphp
								<tr style="background-color: #fff;" >
									<td>{{$row->region}}</td>
									<td>{{$row->dealer_name}}</td>
									<td id="{{$row->region}}_Complaint_Count_{{$m}}">{{$row->Complaint_Count}}</td>
									<td id="{{$row->region}}_ACK_SLA_PERCENTAGE_{{$m}}">{{$row->ACK_SLA_PERCENTAGE.'%'}}</td>
									<td id="{{$row->region}}_Closed_WithinSLA_PERCENTAGE_{{$m}}">{{$row->Closed_WithinSLA_PERCENTAGE.'%'}}</td>
									<td id="{{$row->region}}_Closed_OutSideSLA_PERCENTAGE_{{$m}}">{{$row->Closed_OutSideSLA_PERCENTAGE.'%'}}</td>
									<td id="{{$row->region}}_Rating_Count_{{$m}}">{{$row->Rating_Count}}</td>
									<td id="{{$row->region}}_pcs_score_{{$m}}">{{$row->pcs_score!=''?$row->pcs_score.'%':'0'}}</td>
									<td id="{{$row->region}}_Open_WithinSLA_{{$m}}">{{$row->Open_WithinSLA!=''?$row->Open_WithinSLA:'0'}}</td>
									<td id="{{$row->region}}_Open_OutSideSLA_{{$m}}">{{$row->Open_OutSideSLA}}</td>
									<td id="{{$row->region}}_ReOpen_PERCENTAGE_{{$m}}">{{$row->ReOpen_PERCENTAGE.'%'}}</td>
								</tr>
								
								@php $m++; @endphp
								@if($m>$westCount && $westCount!='')
								
									@php
										$wACK_SLA_PERCENTAGE = round(($wACK_SLA_PERCENTAGE/$wsCL)*100);
										$wClosed_WithinSLA_PERCENTAGE = ($wClosed_WithinSLA_PERCENTAGE/$wsCL)*100;
										$wClosed_OutSideSLA_PERCENTAGE = ($wClosed_OutSideSLA_PERCENTAGE/$wsCL)*100;
										//$wpcs_score = ($wpcs_score)/$westCount;
										$wTotal_Rating_Count = $wTotal_Rating_Count!=0?$wTotal_Rating_Count:1;
										$wpcs_score = round(($wtotalCompletedSurvey/$wTotal_Rating_Count)*100);
										$wReOpen_PERCENTAGE= ($wReOpen_PERCENTAGE/$wsCL)*100;
									@endphp
									<tr style="background-color: #d3d6d2;" >
										<td style="color: transparent;">West</td>
										<td>Region summary</td>
										<td id="west_logged">{{($wsCL!='' or $wsCL!='0')?$wsCL:'0'}}</td>
										<td id="west_ack">{{($wACK_SLA_PERCENTAGE!='' or $wACK_SLA_PERCENTAGE!='0')?($wACK_SLA_PERCENTAGE).'%':'0%'}}</td>
										<td id="west_closedwithinsla">{{($wClosed_WithinSLA_PERCENTAGE!='' or $wClosed_WithinSLA_PERCENTAGE!='0')?round($wClosed_WithinSLA_PERCENTAGE).'%':'0%'}}</td>
										<td id="west_closedoutsidesla">{{($wClosed_OutSideSLA_PERCENTAGE!='' or $wClosed_OutSideSLA_PERCENTAGE!='0')?round($wClosed_OutSideSLA_PERCENTAGE).'%':'0%'}}</td>
										<td id="west_ratingcount">{{($wRating_Count!='' or $wRating_Count!='0')?$wRating_Count:'0'}}</td>
										<td id="west_pcs">{{$wpcs_score!=0?round($wpcs_score).'%':'0'}}</td>
										<td id="west_openwithinsla">{{($wOpen_WithinSLA!='' or $wOpen_WithinSLA!='0')?$wOpen_WithinSLA:'0'}}</td>
										<td id="west_openoutsidesla">{{($wOpen_OutSideSLA!='' or $wOpen_OutSideSLA!='0')?$wOpen_OutSideSLA:'0'}}</td>
										<td id="west_percentage">{{($wReOpen_PERCENTAGE!='' or $wReOpen_PERCENTAGE!='0')?round($wReOpen_PERCENTAGE).'%':'0%'}}</td>
									</tr>

								@endif
							@endif
						@endforeach
						<tr style="background-color: #000;COLOR:#FFF;" >
							<td colspan="2">Pan India summary</td>
							<td style="display: none;">Pan India summary</td>
							<td id="pan_logged"></td>
							<td id="pan_ack"></td>
							<td id="pan_closedwithinsla"></td>
							<td id="pan_closedoutsidesla"></td>
							<td id="pan_ratingcount"></td>
							<td id="pan_pcs"></td>
							<td id="pan_openwithinsla"></td>
							<td id="pan_openoutsidesla"></td>
							<td id="pan_percentage"></td>
						</tr>
						<tr style="display: none;border-left: none;border-right: none;">
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						<tr style="display: none;">
							<td colspan="2">Date From</td>
							<td colspan="2">Date To</td>
							<td colspan="2">Product</td>
							<td colspan="2">Product Segment</td>
							<td colspan="2">Complaint Type</td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						<tr style="display: none;">
							<td colspan="2">@isset($datefrom){{$datefrom}} @endisset</td>
							<td colspan="2">@isset($dateto){{$dateto}} @endisset</td>
							<td colspan="2">@isset($productVal){{$productVal}} @endisset</td>
							<td colspan="2">@isset($segmentVal){{$segmentVal}} @endisset</td>
							<td colspan="2">@isset($compVal){{$compVal}} @endisset</td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
							@else
							<tr style="background-color: #000;COLOR:#FFF;" >
								<td colspan="11">Data No Found</td>
							</tr>
						@endif
					@endisset

                    </tbody>
                </table>
			</div>
			<br>
	    </div>	            
	</div>
<style>
	.dataTables_wrapper .dataTable thead th {
		vertical-align: middle !important;
	}
	#span-listing tr td{
		text-align: center;
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
	function getDealerByZoneId(zoneId,ell){
		var id =zoneId;		
		$.ajax({ 
			url: '{{url("get-dealer-by-zone-id")}}',
			data :{'zone_id':id},
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
				document.getElementById('Dealer').innerHTML = "<optgroup><option value='NA'>--Select--</option>" + str + "</optgroup>";				
			}
		});
	}
	
</script>

<script type="text/javascript">

		$(document).ready(function () {

			var product_text = $('#product option:selected').toArray().map(item => item.text).join();
			var segment_text = $('#segVal').val();
			var complaintType_text = $('#complaintType option:selected').toArray().map(item => item.text).join();

			if(product_text!=''){
				$('#span-listing #pro_table').text('Product : '+product_text);
			}if(segment_text!=''){
				$('#span-listing #seg_table').text('Segment : '+segment_text);
			}if(complaintType_text!=''){
				$('#span-listing #copm_table').text('Complaint Type : '+complaintType_text);
			}

			var  south_logged = $('#span-listing #south_logged').text()!=''?$('#span-listing #south_logged').text():0;
			var  north_logged = $('#span-listing #north_logged').text()!=''?$('#span-listing #north_logged').text():0;
			var  central_logged = $('#span-listing #central_logged').text()!=''?$('#span-listing #central_logged').text():0;
			var  east_logged = $('#span-listing #east_logged').text()!=''?$('#span-listing #east_logged').text():0;
			var  west_logged = $('#span-listing #west_logged').text()!=''?$('#span-listing #west_logged').text():0;

			var  south_ratingcount = $('#span-listing #south_ratingcount').text()!=''?$('#span-listing #south_ratingcount').text():0;
			var  north_ratingcount = $('#span-listing #north_ratingcount').text()!=''?$('#span-listing #north_ratingcount').text():0;
			var  central_ratingcount = $('#span-listing #central_ratingcount').text()!=''?$('#span-listing #central_ratingcount').text():0;
			var  east_ratingcount = $('#span-listing #east_ratingcount').text()!=''?$('#span-listing #east_ratingcount').text():0;
			var  west_ratingcount = $('#span-listing #west_logged').text()!=''?$('#span-listing #west_ratingcount').text():0;

			var  south_openwithinsla = $('#span-listing #south_openwithinsla').text()!=''?$('#span-listing #south_openwithinsla').text():0;
			var  north_openwithinsla = $('#span-listing #north_openwithinsla').text()!=''?$('#span-listing #north_openwithinsla').text():0;
			var  central_openwithinsla = $('#span-listing #central_openwithinsla').text()!=''?$('#span-listing #central_openwithinsla').text():0;
			var  east_openwithinsla = $('#span-listing #east_openwithinsla').text()!=''?$('#span-listing #east_openwithinsla').text():0;
			var  west_openwithinsla = $('#span-listing #west_openwithinsla').text()!=''?$('#span-listing #west_openwithinsla').text():0;

			var total_logged = parseInt(south_logged) + parseInt(north_logged) + parseInt(central_logged) + parseInt(east_logged) + parseInt(west_logged);

			var pan_ratingcount = parseInt(south_ratingcount) + parseInt(north_ratingcount) + parseInt(central_ratingcount) + parseInt(east_ratingcount) + parseInt(west_ratingcount);

			var pan_openwithinsla = parseInt(south_openwithinsla) + parseInt(north_openwithinsla) + parseInt(central_openwithinsla) + parseInt(east_openwithinsla) + parseInt(west_openwithinsla);

			var  south_openoutsidesla = $('#span-listing #south_openoutsidesla').text()!=''?$('#span-listing #south_openoutsidesla').text():0;
			var  north_openoutsidesla = $('#span-listing #north_openoutsidesla').text()!=''?$('#span-listing #north_openoutsidesla').text():0;
			var  central_openoutsidesla = $('#span-listing #central_openoutsidesla').text()!=''?$('#span-listing #central_openoutsidesla').text():0;
			var  east_openoutsidesla = $('#span-listing #east_openoutsidesla').text()!=''?$('#span-listing #east_openoutsidesla').text():0;
			var  west_openoutsidesla = $('#span-listing #west_openoutsidesla').text()!=''?$('#span-listing #west_openoutsidesla').text():0;

			var pan_openoutsidesla = parseInt(south_openoutsidesla) + parseInt(north_openoutsidesla) + parseInt(central_openoutsidesla) + parseInt(east_openoutsidesla) + parseInt(west_openoutsidesla);

			var  south_ack = $('#span-listing #south_ack').text().replace("%", "")!=''?$('#span-listing #south_ack').text().replace("%", ""):0;
			var  north_ack = $('#span-listing #north_ack').text().replace("%", "")!=''?$('#span-listing #north_ack').text().replace("%", ""):0;
			var  central_ack = $('#span-listing #central_ack').text().replace("%", "")!=''?$('#span-listing #central_ack').text().replace("%", ""):0;
			var  east_ack = $('#span-listing #east_ack').text().replace("%", "")!=''?$('#span-listing #east_ack').text().replace("%", ""):0;
			var  west_ack = $('#span-listing #west_ack').text().replace("%", "")!=''?$('#span-listing #west_ack').text().replace("%", ""):0;

			var pan_ack = parseInt(south_ack) + parseInt(north_ack) + parseInt(central_ack) + parseInt(east_ack) + parseInt(west_ack);

			var  south_closedwithinsla = $('#span-listing #south_closedwithinsla').text().replace("%", "")!=''?$('#span-listing #south_closedwithinsla').text().replace("%", ""):0;
			var  north_closedwithinsla = $('#span-listing #north_closedwithinsla').text().replace("%", "")!=''?$('#span-listing #north_closedwithinsla').text().replace("%", ""):0;
			var  central_closedwithinsla = $('#span-listing #central_closedwithinsla').text().replace("%", "")!=''?$('#span-listing #central_closedwithinsla').text().replace("%", ""):0;
			var  east_closedwithinsla = $('#span-listing #east_closedwithinsla').text().replace("%", "")!=''?$('#span-listing #east_closedwithinsla').text().replace("%", ""):0;
			var  west_closedwithinsla = $('#span-listing #west_closedwithinsla').text().replace("%", "")!=''?$('#span-listing #west_closedwithinsla').text().replace("%", ""):0;

var pan_closedwithinsla = parseInt(south_closedwithinsla) + parseInt(north_closedwithinsla) + parseInt(central_closedwithinsla) + parseInt(east_closedwithinsla) + parseInt(west_closedwithinsla);


			var  south_closedoutsidesla = $('#span-listing #south_closedoutsidesla').text().replace("%", "")!=''?$('#span-listing #south_closedoutsidesla').text().replace("%", ""):0;
			var  north_closedoutsidesla = $('#span-listing #north_closedoutsidesla').text().replace("%", "")!=''?$('#span-listing #north_closedoutsidesla').text().replace("%", ""):0;
			var  central_closedoutsidesla = $('#span-listing #central_closedoutsidesla').text().replace("%", "")!=''?$('#span-listing #central_closedoutsidesla').text().replace("%", ""):0;
			var  east_closedoutsidesla = $('#span-listing #east_closedoutsidesla').text().replace("%", "")!=''?$('#span-listing #east_closedoutsidesla').text().replace("%", ""):0;
			var  west_closedoutsidesla = $('#span-listing #west_closedoutsidesla').text().replace("%", "")!=''?$('#span-listing #west_closedoutsidesla').text().replace("%", ""):0;

var pan_closedoutsidesla = parseInt(south_closedoutsidesla) + parseInt(north_closedoutsidesla) + parseInt(central_closedoutsidesla) + parseInt(east_closedoutsidesla) + parseInt(west_closedoutsidesla);

			var  south_pcs = $('#span-listing #south_pcs').text().replace("%", "")!=''?$('#span-listing #south_pcs').text().replace("%", ""):0;
			var  north_pcs = $('#span-listing #north_pcs').text().replace("%", "")!=''?$('#span-listing #north_pcs').text().replace("%", ""):0;
			var  central_pcs = $('#span-listing #central_pcs').text().replace("%", "")!=''?$('#span-listing #central_pcs').text().replace("%", ""):0;
			var  east_pcs = $('#span-listing #east_pcs').text().replace("%", "")!=''?$('#span-listing #east_pcs').text().replace("%", ""):0;
			var  west_pcs = $('#span-listing #west_pcs').text().replace("%", "")!=''?$('#span-listing #west_pcs').text().replace("%", ""):0;

//var pan_pcs = parseInt(south_pcs) + parseInt(north_pcs) + parseInt(central_pcs) + parseInt(east_pcs) + parseInt(west_pcs);


			var  south_percentage = $('#span-listing #south_percentage').text().replace("%", "")!=''?$('#span-listing #south_percentage').text().replace("%", ""):0;
			var  north_percentage = $('#span-listing #north_percentage').text().replace("%", "")!=''?$('#span-listing #north_percentage').text().replace("%", ""):0;
			var  central_percentage = $('#span-listing #central_percentage').text().replace("%", "")!=''?$('#span-listing #central_percentage').text().replace("%", ""):0;
			var  east_percentage = $('#span-listing #east_percentage').text().replace("%", "")!=''?$('#span-listing #east_percentage').text().replace("%", ""):0;
			var  west_percentage = $('#span-listing #west_percentage').text().replace("%", "")!=''?$('#span-listing #west_percentage').text().replace("%", ""):0;

var pan_percentage = parseInt(south_percentage) + parseInt(north_percentage) + parseInt(central_percentage) + parseInt(east_percentage) + parseInt(west_percentage);
var regCount=0;

var southSize = '{{isset($southCount)?$southCount:''}}';
var northSize = '{{isset($northCount)?$northCount:''}}';
var centralSize = '{{isset($centralCount)?$centralCount:''}}';
var eastSize = '{{isset($eastCount)?$eastCount:''}}';
var westSize = '{{isset($westCount)?$westCount:''}}';
			if(southSize!='' && northSize!='' && centralSize!='' && eastSize!='' && westSize!=''){
				regCount = 5;
			}else if(southSize!='' && northSize!='' && centralSize!='' && eastSize!='' && westSize==''){
				regCount = 4;
			}else if(southSize!='' && northSize!='' && centralSize!='' && eastSize=='' && westSize!=''){
				regCount = 4;
			}else if(southSize!='' && northSize!='' && centralSize=='' && eastSize!='' && westSize!=''){
				regCount = 4;
			}else if(southSize!='' && northSize=='' && centralSize!='' && eastSize!='' && westSize!=''){
				regCount = 4;
			}else if(southSize=='' && northSize!='' && centralSize!='' && eastSize!='' && westSize!=''){
				regCount = 4;
			}else if(southSize!='' && northSize!='' && centralSize!='' && eastSize=='' && westSize==''){
				regCount = 3;
			}else if(southSize!='' && northSize=='' && centralSize=='' && eastSize!='' && westSize!=''){
				regCount = 3;
			}else if(southSize=='' && northSize=='' && centralSize!='' && eastSize!='' && westSize!=''){
				regCount = 3;
			}else if(southSize=='' && northSize!='' && centralSize!='' && eastSize!='' && westSize==''){
				regCount = 3;
			}else if(southSize=='' && northSize!='' && centralSize!='' && eastSize!='' && westSize==''){
				regCount = 3;
			}else if(southSize=='' && northSize!='' && centralSize=='' && eastSize!='' && westSize!=''){
				regCount = 3;
			}else if(southSize=='' && northSize!='' && centralSize!='' && eastSize=='' && westSize!=''){
				regCount = 3;
			}else if(southSize!='' && northSize!='' && centralSize=='' && eastSize=='' && westSize==''){
				regCount = 2;
			}else if(southSize!='' && northSize=='' && centralSize=='' && eastSize=='' && westSize==''){
				regCount = 1;
			}else if(southSize=='' && northSize!='' && centralSize=='' && eastSize=='' && westSize==''){
				regCount = 1;
			}else if(southSize=='' && northSize=='' && centralSize!='' && eastSize=='' && westSize==''){
				regCount = 1;
			}else if(southSize=='' && northSize=='' && centralSize=='' && eastSize!='' && westSize==''){
				regCount = 1;
			}else if(southSize=='' && northSize=='' && centralSize=='' && eastSize=='' && westSize!=''){
				regCount = 1;
			}
			var pantotalCompletedSurvey = '{{isset($pantotalCompletedSurvey)?$pantotalCompletedSurvey:''}}';
			var panTotal_Rating_Count = '{{isset($panTotal_Rating_Count)?$panTotal_Rating_Count:''}}';
			var panClosed_WithinSLA_PERCENTAGE = '{{isset($panClosed_WithinSLA_PERCENTAGE)?$panClosed_WithinSLA_PERCENTAGE:''}}';
			var panClosed_OutSideSLA_PERCENTAGE = '{{isset($panClosed_OutSideSLA_PERCENTAGE)?$panClosed_OutSideSLA_PERCENTAGE:''}}';
			var panTotalLogged = '{{isset($panTotalLogged)?$panTotalLogged:''}}';
			var panACK_SLA_PERCENTAGE = '{{isset($panACK_SLA_PERCENTAGE)?$panACK_SLA_PERCENTAGE:''}}';
			var panReOpen_PERCENTAGE = '{{isset($panReOpen_PERCENTAGE)?$panReOpen_PERCENTAGE:''}}';


			var pan_pcs = (pantotalCompletedSurvey/panTotal_Rating_Count)*100;
			var panClosed_WithinSLA_PERCENTAGE = (panClosed_WithinSLA_PERCENTAGE/panTotalLogged)*100;
			var panClosed_OutSideSLA_PERCENTAGE = (panClosed_OutSideSLA_PERCENTAGE/panTotalLogged)*100;
			var panACK_SLA_PERCENTAGE = (panACK_SLA_PERCENTAGE/panTotalLogged)*100;
			var panReOpen_PERCENTAGE = (panReOpen_PERCENTAGE/panTotalLogged)*100;

			$('#span-listing #pan_logged').text(total_logged);
			$('#span-listing #pan_ratingcount').text(pan_ratingcount);
			$('#span-listing #pan_openwithinsla').text(pan_openwithinsla);
			$('#span-listing #pan_openoutsidesla').text(pan_openoutsidesla);
			$('#span-listing #pan_ack').text(Math.round(panACK_SLA_PERCENTAGE)+'%');
			$('#span-listing #pan_closedwithinsla').text(Math.round(panClosed_WithinSLA_PERCENTAGE)+'%');
			$('#span-listing #pan_closedoutsidesla').text(Math.round(panClosed_OutSideSLA_PERCENTAGE)+'%');
			$('#span-listing #pan_pcs').text(Math.round(pan_pcs)+'%');
			$('#span-listing #pan_percentage').text(Math.round(panReOpen_PERCENTAGE)+'%');
			var table =  $('#span-listing').DataTable({
				dom: 'Bfrtip',
				fixedHeader: true,
				"scrollY": 400,
				"scrollX": true,
				"bSort": false,
				"pageLength": 10,
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
						filename: 'Customer Complaints - Dealer Summary',
						exportOptions: { modifier: { page: 'all'} },

					},
					{
						extend: 'pdfHtml5',
						title: 'Customer Complaints - Dealer Summary',
						customize: function(doc) {
							age = table.column(1).data().toArray();
							if(westSize !='' && southSize !='' && northSize !='' && eastSize !='' && centralSize !=''){
								var central = parseInt(centralSize)+1;
								var east = parseInt(eastSize) + parseInt(central)+1;
								var north = parseInt(northSize)+parseInt(east)+1;
								var south = parseInt(southSize) + parseInt(north)+1;
								var west = parseInt(westSize)+parseInt(south)+1;
								
								doc.content[1].table.body[central][0].fillColor  ='#919296';
								doc.content[1].table.body[central][1].fillColor = '#919296';
								doc.content[1].table.body[central][2].fillColor = '#919296';
								doc.content[1].table.body[central][3].fillColor = '#919296';
								doc.content[1].table.body[central][4].fillColor = '#919296';
								doc.content[1].table.body[central][5].fillColor = '#919296';
								doc.content[1].table.body[central][6].fillColor = '#919296';
								doc.content[1].table.body[central][7].fillColor = '#919296';
								doc.content[1].table.body[central][8].fillColor = '#919296';
								doc.content[1].table.body[central][9].fillColor = '#919296';
								doc.content[1].table.body[central][10].fillColor = '#919296';
								
								doc.content[1].table.body[east][0].fillColor  ='#919296';
								doc.content[1].table.body[east][1].fillColor = '#919296';
								doc.content[1].table.body[east][2].fillColor = '#919296';
								doc.content[1].table.body[east][3].fillColor = '#919296';
								doc.content[1].table.body[east][4].fillColor = '#919296';
								doc.content[1].table.body[east][5].fillColor = '#919296';
								doc.content[1].table.body[east][6].fillColor = '#919296';
								doc.content[1].table.body[east][7].fillColor = '#919296';
								doc.content[1].table.body[east][8].fillColor = '#919296';
								doc.content[1].table.body[east][9].fillColor = '#919296';
								doc.content[1].table.body[east][10].fillColor = '#919296';
								
								doc.content[1].table.body[north][0].fillColor  ='#919296';
								doc.content[1].table.body[north][1].fillColor = '#919296';
								doc.content[1].table.body[north][2].fillColor = '#919296';
								doc.content[1].table.body[north][3].fillColor = '#919296';
								doc.content[1].table.body[north][4].fillColor = '#919296';
								doc.content[1].table.body[north][5].fillColor = '#919296';
								doc.content[1].table.body[north][6].fillColor = '#919296';
								doc.content[1].table.body[north][7].fillColor = '#919296';
								doc.content[1].table.body[north][8].fillColor = '#919296';
								doc.content[1].table.body[north][9].fillColor = '#919296';
								doc.content[1].table.body[north][10].fillColor = '#919296';
								
								doc.content[1].table.body[south][0].fillColor  ='#919296';
								doc.content[1].table.body[south][1].fillColor = '#919296';
								doc.content[1].table.body[south][2].fillColor = '#919296';
								doc.content[1].table.body[south][3].fillColor = '#919296';
								doc.content[1].table.body[south][4].fillColor = '#919296';
								doc.content[1].table.body[south][5].fillColor = '#919296';
								doc.content[1].table.body[south][6].fillColor = '#919296';
								doc.content[1].table.body[south][7].fillColor = '#919296';
								doc.content[1].table.body[south][8].fillColor = '#919296';
								doc.content[1].table.body[south][9].fillColor = '#919296';
								doc.content[1].table.body[south][10].fillColor = '#919296';
								
								doc.content[1].table.body[west][0].fillColor  ='#919296';
								doc.content[1].table.body[west][1].fillColor = '#919296';
								doc.content[1].table.body[west][2].fillColor = '#919296';
								doc.content[1].table.body[west][3].fillColor = '#919296';
								doc.content[1].table.body[west][4].fillColor = '#919296';
								doc.content[1].table.body[west][5].fillColor = '#919296';
								doc.content[1].table.body[west][6].fillColor = '#919296';
								doc.content[1].table.body[west][7].fillColor = '#919296';
								doc.content[1].table.body[west][8].fillColor = '#919296';
								doc.content[1].table.body[west][9].fillColor = '#919296';
								doc.content[1].table.body[west][10].fillColor = '#919296';
								
								doc.content[1].table.body[west+1][0].fillColor  ='#d8d7d5';
								doc.content[1].table.body[west+1][1].fillColor = '#d8d7d5';
								doc.content[1].table.body[west+1][2].fillColor = '#d8d7d5';
								doc.content[1].table.body[west+1][3].fillColor = '#d8d7d5';
								doc.content[1].table.body[west+1][4].fillColor = '#d8d7d5';
								doc.content[1].table.body[west+1][5].fillColor = '#d8d7d5';
								doc.content[1].table.body[west+1][6].fillColor = '#d8d7d5';
								doc.content[1].table.body[west+1][7].fillColor = '#d8d7d5';
								doc.content[1].table.body[west+1][8].fillColor = '#d8d7d5';
								doc.content[1].table.body[west+1][9].fillColor = '#d8d7d5';
								doc.content[1].table.body[west+1][10].fillColor = '#d8d7d5';
								
								doc.content[1].table.body[west+3][0].fillColor  ='#919296';
								doc.content[1].table.body[west+3][1].fillColor = '#919296';
								doc.content[1].table.body[west+3][2].fillColor = '#919296';
								doc.content[1].table.body[west+3][3].fillColor = '#919296';
								doc.content[1].table.body[west+3][4].fillColor = '#919296';
								
							}
							
							
							doc.styles.tableBodyEven = {
								alignment: 'center',
								border:'1px splid'
							}
							doc.styles.tableBodyOdd = {
								alignment: 'center'
							}

						},
						orientation : 'landscape',

					}
					],

			});
		});
	</script>
@endsection
