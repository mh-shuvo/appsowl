@extends('Layouts.SuperAdminDashboard')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
        	<div class="ibox float-e-margins">
				<h3>Activity Log</h3>
				<div class="ibox-content">
					<div class="table-responsive">
	                	<table class="table table-bordered table-striped" id="activity_log_datatable">
							<thead>
								<tr>
									<th class='text-center'>Log ID</th>
									<th class='text-center'>Subject</th>
									<th class='text-center'>Resone</th>
									<th class='text-center'>Document</th>
									<th class='text-center'>Ip</th>
									<th class='text-center'>Agent</th>
									<th class='text-center'>Responsible</th>
									<th class='text-center'>Created</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
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

<script type="text/javascript">

	$(document).ready( function () {

		$('#activity_log_datatable').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: {
	        	url: "{{ url('/datatable/activity_log_datatable') }}",
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