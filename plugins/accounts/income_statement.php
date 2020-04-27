<?php defined('_AZ') or die('Restricted access'); 
	include dirname(__FILE__) .'/../../modules/pos/include/header.php';
	include dirname(__FILE__) .'/../../modules/pos/include/side_bar.php';
	include dirname(__FILE__) .'/../../modules/pos/include/navbar.php';
	$company_info = app("db")->table("pos_setting")->get(1);
	
?>
[header]
<?php
	getCss('assets/system/css/plugins/daterangepicker/daterangepicker-bs3.css');
	getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false);
?>
<style type="text/css">
	.m-t-md{ margin-top: 23px; }
	.incomeStatementTale tr td p{font-size:14px;}
	td{text-align:left;}
</style>
[/header]

<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="ibox">
				<div class="ibox-content">
					<form>
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
					</form>
				</div>
			</div>
		</div>
		<?php 
			$charts = app('db')->table('accounts_chart')->get();
			$administrative_cost_name = '';
			$administrative_cost_value = '';
			$operating_cost_name = '';
			$operating_cost_value = '';
			$account_income_of_sold_goods_name = '';
			$account_income_of_sold_goods_value = '';
			$account_cost_of_sold_goods_account_carriage_inward_name = '';
			$account_cost_of_sold_goods_account_carriage_inward_value = '';
			$account_opening_stock_name = '';
			$account_opening_stock_value = '';
			$account_closing_stock_name = '';
			$account_closing_stock_value = '';
			$account_cost_of_sold_goods_name = '';
			$account_cost_of_sold_goods_value = '';
			$account_non_operating_cost_name = '';
			$account_non_operating_cost_value = '';
			$account_non_operating_income_name = '';
			$account_non_operating_income_value = '';
			
			foreach($charts as $chart){
				
				if($chart['chart_category_name'] == 'account_administrative_cost'){
					$administrative_cost_name .= '<div class="'.$chart['chart_name_value'].'_label"><p>'.trans($chart['chart_name']).'</p></div>';	
					$administrative_cost_value .= '<div class="'.$chart['chart_name_value'].'_label"><p class="'.$chart['chart_name_value'].'">0</p></div>';	
				} 
				
				if($chart['chart_category_name'] == 'account_operating_cost'){
					$operating_cost_name .= '<div class="'.$chart['chart_name_value'].'_label"><p>'.trans($chart['chart_name']).'</p></div>';	
					$operating_cost_value .= '<div class="'.$chart['chart_name_value'].'_label"><p class="'.$chart['chart_name_value'].'">0</p></div>';	
				}
				if($chart['chart_category_name'] == 'account_income_of_sold_goods'){
					$account_income_of_sold_goods_name .= '<div class="'.$chart['chart_name_value'].'_label"><p>'.trans($chart['chart_name']).'</p></div>';	
					$account_income_of_sold_goods_value .= '<div class="'.$chart['chart_name_value'].'_label"><p class="'.$chart['chart_name_value'].'">0</p></div>';	
				} 
				if($chart['chart_category_name'] == 'account_cost_of_sold_goods'){
					if($chart['chart_name_value'] == "account_carriage_inward"){
						$account_cost_of_sold_goods_account_carriage_inward_name .= '<div class="text-right '.$chart['chart_name_value'].'_label text-right"><p>'.trans($chart['chart_name']).'</p></div>';	
						$account_cost_of_sold_goods_account_carriage_inward_value .= '<div class="'.$chart['chart_name_value'].'_label"><p class="'.$chart['chart_name_value'].'">0</p></div>';	
						}elseif($chart['chart_name_value'] == "account_opening_stock"){
						$account_opening_stock_name .= '<div class="'.$chart['chart_name_value'].'_label"><p>'.trans($chart['chart_name']).'</p></div>';	
						$account_opening_stock_value .= '<div class="'.$chart['chart_name_value'].'_label"><p class="'.$chart['chart_name_value'].'">0</p></div>';	
						}elseif($chart['chart_name_value'] == "account_closing_stock"){
						$account_closing_stock_name .= '<div class="'.$chart['chart_name_value'].'_label"><p>'.trans($chart['chart_name']).'</p></div>';	
						$account_closing_stock_value .= '<div class="'.$chart['chart_name_value'].'_label"><p class="'.$chart['chart_name_value'].'">0</p></div>';	
						}else{
						$account_cost_of_sold_goods_name .= '<div class="'.$chart['chart_name_value'].'_label"><p>'.trans($chart['chart_name']).'</p></div>';	
						$account_cost_of_sold_goods_value .= '<div class="'.$chart['chart_name_value'].'_label"><p class="'.$chart['chart_name_value'].'">0</p></div>';	
					}
				}  
				if($chart['chart_category_name'] == 'account_non_operating_cost'){
					$account_non_operating_cost_name .= '<div class="'.$chart['chart_name_value'].'_label"><p>'.trans($chart['chart_name']).'</p></div>';	
					$account_non_operating_cost_value .= '<div class="'.$chart['chart_name_value'].'_label"><p class="'.$chart['chart_name_value'].'">0</p></div>';	
				}  
				if($chart['chart_category_name'] == 'account_non_operating_income'){
					$account_non_operating_income_name .= '<div class="'.$chart['chart_name_value'].'_label"><p>'.trans($chart['chart_name']).'</p></div>';	
					$account_non_operating_income_value .= '<div class="'.$chart['chart_name_value'].'_label"><p class="'.$chart['chart_name_value'].'">0</p></div>';	
				}
			} 
		?>
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="ibox">
				<div class="ibox-content">
					<div class="row">
						<div class="col-lg-12 text-right">
							<button type="button" class="btn btn-primary"><i class="fa fa-print"></i></button>
							<button type="button" class="btn btn-success" onclick="printDiv()"><i class="fa fa-book"></i></button>
						</div>
					</div>
				</div>
				<div class="ibox-content" id="incomeStatementDiv">
					<div id="resultShow"></div>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 text-center">
							<h2><?php echo $company_info['company_name']; ?></h2>
							<h3><?php echo trans('income_statement');?></h3>
							<h4 class="range-date-show">00/00/0000 - 00/00/0000</h4>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-8 col-md-8 col-sm-8 col-lg-offset-2 col-md-offset-2">
							<table class="table incomeStatementTale table-bordered">
								<thead class="text-center">
									<tr>
										<th class="text-center"><?php echo trans("details");?></th>
										<th class="text-left"><?php echo trans("taka");?></th>
										<th class="text-left"><?php echo trans("taka");?></th>
									</tr>
								</thead>
								<tbody>
									<tr style="border-bottom: none;">
										<td>
											<!--p class="text-center" style="font-weight:bold;"> (+) <?php echo trans('account_income_of_sold_goods');?></p-->
											
											<?php echo $account_income_of_sold_goods_name; ?>
										</td>
										<td>
											<div class="m-t-lg"></div>
											<?php echo $account_income_of_sold_goods_value; ?>
											
										</td>
									</tr>
									<tr style="border-bottom: none;">
										<td>
											
										</td>
										<th>
											<p class="text-right" ><?php echo trans('account_net_sale');?>=</p>
										</th>
										<td>
											<p class="account_income_of_sold_goods_net_total">0</p>
										</td>
									</tr>
									
									<tr>
										<td >
											
											<p class="text-center" style="font-weight:bold;"> (-) <?php echo trans('account_cost_of_sold_goods');?>:</p>
											<?php echo $account_opening_stock_name;?>
											<?php echo $account_cost_of_sold_goods_name; ?>
										</td>
										<td>
											<div class="m-t-lg"></div>
											<?php echo $account_opening_stock_value; ?>
											<?php echo $account_cost_of_sold_goods_value; ?>
										</td>
										<td>
											
										</td>
									</tr>
									<tr>
										<td>
											<p class="text-right" style="font-weight:bold;"> (-) <?php echo trans('total_purchase');?>=</p>
											<?php echo $account_cost_of_sold_goods_account_carriage_inward_name; ?>
										</td>
										<td>
											<p class="account_cost_of_sold_goods_net_total" style="font-weight:bold;">0</p>
											<?php echo $account_cost_of_sold_goods_account_carriage_inward_value; ?>
										</td>
										<td>
											
										</td>
									</tr>
									<tr>
										<th>
											<p class="text-right"><?php echo trans('account_net_purchase');?></p>
											<p class="text-right"> (-) <?php echo trans('account_closing_balance');?></p>
										</th>
										<td>
											<p class="account_cost_of_sold_goods_total">0</p>
											<p class="account_closing_stock">0</p>
										</td>
										<td>
											
											
										</td>
									</tr>
									<tr>
										<th>
											
										</th>
										<th>
											<p class="text-right"><?php echo trans('account_total_cost_of_sold_good');?>=</p>
										</th>
										<td>
											<p class="account_cost_of_sold_goods_closing_total">0</p>
										</td>
									</tr>
									<tr>
										<th>
										</th>
										<th>
											<p class="text-right"><?php echo trans('account_gross_profit');?> (<span class="account_income_of_sold_goods_net_total">0</span> - <span class="account_cost_of_sold_goods_closing_total">0</span>) =</p>
											
										</th>
										<td>
											<p class="account_gross_profit_total">0</p>
										</td>
									</tr>
									<tr>
										<td>
											<p class="text-center" style="font-weight:bold;">(-) <?php echo trans('account_operating_cost');?>:</p>
											<?php echo $operating_cost_name; ?>
										</td>
										<td>
											<div class="m-t-lg"></div>
											<?php echo $operating_cost_value; ?>
										</td>
										<td>
										</td>
									</tr>
									<tr style="border-bottom: none;">
										
										<td>
											
										</td>
										<th>
											<p class="text-right"><?php echo trans('total');?> <?php echo trans('account_operating_cost');?> =</p>
										</th>
										<td>
											<p class="account_operating_cost_total">0</p>
										</td>
									</tr>
									<tr>
										<td>
											<p class="text-center" style="font-weight:bold;">(-) <?php echo trans('account_administrative_cost');?>:</p>
											<?php echo $administrative_cost_name; ?>
										</td>
										<td>
											<div class="m-t-lg"></div>
											<?php echo $administrative_cost_value; ?>
										</td>
										<td>
											
										</td>
									</tr>
									<tr style="border-bottom: none;">
										<th>
										</th>
										<th>
											<p class="text-right"><?php echo trans('total');?> <?php echo trans('account_administrative_cost');?> =</p>
										</th>
										<th>
											<p class="account_administrative_cost_total ">0</p>
										</th>
									</tr>
									<tr>
										<td>
											<p class="text-center" style="font-weight:bold;">(-) <?php echo trans('account_non_operating_cost');?>:</p>
											<?php echo $account_non_operating_cost_name; ?>
										</td>
										<td>
											<div class="m-t-lg"></div>
											<?php echo $account_non_operating_cost_value; ?>
										</td>
										<td>
											
										</td>
									</tr>
									<tr style="border-bottom: none;">
										
										<td>
											
										</td>
										<th>
											<p class="text-right"><?php echo trans('total');?> <?php echo trans('account_non_operating_cost');?> =</p>
										</th>
										<td>
											<p class="account_non_operating_cost_total">0</p>
										</td>
									</tr>
									<tr>
										<td>
											<p class="text-center" style="font-weight:bold;">(+) <?php echo trans('account_non_operating_income');?>:</p>
											<?php echo $account_non_operating_income_name; ?>
										</td>
										<td>
											<div class="m-t-lg"></div>
											<?php echo $account_non_operating_income_value; ?>
										</td>
										<td></td>
									</tr>
									<tr style="border-bottom: none;">
										
										<td>
											
										</td>
										<th>
											<p class="text-right"><?php echo trans('total');?> <?php echo trans('account_non_operating_income');?> =</p>
										</th>
										<th>
											<p class="account_non_operating_income_total">0</p>
										</th>
									</tr>
									<tr style="border-bottom: none;">
										<td colspan="2">
											<p class="text-right" style="font-weight:bold;">
												<?php echo trans('account_net_income');?>
												(
												<span class="account_gross_profit_total">0</span> -
												<span class="account_operating_cost_total">0</span> -
												<span class="account_administrative_cost_total">0</span> -
												<span class="account_non_operating_cost_total">0</span> +
												<span class="account_non_operating_income_total">0</span>
												) =
											</p>
										</td>
										<th>
											<p style="font-weight:bold;" class="account_net_income_total">0</p>
										</th>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
[footer]	
<?php 
	getJs("assets/system/js/plugins/dataTables/datatables.min.js",false);
	getJs('assets/system/js/plugins/fullcalendar/moment.min.js',false);
	getJs('assets/system/js/plugins/daterangepicker/daterangepicker.js');
	getJs('assets/system/js/plugins/dataTables/datatables.min.js',false,false);
?>	
<script>
	
	$(document).ready(function(){
		
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
			incomeStatementUpdate();
		});
		
		$('#reportrange').val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
		
		function incomeStatementUpdate(){
			var start = $('input#reportrange').data('daterangepicker').startDate.format('YYYY-MM-DD');
			var end = $('input#reportrange').data('daterangepicker').endDate.format('YYYY-MM-DD');
			var storeId = $("#store_id").val() || 0;
			$('.range-date-show').html(start + ' - ' + end);
			
			AS.Http.post({"action" : "GetIncomeStatementData",store_id : storeId, from_data : start, to_data : end }, "pos/ajax/", function (response) {
				if(response.status=='success'){
					$.each(response.chart, function(index, value) {
						$.each(value, function(index, data) {
							incomeReportShowByClass(data.id,data.value);
						}); 
					}); 
				}
			});
			
		}
		
		function incomeReportShowByClass(elementClass,totalValue){
			if(Math.abs(totalValue) > 0){
				if($('.'+elementClass+'_label')){
					$('.'+elementClass+'_label').show();
				}
				$('.'+elementClass).html(formatNumber(parseFloat(totalValue).toFixed(2)));
				}else{
				if($('.'+elementClass+'_label')){
					$('.'+elementClass+'_label').hide();
				}
				$('.'+elementClass).html(formatNumber(parseFloat(totalValue).toFixed(2)));
			}
			
		}
		
		incomeStatementUpdate();
		
		$(document).on("change",".update_store", function(){
			incomeStatementUpdate();
		});
		
	});
</script>
[/footer]
<?php include dirname(__FILE__) .'/../../modules/pos/include/footer.php'; ?>																																																																																																																																																											