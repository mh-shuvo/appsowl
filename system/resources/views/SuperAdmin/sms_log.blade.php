@extends('Layouts.SuperAdminDashboard')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
        	<div class="ibox float-e-margins">
				<h3>Sms Log</h3>
				<div class="ibox-content">
					<div class="table-responsive">
	                	<table class="table table-bordered table-striped" id="sms_log_datatable">
							<thead>
								<tr>
									<th class='text-center'>Log ID</th>
									<th class='text-center'>Sms ID</th>
									<th class='text-center'>Reciver</th>
									<th class='text-center'>Number</th>
									<th class='text-center'>Sms</th>
									<th class='text-center'>Sender</th>
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
<div id="smsbodymodal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Sms body</h4>
			</div>
			<div class="modal-body">
  				<div class="form-group">
  					<textarea class="form-control" id="smsbody" readonly></textarea>
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

		$('#sms_log_datatable').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: {
	        	url: "{{ url('/datatable/sms_log_datatable') }}",
	        	type: 'GET'
	        },

			dom: '<"html5buttons"B>lTfgitp',
			buttons: [
			{extend: 'copy'},
			{extend: 'csv'},
			{extend: 'excel', title: 'Sms-Log-Excel'},
			{extend: 'pdf', title: 'Sms-Log-Pdf'},
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
	                 { data: 'sms_log_id' },
	                 { data: 'messageid' },
	                 { data: 'send_to',name:'sended_to.first_name'},
	                 { data: 'number'},
	                 { data: 'body',orderable: false,searchable: false },
	                 { data: 'sender',name:'send_by.first_name'},
	                 { data: 'created_at'}
	              ]
	     });
	});

	$(document).on("click",".body", function(){
		var body = $(this).attr('body');
		$('#smsbodymodal').modal('show');
		$("#smsbody").val(body);
	});
</script>
@endsection