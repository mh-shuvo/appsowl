<?php defined('_AZ') or die('Restricted access');
include dirname(__FILE__) .'/../../modules/pos/include/header.php';
include dirname(__FILE__) .'/../../modules/pos/include/side_bar.php';
include dirname(__FILE__) .'/../../modules/pos/include/navbar.php';

$getpossetting = app('pos')->GetPosSetting();

if(isset($this->route['id'])){
	$GetSalesProduct = app('admin')->getwhereid("pos_sales","sales_id",$this->route['id']);
}
$salesId = 'SI'.gettoken(8);
	
?>
[header]
<?php 
	getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false);
	getCss('assets/system/css/plugins/iCheck/custom.css');
	getCss('assets/system/css/plugins/select2/select2.min.css');
	getCss('assets/system/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css');
?>
<style type="text/css">
	th {
	text-align: center;
	}
</style>
[/header]
	
	<div class="row add_sales">
		<div class="col-sm-12">
			<h3><?php echo trans('add_sales'); ?></h3>
		</div>
		<form id="PosInvoiceSalesForm">
			<input type="hidden" name="sales_id" value="<?php if(isset($this->route['id'])){ echo $GetSalesProduct['sales_id']; }else{ echo $salesId; } ?>">
			<div class="col-sm-12">
				<div class="ibox">
					<div class="ibox-content">
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<label class="control-label"><?php echo trans('customer_name'); ?>:*</label>
									<div class="input-group">
										<span class="input-group-addon">
											<i class="fa fa-user"></i>
										</span>
										<input type="text" class="form-control customer_name_code_search" autocomplete="off" name="customer_code" id="customer_code" placeholder="<?php echo trans('enter_customer_name_customer_id');?>" value="<?php if(isset($this->route['id'])) echo $GetSalesProduct['customer_id']; ?>">
										<span class="input-group-btn">
											<a href="javascript:void(0);" class="btn btn-default add_new_contact" type="customer" title="Add Customer" ><i class="fa fa-plus-circle text-primary fa-lg"></i></a>
										</span>
									</div>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group" <?php if(!isset($this->route['id'])) echo 'id="data_1"'; ?>>
									<label class="control-label"><?php echo trans('sale_date'); ?>:*</label>
									<div class="input-group date">
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
										<input id="sale_date" type="text" class="form-control" name="sales_date" value="<?php if(isset($this->route['id'])){ echo $GetSalesProduct['created_at']; }else{ echo date('Y-m-d'); } ?>" <?php if(isset($this->route['id'])) echo "readonly"; ?>>
									</div>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label class="control-label"><?php echo trans('status'); ?>:*</label>
									<select class="form-control" name="sales_status">
										<option value="complete" <?php if(isset($this->route['id'])){ if($GetSalesProduct['sales_status'] == "complete") echo "selected"; }  ?>><?php echo trans('complete'); ?></option>
										<option value="draft" <?php if(isset($this->route['id'])){ if($GetSalesProduct['sales_status'] == "draft") echo "selected"; }  ?>><?php echo trans('draft'); ?></option>
										<option value="ordered" <?php if(isset($this->route['id'])){ if($GetSalesProduct['sales_status'] == "ordered") echo "selected"; }  ?>><?php echo trans('ordered'); ?></option>
										<option value="quote" <?php if(isset($this->route['id'])){ if($GetSalesProduct['sales_status'] == "quote") echo "selected"; }  ?>><?php echo trans('quotation'); ?></option>
										<option value="cancel" <?php if(isset($this->route['id'])){ if($GetSalesProduct['sales_status'] == "cancel") echo "selected"; }  ?>><?php echo trans('cancel'); ?></option>
									</select>
								</div>
							</div>
						</div>
						
					</div>
				</div>
			</div>
			<div class="col-sm-12">
				<div class="ibox">
					<div class="ibox-content">
						<div class="row">
							<div class="col-sm-8 col-sm-offset-2">
								<div class="form-group">
									<div class="input-group">
										<span class="input-group-addon">
											<i class="fa fa-barcode"></i>
										</span>
										<input type="text" class="form-control barcode_type_search" placeholder="<?php echo trans('enter_product_name_/_squ_/_scan_barcode'); ?>" id="sales_barcode" name="sales_barcode">
									</div>
								</div>
							</div>
							
						</div>
						<div class="row">
							<div class="col-sm-12 table-responsive">
								<table class="table table-striped table-sm" id="SalesTable" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th><?php echo trans('product_name'); ?></th>
											<th><?php echo trans('unit_cost'); ?></th>
											<th><?php echo trans('quantity'); ?></th>
											<th><?php echo trans('subtotal'); ?></th>
											<th><i class="fa fa-trash"></i></th>
										</tr>
									</thead>
									<tbody>
										<?php if(isset($this->route['id'])){ 
											
											$getProductVariations = app('admin')->getwhereand("pos_stock","sales_id",$GetSalesProduct['sales_id'],"stock_category","sales"); 
											foreach($getProductVariations as $getProductVariation){
												$getVariationDetails = app('admin')->getwhereid("pos_variations","sub_product_id",$getProductVariation['sub_product_id']); 
												$getProductDetails = app('admin')->getwhereid("pos_product","product_id",$getProductVariation['product_id']); 
											?>
											<tr id="<?php echo $getProductVariation['sub_product_id']; ?>">
												<td>
													<?php echo $getProductDetails['product_name']; ?> [<?php echo $getVariationDetails['variation_name']; ?>] [<?php echo $getVariationDetails['sub_product_id']; ?>]
													<input type="hidden" value="<?php echo $getProductVariation['sub_product_id']; ?>" name="sub_product_id[]"><input type="hidden" value="<?php echo $getProductVariation['sub_product_id']; ?>" name="product_id[]">
													<input type="hidden" value="<?php echo $getProductVariation['stock_id']; ?>" name="product_stock_id[]">
													<input type="hidden" value="<?php echo $getProductVariation['product_vat']; ?>" name="product_vat[]" data-id="<?php echo $getProductVariation['sub_product_id']; ?>" id="product_vat_<?php echo $getProductVariation['sub_product_id']; ?>">
													<input type="hidden" data-id="<?php echo $getProductVariation['sub_product_id']; ?>" id="total_product_vat_<?php echo $getProductVariation['sub_product_id']; ?>" name="total_product_vat[]" value="<?php echo $getProductVariation['product_vat_total']; ?>">
												</td>
												<td>
													<input type="text" value="<?php echo $getProductVariation['product_price']; ?>" name="product_price[]" data-id="<?php echo $getProductVariation['sub_product_id']; ?>" id="product_price_<?php echo $getProductVariation['sub_product_id']; ?>" class="form-control input-sm onchange_sales_cal" placeholder="Product Price" aria-invalid="false">
												</td>
												<td>
													<input type="number" value="<?php echo $getProductVariation['product_quantity']; ?>" name="product_quantity[]" data-id="<?php echo $getProductVariation['sub_product_id']; ?>" id="product_quantity_<?php echo $getProductVariation['sub_product_id']; ?>" class="form-control input-sm onchange_sales_cal onchange_sales_qty" placeholder="Product Quantity">
												</td>
												<td>
													<input type="text" value="<?php echo $getProductVariation['product_subtotal']; ?>" name="product_subtotal[]" data-id="<?php echo $getProductVariation['sub_product_id']; ?>" id="product_subtotal_<?php echo $getProductVariation['sub_product_id']; ?>" class="form-control input-sm onchange_sales_cal onchange_sales_subtotal" placeholder="undefined">
												</td>
												<td>
													<a href="javascript:void(0);" class="btn btn-danger btn-xs sales_product_delete"><i class="fa fa-trash"></i></a>
												</td>
											</tr>
											
										<?php } } ?>
										<tr id="empty_cart"><td></td><td></td><td></td><td></td><td></td></tr>
										
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-12">
				<div class="ibox">
					<div class="ibox-content">
						<div class="row">
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label"><?php echo trans('subtotal'); ?></label>
									<input type="text" class="form-control onchange_sales_final_cal" name="sales_sub_total" placeholder="<?php echo trans('subtotal'); ?>" value="<?php if(isset($this->route['id'])) echo $GetSalesProduct['sales_subtotal']; ?>">
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label"><?php echo trans('total_vat'); ?></label>
									<input type="text" class="form-control" name="sales_vat" placeholder="<?php echo trans('total_vat'); ?>" value="<?php if(isset($this->route['id'])) echo $GetSalesProduct['sales_vat']; ?>" readonly>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label"><?php echo trans('discount_amount'); ?></label>
									<input type="text" class="form-control onchange_sales_final_cal" name="sales_discount" value="<?php if(isset($this->route['id'])) echo $GetSalesProduct['sales_discount']; ?>" placeholder="<?php echo trans('discount_amount'); ?>">
								</div>
							</div> 
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label"><?php echo trans('sales_total_amount'); ?></label>
									<input type="text" class="form-control" name="sales_total_amount" placeholder="<?php echo trans('sales_total_amount'); ?>" value="<?php if(isset($this->route['id'])) echo $GetSalesProduct['sales_total']; ?>" readonly>
								</div>
							</div>
						</div>
						<div class="checkbox checkbox-primary">
							<input id="checkbox2" type="checkbox" class="shipping">
							<label for="checkbox2"><?php echo trans('enable_shipping'); ?></label>
						</div>
						<div class="row" id="shipping_form">
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label"><?php echo trans('shipping_details'); ?></label>
									<textarea class="form-control" rows="2" name="shipping_details"><?php if(isset($this->route['id'])) echo $GetSalesProduct['shipping_details']; ?></textarea>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label">+<?php echo trans('additional_shipping_charge'); ?></label>
									<input type="text" value="0" class="form-control onchange_sales_final_cal" name="additional_shipping_charge" placeholder="<?php echo trans('additional_shipping_charge'); ?>" value="<?php if(isset($this->route['id'])) echo $GetSalesProduct['shipping_charge']; ?>">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="from-group">
									<label class="control-label"><?php echo trans('note'); ?></label>
									<textarea class="form-control" name="sales_note"><?php if(isset($this->route['id'])) echo $GetSalesProduct['sales_note']; ?></textarea>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<h1 class="pull-left"><strong><?php echo trans('total_payable_amount'); ?>: <span class="payable_amount"><?php if(isset($this->route['id'])){ $totalPaidAmount = app('pos')->GetSalesByCustomerOrder(false,$GetSalesProduct['sales_id']); echo $totalPaidAmount['total_due'];} ?></span></strong></h1>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-12">
				<div class="row">
					<?php if(isset($this->route['id'])){ ?>
						<div class="col-sm-6">
							<div class="ibox">
								<div class="ibox-title">
									<h3><?php echo trans('payment_info'); ?></h3>
								</div>
								<div class="ibox-content">
									<table class="table table-bordered table-striped">
										<thead>
											<th><?php echo trans('date'); ?></th>
											<th><?php echo trans('amount'); ?></th>
											<th><?php echo trans('added_by'); ?></th>
											<th><?php echo trans('payment_method'); ?></th>
											<th><?php echo trans('transaction_id'); ?></th>
											<th><?php echo trans('payment_note'); ?></th>
										</thead>
										<tbody class="text-center">
											<?php $getSalesPayments = app('admin')->getwhereand("pos_transactions","sales_id",$GetSalesProduct['sales_id'],"transaction_type","sales"); 
												$totalAmount = 0;
												foreach($getSalesPayments as $getSalesPayment){ ?>
												<tr>
													<td><?php echo getdatetime($getSalesPayment['created_at'], 3); ?></td>
													<td><?php echo$getSalesPayment['transaction_amount']; $totalAmount += $getSalesPayment['transaction_amount']; ?></td>
													<td><?php $PaymentUserDetails = app('admin')->getuserdetails($getSalesPayment['user_id']); echo $PaymentUserDetails['first_name'].' '.$PaymentUserDetails['last_name']; ?></td>
													<td><?php echo $getSalesPayment['payment_method_value']; ?></td>
													<td><?php echo $getSalesPayment['transaction_no']; ?></td>
													<td><?php echo $getSalesPayment['transaction_note']; ?></td>
												</tr>
											<?php } ?>
											<tr>
												<td colspan="2"></td>
												<td colspan="2" style="background:gray; color:white;" id="total_due_amount">Total Due : <?php echo $GetSalesProduct['sales_total'] - $totalAmount; ?></td>
												<td colspan="2" style="background:gray; color:white;">Total Payment : <?php echo $totalAmount; ?></td>
												<input type="hidden" value="<?php echo $totalAmount; ?>" id="total_payment_amount">
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<?php }else{ ?>
						<div class="col-sm-6 ">
						</div>
						<?php 
						} 
						if(isset($this->route['id']) && $GetSalesProduct['sales_payment_status'] != 'paid'){
						?>
						<div class="col-sm-6">
							<div class="ibox">
								<div class="ibox-title">
									<h3><?php echo trans('add_payment'); ?></h3>
								</div>
								<div class="ibox-content">
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group">
												<label class="control-label"><?php echo trans('amount'); ?>:*</label>
												<div class="input-group">
													<span class="input-group-addon"> <i class="fa fa-credit-card"></i> </span>
													<input type="text" class="form-control" name="payment_amount" placeholder="<?php echo trans('amount'); ?>">
												</div>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="form-group">
												<label class="control-label"><?php echo trans('payment_method'); ?>:*</label>
												<div class="input-group">
													<span class="input-group-addon"> <i class="fa fa-credit-card"></i> </span>
													<select class="form-control payment_method" name="payment_method">
														<?php $paymentMethods = app('admin')->getall("pos_payment_method"); foreach($paymentMethods as $paymentMethod){ ?>
															<option value="<?php echo $paymentMethod['payment_method_value']; ?>"><?php echo $paymentMethod['payment_method_name']; ?></option>
														<?php } ?>
													</select>
												</div>
											</div>
										</div>
									</div>
									
									<div class="row custom_transaction_id hide">
										<div class="col-sm-12">
											<div class="form-group">
												<label class="control-label"><?php echo trans('transaction_id'); ?></label>
												<input type="text" class="form-control" placeholder="<?php echo trans('transaction_id'); ?>" name="payment_transaction_id">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
											<div class="form-group">
												<label class="control-label"><?php echo trans('note'); ?></label>
												<textarea class="form-control" name="payment_note"></textarea>
											</div>
										</div>
										<div class="col-sm-12">
											<div class="form-group">
												<button type="submit" class="btn btn-success pull-right" id="submit_sales_form"><?php echo trans('save'); ?></button>
											</div>
										</div>
									</div>	
								</div>
							</div>
						</div>
						<?php }elseif(!isset($this->route['id'])){ ?>
						<div class="col-sm-6 ">
							<div class="ibox">
								<div class="ibox-title">
									<h3><?php echo trans('add_payment'); ?></h3>
								</div>
								<div class="ibox-content">
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group">
												<label class="control-label"><?php echo trans('amount'); ?>:*</label>
												<div class="input-group">
													<span class="input-group-addon"> <i class="fa fa-credit-card"></i> </span>
													<input type="text" class="form-control onchange_purchase_final_cal" name="payment_amount" placeholder="<?php echo trans('amount'); ?>">
												</div>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="form-group">
												<label class="control-label"><?php echo trans('payment_method'); ?>:*</label>
												<div class="input-group">
													<span class="input-group-addon"> <i class="fa fa-credit-card"></i> </span>
													<select class="form-control payment_method" name="payment_method">
														<?php $paymentMethods = app('admin')->getall("pos_payment_method"); foreach($paymentMethods as $paymentMethod){ ?>
															<option value="<?php echo $paymentMethod['payment_method_value']; ?>"><?php echo $paymentMethod['payment_method_name']; ?></option>
														<?php } ?>
													</select>
												</div>
											</div>
										</div>
									</div>
									
									<div class="row custom_transaction_id hide">
										<div class="col-sm-12">
											<div class="form-group">
												<label class="control-label"><?php echo trans('transaction_id'); ?></label>
												<input type="text" class="form-control" placeholder="<?php echo trans('transaction_id'); ?>" name="payment_transaction_id">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
											<div class="form-group">
												<label class="control-label"><?php echo trans('note'); ?></label>
												<textarea class="form-control" name="payment_note"></textarea>
											</div>
										</div>
										<div class="col-sm-12">
											<div class="form-group">
												<button type="submit" class="btn btn-success pull-right" id="submit_sales_form"><?php echo trans('save'); ?></button>
											</div>
										</div>
									</div>	
								</div>
							</div>
						</div>
						<?php }else{ ?>
						<div class="col-sm-6">
							<div class="ibox">
								<div class="ibox-content">
									<div class="row">
										<div class="col-sm-12">
											<div class="form-group">
												<button type="submit" class="btn btn-success pull-right" id="submit_sales_form"><?php if(isset($this->route['id'])){ echo trans('update'); }else{ echo trans('save'); } ?></button>
											</div>
										</div>
									</div>	
								</div>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		</form>
	</div>
	<div class="ModalForm">
		<div class="modal_status"></div>
		<div class="serial_modal_status"></div>
	</div>
	<div class='invoice_receipt_view hidden'></div>
	[footer]
	<?php 
		getJs('assets/system/js/plugins/select2/select2.full.min.js',false,false); 
		getJs('assets/system/js/plugins/dataTables/datatables.min.js',false,false); 
		getJs('assets/system/js/plugins/typehead/bootstrap3-typeahead.min.js',false); 
	?>
	<script>
		
		$('.add_sales form').validate({
			rules: {
				sales_sub_total: {
					required: true
				}
			},	
			submitHandler: function(form) {
				AS.Http.PostSubmit(form, {"action" : "GetInvoiceAdvanceData"}, "pos/ajax/", function (response) {
					AS.Util.displaySuccessMessage($(form), response.message);

					location.href='pos/pdf/'+response.sales_id;
					// jQuery.ajax({
						// url: "pos/ajax/",
						// data: {
							// action: "GetInvoice",
							// id	  : response.sales_id
						// },
						// type: "POST",
						// success:function(data){
							
							// $(".invoice_receipt_view").html(data);		
							// $.print("#sales_invoice");
							
						// },
						// error:function (){}
					// });
					resetPos()
				});
			}
		});
		
		function resetPos(){
			$('.show_modal').show().on('shown', function() { 
				$('.show_modal').modal('hide') 
			});
			$(".show_modal").remove();
			document.getElementById("PosInvoiceSalesForm").reset();
			$("#SalesTable tbody").empty();
			$(".modal-backdrop").remove();
			$('.payable_amount').html('0');
		}
		
		
		function AddSalesRow(response){
			$('#empty_cart').remove();
			var generator = new IDGenerator();
			var PurchaseTable = document.getElementById('SalesTable').getElementsByTagName('tbody')[0];
			if (PurchaseTable.rows[response.sub_product_id]){
				var product_quantity = $("#product_quantity_"+response.sub_product_id).val() || 0;
				var new_quantiry =  parseInt(product_quantity) + parseInt('1');
				if(response.product_serial == 'enable'){
					var checkSerial = true;
					$('input[name="product_serial_id['+response.sub_product_id+'][]"]').each(function() {
						if($(this).val() == response.product_serial_no){
							swal({
								title: 'serial_no_already_exits', 
								text: 'please_check_serial_no_again', 
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
						var ele = document.createElement('input');
						ele.setAttribute('type', 'hidden');
						ele.setAttribute('value', response.sub_product_id);
						ele.setAttribute('name', 'sub_product_id[]');
						td.append(response.product_name+' ['+response.variation_name+'] ['+response.sub_product_id+']');
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
						ele.setAttribute('value', response.product_vat);
						ele.setAttribute('name', 'product_vat[]');
						ele.setAttribute('data-id', response.sub_product_id);
						ele.setAttribute('id', 'product_vat_'+response.sub_product_id);
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
						ele.setAttribute('class', 'btn btn-danger btn-xs sales_product_delete');
						var ele_icon = document.createElement('i');
						ele_icon.setAttribute('class', 'fa fa-trash');
						ele.appendChild(ele_icon);
						td.appendChild(ele);
					}
				}
			}
		}
		
		$(document).on("click",".sales_serial_show", function(){
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
		
		function AddSalesRowExtraLoad(productCode,response = false){
			$(".sales_product_delete").click(function(){
				$(this).parent('td').parent('tr').remove();
				var Id = $(this).data("id");
				SalesListCal(Id);
				SalesFinalCal();
			});
			SalesListCal(productCode);
			SalesFinalCal();
		}
		
		$(document).on("keyup",".onchange_sales_final_cal", function(){
			SalesFinalCal();
		});
		
		function SalesFinalCal(){
			var total_sales_subtotal = 0;
			$('input[name="product_subtotal[]"]').each(function() {
				total_sales_subtotal +=  parseInt($(this).val());
			});
			
			var total_sales_vat = 0;
			$('input[name="total_product_vat[]"]').each(function() {
				total_sales_vat +=  parseInt($(this).val());
			});
			
			var sales_sub_total =  $('input[name^="sales_sub_total"]').val() || 0;
			var sales_vat =  $('input[name^="sales_vat"]').val() || 0;
			var sales_discount =  $('input[name^="sales_discount"]').val() || 0;
			var sales_total =  $('input[name^="sales_total_amount"]').val() || 0;
			var additional_shipping_charge =  $('input[name^="additional_shipping_charge"]').val() || 0;
			<?php if($getpossetting['vat_type'] == "global"){ ?>
				var total_vat = parseInt(total_sales_vat) * parseInt(<?php echo $getpossetting['vat']; ?>) / 100;
				<?php	}elseif($getpossetting['vat_type'] == "single"){ ?>
				var total_vat = total_sales_vat;
			<?php } ?>
			var sales_with_vat = parseInt(total_sales_subtotal) + parseInt(total_vat);
			var sales_with_discount = parseInt(sales_with_vat) - parseInt(sales_discount);
			var sales_with_received_amount = parseInt(sales_with_discount) + parseInt(additional_shipping_charge);
			
			$('input[name^="sales_sub_total"]').val(total_sales_subtotal);
			$('input[name^="sales_vat"]').val(total_vat);
			$('input[name^="sales_total_amount"]').val(sales_with_received_amount);
			$('.payable_amount').html(sales_with_received_amount);
			
		}
		
		$(document).on("keyup",".onchange_sales_cal", function(){
			var Id = $(this).data("id");
			SalesListCal(Id);
			SalesFinalCal();
		});
		
		
		$(document).on("change",".onchange_sales_qty", function(){
			var Id = $(this).data("id");
			var product_quantity = $("#product_quantity_"+Id).val() || 0;
			AS.Http.post({"action" : "GetSalesProductList","product_code" : Id, productQuantity : product_quantity}, "pos/ajax/", function (response) {
				if(response.product_status == 'found'){
					SalesListCal(Id);
					SalesFinalCal();
					if(response.product_current_stock == response.alert_quantity){
						
						swal ( "Oops" ,  "Product Limit!" ,  "error" );
						
					}
					}else if(response.product_status == "not_availiable_stock"){
					$("#product_quantity_"+Id).val(response.product_current_stock);
					SalesListCal(Id);
					SalesFinalCal();
					swal ( "Oops" ,  "Not Available Product!" ,  "error" );
				}
			});
		});
		
		$(document).on("keyup",".onchange_sales_final_cal", function(){
			SalesFinalCal();
		});
		
		function SalesListCal(Id){
			var product_price = $("#product_price_"+Id).val() || 0;
			var product_vat = $("#product_vat_"+Id).val() || 0;
			var product_quantity = $("#product_quantity_"+Id).val() || 0;
			var product_sales_value = product_price * product_quantity;
			var product_vat_value = product_vat * product_quantity;
			$("#product_subtotal_"+Id).val(product_sales_value);
			$("#total_product_vat_"+Id).val(product_vat_value);
		}	
		
		$(document).on("click",".add_new_contact", function(){
			var type = $(this).attr('type');
			$(".show_modal").remove();
			AS.Http.posthtml({"action" : "GetNewContact","type" : type}, "pos/modal/", function (data) {
				$(".modal_status").html(data);
				$("#contact_type").val(type);
				$(".business").hide();
				$(".show_modal").modal("show");
			});
		});
		
		$(".select2_demo_3").select2({
			placeholder: "Select a state",
			allowClear: true
		});
		
		$(document).ready(function() {
			$("#shipping_form").hide();
			$("#transaction_id").hide();
			$('#data_1 .input-group.date').datepicker({
				todayBtn: "linked",
				format: "yyyy-mm-dd",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				endDate: new Date(),
				autoclose: true
			});
		});
		
		$('.shipping').change(function(ev) {
			if ( this.checked ) $("#shipping_form").show();
			else $("#shipping_form").hide();
		});
		
		$(document).on("change",".payment_method", function(){
			var type= $(".payment_method").val();
			$(".custom_transaction_id").addClass('hide');
			if(type=='cash'||type=='other'||type=='custom'){
				$(".custom_transaction_id").addClass('hide');
				}else{
				$(".custom_transaction_id").removeClass('hide');
			}
		});
		
		$(document).on("keyup",".customer_name_code_search", function(event){
		var customerCode = $('#customer_code').val();
		if( this.value.length < 2 ){ $('#customer_code').typeahead('destroy'); return; }
		AS.Http.post({"action" : "GetSearchCustomerByNameId","customer_code" : customerCode}, "pos/ajax/", function (response) {
			if(response.source_data){
				var $input = $("#customer_code");
				$input.typeahead({
					source: response.source_data,
					autoSelect: true
				});
				
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
		$(document).on("keyup",".barcode_type_search", function(event){
			var purchaseBarcode = $('#sales_barcode').val();
			if( this.value.length < 2 ){ $('#sales_barcode').typeahead('destroy'); return; }
			AS.Http.post({"action" : "GetPurchaseBarcodeTypeSearch","purchase_barcode" : purchaseBarcode}, "pos/ajax/", function (response) {
				if(response.source_data){
					var $input = $("#sales_barcode");
					$input.typeahead({
						source: response.source_data,
						autoSelect: true
					});
					
					$input.change(function() {
						var current = $input.typeahead("getActive");
						if (current) {
							AddSalesProductList(current.product_id);
							$("#sales_barcode").val('');
							$("#sales_barcode").focus();
							$('#sales_barcode').typeahead('destroy');
						} 
					});
				}
			});
		});
		
		$(document).on("keypress",".barcode_type_search", function(e){
			if ( e.which == 13 ){
				var purchaseBarcode = $('#sales_barcode').val();
				AddSalesProductList(purchaseBarcode)
				$("#sales_barcode").val('');
				$("#sales_barcode").focus();
				$('#sales_barcode').typeahead('destroy');
				e.preventDefault();
			} 
		});
	</script>
	[/footer]
	<?php include dirname(__FILE__) .'/../../modules/pos/include/footer.php'; ?>																																							