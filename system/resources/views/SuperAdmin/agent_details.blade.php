@extends('Layouts.SuperAdminDashboard')

@section('content')

<div class="wrapper wrapper-content animated fadeInRight">
	<div class="ibox-content  m-b-sm border-bottom">
		<div class="row">
			<div class="col-sm-4">
				<div class="well">
					<label class="control-label"><?php echo trans('id'); ?> :</label>
					{{$user_data->user_id}}
					<br>
					<label class="control-label"><?php echo trans('name'); ?> :</label>
					{{$user_data->userDetails->first_name.' '.$user_data->userDetails->last_name}}
					<br>
					<label class="control-label"><?php echo trans('contact'); ?> :</label>
					{{$user_data->userDetails->phone}}
					<br>
					<label class="control-label"><?php echo trans('address'); ?> :</label>
					{{$user_data->userDetails->address}}
				</div>
			</div>
		</div>
	</div>

    
	<div class="ibox float-e-margins">
		<div class="row">
			<div class="col-sm-12">
				<h3>Transaction Log</h3>
		        <div class="ibox-content">
		        	<div class="table-responsive">
			        	<table class="table table-bordered table-striped dataTable" id="transaction_log">
							<thead>
								<tr>
									<th class='text-center'>Type</th>
									<th class='text-center'>Subscribe Id</th>
									<th class='text-center'>Payment Id</th>
									<th class='text-center'>Payment Date</th>
									<th class='text-center'>Amount</th>
									<th class='text-center'>Details</th>
									<th class='text-center'>Status</th>
									<th class='text-center'>Document</th>
									<th class='text-center'>Payment note</th>
									<th class='text-center'>Pay date</th>
									<th class='text-center'>Action</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="ibox float-e-margins">
		<h3>Commission Log</h3>
        <div class="ibox-content">
        	<div class="table-responsive">
	        	<table class="table table-bordered table-striped dataTable" id="commision_log_datatable">
					<thead>
						<tr>
							<th class='text-center'>ID</th>
							<th class='text-center'>Previous rate</th>
							<th class='text-center'>New rate</th>
							<th class='text-center'>Note</th>
							<th class='text-center'>Document</th>
							<th class='text-center'>Date</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
<div id="AgentPayModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="javascript:void(0)" method="post" enctype="multipart/form-data" id="agent_pay_form">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Agent Pay</h4>
				</div>
				<div class="modal-body">
	  				<input type="hidden" name="payment_id" id="payment_id">
	  				<div class="form-group">
	  					<label>Amount:</label>
						<input type="text" name="amount" class="form-control" id="amount" readonly>
					</div>
	  				<div class="form-group">
	  					<label>Note:</label>
						<textarea class="form-control" name="payment_note"></textarea>
					</div>
					<div class="form-group">
						<label>Related document:</label>
						<input type="file" name="payment_document" class="form-control-file" >
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" onclick="$('#agent_pay_form').trigger('reset')" >Close</button>
					<button class="btn btn-primary" type="submit" id="save">Submit</button>
				</div>
  			</form>
		</div>
	</div>
</div>
@endsection

@section('script')

<script type="text/javascript">
	$(document).on("click",".agent_pay", function(){
		var amount = $(this).attr('amount');
		var payment_id = $(this).attr('payment_id');
		$('#AgentPayModal').modal('show');
		$("#amount").val(amount);
		$("#payment_id").val(payment_id);
	});

	if ($("#agent_pay_form").length > 0) {
		$("#agent_pay_form").validate({
			
			rules: {
				payment_note: {
					required: true
				},

			},
			submitHandler: function(form) {
				var $formData = new FormData(form);
				$.ajax({
					url: '{{url('/agent-pay')}}' ,
					type: "post",
					dataType: 'json',
					data: $formData,
					contentType:false,
					cache:false,
					processData:false,
					success: function( response ) {
						$('#agent_pay_form').trigger('reset');
						$('#AgentPayModal').modal('hide');
						$('#transaction_log').DataTable().draw(true);
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

		$('#commision_log_datatable').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: {
	         url: '{{ url("/datatable/commision_log_datatable/".$user_data->user_id)}}',
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
				{ data: 'commission_id' },
				{ data: 'previous_rate'},
				{ data: 'new_rate'},
				{ data: 'commission_note',orderable: false,searchable: false},
				{ data: 'document',orderable: false,searchable: false},
				{ data: 'created_at'}
			]
	     });

		$('#transaction_log').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: {
	         url: '{{ url("/datatable/transaction_log/".$user_data->user_id)}}',
	         type: 'GET',
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
				{ data: 'payment_type'},
				{ data: 'subscribe_id',orderable: false},
				{ data: 'subscribe_payment_id',orderable: false},
				{ data: 'payment_date',name: 'payment_date'},
				{ data: 'payment_amount',orderable: false},
				{ data: 'payment_details',orderable: false},
				{ data: 'payment_status',searchable: false},
				{ data: 'document',orderable: false,searchable: false},
				{ data: 'pay_note',orderable: false,searchable: false},
				{ data: 'pay_date',name: 'pay_date'},
				{ data: 'action',orderable: false,searchable: false}
			]
	     });
	});
</script>
@endsection