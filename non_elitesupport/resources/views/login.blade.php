@extends("layouts.login_master")
@section('title','Login Page')
<style>
	input[type="text"]::placeholder {
		/* Firefox, Chrome, Opera */
		color: #000;
		font-size:20px; 
		
	}
	input[type="password"]::placeholder {
		/* Firefox, Chrome, Opera */
		color: #000;font-size:20px;
	}

	input[type="text"]:-ms-input-placeholder {
		/* Internet Explorer 10-11 */
		color: #000;font-size:20px;
	}
	input[type="password"]:-ms-input-placeholder {
		/* Internet Explorer 10-11 */
		color: #000;font-size:20px;
	}

	input[type="text"]::-ms-input-placeholder {
		/* Microsoft Edge */
		color: #000;font-size:20px;
	}
	input[type="password"]::-ms-input-placeholder {
		/* Microsoft Edge */
		color: #000;font-size:20px;
	}
</style> 
	@section('form')
	<!--{{--<h5 class="login-title">Complaint Management System</h5>--}}--}}-->
	<form name="myForm" method="post" enctype="multipart/form-data" class="pt-3" action="{{url('login-check')}}" onsubmit="return loginValidate()" autocomplete="off">
	<input type="hidden" name="_token" value="{{csrf_token()}}">
	<div class="form-group" style="background:#fff;">
		<input type="text" class="form-control form-control-lg" id="email" name="email" placeholder="Email">
		<span id="email_error" style="color:red"></span>
	</div>
	<div class="form-group" style="background:#fff;">
		<input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Password">
		<span id="password_error" style="color:red"></span>
	</div>
		
	<input type="submit" name="btn_login" class="btn btn-primary" id="btn_login" value="Login">
	<span style="float:right;color: #fff;">
	<a href="{{url('forget-password')}}" class="auth-link text-black" style="color: #004a87;"><b>Forgot Password?</b></a></span>		
	</form>
	
@endsection
