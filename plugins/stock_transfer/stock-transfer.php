<?php defined('_AZ') or die('Restricted access');
	app('admin')->checkAddon('stock_transfer',true);
	app('pos')->checkPermission('stock_transfer','edit',true) or die(redirect("/pos/access-denied"));
	include dirname(__FILE__) .'/../../modules/pos/include/header.php';
	include dirname(__FILE__) .'/../../modules/pos/include/side_bar.php';
	include dirname(__FILE__) .'/../../modules/pos/include/navbar.php';
?>
[header]
<?php 
	getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false);
	getCss('assets/system/css/plugins/datapicker/datepicker3.css');
	getCss('assets/system/css/plugins/select2/select2.min.css');
	getCss('assets/system/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css');
?>
[/header]
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-4">
		
		<h2> <?php echo trans('stock_transfer'); ?></h2>
		<ol class="breadcrumb">
			<li>
				<a href=""><?php echo trans('dashboard'); ?></a>
			</li>
			<li class="active">
				<strong><?php echo trans('stock_transfer'); ?></strong>
			</li>
		</ol>
	</div>
</div>
<div class="stock_transfer">
	<form>
		<div class="wrapper wrapper-content animated fadeInRight">
			<div class="ibox-content  m-b-sm border-bottom">
				
				<div class="row">
					<div class="col-sm-3">
						<div class="form-group" id="data_1">
							<label class="control-label"><?php echo trans('date'); ?>: <span style="color:red"><i class="fa fa-star"></i></span></label>
							<div class="input-group date">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								<input type="text" class="form-control" name="date" value="<?php echo date('Y-m-d');?>" readonly>
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label class="control-label" for="date_from"><?php echo trans('reference_no'); ?>:</label>
							<input type="text" class="form-control" name="reference_no">
						</div>
					</div>
					<?php
						if(app('admin')->checkAddon('multiple_store_warehouse')){
						?>
						<div class="col-sm-2">
							<div class="form-group">
								<label class="control-label">
									<?php echo trans('location(from)'); ?>
								</label>
								<select class="form-control" id="store_id" name="from_location">
									<?php $posStores = app('admin')->getall('pos_store'); foreach($posStores as $posStore){	?>
										<option value="<?php echo $posStore['store_id']; ?>" <?php if($posStore['store_id'] == $this->currentUser->store_id) echo "selected"; ?>><?php echo $posStore['store_name']; ?></option>
									<?php } ?>
								</select>
							</div>	
						</div>
						<div class="col-sm-2">
							<div class="form-group">
								<label class="control-label">
									<?php echo trans('location(to)'); ?>
								</label>
								<select class="form-control" name="to_location" id="to_location">
									<?php $posStores = app('admin')->getall('pos_store'); foreach($posStores as $posStore){	?>
										<option value="<?php echo $posStore['store_id']; ?>" <?php if($posStore['store_id'] == $this->currentUser->store_id) echo "selected"; ?>><?php echo $posStore['store_name']; ?></option>
									<?php } ?>
								</select>
							</div>	
						</div>
					<?php } ?>
					<div class="col-sm-2">
						<div class="form-group">
							<label class="control-label"><?php echo trans('status'); ?>: <span style="color:red"><i class="fa fa-star"></i></span></label>
							<select class="form-control" name="status">
								<option value=""><?php echo trans('select_status'); ?></option>
								<option value="send"><?php echo trans('send'); ?></option>
								<option value="received"><?php echo trans('receive'); ?></option>
							</select>
						</div>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-lg-12">
					<div class="ibox">
						<div class="ibox-title">
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
								</div>
							</div>
						</div>
						<div class="ibox-content">
							<div class="row">
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-hover " id="StockTable">
										<thead>
											<tr>
												<th class="text-center"><?php echo trans('product'); ?></th>
												<th class="text-center"><?php echo trans('unit_price'); ?></th>
												<th class="text-center"><?php echo trans('available_quantity'); ?></th>
												<th class="text-center"><?php echo trans('quantity'); ?></th>
												<th class="text-center"><?php echo trans('sub_total'); ?></th>
												<th class="text-center"><?php echo trans('action'); ?></th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
							</div>
							<div class="row">
								<div class="ibox-content">
									<div class="col-sm-6 checkbox checkbox-primary add_shipping">
										<input id="checkbox2" type="checkbox" class="shipping">
										<label for="checkbox2"><?php echo trans('enable_shipping'); ?></label>
									</div>
									<div class="col-sm-6 shipping_charge">
										<div class="form-group">
											<label class="control-label"><?php echo trans('Shipping Charges'); ?>:</label>
											<input type="text" class="form-control" name="shipping_charge">
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label class="control-label"><?php echo trans('note'); ?>:</label>
											<textarea class="form-control" name="note"></textarea>
										</div>
									</div>
									<button class="btn btn-primary pull-right" type="submit"><strong><?php echo trans('save'); ?></strong></button>
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
<?php  
	getJs('assets/system/js/plugins/typehead/bootstrap3-typeahead.min.js',false); 
	getJs('assets/system/js/plugins/dataTables/datatables.min.js',false,false);
	getJs('assets/system/js/plugins/datapicker/bootstrap-datepicker.js');
	getJs('assets/system/js/plugins/select2/select2.full.min.js',false,false);
?>
<script>
	
	function getstore(){
		var store_id = $('#store_id').val();
		
		if(store_id!= ''){
			AS.Http.post({"action" : "GetStoreNot","store_id" : store_id}, "pos/ajax/", function (response) {
				if(response.status=='success'){
					$("#to_store").html(response.data);
				}
			});
			}else{
			$('#to_store')
			.find('option')
			.remove()
			.end()
			.append('<option value=""><?php echo trans('select_location'); ?></option>');
		}
	}
	
	$('.shipping').change(function(ev) {
		if ( this.checked ) $(".shipping_charge").show();
		$(".add_shipping").hide();
	});
	
	$(".stock_transfer form").validate({
		rules:{
			date:{
				required: true
			},
			from_location:{
				required: true
			},
			to_location:{
				required: true
			},
			status:{
				required: true
			}
			
		},
		submitHandler: function(form) {
			AS.Http.PostSubmit(form, {"action" : "StockTransferSubmit"}, "pos/ajax/", function (response) {
				if(response.status=='success'){
					swal({
						title: $_lang.success, 
						text: response.message, 
						type: "success",
						confirmButtonColor: "#1ab394", 
						confirmButtonText: $_lang.ok,
						},function(isConfirm){
						location.reload();
					});
				}
			});
		}
	});
	
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
						AddStockProductList(current.product_id);
						$("#purchase_barcode").val('');
						$("#purchase_barcode").focus();
						$('.typeahead').typeahead('destroy');
					} 
				});
			}
		});
	});
	
	$(document).on("keypress",".barcode_type_search", function(e){
		if ( e.which == 13 ){
			var purchaseBarcode = $('#purchase_barcode').val();
			AddStockProductList(purchaseBarcode)
			$("#purchase_barcode").val('');
			$("#purchase_barcode").focus();
			e.preventDefault();
		} 
	});
	
	$(document).on("keyup",".onchange_purchase_cal", function(){
		AS.Util.removeErrorMessages();
		var Id = $(this).data("id");
		var purchase_quantity = $("#purchase_quantity_"+Id).val() || 0;
		var purchase_price = $("#purchase_price_"+Id).val() || 0;
		var available_stock = $("#available_stock_"+Id).val() || 0;
		
		if(parseInt(available_stock) < purchase_quantity){
			AS.Util.ShowErrorByElement('purchase_quantity_'+Id, "Invalide Quantity");
			}else{
			var product_purchase_value = purchase_price * purchase_quantity;
			$("#purchase_sub_total_"+Id).val(product_purchase_value);
		}
		
		
	});
	
	function AddStockProductList(productCode){
		AS.Util.removeErrorMessages();
		var StoreId = $('#store_id').val();
		if(!StoreId){
			AS.Util.ShowErrorByElement('store_id', "Select Store Location First");
			return;
		}
		AS.Http.post({"action" : "GetPurchaseProductList","product_code" : productCode}, "pos/ajax/", function (response) {
			if(response.product_status == 'found'){
				if(response.available_stock <= 0){
					swal ( "Oops" ,  "No Avaliable Product" ,  "error" );
					return;
				}
				var generator = new IDGenerator();
				var StockTransferTable = document.getElementById('StockTable');
				var rowCnt = StockTransferTable.rows.length; 
				var tr = StockTransferTable.insertRow(rowCnt); 
				var stock_id = "ST"+generator.generate(); 
				var uid = new Date().getTime();
				for (var c = 0; c <= 5; c++) {
					var td = document.createElement('td');      
					td = tr.insertCell(c);
					if(c==0){
						var ele = document.createElement('input');
						ele.setAttribute('type', 'hidden');
						ele.setAttribute('value', productCode);
						ele.setAttribute('name', 'sub_product_id[]');
						td.append(response.product_name+' ['+response.variation_name+'] ['+productCode+']');
						td.setAttribute('class', 'text-center');
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
						}else if(c==1){
						var ele = document.createElement('input');
						ele.setAttribute('type', 'hidden');
						ele.setAttribute('value', response.purchase_price);
						ele.setAttribute('name', 'purchase_product_price[]');
						ele.setAttribute('data-id', uid);
						ele.setAttribute('id', 'purchase_price_'+uid);
						ele.setAttribute('placeholder', $_lang.purchase_price);
						td.appendChild(ele);
						
						td.append(response.purchase_price);
						td.setAttribute('class', 'text-center');
						td.appendChild(ele);
						}else if(c==2){
						var ele = document.createElement('input');
						ele.setAttribute('type', 'hidden');
						ele.setAttribute('value', response.available_stock);
						ele.setAttribute('name', 'available_stock[]');
						ele.setAttribute('data-id', uid);
						ele.setAttribute('id', 'available_stock_'+uid);
						ele.setAttribute('placeholder', $_lang.available_stock);
						td.appendChild(ele);
						
						td.append(response.available_stock);
						td.setAttribute('class', 'text-center');
						td.appendChild(ele);
						}else if(c==3){
						var ele = document.createElement('input');
						ele.setAttribute('type', 'number');
						ele.setAttribute('value', "1");
						ele.setAttribute('name', 'purchase_product_quantity[]');
						ele.setAttribute('data-id', uid);
						ele.setAttribute('id', 'purchase_quantity_'+uid);
						ele.setAttribute('class', 'form-control input-sm onchange_purchase_cal text-center');
						ele.setAttribute('placeholder', $_lang.purchase_quantity);
						td.appendChild(ele);
						}else if(c==4){
						var ele = document.createElement('input');
						ele.setAttribute('type', 'text');
						ele.setAttribute('value', response.purchase_price);
						ele.setAttribute('name', 'purchase_product_sub_total[]');
						ele.setAttribute('data-id', uid);
						ele.setAttribute('id', 'purchase_sub_total_'+uid);
						ele.setAttribute('class', 'form-control input-sm text-center');
						ele.setAttribute('readonly', '');
						td.appendChild(ele);
						}else if(c==5){
						td.setAttribute('class', 'text-center');
						var ele = document.createElement('a');
						ele.setAttribute('href', 'javascript:void(0);');
						ele.setAttribute('class', 'btn btn-danger btn-xs purchase_product_delete');
						var ele_icon = document.createElement('i');
						ele_icon.setAttribute('class', 'fa fa-trash');
						ele.appendChild(ele_icon);
						td.appendChild(ele);
					}
				}
				$('.date-picker').datepicker({
					todayBtn: "linked",
					format: "yyyy-mm-dd",
					keyboardNavigation: false,
					forceParse: false,
					calendarWeeks: true,
					autoclose: true
				});
				
				$(".purchase_product_delete").click(function(){
					$(this).parent('td').parent('tr').remove();
				});
				}else{
				swal ( "Oops" ,  "No Product Found!" ,  "error" );
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
	
	$(document).ready(function(){
		$(".shipping_charge").hide();
		
		$('#data_1 .input-group.date').datepicker({
			todayBtn: "linked",
			format: "yyyy-mm-dd",
			keyboardNavigation: false,
			forceParse: false,
			calendarWeeks: true,
			endDate: new Date(),
			autoclose: true
		});
		
		$('.dataTables-example').DataTable({
			pageLength: 25,
			responsive: true,
			dom: '<"html5buttons"B>lTfgitp',
			buttons: [
			{extend: 'excel', title: 'purchase_report'},
			{extend: 'pdf', title: 'purchase_report'},
			
			{extend: 'print',
				customize: function (win){
					$(win.document.body).addClass('white-bg');
					$(win.document.body).css('font-size', '10px');
					
					$(win.document.body).find('table')
					.addClass('compact')
					.css('font-size', 'inherit');
				}
			}
			]
			
		});
		
	});
	$(".purchase_product_delete").click(function(){
		$(this).parent('td').parent('tr').remove();
	});
	
</script>
[/footer]
<?php include dirname(__FILE__) .'/../../modules/pos/include/footer.php';
?>																														