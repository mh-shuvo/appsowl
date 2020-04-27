<?php defined('_AZ') or die('Restricted access'); 
	include dirname(__FILE__) .'/include/header.php';
	include dirname(__FILE__) .'/include/side_bar.php';
	// include dirname(__FILE__) .'/include/navbar.php';
	$getuserdetails = app('admin')->getuserdetails($this->currentUser->id);
?>

<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-7">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5><?php echo trans('user_account_setting'); ?></h5>
					
				</div>
				<div class="ibox-content" style="">
					<div class="row">
						<div class="col-sm-12" id="admin_account_update">
							<form role="form">
								<div class="form-group">
									<div class="row">
										<div class="col-sm-6">
											<label> <?php echo trans('first_name'); ?></label>
											<input type="text" name="first_name" placeholder="<?php echo trans('enter_first_name'); ?>" class="form-control" value="<?php echo $getuserdetails['first_name']; ?>">
										</div>
										<div class="col-sm-6">
											<label> <?php echo trans('last_name'); ?></label>
											<input type="text" name="last_name" placeholder="<?php echo trans('enter_last_name'); ?>" class="form-control" value="<?php echo $getuserdetails['last_name']; ?>">
										</div>
									</div>
								</div>
								<div class="form-group">
									<label><?php echo trans('mobile'); ?></label>
									<input type="text" name="user_mobile" placeholder="<?php echo trans('enter_mobile_number'); ?>" class="form-control" value="<?php echo $getuserdetails['phone']; ?>">
								</div>
								<div class="form-group">
									<label><?php echo trans('email'); ?></label>
									<input type="email" placeholder="<?php echo trans('enter_email'); ?>" class="form-control" value="<?php echo $getuserdetails['email']; ?>" name="user_email">
								</div>
								
								<div class="form-group">
									<label><?php echo trans('address'); ?></label>
									<textarea rows="3" placeholder="<?php echo trans('enter_address'); ?>" class="form-control" name="user_address" ><?php echo $getuserdetails['address']; ?></textarea>
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
				<div class="ibox-content" style="">
					<div class="row">
						<div class="col-sm-12" id="admin-change-password-form">
							<form role="form">
								
								<div class="form-group">
									<label><?php echo trans('old_password'); ?></label>
									<input type="password" placeholder="<?php echo trans('old_password'); ?>" class="form-control" value="" name="old_password">
								</div>
								<div class="form-group">
									<label><?php echo trans('new_password'); ?></label>
									<input type="password" placeholder="<?php echo trans('new_password'); ?>" class="form-control" value="" name="new_password">
								</div>
								<div class="form-group">
									<label><?php echo trans('confirm_password'); ?></label>
									<input type="password" placeholder="<?php echo trans('re_enter_your_password'); ?>" class="form-control" value="" name="new_password_confirmation">
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
<?php include dirname(__FILE__) .'/include/footer.php'; ?>