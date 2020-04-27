<?php defined('_AZ') or die('Restricted access');
	app('admin')->checkAddon('stock_adjustment',true);
	app('pos')->checkPermission('stock_adjustment','view',true) or die(redirect("/pos/access-denied"));
	include dirname(__FILE__) .'/../../modules/pos/include/header.php';
	include dirname(__FILE__) .'/../../modules/pos/include/side_bar.php';
	include dirname(__FILE__) .'/../../modules/pos/include/navbar.php';
?>
[header]
<?php 
	getCss('assets/system/css/plugins/daterangepicker/daterangepicker-bs3.css');
	getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false);
?>
<style>
	.overall_amount h2{color: #777;}
	.overall_amount h2 span{color: #a94442;}
	span i{color: #1ab394;}
	.stock_adjustment{min-height:163px;}
	.table{border-top:none;}
</style>
[/header]
<div class="row wrapper">
	<div class="col-sm-12">
		<h2><?php echo trans('stock_adjustment_report'); ?></h2>
	</div>
	<div class="col-sm-12">
		<div class="row">
		<?php
			if(app('admin')->checkAddon('multiple_store_warehouse')){
		?>
			<div class="col-sm-4">
				<form>
					<div class="form-group">
						<label class="control-label"><?php echo trans('all_location'); ?></label>
						<div class="input-group">
							<span class="input-group-addon">
								<i class="fa fa-map-marker"></i>
							</span>
							<select class="form-control get_by_filter" name="store_id" id="store_id">
								<option value="0">All</option>
								<?php $locations = app('admin')->getall('pos_store'); foreach($posStores as $posStore);
									foreach($locations as $location){
										if($this->currentUser->store_id==$location['store_id']){
											echo "<option value='".$location['store_id']."' selected >".$location['store_name']."</option>";
											}else{
											echo "<option value='".$location['store_id']."'>".$location['store_name']."</option>";
										}
									} 
								?>
							</select>
						</div>
						
					</div>
				</form>
			</div>
			<?php } ?>
			<div class="col-sm-4">
				<div class="form-group" id="date_1">
					<label class="control-label"><?php echo trans('date_range'); ?>:</label>
					<div class="input-group">
						<span class="input-group-addon">
							<i class="fa fa-calendar"></i>
						</span>
						<input class="form-control get_by_filter" type="text" name="daterange" id="reportrange" placeholder="<?php echo trans('custom_daterange'); ?>" />
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
						
						<div class="row">
							<div class="col-sm-6">
								<strong><?php echo trans('total_normal');?>:</strong>
							</div>
							<div class="col-sm-6">
								<label class="total_normal">0</label>
							</div>
						</div> </br>
						<div class="row">
							<div class="col-sm-6">
								<strong><?php echo trans('total_abnormal');?>:</strong>
							</div>
							<div class="col-sm-6">
								<label class="total_abnormal">0</label>
							</div>
						</div></br>
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="ibox">
					
					<div class="ibox-content">
						<div class="row">
							<div class="col-sm-6">
								<strong><?php echo trans('total_amount_recovered');?>:</strong>
							</div>
							<div class="col-sm-6">
								<label class="total_recovered">0</label>
							</div>
						</div></br>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-12">
		<div class="ibox">
			<div class="ibox-title">
				<h3><?php echo trans('stock_adjustment'); ?></h3>
			</div>
			<div class="ibox-content">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover stock_adjustment_table" data-title='Stock adjustment' ></table>
				</div>
			</div>
		</div>
	</div>
</div>
	<div class="ModalForm">
		<div class="modal_status"></div>
	</div>
[footer]
<?php
	getJs('assets/system/js/plugins/dataTables/datatables.min.js',false,false);
	getJs('assets/system/js/plugins/fullcalendar/moment.min.js',false);
	getJs('assets/system/js/plugins/daterangepicker/daterangepicker.js');
?>
<script>
	$(document).on("change",".get_by_filter", function(){
		CustomDateRangeFilter(true);
		var storeId= $('#store_id').val() || null;
		start = $('input#reportrange').data('daterangepicker').startDate.format('YYYY-MM-DD');
		end = $('input#reportrange').data('daterangepicker').endDate.format('YYYY-MM-DD');
		AS.Http.post({"action" : "GetStockAdjustmentReportData", from_data:start, to_data:end,store_id:storeId}, "pos/ajax/", function (response) {
			$('.total_normal').html(response.total_normal || 0);
			$('.total_abnormal').html(response.total_abnormal || 0);
			$('.total_recovered').html(response.total_recovered || 0);
			
		});
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
		}, 
		function (start,end) {
			$("#reportrange").val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
		});
		
		$('#reportrange').val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
		
		var storeId= $('#store_id').val() || null;
		AS.Http.post({"action" : "GetStockAdjustmentReportData", from_data:start.format('YYYY-MM-DD'), to_data:end.format('YYYY-MM-DD'),store_id:storeId}, "pos/ajax/", function (response) {
			$('.total_normal').html(response.total_normal || 0);
			$('.total_abnormal').html(response.total_abnormal || 0);
			$('.total_recovered').html(response.total_recovered || 0);
			
		});
		CustomDateRangeFilter(false);
		
	});
	
	function TableDataColums(){
		return [
		{ "title": $_lang.date,"class": "text-center", data : 'created_at' },
		{ "title": $_lang.adjustment_id,"class": "text-center", data : 'stock_adjustment_id' },
		{ "title": $_lang.reference_no,"class": "text-center", data : 'reference_no' },
		{ "title": $_lang.store,"class": "text-center", data : 'pos_store/store_name' },
		{ "title": $_lang.adjustment_type,"class": "text-center", data : 'type' },
		{ "title": $_lang.recovered,"class": "text-center", data : 'pos_transactions/transaction_amount' },
		{ "title": $_lang.added_by,"class": "text-center", data : 'user_id' },
		{ "title": $_lang.created_at,"class": "text-center", data : 'created_at' },
		{ "title": $_lang.action,"class": "text-center not-show",
			orderable: false,
			render: function (data, type, row) {
				return '<div class="btn-group">'
				+'<button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">Action<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>'
				+'<ul class="dropdown-menu pull-right" role="menu">'
				+'<li><a href="javascript:void(0)" class="stock_adjustment_view" stock_adjustment_id="'+row.stock_adjustment_id+'" ><i class="fa fa-eye"></i> '+$_lang.view+'</a></li>'
				+'<li><a href="javascript:void(0)" class="delete_stock_adjustment" stock_adjustment_id="'+row.stock_adjustment_id+'"><i class="glyphicon glyphicon-trash"></i> '+$_lang.delete+'</a></li>'
				+'</ul>';
			}
		}
		];
	}
	
	function CustomDateRangeFilter(FilterType) {
		var businessLocation= $('#store_id').val() || null;
		var start = $('input#reportrange').data('daterangepicker').startDate.format('YYYY-MM-DD');
		var end = $('input#reportrange').data('daterangepicker').endDate.format('YYYY-MM-DD');
		console.log(start);
		AS.Http.GetDataTable('.stock_adjustment_table',TableDataColums(),{ action : "GetStockAdjustmentReportData", between_action : "date", from_data : start, to_data : end, date_range : true, store_id : businessLocation},"pos/filter/",FilterType,);
	}
	
	$(document).on("click",".confirm", function(){
		$('.show_modal').modal('hide');
		CustomDateRangeFilter(true);
	});
	
	$(document).on("click",".stock_adjustment_view", function(){
		var stock_adjustment_id = $(this).attr('stock_adjustment_id');
		$(".show_modal").remove();
		AS.Http.posthtml({"action" : "GetStockAdjustmentView","stock_adjustment_id" : stock_adjustment_id}, "pos/modal/", function (data) {
			$(".modal_status").html(data);
			$(".show_modal").modal("show");
		});
	});
	
	$(document).on("click",".delete_stock_adjustment", function(){
		var stock_adjustment_id = $(this).attr('stock_adjustment_id');
		swal( {
			title: $_lang.are_you_sure,
			type: "warning", 
			showCancelButton: true, 
			confirmButtonColor: "#DD6B55", 
			confirmButtonText: $_lang.yes, 
			cancelButtonText: $_lang.no, 
			closeOnConfirm: false, 
			closeOnCancel: true
			},function (isConfirm) {
			if (isConfirm) {
				AS.Http.post({"action" : "DeleteStockAdjustment","stock_adjustment_id": stock_adjustment_id}, "pos/ajax/", function (response) {
					if(response.status=='success'){
						swal({
							title: $_lang.deleted, 
							text: response.message, 
							type: "success",
							confirmButtonColor: "#1ab394", 
							confirmButtonText: $_lang.ok,
						});
					}else{ CustomDateRangeFilter(true); }
				});
			}else { CustomDateRangeFilter(true); }
		})
	});
	
	$(document).ready(function(){
		$('#data_1 .date').datepicker({
			todayBtn: "linked",
			format: "yyyy-mm-dd",
			keyboardNavigation: false,
			forceParse: false,
			calendarWeeks: true,
			autoclose: true
		});		
	});
	
</script>
[/footer]
<?php include dirname(__FILE__) .'/../../modules/pos/include/footer.php'; ?>									