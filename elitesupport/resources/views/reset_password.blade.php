@extends("layouts.masterlayout")
@section('title','Reset Password')
@section('bodycontent')
<div class="content-wrapper">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Reset Password</h4>
            <div class="row">
                <div class="col-md-12">
                        <div style="width: 50%;margin: 0 auto;">
                        <form name="myForm" method="post" enctype="multipart/form-data" action="{{url('store-reset-password')}}" autocomplete="off">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            {{-- <label for="current_password">Current Password</label>  --}}                                   
                            <input type="hidden" name="employee_id" value="{{Auth::user()->employee_id}}" class="form-control" >
                            <label for="new_password">New Password</label>                                    
                            <input type="password" name="new_password" class="form-control" >
                            {{-- <label for="confirm_password">Confirm Password</label>                                    
                            <input type="password" name="confirm_password" class="form-control" ></br> --}}<br>
                            <div class="box-footer">
                                <span class="pull-right">
                                <button type="button" onclick="reloadPage();" class="btn-secondary">Cancel</button>	
                                <input type="submit"name="submit" id="submit" value="Submit" class="btn-secondary">	
                                </span>
                            </div>
                        </form>  
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
