<?php defined('_AZ') or die('Restricted access'); 
	include dirname(__FILE__) .'/includes/header.php'; 

?>
<section class="gray-section" style="min-height:470px;">
	    <div class="passwordBox NumberVerifySection animated fadeInDown " >
			<div class="row">
				<div class="col-md-12">
					<div class="ibox-content">

						<h2 class="font-bold">Forgot password</h2>

						<p>
							Enter your mobile number that you used for registration.
						</p>

						<div class="row">

							<div class="col-lg-12 NumberVerify">
								<form class="m-t" role="form" action="">
									<div class="form-group">
										
										<select required class="form-control" name="country_code" id="country_code" >
                                                    <option value="" selected>Select Country</option>
                                                    <?php $getcountry = app('admin')->getall('as_country'); 
													foreach($getcountry as $getcountry){ ?>
                                                        <option value="+<?php echo $getcountry['phonecode']; ?>">
                                                            <?php echo $getcountry['name']; ?>
                                                        </option>
                                                        <?php } ?>
                                                </select>
											<br>
											<div class="input-group">
												<span class="input-group-addon cc">+00</span>
												<input type="text" class="form-control" name="phone_number" placeholder="Phone Number" required="">
											</div>
									</div>

									<button type="submit" class="btn btn-primary block full-width m-b verify_code">Send verification Code</button>

								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			
		</div>
		<div class="passwordBox KeyVerifySection hide animated fadeInDown" >
			<div class="row">

				<div class="col-md-12">
					<div class="ibox-content">

						<h2 class="font-bold">Mobile Verification</h2>

						<div class="row">

							<div class="col-lg-12 KeyVerify">
								<form class="m-t" role="form" action="">	
									<input type="hidden" id="user_id" name="user_id" value="">
									<input type="hidden" id="country_code" name="country_code" value="">
										<div class="form-group">
											<input type="text" name="verify_phone_number" id="verify_phone_number" value="" class="form-control" style="text-align:center;">
									</div>
										<div class="form-group">
											<input type="text" name="verification_number" id="verification_number" placeholder="Enter 6 digit code" class="form-control" style="text-align:center;"  maxlength="6">
										</div>
									<small><a href="javascript:void(0)" class="resend_sms">Resend SMS</a></small>
									
									<button type="submit" class="btn btn-primary block full-width">Verify</button>

								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			
		</div>	
		<div class="passwordBox PasswordChangeSection hide animated fadeInDown" >
			<div class="row">

				<div class="col-md-12">
					<div class="ibox-content">

						<h2 class="font-bold">Change Password</h2>

						<div class="row">

							<div class="col-lg-12 change_password">
								<form class="m-t" role="form" action="">								
									<div class="col-md-10">
										<div class="form-group">
										<input type="hidden" id="u_id" name="user_id" value="">
											<input type="password" name="password" class="form-control" placeholder="New password" required="">
										</div>
									</div>
									<div class="col-md-10">
										<div class="form-group">
											<input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password" required="">
										</div>
									</div>
									
									<button type="submit" class="btn btn-primary block full-width m-b">Submit</button>

								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			
		</div>
		
		
</section>
[footer]
	<script>
	$('#country_code').change(function(){
		$('.cc').html($(this).val());
	});
	$(".NumberVerify form").validate({
		  rules: {
			phone_number: {
				required: true
			},
		},	
		submitHandler: function(form) {
			  AS.Http.PostSubmit(form, {action: "SendVerifyNumber"}, false, function (response) {
				  if(response.status=='success'){
					$('#verify_phone_number').val(response.phone);
					$('#user_id').val(response.user_id);
					$('#country_code').val(response.country_code);
					$('.NumberVerifySection').addClass('hide');
					$('.KeyVerifySection').removeClass('hide');
				  }
				  else{
					  swal($_lang.error, response.message, "error");
				  }
				});
		}
	});
	$('.resend_sms').click(function(){
		var phone = $('#verify_phone_number').val();
		phone = phone.replace('+88','');
		jQuery.ajax({
			url: "ajax/",
			data: {
				action	 : "SendVerifyNumber",
				phone_number  : phone,
				country_code  : $('#country_code').val()
			},
			type: "POST",
			success:function(response){
			},
			error:function (){}
		});
	});
	$('.KeyVerify form').validate({
		rules:{
			verification_number: {
				required: true,
				minlength: 6,
			}
		},
		submitHandler: function(form)
		{
			  AS.Http.PostSubmit(form, {action: "GetSmskeyVerify"}, false, function (response) {
				  if(response.status=='success'){
					  $('#u_id').val(response.user_id);
					  $('.KeyVerifySection').addClass('hide');
					  $('.PasswordChangeSection').removeClass('hide');
				  }
				  else{
					   swal($_lang.error, response.message, "error");
				  }
				});
		}
		
	});
	
	$('.change_password form').validate({
		rules:{
			password:{
				required: true,
				minlength: 6
			},
			password_confirmation:{
				required: true,
				minlength: 6
			}
		},
		submitHandler: function(form)
		{
			  AS.Http.PostSubmit(form, {action: "ForgotPasswordChange"}, false, function (response) {
				  if(response.status=='success'){
					  swal($_lang.success, response.success_message, "success");
					 location.href='/';
				  }
				  else{
					   swal("Error", response.message, "error");
				  }
				});
		}
	})
	</script>
[/footer]
<?php include dirname(__FILE__) .'/includes/footer.php'; ?>