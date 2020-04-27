@extends('Layouts.SuperAdminDashboard')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
        	<div class="ibox float-e-margins">
				<h3>Software List</h3>
				<div class="ibox-content">
					<div class="table-responsive">
	                	<table class="table table-bordered table-striped" id="software_datatable">
							<thead>
								<tr>
									<th class='text-center'>SL</th>
									<th class='text-center'>Title</th>
									<th class='text-center'>Base Price</th>
									<th class='text-center'>Status</th>
									<th class='text-center'>Created</th>
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
@endsection

@section('script')

<script type="text/javascript">

	$(document).ready( function () {

		$('#software_datatable').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: {
	        	url: "{{ url('/datatable/software_datatable') }}",
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
	                 { data: 'software_id' },
	                 { data: 'software_title'},
	                 { data: 'software_price'},
	                 { data: 'software_status' },
	                 { data: 'created_at'},
	                 { data: 'action'}
	              ]
	     });
	});
</script>
@endsection