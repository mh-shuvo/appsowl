<?php defined('_AZ') or die('Restricted access');
		include dirname(__FILE__) .'/include/header.php';
		include dirname(__FILE__) .'/include/side_bar.php';
		include dirname(__FILE__) .'/include/navbar.php';	
	?>
	[header]
	<?php getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false);?>
	<style>
		tr td{text-align:center;}
		.add_btn{margin-top: -7px;}
	</style>
	[/header]
	<div class="row wrapper">
		<div class="col-sm-12">		
			<h2><?php echo trans('brand'); ?></h2>
		</div>
		<div class="col-sm-12">
			<div class="ibox">
				<div class="ibox-title">
					<button class="btn btn-primary pull-right add_btn add_brand"> <i class="fa fa-plus-circle"></i> <?php echo trans('add');?></button>
				</div>
				<div class="ibox-content">
					<table class="table table-bordered table-hover brand_table" data-title='Brand'></table>
				</div>
			</div>
			
		</div>
	</div>
	<div class="ModalForm">
		<div class="modal_status"></div>
	</div>		
	[footer]
	<?php getJs('assets/system/js/plugins/dataTables/datatables.min.js',false,false);?>
	<script>
		
		DataTable(false);
		
		function DataTable(FilterType) {
			AS.Http.GetDataTable('.brand_table',TableDataColums(),{ action : "GetBrandData"},"pos/filter/",FilterType);
		}
		
		function TableDataColums(){
			return [
			{ "title": $_lang.serial_number,"class": "text-center", data : 'brand_id' },
			{ "title": $_lang.brand_name,"class": "text-center", data : 'brand_name' },
			{ "title": $_lang.added_by,"class": "text-center", data : 'user_id' },
			{ "title": $_lang.created_at,"class": "text-center", data : 'created_at' },
			{ "title": $_lang.updated_at,"class": "text-center", data : 'update_at' },			
			{ "title": $_lang.action,"class": "text-center not-show",
				orderable: false,
				render: function (data, type, row) {
					var html = '<div class="btn-group">'
					+'<button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">'+$_lang.action+'<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>'
					+'<ul class="dropdown-menu pull-right" role="menu">'
					+'<li><a href="javascript:void(0)" class="edit_brand" brand_id="'+row.brand_id+'" brand_name="'+row.brand_name+'"><i class="glyphicon glyphicon-edit"></i> '+$_lang.edit+'</a></li>'
					+'<li><a href="javascript:void(0)" name="'+row.name+'" brand_id="'+row.brand_id+'"   class="delete_brand"><i class="glyphicon glyphicon-trash"></i> '+$_lang.delete+'</a></li>'
					+'</ul>';
					return html;
				}
			}
			];
		}
		
		$(document).on('click','.add_brand',function(){
			$(".show_modal").remove();
			AS.Http.posthtml({"action" : "GetNewBrandModal"}, "pos/modal/", function (data) {
				$(".modal_status").html(data);
				$(".show_modal").modal("show");
			});
		})
		$(document).on("click",".edit_brand", function(){
			var brand_id = $(this).attr('brand_id');
			var brand_name = $(this).attr('brand_name');
			$(".show_modal").remove(); 
			AS.Http.posthtml({"action" : "GetNewBrandModal","brand_id" : brand_id}, "pos/modal/", function (data) {
				$(".modal_status").html(data);
				$("#brand_name").val(brand_name);
				$(".show_modal").modal("show");
			});
		});
		
		$(document).on("click",".confirm", function(){
			$('.show_modal').modal('hide');
			DataTable(true);
		});
		
		$(document).on("click",".delete_brand", function(){
			var brand_id = $(this).attr('brand_id');
			swal( {
				title: $_lang.are_you_sure,
				type: "warning", 
				showCancelButton: true, 
				confirmButtonColor: "#DD6B55", 
				confirmButtonText: $_lang.yes, 
				cancelButtonText: $_lang.no, 
				closeOnConfirm: false, 
				closeOnCancel: true
				},function (isConfirm) {
				if (isConfirm) {
					AS.Http.post({"action" : "DeleteBrand","brand_id": brand_id}, "pos/ajax/", function (response) {
						if(response.status=='success'){
							swal({
								title: $_lang.deleted, 
								text: response.message, 
								type: "success",
								confirmButtonColor: "#1ab394", 
								confirmButtonText: $_lang.ok,
							});
						}else{ DataTable(true); }
					});
				}else{ DataTable(true);}
			})
		});
		
	</script>
	[/footer]
	<?php include dirname(__FILE__) .'/include/footer.php';?>	