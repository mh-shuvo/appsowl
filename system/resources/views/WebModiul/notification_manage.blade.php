@extends('Layouts.SuperAdminDashboard')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-md-12">
	   <!--  <div class="row">
	    	<div class="col-sm-6">
		      	<div class="ibox float-e-margins">
		      		<div class="ibox-title">
		      			<h2>Add Notification</h2>
		      		</div>
		      		<div class="ibox-content">
		      			@if(isset($notification))
		  				<form action="{{url('/notification-update')}}" method="post">
	  					@else
	  					<form action="{{url('/notification-submit')}}" method="post">
	  					@endif
		  					@csrf
		  					@if(isset($notification))
		  					<input type="hidden" name="notification_id" value="{{$notification->notification_id}}">
		  					@endif
		  					@include('message')
		  					<div class="form-group">
		  						<label>Title</label>
		  						<input type="text" name="title" class="form-control" value="@if(isset($notification)){{$notification->title}}@endif">
		  					</div>
		  					<div class="form-group">
		  						<label>Message</label>
		  						<textarea class="form-control" name="message">@if(isset($notification)){{$notification->message}}@endif</textarea>
		  					</div>
		  					<div class="form-group">
		  						<label>Link</label>
		  						<input type="text" name="link" class="form-control" value="@if(isset($notification)){{$notification->link}}@endif">
				  			</div>
		  					<div class="form-group">
		  						<div class="row">
		  							<div class="col-sm-5">
		  								<button class="btn btn-success" type="submit">Submit</button>
		  							</div>
		  						</div>
		  					</div>
		  				</form>
		      		</div>
		      	</div>
		    </div>
	    </div> -->
	    <div class="row">
	        <div class="col-sm-12">
	        	<div class="ibox">
	        		<h3>Notification Manage</h3>
	        		<div class="ibox-title">
	        			<button class="btn btn-primary btn-sm pull-right add_notification" style="margin-top:-7px;"><i class="fa fa-plus-circle"></i> Add</button>
	        		</div>
	        		<div class="ibox-content">
	        			<table class="table table-bordered table-responsive text-center" id="notification_table">
		            		<thead>
		            			<tr>
		            				<th class="text-center">SL</th>
		            				<th class="text-center">Title</th>
		            				<th class="text-center">Message</th>
		            				<th class="text-center">Send At</th>
		            				<th class="text-center">Status</th>
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
<div class="modal fade" tabindex="-1" role="dialog" id="notification_modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add Notification</h4>
      </div>
      <form action="{{url('/notification-submit')}}" method="post" id="notification_form">
	      <div class="modal-body">

	      	<input type="hidden" name="notification_id" id="notification_id">

			<div class="form-group">
				<label>Title</label>
				<input type="text" name="title" id="notification_title" class="form-control" placeholder="Notification Title">
			</div>
			<div class="form-group">
				<label>Message</label>
				<textarea class="form-control" id="notification_message" name="message" placeholder="Notification Message"></textarea>
			</div>
			<div class="form-group">
				<label>Link</label>
				<input type="text" name="link" id="notification_link" class="form-control" placeholder="Notification Link">
			</div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button type="submit" class="btn btn-primary">Save changes</button>
	      </div>
 	 </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div id="resone" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="javascript:void(0)" id="status_change" enctype="multipart/form-data">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Resone</h4>
				</div>
				<div class="modal-body">
	  				<input type="hidden" name="notification_id" id="s_notification_id">
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
@endsection
@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script type="text/javascript">
	$(document).ready( function () {

 	$('#notification_table').DataTable({
         processing: true,
         serverSide: true,
         ajax: {
          url: "{{ url('/datatable/notification_datatable') }}",
          type: 'GET',
          data: function (d) {
          }
         },
         columns: [
                  { data: 'notification_id',name:'notification_id'},
                  { data: 'title',name:'title'},
                  { data: 'message',orderable: false},
                  { data: 'created_at',orderable: false},
                  { data: 'status',searchable:false},
                  { data: 'action',orderable: false,searchable:false}
               ]
      });
   });
	$(document).on('click','.add_notification',function(){
		$("#notification_modal").modal('toggle');
	});

	if ($("#notification_form").length > 0) {
		$("#notification_form").validate({
			
			rules: {
				title: {
					required: true
				},
				message: {
					required: true,
				},
				link: {
					required: true,
				}
			},
			submitHandler: function(form) {
				var $formData = new FormData(form);
				$.ajax({
					url: form.action ,
					type: form.method,
					dataType: 'json',
					data: $formData,
					contentType:false,
					cache:false,
					processData:false,
					success: function( response ) {
						$('#notification_form').trigger('reset');
						$('#notification_modal').modal('hide');
						$('#notification_table').DataTable().draw(true);
						
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
	$(document).on('click','.notification_edit',function(){
		var id = $(this).attr('notification_id');
		var title = $(this).attr('title');
		var message = $(this).attr('message');
		var link = $(this).attr('link');

		$("#notification_id").val(id);
		$("#notification_title").val(title);
		$("#notification_message").val(message);
		$("#notification_link").val(link);

		$('#notification_modal').modal('toggle');

	});
	$(document).on("click",".notification_status_change", function(){
		var notification_id = $(this).attr('notification_id');
		$('#resone').modal('show');
		$("#s_notification_id").val(notification_id);
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
					url: '{{url('/notification-status-change')}}' ,
					type: "post",
					dataType: 'json',
					data: $formData,
					contentType:false,
					cache:false,
					processData:false,
					success: function( response ) {
						$('#status_change').trigger('reset');
						$('#resone').modal('hide');
						$('#notification_table').DataTable().draw(true);
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
</script>
@endsection