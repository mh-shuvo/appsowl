<?php 
	defined('_AZ') or die('Restricted access');
	include dirname(__FILE__) .'/include/header.php';
	include dirname(__FILE__) .'/include/side_bar.php';
	$checkRequirement = app('admin')->getwhere('as_pos_requirements','user_id',$this->currentUser->id);
	// if($checkRequirement){
		// redirect('history');
	// }
	
?>
<div class="wrapper wrapper-content animated fadeInRight pos-setting-form">
	<div class="row">			
		<div class="col-lg-8 col-lg-offset-2">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h4><?php echo trans('pos_setting_installation')?></h4>
				</div>
				<div class="ibox-content">
					<form class="form-horizontal">
						<div class="form-group">
							<label class="col-sm-3 control-label"><?php echo trans('company_name'); ?></label>
							<div class="col-sm-9">
								<input type="text" name="company_name" id="company_name" class="form-control" onkeyup='seturl()' placeholder="<?php echo trans('your_company_name'); ?>">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label"><?php echo trans('business_url');?></label>
							<div class="col-sm-9" >
								<div class="input-group m-b" >
									<input type="text" name="domain_name" id="domain_name" class="form-control" onkeyup='getcheckdomain()' onchange="getcheckdomain()" placeholder="Search Your Domain">
									<span class="input-group-addon" wfd-id="97">.<?php echo ROOT_DOMAIN; ?></span>
								</div>
							</div>
							<div class="domainstatus text-center"></div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label"><?php echo trans('company_email'); ?></label>
							<div class="col-sm-9">
								<input type="email" name="email" id="email" class="form-control" placeholder="<?php echo trans('email'); ?>">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label"><?php echo trans('company_phone'); ?></label>
							<div class="col-sm-9">
								<input type="text" name="phone" id="phone" class="form-control" placeholder="<?php echo trans('phone'); ?>">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label"><?php echo trans('company_address'); ?></label>
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
								<input type="text" name="postcode" id="postcode" class="form-control" placeholder="<?php echo trans('address'); ?>">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label"><?php echo trans('company_website'); ?></label>
							<div class="col-sm-9">
								<input type="text" name="website" id="website" class="form-control" placeholder="<?php echo trans('website'); ?>">
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
												echo "<option value='".$value['code']."'>".$value['code'].' | '.$value['currency_symbol']."</option>";
											}
										}
									?>
								</select>
							</div>
						</div>
						
						<div class="hr-line-dashed"></div>
						<div class="form-group">
							<label class="col-sm-3 control-label"><?php echo trans('vat_type'); ?></label>
							<div class="col-sm-9">
								<select name='vat_type' class="form-control" id="vat_type">
									<option><?php echo trans('select_vat_type'); ?></option>
									<option value='global'><?php echo trans('global');?></option>
									<option value='single'><?php echo trans('single');?></option>
								</select>
							</div>
						</div>
						<div class="form-group" id="var_area" style="display:none;">
							<label class="col-sm-3 control-label"><?php echo trans('vat').'(%)'; ?></label>
							<div class="col-sm-9">
								<input type="text" name="vat" id="vat" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-5 col-sm-offset-1">
								<a href="pos-setting">
								<label class="btn btn-success" ><?php echo trans('cancel'); ?></label></a>
								<button class="btn btn-primary" type="submit" id="installing-button"><?php echo trans('setup_complete'); ?></button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="poststatus"></div>
[footer]
<script>
	/* function PosSettingSubmit() {
		$(".loader-spin").removeClass("hidden");
		$(".form-body").addClass('hidden');
		jQuery.ajax({
		url: "ajax/",
		type:'POST',
		data:new FormData($('#pos_submit_data').get(0)),
		contentType:false,
		cache:false,
		processData:false,
		success:function(data){
		$(".form-body").removeClass("hidden");
		$(".loader-spin").addClass('hidden');
		swal("Installation Successfull", "", "success");
		$('.confirm').click(function () {
		location.replace('dashboard');
		});
		// $(".poststatus").html(data);
		
		
		},
		error:function (){
		swal("Installation Incomplete", "", "error");
		$(".form-body").removeClass("hidden");
		$(".loader-spin").addClass('hidden');
		}
		});
	} */
	
	$(".pos-setting-form form").validate({
		rules:{
			company_name:{
				required: true
			},
			domain_name:{
				required: true
			},
			email:{
				required: true
			},
			phone:{
				required: true
			},
			address:{
				required: true
			},
			currency:{
				required: true
			},
			vat_type:{
				required: true
			}
		},
		submitHandler: function(form) {
			AS.Http.PostSubmit(form, {"action" : "GetPosRequirementSetup"}, "ajax/", function (response) {
				if(response.status=='success'){
					swal({
						title: $_lang.success, 
						text: response.message, 
						type: "success",
						confirmButtonColor: "#1ab394", 
						confirmButtonText: $_lang.ok,
						},function(isConfirm){
						window.location = 'history';
					});
				}
			});
		}
	});
	
	document.getElementById('company_name').addEventListener('keypress', function(event) {
		if (event.keyCode == 13) {
			event.preventDefault();
		}
	});
</script>
[/footer]
<?php 
include dirname(__FILE__) .'/include/footer.php'; ?>											