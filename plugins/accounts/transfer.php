<?php defined('_AZ') or die('Restricted access');
	app('admin')->checkAddon('accounts',true);		
	app('pos')->checkPermission('accounts','view',true) or die(redirect("/pos/access-denied"));
	include dirname(__FILE__) .'/../../modules/pos/include/header.php';
	include dirname(__FILE__) .'/../../modules/pos/include/side_bar.php';
	include dirname(__FILE__) .'/../../modules/pos/include/navbar.php';		
?>
[header]
<?php 
	getCss('assets/system/css/plugins/daterangepicker/daterangepicker-bs3.css');
	getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false);
?>
<style type="text/css">
	.add_variation_btn{margin-top: -42px;}
</style>
[/header]
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5><?php echo trans("new_transfer");?></h5>
				</div>
				<div class="ibox-content AddPaymentTransfer">
					<form>
						<?php
							if(app('admin')->checkAddon('multiple_store_warehouse')){
							?>
							<div class="form-group">
								<label class="control-label" for="amount"><?php echo trans('business_location');?></label>
								<select class="form-control" id="store_id" name="store_id">
									<?php $posStores = app('admin')->getall('pos_store'); foreach($posStores as $posStore){	?>
										<option value="<?php echo $posStore['store_id']; ?>" <?php if($posStore[ 'store_id']==$this->currentUser->store_id) echo "selected"; ?>>
											<?php echo $posStore['store_name']; ?>
										</option>
									<?php } ?>
								</select>
							</div>
						<?php } ?>	
						<div class="form-group">
							<label class="control-label"><?php echo trans("account_from"); ?></label>
							<select class="form-control" name="account_from">
								<option><?php echo trans("select_from_account");?></option>
								<?php 
									$payment_methods = app('db')->table('pos_payment_method')->where('is_delete','false')->get();
									foreach($payment_methods as $method){
									?>
									<option value="<?php echo $method['payment_method_value'];?>"> <?php echo $method['payment_method_name'];?> </option>
								<?php }?>
							</select>
						</div>
						<div class="form-group">
							<label class="control-label"><?php echo trans("account_to"); ?></label>
							<select class="form-control" name="account_to">
								<option><?php echo trans("select_to_account");?></option>
								<?php 
									$payment_methods = app('db')->table('pos_payment_method')->where('is_delete','false')->get();
									foreach($payment_methods as $method){
									?>
									<option value="<?php echo $method['payment_method_value'];?>"> <?php echo $method['payment_method_name'];?> </option>
								<?php }?>
							</select>
						</div>
						<div class="form-group" id="data_1">
							<label class="control-label"><?php echo trans('date'); ?>:</label>
							<div class="input-group date">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								<input type="text" class="form-control" name="date" id="date" value="<?php echo date('Y-m-d');?>">
							</div>
						</div>
						<div class="from-group">
							<label class="control-label"><?php echo trans("amount");?></label>
							<input type="text" class="form-control" name="amount">
						</div>
						<div class="from-group">
							<label class="control-label"><?php echo trans("description");?></label>
							
							<textarea name="transfer_note" class="form-control" placeholder="<?php echo trans("write_something_about_transfer");?>"></textarea>
						</div>
						<div class="from-group">
							<button class="btn btn-primary m-t-sm" type="submit"> <i class="fa fa-check"></i> <?php echo trans("submit");?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5><?php echo trans("recent_transfer");?></h5>
				</div>
				<div class="ibox-content">
					<table class="table table-striped table-bordered" id="AccountTransferData"></table>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="ModalForm">
	<div class="modal_status"></div>
</div>
<?php include dirname(__FILE__) .'/../../modules/pos/include/footer.php';?>		
[footer]
<?php 
	getJs("assets/system/js/plugins/dataTables/datatables.min.js",false);
	getJs('assets/system/js/plugins/fullcalendar/moment.min.js',false);
	getJs('assets/system/js/plugins/daterangepicker/daterangepicker.js');
?>

<script>
	$(document).ready(function(){
		$('#data_1 .input-group.date').datepicker({
			todayBtn: "linked",
			format: "yyyy-mm-dd",
			keyboardNavigation: false,
			forceParse: false,
			calendarWeeks: true,
			endDate: new Date(),
			autoclose: true
		});
		GetTableData(false);
	});
	$('.AddPaymentTransfer form').validate({
		rules: {
			account_from: {
				required: true
			},
			account_to: {
				required: true
			},
			date: {
				required: true
			},
			amount: {
				required: true
			}
		},  
		submitHandler: function(form) {
			AS.Http.PostSubmit(form, {"action" : "AddNewPaymentTransferSubmit"}, "pos/ajax/", function (response) {
				if(response.status=='success'){
					swal({
						title: $_lang.success, 
						text: response.message, 
						type: "success",
						confirmButtonColor: "#1ab394", 
						confirmButtonText: $_lang.ok,
						},function(isConfirm){
						if(isConfirm){
							GetTableData(true);
							$('.AddPaymentTransfer form').trigger("reset");
						}
					});
				}
			});
		}
	});
	
	function TableDataColums(){
		return [
		// { "title": $_lang.id,"class": "text-center", data : 'id' },
		// { "title": $_lang.account_from,"class": "text-center", data : 'cr_account' },
		// { "title": $_lang.account_to,"class": "text-center", data : 'dr_account' },
		{ "title": $_lang.account_from, "class": "text-center", data : 'from_payment_method_name' },
		{ "title": $_lang.account_to, "class": "text-center", data : 'to_payment_method_name' },
		{ "title": $_lang.date, "class": "text-center", data : 'created_at' },
		{ "title": $_lang.amount, "class": "text-center not-show", data : 'amount' },
		{ "title": $_lang.description, "class": "text-center", data : 'note'},
		{ "title": $_lang.action, "class": "text-center not-show",
			orderable: false,
			render: function (data, type, row) {
				var html = '<button class="btn btn-sm btn-danger removeTransferData" data-id="'+row.id+'"><i class="fa fa-trash"></i></button>';
				return html;
			}
		}
		];
	}	
	// $(document).ready(function(){	
	// GetTableData(false);
	// });
	function GetTableData(Filtertype)
	{
		AS.Http.GetDataTable('#AccountTransferData',TableDataColums(),{ action : "GetAccountTransferData"},"pos/filter/",Filtertype);
	}
	$(document).on('click','.removeTransferData',function(){
	var Id = $(this).data('id');
	swal({
	title: $_lang.are_you_sure,
	text: "",
	type: "warning",
	showCancelButton: true,
	confirmButtonColor: "#DD6B55",
	confirmButtonText: $_lang.yes,/*"Yes!"*/
	cancelButtonText:$_lang.no ,/*"No!"*/
	closeOnConfirm: true,
	closeOnCancel: false },
	function (isConfirm) {
	if (isConfirm) {
	jQuery.ajax({
	url:"pos/ajax/",
	data:{
	action			: "TransferDelete",
	id		: Id
	},
	success:function(res){
	if(res.status=='success'){
	GetTableData(true);
	}
	}
	});
	
	} else {
	swal(
	/*$_lang.cancelled, "", "error"*/
	{
	title: $_lang.cancelled,
	text: "",
	type: "warning",
	confirmButtonColor: "#DD6B55",
	confirmButtonText: $_lang.ok,/*"Yes!"*/
	}
	); /*"বাতিল করা হয়েছে"*/
	}
	});
	});
	</script>
	
	[/footer]							