<div class="row"  id="freshlang">
    <div class="col-md-2" onclick="getFreshForm('{{$type1}}','{{$type2}}','Hindi')">
        <span style="font-size: 10px;"><i class="fa fa-language" aria-hidden="true"></i> <i class="fa fa-language" aria-hidden="true"></i>Hindi({{$records['Hindi']}})</span>
    </div>
    <div class="col-md-2" onclick="getFreshForm('{{$type1}}','{{$type2}}','English')">
        <span style="font-size: 10px;"><i class="fa fa-language" aria-hidden="true"></i> English({{$records['English']}})</span>
    </div>
    <div class="col-md-2" onclick="getFreshForm('{{$type1}}','{{$type2}}','Malayalam')">
        <span style="font-size: 10px;"><i class="fa fa-language" aria-hidden="true"></i> Malayalam({{$records['Malayalam']}})</span>
    </div>
    <div class="col-md-2" onclick="getFreshForm('{{$type1}}','{{$type2}}','Kannad')">
        <span style="font-size: 10px;"><i class="fa fa-language" aria-hidden="true"></i> Kannad({{$records['Kannad']}})</span>
    </div>
    <div class="col-md-2" onclick="getFreshForm('{{$type1}}','{{$type2}}','Tamil')">
        <span style="font-size: 10px;"><i class="fa fa-language" aria-hidden="true"></i> Tamil({{$records['Tamil']}})</span>
    </div>
    <div class="col-md-2" onclick="getFreshForm('{{$type1}}','{{$type2}}','Telugu')">
        <span style="font-size: 10px;"><i class="fa fa-language" aria-hidden="true"></i> Telugu({{$records['Telugu']}})</span>
    </div>
</div>
<div class="row"  id="freshform">
    <form name="myFresh" method="post" enctype="multipart/form-data" action="{{route('cogent-complete-update')}}">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input  type="hidden" name="dataid" id="dataid"/>                        
        <input  type="hidden" name="type1" id="type1" value="{{$type1}}"/>                        
        <input  type="hidden" name="type2" id="type2" value="{{$type2}}"/>                        
        <div class="row">								
            <div class="form-group col-md-3">
                <label for="complaint_number">Complaint Number</label> <span style="color: red;">*</span>
                <input type="text" name="complaint_number" id="complaint_number_fresh" class="form-control" value="" readonly />
            </div>
            
            <div class="form-group col-md-3">
                <label for="language">Language</label> <span style="color: red;">*</span>
                <input type="text" name="language" id="language_fresh" class="form-control" value="" readonly />
            </div>
            
            <div class="form-group col-md-3">
                <label for="Disposition">Disposition</label>
                <select name="disposition" id="disposition_fresh" tabindex="1" class="form-control">
                    <optgroup>
                        <option Value="">--select--</option>
                        <option Value="Contacted">Contacted</option>
                        <option Value="Non-Contacted">Non-Contacted</option>
                    </optgroup>
                </select>	
            </div> 	                                                        
            <div class="form-group col-md-3">
                <label for="sub_disposition">Sub-Disposition</label>
                <select name="sub_disposition" id="sub_disposition_fresh" tabindex="1" class="form-control">
                    
                </select>	
            </div> 	                                                        
            <div class="form-group col-md-3" id="resCodeSh">
                <label for="resolution_code">Resolution Code</label>
                <select name="resolution_code" id="resolution_code_fresh" tabindex="1" class="form-control">                    
                </select>
            </div>
            <br />
            <div class="form-group col-md-3">
                <span id="complaint_number_fresh_val"></span>	
            </div>                                                   
        </div>
        <div class="box-footer">
            <span class="pull-right">									
            <input type="submit"name="submit" id="submit" value="Submit" class="btn-secondary">
            </span>
        </div>
    </form>
</div>

<script>
    $(document).ready(function(){
        $("#resCodeSh").hide();
    });
</script>

@if ($type1 == 'day')
<script>
    $(document).ready(function(){
      $("#disposition_fresh").change(function(){
        $('#sub_disposition_fresh').find('option').remove().end();
        $('#resolution_code_fresh').find('option').remove().end();
        let dispoVal = $(this).val();
        if(dispoVal=="Contacted"){
            
            var fsubDis = ['Closed - Customer Confirmation','Closed - TSM/ASM Confirmation'];
            for(let i=0;i<fsubDis.length;i++){
                var newoption = $('<option value="'+fsubDis[i]+'">'+fsubDis[i]+'</option>');
                $('#sub_disposition_fresh').append(newoption);
            }
    
            /* var fresCode = ['Nearest Dealer is different','Location is incorrect','Workshop Is closed','Manpower is not available'];
            for(let i=0;i<fresCode.length;i++){
                var newoption = $('<option value="'+fresCode[i]+'">'+fresCode[i]+'</option>');
                $('#resolution_code_fresh').append(newoption);
            } */
        }else if(dispoVal=="Non-Contacted"){
            $("#resCodeSh").hide();
            $('#resolution_code_fresh').prop('disabled',true);
            var fsubDis = ['Re-open','Same Status'];
            for(let i=0;i<fsubDis.length;i++){
                var newoption = $('<option value="'+fsubDis[i]+'">'+fsubDis[i]+'</option>');
                $('#sub_disposition_fresh').append(newoption);
            }
        }else{
            $('#sub_disposition_fresh').find('option').remove().end();
            $('#resolution_code_fresh').find('option').remove().end();
            $('#sub_disposition_fresh').prop('disabled',true);
            $('#resolution_code_fresh').prop('disabled',true);
        }
      });
    });
    </script>
    <script>
        function getFreshForm (type1,type2,lang){
                console.log("Call Lang");
                    let typeData ="Lang";
                    $.ajax({
                        url: "{{route('cogent-complete-lang-ajax')}}",
                        type: 'post',
                        data: {
                        "_token": "{{ csrf_token() }}",
                        type1: type1,
                        type2: type2,
                        lang: lang,
                        },
                        success: function(response) {
                            let gf = JSON.parse(response.html);
                            console.log(gf[0].id);
                            var complaint_number = gf[0].complaint_number;
                            if (response.status == 'success') {
                                // toastr.success('Lang Data Found!')
                                $("#complaint_number_fresh").val(gf[0].complaint_number);
                                $("#language_fresh").val(gf[0].lang);
                                $("#dataid").val(gf[0].id);
                                //$('#freshLangDiv').html(response.html);
                                $.ajax({
                                    url: "{{route('cogent-complaint-id')}}",
                                    type: 'post',
                                    data: {
                                    "_token": "{{ csrf_token() }}",
                                    complaint_number: complaint_number
                                    },
                                    success: function(result) {
                                    //    alert(result);
                                        // var link = ;
                                        $("#complaint_number_fresh_val").html(result);
                                    }
                                });
                            } else {
                                toastr.error('Something Went Wrong!');
                            }
                        }
                    });
            }
        // $(document).ready(function() { 
        // });
    </script>
@else
<script>
    $(document).ready(function(){
      $("#disposition_fresh").change(function(){
        $('#sub_disposition_fresh').find('option').remove().end();
        $('#resolution_code_fresh').find('option').remove().end();
        let dispoVal = $(this).val();
       
        if(dispoVal=="Contacted"){
            $("#resCodeSh").hide();
            var fsubDis = ['Closed - Customer Confirmation','Closed - TSM/ASM Confirmation'];
            for(let i=0;i<fsubDis.length;i++){
                var newoption = $('<option value="'+fsubDis[i]+'">'+fsubDis[i]+'</option>');
                $('#sub_disposition_fresh').append(newoption);
            }
    
            /* var fresCode = ['Nearest Dealer is different','Location is incorrect','Workshop Is closed','Manpower is not available'];
            for(let i=0;i<fresCode.length;i++){
                var newoption = $('<option value="'+fresCode[i]+'">'+fresCode[i]+'</option>');
                $('#resolution_code_fresh').append(newoption);
            } */
        }else if(dispoVal=="Non-Contacted"){
            $("#resCodeSh").hide();
            $('#resolution_code_fresh').prop('disabled',false);
            var fsubDis = ['Re-open','Same Status'];
            for(let i=0;i<fsubDis.length;i++){
                var newoption = $('<option value="'+fsubDis[i]+'">'+fsubDis[i]+'</option>');
                $('#sub_disposition_fresh').append(newoption);
            }
           /*  var fresCode = ['TSM','ASM','RSM'];
            for(let i=0;i<fresCode.length;i++){
                var newoption = $('<option value="'+fresCode[i]+'">'+fresCode[i]+'</option>');
                $('#resolution_code_fresh').append(newoption);
            } */
        }else{
            $('#sub_disposition_fresh').find('option').remove().end();
            $('#resolution_code_fresh').find('option').remove().end();
            $('#sub_disposition_fresh').prop('disabled',true);
            $('#resolution_code_fresh').prop('disabled',true);
        }
      });
    });
    </script>
    <script>
        function getFreshForm (type1,type2,lang){
                console.log("Call Lang");
                    let typeData ="Lang";
                    $.ajax({
                        url: "{{route('cogent-complete-lang-ajax')}}",
                        type: 'post',
                        data: {
                        "_token": "{{ csrf_token() }}",
                        type1: type1,
                        type2: type2,
                        lang: lang,
                        },
                        success: function(response) {
                            let gf = JSON.parse(response.html);
                            console.log(gf[0].id);
                            if (response.status == 'success') {
                                // toastr.success('Lang Data Found!')
                                $("#complaint_number_fresh").val(gf[0].complaint_number);
                                $("#language_fresh").val(gf[0].lang);
                                $("#dataid").val(gf[0].id);
                                var complaint_number = gf[0].complaint_number;
                                $.ajax({
                                    url: "{{route('cogent-complaint-id')}}",
                                    type: 'post',
                                    data: {
                                    "_token": "{{ csrf_token() }}",
                                    complaint_number: complaint_number
                                    },
                                    success: function(result) {
                                    //    alert(result);
                                        // var link = ;
                                        $("#complaint_number_fresh_val").html(result);
                                    }
                                });
                                //$('#freshLangDiv').html(response.html);
                            } else {
                                toastr.error('Something Went Wrong!');
                            }
                        }
                    });
            }
        // $(document).ready(function() { 
        // });
    </script>
@endif