<?php defined('_AZ') or die('Restricted access');
	// app('admin')->checkAddon('stock_adjustment');
	app('pos')->checkPermission('stock_adjustment','view',true) or die(redirect("/pos/access-denied"));
	
	include dirname(__FILE__) .'/../../modules/pos/include/header.php';
	include dirname(__FILE__) .'/../../modules/pos/include/side_bar.php';
	include dirname(__FILE__) .'/../../modules/pos/include/navbar.php';
	
	?>
	
	[header]
	<?php getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false); ?>
	[/header]
	
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-4">
			
			<h2><?php echo str_replace('-', ' ', ucfirst($uri_segments[2])); ?></h2>
			<ol class="breadcrumb">
				<li>
					<a href=""><?php echo trans('dashboard'); ?></a>
				</li>
				<li class="active">
					<strong><?php echo str_replace('-', ' ', ucfirst($uri_segments[2])); ?></strong>
				</li>
			</ol>
		</div>
	</div>
	
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox float-e-margins">
					<div class="ibox-content">
						
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover stock_adjustment_table" data-title='Stock adjustment' ></table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="ModalForm">
		<div class="modal_status"></div>
	</div>
	[footer]
	<?php getJs('assets/system/js/plugins/dataTables/datatables.min.js',false,false); ?>
	<script>
		function TableDataColums(){
			return [
			{ "title": $_lang.date,"class": "text-center", data : 'created_at' },
			{ "title": $_lang.adjustment_id,"class": "text-center", data : 'stock_adjustment_id' },
			{ "title": $_lang.reference_no,"class": "text-center", data : 'reference_no' },
			{ "title": $_lang.store,"class": "text-center", data : 'pos_store/store_name' },
			{ "title": $_lang.adjustment_type,"class": "text-center", data : 'type' },
			{ "title": $_lang.recovered,"class": "text-center", data : 'pos_transactions/transaction_amount' },
			{ "title": $_lang.added_by,"class": "text-center", data : 'user_id' },
			{ "title": $_lang.created_at,"class": "text-center", data : 'created_at' },
			{ "title": $_lang.action,"class": "text-center not-show",
				orderable: false,
				render: function (data, type, row) {
					return '<div class="btn-group">'
					+'<button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">Action<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>'
					+'<ul class="dropdown-menu pull-right" role="menu">'
					+'<li><a href="javascript:void(0)" class="stock_adjustment_view" stock_adjustment_id="'+row.stock_adjustment_id+'" ><i class="fa fa-eye"></i> '+$_lang.view+'</a></li>'
					<?php if(app('pos')->checkPermission('stock_adjustment','edit',true)){ ?>
					+'<li><a href="javascript:void(0)" class="delete_stock_adjustment" stock_adjustment_id="'+row.stock_adjustment_id+'"><i class="glyphicon glyphicon-trash"></i> '+$_lang.delete+'</a></li>'
					<?php } ?>
					+'</ul>';
				}
			}
			];
		}
		
		$(document).ready(function(){
			AS.Http.GetDataTable('.stock_adjustment_table',TableDataColums(),{ action : "GetStockAdjustmentData"},"pos/filter/");
		});
		
		$(document).on("click",".confirm", function(){
			$('.show_modal').modal('hide');
			AS.Http.GetDataTable('.stock_adjustment_table',TableDataColums(),{ action : "GetStockAdjustmentData"},"pos/filter/",true);
		});
		
		$(document).on("click",".stock_adjustment_view", function(){
			var stock_adjustment_id = $(this).attr('stock_adjustment_id');
			$(".show_modal").remove();
			AS.Http.posthtml({"action" : "GetStockAdjustmentView","stock_adjustment_id" : stock_adjustment_id}, "pos/modal/", function (data) {
				$(".modal_status").html(data);
				$(".show_modal").modal("show");
			});
		});
		
		$(document).on("click",".delete_stock_adjustment", function(){
			var stock_adjustment_id = $(this).attr('stock_adjustment_id');
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
					AS.Http.post({"action" : "DeleteStockAdjustment","stock_adjustment_id": stock_adjustment_id}, "pos/ajax/", function (response) {
						if(response.status=='success'){
							swal({
								title: $_lang.deleted, 
								text: response.message, 
								type: "success",
								confirmButtonColor: "#1ab394", 
								confirmButtonText: $_lang.ok,
							});
							}else{
							AS.Http.GetDataTable('.stock_adjustment_table',TableDataColums(),{ action : "GetStockAdjustmentData"},"pos/filter/",true);
						}
					});
					}else {
					AS.Http.GetDataTable('.stock_adjustment_table',TableDataColums(),{ action : "GetStockAdjustmentData"},"pos/filter/",true);
				}
			})
		});
		
	</script>
	[/footer]
	<?php include dirname(__FILE__) .'/../../modules/pos/include/footer.php'; ?>
		