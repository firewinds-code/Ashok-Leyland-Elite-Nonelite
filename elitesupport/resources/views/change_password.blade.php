@extends("layouts.masterlayout")
@section('title','Change Password')
@section('bodycontent')
	<div class="content-wrapper"> 
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Change Password</h4>
                <div class="row">
                    <div class="col-md-12">
                    	
						<form name="myForm" method="post" enctype="multipart/form-data" action="{{url('store-change-password')}}">
                        	<input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="employee_id">Login Id</label> <span style="color: red;">*</span> 
                                    <input type="text" name="employee_id" id="employee_id" class="form-control" placeholder="Login Id" autocomplete="off" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="password">Password</label> <span style="color: red;">*</span> 
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" autocomplete="off" required>
                                </div>
                            </div>
                            @if(Auth::user()->role  == '29' || Auth::user()->role  == '30')
                            <div class="box-footer">
                                <span class="pull-right">
									<button type="button" onclick="reloadPage();" class="btn-secondary">Cancel</button>	
                                <input type="submit"name="submit" id="submit" value="Submit" class="btn-secondary">
                                </span>
                            </div>
                            @endif
                        </form> 
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
