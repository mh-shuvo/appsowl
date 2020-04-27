<?php defined('_AZ') or die('Restricted access'); 
	
	switch ($action) {
		
		case "GetOwnerEqutityData":
		app('pos')->getOwnerEqutityData($_POST,$this->currentUser->id,$this->currentUser->store_id);
		break;
		
		case "GetFinancialStatementData":
		app('pos')->getFinancialStatementData($_POST,$this->currentUser->id,$this->currentUser->store_id);
		break;
		
		case "GetIncomeStatementData":
		app('pos')->getIncomeStatementData($_POST,$this->currentUser->id,$this->currentUser->store_id);
		break;
		
		case "CheckaccountDueAdvance":
		app('pos')->getCheckaccountDueAdvance($_POST);
		break;
		
		case "GetDeleteTransactionId":
		app('db')->update("pos_transactions" ,  array(
		"is_delete"=> true
		),
		"`transaction_id` = :id ",
		array("id"=> $_POST['transaction_id'])
		);
		respond(array(
		"status" => "success"
		));
		break;
		
		case "TransactionDeleteById":
		app('pos')->TransactionDeleteById($_POST);
		break;
		
		case "DeletePaymentMethod":
		app('pos')->DeletePaymentMethod($_POST);
		break;
		
		case "AddSalesReturn":
		$_POST['document'] = null;
		if(isset($_FILES['document']) && $_FILES['document']['size'] != 0){
			$_POST['document'] = $_POST['return_id'].'.jpg';
			$upload_handler = new UploadHandler(array(
			'filename' => $_POST['return_id'],
			'max_file_size' => 10000000, //1MB file size
			'accept_file_types' => '/\.(jpe?g)$/i',
			'width' => 250, 
			'height' => 250, 
			'print_response' => false,
			'param_name' => 'document',
			'image_file_types' => '/\.(gif|jpe?g|png|psd|tiff|eps|ai|pdf|bmp)$/i',
			'upload_dir' => dirname(__FILE__).'/../images/stores/'.$_SERVER['SERVER_NAME'].'/',
			));
			$error_massage = json_decode(json_encode($upload_handler));
			if(!empty($error_massage->response->product_image[0]->error)){
				respond(array(
				'status' => 'error',
				'errors' => array(
				'document' => $error_massage->response->document[0]->error
				)
				), 422);
			}
		}
		app('pos')->AddSalesReturn($_POST,$this->currentUser->id,$this->currentUser->store_id);
		break;
		
		case "LogoutSubmit":
		app('pos')->LogoutSubmit($this->currentUser->id);
		break;
		
		case "DeleteStockByStockId":
		app('pos')->DeleteStockByStockId($_POST);
		break;
		
		case "GetInvoiceAdvanceData":
		app('pos')->GetInvoiceAdvanceData($_POST,$this->currentUser->id,$this->currentUser->store_id);
		break;
		
		case "RegisterSubmit":
		if($this->currentUser->store_id!=null){
			app('pos')->RegisterSubmit($_POST,$this->currentUser->id,$this->currentUser->store_id);
			}else{
			app('pos')->UpdateStoreid($_POST,$this->currentUser->id,$this->currentUser->store_id);
		}
		break;
		
		case 'CheckSerialProduct':
		$getProductVariation = app('admin')->getwhereid("pos_variations","sub_product_id",$_POST['product_code']);
		if($getProductVariation){
			$getProduct = app('admin')->getwhereid("pos_product","product_id",$getProductVariation['product_id']);
			if ($getProduct['product_serial']=='enable') {
				respond(array(
				"status" => "serial",
				"product_id" => $getProduct['product_id'],
				"product_serial" => $getProduct['product_serial']
				));
				}else{
				respond(array(
				"status" => "noserial"
				));
			}
		}
		break;
		
		case "GetDeleteSalesRow":
		app('pos')->GetDeleteSalesRow($_POST);
		break;
		
		case "AddPurchaseAdvanceData":
		
		if(isset($_FILES['purchase_document']) && $_FILES['purchase_document']['size'] != 0){
			$_POST['purchase_document_name'] = $_POST['purchase_id'].'.jpg';
			$upload_handler = new UploadHandler(array(
			'filename' => $_POST['purchase_id'],
			'max_file_size' => 1000000, //1MB file size
			'accept_file_types' => '/\.(jpe?g)$/i',
			'width' => 250, 
			'height' => 250, 
			'print_response' => false,
			'param_name' => 'purchase_document',
			'image_file_types' => '/\.(gif|jpe?g|png|psd|tiff|eps|ai|pdf|bmp)$/i',
			'upload_dir' => dirname(__FILE__).'/../images/stores/'.$_SERVER['SERVER_NAME'].'/document/',
			));
			$error_massage = json_decode(json_encode($upload_handler));
			if(!empty($error_massage->response->purchase_document[0]->error)){
				respond(array(
				'status' => 'error',
				'errors' => array(
				'purchase_document' => $error_massage->response->purchase_document[0]->error
				)
				), 422);
			}
		}
		app('pos')->InsertPurchaseProductAdvanced($_POST,$this->currentUser->id);
		break;
		
		case "GetPurchaseProductList":
		$getProductVariation = app('db')->table('pos_variations')->where('sub_product_id',$_POST['product_code'])->where('is_delete','false')->get(1);
		if($getProductVariation){
			$getProduct = app('db')->table('pos_product')->where('product_id',$getProductVariation['product_id'])->where('is_delete','false')->get(1);
			$getUnit = app('db')->table('pos_unit')->where('unit_id',$getProduct['unit_id'])->where('is_delete','false')->get(1);
			$available_quantity = app('pos')->GetProductAvaliableStock(null,$getProductVariation['sub_product_id']);
			$variation_name = $getProductVariation['variation_name'] ? '['.$getProductVariation['variation_name'].']' : "false";
			
			respond(array(
			"status" => "success",
			"product_status" => "found",
			"product_name" => $getProduct['product_name'],
			"product_type" => $getProduct['product_type'],
			"product_vat" => $getProduct['product_vat'],
			"unit_name" => $getUnit['unit_name'],
			"product_serial" => $getProduct['product_serial'],
			"product_stock" => $getProduct['product_stock'],
			"available_stock" => $available_quantity['product_stock'],
			"variation_name" => $variation_name,
			"product_id" => $getProductVariation['product_id'],
			"sub_product_id" => $getProductVariation['sub_product_id'],
			"purchase_price" => $getProductVariation['purchase_price'],
			"profit_percent" => $getProductVariation['profit_percent'],
			"sell_price" => $getProductVariation['sell_price'],
			"variation_id" => $getProductVariation['variation_id'],
			));
			}else{
			respond(array(
			"status" => "success",
			"product_status" => "Not found"
			));
		}
		break;
		
		case "PurchaseStatusChange":
		app('pos')->PurchaseStatusChange($_POST);
		break;
		
		case "NewProductIdGenerate":
		respond(array(
		"status" => "success",
		"product_id" => 'PR'.gettoken(8)
		));
		break;
		
		case "GetProductUnitView":
		app('pos')->GetUnitNameView($_POST['product_unit']);
		break;
		
		case "GetVariationData":
		$variationsCategorys = app('admin')->getwhereid("pos_variations_category","variation_category_id",$_POST['select_variation']);
		respond(array(
		"status" => "success",
		"values" => $variationsCategorys['variation_category_value']
		));
		break;
		
		case "AddNewBrandSubmit":
		app('pos')->AddNewBrandSubmit($_POST,$this->currentUser->id);
		break;
		
		case "CheckProductId":
		if(app('admin')->getwhere("pos_product","product_id",$_POST['product_id'])){
			respond(array(
			"status" => "success",
			"message" => trans('this_product_already_exits')
			));
		}
		break;
		
		case "AddNewCategorySubmit":
		app('pos')->AddNewCategorySubmit($_POST,$this->currentUser->id);
		break;
		
		case "AddNewUnitSubmit":
		app('pos')->AddNewUnitSubmit($_POST,$this->currentUser->id);
		break;
		
		case "AddProductData":
		if(isset($_FILES['product_image']) && $_FILES['product_image']['size'] != 0){
			$_POST['product_image_name'] = $_POST['product_name'].'.jpg';
			$upload_handler = new UploadHandler(array(
			'filename' => $_POST['product_name'],
			'max_file_size' => 10000000, //1MB file size
			'accept_file_types' => '/\.(jpe?g)$/i',
			'width' => 250, 
			'height' => 250, 
			'print_response' => false,
			'param_name' => 'product_image',
			'image_file_types' => '/\.(gif|jpe?g|png|psd|tiff|eps|ai|pdf|bmp)$/i',
			'upload_dir' => dirname(__FILE__).'/../images/stores/'.$_SERVER['SERVER_NAME'].'/',
			));
			$error_massage = json_decode(json_encode($upload_handler));
			if(!empty($error_massage->response->product_image[0]->error)){
				respond(array(
				'status' => 'error',
				'errors' => array(
				'product_image' => $error_massage->response->product_image[0]->error
				)
				), 422);
			}
		}
		
		app('pos')->InsertAndUpdateProduct($_POST,$this->currentUser->id,$this->currentUser->store_id);
		break;
		
		case 'GetBarcodePrint':
		$barcodeobj = new TCPDFBarcode($_POST['product_id'], 'C128');
		$variation_info = app('admin')->getwhereid('pos_variations','sub_product_id',$_POST['product_id']);
		$product_info = app('admin')->getwhereid('pos_product','product_id',$variation_info['product_id']);
	?>
	<div id="barcode-print">
		<table style="width:100%;border-collapse: collapse;">
			<?php
				$getpossetting = app('pos')->GetPosSetting();
				$count = 0;
				for ($i=0; $i <$_POST['number_of_barcode'] ; $i++) {
					if($count%5 == 0) echo "<tr>";
				?>
				<td style="border: 1px solid #ccc;text-align: center;font-size:10px;">
					<b><?php echo $getpossetting['company_name']; ?></b><br/>
					<?php echo $barcodeobj->getBarcodeSVGCODE(1, 25, 'black', 'center'); ?><br/>
					<?php echo $_POST['product_id']; ?><br/>
					<b><?php echo $_POST['product_name']; ?></b><br/>
					<b><?php echo "Price : ".$_POST['product_price']; 
						if($product_info['product_vat']== 0 || $product_info['product_vat']==null){
							echo " (Included VAT)";
						}
						if($product_info['product_vat']!= 0 || $product_info['product_vat']!=null){
							echo ' + '.$product_info['product_vat'];
							if($product_info['product_vat_type'] == 'percent'){
								echo '% VAT';
							}
							else{
								echo 'TK VAT';
							}
							// echo $vat;
						}
					?></b><br/>
					<b></b><br/>
					
				</td>
			<?php $count++; if($count%5 == 0) echo "</tr>"; } ?>
		</table>
	</div>
	<?php
		break;
		
		case "MultiProductBarcodePrint":
	?>
	<div id="barcode-print">
		<table style="width:100%;border-collapse: collapse;">
			<?php
				$getpossetting = app('pos')->GetPosSetting();
				$count = 0;
				for ($i=0; $i < count($_POST['product_id']); $i++) {
					$_POST['product_id_barcode'][$i] = new TCPDFBarcode($_POST['product_id'][$i], 'C128');
					
					for ($x=0; $x < $_POST['product_quantity'][$i]; $x++) {
						if($count%5 == 0) echo "<tr>";
					?>
					<td style="border: 1px solid #ccc;text-align: center;font-size:10px;">
						<b><?php echo $getpossetting['company_name']; ?></b><br/>
						<?php echo $_POST['product_id_barcode'][$i]->getBarcodeSVGCODE(1, 25, 'black', 'center'); ?><br/>
						<?php echo $_POST['product_id'][$i]; ?><br/>
						<b><?php echo $_POST['product_name'][$i]; ?></b><br/>
						<b><?php echo "Price : ".$_POST['product_price'][$i]; 
							
							if($_POST['product_vat'][$i]== 0 || $_POST['product_vat'][$i]==null){
								echo " (Included VAT)";
							}
							if($_POST['product_vat'][$i]!= 0 || $_POST['product_vat'][$i]!=null){
								echo ' + '.$_POST['product_vat'][$i];
								if($_POST['product_vat_type'][$i] == 'percent'){
									echo '% VAT';
								}
								else{
									echo 'TK VAT';
								}
							}
						?></b><br/>
						<b></b><br/>
						
					</td>
					<?php 
						$count++; 
						if($count%5 == 0) echo "</tr>"; 
					} 
				}
			?>
		</table>
	</div>
	<?php
		return;
		
		
		$variation_info = app('admin')->getwhereid('pos_variations','sub_product_id',$_POST['product_id']);
		$product_info = app('admin')->getwhereid('pos_product','product_id',$variation_info['product_id']);
	?>
	<div id="barcode-print">
		<table style="width:100%;border-collapse: collapse;">
			<?php
				$getpossetting = app('pos')->GetPosSetting();
				$count = 0;
				for ($i=0; $i <$_POST['number_of_barcode'] ; $i++) {
					if($count%5 == 0) echo "<tr>";
				?>
				<td style="border: 1px solid #ccc;text-align: center;font-size:10px;">
					<b><?php echo $getpossetting['company_name']; ?></b><br/>
					<?php echo $barcodeobj->getBarcodeSVGCODE(1, 25, 'black', 'center'); ?><br/>
					<?php echo $_POST['product_id']; ?><br/>
					<b><?php echo $_POST['product_name']; ?></b><br/>
					<b><?php echo "Price : ".$_POST['product_price']; 
						if($product_info['product_vat']== 0 || $product_info['product_vat']==null){
							echo " (Included VAT)";
						}
						if($product_info['product_vat']!= 0 || $product_info['product_vat']!=null){
							echo ' + '.$product_info['product_vat'];
							if($product_info['product_vat_type'] == 'percent'){
								echo '% VAT';
							}
							else{
								echo 'TK VAT';
							}
						}
					?></b><br/>
					<b></b><br/>
					
				</td>
				<?php 
					$count++; 
				if($count%5 == 0) echo "</tr>"; } 
			?>
		</table>
	</div>
	<?php
		
		break;
		case "DeleteContact":
		app('pos')->contact_delete($_POST);
		break;
		
		case "DeleteSale":
		app('pos')->DeleteSale($_POST);
		break;
		
		case "DeleteBrand":
		app('pos')->DeleteBrand($_POST);
		break;
		
		case "DeleteUnit":
		app('pos')->DeleteUnit($_POST);
		break;
		
		case "VatPaidSubmit":
		app('pos')->VatPaidSubmit($_POST,$this->currentUser->id);
		break;
		
		case "DeleteCategory":
		app('pos')->DeleteCategory($_POST);
		break;
		
		case "GetContactData":
		app('pos')->contact_submit($_POST,$this->currentUser->id,$this->currentUser->store_id);
		break;
		case "GetAccountUserData":
		app('pos')->GetAccountUserData($_POST,$this->currentUser->id,$this->currentUser->store_id);
		break;
		case "DeleteAccountUser":
		app('pos')->DeleteAccountUser($_POST);
		break;
		
		case 'GetCustomerReceiptViews':
		$numberToWords = new NumberToWords\NumberToWords();
		$numberTransformer = $numberToWords->getNumberTransformer('en');
		$getpossetting = app('pos')->GetPosSetting();
		$getSalesInfo = app('admin')->getwhereid('pos_sales','sales_id',$_POST['sales_id']);
		$getcustomerid = app('admin')->getwhereid('pos_contact','contact_id',$getSalesInfo['customer_id']);
		$getsalesby = app('admin')->getuserdetails($getSalesInfo['user_id']);
		$getsalesproducts = app('admin')->getwhereand('pos_stock','sales_id',$_POST['sales_id'],'stock_status','active');
		$getTransactionDetails=app('admin')->getwhere('pos_transactions','sales_id',$getSalesInfo['sales_id']);
		$getlastorderid = app('admin')->getwhereid('pos_sales','sales_id',$_POST['sales_id']);
		
	?>
	<div id="pos-print" >
		<html>
			<head>
				<meta charset="utf-8">
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<base href="<?php echo WEBSITE_DOMAIN; ?>/" >
				<link href="assets/css/pos-print.css?p=<?php echo time(); ?>" rel="stylesheet">
				<title>Point of Sale</title>
				
			</head>
			<body id="pos-print">
				<center class="pos-logo">
					<?php if(!empty($getpossetting['company_logo'])){ ?>
						
						<?php }else{ ?>
						<p><?php echo $getpossetting['company_name']; ?></p>
					<?php } ?> 
				</center>
				
				<div class="pos-header">
					<p> 
						Address : <?php echo $getpossetting['address']; ?><br>
						Email   : <?php echo $getpossetting['email']; ?><br>
						Phone   : <?php echo $getpossetting['phone']; ?><br>
						
						<?php if(!empty($getpossetting['nbr_no'])){ ?>Vat Reg : <?php echo $getpossetting['nbr_no']; ?>123456 &nbsp;&nbsp; Mushak : <?php echo $getpossetting['nbr_unit']; ?><?php } ?>
					</p>
					
					<div class="pos-header-info">
						<div class="float-left">
							<p>Voucher:  <?php echo $getSalesInfo['sales_id']; ?><br>Sale To: <?php if(empty($getSalesInfo['contact_id'])){ echo "Walk In Customer"; }else{ if(empty($getcustomerid['name'])){ echo $getcustomerid['phone']; }else{ echo $getcustomerid['name']; }} ?>
							</p>
						</div>
						<div class="float-right">
							<p>
								Date: <?php echo date('d-m-Y',strtotime($getSalesInfo['created_at'])); ?> <br>
								Sale By: <?php echo $getsalesby['first_name'].' '.$getsalesby['last_name']; ?>
							</p>
						</div>
					</div>
				</div>
				<table id="pos-product-table">
					<thead>
						<tr>
							<th>Item</th>
							<th>Qty</th>
							<th>Price</th>
							<th>Sub Total</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($getsalesproducts as $getsalesproduct){ 
							$getproductdetails = app('admin')->getwhereid('pos_product','product_id',$getsalesproduct['product_id']);
							$GetSerial = app('admin')->getwhereand('pos_product_serial','product_id',$getsalesproduct['product_id'],'sales_id',$_POST['sales_id']);
						?>
						<tr class="item-row">
							<td class="item-name">
								<?php
									
									echo $getproductdetails['product_name']; 
									if(count($GetSerial)!=0){
										echo '</br>[ S/N: ';													
										foreach($GetSerial as $serial){
											echo $serial['product_serial_no'].',';
										}
										echo ' ]';
									}
									
								?>
							</td>
							<td><?php echo $getsalesproduct['product_quantity']; ?></td>
							<td><?php echo $getsalesproduct['product_price']; ?></td>
							<td><?php echo $getsalesproduct['product_subtotal']; ?></td>
						</tr>
						<?php } ?>
						<tr>
							<td colspan="3" class="total-line">Sub Total =</td>
							<td class="total-value"><?php echo $getSalesInfo['sales_subtotal']; ?></td>
						</tr>
						<tr>
							<td colspan="3" class="total-line"><?php echo $getpossetting['vat_type']=='single' ? 'Total VAT' : 'VAT('.$getpossetting['vat'].'%)'; ?> =</td>
							<td class="total-value"><?php echo $getSalesInfo['sales_vat']; ?></td>
						</tr>
						<?php if($getSalesInfo['sales_discount'] != 0){ ?>
							<tr>
								<td colspan="3" class="total-line">Discount =</td>
								<td class="total-value"><?php echo $getSalesInfo['sales_discount']; ?></td>
							</tr>
						<?php } ?>
						
						<tr>
							<td colspan="3" class="total-line">Grand Total =</td>
							<td class="total-value"><?php echo $getSalesInfo['sales_total']; ?></td>
						</tr>
						<?php
							if($getSalesInfo['sales_status']!='quote'){
								$total_paid=0;
								$total_due=0;
								$total_paid=$getSalesInfo['sales_pay_cash'];
								$total_due=$getSalesInfo['sales_total']-$getSalesInfo['sales_pay_cash'];
								if($total_due<0){
									$total_due=0;
								}
								
							?>
							<tr>
								<td colspan="3" class="total-line">Total Paid =</td>
								<td class="total-value"><?php echo $total_paid==0 ? 'N/A' : $total_paid; ?></td>
							</tr>
							<tr style="border-bottom:1px dashed;">
								<td colspan="3" class="total-line">Total Due =</td>
								<td class="total-value"><?php echo $total_due==0 ? 'N/A' : $total_due; ?></td>
							</tr>
							
							<?php if($getSalesInfo['sales_total']!=$total_due){?>
								<tr class="item-row" >
									<td colspan='4' class="total-text-value" style="text-align:left;"><?php echo ucwords($numberTransformer->toWords(ceil($getSalesInfo['sales_total'])));  ?>*</td>
								</tr>
								
								<tr class="item-row">
									<td colspan='4'><strong>Payment Type</strong></td>
								</tr>
								<?php foreach($getTransactionDetails as $transaction){?>
									<tr>
										<td colspan='2' class="total-line"><?php echo $transaction['payment_method_value']; ?></td>
										<td colspan='2' class='total-value'><?php echo $transaction['transaction_amount']; ?></td>
									</tr>
									<?php if($transaction['payment_method_value']!='cash'){ ?>
										<tr>
											<td colspan='2' class="total-line">Transaction Id</td>
											<td colspan='2' class='total-value' ><?php echo $transaction['transaction_no'];?></td>
										</tr>
										
									<?php } } ?>
									<tr style="border-top: 1px dashed;">
										<td colspan="3" class="total-line">Received Amount</td>
										<td class="total-value"><?php echo $getSalesInfo['sales_pay_cash'];?></td>
									</tr>
									
									<tr class="total-line">
										<td colspan="3" class="total-line">Change Amount</td>
										<td class="total-value"><?php echo $getSalesInfo['sales_pay_change'];?></td>
									</tr>
							<?php }} else{?>
							<tr>
								<th colspan="4" class="text-center">Your Sales Added as Quotation</th>
							</tr>
						<?php } ?>
					</tbody>			
				</table>
				<div class="legalcopy">
					<?php if(!empty($getpossetting['receipt_footer'])){ ?>
						<p><?php echo $getpossetting['receipt_footer']; ?>
						</p>
					<?php } ?>
					<p><?php $barcodeobj = new TCPDFBarcode($getSalesInfo['sales_id'], 'C128'); echo $barcodeobj->getBarcodeSVGcode(1.5, 30, 'black'); ?></p>
					<p><strong>Powered by <?php echo POWERED_BY; ?></strong> 
					</p>
				</div>
			</body>
		</html>
	</div>
	<?php
		
		break;	
		
		case "GetContactData":
		app('pos')->contact_submit($_POST,$this->currentUser->id,$this->currentUser->store_id);
		break;
		
		case "PosSettingSubmit":
		app('pos')->PosSettingSubmit($_POST);
		break;
		
		case "DeletePurchase":
		app('pos')->DeletePurchase($_POST);
		break;
		
		case "GetPosAdvanceData":
		app('pos')->GetPosAdvanceData($_POST,$this->currentUser->id,$this->currentUser->store_id);
		break;
		
		case 'GetReceiptViews':
		$numberToWords = new NumberToWords\NumberToWords();
		$numberTransformer = $numberToWords->getNumberTransformer('en');
		
		if(isset($this->route['id'])){
			$getlastorderid = app('admin')->getwhereid('pos_sales','sales_id',$this->route['id']);
			}else{
			$getlastorderid = app('pos')->GetLastOrderReceipt();
		}
		$getpossetting = app('pos')->GetPosSetting();
		$getcustomerid = app('admin')->getwhereid('pos_contact','contact_id',$getlastorderid['customer_id']);
		$getsalesby = app('admin')->getuserdetails($getlastorderid['user_id']);
		$getSalesInfo = app('admin')->getwhereid('pos_sales','sales_id',$getlastorderid['sales_id']);
		$getsalesproducts = app('admin')->getwhere('pos_stock','sales_id',$getlastorderid['sales_id']);
		$getTransactionDetails=app('admin')->getwhere('pos_transactions','sales_id',$getlastorderid['sales_id']);
		$getpaymentInfo=app('pos')->GetSalesByCustomerOrder(false,$getlastorderid['sales_id']);
	?>
	<div id="pos-print">
		<html>
			<head>
				<meta charset="utf-8">
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<base href="<?php echo WEBSITE_DOMAIN; ?>/" >
				<link href="assets/css/pos-print.css?p=<?php echo time(); ?>" rel="stylesheet">
				<title>Point of Sale</title>
				
			</head>
			<body id="pos-print">
				<center class="pos-logo">
					<?php if(!empty($getpossetting['company_logo'])){ ?>
						
						<?php }else{ ?>
						<p><?php echo $getpossetting['company_name']; ?></p>
					<?php } ?> 
				</center><!--End InvoiceTop-->
				
				<div class="pos-header">
					<p> 
						Address : <?php echo $getpossetting['address']; ?><br>
						Email   : <?php echo $getpossetting['email']; ?><br>
						Phone   : <?php echo $getpossetting['phone']; ?><br>
						
						<?php if(!empty($getpossetting['nbr_no'])){ ?>Vat Reg : <?php echo $getpossetting['nbr_no']; ?>123456 &nbsp;&nbsp; Mushak : <?php echo $getpossetting['nbr_unit']; ?><?php } ?>
					</p>
					
					<div class="pos-header-info">
						<div class="float-left">
							<p>Voucher:  <?php echo $getlastorderid['sales_id']; ?><br>Sale To: <?php if(empty($getlastorderid['contact_id'])){ echo "Walk In Customer"; }else{ if(empty($getcustomerid['name'])){ echo $getcustomerid['phone']; }else{ echo $getcustomerid['name']; }} ?>
							</p>
						</div>
						<div class="float-right">
							<p>
								Date: <?php echo date('d-m-Y',strtotime($getlastorderid['created_at'])); ?> <br>
								Sale By: <?php echo $getsalesby['first_name'].' '.$getsalesby['last_name']; ?>
							</p>
						</div>
					</div>
				</div>
				<table id="pos-product-table">
					<thead>
						<tr>
							<th>Item</th>
							<th>Qty</th>
							<th>Price</th>
							<th>Sub Total</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($getsalesproducts as $getsalesproduct){ 
							$getproductdetails = app('admin')->getwhereid('pos_product','product_id',$getsalesproduct['product_id']);
							$GetSerial = app('admin')->getwhereand('pos_product_serial','product_id',$getsalesproduct['product_id'],'sales_id',$getlastorderid['sales_id']);
						?>
						<tr class="item-row">
							<td class="item-name">
								<?php
									
									echo $getproductdetails['product_name']; 
									if(count($GetSerial)!=0){
										echo '</br>[  S/N:';
										$count=1;	
										foreach($GetSerial as $serial){
											echo $serial['product_serial_no'].',';
											if($count%2==0){
												echo "</br>";
											}
											$count++;
										}
										echo ' ]';
									}
								?>
							</td>
							<td><?php echo $getsalesproduct['product_quantity']; ?></td>
							<td><?php echo $getsalesproduct['product_price']; ?></td>
							<td><?php echo $getsalesproduct['product_subtotal']; ?></td>
						</tr>
						<?php } ?>
						<tr>
							<td colspan="3" class="total-line">Sub Total =</td>
							<td class="total-value"><?php echo $getlastorderid['sales_subtotal']; ?></td>
						</tr>
						<tr>
							<td colspan="3" class="total-line"><?php echo $getpossetting['vat_type']=='single' ? 'Total VAT' : 'VAT('.$getpossetting['vat'].'%)'; ?> =</td>
							<td class="total-value"><?php echo $getlastorderid['sales_vat']; ?></td>
						</tr>
						<?php if($getlastorderid['sales_discount'] != 0){ ?>
							<tr>
								<td colspan="3" class="total-line">Discount =</td>
								<td class="total-value"><?php echo $getlastorderid['sales_discount']; ?></td>
							</tr>
						<?php } ?>
						
						<tr>
							<td colspan="3" class="total-line">Grand Total =</td>
							<td class="total-value"><?php echo $getlastorderid['sales_total']; ?></td>
						</tr>
						<?php
							if($getSalesInfo['sales_status']!='quote'){
								$total_paid=0;
								$total_due=0;
								$total_paid=$getSalesInfo['sales_pay_cash'];
								$total_due=$getSalesInfo['sales_total']-$getSalesInfo['sales_pay_cash'];
								if($total_due<0){
									$total_due=0;
									$total_paid = $getSalesInfo['sales_total'];
								}
								
							?>
							<tr>
								<td colspan="3" class="total-line">Total Paid =</td>
								<td class="total-value"><?php echo $total_paid==0 ? 'N/A' : $total_paid; ?></td>
							</tr>
							<tr>
								<td colspan="3" class="total-line">Total Due =</td>
								<td class="total-value" ><?php echo $total_due==0 ? 'N/A' : $total_due; ?></td>
							</tr>
							
							<?php if($getSalesInfo['sales_total']!=$total_due){?>
								<tr class="item-row" >
									<td colspan='4' class="total-text-value" style="text-align:left;"><?php echo ucwords($numberTransformer->toWords(ceil($getSalesInfo['sales_total'])));  ?>*</td>
								</tr>
								
								<tr class="item-row">
									<td colspan='4'><strong>Payment Type</strong></td>
								</tr>
								<?php foreach($getTransactionDetails as $transaction){?>
									<tr>
										<td colspan='2' class="total-line"><?php echo $transaction['payment_method_value']; ?></td>
										<td colspan='2' class='total-value'><?php echo $transaction['transaction_amount']; ?></td>
									</tr>
									<?php if($transaction['payment_method_value']!='cash'){ ?>
										<tr>
											<td colspan='2' class="total-line">Transaction Id</td>
											<td colspan='2' class='total-value' ><?php echo $transaction['transaction_no'];?></td>
										</tr>
										
									<?php } } ?>
									<tr style="border-top: 1px dashed;">
										<td colspan="2" class="total-line">Received Amount</td>
										<td colspan="2" class="total-value"><?php echo $getSalesInfo['sales_pay_cash'];?></td>
									</tr>
									
									<tr class="total-line">
										<td colspan="2" class="total-line">Change Amount</td>
										<td colspan="2" class="total-value"><?php echo $getSalesInfo['sales_pay_change'];?></td>
									</tr>
							<?php }} else{?>
							<tr>
								<th colspan="4" class="text-center">Your Sales Added as Quotation</th>
							</tr>
						<?php } ?>
					</tbody>			
				</table>
				<div class="legalcopy">
					<?php if(!empty($getpossetting['receipt_footer'])){ ?>
						<p><?php echo $getpossetting['receipt_footer']; ?>
						</p>
					<?php } ?>
					<p><?php $barcodeobj = new TCPDFBarcode($getlastorderid['sales_id'], 'C128'); echo $barcodeobj->getBarcodeSVGcode(1.5, 30, 'black'); ?></p>
					<p><strong>Powered by <?php echo POWERED_BY; ?></strong> 
					</p>
				</div>
			</body>
		</html>
	</div>
	<?php
		
		break;
		
		case "GetProductListFilter":
		app('pos')->GetProductListFilter($_POST);
		break;
		
		case "GetSalesProductList":
		if(app('admin')->checkAddon('serial_product')){ 
			if($_POST['serial_no_check'] == 'no_serial'){
				$getProductVariation = app('db')->table('pos_variations')->where('sub_product_id',$_POST['product_code'])->where('is_delete','false')->get(1);
				$getSerial = app('db')->table('pos_product_serial')->where('product_serial_no',$_POST['product_code'])->where('product_serial_status',"received")->where('is_delete','false')->get();
				$getProductSerial = count($getSerial) > 0 ? $getSerial[0] : null;
				}else{
				$getProductVariation = null;
				$getSerial = null;
				$getProductSerial = app('db')->table('pos_product_serial')->where('product_serial_no',$_POST['serial_no_check'])->where('sub_product_id',$_POST['product_code'])->where('is_delete','false')->get(1);
			}
			
			if($getProductVariation){
				$getProduct = app('db')->table('pos_product')->where('product_id',$getProductVariation['product_id'])->where('is_delete','false')->get(1);
				$getUnit = app('db')->table('pos_unit')->where('unit_id',$getProduct['unit_id'])->where('is_delete','false')->get(1);
				$GetProductAvaliableStock = app('pos')->GetProductAvaliableStock($this->currentUser->store_id,$getProductVariation['sub_product_id']);
				respond(array(
				"status" => "success",
				"product_status" => "found",
				"product_name" => $getProduct['product_name'],
				"product_type" => $getProduct['product_type'],
				"product_search_type" => "product_code",
				"product_vat" => $getProduct['product_vat'],
				"product_vat_type" => $getProduct['product_vat_type'],
				"unit_name" => $getUnit['unit_name'],
				"product_serial" => $getProduct['product_serial'],
				"product_stock" => $getProduct['product_stock'],
				"alert_quantity" => $getProduct['alert_quantity'],
				"variation_name" => $getProductVariation['variation_name'],
				"product_id" => $getProductVariation['product_id'],
				"sub_product_id" => $getProductVariation['sub_product_id'],
				"purchase_price" => $getProductVariation['purchase_price'],
				"profit_percent" => $getProductVariation['profit_percent'],
				"sell_price" => $getProductVariation['sell_price'],
				"variation_id" => $getProductVariation['variation_id'],
				"product_current_stock" => $GetProductAvaliableStock['product_stock']
				));
				}elseif($getProductSerial){
				if($getSerial != null && count($getSerial) > 1){
					respond(array(
					"status" => "success",
					"product_status" => "multi_product_serial"
					));
					}else{
					$getProductVariation = app('db')->table('pos_variations')->where('sub_product_id',$getProductSerial['sub_product_id'])->where('is_delete','false')->get(1);
					$getProduct = app('db')->table('pos_product')->where('product_id',$getProductVariation['product_id'])->where('is_delete','false')->get(1);
					$getUnit = app('db')->table('pos_unit')->where('unit_id',$getProduct['unit_id'])->where('is_delete','false')->get(1);
					$GetProductAvaliableStock = app('pos')->GetProductAvaliableStock($this->currentUser->store_id,$getProductVariation['sub_product_id']);
					respond(array(
					"status" => "success",
					"product_status" => "found",
					"product_name" => $getProduct['product_name'],
					"product_type" => $getProduct['product_type'],
					"product_search_type" => "product_serial",
					"product_vat" => $getProduct['product_vat'],
					"product_vat_type" => $getProduct['product_vat_type'],
					"unit_name" => $getUnit['unit_name'],
					"product_serial" => $getProduct['product_serial'],
					"product_serial_no" => $getProductSerial['product_serial_no'],
					"product_stock" => $getProduct['product_stock'],
					"alert_quantity" => $getProduct['alert_quantity'],
					"sub_product_id" => $getProductVariation['sub_product_id'],
					"variation_name" => $getProductVariation['variation_name'],
					"product_id" => $getProductVariation['product_id'],
					"purchase_price" => $getProductVariation['purchase_price'],
					"profit_percent" => $getProductVariation['profit_percent'],
					"sell_price" => $getProductVariation['sell_price'],
					"variation_id" => $getProductVariation['variation_id'],
					"product_current_stock" => $GetProductAvaliableStock['product_stock']
					));
				}
				}else{
				respond(array(
				"status" => "success",
				"product_status" => "not_found"
				));
			}
			}else{
			$getProductVariation = app('db')->table('pos_variations')->where('sub_product_id',$_POST['product_code'])->where('is_delete','false')->get(1);
			if($getProductVariation){
				$getProduct = app('db')->table('pos_product')->where('product_id',$getProductVariation['product_id'])->where('is_delete','false')->get(1);
				$getUnit = app('db')->table('pos_unit')->where('unit_id',$getProduct['unit_id'])->where('is_delete','false')->get(1);
				$GetProductAvaliableStock = app('pos')->GetProductAvaliableStock($this->currentUser->store_id,$getProductVariation['sub_product_id']);
				respond(array(
				"status" => "success",
				"product_status" => "found",
				"product_name" => $getProduct['product_name'],
				"product_type" => $getProduct['product_type'],
				"product_search_type" => "product_code",
				"product_vat" => $getProduct['product_vat'],
				"product_vat_type" => $getProduct['product_vat_type'],
				"unit_name" => $getUnit['unit_name'],
				"product_serial" => $getProduct['product_serial'],
				"product_stock" => $getProduct['product_stock'],
				"alert_quantity" => $getProduct['alert_quantity'],
				"variation_name" => $getProductVariation['variation_name'],
				"product_id" => $getProductVariation['product_id'],
				"sub_product_id" => $getProductVariation['sub_product_id'],
				"purchase_price" => $getProductVariation['purchase_price'],
				"profit_percent" => $getProductVariation['profit_percent'],
				"sell_price" => $getProductVariation['sell_price'],
				"variation_id" => $getProductVariation['variation_id'],
				"product_current_stock" => $GetProductAvaliableStock['product_stock']
				));
				}else{
				respond(array(
				"status" => "success",
				"product_status" => "not_found"
				));
			}
		}
		break;
		
		case "GetPurchaseBarcodeTypeSearch":
		app('pos')->GetPurchaseBarcodeTypeSearch($_POST);
		break;
		
		case "AddVariationSubmit":		
		for($i=0;$i<sizeof($_POST['variation_value']);$i++){
			$_POST['variation_value'][$i] = strtoupper($_POST['variation_value'][$i]);
		}
		app('pos')->AddVariationSubmit($_POST,$this->currentUser->id,$this->currentUser->store_id);
		break;
		case "DeleteVariation":
		
		app('pos')->DeleteVariation($_POST);
		break;
		
		case 'GetAllSaleByDays':
		app('pos')->GetAllSaleByDays($_POST['total_days']);
		break;
		
		case "AccountUpdate":
		app('register')->AccountUpdate($_POST,$this->currentUser->id,$this->currentUser->store_id);
		break;
		
		case 'account_lock':
		ASSession::set("user_lock_status", $_POST['lock_status']);
		respond(array(
		"status" => "success"
		));
		break;
		
		case "GetSearchCustomerByNameId":
		app('pos')->GetSearchCustomerByNameId($_POST);
		break;
		
		case "GetSearchAccountUserByNameId":
		app('pos')->GetSearchAccountUserByNameId($_POST);
		break;
		
		case "deleteVariationProduct":
		app('pos')->deleteVariationProduct($_POST);
		break;
		
		case "ProductFeaturedChange":
		app('pos')->ProductFeaturedChange($_POST);
		break;
		
		case "GetProfitLossReport":
		
		$GetClosingStock = app('pos')->getStockAmount($_POST,false);
		
		
		respond([
		"purchase_total" => $GetClosingStock['total_sales_purchase_amount'],
		"purchase_return_total" => $GetClosingStock['total_purchase_return_total'],
		"total_purchase_discount" => $GetClosingStock['total_sales_purchase_amount'] * ($GetClosingStock['total_purchase_discount_percentage'] / 100),
		"total_purchase_shipping_charge" => $GetClosingStock['total_sales_purchase_amount'] * ($GetClosingStock['total_purchase_charge_percentage'] / 100),
		"sales_total" => $GetClosingStock['total_sales_amount'],
		"sales_return_total" => $GetClosingStock['total_sales_return_total'],
		"sales_discount" => $GetClosingStock['total_sales_discount'],
		"sales_shipping_charge" => $GetClosingStock['total_sales_shipping_charge'],
		"net_profit" => $GetClosingStock['net_profit_amount'],
		"sales_vat"=> $GetClosingStock['total_sales_vat'] - $GetClosingStock['total_sales_return_vat']
		]);
		
		break;
		
		case "GetPurchaseSalesReport":
		$GetPurchaseTotal = app('admin')->GetSum("pos_purchase",array("purchase_total","purchase_discount","purchase_shipping_charge","purchase_vat"),array(),array("created_at",$_POST['start_date'],$_POST['end_date'],true),array("purchase_payment_status" => "cancel"));
		$GetPurchaseTotalPaid = app('admin')->GetSum("pos_transactions",array("transaction_amount"),array("transaction_type" => "purchase","transaction_status" => "paid"),array("created_at",$_POST['start_date'],$_POST['end_date'],true));
		$GetPurchaseTotalReturn = app('admin')->GetSum("pos_return",array("return_total"),array("return_type" => "purchase","return_status" => "paid"),array("created_at",$_POST['start_date'],$_POST['end_date'],true));
		$GetPurchaseTotalReturnPaid = app('admin')->GetSum("pos_transactions",array("transaction_amount"),array("transaction_type" => "return","transaction_status" => "paid"),array("created_at",$_POST['start_date'],$_POST['end_date'],true));
		$CurrentPurchaseTotal = $GetPurchaseTotal["purchase_total"] - $GetPurchaseTotalReturn["return_total"];
		$CurrentPurchaseTotalPaid = $GetPurchaseTotalPaid["transaction_amount"] - $GetPurchaseTotalReturnPaid["transaction_amount"];
		
		
		$GetSalesTotal = app('admin')->GetSum("pos_sales",array("sales_total","sales_vat","sales_discount","shipping_charge"),array(),array("created_at",$_POST['start_date'],$_POST['end_date'],true),array("sales_status" => "cancel"));
		$GetSalesTotalPaid = app('admin')->GetSum("pos_transactions",array("transaction_amount"),array("transaction_type" => "sales","transaction_status" => "paid"),array("created_at",$_POST['start_date'],$_POST['end_date'],true));
		$GetSalesTotalReturn = app('admin')->GetSum("pos_return",array("return_total"),array("return_type" => "sales","return_status" => "paid"),array("created_at",$_POST['start_date'],$_POST['end_date'],true));
		$GetSalesTotalReturnPaid = app('admin')->GetSum("pos_transactions",array("transaction_amount"),array("transaction_type" => "return","transaction_status" => "paid"),array("created_at",$_POST['start_date'],$_POST['end_date'],true));		
		$CurrentSalesTotal = $GetSalesTotal["sales_total"] - $GetSalesTotalReturn["return_total"];
		$CurrentSalesTotalPaid = $GetSalesTotalPaid["transaction_amount"] - $GetSalesTotalReturnPaid["transaction_amount"];
		
		// $total_purchase_sales = $CurrentPurchaseTotal - $CurrentSalesTotal ;
		
		$netPurchase = $GetPurchaseTotal['purchase_total']+$GetPurchaseTotal['purchase_discount']-$GetPurchaseTotal['purchase_vat']-$GetPurchaseTotal['purchase_shipping_charge'];
		$netSale=$GetSalesTotal['sales_total'] + $GetSalesTotal['sales_discount'] - $GetSalesTotal['sales_vat'];
		
		$total_purchase_sales=$netPurchase - $netSale;
		
		respond(array(
		"status" => "success",
		"purchase_total" => $GetPurchaseTotal['purchase_total'],
		"purchase_discount" => $GetPurchaseTotal['purchase_discount'],
		"purchase_shipping_charge" => $GetPurchaseTotal['purchase_shipping_charge'],
		"purchase_paid_amount" => $GetPurchaseTotalPaid['transaction_amount'],
		"sales_total" => $GetSalesTotal['sales_total'],
		"sales_vat" => $GetSalesTotal['sales_vat'],
		"purchase_vat" => $GetPurchaseTotal['purchase_vat'],
		"sales_discount" => $GetSalesTotal['sales_discount'],
		"shipping_charge" => $GetSalesTotal['shipping_charge'],
		"sales_paid_amount" => $GetSalesTotalPaid['transaction_amount'],
		
		"total_purchase_sales" => $total_purchase_sales,
		"netPurchase" => $netPurchase,
		"netsales" => $netSale,
		));
		
		break;
		case "ProductDelete":
		app('pos')->ProductDelete($_POST);
		break;
		
		case "ManageUserSubmit":
		app('register')->ManageUserSubmit($_POST,$this->currentUser->id);
		break;
		
		case "AddNewStoreSumbit":
		app('pos')->AddNewStoreSumbit($_POST,$this->currentUser->id);
		break;
		
		case "ChangeStatus":
		app('pos')->ChangeStoreStatus($_POST);
		break;
		
		case "AddNewWarehouseSumbit":
		app('pos')->AddNewWarehouseSumbit($_POST,$this->currentUser->id);
		break;
		
		case "ChangeWarehouseStatus":
		app('pos')->ChangeWarehouseStatus($_POST);
		break;
		
		case "DeleteWarehouse":
		app('pos')->DeleteWarehouse($_POST);
		break;
		
		case "InvoiceSettingSubmit":
		$company_info = app('pos')->GetPosSetting();
		if(isset($_FILES['invoice_header']) && $_FILES['invoice_header']['size'] != 0){
			$_POST['invoice_header'] = $company_info['company_name'].'_invoice_header.jpg';
			$filename = $company_info['company_name'].'_invoice_header';
			$upload_handler = new UploadHandler(array(
			'filename' => $filename,
			'max_file_size' => 1000000, //1MB file size
			'accept_file_types' => '/\.(jpe?g)$/i',
			'width' => 595, 
			'height' => 250, 
			'print_response' => false,
			'param_name' => 'invoice_header',
			'image_file_types' => '/\.(gif|jpe?g|png|psd|tiff|eps|ai|pdf|bmp)$/i',
			'upload_dir' => dirname(__FILE__).'/../images/stores/'.$_SERVER['SERVER_NAME'].'/',
			));
			$error_massage = json_decode(json_encode($upload_handler));
			if(!empty($error_massage->response->invoice_header[0]->error)){
				respond(array(
				'status' => 'error',
				'errors' => array(
				'invoice_header' => $error_massage->response->invoice_header[0]->error
				)
				), 422);
			}
		}
		if(isset($_FILES['invoice_footer']) && $_FILES['invoice_footer']['size'] != 0){
			$_POST['invoice_footer'] = $company_info['company_name'].'_invoice_footer.jpg';
			$filename = $company_info['company_name'].'_invoice_footer';
			$upload_handler = new UploadHandler(array(
			'filename' => $filename,
			'max_file_size' => 1000000, //1MB file size
			'accept_file_types' => '/\.(jpe?g)$/i',
			'width' => 595, 
			'height' => 250, 
			'print_response' => false,
			'param_name' => 'invoice_footer',
			'image_file_types' => '/\.(gif|jpe?g|png|psd|tiff|eps|ai|pdf|bmp)$/i',
			'upload_dir' => dirname(__FILE__).'/../images/stores/'.$_SERVER['SERVER_NAME'].'/',
			));
			$error_massage = json_decode(json_encode($upload_handler));
			if(!empty($error_massage->response->invoice_footer[0]->error)){
				respond(array(
				'status' => 'error',
				'errors' => array(
				'invoice_footer' => $error_massage->response->invoice_footer[0]->error
				)
				), 422);
			}
		}
		if(isset($_FILES['invoice_logo']) && $_FILES['invoice_logo']['size'] != 0){
			$_POST['invoice_logo'] = $company_info['company_name'].'_invoice_logo.jpg';
			$filename = $company_info['company_name'].'_invoice_logo';
			$upload_handler = new UploadHandler(array(
			'filename' => $filename,
			'max_file_size' => 1000000, //1MB file size
			'accept_file_types' => '/\.(jpe?g|png)$/i',
			'width' => 100, 
			'height' => 100, 
			'print_response' => false,
			'param_name' => 'invoice_logo',
			'image_file_types' => '/\.(gif|jpe?g|png|psd|tiff|eps|ai|pdf|bmp)$/i',
			'upload_dir' => dirname(__FILE__).'/../images/stores/'.$_SERVER['SERVER_NAME'].'/',
			));
			$error_massage = json_decode(json_encode($upload_handler));
			if(!empty($error_massage->response->invoice_logo[0]->error)){
				respond(array(
				'status' => 'error',
				'errors' => array(
				'invoice_logo' => $error_massage->response->invoice_logo[0]->error
				)
				), 422);
			}
		}
		app('pos')->InvoiceSettingSubmit($_POST);
		break;
		
		case "AccountChartSubmit":
		app('pos')->AccountChartSubmit($_POST,$this->currentUser->id,$this->currentUser->store_id);
		break;
		
		case "AddAccountData":
		if(isset($_FILES['attach_document']) && $_FILES['attach_document']['size'] != 0){
			
			if(isset($_POST['attached_document'])) {
				$path = dirname(__FILE__).'/../images/stores/'.$_SERVER['SERVER_NAME'].'/document/'.$_POST['attached_document'];
				if (file_exists($path)) {
					unlink($path);
				}
			}
			
			$extension = pathinfo($_FILES['attach_document']['name'], PATHINFO_EXTENSION);
			$date = new DateTime();
			$image_name =  $date->getTimestamp();
			$_POST['attach_document_name'] = $image_name.'.'.$extension;
			
			$upload_handler = new UploadHandler(array(
			'max_file_size' => 100000,
			'print_response' => false,
			'param_name' => 'attach_document',
			'filename' => $image_name,
			'image_file_types' => '/\.(gif|jpe?g|png|psd|tiff|eps|ai|pdf|bmp)$/i',
			'upload_dir' => dirname(__FILE__).'/../images/stores/'.$_SERVER['SERVER_NAME'].'/document/',
			));
			
			$error_massage = json_decode(json_encode($upload_handler));
			
			if(!empty($error_massage->response->attach_document[0]->error)){
				respond(array(
				'status' => 'error',
				'errors' => array(
				'attach_document' => $error_massage->response->attach_document[0]->error
				)
				), 422);
			}
		}
		app('pos')->AddAccountSubmit($_POST,$this->currentUser->id);
		break;
		
		case "CapitalCashData":
		
		if(isset($_FILES['attach_document']) && $_FILES['attach_document']['size'] != 0){
			if (isset($_POST['attached_document'])) {
				$path = dirname(__FILE__).'/../images/stores/'.$_SERVER['SERVER_NAME'].'/document/'.$_POST['attached_document'];
				if (file_exists($path)) {
					unlink($path);
				}
			}
			$extension = pathinfo($_FILES['attach_document']['name'], PATHINFO_EXTENSION);
			$date = new DateTime();
			$image_name =  $date->getTimestamp();
			$_POST['attach_document_name'] = $image_name.'.'.$extension;
			$upload_handler = new UploadHandler(array(
			'max_file_size' => 100000,
			'print_response' => false,
			'param_name' => 'attach_document',
			'filename' => $image_name,
			'image_file_types' => '/\.(gif|jpe?g|png|psd|tiff|eps|ai|pdf|bmp)$/i',
			'upload_dir' => dirname(__FILE__).'/../images/stores/'.$_SERVER['SERVER_NAME'].'/document/',
			));
			$error_massage = json_decode(json_encode($upload_handler));
			if(!empty($error_massage->response->attach_document[0]->error)){
				respond(array(
				'status' => 'error',
				'errors' => array(
				'attach_document' => $error_massage->response->attach_document[0]->error
				)
				), 422);
			}
		}
		app('pos')->CapitalCashSubmit($_POST,$this->currentUser->id);
		break;
		case "CapitalBankData":
		if(isset($_FILES['attach_document']) && $_FILES['attach_document']['size'] != 0){
			if (isset($_POST['attached_document'])) {
				$path = dirname(__FILE__).'/../images/stores/'.$_SERVER['SERVER_NAME'].'/document/'.$_POST['attached_document'];
				if (file_exists($path)) {
					unlink($path);
				}
			}
			$extension = pathinfo($_FILES['attach_document']['name'], PATHINFO_EXTENSION);
			$date = new DateTime();
			$image_name =  $date->getTimestamp();
			$_POST['attach_document_name'] = $image_name.'.'.$extension;
			$upload_handler = new UploadHandler(array(
			'max_file_size' => 100000,
			'print_response' => false,
			'param_name' => 'attach_document',
			'filename' => $image_name,
			'image_file_types' => '/\.(gif|jpe?g|png|psd|tiff|eps|ai|pdf|bmp)$/i',
			'upload_dir' => dirname(__FILE__).'/../images/stores/'.$_SERVER['SERVER_NAME'].'/document/',
			));
		$error_massage = json_decode(json_encode($upload_handler));
		if(!empty($error_massage->response->attach_document[0]->error)){
		respond(array(
		'status' => 'error',
		'errors' => array(
		'attach_document' => $error_massage->response->attach_document[0]->error
		)
		), 422);
		}
		}
		app('pos')->CapitalBankSubmit($_POST,$this->currentUser->id);
		break;
		case "CapitalAssetsData":
		if(isset($_FILES['attach_document']) && $_FILES['attach_document']['size'] != 0){
		if (isset($_POST['attached_document'])) {
		$path = dirname(__FILE__).'/../images/stores/'.$_SERVER['SERVER_NAME'].'/document/'.$_POST['attached_document'];
		if (file_exists($path)) {
		unlink($path);
		}
		}
		$extension = pathinfo($_FILES['attach_document']['name'], PATHINFO_EXTENSION);
		$date = new DateTime();
		$image_name =  $date->getTimestamp();
		$_POST['attach_document_name'] = $image_name.'.'.$extension;
		$upload_handler = new UploadHandler(array(
		'max_file_size' => 100000,
		'print_response' => false,
		'param_name' => 'attach_document',
		'filename' => $image_name,
		'image_file_types' => '/\.(gif|jpe?g|png|psd|tiff|eps|ai|pdf|bmp)$/i',
		'upload_dir' => dirname(__FILE__).'/../images/stores/'.$_SERVER['SERVER_NAME'].'/document/',
		));
		$error_massage = json_decode(json_encode($upload_handler));
		if(!empty($error_massage->response->attach_document[0]->error)){
		respond(array(
		'status' => 'error',
		'errors' => array(
		'attach_document' => $error_massage->response->attach_document[0]->error
		)
		), 422);
		}
		}
		app('pos')->CapitalAssetsSubmit($_POST,$this->currentUser->id);
		break;
		
		case "AddPurchaseReturn":
		if(isset($_FILES['document']) && $_FILES['document']['size'] != 0){
		$_POST['document'] = $_POST['return_id'].'.jpg';
		$upload_handler = new UploadHandler(array(
		'filename' => $_POST['return_id'],
		'max_file_size' => 10000000, //1MB file size
		'accept_file_types' => '/\.(jpe?g)$/i',
		'width' => 250, 
		'height' => 250, 
		'print_response' => false,
		'param_name' => 'document',
		'image_file_types' => '/\.(gif|jpe?g|png|psd|tiff|eps|ai|pdf|bmp)$/i',
		'upload_dir' => dirname(__FILE__).'/../images/stores/'.$_SERVER['SERVER_NAME'].'/',
		));
		$error_massage = json_decode(json_encode($upload_handler));
		if(!empty($error_massage->response->product_image[0]->error)){
		respond(array(
		'status' => 'error',
		'errors' => array(
		'document' => $error_massage->response->document[0]->error
		)
		), 422);
		}
		}
		app('pos')->AddPurchaseReturn($_POST,$this->currentUser->id,$this->currentUser->store_id);
		break;
		
		case "AddNewPaymentMethodSubmit":
		app('pos')->AddNewPaymentMethodSubmit($_POST,$this->currentUser->id);
		break;
		
		case "AddAccountLeaseData":
		app('pos')->AddAccountLeaseData($_POST,$this->currentUser->id);
		break;
		
		case "AddAccountAssetData":
		app('pos')->AddAccountAssetData($_POST,$this->currentUser->id);
		break;
		
		
		case "GetInvoice":
		$getpossetting = app('pos')->GetPosSetting();
		?>
		<div id="sales_invoice">
		<html>
		<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<base href="<?php echo WEBSITE_DOMAIN; ?>/" >
		
		<title><?php echo $_POST['id'];?></title>
		<style type="text/css" media="print">
		@media print {
		@page { margin: 0; }
		body { margin: <?php echo $getpossetting['invoice_margin_top'];?>px <?php echo $getpossetting['invoice_margin_bottom'];?>px <?php echo $getpossetting['invoice_margin_left'];?>px <?php echo $getpossetting['invoice_margin_right'];?>px;height: 99%; }
		.col-print-1 {width:8%;  float:left;}
		.col-print-2 {width:16%; float:left;}
		.col-print-3 {width:25%; float:left;}
		.col-print-4 {width:33%; float:left;}
		.col-print-5 {width:42%; float:left;}
		.col-print-6 {width:50%; float:left;}
		.col-print-7 {width:58%; float:left;}
		.col-print-8 {width:66%; float:left;}
		.col-print-9 {width:75%; float:left;}
		.col-print-10{width:83%; float:left;}
		.col-print-11{width:92%; float:left;}
		.col-print-12{width:100%; float:left;}
		table { page-break-after:auto }
		tr    { page-break-inside:avoid; page-break-after:auto }
		td    { page-break-inside:avoid; page-break-after:auto }
		thead { display:table-header-group }
		tfoot { display:table-footer-group }
		.head_label{font-size:14px; line-height:20px; font-weight:bold; }
		.text-right{text-align:right;}
		.text-left{text-align:left;}
		}
		</style>
		</head>
		<body>
		<div class="wrapper wrapper-content animated fadeInRight">
		<?php
		$get_invoice_data = app('admin')->getwhereid('pos_sales','sales_id',$_POST['id']);
		$sales_status = $get_invoice_data['sales_status'];
		$sales_info=app('pos')->GetSalesByCustomerOrder(false,$_POST['id']);
		$getuserdetails = app('admin')->getuserdetails($get_invoice_data['user_id']);
		$customerdetails = app('admin')->getwhereid('pos_contact','contact_id',$get_invoice_data['customer_id']);
		
		if ($customerdetails) {
		if ($customerdetails['phone']!=null) {
		$customer_name = $customerdetails['phone'];
		}
		elseif ($customerdetails['name']!=null) {
		$customer_name = $customerdetails['name'];
		}
		}else{
		$customer_name = "Walk-in-customer";
		}
		$sale_product = app('admin')->getwhereand('pos_stock','sales_id',$_POST['id'],'stock_category','sales');
		
		if($sales_status!='quote'){?>
		<h1 class="text-center"><b>Invoice</b></h1>
		<?php }else{ ?>
		<h1 class="text-center">Quotation</h1>
		<?php } ?>
		<div class="ibox-content">
		<div class="row">
		<div class="col-print-6">							
		
		<span class="head_label">Customer Name: <?php echo $customer_name;?> <br></span>
		<span class="head_label">Address:<?php echo $customerdetails['address']!=null?$customerdetails['address']: 'N/A';?><br></span>
		<span class="head_label">Phone:<?php echo $customerdetails['phone']!=null?$customerdetails['phone']: 'N/A';?><br></span>
		
		</div>
		
		<div class="col-print-6 text-right">
		<p>
		<span class="head_label">Invoice Date :<?php echo $get_invoice_data['created_at'].'<br/>';?></span>
		</p>
		<span class="head_label">Invoice No:<?php echo $_POST['id'];?></span><br>
		
		</div>
		</div>
		
		
		<div class="table-responsive m-t">
		<table class="table invoice-table">
		<thead>
		<tr>
		<th class="text-center"><?php echo trans('no'); ?></th>
		<th class="text-center"><?php echo trans('name');?></th>
		<th class="text-center"><?php echo trans('quantity'); ?></th>
		<th class="text-center"><?php echo trans('unit_price'); ?></th>
		<?php if($sales_status!='quote'){?>
		<th class="text-center"><?php echo trans('vat'); ?></th>
		<th class="text-center"><?php echo trans('total_price'); ?></th>
		<?php } ?>
		</tr>
		</thead>
		<tbody class="text-center">
		<?php
		$subtotal=0;
		foreach ( (array) @$sale_product as $value) {
		$sale_product_details = app('admin')->getwhereid('pos_product','product_id',$value['product_id']);
		$GetSerial = app('admin')->getwhereand('pos_product_serial','product_id',$value['product_id'],'sales_id',$get_invoice_data['sales_id']);
		?>
		<tr>
		<td><?php echo $value['product_id'];?></td>
		<td><strong><?php
		echo $sale_product_details['product_name']; 
		if(count($GetSerial)!=0){
		echo '</br>[  S/N:';
		$count=1;
		foreach($GetSerial as $serial){
		echo $serial['product_serial_no'].',';
		if($count%2==0){
		echo "</br>";
		}
		$count++;
		}
		echo ' ]';
		}
		?></strong></td>
		<td><?php echo $value['product_quantity'];?></td>
		<td><?php echo $value['product_price'];?></td>
		<?php if($sales_status!='quote'){?>
		<td><?php echo $value['product_vat_total']?></td>
		<td><?php $subtotal+=$value['product_subtotal'];
		echo $value['product_vat_total']+$value['product_subtotal'];
		?></td>
		<?php } ?>
		</tr>
		<?php }?>
		
		</tbody>
		</table>
		</div>
		<?php if($sales_status!='quote'){?>
		<table class="table">
		<tbody>
		<tr>
		<td><strong><?php echo trans('sub_total');?> :</strong></td>
		<td><?php echo $subtotal; ?></td>
		<td><strong><?php echo trans('vat');?> :</strong></td>
		<td><?php echo $get_invoice_data['sales_vat'];?></td>
		<td><strong><?php echo trans('total');?> :</strong></td>
		<td><?php echo $get_invoice_data['sales_total'];?></td>
		</tr>
		
		
		<tr>
		<td><strong><?php echo trans('discount');?> :</strong></td>
		<td><?php echo $get_invoice_data['sales_discount'];?></td>
		<?php if($get_invoice_data['shipping_charge']!=null){?>
		
		<td><strong><?php echo trans('shipping_charge');?> :</strong></td>
		<td><?php echo $get_invoice_data['shipping_charge'];?></td>
		
		<?php }?>
		
		</tr>
		
		
		<tr>
		
		</tr>
		</tbody>
		</table>
		<?php } ?>
		<div class="row invoice-table">
		<div class="col-print-4 text-center" style="margin-top:50px;">
		<hr style="width:90%; border:1px solid #e7eaec;">
		Received With Good Condition By
		</div>
		<div class="col-print-4 text-center" style="margin-top:50px;">
		<hr style="width:90%; border:1px solid #e7eaec;">
		Prepared By
		</div>
		<div class="col-print-4 text-center" style="margin-top:50px;">
		<hr style="width:90%; border:1px solid #e7eaec;">
		Authenticated Signature
		</div>
		</div>
		</div>
		<div class="clearfix"></div>
		</div>
		<div class="clearfix"></div>
		</body>
		</html>
		</div>
		<div class="clearfix"></div>
		<?php
		break;
		case "DeleteStockTransfer":
		app('pos')->DeleteStockTransfer($_POST);
		break;
		
		case "StockTransferSubmit":
		app('pos')->StockTransferSubmit($_POST,$this->currentUser->id,$this->currentUser->store_id);
		break;
		
		case "StockAdjustmentSubmit":
		app('pos')->StockAdjustmentSubmit($_POST,$this->currentUser->id,$this->currentUser->store_id);
		break;
		
		case "DeleteStockAdjustment":
		app('pos')->DeleteStockAdjustment($_POST);
		break;
		
		case "AddPaymentTransferSubmit":
		app('pos')->AddPaymentTransferSubmit($_POST,$this->currentUser->id);
		break;
		
		case "DeleteStockAdjustment":
		app('pos')->DeleteStockAdjustment($_POST);
		break;
		
		case "DueAdjustment":
		
		if ($_POST['contact_type']=='customer') {
		
		if ($_POST['due_amount']<0) {
		if (abs($_POST['due_amount'])<abs($_POST['amount'])) {
		respond(array(
		'status' => 'error',
		'errors' => array(
		'amount' => trans('not_allowed')
		)
		), 422);
		}
		$return_invoices = app('admin')->getwhereand('pos_return','customer_id',$_POST['contact_id'],'return_payment_status','due');
		if (!empty($return_invoices)) {
		$amount = abs($_POST['amount']);
		$paidAmount = 0;
		$AvaliableBalance = 0;
		foreach ($return_invoices as $return_invoice) {
		$_POST['return_id'] = $return_invoice['return_id'];
		$_POST['sales_id'] = $return_invoice['sales_id'];
		$return_value = app('pos')->GetSalesByCustomerOrder(false,$return_invoice['sales_id']);
		
		$AvaliableBalance = $amount - $paidAmount ;
		
		if($AvaliableBalance > 0){
		if (abs($return_value['total_due']) <= $AvaliableBalance) {
		$_POST['due'] = abs($return_value['total_due']);
		app('pos')->CustomerDueAdjustmentLess($_POST,$this->currentUser->id,$this->currentUser->store_id);
		app('pos')->UpdateStatus($_POST,$this->currentUser->id,$this->currentUser->store_id);
		$paidAmount += abs($return_value['total_due']);
		}else{
		$_POST['due'] = $AvaliableBalance;
		$paidAmount += $AvaliableBalance;
		app('pos')->CustomerDueAdjustmentLess($_POST,$this->currentUser->id,$this->currentUser->store_id);
		break;
		}
		}
		}
		respond(array(
		"status" => "success"
		));
		}
		
		}else{
		
		if ($_POST['due_amount']<$_POST['amount']) {
		respond(array(
		'status' => 'error',
		'errors' => array(
		'amount' => trans('not_allowed')
		)
		), 422);
		}else {
		$customer_due_invoices = app('admin')->getwhereand('pos_sales','customer_id',$_POST['contact_id'],'sales_payment_status','due');
		if (!empty($customer_due_invoices)) {
		$amount = $_POST['amount'];
		$paidAmount = 0;
		$AvaliableBalance = 0;
		foreach ($customer_due_invoices as $due_invoice) {
		$_POST['sales_id'] = $due_invoice['sales_id'];
		$purchase_value = app('pos')->GetSalesByCustomerOrder(false,$due_invoice['sales_id']);
		$AvaliableBalance = $amount - $paidAmount ;
		
		if($AvaliableBalance > 0){
		if ($purchase_value['total_due'] <= $AvaliableBalance) {
		$_POST['due'] = $purchase_value['total_due'];
		app('pos')->CustomerDueAdjustmentGreater($_POST,$this->currentUser->id,$this->currentUser->store_id);
		app('pos')->Updateinvoice($_POST,$this->currentUser->id,$this->currentUser->store_id);
		$paidAmount += $purchase_value['total_due'];
		}else{
		$_POST['due'] = $AvaliableBalance;
		$paidAmount += $AvaliableBalance;
		app('pos')->CustomerDueAdjustmentGreater($_POST,$this->currentUser->id,$this->currentUser->store_id);
		break;
		}
		}
		}
		respond(array(
		"status" => "success"
		));
		}
		}
		}
		}elseif($_POST['contact_type']=='supplier'){
		
		if ($_POST['due_amount']<0) {
		if (abs($_POST['due_amount'])<abs($_POST['amount'])) {
		respond(array(
		'status' => 'error',
		'errors' => array(
		'amount' => trans('not_allowed')
		)
		), 422);
		}
		$return_invoices = app('admin')->getwhereand('pos_return','supplier_id',$_POST['contact_id'],'return_payment_status','due');
		if (!empty($return_invoices)) {
		$amount = abs($_POST['amount']);
		$paidAmount = 0;
		$AvaliableBalance = 0;
		foreach ($return_invoices as $return_invoice) {
		$_POST['return_id'] = $return_invoice['return_id'];
		$_POST['purchase_id'] = $return_invoice['purchase_id'];
		$return_value = app('pos')->GetPurchaseByCustomerOrder(false,$return_invoice['purchase_id']);
		
		$AvaliableBalance = $amount - $paidAmount ;
		
		if($AvaliableBalance > 0){
		if (abs($return_value['total_due']) <= $AvaliableBalance) {
		$_POST['due'] = abs($return_value['total_due']);
		app('pos')->SupplierDueAdjustmentLess($_POST,$this->currentUser->id,$this->currentUser->store_id);
		app('pos')->UpdateStatus($_POST,$this->currentUser->id,$this->currentUser->store_id);
		$paidAmount += abs($return_value['total_due']);
		}else{
		$_POST['due'] = $AvaliableBalance;
		$paidAmount += $AvaliableBalance;
		app('pos')->SupplierDueAdjustmentLess($_POST,$this->currentUser->id,$this->currentUser->store_id);
		break;
		}
		}
		}
		respond(array(
		"status" => "success"
		));
		}
		
		
		}else{
		
		if ($_POST['due_amount']<$_POST['amount']) {
		respond(array(
		'status' => 'error',
		'errors' => array(
		'amount' => trans('not_allowed')
		)
		), 422);
		}else {
		$supplier_due_invoices = app('admin')->getwhereand('pos_purchase','supplier_id',$_POST['contact_id'],'purchase_payment_status','due');
		if (!empty($supplier_due_invoices)) {
		$amount = $_POST['amount'];
		$paidAmount = 0;
		$AvaliableBalance = 0;
		foreach ($supplier_due_invoices as $due_invoice) {
		$_POST['purchase_id'] = $due_invoice['purchase_id'];
		$purchase_value = app('pos')->GetPurchaseByCustomerOrder(false,$due_invoice['purchase_id']);
		
		$AvaliableBalance = $amount - $paidAmount ;
		
		if($AvaliableBalance > 0){
		if ($purchase_value['total_due'] <= $AvaliableBalance) {
		$_POST['due'] = $purchase_value['total_due'];
		app('pos')->SupplierDueAdjustmentGreater($_POST,$this->currentUser->id,$this->currentUser->store_id);
		app('pos')->Updateinvoice($_POST,$this->currentUser->id,$this->currentUser->store_id);
		$paidAmount += $purchase_value['total_due'];
		}else{
		$_POST['due'] = $AvaliableBalance;
		$paidAmount += $AvaliableBalance;
		app('pos')->SupplierDueAdjustmentGreater($_POST,$this->currentUser->id,$this->currentUser->store_id);
		break;
		}
		}
		}
		respond(array(
		"status" => "success"
		));
		}
		}
		}
		}
		break;
		
		case "GetChangeApiStatus":
		app('admin')->GetChangeApiStatus($_POST);
		break;
		case "AddNewPaymentTransferSubmit":
		app('pos')->AddNewPaymentTransferSubmit($_POST,$this->currentUser->id,$_POST['store_id']);
		break;
		case "TransferDelete":
		app('pos')->TransferDelete($_POST);
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