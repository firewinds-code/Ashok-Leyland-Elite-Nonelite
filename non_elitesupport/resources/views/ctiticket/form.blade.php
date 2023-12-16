@extends("layouts.apilayout")
@section('title','CTI TICKET FORM')
@section('bodycontent')
	<div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">CTI TICKET FORM</h4>
                <div class="row">
                    <div class="col-md-12" style="border: 1px solid #ccc, margin-top : 1rem;">
                        @if (session()->has('success'))
                            <div class="alert alert-success alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{ session('success') }}</strong>
                            </div>
                        @endif
                        @if (session()->has('error'))
                            <div class="alert alert-danger alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{ session('error') }}</strong>
                            </div>
                        @endif
                    </div>
                    <div class="ribbon">Questions</div>
						  <form name="myForm" id="myForm" method="post" enctype="multipart/form-data"  action="{{url('api/ticket-update')}}">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-4 col-lg-4 col-sm-4">
			                        <label for="datefrom" >Ticket No</label>
                                    <input type="text"  name="ticket_no" id="ticket_no" class="form-control"   placeholder="Ticket Number"  value="@isset($ticket) {{ $ticket }}@endisset"  readonly />

			                    </div>
                                <div class="form-group col-md-4 col-lg-4 col-sm-4">
			                        <label for="datefrom" >Reason of Non-Acceptance</label>
                                    <select type="text"  name="reason_of_non_acceptance" id="reason_of_non_acceptance" class="form-control " required/>
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
                                    <input type="text"  name="updated_by_name" id="updated_by_name" class="form-control"  placeholder="updated by name"
                                     value="@isset($tickets->updated_by_name) {{ $tickets->updated_by_name }} @endisset" required/>

			                    </div>
                                <div class="form-group col-md-4 col-lg-4 col-sm-4">
			                        <label for="datefrom">Contact No</label>
                                    <input type="number"  name="contact_no" id="contact_no" minlength="3" maxlength="10" class="form-control"  placeholder="Contact Number"
                                      value="@isset($tickets->contact_number) {{ $tickets->contact_number }} @endisset" required />

			                    </div>
                                <div class="form-group col-md-4 col-lg-4 col-sm-4">
			                        <label for="datefrom" >Role</label>
                                    <select type="text"  name="role" id="role" class="form-control"  required/>
                                    <option @if(isset($tickets->role) && $tickets->role == 'WM1') selected @endif value="WM1">WM1</option>
                                    <option @if(isset($tickets->role) && $tickets->role == 'WM2') selected @endif value="WM2">WM2</option>
                                    <option @if(isset($tickets->role) && $tickets->role == 'TSM') selected @endif value="TSM">TSM</option>
                                  </select>
                                </div>

                                <div class="form-group col-md-4 col-lg-4 col-sm-4">
			                        <label for="datefrom">Remarks</label>
                                    <textarea name="remarks"   id="remarks" class="form-control" rows="6" placeholder="enter remarks"
                                      required/>@isset($tickets->remarks) {{ $tickets->remarks }} @endisset</textarea>
			                    </div>



                             </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                          </form>
                      </div>
                  </div>
               </div>
           </div>
       </div>
@endsection
