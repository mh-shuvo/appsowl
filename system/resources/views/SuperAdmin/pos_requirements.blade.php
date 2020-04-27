@extends('Layouts.SuperAdminDashboard')

@section('content')

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
        	<div class="ibox float-e-margins">
				<h3>Pos Requirements List</h3>
                <div class="ibox-content">
                	<div class="table-responsive">
	                	<table class="table table-bordered table-striped dataTable" id="pos_requirements_datatable">
							<thead>
								<tr>
									<th class='text-center'>ID</th>
									<th class='text-center'>User</th>
									<th class='text-center'>Company Name</th>
									<th class='text-center'>Company Website</th>
									<th class='text-center'>Company Email</th>
									<th class='text-center'>Company Phone</th>
									<th class='text-center'>Company Address</th>
									<th class='text-center'>Company City</th>
									<th class='text-center'>Company Country</th>
									<th class='text-center'>Company Postcode</th>
									<th class='text-center'>Vat No</th>
									<th class='text-center'>Vat Unit</th>
									<th class='text-center'>Status</th>
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
<script>

	$(document).ready( function () {
		$('#pos_requirements_datatable').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: {
	        	url: '{{ url("/datatable/pos_requirements_datatable/")}}',
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
                 { data: 'pos_requirement_id' },
                 { data: 'title',orderable: false,searchable: false},
                 { data: 'company_name'},
                 { data: 'company_website'},
                 { data: 'company_email'},
                 { data: 'company_phone'},
                 { data: 'company_address'},
                 { data: 'company_city'},
                 { data: 'company_country'},
                 { data: 'company_postcode'},
                 { data: 'vat_no'},
                 { data: 'vat_unit'},
                 { data: 'status',orderable: false,searchable: false}
              ]
	     });
	});

</script>
@endsection