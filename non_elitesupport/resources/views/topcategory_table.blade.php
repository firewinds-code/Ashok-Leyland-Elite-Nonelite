<div style="background:#fff;babox-shadow: 0 7px 13px -6px black;border-radius: 4px;margin-top:10px;width: 100%;padding: 7px;">		 
	<div class="table-responsive">	
	 <table id="order-listing1" class="table" >
		<thead>
			<tr>
				<th style="color: #1c273c; font-size: 14px;">Category</th>
				<th style="text-align: center;color: #1c273c; font-size: 14px;">Count</th>
				<th style="text-align: center;color: #1c273c; font-size: 14px;">Trend</th>
				
			</tr>
		</thead>
		<tbody>
			@isset($tabelQuery)
				
				@foreach ($tabelQuery as $row)
					<tr>
						<td class="cls_zone">{{$row->sub_complaint_type}}</td>
						<td class="cls_state" style="text-align: center">{{$row->Current_Quarter}}</td>
						@php
				$trendSum =$row->Current_Quarter+$row->Previous_Quarter+$row->Previous_Previous_Quarter+$row->Previous_Previous_Previous_Quarter;
						$trendAvg = $trendSum/3;
						$trendPercentage = $trendAvg *5/100;
						$crntQtrPlus = $row->Current_Quarter + $trendPercentage;
						$crntQtrMinus = $row->Current_Quarter - $trendPercentage;
						  @endphp
						<td class="cls_state" style="text-align: center">
							@if($trendAvg > $crntQtrPlus )
								<img src="{{asset('images/up.png')}}" alt="logo" style="width: 18px;height: 18px;"/>
							@elseif($trendAvg <= $crntQtrMinus)
								<img src="{{asset('images/down.png')}}" alt="logo" style="width: 18px;height: 18px;"/>
							@elseif($trendAvg < $crntQtrPlus && $trendAvg >= $crntQtrMinus)
								<img src="{{asset('images/middle.png')}}" alt="logo" style="width: 18px;height: 18px;"/>
							@endif
						</td>
						
					</tr>
				@endforeach
			@endisset
		</tbody>
	</table>
	</div>
</div>