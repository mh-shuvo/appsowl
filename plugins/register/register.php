<?php defined('_AZ') or die('Restricted access');
	include dirname(__FILE__) .'/../../modules/pos/include/header.php';
	include dirname(__FILE__) .'/../../modules/pos/include/side_bar.php';
	include dirname(__FILE__) .'/../../modules/pos/include/navbar.php';
?>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-sm-4 col-sm-offset-4">
			<?php
				$registerOpen = app('admin')->getwhereandid('pos_register_report','user_id',$this->currentUser->id,'register_status','open');
				if ($registerOpen!=null){
					
					$store_id = $this->currentUser->store_id;
					$start = $registerOpen['register_open'];
					$end = date("Y-m-d H:i:s");
					
					$GetSalesTotal = app('admin')->GetSum("pos_sales",array("sales_total"),array("store_id" => $store_id),array("created_at",$start,$end,false),array("sales_status" => "cancel"));
					
					$GetActualSalesTotal = app('admin')->GetSum("pos_transactions",array("transaction_amount"),array("store_id" => $store_id,"transaction_type" => "sales"),array("created_at",$start,$end,false));
					
					$GetDueSalesTotal = $GetSalesTotal['sales_total'] - $GetActualSalesTotal['transaction_amount'];
					
					$GetSaleReturnTotal = app('admin')->GetSum("pos_return",array("return_total"),array("store_id" => $store_id,"return_type" => "sales"),array("created_at",$start,$end,false));
					$GetActualSalesReturnTotal = app('admin')->GetSum("pos_transactions",array("transaction_amount"),array("store_id" => $store_id,"transaction_type" => "return","is_return" => "true"),array("created_at",$start,$end,false));
					
					
					$GetPurchaseTotal = app('admin')->GetSum("pos_purchase",array("purchase_total"),array("store_id" => $store_id),array("created_at",$start,$end,false));
					
					$GetActualPurchaseTotal = app('admin')->GetSum("pos_transactions",array("transaction_amount"),array("store_id" => $store_id,"transaction_type" => "purchase"),array("created_at",$start,$end,false));
					
					$GetDuePurchaseTotal = $GetPurchaseTotal['purchase_total'] - $GetActualPurchaseTotal['transaction_amount'];
					
					
					$GetPurchaseReturnTotal = app('admin')->GetSum("pos_return",array("return_total"),array("store_id" => $store_id,"return_type" => "purchase"),array("created_at",$start,$end,false));
					$GetActualPurchaseReturnTotal = app('admin')->GetSum("pos_transactions",array("transaction_amount"),array("store_id" => $store_id,"transaction_type" => "return","is_return" => "false"),array("created_at",$start,$end,false));
					
					
					$GetIncomeTotal = app('admin')->GetSum("pos_transactions",array("transaction_amount"),array("store_id" => $store_id,"transaction_type" => "income"),array("created_at",$start,$end,false));
					
					$GetExpenseTotal = app('admin')->GetSum("pos_transactions",array("transaction_amount"),array("store_id" => $store_id,"transaction_type" => "expense"),array("created_at",$start,$end,false));
					
					$TotalCash = $GetActualSalesTotal['transaction_amount'] - $GetActualSalesReturnTotal['transaction_amount'] - $GetActualPurchaseTotal['transaction_amount'] + $GetActualPurchaseReturnTotal['transaction_amount'] + $GetIncomeTotal['transaction_amount'] - $GetExpenseTotal['transaction_amount'];
				?>
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5><?php echo trans('register_close')." - ".$registerOpen['register_id']; ?></h5>
					</div>
					
					<div class="ibox-content" id="register_close_form">
						<form>
							<input type="hidden" name="register_id" value="<?php echo $registerOpen['register_id'];?>">
							<input type="hidden" name="closing_amount" value="<?php echo $TotalCash;?>">
							<input type="hidden" name="closing_time" value="<?php echo $end;?>">
							<div class="form-group">
								<div class="row">
									<div class="col-sm-6">
										<div class="row">
											<div class="col-sm-6 col-xs-6">
												<label><?php echo trans('sale_in_cash');?>:</label>
											</div>
											<div class="col-sm-6 col-xs-6">
												<label style="font-size: 18px; text-align: left;"><?php echo $GetActualSalesTotal['transaction_amount'];?></label>
											</div>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="row">
											<div class="col-sm-6 col-xs-6">
												<label><?php echo trans('sale_due');?>:</label>
											</div>
											<div class="col-sm-6 col-xs-6">
												<label style="font-size: 18px;"><?php echo $GetDueSalesTotal;?></label>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-sm-6">
										<div class="row">
											<div class="col-sm-6 col-xs-6">
												<label><?php echo trans('purchase_in_cash');?>:</label>
											</div>
											<div class="col-sm-6 col-xs-6">
												<label style="font-size: 18px; text-align: left;"><?php echo $GetActualPurchaseTotal['transaction_amount'];?></label>
											</div>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="row">
											<div class="col-sm-6 col-xs-6">
												<label><?php echo trans('purchase_due');?>:</label>
											</div>
											<div class="col-sm-6 col-xs-6">
												<label style="font-size: 18px;"><?php echo $GetDuePurchaseTotal;?></label>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-sm-6">
										<div class="row">
											<div class="col-sm-6 col-xs-6">
												<label><?php echo trans('sale_return');?>:</label>
											</div>
											<div class="col-sm-6 col-xs-6">
												<label style="font-size: 18px; text-align: left;"><?php echo $GetActualSalesReturnTotal['transaction_amount'];?></label>
											</div>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="row">
											<div class="col-sm-6 col-xs-6">
												<label><?php echo trans('purchase_return');?>:</label>
											</div>
											<div class="col-sm-6 col-xs-6">
												<label style="font-size: 18px;"><?php echo $GetActualPurchaseReturnTotal['transaction_amount'];?></label>
											</div>
										</div>
									</div>
								</div>
							</div>
							<?php if(app('pos')->CheckPosVariation($this->currentUser->domain_id,false,'3')){ ?>
							<div class="form-group">
								<div class="row">
									<div class="col-sm-6">
										<div class="row">
											<div class="col-sm-6 col-xs-6">
												<label><?php echo trans('income');?>:</label>
											</div>
											<div class="col-sm-6 col-xs-6">
												<label style="font-size: 18px; text-align: left;"><?php echo $GetIncomeTotal['transaction_amount'];?></label>
											</div>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="row">
											<div class="col-sm-6 col-xs-6">
												<label><?php echo trans('expense');?>:</label>
											</div>
											<div class="col-sm-6 col-xs-6">
												<label style="font-size: 18px;"><?php echo $GetExpenseTotal['transaction_amount'];?></label>
											</div>
										</div>
									</div>
								</div>
							</div>
							<?php } ?>
							<div class="form-group">
								<div class="row">
									<div class="col-sm-6">
										<div class="row">
											<div class="col-sm-6 col-xs-6">
												<label><?php echo trans('opening_cash');?>:</label>
											</div>
											<div class="col-sm-6 col-xs-6">
												<label style="font-size: 18px; color: green; text-align: left;"><?php echo $registerOpen['register_open_balance'];?></label>
											</div>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="row">
											<div class="col-sm-6 col-xs-6">
												<label><?php echo trans('closing_cash');?>:</label>
											</div>
											<div class="col-sm-6 col-xs-6">
												<label style="font-size: 18px; color: red;"><?php echo $TotalCash;?></label>
											</div>
										</div>
									</div>
								</div>
							</div>
							
							<div class="form-group">
								<div class="row">
									<div class="col-sm-3 ">
										<label><?php echo trans('closing_note'); ?></label>
									</div>
									<div class="col-sm-9">
										<textarea class="form-control" name="register_close_note"></textarea>
									</div>
								</div>
							</div>
							<button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit"><strong><?php echo trans('submit'); ?></strong></button>
							<a href="pos/home" class="btn btn-sm btn-danger pull-right m-t-n-xs"><strong><?php echo trans('close'); ?></strong></a><br>
						</form>
					</div>
				</div>
				<?php }else{ ?>
				<div class="ibox float-e-margins" id="register_form">
					<form>
						<div class="ibox-title">
							<h5><?php echo trans('register_open'); ?></h5>
						</div>
						<div class="ibox-content">
							<div class="form-group">
								<div class="row">
									<div class="col-sm-3">
										<label><?php echo trans('store');?>:</label>
									</div>
									<div class="col-sm-9">
										<select class="form-control GetPurchaseReportChange" id="store_id" name="store_id">
											<option value=""><?php echo trans('select_store'); ?></option>
											<?php $posStores = app('admin')->getwhere('pos_store','store_status','active'); foreach($posStores as $posStore){	?>
												<option value="<?php echo $posStore['store_id']; ?>" <?php if($posStore['store_id'] == $this->currentUser->store_id) echo "selected"; ?>><?php echo $posStore['store_name']; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-sm-3">
										<label><?php echo trans('cash_in_hand');?>:</label>
									</div>
									<div class="col-sm-9">
										<input type="text" class="form-control" name="amount" placeholder="<?php echo trans('amount'); ?>">
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-sm-3 ">
										<label><?php echo trans('note'); ?></label>
									</div>
									<div class="col-sm-9">
										<textarea class="form-control" name="note"></textarea>
									</div>
								</div>
							</div>
							<button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit"><strong><?php echo trans('submit'); ?></strong></button>
							<a href="pos/home" class="btn btn-sm btn-danger pull-right m-t-n-xs"><strong><?php echo trans('close'); ?></strong></a>
							<br>
							
						</div>
					</form>
				</div>
			<?php }  ?>
		</div>
	</div>
</div>
[footer]
<script>
    $('#register_form form').validate({
		rules: {
			amount: {
				required: true,
				number: true
			},
			
		},  
		submitHandler: function(form) {
			AS.Http.PostSubmit(form, {"action" : "RegisterSubmit"}, "pos/ajax/", function (response) {
				if(response.status=='success'){
					window.location = response.url;
				}
			});
		}
	});
	
	$('#register_close_form form').validate({
		submitHandler: function(form) {
			AS.Http.PostSubmit(form, {"action" : "RegisterSubmit"}, "pos/ajax/", function (response) {
				if(response.status=='success'){
					window.location = response.url;
				}
			});
		}
	});
</script>
[/footer]
<?php include dirname(__FILE__) .'/../../modules/pos/include/footer.php'; ?>							