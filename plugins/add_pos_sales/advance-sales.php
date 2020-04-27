<?php defined('_AZ') or die('Restricted access');
	app('admin')->checkAddon('advance_pos_terminal',true);
	app('pos')->checkPermission('sale_pos','view',true) or die(redirect("/pos/access-denied"));
	
	$registerOpen = app('admin')->getwhereandid('pos_register_report','user_id',$this->currentUser->id,'register_status','open');
	
	if (!$registerOpen){
	    redirect('pos/registry');
	}	
	
	include dirname(__FILE__) .'/../../modules/pos/include/header.php';
	include dirname(__FILE__) .'/../../modules/pos/include/side_bar.php';
	include dirname(__FILE__) .'/../../modules/pos/include/navbar.php';	
	
	$getpossetting = app('pos')->GetPosSetting();
	
	if(isset($this->route['id'])){
		$GetSalesProduct = app('admin')->getwhereid("pos_sales","sales_id",$this->route['id']);
	}
	
	$salesId = 'SAL'.gettoken(8);
?>
[header]
<style type="text/css">
	thead tr th { text-align: center; }
	#SalesTable tbody{ text-align: center; }
	#SalesTable tbody tr td input{ text-align: center; }
	
	.panel{background-color: #b2bac9;color: black;}
	.btn-lg{height: 73px; font-weight: bold;}
	.add-payment-row{margin-top: -10px;}
	.due{color:red;}
	.due .form-group input{border:red;}
	
	.full{
	min-height: 350px
	}
	
	.row-eq-height {
	display: -webkit-box;
	display: -webkit-flex;
	display: -ms-flexbox;
	display:         flex;
	}
	.full-width-modal-dialog{
	width: 85%;
	left: 8%;
	height: auto;
	margin: 0;
	padding: 0;
	}
	.full-width-modal-content {
	height: auto;
	min-height: 100%;
	border-radius: 0;
	}
	
</style>
<?php getCss('assets/system/js/plugins/typehead/jquery.typeahead.css',false); ?>
[/header]
<!--div class="fullscreen"-->
<div class="row AddPosAdvance">
	<form id="PosSalesForm">
		<input type="hidden" name="sales_id" value="<?php if(isset($this->route['id'])){ echo $GetSalesProduct['sales_id']; }else{ echo $salesId; } ?>" readonly />
		<div class="col-sm-7">
			<div class="ibox">
				<div class="ibox-title">
					<h3><?php echo trans('pos_terminal');  ?> | <?php echo trans('sales_id'); ?> : <span class="sales_id_show"><?php if(isset($this->route['id'])){ echo $GetSalesProduct['sales_id']; }else{ echo $salesId; } ?></span></h3>
					<div class="pull-right" style="margin-top:-30px;">
						<a href="javascript:void(0)" class="last_receipt" data-toggle="modal">
							<i class="fa fa-receipt" title="Last Receipt"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<div class="input-group">
									<span class="input-group-addon"> <i class="fa fa-user"></i> </span>
									<input type="text" class="form-control customer_name_code_search" name="customer_code" id="customer_code" placeholder="<?php echo trans('enter_customer_name_customer_id');?>" value="<?php if(isset($this->route['id'])) echo $GetSalesProduct['customer_id']; ?>" />
									<div class="input-group-btn">
										<button type="button"  class="btn btn-default OpenContactModal"  title="<?php echo trans('new_contact'); ?>" ><i class="fa fa-plus-circle text-primary fa-lg"></i></button>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<div class="input-group typeahead__container">
									<span class="input-group-addon">
										<i class="fa fa-barcode"></i>
									</span>
									<input type="text" class="form-control barcode_type_search" autocomplete="off" placeholder="<?php echo trans('enter_product_name_/_squ_/_scan_barcode'); ?>" id="sales_barcode" name="sales_barcode" autofocus />
								</div>
							</div>
						</div>
					</div>
					<div class="row full">
						<div class="col-sm-12 table-responsive" >
							<table class="table table-striped table-sm" id="SalesTable" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th><?php echo trans('product_name'); ?></th>
										<th><?php echo trans('sales_price'); ?></th>
										<th><?php echo trans('quantity'); ?></th>
										<th><?php echo trans('subtotal'); ?></th>
										<th><i class="fa fa-trash"></i></th>
									</tr>
								</thead>
								<tbody>
									<?php
										if(isset($this->route['id'])){
											$productStocks = app('admin')->getwhereand('pos_stock','stock_status','active','sales_id',$GetSalesProduct['sales_id']);
											foreach($productStocks as $productStock ) { 
												$getProductVariation = app('admin')->getwhereid("pos_variations","sub_product_id",$productStock['sub_product_id']);
												$getProduct = app('admin')->getwhereid("pos_product","product_id",$getProductVariation['product_id']);
												$getUnit = app('admin')->getwhereid("pos_unit","unit_id",$getProduct['unit_id']);
												$GetProductAvaliableStock = app('pos')->GetProductAvaliableStock(null,$getProductVariation['sub_product_id']);
											?>
											<tr id="<?php echo $productStock['sub_product_id']; ?>">
												<td>
													<?php echo $getProduct['product_name']; ?> [<?php echo $productStock['sub_product_id']; ?>]<?php if(!empty($getProductVariation['variation_name']))echo '['.$getProductVariation['variation_name'].']'; ?>
													<input type="hidden" value="<?php echo $productStock['sub_product_id']; ?>" name="sub_product_id[]">
													<input type="hidden" value="<?php echo $productStock['sales_purchase_price']; ?>" name="sales_purchase_price[]">
													<input type="hidden" value="<?php echo $productStock['product_id']; ?>" name="product_id[]">
													<input type="hidden" value="<?php echo $productStock['stock_id']; ?>" name="product_stock_id[]">
													<input type="hidden" value="<?php echo $productStock['product_vat']; ?>" name="product_vat[]" data-id="<?php echo $productStock['sub_product_id']; ?>" id="product_vat_<?php echo $productStock['sub_product_id']; ?>">
													<input type="hidden" value="<?php echo $productStock['product_vat_value']; ?>" name="product_vat_value[]" data-id="<?php echo $productStock['sub_product_id']; ?>" id="product_vat_value_<?php echo $productStock['sub_product_id']; ?>">
													<input type="hidden" value="<?php echo $productStock['product_vat_type']; ?>" name="product_vat_type[]" data-id="<?php echo $productStock['sub_product_id']; ?>" id="product_vat_type_<?php echo $productStock['sub_product_id']; ?>">
													<input type="hidden" data-id="<?php echo $productStock['sub_product_id']; ?>" id="total_product_vat_<?php echo $productStock['sub_product_id']; ?>" name="total_product_vat[]" value="<?php echo $productStock['product_vat_total']; ?>">
												</td>
												<td>
													<input type="text" value="<?php echo $productStock['product_price']; ?>" name="product_price[]" data-id="<?php echo $productStock['sub_product_id']; ?>" id="product_price_<?php echo $productStock['sub_product_id']; ?>" class="form-control input-sm onchange_sales_cal" placeholder="<?php echo trans('product_price'); ?>">
												</td>
												<td>
													<div class="input-group m-b"><input type="number" value="<?php echo $productStock['product_quantity']; ?>" name="product_quantity[]" data-id="<?php echo $productStock['sub_product_id']; ?>" id="product_quantity_<?php echo $productStock['sub_product_id']; ?>" class="form-control input-sm onchange_sales_cal onchange_sales_qty" placeholder="<?php echo trans('product_quantity'); ?>">
														<span class="input-group-addon"><?php echo $getUnit['unit_name']; ?></span>
													</div>
												</td>
												<td>
													<input type="text" readonly="" value="<?php echo $productStock['product_subtotal']; ?>" name="product_subtotal[]" data-id="<?php echo $productStock['sub_product_id']; ?>" id="product_subtotal_<?php echo $productStock['sub_product_id']; ?>" class="form-control input-sm onchange_sales_cal onchange_sales_subtotal" placeholder="<?php echo trans('product_subtotal'); ?>">
												</td>
												<td>
													<a href="javascript:void(0);" data-stock-id="<?php echo $productStock['stock_id']; ?>" data-id="<?php echo $productStock['sub_product_id']; ?>" class="btn btn-danger btn-xs sales_product_delete"><i class="fa fa-trash"></i></a>
												</td>
											</tr>
										<?php }}else{ ?>
										<tr id="empty_cart"><td></td><td></td><td></td><td></td><td></td></tr>
									<?php } ?>
									
								</tbody>
							</table>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12 ">
							<div class="panel panel-default">
								<div class="panel-body">
									<div class="row">
										<div class="col-sm-3">
											<div class="form-group">
												<label class="control-label"><?php echo trans('subtotal'); ?>:</label><br>
												<input class="form-control input-sm" type="text" readonly name="sales_sub_total" value="<?php if(isset($this->route['id'])){ echo $GetSalesProduct['sales_subtotal']; }else{ echo '0.00';} ?>">
											</div>
										</div>
										<div class="col-sm-3">
											
											<div class="form-group">
												<label class="control-label"><?php echo trans('vat'); ?>:</label><br>
												<input class="form-control input-sm" type="text" readonly name="sales_vat" value="<?php if(isset($this->route['id'])){ echo $GetSalesProduct['sales_vat']; }else{ echo '0.00';} ?>">
											</div>
										</div>
										<div class="col-sm-3">
											<div class="form-group">
												<div class="row">
													<div class="col-sm-12">
														<label class="control-label"><small><?php echo trans('discount'); ?> </small></label>
														<span class="discount_amount_value pull-right"></span>
													</div>
													<div class="col-sm-5">
														<select class="input-sm onchange_discount_type_update" name="sales_discount_type">
															<option value="percent" <?php if(isset($this->route['id'])){ if($GetSalesProduct['sales_discount_type'] == "percent") echo 'selected'; } ?>>%</option>
															<option value="fixed" <?php if(isset($this->route['id'])){ if($GetSalesProduct['sales_discount_type'] == "fixed") echo 'selected'; } ?>>Tk</option>
														</select>
													</div>
													<div class="col-sm-7">
														<input class="form-control input-sm onchange_sales_final_cal" type="text" name="salesdiscount_type_amount" value="<?php if(isset($this->route['id'])){ echo $GetSalesProduct['sales_discount_value']; }else{ echo '0.00';} ?>">
														<input type="hidden" name="sales_discount" value="<?php if(isset($this->route['id'])){ echo $GetSalesProduct['sales_discount']; }else{ echo '0.00';} ?>">
													</div>
													
												</div>
											</div>
										</div>
										<div class="col-sm-3">
											
											<div class="form-group">
												<label class="control-label"><?php echo trans('total'); ?>:</label><br>
												<input class="form-control input-sm onchange_sales_final_cal" type="text" readonly name="sales_total" value="<?php if(isset($this->route['id'])){ echo $GetSalesProduct['sales_total']; }else{ echo '0.00';} ?>">
											</div>
										</div>
										<hr>
										<div class="col-sm-3">
											
											<div class="form-group">
												<label class="control-label"><?php echo trans('need_to_pay'); ?>:</label><br>
												<input class="form-control input-sm transaction-total-bill" type="text" readonly name="sales_need_to_pay" value="<?php if(isset($this->route['id'])){ echo $GetSalesProduct['sales_total']; }else{ echo '0.00';} ?>">
											</div>
										</div>
										
										<div class="col-sm-6">
											
											<div class="form-group">
												<label class="control-label"><?php echo trans('receive_amount'); ?>:</label><br>
												<input class="form-control input-sm payment-total-value onchange_sales_final_cal" type="text" name="sales_receive_amount" value="<?php if(isset($this->route['id'])){ echo $GetSalesProduct['sales_pay_cash']; }else{ echo '';} ?>">
											</div>
										</div>
										<div class="col-sm-3 pay_change_label">
											<div class="form-group">
												<label class="control-label"><?php echo trans('pay_change'); ?>:</label><br>
												<input class="form-control input-sm" type="text" readonly name="sales_pay_change" value="<?php if(isset($this->route['id'])){ echo $GetSalesProduct['sales_pay_change']; }else{ echo '0.00';} ?>">
											</div>
										</div>
										<div class="col-sm-3 due_amount_label due hidden">
											<div class="form-group">
												<label class="control-label"><?php echo trans('due_amount'); ?>:</label><br>
												<input class="form-control input-sm" type="text" readonly name="due_amount" value="<?php if(isset($this->route['id'])){ echo $GetSalesProduct['sales_pay_cash'] - $GetSalesProduct['sales_total']; }else{ echo '0.00';} ?>">
											</div>
										</div>
									</div>
									<div class="clearfix"></div>
									<div class="row">
										<div class="col-sm-2">
											<button type="button" class="btn btn-danger btn-block SalesReseatNow"><i class="fa fa-delete"></i> <?php echo trans('reset'); ?></button>
											<button type="submit" value="sales_update" class="btn btn-info btn-block"><i class="fa fa-update"></i> <?php echo trans('update'); ?></button>
										</div>
										<?php 
											
											if(app('admin')->checkAddon('multiple_payment_method')){										
											?>
											<div class="col-sm-4">
												<button type="button" class="btn btn-primary btn-block btn-lg multipay"><i class="fa fa-check"></i>  
												<?php echo trans('multipay'); ?></button>
											</div>
											<?php
											}
											if(!isset($this->route['id'])) {
											?>
											<div class="col-sm-3">
												<button type="submit" value="cash_payment" class="btn btn-success btn-block btn-lg" data-original-content="<?php echo trans('cash'); ?>" ><i class="fa fa-check"></i><?php echo trans('cash'); ?></button>
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
					</div>				
					
				</div>
			</div>
		</div>
		<div class="modal_status"></div>
		<?php  if(app('admin')->checkAddon('multiple_payment_method')){ ?>
			<div class="modal fade in show_payment_modal">
				<div class="modal-dialog modal-lg full-width-modal-dialog" role="document">
					<div class="modal-content full-width-modal-content" >
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close" ><span aria-hidden="true">×</span></button>
							<h4 class="modal-title"><?php echo trans('multiple_payment'); ?></h4>
						</div>
						<div class="modal-body" >
							<div id="payment_div" data-total-bill=""></div>
						</div>
						<div class="modal-footer">
							<button type="submit" value="<?php if(isset($this->route['id'])){ echo "sales_update";  }else{ echo "multi_payment";} ?>" data-original-content="<?php echo trans('finalize_amount'); ?>" class="btn btn-primary"><?php echo trans('finalize_amount');?></button>
							<button type="button" class="btn btn-default" data-dismiss="modal" ><?php echo trans('close');?></button>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
	</form>
	<div class="col-sm-5">
		<div class="ibox">
			<div class="ibox-content">
				<div class="row">
					<div class="m-b-sm  col-md-4">
						<input type="text" class="form-control product_search_by_name_code" placeholder="<?php echo trans('search_by_product_name'); ?>" id="product_name_search">
					</div> 
					<div class="m-b-sm  col-md-4">
						<select class="selectpicker form-control product_search" data-show-subtext="true" data-live-search="true" style="height: 150px;" id="product_brand" >
							<option value=""><?php echo trans('search_by_brand'); ?></option>
							<?php $pos_brands = app('admin')->getall('pos_brands'); 
								foreach($pos_brands as $pos_brand){
									echo "<option value=".$pos_brand['brand_id'].">".$pos_brand['brand_name']."</option>";
								}?>
						</select>
					</div> 
					<div class="m-b-sm  col-md-4">
						<select class="selectpicker form-control product_search" data-show-subtext="true" data-live-search="true" style="height: 150px;" id="product_category" >
							<option value=""><?php echo trans('search_by_category'); ?></option>
							<?php $pos_category = app('admin')->getall('pos_category'); 
								foreach($pos_category as $pos_Category){
									echo "<option value=".$pos_Category['category_id'].">".$pos_Category['category_name']."</option>";
								}?>
						</select>
					</div>
				</div>
				<div class="row " id="product_list"></div>
			</div>
		</div>
	</div>
</div>
<div class="outside_modal_status"></div>
<div class='last_receipt_view hidden'></div>
<div class="ModalForm">
	<div class="serial_modal_status"></div>
	[footer]
	<?php
		getJs('assets/system/js/plugins/dataTables/datatables.min.js',false,false);
		getJs('assets/system/js/plugins/typehead/bootstrap3-typeahead.js',false);
		// getJs('assets/system/js/plugins/typehead/jquery.typeahead.js',false);
		
	?>
</script>
<?php  if(app('admin')->checkAddon('multiple_payment_method')){ ?>
	<script>
		$(document).on("click",".multipay", function(){
			GetMultipaymentModal();
			$(".show_payment_modal").modal("show");
		});
		
		function paymentAddUpdate(){
			SalesFinalCal();
		}
		
		function GetMultipaymentModal(){
			var salesId =  $('input[name="sales_id"]').val();
			if (document.getElementById('payment_modal_table')) {
				GetTotalTransaction();
				}else{
				AS.Http.posthtml({"action" : "GetPaymentModal", "where_name" : "sales_id", "where_value" : salesId, "where_type" : "sales" }, "pos/modal/", function (data) {
					$("#payment_div").html(data);
					GetTotalTransaction();
				});
			}
		}
		
		<?php if(isset($this->route['id'])) echo "GetMultipaymentModal();" ; ?>
		
	</script>
<?php } ?>
<script type="text/javascript">
	<?php if(isset($this->route['id'])) echo "SalesFinalCal();" ; ?>
	
	$(document).on("change",".onchange_discount_type_update", function(){
		SalesFinalCal();
	});
	
	
	$(document).on("click","button[type=submit]", function(){
		$('#PosSalesForm').find("button[type=submit].active").removeClass("active");
		$(this).addClass('active');
	});
	
	
	
	$(document).ready(function() {
		$(window).keydown(function(event){
			if(event.keyCode == 13) {
				event.preventDefault();
				return false;
			}
		});
	});
	
	
	
	$('.AddPosAdvance form').validate({
		submitHandler: function(form) {
			AS.Http.PostSubmit(form, {"action" : "GetPosAdvanceData"}, "pos/ajax/", function (response) {
				$(".show_modal").remove();
				AS.Http.posthtml({"action" : "GetCustomerReceiptModal", "sales_id" : response.sales_id }, "pos/modal/", function (data) {
					$(".outside_modal_status").html(data);
					$(".show_modal").modal("show");
				});
				if(response.submit_type != 'sales_update'){
					resetPos();
				}
			});
		}
	}); 
	
	function AddSalesProductList(productCode,SerialNo = 'no_serial'){
		AS.Http.post({"action" : "GetSalesProductList","product_code" : productCode,"serial_no_check" : SerialNo}, "pos/ajax/", function (response) {
			if(response.product_status == 'found'){
				var product_quantity = $("#product_quantity_"+response.sub_product_id).val() || 0;
				if(response.product_current_stock > product_quantity || response.product_stock == 'enable'){
					<?php 
						if(app('admin')->checkAddon('serial_product')){ 
						?>
						if(response.product_serial == 'enable' && response.product_search_type == 'product_code' ){
							
							AS.Http.posthtml({"action" : "GetSerialModal","product_code" : productCode}, "pos/modal/", function (data) {
								$(".show_modal").remove();
								$(".serial_modal_status").html(data);
								$(".show_modal").modal("show");
							});
							}else{
							AddSalesRow(response);
						}
						<?php }else{?>
						AddSalesRow(response);
					<?php } ?>
					AddSalesRowExtraLoad(response.sub_product_id,response);
					
					if(response.product_current_stock == response.alert_quantity){
						swal ( "Oops" ,  $_lang.product_limit+"!" ,  "error" );
					}
					
					}else{
					swal ( "Oops" ,  $_lang.out_of_stock+"!" ,  "error" );
				}
				
				}else if(response.product_status == "multi_product_serial"){
				AS.Http.posthtml({"action" : "GetMultiSerialModal","product_code" : productCode}, "pos/modal/", function (data) {
					$(".serial_modal_status").html(data);
					$(".show_modal").modal("show");
				});
				}else{
				swal ( "Oops" ,  $_lang.no_product_found+"!" ,  "error" );
			}
		});
	}
	var multiBarcodeCheck = true;
	var $input = $("#sales_barcode");
	
	$input.typeahead({
		source: function(query, result)
		{
			delay(function(){
				$.ajax({
					url:"pos/ajax/",
					method:"POST",
					data:{"action" : "GetPurchaseBarcodeTypeSearch","purchase_barcode":query},
					dataType:"json",
					success:function(data)
					{
						result($.map(data, function(item){
							return item;
						}));
					}
				});
			}, 20 );
		},
		autoSelect: true,
		hint: true,
		minLength: 3,﻿
		cache: true,
		debug: false
	});
	
	
	$input.change(function(e) {
		var current = $input.typeahead("getActive");
		if (current) {
			$("#sales_barcode").val(current.product_id);
			$("#sales_barcode").focus();
		}
		e.preventDefault();
	});
	
	$(document).on("keyup","#sales_barcode", function(event){
		if(event.which == 13) {
			var purchaseBarcode = $('#sales_barcode').val();
			if(purchaseBarcode){
				AddSalesProductList(purchaseBarcode)
				$("#sales_barcode").val('');
				$("#sales_barcode").focus();
			}
		}
	});
	
	var delay = (function(){
		var timer = 0;
		return function(callback, ms){
			clearTimeout (timer);
			timer = setTimeout(callback, ms);
		};
	})();
	
	$(document).on("keyup",".customer_name_code_search", function(event){
		var customerCode = $('#customer_code').val();
		// if( this.value.length < 2 ){ $('#customer_code').typeahead('destroy'); return; }
		AS.Http.post({"action" : "GetSearchCustomerByNameId","customer_code" : customerCode}, "pos/ajax/", function (response) {
			if(response.source_data){
				var $input = $("#customer_code");
				$input.typeahead({
					source: response.source_data,
					autoSelect: true
				}
				);
				
				$input.change(function() {
					var current = $input.typeahead("getActive");
					var customer_input_text = $('#customer_code').val();
					if (current) {
						$("#customer_code").val(current.customer_id);
						$('#customer_code').typeahead('destroy');
					}
				});
				
			}
			
		});
	});
	
	function add_new_contact(){
		var new_customer_name = $("#customer_code").val();
		AS.Http.posthtml({"action" : "GetNewContact","type" : "customer","customer_name" : new_customer_name}, "pos/modal/", function (data) {
			$(".outside_modal_status").html(data);
			$("#contact_type").val("customer");
			$(".business").hide();
			$(".show_modal").modal("show");
		});
	}
	
	
	
	function resetPos(){
		var generator = new IDGenerator();
		var SalesId = "SI"+generator.generate();
		$('.show_modal').show().on('shown', function() { 
			$('.show_modal').modal('hide') 
		});
		$(".show_modal").remove();
		document.getElementById("PosSalesForm").reset();
		$("#SalesTable tbody").empty();
		$(".modal-backdrop").remove();
		$(".sales_id_show").html(SalesId);
		$("input[name=sales_id]").val(SalesId);
		<?php  if(app('admin')->checkAddon('multiple_payment_method')){ ?>
			if (typeof GetTransactionReset == 'function') { 
				GetTransactionReset(); 
			}
		<?php } ?>
	}
	
	SalesTableUpdate();
	
	function SalesTableUpdate(){
		$('#SalesTable').DataTable({
			"scrollY": "35vh",
			"scrollX":  true,
			"scrollCollapse": true,
			"paging":   false,
			"ordering": false,
			"searching": false,
			"info":     false
		});
	}
	
	
	$(document).on("click",".new_contact_close", function(){
		var newCustomerId = $("#contact_id").val();
		$("#customer_code").val(newCustomerId);
		$(".modal-backdrop").remove();
		
	});
	
	$(document).on("click",".SalesReseatNow", function(){
		resetPos();
	});
	
	$(document).on("click",".OpenContactModal", function(){
		add_new_contact();
	});
	
	$(document).on("click",".onclick_cart_add", function(){
		var Id = $(this).data("id");
		AddSalesProductList(Id);
		SalesListCal(Id);
		SalesFinalCal();
	});
	
	$(document).on("keyup",".product_search_by_name_code", function(){
		$("#product_list").html(" ");
		var product_name_search =  $('#product_name_search').val() || null;
		var product_brand =  $('#product_brand').val() || null;
		var product_category =  $('#product_category').val() || null;
		GetPosProductListByProductId(product_name_search,product_brand,product_category);
	});
	GetPosProductListByProductId(null,null,null);
	$(document).on("change",".product_search", function(){
		$("#product_list").html(" ");
		var product_name_search =  $('#product_name_search').val() || null;
		var product_brand =  $('#product_brand').val() || null;
		var product_category =  $('#product_category').val() || null;
		GetPosProductListByProductId(product_name_search,product_brand,product_category);
	});
	
	function GetPosProductListByProductId(productCode,brandId,categoryId){
		AS.Http.post({"action" : "GetProductListFilter","product_code" : productCode, "brand_id" : brandId, "category_id" : categoryId}, "pos/ajax/", function (response) {
			if(response.product_status == 'found'){
				var product_lists = response.source_data;
				var datas = '';
				for(var i = 0; i < product_lists.length; i++) {
					var product_list = product_lists[i];
					datas += '<div class="col-sm-4 col-xs-6 col-md-4 col-lg-3 mb-5">'+
					'<a  href="javascript:void(0);" class="thumbnail onclick_cart_add center" data-id="'+product_list.product_id+'">'+
					
					'<img class="img-rounded img-lg" src="'+product_list.product_image+'">'+
					
					'<p class="font-bold text-center">'+product_list.name+'</p>'+
					'</a>'+
					'</div>';
				}
				$("#product_list").html(datas);
				}else{
				$("#product_list").html(" ");
			}
		});
	}
	
	function SalesFinalCal(){
		var total_sales_subtotal = 0;
		$('input[name="product_subtotal[]"]').each(function() {
			total_sales_subtotal +=  parseFloat($(this).val());
		});
		
		var total_sales_vat = 0;
		$('input[name="total_product_vat[]"]').each(function() {
			total_sales_vat +=  parseFloat($(this).val());
		});
		$('.discount_amount_value').html('');
		var sales_sub_total =  $('input[name^="sales_sub_total"]').val() || 0;
		var sales_vat =  $('input[name^="sales_vat"]').val() || 0;
		var sales_discount =  $('input[name^="sales_discount"]').val() || 0;
		var sales_total =  $('input[name^="sales_total"]').val() || 0;
		var sales_need_to_pay =  $('input[name^="sales_need_to_pay"]').val() || 0;
		var sales_receive_amount =  $('input[name^="sales_receive_amount"]').val() || 0;
		<?php if($getpossetting['vat_type'] == "global"){ ?>
			var total_vat = parseFloat(total_sales_subtotal) * parseFloat(<?php echo $getpossetting['vat']; ?>) / 100;
			<?php	}elseif($getpossetting['vat_type'] == "single"){ ?>
			var total_vat = total_sales_vat;
		<?php } ?>
		
		var sales_with_vat = parseFloat(total_sales_subtotal) + parseFloat(total_vat);
		
		var totalDiscountAmount = 0;
		var discountType = $("select[name^='sales_discount_type']").val();
		var discountValue = $("input[name^='salesdiscount_type_amount']").val() || 0;
		if(discountType == 'fixed'){
			totalDiscountAmount = parseFloat(discountValue);
			$("input[name^='salesdiscount_type_amount']").attr({
				"max" : sales_with_vat,
				"min" : 0 
			});
			}else if(discountType == 'percent'){
			totalDiscountAmount = parseFloat(total_sales_subtotal) * parseFloat(discountValue) / 100;
			$('.discount_amount_value').html(' (-'+totalDiscountAmount+')');
			$("input[name^='salesdiscount_type_amount']").attr({
				"max" : 100,
				"min" : 0 
			});
		}
		
		$('input[name^="sales_discount"]').val(totalDiscountAmount);
		
		var sales_with_discount = parseFloat(sales_with_vat) - parseFloat(totalDiscountAmount);
		var sales_with_received_amount = parseFloat(sales_receive_amount) - parseFloat(sales_with_discount) ;
		
		$('input[name^="sales_sub_total"]').val(total_sales_subtotal);
		$('input[name^="sales_vat"]').val(total_vat);
		$('input[name^="sales_total"]').val(sales_with_discount);
		$('input[name^="sales_need_to_pay"]').val(sales_with_discount);
		
		if(sales_with_received_amount < 0){
			$(".pay_change_label").addClass('hidden');
			$(".due_amount_label").removeClass("hidden");
			$('input[name^="due_amount"]').val(sales_with_received_amount);
			$('input[name^="sales_pay_change"]').val(0);
			}else{
			$(".due_amount_label").addClass('hidden');
			$(".pay_change_label").removeClass("hidden");
			$('input[name^="sales_pay_change"]').val(sales_with_received_amount);
			$('input[name^="due_amount"]').val(0);
		}
	}
	
	function SalesListCal(Id){
		var product_price = $("#product_price_"+Id).val() || 0;
		var product_vat = $("#product_vat_value_"+Id).val() || 0;
		var product_vat_type = $("#product_vat_type_"+Id).val();
		var product_quantity = $("#product_quantity_"+Id).val() || 0;
		var product_sales_value = product_price * product_quantity;
		if(product_vat_type == 'percent'){
			var product_total_vat = product_price * product_vat / 100;
			}else {
			var product_total_vat = product_vat;
		}
		var product_vat_value = product_total_vat * product_quantity;
		$("#product_subtotal_"+Id).val(product_sales_value);
		$("#product_vat_"+Id).val(product_total_vat);
		$("#total_product_vat_"+Id).val(product_vat_value);
	}
	
	$(document).on("keyup",".onchange_sales_cal", function(){
		var Id = $(this).data("id");
		SalesListCal(Id);
		SalesFinalCal();
	});
	
	$(document).on("change",".onchange_sales_qty", function(){
		var Id = $(this).data("id");
		AS.Http.post({"action" : "GetSalesProductList","product_code" : Id,"serial_no_check" : 'no_serial'}, "pos/ajax/", function (response) {
			if(response.product_status == 'found'){
				var product_quantity = $("#product_quantity_"+response.sub_product_id).val();
				if(response.product_current_stock > product_quantity || response.product_stock == 'enable'){
					if(response.product_current_stock == response.alert_quantity){
						swal ( "Oops" ,  $_lang.product_limit+"!" ,  "error" );
					}
					}else{
					$("#product_quantity_"+response.sub_product_id).val(response.product_current_stock);
					swal ( "Oops" ,  $_lang.out_of_stock+"!" ,  "error" );
				}
				SalesListCal(response.sub_product_id);
				SalesFinalCal();
			}
		});
	});
	
	$(document).on("keyup",".onchange_sales_final_cal", function(){
		SalesFinalCal();
	});
	
	function AddSalesRowExtraLoad(productCode,response = false){
		SalesListCal(productCode);
		SalesFinalCal();
	}
	
	$(document).on("click",".sales_product_delete", function(){
		$(this).parent('td').parent('tr').remove();
		var Id = $(this).data("id");
		<?php if(isset($this->route['id'])) echo 'rowDelete($(this).data("stock-id"));'; ?>
		SalesListCal(Id);
		SalesFinalCal();
	});
	
	function rowDelete(stock_id){
		AS.Http.post({"action" : "GetDeleteSalesRow","stock_id" : stock_id}, "pos/ajax/", function (data) {
			return true;
		});
	}
	
	$(document).on("click",".product_delete", function(){
		$(this).parent('td').parent('tr').remove();
	});
	
	
	$('.last_receipt').click(function(){
		$(".show_modal").remove();
		AS.Http.posthtml({"action" : "GetCustomerReceiptModal"}, "pos/modal/", function (data) {
			$(".outside_modal_status").html(data);
			$(".show_modal").modal("show");
		});
	});
	
	$(document).on("click",".sales_serial_show", function(){
		$(".show_modal").remove();
		var Id = $(this).data("id");
		var serialized = [];
		$('input[name="product_serial_id['+Id+'][]"]').each(function() {
			serialized.push($(this).val());
		});
		
		AS.Http.posthtml({"action" : "GetPurchaseSerialShowModal","serials" : serialized,"product_code" : Id}, "pos/modal/", function (data) {
			$(".modal_status").html(data);
			$(".show_modal").modal("show");
		});
		
	});
	
	function AddSalesRow(response){
		$('#empty_cart').remove();
		var generator = new IDGenerator();
		var PurchaseTable = document.getElementById('SalesTable').getElementsByTagName('tbody')[0];
		if (PurchaseTable.rows[response.sub_product_id]){
			var product_quantity = $("#product_quantity_"+response.sub_product_id).val() || 0;
			var new_quantiry =  parseFloat(product_quantity) + parseFloat('1');
			<?php 
				if(app('admin')->checkAddon('serial_product')){ 
				?>
				if(response.product_serial == 'enable'){
					var checkSerial = true;
					$('input[name="product_serial_id['+response.sub_product_id+'][]"]').each(function() {
						if($(this).val() == response.product_serial_no){
							swal({
								title: $_lang.serial_no_already_exits, 
								text: $_lang.please_check_serial_no_again, 
								type: "error",
								confirmButtonColor: "#1ab394", 
								confirmButtonText: $_lang.ok,
							});
							checkSerial = false;
							return true;
						}
					});
					if(checkSerial){
						var td = document.getElementById('quantity_row_'+response.sub_product_id);
						var ele = document.createElement('input');
						ele.setAttribute('type', 'hidden');
						ele.setAttribute('value', response.product_serial_no);
						ele.setAttribute('id', response.sub_product_id+response.product_serial_no);
						ele.setAttribute('name', 'product_serial_id['+response.sub_product_id+'][]');
						td.appendChild(ele);
						td.setAttribute('id', 'quantity_row_'+response.sub_product_id);
						$("#product_quantity_"+response.sub_product_id).val(new_quantiry);
					}
					}else{
					$("#product_quantity_"+response.sub_product_id).val(new_quantiry);
				}
				<?php }else{ ?>
				$("#product_quantity_"+response.sub_product_id).val(new_quantiry);
			<?php } ?>
			}else{
			var rowCnt = PurchaseTable.rows.length; 
			var tr = PurchaseTable.insertRow(rowCnt); 
			var stock_id = "ST"+generator.generate(); 
			var uid = new Date().getTime();
			tr.setAttribute('id', response.sub_product_id);
			for (var c = 0; c <= 4; c++) {
				var td = document.createElement('td');
				td = tr.insertCell(c);
				if(c==0){
					var productName = response.product_name;
					if(response.variation_name){
						productName += ' ['+response.variation_name+']';
					}
					if(response.sub_product_id){
						productName += ' ['+response.sub_product_id+']';
					}
					var ele = document.createElement('input');
					ele.setAttribute('type', 'hidden');
					ele.setAttribute('value', response.sub_product_id);
					ele.setAttribute('name', 'sub_product_id[]');
					td.append(productName);
					td.appendChild(ele);
					
					var ele = document.createElement('input');
					ele.setAttribute('type', 'hidden');
					ele.setAttribute('value', response.purchase_price);
					ele.setAttribute('name', 'sales_purchase_price[]');
					// td.append(productName);
					td.appendChild(ele);
					
					var ele = document.createElement('input');
					ele.setAttribute('type', 'hidden');
					ele.setAttribute('value', response.product_id);
					ele.setAttribute('name', 'product_id[]');
					td.appendChild(ele);
					
					var ele = document.createElement('input');
					ele.setAttribute('type', 'hidden');
					ele.setAttribute('value', stock_id);
					ele.setAttribute('name', 'product_stock_id[]');
					td.appendChild(ele);
					
					var ele = document.createElement('input');
					ele.setAttribute('type', 'hidden');
					ele.setAttribute('name', 'product_vat[]');
					ele.setAttribute('data-id', response.sub_product_id);
					ele.setAttribute('id', 'product_vat_'+response.sub_product_id);
					td.appendChild(ele);
					
					var ele = document.createElement('input');
					ele.setAttribute('type', 'hidden');
					ele.setAttribute('value', response.product_vat);
					ele.setAttribute('name', 'product_vat_value[]');
					ele.setAttribute('data-id', response.sub_product_id);
					ele.setAttribute('id', 'product_vat_value_'+response.sub_product_id);
					td.appendChild(ele);
					
					var ele = document.createElement('input');
					ele.setAttribute('type', 'hidden');
					ele.setAttribute('value', response.product_vat_type);
					ele.setAttribute('name', 'product_vat_type[]');
					ele.setAttribute('data-id', response.sub_product_id);
					ele.setAttribute('id', 'product_vat_type_'+response.sub_product_id);
					td.appendChild(ele);
					
					var ele = document.createElement('input');
					ele.setAttribute('type', 'hidden');
					ele.setAttribute('data-id', response.sub_product_id);
					ele.setAttribute('id', 'total_product_vat_'+response.sub_product_id);
					ele.setAttribute('name', 'total_product_vat[]');
					td.appendChild(ele);
					
					}else if(c==1){
					
					var ele = document.createElement('input');
					ele.setAttribute('type', 'text');
					ele.setAttribute('value', response.sell_price);
					ele.setAttribute('name', 'product_price[]');
					ele.setAttribute('data-id', response.sub_product_id);
					ele.setAttribute('id', 'product_price_'+response.sub_product_id);
					ele.setAttribute('class', 'form-control input-sm onchange_sales_cal');
					ele.setAttribute('placeholder', $_lang.product_price);
					
					td.appendChild(ele);
					}else if(c==2){
					<?php
						if(app('admin')->checkAddon('serial_product')){ 
						?>
						if(response.product_serial == 'enable'){
							var ele = document.createElement('input');
							ele.setAttribute('type', 'text');
							ele.setAttribute('value', "1");
							ele.setAttribute('name', 'product_quantity[]');
							ele.setAttribute('data-id', response.sub_product_id);
							ele.setAttribute('id', 'product_quantity_'+response.sub_product_id);
							ele.setAttribute('readonly', 'readonly');
							ele.setAttribute('class', 'form-control input-sm');
							ele.setAttribute('placeholder', $_lang.product_quantity);
							var dve = document.createElement('div');
							dve.setAttribute('class', 'input-group m-b');
							
							var spe = document.createElement('span');
							spe.setAttribute('class', 'input-group-addon sales_serial_show');
							spe.setAttribute('data-id', response.sub_product_id);
							
							var ic = document.createElement('i');
							ic.setAttribute('class', 'fa fa-eye');
							
							dve.appendChild(ele);
							spe.appendChild(ic);
							dve.appendChild(spe);
							td.appendChild(dve);
							var ele = document.createElement('input');
							ele.setAttribute('type', 'hidden');
							ele.setAttribute('value', response.product_serial_no);
							ele.setAttribute('id', response.sub_product_id+response.product_serial_no);
							ele.setAttribute('name', 'product_serial_id['+response.sub_product_id+'][]');
							td.appendChild(ele);
							td.setAttribute('id', 'quantity_row_'+response.sub_product_id);
							}else{
							var ele = document.createElement('input');
							ele.setAttribute('type', 'number');
							ele.setAttribute('value', "1");
							ele.setAttribute('name', 'product_quantity[]');
							ele.setAttribute('data-id', response.sub_product_id);
							ele.setAttribute('id', 'product_quantity_'+response.sub_product_id);
							ele.setAttribute('class', 'form-control input-sm onchange_sales_cal onchange_sales_qty');
							ele.setAttribute('placeholder', $_lang.product_quantity);
							var dve = document.createElement('div');
							dve.setAttribute('class', 'input-group m-b');
							var spe = document.createElement('span');
							spe.setAttribute('class', 'input-group-addon');
							spe.append(response.unit_name);
							dve.appendChild(ele);
							dve.appendChild(spe);
							td.appendChild(dve);
						}
						<?php }else{ ?>
						var ele = document.createElement('input');
						ele.setAttribute('type', 'number');
						ele.setAttribute('value', "1");
						ele.setAttribute('name', 'product_quantity[]');
						ele.setAttribute('data-id', response.sub_product_id);
						ele.setAttribute('id', 'product_quantity_'+response.sub_product_id);
						ele.setAttribute('class', 'form-control input-sm onchange_sales_cal onchange_sales_qty');
						ele.setAttribute('placeholder', $_lang.product_quantity);
						var dve = document.createElement('div');
						dve.setAttribute('class', 'input-group m-b');
						var spe = document.createElement('span');
						spe.setAttribute('class', 'input-group-addon');
						spe.append(response.unit_name);
						dve.appendChild(ele);
						dve.appendChild(spe);
						td.appendChild(dve);
					<?php } ?>
					}else if(c==3){
					
					var ele = document.createElement('input');
					ele.setAttribute('type', 'text');
					ele.setAttribute('readonly', '');
					ele.setAttribute('value', "1");
					ele.setAttribute('name', 'product_subtotal[]');
					ele.setAttribute('data-id', response.sub_product_id);
					ele.setAttribute('id', 'product_subtotal_'+response.sub_product_id);
					ele.setAttribute('class', 'form-control input-sm onchange_sales_cal onchange_sales_subtotal');
					ele.setAttribute('placeholder', $_lang.product_subtotal);
					td.appendChild(ele);
					
					}else if(c==4){
					
					var ele = document.createElement('a');
					ele.setAttribute('href', 'javascript:void(0);');
					ele.setAttribute('data-stock-id', stock_id);
					ele.setAttribute('data-id', response.sub_product_id);
					ele.setAttribute('class', 'btn btn-danger btn-xs sales_product_delete');
					var ele_icon = document.createElement('i');
					ele_icon.setAttribute('class', 'fa fa-trash');
					ele.appendChild(ele_icon);
					td.appendChild(ele);
					
				}
			}
		}
	}
</script>

[/footer]
<?php include dirname(__FILE__) .'/../../modules/pos/include/footer.php'; ?>																																																																																																																																																																																																																																						