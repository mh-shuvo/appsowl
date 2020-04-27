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
	getCss('assets/system/css/plugins/steps/jquery.steps.css',false,false);
?>
<style type="text/css">
	.add_variation_btn{margin-top: -42px;}
	
	.nav li a{
	color: white;
	}
</style>
[/header]
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="ibox-content  m-b-sm border-bottom">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="tabs-container">
					<ul class="nav nav-tabs">
						<li class="active"><a data-toggle="tab" class="btn btn-primary text-white updateCapitalType" href="#cash" data-id="cash" aria-expanded="true"> <?php echo trans('cash'); ?></a></li>
						<li class=""><a data-toggle="tab" href="#bank" class="btn btn-success updateCapitalType" data-id="bank" aria-expanded="false"><?php echo trans('bank_cheque');?></a></li>
						<li class=""><a data-toggle="tab" href="#assets" class="btn btn-warning updateCapitalType" data-id="assets" aria-expanded="false"><?php echo trans('assets');?></a></li>
						<li class=""><a data-toggle="tab" href="#loan" class="btn btn-danger updateCapitalType" data-id="loan" aria-expanded="false"><?php echo trans('loan');?></a></li>
						<!--li class=""><a data-toggle="tab" href="#lease" class="btn btn-primary" aria-expanded="false"><?php echo trans('lease');?></a></li-->
					</ul>
					<div class="tab-content">
						<div id="cash" class="tab-pane active">
							<div class="panel-body">
								<div class="row">
									<div class="col-lg-6 col-lg-offset-3 col-md-6 col-md-offset3 col-sm-12 CapitalCash">
										<form>
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
											<div class="form-group" id="data_1">
												<label class="control-label"><?php echo trans('date'); ?>:</label>
												<div class="input-group date">
													<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
													<input type="text" class="form-control" name="date" id="date" value="<?php echo date('Y-m-d');?>">
												</div>
											</div>
											<div class="form-group">
												<label class="control-label" for="amount"><?php echo trans('amount');?></label>
												<input type="text" class="form-control" name="amount" id="amount" placeholder="<?php echo trans('enter_your_amount'); ?>">
											</div>
											<div class="form-group">
												<label class="control-label" for="note"><?php echo trans('note');?></label>
												<textarea class="form-control" name="note" id="note" placeholder="<?php echo trans('note_here'); ?>"></textarea>
											</div>
											<div class="form-group">
												<label class="control-label" for="attach_document"><?php echo trans('attach_document');?></label>
												<input type="file" class="form-control form-control-file" name="attach_document" id="attach_document">
											</div>
											<div class="form-group text-center">
												<button class="btn btn-primary" type="submit"><?php echo trans('submit');?></button>
												<button class="btn btn-danger" type="reset"><?php echo trans('clear');?></button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
						<div id="bank" class="tab-pane">
							<div class="panel-body">
								<div class="row">
									<div class="col-lg-6 col-lg-offset-3 col-md-6 col-md-offset3 col-sm-12 CapitalBank">
										<form>
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
											<div class="form-group">
												<label class="control-label" for="select_bank"><?php echo trans('select_bank'); ?>:</label>
												<div class="input-group">
													<select class="form-control" id="select_bank" name="select_bank">
														
														<option value='null'><?php echo trans('select_bank'); ?></option>
														<?php
															$banks = app('db')->table('pos_payment_method')->where('payment_method_type','account_bank')->get();
															foreach($banks as $bank){
															?>
															<option value="<?php echo $bank['payment_method_value'];?>"><?php echo $bank['payment_method_name'];?></option>
														<?php } ?>
													</select>
													<span class="input-group-btn"><button class="btn btn-primary add_bank_btn" type="button"><i class="fa fa-plus-circle"></i></button></span>
												</div>
											</div>
											<div class="form-group" id="data_1">
												<label class="control-label"><?php echo trans('date'); ?>:</label>
												<div class="input-group date">
													<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
													<input type="text" class="form-control" name="date" id="date" value="<?php echo date('Y-m-d');?>">
												</div>
											</div>
											<div class="form-group">
												<label class="control-label" for="amount"><?php echo trans('amount');?></label>
												<input type="text" class="form-control" name="amount" id="amount" placeholder="<?php echo trans('enter_your_amount'); ?>">
											</div>
											<div class="form-group">
												<label class="control-label" for="note"><?php echo trans('note');?></label>
												<textarea class="form-control" name="note" id="note" placeholder="<?php echo trans('note_here'); ?>"></textarea>
											</div>
											<div class="form-group">
												<label class="control-label" for="attach_document"><?php echo trans('attach_document');?></label>
												<input type="file" class="form-control form-control-file" name="attach_document" id="attach_document">
											</div>
											<div class="form-group text-center">
												<button class="btn btn-primary" type="submit"><?php echo trans('submit');?></button>
												<button class="btn btn-danger" type="reset"><?php echo trans('clear');?></button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
						<div id="assets" class="tab-pane">
							<div class="panel-body">
								<div class="row">
									<div class="col-lg-6 col-lg-offset-3 col-md-6 col-md-offset3 col-sm-12 CapitalAssets">
										<form>
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
											<div class="form-group">
												<label class="control-label" for="select_assets"><?php echo trans('select_assets'); ?>:</label>
												<div class="input-group">
													<select class="form-control" id="select_assets" name="select_assets">
														
														<option><?php echo trans('select'); ?></option>
														<?php
															$asset_account = app('db')->table('accounts_chart')->find('account_group','"capital_assets"')->where('is_delete','false')->get();
															foreach($asset_account as $asset){
															?>
															<option value="<?php echo $asset['chart_name_value'];?>"><?php echo trans($asset['chart_name']);?></option>
														<?php } ?>
													</select>
													<span class="input-group-btn"><button class="btn btn-primary add_assets_btn" type="button"><i class="fa fa-plus-circle"></i></button></span>
												</div>
											</div>
											
											<div class="form-group" id="data_1">
												<label class="control-label"><?php echo trans('date'); ?>:</label>
												<div class="input-group date">
													<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
													<input type="text" class="form-control" name="date" id="date" value="<?php echo date('Y-m-d');?>">
												</div>
											</div>
											<div class="form-group">
												<label class="control-label" for="amount"><?php echo trans('amount');?></label>
												<input type="text" class="form-control" name="amount" id="amount" placeholder="<?php echo trans('enter_your_amount'); ?>">
											</div>
											<div class="form-group">
												<label class="control-label" for="note"><?php echo trans('note');?></label>
												<textarea class="form-control" name="note" id="note" placeholder="<?php echo trans('note_here'); ?>"></textarea>
											</div>
											<div class="form-group">
												<label class="control-label" for="attach_document"><?php echo trans('attach_document');?></label>
												<input type="file" class="form-control form-control-file" name="attach_document" id="attach_document">
											</div>
											<div class="form-group text-center">
												<button class="btn btn-primary" type="submit"><?php echo trans('submit');?></button>
												<button class="btn btn-danger" type="reset"><?php echo trans('clear');?></button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
						
						<div id="loan" class="tab-pane">
							<div class="panel-body">
								<div  class="col-lg-6 col-lg-offset-3 col-md-6 col-md-offset3 col-sm-12 CapitalLoan">
									<form>
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
										<div class="form-group">
											<label class="control-label" for="loan_with"><?php echo trans('loan_type'); ?>:</label>
											<select class="form-control" id="loan_with" name="loan_with">
												
												<option value="0"><?php echo trans('select_loan_type'); ?></option>
												<option value="account_cash"><?php echo trans('account_cash');?></option>
												<option value="account_bank"><?php echo trans('account_bank');?></option>
											</select>
										</div>
										<div class="LoanSection hidden">
											<div class="form-group" id="data_1">
												<label class="control-label"><?php echo trans('date'); ?>:</label>
												<div class="input-group date">
													<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
													<input type="text" class="form-control" name="date" id="date" value="<?php echo date('Y-m-d');?>">
												</div>
											</div>
											<div class="form-group">
												<label class="control-label" for="installment"><?php echo trans('amount');?></label>
												<input type="text" class="form-control" name="amount" id="amount" placeholder="<?php echo trans('enter_your_amount'); ?>">
											</div>
											<div class="form-group">
												<label class="control-label" for="note"><?php echo trans('note');?></label>
												<textarea class="form-control" name="note" id="note" placeholder="<?php echo trans('note_here'); ?>"></textarea>
											</div>
											<div class="form-group BankLoanSection">
												<label class="control-label" for="payment_method"><?php echo trans('payment_method'); ?>:</label>
												<div class="input-group">
													<select class="form-control" id="payment_method" name="payment_method">
														<option value="cash"><?php echo trans('select_payment_method'); ?></option>
														<?php
															$banks = app('db')->table('pos_payment_method')->where('payment_method_type','account_bank')->get();
															foreach($banks as $bank){
															?>
															<option value="<?php echo $bank['payment_method_value'];?>"><?php echo $bank['payment_method_name'];?></option>
														<?php } ?>
													</select>
													<span class="input-group-btn"><button class="btn btn-primary" type="button"><i class="fa fa-plus-circle"></i></button></span>
												</div>
											</div>
											
											<div class="form-group">
												<label class="control-label" for="amount"><?php echo trans('installment');?></label>
												<input type="text" class="form-control" name="installment" id="installment" placeholder="<?php echo trans('enter_your_installment'); ?>">
											</div>
											
											<div class="form-group">
												<label class="control-label" for="attach_document"><?php echo trans('attach_document');?></label>
												<input type="file" class="form-control form-control-file" name="attach_document" id="attach_document">
											</div>
										</div>
										<div class="form-group text-center">
											<button class="btn btn-primary" type="submit"><?php echo trans('submit');?></button>
											<button class="btn btn-danger" type="reset"><?php echo trans('clear');?></button>
										</div>
									</form>
								</div>
							</div>
						</div>
						<!--div id="lease" class="tab-pane">
							<div class="panel-body">
							<div class="row">
							<div class="col-lg-6 col-lg-offset-3 col-md-6 col-md-offset3 col-sm-12 CapitalLease">
							<form>
							<div class="form-group">
							<label class="control-label" for="amount"><?php echo trans('business_location');?></label>
							<select class="form-control" id="store_id" name="store_id">
							<option value="0"><?php echo trans('all'); ?></option>
							<?php $posStores = app('admin')->getall('pos_store'); foreach($posStores as $posStore){	?>
								<option value="<?php echo $posStore['store_id']; ?>" <?php if($posStore[ 'store_id']==$this->currentUser->store_id) echo "selected"; ?>>
								<?php echo $posStore['store_name']; ?>
								</option>
							<?php } ?>
							</select>
							</div>
							<div class="form-group">
							<label class="control-label" for="select_lease"><?php echo trans('select_add'); ?>:</label>
							<div class="input-group">
							<select class="form-control" id="select_lease" name="select_lease">
							
							<option><?php echo trans('select'); ?></option>
							<?php
								$lease_account = app('db')->table('accounts_chart')->find('account_group','"capital_assets"')->where('is_delete','false')->get();
								foreach($lease_account as $lease){
								?>
								<option value="<?php echo $lease['chart_name_value'];?>"><?php echo trans($lease['chart_name']);?></option>
							<?php } ?>
							</select>
							<span class="input-group-btn"><button class="btn btn-primary add_lease_btn" type="button"><i class="fa fa-plus-circle"></i></button></span>
							</div>
							</div>
							<div class="form-group" id="data_1">
							<label class="control-label"><?php echo trans('date'); ?>:</label>
							<div class="input-group date">
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							<input type="text" class="form-control" name="date" id="date" value="<?php echo date('Y-m-d');?>">
							</div>
							</div>
							<div class="form-group" id="data_1">
							<label class="control-label"><?php echo trans('leasting_time'); ?>:</label>
							<div class="input-group date">
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							<input type="text" class="form-control" name="leasting_time" id="date" value="<?php echo date('Y-m-d');?>">
							</div>
							</div>
							
							<div class="form-group">
							<label class="control-label" for="amount"><?php echo trans('amount');?></label>
							<input type="text" class="form-control" name="amount" id="amount" placeholder="<?php echo trans('enter_your_amount'); ?>">
							</div>
							<div class="form-group">
							<label class="control-label" for="note"><?php echo trans('note');?></label>
							<textarea class="form-control" name="note" id="note" placeholder="<?php echo trans('note_here'); ?>"></textarea>
							</div>
							<div class="form-group">
							<label class="control-label" for="attach_document"><?php echo trans('attach_document');?></label>
							<input type="file" class="form-control form-control-file" name="attach_document" id="attach_document">
							</div>
							<div class="form-group text-center">
							<button class="btn btn-primary" type="submit"><?php echo trans('submit');?></button>
							<button class="btn btn-danger" type="reset"><?php echo trans('clear');?></button>
							</div>
							</form>
							</div>
							</div>
							</div>
						</div-->
						
					</div>
					
				</div>
			</div>
		</div>
		<div class="ibox-content">
			<input id="capital_type" value="cash" type="hidden" />
			<div class="row">
				<div class="col-sm-3">
					<div class="form-group">
						<label class="control-label">
							<?php echo trans('business_location'); ?>
						</label>
						<select class="form-control get_expense_list_filter" id="filter_store_id" name="filter_store_id">
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
							<input class="form-control" type="text" name="daterange" id="reportrange" placeholder="<?php echo trans('custom_daterange'); ?>" readonly />
						</div>
					</div>
				</div>
			</div>
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover capitalTable"></table>
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
	getJs('assets/system/js/plugins/dataTables/datatables.min.js',false,false);
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
		
		$(document).on("click",".updateCapitalType", function(){
			$("#capital_type").val($(this).data("id"));
			CapitalFilter(true);
			
		});
		
		$(document).on('change','#loan_with',function(){
			let load_type = $(this).val();
			$(".LoanSection").addClass('hidden');
			$(".BankLoanSection").addClass('hidden');
			
			if(load_type != '0'){
				$(".LoanSection").removeClass('hidden');
				if(load_type == 'account_bank'){
					$(".BankLoanSection").removeClass('hidden');
				}
			}
		});
		
		$(document).on('click','.add_lease_btn',function(){
			AS.Http.posthtml({"action" : "GetNewLease","select_id" : "select_lease"}, "pos/modal/", function (data) {
				$(".modal_status").html(data);
				$(".show_modal").modal("show");
			});
		});
		
		$(document).on('click','.add_assets_btn',function(){
			AS.Http.posthtml({"action" : "GetNewAsset","select_id" : "select_assets"}, "pos/modal/", function (data) {
				$(".modal_status").html(data);
				$(".show_modal").modal("show");
			});
		});
		$(document).on('click','.add_bank_btn',function(){
			AS.Http.posthtml({"action" : "GetNewPaymentMethodModal"}, "pos/modal/", function (data) {
				$(".modal_status").html(data);
				$(".show_modal").modal("show");
			});
		});
		
		$('.CapitalCash form').validate({
			rules: {
				date: {
					required: true
				},
				amount: {
					required: true
				},
			},  
			submitHandler: function(form) {
				AS.Http.PostSubmit(form, {"action" : "CapitalCashData"}, "pos/ajax/", function (response) {
					if(response.status=='success'){
						// swal({
						// title: $_lang.success, 
						// text: response.message, 
						// type: "success",
						// confirmButtonColor: "#1ab394", 
						// confirmButtonText: $_lang.ok,
						// });
						swal({
							title: $_lang.success, 
							text: response.message, 
							type: "success",
							confirmButtonColor: "#1ab394", 
							confirmButtonText: $_lang.ok,
						},
						function (isConfirm) {
							if (isConfirm) {
								$('.CapitalCash form').trigger("reset");
								CapitalFilter(true);
							} 
						});
					}
				});
			}
		});
		
		$('.CapitalBank form').validate({
			rules: {
				date: {
					required: true
				},
				amount: {
					required: true
				},
			},  
			submitHandler: function(form) {
				AS.Http.PostSubmit(form, {"action" : "CapitalCashData"}, "pos/ajax/", function (response) {
					if(response.status=='success'){
						// swal({
						// title: $_lang.success, 
						// text: response.message, 
						// type: "success",
						// confirmButtonColor: "#1ab394", 
						// confirmButtonText: $_lang.ok,
						// });
						swal({
							title: $_lang.success, 
							text: response.message, 
							type: "success",
							confirmButtonColor: "#1ab394", 
							confirmButtonText: $_lang.ok,
						},
						function (isConfirm) {
							if (isConfirm) {
								$('.CapitalBank form').trigger("reset");
								CapitalFilter(true);
							} 
						});
					}
				});
			}
		});		
		$('.CapitalAssets form').validate({
			rules: {
				date: {
					required: true
				},
				select_assets: {
					required: true
				},
				amount: {
					required: true
				},
			},  
			submitHandler: function(form) {
				AS.Http.PostSubmit(form, {"action" : "CapitalCashData"}, "pos/ajax/", function (response) {
					if(response.status=='success'){
						// swal({
						// title: $_lang.success, 
						// text: response.message, 
						// type: "success",
						// confirmButtonColor: "#1ab394", 
						// confirmButtonText: $_lang.ok,
						// });
						swal({
							title: $_lang.success, 
							text: response.message, 
							type: "success",
							confirmButtonColor: "#1ab394", 
							confirmButtonText: $_lang.ok,
						},
						function (isConfirm) {
							if (isConfirm) {
								$('.CapitalAssets form').trigger("reset");
								CapitalFilter(true);
							} 
						});
					}
				});
			}
		});	
		
		$('.CapitalLoan form').validate({
			rules: {
				date: {
					required: true
				},
				loan_with: {
					required: true
				},
				amount: {
					required: true
				},
			},  
			submitHandler: function(form) {
				AS.Http.PostSubmit(form, {"action" : "CapitalCashData"}, "pos/ajax/", function (response) {
					if(response.status=='success'){
						swal({
							title: $_lang.success, 
							text: response.message, 
							type: "success",
							confirmButtonColor: "#1ab394", 
							confirmButtonText: $_lang.ok,
						});
						swal({
							title: $_lang.success, 
							text: response.message, 
							type: "success",
							confirmButtonColor: "#1ab394", 
							confirmButtonText: $_lang.ok,
						},
						function (isConfirm) {
							if (isConfirm) {
								$('.CapitalLoan form').trigger("reset");
								CapitalFilter(true);
							} 
						});
					}
				});
			}
		});
		
		function TableDataColums(){
			return [
			{ "title": $_lang.purpose,"class": "text-center", data : 'dr_account' },
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
		function CapitalFilter(FilterType) {
			var businessLocation= $('#filter_store_id').val() || 0;
			var capitalType = $('#capital_type').val() || 0;
			var start = $('input#reportrange').data('daterangepicker').startDate.format('YYYY-MM-DD');
			var end = $('input#reportrange').data('daterangepicker').endDate.format('YYYY-MM-DD');
			AS.Http.GetDataTable('.capitalTable',TableDataColums(),{ action : "GetCapitalData", between_action : "date", from_data : start, to_data : end, date_range : true, capital_type : capitalType, store_id : businessLocation},"pos/filter/",FilterType,false);
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
						CapitalFilter(true);
					});
				},
				error:function (){}
			});
		});
		
		
		
		
		$(document).on("change",".get_expense_list_filter", function(){
			CapitalFilter(true);
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
			CapitalFilter(true);
		});
		
		$('#reportrange').val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
		
		CapitalFilter(false);
		
	});	
</script>
[/footer]																																																																																									