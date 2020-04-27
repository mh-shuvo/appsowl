@extends('Layouts.SuperAdminDashboard')
@section('style')
<style type="text/css">
	th{text-align: center;}
</style>
@endsection
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-sm-12">
		<div class="ibox float-e-margins">
			<h3>User List</h3>
			<div class="ibox-content">
				<div class="table-responsive">
					<table class="table table-bordered table-striped table-export" id="user_datatable">
				       <thead>
				       <tr>
					       	<th>User ID</th>
							<th>Name</th>
							<th>Username/Phone</th>
							<th>Join Date</th>
							<th>Address</th>
							<th>Status</th>
							<th>Action</th>
		          		</tr>
				       </thead>
				    </table>
				</div>
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
	<div id="ticket" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<form action="javascript:void(0)" id="ticket_submit" enctype="multipart/form-data">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Ticket</h4>
					</div>
					<div class="modal-body">
		  				<input type="hidden" name="user_id" id="user_id">
		  				<div class="form-group">
		  					<textarea name="ticket_details" class="form-control"></textarea>
						</div>
						<div class="form-group">
							<input type="file" name="document" class="form-control-file" >
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal" onclick="$('#ticket_submit').trigger('reset')" >Close</button>
						<button class="btn btn-primary" type="submit" id="save">Submit</button>
					</div>
	  			</form>
			</div>
		</div>
	</div>
	<div id="send_message_modal" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h3>Send Message</h3>
				</div>
				<form action="javascript:void(0)" id="send_message_form" enctype="multipart/form-data">
					<div class="modal-body">
		  				<input type="hidden" name="user_id" id="user__id">
		  				<div class="form-group">
		  					<textarea rows="5" name="message" class="form-control" placeholder="Write Message in here..."></textarea>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal" onclick="$('#send_message_form').trigger('reset')" >Close</button>
						<button class="btn btn-primary" type="submit" id="save">Submit</button>
					</div>
	  			</form>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script>
	$(document).ready( function () {

		$('#user_datatable').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: {
	         url: "{{ url('/datatable/user_datatable') }}",
	         type: 'GET',
	         data: function (d) {
	         }
	        },

			dom: '<"html5buttons"B>lTfgitp',
			buttons: [
				{extend: 'copy'},
				{extend: 'csv'},
				{extend: 'excel', title: 'User-Excel'},
				{extend: 'pdf', title: 'User-Pdf'},
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
	                 { data: 'phone',name: 'userDetails.phone'},
	                 { data: 'register_date'},
	                 { data: 'address',name: 'userDetails.address'},
	                 { data: 'status',searchable: false},
	                 { data: 'action',orderable: false,searchable: false}
	              ]
	     });
	});

	$(document).on("click",".user_status_change", function(){
		var user_id = $(this).attr('user_id');
		$('#resone').modal('show');
		$("#user_id").val(user_id);
	});

	$(document).on("click",".ticket", function(){
		var user_id = $(this).attr('user_id');
		$('#ticket').modal('show');
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
					url: '{{url('/user-status-change')}}' ,
					type: "post",
					dataType: 'json',
					data: $formData,
					contentType:false,
					cache:false,
					processData:false,
					success: function( response ) {
						$('#status_change').trigger('reset');
						$('#resone').modal('hide');
						$('#user_datatable').DataTable().draw(true);
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
	
	$(document).on("click",".send_message", function(){
		var user_id = $(this).attr('user-id');
		$('#user__id').val(user_id);
		$("#send_message_modal").modal("toggle");
	});
	
	$("#send_message_form").validate({			
		rules: {
			message: {
				required: true
			},

		},
		submitHandler: function(form) {
			var $formData = new FormData(form);
			$.ajax({
				url: '{{url('/sms-send-submit')}}' ,
				type: "post",
				dataType: 'json',
				data: $formData,
				contentType:false,
				cache:false,
				processData:false,
				success: function( response ) {
					$('#send_message_form').trigger('reset');
					$('#send_message_modal').modal('hide');
					$('#user_datatable').DataTable().draw(true);
					
					toastr.options = {
					  "closeButton": true,
					  "debug": false,
					  "progressBar": true,
					  "preventDuplicates": false,
					  "positionClass": "toast-top-right",
					  "onclick": null
					}
					if (response.status=='success') {
						toastr.success('',response.msg);
					}else{
						toastr.error('',response.msg);
					}	
				}
			});
		}
	})

	$("#ticket_submit").validate({			
		rules: {
			ticket_details: {
				required: true
			},

		},
		submitHandler: function(form) {
			var $formData = new FormData(form);
			$.ajax({
				url: '{{url('/ticket-submit')}}' ,
				type: "post",
				dataType: 'json',
				data: $formData,
				contentType:false,
				cache:false,
				processData:false,
				success: function( response ) {
					$('#ticket_submit').trigger('reset');
					$('#ticket').modal('hide');
					$('#user_datatable').DataTable().draw(true);
					
					toastr.options = {
					  "closeButton": true,
					  "debug": false,
					  "progressBar": true,
					  "preventDuplicates": false,
					  "positionClass": "toast-top-right",
					  "onclick": null
					}
					if (response.status=='success') {
						toastr.success('',response.msg);
					}else{
						toastr.error('',response.msg);
					}	
				}
			});
		}
	})
</script>
@endsection
