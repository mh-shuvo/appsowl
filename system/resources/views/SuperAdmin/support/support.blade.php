@extends('Layouts.SuperAdminDashboard')

@section('content')
<div class="col-sm-12">
	<div class="ibox float-e-margins">
		<h3>Support</h3>
		<div class="tabs-container">				
			<div class="tabs">
				<ul class="nav nav-tabs">
					<li class="active"><a data-toggle="tab" href="#High" aria-expanded="true">High</a></li>
					<li class=""><a data-toggle="tab" href="#Medium" aria-expanded="true">Medium</a></li>
					<li class=""><a data-toggle="tab" href="#Normal" aria-expanded="true">Normal</a></li>
				</ul>
				<div class="tab-content">
					<div id="High" class="tab-pane active">
						<div class="panel-body">
							<table class="table table-bordered table-striped dataTable" id="support_datatable">
								<thead>
                                    <th class="text-center">Ticket</th>
                                    <th class="text-center">User</th>
                                    <th class="text-center">Title</th>
                                    <th class="text-center">Created</th>
                                    <th class="text-center">Updated</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
								</thead>
							</table>
						</div>
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

		$('#support_datatable').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: {
	        	url: "{{ url('/datatable/support_datatable') }}",
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
	                 { data: 'log_id' },
	                 { data: 'subject'},
	                 { data: 'note'},
	                 { data: 'document' },
	                 { data: 'ip',orderable: false},
	                 { data: 'agent',orderable: false },
	                 { data: 'user_id' },
	                 { data: 'created_at'}
	              ]
	     });
	});

	$(document).on("click",".note", function(){
		var note = $(this).attr('note');
		$('#notemodal').modal('show');
		$("#note").val(note);
	});
</script>
@endsection