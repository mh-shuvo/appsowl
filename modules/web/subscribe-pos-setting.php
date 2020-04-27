<?php 
	defined('_AZ') or die('Restricted access');
	include dirname(__FILE__) .'/include/header.php';
	include dirname(__FILE__) .'/include/side_bar.php';
	// 	include dirname(__FILE__) .'/include/navbar.php';
	$sub_dom_check = app('admin')->getwhereid('as_sub_domain','user_id',$this->currentUser->id);
	if($sub_dom_check){
		redirect('https://'.$sub_dom_check['sub_domain'].'.'.$sub_dom_check['root_domain'].'/pos/setting');
	}
	
?>
<div class="poststatus"></div>
<div class="wrapper wrapper-content animated fadeInRight posSubscribe">
	<form class="form-horizontal">
		<div class="row">
			<div class="col-sm-8 col-sm-offset-2 category">
				<div class="ibox ibox_category">
					<div class="ibox-title">
						<h3><?php echo trans('select_software_category'); ?></h3>
					</div>
					<div class="ibox-content">
						
						<div class="row">
							<?php
								$getCategory=app('admin')->getwhereand('as_software_variation','software_id','1','software_variation_status','active');
								foreach($getCategory as $category){
								?>
								<div class="col-xs-12 col-md-3">
									<a href="javascript:void()" class="thumbnail software_category" software_variation_id="<?php echo $category['software_variation_id'];?>" software_id="<?php echo $category['software_id'];?>" >
										<img src="assets/system/img/icon/category_icon/<?php echo $category['software_variation_icon']; ?>" class="rounded">
										<br>
										<center>
											<p><b><?php echo $category['software_variation_name'];?></b></p>
										</center>
									</a>
								</div>
							<?php } ?>
						</div>
						
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
			
			<div class="col-sm-4 hide subscription_summary">
				<div class="ibox">
					<div class="ibox-title">
						<h5><?php echo trans('subscription_summary'); ?></h5>
					</div>
					<div class="ibox-content">
						<div class="row">
							<div class="col-md-12">
								<span class="font-bold h5">
									<?php echo trans('software_name').' : ';?><span class="software_name"></span> 
								</span>
							</div>
							
						</div>
						<div class="row">
							<div class="col-md-12">
								<span class="font-bold h5">
									<?php echo trans('basic_subscription_fee').' : ';?><span class="basic_subscription_fees">0.00</span> Tk/Month<br/>
									<?php echo trans('subscription_setup_fee').' : ';?><span class="subscription_setup_fee">0.00</span> Tk <span class="variation_fee_advance"></span>
								</span>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12" id="requiredPlugins"></div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12 text-center">
								<span class="font-bold h4">
									<?php echo trans('total').' : ';?><span class="software_total_price">0.00</span> Tk
									<input type="hidden" name="total_amount" id="total_amount" />
									<input type="hidden" name="required_plugins_list" id="required_plugins_list" />
									<input type="hidden" name="software_variation_id" id="software_variation_id" />
									<input type="hidden" name="software_variation_price" id="software_variation_price" />
									<input type="hidden" name="software_id" id="software_id" />
								</span>
							</div>
						</div>
						<hr>
						<span class="text-muted small" id="software_already_exits">
							<?php echo trans('for_bangladeshi_client');?>
						</span>
						<div class="m-t-sm">
							<div class="btn-group">
							<button type="button" class="btn btn-primary btn-sm subscribe_button"></i><?php echo trans('next'); ?></button>
						<button type="button" class="btn btn-success btn-sm back_button hide"></i><?php echo trans('back'); ?></button>
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
</div>
[footer]
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
					} else if(response.status=='exits'){
					swal({
						title: $_lang.success, 
						text: response.message, 
						type: "error",
						confirmButtonColor: "#1ab394", 
						confirmButtonText: $_lang.already_exits,
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
		var variationId = $(this).attr('software_variation_id');
		AS.Http.post({"action" : "getSoftwareVariationPrice","software_id" : softwareId, "variation_id": variationId}, "ajax/", function (response) {
			if(response.status=='success'){
				var totalVariationAmount = 0;
				$('#requiredPlugins').find('span').remove()
				$('#requiredPlugins').find('h3').remove()
				if(response.required_plugins){
					var variationPlugins = response.required_plugins;
					var selectedDiv = document.getElementById("requiredPlugins");
					var eH3 = document.createElement('h3');
					var eH3Content=$_lang.required_plugins;
					eH3.append(eH3Content);
					selectedDiv.append(eH3);
					var countSL = 1;
					$("#required_plugins_list").val(variationPlugins['plugin_id']);
					for (var i = 0, l = variationPlugins['plugin_name'].length; i < l; i++) {
						var ecSpan = document.createElement('span');
						var br = document.createElement("br");
						ecSpan.append(variationPlugins['plugin_price'][i]);
						var eSpan = document.createElement('span');
						eSpan.setAttribute('class', 'font-bold h5');
						eSpan.append(parseInt(countSL)+'. ');
						eSpan.append(variationPlugins['plugin_name'][i]+': ');
						if(variationPlugins['plugin_billing'][i] != 'Free'){
							eSpan.append(ecSpan);
						}
						totalVariationAmount +=  parseInt(variationPlugins['plugin_price'][i]);
						eSpan.append(variationPlugins['plugin_billing'][i]);
						eSpan.append(br);
						selectedDiv.appendChild(eSpan);
						countSL++;
					}
				}
				
				var totalPrice = parseInt(totalVariationAmount) + parseInt(response.variation_price) + parseInt(response.variation_fee);
				
				$(".category").removeClass("col-sm-offset-2");
				$(".subscription_summary").removeClass('hide');
				$(".basic_subscription_fees").html(response.variation_price);
				$(".subscription_setup_fee").html(response.variation_fee);
				$(".variation_fee_advance").html(response.variation_fee_advance);
				$(".software_total_price").html(totalPrice);
				$("#total_amount").val(totalPrice);
				$("#software_variation_id").val(variationId);
				$("#software_variation_price").val(response.variation_price);
				$("#software_id").val(softwareId);
				$(".software_name").html(name);
			}
			
		});
	});
	
	
	
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
		</script>
		[/footer]
		<?php include dirname(__FILE__) .'/include/footer.php'; ?>																																																																																																																																																																											