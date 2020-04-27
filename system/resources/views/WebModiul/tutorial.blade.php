@extends('Layouts.SuperAdminDashboard')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-md-12">
		<div class="row">
	        	<div class="col-sm-6">
		      	<div class="ibox float-e-margins">
		      		<h3>Add Tutorial</h3>
		      		<div class="ibox-content">
		      			@if(isset($tutorial))
		  				<form action="{{url('/tutorial-update')}}" method="post">
	  					@else
		  				<form action="{{url('/tutorial-submit')}}" method="post">
	  					@endif
		  				@csrf
			  				@if(isset($tutorial))
			  				<input type="hidden" name="tutorial_id" value="{{$tutorial->tutorial_id}}">
			  				@endif	  
			  				@include('message')					
		  					<div class="form-group">
		  						<label>Title</label>
		  						<input type="text" name="title" class="form-control" value="@if(isset($tutorial)){{$tutorial->title}}@endif">
		  					</div>
		  					<div class="form-group">
		  						<label>Tutorial Link</label>
		  						<input type="text" name="link" class="form-control" value="@if(isset($tutorial)){{$tutorial->link}}@endif">
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
	        <div class="col-sm-6">
	        	<div class="ibox">
	        		<h3>All Tutorial</h3>
	        		<div class="ibox-content">
	        			<table class="table table-bordered table-responsive text-center">
		            		<thead>
		            			<tr>
		            				<th class="text-center">Title</th>
		            				<th class="text-center">Link</th>
		            				<th class="text-center">Created At</th>
		            				<th class="text-center">Status</th>
		            				<th class="text-center">Action</th>
		            			</tr>
		            		</thead>
		            		<tbody>
		            			@if(isset($tutorials))
		            			@foreach($tutorials as $tutorial)
		            			<tr>
		            				<td>{{$tutorial->title}}</td>
		            				<td>{{$tutorial->link}}</td>
		            				<td>{{ Carbon\Carbon::parse($tutorial->created_at)->format('H:i:s d-m-Y') }}</td>
		            				<td>
		            					@if($tutorial->status=="active")
		            					<button type="button" class="btn btn-primary btn-xs">Active</button>
		            					@elseif($tutorial->status=="deactive")
		            					<button type="button" class="btn btn-danger btn-xs">Deactive</button>
		            					@endif
		            				</td>
		            				<td>
		            					<div class="btn-group">
		            						<button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">Action<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>
			            					<ul class="dropdown-menu pull-right">
				            					<li><a href="{{$tutorial->link}}" target="_blank"><i class="fa fa-eye"></i> View</a></li>
				            					<li><a href="{{url('/tutorial-edit/'.$tutorial->tutorial_id)}}" ><i class="fa fa-edit"></i> Edit</a></li>
				            					<li><a href="{{url('/tutorial-status/'.$tutorial->tutorial_id)}}" ><i class="fa fa-ioxhost"></i> Change Status</a></li>
				            					<li><a href="javascript:void(0)" tutorial_id="{{$tutorial->tutorial_id}}" class="tutorial_delete" ><i class="fa fa-trash"></i> Delete</a></li>
				            				</ul>
				            			</div>
		            				</td>
		            			</tr>
		            			@endforeach
		            			@endif
		            		</tbody>
	        			</table>
	        		</div>
	        	</div>		
	        </div>
	    </div>
	 </div>
</div>               
@endsection
@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script type="text/javascript">
	$(document).on("click",".tutorial_delete", function(){
		var tutorial_id = $(this).attr('tutorial_id');
		swal({
			title: "Delete",
			text: "Are you sure?",
			icon: "warning",
			buttons: true,
			dangerMode: true,
		})
		.then((willDelete) => {
			if (willDelete) {
				
				var url = '{{ route("tutorial-delete", ":id") }}';
				url = url.replace(':id', tutorial_id);
				window.location.href=url;
			}
		});
	});
</script>
@endsection