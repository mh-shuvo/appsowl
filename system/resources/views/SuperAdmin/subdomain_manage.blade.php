@extends('Layouts.SuperAdminDashboard')

@section('style')
<style type="text/css">
	th{text-align: center;}
</style>
@endsection

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-sm-12">
		<div class="ibox float-e-margins">
			<h3>Manage Subdomain</h3>
			<div class="ibox-content">
				<div class="table-responsive">
					<table class="table table-bordered table-striped dataTable" id="sub_domain_datatable">
						<thead>
							<tr>
								<th>ID</th>
								<th>User</th>
								<th>Subdomain</th>
							</tr>
						</thead>
					</table>
				</div>	
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script type="text/javascript">

	$(document).ready( function () {
		$('#sub_domain_datatable').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: {
	        	url: '{{ url("/datatable/sub_domain_datatable/")}}',
	        	type: 'GET'
	        },
	        columns: [
	                 { data: 'domain_id' },
	                 { data: 'user_id'},
	                 { data: 'sub_domain',orderable: false}
	              ]
	     });
	});

</script>
@endsection