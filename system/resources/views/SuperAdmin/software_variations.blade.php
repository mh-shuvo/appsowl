@extends('Layouts.SuperAdminDashboard')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
        	<div class="ibox float-e-margins">
				<h3>Variation List</h3>

				<div class="ibox-content">
					<div class="table-responsive">
	                	<table class="table table-bordered table-striped" id="variation_datatable">
							<thead>
								<tr>
									<th class='text-center'>SL</th>
									<th class='text-center'>Title</th>
									<th class='text-center'>Price</th>
									<th class='text-center'>Subscribe Fee</th>
									<th class='text-center'>In Advance</th>
									<th class='text-center'>Status</th>
									<th class='text-center'>Created</th>
									<th class='text-center'>Updated</th>
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
<div id="variation_edit_modal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Variation Edit</h4>
			</div>
			<div class="modal-body">
  				<form action="javascript:void(0)" id="variation_edit_submit" enctype="multipart/form-data">
  				<input type="hidden" name="variation_id" id="variation_id" >
				<div class="form-group">
					<label>Title</label>
					<input type="text" name="title" id="title" class="form-control" >
				</div>
				<div class="form-group">
					<label>Price</label>
					<input type="text" name="price" id="price" class="form-control" >
				</div>
				<div class="form-group">
					<label>Subscription Charge</label>
					<input type="text" name="sub_price" id="sub_price" class="form-control" >
				</div>
				<div class="form-group">
					<label>Amount</label>
					<select name="in_advance" class="form-control" id="in_advance">
						<option value="true">True</option>
						<option value="false">False</option>
					</select>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" onclick="$('#variation_edit_submit').trigger('reset')" >Close</button>
				<button class="btn btn-primary" type="submit" id="save">Submit</button>
			</div>
  			</form>
		</div>
	</div>
</div>
@endsection

@section('script')

<script type="text/javascript">

	$(document).on("click",".variation_edit", function(){
		var variation_id = $(this).attr('variation_id');
		var title = $(this).attr('title');
		var price = $(this).attr('price');
		var sub_price = $(this).attr('sub_price');
		var in_advance = $(this).attr('in_advance');
		$('#variation_edit_modal').modal('show');
		$("#variation_id").val(variation_id);
		$("#title").val(title);
		$("#price").val(price);
		$("#sub_price").val(sub_price);
		$("#in_advance").val(in_advance);
	});

	if ($("#variation_edit_submit").length > 0) {
		$("#variation_edit_submit").validate({
			
			rules: {
				title: {
					required: true
				},
				price: {
					required: true
				},
				sub_price: {
					required: true
				},
				in_advance: {
					required: true
				},

			},
			submitHandler: function(form) {
				var $formData = new FormData(form);
				$.ajax({
					url: '{{url('/variation-edit')}}' ,
					type: "post",
					dataType: 'json',
					data: $formData,
					contentType:false,
					cache:false,
					processData:false,
					success: function( response ) {
						$('#variation_edit_submit').trigger('reset');
						$('#variation_edit_modal').modal('hide');
						$('#variation_datatable').DataTable().draw(true);
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

		$('#variation_datatable').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: {
	        	url: "{{ url('/datatable/variation_datatable/'.$software_id) }}",
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
	                 { data: 'software_variation_id' },
	                 { data: 'software_variation_name'},
	                 { data: 'software_variation_price'},
	                 { data: 'software_subscribe_fee' },
	                 { data: 'software_subscribe_in_advance' },
	                 { data: 'software_variation_status' },
	                 { data: 'created_at'},
	                 { data: 'updated_at'},
	                 { data: 'action'}
	              ]
	     });
	});
</script>
@endsection