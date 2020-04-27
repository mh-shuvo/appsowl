@extends('Layouts.SuperAdminDashboard')

@section('style')
<link href="{{asset('dashboard/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css')}}" rel="stylesheet">
@endsection
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-md-2"></div>
		<div class="col-md-8">
		  	<div class="ibox float-e-margins">
		  		<div class="ibox-title">
		  			<h2>Sms Portal</h2>
		  		</div>
		  		<div class="ibox-content">
					<form action="javascript:void(0)" id="send_message_form" enctype="multipart/form-data">
						<div class="form-group">
							<div class="radio radio-success radio-inline" >
	                            <input type="radio" id="all_user" class="bluk" value="1" name="bulk" >
	                            <label for="all_user" > All user </label>
	                        </div>
	                        <div class="radio radio-success radio-inline" >
	                            <input type="radio" id="all_agent" class="bluk" value="2" name="bulk" >
	                            <label for="all_agent" > All agent </label>
	                        </div>
	                        <div class="radio radio-danger radio-inline" >
	                            <input type="radio" id="custom" class="custom" value="3" name="bulk" checked >
	                            <label for="custom" > Custom </label>
	                        </div>
	                    </div>
						<div class="form-group">
							<label>Number</label>
							<input type="text" name="number" id="number" class="form-control" >
						</div>
						<div class="form-group">
							<label>Message</label>
							<textarea class="form-control" name="message"></textarea>
						</div>
						<div class="form-group">
							<button class="btn btn-primary" type="submit" id="save">Send</button>
						</div>
					</form>
		  		</div>
		    </div>
		</div>
		<div class="col-md-2"></div>
	</div>
</div>               
@endsection
@section('script')
<script type="text/javascript">

	$(document).on("click",".bluk", function(){
		document.getElementById('number').readOnly = true;
	});

	$(document).on("click",".custom", function(){
		document.getElementById('number').readOnly = false;
	});

	$("#send_message_form").validate({			
		rules: {
			bulk: {
				required: true
			},
			message: {
				required: true
			},

		},
		submitHandler: function(form) {
			$("form input").removeClass('is-invalid').removeClass('is-valid');
			$(".invalid-feedback").remove();

			var $formData = new FormData(form);
			$.ajax({
				url: '{{url('/sms-send-submit')}}' ,
				type: "post",
				dataType: 'json',
				data: $formData,
				contentType:false,
				cache:false,
				processData:false,
				success: function( response ) {

					if (response.error=='true') {
						$('#number').addClass('is-invalid').removeClass('is-valid');
						$('#number').after(
						$("<em class='invalid-feedback' style='color:red;'>"+response.msg+"</em>")
						);
					}else{
						$('#send_message_form').trigger('reset');
						$('#send_message_modal').modal('hide');
						$('#user_datatable').DataTable().draw(true);
						
						toastr.options = {
						  "closeButton": true,
						  "debug": false,
						  "progressBar": true,
						  "preventDuplicates": false,
						  "positionClass": "toast-top-right",
						  "onclick": null
						}
						if (response.status=='success') {
							toastr.success('',response.msg);
						}else{
							toastr.error('',response.msg);
						}
					}	
				}
			});
		}
	})
</script>
@endsection