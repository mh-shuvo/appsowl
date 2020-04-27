@extends('Layouts.SuperAdminDashboard')

@section('style')
<style>
	.add_btn{margin-top: -7px;}
</style>
@endsection

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-sm-12">
		<div class="ibox float-e-margins">
			<h3>Voucher List</h3>
			<div class="ibox-title">
				<button class="btn btn-primary pull-right add_btn" data-toggle="modal" data-target="#AddPromocodeModal"> <i class="fa fa-plus-circle"></i> Add</button>
			</div>
			<div class="ibox-content">
				<div class="table-responsive">
					<table class="table table-bordered table-striped" id="voucher_datatable">
				       <thead>
				          <tr>
				             <th class="text-center">SL</th>
				             <th class="text-center">Title</th>
				             <th class="text-center">Code</th>
				             <th class="text-center">Amount</th>
				             <th class="text-center">Price</th>
				             <th class="text-center">Generat</th>
				             <th class="text-center">Availablity</th>
				             <th class="text-center">Used</th>
				             <th class="text-center">Note</th>
				             <th class="text-center">Document</th>
				             <th class="text-center">Status</th>
				             <th class="text-center">Create</th>
				             <th class="text-center">Action</th>
				          </tr>
				       </thead>
				    </table>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="AddPromocodeModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="javascript:void(0)" id="promocode_add" enctype="multipart/form-data">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Add Voucher</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label>Title</label>
					<input type="text" name="title" class="form-control" >
				</div>
				<div class="form-group">
					<label>Code</label>
					<input type="text" name="code" class="form-control" >
				</div>
				<div class="form-group">
					<label>Amount</label>
					<input type="text" name="amount" class="form-control" >
				</div>
				<div class="form-group">
					<label>Price</label>
					<input type="text" name="price" class="form-control" >
				</div>
				<div class="form-group">
					<label>Note</label>
					<textarea name="note" class="form-control"></textarea>
				</div>
				<div class="form-group">
					<label>Document:</label>
					<input type="file" name="document" class="form-control-file" >
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" onclick="$('#promocode_add').trigger('reset')" >Close</button>
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
	  				<input type="hidden" name="voucher_id" id="voucher_id">
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
<div id="notemodal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Resone</h4>
			</div>
			<div class="modal-body">
  				<div class="form-group">
  					<textarea name="resone" class="form-control" id="note" readonly></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
@endsection
@section('script')

<script>

	$(document).ready( function () {

		$('#voucher_datatable').DataTable({
			processing: true,
	        serverSide: true,
	        ajax: {
	       	url: "{{ url('/datatable/voucher_datatable') }}",
	        type: 'GET',
	    },

	    dom: '<"html5buttons"B>lTfgitp',
	    buttons: [
	    {extend: 'copy'},
	    {extend: 'csv'},
	    {extend: 'excel', title: 'Promotion-Excel'},
	    {extend: 'pdf', title: 'Promotion-Pdf'},
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
				{ data: 'voucher_id' },
				{ data: 'voucher_title' },
				{ data: 'voucher_code',orderable: false },
				{ data: 'voucher_amount' },
				{ data: 'voucher_price' },
				{ data: 'generated_by', name: 'generate.first_name'},
				{ data: 'voucher_available',orderable: false,searchable: false},
				{ data: 'user_id'},
				{ data: 'voucher_note',orderable: false,searchable: false},
				{ data: 'voucher_document',orderable: false,searchable: false},
				{ data: 'voucher_status',searchable: false},
				{ data: 'created_at'},
				{ data: 'action',orderable: false,searchable: false}
			]
		});
	});

	$(document).on("click",".note", function(){
		var note = $(this).attr('note');
		$('#notemodal').modal('show');
		$("#note").val(note);
	});

	$(document).on("click",".voucher_status_change", function(){
		
		var voucher_id = $(this).attr('voucher_id');
		$('#resone').modal('show');
		$("#voucher_id").val(voucher_id);
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
					url: '{{url('/voucher-status')}}' ,
					type: "post",
					dataType: 'json',
					data: $formData,
					contentType:false,
					cache:false,
					processData:false,
					success: function( response ) {
						$('#status_change').trigger('reset');
						$('#resone').modal('hide');
						$('#voucher_datatable').DataTable().draw(true);
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

	$(document).on("click",".swal-button--confirm", function(){
		$('#voucher_datatable').DataTable().draw(true);
	});

	$(document).on("click",".swal-button--confirm", function(){
		$('#modal').modal('hide');
	});
	
	if ($("#promocode_add").length > 0) {
		$("#promocode_add").validate({
			
			rules: {
				title: {
					required: true
				},
				code: {
					required: true,
					minlength:10,
					maxlength:10,
				},
				amount: {
					required: true,
					digits:true,
				},
				price: {
					required: true,
				}

			},
			submitHandler: function(form) {
				var $formData = new FormData(form);
				$.ajax({
					url: '{{url('/promocode-submit')}}' ,
					type: "post",
					dataType: 'json',
					data: $formData,
					contentType:false,
					cache:false,
					processData:false,
					success: function( response ) {
						console.log(response.message);
						$('#promocode_add').trigger('reset');
						$('#AddPromocodeModal').modal('hide');
						swal(response.msg, {
							icon: "success",
							text: response.msg,
						});
					}
				});
			}
		})
	}
</script>
@endsection