@extends('Layouts.SuperAdminDashboard')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
        	<div class="ibox float-e-margins">
				<h3>Subscribe List</h3>
                <div class="ibox-content">
                	<div class="table-responsive">
	                	<table class="table table-bordered table-striped dataTable" id="subscription_log">
							<thead>
								<tr>
									<th class="text-center">Subscribe ID</th>
									<th class="text-center">User</th>
									<th class="text-center">Agent</th>
									<th class="text-center">Software</th>
									<th class="text-center">Software Variation</th>
									<th class="text-center">Subscribe Date</th>
									<th class="text-center">Activate</th>
									<th class="text-center">Amount</th>
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
</div>
<div id="resone" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="javascript:void(0)" id="subscribe_change" enctype="multipart/form-data">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Resone</h4>
				</div>
				<div class="modal-body">
	  				<input type="hidden" name="subscribe_id" id="subscribe_id">
					<input type="hidden" name="todo" id="status">
	  				<div class="form-group">
	  					<textarea name="resone" class="form-control"></textarea>
					</div>
					<div class="form-group">
						<input type="file" name="document" class="form-control-file" >
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" onclick="$('#subscribe_change').trigger('reset')" >Close</button>
					<button class="btn btn-primary" type="submit" id="save">Submit</button>
				</div>
  			</form>
		</div>
	</div>
</div>
<div id="subscribe_edit_modal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="javascript:void(0)" id="subscribe_edit_submit" enctype="multipart/form-data">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Subscribe edit</h4>
				</div>
				<div class="modal-body">
	  				<input type="hidden" name="subscribe_id" id="edit_subscribe_id">
	  				<div class="form-group">
	  					<label>Amount :</label>
	  					<input type="text" name="subscribe_amount" id="subscribe_amount" class="form-control" placeholder="Enter subscribe amount">
					</div>
	  				<div class="form-group date" id="datepicker">
						<label>Activation date :</label>
						<div class="input-group date">
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							<input type="text" class="form-control" name="subscribe_activation_date" id="subscribe_activation_date" readonly />
						</div>
					</div>
	  				<div class="form-group">
	  					<label>Note :</label>
	  					<textarea name="resone" class="form-control" placeholder="Enter subscribe note"></textarea>
					</div>
					<div class="form-group">
						<label>Document :</label>
						<input type="file" name="document" class="form-control-file" >
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" onclick="$('#subscribe_edit_submit').trigger('reset')" >Close</button>
					<button class="btn btn-primary" type="submit" id="save">Submit</button>
				</div>
  			</form>
		</div>
	</div>
</div>
@endsection

@section('script')
<script>

	$(document).on("click",".subscribe_edit", function(){
		var subscribe_id = $(this).attr('subscribe_id');
		var subscribe_amount = $(this).attr('subscribe_amount');
		var subscribe_activation_date = $(this).attr('subscribe_activation_date');
		$('#subscribe_edit_modal').modal('show');
		$("#edit_subscribe_id").val(subscribe_id);
		$("#subscribe_amount").val(subscribe_amount);
		$("#subscribe_activation_date").val(subscribe_activation_date);
	});

	if ($("#subscribe_edit_submit").length > 0) {
		$("#subscribe_edit_submit").validate({
			
			rules: {
				subscribe_id: {
					required: true
				},
				subscribe_amount: {
					required: true
				},
				subscribe_activation_date: {
					required: true
				},
				resone: {
					required: true
				}
			},
			submitHandler: function(form) {
				var $formData = new FormData(form);
				$.ajax({
					url: '{{url('/subscribe-edit')}}' ,
					type: "post",
					dataType: 'json',
					data: $formData,
					contentType:false,
					cache:false,
					processData:false,
					success: function( response ) {
						$('#subscribe_edit_submit').trigger('reset');
						$('#subscribe_edit_modal').modal('hide');
						$('#subscription_log').DataTable().draw(true);
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

	$(document).on("click",".change_status", function(){
		var status = $(this).attr('status');
		var subscribe_id = $(this).attr('subscribe_id');
		$('#resone').modal('show');
		$("#status").val(status);
		$("#subscribe_id").val(subscribe_id);
	});

	if ($("#subscribe_change").length > 0) {
		$("#subscribe_change").validate({
			
			rules: {
				subscribe_id: {
					required: true
				},
				status: {
					required: true
				},
				resone: {
					required: true
				}
			},
			submitHandler: function(form) {
				var $formData = new FormData(form);
				$.ajax({
					url: '{{url('/subscribe-change')}}' ,
					type: "post",
					dataType: 'json',
					data: $formData,
					contentType:false,
					cache:false,
					processData:false,
					success: function( response ) {
						$('#subscribe_change').trigger('reset');
						$('#resone').modal('hide');
						$('#subscription_log').DataTable().draw(true);
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
		$('#subscription_log').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: {
	        	url: '{{ url("/datatable/subscription_log/")}}',
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
                 { data: 'subscribe_id' },
                 { data: 'user',orderable: false,searchable: false},
                 { data: 'agent',orderable: false,searchable: false},
                 { data: 'software',name:'softwareDetails.software_title',orderable: false},
                 { data: 'variation_name',name:'softwareVariationDetails.software_variation_name',orderable: false},
                 { data: 'subscribe_date',orderable: false},
                 { data: 'subscribe_activation_date',orderable: false},
                 { data: 'subscribe_amount'},
                 { data: 'subscribe_status',name:'subscribe_status',searchable: false},
                 { data: 'action',orderable: false,searchable: false}
              ]
	     });
	});

</script>
@endsection