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
										<input class="form-control get_expense_list_filter" type="text" name="daterange" id="reportrange" placeholder="<?php echo trans('custom_daterange'); ?>" readonly />
									</div>
								</div>
							</div>
							<div class="col-lg-1 col-md- col-sm-1">
								<div class="form-group" >
									<button class="btn btn-success btn-block m-t-md" type="submit"><?php echo trans("submit");?></button>
								</div>
							</div>
						</div>
						<form>
						</div>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="ibox">
						<div class="ibox-content">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 text-center">
									<h2><?php echo $company_info['company_name']; ?></h2>
									<h3><?php echo trans('owner_equity');?></h3>
									<h4 class="range-date-show">00/00/0000 - 00/00/0000</h4>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-8 col-md-8 col-sm-8 col-lg-offset-2 col-md-offset-2">
									<table class="table table-striped table-bordered">
										<thead class="text-center">
											<tr>
												<th class="text-center"><?php echo trans("details");?></th>
												<th class="text-center"><?php echo trans("tk");?></th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td class="text-right">
													<p><?php echo trans('account_capital');?></p>
													<p class="net_income_show">
														( + ) <?php echo trans('account_net_income');?> 
													</p>
													<p class="net_loss_show">
														( - )<?php echo trans('account_net_loss');?>
													</p>
												</td>
												<td>
													<p class="total_capital">0</p>
													<p class="net_income">0</p>
												</td>
											</tr>
											<tr>
												<td></td>
												<td>
													<p class="total_capital_with_income">0</p>
												</td>
											</tr>
											<tr>
												<td>
													<p class="text-right">( - ) <?php echo trans('withdraw');?></p>
												</td>
												<td><p class="capital_withdraw">0</p></td>
											</tr>
											<tr>
												<td><p class="text-right"><?php echo trans('owner_equity');?></p></td>
												<td>
													<p class="net_capital">0</p>
												</td>
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
					OwnerEqutityUpdate();
				});
				
				$('#reportrange').val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
				
				function OwnerEqutityUpdate(){
					var start = $('input#reportrange').data('daterangepicker').startDate.format('YYYY-MM-DD');
					var end = $('input#reportrange').data('daterangepicker').endDate.format('YYYY-MM-DD');
					var storeId = $("#store_id").val() || 0;
					$('.range-date-show').html(start + ' - ' + end);
					
					AS.Http.post({"action" : "GetOwnerEqutityData",store_id : storeId, from_data : start, to_data : end }, "pos/ajax/", function (response) {
						$.each(response, function(index, value) {
							OwnerEqutityReportShowByClass(index,value);
						});
					});
				}
				
				function OwnerEqutityReportShowByClass(elementClass,totalValue){
					if(elementClass == 'net_income'){
						if(totalValue >= 0){
							$('.net_income_show').show();
							$('.net_loss_show').hide();
							}else{
							$('.net_income_show').hide();
							$('.net_loss_show').show();
						}
					}
					
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
				
				OwnerEqutityUpdate();
				
				$(document).on("change",".update_store", function(){
					OwnerEqutityUpdate();
				});
				
			});
		</script>
		[/footer]
	<?php include dirname(__FILE__) .'/../../modules/pos/include/footer.php'; ?>																																			