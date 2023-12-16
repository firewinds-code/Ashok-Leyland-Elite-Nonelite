@extends("layouts.masterlayout")
@section('title','Complete Followup')
@section('bodycontent')
	<style>
		.summary {
			display: none;
			border: 1px solid #ccc;
			margin: 7px 0;
			background: #ccc;
			border-radius: 15px;
		}

		.slide {
			cursor: pointer;
		}

		.slide {
			list-style-type: none;
		}

	</style>
	<div class="content-wrapper mobcss">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Complete Followup</h4>
                <div class="row">
					<div class="col-md-4">
						<a href="{{url('cogent-assign')}}">Assign Followup({{$totalCountAssign}})</a>
					</div>
					<div class="col-md-4">
						<a href="{{url('cogent-dealer')}}">Dealer Followup({{$totalCountDealer}})</a>
					</div>
					<div class="col-md-4">
						<a href="{{url('cogent-complete')}}">Complete Followup({{$totalCountComplete}})</a>
					</div>
					<div class="col-md-6 slide" id="daydiv" style="text-align: center;padding: 5px 0;border-radius: 15px;cursor: pointer;">
						<div class="row">
							<div class="col-md-6">
								<span>24/7({{$count['dayCount']}})</span>
								<div class="summary"  id="fresh" style="display: none">
									Fresh({{$count['dayFreshCount']}})
								</div>				
							</div>
							<div class="col-md-6">
								<span>&nbsp;</span>
								<div class="summary" id="freshfollowup" style="display: none">
									Followup({{$count['dayFollowupCount']}})
								</div>				
							</div>					
						</div>
						<div id="freshLangDiv">
						</div>
						<div id="freshfoloowupLangDiv">
						</div>
					</div>
					
					<div class="col-md-6" id="shiftdiv" style="text-align: center;padding: 5px 0;border-radius: 15px;cursor: pointer;">
						<div class="row">
							<div class="col-md-6">
								<span>Day({{$count['fullDayCount']}})</span>
								<div class="summary" id="shiftfresh" style="display: none;">
									Fresh({{$count['fullDayFreshCount']}})
								</div>
							</div>
							<div class="col-md-6">
								<span>&nbsp;</span>
								<div class="summary" id="shiftfollowup" style="display: none">
									Followup({{$count['fullDayFollowupCount']}})
								</div>				
							</div>					
						</div>
						<div id="shiftLangDiv">
						</div>
						<div id="shiftfoloowupLangDiv">
						</div>
						{{-- <div class="row" style="display:none;" id="followuplang">
							<div class="col-md-2" onclick="getFollowupForm('Hindi')">
								<span>Hindi</span>
							</div>
							<div class="col-md-2" onclick="getFollowupForm('English')">
								<span>English</span>
							</div>
							<div class="col-md-2" onclick="getFollowupForm('Malayalam')">
								<span>Malayalam</span>
							</div>
							<div class="col-md-2" onclick="getFollowupForm('Kannad')">
								<span>Kannad</span>
							</div>
							<div class="col-md-2" onclick="getFollowupForm('Tamil')">
								<span>Tamil</span>
							</div>
							<div class="col-md-2" onclick="getFollowupForm('Telugu')">
								<span>Telugu</span>
							</div>
						</div> --}}
						<div class="row" style="display:none;" id="followupform">
							{{-- <form name="myFollowup" method="post" enctype="multipart/form-data" action="{{url('store-area')}}">
								<input type="hidden" name="_token" value="{{csrf_token()}}">
								<input  type="hidden" name="dataid" id="dataid"/>                        
								<div class="row">								
									<div class="form-group col-md-3">
										<label for="complaint_number">Complaint Number</label> <span style="color: red;">*</span>
										<input type="text" name="complaint_number" id="complaint_number" class="form-control" value="" readonly />
									</div>
									
									<div class="form-group col-md-3">
										<label for="language">Language</label> <span style="color: red;">*</span>
										<input type="text" name="language" id="language" class="form-control" value="" readonly />
									</div>
									
									<div class="form-group col-md-3">
										<label for="Disposition">Disposition</label>
										<select name="disposition" id="disposition" tabindex="1" class="form-control">
											<optgroup>
												<option Value="">--select--</option>
												<option Value="Contacted">Contacted</option>
												<option Value="Non-Contacted">Non-Contacted</option>
											</optgroup>
										</select>	
									</div> 	                                                        
									<div class="form-group col-md-3">
										<label for="sub_disposition">Sub-Disposition</label>
										<select name="sub_disposition" id="sub_disposition" tabindex="1" class="form-control">
											<optgroup>
												<option Value="">--select--</option>
												<option Value="A">A</option>
												<option Value="B">B</option>
											</optgroup>
										</select>	
									</div> 	                                                        
								</div>
								<div class="box-footer">
									<span class="pull-right">									
									<input type="submit"name="submit" id="submit" value="Submit" class="btn-secondary">
									</span>
								</div>
							</form> --}}
						</div>
					</div>				
				</div>					
                </div>
            </div>
        </div>
    </div>
	<script>
		$(document).ready(function() {
			$("#daydiv").click(function(){
				$("#fresh").show();
				$("#freshfollowup").show();
				$("#followup").hide();
				$("#followuplang").hide();
				$('#followupform').hide();
				$('#shiftfresh').hide();
				$('#shiftfollowup').hide();
				$("#freshfoloowupLangDiv").show();
				$("#shiftLangDiv").hide();
				$('#freshLangDiv').show();
				$('#shiftfoloowupLangDiv').hide();
				
			});
			$("#shiftdiv").click(function(){
				$("#fresh").hide();
				$("#freshfollowup").hide();
				// $("#followup").show();
				//$("#freshlang").hide();
				//$('#freshform').hide();
				$('#shiftfresh').show();
				$('#shiftfollowup').show();
				$('#freshLangDiv').hide();
				$("#freshfoloowupLangDiv").hide();
			});
			$("#fresh").click(function(){
				$('#freshfoloowupLangDiv').html('');
				$('#freshLangDiv').html('');
				$("#freshfoloowupLangDiv").hide();
				$("#freshLangDiv").show();
				console.log("Call Fresh");
				let typeData ="Fresh";
				
				@if($count['dayFreshCount']!='0')
				$.ajax({
					url: "{{route('cogent-complete-day-fresh')}}",
					type: 'post',
					data: {
					"_token": "{{ csrf_token() }}",
					type1: "day",
					type2: "Fresh",
					},
					success: function(response) {
						//console.log(response.status);
						if (response.status == 'success') {
							//toastr.success('Freash Data Found!')
							$('#freshfoloowupLangDiv').html('');
							$('#freshLangDiv').html(response.html);
						} else if(response.status == 'no') {
							
							toastr.error(response.html);
						}else{
							toastr.error('Something Went Wrong!');
						}
					}
				});
				@endif 
				/*$("#freshLangDiv").html();*/
			});
			$("#freshfollowup").click(function(){
				$('#freshfoloowupLangDiv').html('');
				$('#freshLangDiv').html('');
				$("#freshLangDiv").show();
				$("#freshfoloowupLangDiv").show();
				console.log("Call Fresh");
				let s_id ="Followup";
				@if($count['dayFollowupCount']!='0')
				$.ajax({
					url: "{{route('cogent-complete-day-fresh')}}",
					type: 'post',
					data: {
					"_token": "{{ csrf_token() }}",
					type1: "day",
					type2: "Followup",
					},
					success: function(response) {
						//console.log(response.status);
						if (response.status == 'success') {
							//toastr.success('Follow up Data Found!')
							$('#freshLangDiv').html('');
							$('#freshfoloowupLangDiv').html(response.html);
						} else {
							toastr.error('Something Went Wrong!');
						}
					}
				});
				@endif 
				/*$("#freshLangDiv").html();*/
			});

			/* Sift 24/7 */
			$("#shiftfresh").click(function(){
				$('#freshfoloowupLangDiv').html('');
				$('#freshLangDiv').html('');
				$("#shiftfoloowupLangDiv").hide();
				$("#shiftLangDiv").show();
				console.log("Call Fresh");
				let typeData ="Fresh";
				@if($count['fullDayFreshCount']!='0')
				$.ajax({
					url: "{{route('cogent-complete-day-fresh')}}",
					type: 'post',
					data: {
					"_token": "{{ csrf_token() }}",
					type1: "fullday",
					type2: "Fresh",
					},
					success: function(response) {
						//console.log(response.status);
						if (response.status == 'success') {
							//toastr.success('Freash Data Found!')
							$('#shiftfoloowupLangDiv').html('');
							$('#shiftLangDiv').html(response.html);
						} else if(response.status == 'no') {
							
							toastr.error(response.html);
						}else{
							toastr.error('Something Went Wrong!');
						}
					}
				});
				@endif  
				/*$("#freshLangDiv").html();*/
			});
			$("#shiftfollowup").click(function(){
				$('#freshfoloowupLangDiv').html('');
				$('#freshLangDiv').html('');
				$("#shiftLangDiv").hide();
				$("#shiftfoloowupLangDiv").show();
				console.log("Call Fresh");
				let s_id ="Followup";
				@if($count['fullDayFollowupCount']!='0')
				$.ajax({
					url: "{{route('cogent-complete-day-fresh')}}",
					type: 'post',
					data: {
					"_token": "{{ csrf_token() }}",
					type1: "fullday",
					type2: "Followup",
					},
					success: function(response) {
						//console.log(response.status);
						if (response.status == 'success') {
							//toastr.success('Follow up Data Found!')
							$('#shiftLangDiv').html('');
							$('#shiftfoloowupLangDiv').html(response.html);
						} else {
							toastr.error('Something Went Wrong!');
						}
					}
				});
				@endif 
				/*$("#freshLangDiv").html();*/
			});
			/* Sift 24/7 */
			
		});
		
	</script>
 
@endsection
