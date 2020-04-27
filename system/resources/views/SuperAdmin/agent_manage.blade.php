@extends('Layouts.SuperAdminDashboard')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
        	<div class="ibox float-e-margins">
				<h3>Agent List</h3>
				<div class="ibox-content">
					<div class="table-responsive">
	                	<table class="table table-bordered table-striped table-export" id="agent_datatable">
							<thead>
								<tr>
									<th class='text-center'>ID</th>
									<th class='text-center'>Name</th>
									<th class='text-center'>Username/Phone</th>
									<th class='text-center'>Join Date</th>
									<th class='text-center'>Commission</th>
									<th class='text-center'>Address</th>
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

<div id="AddCommissionModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Commission</h4>
			</div>
			<div class="modal-body">
  				<form action="javascript:void(0)" enctype="multipart/form-data" id="commission_submit_form">
				<input type="hidden" name="user_id" id="user_id">
  				<div class="form-group">
  					<label>Old commission:</label>
					<input type="text" name="old_commission" class="form-control" id="commission" readonly>
				</div>
  				<div class="form-group">
  					<label>New commission:</label>
					<input type="text" name="commission" class="form-control" >
				</div>
  				<div class="form-group">
  					<label>Commission note:</label>
					<textarea class="form-control" name="commission_note"></textarea>
				</div>
				<div class="form-group">
					<label>Related document:</label>
					<input type="file" name="document" class="form-control-file" >
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" onclick="$('#commission_submit_form').trigger('reset')" >Close</button>
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
	  				<input type="hidden" name="user_id" id="agent_user_id">
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
@endsection

@section('script')

<script type="text/javascript">

	$(document).on("click",".agent_status_change", function(){
		var user_id = $(this).attr('user_id');
		$('#resone').modal('show');
		$("#agent_user_id").val(user_id);
	});

	$(document).ready( function () {

		$('#agent_datatable').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: {
	         url: "{{ url('/datatable/agent_datatable') }}",
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
	                 { data: 'name',name: 'userDetails.first_name',orderable: false},
	                 { data: 'phone',name: 'userDetails.phone',orderable: false},
	                 { data: 'register_date' },
	                 { data: 'commission',name: 'agent_commission.new_rate',orderable: false },
	                 { data: 'address',name: 'userDetails.address'},
	                 { data: 'banned',searchable: false},
	                 { data: 'action',orderable: false,searchable: false}
	              ]
	     });
	});

	$(document).on("click",".cngcom", function(){
		var user_id = $(this).attr('user_id');
		var commission = $(this).attr('commission');
		$('#AddCommissionModal').modal('show');
		$("#user_id").val(user_id);
		$("#commission").val(commission);
	});

	$(document).on("click",".agent_status_change", function(){
		var user_id = $(this).attr('user_id');

		$.ajax({
			url: '{{ url("/user_status_change")}}'+'/'+user_id,
			type: "get",
			success: function( response ) {
				$('#agent_datatable').DataTable().draw(true);

				toastr.options = {
				  "closeButton": true,
				  "debug": false,
				  "progressBar": true,
				  "preventDuplicates": false,
				  "positionClass": "toast-top-right",
				  "onclick": null
				}
				toastr.success(response.msg,response.user);
			}
		});

	});

	$(document).on("click",".swal-button--confirm", function(){
		$('#agent_datatable').DataTable().draw(true);
	});
	
	if ($("#commission_submit_form").length > 0) {
		$("#commission_submit_form").validate({
			
			rules: {
				old_commission: {
					required: true
				},
				commission: {
					required: true,
					digits:true
				},
				commission_note: {
					required: true
				}
			},
			submitHandler: function(form) {
				var $formData = new FormData(form);
				$.ajax({
					url: '{{url("/commission-submit")}}' ,
					type: "post",
					dataType: 'json',
					data: $formData,
					contentType:false,
					cache:false,
					processData:false,
					success: function( response ) {
						$('#commission_submit_form').trigger('reset');
						$('#AddCommissionModal').modal('hide');
						$('#agent_datatable').DataTable().draw(true);
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
						$('#agent_datatable').DataTable().draw(true);
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

</script>
@endsection