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
	getCss('assets/system/js/plugins/typehead/jquery.typeahead.css',false); 
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
							<div class="col-sm-3">
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
									<h3><?php echo trans('trial_balance');?></h3>
									<h4>17/08/2019 - 18-08-2019</h4>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-8 col-md-8 col-sm-8 col-lg-offset-2 col-md-offset-2 text-center">
									<table class="table table-striped table-bordered text-center trialBalanceTable">
										<!--thead>
											<tr>
											<th class="text-center"><?php echo trans("serial_no");?></th>
											<th class="text-center"><?php echo trans("account_name");?></th>
											<th class="text-center"><?php echo trans("debit");?></th>
											<th class="text-center"><?php echo trans("credit");?></th>
											<th class="text-center"><?php echo trans("action");?></th>
											</tr>
										</thead-->
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
			getJs('assets/system/js/plugins/typehead/bootstrap3-typeahead.js',false);
			
		?>	
		<script>
			
			function TableDataColums(){
				return [
				{ "title": $_lang.serial_no,"class": "text-center", data : 'id' },
				{ "title": $_lang.account_name,"class": "text-center chart_name", data : 'chart_name' }, 
				{ "title": $_lang.tk,"class": "text-center", orderable: false, data : 'current_balance_debit' }, 
				{ "title": $_lang.tk,"class": "text-center", orderable: false, data : 'current_balance_credit' }, 
				{ "title": $_lang.tk,"class": "text-center hide", orderable: false, data : 'current_balance' }, 
				{ "title": $_lang.action,"class": "text-center not-show",
					orderable: false,
					render: function (data, type, row) {
						
						var html ='<button class="btn btn-sm btn-info ViewAccount" data-ac_name="'+row.chart_name_value+'" data-group="'+row.account_group+'">'+$_lang.view+'</button>';
						return html;
					}
				},
				];
			}
			
			function CustomDateRangeFilter(FilterType) {
				var businessLocation= $('#store_id').val() || null;
				var start = $('input#reportrange').data('daterangepicker').startDate.format('YYYY-MM-DD');
				var end = $('input#reportrange').data('daterangepicker').endDate.format('YYYY-MM-DD');
				AS.Http.GetDataTable('.trialBalanceTable',TableDataColums(),{ action : "GetTrialBalanceData", from_data : start, to_data : end, store_id : businessLocation},"pos/filter/",FilterType,null,0,100,{'current_balance':"0.00"});
			}
			
			$(document).on("click",".ViewAccount", function(){
				let name = $(this).data('ac_name'); 
				let account_group = $(this).data('group');
			
						location.href="pos/chart-report/"+name;
					
			});
			
			$(document).on("change",".update_store", function(){
				CustomDateRangeFilter(true);
			});
			
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
					CustomDateRangeFilter(true);
				});
				
				$('#reportrange').val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
				
				CustomDateRangeFilter(false);
				
			});
		</script>
		[/footer]
	<?php include dirname(__FILE__) .'/../../modules/pos/include/footer.php'; ?>														