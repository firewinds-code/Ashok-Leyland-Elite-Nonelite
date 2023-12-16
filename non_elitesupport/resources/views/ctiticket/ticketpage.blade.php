@extends("layouts.masterlayout")
@section('title','CTI TICKET PAGE')
@section('bodycontent')
	<div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">CTI TICKET PAGE</h4>
                <Button class="btn btn-primary getTicket" id="getTicket">Get Ticket</Button>
                <div class="row">

                    <div class="col-md-12">

                            <div id="content-form"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
    $(document).ready(function (){
       $('.getTicket').on('click',function(){

        $.get("{{ url('cti-ticket') }}",function(response){
            if(response.success)
              {
                $('#content-form').html(response.form);
                $('.getTicket').hide();
            }
            if(response.error)
              {
                toastr.error(response.message);
                $('#content-form').text(response.message);
            }
        });

       });
});
</script>
@endsection

