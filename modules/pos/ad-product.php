<?php defined('_AZ') or die('Restricted access');
	include dirname(__FILE__) .'/include/header.php';
	include dirname(__FILE__) .'/include/side_bar.php';
	include dirname(__FILE__) .'/include/navbar.php';
	
	app('pos')->checkPermission('products_add_product','edit') or redirect("pos/access-denied");
	
	if(isset($this->route['id'])){
		$GetProduct = app('admin')->getwhereid("pos_product","product_id",$this->route['id']);
	}
	$productId = 'PR'.gettoken(8);
?>
[header]
<?php echo getCss('assets/system/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css');?>
<style>
	
	/*.thumbnail img{height: 50px;}*/
</style>

[/header]
<div class="row wrapper  page-heading AddProductForm">
	<div class="row">
		<div class="col-lg-4">
			<h2><?php echo trans('add_new_product'); ?></h2>
			
		</div>
	</div>
	<form>
		<?php if(!isset($this->route['id'])){?><input type="hidden" name="new_product" value="new" /> <?php } ?>
		<div class="row">
			<div class="col-sm-12">
				<div class="ibox">
					<div class="ibox-content">
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<label><?php echo trans('product_name');?>:*</label>
									<input type="text" name="product_name" class="form-control"  placeholder="<?php echo trans('product_name');?>" value="<?php if(isset($this->route['id'])) echo $GetProduct['product_name']; ?>">  
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label><?php echo trans('product_id');?>:(<?php echo trans("changeable");?>)*</label>
									<input type="text" class="form-control <?php if(!isset($this->route['id'])) echo "check_product_id"; ?>" <?php if(isset($this->route['id'])) echo "readonly"; ?> name="product_id" id="product_id" placeholder="<?php echo trans('write_or_scan_barcode');?>" value="<?php if(isset($this->route['id'])){ echo $GetProduct['product_id']; }else{ echo $productId; } ?>">
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label><?php echo trans('product_unit');?>:*</label>
									<div class="input-group">
										<select name="product_unit" id="product_unit" class="form-control select2 view_product_unit_name">
											<option value=""><?php echo trans('select_unit');?></option>
											<?php $getUnits = app('admin')->getall("pos_unit"); 
												foreach($getUnits as $getUnit){
												?>
												<option value="<?php echo $getUnit['unit_id']; ?>" <?php if(isset($this->route['id'])){ if($GetProduct['unit_id'] == $getUnit['unit_id']) echo "selected"; } ?>><?php echo $getUnit['unit_name']; ?></option>
											<?php } ?>
										</select>
										<span class="input-group-btn">
											<a class="btn btn-default add_unit" title="Add Unite"><i class="fa fa-plus-circle text-primary fa-lg"></i></a>
										</span>
									</div>
									
								</div>
							</div>
							<div class="clearfix"></div>
							<div class="col-sm-4 ">
								<div class="form-group">
									<label><?php echo trans('product_category');?>:*</label>
									<div class="input-group">
										<select name="product_category" id="product_category" class="form-control select2">
											<option value=""><?php echo trans('select_category');?></option>
											<?php $getCategorys = app('admin')->getall("pos_category"); 
												foreach($getCategorys as $getCategory){
												?>
												<option value="<?php echo $getCategory['category_id']; ?>" <?php if(isset($this->route['id'])){ if($GetProduct['category_id'] == $getCategory['category_id']) echo "selected"; } ?>><?php echo $getCategory['category_name']; ?></option>
											<?php } ?>
										</select>
										<span class="input-group-btn">
											<button type="button"  class="btn btn-default add_category"  title="Add Unite" ><i class="fa fa-plus-circle text-primary fa-lg"></i></button>
										</span>
									</div>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label><?php echo trans('product_brand');?>:*</label>
									<div class="input-group">
										<select name="product_brand" id="product_brand" class="form-control select2">
											<option value=""><?php echo trans('select_brand');?></option>
											<?php $getBrands = app('admin')->getall("pos_brands"); 
												foreach($getBrands as $getBrand){
												?>
												<option value="<?php echo $getBrand['brand_id']; ?>" <?php if(isset($this->route['id'])){ if($GetProduct['brand_id'] == $getBrand['brand_id']) echo "selected"; } ?>><?php echo $getBrand['brand_name']; ?></option>
											<?php } ?>
										</select>
										<span class="input-group-btn">
											<button type="button" class="btn btn-default add_new_brand"><i class="fa fa-plus-circle text-primary fa-lg"></i></button>
										</span>
									</div>
								</div>
							</div>
							
							<div class="clearfix"></div>
							<div class="col-sm-4">
								<div class="form-group">
									<label><?php echo trans('product_image');?>:*</label>
									<input type="file" class="form-control-file" accept="image/*;capture=camera" name="product_image" accept="image/*">
									<small><p class="help-block"> <?php echo trans('max_file_size_1mb');?> <br><?php echo trans('aspect_ratio_should_be_1:1');?></p></small>
									<?php if(isset($this->route['id'])){ ?>
										<?php 
											$imageLocation = "images/stores/".$_SERVER['SERVER_NAME'].'/'.$GetProduct['product_image'];
											if(!empty($GetProduct['product_image']) && file_exists($imageLocation)){
												$imageLocation = "images/stores/".$_SERVER['SERVER_NAME'].'/'.$GetProduct['product_image'];
												}else{
												$imageLocation = "assets/img/no-image.png";
											}
										?>
										<div class="thumbnail col-sm-2">
											<img src="<?php echo $imageLocation; ?>" alt="<?php echo $GetProduct['product_name']; ?>">
										</div>
									<?php } ?>
								</div>
							</div>
							
							<div class="col-sm-2">
								<div class="form-group">
									<br>
									<label>
										<input type="checkbox" class="form-check-input" id="enable_stock" name="enable_stock" <?php if(isset($this->route['id'])){ if(!empty($GetProduct['alert_quantity'])) echo "checked"; } ?>> <strong><?php echo trans('stock_alert');?></strong>
									</label> <p class="help-block"><i><?php echo trans('stock_alert');?></i></p>
								</div>
							</div>
							<div class="col-sm-2" id="alert_quantity_div">
								<div class="form-group">
									<label><?php echo trans('alert_quantity');?> :*</label>
									<div class="input-group m-b">
										<input type="text" class="form-control" name="product_alert" placeholder="<?php echo trans('write_quantity');?>" value="<?php if(isset($this->route['id'])) echo $GetProduct['alert_quantity']; ?>">
										<span class="input-group-addon"><span class="product_unit_show">

										<?php 
										if(isset($this->route['id'])){ 
											echo app("db")->table('pos_unit')->where("unit_id",$GetProduct['unit_id'])->get('unit_name');
										} ?> </span></span>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-12">
				<div class="ibox">
					<div class="ibox-content">
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<label for="type"><?php echo trans('product_type');?>:*</label> 
									<select class="form-control"  <?php if(isset($this->route['id'])) echo "readonly"; ?> id="variation_type" name="product_variation_type" >
										<?php if(isset($this->route['id'])){ if($GetProduct['product_type'] == "single"){ echo '<option value="single" >Single</option>'; }else{ echo '<option value="variable">Variable</option>'; } }else{ ?>
											<option value="single" selected>Single</option>
											<option value="variable">Variable</option>
										<?php } ?>
									</select> 	
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label><?php echo trans('vat_type'); ?></label>
									<select class="form-control" id="vat_type" name="product_vat_type">
										<option value="percent" <?php if(isset($this->route['id'])){ if($GetProduct['product_vat_type'] == 'percent') echo "selected"; }?>>Vat in Percentage</option>
										<option value="fixed" <?php if(isset($this->route['id'])){ if($GetProduct['product_vat_type'] == 'fixed') echo "selected"; }?>>Vat in TK</option>
									</select>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label><?php echo trans('vat');?> <span id="vat_label">(<?php if(isset($this->route['id'])){ if($GetProduct['product_vat_type']=='fixed'){ echo "TK";}else{ echo "%"; } }else { echo "%"; } ?>)</span> :</label>
									<input class="form-control" placeholder="<?php echo trans('vat_amount');?>" name="product_vat" type="text" value="<?php if(isset($this->route['id'])){ echo $GetProduct['product_vat']; } ?>">
								</div>
							</div>
							<?php 
								if(app('admin')->checkAddon('serial_product')){
							?>
							<div class="col-sm-2">
								<div class="form-group">
									<label class="">
										<input type="checkbox" class="" name="product_serial_num" <?php if(isset($GetProduct)){ if($GetProduct['product_serial'] == "enable") echo "checked"; } ?>>
										<strong><?php echo trans('enable_imie_or_serial_number');?></strong>
									</label>
								</div>
							</div>
							
							<?php } ?>
							<div class="col-sm-2">
								<div class="form-group">
									<label class="">
										<input type="checkbox" class="" name="product_stock_disable" <?php if(isset($this->route['id'])){ if($GetProduct['product_stock'] == "enable") echo "checked"; } ?>>
										<strong><?php echo trans('product_stock_disable');?></strong>
									</label>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="row" id="variations_type_single">
							<div class="col-sm-8 col-sm-offset-2" >
								<br>
								<div class="table-responsive" >
									<table class="table table-bordered add-product-price-table table-condensed bg-green ">
										<thead>
											<tr>
												<th><?php echo trans('default_purchase_price');?></th>
												<th><?php echo trans('profit_margine');?>(%)</th>
												<th><?php echo trans('default_selling_price_without_vat');?></th>
											</tr>
										</thead>
										<tbody class="text-center">
											
											<?php if(isset($this->route['id'])) $getProductVariationSingle = app('admin')->getwhereid("pos_variations","product_id",$GetProduct['product_id']); ?>
											<tr>
												<td>
													<label for="product_purchase_price"><?php echo trans('purchase_price');?>:*</label>
													<input class="form-control input-sm product_purchase_value single_change_purchase" placeholder="<?php echo trans('purchase_price');?>" value="<?php if(isset($this->route['id'])) echo $getProductVariationSingle['purchase_price']; ?>" name="single_product_purchase_price" type="text">
												</td>
												
												<td>
													<label for="product_profit_margine"><?php echo trans('profit_margine');?>:*</label>
													<input class="form-control input-sm single_change_profit_margin" placeholder="<?php echo trans('profit_margine');?>" value="<?php if(isset($this->route['id'])) echo $getProductVariationSingle['profit_percent']; ?>" name="single_product_profit_margine" type="text">
												</td>
												
												<td>
													<label for="product_sales_price"><?php echo trans('sales_price');?>:*</label>
													<input class="form-control input-sm product_sales_value single_change_profit_sale_margin" placeholder="<?php echo trans('sales_price');?>" value="<?php if(isset($this->route['id'])) echo $getProductVariationSingle['sell_price']; ?>" name="single_product_sales_price" type="text">
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="row" id="variations_type_variable">
							<div class="col-sm-8 col-sm-offset-2" >
								<div class="table-responsive" >
									<table class="table table-bordered add-product-price-table table-condensed bg-green">
										<thead>
											<tr>
												<th class="col-sm-2"><?php echo trans('variation_name');?></th>
												<th class="col-sm-10"><?php echo trans('variation_value');?></th>
											</tr>
										</thead>
										<tbody>
											
											<tr class="variation_row">
												<td>
													<select class="form-control select-sm select_change_variation" id="select_variation" name="select_variation">
														<option value=""><?php echo trans('select_variation');?></option>
														<?php $getVariations = app('admin')->getall("pos_variations_category"); 
															foreach($getVariations as $getVariation){
															?>
															<option value="<?php echo $getVariation['variation_category_id']; ?>" <?php if(isset($this->route['id'])){ if($GetProduct['variation_category_id'] == $getVariation['variation_category_id']) echo "selected"; } ?>><?php echo $getVariation['variation_category_name']; ?></option>
														<?php } ?>
													</select>
												</td>
												
												<td>
													<table class="table table-condensed table-bordered blue-header" id="variation_value_table">
														<thead>
															<tr>
																<th><?php echo trans('sub_product_id');?></th>
																<th><?php echo trans('variation_value'); ?></th>
																<th><?php echo trans('default_purchase_price'); ?></th>
																<th><?php echo trans('x_margin_percent'); ?></th>
																<th><?php echo trans('default_sell_price'); ?></th>
																<th>
																	<a href="javascript:void(0);" class="btn btn-success btn-xs added_new_variation">+</a>
																</th>
															</tr>
														</thead>
														<tbody>
															<?php if(isset($this->route['id'])){ 
																
																$getProductVariations = app('admin')->getwhere("pos_variations","product_id",$GetProduct['product_id']); 
																foreach($getProductVariations as $getProductVariation){
																?>
																<tr><td><input type="text" name="sub_product_id[]" <?php if(isset($this->route['id'])) echo "readonly"; ?> class="form-control input-sm" placeholder="undefined" value="<?php echo $getProductVariation['sub_product_id']; ?>"></td>
																	<td><input type="text" name="product_variation_value[]" <?php if(isset($this->route['id'])) echo "readonly"; ?> class="form-control input-sm" placeholder="undefined" value="<?php echo $getProductVariation['variation_name']; ?>"></td>
																	<td><input type="text" name="product_purchase_price[]" data-id="<?php echo $getProductVariation['variation_name']; ?>" id="product_purchase_price_<?php echo $getProductVariation['variation_name']; ?>" class="form-control input-sm variation_change_purchase" placeholder="Purchase Price" required="" value="<?php echo $getProductVariation['purchase_price']; ?>"></td>
																	<td><input type="text" name="product_profit_margine[]" data-id="<?php echo $getProductVariation['variation_name']; ?>" id="product_profit_margine_<?php echo $getProductVariation['variation_name']; ?>" class="form-control input-sm variation_change_profit_margin" placeholder="Profit Margine" value="<?php echo $getProductVariation['profit_percent']; ?>"></td>
																	<td><input type="text" name="product_sales_price[]" data-id="<?php echo $getProductVariation['variation_name']; ?>" id="product_sales_price_<?php echo $getProductVariation['variation_name']; ?>" class="form-control input-sm variable_change_profit_sale_margin" placeholder="Sales Price" required="" value="<?php echo $getProductVariation['sell_price']; ?>"></td>
																	<td><a href="javascript:void(0);" class="btn btn-danger btn-xs remove_variation_rows">-</a></td>
																</tr>
															<?php } } ?>
														</tbody>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="ibox">
					<div class="ibox-content">
						<div class="row">
							<div class="col-sm-12">
								<div class="text-center">
									<div class="btn-group">
										<button type="submit" value="submit" class="btn btn-primary" data-loading-text="Loading Now"><?php echo trans('save');?></button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
	<div class="modal_status"></div>
</div>
[footer]	
<script>
	$(document).on('click','.add_unit',function(){
		$(".show_modal").remove();
		AS.Http.posthtml({"action" : "GetNewUnitModal",select_id : "product_unit"}, "pos/modal/", function (data) {
			$(".modal_status").html(data);
			$(".show_modal").modal("show");
		});
	});
	
	$(document).on('click','.add_category',function(){
		$(".show_modal").remove();
		AS.Http.posthtml({"action" : "GetNewCategoryModal",select_id : "product_category"}, "pos/modal/", function (data) {
			$(".modal_status").html(data);
			$(".show_modal").modal("show");
		});
	});
	
	$(document).on("keyup",".check_product_id", function(){
		AS.Util.removeErrorMessages();
		var ProductId = $("#product_id").val();
		AS.Http.post({"action" : "CheckProductId","product_id" : ProductId}, "pos/ajax/", function (response) {
			AS.Util.ShowErrorByElement('product_id', response.message);
		});
	});
	
	function NewProductIdGenerate(){
		AS.Http.post({"action" : "NewProductIdGenerate"}, "pos/ajax/", function (response) {
			$("#product_id").val(response.product_id);
		});
	}
	
	$("#vat_type").change(function(){
		var type=$("#vat_type").val();
		if(type=='percent'){$('#vat_label').html('(%)')}else{$('#vat_label').html('(Tk)')}
	});
	
	$('.AddProductForm form').validate({
		rules: {
			product_name: {
				required: true
			},
			product_unit: {
				required: true
			},
			product_id: {
				required: true
			},
			product_sales_price: {
				required: true
			},
			single_product_purchase_price: {
				required: true
			},
			single_product_sales_price: {
				required: true
			}
		},	
		submitHandler: function(form) {
			AS.Http.PostSubmit(form, {"action" : "AddProductData"}, "pos/ajax/", function (response) {
				<?php if(!isset($this->route['id'])){ ?>
					$("#variations_type_variable").hide();
					$("#variations_type_single").show();
					form.reset();
					NewProductIdGenerate();
				<?php } ?>
				if(response.status=='success'){
					swal({
						title: $_lang.success, 
						text: response.message, 
						type: "success",
						showCancelButton: false,
						confirmButtonColor: "#1ab394",
						confirmButtonText: $_lang.ok
						},
						function (isConfirm) {
							if (isConfirm) {
									location.href='pos/product-list';
								}
					});
					
				}
			});
		}
	});
	
	$(document).on("click",".add_new_brand", function(){
		$(".show_modal").remove();
		AS.Http.posthtml({"action" : "GetNewBrandModal",select_id : "product_brand"}, "pos/modal/", function (data) {
			$(".modal_status").html(data);
			$(".show_modal").modal("show");
		});
	});
	
	function removeVariationRow(OnButton){
		var variationTable = document.getElementById('variation_value_table');
		variationTable.deleteRow(OnButton.parentNode.parentNode.rowIndex);
	}
	
	$(document).on("change",".select_change_variation", function(){
		var SelectVariation = $("#select_variation").val();
		var ProductId = $("#product_id").val();
		AS.Http.post({"action" : "GetVariationData","select_variation" : SelectVariation}, "pos/ajax/", function (result) {
			var values = result.values.split(',')
			var i = 1;
			for (var k = 0; k < values.length; k++) {
				if(ProductId){
					CreateVariationRows(ProductId+'-',values[k],"","","",values[k]);
					}else{
					CreateVariationRows("",values[k],"","","",values[k]);
				}
				i++;
			}
		});
		
	});
	
	
	$(document).on("keyup",".variation_change_purchase", function(){
		var Id = $(this).data("id");
		// var row = $(this).data("row");
		var row = $(this).closest("tr").index();
		var margine_profit_value = $("#product_profit_margine_"+Id).val();
		var product_purchase_value = $("#product_purchase_price_"+Id).val();
		var total_percent = margine_profit_value / 100 * product_purchase_value;
		 $("#product_sales_price_"+Id).val(parseFloat(product_purchase_value) + parseFloat(total_percent));
		
		if(row==1){
			$('.variation_change_purchase').each(function(){
			$(this).val(parseFloat(product_purchase_value));
			});
			
			$('.variable_change_profit_sale_margin').each(function(){
				$(this).val(parseFloat(product_purchase_value) + parseFloat(total_percent));
			});
		}
		
	});
	
	$(document).on("keyup",".single_change_purchase", function(){
		var margine_profit_value = $(".single_change_profit_margin").val();
		var product_purchase_value = $(".product_purchase_value").val();
		var total_percent = margine_profit_value / 100 * product_purchase_value;
		$(".product_sales_value").val(parseFloat(product_purchase_value) + parseFloat(total_percent));
	});
	
	$(document).on("keyup",".variation_change_profit_margin", function(){
		var Id = $(this).data("id");
		// var row = $(this).data("row");
		var row = $(this).closest("tr").index();
		var margine_profit_value = $("#product_profit_margine_"+Id).val();
		var product_purchase_value = $("#product_purchase_price_"+Id).val();
		var total_percent = margine_profit_value / 100 * product_purchase_value;
		$("#product_sales_price_"+Id).val(parseFloat(product_purchase_value) + parseFloat(total_percent));
		
		if(row==1){
			$('.variation_change_profit_margin').each(function(){
				$(this).val(parseFloat(margine_profit_value));
			});
			
			$('.variable_change_profit_sale_margin').each(function(){
				$(this).val(parseFloat(product_purchase_value) + parseFloat(total_percent));
			});
		}
	});
	
	$(document).on("keyup",".single_change_profit_margin", function(){
		var margine_profit_value = $(".single_change_profit_margin").val();
		var product_purchase_value = $(".product_purchase_value").val();
		var total_percent = margine_profit_value / 100 * product_purchase_value;
		var total_sales_value = parseFloat(product_purchase_value) + parseFloat(total_percent);
		$(".product_sales_value").val(parseFloat(total_sales_value).toFixed(2));
	});
	
	$(document).on("click",".added_new_variation", function(){
		var ProductId = $("#product_id").val();
		if(ProductId){
			CreateVariationRows(ProductId+'-',"","","","","");
			}else{
			CreateVariationRows("","","","","","");
		}
	});	
	
	$(document).on("keyup",".single_change_profit_sale_margin", function(){
		var product_sales_value = $(".product_sales_value").val();
		var product_purchase_value = $(".product_purchase_value").val();
		var total_profit_amount = parseFloat(product_sales_value) - parseFloat(product_purchase_value);
		
		var total_percent = total_profit_amount / product_purchase_value * 100;
		$(".single_change_profit_margin").val(parseFloat(total_percent).toFixed(2));
	});
	
	$(document).on("keyup",".variable_change_profit_sale_margin", function(){
		var Id = $(this).data("id");
		// var row = $(this).data("row");
		var row = $(this).closest("tr").index();
		var product_sales_value = $("#product_sales_price_"+Id).val();
		var product_purchase_value = $("#product_purchase_price_"+Id).val();
		var total_profit_amount = parseFloat(product_sales_value) - parseFloat(product_purchase_value);
		var total_percent = total_profit_amount / product_purchase_value * 100;
		$("#product_profit_margine_"+Id).val(parseFloat(total_percent).toFixed(2));
		
		if(row==1){
		
			$('.variable_change_profit_sale_margin').each(function(){
				$(this).val(parseFloat(product_sales_value));
			});
			
			
			$('.variation_change_profit_margin').each(function(){
				
					$(this).val(parseFloat(total_percent).toFixed(2));
			
				
			});
		}
		
		
		
	});
	
	function CreateVariationRows(sub_product_id,product_variation_value,product_purchase_price,product_profit_margine,product_sales_price,uid){
		var variationTable = document.getElementById('variation_value_table');
		var rowCnt = variationTable.rows.length; 
		var tr = variationTable.insertRow(rowCnt); 
		var i = uid || new Date().getTime();
		var first_row='';
		if(rowCnt==1){
			first_row = 1;
		}
		else{
			first_row=rowCnt;
		}
		for (var c = 0; c <= 5; c++) {
			var td = document.createElement('td');      
			td = tr.insertCell(c);
			if(c==0){
				var ele = document.createElement('input');
				ele.setAttribute('type', 'text');
				ele.setAttribute('value', sub_product_id+rowCnt);
				ele.setAttribute('name', 'sub_product_id[]');
				ele.setAttribute('class', 'form-control input-sm');
				ele.setAttribute('data-row', first_row);
				ele.setAttribute('placeholder', $_lang.sub_product_id);
				td.appendChild(ele);
				}else if(c==1){
				var ele = document.createElement('input');
				ele.setAttribute('type', 'text');
				ele.setAttribute('value', product_variation_value);
				ele.setAttribute('name', 'product_variation_value[]');
				ele.setAttribute('class', 'form-control input-sm');
				ele.setAttribute('data-row', first_row);
				ele.setAttribute('placeholder', $_lang.variation_value);
				td.appendChild(ele);
				}else if(c==2){
				var ele = document.createElement('input');
				ele.setAttribute('type', 'text');
				ele.setAttribute('value', product_purchase_price);
				ele.setAttribute('name', 'product_purchase_price[]');
				ele.setAttribute('id', "product_purchase_price_"+i);
				ele.setAttribute('data-id', i);
				ele.setAttribute('class', 'form-control input-sm variation_change_purchase');
				ele.setAttribute('placeholder', $_lang.purchase_price);
				ele.setAttribute('data-row', first_row);
				ele.setAttribute('required', "");
				td.appendChild(ele);
				}else if(c==3){
				var ele = document.createElement('input');
				ele.setAttribute('type', 'text');
				ele.setAttribute('value', product_profit_margine);
				ele.setAttribute('name', 'product_profit_margine[]');
				ele.setAttribute('data-id', i);
				ele.setAttribute('id', "product_profit_margine_"+i);
				ele.setAttribute('class', 'form-control input-sm variation_change_profit_margin');
				ele.setAttribute('data-row', first_row);
				ele.setAttribute('placeholder', $_lang.profit_margine);
				td.appendChild(ele);
				}else if(c==4){
				var ele = document.createElement('input');
				ele.setAttribute('type', 'text');
				ele.setAttribute('value', product_sales_price);
				ele.setAttribute('name', 'product_sales_price[]');
				ele.setAttribute('data-id', i);
				ele.setAttribute('id', "product_sales_price_"+i);
				ele.setAttribute('class', 'form-control input-sm variable_change_profit_sale_margin');
				ele.setAttribute('data-row', first_row);
				ele.setAttribute('placeholder', $_lang.sales_price);
				ele.setAttribute('required', "");
				td.appendChild(ele);
				}else if(c==5){
				var ele = document.createElement('a');
				ele.setAttribute('href', 'javascript:void(0);');
				ele.setAttribute('onclick', 'removeVariationRow(this);');
				ele.setAttribute('class', 'btn btn-danger btn-xs remove_variation_rows');
				ele.setAttribute('data-row', first_row);
				ele.append("-");
				td.appendChild(ele);
			}
		}
		
	}
	
	
	$(document).ready(function(){
		<?php if(isset($this->route['id'])){ if(empty($GetProduct['alert_quantity'])) echo ' $("#alert_quantity_div").hide();'; }else{ echo ' $("#alert_quantity_div").hide();'; } ?>
		<?php if(isset($this->route['id'])){ if($GetProduct['product_type'] == "single"){ 
			echo '$("#variations_type_single").show(); $("#variations_type_variable").hide();'; 
			}else{
			echo '$("#variations_type_single").hide(); $("#variations_type_variable").show();'; 
		}
		}else{ 
		echo '$("#variations_type_single").show(); $("#variations_type_variable").hide();'; 
		} ?>
		
		$('#enable_stock').change(function(ev) {
			if ( this.checked ) $("#alert_quantity_div").show();
			else $("#alert_quantity_div").hide();;
		});
		
		
		$("#variation_type").change(function(){
			if($("#variation_type").val()=='variable'){
				$("#variations_type_variable").show();
				$("#variations_type_single").hide();
			}
			else{
				$("#variations_type_variable").hide();
				$("#variations_type_single").show();
			}
		});
		
	});
	
	$(document).on("change",".view_product_unit_name", function(){
		var product_unit = $("#product_unit").val();
		AS.Http.post({"action" : "GetProductUnitView","product_unit" : product_unit}, "pos/ajax/", function (result) {
			$(".product_unit_show").html(result.product_unit_name);
		});
	});
	
</script>
<?php getJs('assets/system/js/plugins/iCheck/icheck.min.js');?>
[/footer]
<?php include dirname(__FILE__) .'/include/footer.php'; ?>																																														