<?php defined('_AZ') or die('Restricted access'); 
	$key = md5(date('ymdhi'));
	include dirname(__FILE__) .'/include/header.php';
	include dirname(__FILE__) .'/include/side_bar.php';
	$getSubscriptions = app('admin')->getwhere('as_subscribe','user_id',ASSession::get("user_id"));
	$getPaymentHistory = app('admin')->getallLimitAnd('as_payment','user_id','10','payment_id','payment_status','paid',ASSession::get("user_id"));
	$getSoftware = app('admin')->getall('as_software');
?>
[header]
<style>
.landing-page .pricing-plan dd {
    padding: 10px 16px;
    border-top: 1px solid 
	#e7eaec;
	text-align: center;
	color:
		#aeaeae;
	}
	strong{
		text-align:center;
	}
</style>
[/header]
<div class="wrapper wrapper-content animated fadeInRight posSubscribe">
    <form class="form-horizontal">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 category">
                <!-- App Pricing -->
                <div class="ibox ibox_category">
                    <div class="ibox-content landing-page">
						<section id="pricing" class="pricing">
							<div class="row m-b-lg">
								<div class="col-lg-12 text-center">
									<div class="navy-line"></div>
									<h1>App Pricing</h1>
									<p>Donec sed odio dui. Etiam porta sem malesuada magna mollis euismod.</p>
								</div>
							</div>
							<div class="row">
								<?php 
									$softwareServices = app('db')->table('as_software_variation')->where("software_variation_type", "global")->get();
									foreach($softwareServices as $softwareService){
									$subscribe_soft = app('db')->table('as_subscribe')->where("software_variation_id", $softwareService['software_variation_id'])->where('user_id',$this->currentUser->id)->get(1);
									
									?>
									<div class="col-lg-4 wow zoomIn">
										<ul class="pricing-plan list-unstyled <?php if($subscribe_soft['subscribe_status'] =='active') echo "selected"; ?>">
											<li class="pricing-title">
												<?php echo $softwareService['software_variation_name']; ?>
											</li>
											<li class="pricing-desc">
												<?php echo $softwareService['software_variation_des']; ?>
											</li>
											<li class="pricing-price"> 
												<span><?php echo $softwareService['software_variation_price']; ?></span> / month
											</li>
											<div class="featured_list">
											</div>
											<?php echo $softwareService['software_variation_featured_list']; ?>
											<li>
											<?php if($subscribe_soft['subscribe_status'] =='active'){ 
											$getsubdomain = app('admin')->getwhereid('as_sub_domain','user_id',$this->currentUser->id);
											?>
												<a class="btn btn-success" href="<?php echo '//'.$getsubdomain['sub_domain'].'.'.$getsubdomain['root_domain'].'/pos/' ?>">Goto Dashboard</a>
											<?php } else{ ?>
												<a class="btn btn-primary btn-xs software_category" 
												software_variation_name="<?php echo $softwareService['software_variation_name']; ?>" 
												software_variation_id="<?php echo $softwareService['software_variation_id']; ?>" 
												software_id="<?php echo $softwareService['software_id']; ?>" 
												software_variation_price="<?php echo $softwareService['software_variation_price']; ?>" 
												software_setup_fee="<?php echo $softwareService['software_setup_fee']; ?>" 
												href="javascript:void(0)">
												Signup</a>
											<?php } ?>
											</li>
										</ul>
									</div>
								<?php  } ?>
							</div>
						</section>
					</div>
				</div>
				<div class="ibox float-e-margins hide ibox_setting">
					<div class="ibox-title">
						<h4><?php echo trans('pos_setting_installation')?></h4>
					</div>
					<div class="ibox-content">
						<div class="form-group">
							<label class="col-sm-3 control-label"><?php echo trans('company_name'); ?> <span class="text-danger">*</span></label>
							
							<div class="col-sm-9">
								<input type="text" name="company_name" id="company_name" class="form-control" onkeyup='seturl()' placeholder="<?php echo trans('your_company_name'); ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label"><?php echo trans('business_url');?> <span class="text-danger">*</span></label>
							<div class="col-sm-9" >
								<div class="input-group m-b" >
									<input type="text" name="domain_name" id="domain_name" class="form-control" onkeyup='getcheckdomain()' onchange="getcheckdomain()" placeholder="Search Your Domain">
									<span class="input-group-addon" wfd-id="97">.<?php echo ROOT_DOMAIN; ?></span>
								</div>
							</div>
							<div class="domainstatus text-center"></div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label"><?php echo trans('company_address'); ?> <span class="text-danger">*</span></label>
							<div class="col-sm-9">
								<input type="text" name="address" id="address" class="form-control" placeholder="<?php echo trans('address'); ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label"><?php echo trans('company_city'); ?></label>
							<div class="col-sm-9">
								<input type="text" name="city" id="city" class="form-control" placeholder="<?php echo trans('city'); ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label"><?php echo trans('company_country'); ?></label>
							<div class="col-sm-9">
								<input type="text" name="country" id="country" class="form-control" placeholder="<?php echo trans('company_country'); ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label"><?php echo trans('company_postcode'); ?></label>
							<div class="col-sm-9">
								<input type="text" name="postcode" id="postcode" class="form-control" placeholder="<?php echo trans('company_postcode'); ?>">
							</div>
						</div>
						
						<div class="hr-line-dashed"></div>
						<div class="form-group">
							<label class="col-sm-3 control-label"><?php echo trans('vat_registration_no'); ?></label>
							<div class="col-sm-9">
								<input type="text" name="nbr_no" id="nbr_no" class="form-control" placeholder="<?php echo trans('vat_registration_no'); ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label"><?php echo trans('vat_unit'); ?></label>
							<div class="col-sm-9">
								<input type="text" name="nbr_unite" id="nbr_unite" class="form-control" placeholder="<?php echo trans('vat_unit'); ?>">
							</div>
						</div>
						<div class="form-group"><label class="col-sm-3 control-label"><?php echo trans('currency'); ?></label>
							
							<div class="col-sm-9">
								<select class="form-control m-b" name="currency" id="currency">
									<option value=""><?php echo trans('select_currency'); ?></option>
									<?php $as_country = app('admin')->getall("as_country");
										foreach ($as_country as $value) {
											if(!empty($value['currency_symbol'])){
												if($value['code'] == "BD"){
													echo "<option value='".$value['code']."' selected>".$value['code'].' | '.$value['currency_symbol']."</option>";
													}else{
													echo "<option value='".$value['code']."'>".$value['code'].' | '.$value['currency_symbol']."</option>";
												}
												
											}
										}
									?>
								</select>
							</div>
						</div>
						
						<div class="hr-line-dashed"></div>
						<div class="form-group">
							<label class="col-sm-3 control-label"><?php echo trans('vat_type'); ?> <span class="text-danger">*</span></label>
							<div class="col-sm-9">
								<select name='vat_type' class="form-control" id="vat_type">
									<option value=""><?php echo trans('select_vat_type'); ?></option>
									<option value='global'><?php echo trans('global');?></option>
									<option value='single'><?php echo trans('single');?></option>
								</select>
							</div>
						</div>
						<div class="form-group" id="var_area" style="display:none;">
							<label class="col-sm-3 control-label"><?php echo trans('vat').'(%)'; ?> <span class="text-danger">*</span></label>
							<div class="col-sm-9">
								<input type="text" name="vat" id="vat" class="form-control">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-4 hide subscription_summary">
				<div class="ibox">
					<div class="ibox-title">
						<h5><?php echo trans('subscription_summary'); ?></h5>
					</div>
					<div class="ibox-content">
						<div class="row">
							<div class="col-md-12">
								<span class="font-bold h5">
									<?php echo trans('software_package_name').' : ';?><span class="software_package_name"></span> 
								</span>
							</div>
							
						</div>
						<div class="row">
							<div class="col-md-12">
								<span class="font-bold h5">
									<?php echo trans('basic_subscription_fee').' : ';?><span class="subscription_fees">0.00</span> Tk/Month<br/>
									<?php echo trans('subscription_setup_fee').' : ';?><span class="setup_fee">0.00</span> Tk <span class="variation_fee_advance"></span>
								</span>
							</div>
						</div>
						<hr>
						<div class="row">
							<span class="text-muted small" id="software_already_exits">
								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo trans('billing_period'); ?> <span class="text-danger">*</span></label>
									<div class="col-sm-6">
										<select name='billing_period' class="form-control" id="billing_period">
											<option value='3'>3 <?php echo trans('month');?></option>
											<option value='6'>6 <?php echo trans('month');?></option>
											<option value='12'>12 <?php echo trans('month');?></option>
											<option value='18'>18 <?php echo trans('month');?></option>
											<option value='24'>24 <?php echo trans('month');?></option>
										</select>
									</div>
								</div>
							</span>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12 text-center">
								<span class="font-bold h4">
									<?php echo trans('total').' : ';?><span class="software_total_price">0.00</span> Tk
									<input type="hidden" name="total_amount" id="total_amount" />
									<input type="hidden" name="software_variation_id" id="software_variation_id" />
									<input type="hidden" name="software_variation_price" id="software_variation_price" />
									<input type="hidden" name="software_variation_setup_fee" id="software_variation_setup_fee" />
									<input type="hidden" name="software_id" id="software_id" />
								</span>
							</div>
						</div>
						<div class="m-t-sm">
							<div class="btn-group">
								<button type="button" class="btn btn-primary btn-sm subscribe_button"><?php echo trans('next'); ?></button>
								<button type="button" class="btn btn-success btn-sm back_button hide"><?php echo trans('back'); ?></button>
								<button type="submit" class="btn btn-primary btn-sm hide" id="installing-button"><?php echo trans('setup_now'); ?></button>
								<a href="/dashboard" class="btn btn-white btn-sm"> <?php echo trans('cancel'); ?></a>
							</div>
						</div>
					</div>
				</div>
				
				<div class="ibox hide">
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
	<div class="row">
		<div class="col-sm-5">
			<div class="ibox">
				<div class="ibox-title">
					<h3><?php echo trans('software_subscriptions'); ?></h3>
				</div>
				<div class="ibox-content">
					<div class="table-responsive">
						<table class="table table-striped table-bordered text-center dataTables-example" id="">
							<thead>
								<tr role="row">
									<th class="text-center"><?php echo trans('subscribe_id'); ?></th>
									<th class="text-center"><?php echo trans('subscribe_date'); ?></th>
									<th class="text-center"><?php echo trans('title'); ?></th>  
									<th class="text-center"><?php echo trans('type'); ?></th>
									<th class="text-center"><?php echo trans('status'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php 
									$count=1;
									$subscribe_details = app('admin')->getwhere('as_subscribe','user_id',ASSession::get("user_id"));
									foreach($subscribe_details as $subscribe){
										$software = app('admin')->getwhereid('as_software','software_id',$subscribe['software_id']);
										$checkBillingType= $software['software_billing_type'];
										$invoice_data = app('root')->select("SELECT `invoice_id`,`subscribe_id`,`invoice_status`,`subscribe_start_date`,`subscribe_end_date`,`invoice_amount` FROM `as_invoices` WHERE `subscribe_id` = :id ORDER BY `invoice_id` DESC LIMIT 1", 
										array( 'id' => $subscribe['subscribe_id']));
										
										if(!$invoice_data){
											if($checkBillingType == 'postpaid'){
												$date = new DateTime($subscribe['subscribe_activation_date']);
												$date->modify('+ 1 month');
												$invoice_data[0]['subscribe_end_date'] = $date->format('Y-m-d');
												$invoice_data[0]['subscribe_start_date']=$subscribe['subscribe_activation_date'];
												$invoice_data[0]['invoice_amount']=$subscribe['subscribe_amount'];
											}
										}
										if($subscribe['subscribe_type']=='software'){
											$software_variaton=app('admin')->getwhereid('as_software_variation','software_variation_id',$subscribe['software_variation_id']);
											$software=app('admin')->getwhereid('as_software','software_id',$subscribe['software_id']);
											$title=$software['software_title'].'['.$software_variaton['software_variation_name'].']';
										}
										else if($subscribe['subscribe_type']=='plugins'){
											$plugins=app('admin')->getwhereid('as_plugins','plugins_id',$subscribe['plugins_id']);
											$title=$plugins['plugins_name'];
										}
										else{
											$service=app('admin')->getwhereid('as_services','service_id',$subscribe['service_id']);
											$title = $service['service_name'];
										}
									?>
									<tr>
										<td><?php echo $subscribe['subscribe_id']; ?></td>
										<td><?php 
											echo getdatetime($subscribe['subscribe_date'],3);
										?></td>
										
										<td>
											<?php echo $title; ?>
										</td>
										<td>
											<?php echo ucwords($subscribe['subscribe_type']);?>
										</td>
										<td>
											<span class="label <?php echo ($subscribe['subscribe_status'] == 'active' ? 'label-primary' : 'label-danger');?>">
												<?php
													echo strtoupper($subscribe['subscribe_status']);
												?>
											</span>
										</td>
									</tr>
								<?php }?>
							</tbody>
							<tfoot>
								
							</tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-7">
			<div class="ibox">
				<div class="ibox-title">
					<h3><?php echo trans('last_ten_payment_history'); ?></h3>
				</div>
				<div class="ibox-content">
					<div class="table-responsive">
						<table class="table table-striped table-bordered text-center">
							<thead>
								<th class="text-center">
									<?php echo trans('serial_number'); ?>
								</th>
								<th class="text-center">
									<?php echo trans('payment_date'); ?>
								</th>
								<th class="text-center">
									<?php echo trans('payment_type'); ?>
								</th>
								<th class="text-center">
									<?php echo trans('amount'); ?>
								</th>
								<th class="text-center">
									<?php echo trans('status'); ?>
								</th>
							</thead>
							<tbody>
								<?php foreach($getPaymentHistory as $history){ ?>
									<tr>
										<td>
											<?php echo $history['payment_id'];?>
										</td>
										<td>
											<?php echo getdatetime($history['payment_time'],3);?>
										</td>
										<td>
											<?php echo $history['payment_card'];?>
										</td>
										<td>
											<?php echo $history['payment_amount'];?>
										</td>
										<td><span class="label <?php echo ($history['payment_status'] == 'active' ? " label-primary " : 'label-danger');?>">
										<?php echo $history['payment_status']; ?></span></td>
									</tr>
								<?php }?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	[footer]
	<script type="text/javascript">
		function show() {
			var adv = document.getElementById('ad_button');
			var test = document.getElementById("test");
			
			adv.style.display = "none";
			test.style.display = 'block';
		}
		function hide() {
			var adv = document.getElementById('ad_button');
			var test = document.getElementById("test");
			
			adv.style.display = "block";
			test.style.display = 'none';
		}
		function show2() {
			var adv2 = document.getElementById('ad_button2');
			var test2 = document.getElementById("test2");
			
			adv2.style.display = "none";
			test2.style.display = 'block';
			
		}
		function hide2() {
			var adv2 = document.getElementById('ad_button2');
			var test2 = document.getElementById("test2");
			adv2.style.display = "block";
			test2.style.display = 'none';
		}
		function show3() {
			var adv3 = document.getElementById('ad_button3');
			var test3 = document.getElementById("test3");
			adv3.style.display = "none";
			test3.style.display = 'block';
		}
		function hide3() {
			var adv3 = document.getElementById('ad_button3');
			var test3 = document.getElementById("test3");
			adv3.style.display = "block";
			test3.style.display = 'none';
		}
	</script>
	<script>
		$(".posSubscribe form").validate({
			rules: {
				company_name: {
					required: true
				},
				domain_name: {
					required: true
				},
				address: {
					required: true
				},
				vat_type: {
					required: true
				}
			},	
			submitHandler: function(form) {
				AS.Http.PostSubmit(form, {"action" : "GetPosSubscribe"}, "ajax/", function (response) {
					if(response.status=='success'){
						swal({
							title: $_lang.success, 
							text: response.message, 
							type: "success",
							confirmButtonColor: "#1ab394", 
							confirmButtonText: $_lang.ok,
							},function(isConfirm){
							window.location = 'install/pos/';
						});
						} 
					  else if(response.status=='exits'){
						// swal({
							// title: $_lang.success, 
							// text: response.message, 
							// type: "error",
							// confirmButtonColor: "#1ab394", 
							// confirmButtonText: $_lang.add_fund,
							// CancelButtonColor: "#1ab394", 
							// CancelButtonText: $_lang.close,
						// },function(isConfirm){
								// if(isConfirm){
									// location.href="/fund";
								// }
						// });
						swal({
							title: $_lang.success,
							text: response.message,
							type: "error",
							showCancelButton: true,
							confirmButtonColor: "#1ab394",
							confirmButtonText: $_lang.add_fund,/*"Yes!"*/
							cancelButtonText:$_lang.close ,/*"No!"*/
							closeOnConfirm: false,
						closeOnCancel: false },
						function (isConfirm) {
							if (isConfirm) {
									location.href="/fund";
								} else {
								swal($_lang.cancelled, "", "error"); /*"বাতিল করা হয়েছে"*/
							}
						});
					}
				});
			}
		});
		
		
		$(".transaction_div").addClass('hide');
		$(".subscription_summary").addClass('hide');
		
		
		$(".software_category").click(function(){
			$("#software_variation_price").val('0.00');
			$("#total_amount").val('0.00');
			$(".software_total_price").html('0.00');
			
			var softwareId = $(this).attr('software_id');
			var variationName = $(this).attr('software_variation_name');
			var variationId = $(this).attr('software_variation_id');
			var variationPrice = $(this).attr('software_variation_price');
			var variationSetupFee = $(this).attr('software_setup_fee');
			var billingPeriod = $("#billing_period").val();
			
			var totalPrice = parseInt(variationSetupFee) + (parseInt(variationPrice) * parseInt(billingPeriod));
			
			$(".category").removeClass("col-lg-offset-2");
			$(".subscription_summary").removeClass('hide');
			$(".subscription_fees").html(variationPrice);
			$(".setup_fee").html(variationSetupFee);
			$(".software_total_price").html(totalPrice);
			$("#total_amount").val(totalPrice);
			$("#software_variation_id").val(variationId);
			$("#software_variation_price").val(variationPrice);
			$("#software_variation_setup_fee").val(variationSetupFee);
			$("#software_id").val(softwareId);
			$(".software_package_name").html(variationName+' Pos Software');
		});
		
		$("#billing_period").change(function(){
			updateSoftwarePrice();
		});
		
		function updateSoftwarePrice(){
			$("#total_amount").val('0.00');
			$(".software_total_price").html('0.00');
			
			var variationPrice = $("#software_variation_price").val();
			var variationSetupFee = $("#software_variation_setup_fee").val();
			var billingPeriod = $("#billing_period").val();
			var totalPrice = parseInt(variationSetupFee) + (parseInt(variationPrice) * parseInt(billingPeriod));
			
			$("#total_amount").val(totalPrice);
			$(".software_total_price").html(totalPrice);
		}
		
		
		
		$(".payment_method").change(function(){
			var type = $(this).val();
			if(type!='cash'){
				$(".transaction_div").removeClass("hide");
			}
			else{
				$(".transaction_div").addClass('hide');
			}
		});
		$(".subscribe_button").click(function(){
			$(".ibox_category").addClass('hide');
			$(".ibox_setting").removeClass('hide');
			$(this).addClass('hide');
			$(".back_button").removeClass('hide');
			$("#installing-button").removeClass('hide');
			
		});
		$(".back_button").click(function(){
			$(".ibox_setting").addClass('hide');
			$(".ibox_category").removeClass('hide');
			$(".subscribe_button").removeClass('hide');
			$(this).addClass('hide');
			$("#installing-button").addClass('hide');
		});
		// $(".subscribe").click(function(){
		// alert("Subscribed Successfully");
		// });
		// document.getElementById('company_name').addEventListener('keypress', function(event) {
		// if (event.keyCode == 13) {
		// event.preventDefault();
		// }
		// });
		$(document).ready(function(){
			$("dl").removeClass("list list--single-line list--tick--green ");
			$("dl").addClass("pricing-plan list-unstyled ");
		});
	</script>
	[/footer]
<?php include dirname(__FILE__) .'/include/footer.php'; ?>																	