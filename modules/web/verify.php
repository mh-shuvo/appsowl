<?php defined('_AZ') or die('Restricted access'); 
	include dirname(__FILE__) .'/includes/header.php'; 
	
?>
<section class="gray-section" style="min-height:850px;">
    <div class="container">
		<div class="row">
			<div class="col-sm-6 col-sm-offset-3" class="domain_form" align="center">
				<div class="domain_form" id="sms-verify">
					<form accept-charset="utf-8" class="col-md-offset-2">
						<div class="row">
							<div class="col-md-12">
								<h1 class="text-center">Mobile Verification</h1>
								<hr>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<input type="text" name="phone_number" id="phone_number" value="<?php echo $this->currentUser->phone; ?>" class="form-control" style="text-align:center;">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<input type="text" name="verification_number" id="verification_number" placeholder="Enter 6 digit code" class="form-control" style="text-align:center;"  maxlength="6">
								</div>
							</div>
							<div class="col-md-12">
								<label><a href="javascript:void(0);" onClick="GetResendSms()">Could not get code?</a></label>
								<div class="form-group">
									<input type="submit" class="btn btn-primary btn-lg btn-block" style="text-align:center;" value="Verify">
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>
<?php include dirname(__FILE__) .'/includes/footer.php'; ?>