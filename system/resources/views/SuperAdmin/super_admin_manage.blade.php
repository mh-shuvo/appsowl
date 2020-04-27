@extends('Layouts.SuperAdminDashboard')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-sm-12">
		<div class="ibox float-e-margins">
			<h3>Manage Super Admin</h3>
			<div class="ibox-title">
	            <a href="javascript:void(0)" class="btn btn-sm btn-primary pull-right m-t-n-xs" data-toggle="modal" data-target="#AddSuperAdminModal"><strong>Create SuperAdmin</strong></a>
			</div>
			<div class="ibox-content">
				<div class="table-responsive">
					<table class="table table-bordered table-striped dataTable" id="super_admin_datatable">
						<thead>
							<tr>
								<th class="text-center">Sl</th>
								<th class="text-center">Name</th>
								<th class="text-center">Email</th>
								<th class="text-center">Username/Phone</th>
								<th class="text-center">Permission</th>
								<th class="text-center">Status</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div> 	
	</div>
</div>
<div id="AddSuperAdminModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="javascript:void(0)" id="super_admin_add">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Add Super Admin</h4>
				</div>
				<div class="modal-body">
	  				<div class="form-group">
						<div class="row">
							<div class="col-sm-6">
								<label>First Name</label>
								<input type="text" name="first_name" placeholder="First Name" class="form-control" >
							</div>
							<div class="col-sm-6">
								<label>Last Name</label>
								<input type="text" name="last_name" placeholder="Last Name" class="form-control" >
							</div>
						</div>
					</div>
					<div class="form-group">
						<label>Mobile</label>
						<input type="text" name="mobile" placeholder="Mobile number." class="form-control" >
					</div>
					<div class="form-group">
						<label>Email</label>
						<input type="email" name="email" placeholder="Enter email address" class="form-control" >
					</div>
					<div class="form-group date" id="datepicker">
						<label>Date-of-Birth</label>
						<div class="input-group date">
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							<input type="text" class="form-control" name="dob" readonly />
						</div>
					</div>
					<div class="form-group">
						<label style="font-size:16px;">Permission</label>
						<div class="row">
							<div class="col-sm-6">
								<div class="switch">
								  <label style="font-size:13px;">Account </label>
								  <input type="checkbox" name="account" value="account" >
								</div>
							</div>
							<div class="col-sm-6">
								<div class="switch">
								  <label style="font-size:13px;">Maintainer </label>
								  <input type="checkbox" name="maintainer" value="maintainer" >
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" onclick="$('#super_admin_add').trigger('reset')" >Close</button>
					<button class="btn btn-primary" type="submit" id="save">Submit</button>
				</div>
  			</form>
		</div>
	</div>
</div>
<div id="resone" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="javascript:void(0)" id="status_change" enctype="multipart/form-data">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Resone</h4>
				</div>
				<div class="modal-body">
	  				<input type="hidden" name="user_id" id="user_id">
	  				<div class="form-group">
	  					<textarea name="resone" class="form-control"></textarea>
					</div>
					<div class="form-group">
						<input type="file" name="document" class="form-control-file" >
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" onclick="$('#status_change').trigger('reset')" >Close</button>
					<button class="btn btn-primary" type="submit" id="save">Submit</button>
				</div>
  			</form>
		</div>
	</div>
</div>
@endsection

@section('script')

<script type="text/javascript">

	$(document).ready( function () {
		$('#super_admin_datatable').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: {
	        	url: '{{ url("/datatable/super_admin_datatable/")}}',
	        	type: 'GET'
	        },

			dom: '<"html5buttons"B>lTfgitp',
			buttons: [
			{extend: 'copy'},
			{extend: 'csv'},
			{extend: 'excel', title: 'Excel'},
			{extend: 'pdf', title: 'Pdf'},
			{extend: 'print',
				customize: function (win){
					$(win.document.body).addClass('white-bg');
					$(win.document.body).css('font-size', '10px');
					
					$(win.document.body).find('table')
					.addClass('compact')
					.css('font-size', 'inherit');
				}
			}
			],
	        columns: [
	                 { data: 'user_id' },
	                 { data: 'name',name: 'userDetails.first_name'},
	                 { data: 'email'},
	                 { data: 'username'},
	                 { data: 'permission'},
	                 { data: 'status',name: 'banned',searchable: false},
	                 { data: 'action',orderable: false,searchable: false},
	              ]
	     });
	});

	$(document).on("click",".admin_status_change", function(){
		var user_id = $(this).attr('user_id');
		$('#resone').modal('show');
		$("#user_id").val(user_id);
	});

	if ($("#status_change").length > 0) {
		$("#status_change").validate({
			
			rules: {
				resone: {
					required: true
				},

			},
			submitHandler: function(form) {
				var $formData = new FormData(form);
				$.ajax({
					url: '{{url("/user-status-change")}}' ,
					type: "post",
					dataType: 'json',
					data: $formData,
					contentType:false,
					cache:false,
					processData:false,
					success: function( response ) {
						$('#status_change').trigger('reset');
						$('#resone').modal('hide');
						$('#super_admin_datatable').DataTable().draw(true);
						toastr.options = {
						  "closeButton": true,
						  "debug": false,
						  "progressBar": true,
						  "preventDuplicates": false,
						  "positionClass": "toast-top-right",
						  "onclick": null
						}
						
						toastr.success('',response.msg);
					}
				});
			}
		})
	}

	if ($("#super_admin_add").length > 0) {
		$("#super_admin_add").validate({
			
			rules: {
				first_name: {
					required: true
				},
				last_name: {
					required: true
				},
				mobile: {
					required: true,
					digits:true
				},
				email: {
					required: true,
					email:true
				},
				dob: {
					required: true,
					date:true
				}
			},
			submitHandler: function(form) {
				var $formData = new FormData(form);
				$.ajax({
					url: '{{url("/super-admin-submit")}}' ,
					type: "post",
					dataType: 'json',
					contentType:false,
					cache:false,
					processData:false,
					data: $formData,
					success: function( response ) {
						$('#super_admin_add').trigger('reset');
						$('#AddSuperAdminModal').modal('hide');
						$('#super_admin_datatable').DataTable().draw(true);
						toastr.options = {
						  "closeButton": true,
						  "debug": false,
						  "progressBar": true,
						  "preventDuplicates": false,
						  "positionClass": "toast-top-right",
						  "onclick": null
						}						
						toastr.success('',response.msg);
					}
				});
			}
		})
	}
</script>
@endsection