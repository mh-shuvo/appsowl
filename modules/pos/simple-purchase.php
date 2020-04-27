<?php defined('_AZ') or die('Restricted access'); 
	include dirname(__FILE__) .'/include/header.php';
	include dirname(__FILE__) .'/include/side_bar.php';
	include dirname(__FILE__) .'/include/navbar.php';
?>

<div class="wrapper wrapper-content animated fadeInRight purchase-single-product">
	<form role="form">
		<div class="row">
			
			<div class="col-sm-4">
				<div class="ibox float-e-margins">
					<div class="ibox-content">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-3">
									<label><?php echo trans('product_code'); ?>:</label>
								</div>
								<div class="col-sm-9">
									<input type="text" name="product_code" id="product_code" class="form-control" placeholder="<?php echo trans('write_product_code_scan'); ?>" autofocus onChange="SearchPurchaseProductByCode();">
									<input type="hidden" name="product_id" id="product_id" value="null">
									<input type="hidden" name="supplier_id" id="supplier_id" value="null">
									<input type="hidden" name="unit_id" id="unit_id" value="null">
									<input type="hidden" name="category_id" id="category_id" value="null">
									<input type="hidden" name="brand_id" id="brand_id" value="null">
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-3">
									<label><?php echo trans('product_name'); ?>:</label>
								</div>
								<div class="col-sm-9">
									<input type="text" id="product_name" list='products_name' name="product_name" class="form-control" placeholder="<?php echo trans('write_product_name'); ?>" onChange="SearchPurchaseProductByName();">
									
									<datalist id="products_name">
										<?php
											$pos_product = app('admin')->getall('pos_product');
											
											foreach($pos_product as $pos_product){
												echo "<option>".$pos_product['product_name']."</option>";
											}?>
									</datalist>
								</div>
							</div>
							
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-3">
									<label><?php echo trans('select_unit'); ?>:</label>
								</div>
								<div class="col-sm-9">
									<input type="text" name="product_unit" id="product_unit_name" list="unites" placeholder="<?php echo trans('select_unit_or_write'); ?>" class="form-control" onChange="CheckUnite();">
									
									<datalist id="unites">
										<?php $pos_unit = app('admin')->getall('pos_unit'); 
											foreach($pos_unit as $pos_unit){
												echo "<option>".$pos_unit['unit_name']."</option>";
											}?>
									</datalist>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-3">
									<label><?php echo trans('product_size'); ?>:</label>
								</div>
								<div class="col-sm-9">
									<input type="text" class="form-control" name="product_size" id="product_size" placeholder="<?php echo trans('write_product_size'); ?>">
								</div>
							</div>
							
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-3">
									<label><?php echo trans('purchase_price'); ?>:</label>
								</div>
								<div class="col-sm-9">
									<input type="text" id="purchase_price" name="purchase_price" class="form-control" placeholder="<?php echo trans('write_purchase_price'); ?>" onchange="updatesubtotal();">
								</div>
							</div>
							
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-3">
									<label><?php echo trans('product_sales_quantity'); ?>:</label>
								</div>
								<div class="col-sm-9">
									<input type="number" id="product_quantity" name="product_quantity" class="form-control" placeholder="<?php echo trans('what_are_the_products_you_want_to_add'); ?>"  onkeyup="updatesubtotal();">
								</div>
							</div>
							
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-3">
									<label><?php echo trans('sub_total'); ?>:</label>
								</div>
								<div class="col-sm-9">
									<input type="text" id="subtotal" name="subtotal" data-id="0" class="form-control" placeholder="<?php echo trans('sub_total'); ?>" >
								</div>
							</div>
						</div>
					</div>
				</div>		
			</div>
			<div class="col-sm-4 product-extra hidden">
				<div class="ibox float-e-margins">
					<div class="ibox-content">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-3">
									<label><?php echo trans('sales_price'); ?>:</label>
								</div>
								<div class="col-sm-9">
									<input type="text" class="form-control" name="sales_price" id="sales_price" placeholder="<?php echo trans('write_sales_price'); ?>">
								</div>
							</div>
							
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-3">
									<label><?php echo trans('vat')." (%)"; ?>:</label>
								</div>
								<div class="col-sm-9">
									<input type="text" class="form-control" name="product_vat" id="product_vat" placeholder="<?php echo trans('percent_vat'); ?>">
								</div>
							</div>
							
						</div>
						
						<div class="form-group">
							<div class="row" >
								<div class="col-sm-3">
									<label><?php echo trans('manufacturing_date'); ?>:</label>
								</div>
								<div class="col-sm-9">
									<input type="text" class="form-control " name="manufac_date" data-mask="99-99-9999" id="manufac_date" placeholder="<?php echo trans('product_manufacturing_date'); ?>">
									<span>DD-MM-YYYY</span>
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<div class="row">
								<div class="col-sm-3">
									<label><?php echo trans('expire_date'); ?>:</label>
								</div>
								<div class="col-sm-9">
									<input type="text" class="form-control" data-mask="99-99-9999" name="exp_date" id="exp_date" placeholder="<?php echo trans('product_expire_date'); ?>">
									<span>DD-MM-YYYY</span>
								</div>
							</div>
							
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-3">
									<label><?php echo trans('product_brand'); ?>:</label>
								</div>
								<div class="col-sm-9">
									<input type="text" name="product_brand" id="product_brand" list="brands" placeholder="<?php echo trans('write_select_brand'); ?>" class="form-control" onchange="CheckBrand()">
									<datalist id="brands">
										<?php $pos_brands = app('admin')->getall('pos_brands'); 
											foreach($pos_brands as $pos_brand){
												echo "<option>".$pos_brand['brand_name']."</option>";
											}?>
									</datalist>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-3">
									<label><?php echo trans('product_category'); ?>:</label>
								</div>
								<div class="col-sm-9">
									<input type="text" name="product_category" id="product_category" list="categories" placeholder="<?php echo trans('write_select_category'); ?>" class="form-control" onchange="CheckCategory()">
									<datalist id="categories">
										<?php $pos_category = app('admin')->getall('pos_category'); 
											foreach($pos_category as $category){
												echo "<option>".$category['category_name']."</option>";
											}?>
									</datalist>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-3">
									<label><?php echo trans('supplier_name'); ?>:</label>
								</div>
								<div class="col-sm-9">
									<input type="text" class="form-control" name="supplier_name" list="suppliers" id="supplier_name" placeholder="<?php echo trans('supplier_name_if_any'); ?>" onchange='SearchPurchaseSupplierByName' onkeyup="SearchPurchaseSupplierByName();">
									<datalist id="suppliers">
										<?php $pos_supplier = app('admin')->getwhere('pos_contact','contact_type','supplier'); 
											foreach($pos_supplier as $supplier){
												echo "<option>".$supplier['name']."</option>";
											}?>
									</datalist>
								</div>
							</div>
						</div>
						<!--<div class="form-group" id="add_btn_supplier_col">
							<div class="row">									
							<div class="col-sm-9 pull-right">
							<button type="submit" class="btn btn-success pull-right"><i class="fa fa-plus"></i> <?php echo trans('add_product'); ?></button>
							</div>
							</div>
						</div>-->
					</div>
				</div>
			</div>
			<div class="col-sm-4 supplier-extra hidden">
				<div class="ibox float-e-margins">
					<div class="ibox-content">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-3">
									<label><?php echo trans('supplier_address'); ?>:</label>
								</div>
								<div class="col-sm-9">
									<textarea rows="1" class="form-control" name="supplier_address" placeholder="<?php echo trans('supplier_address_if_any'); ?>"></textarea>
								</div>
							</div>
							
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-3">
									<label><?php echo trans('supplier_phone'); ?>:</label>
								</div>
								<div class="col-sm-9">
									<input type="text" class="form-control" name="supplier_phone" id="supplier_phone" placeholder="<?php echo trans('supplier_phone_if_any'); ?>">
								</div>
							</div>
							
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-3">
									<label><?php echo trans('supplier_email'); ?>:</label>
								</div>
								<div class="col-sm-9">
									<input type="email" class="form-control" name="supplier_email" id="supplier_email" placeholder="<?php echo trans('supplier_email_if_any'); ?>">
								</div>
							</div>
							
						</div>
						<!--<div class="form-group">
							<div class="row">									
							<div class="col-sm-9 pull-right">
							<button type="submit" class="btn btn-success pull-right"><i class="fa fa-plus"></i> <?php echo trans('add_product'); ?></button>
							</div>
							</div>
						</div>-->
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6 col-sm-offset-3">
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
						
						<div class="row custom_transaction_id">
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
									<button type="submit" class="btn btn-success pull-right" id="submit_purchase_form"><?php echo trans('add_product'); ?></button>
								</div>
							</div>
						</div>	
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
[footer]
<?php getJs('assets/system/js/plugins/jasny/jasny-bootstrap.min.js',false);?>
<script>
	document.getElementById('supplier_name').addEventListener('keypress', function(event) {
		if (event.keyCode == 13) {
			event.preventDefault();
		}
	});
	document.getElementById('product_code').addEventListener('keypress', function(event) {
	if (event.keyCode == 13) {
	SearchPurchaseProductByCode();
	event.preventDefault();
	}
	});
	document.getElementById('product_name').addEventListener('keypress', function(event) {
	if (event.keyCode == 13) {
	SearchPurchaseProductByName();
	event.preventDefault();
	}
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
</script>
[/footer]
<?php include dirname(__FILE__) .'/include/footer.php'; ?>	
