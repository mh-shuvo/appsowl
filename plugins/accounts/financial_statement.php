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
</style>
[/header]

<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
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
		<?php 
			$charts = app('db')->table('accounts_chart')->get();
			$current_assets_name = '';
			$current_assets_value = '';
			$fixed_assets_name = '';
			$fixed_assets_value = '';
			$current_liabilities_name = '';
			$current_liabilities_value = '';
			$long_term_liabilities_name = '';
			$long_term_liabilities_value = '';
			
			foreach($charts as $chart){
				if($chart['chart_category_name'] == 'account_long_term_liabilities'){
					if($chart['chart_name_value'] == 'account_owners_equity'){
						$long_term_liabilities_name .= '<div class="'.$chart['chart_name_value'].'_label hide_class"><h3>'.trans($chart['chart_name']).'</h3></div>';	
						$long_term_liabilities_value .= '<div class="'.$chart['chart_name_value'].'_label hide_class"><h3 class="'.$chart['chart_name_value'].'">0</h3></div>';	
						}elseif($chart['chart_category_name'] != 'account_fixed_assets' && $chart['chart_category_name'] != 'account_current_assets' && $chart['chart_category_name'] != 'account_current_liabilities' && $chart['chart_category_name'] != 'account_capital'){
						$long_term_liabilities_name .= '<div class="'.$chart['chart_name_value'].'_label hide_class"><p>'.trans($chart['chart_name']).'</p></div>';	
						$long_term_liabilities_value .= '<div class="'.$chart['chart_name_value'].'_label hide_class"><p class="'.$chart['chart_name_value'].'">0</p></div>';	
					}
				}
				
				if($chart['chart_category_name'] == 'account_fixed_assets'){
					$fixed_assets_name .= '<div class="'.$chart['chart_name_value'].'_label hide_class"><p>'.trans($chart['chart_name']).'</p></div>';	
					$fixed_assets_value .= '<div class="'.$chart['chart_name_value'].'_label hide_class"><p class="'.$chart['chart_name_value'].'">0</p></div>';	
				}
				
				if($chart['chart_category_name'] == 'account_current_assets' || $chart['chart_name_value'] == 'account_closing_stock'){
					$current_assets_name .= '<div class="'.$chart['chart_name_value'].'_label hide_class"><p>'.trans($chart['chart_name']).'</p></div>';	
					$current_assets_value .= '<div class="'.$chart['chart_name_value'].'_label hide_class"><p class="'.$chart['chart_name_value'].'">0</p></div>';	
					}elseif($chart['chart_category_name'] != 'account_current_liabilities' && $chart['chart_category_name'] != 'account_long_term_liabilities' && $chart['chart_category_name'] != 'account_capital'){
				
					if($chart['chart_sub_category_name'] == 'account_income'){
						$current_assets_name .= '<div class="due_'.$chart['chart_name_value'].'_label hide_class"><p>'.trans('due').' '.trans($chart['chart_name']).'</p></div>';	
						$current_assets_value .= '<div class="due_'.$chart['chart_name_value'].'_label hide_class"><p class="due_'.$chart['chart_name_value'].'">0</p></div>';	
						}else{
						$current_assets_name .= '<div class="advance_'.$chart['chart_name_value'].'_label hide_class"><p>'.trans('advance').' '.trans($chart['chart_name']).'</p></div>';	
						$current_assets_value .= '<div class="advance_'.$chart['chart_name_value'].'_label hide_class"><p class="advance_'.$chart['chart_name_value'].'">0</p></div>';	
					}
				}
				
				if($chart['chart_category_name'] == 'account_current_liabilities'){
					$current_liabilities_name .= '<div class="'.$chart['chart_name_value'].'_label hide_class"><p>'.trans($chart['chart_name']).'</p></div>';	
					$current_liabilities_value .= '<div class="'.$chart['chart_name_value'].'_label hide_class"><p class="'.$chart['chart_name_value'].'">0</p></div>';	
					}elseif($chart['chart_category_name'] != 'account_fixed_assets' && $chart['chart_category_name'] != 'account_current_assets' && $chart['chart_category_name'] != 'account_long_term_liabilities' && $chart['chart_category_name'] != 'account_capital'){
					if($chart['chart_sub_category_name'] == 'account_income'){
						$current_liabilities_name .= '<div class="advance_'.$chart['chart_name_value'].'_label hide_class"><p>'.trans('advance').' '.trans($chart['chart_name']).'</p></div>';	
						$current_liabilities_value .= '<div class="advance_'.$chart['chart_name_value'].'_label hide_class"><p class="advance_'.$chart['chart_name_value'].'">0</p></div>';	
						}else{
						$current_liabilities_name .= '<div class="due_'.$chart['chart_name_value'].'_label hide_class"><p>'.trans('due').' '.trans($chart['chart_name']).'</p></div>';	
						$current_liabilities_value .= '<div class="due_'.$chart['chart_name_value'].'_label hide_class"><p class="due_'.$chart['chart_name_value'].'">0</p></div>';	
						
					} 
				} 
				
			} 
		?>
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="ibox">
				<div class="ibox-content">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 text-center">
							<h2><?php echo $company_info['company_name']; ?></h2>
							<h3><?php echo trans('financial_statement');?></h3>
							<h4 class="range-date-show">00/00/0000 - 00/00/0000</h4>
						</div>
						
						<div class="col-lg-8 col-md-8 col-sm-8 col-lg-offset-2 col-md-offset-2">
							<table class="table table-striped table-bordered">
								<thead class="text-center">
									<tr>
									<th class="text-center"><?php echo trans("assets");?></th>
									<th class="text-center"><?php echo trans("taka");?></th>
									<th class="text-center"><?php echo trans("liability_and_owners_equity");?></th>
									<th class="text-center"><?php echo trans("taka");?></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>
											<h3><?php echo trans('account_current_assets');?>:</h3>
											<?php echo $current_assets_name; ?>
											
										</td>
										<td>
											<div class="m-t-lg"></div>
											<?php echo $current_assets_value; ?>
										</td>
										<td>
											<h3><?php echo trans('account_current_liabilities');?>:</h3>
											<?php echo $current_liabilities_name; ?>
										</td>
										<td>
											<div class="m-t-lg"></div>
											<?php echo $current_liabilities_value; ?>
										</td>
									</tr>
									<tr>
										<td>
											<h3><?php echo trans('account_fixed_assets');?>:</h3>
											<?php echo $fixed_assets_name; ?>
										</td>
										<td>
											<div class="m-t-lg"></div>
											<?php echo $fixed_assets_value; ?>
										</td>
										<td>
											<h3><?php echo trans('account_long_term_liabilities');?>:</h3>
											<?php echo $long_term_liabilities_name; ?>
										</td>
										<td>
											<div class="m-t-lg"></div>
											<?php echo $long_term_liabilities_value; ?>
										</td>
									</tr>
									<tr>
										<th class="text-right">
											<?php echo trans('total'); ?>
										</th>
										<th class="account_total_assets">
											0.00
										</th>
										
										<th class="text-right">
											<?php echo trans('total'); ?>
										</th>
										<th class="account_total_liability">
											0.00
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
			financialStatementUpdate();
		});
		
		$('#reportrange').val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
		
		function financialStatementUpdate(){
			var start = $('input#reportrange').data('daterangepicker').startDate.format('YYYY-MM-DD');
			var end = $('input#reportrange').data('daterangepicker').endDate.format('YYYY-MM-DD');
			var storeId = $("#store_id").val() || 0;
			$('.range-date-show').html(start + ' - ' + end);
			
			AS.Http.post({"action" : "GetFinancialStatementData",store_id : storeId, from_data : start, to_data : end }, "pos/ajax/", function (response) {
				
				$.each(response, function(index, value) {
					$.each(value, function(index, data) {
						financialReportShowByClass(data.id,data.value);
						
					}); 
				}); 
			});
			
		}
		
		function financialReportShowByClass(elementClass,totalValue){
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
		$('.hide_class').hide();
		financialStatementUpdate();
		
		$(document).on("change",".update_store", function(){
			financialStatementUpdate();
		});
		
	});
</script>
[/footer]
<?php include dirname(__FILE__) .'/../../modules/pos/include/footer.php'; ?>																																										