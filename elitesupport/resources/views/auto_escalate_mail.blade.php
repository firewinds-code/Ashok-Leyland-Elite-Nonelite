<!doctype html>
<html lang="{{ app()->getLocale() }}">
<style>
	tr td
	{
		padding-left: 5px;
	}
</style>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
		<p>Dear Mr. {{$data['assign_name']}}</p>
		<p>The blow mentioned complaints have reached {{$data['level']}} stage of escalation.</p>		
		{!!$data['tableData']!!}
		<p>Kindly intervene accordingly so as to resolve the complaint quickly and to the meet the Customer expectations. </p>
		<p>Thanks & Regards,<br/>
      	Complaint Management System</p>
    </body>
</html>
