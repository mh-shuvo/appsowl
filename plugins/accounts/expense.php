<?php defined('_AZ') or die('Restricted access');
	include dirname(__FILE__) .'/../../modules/pos/include/header.php';
	include dirname(__FILE__) .'/../../modules/pos/include/side_bar.php';
	include dirname(__FILE__) .'/../../modules/pos/include/navbar.php';
	
?>
[header]
<?php 
	getCss('assets/system/css/plugins/daterangepicker/daterangepicker-bs3.css');
	getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false);
	getCss('assets/system/js/plugins/typehead/jquery.typeahead.css',false); 
?>
<style type="text/css">
	.add_account{margin-top: -42px;}
</style>
[/header]
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="ibox-content  m-b-sm border-bottom">
		<div class="row">
			<div class="col-sm-3">
				<div class="form-group">
					<label class="control-label">
						<?php echo trans('business_location'); ?>
					</label>
					<select class="form-control get_expense_list_filter" id="store_id" name="store_id">
						<option value="0"><?php echo trans('all'); ?></option>
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
				<label class="control-label"><?php echo trans('expense_for'); ?>: </label>
				<div class="input-group" wfd-id="17">
					<span class="input-group-addon" wfd-id="19"><i class="fa fa-user"></i></span>
					<select class="form-control get_expense_list_filter" id="expense_for">
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
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h2><?php echo trans('expense_list'); ?></h2>
					<?php if(app('pos')->checkPermission('accounts','edit',true)){ ?>
						<a href="javascript:void(0)" class="btn btn-success add_account pull-right"><i class="fa fa-plus-circle"></i> <?php echo trans('add'); ?></a>
					<?php } ?>
				</div>
				
				<div class="ibox-content">
					
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover expense_table">
							<tfoot>
								<tr class="bg-gray font-17 footer-total text-center">
									<td><?php echo trans("total"); ?> :</td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
							</tfoot>
						</table>
					</div>
					
				</div>
			</div>
		</div>
	</div>
</div>
<div class="ModalForm">
	<div class="modal_status"></div>
</div>
<div class="modal inmodal show_extra_modal" aria-hidden="true">
	<div class="extra_modal_status"></div>
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
		{ "title": $_lang.purpose,"class": "text-center", data : 'chart_name' },
		{ "title": $_lang.total_amount,"class": "text-center account_amount", data : 'amount' }, 
		{ "title": $_lang.paid_amount,"class": "text-center account_amount", orderable: false, data : 'paid_amount' }, 
		{ "title": $_lang.due,"class": "text-center account_amount", orderable: false, data : 'due_amount' }, 
		{ "title": $_lang.advance,"class": "text-center account_amount", orderable: false, data : 'advance_amount' }, 
		{ "title": $_lang.action,"class": "text-center not-show",
			orderable: false,
			render: function (data, type, row) {
				
				var html ='<button class="btn btn-sm btn-info ViewAccount" data-ac_name="'+row.chart_name_value+'">'+$_lang.view+'</button>';
				return html;
			}
		}
		];
	}
	
	function CustomDateRangeFilter(FilterType) {
		var businessLocation= $('#store_id').val() || null;
		var expenseFor= $('#expense_for').val() || null;
		var start = $('input#reportrange').data('daterangepicker').startDate.format('YYYY-MM-DD');
		var end = $('input#reportrange').data('daterangepicker').endDate.format('YYYY-MM-DD');
		AS.Http.GetDataTable('.expense_table',TableDataColums(),{ action : "GetExpenseData", from_data : start, to_data : end, account_for : expenseFor, store_id : businessLocation},"pos/filter/",FilterType,['account_amount'],0,100,{'amount':"0.00"});
	}
	
	$(document).on("change",".get_expense_list_filter", function(){
		CustomDateRangeFilter(true);
	});
	
	$(document).on("click",".ViewAccount", function(){
		let name = $(this).data('ac_name');
		location.href="pos/expense-report/"+name;
	});
	
	$(document).on("click",".confirm", function(){
		$('.show_modal').modal('hide');
		CustomDateRangeFilter(true);
	});
	
	$(document).on("click",".new_account_close", function(){
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
		});
		
		$('#reportrange').val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
		
		CustomDateRangeFilter(false);
		
	});
	
	$(document).on("click",".add_account", function(){
		var add_account_type = 'account_expense';
		$(".show_modal").remove();
		AS.Http.posthtml({"action" : "AddAccount","add_account_type" : add_account_type}, "pos/modal/", function (data) {
			$(".modal_status").html(data);
			$(".show_modal").modal("show");
		});
	});
	
	$(document).on("click",".account_view", function(){
		var account_id = $(this).attr('account_id');
		$(".show_modal").remove();
		AS.Http.posthtml({"action" : "AccountView","account_id" : account_id}, "pos/modal/", function (data) {
			$(".modal_status").html(data);
			$(".show_modal").modal("show");
		});
	});
	
	$(document).on("click",".account_edit", function(){
		var account_id = $(this).attr('account_id');
		var store_name = $(this).data('store_name');
		var category = $(this).attr('category');
		var reference_no = $(this).attr('reference_no');
		var date = $(this).attr('date');
		var account_amount = $(this).attr('account_amount');
		var account_for = $(this).attr('account_for');
		var account_details = $(this).attr('account_details');
		var method = $(this).attr('method');
		var attached_document = $(this).attr('attached_document');
		var add_account_type = 'account_expense';
		$(".show_modal").remove();
		AS.Http.posthtml({"action" : "AddAccount","account_id" : account_id,"add_account_type" : add_account_type,"attached_document" : attached_document}, "pos/modal/", function (data) {
			$(".modal_status").html(data);
			$("#business_location").val(store_name);
			$("#category").val(category);
			$("#reference_no").val(reference_no);
			$("#date").val(date);
			$("#total_amount").val(account_amount);
			$("#account_for").val(account_for);
			$("#method").val(method);
			$("#note").val(account_details);
			$(".show_modal").modal("show");
		});
	});
	
	$(document).on("click",".delete_account_id", function(){
		var account_id = $(this).attr('account_id');
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
				AS.Http.post({"action" : "DeleteAccountId","account_id": account_id}, "pos/ajax/", function (response) {
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
	
</script>
[/footer]
<?php include dirname(__FILE__) .'/../../modules/pos/include/footer.php';?>								