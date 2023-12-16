@extends("layouts.masterlayout")
@section('title','PSF Update')
@section('bodycontent')
<style>
	/* The Modal (background) */
	.modal {
	  display: none; /* Hidden by default */
	  position: fixed; /* Stay in place */
	  z-index: 1; /* Sit on top */
	  padding-top: 100px; /* Location of the box */
	  left: 0;
	  top: 0;
	  width: 100%; /* Full width */
	  height: 100%; /* Full height */
	  overflow: auto; /* Enable scroll if needed */
	  background-color: rgb(0,0,0); /* Fallback color */
	  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
	}

	/* Modal Content */
	.modal-content {
	  background-color: #fefefe;
	  margin: auto;
	  padding: 20px;
	  border: 1px solid #888;
	  width: 80%;
	}

	/* The Close Button */
	.close {
	  color: #aaaaaa;
	  float: right;
	  font-size: 28px;
	  font-weight: bold;
	}

	.close:hover,
	.close:focus {
	  color: #000;
	  text-decoration: none;
	  cursor: pointer;
	}
</style>

	<div class="content-wrapper mobcss">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">PSF</h4>
                <div class="row" >
                    <div class="col-md-12" style="border: 1px solid #ccc">
                    @if ($notification = Session::get('success'))
                        <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{ $notification }}</strong>
                        </div>
                     @endif
                     @if ($notification = Session::get('error'))
                        <div class="alert alert-danger alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{ $notification }}</strong>
                        </div>
                    @endif
                        <div class="ribbon">Questions</div>
						  <form name="myForm" id="myForm" method="post" enctype="multipart/form-data" action="{{url('psf-query-update')}}" onsubmit="return formSubmitPSF()">
                              <input type="hidden" value="{{ $record->id }}" name="psf_info_id">
                              <input type="hidden" value="{{ csrf_token() }}" name="_token">
                              {{--  {{ dd($record) }}  --}}
                            @php $i = 1; @endphp
                              @foreach($questions as $list)
                            @php
                                $fieldName = 'q'.$list->id.'_ans';
                                $qName = 'q'.$list->id;

                            @endphp
                            @if(($record->psf_call_type == 'PSF WO Estimate Question' && $list->id == 3) || ($record->psf_call_type == 'PSF WO Estimate Question' && $list->id == 4))

                            @continue
                            @else
							<div class="row">
								<div class="form-group col-md-8">
			                        <label for="datefrom" >Question {{ $i++ }}</label>
									<span style="color: red;">*</span><br />
                                    <span><b>{{ $list->question }}</b></span>
									<input type="hidden" name="{{ $qName }}" id="{{ $qName }}" class="form-control"  placeholder="Registration Number"  value="{{ $list->id }}"  readonly />
			                        <span id="reg_number1_error" style="color:red"></span>
			                    </div>
                            @endif
                                @if($list->id > 4)

                                    <div class="form-group col-md-2">
                                        <label for="datefrom" >Answer {{-- {{ $i } --}}</label>
                                        <span style="color: red;">*</span>

                                        <select name="{{ $fieldName }}" id="{{ $fieldName }}"  @if($list->id == 5) onchange="rating(this.options[this.selectedIndex].value);" @endif class="form-control" {{ !empty($record->$fieldName)?"readonly":'' }}>
                                            @if(!empty($record->$fieldName))
                                            <option value="{{ $record->$fieldName }}">{{ $record->$fieldName }}</option></select>
                                            @else


                                        @if($list->id == 5)
                                        <option value="">--Select--</option>
                                        <option value="5" {{ $record->$fieldName =='1'?'selected':"" }}>5 - Exellent</option>
                                        <option value="4" {{ $record->$fieldName =='2'?'selected':"" }}>4 - Good</option>
                                        <option value="3" {{ $record->$fieldName =='3'?'selected':"" }}>3 - Average</option>
                                        <option value="2" {{ $record->$fieldName =='4'?'selected':"" }}>2 - Poor</option>
                                        <option value="1" {{ $record->$fieldName =='5'?'selected':"" }}>1 - Very Poor</option>
                                        </select>
                                        @else
                                        <option value="">--Select--</option>
                                        <option value="5" {{ $record->$fieldName =='1'?'selected':"" }}>5 - Strongly Recommend</option>
                                        <option value="4" {{ $record->$fieldName =='2'?'selected':"" }}>4 - Recommend</option>
                                        <option value="3" {{ $record->$fieldName =='3'?'selected':"" }}>3 - Not Sure</option>
                                        <option value="2" {{ $record->$fieldName =='4'?'selected':"" }}>2 - Not Recommend</option>
                                        <option value="1" {{ $record->$fieldName =='5'?'selected':"" }}>1 - Strongly Not Recommend</option>
                                        </select>
                                        @endif

                                        @endif

                                     </div>
                                @if($list->id == 5)
                                    <div class="form-group col-md-2" id="ratingdiv">
                                    <label for="datefrom" id="lable{{ $fieldName }}"></label>
                                       <select name="rating{{ $fieldName }}[]" multiple id="rating{{ $fieldName }}" onchange="ratingother(this.options[this.selectedIndex].value);" class="form-control" {{ !empty($record->$fieldName)?"readonly":'' }}>
                                        <option value="">--Select--</option>
                                        </select><br>
                                        <input type="text" name="other_remarks" id="other_remarks" class="form-control" value="{{ $record->low_rating_remarks  }}"  {{ !empty($record->low_rating_remarks)?"readonly":'' }} placeholder="other remarks" />
                                    </div>
                                    @endif
                                    @if(!empty($record->$fieldName))
                                        <input type="hidden" name="{{ $fieldName }}" value="{{ $record->$fieldName }}" />
                                    @endif

                                @else
                                    <div class="form-group col-md-4">
                                        <label for="datefrom" >Answer {{-- {{ $i }} --}}</label>
                                        <span style="color: red;">*</span>
                                        <select name="{{ $fieldName }}" id="{{ $fieldName }}" class="form-control" {{ !empty($record->$fieldName)?"readonly":'' }}>
                                           @if(!empty($record->$fieldName))
                                           <option value="{{ $record->$fieldName }}">{{ $record->$fieldName }}</option>
                                           @else
                                            <option value="">--Select--</option>
                                        <option value="Yes" {{ $record->$fieldName =='Yes'?'selected':"" }}>Yes</option>
                                        <option value="No" {{ $record->$fieldName =='No'?'selected':"" }}>No</option>
                                        @endif
                                        </select>
                                    </div>
                                    @if(!empty($record->$fieldName))
                                        <input type="hidden" name="{{ $fieldName }}" value="{{ $record->$fieldName }}" />
                                    @endif
                                @endif
                           </div>
                           @endforeach
                           <div class="row">
                            <div class="form-group col-md-8">
                            <label class="form-label" for="textAreaExample">Remarks</label>
                            <span style="color: red;">*</span>
                            <textarea class="form-control" id="remarks" name="remarks" rows="4"  {{ !empty($record->feedback_given_by)?"":'required' }}>{{ $record->remarks }}</textarea>
                            </div>
                           </div>


                           <div class="ribbon">Feedback & Complaint Action</div>
                            <div class="row">
                                @if($record->status != '')
                                    <div class="form-group col-md-3">
                                        <label for="datefrom" >Status</label>
                                        <span style="color: red;">*</span>
                                        <select name="status" class="form-control" {{ !empty($record->status)?"readonly":'required' }}>
                                            @if($record->status == 'Closed')
                                            <option value="Closed"  >{{ $record->status }}</option>
                                            @else
                                            <option value="Pending" {{ $record->status =='Pending'?'selected':"" }} >Pending</option>
                                            {{-- <option value="Resolved" {{ $record->status =='Resolved'?'selected':"" }} >Resolved</option> --}}
                                            <option value="Dealer Responded-Customer Verification Pending" {{ $record->status =='Dealer Responded-Customer Verification Pending'?'selected':"" }} >Dealer Responded-Customer Verification Pending</option>
                                            <option value="ReOpen" {{ $record->status =='ReOpen'?'selected':"" }} >ReOpen</option>
                                            <option value="Closed" {{ $record->status =='Closed'?'selected':"" }} >Closed</option>
                                            @endif
                                        </select>
                                        <span id="chassis_number1_error" style="color:red"></span>
                                    </div>
                                @endif

                                <div class="form-group col-md-3">
			                        <label for="datefrom" >Feedback Status</label>
									<span style="color: red;">*</span>
                                    <select name="feedback_status" class="form-control"  {{ !empty($record->feedback_status)?"readonly":'required' }}>
                                        @if(!empty($record->feedback_status))
                                             <option value="{{ $record->feedback_status }}">{{ $record->feedback_status }}</option>
                                        @else
                                        <option value="">--Please Select--</option>
                                           <option value="Partial Feedback" {{ $record->feedback_status =='Partial Feedback'?'selected':"" }} >Partial Feedback</option>
                                           <option value="Complete Feedback" {{ $record->feedback_status =='Complete Feedback'?'selected':"" }} >Complete Feedback</option>
                                           {{--  <option value="ReOpen" {{ $record->status =='ReOpen'?'selected':"" }} >ReOpen</option>
                                           <option value="Closed" {{ $record->status =='Closed'?'selected':"" }} >Closed</option>  --}}
                                           @endif
                                        </select>
                                    <span id="chassis_number1_error" style="color:red"></span>
			                    </div>
                                <div class="form-group col-md-3">
			                        <label for="datefrom" >Customer Name</label>
									<span style="color: red;">*</span>
									<input type="text" name="Customer_name" id="Customer_name" class="form-control"  readonly value="{{ $record->Customer_name }}" placeholder="Customer Name"/>
			                        <span id="engine_number1_error" style="color:red"></span>
			                    </div>
								<div class="form-group col-md-3" style="display:none">
			                        <label for="datefrom" >Customer Number</label>
									<span style="color: red;">*</span>
									<input type="text" name="customer_number" id="customer_number" class="form-control"  readonly value="{{ $record->Customer_number }}" placeholder="Customer Number"/>
			                        <span id="engine_number1_error" style="color:red"></span>
			                    </div>
                                <div class="form-group col-md-3">
			                        <label for="datefrom" >Feedback Received Number</label>
                                    <span style="color: red;">*</span>
									<input type="number" name="followup_number" id="followup_number" class="form-control"  placeholder="Feedback Received Number"  value="{{ $record->followup_number }}"  required/>
			                        <span id="engine_number1_error" style="color:red"></span>
			                    </div>
								
								<div class="form-group col-md-3">
			                        <label for="datefrom" >SAC CODE</label>
									<span style="color: red;">*</span>
									<input type="text" name="sac_code" id="sac_code" class="form-control" value="{{ $record->SAC_code }}" placeholder="SAC Code" readonly required/>

			                    </div>
                            </div>
                            <div class="row">

								{{--  <div class="form-group col-md-4">
			                        <label for="datefrom" >Disposition</label>
									<span style="color: red;">*</span>
									<select name="disposition" id="disposition" class="form-control" onchange="addSubDis(this.value,'')" >
										<option value="">--Select--</option>
										<option value="Connected" {{ $record->disposition =='Connected'?'selected':"" }}>Connected</option>
										{{--  <option value="Not Connected" {{ $record->disposition =='Not Connected'?'selected':"" }}>Not Connected</option>
									</select>
			                    </div>
								<div class="form-group col-md-4">
			                        <label for="datefrom" >Sub Disposition</label>
									<span style="color: red;">*</span>
									<select name="sub_disposition" id="sub_disposition"  class="form-control" >
										<option value="">--Select--</option>

									</select>
			                    </div> --}}

                                <div class="form-group col-md-4">
			                        <label for="datefrom" >Feedback Given By</label>
									<span style="color: red;">*</span>
									<select name="feedback_given_by" id="feedback_given_by"  class="form-control" {{ !empty($record->feedback_given_by)?"readonly":'required' }} >
                                        @if(!empty($record->feedback_given_by))
                                        <option value="{{ $record->feedback_given_by }}">{{ $record->feedback_given_by }}</option>
                                        @else
										<option value="">--Please Select--</option>
                                        <option value="Owner" {{ $record->feedback_given_by =='Owner'?'selected':"" }}>Owner</option>
                                        <option value="Driver" {{ $record->feedback_given_by =='Driver'?'selected':"" }}>Driver</option>
                                        <option value="Fleet Manager" {{ $record->feedback_given_by =='Fleet Manager'?'selected':"" }}>Fleet Manager</option>
                                        <option value="Service Contact" {{ $record->feedback_given_by =='Service Contact'?'selected':"" }}>Service Contact</option>
                                        <option value="Owner Cum Driver" {{ $record->feedback_given_by =='Owner Cum Driver'?'selected':"" }}>Owner Cum Driver</option>
                                        @endif
                                   </select>

							    </div>
                                <div class="form-group col-md-4">
                                    <label for="datefrom" >Dealer Remarks</label>
                                    <span style="color: red;">*</span>
                                    <textarea name="dealer_remarks" id="dealer_remarks" class="form-control" readonly>{{ $record->dealer_remarks }}</textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="row" style="margin-top: 15px;">
                                    <div class="container-fluid">
                                        <div class="col-sm-12 text-center">

                                            <input type="submit"class="btn btn-primary rounded"  @isset($record->feedback_status) @if(($record->complaint_no == '' || $record->complaint_no == NULL)
                                            || ($record->status =='Closed')) @disabled(true) @endif @endisset name="submit" id="submit" value="Submit" />
                                        </div>
                                    </div>
                                </div>
                            </div>
						</form><br />

                </div>
            </div>
        </div>
    </div>
    </div>
    <script>
        $(document).ready(function () {

            $("#followup_number").keyup(function(){
                const lnth = this.value;
                const getlenth = lnth.length;
                if(getlenth >10){
                    alert("Followup number should be Less than 10");
                    $('#submit').prop('disabled',true);
                    $('#followup_number').val('');
                }else{
                    $('#submit').prop('disabled',false);
                }

            });
            var disVal = "{{$record->disposition}}";

            var sdisVal = "{{$record->sub_disposition}}";
            addSubDis(disVal,sdisVal);
         });
    </script>
    <script>


        function formSubmitPSF(){
            var disposition = $("#disposition").val();
            var sub_disposition = $("#sub_disposition").val();
            if(disposition ==''){
                alert("Disposition is Manadatory");
                $("#disposition").focus();
                return false;
            }
            if(sub_disposition ==''){
                alert("Sub Disposition is Manadatory");
                $("#sub_disposition").focus();
                return false;
            }
            return true;
        }
        function addSubDis(val,subdis){
            if(val == 'Connected')
            {
                var html ='<option value="Call Back">Call Back</option><option value="Feedback Given">Feedback Given</option>';
                $('#sub_disposition').html(html);
            }
            if(val == 'Not Connected')
            {
                if(subdis !=''){
                    if(subdis =='Switch Off'){
                        var html = '<option value="Switch Off" selected>Switch Off</option><option value="Busy">Busy</option><option value="Out Of Network">Out of Network</option>';
                    }else if(subdis =='Busy'){
                        var html = '<option value="Switch Off">Switch Off</option><option value="Busy" Selected>Busy</option><option value="Out Of Network">Out of Network</option>';
                    }else if(subdis =='Out Of Network'){
                        var html = '<option value="Switch Off">Switch Off</option><option value="Busy">Busy</option><option value="Out Of Network" selected>Out of Network</option>';
                    }
                }else{
                    var html = '<option value="Switch Off">Switch Off</option><option value="Busy">Busy</option><option value="Out Of Network">Out of Network</option>';
                }


                $('#sub_disposition').html(html);
            }
        }

    function rating(val)
    {

            var html = '<option value=" High Cost Of Service"> High Cost Of Service</option><option value="Poor Repair Quality">Poor Repair Quality</option><option value="Not Treated Well">Not Treated Well</option><option value="SA Behavior">SA Behavior</option><option value="Parts Not Available">Parts Not Available</option><option value="Warranty Or Amc Not Honored">Warranty Or Amc Not Honored</option><option value="Washing Not Done">Washing Not Done</option><option value="High Waiting Time">High Waiting Time</option> <option value="Nearest Workshop Not Available">Nearest Workshop Not Available</option><option value="Unskilled Manpower">Unskilled Manpower</option><option value="No Manpower">No Manpower</option><option value="Documentation Delay">Documentation Delay</option><option value="Delay In Service">Delay In Service</option><option value="Old Parts Not Returned">Old Parts Not Returned</option><option value="Others">Others</option>';


           if(parseInt(val) >= 4)
           {
            $('#ratingq5_ans').html('');
            $('#ratingdiv').hide();
            $('#ratingq5_ans').attr('required', false);

           }else{
            $('#ratingdiv').show();
            $('#lableq5_ans').text('Reason Of Low Rating');
            $('#ratingq5_ans').html(html);
            $('#ratingq5_ans').attr('required', true);
          }
   }

   function ratingother(val)
   {

              if(val == 'Others')
              {
                $('#other_remarks').show();
                $('#other_remarks').attr('required', true);
              }
              else{
                $('#other_remarks').hide();
                $('#other_remarks').attr('required', false);
              }

   }

$('#ratingq6_ans').hide('');
  var low_rating =  '{{ $record->reason_of_low_rating }}';
  var low_rating_remarks =  '{{ $record->low_rating_remarks }}';
   if(low_rating != '')
   {
    var low_ratingArr =low_rating.split(',');
    var html = '';
    for(var i=0; i< low_ratingArr.length;i++){
         html += '<option value="'+low_ratingArr[i]+'">'+low_ratingArr[i]+'</option>';
    }
    $('#lableq5_ans').text('Reason Of Low Rating');
    var html = '<option value="'+low_rating+'">'+low_rating+'</option>';
    $('#ratingdiv').show();
    $('#ratingq5_ans').html(html);
  


   }
   else{

    $('#ratingdiv').hide();

   }
   if(low_rating_remarks != '')
   {
    $('#other_remarks').show();
   }
   else{
    $('#other_remarks').hide();
   }

  </script>
 @endsection

