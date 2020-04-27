<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<title>Appsowl || {{ ucfirst(Request::segment(1)??'Login') }}</title>
		<link rel="shortcut icon" href="{{ asset('favicon.png') }}">
		
		<link href="{{ asset('public/dashboard/css/bootstrap.min.css') }}" rel="stylesheet">
		<link href="{{ asset('public/dashboard/font-awesome/css/font-awesome.css') }}" rel="stylesheet">		
		<link href="{{ asset('public/dashboard/css/animate.css') }}" rel="stylesheet">
		<link href="{{ asset('public/dashboard/css/style.css') }}" rel="stylesheet">
		
	</head>
	
	<body class="gray-bg">
		
		<div class="middle-box text-center loginscreen animated fadeInDown">
			<div>
				<div>
					<h1 class="logo-name">SG</h1>
				</div>
				<h3>Super Admin Login</h3>
				<form action="javascript:void(0)" id="login">
					<div class="form-group">
						<input type="text" name="username" class="form-control" placeholder="Username">
					</div>
					<div class="form-group">
						<input type="password" name="password" class="form-control" placeholder="Password">
					</div>
					<button type="submit" class="btn btn-primary block full-width m-b">Login</button>
				</form>
			</div>
		</div>
		
		<script src="{{ asset('public/dashboard/js/jquery-3.1.1.min.js') }}"></script>
		<script src="{{ asset('public/dashboard/js/bootstrap.min.js') }}"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function() { 
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});
				
				if ($("#login").length > 0) {
					
					
					$("#login").validate({
						
						rules: {
							username: {
								required: true
							},
							password: {
								required: true
							}
						},
						submitHandler: function(form) {
							$.ajax({
								url: '{{url('/login')}}' ,
								type: "post",
								data: $('#login').serialize(),
								success: function( response ) {
									if (response.status=='false') {
										swal(response.msg, {
											icon: "error",
											text: response.msg,
										});
										}else if (response.status=='true'){
										$('#login').trigger('reset');
										window.location.replace(response.url);
									}
								}
							});
						}
					});
				}
			});
		</script>
		
	</body>
	
</html>