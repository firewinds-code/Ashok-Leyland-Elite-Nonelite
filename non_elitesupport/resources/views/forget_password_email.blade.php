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
      	<p>Dear User,</p>
      	<p>Your new password is : <b>{{ $data["password"] }}</b></p>
      	<p>Thanks,<br/>
      	CMS Team</p>
    </body>
</html>
