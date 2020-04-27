@extends('Layouts.SuperAdminDashboard')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
        	<div class="ibox float-e-margins">
				<h3>Withdraw List</h3>
                <div class="ibox-content">
                	<div class="table-responsive">
	                	<table class="table table-bordered table-striped dataTable" id="withdraw_datatable">
							<thead>
								<tr>
									<th class="text-center">SL</th>
									<th class="text-center">Type</th>
									<th class="text-center">User</th>
									<th class="text-center">amount</th>
									<th class="text-center">Status</th>
									<th class="text-center">Created</th>
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
<div id="withdrawal_details_modal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Withdraw Details</h4>
			</div>
			<div class="modal-body">
  				<div class="form-group">
  					<label>Withdrawal Id :</label> <label id="d_withdrawal_id"></label>
				</div>
  				<div class="form-group">
  					<label>Withdraw By :</label> <label id="withdraw_by"></label>
				</div>
  				<div class="form-group">
  					<label>Type :</label> <label id="withdrawal_type"></label>
				</div>
  				<div class="form-group">
  					<label>Amount :</label> <label id="withdrawal_amount"></label>
				</div>
  				<div class="form-group">
  					<label>Charge :</label> <label id="withdrawal_charge"></label>
				</div>
  				<div class="form-group">
  					<label>Total amount :</label> <label id="withdrawal_total_amount"></label>
				</div>				
  				<div class="form-group">
  					<label>User Note :</label> <label id="withdrawal_note"></label>
				</div>
  				<div class="form-group">
  					<label>Method :</label> <label id="withdrawal_method"></label>
				</div>
  				<div class="form-group">
  					<label>Transaction Id :</label> <label id="withdrawal_transaction_id"></label>
				</div>
  				<div class="form-group">
  					<label>Status :</label> <label id="withdrawal_status"></label>
				</div>
  				<div class="form-group">
  					<label>Approve By :</label> <label id="withdrawal_approve_by"></label>
				</div>
				<div class="form-group">
					<label>Created :</label> <label id="created_at"></label>
				</div>
				<div class="form-group">
					<label>Updated :</label> <label id="updated_at"></label>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" onclick="$('#withdrawal_details_modal').trigger('reset')" >Close</button>
			</div>
		</div>
	</div>
</div>
<div id="withdrawal_pay_modal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="javascript:void(0)" id="withdrawal_pay_submit" enctype="multipart/form-data">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Withdrawal pay</h4>
				</div>
				<div class="modal-body">
	  				<input type="hidden" name="withdrawal_id" id="withdrawal_id">
	  				<div class="form-group">
	  					<input type="text" name="amount" id="amount" class="form-control" readonly>
					</div>
	  				<div class="form-group">
	  					<input type="text" name="transaction_id" class="form-control" placeholder="Enter Transaction Id">
					</div>
	  				<div class="form-group">
	  					<textarea name="note" class="form-control" placeholder="Transaction note"></textarea>
					</div>
					<div class="form-group">
						<input type="file" name="document" class="form-control-file" >
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" onclick="$('#withdrawal_pay_modal').trigger('reset')" >Close</button>
					<button class="btn btn-primary" type="submit" id="save">Submit</button>
				</div>
  			</form>
		</div>
	</div>
</div>
@endsection

@section('script')
<script>

	$(document).on("click",".withdrawal_pay", function(){		
		var amount = $(this).attr('amount');
		var withdrawal_id = $(this).attr('withdrawal_id');
		$('#withdrawal_pay_modal').modal('show');
		$("#withdrawal_id").val(withdrawal_id);
		$("#amount").val(amount);
	});

	if ($("#withdrawal_pay_submit").length > 0) {
		$("#withdrawal_pay_submit").validate({
			
			rules: {
				withdrawal_id: {
					required: true
				},
				amount: {
					required: true
				},
				transaction_id: {
					required: true
				},
				note: {
					required: true
				}
			},
			submitHandler: function(form) {
				var $formData = new FormData(form);
				$.ajax({
					url: '{{url('/withdrawal-pay')}}' ,
					type: "post",
					dataType: 'json',
					data: $formData,
					contentType:false,
					cache:false,
					processData:false,
					success: function( response ) {
						$('#withdrawal_pay_submit').trigger('reset');
						$('#withdrawal_pay_modal').modal('hide');
						$('#withdraw_datatable').DataTable().draw(true);
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

	$(document).on("click",".withdrawal_details", function(){
		var withdrawal_id = $(this).attr('withdrawal_id');
		$.ajax({
			url: '{{url('/withdraw-details')}}/'+withdrawal_id ,
			type: "get",
			dataType: 'json',
			success: function( response ) {
				$('#withdrawal_details_modal').modal('show');
				$("#d_withdrawal_id").html(response.withdrawal_id);
				$("#withdraw_by").html(response.withdraw_by);
				$("#withdrawal_type").html(response.withdrawal_type);
				$("#withdrawal_amount").html(response.withdrawal_amount);
				$("#withdrawal_charge").html(response.withdrawal_charge);
				$("#withdrawal_total_amount").html(response.withdrawal_total_amount);
				$("#withdrawal_note").html(response.withdrawal_note);
				$("#withdrawal_method").html(response.withdrawal_method);
				$("#withdrawal_transaction_id").html(response.withdrawal_transaction_id);
				$("#withdrawal_status").html(response.withdrawal_status);
				$("#withdrawal_approve_by").html(response.approved_by);
				$("#created_at").html(response.created_at);
				$("#updated_at").html(response.updated_at);
			}
		});
	});

	$(document).on("click",".change_status", function(){
		var status = $(this).attr('status');
		var withdrawal_id = $(this).attr('withdrawal_id');
		$.ajax({
			url: '{{url('/withdraw-status-change')}}/'+withdrawal_id+'/'+status ,
			type: "get",
			dataType: 'json',
			success: function( response ) {
				$('#withdraw_datatable').DataTable().draw(true);
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
	});

	$(document).ready( function () {
		$('#withdraw_datatable').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: {
	        	url: '{{ url("/datatable/withdraw_datatable")}}',
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
                 { data: 'withdrawal_id' },
                 { data: 'withdrawal_type',searchable: false},
                 { data: 'user',name: 'userDetails.first_name'},
                 { data: 'withdrawal_amount',orderable: false},
                 { data: 'withdrawal_status',name:'withdrawal_status',searchable: false},
                 { data: 'created',orderable: false,searchable: false},
                 { data: 'action',orderable: false,searchable: false}
              ]
	     });
	});

</script>
@endsection