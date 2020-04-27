<?php defined('_AZ') or die('Restricted access');
	
	include dirname(__FILE__) .'/../../modules/pos/include/header.php';
	include dirname(__FILE__) .'/../../modules/pos/include/side_bar.php';
	include dirname(__FILE__) .'/../../modules/pos/include/navbar.php';
	app('admin')->checkAddon('add_purchase',true);
	app('pos')->checkPermission('purchase_add_purchase','view') or redirect("pos/access-denied");
	
	if(isset($this->route['id'])){
		$GetPurchaseProduct = app('admin')->getwhereid("pos_purchase","purchase_id",$this->route['id']);
	}
	
	$productId = 'PUR'.gettoken(8);
?>
[header]
<style type="text/css">
	.form-control-file{height: 33px;}
	th{text-align: center;}
	.bg-green{ 
	background: #1ab394;
	color: white;
	}
	
	span.textbox {
	border: 1px inset #ccc;
	}
	
	span.textbox input {
	border: 0;
	}
	
	.btn-space {
    margin-right: 5px;
	}
	
</style>
[/header]
<div class="row AddPurchaseAdvance">
	<div class="col-sm-12">
		<h2><?php echo trans('add_purchase'); ?></h2>
	</div>
	<form id="addPurchaseFrom">
		<div class="col-sm-12">
			<div class="ibox">
				<div class="ibox-content">
					<div class="row">
						<div class="col-sm-3">
							<div class="form-group" id="data_1">
								<label><?php echo trans('purchase_date'); ?>:(<?php echo trans('changeable')?>)*</label>
								<div class="input-group date">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
									<input id="purchase_date" type="text" class="form-control" name="purchase_date" value="<?php if(isset($this->route['id'])){ echo $GetPurchaseProduct['purchase_date']; }else{ echo date('Y-m-d'); } ?>" readonly />
								</div>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<label><?php echo trans('purchase_id'); ?>:(<?php echo trans('changeable')?>)*</label>
								<input class="form-control" type="text" name="purchase_id" id="purchase_id" value="<?php if(isset($this->route['id'])){ echo $GetPurchaseProduct['purchase_id']; }else{ echo $productId; } ?>" <?php if(isset($this->route['id'])){ echo "readonly"; } ?>>
							</div>
						</div>
						<?php
							if(app('admin')->checkAddon('multiple_store_warehouse')){
						?>
						<div class="col-sm-3">
							<div class="form-group">
								<label class="control-label">
									<?php echo trans('business_location'); ?>
								</label>
								<select class="form-control" name="store_id">
									<?php $posStores = app('admin')->getall('pos_store'); foreach($posStores as $posStore){	?>
										<option value="<?php echo $posStore['store_id']; ?>" <?php if($posStore['store_id'] == $this->currentUser->store_id) echo "selected"; ?>><?php echo $posStore['store_name']; ?></option>
									<?php } ?>
								</select>
							</div>	
						</div>
						<?php } ?>
						<div class="col-sm-3">
							<div class="form-group">
								<label class="control-label"><?php echo trans('purchases_status'); ?>:*</label>
								<select class="form-control" name="purchase_status">
									<option value="received" <?php if(isset($this->route['id']) && $GetPurchaseProduct['purchase_status'] == 'received' ) echo "selected";  ?>>Received</option>
									<option value="pending" <?php if(isset($this->route['id']) && $GetPurchaseProduct['purchase_status'] == 'pending' ) echo "selected";  ?>>Pending</option>
									
									<option value="ordered" <?php if(isset($this->route['id']) && $GetPurchaseProduct['purchase_status'] == 'ordered' ) echo "selected";  ?>>Ordered</option>
								</select>
							</div>
						</div>
						
						<div class="clearfix"></div>
						
						<div class="col-sm-3">
							<div class="form-group">
								<label><?php echo trans('supplier');?>:*</label>
								<div class="input-group">
									<select name="purchase_supplier" id="purchase_supplier" class="form-control select2">
										<option value=""><?php echo trans('select_supplier');?></option>
										<?php $getSuppliers = app('admin')->getwhere("pos_contact","contact_type","supplier"); foreach($getSuppliers as $getSupplier){ ?>
											<option value="<?php echo $getSupplier['contact_id']; ?>" <?php if(isset($this->route['id'])){ if($GetPurchaseProduct['supplier_id'] == $getSupplier['contact_id']) echo "selected"; } ?>><?php echo $getSupplier['name']; ?></option>
										<?php } ?>
									</select>
									<span class="input-group-btn">
										<a class="btn btn-default add_supplier" type="supplier" title="Add Supplier"><i class="fa fa-plus-circle text-primary fa-lg"></i></a>
									</span>
								</div>
							</div>
						</div>
						
						<div class="col-sm-3">
							<div class="form-group">
								<label><?php echo trans('reference_no'); ?>:*</label>
								<input class="form-control" type="text" name="purchase_reference_no" value="<?php if(isset($this->route['id'])) echo $GetPurchaseProduct['purchase_reference_no']; ?>">
							</div>
						</div>
						<?php if(isset($this->route['id']) && !empty($GetPurchaseProduct['purchase_document'])){ ?>
							<div class="col-sm-3">
								<div class="thumbnail col-sm-3">
									<a href="<?php echo 'images/stores/'.$_SERVER['SERVER_NAME'].'/document/'.$GetPurchaseProduct['purchase_document']; ?>" download><img src="assets/img/download-img.png" alt="<?php echo $GetPurchaseProduct['purchase_document']; ?>" /></a>
								</div>
								<p class="text-center align-middle">Document File :<br />
								<strong><?php echo $GetPurchaseProduct['purchase_document']; ?></strong></p>
							</div>
						<?php } ?>
						<div class="col-sm-3">
							<div class="form-group">
								<label class="control-label"><?php echo trans('attache_document'); ?></label>
								<input type="file" name="purchase_document" class="form-control-file">
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
										<i class="fa fa-search"></i>
									</span>
									<input type="text" autocomplete="off" name="purchase_barcode" id="purchase_barcode" class="form-control typeahead barcode_type_search" placeholder="<?php echo trans('enter_product_name_/_squ_/_scan_barcode'); ?>" />
									</div>
							</div>
							<?php 
								$loadPlugins = app('admin')->loadAddon('serial_product','addPurchaseSearchSerialProduct'); 
								if(app('admin')->checkAddon('serial_product'));
							?>
							<div class="row serial-enable">
								<div class="col-sm-6">
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon">
												<i class="fa fa-key"></i>
											</span>
											<input type="text" name="mother_product_id" id="mother_product_id" class="form-control" readonly/>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group serial-disable">
										<div class="input-group">
											<span class="input-group-addon">
												<i class="fa fa-barcode"></i>
											</span>
											<input type="text" autocomplete="off" name="serial_number" id="serial_number" class="form-control serial_number" placeholder="<?php echo trans('enter_serial_number'); ?>" />
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-2">
							<a href="pos/product" data-toggle="modal">
								<span class="fa fa-plus"></span>
								<?php echo trans('add_new_product'); ?>
							</a>
						</div>
					</div>
					<div class="table-responsive">
						<table class="table table-striped table-bordered" id="PurchaseListTable">
							<thead class="text-center">
								<tr style="background:#1ab394;">
									<th>#</th>
									<th style="width:15%;"><small><?php echo trans('product_name'); ?></small></th>
									<th style="width:10%;"><small><?php echo trans('quantity'); ?></small></th>
									<th style="width:10%;"><small><?php echo trans('unit_cost'); ?></small></th>
									<th style="width:10%;"><small><?php echo trans('discount'); ?> </small></th>
									<th style="width:10%;"><small><?php echo trans('sub_total'); ?></small></th>
									<?php 
										if(app('admin')->checkAddon('expired_manufacturing_date')){ 
										?>
										<th style="width:10%;"><small><?php echo trans('mgf_date'); ?></small></th>
										<th style="width:10%;"><small><?php echo trans('mxp_date'); ?></small></th>
									<?php } ?>
									<th style="width:10%;"><small><?php echo trans('profit_margin'); ?> %</small></th>
									<th style="width:10%;"><small><?php echo trans('unit_selling_price'); ?></small></th>
									<th><i class="fa fa-trash"></i></th>
								</tr>
							</thead>
							<tbody class="text-center">
								<?php if(isset($this->route['id'])){ 
									$getProductVariations = app('db')->table('pos_stock')->where('purchase_id',$GetPurchaseProduct['purchase_id'])->where('is_delete','false')->where("stock_category","purchase")->get();
									$i = 1;
									foreach($getProductVariations as $getProductVariation){
										$getVariationDetails = app('admin')->getwhereid("pos_variations","sub_product_id",$getProductVariation['sub_product_id']); 
										$getProductDetails = app('admin')->getwhereid("pos_product","product_id",$getProductVariation['product_id']); 
									?>
									<tr id="<?php echo $getVariationDetails['sub_product_id']; ?>">
										<td><?php echo $i; ?></td>
										<td><?php echo $getProductDetails['product_name']; ?> [<?php echo $getVariationDetails['variation_name']; ?>] [<?php echo $getVariationDetails['sub_product_id']; ?>]<input type="hidden" value="<?php echo $getProductVariation['sub_product_id']; ?>" name="sub_product_id[]"><input type="hidden" value="<?php echo $getProductVariation['product_id']; ?>" name="product_id[]"><input type="hidden" value="<?php echo $getProductVariation['stock_id']; ?>" name="product_stock_id[]"></td>
										<td><input type="text" value="<?php echo $getProductVariation['product_quantity']; ?>" name="purchase_product_quantity[]" data-id="<?php echo $getVariationDetails['sub_product_id']; ?>" id="purchase_quantity_<?php echo $getVariationDetails['sub_product_id']; ?>" class="form-control input-sm onchange_purchase_cal" placeholder="<?php echo trans('purchase_quantity'); ?>"></td>
										<td><input type="text" value="<?php echo $getProductVariation['product_price']; ?>" name="purchase_product_price[]" data-id="<?php echo $getVariationDetails['sub_product_id']; ?>" id="purchase_price_<?php echo $getVariationDetails['sub_product_id']; ?>" class="form-control input-sm onchange_purchase_cal" placeholder="<?php echo trans('unit_cost'); ?>"></td>
										<td><input type="text" value="<?php echo $getProductVariation['product_discount']; ?>" name="purchase_product_discount[]" data-id="<?php echo $getVariationDetails['sub_product_id']; ?>" id="purchase_discount_<?php echo $getVariationDetails['sub_product_id']; ?>" class="form-control input-sm onchange_purchase_cal" placeholder="<?php echo trans('discount'); ?>"></td>
										<td><input type="text" value="<?php echo $getProductVariation['product_subtotal']; ?>" readonly name="purchase_product_sub_total[]" data-id="<?php echo $getVariationDetails['sub_product_id']; ?>" id="purchase_sub_total_<?php echo $getVariationDetails['sub_product_id']; ?>" class="form-control input-sm onchange_purchase_cal" placeholder="<?php echo trans('sub_total'); ?>"></td>
										<td><input type="text" value="<?php echo $getProductVariation['manufac_date']; ?>" class="form-control input-sm date-picker" placeholder="<?php echo trans('mgf_date'); ?>" name="purchase_product_mgf_date[]"></td>
										<td><input type="text" value="<?php echo $getProductVariation['expire_date']; ?>" class="form-control input-sm date-picker" placeholder="<?php echo trans('mxp_date'); ?>" name="purchase_product_exp_date[]"></td>
										<td><input type="text" value="<?php echo $getVariationDetails['profit_percent']; ?>" class="form-control input-sm onchange_purchase_cal" placeholder="<?php echo trans('profit_margin'); ?>" name="purchase_product_profit_percent[]" data-id="<?php echo $getVariationDetails['sub_product_id']; ?>" id="purchase_profit_percent_<?php echo $getVariationDetails['sub_product_id']; ?>"></td>
										<td><input type="text" value="<?php echo $getVariationDetails['sell_price']; ?>" class="form-control input-sm onchange_purchase_cal variable_change_profit_sale_margin" placeholder="<?php echo trans('unit_selling_price'); ?>" name="purchase_product_sales_price[]" data-id="<?php echo $getVariationDetails['sub_product_id']; ?>" id="purchase_sales_price_<?php echo $getVariationDetails['sub_product_id']; ?>" aria-invalid="false"></td>
										<td><a href="javascript:void(0);" stock_id="<?php echo $getProductVariation['stock_id']; ?>" class="btn btn-danger btn-xs purchase_product_delete"><i class="fa fa-trash"></i></a></td>
									</tr>
								<?php $i++;} } ?>
							</tbody>
						</table>
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
								<label class="control-label"><?php echo trans('sub_total_amount'); ?></label>
								<input type="text" name="purchase_subtotal" readonly class="form-control onchange_purchase_final_cal" value="<?php if(isset($this->route['id'])) echo $GetPurchaseProduct['purchase_subtotal']; ?>" placeholder="<?php echo trans('sub_total_amount'); ?>">
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<label class="control-label"><?php echo trans('discount_amount'); ?></label>
								<input type="text" name="purchase_discount" class="form-control onchange_purchase_final_cal" value="<?php if(isset($this->route['id'])) echo $GetPurchaseProduct['purchase_discount']; ?>" placeholder="<?php echo trans('discount_amount'); ?>">
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<label class="control-label"><?php echo trans('shipping_charge'); ?></label>
								<input type="text" name="purchase_shipping_charge" class="form-control onchange_purchase_final_cal" value="<?php if(isset($this->route['id'])) echo $GetPurchaseProduct['purchase_shipping_charge']; ?>" placeholder="<?php echo trans('shipping_charge'); ?>">
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<label class="control-label"><?php echo trans('total_payable_amount'); ?></label>
								<input type="text" name="purchase_paid_amount" readonly class="form-control onchange_purchase_final_cal transaction-total-bill" value="<?php  if(isset($this->route['id'])){ $total_amount = $GetPurchaseProduct['purchase_subtotal'] - $GetPurchaseProduct['purchase_discount'] + $GetPurchaseProduct['purchase_shipping_charge']; echo $total_amount; }?>" placeholder="<?php echo trans('total_payable_amount'); ?>">
								
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="row">
						<div class="col-sm-6">
							<div class="from-group">
								<label class="control-label"><?php echo trans('shipping_details'); ?></label>
								<textarea class="form-control" name="purchase_shipping_note"><?php if(isset($this->route['id'])) echo $GetPurchaseProduct['purchase_shipping_note']; ?></textarea>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="from-group">
								<label class="control-label"><?php echo trans('note'); ?></label>
								<textarea class="form-control" name="purchase_note"><?php if(isset($this->route['id'])) echo $GetPurchaseProduct['purchase_note']; ?></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php  if(app('admin')->checkAddon('multiple_payment_method')){ ?>
			<div class="col-sm-12">
				<div class="ibox">
					<div class="ibox-content">
						<div id="payment_div" data-total-bill=""></div>
					</div>
				</div>
			</div>
		<?php } ?>
		<div class="col-sm-12">
			<div class="ibox">
				<div class="ibox-content">
					
					<div class="row">
						<div class="col-sm-12">
							<?php if(isset($this->route['id'])){ ?>
								<button type="submit" class="btn btn-success pull-right update_button" value="from_update"><?php echo trans('update'); ?></button> 
								<?php }else{ ?>
								<button type="submit" class="btn btn-success pull-right save_button " value="from_save"><?php echo trans('save'); ?></button> 
								<a href="javascript:void(0);" class="btn btn-danger pull-right reset_button btn-space "><?php echo trans('reset'); ?></a>
							<?php } ?>
							<a href="pos/purchase-list" class="btn btn-primary pull-right reset_button btn-space"><?php echo trans('back_to_purchase_list'); ?></a>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</form> 
</div>	
<div class="ModalForm">
	<div class="modal_status"></div>
</div> 	
[footer]
<?php  getJs('assets/system/js/plugins/typehead/bootstrap3-typeahead.min.js',false); ?>
<?php  if(app('admin')->checkAddon('multiple_payment_method')){ ?>
	<script>
		$(document).ready(function(){
			var purchaseId =  $('#purchase_id').val();
			AS.Http.posthtml({"action" : "GetPaymentModal", "where_name" : "purchase_id", "where_value" : purchaseId, "where_type" : "purchase" }, "pos/modal/", function (data) {
				$("#payment_div").html(data);
				GetTotalTransaction();
			});
		});
		
	</script>
<?php } ?>
<script type="text/javascript">
	
	function PurchaseFinalPaymentCal(){
		var purchase_paid_amount =  $('input[name^="purchase_paid_amount"]').val() || 0;
		var total_payment_amount =  $('#total_payment_amount').val() || 0;
		
		var total_payment_amount_purchase_paid_amount = parseFloat(purchase_paid_amount) - parseFloat(total_payment_amount);
		$('#total_due_amount').html('Total Due :'+total_payment_amount_purchase_paid_amount);
	}
	
	function PurchaseFinalCal(){
		var purchase_subtotal = 0;
		$('input[name="purchase_product_sub_total[]"]').each(function() {
			purchase_subtotal +=  parseFloat($(this).val());
		});
		var purchase_discount =  $('input[name^="purchase_discount"]').val() || 0;
		var purchase_shipping_charge =  $('input[name^="purchase_shipping_charge"]').val() || 0;
		var purchase_subtotal_with_discount = parseFloat(purchase_subtotal) - parseFloat(purchase_discount);
		var purchase_subtotal_with_discount_and_shipping_charge = parseFloat(purchase_subtotal_with_discount) + parseFloat(purchase_shipping_charge);
		$('input[name^="purchase_subtotal"]').val(purchase_subtotal);
		$('input[name^="purchase_paid_amount"]').val(purchase_subtotal_with_discount_and_shipping_charge);
		<?php if(isset($this->route['id'])) echo 'PurchaseFinalPaymentCal();'; ?>
		<?php  if(app('admin')->checkAddon('multiple_payment_method')){ ?>
			GetTotalTransaction();
		<?php } ?>
	}
	
	$(document).on("keyup",".onchange_purchase_final_cal", function(){
		PurchaseFinalCal();
	});
	
	$(document).on("click",".add_supplier", function(){
		var type = $(this).attr('type');
		$(".show_modal").remove();
		AS.Http.posthtml({"action" : "GetNewContact","type" : type, "select_id" : "purchase_supplier"}, "pos/modal/", function (data) {
			$(".modal_status").html(data);
			$("#contact_type").val(type);
			$(".show_modal").modal("show");
		});
	});	
	
	$(document).on("keyup",".onchange_purchase_cal", function(){
		var Id = $(this).data("id");
		PurchaseRowCal(Id);
		PurchaseFinalCal();
	});
	
	function PurchaseRowCal(Id){
		var purchase_quantity = $("#purchase_quantity_"+Id).val() || 0;
		var purchase_price = $("#purchase_price_"+Id).val() || 0;
		var purchase_discount = $("#purchase_discount_"+Id).val() || 0;
		var purchase_sub_total = $("#purchase_sub_total_"+Id).val() || 0;
		var purchase_profit_percent = $("#purchase_profit_percent_"+Id).val() || 0;
		
		var product_purchase_value = purchase_price * purchase_quantity;
		var product_purchase_subtotal_value = parseFloat(product_purchase_value) - parseFloat(purchase_discount);
		var total_percent = purchase_profit_percent / 100 * purchase_price;
		var total_sell_price = parseFloat(purchase_price) + parseFloat(total_percent);
		$("#purchase_sales_price_"+Id).val(total_sell_price);
		$("#purchase_sub_total_"+Id).val(product_purchase_subtotal_value);
	}
	
	$(document).on("keyup",".variable_change_profit_sale_margin", function(){
		var Id = $(this).data("id");
		var product_sales_value = $("#purchase_sales_price_"+Id).val();
		var product_purchase_value = $("#purchase_price_"+Id).val();
		var total_profit_amount = parseFloat(product_sales_value) - parseFloat(product_purchase_value);
		var total_percent = total_profit_amount / product_purchase_value * 100;
		$("#purchase_profit_percent_"+Id).val(parseFloat(total_percent).toFixed(2));
	});
	
	function AddPurchaseRow(PurchaseTable,productCode,response){
		var generator = new IDGenerator();
		var rowCnt = PurchaseTable.rows.length; 
		var tr = PurchaseTable.insertRow(rowCnt); 
		var stock_id = "ST"+generator.generate(); 
		tr.setAttribute('id', productCode);
		var col_limit = 9;
		<?php 
			if(app('admin')->checkAddon('expired_manufacturing_date')){
			?>
			col_limit =11;
		<?php  } ?>
		for (var c = 0; c < col_limit; c++) {
			var td = document.createElement('td');      
			td = tr.insertCell(c);
			if(c==0){
				td.append(rowCnt);
				}else if(c==1){
				var ele = document.createElement('input');
				ele.setAttribute('type', 'hidden');
				ele.setAttribute('value', productCode);
				ele.setAttribute('name', 'sub_product_id[]');
				td.append(response.product_name+response.variation_name+'['+productCode+']');
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
				}else if(c==2){
				<?php 
					if(app('admin')->checkAddon('serial_product')){ 
					?>
					if (response.serialNumber){
						var dve = document.createElement('div');
						dve.setAttribute('class', 'input-group m-b');
						
						var ele = document.createElement('input');
						ele.setAttribute('type', 'text');
						ele.setAttribute('value', "1");
						ele.setAttribute('name', 'purchase_product_quantity[]');
						ele.setAttribute('data-id', productCode);
						ele.setAttribute('id', 'purchase_quantity_'+productCode);
						ele.setAttribute('class', 'form-control input-sm onchange_purchase_cal');
						ele.setAttribute('placeholder', $_lang.purchase_quantity);
						ele.setAttribute('readonly', 'readonly');
						
						var spe = document.createElement('span');
						spe.setAttribute('class', 'input-group-addon serial_show');
						spe.setAttribute('data-id', productCode);
						
						var unite_spe = document.createElement('span');
						unite_spe.setAttribute('class', 'input-group-addon');
						unite_spe.append(response.unit_name);
						
						var ic = document.createElement('i');
						ic.setAttribute('class', 'fa fa-eye');
						
						dve.appendChild(ele);
						spe.appendChild(ic);
						dve.appendChild(unite_spe);
						dve.appendChild(spe);
						td.appendChild(dve);
						
						td.setAttribute('id', 'quantity_row_'+productCode);
						
						var ele = document.createElement('input');
						ele.setAttribute('type', 'hidden');
						ele.setAttribute('value', response.serialNumber);
						ele.setAttribute('id', productCode+response.serialNumber);
						ele.setAttribute('name', 'product_serial_id['+productCode+'][]');
						td.appendChild(ele);
						
						}else{
						var dve = document.createElement('div');
						dve.setAttribute('class', 'input-group m-b');
						
						var ele = document.createElement('input');
						ele.setAttribute('type', 'text');
						ele.setAttribute('value', "1");
						ele.setAttribute('name', 'purchase_product_quantity[]');
						ele.setAttribute('data-id', productCode);
						ele.setAttribute('id', 'purchase_quantity_'+productCode);
						ele.setAttribute('class', 'form-control input-sm onchange_purchase_cal');
						ele.setAttribute('placeholder', $_lang.purchase_quantity);
						
						var spe = document.createElement('span');
						spe.setAttribute('class', 'input-group-addon');
						spe.append(response.unit_name);
						
						dve.appendChild(ele);
						dve.appendChild(spe);
						td.appendChild(dve);
					}
					<?php
						}else{ 
					?>
					var dve = document.createElement('div');
					dve.setAttribute('class', 'input-group m-b');
					
					var ele = document.createElement('input');
					ele.setAttribute('type', 'text');
					ele.setAttribute('value', "1");
					ele.setAttribute('name', 'purchase_product_quantity[]');
					ele.setAttribute('data-id', productCode);
					ele.setAttribute('id', 'purchase_quantity_'+productCode);
					ele.setAttribute('class', 'form-control input-sm onchange_purchase_cal');
					ele.setAttribute('placeholder', $_lang.purchase_quantity);
					
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
				ele.setAttribute('value', response.purchase_price);
				ele.setAttribute('name', 'purchase_product_price[]');
				ele.setAttribute('data-id', productCode);
				ele.setAttribute('id', 'purchase_price_'+productCode);
				ele.setAttribute('class', 'form-control input-sm onchange_purchase_cal');
				ele.setAttribute('placeholder', $_lang.purchase_price);
				td.appendChild(ele);
				}else if(c==4){
				var ele = document.createElement('input');
				ele.setAttribute('type', 'text');
				ele.setAttribute('value', "");
				ele.setAttribute('name', 'purchase_product_discount[]');
				ele.setAttribute('data-id', productCode);
				ele.setAttribute('id', 'purchase_discount_'+productCode);
				ele.setAttribute('class', 'form-control input-sm onchange_purchase_cal');
				ele.setAttribute('placeholder', $_lang.purchase_discount);
				td.appendChild(ele);
				}else if(c==5){
				var ele = document.createElement('input');
				ele.setAttribute('type', 'text');
				ele.setAttribute('value', response.purchase_price);
				ele.setAttribute('name', 'purchase_product_sub_total[]');
				ele.setAttribute('data-id', productCode);
				ele.setAttribute('id', 'purchase_sub_total_'+productCode);
				ele.setAttribute('class', 'form-control input-sm onchange_purchase_cal');
				ele.setAttribute('placeholder', $_lang.purchase_sub_total);
				td.appendChild(ele);
				}else if(c==8){
				var ele = document.createElement('input');
				ele.setAttribute('type', 'text');
				ele.setAttribute('value', response.profit_percent);
				ele.setAttribute('class', 'form-control input-sm onchange_purchase_cal');
				ele.setAttribute('placeholder', $_lang.purchase_profit_percent);
				ele.setAttribute('name','purchase_product_profit_percent[]');
				ele.setAttribute('data-id', productCode);
				ele.setAttribute('id', 'purchase_profit_percent_'+productCode);
				td.appendChild(ele);
				}else if(c==9){
				var ele = document.createElement('input');
				ele.setAttribute('type', 'text');
				ele.setAttribute('value', response.sell_price);
				ele.setAttribute('class', 'form-control input-sm variable_change_profit_sale_margin');
				ele.setAttribute('placeholder', $_lang.purchase_sales_price);
				ele.setAttribute('name','purchase_product_sales_price[]');
				ele.setAttribute('data-id', productCode);
				ele.setAttribute('id', 'purchase_sales_price_'+productCode);
				td.appendChild(ele);
			}
			<?php 
				if( app('admin')->checkAddon('expired_manufacturing_date')){
				?>
				else if(c==6){
					var ele = document.createElement('input');
					ele.setAttribute('type', 'text');
					ele.setAttribute('value', '');
					ele.setAttribute('class', 'form-control input-sm date-picker');
					ele.setAttribute('placeholder', $_lang.purchase_mgf_date);
					ele.setAttribute('name','purchase_product_mgf_date[]');
					td.appendChild(ele);
					}else if(c==7){
					var ele = document.createElement('input');
					ele.setAttribute('type', 'text');
					ele.setAttribute('value', '');
					ele.setAttribute('class', 'form-control input-sm date-picker');
					ele.setAttribute('placeholder', $_lang.purchase_exp_date);
					ele.setAttribute('name','purchase_product_exp_date[]');
					td.appendChild(ele);
				}
				
				else if(c==10){
					<?php }
					else{ ?>
					
					else if(c==8){
					<?php } ?>
					var ele = document.createElement('a');
					ele.setAttribute('href', 'javascript:void(0);');
					ele.setAttribute('stock_id', stock_id);
					ele.setAttribute('class', 'btn btn-danger btn-xs purchase_product_delete');
					var ele_icon = document.createElement('i');
					ele_icon.setAttribute('class', 'fa fa-trash');
					ele.appendChild(ele_icon);
					td.appendChild(ele);
				}
			}
		}
		
		function AddPurchaseProductList(productCode,serialNumber = false){
			var product_quantity = $("#purchase_quantity_"+productCode).val() || 0;
			AS.Http.post({"action" : "GetPurchaseProductList","product_code" : productCode}, "pos/ajax/", function (response) {
				if(response.status == 'success'){
					if(response.product_status == 'found'){
						response.serialNumber = serialNumber;
						if (!serialNumber) {
							$(".serial-enable").hide();
						}
						$(".serial_number").val("");
						$('#empty_cart').remove();
						var PurchaseTable = document.getElementById('PurchaseListTable');
						if (PurchaseTable.rows[response.sub_product_id]){
							<?php 
								if(app('admin')->checkAddon('serial_product')){ 
								?>
								var new_quantiry =  parseFloat(product_quantity) + parseFloat('1');
								if (serialNumber) {
									var checkSerial = true;
									$('input[name="product_serial_id['+response.sub_product_id+'][]"]').each(function() {
										if($(this).val() == serialNumber){
											swal({
												title: 'this_serial_no_already_exits', 
												text: 'check_serial_no_again', 
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
										ele.setAttribute('value', serialNumber);
										ele.setAttribute('id', response.sub_product_id+serialNumber);
										ele.setAttribute('name', 'product_serial_id['+response.sub_product_id+'][]');
										td.appendChild(ele);
										$("#purchase_quantity_"+response.sub_product_id).val(new_quantiry);
									}
									}else{
									$("#purchase_quantity_"+response.sub_product_id).val(new_quantiry);
								}
								<?php
									}else{
								?>
								var new_quantiry =  parseFloat(product_quantity) + parseFloat('1');
								$("#purchase_quantity_"+response.sub_product_id).val(new_quantiry);
							<?php } ?>
							}else{
							AddPurchaseRow(PurchaseTable,response.sub_product_id,response);
							console.log(response.sub_product_id);
						}
						AddPurchaseRowExtraLoad(response.sub_product_id);
						}else{
						alert('No Product Found');
					}
					}else{
					alert('No Product Found');
				}
			});
		}
		
		function AddPurchaseRowExtraLoad(productCode,response = false){
			$('.date-picker').datepicker({
				todayBtn: "linked",
				format: "yyyy-mm-dd",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
			});
			
// 			$(".purchase_product_delete").click(function(){
// 			$(this).parent('td').parent('tr').remove();
// 				PurchaseFinalCal();
// 			});
			PurchaseRowCal(productCode);
			PurchaseFinalCal();
		}
		
		$(document).on("keyup",".barcode_type_search", function(event){
			var purchaseBarcode = $('#purchase_barcode').val();
			var key = event.keyCode || event.charCode;
			if( key == 8 || key == 46 ) $('.typeahead').typeahead('destroy');
			if( this.value.length < 3 ) return;
			AS.Http.post({"action" : "GetPurchaseBarcodeTypeSearch","purchase_barcode" : purchaseBarcode}, "pos/ajax/", function (response) {
				if(response.source_data){
					var $input = $(".typeahead");
					$input.typeahead({
						source: response.source_data,
						autoSelect: true
					});
					$input.change(function() {
						var current = $input.typeahead("getActive");
						if (current) {
							<?php 
								if(app('admin')->checkAddon('serial_product')){ 
								?>
								if(current.product_serial == 'enable'){
									$(".barcode_type_search").val("");
									$("#mother_product_id").val(current.product_id);
									$(".serial-enable").show();
									$('.typeahead').typeahead('destroy');
									}else if(current.product_serial == 'disable'){
									AddPurchaseProductList(current.product_id);
									$("#purchase_barcode").val('');
									$("#purchase_barcode").focus();
									$('.typeahead').typeahead('destroy');
								}
								<?php
									}else{
								?>
								
								AddPurchaseProductList(current.product_id);
								$("#purchase_barcode").val('');
								$("#purchase_barcode").focus();
								$('.typeahead').typeahead('destroy');
							<?php } ?>
						}
					});
				}
			});
		});
		
		$(document).on("keypress",".barcode_type_search", function(e){
			if ( e.which == 13 ){
				var purchaseBarcode = $('#purchase_barcode').val();
				AddPurchaseProductList(purchaseBarcode)
				$("#purchase_barcode").val('');
				$("#purchase_barcode").focus();
				e.preventDefault();
				
			} 
		});
		
		<?php 
			if(app('admin')->checkAddon('serial_product')){ 
			?>
			
			$(document).on("keypress",".serial_number", function(e){
				if ( e.which == 13 ){
					var productCode = $('#mother_product_id').val();
					var serialNumber = $('#serial_number').val();
					AddPurchaseProductList(productCode,serialNumber)
					$("#purchase_barcode").val('');
					$("#serial_number").val('');
					$("#serial_number").focus();
					e.preventDefault();
				} 
			});	
			
		<?php } ?>
		
		<?php 
		if(app('admin')->checkAddon('serial_product')){ 
			?>
			
			$(document).on("click",".serial_show", function(){
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
			
		<?php } ?>
		
		$(document).on("click","button[type=submit]", function(){
			$('#addPurchaseFrom').find("button[type=submit].active").removeClass("active");
			$(this).addClass('active');
		});
		
		$(document).on("click",".reset_button", function(){
			purchaseFromReset();
		});
		
		$(document).bind('keydown', 'ctrl+s', function(e) {
			$('#addPurchaseFrom').find("button[type=submit].active").removeClass("active");
			$('.from_save_button').addClass('active');
			$( "#addPurchaseFrom" ).submit();
			e.preventDefault();
		});
		
		$(document).bind('keydown', 'ctrl+D', function(E) {
			$('#addPurchaseFrom').find("button[type=submit].active").removeClass("active");
			purchaseFromReset();
			e.preventDefault();
		});
		
		
		function purchaseFromReset(){
			var generator = new IDGenerator();
			var purchaseId = "PO"+generator.generate();
			document.getElementById("addPurchaseFrom").reset();
			$('#PurchaseListTable').find('tr:gt(0)').remove(); 
			<?php  if(app('admin')->checkAddon('multiple_payment_method')){ ?>
				GetTransactionReset();
			<?php } ?>
			$('#purchase_id').val(purchaseId);
			$(".serial-enable").hide();
		}
		
		$('.AddPurchaseAdvance form').validate({
			rules: {
				purchase_supplier: {
					required: true
				}
			},	
			submitHandler: function(form) {		
				AS.Http.PostSubmit(form, {"action" : "AddPurchaseAdvanceData"}, "pos/ajax/", function (response) {
					<?php  if(!isset($this->route['id'])){ echo "purchaseFromReset();"; }?>
					if(response.status=='success'){
						swal({
							title: $_lang.success, 
							text: response.message, 
							type: "success",
							confirmButtonColor: "#1ab394", 
							confirmButtonText: $_lang.ok,
						});
					}
				});
			}
		});
		
		
		$(document).ready(function(){
			
			$(".serial-enable").hide();
			
			$('#data_1 .input-group.date').datepicker({
				todayBtn: "linked",
				format: "yyyy-mm-dd",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				endDate: new Date(),
				autoclose: true
			});
			
			$('.custom_transaction_id').hide();
			$('.payment_method').change(function(){
				var type= $('.payment_method').val();
				if(type!='cash'){
					$('.custom_transaction_id').show();
				}
				else{
					$('.custom_transaction_id').hide();
				}
			});
			
			$('.date-picker').datepicker({
				todayBtn: "linked",
				format: "yyyy-mm-dd",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
			});
			$(".purchase_product_delete").click(function(){
			    var $purchase_item = $(this);
		    	var stock_id = $(this).attr('stock_id');
			    AS.Http.post({"action" : "DeleteStockByStockId","stock_id": stock_id }, "pos/ajax/", function (response) {
					if(response.status == 'success'){
						$purchase_item.parent('td').parent('tr').remove();
				        PurchaseFinalCal();
					}
				});
			});
		});
	</script>
	[/footer]
	
<?php include dirname(__FILE__) .'/../../modules/pos/include/footer.php'; 																																																									