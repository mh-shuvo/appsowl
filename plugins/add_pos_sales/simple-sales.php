<?php defined('_AZ') or die('Restricted access');
	app('admin')->checkAddon('add_purchase',true);
	app('pos')->checkPermission('sale_pos','view',true) or die(redirect("/pos/access-denied"));
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
<style type="text/css">
	/*.table-wrapper-scroll-y {
	display: block;
	max-height: 200px;
	overflow-y: auto;
	-ms-overflow-style: -ms-autohiding-scrollbar;
	}*/
	
	thead tr th { text-align: center; }
	#SalesTable tbody{ text-align: center; }
	#SalesTable tbody tr td input{ text-align: center; }
	
	.panel{background-color: #b2bac9;color: black;}
	.btn-lg{height: 73px; font-weight: bold;}
	.add-payment-row{margin-top: -10px;}
	.due{color:red;}
	.due .form-group input{border:red;}
</style>
<?php getCss('assets/system/css/plugins/touchspin/jquery.bootstrap-touchspin.min.css',false);?>
[/header]
<div class="wrapper wrapper-content">
	<div class="row">
		<div class="AddPosAdvance">
			<form id="PosSalesForm">
				<input type="hidden" name="sales_id" value="<?php if(isset($this->route['id'])){ echo $GetSalesProduct['sales_id']; }else{ echo $salesId; } ?>">
				<div class="col-md-4">
					<div class="ibox">
						<div class="ibox-title">
							<h5><?php echo trans('sale'); ?></h5>
							<div class="pull-right" style="margin-top:0px;">
								<a href="javascript:void(0)" class="last_receipt" data-toggle="modal">
									<i class="fa fa-receipt" title="Last Receipt"></i>
								</a>
							</div>
						</div>
						<div class="ibox-content">
							<div class="row">
								<div class="form-group">
									<div class="input-group">
										<span class="input-group-addon"> <i class="fa fa-user"></i> </span>
										<input type="text" class="form-control customer_name_code_search" name="customer_code" id="customer_code" placeholder="<?php echo trans('enter_customer_name_customer_id');?>">
										<div class="input-group-btn">
											<button type="button"  class="btn btn-default OpenContactModal"  title="<?php echo trans('new_contact'); ?>" ><i class="fa fa-plus-circle text-primary fa-lg"></i></button>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="form-group">
									<div class="input-group">
										<span class="input-group-addon">
											<i class="fa fa-barcode"></i>
										</span>
										<input type="text" class="form-control barcode_type_search" placeholder="<?php echo trans('enter_product_name_/_squ_/_scan_barcode'); ?>" id="sales_barcode" name="sales_barcode">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 table-responsive" >
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
											<?php for ($x = 0; $x < 0; $x++) { ?>
												<tr>
													<td>test product
														<input type="hidden" class="form-control" name="product_id[]">
														<input type="hidden" class="form-control" name="product_stock_id[]">
													</td>
													<td>
														<input type="text" class="form-control input-sm" value="1" name="product_price[]">
													</td>
													<td>
														<input type="number" class="form-control input-sm" value="1" name="product_quantity[]">
													</td>
													<td>
														<input type="text" class="form-control input-sm" value="1" name="product_subtotal[]">
													</td>
													<td>
														<button class="btn btn-danger btn-xs product_delete"><i class="fa fa-trash"></i></button>
													</td>
												</tr>
											<?php } ?>
											<tr id="empty_cart"><td></td><td></td><td></td><td></td><td></td></tr>
											
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="ibox">
						<div class="ibox-title">
							<h5><?php echo trans('payment_method'); ?></h5>
							<div class="ibox-tools">
								<!--a href="javascript:void(0)" onclick="GetReceiptView()">
									<i class="fa fa-receipt" title="Last Receipt"></i>
								</a-->
							</div>
						</div>
						<div class="ibox-content">
							<div class="form-group">
								<div class="row">
									<div class="col-sm-4">
										<label><?php echo trans('sub_total'); ?>:</label>
									</div>
									<div class="col-sm-8">
										<input class="form-control input-sm" type="text" readonly name="sales_sub_total" value="0">
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-sm-4">
										<label id="product_total_vat_label"><?php echo trans('vat'); ?><?php if($getpossetting['vat_type'] == "global") echo '('.$getpossetting['vat'].'%)'; ?>:</label>
									</div>
									<div class="col-sm-8">
										<input class="form-control input-sm" type="text" readonly name="sales_vat" value="0">
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-sm-4">
										<label><?php echo trans('total'); ?>:</label>
									</div>
									<div class="col-sm-8">
										<input class="form-control input-sm onchange_sales_final_cal" type="text" readonly name="sales_total" value="0">
									</div>
								</div>
							</div>
							<!--div class="form-group">
								<div class="row">
								<div class="col-sm-4">
								<label><?php echo trans('sale_return_id'); ?> :</label>
								</div>
								<div class="col-sm-8">
								<input type="text" placeholder="<?php echo trans('return_id'); ?>" name="return_id" id="return_id" class="form-control" onchange="returnidchange()">
								</div>
								</div>
								</div>
								<div class="form-group return-amount hidden">
								<div class="row">
								<div class="col-sm-4">
								<label><?php echo trans('return_amount'); ?> :</label>
								</div>
								<div class="col-sm-8">
								<input type="text" placeholder="<?php echo trans('return_id'); ?>" name="return_amount" id="return_amount" class="form-control" value="0" readonly>
								</div>
								</div>
							</div-->
							<div class="form-group">
								<div class="row">
									<div class="col-sm-4">
										<label><?php echo trans('discount'); ?> :</label>
									</div>
									<div class="col-sm-8">
										<input class="form-control input-sm onchange_sales_final_cal" type="text" name="sales_discount" value="0">
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-sm-4">
										<label style='color:#ed5565;'><?php echo trans('need_to_pay'); ?> :</label>
									</div>
									<div class="col-sm-8">
										<input class="form-control input-sm" type="text" readonly name="sales_need_to_pay" value="0">
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-sm-4">
										<label><?php echo trans('method'); ?> :</label>
									</div>
									<div class="col-sm-8">
										<select name="payment_method" id="payment_method" class="form-control">
											<option value=""><?php echo trans('select_payment_method'); ?></option>
											<?php $getpayment=app('admin')->getwhere('pos_payment_method','payment_method_status','active');
												foreach ($getpayment as $payment) { ?>
												<option value="<?php echo $payment['payment_method_value']; ?>"><?php echo $payment['payment_method_name']; ?> </option>
											<?php } ?>
										</select>
									</div>
								</div>
							</div>
							<div class="form-group transition-id hidden">
								<div class="row">
									<div class="col-sm-4">
										<label><?php echo trans('transition_id'); ?> :</label>
									</div>
									<div class="col-sm-8">
										<input type="text" placeholder="<?php echo trans('transition_id'); ?>" name="transition_id" id="transition_id" class="form-control" value="">
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-sm-4">
										<label style='color:#1ab394;'><?php echo trans('received'); ?> :</label>
									</div>
									<div class="col-sm-8">
										<input class="form-control input-sm onchange_sales_final_cal" type="text" name="sales_receive_amount" value="0">
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-sm-4">
										<label style='color:0828d8;' ><?php echo trans('pay_change'); ?> :</label>
									</div>
									<div class="col-sm-8">
										<input class="form-control input-sm" type="text" readonly name="sales_pay_change" value="0">
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-sm-4">
										<label style='color:#ed5565;' ><?php echo trans('due_amount'); ?> :</label>
									</div>
									<div class="col-sm-8">
										<input style='color:#ed5565;' class="form-control input-sm" type="text" readonly name="due_amount" value="0">
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-sm-4">
										<label><?php echo trans('sales_note'); ?> :</label>
									</div>
									<div class="col-sm-8">
										<textarea name="sales_note" id="sales_note" class="form-control" placeholder="<?php echo trans('sales_note_des');?>"></textarea>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-sm-12">
										<button type="submit" class="btn btn-sm btn-primary pull-right m-t-n-xs"><strong><?php echo trans('completed'); ?></strong></button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
		<!--div class="col-md-5">
			<div class="ibox">
			<div class="ibox-content">
			<div class="row">
			<div class="m-b-sm  col-md-4">
			<input type="text" class="form-control" placeholder="<?php echo trans('search_by_product_name'); ?>" id="product_name_search" onkeypress="getproductbyname()">
			</div> 
			<div class="m-b-sm  col-md-4">
			<input type="text" class="form-control" placeholder="<?php echo trans('search_by_product_code'); ?>" id="product_code_search" onchange="getproductbycode()">
			</div> 
			<div class="m-b-sm  col-md-4">
			<select class="selectpicker form-control" data-show-subtext="true" data-live-search="true" style="height: 150px;" id="product_category" onchange="getproductbycategory()" >
			<option><?php echo trans('search_by_category'); ?></option>
			
			<?php $pos_category = app('admin')->getall('pos_category'); 
				foreach($pos_category as $pos_Category){
					echo "<option value=".$pos_Category['category_id'].">".$pos_Category['category_name']."</option>";
				}?>
				</select>
				</div>
				</div>
				<div class="row" id="product_list">
				<?php $product = app('admin')->getwhere('pos_product','category_id',$pos_Category['category_id']); 
					foreach($product as $Product){ ?>
					<div class="m-b-sm col-md-2">
					<a data-id="<?php echo $Product['id']; ?>" href="javascript:void(0);" class="addcartpurchase">
					<img src="assets/system/product/avatar.jpg" class="img-lg" hspace="5" vspace="5">
					<p class="font-bold text-left"><?php echo $Product['product_name']; ?></p>
					</a>
					</div>
				<?php } ?>
				</div>
				</div>
				</div>
		</div-->
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
					<div class="row" id="product_list"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="outside_modal_status"></div>
<div class="receiptview"></div>
<div class='last_receipt_view hidden'></div>
<!-- end  receipt inovice modal-->

[footer]
<?php
	getJs('assets/system/js/plugins/dataTables/datatables.min.js',false,false);
	getJs('assets/system/js/plugins/typehead/bootstrap3-typeahead.min.js',false);
?>
<script>
	
	$(document).on("click",".onclick_cart_add", function(){
		var Id = $(this).data("id");
		AddSalesProductList(Id);
		SalesListCal(Id);
		SalesFinalCal();
	});
	
	$(document).on("keyup",".onchange_sales_final_cal", function(){
		SalesFinalCal();
	});
	
	$(document).on("keyup",".product_search_by_name_code", function(){
		$("#product_list").html(" ");
		var product_name_search =  $('#product_name_search').val() || null;
		var product_brand =  $('#product_brand').val() || null;
		var product_category =  $('#product_category').val() || null;
		GetPosProductListByProductId(product_name_search,product_brand,product_category);
	});
	
	$(document).on("change",".product_search", function(){
		$("#product_list").html(" ");
		var product_name_search =  $('#product_name_search').val() || null;
		var product_brand =  $('#product_brand').val() || null;
		var product_category =  $('#product_category').val() || null;
		GetPosProductListByProductId(product_name_search,product_brand,product_category);
	});
	
	$(document).on("change","#payment_method", function(){
		var paidamount = $("#payment_method").val();
		$(".transition-id").addClass('hidden');
		
		if(paidamount != 'cash'){
			$(".transition-id").removeClass("hidden");
			}else{
			$(".transition-id").addClass('hidden');
		}
	});
	
	function GetPosProductListByProductId(productCode,brandId,categoryId){
		AS.Http.post({"action" : "GetProductListFilter","product_code" : productCode, "brand_id" : brandId, "category_id" : categoryId}, "pos/ajax/", function (response) {
			if(response.product_status == 'found'){
				var product_lists = response.source_data;
				var datas = null;
				for(var i = 0; i < product_lists.length; i++) {
					var product_list = product_lists[i];
					datas += '<div class="col-lg-2">'+
					'<a  href="javascript:void(0);" class="onclick_cart_add" data-id="'+product_list.product_id+'">'+
					'<img src="images/stores/<?php echo $_SERVER['SERVER_NAME']; ?>/'+product_list.product_image+'" class="img-lg" hspace="5" vspace="5">'+
					'<p class="font-bold text-left">'+product_list.name+'</p>'+
					'</a>'+
					'</div>';
				}
				$("#product_list").html(datas);
				}else{
				$("#product_list").html(" ");
			}
		});
	}
	
	$('.AddPosAdvance form').validate({
		rules: {
			sales_sub_total: {
				required: true
			}
		},	
		submitHandler: function(form) {
			// console.log(form['payment_method'].value);
			AS.Http.PostSubmit(form, {"action" : "GetPosAdvanceData"}, "pos/ajax/", function (response) {
				$(".show_modal").remove();
				AS.Http.posthtml({"action" : "GetReceiptView"}, "pos/modal/", function (data) {
					$(".outside_modal_status").html(data);
					$(".show_modal").modal("show");
				});
				resetPos();
			});
		}
	});
	
	$(document).on("click",".OpenContactModal", function(){
		var new_customer_name = $("#customer_code").val();
		AS.Http.posthtml({"action" : "GetNewContact","type" : "customer","customer_name" : new_customer_name}, "pos/modal/", function (data) {
			$(".outside_modal_status").html(data);
			$("#contact_type").val("customer");
			$(".business").hide();
			$(".show_modal").modal("show");
		});
	});
	
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
	
	function AddSalesProductList(productCode){
		var product_quantity = $("#product_quantity_"+productCode).val() || 0;
		AS.Http.post({"action" : "GetSalesProductList","product_code" : productCode, productQuantity : product_quantity, productCode,"serial_no_check" : "no_serial"}, "pos/ajax/", function (response) {
			if(response.product_status == 'found'){
				$('#empty_cart').remove();
				var generator = new IDGenerator();
				var PurchaseTable = document.getElementById('SalesTable').getElementsByTagName('tbody')[0];
				if (PurchaseTable.rows[productCode]){
					var new_quantiry =  parseInt(product_quantity) + parseInt('1');
					$("#product_quantity_"+productCode).val(new_quantiry);
					}else{
					var rowCnt = PurchaseTable.rows.length; 
					var tr = PurchaseTable.insertRow(rowCnt); 
					var stock_id = "ST"+generator.generate(); 
					var uid = new Date().getTime();
					tr.setAttribute('id', productCode);
					for (var c = 0; c <= 4; c++) {
						var td = document.createElement('td');      
						td = tr.insertCell(c);
						if(c==0){
							var ele = document.createElement('input');
							ele.setAttribute('type', 'hidden');
							ele.setAttribute('value', productCode);
							ele.setAttribute('name', 'sub_product_id[]');
							td.append(response.product_name+' ['+response.variation_name+'] ['+productCode+']');
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
							ele.setAttribute('data-id', productCode);
							ele.setAttribute('id', 'product_vat_'+productCode);
							td.appendChild(ele);
							
							var ele = document.createElement('input');
							ele.setAttribute('type', 'hidden');
							ele.setAttribute('data-id', productCode);
							ele.setAttribute('id', 'total_product_vat_'+productCode);
							ele.setAttribute('name', 'total_product_vat[]');
							td.appendChild(ele);
							}else if(c==1){
							var ele = document.createElement('input');
							ele.setAttribute('type', 'text');
							ele.setAttribute('value', response.sell_price);
							ele.setAttribute('name', 'product_price[]');
							ele.setAttribute('data-id', productCode);
							ele.setAttribute('id', 'product_price_'+productCode);
							ele.setAttribute('class', 'form-control input-sm onchange_sales_cal');
							ele.setAttribute('placeholder', $_lang.product_price);
							td.appendChild(ele);
							}else if(c==2){
							var ele = document.createElement('input');
							ele.setAttribute('type', 'number');
							ele.setAttribute('value', "1");
							ele.setAttribute('name', 'product_quantity[]');
							ele.setAttribute('data-id', productCode);
							ele.setAttribute('id', 'product_quantity_'+productCode);
							ele.setAttribute('class', 'form-control input-sm onchange_sales_cal onchange_sales_qty');
							ele.setAttribute('placeholder', $_lang.product_quantity);
							td.appendChild(ele);
							}else if(c==3){
							var ele = document.createElement('input');
							ele.setAttribute('type', 'text');
							ele.setAttribute('value', "1");
							ele.setAttribute('name', 'product_subtotal[]');
							ele.setAttribute('data-id', productCode);
							ele.setAttribute('id', 'product_subtotal_'+productCode);
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
				
				$(".sales_product_delete").click(function(){
					$(this).parent('td').parent('tr').remove();
					var Id = $(this).data("id");
					SalesListCal(Id);
					SalesFinalCal();
				});
				SalesListCal(productCode);
				SalesFinalCal();
				
				if(response.product_current_stock == response.alert_quantity){
					swal ( "Oops" ,  "Product Limit!" ,  "error" );
				}
				}else if(response.product_status == "not_availiable_stock"){
				swal ( "Oops" ,  "Not Available!" ,  "error" );
				}else{
				swal ( "Oops" ,  "Not Product Found!" ,  "error" );
			}
		});
	}
	
	function IDGenerator() {
		
		this.length = 8;
		this.timestamp = +new Date;
		
		var _getRandomInt = function( min, max ) {
			return Math.floor( Math.random() * ( max - min + 1 ) ) + min;
		}
		
		this.generate = function() {
			var ts = this.timestamp.toString();
			var parts = ts.split( "" ).reverse();
			var id = "";
			
			for( var i = 0; i < this.length; ++i ) {
				var index = _getRandomInt( 0, parts.length - 1 );
				id += parts[index];	 
			}
			
			return id;
		}
	}
	
	$(".product_delete").click(function(){
		$(this).parent('td').parent('tr').remove();
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
		var sales_total =  $('input[name^="sales_total"]').val() || 0;
		var sales_need_to_pay =  $('input[name^="sales_need_to_pay"]').val() || 0;
		var sales_receive_amount =  $('input[name^="sales_receive_amount"]').val() || 0;
		<?php if($getpossetting['vat_type'] == "global"){ ?>
			var total_vat = parseInt(total_sales_vat) * parseInt(<?php echo $getpossetting['vat']; ?>) / 100;
			<?php	}elseif($getpossetting['vat_type'] == "single"){ ?>
			var total_vat = total_sales_vat;
		<?php } ?>
		var sales_with_vat = parseInt(total_sales_subtotal) + parseInt(total_vat);
		var sales_with_discount = parseInt(sales_with_vat) - parseInt(sales_discount);
		var sales_with_received_amount = parseInt(sales_with_discount) - parseInt(sales_receive_amount);
		
		$('input[name^="sales_sub_total"]').val(total_sales_subtotal);
		$('input[name^="sales_vat"]').val(total_vat);
		$('input[name^="sales_total"]').val(sales_with_discount);
		$('input[name^="sales_need_to_pay"]').val(sales_with_discount);
		
		if(sales_with_received_amount < 0){
			$(".due_amount_label").addClass('hidden');
			$(".pay_change_label").removeClass("hidden");
			$('input[name^="sales_pay_change"]').val(sales_with_received_amount);
			$('input[name^="due_amount"]').val(0);
			}else{
			$(".pay_change_label").addClass('hidden');
			$(".due_amount_label").removeClass("hidden");
			$('input[name^="due_amount"]').val(sales_with_received_amount);
			$('input[name^="sales_pay_change"]').val(0);
		}
	}
	
	function SalesListCal(Id){
		var product_price = $("#product_price_"+Id).val() || 0;
		var product_vat = $("#product_vat_"+Id).val() || 0;
		var product_quantity = $("#product_quantity_"+Id).val() || 0;
		var product_sales_value = product_price * product_quantity;
		var product_vat_value = product_vat * product_quantity;
		$("#product_subtotal_"+Id).val(product_sales_value);
		$("#total_product_vat_"+Id).val(product_vat_value);
	}
	
	function resetPos(){
		$('.show_modal').show().on('shown', function() { 
			$('.show_modal').modal('hide') 
		});
		$(".show_modal").remove();
		document.getElementById("PosSalesForm").reset();
		$("#SalesTable tbody").empty();
		$(".modal-backdrop").remove();
	}
	$('.last_receipt').click(function(){
		$(".show_modal").remove();
		AS.Http.posthtml({"action" : "GetReceiptView"}, "pos/modal/", function (data) {
			$(".outside_modal_status").html(data);
			$(".show_modal").modal("show");
		});
	})
</script>
[/footer]
<?php include dirname(__FILE__) .'/../../modules/pos/include/footer.php'; ?>
