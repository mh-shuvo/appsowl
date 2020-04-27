@extends('Layouts.SuperAdminDashboard')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
        	<div class="ibox float-e-margins">
				<h3>Ticket List - {{ $user_info->userDetails->first_name.' '.$user_info->userDetails->last_name }}</h3>
				<div class="ibox-title">
		            <a href="javascript:void(0)" class="btn btn-sm btn-primary pull-right m-t-n-xs" data-toggle="modal" data-target="#AddTicket"><strong>Create Ticket</strong></a>
				</div>
				<div class="ibox-content">
					<div class="table-responsive">
	                	<table class="table table-bordered table-striped" id="user_support_datatable">
							<thead>
								<tr>
									<th class='text-center'>Ticket</th>
									<th class='text-center'>Priority</th>
									<th class='text-center'>Title</th>
									<th class='text-center'>Created</th>
									<th class='text-center'>Updated</th>
									<th class='text-center'>Status</th>
									<th class='text-center'>Action</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="AddTicket" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="javascript:void(0)" id="ticket_submit">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Add Ticket</h4>
				</div>
				<div class="modal-body">
					<input type="hidden" name="user_id" value="{{$user_info->user_id}}">
	  				<div class="form-group">
						<label>Title</label>
						<input type="text" name="ticket_title" placeholder="Enter ticket title" class="form-control" >
					</div>
	  				<div class="form-group">
						<label>Priority</label>
						<select class="form-control" name="priority">
							<option value="normal" selected>Normal</option>
							<option value="medium">Medium</option>
							<option value="high">High</option>
						</select>
					</div>
					<div class="form-group">
						<label>Details</label>
						<textarea name="ticket_details" placeholder="Enter ticket details" class="form-control"></textarea>
					</div>
					<div class="form-group">
						<label>File</label>
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
@endsection

@section('script')

<script type="text/javascript">

	if ($("#ticket_submit").length > 0) {
		$("#ticket_submit").validate({
			
			rules: {
				ticket_title: {
					required: true
				},
				ticket_details: {
					required: true
				}
			},
			submitHandler: function(form) {
				var $formData = new FormData(form);
				$.ajax({
					url: '{{url("/ticket-submit")}}' ,
					type: "post",
					dataType: 'json',
					contentType:false,
					cache:false,
					processData:false,
					data: $formData,
					success: function( response ) {
						$('#ticket_submit').trigger('reset');
						$('#AddTicket').modal('hide');
						$('#user_support_datatable').DataTable().draw(true);
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

	$(document).ready( function () {

		$('#user_support_datatable').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: {
	        	url: "{{ url('/datatable/user_support_datatable/13') }}",
	        	type: 'GET'
	        },

			dom: '<"html5buttons"B>lTfgitp',
			buttons: [
			{extend: 'copy'},
			{extend: 'csv'},
			{extend: 'excel', title: 'Log-Excel'},
			{extend: 'pdf', title: 'Log-Pdf'},
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
	                 { data: 'ticket_no' },
	                 { data: 'priority'},
	                 { data: 'ticket_title'},
	                 { data: 'created_at',orderable: false},
	                 { data: 'updated_at',orderable: false},
	                 { data: 'status',orderable: false},
	                 { data: 'action',orderable: false }
	              ]
	     });
	});
</script>
@endsection