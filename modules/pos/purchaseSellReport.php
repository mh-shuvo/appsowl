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
</style>
[/header]
<div class="row wrapper">
	<div class="col-sm-12">
		<h2><?php echo trans('purchase_sell_report'); ?></h2>
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
				<select class="form-control GetStockReportChange" id="store_id">
					<option value="">All</option>
					<?php $posStores = app('admin')->getall('pos_store'); foreach($posStores as $posStore){	?>
						<option value="<?php echo $posStore['store_id']; ?>" <?php if($posStore[ 'store_id']==$this->currentUser->store_id) echo "selected"; ?>>
							<?php echo $posStore['store_name']; ?>
						</option>
						<?php } ?>
				</select>
			</div>
		</div>						
				
			<?php } ?>
			<div class="col-sm-4">
				<div class="form-group">
				<label class="control-label"><?php echo trans('date_range'); ?></label>
				<div class="input-group">
					<span class="input-group-addon"> <i class="fa fa-calendar span-primary"></i> </span>
					<input class="form-control GetPurchaseSalesReportChange" type="text" name="daterange" id="reportrange" placeholder="<?php echo trans('custom_daterange'); ?>" />
				</div>
			</div>
		</div>
	</div>
</div>	
<div class="col-sm-12">
	<div class="row">
		<div class="col-sm-6">
			<div class="ibox">
				<div class="ibox-title">
					<h3><?php echo trans('purchase'); ?></h3>
				</div>
				<div class="ibox-content">
					<table class="table table-striped">
						<tr>
							<td>
								<strong><?php echo trans('total_purchase');?>:</strong>
							</td>
							<td class="total_purchase_total"></td>
						</tr>
						<tr>
							<td>
								<strong><?php echo trans('total_purchase_discount');?>:</strong>
							</td>
							<td class="total_purchase_discount"></td>
						</tr>
						<tr>
							<td>
								<strong><?php echo trans('total_purchase_shipping_charge');?></strong>
							</td>
							<td class="total_purchase_shipping_charge"></td>
						</tr>
						<tr>
							<td>
								<strong><?php echo trans('total_purchase_vat');?></strong>
							</td>
							<td class="purchase_vat"></td>
						</tr>
						
					</table>
					
				</div>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="ibox">
				<div class="ibox-title">
					<h3><?php echo trans('sales'); ?></h3>
				</div>
				<div class="ibox-content">
					<table class="table table-striped">
						<tr>
							<td>
								<strong><?php echo trans('total_sales_total');?>:</strong>
							</td>
							<td class="total_sales_total"></td>
						</tr>
						<tr>
							<td>
								<strong><?php echo trans('total_sales_discount');?>:</strong>
							</td>
							<td class="total_sales_discount"></td>
						</tr>
						<tr>
							<td>
								<strong><?php echo trans('total_sales_vat');?></strong>
							</td>
							<td class="total_sales_vat"></td>
						</tr>
						<tr>
							<td>
								<strong><?php echo trans('total_shipping_charge');?></strong>
							</td>
							<td class="total_shipping_charge"></td>
						</tr>
						
						
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="col-sm-12">
	<div class="ibox">
		<div class="ibox-title">
			<h3><?php echo trans('overall(sale_minus_sale_return_minus_purchase_minus_purchase_return)'); ?></h3>
		</div>
		<div class="ibox-content overall_amount">
			<h2>
				<?php echo trans("sale_minus_purchase"); ?>: <span class="total_purchase_sales"></span>
			</h2>
		</div>
	</div>
</div>
<!--div class="col-sm-12">
	<button class="btn btn-success pull-right"><span class="fa fa-print"></span> <?php echo trans('print'); ?></button>
</div-->								

</div>

[footer]
<?php
echo getJs('assets/system/js/plugins/dataTables/datatables.min.js',false,false);
echo getJs('assets/system/js/plugins/fullcalendar/moment.min.js',false);
echo getJs('assets/system/js/plugins/daterangepicker/daterangepicker.js');
?>
<script>
$(document).ready(function(){
	
	function CustomDateRangeFilter() {
		var storeId= $('#store_id').val() || null;
		start = $('input#reportrange').data('daterangepicker').startDate.format('YYYY-MM-DD');
		end = $('input#reportrange').data('daterangepicker').endDate.format('YYYY-MM-DD');
		AS.Http.post({"action" : "GetPurchaseSalesReport", start_date:start, end_date:end,store_id:storeId}, "pos/ajax/", function (response) {
			$('.total_sales_due').html(response.sales_due || 0);
			$('.total_sales_return_total').html(response.sales_return_total || 0);
			$('.total_shipping_charge').html(response.shipping_charge || 0);
			$('.total_sales_discount').html(response.sales_discount || 0);
			$('.total_sales_vat').html(response.sales_vat || 0);
			$('.total_sales_total').html(response.sales_total || 0);
			$('.total_purchase_total').html(response.purchase_total || 0);
			$('.total_purchase_discount').html(response.purchase_discount || 0);
			$('.total_purchase_shipping_charge').html(response.purchase_shipping_charge || 0);
			$('.total_purchase_return_total').html(response.purchase_return_total || 0);
			$('.total_purchase_due').html(response.purchase_due || 0);
			$('.total_purchase_sales').html(response.total_purchase_sales || 0);
			$('.purchase_vat').html(response.purchase_vat || 0);
		});
	}
	
	$(document).on("change",".GetPurchaseSalesReportChange", function(){
		CustomDateRangeFilter();
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
	
});
</script>
[/footer]
<?php include dirname(__FILE__) .'/include/footer.php';?>