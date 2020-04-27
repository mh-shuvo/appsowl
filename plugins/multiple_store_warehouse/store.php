<?php defined('_AZ') or die('Restricted access');		
	
	app('admin')->checkAddon('multiple_store_warehouse',true);		
	app('pos')->checkPermission('multiple_store_warehouse','view',true) or die(redirect("/pos/access-denied"));
	
	include dirname(__FILE__) .'/../../modules/pos/include/header.php';
	include dirname(__FILE__) .'/../../modules/pos/include/side_bar.php';
	include dirname(__FILE__) .'/../../modules/pos/include/navbar.php';
?>
[header]
<?php 
	getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false);
?>
<style>
	tr td{text-align:center;}
	.add_btn{margin-top: -7px;}
</style>
[/header]
<div class="row wrapper">
	<div class="col-sm-12">		
		<h2><?php echo trans('store'); ?></h2>
	</div>
	<div class="col-sm-12">
		<div class="ibox">
			<div class="ibox-title">
				<?php
					if(app('pos')->checkPermission('multiple_store_warehouse','edit',true)){
					?>
					<a href="javascript:void(0);" class="btn btn-primary pull-right add_btn add_store"> <i class="fa fa-plus-circle"></i> <?php echo trans('add'); ?></a>
				<?php } ?>
			</div>
			<div class="ibox-content">
				<table class="table table-bordered table-hover store_table" data-title='Store'></table>
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
		AS.Http.GetDataTable('.store_table',TableDataColums(),{ action : "GetStoreData"},"pos/filter/",FilterType);
	}
	
	function TableDataColums(){
		return [
		{ "title": $_lang.store_id,"class": "text-center", data : 'store_id' },
		{ "title": $_lang.store_name,"class": "text-center", data : 'store_name' },
		{ "title": $_lang.warehouse,"class": "text-center", data : 'warehouse' },
		{ "title": $_lang.store_location,"class": "text-center", data : 'store_location' },
		{ "title": $_lang.status,"class": "text-center",
			orderable: false,
			render: function (data, type, row) {
				if(row.store_status=='active'){
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
					var html = '<div class="btn-group">'
					+'<button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">'+$_lang.action+'<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>'
					+'<ul class="dropdown-menu pull-right" role="menu">'
					+'<li><a href="javascript:void(0)" class="edit_store" store_id="'+row.store_id+'" store_name="'+row.store_name+'" store_location="'+row.store_location+'" warehouse="'+row.warehouse_id+'"><i class="glyphicon glyphicon-edit"></i> '+$_lang.edit+'</a></li>'
					+'<li><a href="javascript:void(0)" class="change_status" store_id="'+row.store_id+'" status="'+row.store_status+'">'+$_lang.status_change+'</a></li>'
					+'</ul>';
					return html;
				}
			}
		<?php } ?>
		];
	}
	
	$(document).on("click",".add_store", function(){
		$(".show_modal").remove();
		AS.Http.posthtml({"action" : "GetNewStore"}, "pos/modal/", function (data) {
			$(".modal_status").html(data);
			$(".show_modal").modal("show");
		});
	});
	
	$(document).on("click",".change_status", function(){
		var store_id = $(this).attr('store_id');
		var status = $(this).attr('status');
		AS.Http.post({"action" : "ChangeStatus","store_id": store_id,"status": status}, "pos/ajax/", function (response){
			DataTable(true);
		});
	});	
	
	$(document).on("click",".edit_store", function(){
		var store_id = $(this).attr('store_id');
		var store_name = $(this).attr('store_name');
		var store_location = $(this).attr('store_location');
		var warehouse = $(this).attr('warehouse');
		$(".show_modal").remove();
		AS.Http.posthtml({"action" : "GetNewStore","store_id" : store_id}, "pos/modal/", function (data) {
			$(".modal_status").html(data);
			$("#store_name").val(store_name);
			$("#store_location").val(store_location);
			$("#warehouse").val(warehouse);
			$(".show_modal").modal("show");
		});
	});
	
	$(document).on("click",".confirm", function(){
		$('.show_modal').modal('hide');
		DataTable(true);
	});
	
</script>
[/footer]
<?php include dirname(__FILE__) .'/../../modules/pos/include/footer.php'; ?>														