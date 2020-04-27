@extends('Layouts.SuperAdminDashboard')

@section('style')
<link href="{{asset('dashboard/css/plugins/iCheck/custom.css')}}" rel="stylesheet">
<link href="{{asset('dashboard/css/plugins/jasny/jasny-bootstrap.min.css')}}" rel="stylesheet">
<style>
    .ScrollStyle{
        height:500px;
        overflow-y: scroll;
        scroll-behavior: auto;
    }
</style>
@endsection

@section('content')
<div class="wrapper wrapper-content">
    <div class="row animated fadeInRight">
        <div class="col-lg-12">
	        <div class="ibox float-e-margins">
	            <div id="ibox-content">
	            	<div id="vertical-timeline" class="vertical-container light-timeline left-orientation">
	                    
	                    @if(isset($ticket))
	                    @foreach($ticket as $ticket_info)
						<div class="vertical-timeline-block">
	                        
                        	@if($ticket_info->reply_by=="user")
                            <div class="vertical-timeline-icon navy-bg">
                            	<i class="fa fa-user"></i>
                            </div>
                            @elseif($ticket_info->reply_by=="admin")
                            <div class="vertical-timeline-icon yellow-bg">
                                <i class="fa fa-user-md"></i>
                            </div>
                            @endif
	                        

	                        <div class="vertical-timeline-content">
                        		@if(!empty($ticket_info->ticket_title))
	                            <h2>{{ucwords($ticket_info->ticket_title)}}</h2>
                        	    @endif

                    	    	@if(!empty($ticket_info->ticket_details))
                    	        <p>{{ucwords($ticket_info->ticket_details)}}</p>
                    	        @elseif(!empty($ticket_info->ticket_message))
                    	        <p>{{ucwords($ticket_info->ticket_message)}}</p>
                    	        @endif
                    	        <span class="vertical-date">
                    	        	<small>From: {{ucwords($ticket_info->reply_by)}}</small><br>
                    	        	{{Carbon::parse($ticket_info->created_at)->format('d-m-Y H:i:s')}}
                    	        </span>
	                        </div>
	                    </div>
	                    @endforeach
	                    @endif	                    
	                </div>

	            </div>
	            <div class="container">
	                <div class="row MessageForm">

	                    <form action="javascript:void(0)" id="ticket_replay">

	                    	<input type="hidden" name="ticket_no" value="{{$ticket_info->ticket_for}}">
	                    	<input type="hidden" name="user_id" value="{{$ticket_info->user_id}}">
	                    	<div class="col-lg-12">
	                            <div class="ibox float-e-margins">
	                                <div class="ibox-content">
                          				<div class="form-group">
                        					<textarea class="form-control" rows="5" name="ticket_message"></textarea>
                        				</div>
	                                    <!-- <div class="fileinput fileinput-new input-group" data-provides="fileinput">
	                                        <div class="form-control" data-trigger="fileinput">
	                                        	<i class="glyphicon glyphicon-file fileinput-exists"></i>
	                                        	<span class="fileinput-filename"></span>
	                                        </div>
	                                        <span class="input-group-addon btn btn-default btn-file">
	                                        	<span class="fileinput-new">Select file</span>
	                                        	<span class="fileinput-exists">Change</span>
	                                        	<input type="file" name="chat_document">
	                                        </span>
	                                        <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
	                                    </div> -->
	                                	<button class="btn btn-primary btn-block" type="submit">Submit</button>
	                                </div>
	                            </div>
	                        </div>
	                    </form>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
</div>
@endsection

@section('script')
<script type="text/javascript" src="{{asset('dashboard/js/plugins/jasny/jasny-bootstrap.min.js')}}"></script>
<script>
	if ($("#ticket_replay").length > 0) {
		$("#ticket_replay").validate({
			
			rules: {
				ticket_message: {
					required: true
				}
			},
			submitHandler: function(form) {
				var $formData = new FormData(form);
				$.ajax({
					url: '{{url("/ticket-replay")}}' ,
					type: "post",
					dataType: 'json',
					contentType:false,
					cache:false,
					processData:false,
					data: $formData,
					success: function( response ) {
						$('#ticket_replay').trigger('reset');
						window.location.reload();
					}
				});
			}
		})
	}
</script>
@endsection