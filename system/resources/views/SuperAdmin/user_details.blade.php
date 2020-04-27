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
			<div class="col-sm-4">
				<div class="well">
					<label class="control-label">Total Subscribe : {{$user_data->subscribtion->count()}}</label>
					<br>
					<label class="control-label">Total Plugins : {{$plugins}} </label>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="well">
					<h1>Available balance : <label class="control-label">{{$balance}}</label>
					<a href="javascript:void(0)" class="add_fund" user_id="{{$user_data->user_id}}"><i class="fa fa-plus-square"></i></a></h1>
				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<h3>Subscribe Details</h3>
				<div class="ibox-content">
					<div class="table-responsive">
	                    <table class="table table-bordered table-striped dataTable" id="user_subscribe_details">
	                    	<thead>
	                    		<tr>
									<th class="text-center">Type</th>
									<th class="text-center">Name</th>
									<th class="text-center">Start</th>
									<th class="text-center">Renew</th>
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

	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<h3>Fund Log</h3>
				<div class="ibox-content">
					<div class="table-responsive">
	                    <table class="table table-bordered table-striped dataTable" id="fund_log_datatable">
	                    	<thead>
	                    		<tr>
									<th class="text-center">SL</th>
									<th class="text-center">Type</th>
									<th class="text-center">Amount</th>
									<th class="text-center">Charge</th>
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
			@csrf
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Resone</h4>
				</div>
				<div class="modal-body">
	  				<input type="hidden" name="subscribe_id" id="subscribe_id">
					<input type="hidden" name="todo" id="todo">
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
<div id="payment_details_modal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Fund Details</h4>
			</div>
			<div class="modal-body">
  				<div class="form-group">
  					<label>Payment Id :</label> <label id="r_payment_id"></label>
				</div>
  				<div class="form-group">
  					<label>Type :</label> <label id="payment_type"></label>
				</div>
  				<div class="form-group">
  					<label>Load date :</label> <label id="payment_load_date"></label>
				</div>
  				<div class="form-group">
  					<label>Voucher :</label> <label id="voucher_id"></label>
				</div>
  				<div class="form-group">
  					<label>Office pay by :</label> <label id="office_payment_by"></label>
				</div>				
  				<div class="form-group">
  					<label>Amount :</label> <label id="payment_amount"></label>
				</div>
  				<div class="form-group">
  					<label>Charge :</label> <label id="payment_charge"></label>
				</div>
  				<div class="form-group">
  					<label>Discount :</label> <label id="payment_discount"></label>
				</div>
  				<div class="form-group">
  					<label>Total amount :</label> <label id="payment_total_amount"></label>
				</div>
  				<div class="form-group">
  					<label>Method :</label> <label id="payment_method"></label>
				</div>
				<div class="form-group">
					<label>Time :</label> <label id="payment_time"></label>
				</div>
				<div class="form-group">
					<label>Note :</label> <label id="payment_note"></label>
				</div>
				<div class="form-group">
					<label>Status :</label> <label id="payment_status"></label>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" onclick="$('#payment_details_modal').trigger('reset')" >Close</button>
			</div>
		</div>
	</div>
</div>
<div id="add_fund_modal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="javascript:void(0)" id="add_fund_form" enctype="multipart/form-data">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Add Fund</h4>
				</div>
				<div class="modal-body">
	  				<input type="hidden" name="user_id" id="user_id">
	  				<div class="form-group date" id="datepicker">
						<label>Available date</label>
						<div class="input-group date">
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							<input type="text" class="form-control" name="payment_load_date" readonly />
						</div>
					</div>
	  				<div class="form-group">
	  					<label>Amount</label>
						<input type="text" name="payment_amount" class="form-control" >
					</div>
	  				<div class="form-group">
	  					<label>Charge</label>
						<input type="text" name="payment_charge" class="form-control" >
					</div>
	  				<div class="form-group">
	  					<label>Note</label>
	  					<textarea name="payment_note" class="form-control"></textarea>
					</div>
					<div class="form-group">
						<label>Document</label>
						<input type="file" name="document" class="form-control-file" >
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" onclick="$('#add_fund_form').trigger('reset')" >Close</button>
					<button class="btn btn-primary" type="submit" id="save">Submit</button>
				</div>
  			</form>
		</div>
	</div>
</div>
@endsection

@section('script')
<script type="text/javascript">

	if ($("#add_fund_form").length > 0) {
		$("#add_fund_form").validate({
			
			rules: {
				payment_load_date: {
					required: true
				},
				payment_amount: {
					required: true,
					digits:true
				},
				payment_charge: {
					digits:true
				}
			},
			submitHandler: function(form) {
				var $formData = new FormData(form);
				$.ajax({
					url: '{{url('/add-fund-submit')}}' ,
					type: "post",
					dataType: 'json',
					data: $formData,
					contentType:false,
					cache:false,
					processData:false,
					success: function( response ) {
						location.reload();						
					}
				});
			}
		})
	}

	$(document).on("click",".payment_details", function(){
		var payment_id = $(this).attr('payment_id');
		$.ajax({
			url: '{{url('/payment-details')}}/'+payment_id ,
			type: "get",
			dataType: 'json',
			success: function( response ) {
				$('#payment_details_modal').modal('show');
				$("#r_payment_id").html(response.payment_id);
				$("#payment_type").html(response.payment_type);
				$("#payment_load_date").html(response.payment_load_date);
				$("#voucher_id").html(response.voucher_id);
				$("#office_payment_by").html(response.office_payment_by);
				$("#payment_amount").html(response.payment_amount);
				$("#payment_charge").html(response.payment_charge);
				$("#payment_discount").html(response.payment_discount);
				$("#payment_total_amount").html(response.payment_total_amount);
				$("#payment_method").html(response.payment_method);
				$("#payment_time").html(response.payment_time);
				$("#payment_note").html(response.payment_note);
				$("#payment_status").html(response.payment_status);
			}
		});
	});

	$(document).on("click",".add_fund", function(){
		var user_id = $(this).attr('user_id');
		$('#add_fund_modal').modal('show');
		$("#user_id").val(user_id);
	});

	$(document).ready( function () {
		$('#user_subscribe_details').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: {
	        	url: '{{ url("/datatable/user_subscribe_details/".$user_data->user_id)}}',
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
	                 { data: 'subscribe_type' },
	                 { data: 'name',orderable: false,searchable: false},
	                 { data: 'start',name: 'subscribe_date',orderable: false},
	                 { data: 'renew',name: 'invoices.subscribe_end_date',orderable: false},
	                 { data: 'status',name: 'subscribe_status',searchable: false},
	                 { data: 'action',orderable: false,searchable: false}
	              ]
	     });

		$('#fund_log_datatable').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: {
	        	url: '{{ url("/datatable/fund_log_datatable/".$user_data->user_id)}}',
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
	                 { data: 'payment_id' },
	                 { data: 'payment_type'},
	                 { data: 'payment_amount',orderable: false},
	                 { data: 'payment_charge',searchable: false,orderable: false},
	                 { data: 'status',searchable: false,orderable: false},
	                 { data: 'action',searchable: false,orderable: false}
	            ]
	     });
	});

	$(document).on("click",".change_status", function(){
		var subscribe_id = $(this).attr('subscribe_id');
		var todo = $(this).attr('todo');
		$('#resone').modal('show');
		$("#subscribe_id").val(subscribe_id);
		$("#todo").val(todo);
	});

	if ($("#subscribe_change").length > 0) {
		$("#subscribe_change").validate({
			
			rules: {
				resone: {
					required: true
				},

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
						$('#user_subscribe_details').DataTable().draw(true);
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