<!DOCTYPE html>
<html>
<body>
	<div>
		<p>Hello,</p>
		<p>Click for verification</p>
		<a href="{{ route('email-varify',$user->email_varification_token) }}">{{$user->email_varification_token}}</a>
	</div>
</body>
</html>