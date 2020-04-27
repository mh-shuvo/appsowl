<?php defined('_AZ') or die('Restricted access'); 
	include dirname(__FILE__) .'/include/header.php';
	include dirname(__FILE__) .'/include/side_bar.php';
	include dirname(__FILE__) .'/include/navbar.php';
	
	if(isset($this->currentUser->id)){
		$getuserdetails = app('admin')->getuserdetails($this->currentUser->id);
	}
?>
[header]
<?php
	getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false);
	getCss('assets/system/css/plugins/textSpinners/spinners.css',false);
?>
[/header]
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-7">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5><?php echo trans('user_account_setting'); ?></h5>
				</div>
				<div class="ibox-content">
					<div class="row">
						<div class="col-sm-12 account_update">
							<form>
								<div class="form-group">
									<div class="row">
										<div class="col-sm-12">
											<label> <?php echo trans('company_name'); ?></label>
											<input type="text" READONLY placeholder="<?php echo trans('company_name'); ?>" class="form-control" value="<?php echo WEBSITE_NAME; ?>">
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="row">
										<div class="col-sm-6">
											<label> <?php echo trans('first_name'); ?></label>
											<input type="text" name="first_name" placeholder="<?php echo trans('enter_first_name'); ?>" class="form-control" value="<?php if(isset($this->currentUser->id)){echo $getuserdetails['first_name'];} ?>">
										</div>
										<div class="col-sm-6">
											<label> <?php echo trans('last_name'); ?></label>
											<input type="text" name="last_name" placeholder="<?php echo trans('enter_last_name'); ?>" class="form-control" value="<?php if(isset($this->currentUser->id)){echo $getuserdetails['last_name'];} ?>">
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="row">
										<div class="col-sm-3">
											<label><?php echo trans('country'); ?></label>
											<select class="form-control" name="country_code" id="country_code" onchange="getcountrycode();" data-show-subtext="true" data-live-search="true">
												<option value="">Country</option>
												<?php $getcountry = app('root')->select("SELECT * FROM `as_country`"); 
													foreach($getcountry as $getcountry){ ?>
													<option value="+<?php echo $getcountry['phonecode']; ?>" <?php if(isset($this->currentUser->id)&&$getcountry['phonecode']==str_replace('+',"", $getuserdetails['country_code'])){echo "selected";} ?> >
														<?php echo $getcountry['name']; ?>
													</option>
												<?php } ?>
											</select>
										</div>
										<div class="col-sm-9">
											<label><?php echo trans('mobile'); ?></label>
											<input type="text" name="user_mobile" id="user_mobile" placeholder="<?php echo trans('enter_mobile_number'); ?>" class="form-control" value="<?php if(isset($this->currentUser->id)){echo $getuserdetails['phone'];} ?>">
										</div>
									</div>
								</div>
								<div class="form-group">
									<label> <?php echo trans('email'); ?></label>
									<input type="email" placeholder="<?php echo trans('enter_email'); ?>" class="form-control" value="<?php if(isset($this->currentUser->id)){echo $getuserdetails['email'];} ?>" name="email">
								</div>
								<div class="form-group">
									<label> <?php echo trans('address'); ?></label>
									<textarea rows="3" placeholder="<?php echo trans('enter_address'); ?>" class="form-control" name="address" ><?php if(isset($this->currentUser->id)){echo $getuserdetails['address'];} ?></textarea>
								</div>
								<div>
									<button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit"><strong><?php echo trans('save_changes'); ?></strong></button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	<div class="col-lg-5">
	<div class="ibox float-e-margins">
	<div class="ibox-title">
	<h5><?php echo trans('password_setting'); ?></h5>
	</div>
	<div class="ibox-content">
	<div class="row" id="pwd-container">
	<div class="col-sm-12 change_password">
	<form>
	<!-- <div class="form-group">
	<label><?php echo trans('old_password'); ?></label>
	<input type="password" placeholder="<?php echo trans('old_password');?>" class="form-control" name="old_password">
	</div>
	<div class="form-group">
	<label><?php echo trans('new_password'); ?></label>
	<input type="password" placeholder="<?php echo trans('new_password');?>" class="form-control" name="new_password">
	</div>
	<div class="form-group">
	<label><?php echo trans('confirm_password'); ?></label>
	<input type="password" placeholder="<?php echo trans('re_enter_your_password');?>" class="form-control" name="confirm_password">
	</div> -->
	<div class="form-group">
	<label><?php echo trans('old_password'); ?></label>
	<div class="input-group" id="show_hide_password">
	<input type="password" placeholder="<?php echo trans('old_password');?>" class="form-control" name="old_password">
	<div class="input-group-addon">
	<a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
	</div>
	</div>
	</div>
	<div class="form-group">
	<label><?php echo trans('new_password'); ?></label>
	<div class="input-group" id="show_hide_password">
	<input type="password" placeholder="<?php echo trans('new_password');?>" class="form-control example4" name="new_password">
	<div class="input-group-addon">
	<a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
	</div>
	</div>
	</div>
	<div class="form-group">
	<span class="font-bold pwstrength_viewport_verdict"></span>
	<span class="pwstrength_viewport_progress"></span>
	</div>
	<div class="form-group">
	<label><?php echo trans('confirm_password'); ?></label>
	<div class="input-group" id="show_hide_password">
	<input type="password" placeholder="<?php echo trans('re_enter_your_password');?>" class="form-control" name="confirm_password">
	<div class="input-group-addon">
	<a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
	</div>
	</div>
	</div>
	
	<div>
	<button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit"><strong><?php echo trans('save_changes'); ?></strong></button>
	</div>
	</form>
	</div>
	</div>
	</div>
	</div>
	</div>
	
	</div>
	</div>
	[footer]
	<?php 
	getJs('assets/system/js/plugins/pace/pace.min.js');
	getJs('assets/system/js/plugins/pwstrength/pwstrength-bootstrap.min.js');
	getJs('assets/system/js/plugins/pwstrength/zxcvbn.js');
	?>
	<script>
	
	function getcountrycode(){
	var countrycode = $("#country_code").val();
	$("#user_mobile").val(countrycode);
	}
	
	$(document).ready(function(){
	
	var passwordmetter = {};
	passwordmetter.ui = {
	container: "#pwd-container",
	viewports: {
	progress: ".pwstrength_viewport_progress",
	verdict: ".pwstrength_viewport_verdict"
	}
	};
	passwordmetter.common = {
	zxcvbn: true,
	zxcvbnTerms: ['samurai', 'shogun', 'bushido', 'daisho', 'seppuku'],
	userInputs: ['#year', '#familyname']
	};
	$('.example4').pwstrength(passwordmetter);
	
	
	})
	
	$(document).on("click",".confirm", function(){
	window.location.reload();
	});
	
	$('.account_update form').validate({
	rules: {
	first_name: {
	required: true
	},
	last_name: {
	required: true
	},
	country_code: {
	required: true
	},
	user_mobile: {
	required: true
	},
	email: {
	required: true,
	email:true
	},
	address: {
	required: true
	},
	
	},  
	submitHandler: function(form) {
	AS.Http.PostSubmit(form, {"action" : "AccountUpdate"}, "pos/ajax/", function (response) {
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
	
	$('.change_password form').validate({
	rules: {
	old_password: {
	required: true
	},
	new_password: {
	minlength: 6
	},
	confirm_password: {
	minlength: 6
	}
	
	},  
	submitHandler: function(form) {
	AS.Http.PostSubmit(form, {"action" : "PasswordUpdate"}, "pos/ajax/", function (response) {
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
	[/footer]
	<?php include dirname(__FILE__) .'/include/footer.php'; ?>			