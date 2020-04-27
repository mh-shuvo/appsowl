<?php defined('_AZ') or die('Restricted access');
	app('pos')->checkPermission('report','view') or redirect("pos/access-denied");
	include dirname(__FILE__) .'/include/header.php';
	include dirname(__FILE__) .'/include/side_bar.php';
	include dirname(__FILE__) .'/include/navbar.php';
?>
[header]
<?php getCss('assets/system/css/plugins/daterangepicker/daterangepicker-bs3.css');?>
[/header]
<div class="row wrapper">
	<div class="col-sm-12">
		<h3><?php echo trans('profit_loss_report'); ?></h3>
	</div>
	<div class="col-sm-12">
		<div class="ibox">
			<div class="ibox-content">
				<div class="row">
					<?php
						if(app('admin')->checkAddon('multiple_store_warehouse')){
					?>
					<div class="col-lg-3 col-md-3 col-sm-4">
						<div class="form-group">
							<label class="control-label">
								<?php echo trans('business_location'); ?>
							</label>
							<select class="form-control update_store" id="store_id" name="store_id">
								<option value="0">All</option>
								<?php $posStores = app('admin')->getall('pos_store'); foreach($posStores as $posStore){	?>
									<option value="<?php echo $posStore['store_id']; ?>" <?php if($posStore[ 'store_id']==$this->currentUser->store_id) echo "selected"; ?>>
										<?php echo $posStore['store_name']; ?>
									</option>
								<?php } ?>
							</select>
						</div>
					</div>
						<?php } ?>
					<div class="col-lg-4 col-md-4 col-sm-4">
						<div class="form-group">
							<label class="control-label"><?php echo trans('date_range'); ?></label>
							<div class="input-group">
								<span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
								<input class="form-control" type="text" name="daterange" id="reportrange" placeholder="<?php echo trans('custom_daterange'); ?>" readonly />
							</div>
						</div>
					</div>
				</div>
			</div>				
		</div>
	</div>
	<div class="col-sm-12">
		<div class="row">
			<div class="col-sm-6">
				<div class="ibox">
					
					<div class="ibox-content">
						<table class="table table-striped">
							<tr>
								<td>
									<strong><?php echo trans('purchase_total');?>:</strong>
								</td>
								<td class="purchase_total"></td>
							</tr>
							<tr>
								<td>
									<strong><?php echo trans('purchase_return_total');?>:</strong>
								</td>
								<td class="purchase_return_total"></td>
							</tr>
							
							<tr>
								<td>
									<strong><?php echo trans('total_purchase_discount');?>:</strong>
								</td>
								<td class="total_purchase_discount"></td>
							</tr>
							<tr>
								<td>
									<strong><?php echo trans('total_purchase_shipping_charge');?>:</strong>
								</td>
								<td class="total_purchase_shipping_charge"></td>
							</tr>
						</table>
						
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="ibox">
					<div class="ibox-content">
						<table class="table table-striped">
							<tr>
								<td>
									<strong><?php echo trans('total_sales');?>:</strong>
								</td>
								<td class="sales_total"></td>
							</tr>
							<tr>
								<td>
									<strong><?php echo trans('total_sales_return');?>:</strong>
								</td>
								<td class="sales_return_total"></td>
							</tr>
							<tr>
								<td>
									<strong><?php echo trans('total_sell_discount');?></strong>
								</td>
								<td class="sales_discount"></td>
							</tr>
							<tr>
								<td>
									<strong><?php echo trans('total_sell_vat');?></strong>
								</td>
								<td class="sales_vat"></td>
							</tr>
							
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-12">
		<div class="ibox">
			<div class="ibox-content">
				<h3><strong><?php echo trans('net_profit')?>: <span class="net_profit"></span></strong></h3>
			</div>
		</div>
	</div>
</div>
[footer]
<?php
	getJs('assets/system/js/plugins/fullcalendar/moment.min.js',false);
	getJs('assets/system/js/plugins/daterangepicker/daterangepicker.js');	
?>
<script type="text/javascript">
	$(document).ready(function(){
		
		function CustomDateRangeFilter() {
			var start = $('input#reportrange').data('daterangepicker').startDate.format('YYYY-MM-DD');
			var end = $('input#reportrange').data('daterangepicker').endDate.format('YYYY-MM-DD');
			var storeId = $("#store_id").val() || 0;
			
			AS.Http.post({"action" : "GetProfitLossReport", store_id : storeId, from_data : start, to_data : end}, "pos/ajax/", function (response) {
				$('.net_profit').html(response.net_profit || 0);
				$('.sales_shipping_charge').html(response.sales_shipping_charge || 0);
				$('.total_purchase_discount').html(response.total_purchase_discount || 0);
				$('.total_purchase_shipping_charge').html(response.total_purchase_shipping_charge || 0);
				$('.sales_discount').html(response.sales_discount || 0);
				$('.sales_total').html(response.sales_total || 0);
				$('.sales_return_total').html(response.sales_return_total || 0);
				$('.purchase_total').html(response.purchase_total || 0);
				$('.purchase_return_total').html(response.purchase_return_total || 0);
				$('.sales_vat').html(response.sales_vat || 0);
			});
		}
		
		$(document).on("change",".GetProfitLossReportChange", function(){
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
			CustomDateRangeFilter();
		});
		
		$('#reportrange').val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
		CustomDateRangeFilter();
		
		$(document).on("change",".update_store", function(){
			CustomDateRangeFilter();
		});
		
	});
</script>
[/footer]
<?php include dirname(__FILE__) .'/include/footer.php';	?>														