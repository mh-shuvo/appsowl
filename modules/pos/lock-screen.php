<?php 
	if (ASSession::get("user_lock_status")=="unlocked"){
		redirect("/pos/home");
	}
?>
<!DOCTYPE html>
<html>
	
	<head>
		
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<base href="<?php echo WEBSITE_DOMAIN; ?>/" >
		<title>Point of Sale</title>
			
		<?php
			getCss('assets/system/css/bootstrap.min.css',false);
			getCss('assets/system/font-awesome/css/font-awesome.css');
			getCss('assets/system/css/animate.css');
			getCss('assets/system/css/style.css');
		?>
		
	</head>
	
	<body class="gray-bg">
		
		<div class="lock-word animated fadeInDown">
			<span class="first-word">LOCKED</span><span>SCREEN</span>
		</div>
		<div class="middle-box text-center lockscreen animated fadeInDown">
			<div id="lock_screen">
				<div class="m-b-md">
					<img alt="image" class="img-circle" src="favicon.ico">
				</div>
				<h3><?php echo $this->currentUser->first_name." ".$this->currentUser->last_name; ?></h3>
				<form>
					<div class="form-group">
						<input type="hidden" name="username" value="<?php echo $this->currentUser->username; ?>">
						<input type="password" name="password" class="form-control" placeholder="******">
					</div>
					<button type="submit" class="btn btn-primary block full-width">Unlock</button>
				</form>
			</div>
		</div>
		<?php
			getJs('assets/js/vendor/sha512.js');
			getJs('assets/system/js/jquery-3.1.1.min.js',false);
			getJs('assets/system/js/bootstrap.min.js',false);
			getJs('assets/js/vendor/popper.min.js',false);
			getJs('assets/js/vendor/jquery-validate/jquery.validate.min.js',false);
		?>
		<script src="assets/js/app/bootstrap.php"></script>
		<?php
			getJs('assets/js/app/common.js');
			getJs('assets/js/app/pos.js');
			if (ASLang::getLanguage() != DEFAULT_LANGUAGE){
				getJs('assets/js/vendor/jquery-validate/localization/messages_'.ASLang::getLanguage().'');
			}
		?>
		<script>
			
			$('#lock_screen form').validate({
				rules: {
					username: {
						required: true
					},
					password: {
						required: true,
						minlength: 6
					}
				},
				submitHandler: function(form) {
					AS.Http.submit(form, getLoginFormData(form), false, function (result) {
						jQuery.ajax({
							url: "pos/ajax/",
							data: {
								action	: "account_lock",
								lock_status	: "unlocked"
							},
							success:function(){
								window.location = result.page;
							},
						});
					});
				}
			});
			
			function getLoginFormData(form) {
				return {
					action: "checkLogin",
					unlocked: "unlocked",
					username: form['username'].value,
					password: AS.Util.hash(form['password'].value)
				};
			}
		</script>	
	</body>
</html>

