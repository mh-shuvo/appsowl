<?php ob_start();
if(isset($this->route['id'])){
$id = $this->route['id'];	
}
$saleData = app('admin')->getwhereid('pos_sales','sales_id',$id);
if(count($saleData)>0){
$UserData = app('admin')->getuserdetails($saleData['user_id']);
$companyProfile = app('root')->table('as_pos_requirements')->where('user_id',$UserData['added_by'])->get(); 
$companyProfile = $companyProfile[0];
$customerData = app('admin')->getwhereid('pos_contact','contact_id',$saleData['customer_id']);
$saleProduct = app('admin')->getwhereand('pos_stock','sales_id',$id,'stock_category','sales');
$getInvoice = app('admin')->getall('pos_invoice');
$isInvoiceNull = false;
$height = 100;
$footer = 100;
$header=null;
$footer = null;
$logo =null;
$footer_note = null;
if(count($getInvoice)== 0){
	$isInvoiceNull = true;
}
else{
	$getInvoice = $getInvoice[0];
}

if(isset($getInvoice['top'])&&$getInvoice['top']!=null){
	$height = $getInvoice['top'];
}
if(isset($getInvoice['bottom'])&&$getInvoice['bottom']!=null){
	$height = $getInvoice['bottom'];
}

if(isset($getInvoice['header'])&&$getInvoice['header']!=null){
	$header = $getInvoice['header'];
}
if(isset($getInvoice['footer'])&&$getInvoice['footer']!=null){
	$footer = $getInvoice['footer'];
}

if(isset($getInvoice['logo'])&&$getInvoice['logo']!=null){
	$logo = $getInvoice['logo'];
}
if(isset($getInvoice['footer_note'])&&$getInvoice['footer_note']!=null){
	$footer_note = $getInvoice['footer_note'];
}

?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset='utf-8'>
        <link rel="stylesheet" type="text/css" href="assets/system/css/bootstrap.min.css">
        <style>
            body{
				font-size:10px;
			}
            @page {
                margin: 50px 0px;
            }
            
            header {
                position: fixed;
                top: -50px;
                left: 0px;
                right: 0px;
                /** Extra personal styles 
                background-color: #03a9f4;
                color: white;
                text-align: center;
                line-height: 35px;
				**/
            }
            
            footer {
                position: fixed;
                bottom: -50px;
                left: 0px;
                right: 0px;
                
            }
			.title{
				font-weight:bold;
			}
			.f-12{
					font-size:12px;
			}
			.f-bold{
					font-weight:bold;
			}
        </style>
        </style>
        <title><?php echo $companyProfile['company_name'];?></title>
    </head>

    <body>

        <header style="height: <?php echo $height;?>px;">
			<?php 
				if(isset($getInvoice['header'])&&$getInvoice['header']!=null){
			?>
          <img src="images/stores/<?php echo $_SERVER['SERVER_NAME']; ?>/<?php echo $getInvoice['header']; ?>" width="100%" height="100%" />
			<?php } ?>
			
			<?php if(isset($getInvoice['logo'])&& $getInvoice['logo']!=null){ ?>
			<div class="container">
				<div class="row">
					<div class="col-sm-4 col-sm-offset-1">
						<img src="images/stores/<?php echo $_SERVER['SERVER_NAME']; ?>/<?php echo $getInvoice['logo']; ?>" height="100%" style="margin-top:10px;">
						
					</div>
					<div class="col-sm-6" style="text-align:right;">
						<span class="f-12 f-bold"><?php echo $companyProfile['company_name'];?></span><br>
						<span class="f-12 f-bold"><?php echo $companyProfile['company_address']; ?></span><br>
						<span class="f-12 f-bold">Phone: <?php echo $companyProfile['company_phone'];?></span><br>
						<span class="f-12 f-bold">Email: <?php echo $companyProfile['company_email'];?></span><br>
						<?php if($companyProfile['company_website']!=null || $companyProfile['company_website']!='' ){?>
							<span class="f-12 f-bold">Website: <?php echo $companyProfile['company_website'];?></span><br>
						<?php }?>
					</div>
					<div class="col-sm-1"></div>
				</div>
			</div>
			<?php } ?>
			<?php if($isInvoiceNull){ ?>
			<div class="container">
				<div class="row">
					<div class="col-sm-12" style="text-align:center;">
						<span class="h2 f-bold"><?php echo $companyProfile['company_name'];?></span><br>
						<span class="f-12 f-bold"><?php echo $companyProfile['company_address']; ?></span><br>
						<span class="f-12 f-bold">Phone: <?php echo $companyProfile['company_phone'];?></span><br>
						<span class="f-12 f-bold">Email: <?php echo $companyProfile['company_email'];?></span><br>
						<?php if($companyProfile['company_website']!=null || $companyProfile['company_website']!='' ){?>
							<span class="f-12 f-bold">Website: <?php echo $companyProfile['company_website'];?></span><br>
						<?php }?>
					</div>
					<div class="col-sm-1"></div>
				</div>
			</div>
			<?php } ?>
			
        </header>

        <footer style="height: <?php echo $height;?>px;text-align:center;">
            <?php 
				if(isset($getInvoice['footer'])&&$getInvoice['footer']!=null){
			?>
				<img src="images/stores/<?php echo $_SERVER['SERVER_NAME']; ?>/<?php echo $getInvoice['footer']; ?>" width="100%" height="100%" />
			<?php } ?>
			
			<?php 
				if(isset($getInvoice['footer_note'])&&$getInvoice['footer_note']!=null){
			?>
				<p class="h4"><?php echo $footer_note; ?></p>
			<?php } ?>
			<?php if($isInvoiceNull){ ?>
				<p class="h4">Thanks For Buying From Use. Come Again.</p>
			<?php } ?>
			
        </footer>
        <main style="margin-top:50px;">
		<div class="row">
			<div class="col-sm-12">
				<h3 class="text-center title">
				<?php if($saleData['sales_status']=='complete'||$saleData['sales_status']=='ordered'||$saleData['sales_status']=='cancel'||$saleData['sales_status']=='draft'){
					echo "Invoice";
				}
				else if($saleData['sales_status']=='quote'){
					echo "Quotation";
				}
				else{
					echo "";
				}
					?>
				</h3>
			</div>
		</div>
		<div class="row" style="margin-bottom:30px;">
			<div class="container">
				<div class="col-sm-8">
					<span class="f-12">Customer Name: <?php echo $customerData['name'];?></span><br>
					<span class="f-12">Customer Phone: <?php echo $customerData['phone'];?></span><br>
					<span class="f-12">Customer Address: <?php echo $customerData['address'];?></span><br>
				</div>			
				<div class="col-sm-4">
					<span class="f-12">Invoice Date: <?php echo getdatetime($saleData['created_at'],3);?> </span><br>
					<span class="f-12">Invoice No: <?php echo $saleData['sales_id'];?></span><br>
					<span class="f-12">Sold By: <?php echo  $UserData['first_name'].' '. $UserData['last_name']?></span><br>
				</div>
			</div>
		</div>		
		<div class="row">
			<div class="col-sm-12">
		
				<table class="table table-bordered">
					<thead>
						<tr>
							<th class="text-center">No</th>
							<th class="text-center">Name</th>
							<th class="text-center">Quantity</th>
							<th class="text-center">Unite Price</th>
							<?php if($saleData['sales_status']!='quote'){?>
							<th class="text-center">Vat</th>
							<th class="text-center">Total Price</th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
					<?php
					$subtotal=0;
					for($i=0;$i<count($saleProduct);$i++){
						$salePproductDetails = app('admin')->getwhereid('pos_product','product_id',$saleProduct[$i]['product_id']);
						$GetSerial = app('admin')->getwhereand('pos_product_serial','product_id',$saleProduct[$i]['product_id'],'sales_id',$saleData['sales_id']);
						
						?>
						<tr class="text-center">
							<td><?php echo $saleProduct[$i]['product_id'];?></td>
							<td>
							<strong>
								<?php
									echo $salePproductDetails['product_name']; 
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
							</strong>
							</td>
							<td><?php echo $saleProduct[$i]['product_quantity'];?></td>
							<td><?php echo $saleProduct[$i]['product_price'];?></td>
							<?php if($saleData['sales_status']!='quote'){?>
							<td><?php echo $saleProduct[$i]['product_vat_total'];?></td>
							<td>
							<?php 
							$subtotal+=$saleProduct[$i]['product_subtotal'];
							echo $saleProduct[$i]['product_vat_total']+$saleProduct[$i]['product_subtotal'];
							?>
							</td>
							<?php } ?>
						</tr>
					<?php } ?>
					</tbody>
				</table>				
			</div>
		</div>
		<div class="row">
			<div class="col-sm-4 col-sm-offset-8">
				<table class="table table-striped">
					<tbody>
						<tr>
							<td><strong>Subtotal:</strong></td>
							<td>
								<?php echo $subtotal; ?>
							</td>
						</tr>
						
						<tr>
							<td><strong>Vat :</strong></td>
							<td>
								<?php echo $saleData['sales_vat']; ?>
							</td>
						</tr>
						<tr>
							<td><strong>Total :</strong></td>
								<td>
									<?php echo $subtotal+$saleData['sales_vat']; ?>
								</td>
						</tr>
						

						<tr>
							<td><strong>Discount(<?php echo $saleData['sales_discount_type']=='fixed'? $saleData['sales_discount_value'].'TK' : $saleData['sales_discount_value'].'%'; ?>) :</strong></td>
							<td>
								<?php
									echo $saleData['sales_discount']; 
								?>
							</td>
						</tr>
						
						<tr>
							<td><strong>Payable Amount :</strong></td>
							<td>
								<?php echo $saleData['sales_total']; ?>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<br><br><br>
		<div class="row">
			<div class="col-sm-3 col-sm-offset-1 text-center">
			<hr>
				<p class="h5">Goods Received as per specification</p>
			</div>
			<div class="col-sm-3 text-center">
			<hr>
				<p class="h5">Prepared by</p>
			</div>
			<div class="col-sm-3 text-center">
			<hr>
				<p class="h5">Authurized Signature</p>
			</div>
		</div>
	
        </main>
    </body>

    </html>
    <?php
$html = ob_get_clean();

		$dompdf = new Dompdf\Dompdf();  //if you use namespaces you may use new \DOMPDF()
		$dompdf->loadHtml($html);
		$dompdf->render();
		$dompdf->stream("sample.pdf", array("Attachment"=>0));
		
}
else{
?>
 <!DOCTYPE html>
    <html>

    <head>
        <meta charset='utf-8'>
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <style>
            
        </style>
        </style>
        <title><?php echo $id;?></title>
    </head>

    <body>
		 <div class="middle-box text-center animated fadeInDown">
				<h1>404</h1>
				<h3 class="font-bold">Invoice Not Found</h3>

				<div class="error-desc">
					Sorry, you entered invoice number or sales id does not exist.
				</div>
			</div>
			
			    <!-- Mainly scripts -->
    <script src="assets/system/js/jquery-3.1.1.min.js"></script>
    <script src="assets/system/js/bootstrap.min.js"></script>
	</body>
</html>
<?php } ?>