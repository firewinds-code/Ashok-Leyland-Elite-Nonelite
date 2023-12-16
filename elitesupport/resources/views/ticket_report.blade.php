@extends("layouts.masterlayout")
@section('title','Single Ticket Report')
@section('bodycontent')
<script></script>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script> --}}
	<div class="content-wrapper mobcss">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Single Ticket Report</h4>
                <div class="row">
                    <div class="col-md-12" style="margin-bottom: 18px;">
                        <form name="myForm" method="post" enctype="multipart/form-data" action="{{url('store-ticket-report')}}">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="row">
								<div class="form-group col-md-6">
                                    <label for="datefrom" >Complaint Number</label>
                                    <span style="color: red;">*</span>
                                    <input type="text" name="complaint_number_search" id="complaint_number_search" class="form-control" placeholder="Complaint Number" autocomplete="off" value="{{isset($complaint_number_search)?$complaint_number_search:''}}" required/>
                                    <div id="suggesstion-box"></div>
                                </div>
                                <div class="form-group col-md-6" style="position: relative;top: 15px;">
                                    <input type="submit"class="btn btn-primary rounded" name="submit" value="Submit" />
                                    <a href="#" onclick="getPDF()" class="btn btn-primary rounded">Generate PDF</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    @php
                        $divshows1 = '';
                        if(isset($vehicleId)){
                            $divshows1 ='true';
                        }else{
                            $divshows1 ='false';
                        }
                       
                    @endphp
                    <br>
                    @if($divshows1 == 'true')
                    <div id="editor"></div>
                    <div class="col-md-12" style="border: 1px solid #ccc;" id="ticketDataPDf">
                        <style>
                            table {border-collapse: collapse;}
                        </style>
                        <p style="color: red;font-size: 18px;padding: 8px;"><img style="padding: 0 30px;position: relative;top: 29px;" id="logo" src="{{asset('images/al-logo.svg')}}" alt="logo" width="243" height="50" /></p>
						<p style="text-align: center;color: red;font-size: 18px;padding: 8px;">Single Ticket Report: {{isset($complaint_number_search)?$complaint_number_search:''}} </p>
                        <table class="table">
                            <tr style="background: #ccc;">
                            <td colspan="6"><label style="text-decoration: underline;font-size: 16px;"><b>Ticket Info</b></label></td>
                            </tr>
                            <tr>
                                <td><b>Ticket Number</b></td>
                                <td>{{isset($complaint_number_search)?$complaint_number_search:''}}</td>
                                <td><b>Ticket Created By</b></td>
                                <td>{{$caseCreatedBy}}</td>
                                <td><b>Ticket Closed Date & Time</b></td>
                                <td>{{$closedDate}}</td>
                            </tr>
                            <tr>
                                <td><b>Ticket Status</b></td>
                                <td>{{$remark_type}}</td>
                                <td><b>Ticket Created Date</b></td>
                                <td>{{$ticketCreatedDate}}</td>
                                <td><b>Actual Response Date & Time</b></td>
                                <td>{{$actual_response_time}}</td>
                            </tr>
                            <tr>
                                <td><b>Restoration Date & Time</b></td>
                                <td>{{$tat_scheduled}}</td>
                            </tr>
                            <tr style="background: #ccc;">
                                <td colspan="6"><label style="text-decoration: underline;font-size: 16px;"><b>Vehicle Info</b></label></td>
                            </tr>
                            <tr>
                                <td><b>Chassis Number</b></td>
                                <td>{{$chassis_number}}</td>
                                <td><b>Vehicle Model</b></td>
                                <td>{{$vehicle_model}}</td>
                                <td><b>Purchase Date</b></td>
                                <td>{{$purchase_date}}</td>
                            </tr>
                            <tr>
                                <td><b>Engine Number</b></td>
                                <td>{{$engine_number}}</td>
                                <td><b>Vehicle Segment</b></td>
                                <td>{{$vehicle_segment}}</td>
                                <td><b>Add Blue Use</b></td>
                                <td>{{$add_blue_use}}</td>
                            </tr>
                            <tr>
                                <td><b>Register Number</b></td>
                                <td>{{$reg_number}}</td>
                                <td><b>Support Center Code - Name</b></td>
                                <td>{{$supportCenterCodeName}}</td>
                                <td><b>Engine Emmission Type</b></td>
                                <td>{{$engine_emmission_type}}</td>
                            </tr>
                            <tr style="background: #ccc;">
                                <td colspan="6"><label style="text-decoration: underline;font-size: 16px;"><b>Owner Info</b></label></td>
                            </tr>
                            <tr>
                                <td><b>Owner Name</b></td>
                                <td>{{$owner_name}}</td>
                                <td><b>Mobile Number</b></td>
                                <td>{{$owner_mob}}</td>
                                <td><b>Landline Number</b></td>
                                <td>{{$owner_landline}}</td>
                            </tr>
                            <tr>
                                <td><b>Owner Category</b></td>
                                <td>Select Customer</td>
                                <td><b>Campany Name</b></td>
                                <td>{{$owner_company}}</td>
                                <td><b>Contact Person</b></td>
                                <td>{{$contact_name}}</td>
                            </tr>
                            <tr>
                                <td><b>Phone Number</b></td>
                                <td>{{$mob}}</td>
                                
                            </tr>
                            <tr style="background: #ccc;">
                                <td colspan="6"><label style="text-decoration: underline;font-size: 16px;"><b>Ticket Breakdown Info</b></label></td>
                            </tr>
                            <tr>
                                <td><b>Caller Type</b></td>
                                <td>{{$caller_type}}</td>
                                <td><b>Location</b></td>
                                <td>{{$location}}</td>
                                <td><b>Vehicle Problem</b></td>
                                <td>{{$vehicle_problem}}</td>
                            </tr>
                            <tr>
                                <td><b>Caller Name</b></td>
                                <td>Select Customer</td>
                                <td><b>Caller Contact</b></td>
                                <td>{{$caller_contact}}</td>
                                <td><b>Breakdown City</b></td>
                                <td>{{$city}}</td>
                            </tr>
                            <tr>
                                <td><b>Breakdown District</b></td>
                                <td>{{$district}}</td>
                                <td><b>Breakdown State</b></td>
                                <td>{{$state}}</td>
                                <td><b>From Where</b></td>
                                <td>{{$from_where}}</td>
                            </tr>
                            <tr>
                                <td><b>To Where</b></td>
                                <td>{{$to_where}}</td>
                                <td><b>Aggregate</b></td>
                                <td>{{$aggregate}}</td>
                                <td><b>Highway</b></td>
                                <td>{{$highway}}</td>
                            </tr>
                            <tr>
                                <td><b>Is Vehicle Movable</b></td>
                                <td>{{$vehicle_movable}}</td>
                                <td><b>Vehicle Status</b></td>
                                <td>{{$vehicle_type}}</td>
                                <td><b>Time Since Ticket Created</b></td>
                                <td>{{$timeSinceTicketCreated}}</td>
                            </tr>
                        </table>
                        <table class="table">
                            <tr style="background: #ccc;">
                                <td colspan="6"><label style="text-decoration: underline;font-size: 16px;"><b>Ticket Breakdown Info</b></label></td>
                            </tr>
                            <tr>
                                <th><b>Assigned Date & Time</b></th>
                                <th><b>Support Code & Name</b></th>
                                <th><b>Contact Person</b></th>
                                <th><b>Contact Mobile</b></th>
                                <th><b>TSM Name</b></th>
                                <th><b>TSM Mobile</b></th>
                            </tr>
                            <tr>
                                <td>{{$ticketCreatedDate}}</td>
                                <td>{{$supportCenterCodeName}}</td>
                                <td>{{$contact_name}}</td>
                                <td>{{$mob}}</td>
                                <td>{{$ALSEName}}</td>
                                <td>{{$ALSEMobile}}</td>
                            </tr>
                        </table>
                        <table class="table" border="1">
                            <tr style="background: #ccc;">
                                <td colspan="10"><label style="text-decoration: underline;font-size: 16px;"><b>Call History Details</b></label></td>
                            </tr>
                            <tr>
                                <th style="text-align: left;"><b>Status</b></th>
                                <th style="text-align: left;"><b>Assigned</b></th>
                                <th style="text-align: left;"><b>Assign Remarks</b></th>
                                {{-- <th style="text-align: left;"><b>Agent Remarks</b></th> --}}
                                <th style="text-align: left;"><b>Actual Response Time</b></th>
                                <th style="text-align: left;"><b>Restoration Time</b></th>
                                <th style="text-align: left;"><b>Acceptance</b></th>
                                <th style="text-align: left;"><b>Date</b></th>
                            </tr>
                            @isset($history)
                            @foreach($history as $row)
                            <tr>
                                <td style="text-align: left;width:5%;">{{$row->remark_type}}</td>
                                <td style="text-align: left;">{{$row->dealer_name}}</td>
                                <td style="text-align: left;">{{$row->assign_remarks}}</td>
                                {{-- <td style="text-align: left;">{{$row->agent_remark}}</td> --}}
                                <td style="text-align: left;">{{$row->actual_response_time}}</td>
                                <td style="text-align: left;">{{$row->tat_scheduled}}</td>
                                <td style="text-align: left;">{{$row->acceptance==1?"Yes":"No"}}</td>
                                <td style="text-align: left;">{{$row->created_at}}</td>
                            </tr>
                            @endforeach
                            @endisset

                        </table>
						
                    </div>
					@endif
                </div>
            </div>
        </div>
    </div>
	
<script type="text/javascript">
$(document).ready(function(){
	$("#complaint_number_search").keyup(function(){ 
        var inptData = $(this).val();
       
        $.ajax({
            url: '{{url("complaint-number")}}',
            data: {'keyword':inptData},
            success: function(data){
                $("#suggesstion-box").show();
                $("#suggesstion-box").html(data);
                $("#complaint_number_search").css("background","#FFF");
            }
	    });
		
	});
});
//To select country name
function getPDF(){
    /* var divToPrint=document.getElementById("ticketDataPDf");
    $('#logo').show();
    newWin= window.open("");
    newWin.document.write(divToPrint.outerHTML);
    newWin.print();
     newWin.close(); */
     var contents = $("#ticketDataPDf").html();
        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({ "position": "absolute", "top": "-1000000px" });
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        //Create a new HTML document.
        frameDoc.document.write('<html><head><title>Invoice</title>');
        frameDoc.document.write('</head><body>');
        //Append the external CSS file.
        frameDoc.document.write('<link href="invoice.css" rel="stylesheet" type="text/css" />');
        //Append the DIV contents.
        frameDoc.document.write(contents);
        frameDoc.document.write('</body></html>');
        frameDoc.document.close();
        setTimeout(function () {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            frame1.remove();
        }, 800);
}

function selectCountry(val) {
    $("#complaint_number_search").val(val);
    $("#suggesstion-box").hide();
}
</script>
@endsection