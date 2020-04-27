<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Appsowl || {{ ucfirst(Request::segment(1)) }}</title>
	<link rel="shortcut icon" href="{{ asset('favicon.png') }}">

	<link href="{{asset('public/dashboard/css/bootstrap.min.css')}}" rel="stylesheet">
	<link href="{{asset('public/dashboard/font-awesome/css/font-awesome.css')}}" rel="stylesheet">	
	<link href="{{asset('public/dashboard/css/animate.css')}}" rel="stylesheet">
	<link href="{{asset('public/dashboard/css/style.css')}}" rel="stylesheet">
	<link href="{{asset('public/dashboard/css/plugins/dataTables/datatables.min.css')}}" rel="stylesheet">
	<link href="{{asset('public/dashboard/css/plugins/datapicker/datepicker3.css')}}" rel="stylesheet">
	<link href="{{asset('public/dashboard/css/plugins/toastr/toastr.min.css')}}" rel="stylesheet">
	@yield('style')
	<style>
		tr td{text-align:center;}
	</style>
	<script src="{{asset('public/dashboard/js/jquery-3.1.1.min.js')}}"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
</head>
@php
$permission = Session::get('admin_data')[0] ['permission'];

if(strpos($permission, ',') !== false) {
    $permission = explode(',', $permission);
	$count = count($permission);
	for ($x = 0; $x < $count; $x++) {
		if ($permission[$x]=='account'){
		    $account = true;
		}elseif ($permission[$x]=='maintainer'){
		    $maintainer = true;
		}else{
			$account = false;
			$maintainer = false;
		}
	}
}elseif($permission=='account'){
	$account = true;
}elseif($permission=='maintainer'){
	$maintainer = true;
}else{
	$account = false;
	$maintainer = false;
}
@endphp
<body class="pace-done skin-3">
	<div id="wrapper">
		<nav class="navbar-default navbar-static-side" role="navigation">
			<div class="sidebar-collapse">
				<ul class="nav metismenu" id="side-menu">
					<li class="nav-header">
						<div class="dropdown profile-element">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#">
						        <span class="clear">
						        	<span class="block m-t-xs">
						        		<strong class="font-bold">
						        			{{ session('admin_data')[0]['first_name'].' '.session('admin_data')[0]['last_name'] }}
						        		</strong>
						        	</span>
						        	<span class="text-muted text-xs block">
							         	@if(isset($account,$maintainer))
							         	{{ 'Super Admin' }}
							         	@elseif(isset($account))
							         	{{ 'Accountant' }}
							         	@elseif(isset($maintainer))
							         	{{ 'Maintainer' }}
							         	@endif
							         	<b class="caret"></b>
							        </span>
							    </span>
							</a>
						    <ul class="dropdown-menu animated fadeInRight m-t-xs">
						        <li><a href="javascript:void(0);" data-toggle="modal" data-target="#ChangePasswordModal" >Change Password</a></li>
						    </ul>
						</div>
						<div class="logo-element">SA</div>
					</li>

					@if($uri_segments[1]=='home')<li class="active">@else<li>@endif
						<a href="{{ url('/home') }}"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a>
					</li>

					@if(isset($account))
						@if($uri_segments[1]=='user-manage'||$uri_segments[1]=='user-view')<li class="active">@else<li>@endif
							<a href="{{url('/user-manage')}}"><i class="fa fa-user"></i> <span class="nav-label">User Manage</span></a>
						</li>
						@if($uri_segments[1]=='agent-manage'||$uri_segments[1]=='agent-details')<li class="active">@else<li>@endif
							<a href="{{url('/agent-manage')}}"><i class="fa fa-group"></i> <span class="nav-label">Agent Manage</span></a>
						</li>
						@if($uri_segments[1]=='withdraw-manage')<li class="active">@else<li>@endif
							<a href="{{url('/withdraw-manage')}}"><i class="fa fa-briefcase"></i> <span class="nav-label">Withdraw Manage</span></a>
						</li>
						@if($uri_segments[1]=='subdomain-manage')<li class="active">@else<li>@endif
							<a href="{{url('/subdomain-manage')}}"><i class="fa fa-server"></i> <span class="nav-label">Subdoamin</span></a>
						</li>
						@if($uri_segments[1]=='super-admin-manage')<li class="active">@else<li>@endif
							<a href="{{url('/super-admin-manage')}}"><i class="fa fa-superpowers"></i> <span class="nav-label">Super Admin Manage</span></a>
						</li>                                        
						@if($uri_segments[1]=='subscribe-list'||$uri_segments[1]=='subscribe-details')<li class="active">@else<li>@endif
							<a href="{{url('/subscribe-list')}}"><i class="fa fa-credit-card"></i> <span class="nav-label">Subscribe Log</span></a>
						</li> 
						@if($uri_segments[1]=='voucher-manage')<li class="active">@else<li>@endif
							<a href="{{url('/voucher-manage')}}"><i class="fa fa-slideshare"></i> <span class="nav-label">Voucher</span></a>
						</li>                                        
						@if($uri_segments[1]=='software-list'||$uri_segments[1]=='software-variations')<li class="active">@else<li>@endif
							<a href="{{url('/software-list')}}"><i class="fa fa-server"></i> <span class="nav-label">Software</span></a>
						</li>                                        
						@if($uri_segments[1]=='pos-requirements')<li class="active">@else<li>@endif
							<a href="{{url('/pos-requirements')}}"><i class="fa fa-cog"></i> <span class="nav-label">Pos Requirements</span></a>
						</li>
						@if($uri_segments[1]=='sms-log'||$uri_segments[1]=='sms-send')<li class="active">@else<li>@endif
							<a><i class="fa fa-comments"></i> <span class="nav-label">Sms</span><span class="fa arrow"></span></a>
							<ul class="nav nav-second-level collapse">
								@if($uri_segments[1]=='sms-send')<li class="active">@else<li>@endif
									<a href="{{url('/sms-send')}}"><i class="fa fa-plus"></i>Send</a>
								</li> 
								@if($uri_segments[1]=='sms-log')<li class="active">@else<li>@endif
									<a href="{{ url('/sms-log') }}"><i class="fa fa-star-half-o"></i> Sms Log</a>
								</li>
							</ul>
						</li>
						@if($uri_segments[1]=='activity-log')<li class="active">@else<li>@endif
							<a href="{{ url('/activity-log') }}"><i class="fa fa-slack"></i> <span class="nav-label">Activity Log</span></a>
						</li>

					@endif

					@if(isset($maintainer))

						<!-- @if($uri_segments[1]=='ticket-manage'||$uri_segments[1]=='support'||$uri_segments[1]=='ticket-details')<li class="active">@else<li>@endif
							<a href="{{ url('/ticket-manage') }}"><i class="fa fa-support"></i> <span class="nav-label"> Support</span></a>
						</li> -->
						@if($uri_segments[1]=='notification-manage'||$uri_segments[1]=='notification-edit')<li class="active">@else<li>@endif
							<a href="{{url('/notification-manage')}}"><i class="fa fa-bell"></i> <span class="nav-label"> Notification</span></a>
						</li>

						@if($uri_segments[1]=='tutorial-manage'||$uri_segments[1]=='terms-condition'||$uri_segments[1]=='privacy')<li class="active">@else<li>@endif
							<a><i class="fa fa-desktop"></i> <span class="nav-label">Web Modiul</span><span class="fa arrow"></span></a>
							<ul class="nav nav-second-level collapse">
								@if($uri_segments[1]=='tutorial-manage')<li class="active">@else<li>@endif
									<a href="{{url('/tutorial-manage')}}"><i class="fa fa-video-camera"></i> Tutorial</a>
								</li>								
								@if($uri_segments[1]=='terms-condition')<li class="active">@else<li>@endif
									<a href="{{url('/terms-condition')}}"><i class="fa fa-check"></i> Terms & 	Condition</a>
								</li>
								@if($uri_segments[1]=='privacy')<li class="active">@else<li>@endif
									<a href="{{url('/privacy')}}"><i class="fa fa-user-secret"></i> Privacy & Policy</a>
								</li>
							</ul>
						</li>
					@endif
				</ul>
			</div>
		</nav>
							
		<div id="page-wrapper" class="gray-bg dashbard-1">
			<div class="row border-bottom">
				<nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
					<div class="navbar-header">
						<a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
					</div>
					<ul class="nav navbar-top-links navbar-right">
						<li>
							<a class="logout" href="javascript:void(0);">
								<span class="btn btn-danger btn-sm"> <i class="fa fa-sign-out"></i> Log out</span>
							</a>
						</li>
					</ul>
					
				</nav>
				@yield('content')  
				<div class="footer">
					<div class="pull-right">
						Developed by<strong> <a href="http://softwaregalaxyltd.com/">Software Galaxy Ltd</a></strong>
					</div>
					<div>
						<strong>Copyright</strong> © AppsOWl.com
					</div>
				</div>             
			</div>
		</div>
	</div>
	<form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
		@csrf
	</form>
	
	<div id="ChangePasswordModal" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Change Password</h4>
				</div>
				<form action="javascript:void(0)" id="change_password">
				<div class="modal-body">
					<div class="form-group">
						<label>Current Password</label>
						<input type="password" name="current_password" id="current_password" placeholder="Enter your current password" class="form-control" >
					</div>
					<div class="form-group">
						<label>New Password</label>
						<input type="password" name="new_password" id="new_password" placeholder="Enter new password" class="form-control" >
					</div>
					<div class="form-group">
						<label>Confirm Password</label>
						<input type="password" name="confirm_password" placeholder="Confirm new password" class="form-control" >
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" onclick="close_button()" >Close</button>
					<button class="btn btn-primary" type="submit" id="save">Submit</button>
				</div>
	  			</form>
			</div>
		</div>
	</div>

	<!-- Mainly scripts -->
	<script src="{{asset('public/dashboard/js/bootstrap.min.js') }}"></script>
	<script src="{{ asset('public/dashboard/js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
	<script src="{{ asset('public/dashboard/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
	
	<script src="{{asset('public/dashboard/js/inspinia.js')}}"></script>
	<script src="{{asset('public/dashboard/js/plugins/pace/pace.min.js')}}"></script>

	<script src="{{asset('public/dashboard/js/plugins/dataTables/datatables.min.js')}}"></script>
	<script src="{{asset('public/dashboard/js/plugins/datapicker/bootstrap-datepicker.js')}}"></script>
	<script src="{{asset('public/dashboard/js/plugins/toastr/toastr.min.js')}}"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>  
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>
	
	<script type="text/javascript">
		$(document).ready(function(){
			$('#datepicker .input-group.date').datepicker({
				todayBtn: "linked",
				format: "yyyy-mm-dd",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
			});
		});

		$(document).ready(function() {
			$('.dataTables-example').DataTable({
				pageLength: 25,
				responsive: true,
				dom: '<"html5buttons"B>lTfgitp',
				buttons: [
				{ extend: 'copy'},
				{extend: 'csv'},
				{extend: 'excel', title: 'ExcelFile'},
				{extend: 'pdf', title: 'PdfFile'},
				
				{extend: 'print',
					customize: function (win){
						$(win.document.body).addClass('white-bg');
						$(win.document.body).css('font-size', '10px');
						
						$(win.document.body).find('table')
						.addClass('compact')
						.css('font-size', 'inherit');
					}
				}
				]
				
			});
		});
		
		$(document).on("click",".logout", function(){
			swal({
				title: "Logout",
				text: "Are you sure?",
				icon: "warning",
				buttons: true,
				dangerMode: true,
			})
			.then((willDelete) => {
				if (willDelete) {
					document.getElementById('logout-form').submit();
				}
			});
		});
		
		if ($("#change_password").length > 0) {
			$("#change_password").validate({
				
				rules: {
					current_password: {
						required: true,
						minlength: 6,
						maxlength: 10
					},
					new_password: {
						required: true,
						minlength: 6,
						maxlength: 10
					},
					confirm_password: {
						required: true,
						minlength: 6,
						maxlength: 10,
						equalTo: "#new_password"
					}
				},
				submitHandler: function(form) {

					$("form input").removeClass('is-invalid').removeClass('is-valid');
					$(".invalid-feedback").remove();

					$.ajax({
						url: '{{url('/change-password')}}' ,
						type: "post",
						data: $('#change_password').serialize(),
						success: function( response ) {
							if (response.status=='success') {
								$('#change_password').trigger('reset');
								$('#ChangePasswordModal').modal('hide');
								swal(response.msg, {
									icon: "success",
									text: response.msg,
								});
							}else if (response.status=='error'){
								
								$('#current_password').addClass('is-invalid').removeClass('is-valid');
								$('#current_password').after(
								$("<em class='invalid-feedback' style='color:red;'>"+response.msg+"</em>")
								);
								
							}
						}
					});
				}
			})
		}
		
		function close_button() {
		  $('#change_password').trigger('reset');
		}
	</script>
	@yield('script')
	</body>
</html>														