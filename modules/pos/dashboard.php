<?php defined('_AZ') or die('Restricted access'); 
	include dirname(__FILE__) .'/include/header.php';
	include dirname(__FILE__) .'/include/side_bar.php';
	include dirname(__FILE__) .'/include/navbar.php';
	
?>
[header]
<style>
	tr th,
	td {
	text-align: center;
	}
</style>
<?php 
	getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false);
?>
[/header]
<div class="row">
	<?php 
		$total_sale = app('pos')->GetSalesByCustomerOrder();
		$total_purchase = app('pos')->GetPurchaseByCustomerOrder();
		$currency = app("root")->table('as_country')->where('name',$this->currentUser->country_name)->get('currency_symbol');
	?>
	<input type="hidden" class="currency" value="<?php echo $currency; ?>">
	<div class="col-lg-2 col-md-4 col-sm-3 col-xs-6">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5><?php  echo trans("net_sale"); ?></h5>
			</div>
			<div class="ibox-content">
				<h3 class="no-margins"><?php if($total_sale['total_sales_with_return']!=null){echo number_format($total_sale['total_sales_with_return'],2).' '.$currency;}else{echo '0.00 '.$currency;} ?></h3>
				<small><?php echo trans('net_sale'); ?></small>
			</div>
		</div>
	</div>
	<?php if(app('admin')->checkAddon('multiple_payment_method')){ ?>
	<div class="col-lg-2 col-md-4 col-sm-3 col-xs-6">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5><?php  echo trans("cash_sale"); ?></h5>
			</div>
			<div class="ibox-content">
				<h3 class="no-margins"><?php if($total_sale['total_paid_with_return']!=null){echo number_format($total_sale['total_paid_with_return'],2).' '.$currency;}else{echo '0.00 '.$currency;} ?></h3>
				<small><?php echo trans('total_cash_sale'); ?></small>
			</div>
		</div>
	</div>
	<?php } ?>
	<?php if(app('admin')->checkAddon('due_sale')){ ?>
		<div class="col-lg-2 col-md-4 col-sm-3 col-xs-6">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5><?php  echo trans("due_sale"); ?></h5>
				</div>
				<div class="ibox-content">
					<h3 class="no-margins"><?php if($total_sale['total_due']!=null){echo number_format($total_sale['total_due'],2).' '.$currency;}else{echo '0.00 '.$currency;} ?></h3>
					<small><?php echo trans('total_due_sale'); ?></small>
				</div>
			</div>
		</div>
		
		<?php }
		if(app('admin')->checkAddon('sale_return')){
		?>
		
		<div class="col-lg-2 col-md-4 col-sm-3 col-xs-6">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5><?php  echo trans("sale_return"); ?></h5>
				</div>
				<div class="ibox-content">
					<h3 class="no-margins"><?php if($total_sale['total_return']!=null){echo number_format($total_sale['total_return'],2).' '.$currency;}else{echo '0.00 '.$currency;} ?></h3>
					<small><?php echo trans('total_sale_return'); ?></small>
				</div>
			</div>
		</div>
		<?php if(app('admin')->checkAddon('multiple_payment_method')){ ?>
		<div class="col-lg-2 col-md-4 col-sm-3 col-xs-6">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5><?php  echo trans("cash_sale_return"); ?></h5>
				</div>
				<div class="ibox-content">
					<h3 class="no-margins"><?php if($total_sale['total_return_paid']!=null){echo number_format($total_sale['total_return_paid'],2).' '.$currency;}else{echo '0.00 '.$currency;} ?></h3>
					<small><?php echo trans('total_cash_sale_return'); ?></small>
				</div>
			</div>
		</div>
		<?php } ?>
			<?php if(app('admin')->checkAddon('due_sale')){ ?>
		<div class="col-lg-2 col-md-4 col-sm-3 col-xs-6">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5><?php  echo trans("due_sale_return"); ?></h5>
				</div>
				<div class="ibox-content">
					<h3 class="no-margins"><?php if($total_sale['total_return_due']!=null){echo number_format($total_sale['total_return_due'],2).' '.$currency;}else{echo '0.00 '.$currency;} ?></h3>
					<small><?php echo trans('total_due_sale_return'); ?></small>
				</div>
			</div>
		</div>
		
	<?php } ?>
	
	<?php } ?>
	
	<div class="col-lg-2 col-md-4 col-sm-3 col-xs-6">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5><?php echo trans('net_purchase'); ?></h5>
			</div>
			<div class="ibox-content">
				<h3 class="no-margins"><?php if($total_purchase['total_purchase_with_return']!=null){echo number_format($total_purchase['total_purchase_with_return'],2).' '.$currency;}else{echo '0.00 '.$currency;} ?></h3>
				<small><?php echo trans('net_purchase'); ?></small>
			</div>
		</div>
	</div>
	<?php if(app('admin')->checkAddon('multiple_payment_method')){ ?>
	<div class="col-lg-2 col-md-4 col-sm-3 col-xs-6">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5><?php echo trans('cash_purchase'); ?></h5>
			</div>
			<div class="ibox-content">
				<h3 class="no-margins"><?php if($total_purchase['total_paid_with_return']!=null){echo number_format($total_purchase['total_paid_with_return'],2).' '.$currency;}else{echo '0.00 '.$currency;} ?></h3>
				<small><?php echo trans('total_cash_purchase'); ?></small>
			</div>
		</div>
	</div>
	<?php } ?>
	<?php if(app('admin')->checkAddon('due_purchase')){ ?>
		
		<div class="col-lg-2 col-md-4 col-sm-3 col-xs-6">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5><?php echo trans('due_purchase'); ?></h5>
				</div>
				<div class="ibox-content">
					<h3 class="no-margins"><?php if($total_purchase['total_due']!=null){echo number_format($total_purchase['total_due'],2).' '.$currency;}else{echo '0.00 '.$currency;} ?></h3>
					<small><?php echo trans('total_due_purchase'); ?></small>
				</div>
			</div>
		</div>
		
		<?php }
		if(app('admin')->checkAddon('purchase_return')){
		?>
		
		<div class="col-lg-2 col-md-4 col-sm-3 col-xs-6">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5><?php echo trans('purchase_return'); ?></h5>
				</div>
				<div class="ibox-content">
					<h3 class="no-margins"><?php if($total_purchase['total_return']!=null){echo number_format($total_purchase['total_return'],2).' '.$currency;}else{echo '0.00 '.$currency;} ?></h3>
					<small><?php echo trans('total_purchase_return'); ?></small>
				</div>
			</div>
		</div>
		
		<?php if(app('admin')->checkAddon('multiple_payment_method')){ ?>
		<div class="col-lg-2 col-md-4 col-sm-3 col-xs-6">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5><?php echo trans('cash_purchase_return'); ?></h5>
				</div>
				<div class="ibox-content">
					<h3 class="no-margins"><?php if($total_purchase['total_return_paid']!=null){echo number_format($total_purchase['total_return_paid'],2).' '.$currency;}else{echo '0.00 '.$currency;} ?></h3>
					<small><?php echo trans('total_cash_purchase_return'); ?></small>
				</div>
			</div>
		</div>
		<?php } ?>
		<?php if(app('admin')->checkAddon('due_purchase')){ ?>
		<div class="col-lg-2 col-md-4 col-sm-3 col-xs-6">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5><?php echo trans('due_purchase_return'); ?></h5>
				</div>
				<div class="ibox-content">
					<h3 class="no-margins"><?php if($total_purchase['total_return_due']!=null){echo number_format($total_purchase['total_return_due'],2).' '.$currency;}else{echo '0.00 '.$currency;} ?></h3>
					<small><?php echo trans('total_due_purchase_return'); ?></small>
				</div>
			</div>
		</div>
		<?php } ?>
		
	<?php } ?>
</div>

<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5><?php echo trans('sales_chart'); ?></h5>
				<div class="pull-right">
					<div class="btn-group">
						<button type="button" class="btn btn-xs btn-white monthly">
							<?php echo trans('monthly'); ?>
						</button>
						<button type="button" class="btn btn-xs btn-white annualy">
							<?php echo trans('annual'); ?>
						</button>
					</div>
				</div>
			</div>
			<div class="ibox-content">
				<div class="row">
					<div class="col-lg-9">
						<canvas id="lineChart" height="70"></canvas>
					</div>
					<div class="col-lg-3">
						<ul class="stat-list">
							<li>
								<h2 class="no-margins total_sales"></h2>
								<small><?php echo trans('total_sale'); ?></small>
								
							</li>
							<li>
								<h2 class="no-margins total_discount"></h2>
								<small><?php echo trans('discount'); ?></small>
							</li>
							<li>
								<h2 class="no-margins total_vat"></h2>
								<small><?php echo trans('vat'); ?></small>
							</li>
						</ul>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>

<div class="row">
	<?php 
		if(app('admin')->checkAddon('stock_alert')){
		?>
		<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5><?php echo trans('low_stock_alert');?></h5>
				</div>
				<div class="ibox-content">
					<table class="table table-hover dataTables-example">
						<thead>
							<tr>
								<th style="text-align:center;">
									<?php echo trans('serial_number'); ?>
								</th>
								<th style="text-align:center;">
									<?php echo trans('product_code'); ?>
								</th>
								<th style="text-align:center;">
									<?php echo trans('product_name'); ?>
								</th>
								<th style="text-align:center;">
									<?php echo trans('low_product_quantity'); ?>
								</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								$productdetails = app('admin')->getall('pos_product');
								$count = 0;
								foreach($productdetails as $product_details){
									$totalproductin = app('admin')->getsumtotalbywhereand('pos_stock','product_quantity','stock_type','in','product_id',$product_details['product_id']);
									$totalproductout = app('admin')->getsumtotalbywhereand('pos_stock','product_quantity','stock_type','out','product_id',$product_details['product_id']);
									$product_unite = app('admin')->getwhereid('pos_unit','unit_id',$product_details['unit_id']);
									$availiablestock = $totalproductin - $totalproductout;
									if($availiablestock <= $product_details['alert_quantity'] ){
									?>
									<tr style="text-align:center;">
										<td>
											<?php echo ++$count; ?>
										</td>
										<td>
											<?php echo $product_details['product_id']; ?>
										</td>
										<td>
											<?php echo $product_details['product_name']; ?>
										</td>
										<td class="text-navy">
											<?php echo $availiablestock.' '.$product_unite['unit_name']; ?>
										</td>
									</tr>
								<?php  }}  ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<?php
		}
	?>
	<?php 
		if(app('admin')->checkAddon('expired_manufacturing_date')){
		?>
		<div class="row">
			<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5><?php echo trans('near_expire');?></h5>
					</div>
					<div class="ibox-content">
						<table class="table table-hover no-margins dataTables-example">
							<thead>
								<tr>
									<th>
										<?php echo trans('serial_number'); ?>
									</th>
									<th>
										<?php echo trans('product_code'); ?>
									</th>
									<th>
										<?php echo trans('product_name'); ?>
									</th>
									<th>
										<?php echo trans('expire_date'); ?>
									</th>
									<th>
										<?php echo trans('join_supplier'); ?>
									</th>
								</tr>
							</thead>
							<tbody>
								<?php 
									$date = new DateTime();
									$date->modify('-7 days');
									$default_startdate = $date->format('Y-m-d');
									$date = new DateTime();
									$date->modify('+7 days');
									$default_enddate = $date->format('Y-m-d');
									
									$subproductdetails = app('admin')->getall('pos_variations');
									$count = 0;
									
									// $getlastproductstock = app('pos')->getlastproductstock('PR3211001824',$default_startdate,$default_enddate);
									foreach($subproductdetails as $product_details){
										$getlastproductstock = app('pos')->getlastproductstock($product_details['sub_product_id'],$default_startdate,$default_enddate);
										$productdetails = app('admin')->getwhereid('pos_product','product_id',$product_details['product_id']);
										$totalproductin = app('admin')->getsumtotalbywhereand('pos_stock','product_quantity','stock_type','in','sub_product_id',$product_details['sub_product_id']);
										$totalproductout = app('admin')->getsumtotalbywhereand('pos_stock','product_quantity','stock_type','out','sub_product_id',$product_details['sub_product_id']);
										$availiablestock = $totalproductin - $totalproductout;
										if($availiablestock != 0 && $getlastproductstock){
										?>
										<tr style="text-align:center;<?php if($getlastproductstock['expire_date'] < date('Y-m-d')) echo 'background-color:red;color:white;'; ?>">
											<td>
												<?php echo ++$count; ?>
											</td>
											<td>
												<?php echo $product_details['sub_product_id']; ?>
											</td>
											<td>
												<?php echo $productdetails['product_name'].'['.$product_details['variation_name'].']'; ?>
											</td>
											<td>
												<?php echo getdatetime( $getlastproductstock['expire_date'],3); ?>
											</td>
											<td>
												<?php echo getdatetime($getlastproductstock['created_at'], 3); ?>
											</td>
										</tr>
									<?php  }}  ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<?php
		}
	?>
</div>
[footer]
<?php 
	getJs('assets/system/js/plugins/jquery-ui/jquery-ui.min.js'); 
	getJs('assets/system/js/plugins/chartJs/Chart.min.js'); 
	getJs('assets/system/js/plugins/dataTables/datatables.min.js',false,false);
?>
<script>
	var country_currency = $(".currency").val();
	
	$(document).ready(function() {
		getSalesChart(30);
	});
	
	$(document).on("click", ".monthly", function() {
		getSalesChart(30);
	});
	
	$(document).on("click", ".annualy", function() {
		getSalesChart(35);
	});
	
	function getSalesChart(days) {
		
		AS.Http.post({
			action: "GetAllSaleByDays",
			total_days: days
			}, "pos/ajax/", function(result) {
			
			var lineData = {
				labels: result.days,
				datasets: [{
					label: $_lang.total_sales_amount,
					backgroundColor: "rgba(26,179,148,0.5)",
					borderColor: "rgba(26,179,148,0.7)",
					pointBackgroundColor: "rgba(26,179,148,1)",
					pointBorderColor: "#fff",
					data: result.sales_amounts
					}, {
					label: $_lang.total_sales_discount,
					backgroundColor: "rgba(255,0,0,0.5)",
					borderColor: "rgba(255,0,0,1)",
					pointBackgroundColor: "rgba(255,0,0,1)",
					pointBorderColor: "#FF0000",
					data: result.sales_discount
					}, {
					label: $_lang.total_sales_vat,
					backgroundColor: "rgba(255,255,0,0.5)",
					borderColor: "rgba(255,255,0,1)",
					pointBackgroundColor: "rgba(255,255,0,1)",
					pointBorderColor: "#FFFF00",
					data: result.sales_vat
				}]
			};
			
			var lineOptions = {
				responsive: true
			};
			
			var ctx = document.getElementById("lineChart").getContext("2d");
			new Chart(ctx, {
				type: 'line',
				data: lineData,
				options: lineOptions
			});
			
			$(".total_sales").html(formatNumber(parseFloat(result.total_sales_amounts).toFixed(2)) + " " + country_currency);
			$(".total_discount").html(formatNumber(parseFloat(result.total_sales_discount).toFixed(2)) + " " + country_currency);
			$(".total_vat").html(formatNumber(parseFloat(result.total_sales_vat).toFixed(2)) + " " + country_currency);
			
		});
	}
	
	$('.dataTables-example').DataTable({
		pageLength: 10,
		responsive: true,
		dom: '<"html5buttons"B>lTfgitp',
		buttons: [
		// { extend: 'copy'},
		// {extend: 'csv',title: 'Low Stock Product List'},
		{extend: 'excel', title: 'Low Stock Product List'},
		{extend: 'pdf', title: 'Low Stock Product List'},
		{extend: 'print',
			customize: function (win){
				$(win.document.body).addClass('white-bg');
				$(win.document.body).css('font-size', '10px');
				
				$(win.document.body).find('table')
				.addClass('compact')
				.css('font-size', 'inherit');
			}
		}
		]
		
	});
</script>
[/footer]

<?php include dirname(__FILE__) .'/include/footer.php'; ?>								