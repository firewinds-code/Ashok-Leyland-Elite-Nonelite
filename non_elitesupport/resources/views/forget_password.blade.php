@extends("layouts.login_master")
@section('title','Forget Password')
@section('form')
<style>
	input[type="email"]::placeholder {
		/* Firefox, Chrome, Opera */
		color: #000;font-size:20px;
	}


	input[type="email"]:-ms-input-placeholder {
		/* Internet Explorer 10-11 */
		color: #000;font-size:20px;
	}


	input[type="email"]::-ms-input-placeholder {
		/* Microsoft Edge */
		color: #000;font-size:20px;
	}

</style> 
 <!--<h5 class="login-title">Complaint Management System</h5>-->
	<form name="myForm" method="post" enctype="multipart/form-data" class="pt-3" action="{{url('forget-pwd-submit')}}" onsubmit="return forgetPasswordValidate()">
	<input type="hidden" name="_token" value="{{csrf_token()}}">
	<div class="form-group" style="background: #fff;">		
			<div class="position-relative has-icon-right">
			<input type="email" id="useremail" name="useremail" tabindex="1" class="input passbx form-control" placeholder="Email" >				
			</div>
		</div>
		<div class="mt-3">			
			<input type="submit" name="btn_login" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" id="btn_login" value="Send">
		<a href="{{url('/') }}" style="float: right;color: #fff;">Go back to login</a>
		</div>
		
	</form>
@endsection