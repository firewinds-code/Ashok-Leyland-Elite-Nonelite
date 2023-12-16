
                    <div class="ribbon">Questions</div>
						  <form name="ticket-update-by-agent" id="ticket-update-by-agent" method="post" enctype="multipart/form-data"  action="{{url('ticket-update-by-agent')}}">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-4 col-lg-4 col-sm-4">
			                        <label for="datefrom" >Ticket No</label>
                                    <input type="text"  name="ticket_no" id="ticket_no" class="form-control"   placeholder="Ticket Number"  value="@isset($tickets->ticket_number) {{ $tickets->ticket_number }}@endisset"  readonly />

			                    </div>
                                <div class="form-group col-md-4 col-lg-4 col-sm-4">
			                        <label for="datefrom" >Reason of Non-Acceptance</label>
                                    <select type="text"  name="reason_of_non_acceptance" @if(isset(Auth::user()->employee_id)) @disabled(true) @endif id="reason_of_non_acceptance" class="form-select form-control" required>
                                    <option @if(isset($tickets) && $tickets->reason_of_non_acceptance == 'Incorrect identification of nearest workshop')  selected @endif  value="Incorrect identification of nearest workshop">Incorrect identification of nearest workshop</option>
                                    <option @if(isset($tickets) && $tickets->reason_of_non_acceptance == 'Manpower Not Available')  selected @endif value="Manpower Not Available">Manpower Not Available</option>
                                    <option @if(isset($tickets) && $tickets->reason_of_non_acceptance == 'Workshop is Closed')  selected @endif value="Workshop is Closed">Workshop is Closed</option>
                                    <option @if(isset($tickets) && $tickets->reason_of_non_acceptance == 'Local bandh')  selected @endif value="Local bandh">Local bandh</option>
                                    <option @if(isset($tickets) && $tickets->reason_of_non_acceptance == 'Not interested')  selected @endif value="Not interested">Not interested</option>
                                    <option @if(isset($tickets) && $tickets->reason_of_non_acceptance == 'Others')  selected @endif value="Others">Others</option>
                                   </select>

			                    </div>

                                <div class="form-group col-md-4 col-lg-4 col-sm-4">
			                        <label for="datefrom">Updated by (Name)</label>
                                    <input type="text"  name="updated_by_name" @if(isset(Auth::user()->employee_id)) @readonly(true) @endif id="updated_by_name" class="form-control "  placeholder="updated by name"
                                     value="@isset($tickets->updated_by_name) {{ $tickets->updated_by_name }} @endisset" required/>

			                    </div>
                                <div class="form-group col-md-4 col-lg-4 col-sm-4">
			                        <label for="datefrom">Contact No</label>
                                    <input type="text"  name="contact_no" @if(isset(Auth::user()->employee_id)) @readonly(true) @endif id="contact_no" class="form-control"  placeholder="Contact Number"
                                      value="@isset($tickets->contact_number) {{ $tickets->contact_number }} @endisset" required />

			                    </div>
                                <div class="form-group col-md-4 col-lg-4 col-sm-4">
			                        <label for="datefrom" >Role</label>
                                    <select type="text" @if(isset(Auth::user()->employee_id)) @disabled(true) @endif  name="role" id="role" class="form-select form-control"  required>
                                    <option @if(isset($tickets->role) && $tickets->role == 'WM1') selected @endif value="WM1">WM1</option>
                                    <option @if(isset($tickets->role) && $tickets->role == 'WM2') selected @endif value="WM2">WM2</option>
                                    <option @if(isset($tickets->role) && $tickets->role == 'TSM') selected @endif value="TSM">TSM</option>
                                  </select>
                                </div>

                                <div class="form-group col-md-4 col-lg-4 col-sm-4">
			                        <label for="datefrom">Remarks</label>
                                    <textarea name="remarks"   id="remarks" class="form-control" @if(isset(Auth::user()->employee_id)) @readonly(true) @endif rows="6" placeholder="enter remarks"
                                      required>@isset($tickets->remarks) {{ $tickets->remarks }} @endisset</textarea>
			                    </div>

                                @if(isset(Auth::user()->employee_id))

                                <div class="form-group col-md-4 col-lg-4 col-sm-4">
			                        <label for="agent_status" >Agent Status</label>
                                    <select name="agent_status" id="agent_status" class="form-select form-control">
                                    <option @if(isset($tickets->agent_status) && $tickets->agent_status == 'Re-Assign Done') selected @endif value="Re-Assign Done">Re-Assign Done</option>
                                    <option @if(isset($tickets->agent_status) && $tickets->agent_status == 'Confirmation Pending') selected @endif value="Confirmation Pending">Confirmation Pending</option>
                                    <option @if(isset($tickets->agent_status) && $tickets->agent_status == 'Not Required To Re-assign') selected @endif value="Not Required To Re-assign">Not Required To Re-assign</option>
                                </select>
                              </div>
                               <div class="form-group col-md-4 col-lg-4 col-sm-4">
			                        <label for="datefrom">Remarks</label>
                                    <textarea name="agent_remarks"   id="agent_remarks" class="form-control"  rows="6" placeholder="enter remarks"
                                      required>@isset($tickets->agent_remarks) {{ $tickets->agent_remarks }} @endisset</textarea>
			                    </div>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                          </form>
<script>
    $(function(){

        $('#ticket-update-by-agent').on('submit',function(event){
            event.preventDefault();

            var url = $(this).attr('action');
            var form = $(this).serialize();
             $.post(url,form,function(response){

                if(response.success)
                {
                    toastr.success(response.message);
                    $('#getTicket').show();
                    setTimeout($('#content-form').html(''), 3000);
                }
                if(response.error)
                {
                    toastr.error(response.message);
                    $('#getTicket').show();
                }
            });
        });

    });

</script>

