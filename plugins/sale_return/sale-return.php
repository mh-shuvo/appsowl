<?php defined('_AZ') or die('Restricted access'); 
	// app('pos')->checkPermission('sale_return','edit',true) or die(redirect("/pos/access-denied"));
	include dirname(__FILE__) .'/../../modules/pos/include/header.php';
	include dirname(__FILE__) .'/../../modules/pos/include/side_bar.php';
	include dirname(__FILE__) .'/../../modules/pos/include/navbar.php';
	
	if(isset($this->route['id'])){
		$GetSalesProduct = app('admin')->getwhereid("pos_sales","sales_id",$this->route['id']);
		}else{
		redirect("pos/return-sale-list");
	}
	$GetSalesReturnProduct = app('admin')->getwhereid("pos_return","sales_id",$GetSalesProduct['sales_id']);
	if($GetSalesReturnProduct){
		$returnId = $GetSalesReturnProduct['return_id'];
		}else{
		$returnId = 'RT'.gettoken(8);
	}
?>
[header]
<style type="text/css">
	.form-control-file{height: 33px;}
	th{text-align: center;}
	.bg-green{ 
	background: #1ab394;
	color: white;
	}
	.text-primary{color:#1ab394; border:1px solid #1ab394;}
	.text-danger{color:#ed5565; border:1px solid #ed5565;}
</style>
[/header]
<div class="row AddSalesReturn">
	<div class="col-sm-12">
		<h2><?php echo trans('sale_return'); ?></h2>
	</div>
	<form>
		<input type="hidden" name="return_id" id="return_id" value="<?php if(isset($this->route['id'])){ echo $returnId; } ?>">
		<div class="col-sm-12">
			<div class="ibox">
				<div class="ibox-content">
					<div class="row">
						<div class="col-sm-3">
							<div class="form-group">
								<label><?php echo trans('sales_id'); ?> :</label>
								<label><?php echo $GetSalesProduct['sales_id'];?></label>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<label><?php echo trans('customer_id');?> :</label>
								<?php $getSuppliers = app('admin')->getwhereid("pos_contact","contact_id",$GetSalesProduct['customer_id']);?>
								<label><?php echo $getSuppliers['name'];?></label>
							</div>
						</div>
						
						<div class="col-sm-3">
							<div class="form-group" id="data_1">
								<label><?php echo trans('sales_date'); ?> :</label>
								<label><?php echo getdatetime($GetSalesProduct['created_at'],3);?></label>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-12">
			<div class="ibox">
				<div class="ibox-content">
					<div class="table-responsive">
						<table class="table table-striped table-bordered" id="PurchaseListTable">
							<thead class="text-center">
								<tr style="background:#1ab394;">
									<th><small><?php echo trans('product_name'); ?></small></th>
									<th><small><?php echo trans('unit_cost'); ?></small></th>
									
									<th><small><?php echo trans('sales_quantity'); ?></small></th>
									
									<th><small><?php echo trans('already_returned'); ?></small></th>
									
									<th><small><?php echo trans('return_quantity'); ?></small></th>
									<th><small><?php echo trans('sub_total'); ?></small></th>
									
								</tr>
							</thead>
							<tbody class="text-center">
								<input type="hidden" name="sales_id" value="<?php echo $this->route['id'];?>">
								<input type="hidden" name="customer_id" value="<?php echo $GetSalesProduct['customer_id'];?>">
								<?php if(isset($this->route['id'])){ 
									$getProductVariations = app('db')->table('pos_stock')->where('sales_id',$GetSalesProduct['sales_id'])->where('stock_category',"sales")->where('is_delete',"false")->get();
									foreach($getProductVariations as $getProductVariation){
										$getVariationDetails = app('db')->table('pos_variations')->where('sub_product_id',$getProductVariation['sub_product_id'])->where('is_delete',"false")->get(1);
										$getProductDetails = app('db')->table('pos_product')->where('product_id',$getProductVariation['product_id'])->where('is_delete',"false")->get(1);
										$GetReturnSales = app('db')->table('pos_stock')->where('sales_id',$getProductVariation['sales_id'])->where('sub_product_id',$getProductVariation['sub_product_id'])->where('return_id',$returnId)->where('stock_category','return')->where('is_delete',"false")->get(1);
									?>
									<tr>
										<td>
											<?php echo $getProductDetails['product_name']; if(!empty($getVariationDetails['variation_name'])) echo ' ['.$getVariationDetails['variation_name'].'] '; ?> [<?php echo $getVariationDetails['sub_product_id']; ?>]
											<input type="hidden" data-id="<?php echo $getProductVariation['stock_id']; ?>" value="<?php echo $getProductVariation['sub_product_id']; ?>" name="sub_product_id[]">
											<input type="hidden" value="<?php echo $getProductVariation['product_id']; ?>" name="product_id[]" />
											<input type="hidden" value="<?php echo $getProductVariation['product_vat']; ?>" name="product_vat[]" id="product_vat_<?php echo $getProductVariation['stock_id']; ?>" data-id="<?php echo $getProductVariation['stock_id']; ?>" />
										</td>
										<td>
											<label><?php echo $getProductVariation['product_price']; ?></label>
											<input type="hidden" value="<?php echo $getProductVariation['product_price']; ?>" data-id="<?php echo $getProductVariation['stock_id']; ?>" data-pid="<?php echo $getProductVariation['sub_product_id']; ?>" name="product_price[]">
										</td>
										<td>
											<label><?php echo $getProductVariation['product_quantity']; ?></label>
											<input type="hidden" data-id="<?php echo $getProductVariation['stock_id']; ?>" value="<?php echo $getProductVariation['product_quantity']; ?>" id="available_quantity_<?php echo $getProductVariation['stock_id']; ?>">
										</td>
										<td>
											<label id="AlreadyReturnQty_<?php echo $getProductVariation['stock_id']; ?>"><?php if($GetSalesReturnProduct) echo $GetReturnSales['product_quantity']; ?></label>
											<input type="hidden" data-id="<?php echo $getProductVariation['stock_id']; ?>" value="<?php if($GetSalesReturnProduct){ echo $GetReturnSales['product_quantity']; }else{ echo "0";} ?>" name="already_returned[]" id="already_returned_<?php echo $getProductVariation['stock_id']; ?>">
										</td>
										
										<td>
											<?php
												if($getProductDetails['product_serial']=='enable'){
												?>
												<div class="input-group">
													<input type="text" name="return_product_quantity[]" readonly data-id="<?php echo $getProductVariation['stock_id']; ?>" id="return_quantity_<?php echo $getProductVariation['stock_id']; ?>" class="form-control input-sm onchange_quantity_check" placeholder="<?php echo trans('return_quantity'); ?>">
													<span class="input-group-addon view_serial_product" data-id="<?php echo $getProductVariation['sub_product_id']; ?>" data-s_id="<?php echo $this->route['id'];?>"><i class="fa fa-eye"></i></span>
												</div>
												<div class="modal_status_<?php echo $getProductVariation['sub_product_id']; ?>"></div>
												<?php 
													$sale_serial_product = app('db')->table('pos_product_serial')->where('sub_product_id',$getProductVariation['sub_product_id'])->where('sales_id',$this->route['id'])->where('product_serial_status','sell')->where('is_delete',"false")->get();
												?>
												
												<div class="modal fade show_modal_<?php echo $getProductVariation['sub_product_id']; ?>" tabindex="-1" role="dialog" >
													<div class="modal-dialog purchase-modal" >
														<div class="modal-content">
															<div class="modal-header">
																<button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																<h2 class="modal-title text-center" id="modalTitle"> <?php echo trans('serial_details');?> </h2>
															</div>
															<div class="modal-body">
																<div class="row">
																	<div class="col-sm-12">
																		<table class="table table-bordered" >
																			<thead>
																				<tr>
																					<th>#</th>
																					<th><?php echo trans('serial'); ?></th>
																				</tr>
																			</thead>
																			<tbody class="text-center">
																				<?php 
																					for ($i=0; $i < count($sale_serial_product) ; $i++) { 
																						$CheckTransferStatus = app('db')->table('pos_product_serial')->where('id',$sale_serial_product[$i]['id'])->where('product_serial_category','sales_return')->where('is_delete',"false")->get(1);
																					?>
																					<tr>
																						<td>
																							<input type="checkbox" class="checkboxQtyUpdate" data-id="<?php echo $getProductVariation['stock_id']; ?>" data-pid="<?php echo $getProductVariation['sub_product_id']; ?>" name="serial_number[<?php echo $getProductVariation['sub_product_id']; ?>][]" value="<?php echo $sale_serial_product[$i]['product_serial_no'];?>" <?php if($CheckTransferStatus) echo 'disabled="disabled"'; ?>>
																						</td>
																						<td><?php echo $sale_serial_product[$i]['product_serial_no']; ?></td>
																					</tr>
																				<?php } ?>
																			</tbody>
																		</table>
																	</div>									
																</div>						
																
															</div>
															<div class="modal-footer">
																<button type="button" class="btn btn-default no-print" data-dismiss="modal"><?php echo trans('close'); ?></button>
															</div>
														</div>
													</div>
												</div>
												<?php }else{ ?>
												<input type="text" name="return_product_quantity[]" data-id="<?php echo $getProductVariation['stock_id']; ?>" id="return_quantity_<?php echo $getProductVariation['stock_id']; ?>" class="form-control input-sm onchange_quantity_check" placeholder="<?php echo trans('return_quantity'); ?>">
											<?php } ?>
										</td>
										<td>
											<input type="text" name="return_product_sub_total[]" value="<?php if($GetSalesReturnProduct) echo $GetReturnSales['product_subtotal']; ?>" data-id="<?php echo $getProductVariation['stock_id']; ?>" id="return_sub_total_<?php echo $getProductVariation['stock_id']; ?>" class="form-control input-sm onchange_purchase_cal" readonly>
										</td>
										
									</tr>
									<?php
									} 
								} 
								?>
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
								<label class="control-label"><?php echo trans('sales_return_subtotal'); ?></label>
								<input type="text" name="sales_return_subtotal" class="form-control" value="<?php if($GetSalesReturnProduct) echo $GetSalesReturnProduct['return_subtotal']; ?>" readonly>
							</div>
						</div>
						<div class="col-sm-3">
							<label class="control-label"><?php echo trans('return_vat'); ?></label>
							<input type="text" name="sales_return_vat" class="form-control onchange_return_cal" value="<?php if($GetSalesReturnProduct) echo $GetSalesReturnProduct['return_vat']; ?>" readonly>
						</div>
						
						<div class="col-sm-3">
							<div class="form-group">
								<label class="control-label"><?php echo trans('sales_return_discount'); ?></label>
								<input type="text" name="sales_return_discount" class="form-control onchange_return_cal" value="<?php if($GetSalesReturnProduct) echo $GetSalesReturnProduct['return_discount']; ?>" placeholder="<?php echo trans('sales_return_discount'); ?>">
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<label class="control-label"><?php echo trans('sales_return_total'); ?></label>
								<input type="text" name="sales_return_total" class="form-control transaction-total-bill" value="<?php if($GetSalesReturnProduct) echo $GetSalesReturnProduct['return_total']; ?>" readonly>
							</div>
						</div>
						<div class="col-sm-6 col-xs-12">
							<div class="form-group">
								<textarea class="return_note form-control" name="return_note" placeholder="<?php echo trans('return_note');?>"></textarea>	
							</div>
						</div>
						<div class="col-sm-4 col-xs-4">
							<div class="form-group">
									<label><?php echo trans('attach_document');?>:*</label>
									<input type="file" class="form-control-file" accept="image/*;capture=camera" name="document" accept="image/*">
									<small><p class="help-block"> <?php echo trans('max_file_size_1mb');?> <br><?php echo trans('aspect_ratio_should_be_1:1');?></p></small>
									<?php if($GetSalesReturnProduct){ ?>
										<?php 
											$imageLocation = "images/stores/".$_SERVER['SERVER_NAME'].'/'.$GetSalesReturnProduct['document'];
											if(!empty($GetSalesReturnProduct['document']) && file_exists($imageLocation)){
												$imageLocation = "images/stores/".$_SERVER['SERVER_NAME'].'/'.$GetSalesReturnProduct['document'];
												}else{
												$imageLocation = "assets/img/no-image.png";
											}
										?>
										<div class="thumbnail col-sm-2">
											<img src="<?php echo $imageLocation; ?>" alt="<?php echo $GetSalesReturnProduct['product_name']; ?>">
										</div>
									<?php } ?>
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
						<button type="submit" class="btn btn-success pull-right" id="submit_purchase_form"><?php echo trans('submit'); ?></button>
						<a href="pos/sell-return-report" class="btn btn-primary pull-right reset_button btn-space"><?php echo trans('back_to_purchase_list'); ?></a>
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
<?php getJs('assets/system/js/plugins/dataTables/datatables.min.js',false,false); ?>
<?php  if(app('admin')->checkAddon('multiple_payment_method')){ ?>
	<script>
		$(document).ready(function(){
			var returnId =  $('#return_id').val();
			AS.Http.posthtml({"action" : "GetPaymentModal", "where_name" : "return_id", "where_value" : returnId, "where_type" : "return" }, "pos/modal/", function (data) {
				$("#payment_div").html(data);
				GetTotalTransaction();
			});
		});
		
	</script>
<?php } ?>
<script type="text/javascript">
	var return_id = new IDGenerator(10).generate();
	
	$(document).on("keyup",".onchange_quantity_check", function(){
		ReturnFinalCal();
	});
	$(document).on("keyup",".onchange_return_cal", function(){
		ReturnFinalCal();
	});
	
	function ReturnFinalCal(formupdate = false){
		AS.Util.removeErrorMessages();
		var return_subtotal_value = 0;
		var IsStock = false;
		var return_total_vat = 0;
		var sales_return_discount = $('input[name="sales_return_discount"]').val() || 0 ;
		$('input[name="product_price[]"]').each(function() {
			return_subtotal =  parseFloat($(this).val() || 0);
			var return_stock_id =  $(this).data('id');
			var return_product_id =  $(this).data('pid');
			var return_quantity =  $('#return_quantity_'+return_stock_id).val() || 0;
			var return_vat =  $('#product_vat_'+return_stock_id).val() || 0;
			var available_quantity =  $('#available_quantity_'+return_stock_id).val() || 0;
			var already_returned =  $('#already_returned_'+return_stock_id).val() || 0;
			var total_return_quantity =  parseFloat(return_quantity) + parseFloat(already_returned);
			var total_vat_subtotal = total_return_quantity * return_vat;
			var total_subtotal_value = total_return_quantity * return_subtotal;
			
			if(parseFloat(available_quantity) < total_return_quantity){
				AS.Util.ShowErrorByElement('return_quantity_'+return_stock_id, "Invalide Quantity");
				IsStock = false;
				}else{
				IsStock = true;
				$('#return_sub_total_'+return_stock_id).val(total_subtotal_value || 0);
				return_total_vat += parseFloat(total_vat_subtotal || 0);
				return_subtotal_value += parseFloat(total_subtotal_value || 0);
			}
			
			if(formupdate){
				var AvailableQty = parseFloat(available_quantity) - parseFloat(return_quantity);
				$('#already_returned_'+return_stock_id).val(total_return_quantity);
				$('#AlreadyReturnQty_'+return_stock_id).html(total_return_quantity);
				$('#return_quantity_'+return_stock_id).val('');
				$('[name="serial_number['+return_product_id+'][]"]:checked').each(function() {
					$(this).prop("disabled", 'true');
				});
			}
			
		});
		
		if(IsStock){
			var sales_return_total = parseFloat(return_subtotal_value) + parseFloat(return_total_vat);
			var sales_return_total_with_discount = parseFloat(sales_return_total) - parseFloat(sales_return_discount);
			$('input[name="sales_return_subtotal"]').val(return_subtotal_value);
			$('input[name="sales_return_vat"]').val(return_total_vat);
			$('input[name="sales_return_total"]').val(sales_return_total_with_discount);
		}
		
		<?php  if(app('admin')->checkAddon('multiple_payment_method')){ ?>
			GetTotalTransaction();
		<?php } ?>
		
	}
	
	$('.AddSalesReturn form').validate({
		submitHandler: function(form) {
			AS.Http.PostSubmit(form, {"action" : "AddSalesReturn"}, "pos/ajax/", function (response) {
				ReturnFinalCal(true);
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
	
	$(document).on('click','.view_serial_product',function(){
		var Id = $(this).data("id");
		$(".show_modal_"+Id).modal("show");
	});
	
	$(document).on('change','.checkboxQtyUpdate',function(){
		var Id = $(this).data("id");
		var pId = $(this).data("pid");
		$("#return_quantity_"+Id).val($('[name="serial_number['+pId+'][]"]:checked').length);
		ReturnFinalCal();
	});
</script>
[/footer]
<?php include dirname(__FILE__) .'/../../modules/pos/include/footer.php'; ?>														