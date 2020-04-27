<?php defined('_AZ') or die('Restricted access'); 
	include dirname(__FILE__) .'/include/header.php';
	include dirname(__FILE__) .'/include/side_bar.php';
	//include dirname(__FILE__) .'/include/navbar.php';
	$id=$this->route['id'];
	$getSoftware = app('admin')->getwhereid('as_software','software_id',$id);
?>
<div class="poststatus"></div>
<div class="wrapper wrapper-content animated fadeInRight" id="app_reg">
	
	<form>
		
		<div class="row">
			<div class="col-md-9">
				
				<div class="ibox">
					<div class="ibox-title">
						<h5><?php echo trans('items_in_your_cart'); ?></h5>
						
					</div>
					
					<div class="ibox-content">
						
						
						<div class="table-responsive">
							<table class="table shoping-cart-table">
								
								<tbody>
                                    <tr>
                                        <td width="90">
                                            <div class="cart-product-imitation">
                                            	<img src="images/product/pos2.jpg" >
											</div>
										</td>
                                        <td class="desc">
                                            <h3>
												<a href="#" class="text-navy">
													<?php echo $getSoftware['software_title'];?>
												</a>
											</h3>
                                            <p class="small">
												<?php echo $getSoftware['software_tagline'];?>
											</p>
                                            <dl class="small m-b-none">
												<?php echo $getSoftware['software_short_des'];?>
												<?php echo $getSoftware['software_long_des'];?>
											</dl>
											
											<!--  <div class="m-t-sm">
												<a href="#" class="text-muted"><i class="fa fa-trash"></i> Remove item</a>
											</div> -->
										</td>
										
                                        <td>
										 <span style='color:#1ab394;font-weight:bold;'><?php echo $getSoftware['software_price'].' tk'; ?></span> / month
										</td>
                                        <td width="auto">
                                            <input type='hidden' name='software_id' id='software_id' value='<?php echo $getSoftware['software_id'];?>'>
                                            <input type='hidden' name='software_price' id='software_price' value='<?php echo $getSoftware['software_price'];?>'>
											<select class="form-control" name="soft_package" id="soft_package" >
												<option value="null"><?php echo trans('select_your_package'); ?></option>
												<option value="0">Free Trial</option>
												<option value="1"><?php echo trans('one_month'); ?></option>
												<option value="3"><?php echo trans('three_month'); ?></option>
												<option value="6"><?php echo trans('six_month'); ?></option>
												<option value="12"><?php echo trans('one_year'); ?></option>
											</select>
											
										</td>
                                        <td>
                                            <h4 id="total1">
                                                0,00
											</h4>Tk
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						
					</div>
					
					
				</div>
			</div>
			<div class="col-md-3">
				
				<div class="ibox">
					<div class="ibox-title">
						<h5><?php echo trans('subscription_summary'); ?></h5>
					</div>
					<div class="ibox-content">
						<span>
							<?php echo trans('total'); ?>
						</span>
						<h2 class="font-bold">
							<input type="text" name="subscribe_payment" class="form-control" value="0.00" readonly style="border:none;background:none;" id="total2">Tk
						</h2>
						<span>
							
						</span>
						
						<hr>
						<span>
							<?php echo trans('payment_method'); ?>
						</span>
						<h2>
							<select class="form-control" name='payment_type' id="payment_type">
								<option value='cash'><?php echo trans('cash_in'); ?></option>
								<!--<option value='paypal'>PayPal</option>-->
							</select>
						</h2>
						<hr>
						<span class="text-muted small">
							
                            <?php echo trans('for_bangladeshi_client');?>
						</span>
						<div class="m-t-sm">
							<div class="btn-group">
							<button type="submit" class="btn btn-primary btn-sm"></i><?php echo trans('get_started'); ?></button>
							<a href="admin/home" class="btn btn-white btn-sm"> <?php echo trans('cancel'); ?></a>
						</div>
					</div>
				</div>
			</div>
			
			<div class="ibox">
				<div class="ibox-title">
					<h5><?php echo trans('support');?></h5>
				</div>
				<div class="ibox-content text-center">
					<h3><i class="fa fa-phone"></i> <a href="callto:+8801844519430">+88 018 445 194 30</a></h3>
					<span class="small">
                        <?php echo trans('please_contact_with_us');?>
					</span>
				</div>
			</div>
		</div>
		
	</div>
</form>
</div>
[footer]
<script type="text/javascript">
	$("#soft_package").change(function(){
	var pk= $("#soft_package").val();
	var price = $("#software_price").val();
	if(pk == "null"){
	$("#total1").html(0);
	$("#total2").val(0);
	}else{
	$("#total1").html(pk*price);
	$("#total2").val(pk*price);
	}
	});
	</script>
[/footer]

<?php include dirname(__FILE__) .'/include/footer.php'; ?>																						