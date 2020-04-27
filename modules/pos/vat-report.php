<?php defined('_AZ') or die('Restricted access');
app('pos')->checkPermission('report','view') or redirect("pos/access-denied");
include dirname(__FILE__) .'/include/header.php';
include dirname(__FILE__) .'/include/side_bar.php';
include dirname(__FILE__) .'/include/navbar.php';
?>
[header]
<?php 
	echo getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false); 
	echo getCss('assets/system/css/plugins/daterangepicker/daterangepicker-bs3.css');
?>
<style type="text/css">
	.overall_amount h2{color: #777;}
	.overall_amount h2 span{color: #a94442;}
	.span i{color: #1ab394;}
</style>
[/header]
<div class="row wrapper">
	<div class="col-sm-12">
		<h2><?php echo trans('vat_report'); ?></h2>
	</div>
	<div class="col-sm-12">
		<div class="row">
			<?php
				if(app('admin')->checkAddon('multiple_store_warehouse')){
			?>
			<div class="col-sm-3">
				<div class="form-group">
					<label class="control-label">
						<?php echo trans('business_location'); ?>
					</label>
					<select class="form-control GetVatReportChange" id="store_id" name="store_id">
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
							<label class="control-label"><?php echo trans('date_range'); ?></label>
							<div class="input-group">
								<span class="input-group-addon span"> <i class="fa fa-calendar"></i> </span>
								<input class="form-control GetVatReportChange" type="text" name="daterange" id="reportrange" placeholder="<?php echo trans('custom_daterange'); ?>" />
							</div>
						</div>
			</div>
		
			<div class="col-sm-3">
				<div class="form-group">
					<label class="control-label"><?php echo trans('customer'); ?></label>
					<div class="input-group">
						<span class="input-group-addon span"><i class="fa fa-map-marker"></i></span>
						<select class="form-control GetVatReportChange" id="customer_id">
							<option value=""><?php echo trans('select_customer'); ?></option>
							<?php $posCustomer = app('admin')->getwhere('pos_contact','contact_type','customer');
							foreach($posCustomer as $customer){	?>
								<option value="<?php echo $customer['contact_id']; ?>"><?php echo $customer['name']; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label class="control-label"><?php echo trans('Product'); ?></label>
					<div class="input-group">
						<span class="input-group-addon span"><i class="fa fa-map-marker"></i></span>
						<select class="form-control GetVatReportChange" id="product_id">
							<option value=""><?php echo trans('select_product'); ?></option>
							<?php $posProduct = app('admin')->getall('pos_product');
							foreach($posProduct as $product){	?>
								<option value="<?php echo $product['product_id']; ?>"><?php echo $product['product_name']; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>	
	<div class="col-sm-12">
		<div class="ibox">
			<div class="ibox-content">
				<table class="table table-striped table-bordered vat_report_table" data-title="VAT Report">
				<tfoot>
						<tr class="bg-gray font-17 footer-total text-center">
							<td></td>
							<td></td>
							<td></td>
							<td><?php echo trans('total');?>:</td>
							<td></td>
						</tr>
					</tfoot>
				
				</table>
			</div>
		</div>
	</div>
	<!--div class="col-sm-12">
		<button class="btn btn-success pull-right"><span class="fa fa-print"></span> <?php echo trans('print'); ?></button>
	</div-->								
	
</div>

[footer]
<?php
	getJs('assets/system/js/plugins/dataTables/datatables.min.js',false,false);
	getJs('assets/system/js/plugins/fullcalendar/moment.min.js',false);
	getJs('assets/system/js/plugins/daterangepicker/daterangepicker.js');
?>
<script>
function TableDataColums(){
		return [
		{ "title": $_lang.product_id,"class": "text-center", data : 'product_id' },
		{ "title": $_lang.order_id,"class": "text-center", data : 'sales_id' },
		{ "title": $_lang.customer,"class": "text-center", data : 'customer_id' },
		{ "title": $_lang.date,"class": "text-center", data : 'created_at' },
		{ "title": $_lang.amount,"class": "text-center product_quantity product_vat", data : 'product_vat' }, 
		];
	}
	function CustomDateRangeFilter(FilterType) {
		var customerId= $('#customer_id').val() || null;
		var productId= $('#product_id').val() || null;
		start = $('input#reportrange').data('daterangepicker').startDate.format('YYYY-MM-DD');
		end = $('input#reportrange').data('daterangepicker').endDate.format('YYYY-MM-DD');
		var storeId= $('#store_id').val() || null;
		AS.Http.GetDataTable('.vat_report_table',TableDataColums(),{ action : "GetVatReportData",between_action : "pos_stock/created_at",from_data : start, to_data : end,date_range : true, customer : customerId,product : productId,store_id: storeId },"pos/filter/",FilterType,['product_vat']);
			
	}
	$(document).on("change",".GetVatReportChange", function(){
		CustomDateRangeFilter(true);
	});
	
	
	var start = moment().subtract(29, 'days');
	var end = moment();
	
	$("#reportrange").daterangepicker({
		startDate: start,
		endDate: end,
		ranges: {
			'Today': [moment(), moment()],
			'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			'Last 7 Days': [moment().subtract(6, 'days'), moment()],
			'Last 30 Days': [moment().subtract(29, 'days'), moment()],
			'This Month': [moment().startOf('month'), moment().endOf('month')],
			'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
		}
		}, function (start,end) {
		$("#reportrange").val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
	});
	
	$('#reportrange').val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
	CustomDateRangeFilter();
</script>
[/footer]
<?php include dirname(__FILE__) .'/include/footer.php';?>		