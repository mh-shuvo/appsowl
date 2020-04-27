<?php defined('_AZ') or die('Restricted access');
	app('pos')->checkPermission('report','view') or redirect("pos/access-denied");
	include dirname(__FILE__) .'/include/header.php';
	include dirname(__FILE__) .'/include/side_bar.php';
	include dirname(__FILE__) .'/include/navbar.php';
?>
[header]
<?php getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false); ?>
<style type="text/css">
	th{text-align: center;}
	.product_image img{height: 50px; width: 50px;}
	.full-width-modal-dialog {
	width: 85%;
	left:8%;
	height: auto;
	margin: 0;
	padding: 0;
	}
	
	.full-width-modal-content {
	height: auto;
	min-height: 100%;
	border-radius: 0;
	}
	.thumbnail img{height:100%; width:100%;}
</style>
[/header]

<div class="row wrapper">
	<div class="col-sm-12">
		<h2><?php echo trans('stock_report'); ?></h2>
	</div>
	<div class="col-sm-12">
		<div class="ibox">
			<div class="ibox-title">
				<h3 class="text-primary"><i class="fa fa-filter"></i><?php echo trans('filter'); ?></h3>
				<form>					
					<div class="row">
					<?php
						if(app('admin')->checkAddon('multiple_store_warehouse')){
					?>
						<div class="col-sm-3">
							<div class="form-group">
								<label class="control-label">
									<?php echo trans('business_location'); ?>
								</label>
								<select class="form-control GetStockReportChange" id="store_id">
									<option value="">All</option>
									<?php $posStores = app('admin')->getall('pos_store'); foreach($posStores as $posStore){	?>
										<option value="<?php echo $posStore['store_id']; ?>" <?php if($posStore['store_id'] == $this->currentUser->store_id) echo "selected"; ?>><?php echo $posStore['store_name']; ?></option>
									<?php } ?>
								</select>
							</div>	
						</div>
						
					<?php } ?>
					
						<div class="col-sm-3">
							<div class="form-group">
								<label class="control-label"><?php echo trans('category'); ?></label>
								<select class="form-control GetStockReportChange" id="category_id">
									<option value=""><?php echo trans('select_category'); ?></option>
									<?php 
										$getCategories=app('admin')->getall('pos_category');
										foreach ($getCategories as $category) {
										?>
										<option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
										
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<label class="control-label"><?php echo trans('brand'); ?></label>
								<select class="form-control GetStockReportChange" id="brand_id">
									<option value=""><?php echo trans('select_brand'); ?></option>
									<?php 
										$getBrands=app('admin')->getall('pos_brands');
										foreach ($getBrands as $brand) {
										?>
										<option value="<?php echo $brand['brand_id']; ?>"><?php echo $brand['brand_name']; ?></option>
										
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<label class="control-label"><?php echo trans('unit'); ?></label>
								<select class="form-control GetStockReportChange" id="unit_id">
									<option value=""><?php echo trans('select_unit'); ?></option>
									<?php 
										$getUnit=app('admin')->getall('pos_unit');
										foreach ($getUnit as $unit) {
										?>
										<option value="<?php echo $unit['unit_id']; ?>"><?php echo $unit['unit_name']; ?></option>
										
									<?php } ?>
								</select>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="ibox-content">
				<table class="table table-striped table-bordered StockTableData" data-title="Stock Report"></table>
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
	
	$(document).on("change",".GetStockReportChange", function(){
		CustomStockReportData(true);
	});
	
	function TableDataColums(){
		return [
		{ "title": $_lang.product_id,"class": "text-center", data : 'product_id' },
		{ "title": $_lang.product_name,"class": "text-center", data : 'product_name' },
		{ "title": $_lang.current_stock,"class": "text-center", orderable: false, orderable: false, data : 'current_stock' },
		{ "title": $_lang.total_purchase,"class": "text-center", orderable: false, data : 'total_purchase' },
		{ "title": $_lang.total_sale,"class": "text-center", orderable: false, data : 'total_sale' },
		];
	}
	
	CustomStockReportData(false);
	
	function CustomStockReportData(FilterType){
		var categoryId= $('#category_id').val() || null;
		var brandId= $('#brand_id').val() || null;
		var unitId= $('#unit_id').val() || null;
		var storeId= $('#store_id').val() || null;
		AS.Http.GetDataTable('.StockTableData',TableDataColums(),{ action : "GetStockReport", category_id : categoryId, brand_id :  brandId, unit_id : unitId,store_id : storeId},"pos/filter/",FilterType);
		
	}
	
	$(document).on("click",".view_serial", function(){
		var product_id = $(this).attr('product_id');
		$(".show_modal").remove();
		AS.Http.posthtml({"action" : "GetSerialShowModal","product_id" : product_id}, "pos/modal/", function (data) {
			$(".modal_status").html(data);
			$(".show_modal").modal("show");
		});
	});
</script>
[/footer]
<?php include dirname(__FILE__) .'/include/footer.php';?>					