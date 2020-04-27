<?php defined('_AZ') or die('Restricted access'); 
include dirname(__FILE__) .'/../../modules/pos/include/header.php';
include dirname(__FILE__) .'/../../modules/pos/include/side_bar.php';
include dirname(__FILE__) .'/../../modules/pos/include/navbar.php';
	$getpossetting = app('pos')->GetPosSetting();
?>
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-4">
		<h2><?php echo trans('challan_view'); ?></h2>
		<ol class="breadcrumb">
			<li>
				<a href="pos/home"><?php echo trans('dashboard'); ?></a>
			</li>
			<li class="active">
				<strong><?php echo trans('challan_view'); ?></strong>
			</li>
		</ol>
	</div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
	<?php 
		if(isset($this->route['id'])){
			$sales_info=app('pos')->GetSalesByCustomerOrder(false,$this->route['id']);
			$get_invoice_data = app('admin')->getwhereid('pos_sales','sales_id',$this->route['id']);
			if($get_invoice_data!=null){
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
				$sale_product = app('admin')->getwhereand('pos_stock','sales_id',$this->route['id'],'stock_category','sales');
			?>
			<div class="ibox-content p-xl">
				<div class="row">
					<div class="col-sm-6">
						<h5>From:</h5>
						<address>
							<strong><?php echo $getpossetting['company_name'];?>.</strong><br>
							<?php
								if(isset($getpossetting['address']) && $getpossetting['address']!=null){
									echo $getpossetting['address'];
								}
								else{echo "N/A";}
							?><br>
							<abbr title="Phone">Phone:</abbr> 
							<?php
								if(isset($getpossetting['phone']) && $getpossetting['phone']!=null){
									echo $getpossetting['phone'];
								}
								else{echo "N/A";}
							?>
						</address>
					</div>
					
					<div class="col-sm-6 text-right">
						<h4>Invoice No.</h4>
						<h4 class="text-navy"><?php echo $this->route['id'];?></h4>
						<span>To:</span>
						<address>
							<strong><?php echo $customer_name;?></strong> <br>
							<?php
								if(isset($customerdetails['address']) && $customerdetails['address']!=null){
									echo $customerdetails['address'];
								}
								else{echo "N/A";}
							?><br>
							<abbr title="Phone">Phone:</abbr> 
							<?php
								if(isset($customerdetails['phone']) && $customerdetails['phone']!=null){
									echo $customerdetails['phone'].'</br>';
								}
								else{echo "N/A <br>";}
							?>
						</address>
						<p>
							<span><strong>Invoice Date:</strong> <?php echo $get_invoice_data['created_at']	;?></span><br/>
						</p>
					</div>
				</div>
				
				
				
				<div class="table-responsive m-t">
					<table class="table invoice-table">
						<thead>
							<tr>
								<th class="text-center"><?php echo trans('serial_no'); ?></th>
								<th class="text-center"><?php echo trans('item'); ?></th>
								<th class="text-center"><?php echo trans('quantity'); ?></th>
							</tr>
						</thead>
						<tbody class="text-center">
							<?php
								$i=0; 
								$subtotal=0;
								foreach ( (array) @$sale_product as $value) {
									$i++;
									@$sale_product_details = app('admin')->getwhereid('pos_product','product_id',$value['product_id']);
									$GetSerial = app('admin')->getwhereand('pos_product_serial','product_id',$value['product_id'],'sales_id',$get_invoice_data['sales_id']);
								?>
								<tr>
									<td><?php echo $i;?></td>
									<td><strong><?php 
									echo $sale_product_details['product_name']; 
										if(count($GetSerial)!=0){
											echo ' [';		
											$count=1;
											foreach($GetSerial as $serial){
											echo $serial['product_serial_no'].',';
											if($count%2==0){
												echo "</br>";
											}
											$count++;
											}
											echo ']';
											}
									?></strong></td>
									<td><?php echo $value['product_quantity'];?></td>

								</tr>
							<?php }?>
							
						</tbody>
					</table>
				</div>
				<div class="text-right">
					<a class="btn btn-primary print_challan" href="pos/print-challan/<?php echo $this->route['id']?>" sales_id="<?php echo $this->route['id']?>"><i class="fa fa-print"></i> <?php echo trans("print_challan");?></a>
				</div>
			</div>	
			<?php } else{
			?>
			<script>
				window.onload = function(){ Alert() };
			</script>
		<?php }}?>
</div>
<div class='challan_receipt_view hidden'></div>
[footer]
<script>
	$(".print_challan").click(function(){
		var s_id = $(this).attr('sales_id');
		jQuery.ajax({
			url: "pos/ajax/",
			data: {
				action: "GetChallan",
				id	  : s_id
			},
			type: "POST",
			success:function(data){
				
				$(".challan_receipt_view").html(data);		
				$.print("#sales_challan");
				
			},
			error:function (){}
		});
	});
	
	function Alert()
	{
		swal ( "Oops" ,  "No Challan Found" ,  "error" );
	}
</script>
[/footer]
<?php include dirname(__FILE__) .'/../../modules/pos/include/footer.php'; ?>					