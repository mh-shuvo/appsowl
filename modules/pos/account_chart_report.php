<?php defined('_AZ') or die('Restricted access'); 
	include dirname(__FILE__) .'/include/header.php';
	include dirname(__FILE__) .'/include/side_bar.php';
	include dirname(__FILE__) .'/include/navbar.php';
	if(isset($this->route['id'])){
		$account_name = $this->route['id'];
		}else{
		$account_name = 0;
	}
	
?>
[header]
<?php 
	getCss('assets/system/css/plugins/daterangepicker/daterangepicker-bs3.css');
	getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false);
	getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false);
?>
[/header]
<div class="row">
	<div class="col-sm-12">
		<div class="ibox">
			<div class="ibox-title">
				<h3> <?php echo trans($account_name).' '.trans('report'); ?> </h3>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-12 ibox">
		<div class="ibox-content  m-b-sm border-bottom">
			<div class="row">
				<div class="col-sm-3">
					<div class="form-group">
						<label class="control-label">
							<?php echo trans('business_location'); ?>
						</label>
						<select class="form-control get_expense_list_filter" id="store_id" name="store_id">
							<option value="0">All</option>
							<?php $posStores = app('admin')->getall('pos_store'); foreach($posStores as $posStore){	?>
								<option value="<?php echo $posStore['store_id']; ?>" <?php if($posStore[ 'store_id']==$this->currentUser->store_id) echo "selected"; ?>>
									<?php echo $posStore['store_name']; ?>
								</option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group">
						<label class="control-label"><?php echo trans('date_range'); ?></label>
						<div class="input-group">
							<span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
							<input class="form-control get_expense_list_filter" type="text" name="daterange" id="reportrange" placeholder="<?php echo trans('custom_daterange'); ?>" readonly />
						</div>
					</div>
				</div>
				<div class="col-sm-3">
					<label class="control-label"><?php echo trans('wages_for'); ?>: </label>
					<div class="input-group" wfd-id="17">
						<span class="input-group-addon" wfd-id="19"><i class="fa fa-user"></i></span>
						<select class="form-control get_expense_list_filter" id="chart_for">
							<option value="0"><?php echo trans('all'); ?></option>
							<?php $users = app('db')->select('SELECT * FROM `pos_contact`');
								foreach($users as $user){ 
									echo "<option value='".$user['contact_id']."'>".$user['name']."</option>";
								} 
							?>
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="ibox">
			<input type="hidden" name="account_name" id="account_name" value="<?php echo $account_name; ?>">
			<div class="ibox-content table-responsive">
				<table class="table table-striped table-bordered" id="AccountChartReportTable" data-title="Acccount Chart Report"></table>
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
	
	
	function TableDataColums(){
		return [
		// { "title": $_lang.serial_no,"class": "text-center",
			// orderable: false,
			// render: function(data, type, row){
				// var html = '<p>1</p>';
				// return html;
			// }
		// },
		// { "title": $_lang.purpose,"class": "text-center", data : 'dr_account' },
		{ "title": $_lang.prayer_name,"class": "text-center", data : 'payer_name' },
		{ "title" : $_lang.status,"class": "text-center", data : 'account_status' },
		{ "title": $_lang.payment_method,"class": "text-center", data : 'payment_method' },
		{ "title": $_lang.amount,"class": "text-center account_amount", data : 'amount' }, 
		{ "title": $_lang.added_by,"class": "text-center", data : 'user_id' },
		{ "title": $_lang.note,"class": "text-center", data : 'note' },
		{ "title": $_lang.created_at,"class": "text-center", data : 'created_at' },
		{ "title": $_lang.action,"class": "text-center not-show",
			orderable: false,
			render: function(data, type, row){
				var html ='<button class="btn btn-sm btn-danger AccountDelete" data-id="'+row.id+'"><i class="fa fa-trash"></i></button>';
				return html;
			}
		}
		];
	}
	function ExpenseFilter(FilterType) {
		var businessLocation= $('#store_id').val() || 0;
		var chartFor= $('#chart_for').val() || 0;
		var start = $('input#reportrange').data('daterangepicker').startDate.format('YYYY-MM-DD');
		var end = $('input#reportrange').data('daterangepicker').endDate.format('YYYY-MM-DD');
		let ac_name = $("#account_name").val();
		AS.Http.GetDataTable('#AccountChartReportTable',TableDataColums(),{ action : "GetAccountChartReportData",account_name : ac_name, between_action : "date", from_data : start, to_data : end, date_range : true, account_for : chartFor, store_id : businessLocation},"pos/filter/",FilterType,false);
	}
	
	$(document).on('click','.AccountDelete',function(){
		var Id = $(this).data('id');
		jQuery.ajax({
			url: "pos/ajax/",
			data: {
				action	 : "TransactionDeleteById",
				id  : Id
			},
			type: "POST",
			success:function(data){
				swal(data.message, "", data.status);
				$('.confirm').click(function () {
					ExpenseFilter(true);
				});
			},
			error:function (){}
		});
	});
	
	
	
	
	$(document).on("change",".get_expense_list_filter", function(){
		ExpenseFilter(true);
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
		});
		
		$('#reportrange').val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
		
		ExpenseFilter(false);
		
	});
	
</script>
[/footer]

<?php include dirname(__FILE__) .'/include/footer.php'; ?>											