<?php defined('_AZ') or die('Restricted access');
	app('admin')->checkAddon('multiple_store_warehouse',true);		
	app('pos')->checkPermission('multiple_store_warehouse','view',true) or die(redirect("/pos/access-denied"));
	include dirname(__FILE__) .'/../../modules/pos/include/header.php';
	include dirname(__FILE__) .'/../../modules/pos/include/side_bar.php';
	include dirname(__FILE__) .'/../../modules/pos/include/navbar.php';
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
			<h2><?php echo trans('warehouse'); ?></h2>
		</div>
		<div class="col-sm-12">
			<div class="ibox">
				<div class="ibox-title">
				<?php
						if(app('pos')->checkPermission('multiple_store_warehouse','edit',true)){
					?>
					<a href="javascript:void(0);" class="btn btn-primary pull-right add_btn add-warehouse"> <i class="fa fa-plus-circle"></i><?php echo trans('add'); ?></a>
					<?php } ?>
				</div>
				<div class="ibox-content">
					<table class="table table-bordered table-hover warehouse_table" data-title='Warehouse'></table>
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
			AS.Http.GetDataTable('.warehouse_table',TableDataColums(),{ action : "GetWarehouseData"},"pos/filter/",FilterType);
		}
		
		function TableDataColums(){
			return [
			{ "title": $_lang.warehouse_id,"class": "text-center", data : 'warehouse_id' },
			{ "title": $_lang.warehouse_name,"class": "text-center", data : 'warehouse_name' },
			{ "title": $_lang.warehouse_location,"class": "text-center", data : 'warehouse_location' },
			{ "title": $_lang.status,"class": "text-center",
				orderable: false,
				render: function (data, type, row) {
					if(row.warehouse_status=='active'){
						return '<button type="button" class="btn btn-primary btn-xs">Active</button>';
						}else{
						return '<button type="button" class="btn btn-danger btn-xs">Deactive</button>';
					}
				}
			},
			{ "title": $_lang.added_by,"class": "text-center", data : 'user_id' },
			{ "title": $_lang.created_at,"class": "text-center", data : 'created_at' },
			{ "title": $_lang.updated_at,"class": "text-center", data : 'updated_at' },		
			<?php
				if(app('pos')->checkPermission('multiple_store_warehouse','edit',true)){
			?>			
			{ "title": $_lang.action,"class": "text-center not-show",
				orderable: false,
				render: function (data, type, row) {
					return '<div class="btn-group">'
					+'<button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">Action<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>'
					+'<ul class="dropdown-menu pull-right" role="menu">'
					+'<li><a href="javascript:void(0)" class="edit_warehouse" warehouse_id="'+row.warehouse_id+'" warehouse_name="'+row.warehouse_name+'" warehouse_location="'+row.warehouse_location+'"><i class="glyphicon glyphicon-edit"></i> '+$_lang.edit+'</a></li>'
					+'<li><a href="javascript:void(0)" class="change_status" warehouse_id="'+row.warehouse_id+'" status="'+row.warehouse_status+'"> '+$_lang.status_change+'</a></li>'
					+'<li><a href="javascript:void(0)" warehouse_id="'+row.warehouse_id+'" class="delete_warehouse"><i class="glyphicon glyphicon-trash"></i> '+$_lang.delete+'</a></li>'
					+'</ul>';
				}
			}
				<?php } ?>
			];
		}
		
		$(document).on("click",".add-warehouse", function(){
			$(".show_modal").remove();
			AS.Http.posthtml({"action" : "GetNewWarehouse"}, "pos/modal/", function (data) {
				$(".modal_status").html(data);
				$(".show_modal").modal("show");
			});
		});
		
		$(document).on("click",".change_status", function(){
			var warehouse_id = $(this).attr('warehouse_id');
			var status = $(this).attr('status');
			AS.Http.post({"action" : "ChangeWarehouseStatus","warehouse_id": warehouse_id,"status": status}, "pos/ajax/", function (response) {
				DataTable(true);
			});
		});	
		
		$(document).on("click",".edit_warehouse", function(){
			var warehouse_id = $(this).attr('warehouse_id');
			var warehouse_name = $(this).attr('warehouse_name');
			var warehouse_location = $(this).attr('warehouse_location');
			$(".show_modal").remove();
			AS.Http.posthtml({"action" : "GetNewWarehouse","warehouse_id" : warehouse_id}, "pos/modal/", function (data) {
				$(".modal_status").html(data);
				$("#warehouse_name").val(warehouse_name);
				$("#warehouse_location").val(warehouse_location);
				$(".show_modal").modal("show");
			});
		});
		
		$(document).on("click",".confirm", function(){
			$('.show_modal').modal('hide');
			DataTable(true);
		});
		
		$(document).on("click",".delete_warehouse", function(){
			var warehouse_id = $(this).attr('warehouse_id');
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
					AS.Http.post({"action" : "DeleteWarehouse","warehouse_id": warehouse_id}, "pos/ajax/", function (response) {
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
				}else { DataTable(true); }
			})
		});
		
	</script>
[/footer]
<?php include dirname(__FILE__) .'/../../modules/pos/include/footer.php'; ?>