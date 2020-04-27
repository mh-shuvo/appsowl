@extends('Layouts.SuperAdminDashboard')
@section('style')
<style type="text/css">
	.add_btn{margin-top: -42px;}
</style>
@endsection
@section('content')

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
        	<div class="ibox float-e-margins">
				<div class="ibox-title">
        			<h2>Subscribe Details : {{ $check_result[0]->user->userDetails->first_name." ".$check_result[0]->user->userDetails->last_name }}</h2>
					<button type="button" class="btn btn-primary add_btn pull-right" data-toggle="modal" data-target="#add"><i class="fa fa-plus-circle"></i> Add</button>
				</div>
                <div class="ibox-content">
                	<div class="table-responsive">
	                	<table class="table table-bordered table-striped text-center dataTable" id="subscribe_details_datatable">
							<thead>
								<tr>
									<th class="text-center">Subscribe ID</th>
									<th class="text-center">Software</th>
									<th class="text-center">Software Variation</th>
									<th class="text-center">Start Date</th>
									<th class="text-center">End Date</th>
									<th class="text-center">Payment Amount</th>
									<th class="text-center">Transaction ID</th>
									<th class="text-center">Month</th>
									<th class="text-center">Payment Time</th>
									<th class="text-center">Payment Status</th>
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
@endsection

@section('script')
<script>
	$(document).ready( function () {
		$('#subscribe_details_datatable').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: {
	        	url: '{{ url("/datatable/subscribe_details_datatable/".$subscribe_id)}}',
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
                 { data: 'subscribe_id'},
                 { data: 'software'},
                 { data: 'software_variation'},
                 { data: 'start_date',orderable: false},
                 { data: 'end_date',orderable: false},
                 { data: 'payment_amount',orderable: false},
                 { data: 'transaction_iD',orderable: false},
                 { data: 'month'},
                 { data: 'payment_time',orderable: false},
                 { data: 'status'},
                 { data: 'action',orderable: false}
              ]
	     });
	});

</script>
@endsection