<?php defined('_AZ') or die('Restricted access');
	
	switch ($action) {
		
	case "GetPurchaseSerialShowModal": ?>
	
	<div class="modal fade show_modal" tabindex="-1" role="dialog" >
		
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
										<th><?php echo trans('serial'); ?></th>
										<th><?php echo trans('action'); ?></th>
									</tr>
								</thead>
								<tbody class="text-center">
									<?php 
										for ($i=0; $i < count($_POST['serials']) ; $i++) { ?>
										<tr>
											<td><?php echo $_POST['serials'][$i]; ?></td>
											<td><a href="javascript:void(0);" class="remove_serial" array_key="<?php echo $i; ?>" product_code="<?php echo $_POST['product_code']; ?>" array_value="<?php echo $_POST['serials'][$i]; ?>" ><i class="fa fa-trash"></i></a></td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>									
					</div>						
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default no-print" data-dismiss="modal"><?php echo trans('cancel'); ?></button>
				</div>
			</div>
		</div>
	</div>
	
	<script type="text/javascript">
		$(document).on("click",".remove_serial", function(){
			$(".show_modal").remove();
			var array_key = $(this).attr('array_key');
			var array_value = $(this).attr('array_value');
			var product_code = $(this).attr('product_code');
			swal( {
				title: $_lang.are_you_sure, 
				text: array_value, 
				type: "warning", 
				showCancelButton: true, 
				confirmButtonColor: "#DD6B55", 
				confirmButtonText: $_lang.yes, 
				cancelButtonText: $_lang.no, 
				closeOnConfirm: false, 
				closeOnCancel: true
				},function (isConfirm) {
				if (isConfirm) {
					var element = document.getElementById(product_code+array_value);
					element.parentNode.removeChild(element);
					var purchase_product_quantity = $("#purchase_quantity_"+product_code).val();
					var sales_product_quantity = $("#product_quantity_"+product_code).val();
					if(purchase_product_quantity){
						
						var product_quantity = $("#purchase_quantity_"+product_code).val() || 0;
						var new_quantiry =  parseInt(product_quantity) - parseInt('1');
						$("#purchase_quantity_"+product_code).val(new_quantiry);
						AddPurchaseRowExtraLoad(product_code);
						
						}else if(sales_product_quantity){
						var product_quantity = $("#product_quantity_"+product_code).val() || 0;
						var new_quantiry =  parseInt(product_quantity) - parseInt('1');
						$("#product_quantity_"+product_code).val(new_quantiry);
						AddSalesRowExtraLoad(product_code);
					}
					
					swal({
						customClass: "confirmed",
						title: $_lang.deleted, 
						type: "success",
						confirmButtonColor: "#1ab394", 
						confirmButtonText: $_lang.ok,
					});
				}
			});
			$(".modal-backdrop").remove();
		});
		
	</script>
	
	<?php break;
		case "GetPurchaseReturnSerialShowModal": 
		$purchase__serial_product = app('db')->table('pos_product_serial')->where('sub_product_id',$_POST['sub_product_code'])->where('purchase_id',$_POST['product_code'])->where('product_serial_status','!=','sell')->where('is_delete',"false")->get();
		
	?>
	
	<div class="modal fade show_modal_<?php echo $_POST['sub_product_code']; ?>" tabindex="-1" role="dialog" >
		
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
										for ($i=0; $i < count($purchase__serial_product) ; $i++) { 
											$CheckTransferStatus = app('db')->table('pos_product_serial')->where('id',$purchase__serial_product[$i]['id'])->where('product_serial_category','purchase_return')->where('is_delete',"false")->get(1);
										?>
										<tr>
											<td>
												<input type="checkbox" name="serial_number[<?php echo $_POST['sub_product_code']; ?>]" value="<?php echo $purchase__serial_product[$i]['product_serial_no'];?>" <?php if($CheckTransferStatus) echo 'checked'; ?>>
											</td>
											<td><?php echo $purchase__serial_product[$i]['product_serial_no']; ?></td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>									
					</div>						
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default no-print" data-dismiss="modal"><?php echo trans('cancel'); ?></button>
				</div>
			</div>
		</div>
	</div>
	
	<?php break;
		
		case "GetSalesReturnSerialShowModal": 
		$purchase__serial_product = app('db')->table('pos_product_serial')->where('sub_product_id',$_POST['sub_product_code'])->where('sales_id',$_POST['product_code'])->where('is_delete',"false")->get();
		
	?>
	
	<div class="modal fade show_modal" tabindex="-1" role="dialog" >
		
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
										for ($i=0; $i < count($purchase__serial_product) ; $i++) { 
											
										?>
										<tr>
											<td>
												<input type="checkbox" name="serial_number[]" value="<?php echo $purchase__serial_product[$i]['product_serial_no'];?>">
												
											</td>
											<td><?php echo $purchase__serial_product[$i]['product_serial_no']; ?></td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>									
					</div>						
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default no-print" data-dismiss="modal"><?php echo trans('cancel'); ?></button>
				</div>
			</div>
		</div>
	</div>
	
	<?php break;
		
		case "GetSerialShowModal":
		if (isset($_POST['product_id'])) {
			$getProductSerials = app('admin')->getwhere("pos_product_serial","product_id",$_POST['product_id']);
		?>
		
		<div class="modal fade show_modal" >
			
			<div class="modal-dialog full-width-modal-dialog" >
				<div class="modal-content full-width-modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h2 class="modal-title text-center" id="modalTitle"> <?php echo trans('serial_details').' - '.$_POST['product_id'];?> </h2>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-sm-12">
								<table class="table table-bordered" >
									<thead>
										<tr>
											<th><?php echo trans('purchase_id'); ?></th>
											<th><?php echo trans('serial_number'); ?></th>
											<th><?php echo trans('serial_category'); ?></th>
											<th><?php echo trans('serial_status'); ?></th>
											<th><?php echo trans('stock_type'); ?></th>
											<th><?php echo trans('created_at'); ?></th>
											<th><?php echo trans('sold_at'); ?></th>
										</tr>
									</thead>
									<tbody class="text-center">
										<?php 
											for ($i=0; $i < count($getProductSerials) ; $i++) { ?>
											<tr>
												<td><?php echo $getProductSerials[$i]['product_id']; ?></td>
												<td><?php echo $getProductSerials[$i]['product_serial_no']; ?></td>
												<td><?php echo $getProductSerials[$i]['product_serial_category']; ?></td>
												<td><?php echo $getProductSerials[$i]['product_serial_status']; ?></td>
												<td><?php echo $getProductSerials[$i]['product_serial_stock_type']; ?></td>
												<td><?php echo $getProductSerials[$i]['created_at']; ?></td>
												<td><?php echo $getProductSerials[$i]['sold_at']; ?></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>									
						</div>						
						
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default no-print" data-dismiss="modal"><?php echo trans('cancel'); ?></button>
					</div>
				</div>
			</div>
		</div>
		
		<?php } 
		break;
		
		case 'GetSerialModal';
		$GetAllSerial=app('admin')->getwhere('pos_product_serial','product_id',$_POST['product_code']);
	?>
	<div class="modal inmodal show_modal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12">
							<form>
								<table class="table table-bordered" >
									<thead>
										<tr>
											<th>#</th>
											<th><?php echo trans('serial'); ?></th>
										</tr>
									</thead>
									<tbody class="text-center">
										<?php foreach($GetAllSerial as $serial){
											if($serial['product_serial_status']=='received'){
											?>
											<tr>
												<td>
													<input type="checkbox" name="serial_number[]" value="<?php echo $serial['product_serial_no'];?>">
												</td>
												<td><?php
													
													echo $serial['product_serial_no'];
													
												?>
												</td>
											</tr>
										<?php } }?>
									</tbody>
								</table>
								
								
								<button class="btn btn-sm btn-primary pull-right m-t-n-xs add_serial" type="button"><strong><?php echo trans('submit'); ?></strong></button>
								<button class="btn btn-sm btn-danger pull-right m-t-n-xs close_add" data-dismiss="modal" aria-label="Close" type="button" ><strong><?php echo trans('cancel'); ?></strong></button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		
		$('.add_serial').click(function(){
			$('input[name="serial_number[]"]').each(function() {
				if($(this).prop('checked')){
					AddSalesProductList("<?php echo $_POST['product_code'];?>",$(this).val());
				}
			});
			$(".show_modal").modal("hide");
		});
	</script>
	<?php
		break;
		case 'GetMultiSerialModal';
		$GetAllSerial = app('admin')->getwhere('pos_product_serial','product_serial_no',$_POST['product_code']);
		
	?>
	<div class="modal inmodal show_modal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12">
							<form>
								<table class="table table-bordered" >
									<thead>
										<tr>
											<th>#</th>
											<th><?php echo trans('serial'); ?></th>
										</tr>
										
										
										
									</thead>
									<tbody class="text-center">
										<?php foreach($GetAllSerial as $serial){
											if($serial['product_serial_status']=='received'){
												$GetProduct = app('admin')->getwhereid('pos_product','product_id',$serial['sub_product_id']);
											?>
											<tr>
												<td colspan="2"><?php echo $GetProduct['product_name'];?></td>
											</tr>
											<tr>
												<td>
													<input type="checkbox" name="serial_number[]" value="<?php echo $serial['sub_product_id'];?>" class="form-control">
												</td>
												<td>
													<?php echo $serial['product_serial_no']; ?>
												</td>
											</tr>
										<?php }}?>
									</tbody>
								</table>
								
								
								<button class="btn btn-sm btn-primary pull-right m-t-n-xs add_serial" type="button"><strong><?php echo trans('submit'); ?></strong></button>
								<button class="btn btn-sm btn-danger pull-right m-t-n-xs close_add" data-dismiss="modal" aria-label="Close" type="button" ><strong><?php echo trans('cancel'); ?></strong></button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		
		$('.add_serial').click(function(){
			var arr = [];
			var sl = $('input[name="serial_number[]"]');
			sl.each(function(){
				if($(this).prop('checked')){
					AddSalesProductList($(this).val(),<?php echo $_POST['product_code']; ?>);
				}
			});
			$(".show_modal").modal("hide");
			
			
		});
	</script>
	<?php
		break;
		
		case "GetNewBrandModal":
	?>
	<div class="modal inmodal show_modal" tabindex="-1" role="dialog"  aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content animated fadeIn AddNewBrand">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title"><?php echo trans('add_brand');?></h4>
				</div>
				<form>
					<?php if(isset($_POST['brand_id'])) echo '<input type="hidden" name="brand_id" value="'.$_POST['brand_id'].'">';?>
					<div class="modal-body">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-3">
									<label><?php echo trans('brand_name');?></label>
								</div>
								<div class="col-sm-9">
									<input type="text" class="form-control" id="brand_name" name="brand_name">
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary"><?php echo trans('save');?></button>
						<button type="button" class="btn btn-white" data-dismiss="modal"><?php echo trans('close');?></button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<script>
		$('.AddNewBrand form').validate({
			rules: {},  
			submitHandler: function(form) {
				AS.Http.PostSubmit(form, {"action" : "AddNewBrandSubmit"}, "pos/ajax/", function (response) {
					if(response.status=='success'){
						<?php if(isset($_POST['select_id'])){ ?>
							if(response.update_type == "new"){
								var option = document.createElement("option");
								option.text = response.brand_name;
								option.value = response.brand_id;
								var select = document.getElementById("<?php echo $_POST['select_id']; ?>");
								select.appendChild(option);
							}
							document.getElementById('<?php echo $_POST['select_id']; ?>').value = response.brand_id;
						<?php } ?>
						swal({
							title: $_lang.success, 
							// text: response.message, 
							text: '', 
							type: "success",
							confirmButtonColor: "#1ab394", 
							confirmButtonText: $_lang.ok,
							},function(isConfirm){
							$(".show_modal").modal("hide");
						});
					}
				});
			}
		});
	</script>
	<?php break;
		
	case "GetNewCategoryModal": ?>
	<div class="modal inmodal show_modal" tabindex="-1" role="dialog"  aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content animated fadeIn AddNewCategory">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title"><?php echo trans('add_category');?></h4>
					
				</div>
				<form>
					<?php if(isset($_POST['category_id'])) echo '<input type="hidden" name="category_id" value="'.$_POST['category_id'].'">';?>
					<div class="modal-body">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-3">
									<label><?php echo trans('category_name');?></label>
								</div>
								<div class="col-sm-9">
									<input type="text" class="form-control" name="category_name" id="category_name">
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary" ><?php echo trans('save');?></button>
						<button type="button" class="btn btn-white" data-dismiss="modal"><?php echo trans('close');?></button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<script>
		$('.AddNewCategory form').validate({
			rules: {
				category_name: {
					required: true
				},
				
			},  
			submitHandler: function(form) {
				AS.Http.PostSubmit(form, {"action" : "AddNewCategorySubmit"}, "pos/ajax/", function (response) {
					if(response.status=='success'){
						<?php if(isset($_POST['select_id'])){ ?>
							if(response.update_type == "new"){
								var option = document.createElement("option");
								option.text = response.category_name;
								option.value = response.category_id;
								var select = document.getElementById("<?php echo $_POST['select_id']; ?>");
								select.appendChild(option);
							}
							document.getElementById('<?php echo $_POST['select_id']; ?>').value = response.category_id;
						<?php } ?>
						swal({
							title: $_lang.success,
							// text: response.message,							
							text: '', 
							type: "success",
							confirmButtonColor: "#1ab394", 
							confirmButtonText: $_lang.ok,
							},function(isConfirm){
							$(".show_modal").modal("hide");
						});
					}
				});
			}
		});
	</script>
	<?php
		break;
		
		case "GetNewUnitModal": 
	?>
	<div class="modal inmodal show_modal" tabindex="-1" role="dialog"  aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content animated fadeIn AddNewUnit">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title"><?php echo trans('add_unit');?></h4>
					
				</div>
				<form>
					<?php if(isset($_POST['unit_id'])) echo '<input type="hidden" name="unit_id" value="'.$_POST['unit_id'].'">';?>
					<div class="modal-body">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-3">
									<label><?php echo trans('unit_name');?></label>
								</div>
								<div class="col-sm-9">
									<input type="text" class="form-control" name="unit_name" id="unit_name">
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary" ><?php echo trans('save');?></button>
						<button type="button" class="btn btn-white" data-dismiss="modal"><?php echo trans('close');?></button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<script>
		$('.AddNewUnit form').validate({
			rules: {
				unit_name: {
					required: true
				},
				
			},  
			submitHandler: function(form) {
				AS.Http.PostSubmit(form, {"action" : "AddNewUnitSubmit"}, "pos/ajax/", function (response) {
					if(response.status=='success'){
						<?php if(isset($_POST['select_id'])){ ?>
							if(response.update_type == "new"){
								var option = document.createElement("option");
								option.text = response.unit_name;
								option.value = response.unit_id;
								var select = document.getElementById("<?php echo $_POST['select_id']; ?>");
								select.appendChild(option);
								$(".product_unit_show").html(response.unit_name);
							}
							document.getElementById('<?php echo $_POST['select_id']; ?>').value = response.unit_id;
						<?php } ?>
						swal({
							title: $_lang.success, 
							text: '', 
							// text: response.message,
							type: "success",
							confirmButtonColor: "#1ab394", 
							confirmButtonText: $_lang.ok,
							},function(isConfirm){
							$(".show_modal").modal("hide");
						});
					}
				});
			}
		});
	</script>
	<?php
		break;
		
		case 'GetProductBarcode': 
	?>
	<div class="modal inmodal show_modal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12 barcode_print">
							<form>
								<h3 class="m-t-none m-b"><?php echo trans('number_of_barcode'); ?></h3>
								
								<?php if(isset($_POST['product_id'])) echo '<input type="hidden" name="product_id" id="product_id" value="'.$_POST['product_id'].'">';?>
								<?php if(isset($_POST['product_name'])) echo '<input type="hidden" name="product_name" id="product_name" value="'.$_POST['product_name'].'">';?>
								<?php if(isset($_POST['product_price'])) echo '<input type="hidden" name="product_price" id="product_price" value="'.$_POST['product_price'].'">';?>
								
								<div class="form-group">
									<input type="text" class="form-control" name="number_of_barcode" id="barcode_number" placeholder="<?php echo trans('enter_number_of_barcode'); ?>">
								</div>
								
								<button class="btn btn-sm btn-primary pull-right m-t-n-xs barcode_download modal_close" type="submit"><strong><?php echo trans('download'); ?></strong></button>
								<a class="btn btn-sm btn-danger pull-right m-t-n-xs modal_close" data-dismiss="modal" aria-label="Close"><strong><?php echo trans('cancel'); ?></strong></a>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<script>
		$('.barcode_print form').validate({
			rules: {
				product_id: {
					required: true
				},
				number_of_barcode: {
					required: true,
					number: true
				}
			},  
			submitHandler: function(form) {
				var barcode_number= $("#barcode_number").val();
				var price= $("#product_price").val();
				var name= $("#product_name").val();
				var pdt_id= $("#product_id").val();
				
				AS.Http.posthtml({"action" : "GetBarcodePrint","number_of_barcode" : barcode_number,"product_name": name, "product_price": price,"product_id" : pdt_id}, "pos/ajax/", function (data) {
					
					$("#barcode_view").html(data);	
					$.print("#barcode-print");					
				});
			}
		}); 
		
		$(document).on("click",".modal_close", function(){
			$('.show_modal').modal('hide'); 
			$(".modal-backdrop").remove();
		});
	</script>
	<?php 
		break;
		
		case 'GetProductView':
		if(isset($_POST['product_id'])){
			$product = app('admin')->getwhereid('pos_product','product_id',$_POST['product_id']);
			if (empty($product['product_vat'])) { $product_vat = 0; } else { $product_vat = $product['product_vat']; }
			$category = app('admin')->getwhereid('pos_category','category_id',$product['category_id']);
			$unit = app('admin')->getwhereid('pos_unit','unit_id',$product['unit_id']);
			$brand = app('admin')->getwhereid('pos_brands','brand_id',$product['brand_id']);
			$supplier = app('admin')->getwhereid('pos_contact','contact_id',$product['supplier_id']);
			$getuserdetails = app('admin')->getuserdetails($product['user_id']);
			
			$variations = app('admin')->getwhere('pos_variations','product_id',$_POST['product_id']);
		}
	?>
	<div class="modal inmodal show_modal" tabindex="-1" role="dialog"  aria-hidden="true" id="ProductViewModal">
		
		<div class="modal-dialog full-width-modal-dialog modal-dialog-centered" >
			<div class="modal-content full-width-modal-content">
				<div class="modal-header">
					<button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="modalTitle"> <?php echo trans('product_details').' - '.$product['product_id']; ?> </h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-4">
							<b><?php echo trans('product_name'); ?>:</b>
							<?php echo $product['product_name']; ?><br>
							<b><?php echo trans('brand'); ?>: </b>
							<?php echo $brand['brand_name']; ?><br>
							<b><?php echo trans('unit'); ?>: </b>
							<?php echo $unit['unit_name']; ?><br>
							<b><?php echo trans('product_type'); ?>: </b>
							<?php echo $product['product_type']; ?><br>
						</div>
						
						<div class="col-md-4">
							<b><?php echo trans('category'); ?>: </b><?php echo $category['category_name']; ?><br>	
							<b><?php echo trans('manage_stock'); ?>: </b><?php echo $product['product_stock']; ?><br>
							<b><?php echo trans('alert_quantity'); ?>: </b><?php echo $product['alert_quantity']; ?><br>
							<b><?php echo trans('applicable_vat'); ?>: </b>
							<?php
								if($product['product_vat']==null){
									$product['product_vat']=0;
								}
								if($product['product_vat_type']=='percent'){
									echo $product['product_vat'].'%'; 
								}
								else{
									echo $product['product_vat'].'TK';
								}
							?><br>
						</div>
						
						<div class="col-md-4">
							<?php 
								$imageLocation = "images/stores/".$_SERVER['SERVER_NAME'].'/'.$product['product_image'];
								if(!empty($product['product_image']) && file_exists($imageLocation)){
									$imageLocation = "images/stores/".$_SERVER['SERVER_NAME'].'/'.$product['product_image'];
									}else{
									$imageLocation = "assets/img/no-image.png";
								}
							?>
							<img class="img-rounded img-lg" src="<?php echo $imageLocation; ?>">
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<h4><?php echo trans('variations'); ?>:</h4>
						</div>
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-striped table-bordered bg-green">
									<thead>
										<tr>
											<th><?php echo trans('variations'); ?></th>
											<th><?php echo trans('sku'); ?></th>
											<th><?php echo trans('default_purchase_price'); ?></th>
											<th><?php echo trans('x_margin_percent'); ?></th>
											<th><?php echo trans('default_selling_price_exc_vat'); ?></th>
											<th><?php echo trans('default_selling_price_inc_vat'); ?></th>
											<th><?php echo trans('barcode'); ?></th>
										</tr>
									</thead>
									<tbody class="text-center">
										<?php foreach ($variations as $variation) { 
											$product_name=$product['product_name'];
										?>
										<tr>
											<td>
												<?php if ($variation['variation_name']!=null) {
													echo $variation['variation_name'];
													$product_name = $product_name.'[ '.$variation['variation_name'].' ]';
													}else{
													echo "N/A";
												} ?>
											</td>
											<td><?php echo $variation['sub_product_id']; ?></td>
											<td><?php echo $variation['purchase_price']; ?></td>
											<td><?php echo $variation['profit_percent']; ?></td>
											<td><?php echo $variation['sell_price']; ?></td>
											<td>
												<?php if($product['product_vat_type']=='percent'){
													echo ($variation['sell_price']*($product['product_vat'])/100)+$variation['sell_price'];
												}
												else{
													echo $product['product_vat']+$variation['sell_price'];
												}
												
												?>
											</td>
											<td>
												<a href="javascript:void(0)" class="barcode" product_id="<?php echo $variation['sub_product_id']; ?>" product_name="<?php echo $product_name; ?>" product_price="<?php echo $variation['sell_price']; ?>" ><i class="fa fa-barcode"></i> <?php echo trans('barcode');?></a>
											</td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default no-print" data-dismiss="modal"><?php echo trans('cancel'); ?></button>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$(document).on("click",".barcode", function(){
			var product_id = $(this).attr('product_id');
			var product_name = $(this).attr('product_name');
			var product_price = $(this).attr('product_price');
			$(".show_modal").remove();
			AS.Http.posthtml({"action" : "GetProductBarcode","product_id" : product_id,"product_name" : product_name,"product_price" : product_price}, "pos/modal/", function (data) {
				$(".modal_status").html(data);
				$("#product_id").val(product_id);
				$(".show_modal").modal("show");
			});
		});
	</script>
	
	<?php break;
		
	case "GetNewContact": ?>
	<div class="modal show_modal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12 AddContactForm">
							<form>
								<h3 class="m-t-none m-b"><?php echo trans('contact'); ?></h3>
								<div class="form-group row">
									<div class="col-sm-6" id="contact">
										<label><?php echo trans('contact_type'); ?> *</label>
										<?php if(isset($_POST['type'])){ ?>
											<input type="text" name="contact_type" class="form-control" id="contact_type" value="<?php if(isset($_POST['type'])) echo $_POST['type']; ?>" readonly>
											<?php }else{ ?>
											<select class="form-control" name="contact_type" id="contact_type">
												<option value=""><?php echo trans('select_contact'); ?></option>
												<option value="customer"><?php echo trans('customer'); ?></option>
												<option value="supplier"><?php echo trans('supplier'); ?></option>
												<option value="both"><?php echo trans('both'); ?></option>
											</select>
										<?php } ?>
									</div>
									<div class="col-sm-12">
										<label><?php echo trans('name'); ?></label>
										<input type="text" name="name" class="form-control" id="name" value="<?php if(isset($_POST['customer_name'])) echo $_POST['customer_name']; ?>" placeholder="<?php echo trans('name'); ?>">
										<input type="hidden" name="contact_id" id="contact_id" value="<?php if(isset($_POST['contact_id'])) echo $_POST['contact_id']; ?>">
									</div>
								</div>
								<div class="form-group row business">
									<div class="col-sm-6">
										<label><?php echo trans('business_name'); ?></label>
										<input type="text" name="business_name" class="form-control" id="business_name" placeholder="<?php echo trans('business_name'); ?>">
									</div>
									<div class="col-sm-6">
										<label><?php echo trans('website_name'); ?></label>
										<input type="text" name="website_name" class="form-control" id="website_name" placeholder="<?php echo trans('website_name'); ?>">
									</div>
								</div>
								<div class="form-group row">
									<div class="col-sm-6">
										<label><?php echo trans('phone'); ?></label>
										<input type="text" name="phone" class="form-control" id="phone" placeholder="<?php echo trans('phone'); ?>">
									</div>
									<div class="col-sm-6">
										<label><?php echo trans('email'); ?></label>
										<input type="text" name="email" class="form-control" id="email" placeholder="<?php echo trans('email'); ?>">
									</div>
								</div>
								<div class="form-group">
									<label><?php echo trans('address'); ?></label>
									<textarea name="address" class="form-control" id="address" placeholder="<?php echo trans('address'); ?>"></textarea>
								</div>
								<button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit"><strong><?php echo trans('submit'); ?></strong></button>
								<button class="btn btn-sm btn-danger pull-right m-t-n-xs new_contact_close" data-dismiss="modal" aria-label="Close" type="button" ><strong><?php echo trans('cancel'); ?></strong></button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		$('.AddContactForm form').validate({
			rules: {
				contact_type: {
					required: true
				},
				name: {
					required: true
				},
				phone: {
					required: true,
					number: true
				},
				email: {
					email: true
				}
			},	
			submitHandler: function(form) {
				AS.Http.PostSubmit(form, {"action" : "GetContactData"}, "pos/ajax/", function (response) {
					if(response.status == 'success'){
						$("#contact_id").val(response.contact_id);
						
						<?php if(isset($_POST['select_id'])){ ?>
							if(response.update_type == "New"){
								var option = document.createElement("option");
								option.text = response.name;
								option.value = response.contact_id;
								var select = document.getElementById("<?php echo $_POST['select_id']; ?>");
								select.appendChild(option);
								// console.log(response.name);
							}
							document.getElementById("<?php echo $_POST['select_id']; ?>").value = response.contact_id;
						<?php } ?>
						
						swal({
							title: $_lang.success, 
							text: response.message, 
							type: "success",
							confirmButtonColor: "#1ab394", 
							confirmButtonText: $_lang.ok,
							},function(isConfirm){
							$(".show_modal").modal("hide");
						});
						toastr.options = {
							closeButton: true,
							progressBar: true,
							showMethod: 'slideDown',
							timeOut: 4000
						};
						//toastr.success(response.contact_type + ' Successfully '+response.update_type, 'Success');
					}
					
				});
			}
		});		
	</script>
	<?php
		break;		
		case 'GetCustomerReceiptModal':
		$numberToWords = new NumberToWords\NumberToWords();
		$numberTransformer = $numberToWords->getNumberTransformer('en');
		$getpossetting = app('pos')->GetPosSetting();
		
		if(isset($_POST['sales_id'])){
			$getSaleInfoData = app('admin')->getwhereid('pos_sales','sales_id',$_POST['sales_id']);
			}else{
			$getSaleInfoData = app('pos')->GetLastOrderReceipt($this->currentUser->id);
		}
		
		$getcustomerid = app('admin')->getwhereid('pos_contact','contact_id',$getSaleInfoData['customer_id']);
		$getsalesby = app('admin')->getuserdetails($getSaleInfoData['user_id']);
		$getsalesproducts = app('admin')->getwhereand('pos_stock','sales_id',$getSaleInfoData['sales_id'],'stock_status','active');
		$getTransactionDetails=app('admin')->getwhere('pos_transactions','sales_id',$getSaleInfoData['sales_id']);
		$getlastorderid = app('pos')->GetLastOrderReceipt($this->currentUser->id);
		$getSalesInfo = app('admin')->getwhereid('pos_sales','sales_id',$getSaleInfoData['sales_id']);
		
		if($getSalesInfo['sales_type']=='pos'){
		?>
		<div class="modal inmodal fade show_modal"  tabindex="-1" role="dialog"  aria-hidden="true">
			<div class="modal-dialog ">
				<div class="modal-content receiptview" style="width: 450px;">
					<div class="modal-body" id="invoice-POS">
						<center id="top">
							<div class="info"> 
								<?php if(!empty($getpossetting['company_logo'])){ ?>
									
									<?php }else{ ?>
									<p class="company_name"><?php 
										echo $getpossetting['company_name']; 
									?></p>
								<?php } ?>
							</div>
						</center>
						
						<div id="mid">
							<div class="text_center" style="font-family: -webkit-body;">
								<p> 
									Address : <?php echo $getpossetting['address']; ?><br>
									Email   : <?php echo $getpossetting['email']; ?><br>
									Phone   : <?php echo $getpossetting['phone']; ?><br>
									<?php if(!empty($getpossetting['nbr_no'])){ ?>Vat Reg : <?php echo $getpossetting['nbr_no']; ?> Mushak : <?php echo $getpossetting['nbr_unit']; ?><br> <?php } ?>
								</p>
							</div>
							<div class="info">
								<p class="pull-left" style="font-size:14px;font-family: -webkit-body; float:left;">
									Voucher:  <?php echo $getSalesInfo['sales_id']; ?> <br>
									Sale To: <?php if(empty($getSalesInfo['customer_id'])){ echo "Walk In Customer"; }else{ if(empty($getcustomerid['name'])){ echo $getcustomerid['phone']; }else{ echo $getcustomerid['name']; }} ?>
									
								</p>
								<p class="pull-right" style="font-size:14px;font-family: -webkit-body; float:right;">
									Date: <?php echo getdatetime($getSalesInfo['created_at'], 3); ?> <br>
									Sale By: <?php echo $getsalesby['first_name'].' '.$getsalesby['last_name']; ?>
								</p>
							</div>
						</div><!--End Invoice Mid-->
						
						<div id="bot">
							<div id="table">
								<table class="pdt_table" >
									<tr class="text_center head" style="border: 2px solid #ccc2c2">
										<th style="font-size: 12px;">Item</th>
										<th class="text_center" style="font-size: 13px;">Qty</th>
										<th class="text_center" style="font-size: 13px;">Price</th>
										<th class="text_center" style="font-size: 13px;">Sub Total</th>
									</tr>
									
									<tbody id="body">
										<?php 
											foreach($getsalesproducts as $getsalesproduct){ 
												$getproductdetails = app('admin')->getwhereid('pos_product','product_id',$getsalesproduct['product_id']);
												$GetSerial = app('admin')->getwhereand('pos_product_serial','product_id',$getsalesproduct['product_id'],'sales_id',$getlastorderid['sales_id']);
											?>
											<tr>
												<td><p style="font-weight:bold;"><?php
													echo $getproductdetails['product_name'];
													if(count($GetSerial)!=0){
														echo '</br>[ S/N: ';
														foreach($GetSerial as $serial){
															echo $serial['product_serial_no'].',';
														}
														echo ' ]';
													}
												?></p></td>
												<td class="text_center"><p style="font-weight:bold;"><?php 
													echo $getsalesproduct['product_quantity'];
												?> </p></td>
												<td class="text_center"><p style="font-weight:bold;"><?php echo $getsalesproduct['product_price']; ?></p></td>
												<td class="text_center"><p style="font-weight:bold;"><?php echo $getsalesproduct['product_subtotal']; ?></p></td>
											</tr>
										<?php } ?>	
									</tbody>
									<tr class="calculation" style="border-top: 1px solid gray; margin-top: 5px;">
										
										<th colspan="3" style="font-size: 13px;"><span class="float_right">Sub Total =</span></th>
										<th colspan="1" class="text_right" style="font-size: 13px;"><?php echo $getSalesInfo['sales_subtotal']; ?></th>
									</tr>
									<?php if($getSalesInfo['sales_discount'] != 0){ ?>
										<tr class="calculation">
											
											<th colspan="3" style="font-size: 13px;"><span class="float_right">Discount =</span></th>
											<th colspan="1" class="text_right" style="font-size: 13px;"><?php echo $getSalesInfo['sales_discount']; ?></th>
										</tr>
									<?php } ?>
									<tr class="calculation">
										<?php if($getpossetting['vat_type']=='global'){ ?>
											<th colspan="3" style="font-size: 13px;"><span class="float_right">VAT(<?php echo $getpossetting['vat']; ?>%) =</span></th>
											<?php } else{ ?>
											<th colspan="3" style="font-size: 13px;"><span class="float_right">Total VAT =</span></th>
										<?php } ?>
										<th colspan="1" class="text_right" style="font-size: 13px;"><?php echo $getSalesInfo['sales_vat']; ?></th>
									</tr>
									<tr class="calculation" style="border-top: 1px solid gray;">
										
										<th colspan="3" style="font-size: 13px;" ><span class="float_right" >Grand Total =</span></th>
										<th colspan="1" style="font-size: 13px;" class="text_right"><?php echo $getSalesInfo['sales_total']; ?></th>
									</tr>
									<?php
										if($getSalesInfo['sales_status']!='quote'){
											$total_paid=0;
											$total_due=0;
											$total_paid=$getSalesInfo['sales_pay_cash'];
											$total_due=$getSalesInfo['sales_pay_cash'] - $getSalesInfo['sales_total'];
											// if($total_due<0){
											// $total_due=0;
											// }
										?>
										
										<tr class="calculation" style="border-top: 1px solid gray;">
											
											<th colspan="3" style="font-size: 13px;" ><span class="float_right" >Total Paid =</span></th>
											<th colspan="1" style="font-size: 13px;" class="text_right"><?php echo $total_paid==0 ? 'N/A' : $total_paid; ?></th>
										</tr>
										<tr class="calculation" style="border-top: 1px solid gray;">
											
											<th colspan="3" style="font-size: 13px;" ><span class="float_right" >Total Due =</span></th>
											<th colspan="1" style="font-size: 13px;" class="text_right"><?php echo $total_due<0 ? str_replace('-','',(string)$total_due) : 'N/A'; ?></th>
										</tr>
										
										
										<?php if($getSalesInfo['sales_total']!=$total_due){?>
											<tr>
												<td colspan='4'><?php echo ucwords($numberTransformer->toWords(ceil($getlastorderid['sales_total'])));  ?>*</td>
											</tr>
											<tr style='border-top:1px dashed;border-bottom:1px dashed;'>
												<td colspan='4' class='text_right'>Payment Type</td>
											</tr>
											
											<?php foreach($getTransactionDetails as $transaction){?>
												<tr>
													<td colspan='2' class='text_right'><?php echo $transaction['payment_method_value']; ?></td>
													<td colspan='2' class='text_right'><?php echo $transaction['transaction_amount']; ?></td>
												</tr>
												<?php if($transaction['payment_method_value']!='cash'){ ?>
													<tr>
														<td colspan='2' class='text_right'>Transaction Id</td>
														<td colspan='2' class='text_right' ><?php echo $transaction['transaction_no'];?></td>
													</tr>
													
												<?php }} ?>
												
												
												<tr style='border-top:1px dashed'>
													<td colspan='2' class='text_right'>Received BDT</td>
													<td colspan='2' class='text_right' ><?php echo $getSalesInfo['sales_pay_cash'];?></td>
												</tr>
												<tr style='border-bottom:1px dashed'>
													<td colspan='2' class='text_right'>Change BDT</td>
													<td colspan='2' class='text_right' ><?php echo $getSalesInfo['sales_pay_change'];?></td>
												</tr>
										<?php } }
										else{
										?>
										<tr class="calculation" style="border-top: 1px solid gray;">
											
											<th colspan="3" style="font-size: 13px;" ><span class="float_right" ></span></th>
											<th colspan="1" style="font-size: 13px;" class="text_right">Your Sales Added as Quotation</th>
										</tr>
									<?php } ?>
								</table>
							</div><!--End Table-->
							
							<div id="legalcopy">
								
								<?php if(!empty($getpossetting['receipt_footer'])){ ?>
									<p class="text_center"><?php echo $getpossetting['receipt_footer']; ?>
									</p>
								<?php } ?>
								<p class="text_center"><?php $barcodeobj = new TCPDFBarcode($getSalesInfo['sales_id'], 'C128'); echo $barcodeobj->getBarcodeSVGcode(1.5, 30, 'black'); ?></p>
								<p class="text_center"><strong>Powered by <?php echo POWERED_BY; ?></strong> 
								</p>
							</div>
							
						</div>
						<div class="modal-footer">
							<a href="javascript:void(0)" data-dismiss="modal" class="btn btn-white"><?php echo trans('cancel'); ?></a>
							
							<a class="btn btn-primary" sales_id="<?php echo $getSalesInfo['sales_id']; ?>" onclick="GetCustomerReceiptViews(this)"  href="javascript:void(0)" ><i class="fa fa-print"></i> <?php echo trans('print'); ?></a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php }
		
		break;
		
		// case "PurchaseViewModal":
		
		// $getPurchase=app('admin')->getwhereid('pos_purchase','purchase_id',$_POST['purchase_id']);
		// $getSupplier=app('admin')->getwhereid('pos_contact','contact_id',$getPurchase['supplier_id']);
		
	?>
	<!--div class="modal fade show_modal" tabindex="-1" role="dialog" >
		
		<div class="modal-dialog purchase-modal" >
		<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h2 class="modal-title text-center" id="modalTitle"> <?php echo trans('purchase_details');?> </h2>
		</div>
		<div class="modal-body">
		<div class="row">
		<div class="col-sm-12">
		<h5 class="pull-right"><strong><?php echo trans('date'); ?></strong>:<?php echo $getPurchase['purchase_date']; ?></h5>
		</div>
		<div class="col-sm-12">
		<div class="row">
		<div class="col-sm-4">
		<p>
		
		<strong><?php echo trans("supplier"); ?>:</strong>
		<?php echo $getSupplier['name']; ?>
		</p>
		<p>
		<strong><?php echo trans("mobile_no"); ?>:</strong>
		<?php echo $getSupplier['phone']; ?>
		</p>
		</div>
		<div class="col-sm-4">
		<p>
		<strong><?php echo trans("purchase_status"); ?>:</strong>
		<?php 
			if($getPurchase['purchase_status']=='pending'){
			?>
			<label class="label label-success"><?php echo $getPurchase['purchase_status']; ?></label>
			<?php }
			else{
			?>
			<label class="label label-primary"><?php echo $getPurchase['purchase_status']; ?></label>
		<?php } ?>
		
		
		</p>
		<p>
		<strong><?php echo trans("payment_status"); ?>:</strong>
		<?php 
			if($getPurchase['purchase_payment_status']=='paid'){
			?>
			<label class="label label-primary"><?php echo $getPurchase['purchase_payment_status']; ?></label>
			<?php }
			else{
			?>
			<label class="label label-danger"><?php echo $getPurchase['purchase_payment_status']; ?></label>
		<?php } ?>
		</p>
		</div>
		<div class="col-sm-4">
		<p>
		<strong><?php echo trans("grand_total"); ?>:</strong>
		<?php echo $getPurchase['purchase_total']; ?>
		</p>
		</div>
		</div>
		</div>
		<div class="col-sm-12 table-responsive">
		<table class="table table-striped table-bordered bg-green">
		<thead>
		<tr>
		<th><?php echo trans('serial_no'); ?></th>
		<th><?php echo trans('product_name'); ?></th>
		<th><?php echo trans('quantity'); ?></th>
		<th><?php echo trans('purchase_price'); ?></th>
		<th><?php echo trans('purchase_discount'); ?></th>
		<th><?php echo trans('subtotal_total'); ?></th>
		</tr>
		</thead>
		
		<tbody class="text-center">
		<?php 
			$getPurchaseProducts=app('admin')->getwhereand('pos_stock','purchase_id',$getPurchase['purchase_id'],"stock_category","purchase");
			// print_r($getPurchaseProducts);
			$count=1;
			foreach ($getPurchaseProducts as $product) {
				
				$getSubProduct=app('admin')->getwhereid('pos_variations','sub_product_id',
				$product['sub_product_id']);
				$getProducts=app('admin')->getwhereid('pos_product','product_id',$getSubProduct['product_id']);
			?>
			<tr>
			<td><?php echo $count; ?></td>
			<td><?php echo $getProducts['product_name']; if($getSubProduct['variation_name']!=null){echo '['.$getSubProduct['variation_name'].']';} ?></td>
			<td><?php echo $product['product_quantity']; ?></td>
			<td><?php echo $getSubProduct['purchase_price']; ?></td>
			<td><?php echo $product['product_discount']; ?></td>
			<td><?php echo $product['product_subtotal']; ?></td>
			
			
			</tr>
		<?php $count++; } ?>
		</tbody>
		</table>
		</div>
		<div class="col-sm-12">
		
		<div class="row">
		<?php 
			if(app('admin')->checkAddon('multiple_payment_method')){
			?>
			<div class="col-sm-6 table-responsive">
			<h3><?php echo trans('payment_info'); ?>:</h3>
			<table class="table table-bordered bg-green" style="">
			<thead>
			<tr>
			<th><?php echo trans('serial_no'); ?></th>
			<th><?php echo trans('date'); ?></th>
			<th><?php echo trans('amount'); ?></th>
			<th><?php echo trans('payment_method'); ?></th>
			<th><?php echo trans('note'); ?></th>
			</tr>
			</thead>
			<tbody class="text-center">
			<?php 
				$getPaymenInfo=app('admin')->getwhere('pos_transactions','purchase_id',$getPurchase['purchase_id']);
				$count=1;
				foreach ($getPaymenInfo as $payment) {
					
				?>
				<tr>
				<td><?php echo $count; ?></td>
				<td><?php echo $payment['paid_date']; ?></td>
				<td><?php echo $payment['transaction_amount']; ?></td>
				<td><?php echo $payment['payment_method_value']; ?></td>
				<td> <?php echo $payment['transaction_note']; ?></td>
				</tr>
				
			<?php } ?>
			</tbody>
			</table>
			</div>
		<?php } ?>
		<div class="col-sm-6 table-responsive">
		<br>
		<table class="table pull-left" style="margin-top: 15px;">
		<tr>
		<th><?php echo trans('net_amount'); ?>:</th>
		<td><?php echo $getPurchase['purchase_subtotal']; ?></td>
		</tr>
		<tr>
		<th><?php echo trans('discount'); ?>:</th>
		<td><?php echo $getPurchase['purchase_discount']; ?></td>
		</tr>
		<tr>
		<th><?php echo trans('additional_shipping_charge'); ?>:</th>
		<td><?php echo $getPurchase['purchase_shipping_charge']; ?></td>
		</tr>
		<tr>
		<th><?php echo trans('tax'); ?>:</th>
		<td><?php echo $getPurchase['purchase_tax']; ?></td>
		</tr>
		<tr>
		<th><?php echo trans('total_purchase'); ?>:</th>
		<td><?php echo $getPurchase['purchase_total']; ?></td>
		</tr>
		
		<tfoot>
		<tr>
		<td colspan="">
		<strong><?php echo trans('additional_notes'); ?></strong>
		</td>
		</tr>
		<tr style="background-color: #e7e4e4; height: 40px;">
		<td colspan="2">
		<?php echo $getPurchase['purchase_note']; ?>
		</td>
		</tr>
		</tfoot>
		</table>
		
		</div>
		</div>
		
		
		
		</div>									
		</div>						
		
		</div>
		<div class="modal-footer">
		<button type="button" class="btn btn-default no-print" data-dismiss="modal"><?php echo trans('cancel'); ?></button>
		</div>
		</div>
		</div>
	</div--> 
	<?php
		// break;
		
		case "GetVatPaidModal":
		$vat_total=app('admin')->GetSum('pos_sales',array('sales_vat'),array('sales_status'=>'complete'));
		$paid_vat=app('admin')->GetSum('pos_transactions',array('transaction_amount'),array('transaction_type'=>'vat'));
		$due_amount = $vat_total['sales_vat']-$paid_vat['transaction_amount'];
		if($due_amount<0){
			$due_amount=0;
		}
	?>
	<div class="modal fade in show_modal">
		<div class="modal-dialog">
			<div class="modal-content VatPaid">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<div class="modal-title">
						<h3 class="">
							<?php echo trans('total_payable_vat');?>: <?php echo $due_amount;?>
						</h3>
					</div>
					
				</div>
				<form>
					
					<div class="modal-body">
						
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
									<input type="hidden" name="transaction_id" id="transaction_id">
									<label class="control-label">
										<?php echo trans('payment_method'); ?>
									</label>
									<select class="form-control payment_method" name="payment_method">
										<!-- <option value="null"><?php echo trans('select_payment_mathod'); ?></option> -->
										<?php $paymentMethods = app('admin')->getall("pos_payment_method"); foreach($paymentMethods as $paymentMethod){ ?>
											<option value="<?php echo $paymentMethod['payment_method_value']; ?>"><?php echo $paymentMethod['payment_method_name']; ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="form-group hide transaction_number">
									<label class="control-panel"><?php echo trans('transaction_number'); ?>:*</label>
									<div class="input-group">
										<span class="input-group-addon">
											<i class="fa fa-credit-card"></i>
										</span>	
										<input type="text" class="form-control" name="transaction_no" placeholder="<?php echo trans('enter_transaction_number'); ?>">
									</div>
									
								</div>
								<div class="form-group">
									<label class="control-panel"><?php echo trans('payment_amount'); ?>:*</label>
									<div class="input-group">
										<span class="input-group-addon">
											<i class="fa fa-credit-card"></i>
										</span>	
										<input type="text" class="form-control PaymentAmountChange" name="payment_amount" placeholder="<?php echo trans('payment_amount'); ?>">
										<input id="total_payment_amount" type="hidden" value="<?php echo $_POST['payment_amount']; ?>">
									</div>
								</div>
								<br>
								<div class="row" >
									<div class="col-lg-12">
										<div class="form-group" >
											<label><?php echo trans('payment_note');?></label>
											<textarea class="form-control" placeholder="<?php echo trans('payment_note');?>" name="payment_note" ></textarea>
										</div>
									</div>
								</div>
							</div>
							
						</div>
					</div>
					<div class="modal-footer" >
						<button type="submit" class="btn btn-primary"><?php echo trans('complete'); ?></button>
						<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo trans('close');?></button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$(".payment_method").change(function(){
			var method = $(".payment_method").val();
			if(method!='cash')
			{
				$(".transaction_number").removeClass('hide');	
			}
			else{
				$(".transaction_number").addClass('hide');	
			}
		});
		$('.VatPaid form').validate({
			rules: {
				payment_amount: {
					required: true
				},
				
				brand_short_description: {
					required: true
				}
			},  
			submitHandler: function(form) {
				AS.Http.PostSubmit(form, {"action" : "VatPaidSubmit"}, "pos/ajax/", function (response) {
					if(response.status=='success'){
						swal({
							title: $_lang.success, 
							text: response.message, 
							type: "success",
							confirmButtonColor: "#1ab394", 
							confirmButtonText: $_lang.ok,
							},function(isConfirm){
							$(".show_modal").modal("hide");
							location.reload();
						});
					}
				});
			}
		});
		
	</script>
	<?php
		break;
		
		
	case "GetNewVariation": ?>
	<div class="modal fade show_modal" tabindex="-1" role="dialog">
		
		<div class="modal-dialog">
			<div class="modal-content AddVariationSubmit">
				<div class="modal-header">
					<button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="modalTitle"> <?php echo trans('add_variation'); ?> </h4>
				</div>
				<form>
					<?php if(isset($_POST['variation_id'])) echo '<input type="hidden" name="variation_id" value="'.$_POST['variation_id'].'">';?>
					<div class="modal-body from-body">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-3">
									<label class="control-label">
									<?php echo trans('variation_name'); ?>:*</label>
								</div>
								<div class="col-sm-9">
									<input type="text" class="form-control input-sm" name="variation_name" id="variation_name" placeholder="<?php echo trans('variation_name'); ?>">
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-3">
									<label class="control-label">
									<?php echo trans('variation_value'); ?>:*</label>
								</div>
								<div class="col-sm-7">
									<input type="text" class="form-control input-sm" name="variation_value[]" id="variation_value" placeholder="<?php echo trans('variation_value'); ?>">
								</div>
								<div class="col-sm-2">
									<button type="button" class="btn btn-success btn-sm" id="add_variation_values">+</button>
								</div>
							</div>
						</div>
						
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-success">
							<?php echo trans('save'); ?>
						</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">
							<?php echo trans('cancel'); ?>
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$("#add_variation_values").click(function() {
			var html = '<div class="form-group"><div class="row"><div class="col-sm-7 col-sm-offset-3"><input type="text" class="form-control input-sm" name="variation_value[]" placeholder="<?php echo trans("variation_value"); ?>"></div><div class="col-sm-2"><button type="button" class="btn btn-danger btn-sm" id="remove_variation_values" onclick="remove(this)">-</button></div></div></div>';
			$(".from-body").append(html);
		});
		$('.AddVariationSubmit form').validate({
			rules: {
				variation_name: {
					required: true
				},
				
			},
			submitHandler: function(form) {
				AS.Http.PostSubmit(form, {
					"action": "AddVariationSubmit"
					}, "pos/ajax/", function(response) {
					if (response.status == 'success') {
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
	</script>
	<?php
		break;
		
	case "GetPosMultiplePay": ?>
	<div class="modal fade in show_modal">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content" >
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" ><span aria-hidden="true">×</span></button>
					<h4 class="modal-title"><?php echo trans('multiple_payment'); ?></h4>
				</div>
				<div class="modal-body" >
					<div class="row" >
						<div class="col-md-9" >
							<div class="add_paymnent">  <!--  -->
								<div class="row" >
									<div id="payment_rows_div" >
										<div class="col-md-12" >
											<div class="ibox" >
												<div class="ibox-title">
													<button type="button" class="btn btn-primary btn-sm pull-right add-payment-row" id="">Add Payment Row</button>
												</div>
												<div class="ibox-content">
													<input id="total_payment_amount" type="hidden" value="<?php echo $_POST['payment_amount']; ?>">
													<div class="row">
														<div class="col-md-6">
															<div class="form-group">
																<label for="amount_0" >Amount:*</label>
																<div class="input-group" >
																	<span class="input-group-addon" ><i class="fa fa-credit-card"></i></span>
																	<input class="form-control payment-amount input_number valid PaymentAmountChange" required="" placeholder="Amount" name="payment_amount[]" type="text" value="0.00">
																</div>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group" >
																<label for="method_0" >Payment Method:*</label>
																<div class="input-group" >
																	<span class="input-group-addon" ><i class="fa fa-credit-card"></i></span>
																	<select class="form-control col-md-12 payment_types_dropdown" required="" style="width:100%;" name="payment_method[]" aria-required="true" >
																		<?php $paymentMethods = app('admin')->getall("pos_payment_method"); foreach($paymentMethods as $paymentMethod){ ?>
																			<option value="<?php echo $paymentMethod['payment_method_value']; ?>"><?php echo $paymentMethod['payment_method_name']; ?></option>
																		<?php } ?>
																	</select>
																</div>
															</div>
														</div>
														<div class="clearfix"></div>
														<div class="payment_details_div hide">
															<div class="col-md-12">
																<div class="form-group">
																	<label for="transaction_no_3_0">Transaction No.</label>
																	<input class="form-control" placeholder="Transaction No." name="transaction_no[]" type="text" value="">
																</div>
															</div>
														</div>
														<div class="col-md-12">
															<div class="form-group" >
																<label for="note_0">Payment note:</label>
																<textarea class="form-control" rows="3" name="payment_note[]" cols="50"></textarea>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<br>
							<div class="row" >
								<div class="col-md-12">
									<div class="form-group" >
										<label for="sale_note" >Sell note:</label>
										<textarea class="form-control" rows="3" placeholder="Sell note" name="sale_note" cols="50"></textarea>
									</div>
								</div>
								
							</div>
						</div>
						<div class="col-md-3">
							<div class="ibox">
								<div class="ibox-content" >
									<div class="col-md-12" >
										<hr>
										<strong>
											Total Payable:
										</strong>
										<br>
										<span class="lead text-bold total_payable_span" ><?php echo $_POST['payment_amount']; ?></span>
									</div>
									
									<div class="col-md-12" >
										<hr>
										<strong>
											Total Paying:
										</strong>
										<br>
										<span class="lead text-bold total_paying" >0.00</span>
									</div>
									
									<div class="col-md-12" >
										<hr>
										<strong>
											Change Return:
										</strong>
										<br>
										<span class="lead text-bold change_return_span" >0.00</span>
									</div>
									
									<div class="col-md-12" >
										<hr>
										<strong>
											Due Balance:
										</strong>
										<br>
										<span class="lead text-bold balance_due" > 0.00</span>
									</div>
									
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary"><?php echo trans('finalize_amount');?></button>
					<button type="button" class="btn btn-default" data-dismiss="modal" ><?php echo trans('close');?></button>
				</div>
			</div>
		</div>
	</div>
	<script>
		$(document).on("keyup",".PaymentAmountChange", function(){
			var total_payment_amount =  $('#total_payment_amount').val() || 0;
			var total_paid_amount = 0;
			$('input[name="payment_amount[]"]').each(function() {
				total_paid_amount +=  parseInt($(this).val() || 0);
			});
			var total_due_amount = parseInt(total_payment_amount) - parseInt(total_paid_amount);
			$('.total_paying').html(total_paid_amount || 0);
			$('input[name^="sales_receive_amount"]').val(total_paid_amount || 0);
			if(total_due_amount < 0){
				$('.change_return_span').html(total_due_amount || 0);
				$('.balance_due').html(0);
				$(".due_amount_label").addClass('hidden');
				$(".pay_change_label").removeClass("hidden");
				$('input[name^="sales_pay_change"]').val(total_due_amount || 0);
				$('input[name^="due_amount"]').val(0);
				}else{
				$('.balance_due').html(total_due_amount || 0);
				$('.change_return_span').html(0);
				$(".pay_change_label").addClass('hidden');
				$(".due_amount_label").removeClass("hidden");
				$('input[name^="due_amount"]').val(total_due_amount || 0);
				$('input[name^="sales_pay_change"]').val(0);
			}
		});
		
		$(".payment_types_dropdown").click(function(){
			var type =$(this).val();
			if(type!='cash'){
				$(".payment_details_div").removeClass('hide');
				}else{
				$(".payment_details_div").addClass('hide');	
			}
		});
		
		function remove_payment(el){
			$(el).parent('div').parent('div').parent('div').parent('div').parent('div').remove();
		}
		
		function payment_type_change(id){
			var div=".payment_types_dropdown"+id;
			var type=$(div).val();
			if(type!='cash'){
				$(".payment_details_div"+id).removeClass('hide');
			}
			else{
				$(".payment_details_div"+id).addClass('hide');	
			}
		}
		
		var count = 0;
		
		$('.add-payment-row').click(function(){
			var html ='<div class="row" >'+
			'<div id="payment_rows_div">'+
			'<div class="col-md-12" >'+
			'<div class="ibox">'+
			'<div class="ibox-title">'+
			'<button type="button" class="close" onclick="remove_payment(this)" aria-label="Close" ><span aria-hidden="true">×</span></button>'+
			'</div>'+
			'<div class="ibox-content">'+
			'<div class="row">'+
			'<input type="hidden" class="payment_row_index" value="0">'+
			'<div class="col-md-6">'+
			'<div class="form-group">'+
			'<label for="amount_0" >Amount:*</label>'+
			'<div class="input-group" >'+
			'<span class="input-group-addon" ><i class="fa fa-credit-card"></i></span>'+
			'<input class="form-control payment-amount input_number valid PaymentAmountChange" required="" id="amount_0" placeholder="Amount" name="payment_amount[]" type="text" value="0.00" aria-required="true" aria-invalid="false">'+
			'</div>'+
			'</div>'+
			'</div>'+
			'<div class="col-md-6">'+
			'<div class="form-group" >'+
			'<label for="method_0" >Payment Method:*</label>'+
			'<div class="input-group" >'+
			'<span class="input-group-addon" ><i class="fa fa-credit-card"></i></span>'+
			'<select class="form-control col-md-12 payment_types_dropdown'+count+'" required=""'+ 'id="method" onchange="payment_type_change('+count+')" style="width:100%;" name="payment_method[]" aria-required="true" >'+
			<?php 
				$paymentMethods = app('admin')->getall("pos_payment_method");
				foreach($paymentMethods as $paymentMethod){
				?>
				'<option value="<?php echo $paymentMethod['payment_method_value']; ?>"><?php echo $paymentMethod['payment_method_name']; ?></option>'+
				
			<?php } ?>
			'</select>'+
			'</div>'+
			'</div>'+
			'</div>'+
			'<div class="clearfix"></div>'+
			'<div class="payment_details_div'+count+' hide">'+
			'<div class="col-md-12">'+
			'<div class="form-group">'+
			'<label for="transaction_no_3_0">Transaction No.</label>'+
			'<input class="form-control" placeholder="Transaction No." id="transaction_no_3_0" name="transaction_no[]" type="text" value="">'+
			'</div>'+
			'</div>'+
			'</div>'+
			'<div class="col-md-12">'+
			'<div class="form-group" >'+
			'<label for="note_0">Payment note:</label>'+
			'<textarea class="form-control" rows="3" id="payment_note[]" name="payment_note[]" cols="50"></textarea>'+
			'</div>'+
			' </div>'+
			' </div>'+
			'</div>'+
			'</div>'+
			'</div>'+
			'</div>';
			
			$(".add_paymnent").append(html);
			count++;
		});
	</script>
	<?php
		
		break;
		
	case "GetPosCardPay": ?>
	
	<div class="modal fade in show_modal">
		<div class="modal-dialog  modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title"><?php echo trans('card_transaction_details'); ?></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-9">
							<div class="form-group">
								<label class="control-label">
									<?php echo trans('payment_method'); ?>
								</label>
								<select class="form-control payment_method" name="payment_method[]">
									<option value="null"><?php echo trans('select_payment_mathod'); ?></option>
									<?php $paymentMethods = app('admin')->getall("pos_payment_method"); foreach($paymentMethods as $paymentMethod){ if($paymentMethod['payment_method_value']!='cash'){?>
										<option value="<?php echo $paymentMethod['payment_method_value']; ?>"><?php echo $paymentMethod['payment_method_name']; ?></option>
									<?php }} ?>
								</select>
							</div>
							<div class="form-group">
								<label class="control-panel"><?php echo trans('transaction_number'); ?>:*</label>
								<div class="input-group">
									<span class="input-group-addon">
										<i class="fa fa-credit-card"></i>
									</span>	
									<input type="text" class="form-control" name="transaction_no[]" placeholder="<?php echo trans('enter_transaction_number'); ?>">
								</div>
								
							</div>
							<div class="form-group">
								<label class="control-panel"><?php echo trans('payment_amount'); ?>:*</label>
								<div class="input-group">
									<span class="input-group-addon">
										<i class="fa fa-credit-card"></i>
									</span>	
									<input type="text" class="form-control PaymentAmountChange" name="payment_amount[]" placeholder="<?php echo trans('payment_amount'); ?>">
									<input id="total_payment_amount" type="hidden" value="<?php echo $_POST['payment_amount']; ?>">
								</div>
							</div>
							<br>
							<div class="row" >
								<div class="col-md-6">
									<div class="form-group" >
										<label for="sale_note" ><?php echo trans('sell_note');?></label>
										<textarea class="form-control" rows="3" placeholder="Sell note" name="sale_note" cols="50"></textarea>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group" >
										<label for="staff_note"><?php echo trans('payment_note');?></label>
										<textarea class="form-control" rows="3" placeholder="Staff note" name="payment_note[]" cols="50"></textarea>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							
							<div class="row" >
								<div class="col-md-12" >
									<hr>
									<strong>
										Total Payable:
									</strong>
									<br>
									<span class="lead text-bold total_payable_span" ><?php echo $_POST['payment_amount']; ?></span>
								</div>
								
								<div class="col-md-12" >
									<hr>
									<strong>
										Total Paying:
									</strong>
									<br>
									<span class="lead text-bold total_paying" >0.00</span>
								</div>
								
								<div class="col-md-12" >
									<hr>
									<strong>
										Change Return:
									</strong>
									<br>
									<span class="lead text-bold change_return_span" >0.00</span>
								</div>
								
								<div class="col-md-12" >
									<hr>
									<strong>
										Due Balance:
									</strong>
									<br>
									<span class="lead text-bold balance_due" > 0.00</span>
								</div>
								
							</div>
							
						</div>
					</div>
				</div>
				<div class="modal-footer" >
					<button type="submit" class="btn btn-primary"><?php echo trans('finalize_amount'); ?></button>
					<button type="button" class="btn btn-default" data-dismiss="modal" ><?php echo trans('close');?></button>
				</div>
			</div>
		</div>
	</div>
	<script>
		$(document).on("keyup",".PaymentAmountChange", function(){
			var total_payment_amount =  $('#total_payment_amount').val() || 0;
			var total_paid_amount = 0;
			$('input[name="payment_amount[]"]').each(function() {
				total_paid_amount +=  parseInt($(this).val() || 0);
			});
			var total_due_amount = parseInt(total_payment_amount) - parseInt(total_paid_amount);
			$('.total_paying').html(total_paid_amount || 0);
			$('input[name^="sales_receive_amount"]').val(total_paid_amount || 0);
			if(total_due_amount < 0){
				$('.change_return_span').html(total_due_amount || 0);
				$('.balance_due').html(0);
				$(".due_amount_label").addClass('hidden');
				$(".pay_change_label").removeClass("hidden");
				$('input[name^="sales_pay_change"]').val(total_due_amount || 0);
				$('input[name^="due_amount"]').val(0);
				}else{
				$('.balance_due').html(total_due_amount || 0);
				$('.change_return_span').html(0);
				$(".pay_change_label").addClass('hidden');
				$(".due_amount_label").removeClass("hidden");
				$('input[name^="due_amount"]').val(total_due_amount || 0);
				$('input[name^="sales_pay_change"]').val(0);
			}
		});
	</script>
	<?php
		break;
		
	case "GetPurchaseSerialShowModal": ?>
	
	<div class="modal fade show_modal" tabindex="-1" role="dialog" >
		
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
										<th><?php echo trans('serial'); ?></th>
										<th><?php echo trans('action'); ?></th>
									</tr>
								</thead>
								<tbody class="text-center">
									<?php 
										for ($i=0; $i < count($_POST['serials']) ; $i++) { ?>
										<tr>
											<td><?php echo $_POST['serials'][$i]; ?></td>
											<td><a href="javascript:void(0);" class="remove_serial" array_key="<?php echo $i; ?>" product_code="<?php echo $_POST['product_code']; ?>" array_value="<?php echo $_POST['serials'][$i]; ?>" ><i class="fa fa-trash"></i></a></td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>									
					</div>						
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default no-print" data-dismiss="modal"><?php echo trans('cancel'); ?></button>
				</div>
			</div>
		</div>
	</div>
	
	<script type="text/javascript">
		$(document).on("click",".remove_serial", function(){
			$(".show_modal").remove();
			var array_key = $(this).attr('array_key');
			var array_value = $(this).attr('array_value');
			var product_code = $(this).attr('product_code');
			swal( {
				title: $_lang.are_you_sure, 
				text: array_value, 
				type: "warning", 
				showCancelButton: true, 
				confirmButtonColor: "#DD6B55", 
				confirmButtonText: $_lang.yes, 
				cancelButtonText: $_lang.no, 
				closeOnConfirm: false, 
				closeOnCancel: true
				},function (isConfirm) {
				if (isConfirm) {
					var element = document.getElementById(product_code+array_value);
					element.parentNode.removeChild(element);
					var purchase_product_quantity = $("#purchase_quantity_"+product_code).val();
					var sales_product_quantity = $("#product_quantity_"+product_code).val();
					if(purchase_product_quantity){
						
						var product_quantity = $("#purchase_quantity_"+product_code).val() || 0;
						var new_quantiry =  parseInt(product_quantity) - parseInt('1');
						$("#purchase_quantity_"+product_code).val(new_quantiry);
						AddPurchaseRowExtraLoad(product_code);
						
						}else if(sales_product_quantity){
						var product_quantity = $("#product_quantity_"+product_code).val() || 0;
						var new_quantiry =  parseInt(product_quantity) - parseInt('1');
						$("#product_quantity_"+product_code).val(new_quantiry);
						AddSalesRowExtraLoad(product_code);
					}
					
					swal({
						customClass: "confirmed",
						title: $_lang.deleted, 
						type: "success",
						confirmButtonColor: "#1ab394", 
						confirmButtonText: $_lang.ok,
					});
				}
			});
			$(".modal-backdrop").remove();
		});
		
	</script>
	
	<?php
		break;
		case "GetSalesViewModal": 
		$getSales=app('admin')->getwhereid('pos_sales','sales_id',$_POST['sales_id']); 
		$getSalesProduct = app('db')->select("SELECT * FROM `pos_stock` WHERE `sales_id` = :id AND `stock_category` = :category AND `stock_status` = :status", array( 'id' => $_POST['sales_id'], 'category' => 'sales', 'status' => 'active'));
		$getCustomer=app('admin')->getwhereid('pos_contact','contact_id',$getSales['customer_id']); 
	?>
	<style>
		.modal-dialog {
		width: 85%;
		left: 8%;
		height: auto;
		margin: 0;
		padding: 0;
		}
		
		.modal-content {
		height: auto;
		min-height: 100%;
		border-radius: 0;
		}
		th,tbody{text-align:center;}
		
	</style>
	<div class="modal fade show_modal" tabindex="-1" role="dialog">
		
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h2 class="modal-title text-center" id="modalTitle"> <?php echo trans('sales_details');?> </h2>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12">
							<h5 class="pull-right"><strong><?php echo trans('date'); ?></strong>:<?php echo $getSales['created_at']; ?></h5>
						</div>
						<div class="col-sm-12">
							<div class="row">
								<div class="col-sm-4">
									<p>
										
										<strong><?php echo trans("customer"); ?>:</strong>
										<?php echo $getCustomer['name']; ?>
									</p>
									<p>
										<strong><?php echo trans("mobile_no"); ?>:</strong>
										<?php echo $getCustomer['phone']; ?>
									</p>
								</div>
								<div class="col-sm-4">
									<p>
										<strong><?php echo trans("sales_status"); ?>:</strong>
										<?php 
											if($getSales['sales_status']!='cancel'){
											?>
											<label class="label label-success">
												<?php echo $getSales['sales_status']; ?>
											</label>
											<?php }
											else{
											?>
											<label class="label label-danger">
												<?php echo $getSales['sales_status']; ?>
											</label>
										<?php } ?>
										
									</p>
								</div>
								<div class="col-sm-4">
									<p>
										<strong><?php echo trans("grand_total"); ?>:</strong>
										<?php echo $getSales['sales_total']; ?>
									</p>
									<!-- <p>
										<strong><?php echo trans("payment_due"); ?>:</strong>
										<?php echo $getSales['sales_discount']; ?>
									</p> -->
								</div>
							</div>
						</div>
						<div class="col-sm-12 table-responsive">
							<table class="table table-striped table-bordered bg-green">
								<thead>
									<tr>
										<th>#</th>
										<th>
											<?php echo trans('product_name'); ?>
										</th>
										<th>
											<?php echo trans('quantity'); ?>
										</th>
										<th>
											<?php echo trans('sales_price'); ?>
										</th>
										<th>
											<?php echo trans('discount'); ?>
										</th>
										<th>
											<?php echo trans('subtotal_total'); ?>
										</th>
										
									</tr>
								</thead>
								
								<tbody class="text-center">
									<?php 
										$count=1;
										
										foreach ($getSalesProduct as $product) {
											
											$getSubProduct=app('admin')->getwhereid('pos_variations','sub_product_id',
											$product['sub_product_id']);
											
											$getProducts=app('admin')->getwhereid('pos_product','product_id',$getSubProduct['product_id']);
										?>
										<tr>
											<td>
												<?php echo $count; ?>
											</td>
											<td>
												<?php echo $getProducts['product_name']; if($getSubProduct['variation_name']!=null){echo '['.$getSubProduct['variation_name'].']';} ?>
											</td>
											<td>
												<?php echo $product['product_quantity']; ?>
											</td>
											<td>
												<?php echo $getSubProduct['sell_price']; ?>
											</td>
											<td>
												<?php echo $product['product_discount']; ?>
											</td>
											<td>
												<?php echo $product['product_subtotal']; ?>
											</td>
											
										</tr>
									<?php $count++; } ?>
								</tbody>
							</table>
						</div>
						<div class="col-sm-12">
							
							<div class="row">
								<?php 
									if(app('admin')->checkAddon('multiple_payment_method')){
									?>
									<div class="col-sm-6 table-responsive">
										<h3><?php echo trans('payment_info'); ?>:</h3>
										<table class="table table-bordered bg-green" style="">
											<thead>
												<tr>
													<th>
														<?php echo trans('serial_no'); ?>
													</th>
													<th>
														<?php echo trans('date'); ?>
													</th>
													<th>
														<?php echo trans('amount'); ?>
													</th>
													<th>
														<?php echo trans('payment_method'); ?>
													</th>
													<th>
														<?php echo trans('note'); ?>
													</th>
												</tr>
											</thead>
											<tbody class="text-center">
												<?php 
													$getPaymenInfo=app('admin')->getwhereand('pos_transactions','sales_id',$getSales['sales_id'],'transaction_type','sales');
													$count=1;
													foreach ($getPaymenInfo as $payment) {	
													?>
													<tr>
														<td>
															<?php echo $count; ?>
														</td>
														<td>
															<?php echo $payment['paid_date']; ?>
														</td>
														<td>
															<?php echo $payment['transaction_amount']; ?>
														</td>
														<td>
															<?php echo $payment['payment_method_value']; ?>
														</td>
														<td>
															<?php echo $payment['transaction_note']; ?>
														</td>
													</tr>
													
												<?php } ?>
											</tbody>
										</table>
									</div>
								<?php } ?>
								<div class="col-sm-6 table-responsive">
									<br>
									<table class="table pull-left" style="margin-top: 15px;">
										<tr>
											<th>
											<?php echo trans('net_amount'); ?>:</th>
											<td>
												<?php echo $getSales['sales_subtotal']; ?>
											</td>
										</tr>
										<tr>
											<th>
											<?php echo trans('discount'); ?>:</th>
											<td>
												<?php echo $getSales['sales_discount']; ?>
											</td>
										</tr>
										<tr>
											<th>
											<?php echo trans('additional_shipping_charge'); ?>:</th>
											<td>
												<?php echo $getSales['shipping_charge']; ?>
											</td>
										</tr>
										<tr>
											<th>
											<?php echo trans('vat'); ?>:</th>
											<td>
												<?php echo $getSales['sales_vat']; ?>
											</td>
										</tr>
										<tr>
											<th>
											<?php echo trans('total_sales'); ?>:</th>
											<td>
												<?php echo $getSales['sales_total']; ?>
											</td>
										</tr>
										<tr>
											<th>
											<?php echo trans('receive_amount'); ?>:</th>
											<td>
												<?php echo $getSales['sales_pay_cash']; ?>
											</td>
										</tr>
										<tr>
											<th>
											<?php echo trans('pay_change'); ?>:</th>
											<td>
												<?php echo $getSales['sales_pay_change']; ?>
											</td>
										</tr>
										
										<tfoot>
											<tr>
												<td colspan="">
													<strong><?php echo trans('additional_notes'); ?></strong>
												</td>
											</tr>
											<tr style="background-color: #e7e4e4; height: 40px;">
												<td colspan="2">
													<?php echo $getSales['sales_note']; ?>
												</td>
											</tr>
										</tfoot>
									</table>
									
								</div>
							</div>
						</div>
					</div>
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default no-print" data-dismiss="modal">
						<?php echo trans('cancel'); ?>
					</button>
				</div>
			</div>
		</div>
	</div>
	<?php
		break;
		
		case "GetAddProduct":
	?>
	<style>
		.modal-dialog-extra {
		width: 85%;
		left: 8%;
		height: auto;
		margin: 0;
		padding: 0;
		}
		
		.modal-content-extra {
		height: auto;
		min-height: 100%;
		border-radius: 0;
		}
		th,tbody{text-align:center;}
		
	</style>
	
	<div class="modal fade show_modal_extend" tabindex="-2" role="dialog">
		<div class="modal-dialog modal-dialog-extra">
			<div class="modal-content modal-content-extra">
				<div class="modal-header">
					<button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h2 class="modal-title text-center" id="modalTitle"> <?php echo trans('add_product');?> </h2>
				</div>
				<div class="modal-body">
					<form>
						<input type="hidden" name="new_product" value="new" />
						<div class="row">
							<div class="col-sm-12">
								<div class="ibox">
									<div class="ibox-content">
										<div class="row">
											<div class="col-sm-4">
												<div class="form-group">
													<label><?php echo trans('product_name');?>:*</label>
													<input type="text" name="product_name" onclick="test()" class="form-control"  placeholder="<?php echo trans('product_name');?>" value="<?php if(isset($this->route['id'])) echo $GetProduct['product_name']; ?>">  
												</div>
											</div>
											<div class="col-sm-4">
												<div class="form-group">
													<label><?php echo trans('product_id');?>:*</label>
													<input type="text" class="form-control" name="product_id" id="product_id" placeholder="" value="">
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
													<input type="file" class="form-control-file" name="product_image" accept="image/*">
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
														<input type="checkbox" class="form-check-input" id="enable_stock" name="enable_stock" <?php if(isset($this->route['id'])){ if(!empty($GetProduct['alert_quantity'])) echo "checked"; } ?>> <strong><?php echo trans('manage_stock');?></strong>
													</label> <p class="help-block"><i><?php echo trans('manage_stock');?></i></p>
												</div>
											</div>
											<div class="col-sm-2" id="alert_quantity_div">
												<div class="form-group">
													<label><?php echo trans('product_alert');?> :*</label>
													<div class="input-group m-b">
														<input type="text" class="form-control" name="product_alert" placeholder="<?php echo trans('product_alert');?>" value="<?php if(isset($this->route['id'])) echo $GetProduct['alert_quantity']; ?>">
														<span class="input-group-addon"><span class="product_unit_show"></span></span>
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
														<option value="fixed">Vat in TK</option>
														<option value="percent">Vat in Percentage</option>
													</select>
												</div>
											</div>
											<div class="col-sm-4">
												<div class="form-group">
													<label><?php echo trans('vat');?> <span id="vat_label">(TK)</span> :</label>
													<input class="form-control" placeholder="<?php echo trans('vat_amount');?>" name="product_vat" type="text" value="<?php if(isset($route['id'])) echo $GetProduct['product_vat']; ?>">
												</div>
											</div>
											<div class="col-sm-2">
												<div class="form-group">
													<label class="">
														<input type="checkbox" class="" name="product_serial_num" <?php if(isset($this->route['id'])){ if($GetProduct['product_serial'] == "enable") echo "checked"; } ?>>
														<strong><?php echo trans('enable_imie_or_serial_number');?></strong>
													</label>
												</div>
											</div>
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
																<th><?php echo trans('x_margin_percent');?></th>
																<th><?php echo trans('default_selling_price');?></th>
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
																	<input class="form-control input-sm product_sales_value" placeholder="<?php echo trans('sales_price');?>" value="<?php if(isset($this->route['id'])) echo $getProductVariationSingle['sell_price']; ?>" name="single_product_sales_price" type="text">
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
																					<td><input type="text" name="product_profit_margine[]" data-id="<?php echo $getProductVariation['variation_name']; ?>" id="product_profit_margine_<?php echo $getProductVariation['variation_name']; ?>" class="form-control input-sm variation_change_profit_margin" placeholder="undefined" value="<?php echo $getProductVariation['profit_percent']; ?>"></td>
																					<td><input type="text" name="product_sales_price[]" id="product_sales_price_<?php echo $getProductVariation['variation_name']; ?>" class="form-control input-sm" placeholder="Sales Price" required="" value="<?php echo $getProductVariation['sell_price']; ?>"></td>
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
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default no-print" data-dismiss="modal">
						<?php echo trans('cancel'); ?>
					</button>
				</div>
			</div>
			
		</div>
		<script>
			$(document).on('click','.add_unit',function(){
				AS.Http.posthtml({"action" : "GetNewUnitModal",select_id : "product_unit"}, "pos/modal/", function (data) {
					$(".modal_status").html(data);
					$(".show_modal").modal("show");
				});
			});
			
			$(document).on('click','.add_category',function(){
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
								confirmButtonColor: "#1ab394", 
								confirmButtonText: $_lang.ok,
							});
						}
					});
				}
			});
			
			$(document).on("click",".add_new_brand", function(){
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
				var margine_profit_value = $("#product_profit_margine_"+Id).val();
				var product_purchase_value = $("#product_purchase_price_"+Id).val();
				var total_percent = margine_profit_value / 100 * product_purchase_value;
				$("#product_sales_price_"+Id).val(parseInt(product_purchase_value) + parseInt(total_percent));
			});
			
			$(document).on("keyup",".single_change_purchase", function(){
				var margine_profit_value = $(".single_change_profit_margin").val();
				var product_purchase_value = $(".product_purchase_value").val();
				var total_percent = margine_profit_value / 100 * product_purchase_value;
				$(".product_sales_value").val(parseInt(product_purchase_value) + parseInt(total_percent));
			});
			
			$(document).on("keyup",".variation_change_profit_margin", function(){
				var Id = $(this).data("id");
				var margine_profit_value = $("#product_profit_margine_"+Id).val();
				var product_purchase_value = $("#product_purchase_price_"+Id).val();
				var total_percent = margine_profit_value / 100 * product_purchase_value;
				$("#product_sales_price_"+Id).val(parseInt(product_purchase_value) + parseInt(total_percent));
			});
			
			$(document).on("keyup",".single_change_profit_margin", function(){
				var margine_profit_value = $(".single_change_profit_margin").val();
				var product_purchase_value = $(".product_purchase_value").val();
				var total_percent = margine_profit_value / 100 * product_purchase_value;
				$(".product_sales_value").val(parseInt(product_purchase_value) + parseInt(total_percent));
			});
			
			$(document).on("click",".added_new_variation", function(){
				var ProductId = $("#product_id").val();
				if(ProductId){
					CreateVariationRows(ProductId+'-',"","","","","");
					}else{
					CreateVariationRows("","","","","","");
				}
			});	
			
			function CreateVariationRows(sub_product_id,product_variation_value,product_purchase_price,product_profit_margine,product_sales_price,uid){
				var variationTable = document.getElementById('variation_value_table');
				var rowCnt = variationTable.rows.length; 
				var tr = variationTable.insertRow(rowCnt); 
				var i = uid || new Date().getTime();
				for (var c = 0; c <= 5; c++) {
					var td = document.createElement('td');      
					td = tr.insertCell(c);
					if(c==0){
						var ele = document.createElement('input');
						ele.setAttribute('type', 'text');
						ele.setAttribute('value', sub_product_id+rowCnt);
						ele.setAttribute('name', 'sub_product_id[]');
						ele.setAttribute('class', 'form-control input-sm');
						ele.setAttribute('placeholder', $_lang.sub_product_id);
						td.appendChild(ele);
						}else if(c==1){
						var ele = document.createElement('input');
						ele.setAttribute('type', 'text');
						ele.setAttribute('value', product_variation_value);
						ele.setAttribute('name', 'product_variation_value[]');
						ele.setAttribute('class', 'form-control input-sm');
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
						ele.setAttribute('placeholder', $_lang.profit_margine);
						td.appendChild(ele);
						}else if(c==4){
						var ele = document.createElement('input');
						ele.setAttribute('type', 'text');
						ele.setAttribute('value', product_sales_price);
						ele.setAttribute('name', 'product_sales_price[]');
						ele.setAttribute('id', "product_sales_price_"+i);
						ele.setAttribute('class', 'form-control input-sm');
						ele.setAttribute('placeholder', $_lang.sales_price);
						ele.setAttribute('required', "");
						td.appendChild(ele);
						}else if(c==5){
						var ele = document.createElement('a');
						ele.setAttribute('href', 'javascript:void(0);');
						ele.setAttribute('onclick', 'removeVariationRow(this);');
						ele.setAttribute('class', 'btn btn-danger btn-xs remove_variation_rows');
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
	</div>
	<?php
		break;
		case "GetProductViewByVariation":
		$variation_product = app('admin')->getwhereand('pos_variations','product_id',$_POST['product_id'],'variations_status','active');
	?>
	<div class="modal inmodal show_modal" tabindex="-1" role="dialog"  aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content animated fadeIn">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title"><?php echo trans('products_with_variation');?></h4>
				</div>
				<div class="modal-body">
					<?php
						// print_r($variation_product);
						// echo count($variation_product);
					?>
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th><?php echo trans('serial_no');?></th>
								<th><?php echo trans('product_id');?></th>
								<th><?php echo trans('product_name');?></th>
								<th><?php echo trans('purchase_price');?></th>
								<th><?php echo trans('sales_price');?></th>
								<th><?php echo trans('action');?></th>
							</tr>
						</thead>
						<tbody>
							<?php for($i=0;$i<count($variation_product);$i++){?>
								<tr>
									<td><?php echo $i+1; ?></td>
									<td><?php echo $variation_product[$i]['sub_product_id']; ?></td>
									<td><?php echo $_POST['product_name'].'['.$variation_product[$i]['variation_name'].']';?></td>
									<td><?php echo $variation_product[$i]['purchase_price'];?></td>
									<td><?php echo $variation_product[$i]['sell_price']?></td>
									<td>
										<button type="button" class="btn btn-danger btn-sm delete_variation_product" product-id="<?php echo $variation_product[$i]['sub_product_id'];?>"><i class="fa fa-trash"></i></button>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				<div class="modal-footer">
					<!--button type="submit" class="btn btn-primary"><?php echo trans('save');?></button-->
					<button type="button" class="btn btn-white" data-dismiss="modal"><?php echo trans('close');?></button>
				</div>
			</div>
		</div>
	</div>
	<script>
		$('.delete_variation_product').click(function(){
			var p_id = $(this).attr('product-id');
			
			$(this).parent().parent().remove();
			jQuery.ajax({
				url: "pos/ajax/",
				data: {
					action	: "deleteVariationProduct",
					sub_product_id	: p_id
				},
				success:function(res){
					// $(this).parent().parent().remove();
				},
			});
		});
	</script>
	<?php
		break;
		case "GetPaymentModal":
	?>
	<div class="row">
		<div class="col-xs-8">
			<h3><?php echo trans('payment_info'); ?></h3>
			
			<div class="table-responsive">
				<table class="table table-bordered table-striped table-xs">
					<thead>
						<th>
							<?php echo trans('id'); ?>
						</th>
						<th>
							<?php echo trans('date'); ?>
						</th>
						<th>
							<?php echo trans('amount'); ?>
						</th>
						<th>
							<?php echo trans('received_by'); ?>
						</th>
						<th>
							<?php echo trans('payment_method'); ?>
						</th>
						<th>
							<?php echo trans('transaction_id'); ?>
						</th>
						<th>
							<?php echo trans('payment_note'); ?>
						</th> 
						<th>
							<?php echo trans('action'); ?>
						</th>
					</thead>
					<tbody class="text-center" id="payment_modal_table">
						<?php 
							$paymentTransactions = app('db')->table('pos_transactions');
							$paymentTransactions = $paymentTransactions->where($_POST['where_name'],$_POST['where_value']);
							$paymentTransactions = $paymentTransactions->where('transaction_type',$_POST['where_type']);
							$paymentTransactions = $paymentTransactions->where('is_delete','false');
							$paymentTransactions = $paymentTransactions->get(); 
							$totalAmount = 0;
							foreach($paymentTransactions as $paymentTransaction){
								$totalAmount += $paymentTransaction['transaction_amount'];
							?>
							<tr id="<?php echo $paymentTransaction['transaction_id']; ?>">
								<td><small><?php echo $paymentTransaction['transaction_id']; ?></small><input type="hidden" value="<?php echo $paymentTransaction['transaction_id']; ?>" name="transaction_id[]"></td>
								<td><?php echo getdatetime($paymentTransaction['created_at'],3); ?></td>
								<td><span><?php echo $paymentTransaction['transaction_amount']; ?></span><input type="hidden" value="<?php echo $paymentTransaction['transaction_amount'];  ?>" name="transaction_amount[]"></td>
								<?php
									$GetuserInfo = app('admin')->getuserdetails($paymentTransaction['user_id']);
								?>
								<td><small><?php echo $GetuserInfo['first_name'].' '.$GetuserInfo['last_name']; ?></small></td>
								<td><small><?php echo ucfirst($paymentTransaction['payment_method_value']); ?></small><input type="hidden" value="<?php echo $paymentTransaction['payment_method_value']; ?>" name="transaction_method[]"></td>
								<td><small><?php echo $paymentTransaction['transaction_no']; ?></small><input type="hidden" value="<?php echo $paymentTransaction['transaction_no']; ?>" name="transaction_no[]"></td>
								<td><small><?php echo $paymentTransaction['transaction_note']; ?></small><input type="hidden" value="<?php echo $paymentTransaction['transaction_note']; ?>" name="transaction_note[]"></td>
								<td><a href="javascript:void(0);" data-transaction-id="<?php echo $paymentTransaction['transaction_id']; ?>" class="btn btn-danger btn-xs payment_modal_delete"><i class="fa fa-trash"></i></a></td>
							</tr>
						<?php } ?>
						
					</tbody>
					<tfoot>
						<tr>
							<td colspan="2" style="background:gray; color:white;" id="total_payment">Total Payment : <?php echo $totalAmount; ?></td>
							<td colspan="4"></td>
							<td colspan="2" style="background:crimson; color:white;" id="total_due_amount">Total Due : 0</td>
							<input type="hidden" value="<?php echo $totalAmount; ?>" id="total_payment_amount" />
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
		
		<div class="col-xs-4">
			<h3><?php echo trans('add_payment'); ?></h3>
			<div id="payment_form">
				<div class="row">
					<div class="col-xs-12 col-sm-6">
						<div class="form-group">
							<label class="control-label">
							<?php echo trans('amount'); ?>:*</label>
							<div class="input-group">
								<span class="input-group-addon"> <i class="fa fa-credit-card"></i> </span>
								<input type="text" class="form-control onchange_purchase_final_cal" id="payment_amount" placeholder="<?php echo trans('amount'); ?>">
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6">
						<div class="form-group">
							<label class="control-label">
							<?php echo trans('payment_method'); ?>:*</label>
							<div class="input-group">
								<span class="input-group-addon"> <i class="fa fa-credit-card"></i> </span>
								<select class="form-control payment_method" id="payment_method">
									<?php $paymentMethods = app('admin')->getwhere("pos_payment_method","payment_method_status","active"); foreach($paymentMethods as $paymentMethod){ ?>
										<option value="<?php echo $paymentMethod['payment_method_value']; ?>">
											<?php echo $paymentMethod['payment_method_name']; ?>
										</option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>
					<div class="custom_transaction_id hide">
						<div class="col-xs-12">
							<div class="form-group">
								<label class="control-label">
									<?php echo trans('transaction_id'); ?>
								</label>
								<input type="text" class="form-control" placeholder="<?php echo trans('transaction_id'); ?>" id="payment_transaction_id">
							</div>
						</div>
					</div>
					<div class="col-xs-12">
						<div class="form-group">
							<label class="control-label">
								<?php echo trans('note'); ?>
							</label>
							<textarea class="form-control" id="payment_note"></textarea>
						</div>
					</div>
					<div class="col-xs-12">
						<div class="form-group">
							<button type="button" class="btn btn-success pull-right payment_modal_complete">
								<?php echo trans('add_payment'); ?>
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script>
			$(document).ready(function(){
				
			});
			
			$('.payment_method').change(function(){
				var type= $('.payment_method').val();
				$('.custom_transaction_id').addClass('hide');
				if(type == 'cash'){
					$('.custom_transaction_id').addClass('hide');
					}else{
					$('.custom_transaction_id').removeClass('hide');
				}
			});
			
			
			$(document).on("click",".payment_modal_complete", function(){
				AS.Util.removeErrorMessages();
				
				var received_by = <?php echo '"'.$this->currentUser->first_name.' '.$this->currentUser->last_name.'"'; ?>;
				var total_payment_amount = $('#total_payment_amount').val();
				var payment_amount = $('#payment_amount').val();
				var paymentMethod = $('select[id="payment_method"] option:selected');
				var payment_method_text = paymentMethod.text();
				var payment_method_value = paymentMethod.val();
				var payment_transaction_id = $('#payment_transaction_id').val();
				var payment_note = $('#payment_note').val();
				var generator = new IDGenerator();
				var transaction_id = "TXN"+generator.generate(); 
				var paymentData = {'payment_amount' : payment_amount,'payment_method_text' : payment_method_text,'payment_method_value' : payment_method_value,'payment_transaction_id' : payment_transaction_id,'payment_note' : payment_note,'transaction_id' : transaction_id,'received_by' : received_by,'total_payment_amount' : total_payment_amount};
				var totalAmountCheck = parseFloat(total_payment_amount) - parseFloat(GetTotalTransaction())
				
				
				if( totalAmountCheck < payment_amount){
					AS.Util.displayErrorMessage($('#payment_amount'), 'Over Payment');
					}else{
					if(payment_amount == ""){
						AS.Util.displayErrorMessage($('#payment_amount'), 'Payment Amount Required');
						}else if(payment_amount != 0){
						AddPaymentListRow(paymentData);
					}
				}
				
			});
			
			function AddPaymentListRow(data){
				var paymentTable = document.getElementById('payment_modal_table');
				var rowCnt = paymentTable.rows.length; 
				var tr = paymentTable.insertRow(rowCnt); 
				tr.setAttribute('id', data.transaction_id);
				for (var c = 0; c < 8; c++) {
					var td = document.createElement('td');      
					td = tr.insertCell(c);
					if(c==0){
						var span_text = document.createElement('small');
						span_text.append(data.transaction_id);
						td.appendChild(span_text);
						
						var ele = document.createElement('input');
						ele.setAttribute('type', 'hidden');
						ele.setAttribute('value', data.transaction_id);
						ele.setAttribute('name', 'transaction_id[]');
						td.appendChild(ele);
						
						}else if(c==1){
						
						td.append(GetDateTime());
						
						}else if(c==2){
						
						var span_text = document.createElement('span');
						span_text.append(data.payment_amount);
						td.appendChild(span_text);
						
						var ele = document.createElement('input');
						ele.setAttribute('type', 'hidden');
						ele.setAttribute('value', data.payment_amount);
						ele.setAttribute('name', 'transaction_amount[]');
						td.appendChild(ele);
						
						}else if(c==3){
						
						var span_text = document.createElement('small');
						span_text.append(data.received_by);
						td.appendChild(span_text);
						
						}else if(c==4){
						
						var span_text = document.createElement('small');
						span_text.append(data.payment_method_text);
						td.appendChild(span_text);
						
						var ele = document.createElement('input');
						ele.setAttribute('type', 'hidden');
						ele.setAttribute('value', data.payment_method_value);
						ele.setAttribute('name', 'transaction_method[]');
						td.appendChild(ele);
						
						}else if(c==5){
						
						var span_text = document.createElement('small');
						span_text.append(data.payment_transaction_id);
						td.appendChild(span_text);
						
						var ele = document.createElement('input');
						ele.setAttribute('type', 'hidden');
						ele.setAttribute('value', data.payment_transaction_id);
						ele.setAttribute('name', 'transaction_no[]');
						td.appendChild(ele);
						
						}else if(c==6){
						
						var span_text = document.createElement('small');
						span_text.append(data.payment_note);
						td.appendChild(span_text);
						
						var ele = document.createElement('input');
						ele.setAttribute('type', 'hidden');
						ele.setAttribute('value', data.payment_note);
						ele.setAttribute('name', 'transaction_note[]');
						td.appendChild(ele);
						
						}else if(c==7){
						
						var ele = document.createElement('a');
						ele.setAttribute('href', 'javascript:void(0);');
						ele.setAttribute('class', 'btn btn-danger btn-xs payment_modal_delete');
						var ele_icon = document.createElement('i');
						ele_icon.setAttribute('class', 'fa fa-trash');
						ele.appendChild(ele_icon);
						td.appendChild(ele);
						
					}
				}
				GetTotalTransaction();
				$('#payment_amount').val('');
				$('select[id="payment_method"]').prop('selectedIndex',0);;
				$('#payment_transaction_id').val('');
				$('#payment_note').val('');
				$('.custom_transaction_id').addClass('hide');
				if (typeof paymentAddUpdate === "function")
				{
					paymentAddUpdate();
				}
				
			}
			
			$(document).on("click",".payment_modal_delete", function(){
				$(this).parent('td').parent('tr').remove();
				var transactionId = $(this).data('transaction-id');
				AS.Http.post({"action" : "GetDeleteTransactionId","transaction_id" : transactionId}, "pos/ajax/", function (response) {
					
				});
				
				GetTotalTransaction();
			});
			
			function GetTotalTransaction(){
				
				$('#total_payment_amount').val($('.transaction-total-bill').val() || 0);
				
				var transaction_amount = 0;
				$('input[name="transaction_amount[]"]').each(function() {
					transaction_amount +=  parseFloat($(this).val());
				});
				$('#total_payment').html('Total Paid : '+parseFloat(transaction_amount).toFixed(2));
				var	total_payment_amount = $('#total_payment_amount').val();
				var totalDue = total_payment_amount - transaction_amount;
				$('#total_due_amount').html('Total Due : '+parseFloat(totalDue).toFixed(2));
				if('.payment-total-value'){
					$('.payment-total-value').val(parseFloat(transaction_amount).toFixed(2));
				}
				return transaction_amount;
			}
			
			function GetTransactionReset(){
				
				$('#payment_modal_table').find('tr').remove(); 
				GetTotalTransaction();
			}
			
			
			function GetDateTime(){
				var today = new Date();
				var dd = today.getDate();
				var yyyy = today.getFullYear();
				var month = today.toLocaleString('en-us', { month: 'long' });
				
				if(dd<10) 
				{
					dd='0'+dd;
				} 
				today = dd+' '+month+' '+yyyy;
				
				return today;
			}
			
			
		</script>
	</div>
	<?php
		
		break;
		case 'GetNewStore':
	?>
	<div class="modal inmodal show_modal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12 AddNewStore">
							<form>
								<h3 class="m-t-none m-b"><?php echo trans('store'); ?></h3>
								
								<?php if(isset($_POST['store_id'])) echo '<input type="hidden" name="store_id" value="'.$_POST['store_id'].'">';?>
								<div class="form-group">
									<label class="control-label"><?php echo trans('warehouse'); ?>: </label>
									<select class="form-control" name="warehouse" id="warehouse">
										<option value=""><?php echo trans('select_warehouse'); ?></option>
										<?php $warehouses = app('admin')->getwhere('pos_warehouse','warehouse_status','active');
											foreach($warehouses as $warehouse){ 
												echo "<option value='".$warehouse['warehouse_id']."'>".$warehouse['warehouse_name']."</option>";
											} 
										?>
									</select>
								</div>
								
								<div class="form-group">
									<label class="control-label"><?php echo trans('store_name'); ?>: </label>
									<input type="text" class="form-control" id="store_name" name="store_name">
								</div>
								
								<div class="form-group">
									<label class="control-label"><?php echo trans('store_location'); ?>: </label>
									<textarea class="form-control" name="store_location" id="store_location"></textarea>
								</div>
								
								<button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit"><strong><?php echo trans('submit'); ?></strong></button>
								<a class="btn btn-sm btn-danger pull-right m-t-n-xs" data-dismiss="modal"><strong><?php echo trans('cancel'); ?></strong></a>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		$('.AddNewStore form').validate({
			rules: {
				warehouse: {
					required: true
				},
				store_name: {
					required: true
				},
				store_location: {
					required: true
				}
			},  
			submitHandler: function(form) {
				AS.Http.PostSubmit(form, {"action" : "AddNewStoreSumbit"}, "pos/ajax/", function (response) {
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
	</script>
	
	<?php 
		
		break;
		case "GetNewWarehouse":
	?>
	
	<div class="modal inmodal show_modal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12 AddNewWarehouse">
							<form>
								<h3 class="m-t-none m-b"><?php echo trans('warehouse'); ?></h3>
								
								<?php if(isset($_POST['warehouse_id'])) echo '<input type="hidden" name="warehouse_id" value="'.$_POST['warehouse_id'].'">';?>
								<div class="form-group">
									<label class="control-label"><?php echo trans('warehouse_name'); ?>: </label>
									<input type="text" class="form-control" id="warehouse_name" name="warehouse_name">
								</div>
								
								<div class="form-group">
									<label class="control-label"><?php echo trans('warehouse_location'); ?>: </label>
									<textarea class="form-control" name="warehouse_location" id="warehouse_location"></textarea>
								</div>
								
								<button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit"><strong><?php echo trans('submit'); ?></strong></button>
								<a class="btn btn-sm btn-danger pull-right m-t-n-xs" data-dismiss="modal"><strong><?php echo trans('cancel'); ?></strong></a>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		$('.AddNewWarehouse form').validate({
			rules: {
				warehouse_name: {
					required: true
				},
				warehouse_location: {
					required: true
				}
			},  
			submitHandler: function(form) {
				AS.Http.PostSubmit(form, {"action" : "AddNewWarehouseSumbit"}, "pos/ajax/", function (response) {
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
	</script>
	
	<?php
		
		break;
	case "GetInvoiceSettingModal": ?>
	<div class="modal inmodal show_modal" tabindex="-1" role="dialog"  aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content animated fadeIn Invoice-Setting">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title"><?php echo trans('invoice_setting');?></h4>
					
				</div>
				<form enctype="multipart/form-data">
					<div class="modal-body">
						<?php if(isset($_POST['id'])){
							if($_POST['id']=='INV-1'){
							?>
							<input type="hidden" name="invoice_id" value="INV-1">
							<input type="hidden" name="invoice_title" value="Invoice 1">
							<div class="form-group">
								<div class="row">
									<div class="col-sm-3">
										<label><?php echo trans('top');?></label>
									</div>
									<div class="col-sm-9">
										<input type="text" class="form-control" name="invoice_top" id="invoice_top" value="">
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-sm-3">
										<label><?php echo trans('bottom');?></label>
									</div>
									<div class="col-sm-9">
										<input type="text" class="form-control" name="invoice_bottom" id="invoice_bottom" value="">
									</div>
								</div>
							</div>
							<?php } else if($_POST['id']=='INV-2'){?>
							<input type="hidden" name="invoice_id" value="INV-2">
							<input type="hidden" name="invoice_title" value="Invoice 2">
							<div class="form-group">
								<div class="row">
									<div class="col-sm-3">
										<label><?php echo trans('invoice_header');?></label>
									</div>
									<div class="col-sm-9">
										<input type="file" class="form-control-file" name="invoice_header" id="invoice_header" value="">
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-sm-3">
										<label><?php echo trans('invoice_footer');?></label>
									</div>
									<div class="col-sm-9">
										<input type="file" class="form-control-file" name="invoice_footer" id="invoice_footer" value="">
									</div>
								</div>
							</div>
							<?php } else if($_POST['id']=='INV-3'){?>
							<input type="hidden" name="invoice_id" value="INV-3">
							<input type="hidden" name="invoice_title" value="Invoice 3">
							<div class="form-group">
								<div class="row">
									<div class="col-sm-3">
										<label><?php echo trans('invoice_logo');?></label>
									</div>
									<div class="col-sm-9">
										<input type="file" class="form-control-file" name="invoice_logo" id="invoice_logo" value="">
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-sm-3">
										<label><?php echo trans('footer_note');?></label>
									</div>
									<div class="col-sm-9">
										<textarea name="invoice_footer_note" class="form-control" id="invoice_footer_note"></textarea>
									</div>
								</div>
							</div>
						</div>
						<?php }}?>
						<div class="modal-footer">
							<button type="submit" class="btn btn-primary" ><?php echo trans('save');?></button>
							<button type="button" class="btn btn-white" data-dismiss="modal"><?php echo trans('close');?></button>
						</div>
				</form>
			</div>
		</div>
	</div>
	<script>
		$('.Invoice-Setting form').validate({
			rules: {
				invoice_top: {
					required: true
				},
				invoice_bottom: {
					required: true
				},
				invoice_header: {
					required: true
				},
				invoice_footer: {
					required: true
				},
				invoice_logo: {
					required: true
				},
				invoice_footer_note: {
					required: true
				},
				
			},  
			submitHandler: function(form) {
				AS.Http.PostSubmit(form, {"action" : "InvoiceSettingSubmit"}, "pos/ajax/", function (response) {
					if(response.status=='success'){
						swal({
							title: $_lang.success, 
							text: response.message, 
							type: "success",
							confirmButtonColor: "#1ab394", 
							confirmButtonText: $_lang.ok,
							},function(isConfirm){
							if(isConfirm){
								location.reload();
							}
						});
					}
				});
			}
		});
	</script>
	<?php
		break;
		
	case "AddChartAccount": ?>
	
	<div class="modal fade in show_modal">
		<div class="modal-dialog">
			<div class="modal-content account_chart">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<div class="modal-title">
						<h3 class="">
							<?php echo trans('account_chart');?>
						</h3>
					</div>
				</div>
				<form>
					<div class="modal-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
									<?php if(isset($_POST['account_id'])){ ?>
										<input type="hidden" name="account_id" value="<?php echo $_POST['account_id']; ?>">
									<?php }?>
									<label class="control-label">
										<?php echo trans('chart_type'); ?>
									</label>
									<select class="form-control" name="chart_category" id="chart_category">
										<option value=""><?php echo trans('select_category'); ?></option>
										<?php
											// $categoryType='';
											
											// if($_POST['subcategory'] == 'account_income'){
											// $categoryType = 'income_statement';
											// }
											// else{
											// $categoryType = '';
											// }
											$categorys = app('db')->table('accounts_chart_category')->get();
											foreach($categorys as $category){
											?>
											
											<option value="<?php echo $category['chart_category_name'];?>"><?php echo trans($category['chart_category_name']); ?></option>
											
										<?php } ?>
									</select>
								</div>
								<div class="form-group" >
									<label><?php echo trans('account_no');?></label>
									<input class="form-control" name="chart_no" placeholder="<?php echo trans('enter_chart_no'); ?>" id="account_no">
								</div>
								<div class="form-group" >
									<label><?php echo trans('account_title');?></label>
									<input class="form-control chart_title" name="chart_title" placeholder="<?php echo trans('enter_chart_title'); ?>" id="account_title">
									<input type="hidden" name="chart_name_value" class="chart_name_value">
									<input type="hidden" name="chart_sub_category_name" value="<?php echo $_POST['subcategory']; ?>">
									<input type="hidden" name="chart_type" value="<?php echo $_POST['type'];?>">
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer" >
						<button type="button" class="btn btn-white" data-dismiss="modal"><?php echo trans('close');?></button>
						<button type="submit" class="btn btn-default"><?php echo trans('save'); ?></button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$('.account_chart form').validate({
			rules: {
				chart_type: {
					required: true
				},
				chart_no: {
					required: true
				},
				chart_title: {
					required: true
				}
			},  
			submitHandler: function(form) {
				AS.Http.PostSubmit(form, {"action" : "AccountChartSubmit"}, "pos/ajax/", function (response) {
					if(response.status == 'success'){
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
		$(document).on('keyup','.chart_title',function(){
			var title = $(this).val();
			var c_val = 'account_'+title.replace(/\s/g, '_').toLowerCase();
			$(".chart_name_value").val(c_val);
		});
	</script>
	<?php 
		break;
		case "GetNewAccountUserModal":
	?>
	<div class="modal show_modal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12 AddContactForm">
							<form>
								<h3 class="m-t-none m-b"><?php echo trans('add_new_account_user'); ?> </h3>
								<div class="form-group row">
									<div class="col-sm-12">
										
										<label><?php echo trans('name'); ?></label>
										<input type="text" name="name" class="form-control" id="name" value="<?php if(isset($_POST['customer_name'])) echo $_POST['customer_name']; ?>" placeholder="<?php echo trans('name'); ?>">
										<input type="hidden" name="contact_id" id="contact_id" value="<?php if(isset($_POST['contact_id'])) echo $_POST['contact_id']; ?>">
										
										<input type="hidden" id="total_due" value="<?php echo $_POST['due_total_amount']; ?>">
										<input type="hidden" id="total_advance" value="<?php echo $_POST['advanced_total_amount']; ?>">
										<input type="hidden" id="check_advance" value="<?php echo $_POST['advance_check']; ?>">
										<input type="hidden" id="check_due" value="<?php echo $_POST['due_check']; ?>">
										<input type="hidden" id="store_location" value="<?php echo $_POST['store']; ?>">
										<input type="hidden" id="account_purpose" value="<?php echo $_POST['purpose']; ?>">
										<input type="hidden" id="account_date" value="<?php echo $_POST['date']; ?>">
										<input type="hidden" id="status" value="<?php echo $_POST['status']; ?>">
										<input type="hidden" id="type" value="<?php echo $_POST['account_type'];?>">
									</div>
								</div>
								<div class="form-group row business">
									<div class="col-sm-6">
										<label><?php echo trans('business_name'); ?></label>
										<input type="text" name="business_name" class="form-control" id="business_name" placeholder="<?php echo trans('business_name'); ?>">
									</div>
									<div class="col-sm-6">
										<label><?php echo trans('website_name'); ?></label>
										<input type="text" name="website_name" class="form-control" id="website_name" placeholder="<?php echo trans('website_name'); ?>">
									</div>
								</div>
								<div class="form-group row">
									<div class="col-sm-6">
										<label><?php echo trans('phone'); ?></label>
										<input type="text" name="phone" class="form-control" id="phone" placeholder="<?php echo trans('phone'); ?>">
									</div>
									<div class="col-sm-6">
										<label><?php echo trans('email'); ?></label>
										<input type="text" name="email" class="form-control" id="email" placeholder="<?php echo trans('email'); ?>">
									</div>
								</div>
								<div class="form-group">
									<label><?php echo trans('address'); ?></label>
									<textarea name="address" class="form-control" id="address" placeholder="<?php echo trans('address'); ?>"></textarea>
								</div>
								<button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit"><strong><?php echo trans('submit'); ?></strong></button>
								<button class="btn btn-sm btn-danger pull-right m-t-n-xs new_contact_close" type="button" ><strong><?php echo trans('cancel'); ?></strong></button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		var store = $("#store_location").val();
		var purpose = $("#account_purpose").val();
		var date = $("#account_date").val();
		var status = $("#status").val();
		var dueTotalAmount = $("#total_due").val();
		var advanceTotalAmount = $("#total_advance").val();
		var advanceCheck = $("#check_advance").val();
		var dueCheck = $("#check_due").val();
		var Type = $("#type").val();
		$('.AddContactForm form').validate({
			rules: {
				name: {
					required: true
				},
				phone: {
					required: true,
					number: true
				},
				email: {
					email: true
				}
			},	
			submitHandler: function(form) {
				AS.Http.PostSubmit(form, {"action" : "GetAccountUserData"}, "pos/ajax/", function (response) {
				
				
					if(response.status == 'success'){
						$(".show_modal").remove();
						$(".modal-backdrop").remove();
						
					if(purpose!=''){
						AS.Http.posthtml({"action" : "AddAccount","add_account_type" : Type}, "pos/modal/", function (data) {
							$(".modal_status").html(data);
						
    							$("#user_code").val(response.name);
    							$("#prayer").val(response.contact_id);
    							
    							$("#business_location").val(store);;
    							$("#category").val(purpose);
    							$("#date").val(date);
    							$("#account_status").val(status);
    							$("#due_total_amount").val(dueTotalAmount);
    							$("#advanced_total_amount").val(advanceTotalAmount);
    							$("#advance_check").val(advanceCheck);
    							$("#due_check").val(dueCheck);
    							$("#account_type").val(Type);
						
							$(".show_modal").modal("show");
						});
					}else{
					    DataTable(true);
					}
						
					}
					
				});
			}
		});	
		
		$(document).on('click','.new_contact_close',function(){
			// alert(date);
			// return false;
			$(".show_modal").remove();
			$(".modal-backdrop").remove();
			if(Type!=''){
				AS.Http.posthtml({"action": "AddAccount","add_account_type": Type}, "pos/modal/", function(data) {
					$(".modal_status").html(data);
					$("#business_location").val(store);;
					$("#category").val(purpose);
					$("#date").val(date);
					$("#account_status").val(status);
					$("#due_total_amount").val(dueTotalAmount);
					$("#advanced_total_amount").val(advanceTotalAmount);
					$("#advance_check").val(advanceCheck);
					$("#due_check").val(dueCheck);
					$("#account_type").val(Type);
					
					$(".show_modal").modal("show");
				});
			}
			
			
		});
		
	</script>
	<?php	
		break;
		case 'AddAccount':
		
	?>
	<div class="modal inmodal show_modal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12 AddAccount">
							<form>
								<h3 class="m-t-none m-b"><?php echo trans('account'); ?>
								</h3>
								
								<?php if(isset($_POST['account_id'])) echo '<input type="hidden" name="account_id" value="'.$_POST['account_id'].'">';?>
								<?php if(isset($_POST['attached_document']) && $_POST['attached_document']!=null) echo '<input type="hidden" name="attached_document" value="'.$_POST['attached_document'].'">';?>
								
								<div class="row">
									<?php
										if(app('admin')->checkAddon('multiple_store_warehouse')){
										?>
										<div class="col-sm-6">
											<div class="form-group">
												<label class="control-label"><?php echo trans('business_location'); ?>: </label>
												<select class="form-control" name="business_location" id="business_location">
													<?php $posStores = app('admin')->getwhere('pos_store','store_status','active'); foreach($posStores as $posStore){	?>
														<option value="<?php echo $posStore['store_id']; ?>" <?php if($posStore['store_id'] == $this->currentUser->store_id) echo "selected"; ?>><?php echo $posStore['store_name']; ?></option>
													<?php } ?>
												</select>
											</div>
										</div>
									<?php } ?>
									<div class="col-sm-6">
										<div class="form-group">
											<label class="control-label"><?php echo $_POST['add_account_type']=='account_income' ? trans('income') : trans('expense'); ?> <?php echo trans('purpose');?>: </label>
											<div class="input-group">
												<select class="form-control checkDueAdvance" name="chart_category" id="category">
													<option value="0"><?php echo trans('select');?> <?php echo $_POST['add_account_type']=='account_income' ? trans('income') : trans('expense'); ?></option>
													<?php 
														
														$accounts_category = app('db')->table('accounts_chart')->find('account_group','"'.$_POST['add_account_type'].'"')->where('is_delete','false')->get();
														foreach($accounts_category as $accounts_categorys){ 
															echo "<option value='".$accounts_categorys['chart_name_value']."'>".trans($accounts_categorys['chart_name'])."</option>";
														}
													?>
												</select>
											</div>
										</div>
									</div>
									
									<div class="col-sm-6">
										<div class="form-group" id="data_1">
											<label class="control-label"><?php echo $_POST['add_account_type']=='account_income' ? trans('income') : trans('expense'); ?> <?php echo trans('date'); ?>:</label>
											<div class="input-group date">
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												<input type="text" class="form-control" name="date" id="date" readonly value="<?php echo date('Y-m-d');?>">
											</div>
										</div>
									</div>
									
									<div class="col-sm-6">
										<div class="form-group">
											<label class="control-label"><?php echo trans('status'); ?>:</label>
											
											<select class="form-control account_status" id="account_status" name="account_status">
												<option value="paid"><?php echo trans("paid");?></option>
												<option value="due"><?php echo trans("due");?></option>
												<option value="advance"><?php echo trans("advanced");?></option>
											</select>
											
										</div>
									</div>
									<div class="col-sm-12">
										<div class="form-group" id="data_1">
											<label class="control-label payer_name"><?php echo trans('prayer');?>:</label>
											
											<div class="input-group">
												<span class="input-group-addon"> <i class="fa fa-user"></i> </span>
												<input type="text" class="form-control prayer_search checkDueAdvance" name="prayer_name" id="user_code" placeholder="<?php echo trans('prayer').' '.trans('name');?>" autocomplete="off">
												<input type="hidden" name="prayer" id="prayer" autocomplete="off">
												<div class="input-group-btn">
													<button type="button"  class="btn btn-default OpenContactModal"  title="<?php echo trans('new_contact'); ?>" ><i class="fa fa-plus-circle text-primary fa-lg"></i></button>
												</div>
											</div>
										</div>
									</div>
									
									<div class="col-sm-12 PaymentConditionSection m-t-sm m-b-sm">
										<div class="row">
											<div class="col-sm-6">
												<label>
													<input type="checkbox" name="due_purpsoe_payment" id="due_purpsoe_payment"> <?php echo trans('due_purpose_payment');?>
												</label>
											</div>
											<div class="col-sm-6">
												<label>
													<input type="checkbox" name="advanced_purpsoe_payment" id="advanced_purpsoe_payment"> <?php echo trans('advanced_purpsoe_payment');?>
													<input type="hidden" id="due_total_amount" name="due_total_amount">
													<input type="hidden" id="advanced_total_amount" name="advanced_total_amount">
													<input type="hidden" id="advance_check" name="advance_check">
													<input type="hidden" id="due_check" name="due_check">
												</label>
											</div>
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<label class="control-label" for="date_from"><?php echo trans('total_amount'); ?>:</label>
											<input type="text" class="form-control checkDueAdvanceAmount" name="total_amount" id="total_amount">
										</div>
									</div>
									<div class="col-sm-4 PaymentMethodSection">
										<div class="form-group">
											<label class="control-label"><?php echo trans('method'); ?>: </label>
											<select class="form-control payment_method" name="method" id="method">
												<option value=""><?php echo trans('method'); ?></option>
												<?php 
													$methods = app('admin')->getwhere('pos_payment_method','payment_method_status','active');
													foreach($methods as $method){ 
														echo "<option value='".$method['payment_method_value']."'>".$method['payment_method_name']."</option>";
													} 
												?>
											</select>
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<label class="control-label"><?php echo trans('attache_document'); ?>: </label>
											<input type="file" class="form-control-file" id="attach_document" name="attach_document">
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
											<label class="control-label"><?php echo trans('Note'); ?>: </label>
											<textarea class="form-control" name="note" rows="3" id="note" ></textarea>
										</div>
									</div>
								</div>
								<input type="hidden" name="account_type" id="account_type" class="chart_subcategory" value="<?php echo $_POST['add_account_type'];?>">
								<button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit"><strong><?php echo trans('submit'); ?></strong></button>
								<button class="btn btn-sm btn-danger pull-right m-t-n-xs new_account_close" data-dismiss="modal" aria-label="Close" type="button" ><strong><?php echo trans('cancel'); ?></strong></button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<script>
		
		var $input = $("#user_code");
		
		$input.typeahead({
			source: function(query, result)
			{
				delay(function(){
					$.ajax({
						url:"pos/ajax/",
						method:"POST",
						data:{"action" : "GetSearchAccountUserByNameId","customer_code":query},
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
			autoSelect: false,
			hint: true,
			minLength: 2,﻿
			cache: false,
			debug: false
		});
		
		
		$input.change(function(e) {
			var current = $input.typeahead("getActive");
			if (current) {
				$("#user_code").val(current.name);
				$("#prayer").val(current.customer_id);
			}
			e.preventDefault();
		});
		
		
		var delay = (function(){
			var timer = 0;
			return function(callback, ms){
				clearTimeout (timer);
				timer = setTimeout(callback, ms);
			};
		})();
		
		$(".checkDueAdvance").change(function(e) {
			var chartCategory = $("#category").val() || 0;
			var businessLocation = $("#business_location").val() || 0;
			var userCode = $("#prayer").val() || 0;
			var totalAmount = $("#total_amount").val() || 0;
			var accountType = $("#account_type").val();
			$("#advanced_purpsoe_payment").prop('checked',false);
			$("#due_purpsoe_payment").prop('checked',false);
			$("#advanced_purpsoe_payment").attr('disabled','true');
			$("#due_purpsoe_payment").attr('disabled','true');	
			AS.Http.post({
				"action" : "CheckaccountDueAdvance", 
				"chart_category" : chartCategory,
				"business_location" : businessLocation,
				"total_amount" : totalAmount,
				"account_type" : accountType,
				"user_code" : userCode
				}, "pos/ajax/", function (data) {
				if(data.status == 'found'){
					$("#due_total_amount").val(data.due_amount);
					$("#advanced_total_amount").val(data.advance_amount);
					$("#advance_check").val(data.is_advance);
					$("#due_check").val(data.is_due);
					if(data.is_advance == 'true'){
						$("#advanced_purpsoe_payment").removeAttr('disabled','');
					}
					
					if(data.is_due == 'true'){
						$("#due_purpsoe_payment").removeAttr('disabled','');
					}
					
				}
			});
		});
		
		$(document).on('keyup','.checkDueAdvanceAmount',function(){
			AS.Util.removeErrorMessages();
			var totalAmount = $(this).val();
			
			var due_total_amount = $("#due_total_amount").val() || 0;
			var advanced_total_amount = $("#advanced_total_amount").val() || 0;
			
			
			let dueFlag = $("#due_purpsoe_payment").prop('checked');
			let advanceFlag = $("#advanced_purpsoe_payment").prop('checked');
			
			if(dueFlag == true){
				if(parseFloat(totalAmount) > parseFloat(due_total_amount)){
					AS.Util.ShowErrorByElement("total_amount", "Due amount not more then "+due_total_amount);
				}
				
			}
			
			if(advanceFlag == true){
				if(parseFloat(totalAmount) > parseFloat(advanced_total_amount)){
					AS.Util.ShowErrorByElement("total_amount", "Advanced amount not more then "+advanced_total_amount)
				}
			}
			
		});
		
		$(document).on('click',".OpenContactModal",function(){
			var store = $("#business_location").val() || 1;
			var purpose = $("#category").val() || 0;
			var date = $("#date").val();
			var status = $("#account_status").val() || 'paid';
			
			var dueTotalAmount = $("#due_total_amount").val() || 0;
			var advanceTotalAmount = $("#advanced_total_amount").val() || 0;
			var advanceCheck = $("#advance_check").val() || 0;
			var dueCheck = $("#due_check").val() || 0;
			var acType = $("#account_type").val();
			// alert(acType);
			// return false;
			$(".show_modal").remove();
			$(".modal-backdrop").remove();
			
			AS.Http.posthtml({"action" : "GetNewAccountUserModal",
				"store" : store,"purpose": purpose,"date": date, "status": status,
				"due_total_amount" : dueTotalAmount,
				"advanced_total_amount" : advanceTotalAmount,
				"advance_check" : advanceCheck,
				"due_check" : dueCheck,
				"date" : date,
				"account_type" : acType,
			}, 
			"pos/modal/", function (data) {
				$(".modal_status").html(data);
				$(".show_modal").modal("show");
			});
		});
		
		$('.custom_transaction_id').hide();
		
		
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
		});
		
		$("#attach_document").change(function(e) {
			for (var i = 0; i < e.originalEvent.srcElement.files.length; i++) {
				
				var file = e.originalEvent.srcElement.files[i];
				
				var img = document.createElement("img");
				img.setAttribute('style','height:150px;width:150px;');
				img.setAttribute('class','img-rounded');
				var reader = new FileReader();
				reader.onloadend = function() {
					img.src = reader.result;
					$("#attach_document").hide();
				}
				reader.readAsDataURL(file);
				$("#attach_document").after(img);
			}
		});
		
		$('.AddAccount form').validate({
			rules: {
				business_location: {
					required: true
				},
				chart_category: {
					required: true
				},
				date: {
					required: true
				},
				total_amount: {
					required: true,
					number: true
				},
				method: {
					required: true
				},
			},  
			submitHandler: function(form) {
				AS.Http.PostSubmit(form, {"action" : "AddAccountData"}, "pos/ajax/", function (response) {
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
		
		$(document).on('change','.account_status',function(){
			$(".PaymentConditionSection").addClass('hide');
			$(".PaymentMethodSection").removeClass('hide');
			if($(this).val()=='due'){
				$('.payer_name').html($_lang.creditor);
				$(".PaymentMethodSection").addClass('hide');
			}
			else if($(this).val()=='advance'){
				$('.payer_name').html($_lang.debitor);
				// $(".PaymentMethodSection").addClass('hide');
			}
			else{
				$('.payer_name').html($_lang.payer_name);
				$(".PaymentConditionSection").removeClass('hide');
			}
		});
		
		$(document).on('click','.add_category',function(){
			// $(".show_modal").remove();
			var chart_subCat = $(".chart_subcategory").val();
			var chart_Type = $(".chart_type").val();
			$(".show_modal").modal("hide");
			AS.Http.posthtml({"action" : "AddChartAccount","subcategory" : chart_subCat, "type" : chart_Type}, "pos/modal/", function (data) {
				$(".modal_status").html(data);
				$(".show_modal").modal("show");
			});
		})
		
		
		
		$(document).on('change','#category',function(){
			var type = $("#category option:selected").text();
			$('.payer_name').html(type+' '+$_lang.for);
			
		});
		
		$(document).on('click','#due_purpsoe_payment',function(){
			var advanceCheck = $("#advance_check").val();
			
			let flag = $(this).prop('checked');
			
			if(advanceCheck == 'true'){
				$("#advanced_purpsoe_payment").removeAttr('disabled','');
			}
			if(flag == true){
				$("#advanced_purpsoe_payment").prop('checked',false);
				$("#advanced_purpsoe_payment").attr('disabled','true');	
			}
			
		});
		
		
		
		$(document).on('click','#advanced_purpsoe_payment',function(){
			
			let flag = $(this).prop('checked');
			var dueCheck = $("#due_check").val();
			if(dueCheck == 'true'){
				$("#due_purpsoe_payment").removeAttr('disabled','');
			}
			if(flag == true){
				$("#due_purpsoe_payment").prop('checked',false);
				$("#due_purpsoe_payment").attr('disabled','true');	
				$(".PaymentMethodSection").addClass('hide');
			}
			else{
				$(".PaymentMethodSection").removeClass('hide');
			}
		});
		
	</script>
	
	<?php 		
		break;
		case 'AddWithdawaAccount':
	?>
	<div class="modal inmodal show_modal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12 AddWithdawaAccount">
							<form>
								<h3 class="m-t-none m-b"><?php echo trans('account'); ?></h3>
								
								<?php if(isset($_POST['account_id'])) echo '<input type="hidden" name="account_id" value="'.$_POST['account_id'].'">';?>
								<?php if(isset($_POST['attached_document']) && $_POST['attached_document']!=null) echo '<input type="hidden" name="attached_document" value="'.$_POST['attached_document'].'">';?>
								
								<div class="row">
									<?php
										if(app('admin')->checkAddon('multiple_store_warehouse')){
									?>
										<div class="col-sm-4">
											<div class="form-group">
												<label class="control-label"><?php echo trans('business_location'); ?>: </label>
												<select class="form-control" name="business_location" id="business_location">
													<?php $posStores = app('admin')->getwhere('pos_store','store_status','active'); foreach($posStores as $posStore){	?>
														<option value="<?php echo $posStore['store_id']; ?>" <?php if($posStore['store_id'] == $this->currentUser->store_id) echo "selected"; ?>><?php echo $posStore['store_name']; ?></option>
													<?php } ?>
												</select>
											</div>
										</div>
									<?php } ?>
									<div class="col-sm-4">
										<div class="form-group" id="data_1">
											<label class="control-label"><?php echo trans('withdraw');?> <?php echo trans('date'); ?>:</label>
											<div class="input-group date">
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												<input type="text" class="form-control" name="date" id="date" readonly value="<?php echo date('Y-m-d');?>">
												<input type="hidden" name="account_status" id="account_status" value="paid">
											</div>
										</div>
									</div>
									<div class="col-sm-12">
										<div class="form-group" id="data_1">
											<label class="control-label payer_name"><?php echo trans('receiver');?>:</label>
											
											<div class="input-group">
												<span class="input-group-addon"> <i class="fa fa-user"></i> </span>
												<input type="text" class="form-control prayer_search checkDueAdvance" name="prayer_name" id="user_code" placeholder="<?php echo trans('prayer').' '.trans('name');?>" autocomplete="off">
												<input type="hidden" name="prayer" id="prayer" autocomplete="off">
												<div class="input-group-btn">
													<button type="button"  class="btn btn-default OpenContactModal"  title="<?php echo trans('new_contact'); ?>" ><i class="fa fa-plus-circle text-primary fa-lg"></i></button>
												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<label class="control-label" for="date_from"><?php echo trans('total_amount'); ?>:</label>
											<input type="text" class="form-control" name="total_amount" id="total_amount">
										</div>
									</div>
									<div class="col-sm-4 PaymentMethodSection">
										<div class="form-group">
											<label class="control-label"><?php echo trans('withdraw');?> <?php echo trans('method'); ?>: </label>
											<select class="form-control payment_method" name="method" id="method">
												<option value=""><?php echo trans('method'); ?></option>
												<?php $methods = app('admin')->getwhere('pos_payment_method','payment_method_status','active');
													foreach($methods as $method){ 
														echo "<option value='".$method['payment_method_value']."'>".$method['payment_method_name']."</option>";
													} 
												?>
											</select>
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<label class="control-label"><?php echo trans('attache_document'); ?>: </label>
											<input type="file" class="form-control-file" id="attach_document" name="attach_document">
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
											<label class="control-label"><?php echo trans('Note'); ?>: </label>
											<textarea class="form-control" name="note" rows="3" id="note" ></textarea>
										</div>
									</div>
								</div>
								<input type="hidden" name="account_type" class="chart_subcategory" value="account_withdraw">
								<button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit"><strong><?php echo trans('submit'); ?></strong></button>
								<button class="btn btn-sm btn-danger pull-right m-t-n-xs new_account_close" data-dismiss="modal" aria-label="Close" type="button" ><strong><?php echo trans('cancel'); ?></strong></button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<script>
		
		var $input = $("#user_code");
		
		$input.typeahead({
			source: function(query, result)
			{
				delay(function(){
					$.ajax({
						url:"pos/ajax/",
						method:"POST",
						data:{"action" : "GetSearchAccountUserByNameId","customer_code":query},
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
			autoSelect: false,
			hint: true,
			minLength: 2,﻿
			cache: false,
			debug: false
		});
		
		
		$input.change(function(e) {
			var current = $input.typeahead("getActive");
			if (current) {
				$("#user_code").val(current.name);
				$("#prayer").val(current.customer_id);
			}
			e.preventDefault();
		});
		
		
		var delay = (function(){
			var timer = 0;
			return function(callback, ms){
				clearTimeout (timer);
				timer = setTimeout(callback, ms);
			};
		})();
		
		$(document).on('click',".OpenContactModal",function(){
			// $(".show_modal").modal("hide");
			
			// AS.Http.posthtml({"action" : "GetNewAccountUserModal"}, "pos/modal/", function (data) {
			// $(".modal_status").html(data);
			// $(".show_modal").modal("hide");
			// });
			
			$(".show_modal").remove();
			$(".modal-backdrop").remove();
			AS.Http.posthtml({"action" : "GetNewAccountUserModal"}, "pos/modal/", function (data) {
				$(".modal_status").html(data);
				$(".show_modal").modal("show");
			});
		});
		$('.custom_transaction_id').hide();
		
		
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
		});
		
		$("#attach_document").change(function(e) {
			
			for (var i = 0; i < e.originalEvent.srcElement.files.length; i++) {
				
				var file = e.originalEvent.srcElement.files[i];
				
				var img = document.createElement("img");
				img.setAttribute('style','height:150px;width:150px;');
				img.setAttribute('class','img-rounded');
				var reader = new FileReader();
				reader.onloadend = function() {
					img.src = reader.result;
					$("#attach_document").hide();
				}
				reader.readAsDataURL(file);
				$("#attach_document").after(img);
			}
		});
		
		$('.AddWithdawaAccount form').validate({
			rules: {
				business_location: {
					required: true
				},
				chart_category: {
					required: true
				},
				date: {
					required: true
				},
				total_amount: {
					required: true,
					number: true
				},
				method: {
					required: true
				},
			},  
			submitHandler: function(form) {
				AS.Http.PostSubmit(form, {"action" : "AddAccountData"}, "pos/ajax/", function (response) {
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
		
		$(document).on('change','.account_status',function(){
			$(".PaymentConditionSection").addClass('hide');
			$(".PaymentMethodSection").removeClass('hide');
			if($(this).val()=='due'){
				$('.payer_name').html($_lang.creditor);
				$(".PaymentMethodSection").addClass('hide');
			}
			else if($(this).val()=='advance'){
				$('.payer_name').html($_lang.debitor);
				$(".PaymentMethodSection").addClass('hide');
			}
			else{
				$('.payer_name').html($_lang.payer_name);
				$(".PaymentConditionSection").removeClass('hide');
			}
		});
		
		$(document).on('click','.add_category',function(){
			// $(".show_modal").remove();
			var chart_subCat = $(".chart_subcategory").val();
			var chart_Type = $(".chart_type").val();
			$(".show_modal").modal("hide");
			AS.Http.posthtml({"action" : "AddChartAccount","subcategory" : chart_subCat, "type" : chart_Type}, "pos/modal/", function (data) {
				$(".modal_status").html(data);
				$(".show_modal").modal("show");
			});
		})
		
		$(document).on('change','#category',function(){
			var type = $("#category option:selected").text();
			$('.payer_name').html(type+' '+$_lang.for);
			
		});
		
		$(document).on('click','#due_purpsoe_payment',function(){
			let flag = $(this).prop('checked');
			$("#advanced_purpsoe_payment").removeAttr('disabled','');
			if(flag == true){
				$("#advanced_purpsoe_payment").attr('disabled','true');	
			}
			
		});
		$(document).on('click','#advanced_purpsoe_payment',function(){
			
			let flag = $(this).prop('checked');
			$("#due_purpsoe_payment").removeAttr('disabled','');
			if(flag == true){
				$("#due_purpsoe_payment").attr('disabled','true');	
				$(".PaymentMethodSection").addClass('hide');
			}
			else{
				$(".PaymentMethodSection").removeClass('hide');
			}
		});
	</script>
	
	<?php 
		
		break;
		case 'GetNewLease':
	?>
	<div class="modal inmodal show_modal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title"><?php echo trans("add_new_lease");?></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12 AddAccountLease">
							<form>
								<div class="form-group">
									<label class="control-label"> <?php echo trans("new_lease_account_title");?> </label>
									<input type="text" class="form-control chart_account_name"  name="lease_chart_account_name" placeholder="<?php echo trans("enter_new_lease_account_title");?>">
									<input type="hidden" name="lease_chart_account_value" class="chart_account_value">
									<input type="hidden" name="lease_chart_account_category" value="account_fixed_assets">
									<input type="hidden" name="lease_chart_account_subcategory" value="account_capital_lease">
								</div>
								<button class="btn btn-primary pull-right" type="submit"> <i class="fa fa-check"></i> <?php echo trans("submit");?></button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		
		$(document).on('keyup','.chart_account_name',function(){
			var chartName = $(this).val();
			chartName = chartName.toLowerCase().replace(/\s/g, '_');
			chartName = 'account_lease_'+chartName;
			$(".chart_account_value").val(chartName);
		})
		$('.AddAccountLease form').validate({
			rules: {
				lease_chart_account_name: {
					required: true
				},
			},  
			submitHandler: function(form) {
				AS.Http.PostSubmit(form, {"action" : "AddAccountLeaseData"}, "pos/ajax/", function (response) {
					if(response.status=='success'){
						var option = document.createElement("option");
						option.text = response.chart_name;
						option.value = response.chart_name_value;
						var select = document.getElementById("<?php echo $_POST['select_id']; ?>");
						select.appendChild(option);
						document.getElementById("<?php echo $_POST['select_id']; ?>").value = response.chart_name_value;
						$(".show_modal").modal("hide");
					}
					
				});
			}
		});
	</script>
	<?php
		
		break;		
		case 'GetNewAsset':
	?>
	<div class="modal inmodal show_modal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title"><?php echo trans("add_new_asset");?></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12 AddAccountAsset">
							<form>
								<div class="form-group">
									<label class="control-label"> <?php echo trans("new_asset_account_title");?> </label>
									<input type="text" class="form-control chart_account_name"  name="asset_chart_account_name" placeholder="<?php echo trans("enter_new_asset_account_title");?>">
									<input type="hidden" name="asset_chart_account_value" class="chart_account_value">
									<input type="hidden" name="asset_chart_account_category" value="account_fixed_assets">
									<input type="hidden" name="asset_chart_account_subcategory" value="account_capital_assets">
								</div>
								<button class="btn btn-primary pull-right" type="submit"> <i class="fa fa-check"></i> <?php echo trans("submit");?></button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		
		$(document).on('keyup','.chart_account_name',function(){
			var chartName = $(this).val();
			chartName = chartName.toLowerCase().replace(/\s/g, '_');
			chartName = 'account_lease_'+chartName;
			$(".chart_account_value").val(chartName);
		})
		$('.AddAccountAsset form').validate({
			rules: {
				asset_chart_account_name: {
					required: true
				},
			},  
			submitHandler: function(form) {
				AS.Http.PostSubmit(form, {"action" : "AddAccountAssetData"}, "pos/ajax/", function (response) {
					if(response.status=='success'){
						var option = document.createElement("option");
						option.text = response.chart_name;
						option.value = response.chart_name_value;
						var select = document.getElementById("<?php echo $_POST['select_id']; ?>");
						select.appendChild(option);
						document.getElementById("<?php echo $_POST['select_id']; ?>").value = response.chart_name_value;
						$(".show_modal").modal("hide");
					}
					
				});
			}
		});
	</script>
	<?php
		
		break;
		case 'AddNewAssetsCategory':
	?>
	<div class="modal inmodal show_modal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title"><?php echo trans("add_new_assets_category");?></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12 AddAccount">
							<form>
								<div class="form-group">
									<label class="control-label"> <?php echo trans("category_name");?> </label>
									<input type="text" class="form-control" name="category_name" placeholder="<?php echo trans("enter_category_name");?>">
								</div>
								<button class="btn btn-primary pull-right" type="submit"> <i class="fa fa-check"></i> <?php echo trans("submit");?></button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
		
		break;
		case 'AddNewAssets':
	?>
	<div class="modal inmodal show_modal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title"><?php echo trans("add_new_assets");?></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12 AddAccount">
							<form>
								<div class="form-group">
									<label class="control-label"> <?php echo trans("name");?> </label>
									<input type="text" class="form-control" name="name" placeholder="<?php echo trans("assets_name");?>">
								</div>
								
								<div class="form-group" id="data_1">
									<label class="control-label"><?php echo trans('purchase_date'); ?>:</label>
									<div class="input-group date">
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
										<input type="text" class="form-control" name="date" id="date" value="<?php echo date('Y-m-d');?>">
									</div>
								</div>
								
								<div class="form-group" id="data_1">
									<label class="control-label"><?php echo trans('supported_warenty'); ?>:</label>
									<div class="input-group date">
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
										<input type="text" class="form-control" name="date" id="warenty" value="<?php echo date('Y-m-d');?>">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label"> <?php echo trans("price");?> </label>
									<input type="text" class="form-control" name="price" placeholder="<?php echo trans("enter_assets_price");?>">
								</div>
								<div class="form-group">
									<label class="control-label"> <?php echo trans("category");?> </label>
									<select class="form-control" name="category">
										<option><?php echo trans("select_category");?></option>
									</select>
								</div>
								<div class="form-group">
									<label class="control-label"> <?php echo trans("note");?> </label>
									<textarea class="form-control" name="note" rows="3" placeholder="<?php echo trans("write_something_about_assets");?>"></textarea>
								</div>
								<button class="btn btn-primary pull-right" type="submit"> <i class="fa fa-check"></i> <?php echo trans("submit");?></button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		$(document).ready(function(){
			$('#data_1 .date').datepicker({
				todayBtn: "linked",
				format: "yyyy-mm-dd",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
			});		
		});
	</script>
	<?php
		
		break;
		
		case 'AccountView':
		$account_data = app('admin')->getwhereid('pos_accounts','account_id',$_POST['account_id']);
		$income_category = app('admin')->getwhereid('account_chart','account_id',$account_data['account_category_id']);
		$transactions_data = app('admin')->getwhereid('pos_transactions','account_id',$_POST['account_id']);
		$method = app('admin')->getwhereid('pos_payment_method','payment_method_value',$transactions_data['payment_method_value']);
		$location = app('admin')->getwhereid('pos_store','store_id',$account_data['store_id']);
		$getuser = app('admin')->getuserdetails($account_data['account_for']);
		$getuserdetails = app('admin')->getuserdetails($account_data['user_id']);
	?>
	
	<div class="modal inmodal show_modal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<div class="modal-title">
						<h3><?php echo trans('account_view'); ?></h3>
					</div>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label><?php echo trans('date'); ?> :</label>
								<?php echo $account_data['date'];?>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label><?php echo trans('reference_no'); ?> :</label>
								<?php echo $account_data['reference_no'];?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label><?php echo trans('for'); ?> :</label>
								<?php echo $getuser['first_name'].' '.$getuser['last_name']; ?>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label><?php echo trans('category'); ?> :</label>
								<?php echo $income_category['chart_title'];?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label><?php echo trans('location'); ?> :</label>
								<?php echo $location['store_name']; ?>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label><?php echo trans('added_by'); ?> :</label>
								<?php echo $getuserdetails['first_name'].' '.$getuserdetails['last_name']; ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label><?php echo trans('method'); ?> :</label>
								<?php echo $method['payment_method_name'];?>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label><?php echo trans('created'); ?> :</label>
								<?php echo $account_data['created_at']; ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<h1><strong><?php echo trans('amount').' :'; ?></strong><strong class="text-danger"><?php echo $account_data['account_amount'];?></strong></h1>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label><?php echo trans('note'); ?> :</label>
								<?php echo $account_data['account_details']; ?>
							</div>
						</div>
					</div>
					<?php if ($account_data['attach_document']!=null) { ?>
						<div class="row">
							<div class="col-sm-12">
								<label><?php echo trans('attach_document'); ?> :</label>
								<div class="form-group">
									
									<img class="img-rounded" height="300px" width="500px" src="images/stores/<?php echo $_SERVER['SERVER_NAME']; ?>/document/<?php echo $account_data['attach_document']; ?>">
								</div>
							</div>
						</div>
					<?php } ?>
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary confirm"><?php echo trans('close');?></button>
				</div>
			</div>
		</div>
	</div>
	
	
	<?php 
		break;
		case 'GetCreateTicketData':
	?>
	
	<div class="modal inmodal ticket_modal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content CreateTicket">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<div class="modal-title">
						<h3><?php echo trans('create_ticket'); ?></h3>
					</div>
				</div>
				<form method="post" enctype="multipart/form-data">
					<div class="modal-body">
						
						<div class="row">
							<div class="col-sm-12">
								<div class="fortm-group">
									<label class="control-label"><?php echo trans("title");?>:</label>
									<input class="form-control" type="text" name="ticket_title" placeholder="<?php echo trans('enter_ticket_title');?>">
								</div>
								<div class="fortm-group">
									<label class="control-label"><?php echo trans("details");?>:</label>
									<textarea class="form-control" rows="7" name="ticket_details" placeholder="<?php echo trans('enter_ticket_details');?>"></textarea>
								</div>
								<div class="fortm-group">
									<label class="control-label"><?php echo trans("priority_level");?>:</label>
									<select class="form-control" name="priority_level">
										<option value="high"><?php echo trans('high');?></option>
									<option value="medium"><?php echo trans('medium');?></option>
									<option value="normal"><?php echo trans('normal');?></option>
									</select>
									</div>
									<div class="fortm-group">
									<label class="control-label"><?php echo trans("attach_document");?>:</label>
									<input class="form-control-file" type="file" name="ticket_document">
									</div>
									
									</div>
									</div>
									
									</div>
									<div class="modal-footer">
									<button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit"><strong><?php echo trans('submit'); ?></strong></button>
									<button class="btn btn-sm btn-danger pull-right m-t-n-xs" data-dismiss="modal" aria-label="Close" type="button" ><strong><?php echo trans('cancel'); ?></strong></button>
									</div>
									</form>
									</div>
									</div>
									<script>
									$('.CreateTicket form').validate({
									rules: {
									ticket_title: {
									required: true
									},
									ticket_details: {
									required: true
									}
									},
									submitHandler: function(form) {
									AS.Http.PostSubmit(form, {"action" : "AddTicketData"}, "ajax/", function (response) {
									if(response.status=='success'){
									swal({
									title: $_lang.success,
									text: response.message,
									type: "success",
									confirmButtonColor: "#1ab394",
									confirmButtonText: $_lang.ok,
									},function (isConfirm) {
									if (isConfirm) {
									location.href="pos/ticket_list";
									}
									});
									
									}
									});
									}
									});
									</script>
									<?php
									break;
									case "GetTicketReadMoreData":
									$ticket = app('root')->select("SELECT * FROM `as_ticket` WHERE `ticket_no`=:id",array("id"=>$_POST['ticket_no']));
									?>
									<div class="modal inmodal show_modal" aria-hidden="true">
									<div class="modal-dialog">
									<div class="modal-content CreateTicket">
									<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
									<div class="modal-title">
									<h3><?php echo $ticket[0]['ticket_title']; ?></h3>
									</div>
									</div>
									<div class="modal-body">
									<div class="row">
									<div class="col-sm-12">
									<?php echo $ticket[0]['ticket_details'];?>
									</div>
									</div>
									</div>
									<div class="modal-footer">
									
									<button class="btn btn-sm btn-danger pull-right m-t-n-xs" data-dismiss="modal" aria-label="Close" type="button" ><strong><?php echo trans('cancel'); ?></strong></button>
									</div>
									</div>
									</div>
									
									<?php
									break;
									
									case "GetSupportChatData":
									$chats = app('root')->select('SELECT * FROM `as_ticket` WHERE `ticket_for`=:ticket_id',array("ticket_id"=>$_POST['ticket_id']));
									$ticket = app('root')->select('SELECT * FROM `as_ticket` WHERE `ticket_no`=:ticket_id',array("ticket_id"=>$_POST['ticket_id']));
									$ticket = $ticket[0];
									foreach ($chats as $chat){
									
									?>
									<div class="vertical-timeline-block">
									<!--                                <div class="vertical-timeline-icon yellow-bg">-->
									<div class="vertical-timeline-icon <?php echo $chat['reply_by']=='user'?'navy-bg':'yellow-bg';?>">
									<i class="fa <?php echo $chat['reply_by']=='user'?'fa-user':'fa-user-md';?>"></i>
									</div>
									
									<div class="vertical-timeline-content">
									<h2><?php echo $ticket['ticket_title']; ?></h2>
									<p><?php echo $chat['ticket_message'];?></p>
									<p><?php
									if(isset($chat['ticket_document'])){?>
									<a href="/images/stores/<?php echo $_SERVER['SERVER_NAME']?>/document/<?php echo $chat['ticket_document']?>">
									<img src="/images/stores/<?php echo $_SERVER['SERVER_NAME']?>/document/<?php echo $chat['ticket_document']?>" class="img-responsive">
									</a>
									<?php }
									?></p>
									<span class="vertical-date"> <?php echo date("D",strtotime($chat['created_at']));?> <br/><small>
									<?php echo getdatetime($chat['created_at'],3); ?></small><br/>
									<?php echo $chat['reply_by']; ?></small>
									</span>
									</div>
									</div>
									<?php }
									break;
									
									case "GetNewPaymentMethodModal": 
									?>
									<div class="modal inmodal show_modal" tabindex="-1" role="dialog"  aria-hidden="true">
									<div class="modal-dialog">
									<div class="modal-content animated fadeIn AddNewPaymentMethod">
									<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
									<h4 class="modal-title"><?php echo trans('add_payment_method');?></h4>
									
									</div>
									<form>
									<?php if(isset($_POST['payment_method_id'])) echo '<input type="hidden" name="payment_method_id" value="'.$_POST['payment_method_id'].'">';?>
									<div class="modal-body">
									<div class="form-group">
									<div class="row">
									<div class="col-sm-4">
									<label><?php echo trans('payment_method_name');?></label>
									</div>
									<div class="col-sm-8">
									<input type="text" class="form-control" placeholder="<?php echo trans('enter_payment_method'); ?>" id="payment_method_name" name="payment_method_name">
									<input type="hidden" name="payment_method_value" id="payment_method_value">
									</div>
									</div>
									</div>
									<div class="form-group">
									<div class="row">
									<div class="col-sm-4">
									<label><?php echo trans('account_number');?></label>
									</div>
									<div class="col-sm-8">
									<input type="text" placeholder="<?php echo trans('enter_your_account_number'); ?>" class="form-control" name="account_number" id="account_number">
									</div>
									</div>
									</div>
									<div class="form-group">
									<div class="row">
									<div class="col-sm-4">
									<label><?php echo trans('minimum_amount');?></label>
									</div>
									<div class="col-sm-8">
									<input type="text" placeholder="<?php echo trans('enter_minimum_amount'); ?>" class="form-control" name="minimum_amount" id="minimum_amount">
									</div>
									</div>
									</div>
									</div>
									<div class="modal-footer">
									<button type="submit" class="btn btn-primary" ><?php echo trans('save');?></button>
									<button type="button" class="btn btn-white" data-dismiss="modal"><?php echo trans('close');?></button>
									</div>
									</form>
									</div>
									</div>
									</div>
									<script>
									$('.AddNewPaymentMethod form').validate({
									rules: {
									payment_method_name: {
									required: true
									},
									account_number: {
									required: true
									}
									},  
									submitHandler: function(form) {
									AS.Http.PostSubmit(form, {"action" : "AddNewPaymentMethodSubmit"}, "pos/ajax/", function (response) {
									if(response.status=='success'){
									$("#select_bank").append("<option value='"+response.payment_method_value+"'>"+response.payment_method+"</option>");
									$("#select_bank").val(response.payment_method_value);
									console.log(response);
									
									swal({
									title: $_lang.success, 
									text: response.message, 
									type: "success",
									confirmButtonColor: "#1ab394", 
									confirmButtonText: $_lang.ok,
									},function(isConfirm){
										if(isConfirm){
											$(".show_modal").modal('hide');
										}
									});
									}
									});
									}
									});
									</script>
									<?php
									break;
									case "AddNewHeadOfAccount": 
									?>
									<div class="modal inmodal show_modal" tabindex="-1" role="dialog" aria-hidden="true">
									<div class="modal-dialog">
									<div class="modal-content animated fadeIn AddNewPaymentMethod">
									<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
									<h4 class="modal-title"><?php echo trans('add_payment_method');?></h4>
									
									</div>
									<form>
									<?php if(isset($_POST['payment_method_id'])) echo '<input type="hidden" name="payment_method_id" value="'.$_POST['payment_method_id'].'">';?>
									<div class="modal-body">
									<div class="form-group">
									<div class="row">
									<div class="col-sm-4">
									<label>
									<?php echo trans('payment_method_name');?>
									</label>
									</div>
									<div class="col-sm-8">
									<input type="text" class="form-control" placeholder="<?php echo trans('enter_payment_method'); ?>" id="payment_method_name" name="payment_method_name">
									<input type="hidden" name="payment_method_value" id="payment_method_value">
									</div>
									</div>
									</div>
									<div class="form-group">
									<div class="row">
									<div class="col-sm-4">
									<label>
									<?php echo trans('account_number');?>
									</label>
									</div>
									<div class="col-sm-8">
									<input type="text" placeholder="<?php echo trans('enter_your_account_number'); ?>" class="form-control" name="account_number" id="account_number">
									</div>
									</div>
									</div>
									<div class="form-group">
									<div class="row">
									<div class="col-sm-4">
									<label>
									<?php echo trans('minimum_amount');?>
									</label>
									</div>
									<div class="col-sm-8">
									<input type="text" placeholder="<?php echo trans('enter_minimum_amount'); ?>" class="form-control" name="minimum_amount" id="minimum_amount">
									</div>
									</div>
									</div>
									</div>
									<div class="modal-footer">
									<button type="submit" class="btn btn-primary">
									<?php echo trans('save');?>
									</button>
									<button type="button" class="btn btn-white" data-dismiss="modal">
									<?php echo trans('cancel');?>
									</button>
									</div>
									</form>
									</div>
									</div>
									</div>
									<script>
									$('.AddNewPaymentMethod form').validate({
									rules: {
									payment_method_name: {
									required: true
									},
									account_number: {
									required: true
									}
									},  
									submitHandler: function(form) {
									AS.Http.PostSubmit(form, {"action" : "AddNewPaymentMethodSubmit"}, "pos/ajax/", function (response) {
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
									</script>
									<?php
									break;
									
									case 'GetStockTransferView':?>
									
									<?php if(isset($_POST['stock_transfer_id'])){
									$info = app('admin')->getwhereid('pos_stock_transfer','stock_transfer_id',$_POST['stock_transfer_id']);
									
									$from_store=app('admin')->getwhereid('pos_store','store_id',$info['from_store_id']);
									$to_store=app('admin')->getwhereid('pos_store','store_id',$info['to_store_id']);
									$GetuserInfo = app('admin')->getuserdetails($info['user_id']);
									
									$products = app('admin')->getwhereand('pos_stock','transfer_id',$_POST['stock_transfer_id'],'stock_type','out');
									}?>
									
									<div class="modal inmodal show_modal" tabindex="-1" role="dialog"  aria-hidden="true">
									
									<div class="modal-dialog full-width-modal-dialog" >
									<div class="modal-content full-width-modal-content">
									<div class="modal-header">
									<button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title" id="modalTitle"> <?php echo trans('transfer_id').' - '.$_POST['stock_transfer_id']; ?> </h4>
									</div>
									<div class="modal-body">
									<div class="row">
									<div class="col-md-4">
									<b><?php echo trans('date'); ?>:</b><?php echo getdatetime($info['date'],4); ?><br>
									<b><?php echo trans('reference_no'); ?>: </b><?php echo $info['reference_no']; ?><br>
									<b><?php echo trans('location_from'); ?>: </b><?php echo $from_store['store_name']; ?><br>
									<b><?php echo trans('location_to'); ?>: </b><?php echo $to_store['store_name']; ?><br>
									</div>
									
									<div class="col-md-4">
									<b><?php echo trans('shipping_charge'); ?>: </b><?php echo $info['shipping_charge']; ?><br>	
									<b><?php echo trans('added_by'); ?>: </b><?php echo $GetuserInfo['first_name'].' '.$GetuserInfo['last_name']; ?><br>
									<b><?php echo trans('created_at'); ?>: </b><?php echo getdatetime($info['created_at'],3); ?><br>
									</div>
									
									<div class="col-md-4">
									<b><?php echo trans('note'); ?>: </b><?php echo $info['stock_transfer_note']; ?>
									</div>
									</div>
									<hr/>
									<div class="row">
									<div class="col-md-12">
									<div class="table-responsive">
									<table class="table table-striped table-bordered bg-green">
									<thead>
									<tr>
									<th class="text-center"><?php echo trans('product'); ?></th>
									<th class="text-center"><?php echo trans('quantity'); ?></th>
									<th class="text-center"><?php echo trans('price'); ?></th>
									<th class="text-center"><?php echo trans('sub_total'); ?></th>
									</tr>
									</thead>
									<tbody class="text-center">
									<?php foreach ($products as $product) { ?>
									<tr>
									<td><?php echo $product['sub_product_id']; ?></td>
									<td><?php echo $product['product_quantity']; ?></td>
									<td><?php echo $product['product_price']; ?></td>
									<td><?php echo $product['product_subtotal']; ?></td>
									</tr>
									<?php } ?>
									</tbody>
									</table>
									</div>
									</div>
									</div>
									</div>
									<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo trans('cancel'); ?></button>
									</div>
									</div>
									</div>
									</div>
									
									<?php 
									break;
									case "GetPaymentTransferModal": 
									?>
									<div class="modal inmodal show_modal" tabindex="-1" role="dialog"  aria-hidden="true">
									<div class="modal-dialog">
									<div class="modal-content animated fadeIn AddNewPaymentTransfer">
									<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
									<h4 class="modal-title"><?php echo trans('add_payment_method');?></h4>
									
									</div>
									<form>
									<?php if(isset($_POST['payment_method_value'])) $payment_method_value=$_POST['payment_method_value'];
									// if(isset($_POST['payment_method_amount'])) $payment_method_amount=$_POST['payment_method_amount'];
									?>
									<div class="modal-body">
									<div class="form-group">
									<div class="row">
									<div class="col-sm-4">
									<label><?php echo trans('payment_method_name');?></label>
									</div>
									<div class="col-sm-8">
									<input type="text" class="form-control" readonly="" name="payment_method_name" id="payment_method_name">
									<input type="hidden" class="form-control" readonly="" name="payment_method_value" id="payment_method_value">
									</div>
									</div>
									</div>
									<div class="form-group">
									<div class="row">
									<div class="col-sm-4">
									<label><?php echo trans('payment_transfer_method');?></label>
									</div>
									<div class="col-sm-8">
									<select class="form-control payment_method required" name="payment_transfer_method">
									<option value=""><?php echo trans('select_transfer_payment_method'); ?></option>
									<?php 
									$getpayment=app('admin')->getwhere('pos_payment_method','payment_method_status','active');
									foreach ($getpayment as $payment) {
									if($payment['payment_method_value']!=$payment_method_value){
									?>
									<option value="<?php echo $payment['payment_method_value']; ?>"><?php echo $payment['payment_method_name']; ?> </option>
									<?php } }?>
									</select>
									</div>
									</div>
									</div>
									<div class="form-group">
									<div class="row">
									<div class="col-sm-4">
									<label class="control-label"><?php echo trans('transfer_amount'); ?></label>
									</div>
									<div class="col-sm-8">
									<input type="text" name="transfer_amount" class="form-control">
									</div>
									</div>
									</div>
									<div class="form-group transaction_number">
									<div class="row">
									<div class="col-sm-4">
									<label class="control-label"><?php echo trans('transaction_number'); ?></label>
									</div>
									<div class="col-sm-8">
									<input type="text" name="transaction_number" placeholder="<?php echo trans('transaction_number'); ?>" class="form-control">
									</div>
									</div>
									</div>
									<div class="form-group">
									<div class="row">
									<div class="col-sm-4">
									<label class="control-label" ><?php echo trans('payment_note'); ?></label>
									</div>
									<div class="col-sm-8">
									<textarea class="form-control" name="payment_note"></textarea>
									</div>
									</div>
									</div>
									</div>
									<div class="modal-footer">
									<button type="submit" class="btn btn-primary" ><?php echo trans('save');?></button>
									<button type="button" class="btn btn-white" data-dismiss="modal"><?php echo trans('close');?></button>
									</div>
									</form>
									</div>
									</div>
									</div>
									<script>
									$(".transaction_number").addClass('hide');
									$(".payment_method").change(function(){
									var payment_value=$(".payment_method").val();
									if(payment_value=='cash' || payment_value=='null'){
									$(".transaction_number").addClass('hide');
									}
									else{
									$(".transaction_number").removeClass('hide');
									}
									});
									
									$(".AddNewPaymentTransfer form").validate({
									rules:{
									payment_transfer_method:{
									required: true
									},
									transfer_amount:{
									required: true
									},
									transaction_number:{
									required: true
									},
									
									},
									submitHandler: function(form) {
									AS.Http.PostSubmit(form, {"action" : "AddPaymentTransferSubmit"}, "pos/ajax/", function (response) {
									if(response.status=='success'){
									swal({
									title: $_lang.success, 
									text: response.message, 
									type: "success",
									confirmButtonColor: "#1ab394", 
									confirmButtonText: $_lang.ok,
									},function(isConfirm){
									if(isConfirm){
									location.reload();
									}
									});
									}
									});
									}
									});
									
									</script>
									<?php
									break;
									case "PurchaseViewModal":
									// $getPurchase=app('admin')->getwhereid('pos_purchase','purchase_id',$_POST['purchase_id']);
									$getPurchase = app('db')->table('pos_purchase')->where('purchase_id',$_POST['purchase_id'])->where('is_delete','false')->get(1);
									$getSupplier=app('admin')->getwhereid('pos_contact','contact_id',$getPurchase['supplier_id']);
									
									?>
									<div class="modal fade show_modal" tabindex="-1" role="dialog" >
									
									<div class="modal-dialog purchase-modal" >
									<div class="modal-content">
									<div class="modal-header">
									<button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h2 class="modal-title text-center" id="modalTitle"> <?php echo trans('purchase_details');?> </h2>
									</div>
									<div class="modal-body">
									<div class="row">
									<div class="col-sm-12">
									<h5 class="pull-right"><strong><?php echo trans('date'); ?></strong>:<?php echo $getPurchase['purchase_date']; ?></h5>
									</div>
									<div class="col-sm-12">
									<div class="row">
									<div class="col-sm-4">
									<p>
									
									<strong><?php echo trans("supplier"); ?>:</strong>
									<?php echo $getSupplier['name']; ?>
									</p>
									<p>
									<strong><?php echo trans("mobile_no"); ?>:</strong>
									<?php echo $getSupplier['phone']; ?>
									</p>
									</div>
									<div class="col-sm-4">
									<p>
									<strong><?php echo trans("purchase_status"); ?>:</strong>
									<?php 
									if($getPurchase['purchase_status']=='pending'){
									?>
									<label class="label label-success"><?php echo $getPurchase['purchase_status']; ?></label>
									<?php }
									else{
									?>
									<label class="label label-primary"><?php echo $getPurchase['purchase_status']; ?></label>
									<?php } ?>
									
									
									</p>
									<p>
									<strong><?php echo trans("payment_status"); ?>:</strong>
									<?php 
									if($getPurchase['purchase_payment_status']=='paid'){
									?>
									<label class="label label-primary"><?php echo $getPurchase['purchase_payment_status']; ?></label>
									<?php }
									else{
									?>
									<label class="label label-danger"><?php echo $getPurchase['purchase_payment_status']; ?></label>
									<?php } ?>
									</p>
									</div>
									<div class="col-sm-4">
									<p>
									<strong><?php echo trans("grand_total"); ?>:</strong>
									<?php echo $getPurchase['purchase_total']; ?>
									</p>
									<!-- <p>
									<strong><?php echo trans("payment_due"); ?>:</strong>
									<?php echo $getPurchase['']; ?>
									</p> -->
									</div>
									</div>
									</div>
									<div class="col-sm-12 table-responsive">
									<table class="table table-striped table-bordered bg-green">
									<thead>
									<tr>
									<th>#</th>
									<th><?php echo trans('product_name'); ?></th>
									<th><?php echo trans('purchase_quantity'); ?></th>
									<th><?php echo trans('unit_cost'); ?></th>
									<th><?php echo trans('subtotal_total'); ?></th>
									</tr>
									</thead>
									
									<tbody class="text-center">
									<?php 
									// $getPurchaseProducts=app('admin')->getwhereand('pos_stock','purchase_id',$getPurchase['purchase_id'],"stock_category","purchase");
									$getPurchaseProducts = app('db')->table('pos_stock')->where('purchase_id',$getPurchase['purchase_id'])->where("stock_category","purchase")->where('is_delete','false')->get();
									$count=1;
									foreach ($getPurchaseProducts as $product) {
									
									$getSubProduct=app('admin')->getwhereid('pos_variations','sub_product_id',$product['sub_product_id']);
									$getProducts=app('admin')->getwhereid('pos_product','product_id',$getSubProduct['product_id']);
									?>
									<tr>
									<td><?php echo $count; ?></td>
									<td><?php echo $getProducts['product_name']; if($getSubProduct['variation_name']!=null){echo '['.$getSubProduct['variation_name'].']';} ?></td>
									<td><?php echo $product['product_quantity']; ?></td>
									<td><?php echo $getSubProduct['purchase_price']; ?></td>
									<td><?php echo $product['product_subtotal']; ?></td>
									
									
									</tr>
									<?php $count++; } ?>
									</tbody>
									</table>
									</div>
									<div class="col-sm-12">
									<div class="row">
									<div class="col-sm-6 table-responsive">
									<h3><?php echo trans('payment_info'); ?>:</h3>
									<table class="table table-bordered bg-green" style="">
									<thead>
									<tr>
									<th><?php echo trans('serial_no'); ?></th>
									<th><?php echo trans('date'); ?></th>
									<th><?php echo trans('amount'); ?></th>
									<th><?php echo trans('payment_method'); ?></th>
									<th><?php echo trans('note'); ?></th>
									</tr>
									</thead>
									<tbody class="text-center">
									<?php 
									// $getPaymenInfo=app('admin')->getwhere('pos_transactions','purchase_id',$getPurchase['purchase_id']);
									$getPaymenInfo = app('db')->table('pos_transactions')->where('purchase_id',$getPurchase['purchase_id'])->where('is_delete','false')->get();
									$count=1;
									foreach ($getPaymenInfo as $payment) {
									
									?>
									<tr>
									<td><?php echo $count; ?></td>
									<td><?php echo $payment['paid_date']; ?></td>
									<td><?php echo $payment['transaction_amount']; ?></td>
									<td><?php echo $payment['payment_method_value']; ?></td>
									<td> <?php echo $payment['transaction_note']; ?></td>
									</tr>
									
									<?php } ?>
									</tbody>
									</table>
									</div>
									<div class="col-sm-6 table-responsive">
									<br>
									<table class="table pull-left" style="margin-top: 15px;">
									<tr>
									<th><?php echo trans('net_amount'); ?>:</th>
									<td><?php echo $getPurchase['purchase_subtotal']; ?></td>
									</tr>
									<tr>
									<th><?php echo trans('discount'); ?>:</th>
									<td><?php echo $getPurchase['purchase_discount']; ?></td>
									</tr>
									<tr>
									<th><?php echo trans('additional_shipping_charge'); ?>:</th>
									<td><?php echo $getPurchase['purchase_shipping_charge']; ?></td>
									</tr>
									<tr>
									<th><?php echo trans('tax'); ?>:</th>
									<td><?php echo $getPurchase['purchase_tax']; ?></td>
									</tr>
									<tr>
									<th><?php echo trans('purchase_total'); ?>:</th>
									<td><?php echo $getPurchase['purchase_total']; ?></td>
									</tr>
									
									<tfoot>
									<tr>
									<td colspan="">
									<strong><?php echo trans('additional_notes'); ?></strong>
									</td>
									</tr>
									<tr style="background-color: #e7e4e4; height: 40px;">
									<td colspan="2">
									<?php echo $getPurchase['purchase_note']; ?>
									</td>
									</tr>
									</tfoot>
									</table>
									
									</div>
									</div>
									</div>									
									</div>						
									
									</div>
									<div class="modal-footer">
									<button type="button" class="btn btn-default no-print" data-dismiss="modal"><?php echo trans('cancel'); ?></button>
									</div>
									</div>
									</div>
									</div> 
									<?php
									break;
									
									case "RegisterDetails":
									
									$register_details=app('admin')->getwhereid('pos_register_report','register_id', $_POST['register_id']);
									
									$store_id = $this->currentUser->store_id;
									$start = $register_details['register_open'];
									$end = $register_details['register_close'];
									
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
									
									?>
									<div class="modal fade show_modal" tabindex="-1" role="dialog" >
									<style>
									.modal-dialog {
									width: 40%;
									left:30%;
									right:30%;
									height: auto;
									margin: 0;
									padding: 0;
									}
									
									.modal-content {
									height: auto;
									min-height: 100%;
									border-radius: 0;
									}
									</style>
									<div class="modal-dialog" >
									<div class="modal-content">
									<div class="modal-header">
									<h1 class="modal-title text-center" id="modalTitle"> <?php echo trans('register_details');?> </h1>
									<h2 class="modal-title text-center" id="modalTitle"> <?php echo getdatetime($start,2)." ".trans('to')." ".getdatetime($end,2);?> </h2>
									</div>
									<div class="modal-body">
									<div class="row">
									<div class="col-sm-12 table-responsive">
									
									<table class="table">
									<tbody>
									<tr>
									<td><?php echo trans('cash_in_hand');?>:</td>
									<td><?php echo $register_details['register_open_balance'];?></td>
									</tr>
									<tr>
									<td><?php echo trans('sale_in_cash');?>:</td>
									<td><?php echo $GetActualSalesTotal['transaction_amount'];;?></td>
									</tr>
									<tr>
									<td><?php echo trans('sale_due');?>:</td>
									<td><?php echo $GetDueSalesTotal;?></td>
									</tr>
									<tr>
									<td><?php echo trans('purchase_in_cash');?>:</td>
									<td><?php echo $GetActualPurchaseTotal['transaction_amount'];?></td>
									</tr>
									<tr>
									<td><?php echo trans('purchase_due');?>:</td>
									<td><?php echo $GetDuePurchaseTotal;?></td>
									</tr>
									<tr>
									<td><?php echo trans('sale_return');?>:</td>
									<td><?php echo $GetActualSalesReturnTotal['transaction_amount'];?></td>
									</tr>
									<tr>
									<td><?php echo trans('purchase_return');?>:</td>
									<td><?php echo $GetActualPurchaseReturnTotal['transaction_amount'];?></td>
									</tr>
									<tr>
									<td><?php echo trans('income');?>:</td>
									<td><?php echo $GetIncomeTotal['transaction_amount'];?></td>
									</tr>
									<tr>
									<td><?php echo trans('expense');?>:</td>
									<td><?php echo $GetExpenseTotal['transaction_amount'];?></td>
									</tr>
									<tr>
									<td><?php echo trans('closing_cash');?>:</td>
									<td><?php echo $register_details['register_close_balance'];?></td>
									</tr>
									
									<tr>
									<td><?php echo trans('onening_note');?>:<br/>
									<?php echo $register_details['opening_note'];?></td>
									<td><?php echo trans('closing_note');?>:<br/>
									<?php echo $register_details['closing_note'];?></td>
									</tr>
									</tbody>
									</table>
									
									</div>
									
									</div>						
									
									</div>
									<div class="modal-footer">
									<button type="button" class="btn btn-default no-print" data-dismiss="modal"><?php echo trans('cancel'); ?></button>
									</div>
									</div>
									</div>
									</div> 
									<?php
									break;
									case 'GetStockAdjustmentView':?>
									
									<?php if(isset($_POST['stock_adjustment_id'])){
									$info = app('admin')->getwhereid('pos_stock_adjustment','stock_adjustment_id',$_POST['stock_adjustment_id']);
									
									$store=app('admin')->getwhereid('pos_store','store_id',$info['from_store_id']);
									$GetuserInfo = app('admin')->getuserdetails($info['user_id']);
									
									$products = app('admin')->getwhere('pos_stock','adjustment_id',$_POST['stock_adjustment_id']);
									}?>
									
									<div class="modal inmodal show_modal" tabindex="-1" role="dialog"  aria-hidden="true">
									
									<div class="modal-dialog full-width-modal-dialog" >
									<div class="modal-content full-width-modal-content">
									<div class="modal-header">
									<button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title" id="modalTitle"> <?php echo trans('adjustment_id').' - '.$_POST['stock_adjustment_id']; ?> </h4>
									</div>
									<div class="modal-body">
									<div class="row">
									<div class="col-md-4">
									<b><?php echo trans('date'); ?>:</b><?php echo getdatetime($info['date'],4); ?><br>
									<b><?php echo trans('reference_no'); ?>: </b><?php echo $info['reference_no']; ?><br>
									<b><?php echo trans('location_from'); ?>: </b><?php echo $store['store_name']; ?><br>
									<b><?php echo trans('type'); ?>: </b><?php echo $info['type']; ?><br>
									</div>
									
									<div class="col-md-4">
									<b><?php echo trans('shipping_charge'); ?>: </b><?php echo $info['shipping_charge']; ?><br>	
									<b><?php echo trans('added_by'); ?>: </b><?php echo $GetuserInfo['first_name'].' '.$GetuserInfo['last_name']; ?><br>
									<b><?php echo trans('created_at'); ?>: </b><?php echo getdatetime($info['created_at'],3); ?><br>
									</div>
									
									<div class="col-md-4">
									<b><?php echo trans('note'); ?>: </b><?php echo $info['stock_adjustment_note']; ?>
									</div>
									</div>
									<hr/>
									<div class="row">
									<div class="col-md-12">
									<div class="table-responsive">
									<table class="table table-striped table-bordered bg-green">
									<thead>
									<tr>
									<th class="text-center"><?php echo trans('product'); ?></th>
									<th class="text-center"><?php echo trans('quantity'); ?></th>
									<th class="text-center"><?php echo trans('price'); ?></th>
									<th class="text-center"><?php echo trans('sub_total'); ?></th>
									</tr>
									</thead>
									<tbody class="text-center">
									<?php foreach ($products as $product) { ?>
									<tr>
									<td><?php echo $product['sub_product_id']; ?></td>
									<td><?php echo $product['product_quantity']; ?></td>
									<td><?php echo $product['product_price']; ?></td>
									<td><?php echo $product['product_subtotal']; ?></td>
									</tr>
									<?php } ?>
									</tbody>
									</table>
									</div>
									</div>
									</div>
									</div>
									<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo trans('cancel'); ?></button>
									</div>
									</div>
									</div>
									</div>
									
									<?php break;
									
									case "GetDueModal":
									?>
									<div class="modal fade show_modal" tabindex="-1" role="dialog" >
									<div class="modal-dialog">
									<div class="modal-content">
									<div class="modal-header">
									<h3 class="modal-title text-center" > <?php echo trans('add_payment');?> </h3>
									</div>
									<div class="modal-body DueAdjustment">
									<div class="row">
									<div class="col-sm-5 well">
									<p class="h6">
									<strong><?php echo trans('total_purchase');?>: </strong>
									</p>
									<p class="h6">
									<strong><?php echo trans('total_paid');?>:</strong>
									</p>
									</div>
									<div class="col-sm-6 col-sm-offset-1 well">
									<p class="h6">
									<strong><?php echo trans('total_purchase_due');?>: <span id="show_due_amount"></span></strong>
									</p>
									<p class="h6">
									<strong><?php echo trans('opening_balence');?>:</strong>
									</p>
									<p class="h6">
									<strong><?php echo trans('opening_balence_due');?>:</strong>
									</p>
									</div>
									</div>	
									<form>
									<input type="hidden" name="contact_id" id="contact_id">
									<input type="hidden" name="contact_type" id="contact_type">
									<input type="hidden" name="due_amount" id="due_amount">
									<div class="ibox">
									<div class="ibox-content">
									<div class="row">
									<div class="col-sm-4">
									<div class="form-group">
									<label class="control-label"><?php echo trans("amount");?>:*</label>
									<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
									<input type="text" name="amount" class="form-control">
									</div>
									</div>
									</div>
									<div class="col-sm-4">
									<div class="form-group" id="data_1">
									<label class="control-label"><?php echo trans("date");?>:*</label>
									<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
									<input type="text" name="payment_date" class="form-control date">
									</div>
									</div>
									</div>
									<div class="col-sm-4">
									<div class="form-group">
									<label class="control-label"><?php echo trans("payment_method");?>:*</label>
									<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
									<select class="form-control" name="payment_method" id="payment_method">
									<?php
									$getMethod=app('admin')->getwhere('pos_payment_method','payment_method_status','active');
									foreach($getMethod as $method){
									?>
									<option value="<?php echo $method['payment_method_value'];?>"><?php echo $method['payment_method_name'];?></option>
									<?php } ?>
									</select>
									</div>
									</div>
									</div>
									</div>
									<div class="row transactin_row">
									<div class="col-sm-12">
									<div class="form-group">
									<label class="control-label"><?php echo trans('transaction_number');?></label>
									<input type="text" class="form-control" name="transaction_number">
									</div>
									</div>
									</div>
									<div class="row">
									<div class="col-sm-12">
									<div class="form-group" ">
									<label class="control-label"><?php echo trans("payment_note");?>:*</label>
									<textarea class="form-control" name="payment_note"></textarea>
									</div>
									</div>
									</div>
									<div class="row">
									<div class="col-sm-12">
									<button type="button" class="btn btn-default no-print" data-dismiss="modal"><?php echo trans('cancel'); ?></button>
									<button class="btn btn-primary pull-right" type="submit"><?php echo trans('submit');?></button>
									</div>
									</div>
									</div>
									</div>
									</form>
									</div>
									</div>
									</div>
									</div>
									<script>
									
									$('.DueAdjustment form').validate({
									rules: {
									amount: {
									required: true,
									number: true
									},
									
									},  
									submitHandler: function(form) {
									AS.Http.PostSubmit(form, {"action" : "DueAdjustment"}, "pos/ajax/", function (response) {
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
									$(".transactin_row").addClass('hide');
									$("#payment_method").change(function(){
									var method = $("#payment_method").val();
									if(method!='cash')
									{
									$(".transactin_row").removeClass('hide');	
									}
									else{
									$(".transactin_row").addClass('hide');	
									}
									});
									$('#data_1 .input-group .date').datepicker({
									todayBtn: "linked",
									format: "yyyy-mm-dd",
									keyboardNavigation: false,
									forceParse: false,
									calendarWeeks: true,
									autoclose: true
									});
									});
									
									</script>
									<?php
									break;
									case "GetTutorialByPageModal"
									?>
									<!--div class="modal inmodal show_tutorial_modal" tabindex="-1" role="dialog"  aria-hidden="true">
									<div class="modal-dialog">
									<div class="modal-content animated fadeIn AddNewUnit">
									<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
									</div>
									<div class="modal-body">
									<iframe width="1280" height="720" src="https://www.youtube.com/embed/Iwh--Ju60aU?list=RDMMIwh--Ju60aU" 
									frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" 
									allowfullscreen></iframe>
									</div>
									</div>
									</div>
									</div-->
									<div class="modal fade show_tutorial_modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
									<div class="modal-dialog" role="document" style="max-width: 800px;margin: 30px auto;">
									<div class="modal-content">
									
									
									<div class="modal-body" style="position:relative;padding:0px;">
									
									<button type="button" class="close" data-dismiss="modal" aria-label="Close" 
									style="position:absolute;right:-30px;top:0;z-index:999;font-size:2rem;font-weight: normal;color:#fff;opacity:1;">
									<span aria-hidden="true">&times;</span>
									</button>        
									<!-- 16:9 aspect ratio -->
									<div class="embed-responsive embed-responsive-16by9">
									<iframe class="embed-responsive-item video" src="https://www.youtube.com/embed/icJWYN9BaI0" id="video"  allowscriptaccess="always" allow="autoplay"></iframe>
									</div>
									
									</div>
									
									</div>
									</div>
									</div> 
									<?php
									break;
									
									case "GetPosMultiplePay": 
									?>
									<div class="modal fade in show_modal">
									<div class="modal-dialog modal-lg" role="document">
									<div class="modal-content" >
									<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close" ><span aria-hidden="true">×</span></button>
									<h4 class="modal-title"><?php echo trans('multiple_payment'); ?></h4>
									</div>
									<div class="modal-body" >
									<div class="row" >
									<div class="col-md-9" >
									<div class="add_paymnent">  <!--  -->
									<div class="row" >
									<div id="payment_rows_div" >
									<div class="col-md-12" >
									<div class="ibox" >
									<div class="ibox-title">
									<button type="button" class="btn btn-primary btn-sm pull-right add-payment-row" id="">Add Payment Row</button>
									</div>
									<div class="ibox-content">
									<input id="total_payment_amount" type="hidden" value="<?php echo $_POST['payment_amount']; ?>">
									<div class="row">
									<div class="col-md-6">
									<div class="form-group">
									<label for="amount_0" >Amount:*</label>
									<div class="input-group" >
									<span class="input-group-addon" ><i class="fa fa-credit-card"></i></span>
									<input class="form-control payment-amount input_number valid PaymentAmountChange" required="" placeholder="Amount" name="payment_amount[]" type="text" value="0.00">
									</div>
									</div>
									</div>
									<div class="col-md-6">
									<div class="form-group" >
									<label for="method_0" >Payment Method:*</label>
									<div class="input-group" >
									<span class="input-group-addon" ><i class="fa fa-credit-card"></i></span>
									<select class="form-control col-md-12 payment_types_dropdown" required="" style="width:100%;" name="payment_method[]" aria-required="true" >
									<?php $paymentMethods = app('admin')->getall("pos_payment_method"); foreach($paymentMethods as $paymentMethod){ ?>
									<option value="<?php echo $paymentMethod['payment_method_value']; ?>"><?php echo $paymentMethod['payment_method_name']; ?></option>
									<?php } ?>
									</select>
									</div>
									</div>
									</div>
									<div class="clearfix"></div>
									<div class="payment_details_div hide">
									<div class="col-md-12">
									<div class="form-group">
									<label for="transaction_no_3_0">Transaction No.</label>
									<input class="form-control" placeholder="Transaction No." name="transaction_no[]" type="text" value="">
									</div>
									</div>
									</div>
									<div class="col-md-12">
									<div class="form-group" >
									<label for="note_0">Payment note:</label>
									<textarea class="form-control" rows="3" name="payment_note[]" cols="50"></textarea>
									</div>
									</div>
									</div>
									</div>
									</div>
									</div>
									</div>
									</div>
									</div>
									<br>
									<div class="row" >
									<div class="col-md-12">
									<div class="form-group" >
									<label for="sale_note" >Sell note:</label>
									<textarea class="form-control" rows="3" placeholder="Sell note" name="sale_note" cols="50"></textarea>
									</div>
									</div>
									
									</div>
									</div>
									<div class="col-md-3">
									<div class="ibox">
									<div class="ibox-content" >
									<div class="col-md-12" >
									<hr>
									<strong>
									Total Payable:
									</strong>
									<br>
									<span class="lead text-bold total_payable_span" ><?php echo $_POST['payment_amount']; ?></span>
									</div>
									
									<div class="col-md-12" >
									<hr>
									<strong>
									Total Paying:
									</strong>
									<br>
									<span class="lead text-bold total_paying" >0.00</span>
									</div>
									
									<div class="col-md-12" >
									<hr>
									<strong>
									Change Return:
									</strong>
									<br>
									<span class="lead text-bold change_return_span" >0.00</span>
									</div>
									
									<div class="col-md-12" >
									<hr>
									<strong>
									Due Balance:
									</strong>
									<br>
									<span class="lead text-bold balance_due" > 0.00</span>
									</div>
									
									</div>
									</div>
									</div>
									</div>
									</div>
									<div class="modal-footer">
									<button type="submit" class="btn btn-primary"><?php echo trans('finalize_amount');?></button>
									<button type="button" class="btn btn-default" data-dismiss="modal" ><?php echo trans('close');?></button>
									</div>
									</div>
									</div>
									</div>
									<script>
									$(document).on("keyup",".PaymentAmountChange", function(){
									var total_payment_amount =  $('#total_payment_amount').val() || 0;
									var total_paid_amount = 0;
									$('input[name="payment_amount[]"]').each(function() {
									total_paid_amount +=  parseInt($(this).val() || 0);
									});
									var total_due_amount = parseInt(total_payment_amount) - parseInt(total_paid_amount);
									$('.total_paying').html(total_paid_amount || 0);
									$('input[name^="sales_receive_amount"]').val(total_paid_amount || 0);
									if(total_due_amount < 0){
									$('.change_return_span').html(total_due_amount || 0);
									$('.balance_due').html(0);
									$(".due_amount_label").addClass('hidden');
									$(".pay_change_label").removeClass("hidden");
									$('input[name^="sales_pay_change"]').val(total_due_amount || 0);
									$('input[name^="due_amount"]').val(0);
									}else{
									$('.balance_due').html(total_due_amount || 0);
									$('.change_return_span').html(0);
									$(".pay_change_label").addClass('hidden');
									$(".due_amount_label").removeClass("hidden");
									$('input[name^="due_amount"]').val(total_due_amount || 0);
									$('input[name^="sales_pay_change"]').val(0);
									}
									});
									
									$(".payment_types_dropdown").click(function(){
									var type =$(this).val();
									if(type!='cash'){
									$(".payment_details_div").removeClass('hide');
									}else{
									$(".payment_details_div").addClass('hide');	
									}
									});
									
									function remove_payment(el){
									$(el).parent('div').parent('div').parent('div').parent('div').parent('div').remove();
									}
									
									function payment_type_change(id){
									var div=".payment_types_dropdown"+id;
									var type=$(div).val();
									if(type!='cash'){
									$(".payment_details_div"+id).removeClass('hide');
									}
									else{
									$(".payment_details_div"+id).addClass('hide');	
									}
									}
									
									$('.add-payment-row').click(function(){
									var html ='<div class="row" >'+
									'<div id="payment_rows_div">'+
									'<div class="col-md-12" >'+
									'<div class="ibox">'+
									'<div class="ibox-title">'+
									'<button type="button" class="close" onclick="remove_payment(this)" aria-label="Close" ><span aria-hidden="true">×</span></button>'+
									'</div>'+
									'<div class="ibox-content">'+
									'<div class="row">'+
									'<input type="hidden" class="payment_row_index" value="0">'+
									'<div class="col-md-6">'+
									'<div class="form-group">'+
									'<label for="amount_0" >Amount:*</label>'+
									'<div class="input-group" >'+
									'<span class="input-group-addon" ><i class="fa fa-credit-card"></i></span>'+
									'<input class="form-control payment-amount input_number valid PaymentAmountChange" required="" id="amount_0" placeholder="Amount" name="payment_amount[]" type="text" value="0.00" aria-required="true" aria-invalid="false">'+
									'</div>'+
									'</div>'+
									'</div>'+
									'<div class="col-md-6">'+
									'<div class="form-group" >'+
									'<label for="method_0" >Payment Method:*</label>'+
									'<div class="input-group" >'+
									'<span class="input-group-addon" ><i class="fa fa-credit-card"></i></span>'+
									'<select class="form-control col-md-12 payment_types_dropdown'+count+'" required=""'+ 'id="method" onchange="payment_type_change('+count+')" style="width:100%;" name="payment_method[]" aria-required="true" >'+
									<?php 
									$paymentMethods = app('admin')->getall("pos_payment_method");
									foreach($paymentMethods as $paymentMethod){
									?>
									'<option value="<?php echo $paymentMethod['payment_method_value']; ?>"><?php echo $paymentMethod['payment_method_name']; ?></option>'+
									
									<?php } ?>
									'</select>'+
									'</div>'+
									'</div>'+
									'</div>'+
									'<div class="clearfix"></div>'+
									'<div class="payment_details_div'+count+' hide">'+
									'<div class="col-md-12">'+
									'<div class="form-group">'+
									'<label for="transaction_no_3_0">Transaction No.</label>'+
									'<input class="form-control" placeholder="Transaction No." id="transaction_no_3_0" name="transaction_no[]" type="text" value="">'+
									'</div>'+
									'</div>'+
									'</div>'+
									'<div class="col-md-12">'+
									'<div class="form-group" >'+
									'<label for="note_0">Payment note:</label>'+
									'<textarea class="form-control" rows="3" id="payment_note[]" name="payment_note[]" cols="50"></textarea>'+
									'</div>'+
									' </div>'+
									' </div>'+
									'</div>'+
									'</div>'+
									'</div>'+
									'</div>';
									
									$(".add_paymnent").append(html);
									count++;
									});
									</script>
									
									
									
									<?php
									break;
									
									default:
									break;
									}
									
									function onlyAdmin()
									{
									if (! (app('login')->isLoggedIn() && app('current_user')->is_admin)) {
									respond(array('error' => 'Forbidden.'), 403);
									}
									}
									
									function onlySuperAdmin()
									{
									if (! (app('login')->isLoggedIn() && app('current_user')->is_superadmin)) {
									respond(array('error' => 'Forbidden.'), 403);
									}
									}
									
									function onlyAgent()
									{
									if (! (app('login')->isLoggedIn() && app('current_user')->is_agent)) {
									respond(array('error' => 'Forbidden.'), 403);
									}
									}
																		