<?php defined('_AZ') or die('Restricted access');  
	
	switch ($loadPlugins['load_addon']) {
		
		case 'addPurchaseSearchSerialNumberShowJS':
		
		break;
		
		case 'AddPurchaseRowJS':
		
		break;
		
		case 'AddPurchaseProductListJS':
		
		break;
		
		case 'addPurchaseSearchSerialProductJS':
		
		break;
		
		case 'addProdductEnableSerial':
	?>
	<div class="col-sm-2">
		<div class="form-group">
			<label class="">
				<input type="checkbox" class="" name="product_serial_num" <?php if(isset($GetProduct)){ if($GetProduct['product_serial'] == "enable") echo "checked"; } ?>>
				<strong><?php echo trans('enable_imie_or_serial_number');?></strong>
			</label>
		</div>
	</div>
	<?php
		break;
		
		case 'addPurchaseSearchSerialProduct':
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
	<?php
		break;
		
		default:
		break;
	}
	
	
?>				