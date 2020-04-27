@extends('Layouts.SuperAdminDashboard')

@section('style')
<link href="{{asset('dashboard/css/plugins/summernote/summernote.css')}}" rel="stylesheet">
<link href="{{asset('dashboard/css/plugins/summernote/summernote-bs3.css')}}" rel="stylesheet">
@endsection

@section('content')
@php
	$body="";
	$page_title="Add";
	$btn_text="Save";
	if(isset($privacy)){
	$body= $privacy['body_text'];
	$document= $privacy['document'];
	$id= $privacy['t_c_id'];
	$page_title="Edit";
	$btn_text="Update";
	}
@endphp
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-sm-12">
		<div class="ibox float-e-margins">
			<h3> {{$page_title}} Privacy & Policy</h3>
			<div class="ibox-content">
				<!--form action="{{url('privacy/add')}}" method="POST" enctype="multipart/form-data"-->
				<form action="javascript:void(0)" id="privacy_form" enctype="multipart/form-data">
					<div class="summernote">
					@php 
						echo $body;
					@endphp
					</div>
				</br>
				<div class="form-group">
					<label class="control-label">Attach Document:</label>
					<input type="file" class="form-control-file document" name="tc_document">
					<input type="hidden" name="body" class="body">
				</div>
				@php
					if(isset($privacy)&& !empty($privacy->document)){
				@endphp
				<div class="row">
				  <div class="col-sm-2">
					<div class="thumbnail">

						<img src="{{asset('public/storage/uploads/document')}}/{{$document}}" class="thumbnails">
						<input type="hidden" name="id" value="{{$id}}">
					</div>
				  </div>
				</div>
				@php
					}
				@endphp
				<div class="form-group row">
					<div class="col-sm-4 col-sm-offset-4">
						<button type="submit" class="btn btn-success btn-block save">{{$btn_text}}</button>
					</div>
				</div>
				</form>
			</div >
		</div>
	</div>
</div>
@endsection

@section('script')
<script src="{{asset('dashboard/js/plugins/summernote/summernote.min.js')}}"></script>
<script>
	$(document).ready(function(){
		$('.summernote').summernote({
		  height: 500,   //set editable area's height
		  codemirror: { // codemirror options
			theme: 'monokai'
		  }
		});

		$('#privacy_form').submit(function(event){
			event.preventDefault();
		
			var html = $('.summernote').summernote('code');
			$(".body").val(html);
			var formData = new FormData($(this)[0]);
			$.ajax({
				url: '{{url("privacy/add")}}',
				type: "post",
				dataType: 'json',
				data:formData,
				contentType:false,
				cache:false,
				processData:false,
				success:function(res){
					swal(res.msg, {
						icon: "success",
						text: res.msg,
					});
				}
			});
			
		});
	});
</script>
@endsection